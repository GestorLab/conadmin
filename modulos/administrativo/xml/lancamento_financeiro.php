<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_lancamento_financeiro(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdPessoaLogin				= $_SESSION['IdPessoa'];
		$IdContaReceber 			= $_GET['IdContaReceber'];
		$IdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
		$IdLancamentoFinanceiro		= $_GET['IdLancamentoFinanceiro'];
		$where						= "";
		$sqlAux						= "";
		
		if($IdLancamentoFinanceiro != ''){	$where .= " and LancamentoFinanceiroDados.IdLancamentoFinanceiro=$IdLancamentoFinanceiro";	}
		if($IdContaReceber != ''){			$where .= " and LancamentoFinanceiroDados.IdContaReceber=$IdContaReceber";	}
		if($IdProcessoFinanceiro != ""){	$where .= " and LancamentoFinanceiroDados.IdProcessoFinanceiro=$IdProcessoFinanceiro";	}
		
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
						LancamentoFinanceiroDados.IdLoja,
						LancamentoFinanceiroDados.IdLancamentoFinanceiro,
						LancamentoFinanceiroDados.IdContaReceber,	
						LancamentoFinanceiroDados.IdPessoa,
						LancamentoFinanceiroDados.Tipo,
						LancamentoFinanceiroDados.IdContrato,
						LancamentoFinanceiroDados.IdContaEventual,
						LancamentoFinanceiroDados.IdOrdemServico,
						LancamentoFinanceiroDados.IdProcessoFinanceiro,
						LancamentoFinanceiroDados.QtdParcela,
						LancamentoFinanceiroDados.NumParcelaEventual,
						LancamentoFinanceiroDados.DataReferenciaInicial,
						LancamentoFinanceiroDados.DataReferenciaFinal,
						LancamentoFinanceiroDados.Valor,
						LancamentoFinanceiroDados.ValorRepasseTerceiro,
						LancamentoFinanceiroDados.IdStatus,
						LancamentoFinanceiroDados.IdOrdemServico,
						LancamentoFinanceiroDados.IdStatusContaReceber,
						LancamentoFinanceiroDados.DataCancelamento,
						LancamentoFinanceiroDados.LoginCancelamento,
						LancamentoFinanceiroDados.ObsLancamentoFinanceiro,
						LancamentoFinanceiroDados.ValorDescontoAConceber	
					from
						LancamentoFinanceiroDados,
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
						LancamentoFinanceiroDados.IdLoja = $IdLoja and
						LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa $where";
					     
		$res	=	mysql_query($sql,$con);
		
		$total = 0;
		while($lin	=	@mysql_fetch_array($res)){
			$query = 'true';
		
			if($lin[IdContrato]!=""){
				if($_SESSION["RestringirCarteira"] == true){
					$sqlTemp =	"select 
									AgenteAutorizadoPessoa.IdContrato 
								from 
									AgenteAutorizadoPessoa,
									Carteira 
								where 
									AgenteAutorizadoPessoa.IdLoja = $IdLoja and 
									AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
									AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
									Carteira.IdCarteira = $IdPessoaLogin and 
									Carteira.Restringir = 1 and 
									Carteira.IdStatus = 1 and
									AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}else{
					if($_SESSION["RestringirAgenteAutorizado"] == true){
						$sqlTemp =	"select 
										AgenteAutorizadoPessoa.IdContrato
									from 
										AgenteAutorizadoPessoa,
										AgenteAutorizado
									where 
										AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
										AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
										AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
										AgenteAutorizado.IdAgenteAutorizado = $IdPessoaLogin and 
										AgenteAutorizado.Restringir = 1 and 
										AgenteAutorizado.IdStatus = 1 and
										AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
					if($_SESSION["RestringirAgenteCarteira"] == true){
						$sqlTemp		.=	"select 
												AgenteAutorizadoPessoa.IdContrato
											from 
												AgenteAutorizadoPessoa,
												AgenteAutorizado,
												Carteira
											where 
												AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
												AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
												AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
												AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
												AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
												Carteira.IdCarteira = $IdPessoaLogin and 
												AgenteAutorizado.Restringir = 1 and 
												AgenteAutorizado.IdStatus = 1 and
												AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
					
				}
			}
			
			if($query == 'true'){
			
				if($cont == 0){
					header ("content-type: text/xml");
					$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
					$dados	.=	"\n<reg>";
				}
			
			
				$total	+=	$lin[Valor];
				
				$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
				$dados	.=	"\n<IdContrato><![CDATA[$lin[IdContrato]]]></IdContrato>";
				$dados	.=	"\n<IdLancamentoFinanceiro>$lin[IdLancamentoFinanceiro]</IdLancamentoFinanceiro>";
				$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
				$dados	.=	"\n<IdContaEventual><![CDATA[$lin[IdContaEventual]]]></IdContaEventual>";
				$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
				$dados	.=	"\n<IdProcessoFinanceiro><![CDATA[$lin[IdProcessoFinanceiro]]]></IdProcessoFinanceiro>";
				$dados	.=	"\n<DataReferenciaInicial><![CDATA[$lin[DataReferenciaInicial]]]></DataReferenciaInicial>";
				$dados	.=	"\n<DataReferenciaFinal><![CDATA[$lin[DataReferenciaFinal]]]></DataReferenciaFinal>";
				$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
				$dados	.=	"\n<TotalValor><![CDATA[$total]]></TotalValor>";
				$dados	.=	"\n<ValorRepasseTerceiro><![CDATA[$lin[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
				$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados	.=	"\n<Tipo><![CDATA[$lin[Tipo]]]></Tipo>";;
				$dados	.=	"\n<IdOrdemServico><![CDATA[$lin[IdOrdemServico]]]></IdOrdemServico>";
				$dados	.=	"\n<QtdParcela><![CDATA[$lin[QtdParcela]]]></QtdParcela>";
				$dados	.=	"\n<ValorDescontoAConceber><![CDATA[$lin[ValorDescontoAConceber]]]></ValorDescontoAConceber>";
				$dados	.=	"\n<NumParcelaEventual><![CDATA[$lin[NumParcelaEventual]]]></NumParcelaEventual>";
				$dados	.=	"\n<IdStatusContaReceber><![CDATA[$lin[IdStatusContaReceber]]]></IdStatusContaReceber>";
				$dados	.=	"\n<DataCancelamento><![CDATA[$lin[DataCancelamento]]]></DataCancelamento>";
				$dados	.=	"\n<LoginCancelamento><![CDATA[$lin[LoginCancelamento]]]></LoginCancelamento>";
				$dados	.=	"\n<ObsLancamentoFinanceiro><![CDATA[$lin[ObsLancamentoFinanceiro]]]></ObsLancamentoFinanceiro>";
			
				$cont++;
				
				if($Limit!= ""){
					if($cont >= $Limit){
						break;
					}
				}
			}
		}
		if($cont >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_lancamento_financeiro();
?>
