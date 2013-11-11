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
		$OrderBy					= $_GET['OrderBy'];
		$Order						= $_GET['Order'];
		$where						= "";
		$orderby					= "";
		
		if($IdContaReceber != ''){			$where .= " and Demonstrativo.IdContaReceber in ($IdContaReceber)";	}
		if($IdProcessoFinanceiro != ''){	$where .= " and Demonstrativo.IdProcessoFinanceiro=$IdProcessoFinanceiro";	}
		if($IdCarne != ''){					$where .= " and ContaReceber.IdCarne=$IdCarne";	}

		if($OrderBy != "" && $OrderBy != "Nome"){
			$orderby = "Demonstrativo.".$OrderBy." ".$Order.",
						Pessoa.TipoPessoa,
						Pessoa.Nome,
						Demonstrativo.IdLancamentoFinanceiro";
		}
		if($OrderBy != "" && $OrderBy == "Nome"){
			$orderby = "Pessoa.Nome ".$Order.",
						Pessoa.TipoPessoa,
						Pessoa.Nome,
						Demonstrativo.IdLancamentoFinanceiro";
		}
		if($OrderBy == ""){
			$orderby = "Pessoa.TipoPessoa,
						Pessoa.Nome,
						Demonstrativo.IdLancamentoFinanceiro";
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
		
		$sql	=	"select
						Demonstrativo.IdLoja,
						Demonstrativo.IdContaReceber,
						Demonstrativo.IdLancamentoFinanceiro,
						Demonstrativo.IdProcessoFinanceiro,
						Demonstrativo.IdPessoa,
						substr(Pessoa.Nome,1,30) Nome,
						substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
						Demonstrativo.Tipo,
						Demonstrativo.Codigo,
						Demonstrativo.Descricao,
						Demonstrativo.Referencia,
						Demonstrativo.Valor,
						Demonstrativo.ValorDespesas,
						Demonstrativo.IdStatus
					from
						Demonstrativo LEFT JOIN ContaReceber ON (
							Demonstrativo.IdLoja = ContaReceber.IdLoja and 
							Demonstrativo.IdContaReceber = ContaReceber.IdContaReceber
						),
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
						Demonstrativo.IdLoja = $IdLoja and
						Demonstrativo.IdPessoa = Pessoa.IdPessoa $where
					order by
						$orderby
					$Limit";
		$res	=	mysql_query($sql,$con);
		$total = 0;
		while($lin	=	@mysql_fetch_array($res)){
			$query = 'true';
		
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
					$sql4 = "select
								LancamentoFinanceiroContaReceber.IdContaReceber MaxIdContaReceber
							from
								LancamentoFinanceiro,
								LancamentoFinanceiroContaReceber
							where
								LancamentoFinanceiro.IdLoja = $IdLoja and
								LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
								LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
								LancamentoFinanceiro.IdContrato = '$lin[Codigo]' and
								LancamentoFinanceiro.IdOrdemServico = '' and
								LancamentoFinanceiro.IdContaEventual = '' and
								LancamentoFinanceiro.IdStatus != 0 
							order by
								LancamentoFinanceiroContaReceber.IdContaReceber DESC
							limit 0,1";
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
				
				$SequenciaCorNome = getCodigoInterno(3,202);
	
				$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
				$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
				$dados	.=	"\n<IdLancamentoFinanceiro>$lin[IdLancamentoFinanceiro]</IdLancamentoFinanceiro>";
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
				$dados	.=	"\n<Moeda><![CDATA[$lin[Moeda]]]></Moeda>";
				$dados	.=	"\n<SequenciaCorNome><![CDATA[$SequenciaCorNome]]></SequenciaCorNome>";
				$dados	.=	"\n<Voltar><![CDATA[$lin4[Voltar]]]></Voltar>";
				
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