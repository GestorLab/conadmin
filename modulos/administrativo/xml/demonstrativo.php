<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	header ('Content-type: text/html; charset=iso-8859-1');
	function get_demonstrativo(){
		
		global $con;
		global $_GET;
		
		echo $_SESSION["RestringirCarteira"];
		echo $_SESSION["RestringirAgenteAutorizado"];
		echo $_SESSION["RestringirAgenteCarteira"];
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdPessoaLogin				= $_SESSION['IdPessoa'];
		$IdContaReceber 			= $_GET['IdContaReceber'];
		$IdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
		$IdCarne					= $_GET['IdCarne'];
		$NumDoc 					= $_GET['NumDoc'];
		$where						= "";
		
		if($IdContaReceber != ''){			$where .= " and ContaReceber.IdContaReceber in ($IdContaReceber)";	}
		//if($NumDoc != ''){					$where .= " and ContaReceber.NumeroDocumento in($NumDoc)";	}
		if($IdProcessoFinanceiro != ''){	$where .= " and Demonstrativo.IdProcessoFinanceiro=$IdProcessoFinanceiro";	}
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
		
		/*$sql7 = "select 
				  IdContaReceber 
				from
				  ContaReceber 
				where IdLoja = $IdLoja 
				  and IdContaReceber in ($IdContaReceber)";
		$res7 = @mysql_query($sql7,$con);
		$lin7 =	@mysql_fetch_array($res7);*/
		
		$sql	=	"select distinct
						LancamentoFinanceiroDados.IdLoja,
						LancamentoFinanceiroDados.IdContaReceber,
						LancamentoFinanceiroDados.IdLancamentoFinanceiro,
						LancamentoFinanceiroDados.IdProcessoFinanceiro,
						LancamentoFinanceiroDados.IdPessoa,
						substr(Pessoa.Nome,1,30) Nome,
						substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
						LancamentoFinanceiroDados.Tipo,
						if(
							LancamentoFinanceiroDados.Tipo = 'CO',LancamentoFinanceiroDados.`IdContrato`,
							if(
								LancamentoFinanceiroDados.Tipo = 'OS',LancamentoFinanceiroDados.`IdOrdemServico`,
								if(
									LancamentoFinanceiroDados.Tipo = 'EV',LancamentoFinanceiroDados.`IdContaEventual`,
									if(
										LancamentoFinanceiroDados.Tipo = 'EF',LancamentoFinanceiroDados.`IdEncargoFinanceiro`,
										LancamentoFinanceiroDados.`IdContaReceberAgrupado`
									)
								)
							)
						) Codigo,
						IF(
							LancamentoFinanceiroDados.IdContaEventual != '',
							LancamentoFinanceiroDados.DescricaoContaEventual,
							IF(
								LancamentoFinanceiroDados.IdOrdemServico != '',
								CONCAT(Servico.DescricaoServico,CONVERT(IF(LancamentoFinanceiroDados.DataAgendamentoAtendimento !='',CONCAT(' (',DATE_FORMAT(LancamentoFinanceiroDados.DataAgendamentoAtendimento,'%d/%m/%Y'),')'),'') USING latin1)),
								IF(
									LancamentoFinanceiroDados.IdContrato !='',
									IF(
										LancamentoFinanceiroDados.IdEncargoFinanceiro !='',
										'Encargos por atraso',
										IF(
											LancamentoFinanceiroDados.ParametroDemonstrativo !='',
											CONCAT(Servico.DescricaoServico,' (',LancamentoFinanceiroDados.ParametroDemonstrativo,')'),
											Servico.DescricaoServico
										)
									),
									IF(
										LancamentoFinanceiroDados.IdContaReceberAgrupado !='',
										'Conta Receber',
										''
									)
								)
							)
						) Descricao,
						IF(
							LancamentoFinanceiroDados.IdContaEventual !='',
							IF(
								LancamentoFinanceiroDados.OcultarReferencia !='S',
								CONCAT('Parcela ',LancamentoFinanceiroDados.NumParcelaEventual,'/',LancamentoFinanceiroDados.QtdParcela),
								'-'
							),
							IF(
								LancamentoFinanceiroDados.IdOrdemServico !='',
								CONCAT('Parcela ',LancamentoFinanceiroDados.NumParcelaEventual,'/',LancamentoFinanceiroDados.QtdParcela),
								IF(
									LancamentoFinanceiroDados.IdContrato !='',
									IF(
										LancamentoFinanceiroDados.IdMudancaStatus !='',
										IF(
											LancamentoFinanceiroDados.Valor > 0,
											CONCAT(DATE_FORMAT(LancamentoFinanceiroDados.DataReferenciaInicial,'%d/%m/%Y'),' (Mudanca de STATUS)'),
											CONCAT(CONCAT(DATE_FORMAT(LancamentoFinanceiroDados.DataReferenciaInicial,'%d/%m/%Y'),' a ',DATE_FORMAT(LancamentoFinanceiroDados.DataReferenciaFinal,'%d/%m/%Y')),' (Desconto)')
										),
										CONCAT(DATE_FORMAT(LancamentoFinanceiroDados.DataReferenciaInicial,'%d/%m/%Y'),' a ',DATE_FORMAT(LancamentoFinanceiroDados.DataReferenciaFinal,'%d/%m/%Y'))
									),
									IF(
										LancamentoFinanceiroDados.IdContaReceberAgrupado !='',
										'Agrupado',
										''
									)
								)
							)
						) Referencia,
						LancamentoFinanceiroDados.Valor,
						LancamentoFinanceiroDados.ValorDespesas,
						LancamentoFinanceiroDados.IdStatus
					from
						Servico,
						LancamentoFinanceiroDados LEFT JOIN ContaReceber ON (
							LancamentoFinanceiroDados.IdLoja = ContaReceber.IdLoja and 
							LancamentoFinanceiroDados.IdContaReceber = ContaReceber.IdContaReceber
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
						LancamentoFinanceiroDados.IdLoja = $IdLoja and
						LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa $where
						/*group by ContaReceber.NumeroDocumento*/
						GROUP BY LancamentoFinanceiroDados.IdLancamentoFinanceiro ORDER BY LancamentoFinanceiroDados.Tipo
					$Limit";
		//echo $sql;die;
		$res	=	mysql_query($sql,$con);
		$total = 0;
		$cont = 0;
		$dados = '';
		$i = 0;
		$j = 0;
		$x = 0;
		$y = 0;
		$co = 0;
		$ev = 0;
		while($lin	=	@mysql_fetch_array($res)){
			$query = 'true';
			$IdContratoAutomatico = $lin[Codigo];
			$IdLancamentoFinanceiroAutomatico = null;
		
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
			//exit('akii');
				if($cont == 0){
					/*header ("content-type: text/xml");
					$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
					$dados	.=	"\n<reg>";*/
					//$dados .= "<table>";
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
						
						$sql6 = "select 
								  Demonstrativo.IdLancamentoFinanceiro
								from
								  Demonstrativo 
								  LEFT JOIN ContaReceber 
									ON (
									  Demonstrativo.IdLoja = ContaReceber.IdLoja 
									  and Demonstrativo.IdContaReceber = ContaReceber.IdContaReceber
									),
								  Pessoa 
								  left join (PessoaGrupoPessoa, GrupoPessoa) 
									on (
									  Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa 
									  and PessoaGrupoPessoa.IdLoja = '$IdLoja' 
									  and PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja 
									  and PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
									) 
								where Demonstrativo.IdLoja = $IdLoja 
								  and Demonstrativo.IdPessoa = Pessoa.IdPessoa 
								  and Demonstrativo.IdContaReceber = ".$lin["IdContaReceber"]."  
								  and Demonstrativo.Tipo = 'CO' 
								order by 
								  Demonstrativo.IdLancamentoFinanceiro";
						$res6 = @mysql_query($sql6, $con);
						
						while($lin6 = @mysql_fetch_array($res6)){
							if(empty($IdLancamentoFinanceiroAutomatico)){
								$IdLancamentoFinanceiroAutomatico = $lin6["IdLancamentoFinanceiro"];
							} else {
								$IdLancamentoFinanceiroAutomatico .= ",".$lin6["IdLancamentoFinanceiro"];
							}
						}
					}
					
					
					
					/*$sql4 = "select
								max(IdContaReceber) MaxIdContaReceber
							from
							    Demonstrativo
							where
							    IdLoja = $IdLoja and
							    Tipo = 'CO' and
							    Codigo = '$lin[Codigo]';";
		 			$res4 = @mysql_query($sql4,$con);
					$lin4 = @mysql_fetch_array($res4);
					
					if((int)$lin4[MaxIdContaReceber] <= (int)$lin[IdContaReceber]){
						$lin4[Voltar] = "true";
					} else{
						$lin4[Voltar] = "false";
					}*/
				} else{
					$lin4[Voltar] = "false";
				}
	
				/*$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
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
				$dados	.=	"\n<IdContratoAutomatico><![CDATA[$IdContratoAutomatico]]></IdContratoAutomatico>";*/
				if($lin[Valor] == ''){
					$lin[Valor] = 0;
				}
				
				if($lin[Valor] < 0){
					$lin[Valor] = round($lin[Valor], 2);
					$Valor = number_format($lin[Valor], 2, ",", "."); 
					$dados_neg .= "<table>
						
							<tr >
								<td class='find'></td>
								<td class='descCampo'>Contas R.</td>
								<td class='separador'></td>
								<td class='descCampo'>Tipo</td>
								<td class='separador'></td>
								<td class='descCampo'>Código</td>
								<td class='separador'></td>
								<td class='descCampo'>Descrição</td>
								<td class='separador'></td>
								<td class='descCampo'>Referência</td>
								<td class='separador'></td>
								<td class='descCampo'>Valor (R$)</td>
								<td class='separador'></td>
								<td class='descCampo'><B>Reaproveitar Crédito?</B></td>
							</tr>
							<tr>
								<td class='find'></td>
								<td class='campo'>
									<input type='text' readonly='' style='width: 60px;' value='$lin[IdContaReceber]' nome='ContaReceber_$lin[IdLancamentoFinanceiro]' />
								</td>
								<td class='separador'></td>
								<td class='campo'>
									<select style='width: 50px;' name='Tipo_$lin[IdLancamentoFinanceiro]'>
										<option value='1'>$lin[Tipo]</option>
									</select>
								</td>
								<td class='separador'></td>
								<td class='campo'>
									<input type='text' readonly='' style='width: 60px;' value='$lin[Codigo]' name='Codigo_$lin[IdLancamentoFinanceiro]' />
								</td>
								<td class='separador'></td>
								<td class='campo'>
									<input type='text' readonly='' style='width: 156px;' value='$lin[Descricao]' name='Descricao_$lin[IdLancamentoFinanceiro]' />
								</td>
								<td class='separador'></td>
								<td class='campo'>
									<input type='text' readonly='' style='width: 146px' value='$lin[Referencia]' name='Referencia_$lin[IdLancamentoFinanceiro]' />
								</td>
								<td class='separador'></td>
								<td class='campo'>
									<input type='text' readonly='' style='width: 84px;' value='$Valor' name='ValorLancamento_$lin[IdLancamentoFinanceiro]' />
								</td>
								<td class='separador'></td>
								<td class='Campo'>";
								switch($lin[Tipo]){
									case 'CO':
										$dados_neg .= "<select class='co obrig' onChange='\$j.teste(this.value, this.id, this.className)' onBlur='Foco(this, \"out\")' onFocus='Foco(this, \"in\")' style='width: 170px;' name='ReaproveitarCredito_$lin[IdLancamentoFinanceiro]' id='ReaproveitarCreditoCo_$co'>";
										$co++;			
										break;
									case 'EV':
										$dados_neg .= "<select class='ev obrig' onChange='\$j.teste(this.value, this.id, this.className)' onBlur='Foco(this, \"out\")' onFocus='Foco(this, \"in\")' style='width: 170px;' name='ReaproveitarCredito_$lin[IdLancamentoFinanceiro]' id='ReaproveitarCreditoEv_$ev'>";
										$ev++;
										break;
								}
									
								$dados_neg .=	"<option value='0'></option>
											<option value='1'>Sim</option>
											<option value='2'>Não</option>
									  	</select>
										<input type='hidden' name='ReaproveitarCreditoDefault_$lin[IdLancamentoFinanceiro]' value='$lin4[Voltar]' />
								</td>
							</tr>
						</table>";
					$cont++;
					}else{
						$dados .= "<table>
								    <tr >
									<td class='find'></td>
									<td class='descCampo'>Contas R.</td>
									<td class='separador'></td>
									<td class='descCampo'>Tipo</td>
									<td class='separador'></td>
									<td class='descCampo'>Código</td>
									<td class='separador'></td>
									<td class='descCampo'>Descrição</td>
									<td class='separador'></td>
									<td class='descCampo'>Referência</td>
									<td class='separador'></td>
									<td class='descCampo'>Valor (R$)</td>
									<td class='separador'></td>";
						switch($lin[Tipo]){
							case 'CO':
								$dados	.=	"<td class='descCampo'><B>Voltar data base de cálculo?</B></td>";
								break;
							case 'EV':
								$dados	.=	"<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
							case 'OS':
								$dados	.=	"<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
							case 'EF':
								$dados	.=	"<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
						}
						$dados .= "</tr>
						<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
						<input type='text' name='ContaReceber_$lin[IdLancamentoFinanceiro]' value='$lin[IdContaReceber]' style='width:60px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
						<select class='tipo' name='Tipo_$lin[IdLancamentoFinanceiro]' style='width:50px'>
						<option value='1'>$lin[Tipo]</option>
						</select>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
						<input type='text' name='Codigo_$lin[IdLancamentoFinanceiro]' value='$lin[Codigo]' style='width:60px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
						<input type='text' name='Descricao_$lin[IdLancamentoFinanceiro]' value='$lin[Descricao]' style='width:156px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
						<input type='text' name='Referencia_$lin[IdLancamentoFinanceiro]' value='$lin[Referencia]' style='width:146px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
						<input type='text' name='ValorLancamento_$lin[IdLancamentoFinanceiro]' value='$lin[Valor]' style='width:84px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>";
						switch($lin[Tipo]){
							case 'CO':
								if(isset($IdLancamentoFinanceiroAutomatico) && $IdLancamentoFinanceiroAutomatico != ""){
									//$IdLancamentoFinanceiroAutomatico = $lin6["IdLancamentoFinanceiro"];
									$dados .= "<input type='hidden' name='IdLancamentoFinanceiroAutomatico_$lin[IdLancamentoFinanceiro]' value='$IdLancamentoFinanceiroAutomatico' />";
								} //else {
								//	$IdLancamentoFinanceiroAutomatico .= ",".$lin6["IdLancamentoFinanceiro"];
								//}
								$dados	.=	"<select onChange='\$j.teste(this.value, this.id, this.className)' class='co obrig' name='VoltarDataBase_$lin[IdLancamentoFinanceiro]' id='VoltarDataBase_$i' onFocus='Foco(this, \"in\")' onBlur='Foco(this,\"out\")' style='width:170px'>
											<option value='0'></option>
											<option value='1'>Sim</option>
											<option value='2'>Não</option>";
								$i++;
								//onChange='verificaMudarDataBase('$lin[Codigo]','$lin[IdLancamentoFinanceiro]',this.value)'
								//onFocus='Foco(this,\'in\')'  onBlur='Foco(this,\'out\')'
								break;
							case 'EV':
								$dados	.=	"<select onChange='\$j.teste(this.value, this.id, this.className)' class='ev obrig' name='CancelarContaEventual_$lin[IdLancamentoFinanceiro]' id='CancelarContaEventual_$j' style='width:170px'  onFocus='Foco(this, \"in\")'  onBlur='Foco(this,\"out\")'>
											<option value='0'></option>
											<option value='1'>Sim</option>
											<option value='2'>Não</option>";
								$j++;
								break;
							case 'OS':
								$dados	.=	"<select onChange='\$j.teste(this.value, this.id, this.className)' class='os obrig' name='CancelarOrdemServico_$lin[IdLancamentoFinanceiro]' id='CancelarOrdemServico_$x' style='width:170px'  onFocus='Foco(this, \"in\")'  onBlur='Foco(this,\"out\")'>
											<option value='0'></option>
											<option value='1'>Sim</option>
											<option value='2'>Não</option>";
								$x++;
								break;
							case 'EF':
								$dados	.=	"<select onChange='\$j.teste(this.value, this.id, this.className)' class='ef obrig' name='CancelarEncargoFinanceiro_$lin[IdLancamentoFinanceiro]' id='CancelarEncargoFinanceiro_$y' style='width:170px'  onFocus='Foco(this, \"in\")'  onBlur='Foco(this,\"out\")'>
											<option value='0'></option>
											<option value='1'>Sim</option>
											<option value='2'>Não</option>";
								$y++;
								break;
						}
						$dados	.=	"</select>
										<input type='hidden' name='VoltarDataBaseDefault_$lin[IdLancamentoFinanceiro]' value='$lin4[Voltar]' />
										</td>
										</tr>
										</table>";
						
						$cont++;
						//echo $cont;
						//echo $dados;
						//exit;
					}
				
				//$cont++;
			}
			//$i++;
		}
		if($cont >= 1){
			//$dados .= "</table>";
			return $dados_neg . $dados;
		}else{
			return 'false';
		}
	}
	echo get_demonstrativo();
?>
