<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_demonstrativo(){
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdPessoaLogin				= $_SESSION['IdPessoa'];
		$IdContaReceber 			= $_GET['IdContaReceber'];
		$IdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
		$IdCarne					= $_GET['IdCarne'];
		$where						= "";
		
		if($IdContaReceber != ''){			$where .= " and LancamentoFinanceiroDados.IdContaReceber in ($IdContaReceber)";	}
		if($IdProcessoFinanceiro != ''){	$where .= " and LancamentoFinanceiroDados.IdProcessoFinanceiro=$IdProcessoFinanceiro";	}
		if($IdCarne != ''){					$where .= " and ContaReceber.IdCarne=$IdCarne";	}
		
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
		
		$sql7 = "select 
				  IdContaReceber 
				from
				  ContaReceber 
				where IdLoja = $IdLoja 
				  and IdContaReceber in ($IdContaReceber)";
		$res7 = @mysql_query($sql7,$con);
		$lin7 =	@mysql_fetch_array($res7);
		
		$sql	=	"select
						LancamentoFinanceiroDados.IdLoja,
						LancamentoFinanceiroDados.IdContaReceber,
						LancamentoFinanceiroDados.IdLancamentoFinanceiro,
						LancamentoFinanceiroDados.IdProcessoFinanceiro,
						Pessoa.IdPessoa,
						substr(Pessoa.Nome,1,30) Nome,
						substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
						LancamentoFinanceiroDados.Tipo,
						LancamentoFinanceiroDados.IdContrato,
						LancamentoFinanceiroDados.IdContaEventual,
						LancamentoFinanceiroDados.IdOrdemServico,
						/*Demonstrativo.Descricao,
						Demonstrativo.Referencia,*/
						LancamentoFinanceiroDados.Valor,
						LancamentoFinanceiroDados.ValorDespesas,
						LancamentoFinanceiroDados.IdStatus,
						LancamentoFinanceiroDados.IdServico,
						LancamentoFinanceiroDados.ParametroDemonstrativo,
						LancamentoFinanceiroDados.DataReferenciaInicial,
						LancamentoFinanceiroDados.DataReferenciaFinal,
						LancamentoFinanceiroDados.NumParcelaEventual,
						LancamentoFinanceiroDados.QtdParcela
					from
						Pessoa,
						LancamentoFinanceiroDados,
						ContaReceber,
						PessoaGrupoPessoa,
						GrupoPessoa
					where
						LancamentoFinanceiroDados.IdLoja = ContaReceber.IdLoja and 
						LancamentoFinanceiroDados.IdContaReceber = ContaReceber.IdContaReceber and
						LancamentoFinanceiroDados.IdLoja = $IdLoja and
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa and
						LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa $where
					order by
						/*Pessoa.Nome,*/
						LancamentoFinanceiroDados.Tipo,
						LancamentoFinanceiroDados.IdContrato,
						LancamentoFinanceiroDados.IdContaEventual,
						LancamentoFinanceiroDados.IdOrdemServico,
						LancamentoFinanceiroDados.DataReferenciaInicial,
						LancamentoFinanceiroDados.IdLancamentoFinanceiro 
					$Limit";
		$res	=	mysql_query($sql,$con);
		$total = 0;
		while($lin	=	@mysql_fetch_array($res)){
			$query = 'true';
			if($lin[IdContrato] != ""){
				$lin[Codigo] = $lin[IdContrato];
			}
			if($lin[IdContaEventual] != ""){
				$lin[Codigo] = $lin[IdContaEventual];
			}
			if($lin[IdOrdemServico] != ""){
				$lin[Codigo] = $lin[IdOrdemServico];
			}
			
			$IdContratoAutomatico = $lin[IdContrato];
			$IdLancamentoFinanceiroAutomatico = null;
			
			if($lin[Tipo] != "EV"){
				$sql = "Select 
							IdServico,
							DescricaoServico Descricao
						from
							Servico
						where
							Servico.IdLoja = '$lin[IdLoja]' AND
							Servico.IdServico = '$lin[IdServico]'";
				
				$resServico = mysql_query($sql,$con);
				$linServico = mysql_fetch_array($resServico);
				if($linServico[Descricao] != ""){
					$lin[Descricao] = $linServico[Descricao];
				}
			}
		
			if($lin[Tipo]=='CO' && $lin[Codigo]!=""){

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
									AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
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
										AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
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
												AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
				}
			}
			
			if($lin[Tipo]=='EV' && $lin[Codigo]!=""){
				$sql2	=	"select IdContrato from ContaEventual where IdLoja = $IdLoja and IdContaEventual = $lin[Codigo]";
				$res2	=	@mysql_query($sql2,$con);
				$lin2	=	@mysql_fetch_array($res2);
				
				if($lin2[IdContrato]!=""){
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
										AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
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
											AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
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
													AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
							$resTemp	=	@mysql_query($sqlTemp,$con);
							if(@mysql_num_rows($resTemp) == 0){
								$query = 'false';
							}
						}
					}
				}
			}
			
			if($lin[Tipo]=='OS' && $lin[Codigo]!=""){
				$sql2	=	"select IdContrato,IdContratoFaturamento from OrdemServico where IdLoja = $IdLoja and IdOrdemServico = $lin[Codigo]";
				$res2	=	@mysql_query($sql2,$con);
				$lin2	=	@mysql_fetch_array($res2);
				
				if($lin2[IdContrato]!="" ||  $lin2[IdContratoFaturamento]!=""){
				
					if($lin2[IdContrato]!=""){
						$aux	.=	" and AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
					}
					
					if($lin2[IdContrato]!="" && $lin2[IdContratoFaturamento]!=""){
						$aux	.=	" or";
					}else{
						$aux	.=	" and";
					}
					
					if($lin2[IdContratoFaturamento]!=""){
						$aux	.=	" AgenteAutorizadoPessoa.IdContrato = $lin2[IdContratoFaturamento]";
					}
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
										Carteira.IdStatus = 1 $aux";
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
											AgenteAutorizado.IdStatus = 1 $aux";
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
													AgenteAutorizado.IdStatus = 1 $aux";
							$resTemp	=	@mysql_query($sqlTemp,$con);
							if(@mysql_num_rows($resTemp) == 0){
								$query = 'false';
							}
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
			
				$lin[Nome]		=	$lin[getCodigoInterno(3,24)];	
				$lin[Moeda]		=	getParametroSistema(5,1);
				
				//Comentei pois não encontrei a utilidade deste código. Responsável Weiner. 09/05/2012.
				/*if($lin[Tipo] == 'CO'){ 
					$sql4	=	"select
								    if(count(IdContaReceber) > 0,'false','true') Voltar
								from
								    Demonstrativo
								where
								    IdLoja = $IdLoja and
								    IdContaReceber > '$lin[IdContaReceber]' and
								    Tipo = 'CO' and
								    Codigo = '$lin[Codigo]' and
								    IdStatus != 0";
		 			$res4	=	@mysql_query($sql4,$con);
					$lin4	=	@mysql_fetch_array($res4);
				}*/
				if($lin[Tipo] == 'CO'){
					$sql3	=	"select IdContrato,IdStatus from Contrato where IdLoja = $IdLoja and IdContrato = $lin[Codigo]";
					$res3	=	@mysql_query($sql3,$con);
					$lin3	=	@mysql_fetch_array($res3);
					
					$sql5	=	"select IdContrato from Contrato where IdLoja = $IdLoja and IdContratoAgrupador = $IdContratoAutomatico";
					$res5	=	@mysql_query($sql5,$con);
					$lin5	=	@mysql_fetch_array($res5);
					
					if($lin5[IdContrato] !=""){
						$IdContratoAutomatico = $lin5["IdContrato"];
						
						$sql6 = "select distinct
								  LancamentoFinanceiroDados.IdLancamentoFinanceiro
								from
								  LancamentoFinanceiroDados,
								  ContaReceber,
								  Pessoa 
								  left join (PessoaGrupoPessoa, GrupoPessoa) 
									on (
									  Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa 
									  and PessoaGrupoPessoa.IdLoja = '$IdLoja' 
									  and PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja 
									  and PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
									) 
								where LancamentoFinanceiroDados.IdLoja = $IdLoja 
								  and LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa 
								  and LancamentoFinanceiroDados.IdContaReceber = ".$lin["IdContaReceber"]."  
								  and LancamentoFinanceiroDados.Tipo = 'CO' 
								order by 
								  LancamentoFinanceiroDados.IdLancamentoFinanceiro";
						$res6 = @mysql_query($sql6, $con);
						
						while($lin6 = @mysql_fetch_array($res6)){
							if(empty($IdLancamentoFinanceiroAutomatico)){
								$IdLancamentoFinanceiroAutomatico = $lin6["IdLancamentoFinanceiro"];
							} else {
								$IdLancamentoFinanceiroAutomatico .= ",".$lin6["IdLancamentoFinanceiro"];
							}
						}
					}
					
					
					
					$sql4 = "select
								max(IdContaReceber) MaxIdContaReceber
							from
							    LancamentoFinanceiroDados
							where
							    IdLoja = $IdLoja and
							    Tipo = 'CO' and
							    IdContrato = '$lin[IdContrato]';";
		 			$res4 = @mysql_query($sql4,$con);
					$lin4 = @mysql_fetch_array($res4);
					
					if((int)$lin4[MaxIdContaReceber] <= (int)$lin[IdContaReceber]){
						$lin4[Voltar] = "true";
					} else{
						$lin4[Voltar] = "false";
					}
				} else{
					$lin4[Voltar] = "false";
				}
				
				$sqlDescricao = "SELECT
									ContaEventual.DescricaoContaEventual,
									Servico.DescricaoServico
								FROM
									LancamentoFinanceiroDados LEFT JOIN OrdemServico ON
									(
									   OrdemServico.IdLoja  = LancamentoFinanceiroDados.IdLoja AND
									   OrdemServico.IdOrdemServico = LancamentoFinanceiroDados.IdOrdemServico
									)
									LEFT JOIN ContaEventual ON(
										ContaEventual.IdLoja = LancamentoFinanceiroDados.IdLoja AND
										ContaEventual.IdContaEventual = LancamentoFinanceiroDados.IdContaEventual	
									) LEFT JOIN Contrato ON(
									
										Contrato.IdLoja = LancamentoFinanceiroDados.IdLoja AND
										Contrato.IdContrato = LancamentoFinanceiroDados.IdContrato
									) LEFT JOIN Servico ON(
										Servico.IdLoja = LancamentoFinanceiroDados.IdLoja AND
										Servico.IdServico = LancamentoFinanceiroDados.IdServico
									)
								WHERE	
									LancamentoFinanceiroDados.IdLoja = $IdLoja AND
									LancamentoFinanceiroDados.IdContaReceber = $lin[IdContaReceber]";
				$resDescricao 	= mysql_query($sqlDescricao,$con);
				$linDescricao	= mysql_fetch_array($resDescricao);

				switch($lin[Tipo]){
					case "CO":
						if($lin[ParametroDemonstrativo] != ""){
							$lin[Descricao] .= " ($lin[ParametroDemonstrativo])";
						}
						$lin[Referencia] = dataConv($lin[DataReferenciaInicial],"Y-m-d","d/m/Y")." a ".dataConv($lin[DataReferenciaFinal],"Y-m-d","d/m/Y");
						break;

					case "EV":
						if($linDescricao[DescricaoContaEventual] != ""){
							$lin[Descricao] = $linDescricao[DescricaoContaEventual];
						}else{
							$lin[Descricao] = $linDescricao[DescricaoServico];
						}
						$lin[Referencia] = "Parcela $lin[NumParcelaEventual]/$lin[QtdParcela]";
						break;

					case "OS":
						$lin[Referencia] = "Parcela $lin[NumParcelaEventual]/$lin[QtdParcela]";
						break;

				}
				
				$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
				$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
				$dados	.=	"\n<IdLancamentoFinanceiro>$lin[IdLancamentoFinanceiro]</IdLancamentoFinanceiro>";
				$dados	.=	"\n<IdLancamentoFinanceiroAutomatico><![CDATA[$IdLancamentoFinanceiroAutomatico]]></IdLancamentoFinanceiroAutomatico>";
				$dados	.=	"\n<IdProcessoFinanceiro><![CDATA[$lin[IdProcessoFinanceiro]]]></IdProcessoFinanceiro>";
				$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
				$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
				$dados	.=	"\n<Referencia><![CDATA[$lin[Referencia]]]></Referencia>";
				$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
				$dados	.=	"\n<ValorDespesas><![CDATA[$lin[ValorDespesas]]]></ValorDespesas>";
				$dados	.=	"\n<Descricao><![CDATA[$lin[Descricao]]]></Descricao>";
				$dados	.=	"\n<Tipo><![CDATA[$lin[Tipo]]]></Tipo>";
				$dados	.=	"\n<Codigo><![CDATA[$lin[Codigo]]]></Codigo>";
				$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados	.=	"\n<IdStatusCO><![CDATA[$lin3[IdStatus]]]></IdStatusCO>";
				$dados	.=	"\n<Moeda><![CDATA[$lin[Moeda]]]></Moeda>";
				$dados	.=	"\n<Voltar><![CDATA[$lin4[Voltar]]]></Voltar>";
				$dados	.=	"\n<IdContratoAutomatico><![CDATA[$IdContratoAutomatico]]></IdContratoAutomatico>";
				
				$cont++;
			}
		}
		if($cont >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_demonstrativo();
?>
