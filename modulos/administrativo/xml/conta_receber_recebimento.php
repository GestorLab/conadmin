<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ContaReceberRecebimento(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdContaReceber 			= $_GET['IdContaReceber'];
		$IdContaReceberRecebimento 	= $_GET['IdContaReceberRecebimento'];
		$IdArquivoRetorno			= $_GET['IdArquivoRetorno'];
		$IdLocalCobranca			= $_GET['IdLocalCobranca'];
		$IdRecibo					= $_GET['IdRecibo'];
		$where						= "";
		
		if($IdContaReceber !=''){
			$where .= " and ContaReceberRecebimento.IdContaReceber=$IdContaReceber";	
			$where .= " and ContaReceberRecebimento.IdLoja=$IdLoja";	
		}

		if($IdContaReceberRecebimento !=''){	
			$where .= " and ContaReceberRecebimento.IdContaReceberRecebimento=$IdContaReceberRecebimento";	
		}
		
		if($IdArquivoRetorno !=''){				
			$where .= " and ContaReceberRecebimento.IdArquivoRetorno=$IdArquivoRetorno";	
			$where .= " and ContaReceberRecebimento.IdLojaRecebimento=$IdLoja";	
		}
		
		if($IdLocalCobranca !=''){				
			$where .= " and ContaReceberRecebimento.IdLocalCobranca=$IdLocalCobranca";	
		}
		
		if($IdRecibo !=''){				
			$where .= " and ContaReceberRecebimento.IdRecibo=$IdRecibo";	
		}
		
		$sql	=	"select
						ContaReceberRecebimento.IdLoja,
						IdContaReceberRecebimento, 
						DataRecebimento, 
						DataLancamento,
						ContaReceberRecebimento.IdLocalCobranca IdLocalRecebimento, 
						ValorRecebido, 
						ContaReceberRecebimento.IdLoja, 
						ContaReceberRecebimento.ValorDesconto ValorDescontoRecebimento, 
						ContaReceberRecebimento.ValorOutrasDespesas, 
						ContaReceberRecebimento.ValorMoraMulta, 
						ContaReceberRecebimento.IdContaReceber, 
						ContaReceberRecebimento.MD5, 
						ContaReceberRecebimento.IdRecibo, 
						ContaReceberRecebimento.IdCaixa,
						ContaReceberRecebimento.IdCaixaMovimentacao,
						ContaReceberRecebimento.IdCaixaItem, 
						IdArquivoRetorno, 
						IdContratoEstorno,
						ContaReceberDados.IdStatus IdStatusContaReceber,
						ContaReceberDados.ValorLancamento, 
						ContaReceberDados.ValorDesconto, 
						ContaReceberDados.ValorDespesas, 
						ContaReceberDados.DataVencimento, 
						(ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas) ValorContaReceber, 
						ContaReceberRecebimento.IdStatus, 
						ContaReceberRecebimento.DataCriacao, 
						ContaReceberRecebimento.LoginCriacao, 
						ContaReceberRecebimento.DataAlteracao, 
						ContaReceberRecebimento.LoginAlteracao, 
						ContaReceberRecebimento.Obs
					from
						ContaReceberRecebimento,
						ContaReceberDados 
					where 
						ContaReceberRecebimento.IdLoja = ContaReceberDados.IdLoja and 
						ContaReceberRecebimento.IdContaReceber = ContaReceberDados.IdContaReceber $where
					order by
						ContaReceberRecebimento.IdLoja,
						IdRecibo";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[IdLocalRecebimento] != ""){
				$sql3	=	"select 
								LocalCobranca.DescricaoLocalCobranca DescricaoLocalRecebimento
							from
								LocalCobranca
							where 
								LocalCobranca.IdLoja = '$lin[IdLoja]' and 
								LocalCobranca.IdLocalCobranca = '$lin[IdLocalRecebimento]'";
				$res3	=	@mysql_query($sql3,$con);
				$lin3	=	@mysql_fetch_array($res3);
			} else {
				$lin3 =  array(
					"DescricaoLocalRecebimento" => "Caixa $lin[IdCaixa]"
				);
			}
			
			$sql2	=	"select 
							TipoPessoa,
							Nome,
							RazaoSocial 
						from
							Pessoa,
							LancamentoFinanceiroDados 
						where 
							LancamentoFinanceiroDados.IdLoja = $lin[IdLoja] and 
							LancamentoFinanceiroDados.IdContaReceber=$lin[IdContaReceber] and 
							LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			if($lin2[TipoPessoa]=='1'){
				$lin2[Nome]	=	$lin2[getCodigoInterno(3,24)];	
			}
			
			$RecebimentoDuplicado = "";
			$sql1	=	"select					
							ContaReceberRecebimentoAtivo.ValorRecebido						
						from
							ContaReceberRecebimentoAtivo
						where
							ContaReceberRecebimentoAtivo.IdLoja = $lin[IdLoja] and 
							ContaReceberRecebimentoAtivo.IdContaReceber = $lin[IdContaReceber];";
			$res1	=	mysql_query($sql1,$con);
			if($lin1 = mysql_fetch_array($res1)){			
				if($lin1[ValorRecebido] == '***'){
					$RecebimentoDuplicado = 1;			
				}
			}
			
			$linkImpresso = "IdCaixa=".$lin[IdCaixa]."&IdCaixaMovimentacao=".$lin[IdCaixaMovimentacao];
			$lin[ValorParcela]	=	$lin[ValorLancamento] + $lin[ValorDespesas] - $lin[ValorDesconto];
			$lin[ValorReceber]	=	$lin[ValorParcela] + $lin[ValorOutrasDespesas] + $lin[ValorMoraMulta] - $lin[ValorDescontoRecebimento];
			
			$dados	.=	"\n<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
			$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
			$dados	.=	"\n<IdContaReceberRecebimento><![CDATA[$lin[IdContaReceberRecebimento]]]></IdContaReceberRecebimento>";
			$dados	.=	"\n<DescricaoLocalRecebimento><![CDATA[$lin3[DescricaoLocalRecebimento]]]></DescricaoLocalRecebimento>";
			$dados	.=	"\n<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
			$dados	.=	"\n<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
			$dados	.=	"\n<ValorRecebido><![CDATA[$lin[ValorRecebido]]]></ValorRecebido>";
			$dados	.=	"\n<ValorDescontoRecebimento><![CDATA[$lin[ValorDescontoRecebimento]]]></ValorDescontoRecebimento>";
			$dados	.=	"\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";
			$dados	.=	"\n<ValorOutrasDespesas><![CDATA[$lin[ValorOutrasDespesas]]]></ValorOutrasDespesas>";
			$dados	.=	"\n<ValorMoraMulta><![CDATA[$lin[ValorMoraMulta]]]></ValorMoraMulta>";
			$dados	.=	"\n<IdArquivoRetorno><![CDATA[$lin[IdArquivoRetorno]]]></IdArquivoRetorno>";
			$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
			$dados	.=	"\n<MD5><![CDATA[$lin[MD5]]]></MD5>";
			$dados	.=	"\n<IdRecibo><![CDATA[$lin[IdRecibo]]]></IdRecibo>";
			$dados	.=	"\n<IdLocalRecebimento><![CDATA[$lin[IdLocalRecebimento]]]></IdLocalRecebimento>";
			$dados	.=	"\n<IdCaixa><![CDATA[$lin[IdCaixa]]]></IdCaixa>";
			$dados	.=	"\n<IdCaixaMovimentacao><![CDATA[$lin[IdCaixaMovimentacao]]]></IdCaixaMovimentacao>";
			$dados	.=	"\n<IdCaixaItem><![CDATA[$lin[IdCaixaItem]]]></IdCaixaItem>";
			$dados	.=	"\n<ValorContaReceber><![CDATA[$lin[ValorContaReceber]]]></ValorContaReceber>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<IdStatusContaReceber><![CDATA[$lin[IdStatusContaReceber]]]></IdStatusContaReceber>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<Nome><![CDATA[$lin2[Nome]]]></Nome>";
			$dados	.=	"\n<ValorParcela><![CDATA[$lin[ValorParcela]]]></ValorParcela>";
			$dados	.=	"\n<RecebimentoDuplicado><![CDATA[$RecebimentoDuplicado]]></RecebimentoDuplicado>";			
			$dados	.=	"\n<IdContratoEstorno><![CDATA[$lin[IdContratoEstorno]]]></IdContratoEstorno>";
			$dados	.=	"\n<linkImpresso><![CDATA[$linkImpresso]]></linkImpresso>";
			
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	echo get_ContaReceberRecebimento();
?>
