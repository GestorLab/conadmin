<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_GerarNotaFiscalEmMassaContaReceber(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdContrato		 			= $_GET['IdContrato'];
		$MesVencimento			 	= $_GET['MesVencimento'];
		$IdGrupoPessoa				= $_GET['IdGrupoPessoa'];
		$IdTipoPessoa				= $_GET['IdTipoPessoa'];
		$IdFormaAvisoCobranca		= $_GET['IdFormaAvisoCobranca'];
		$IdStatusContrato			= $_GET['IdStatusContrato'];
		$IdStatusContaReceber		= $_GET['IdStatusContaReceber'];
		$IdServico					= $_GET['IdServico'];
		$IdEstado					= $_GET['IdEstado'];
		$IdCidade					= $_GET['IdCidade'];
		$where 						= "";
		
		if($MesVencimento != ""){
			$MesVencimento			= dataConv($MesVencimento,'m-Y','Y-m');
			$where 				   	.= " and substring(ContaReceber.DataVencimento,1,7) = '$MesVencimento'";		
		}
		
		if($IdContrato != ""){						
			$where 	   			   	.= " and LancamentoFinanceiro.IdContrato in ($IdContrato)";
		}
		
		if($IdGrupoPessoa != ""){
			$where 	   			   	.= " and GrupoPessoa.IdGrupoPessoa = $IdGrupoPessoa";			
		}	
		
		if($IdTipoPessoa != ""){
			$where					.= " and Pessoa.TipoPessoa = '$IdTipoPessoa'";
		}
		
		if($IdFormaAvisoCobranca != ""){
			switch($IdFormaAvisoCobranca){
				case 1:
					$where	.= " and Pessoa.Cob_FormaCorreio = 'S'";
					break;
				case 2:
					$where  .= " and Pessoa.Cob_FormaEmail = 'S'";	
					break;
				case 3:
					$where  .= " and Pessoa.Cob_FormaOutro = 'S'";
					break;
			}
		}
		
		if($IdStatusContrato != ""){
			$where .= " and Contrato.IdStatus = '$IdStatusContrato'";
		}
		
		if($IdStatusContaReceber != ""){
			$where .= " and ContaReceber.IdStatus = '$IdStatusContaReceber'";
		}
		
		if($IdServico != ""){
			$where .= " and Contrato.IdServico = $IdServico";
		}
		
		if($IdEstado != ""){
			$where .= " and PessoaEndereco.IdEstado = $IdEstado";
		}
		
		if($IdCidade != ""){
			$where .= " and PessoaEndereco.IdCidade = $IdCidade";
		}
		
		$sql	=	"select
						distinct
						ContaReceber.IdContaReceber,
						ContaReceber.DataLancamento,
						ContaReceber.DataVencimento,	
						((ContaReceberVencimento.ValorContaReceber + ContaReceberVencimento.ValorMulta + ContaReceberVencimento.ValorJuros + ContaReceberVencimento.ValorTaxaReImpressaoBoleto + ContaReceberVencimento.ValorOutrasDespesas) - ContaReceberVencimento.ValorDesconto) ValorTotal,
						ContaReceber.IdPessoa,
						ContaReceber.IdStatus
					from
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber,
						ContaReceber,
						ContaReceberVencimento,
						Contrato,
						Servico,
						Pessoa left join (
								PessoaGrupoPessoa, 
								GrupoPessoa
							) on (
								Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
								PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
								PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
								PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
							),PessoaEndereco							
					where
						LancamentoFinanceiro.IdLoja = $IdLoja and
						LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
						LancamentoFinanceiro.IdLoja = Contrato.IdLoja and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico and
						Servico.IdNotaFiscalTipo >= 1 and
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
						LancamentoFinanceiro.IdLoja = ContaReceber.IdLoja and
						LancamentoFinanceiro.IdLoja = ContaReceberVencimento.IdLoja and
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and	
						LancamentoFinanceiro.IdContrato != '' and
						LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and						
						ContaReceber.IdContaReceber = ContaReceberVencimento.IdContaReceber and
						ContaReceber.DataVencimento = ContaReceberVencimento.DataVencimento and						
						ContaReceber.IdStatus != 0 and
						ContaReceber.IdPessoa = Pessoa.IdPessoa and
						ContaReceber.IdContaReceber not in (select IdContaReceber from NotaFiscal where IdLoja = $IdLoja and IdStatus = 1) AND
						PessoaEndereco.IdPessoa = Pessoa.IdPessoa
						$where;"; 
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){			
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{			
			return "false";		
		}
		while($lin	=	@mysql_fetch_array($res)){
			
			$Status = getParametroSistema(35,$lin[IdStatus]);
			
			$sql2	=  "select
					   	  	Nome,
					   	  	RazaoSocial,
					   	  	TipoPessoa
						from 
					     	Pessoa
						where
							Pessoa.IdPessoa = $lin[IdPessoa]
						";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
							
			if($lin2[TipoPessoa]=='1'){
				$lin2[Nome]	=	$lin2[RazaoSocial];	
			}		
			
			$dados	.=	"\n<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";		
			$dados	.=	"\n<Nome><![CDATA[$lin2[Nome]]]></Nome>";	
			$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
			$dados	.=	"\n<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
			$dados	.=	"\n<Valor><![CDATA[$lin[ValorTotal]]]></Valor>";			
			$dados	.=	"\n<Status><![CDATA[$Status]]></Status>";	
		}		

		if(mysql_num_rows($res) >=1){		
			$dados	.=	"\n</reg>";
			return $dados;
		}
		
	}	
	echo get_GerarNotaFiscalEmMassaContaReceber();
?>
