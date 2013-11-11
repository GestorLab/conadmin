<?
	// Carne
	switch($DadosContrato[TipoContrato]){
		case "1": // Pré-Pago
		
#			echo "CARNÊ - PRÉ-PAGO<BR>";
			
			$QtdParcela						= $DadosContrato[QtdParcela]; // Quantidade de Parcelas do Carnê
 			$MesAnoReferenciaTemp			= incrementaMesReferencia($MesAnoReferencia,1);
			$DataBaseContrato				= $DadosContrato[DataBaseCalculo];
			$DataUltimaCobrancaContrato		= $DadosContrato[DataUltimaCobranca];
			$DataPrimeiraCobrancaContrato 	= $DadosContrato[DataPrimeiraCobranca];
				
			if($DataBaseContrato == '' || $DataBaseContrato == '0000-00-00' || dataConv($DataBaseContrato,"Y-m-d","Ymd") < dataConv(incrementaData($DataPrimeiraCobrancaContrato,-1),"Y-m-d","Ymd")){
				$DataBaseContrato = incrementaData($DataPrimeiraCobrancaContrato,-1);
			}

			if($DadosContrato[MesFechado] == 2){
				$DiaCobranca = $DadosContrato[DiaCobranca];
				
				if($DiaCobranca < 10){
					$DiaCobranca = "0".$DiaCobranca;
				}
				
				if($DiaCobrancaFinal < 10){
					$DiaCobrancaFinal = "0".$DiaCobrancaFinal;
				}

				$MesReferenciaTemp			= substr($MesAnoReferenciaTemp,0,2); // Obtem-se o Mes de Referencia
				$AnoReferenciaTemp			= substr($MesAnoReferenciaTemp,3,4); // Obtem-se o Ano de Referencia

				$DiaReferenciaFinalTemp		= ultimoDiaMes($MesReferenciaTemp, $AnoReferenciaTemp); // Ultimo dia do mes de referencia
				$DataReferenciaFinalTemp	= $DiaReferenciaFinalTemp."/".$MesAnoReferenciaTemp; // // Ultimo data do mes de referencia
				$DataReferenciaFinalTemp	= dataConv($DataReferenciaFinalTemp,"d/m/Y","Y-m-d");
						
				$i = 0;
				while(($DataBaseContrato < $DataReferenciaFinalTemp && ($DataUltimaCobrancaContrato > $DataBaseContrato || $DataUltimaCobrancaContrato == '' || $DataUltimaCobrancaContrato == null)) || ($i%$QtdParcela != 0)){

					$DataCobrancaInicio = incrementaData($DataBaseContrato,1);

					$AnoCobrancaInicio		= substr($DataCobrancaInicio,0,4);
					$MesCobrancaInicio		= substr($DataCobrancaInicio,5,2);
					$MesAnoCobrancaInicio	= $MesCobrancaInicio."/".$AnoCobrancaInicio; // Mês Referência
					$MesAnoCobrancaInicio	= incrementaMesReferencia($MesAnoCobrancaInicio,1);
					$DiaCobrancaFinal		= $DiaCobranca;
					
					if($DiaCobrancaFinal > ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio)){
						$DiaCobrancaFinal = ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio);
					}

					if(substr($DataCobrancaInicio,8,2) < $DiaCobrancaFinal){
						$MesAnoCobrancaInicio = incrementaMesReferencia($MesAnoCobrancaInicio,-1);
					}
					
					$DataCobrancaFinal	= $DiaCobrancaFinal."/".$MesAnoCobrancaInicio;
					$DataCobrancaFinal	= dataConv($DataCobrancaFinal,"d/m/Y","Y-m-d");
					$DataCobrancaFinal	= incrementaData($DataCobrancaFinal,-1);
					$DataCobrancaFinal	= dataConv($DataCobrancaFinal,"Y-m-d","Ymd");
					
					if($DataCobrancaFinal > dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd") && $DataUltimaCobrancaContrato != ''){
						$DataCobrancaFinal = dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd");
					}
					
					$DataBaseContrato	= dataConv($DataCobrancaFinal,"Ymd","Y-m-d");

					$Cobrar[$i][DataInicio] = $DataCobrancaInicio;
					$Cobrar[$i][DataFinal] 	= $DataBaseContrato;
					$i++;
					
					$MesReferenciaLancamentoAgendados	= incrementaMesReferencia($MesAnoCobrancaInicio,-1);
					$MesReferenciaLancamentoAgendados2 = $MesAnoCobrancaInicio;

					$sqlContaEventual = "select 
											LancamentoFinanceiro.IdLancamentoFinanceiro, 
											LancamentoFinanceiro.NumParcelaEventual, 
											ContaEventualParcela.MesReferencia, 
											substring(ContaEventualParcela.MesReferencia,1,2) MesMesReferencia, 
											substring(ContaEventualParcela.MesReferencia,4,4) AnoMesReferencia,
											concat(substring(ContaEventualParcela.MesReferencia,4,4),substring(ContaEventualParcela.MesReferencia,1,2)) Mes,
											Contrato.TipoContrato
										from 
											LancamentoFinanceiro, 
											ContaEventual, 
											ContaEventualParcela,
											Contrato
										where 
											LancamentoFinanceiro.IdLoja = $IdLoja and 
											LancamentoFinanceiro.IdLoja = ContaEventual.IdLoja and 
											LancamentoFinanceiro.IdLoja = ContaEventualParcela.IdLoja and 
											LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
											LancamentoFinanceiro.IdStatus = 2 and 
											(Contrato.IdContrato = $DadosContrato[IdContrato] or Contrato.IdContratoAgrupador = $DadosContrato[IdContrato]) and 
											LancamentoFinanceiro.IdContrato = Contrato.IdContrato and 
											LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual and 
											ContaEventual.IdContaEventual = ContaEventualParcela.IdContaEventual and 
											LancamentoFinanceiro.NumParcelaEventual = ContaEventualParcela.IdContaEventualParcela and 
											((Contrato.TipoContrato = 1 and
											concat(substring('$MesReferenciaLancamentoAgendados2',4,4),substring('$MesReferenciaLancamentoAgendados2',1,2)) >= concat(substring(ContaEventualParcela.MesReferencia,4,4),substring(ContaEventualParcela.MesReferencia,1,2))) or 
											(Contrato.TipoContrato = 2 and 
											concat(substring('$MesReferenciaLancamentoAgendados',4,4),substring('$MesReferenciaLancamentoAgendados',1,2)) >= concat(substring(ContaEventualParcela.MesReferencia,4,4),substring(ContaEventualParcela.MesReferencia,1,2))))";
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
			}else{
				$MesAnoReferenciaTemp		= $MesAnoReferencia; // Cobra o mês de referencia de 3 meses anterior, pois ele é pós-pago
				$MesReferenciaTemp			= substr($MesAnoReferenciaTemp,0,2); // Obtem-se o Mes de Referencia
				$AnoReferenciaTemp			= substr($MesAnoReferenciaTemp,3,4); // Obtem-se o Ano de Referencia

				$DiaReferenciaFinalTemp		= ultimoDiaMes($MesReferenciaTemp, $AnoReferenciaTemp); // Ultimo dia do mes de referencia

				if($DiaCobrancaFinal < 10){
					$DiaCobrancaFinal = '0'.$DiaCobrancaFinal;
				}

				$DataReferenciaFinalTemp	= $DiaReferenciaFinalTemp."/".$MesAnoReferenciaTemp; // // Ultimo data do mes de referencia
				$DataReferenciaFinalTemp	= dataConv($DataReferenciaFinalTemp,"d/m/Y","Y-m-d");
				
	
				$i = 0;
				while(($DataBaseContrato < $DataReferenciaFinalTemp && ($DataUltimaCobrancaContrato > $DataBaseContrato || $DataUltimaCobrancaContrato == '' || $DataUltimaCobrancaContrato == null)) || ($i%$QtdParcela != 0)){

					$DataCobrancaInicio = incrementaData($DataBaseContrato,1);

					$AnoCobrancaInicio	= substr($DataCobrancaInicio,0,4);
					$MesCobrancaInicio	= substr($DataCobrancaInicio,5,2);
					$DiaCobrancaFinal	= ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio);
					
					$DataCobrancaFinal	= $AnoCobrancaInicio.$MesCobrancaInicio.$DiaCobrancaFinal;
					
					if($DataCobrancaFinal > dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd") && $DataUltimaCobrancaContrato != ''){
						$DataCobrancaFinal = dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd");
					}
					
					$DataBaseContrato	= dataConv($DataCobrancaFinal,"Ymd","Y-m-d");

					$Cobrar[$i][DataInicio] = $DataCobrancaInicio;
					$Cobrar[$i][DataFinal] 	= $DataBaseContrato;
					$i++;
				}
			}
			break;
		case "2": // Pós-Pago
		
#			echo "CARNÊ - PÓS-PAGO<BR>";
			
			$QtdParcela						= $DadosContrato[QtdParcela]; // Quantidade de Parcelas do Carnê
			$DataBaseContrato				= $DadosContrato[DataBaseCalculo];
			$DataUltimaCobrancaContrato		= $DadosContrato[DataUltimaCobranca];
			$DataPrimeiraCobrancaContrato 	= $DadosContrato[DataPrimeiraCobranca];
				
			if($DataBaseContrato == '' || $DataBaseContrato == '0000-00-00' || dataConv($DataBaseContrato,"Y-m-d","Ymd") < dataConv(incrementaData($DataPrimeiraCobrancaContrato,-1),"Y-m-d","Ymd")){
				$DataBaseContrato = incrementaData($DataPrimeiraCobrancaContrato,-1);
			}

			if($DadosContrato[MesFechado] == 2){
				$DiaCobranca = $DadosContrato[DiaCobranca];
				
				if($DiaCobranca < 10){
					$DiaCobranca = "0".$DiaCobranca;
				}
				
				if($DiaCobrancaFinal < 10){
					$DiaCobrancaFinal = "0".$DiaCobrancaFinal;
				}

				$MesAnoReferenciaTemp		= $MesAnoReferencia; // Cobra o mês de referencia de 3 meses anterior, pois ele é pós-pago
				$MesReferenciaTemp			= substr($MesAnoReferenciaTemp,0,2); // Obtem-se o Mes de Referencia
				$AnoReferenciaTemp			= substr($MesAnoReferenciaTemp,3,4); // Obtem-se o Ano de Referencia

				$DiaReferenciaFinalTemp		= ultimoDiaMes($MesReferenciaTemp, $AnoReferenciaTemp); // Ultimo dia do mes de referencia
				$DataReferenciaFinalTemp	= $DiaReferenciaFinalTemp."/".$MesAnoReferenciaTemp; // // Ultimo data do mes de referencia
				$DataReferenciaFinalTemp	= dataConv($DataReferenciaFinalTemp,"d/m/Y","Y-m-d");
								
				$i = 0;
				while(($DataBaseContrato < $DataReferenciaFinalTemp && ($DataUltimaCobrancaContrato > $DataBaseContrato || $DataUltimaCobrancaContrato == '' || $DataUltimaCobrancaContrato == null)) || ($i%$QtdParcela != 0)){

					$DataCobrancaInicio = incrementaData($DataBaseContrato,1);

					$AnoCobrancaInicio		= substr($DataCobrancaInicio,0,4);
					$MesCobrancaInicio		= substr($DataCobrancaInicio,5,2);
					$MesAnoCobrancaInicio	= $MesCobrancaInicio."/".$AnoCobrancaInicio; // Mês Referência
					$MesAnoCobrancaInicio	= incrementaMesReferencia($MesAnoCobrancaInicio,1);
					$DiaCobrancaFinal		= $DiaCobranca;
					
					if($DiaCobrancaFinal > ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio)){
						$DiaCobrancaFinal = ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio);
					}

					if(substr($DataCobrancaInicio,8,2) < $DiaCobrancaFinal){
						$MesAnoCobrancaInicio = incrementaMesReferencia($MesAnoCobrancaInicio,-1);
					}
					
					$DataCobrancaFinal	= $DiaCobrancaFinal."/".$MesAnoCobrancaInicio;
					$DataCobrancaFinal	= dataConv($DataCobrancaFinal,"d/m/Y","Y-m-d");
					$DataCobrancaFinal	= incrementaData($DataCobrancaFinal,-1);
					$DataCobrancaFinal	= dataConv($DataCobrancaFinal,"Y-m-d","Ymd");
					
					if($DataCobrancaFinal > dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd") && $DataUltimaCobrancaContrato != ''){
						$DataCobrancaFinal = dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd");
					}
					
					$DataBaseContrato	= dataConv($DataCobrancaFinal,"Ymd","Y-m-d");

					$Cobrar[$i][DataInicio] = $DataCobrancaInicio;
					$Cobrar[$i][DataFinal] 	= $DataBaseContrato;
					$i++;
					
					$MesReferenciaLancamentoAgendados	= incrementaMesReferencia($MesAnoCobrancaInicio,-1);
					$MesReferenciaLancamentoAgendados2 = $MesAnoCobrancaInicio;

					$sqlContaEventual = "select 
											LancamentoFinanceiro.IdLancamentoFinanceiro, 
											LancamentoFinanceiro.NumParcelaEventual, 
											ContaEventualParcela.MesReferencia, 
											substring(ContaEventualParcela.MesReferencia,1,2) MesMesReferencia, 
											substring(ContaEventualParcela.MesReferencia,4,4) AnoMesReferencia,
											concat(substring(ContaEventualParcela.MesReferencia,4,4),substring(ContaEventualParcela.MesReferencia,1,2)) Mes,
											Contrato.TipoContrato
										from 
											LancamentoFinanceiro, 
											ContaEventual, 
											ContaEventualParcela,
											Contrato
										where 
											LancamentoFinanceiro.IdLoja = $IdLoja and 
											LancamentoFinanceiro.IdLoja = ContaEventual.IdLoja and 
											LancamentoFinanceiro.IdLoja = ContaEventualParcela.IdLoja and 
											LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
											LancamentoFinanceiro.IdStatus = 2 and 
											LancamentoFinanceiro.IdContrato = Contrato.IdContrato and 
											(Contrato.IdContrato = $DadosContrato[IdContrato] or Contrato.IdContratoAgrupador = $DadosContrato[IdContrato]) and 
											LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual and 
											ContaEventual.IdContaEventual = ContaEventualParcela.IdContaEventual and 
											LancamentoFinanceiro.NumParcelaEventual = ContaEventualParcela.IdContaEventualParcela and 
											((Contrato.TipoContrato = 1 and
											concat(substring('$MesReferenciaLancamentoAgendados2',4,4),substring('$MesReferenciaLancamentoAgendados2',1,2)) >= concat(substring(ContaEventualParcela.MesReferencia,4,4),substring(ContaEventualParcela.MesReferencia,1,2))) or 
											(Contrato.TipoContrato = 2 and 
											concat(substring('$MesReferenciaLancamentoAgendados',4,4),substring('$MesReferenciaLancamentoAgendados',1,2)) >= concat(substring(ContaEventualParcela.MesReferencia,4,4),substring(ContaEventualParcela.MesReferencia,1,2))))";
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
			}else{
				$MesAnoReferenciaTemp		= $MesAnoReferencia; // Cobra o mês de referencia de 3 meses anterior, pois ele é pós-pago
				$MesReferenciaTemp			= substr($MesAnoReferenciaTemp,0,2); // Obtem-se o Mes de Referencia
				$AnoReferenciaTemp			= substr($MesAnoReferenciaTemp,3,4); // Obtem-se o Ano de Referencia

				$DiaReferenciaFinalTemp		= ultimoDiaMes($MesReferenciaTemp, $AnoReferenciaTemp); // Ultimo dia do mes de referencia

				if($DiaCobrancaFinal < 10){
					$DiaCobrancaFinal = '0'.$DiaCobrancaFinal;
				}

				$DataReferenciaFinalTemp	= $DiaReferenciaFinalTemp."/".$MesAnoReferenciaTemp; // // Ultimo data do mes de referencia
				$DataReferenciaFinalTemp	= dataConv($DataReferenciaFinalTemp,"d/m/Y","Y-m-d");
				
				$i = 0;
				while(($DataBaseContrato < $DataReferenciaFinalTemp && ($DataUltimaCobrancaContrato > $DataBaseContrato || $DataUltimaCobrancaContrato == '' || $DataUltimaCobrancaContrato == null)) || ($i%$QtdParcela != 0)){

					$DataCobrancaInicio = incrementaData($DataBaseContrato,1);

					$AnoCobrancaInicio	= substr($DataCobrancaInicio,0,4);
					$MesCobrancaInicio	= substr($DataCobrancaInicio,5,2);
					$DiaCobrancaFinal	= ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio);
					
					$DataCobrancaFinal	= $AnoCobrancaInicio.$MesCobrancaInicio.$DiaCobrancaFinal;
					
					if($DataCobrancaFinal > dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd") && $DataUltimaCobrancaContrato != ''){
						$DataCobrancaFinal = dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd");
					}
					
					$DataBaseContrato	= dataConv($DataCobrancaFinal,"Ymd","Y-m-d");

					$Cobrar[$i][DataInicio] = $DataCobrancaInicio;
					$Cobrar[$i][DataFinal] 	= $DataBaseContrato;
					$i++;
				}
			}
			break;
	}
?>