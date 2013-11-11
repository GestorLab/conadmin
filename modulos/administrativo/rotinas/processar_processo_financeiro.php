<?
	set_time_limit(0);
	ini_set("memory_limit",getParametroSistema(138, 1));

	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false && $localOperacao != 2){
		$local_Erro = 2;
	}else{
		$IdLoja 				= $local_IdLoja;
		$IdProcessoFinanceiro 	= $local_IdProcessoFinanceiro;
		$Login					= $local_Login;
		
		$sql = "select 
					IdProcessoFinanceiro	
				from 
					ProcessoFinanceiro	
				where 
					IdLoja = $local_IdLoja and 
					Filtro_IdLocalCobranca	= $local_Filtro_IdLocalCobranca and 
					IdStatus != 3 and IdStatus != 1 and 
					IdProcessoFinanceiro < $local_IdProcessoFinanceiro";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) >=1 && $localOperacao != 2){
			$local_Erro	=	78;
		}else{
			$sqlProcessoFinanceiro = "select
									    MesReferencia,
									    Filtro_IdPessoa,
									    Filtro_IdLocalCobranca,
										Filtro_TipoLancamento,
										Filtro_IdPaisEstadoCidade,
										Filtro_IdContrato,
										Filtro_IdStatusContrato,
										Filtro_FormaAvisoCobranca,
										Filtro_IdServico,
										Filtro_TipoCobranca,
										Filtro_IdTipoContrato,
										Filtro_IdAgenteAutorizado,
										Filtro_IdGrupoPessoa,
										Filtro_DiaCobranca,
										Filtro_TipoPessoa,
										MesVencimento
									from
									    ProcessoFinanceiro
									where
									    IdLoja = $IdLoja and
									    IdProcessoFinanceiro = $IdProcessoFinanceiro and
									    IdStatus = 1";
			$resProcessoFinanceiro = mysql_query($sqlProcessoFinanceiro, $con);
			if($linProcessoFinanceiro = mysql_fetch_array($resProcessoFinanceiro)){
			
				$sql	=	"START TRANSACTION;";
				mysql_query($sql,$con);
				$tr_i = 0;		
		
				$MesAnoReferencia		= $linProcessoFinanceiro[MesReferencia]; // Mes/Ano de referencia informado pelo cliente
				$MesReferencia			= substr($MesAnoReferencia,0,2); // Obtem-se o Mes de Referencia
				$AnoReferencia			= substr($MesAnoReferencia,3,4); // Obtem-se o Ano de Referencia
				$DataReferenciaInicial	= "01/".$MesAnoReferencia; // Primeiro dia do mes de Referencia
				$DiaReferenciaFinal		= ultimoDiaMes($MesReferencia, $AnoReferencia); // Ultimo dia do mes de referencia
				$DataReferenciaFinal	= $DiaReferenciaFinal."/".$MesAnoReferencia; // // Ultimo data do mes de referencia
			
				$DataReferenciaInicial	= dataConv($DataReferenciaInicial,"d/m/Y","Y-m-d");
				$DataReferenciaFinal	= dataConv($DataReferenciaFinal,"d/m/Y","Y-m-d");
	
					
				$MesAnoReferencia2		= incrementaMesReferencia($linProcessoFinanceiro[MesReferencia], 1); // Mes/Ano de referencia informado pelo cliente
				$MesReferencia2			= substr($MesAnoReferencia2,0,2); // Obtem-se o Mes de Referencia
				$AnoReferencia2			= substr($MesAnoReferencia2,3,4); // Obtem-se o Ano de Referencia
				$DataReferenciaInicial2	= "01/".$MesAnoReferencia2; // Primeiro dia do mes de Referencia
				$DiaReferenciaFinal2	= ultimoDiaMes($MesReferencia2, $AnoReferencia2); // Ultimo dia do mes de referencia
				$DataReferenciaFinal2	= $DiaReferenciaFinal2."/".$MesAnoReferencia2; // // Ultimo data do mes de referencia
			
				$DataReferenciaInicial2	= dataConv($DataReferenciaInicial2,"d/m/Y","Y-m-d");
				$DataReferenciaFinal2	= dataConv($DataReferenciaFinal2,"d/m/Y","Y-m-d");
				
				// Estabelecendo os filtros
				if($linProcessoFinanceiro[Filtro_IdPessoa]!=''){
					$sqlFiltro_IdPessoa = "Contrato.IdPessoa in (".$linProcessoFinanceiro[Filtro_IdPessoa].") and";
				}

				if($linProcessoFinanceiro[Filtro_IdGrupoPessoa]!=''){
					$sqlFiltro_IdGrupoPessoa = " Pessoa.IdPessoa in (
													select 
														PessoaGrupoPessoa.IdPessoa
													from 
														PessoaGrupoPessoa
													where
														PessoaGrupoPessoa.IdGrupoPessoa in (".$linProcessoFinanceiro[Filtro_IdGrupoPessoa].")
												) and";
				}
								
				if($linProcessoFinanceiro[Filtro_DiaCobranca]!=''){
					$sqlFiltro_DiaCobranca = "Contrato.DiaCobranca in (".$linProcessoFinanceiro[Filtro_DiaCobranca].") and";
				}
				
				if($linProcessoFinanceiro[Filtro_TipoCobranca]==1){
					$sqlFiltro_TipoCobranca = "Contrato.IdPeriodicidade != 8";
				}else{					
					$sqlFiltro_TipoCobranca = "Contrato.IdPeriodicidade = 8";
				}
				
				if($linProcessoFinanceiro[Filtro_IdPaisEstadoCidade]!=''){

					$sqlFiltro_IdPaisEstadoCidade = '';

					$Filtro_IdPaisEstadoCidade = explode('^',$linProcessoFinanceiro[Filtro_IdPaisEstadoCidade]);

					$sqlFiltro_IdPaisEstadoCidade .= "(";

					for($i=0; $i < count($Filtro_IdPaisEstadoCidade); $i++){
						$Filtro_IdPaisEstadoCidadeTemp = explode(',',$Filtro_IdPaisEstadoCidade[$i]);

						$Filtro_IdPais		= $Filtro_IdPaisEstadoCidadeTemp[0];
						$Filtro_IdEstado	= $Filtro_IdPaisEstadoCidadeTemp[1];
						$Filtro_IdCidade	= $Filtro_IdPaisEstadoCidadeTemp[2];

						$sqlFiltro_IdPaisEstadoCidade .= "(PessoaEndereco.IdPais = $Filtro_IdPais and PessoaEndereco.IdEstado = $Filtro_IdEstado and PessoaEndereco.IdCidade = $Filtro_IdCidade)";

						if(($i+1) < count($Filtro_IdPaisEstadoCidade)){
							$sqlFiltro_IdPaisEstadoCidade .= " or ";
						}

					}

					$sqlFiltro_IdPaisEstadoCidade .= ") and";
				}

				if($linProcessoFinanceiro[Filtro_FormaAvisoCobranca]!=''){
					switch($linProcessoFinanceiro[Filtro_FormaAvisoCobranca]){
						case 1:
							// 'Correios';
							$sqlFiltro_FormaAvisoCobranca = "Pessoa.Cob_FormaCorreio = 'S' and";
							break;
						case 2:
							// 'E-mail';
							$sqlFiltro_FormaAvisoCobranca = "Pessoa.Cob_FormaEmail = 'S' and";
							break;
						case 3:
							// 'Outros';
							$sqlFiltro_FormaAvisoCobranca = "Pessoa.Cob_FormaOutro = 'S' and";
							break;
					}
				}

				if($linProcessoFinanceiro[Filtro_IdContrato]!=''){
					$sqlFiltro_IdContrato = "(Contrato.IdContrato in ($linProcessoFinanceiro[Filtro_IdContrato]) or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $IdLoja and IdContrato in ($linProcessoFinanceiro[Filtro_IdContrato]))) and";					
				}

				if($linProcessoFinanceiro[Filtro_IdServico]!=''){
					$sqlFiltro_IdServico = "(Contrato.IdServico in (".$linProcessoFinanceiro[Filtro_IdServico].") or Contrato.IdContrato in (select IdContrato from Contrato where IdLoja = $IdLoja and IdContratoAgrupador != '' and IdServico in (select distinct IdServico from ServicoAgrupado where IdLoja = $IdLoja and IdServicoAgrupador in (".$linProcessoFinanceiro[Filtro_IdServico].")))) and";
				}

				if($linProcessoFinanceiro[Filtro_IdAgenteAutorizado]!=''){
					$sqlFiltro_IdAgenteAutorizado = "Contrato.IdAgenteAutorizado in ($linProcessoFinanceiro[Filtro_IdAgenteAutorizado]) and";
				}

				if($linProcessoFinanceiro[Filtro_IdStatusContrato]!=''){
					$sqlFiltro_IdStatusContrato = "Contrato.IdStatus in ($linProcessoFinanceiro[Filtro_IdStatusContrato]) and";
				}else{
					$sqlFiltro_IdStatusContrato = "Contrato.IdStatus != 202 and";
				}

				if($linProcessoFinanceiro[Filtro_TipoPessoa]!=''){
					$sqlFiltro_TipoPessoa = "Pessoa.TipoPessoa = $linProcessoFinanceiro[Filtro_TipoPessoa] and";
				}
				
				if($linProcessoFinanceiro[Filtro_IdTipoContrato]!=''){
					switch($linProcessoFinanceiro[Filtro_IdTipoContrato]){
						case '1':/* Pré-Pago */
							$sqlFiltro_IdTipoContrato = "
								(
									Contrato.TipoContrato = 1 and
									Contrato.DataPrimeiraCobranca <= '$DataReferenciaFinal2' and 
									(
										(
											Contrato.DataBaseCalculo = '0000-00-00' or
											Contrato.DataBaseCalculo is null or
											Contrato.DataUltimaCobranca <= '$DataReferenciaFinal2'
										) or 
										(
											(
												Contrato.DataUltimaCobranca is null or
												Contrato.DataUltimaCobranca >= '$DataReferenciaInicial2'
											) and 
											(
												Contrato.DataBaseCalculo = '0000-00-00' or
												Contrato.DataBaseCalculo is null or
												Contrato.DataBaseCalculo <= '$DataReferenciaFinal2'
											)
										)
									)
								) and";
						break;
						case '2':/* Pós-Pago */
							$sqlFiltro_IdTipoContrato = "
								(
									Contrato.TipoContrato = 2 and
									Contrato.DataPrimeiraCobranca <= '$DataReferenciaFinal' and 
									(
										(
											Contrato.DataBaseCalculo = '0000-00-00' or
											Contrato.DataBaseCalculo is null or
											Contrato.DataUltimaCobranca <= '$DataReferenciaFinal'
										) or 
										(
											(
												Contrato.DataUltimaCobranca is null or
												Contrato.DataUltimaCobranca >= '$DataReferenciaInicial'
											) and 
											(
												Contrato.DataBaseCalculo = '0000-00-00' or
												Contrato.DataBaseCalculo is null or
												Contrato.DataBaseCalculo <= '$DataReferenciaFinal'
											)
										)
									)
								) and";
						break;
						default:
							$sqlFiltro_IdTipoContrato = "
							(
								/* Pós-Pago */
								(
									Contrato.TipoContrato = 2 and
									Contrato.DataPrimeiraCobranca <= '$DataReferenciaFinal' and 
									(
										(
											Contrato.DataBaseCalculo = '0000-00-00' or
											Contrato.DataBaseCalculo is null or
											Contrato.DataUltimaCobranca <= '$DataReferenciaFinal'
										) or 
										(
											(
												Contrato.DataUltimaCobranca is null or
												Contrato.DataUltimaCobranca >= '$DataReferenciaInicial'
											) and 
											(
												Contrato.DataBaseCalculo = '0000-00-00' or
												Contrato.DataBaseCalculo is null or
												Contrato.DataBaseCalculo <= '$DataReferenciaFinal'
											)
										)
									)
								) or
								/* Pré-Pago */
								(
									(
										Contrato.TipoContrato = 1 and
										Contrato.DataPrimeiraCobranca <= '$DataReferenciaFinal2' and 
										(
											(
												Contrato.DataBaseCalculo = '0000-00-00' or
												Contrato.DataBaseCalculo is null or
												Contrato.DataUltimaCobranca <= '$DataReferenciaFinal2'
											) or 
											(
												(
													Contrato.DataUltimaCobranca is null or
													Contrato.DataUltimaCobranca >= '$DataReferenciaInicial2'
												) and 
												(
													Contrato.DataBaseCalculo = '0000-00-00' or
													Contrato.DataBaseCalculo is null or
													Contrato.DataBaseCalculo <= '$DataReferenciaFinal2'
												)
											)
										)
									)
								)
							) and";
						break;
					}
				}else{
					$sqlFiltro_IdTipoContrato = "
						(
							/* Pós-Pago */
							(
								Contrato.TipoContrato = 2 and
								Contrato.DataPrimeiraCobranca <= '$DataReferenciaFinal' and 
								(
									(
										Contrato.DataBaseCalculo = '0000-00-00' or
										Contrato.DataBaseCalculo is null or
										Contrato.DataUltimaCobranca <= '$DataReferenciaFinal'
									) or 
									(
										(
											Contrato.DataUltimaCobranca is null or
											Contrato.DataUltimaCobranca >= '$DataReferenciaInicial'
										) and 
										(
											Contrato.DataBaseCalculo = '0000-00-00' or
											Contrato.DataBaseCalculo is null or
											Contrato.DataBaseCalculo <= '$DataReferenciaFinal'
										)
									)
								)
							) or
							/* Pré-Pago */
							(
								(
									Contrato.TipoContrato = 1 and
									Contrato.DataPrimeiraCobranca <= '$DataReferenciaFinal2' and 
									(
										(
											Contrato.DataBaseCalculo = '0000-00-00' or
											Contrato.DataBaseCalculo is null or
											Contrato.DataUltimaCobranca <= '$DataReferenciaFinal2'
										) or 
										(
											(
												Contrato.DataUltimaCobranca is null or
												Contrato.DataUltimaCobranca >= '$DataReferenciaInicial2'
											) and 
											(
												Contrato.DataBaseCalculo = '0000-00-00' or
												Contrato.DataBaseCalculo is null or
												Contrato.DataBaseCalculo <= '$DataReferenciaFinal2'
											)
										)
									)
								)
							)
						) and";
				}
				
				$cont = 0;
				
				// Contratos e Todos
				if($linProcessoFinanceiro[Filtro_TipoLancamento] == '' || $linProcessoFinanceiro[Filtro_TipoLancamento] == 1){
			
					$contratoSemVigencia = 0;
					
					// Listo todos os contatos que são vigentes
					$sql = "select 
								Contrato.IdContrato, 
								Contrato.IdPeriodicidade, 
								Contrato.QtdParcela, 
								Contrato.TipoContrato, 
								Contrato.IdServico, 
								Contrato.DataBaseCalculo, 
								Contrato.DataUltimaCobranca, 
								Contrato.DataPrimeiraCobranca,
								Contrato.IdPessoa,
								Periodicidade.Fator,
								Contrato.DiaCobranca,
								Contrato.MesFechado,
								Contrato.IdContratoAgrupador,
								Servico.FaturamentoFracionado
							from 
								Contrato, 
								Servico,
								Pessoa,
								PessoaEndereco,
								Periodicidade,
								LocalCobranca
							where 
								Contrato.IdLoja = $IdLoja and 
								Contrato.IdLoja = Servico.IdLoja and 
								Contrato.IdLoja = Periodicidade.IdLoja and 
								Contrato.IdLoja = LocalCobranca.IdLoja and 
								Contrato.IdServico = Servico.IdServico and
								Contrato.IdPessoa = Pessoa.IdPessoa and
								Contrato.IdPessoa = PessoaEndereco.IdPessoa and
								Contrato.IdPessoaEnderecoCobranca = PessoaEndereco.IdPessoaEndereco and
								Contrato.IdPeriodicidade = Periodicidade.IdPeriodicidade and
								Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
								LocalCobranca.IdLocalCobranca = $linProcessoFinanceiro[Filtro_IdLocalCobranca] and
								(
									(
										LocalCobranca.IdNotaFiscalTipo is not null and
										LocalCobranca.IdNotaFiscalTipo = Servico.IdNotaFiscalTipo
									) or
									LocalCobranca.IdNotaFiscalTipo is null
								) and
								(
									DebitoAutorizado(Contrato.IdLoja, Pessoa.IdPessoa, LocalCobranca.IdLocalCobranca) = 1 or
									LocalCobranca.IdTipoLocalCobranca != 3
								) and
								$sqlFiltro_IdPessoa
								$sqlFiltro_IdPaisEstadoCidade
								$sqlFiltro_IdContrato
								$sqlFiltro_IdStatusContrato
								$sqlFiltro_FormaAvisoCobranca
								$sqlFiltro_IdServico								
								$sqlFiltro_IdAgenteAutorizado
								$sqlFiltro_IdGrupoPessoa
								$sqlFiltro_DiaCobranca
								$sqlFiltro_TipoPessoa
								$sqlFiltro_IdTipoContrato
								
								/* essesempre tem que ser o último */
								$sqlFiltro_TipoCobranca";
					$resDadosContrato = mysql_query($sql,$con);
					while($DadosContrato = mysql_fetch_array($resDadosContrato)){
						
						$Cobrar = null;
						
						if($local_DataAtivacaoFim != ''){
							if($DadosContrato[DataUltimaCobranca] == '' || dataConv($local_DataAtivacaoFim,"d/m/Y","Ymd") < dataConv($DadosContrato[DataUltimaCobranca],"d/m/Y","Ymd")){
								$DadosContrato[DataUltimaCobranca] = dataConv($local_DataAtivacaoFim,"d/m/Y","Y-m-d");
							}
						}

						if(($DadosContrato[DiaCobranca] == '' || $DadosContrato[DiaCobranca] == 0) && $DadosContrato[IdContratoAgrupador] != ''){
							$sqlDiaCobranca = "select
														DiaCobranca
													from
														Contrato
													where
														IdLoja = $IdLoja and
														IdContrato = $DadosContrato[IdContratoAgrupador]";
							$resDiaCobranca = mysql_query($sqlDiaCobranca,$con);
							$linDiaCobranca = mysql_fetch_array($resDiaCobranca);

							$DadosContrato[DiaCobranca] = $linDiaCobranca[DiaCobranca];
						}

						// Cada periodicidade, um caso
						switch($DadosContrato[IdPeriodicidade]){

							case "8":	// Carnê
								include('rotinas/processar_processo_financeiro_carne.php');								
								break;

							default:	// Totos os casos comuns
								include('rotinas/processar_processo_financeiro_all.php');
								break;
						}

						// Créditos Autorizados...
						$sqlCredito = "select 
											LancamentoFinanceiro.IdLancamentoFinanceiro
										from 
											LancamentoFinanceiro, 
											Contrato,
											Pessoa
										where 
											Contrato.IdLoja = $IdLoja and 
											Contrato.IdLoja = LancamentoFinanceiro.IdLoja and  
											Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
											LancamentoFinanceiro.IdContaEventual is null and
											LancamentoFinanceiro.IdOrdemServico is null and
											Contrato.IdPessoa = $DadosContrato[IdPessoa] and 
											Pessoa.IdPessoa = Contrato.IdPessoa and
											LancamentoFinanceiro.IdProcessoFinanceiro is null and 
											LancamentoFinanceiro.IdStatus = 2 and

											$sqlFiltro_TipoPessoa

											LancamentoFinanceiro.Valor < 0";
						$resCredito = mysql_query($sqlCredito,$con);
						while($linCredito = mysql_fetch_array($resCredito)){
							$cont++;
							$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET
															IdStatus = 3,
															IdProcessoFinanceiro=$IdProcessoFinanceiro
														WHERE 
															IdLoja=$IdLoja AND 
															IdLancamentoFinanceiro=$linCredito[IdLancamentoFinanceiro]";
							$local_transaction[$tr_i] = mysql_query($sqlLancamentoFinanceiro,$con);
							$tr_i++;
						}
						
						$Cobrar[$i][Valor] = 0;
							
						for($i = 0; $i < count($Cobrar)-1; $i++){
							
#							echo "<br><br><br><br>Contrato($DadosContrato[IdContrato]) -> ".$Cobrar[$i][DataInicio]." > ".$Cobrar[$i][DataFinal]."<br>";
							
							$Cobrar[$i][DataInicio]	= dataConv($Cobrar[$i][DataInicio],"Y-m-d","Ymd");
							$Cobrar[$i][DataFinal] 	= dataConv($Cobrar[$i][DataFinal],"Y-m-d","Ymd");
							$Cobrar[$i][Valor] 		= 0;							
							
							$sqlDadosVigencia = "select
													DataInicio,
													DataTermino,  
													Valor,
													ValorDesconto,
													IdTipoDesconto,
													LimiteDesconto,
													ValorRepasseTerceiro
												from
													ContratoVigencia
												where
													IdLoja = $IdLoja and
													IdContrato = ".$DadosContrato[IdContrato];
							$resDadosVigencia = mysql_query($sqlDadosVigencia,$con);
							if(mysql_num_rows($resDadosVigencia) == 0){
								$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - (ERRO) Contrato ($DadosContrato[IdContrato]) sem vigência (".dataConv($Cobrar[$i][DataInicio],"Ymd","d/m/Y")." -> ".dataConv($Cobrar[$i][DataFinal],"Ymd","d/m/Y").").\n".$LogProcessamento;
								$contratoSemVigencia++;
							}
							
							$VigenciaArray = NULL;

							while($linDadosVigencia = mysql_fetch_array($resDadosVigencia)){
								$VigenciaArray[$linDadosVigencia[DataInicio]][DataTermino]				= $linDadosVigencia[DataTermino];
								$VigenciaArray[$linDadosVigencia[DataInicio]][Valor]					= $linDadosVigencia[Valor];
								$VigenciaArray[$linDadosVigencia[DataInicio]][ValorDesconto]			= $linDadosVigencia[ValorDesconto];
								$VigenciaArray[$linDadosVigencia[DataInicio]][IdTipoDesconto]			= $linDadosVigencia[IdTipoDesconto];
								$VigenciaArray[$linDadosVigencia[DataInicio]][LimiteDesconto]			= $linDadosVigencia[LimiteDesconto];
								$VigenciaArray[$linDadosVigencia[DataInicio]][ValorRepasseTerceiro]		= $linDadosVigencia[ValorRepasseTerceiro];
							}

#							echo "CONTRATO: ".$DadosContrato[IdContrato]." ################################################### INI ORG<BR>";

							$VigenciaArray	 = OrganizaVigenciaArray($VigenciaArray);

#							echo "CONTRATO: ".$DadosContrato[IdContrato]." ################################################### FIM ORG<BR>";

							$DataInicioTemp  = dataConv($linDadosVigencia[DataInicio],"Y-m-d","Ymd");
							$DataTerminoTemp = dataConv($linDadosVigencia[DataTermino],"Y-m-d","Ymd");
						
							if(($Cobrar[$i][DataInicio] <= $DataTerminoTemp || $DataTerminoTemp == '') && $DataInicioTemp <= $Cobrar[$i][DataFinal]){

								if($DataInicioTemp < $Cobrar[$i][DataInicio]){
									$DataInicioTemp = $Cobrar[$i][DataInicio];
								}
								if($DataTerminoTemp > $Cobrar[$i][DataFinal] || $DataTerminoTemp == ''){
									$DataTerminoTemp = $Cobrar[$i][DataFinal];
								}
								
								$DataInicioTemp  = dataConv($DataInicioTemp,"Ymd","Y-m-d");
								$DataTerminoTemp = dataConv($DataTerminoTemp,"Ymd","Y-m-d");

#								echo "Inicio: $DataInicioTemp  >>>  Fim: $DataTerminoTemp<br>";
								
								$QtdDias 	= nDiasIntervalo($DataInicioTemp,$DataTerminoTemp);
								$QtdDiasMes = ultimoDiaMes(substr($DataInicioTemp,5,2), substr($DataInicioTemp,0,4));
								
								$DataInicioTemp  = dataConv($DataInicioTemp,"Y-m-d","Ymd");
								$DataTerminoTemp = dataConv($DataTerminoTemp,"Y-m-d","Ymd");

								if(is_array($VigenciaArray)){
									$VigenciaElementos = array_keys($VigenciaArray);
								}

								$Valor					= 0;
								$ValorDesconto			= 0;
								$ValorRepasseTerceiro	= 0;
								$LimiteDesconto			= '';

								for($iVigenciaPos = 0; $iVigenciaPos < count($VigenciaElementos); $iVigenciaPos++){

#									echo "<BR>".$VigenciaElementos[$iVigenciaPos]." >>> ".$VigenciaArray[$VigenciaElementos[$iVigenciaPos]][DataTermino]."<BR>";

									$DataInicioVigenciaTemp = dataConv($VigenciaElementos[$iVigenciaPos], "Y-m-d", "Ymd");
									$DataFinalVigenciaTemp	= dataConv($VigenciaArray[$VigenciaElementos[$iVigenciaPos]][DataTermino], "Y-m-d", "Ymd");

									if((($DataInicioVigenciaTemp <= $DataTerminoTemp && $DataFinalVigenciaTemp == '') || ($DataInicioVigenciaTemp <= $DataTerminoTemp && $DataInicioVigenciaTemp <= $DataTerminoTemp)) && ($DataInicioTemp <= $DataTerminoTemp)){

#										echo "C---- DataInicioTemp: $DataInicioTemp -> DataTerminoTemp: $DataTerminoTemp<br>";
#										echo "V---- DataInicioVigenciaTemp: $DataInicioVigenciaTemp -> DataFinalVigenciaTemp: $DataFinalVigenciaTemp<br>";

										if($DataInicioTemp > $DataInicioVigenciaTemp){
											$DataInicioCob = $DataInicioTemp;
										}else{
											$DataInicioCob = $DataInicioVigenciaTemp;
										}

										if($DataTerminoTemp < $DataFinalVigenciaTemp || $DataFinalVigenciaTemp == ''){	
											$DataTerminoCob = $DataTerminoTemp;
										}else{
											$DataTerminoCob = $DataFinalVigenciaTemp;
										}

#										echo "R---- DataInicioCob: $DataInicioCob -> DataTerminoCob: $DataTerminoCob<br>";

										$nDias = nDiasIntervalo(dataConv($DataInicioCob,"Ymd","Y-m-d"),dataConv($DataTerminoCob,"Ymd","Y-m-d"));
										
#										echo "Qtd dias: $nDias<br>";
								
										// Calcula o Valor do Lançamento
										if($nDias > 0 || $DadosContrato[FaturamentoFracionado] == 2){
											if($QtdDiasMes == $nDias || $DadosContrato[FaturamentoFracionado] == 2){
												$Valor					= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][Valor];
												$ValorRepasseTerceiro	= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][ValorRepasseTerceiro];

												if($VigenciaArray[$VigenciaElementos[$iVigenciaPos]][IdTipoDesconto] == 1){
													$Valor			-= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][ValorDesconto];
												}else{
													$ValorDesconto	+= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][ValorDesconto];
												}
											}else{
												$Valor					+= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][Valor]/$QtdDiasMes*$nDias;
												$ValorRepasseTerceiro	+= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][ValorRepasseTerceiro]/$QtdDiasMes*$nDias;
												
												if($VigenciaArray[$VigenciaElementos[$iVigenciaPos]][IdTipoDesconto] == 1){
													$Valor -= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][ValorDesconto]/$QtdDiasMes*$nDias;
												}else{
													$ValorDesconto	+= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][ValorDesconto]/$QtdDiasMes*$nDias;
												}
											}
										}
										if($LimiteDesconto < $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][LimiteDesconto]){
											$LimiteDesconto = $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][LimiteDesconto];
										}
									}else{
#										echo "NÃO ENTROU<BR>";
									}
								}
								
								$DataInicioTemp  = dataConv($DataInicioTemp,"Ymd","Y-m-d");
								$DataTerminoTemp = dataConv($DataTerminoTemp,"Ymd","Y-m-d");

								// ValorRepasseTerceiro

/*								// Calcula o Valor do Terceiro
								if($linDadosVigencia[ValorRepasseTerceiro] > 0){
									if($QtdDiasMes == $QtdDias){
										$ValorRepasseTerceiro = $linDadosVigencia[ValorRepasseTerceiro];
									}else{
										$ValorRepasseTerceiro = $linDadosVigencia[ValorRepasseTerceiro]/$QtdDiasMes*$QtdDias;
									}
								}*/
				
							}

							$Cobrar[$i][DataInicio]				= dataConv($Cobrar[$i][DataInicio],"Ymd","Y-m-d");
							$Cobrar[$i][DataFinal]			 	= dataConv($Cobrar[$i][DataFinal],"Ymd","Y-m-d");
							$Cobrar[$i][Valor]					= number_format($Valor, 2, '.', '');
							$Cobrar[$i][ValorRepasseTerceiro]	= number_format($ValorRepasseTerceiro, 2, '.', '');
							$Cobrar[$i][ValorDesconto]			= number_format($ValorDesconto, 2, '.', '');
							$Cobrar[$i][LimiteDesconto]			= $LimiteDesconto;
							
							$sqlLancamento = "select
												max(IdLancamentoFinanceiro) IdLancamentoFinanceiro
											from
												LancamentoFinanceiro
											where
												IdLoja=$IdLoja";
			
							$resLancamento = mysql_query($sqlLancamento);
							$linLancamento = mysql_fetch_array($resLancamento);
							
							$IdLancamentoFinanceiro = $linLancamento[IdLancamentoFinanceiro];
							if($IdLancamentoFinanceiro == null){
								$IdLancamentoFinanceiro = 1;
							}else{
								$IdLancamentoFinanceiro++;
							}

#							echo $DadosContrato[IdContrato]."<br>";
							if($Cobrar[$i][Valor] > 0){

								$sqlParametroDemonstrativo = "SELECT
																ParametroDemonstrativo
															FROM
																ContratoParametroDemonstrativo
															WHERE
																IdLoja = $IdLoja AND
																IdContrato = ".$DadosContrato[IdContrato];
								$resParametroDemonstrativo = @mysql_query($sqlParametroDemonstrativo,$con);
								$linParametroDemonstrativo = @mysql_fetch_array($resParametroDemonstrativo);

#								echo "R$ ".$Cobrar[$i][Valor]."<Br>";

								if($Cobrar[$i][LimiteDesconto] == ''){
									$Cobrar[$i][LimiteDesconto] = 0;
								}

								$cont++;
								$sqlLancamento = "INSERT INTO LancamentoFinanceiro SET 
													IdLoja=$IdLoja,
													IdLancamentoFinanceiro=$IdLancamentoFinanceiro,
													IdContrato=".$DadosContrato[IdContrato].",
													Valor='".$Cobrar[$i][Valor]."',
													ValorRepasseTerceiro='".$Cobrar[$i][ValorRepasseTerceiro]."',
													ValorDescontoAConceber='".$Cobrar[$i][ValorDesconto]."',
													LimiteDesconto='".$Cobrar[$i][LimiteDesconto]."',
													DataReferenciaInicial='".$Cobrar[$i][DataInicio]."', 
													DataReferenciaFinal='".$Cobrar[$i][DataFinal]."',
													ParametroDemonstrativo ='$linParametroDemonstrativo[ParametroDemonstrativo]',
													IdProcessoFinanceiro=$IdProcessoFinanceiro,
													IdStatus = 3";
								$local_transaction[$tr_i]	=	mysql_query($sqlLancamento,$con);
								$tr_i++;
							}

							$sqlMaxReferencia = "select
													max(DataReferenciaInicial) MaxReferencia
												from
													LancamentoFinanceiro
												where
													IdLoja = $IdLoja and
													IdProcessoFinanceiro = $IdProcessoFinanceiro and
													DataReferenciaFinal != '' and
													IdContrato = $DadosContrato[IdContrato]";
							$resMaxReferencia = mysql_query($sqlMaxReferencia,$con);
							$linMaxReferencia = mysql_fetch_array($resMaxReferencia);

							$MaxReferencia = $linMaxReferencia[MaxReferencia];

							if($linProcessoFinanceiro[Filtro_TipoLancamento] == '' || $linProcessoFinanceiro[Filtro_TipoLancamento] == '2'){

								$sqlContaEventual = "select
														LancamentoFinanceiro.IdLancamentoFinanceiro
													from
														LancamentoFinanceiro,
														ContaEventualParcela,
														ContaEventual,
														Pessoa
													where
														LancamentoFinanceiro.IdLoja = $IdLoja and
														LancamentoFinanceiro.IdLoja = ContaEventualParcela.IdLoja and
														LancamentoFinanceiro.IdContrato = $DadosContrato[IdContrato] and
														LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual and
														LancamentoFinanceiro.IdContaEventual = ContaEventualParcela.IdContaEventual and
														LancamentoFinanceiro.NumParcelaEventual = ContaEventualParcela.IdContaEventualParcela and
														ContaEventual.IdPessoa = Pessoa.IdPessoa and

														$sqlFiltro_TipoPessoa
											
														LancamentoFinanceiro.IdStatus = 2 and
														concat(substring(ContaEventualParcela.MesReferencia,4,4),'-',substring(ContaEventualParcela.MesReferencia,1,2),'-01') <= '$MaxReferencia'";
								$resContaEventual = mysql_query($sqlContaEventual,$con);
								while($linContaEventual = mysql_fetch_array($resContaEventual)){
										// Altero os Status do lancamento financeiro
										$cont++;
										$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET 
																		IdStatus = 3,
																		IdProcessoFinanceiro=$IdProcessoFinanceiro
																	WHERE 
																		IdLoja=$IdLoja AND 
																		IdLancamentoFinanceiro=$linContaEventual[IdLancamentoFinanceiro]";
										$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
										$tr_i++;
								}
							}

							if($linProcessoFinanceiro[Filtro_TipoLancamento] == '' || $linProcessoFinanceiro[Filtro_TipoLancamento] == '4'){

								$sqlOrdemServico = "select
														LancamentoFinanceiro.IdLancamentoFinanceiro
													from
														LancamentoFinanceiro,
														OrdemServico,
														OrdemServicoParcela
													where
														LancamentoFinanceiro.IdLoja = $IdLoja and
														OrdemServico.IdLoja = OrdemServicoParcela.IdLoja and
														LancamentoFinanceiro.IdLoja = OrdemServicoParcela.IdLoja and
														LancamentoFinanceiro.IdContrato = $DadosContrato[IdContrato] and
														LancamentoFinanceiro.IdOrdemServico = OrdemServicoParcela.IdOrdemServico and
														OrdemServico.IdOrdemServico = OrdemServicoParcela.IdOrdemServico and
														LancamentoFinanceiro.NumParcelaEventual = OrdemServicoParcela.IdOrdemServicoParcela and
														LancamentoFinanceiro.IdStatus = 2 and
														concat(substring(OrdemServicoParcela.MesReferencia,4,4),'-',substring(OrdemServicoParcela.MesReferencia,1,2),'-01') <= '$MaxReferencia' and														
														(DebitoAutorizado(OrdemServico.IdLoja, OrdemServico.IdPessoa, $linProcessoFinanceiro[Filtro_IdLocalCobranca]) = 1 or TipoLocalCobranca(OrdemServico.IdLoja, $linProcessoFinanceiro[Filtro_IdLocalCobranca]) != 3)";
								$resOrdemServico = mysql_query($sqlOrdemServico,$con);
								while($linOrdemServico = @mysql_fetch_array($resOrdemServico)){
									// Altero os Status do lancamento financeiro
									$cont++;
									$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET 
																	IdStatus = 3,
																	IdProcessoFinanceiro=$IdProcessoFinanceiro
																WHERE 
																	IdLoja=$IdLoja AND 
																	IdLancamentoFinanceiro=$linOrdemServico[IdLancamentoFinanceiro]";
									$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
									$tr_i++;
								}
							}
						}
					}
					
					if($contratoSemVigencia > 0){
						$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - $contratoSemVigencia lancamento(s) sem vigencia.\n".$LogProcessamento;
					}
				}
				
				// Ordem de Servico e Todos
				if($linProcessoFinanceiro[Filtro_TipoLancamento] == '' || $linProcessoFinanceiro[Filtro_TipoLancamento] == 4){

					$sqlFiltro_IdPessoa				= "";
					$sqlFiltro_IdContrato			= "";
					$sqlFiltro_IdPaisEstadoCidade	= "";
					$sqlFiltro_IdServico			= "";
					$sqlFiltro_FormaAvisoCobranca	= "";
					$sqlFiltro_IdGrupoPessoa		= "";
					$sqlFiltro_TipoPessoa			= "";
					$sqlFiltro_IdStatusContrato		= "";
					$sqlFiltro_DiaCobranca			= "";
					$sqlFiltro_TipoCobranca			= "";

					$sqlTable_Contrato				= false;

					// Estabelecendo os filtros
					if($linProcessoFinanceiro[Filtro_IdPessoa]!=''){
						$sqlFiltro_IdPessoa = "and OrdemServico.IdPessoa in (".$linProcessoFinanceiro[Filtro_IdPessoa].")";
					}

					if($linProcessoFinanceiro[Filtro_IdContrato]!=''){
						$sqlFiltro_IdContrato = "and OrdemServico.IdContratoFaturamento in (".$linProcessoFinanceiro[Filtro_IdContrato].")";
					}

					if($linProcessoFinanceiro[Filtro_IdServico]!=''){
						$sqlFiltro_IdServico = "and OrdemServico.IdServico in (".$linProcessoFinanceiro[Filtro_IdServico].")";
					}
					
					if($linProcessoFinanceiro[Filtro_IdGrupoPessoa]!=''){
						$sqlFiltro_IdGrupoPessoa = "and Pessoa.IdPessoa in (
														select 
															PessoaGrupoPessoa.IdPessoa
														from 
															PessoaGrupoPessoa
														where
															PessoaGrupoPessoa.IdGrupoPessoa in (".$linProcessoFinanceiro[Filtro_IdGrupoPessoa].")
													)";
					}

					if($linProcessoFinanceiro[Filtro_TipoPessoa]!=''){
						$sqlFiltro_TipoPessoa = "and Pessoa.TipoPessoa = $linProcessoFinanceiro[Filtro_TipoPessoa]";
					}

					if($linProcessoFinanceiro[Filtro_IdStatusContrato]!=''){
						$sqlFiltro_IdStatusContrato = "and (LancamentoFinanceiro.IdContrato is null or (Contrato.IdStatus in ($linProcessoFinanceiro[Filtro_IdStatusContrato])))";
						$sqlTable_Contrato = true;
					}

					if($linProcessoFinanceiro[Filtro_DiaCobranca]!=''){
						$sqlFiltro_DiaCobranca = "and Contrato.DiaCobranca in (".$linProcessoFinanceiro[Filtro_DiaCobranca].")";
						$sqlTable_Contrato = true;
					}
									
					if($linProcessoFinanceiro[Filtro_TipoCobranca]==1){
						$sqlFiltro_TipoCobranca = "Contrato.IdPeriodicidade != 8"; // vai ficar sem o and/or assim mesmo
					}else{					
						$sqlFiltro_TipoCobranca = "Contrato.IdPeriodicidade = 8";
					}

					if($linProcessoFinanceiro[Filtro_IdPaisEstadoCidade]!=''){

						$sqlFiltro_IdPaisEstadoCidade = '';

						$Filtro_IdPaisEstadoCidade = explode('^',$linProcessoFinanceiro[Filtro_IdPaisEstadoCidade]);

						for($i=0; $i < count($Filtro_IdPaisEstadoCidade); $i++){
							$Filtro_IdPaisEstadoCidadeTemp = explode(',',$Filtro_IdPaisEstadoCidade[$i]);

							$Filtro_IdPais		= $Filtro_IdPaisEstadoCidadeTemp[0];
							$Filtro_IdEstado	= $Filtro_IdPaisEstadoCidadeTemp[1];
							$Filtro_IdCidade	= $Filtro_IdPaisEstadoCidadeTemp[2];

							$sqlFiltro_IdPaisEstadoCidade .= "and (PessoaEndereco.IdPais = $Filtro_IdPais and PessoaEndereco.IdEstado = $Filtro_IdEstado and PessoaEndereco.IdCidade = $Filtro_IdCidade)";
						}
					}

					if($linProcessoFinanceiro[Filtro_FormaAvisoCobranca]!=''){
						switch($linProcessoFinanceiro[Filtro_FormaAvisoCobranca]){
							case 1:
								// 'Correios';
								$sqlFiltro_FormaAvisoCobranca = "and Pessoa.Cob_FormaCorreio = 'S'";
								break;
							case 2:
								// 'E-mail';
								$sqlFiltro_FormaAvisoCobranca = "and Pessoa.Cob_FormaEmail = 'S'";
								break;
							case 3:
								// 'Outros';
								$sqlFiltro_FormaAvisoCobranca = "and Pessoa.Cob_FormaOutro = 'S'";
								break;
						}
					}
					
					$sqlOS = "select 
									LancamentoFinanceiro.IdLoja, 
									LancamentoFinanceiro.IdLancamentoFinanceiro 
								from 
									LancamentoFinanceiro,
									OrdemServico 
										left join Contrato on (OrdemServico.IdLoja = Contrato.IdLoja and OrdemServico.IdContrato = Contrato.IdContrato),
									OrdemServicoParcela,
									Pessoa,
									PessoaEndereco
								where 
									LancamentoFinanceiro.IdLoja = $IdLoja and
									LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and
									LancamentoFinanceiro.IdLoja = OrdemServicoParcela.IdLoja and
									LancamentoFinanceiro.IdStatus = 2 and 
									LancamentoFinanceiro.IdOrdemServico != '' and 
									LancamentoFinanceiro.IdOrdemServico = OrdemServicoParcela.IdOrdemServico and 
									OrdemServico.IdOrdemServico = OrdemServicoParcela.IdOrdemServico and 
									LancamentoFinanceiro.NumParcelaEventual = OrdemServicoParcela.IdOrdemServicoParcela and 
									LancamentoFinanceiro.Valor > 0 and
									concat(substring(OrdemServicoParcela.MesReferencia,4,4),substring(OrdemServicoParcela.MesReferencia,1,2)) <= concat(substring('$MesAnoReferencia',4,4),substring('$MesAnoReferencia',1,2)) and
									OrdemServico.IdPessoa = Pessoa.IdPessoa and
									Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
									Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
									(OrdemServico.IdLocalCobranca = $local_Filtro_IdLocalCobranca or OrdemServico.IdLocalCobranca is null) and
									(OrdemServico.IdContrato is null or Contrato.IdLocalCobranca = $local_Filtro_IdLocalCobranca) and
									(OrdemServico.IdContrato is null or $sqlFiltro_TipoCobranca) and
									(DebitoAutorizado(OrdemServico.IdLoja, OrdemServico.IdPessoa, $linProcessoFinanceiro[Filtro_IdLocalCobranca]) = 1 or TipoLocalCobranca(OrdemServico.IdLoja, $linProcessoFinanceiro[Filtro_IdLocalCobranca]) != 3)

									$sqlFiltro_IdPessoa
									$sqlFiltro_IdPaisEstadoCidade
									$sqlFiltro_IdContrato
									$sqlFiltro_IdServico
									$sqlFiltro_FormaAvisoCobranca
									$sqlFiltro_IdGrupoPessoa
									$sqlFiltro_TipoPessoa
									$sqlFiltro_IdStatusContrato
									$sqlFiltro_DiaCobranca";
					$resOS = mysql_query($sqlOS,$con);
					while($linOS = @mysql_fetch_array($resOS)){
						$cont++;
						$sqlLancamento = "update LancamentoFinanceiro set IdProcessoFinanceiro='$IdProcessoFinanceiro', IdStatus='3' where IdLoja='$linOS[IdLoja]' and IdLancamentoFinanceiro='$linOS[IdLancamentoFinanceiro]'";
						$local_transaction[$tr_i]	=	mysql_query($sqlLancamento,$con);
						$tr_i++;
					}
				}

				// ContaEventual e Todos
				if($linProcessoFinanceiro[Filtro_TipoLancamento] == '' || $linProcessoFinanceiro[Filtro_TipoLancamento] == 2){

					$sqlFiltro_IdPessoa				= "";
					$sqlFiltro_IdContrato			= "";
					$sqlFiltro_IdPaisEstadoCidade	= "";
					$sqlFiltro_FormaAvisoCobranca	= "";
					$sqlFiltro_IdGrupoPessoa		= "";
					$sqlFiltro_TipoPessoa			= "";
					$sqlFiltro_IdStatusContrato		= "";
					$sqlFiltro_DiaCobranca			= "";
					$sqlFiltro_TipoCobranca			= "";

					$sqlTable_Contrato				= false;

					// Estabelecendo os filtros
					if($linProcessoFinanceiro[Filtro_IdPessoa]!=''){
						$sqlFiltro_IdPessoa = "and ContaEventual.IdPessoa in (".$linProcessoFinanceiro[Filtro_IdPessoa].")";
					}

					if($linProcessoFinanceiro[Filtro_IdContrato]!=''){
						$sqlFiltro_IdContrato = "and ContaEventual.IdContrato in (".$linProcessoFinanceiro[Filtro_IdContrato].")";
					}					
					
					if($linProcessoFinanceiro[Filtro_IdGrupoPessoa]!=''){
						$sqlFiltro_IdGrupoPessoa = "and Pessoa.IdPessoa in (
														select 
															PessoaGrupoPessoa.IdPessoa
														from 
															PessoaGrupoPessoa
														where
															PessoaGrupoPessoa.IdGrupoPessoa in (".$linProcessoFinanceiro[Filtro_IdGrupoPessoa].")
													)";
					}

					if($linProcessoFinanceiro[Filtro_TipoPessoa]!=''){
						$sqlFiltro_TipoPessoa = "and Pessoa.TipoPessoa = $linProcessoFinanceiro[Filtro_TipoPessoa]";
					}

					if($linProcessoFinanceiro[Filtro_IdStatusContrato]!=''){
						$sqlFiltro_IdStatusContrato = "and (LancamentoFinanceiro.IdContrato is null or (Contrato.IdStatus in ($linProcessoFinanceiro[Filtro_IdStatusContrato])))";
						$sqlTable_Contrato = true;
					}

					if($linProcessoFinanceiro[Filtro_DiaCobranca]!=''){
						$sqlFiltro_DiaCobranca = "and Contrato.DiaCobranca in (".$linProcessoFinanceiro[Filtro_DiaCobranca].")";
						$sqlTable_Contrato = true;
					}
									
					if($linProcessoFinanceiro[Filtro_TipoCobranca]==1){
						$sqlFiltro_TipoCobranca = "Contrato.IdPeriodicidade != 8"; // vai ficar sem end/or memso.
					}else{					
						$sqlFiltro_TipoCobranca = "Contrato.IdPeriodicidade = 8";
					}

					if($linProcessoFinanceiro[Filtro_IdPaisEstadoCidade]!=''){

						$sqlFiltro_IdPaisEstadoCidade = '';

						$Filtro_IdPaisEstadoCidade = explode('^',$linProcessoFinanceiro[Filtro_IdPaisEstadoCidade]);

						for($i=0; $i < count($Filtro_IdPaisEstadoCidade); $i++){
							$Filtro_IdPaisEstadoCidadeTemp = explode(',',$Filtro_IdPaisEstadoCidade[$i]);

							$Filtro_IdPais		= $Filtro_IdPaisEstadoCidadeTemp[0];
							$Filtro_IdEstado	= $Filtro_IdPaisEstadoCidadeTemp[1];
							$Filtro_IdCidade	= $Filtro_IdPaisEstadoCidadeTemp[2];

							$sqlFiltro_IdPaisEstadoCidade .= "and (PessoaEndereco.IdPais = $Filtro_IdPais and PessoaEndereco.IdEstado = $Filtro_IdEstado and PessoaEndereco.IdCidade = $Filtro_IdCidade)";
						}
					}					

					if($linProcessoFinanceiro[Filtro_FormaAvisoCobranca]!=''){
						switch($linProcessoFinanceiro[Filtro_FormaAvisoCobranca]){
							case 1:
								// 'Correios';
								$sqlFiltro_FormaAvisoCobranca = "and Pessoa.Cob_FormaCorreio = 'S'";
								break;
							case 2:
								// 'E-mail';
								$sqlFiltro_FormaAvisoCobranca = "and Pessoa.Cob_FormaEmail = 'S'";
								break;
							case 3:
								// 'Outros';
								$sqlFiltro_FormaAvisoCobranca = "and Pessoa.Cob_FormaOutro = 'S'";
								break;
						}
					}
					
					$sqlEV = "select 
									LancamentoFinanceiro.IdLoja, 
									LancamentoFinanceiro.IdLancamentoFinanceiro 
								from 
									LancamentoFinanceiro,
									ContaEventual
										left join Contrato on (ContaEventual.IdLoja = Contrato.IdLoja and ContaEventual.IdContrato = Contrato.IdContrato),
									ContaEventualParcela,
									Pessoa,
									PessoaEndereco
								where 
									LancamentoFinanceiro.IdLoja = $IdLoja and
									LancamentoFinanceiro.IdLoja = ContaEventual.IdLoja and
									LancamentoFinanceiro.IdLoja = ContaEventualParcela.IdLoja and
									LancamentoFinanceiro.IdStatus = 2 and 
									LancamentoFinanceiro.IdContaEventual != '' and 
									LancamentoFinanceiro.IdContaEventual = ContaEventualParcela.IdContaEventual and 
									ContaEventual.IdContaEventual = ContaEventualParcela.IdContaEventual and 
									LancamentoFinanceiro.NumParcelaEventual = ContaEventualParcela.IdContaEventualParcela and 
									(concat(substring(ContaEventualParcela.MesReferencia,4,4),substring(ContaEventualParcela.MesReferencia,1,2)) <= concat(substring('$MesAnoReferencia',4,4),substring('$MesAnoReferencia',1,2)) or ContaEventualParcela.MesReferencia IS NULL) and
									ContaEventual.IdPessoa = Pessoa.IdPessoa and
									Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
									Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
									(ContaEventual.IdLocalCobranca = $local_Filtro_IdLocalCobranca or ContaEventual.IdLocalCobranca is null) and
									(ContaEventual.IdContrato is null or Contrato.IdLocalCobranca = $local_Filtro_IdLocalCobranca) and
									(ContaEventual.IdContrato is null or $sqlFiltro_TipoCobranca) and
									(DebitoAutorizado(ContaEventual.IdLoja, ContaEventual.IdPessoa, $linProcessoFinanceiro[Filtro_IdLocalCobranca]) = 1 or TipoLocalCobranca(ContaEventual.IdLoja, $linProcessoFinanceiro[Filtro_IdLocalCobranca]) != 3)

									$sqlFiltro_IdPessoa
									$sqlFiltro_IdPaisEstadoCidade
									$sqlFiltro_IdContrato
									$sqlFiltro_FormaAvisoCobranca
									$sqlFiltro_IdGrupoPessoa
									$sqlFiltro_TipoPessoa
									$sqlFiltro_IdStatusContrato
									$sqlFiltro_DiaCobranca";
					$resEV = mysql_query($sqlEV,$con);
					while($linEV = @mysql_fetch_array($resEV)){

						$cont++;
						$sqlLancamento = "update LancamentoFinanceiro set IdProcessoFinanceiro='$IdProcessoFinanceiro', IdStatus='3' where IdLoja='$linEV[IdLoja]' and IdLancamentoFinanceiro='$linEV[IdLancamentoFinanceiro]'";
						$local_transaction[$tr_i]	=	mysql_query($sqlLancamento,$con);
						$tr_i++;
					}
				}
				
				// Máscara de Status
				include('rotinas/processar_processo_financeiro_mascara_status.php');
				
				// Multa e Juros Pendentes Mês Anterior ao Vencimento
				include('rotinas/processar_processo_financeiro_encargos.php');
	
				$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - $cont lancamento(s) processado(s).\n".$LogProcessamento;
	
				$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
											IdStatus=2,
											LoginProcessamento = '$local_Login',
											DataProcessamento = concat(curdate(),' ',curtime()),
											LogProcessamento = concat('$LogProcessamento',LogProcessamento)
										 WHERE 
										  	IdLoja=$IdLoja AND 
											IdProcessoFinanceiro=$IdProcessoFinanceiro";
				$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
				$tr_i++;
		
				for($i=0; $i<$tr_i; $i++){
					if($local_transaction[$i] == false){
						$local_transaction = false;
					}
				}
			
				if($local_transaction == true){
					$sql = "COMMIT;";
					$local_Erro = 51;
				}else{
					$sql = "ROLLBACK;";
					$local_Erro = 50;
				}
				mysql_query($sql,$con);
			}
		}
	}
?>
