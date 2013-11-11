<?
	$localModulo	=	0;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Conta_Eventual(){
		
		global $con;
		global $_GET;
		
		$IdLoja						= $_SESSION["IdLoja"];	
		$IdPessoaLogin				= $_SESSION['IdPessoa'];
		$Limit 						= $_GET['Limit'];
		$IdPessoa					= $_GET['IdPessoa'];
		$IdContaEventual			= $_GET['IdContaEventual'];
		$DescricaoContaEventual		= $_GET['DescricaoContaEventual'];
		$FormaCobranca				= $_GET['FormaCobranca'];
		$IdStatus					= $_GET['IdStatus'];
		$IdContrato					= $_GET['IdContrato'];
		$IdContaReceber				= $_GET['IdContaReceber'];
		$where						= "";
		$sqlAux						= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdPessoa!= ''){
			$where	.=	" and ContaEventual.IdPessoa = ".$IdPessoa;
		}
		if($IdContrato!= ''){
			$where	.=	" and ContaEventual.IdContrato = ".$IdContrato;
		}
		if($IdContaEventual!= ''){
			$where	.=	" and ContaEventual.IdContaEventual = ".$IdContaEventual;
		}
		if($DescricaoContaEventual !=''){	 				 
			$where  .= " and DescricaoContaEventual like '$DescricaoContaEventual%'";	 
		}
		if($FormaCobranca !=''){	 				 
			$where  .= " and ContaEventual.FormaCobranca = ".$FormaCobranca;	 
		}
		if($IdStatus !=''){	 				 
			$where  .= " and ContaEventual.IdStatus = ".$IdStatus;	 
		}
		if($IdContaReceber !=''){	
			$IdContaReceberAux = "";
			$i = 0;
			
			$sql = "select 
						LancamentoFinanceiro.IdContaEventual
					from
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber
					where
						LancamentoFinanceiro.IdLoja = $IdLoja and
						LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
						LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber";
			$resLancamentoFinanceiro	=	@mysql_query($sql,$con);
			while($linLancamentoFinanceiro	=	@mysql_fetch_array($resLancamentoFinanceiro)){				
				if($i == 0){
					$IdContaReceberAux = $linLancamentoFinanceiro[IdContaReceber];
					$i = 1;
				}else{
					$IdContaReceberAux .= ",".$linLancamentoFinanceiro[IdContaReceber];
				}
			}
			$where .= " and ContaEventual.IdContaEventual in ($IdContaReceberAux)";
		}
		
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}	
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado,
								Carteira
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdLoja = Carteira.IdLoja and
								AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
								Carteira.IdCarteira = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and 
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}	
		
		$cont	=	0;	
		$sql	=	"select
				      ContaEventual.IdContaEventual,
				      DescricaoContaEventual,
				      ContaEventual.IdPessoa,
				      ContaEventual.IdLocalCobranca,
				      LocalCobranca.ValorCobrancaMinima,
				      ValorTotal,
				      ContaEventual.ValorDespesaLocalCobranca,
				      ContaEventual.IdContrato,
				      ContaEventual.IdStatus,
					  ContaEventual.IdCartao,
					  ContaEventual.IdContaDebito,
				      Carne,
				      QtdParcela,
				      ContaEventualParcela.Vencimento,
					  ContaEventualParcela.MesReferencia,
				      ObsContaEventual,
				      FormaCobranca,
				      OcultarReferencia,
				      ContaEventual.IdPessoaEnderecoCobranca,
				      ContaEventual.DataAlteracao,
				      ContaEventual.LoginAlteracao,
				      ContaEventual.DataCriacao,
				      ContaEventual.LoginCriacao,
					  ContaEventual.DataConfirmacao,
				      ContaEventual.LoginConfirmacao,
				      LocalCobranca.IdTipoLocalCobranca
				from
				      ContaEventual LEFT JOIN (select ContaEventualParcela.IdContaEventual, ContaEventualParcela.MesReferencia, ContaEventualParcela.Vencimento from ContaEventualParcela where ContaEventualParcela.IdLoja = $IdLoja group by ContaEventualParcela.IdContaEventual order by ContaEventualParcela.Vencimento Asc ) ContaEventualParcela ON (ContaEventual.IdContaEventual = ContaEventualParcela.IdContaEventual) LEFT JOIN LocalCobranca ON (ContaEventual.IdLocalCobranca = LocalCobranca.IdLocalCobranca),
					  Pessoa left join (
							PessoaGrupoPessoa, 
							GrupoPessoa
						) on (
							Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
							PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
							PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
							PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						)
				where	
					  ContaEventual.IdLoja = $IdLoja and 
					  ContaEventual.IdPessoa = Pessoa.IdPessoa $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[FormaCobranca] == 1){
				$lin[Vencimento]	=	$lin[MesReferencia];
			}
			
			$sql = "select
						count(*) QtdContaReceberAguardandoPagamento
					from
						LancamentoFinanceiroDados
					where
						IdLoja = $IdLoja and
						IdContaEventual = $lin[IdContaEventual] and
						IdStatusContaReceber = 1";
			$res2 =	@mysql_query($sql,$con);
			$lin2 =	@mysql_fetch_array($res2);
			
			$lin[DescricaoFormaCobranca] = getParametroSistema(50,$lin[FormaCobranca]);
			$lin[Status] = getParametroSistema(46,$lin[IdStatus]);
				
			$dados	.=	"\n<IdContaEventual>$lin[IdContaEventual]</IdContaEventual>";
			$dados	.=	"\n<DescricaoContaEventual><![CDATA[$lin[DescricaoContaEventual]]]></DescricaoContaEventual>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<IdCartao><![CDATA[$lin[IdCartao]]]></IdCartao>";
			$dados	.=	"\n<IdContaDebito><![CDATA[$lin[IdContaDebito]]]></IdContaDebito>";
			$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
			$dados	.=	"\n<FormaCobranca><![CDATA[$lin[FormaCobranca]]]></FormaCobranca>";
			$dados	.=	"\n<DescricaoFormaCobranca><![CDATA[$lin[DescricaoFormaCobranca]]]></DescricaoFormaCobranca>";
			$dados	.=	"\n<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";
			$dados	.=	"\n<ValorDespesaLocalCobranca><![CDATA[$lin[ValorDespesaLocalCobranca]]]></ValorDespesaLocalCobranca>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<QtdParcela><![CDATA[$lin[QtdParcela]]]></QtdParcela>";
			$dados	.=	"\n<Carne><![CDATA[$lin[Carne]]]></Carne>";
			$dados	.=	"\n<OcultarReferencia><![CDATA[$lin[OcultarReferencia]]]></OcultarReferencia>";
			$dados	.=	"\n<ValorCobrancaMinima><![CDATA[$lin[ValorCobrancaMinima]]]></ValorCobrancaMinima>";
			$dados	.=	"\n<ObsContaEventual><![CDATA[$lin[ObsContaEventual]]]></ObsContaEventual>";
			$dados	.=	"\n<DataPrimeiroVencimento><![CDATA[$lin[Vencimento]]]></DataPrimeiroVencimento>";
			$dados	.=	"\n<IdPessoaEnderecoCobranca><![CDATA[$lin[IdPessoaEnderecoCobranca]]]></IdPessoaEnderecoCobranca>";
			$dados	.=	"\n<QtdContaReceberAguardandoPagamento>$lin2[QtdContaReceberAguardandoPagamento]</QtdContaReceberAguardandoPagamento>";
			$dados	.=	"\n<IdTipoLocalCobranca><![CDATA[$lin[IdTipoLocalCobranca]]]></IdTipoLocalCobranca>";
			$dados	.=	"\n<IdContrato><![CDATA[$lin[IdContrato]]]></IdContrato>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataConfirmacao><![CDATA[$lin[DataConfirmacao]]]></DataConfirmacao>";
		    $dados	.=	"\n<LoginConfirmacao><![CDATA[$lin[LoginConfirmacao]]]></LoginConfirmacao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Conta_Eventual();
?>
