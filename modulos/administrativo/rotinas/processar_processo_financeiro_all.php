<?
	// Todos os tipos
	switch($DadosContrato[TipoContrato]){
		case "1": // Pré-Pago

			$DataBaseContrato				= $DadosContrato[DataBaseCalculo];
			$DataUltimaCobrancaContrato		= $DadosContrato[DataUltimaCobranca];
			$DataPrimeiraCobrancaContrato 	= $DadosContrato[DataPrimeiraCobranca];
			
			if($DataBaseContrato == '' || $DataBaseContrato == '0000-00-00' || dataConv($DataBaseContrato,"Y-m-d","Ymd") < dataConv(incrementaData($DataPrimeiraCobrancaContrato,-1),"Y-m-d","Ymd")){
				$DataBaseContrato = incrementaData($DataPrimeiraCobrancaContrato,-1);
			}

			if($DadosContrato[MesFechado] == 2){
				// Mês Quebrado
				$DiaCobranca				= $DadosContrato[DiaCobranca];

				if($DiaCobranca < 10){
					$DiaCobranca = '0'.$DiaCobranca;
				}

				$DataCobrancaInicio			= incrementaData($DataBaseContrato,1);				
				$MesAnoReferenciaFinalTemp	= incrementaMesReferencia($MesAnoReferencia,2);
				$MesReferenciaFinalTemp		= substr($MesAnoReferenciaFinalTemp,0,2);
				$AnoReferenciaFinalTemp		= substr($MesAnoReferenciaFinalTemp,3,4);

				if($DiaCobranca > ultimoDiaMes($MesReferenciaFinalTemp, $AnoReferenciaFinalTemp)){
					$DiaCobranca = ultimoDiaMes($MesReferenciaFinalTemp, $AnoReferenciaFinalTemp);
				}

				$DataReferenciaFinalTemp	= $DiaCobranca."/".$MesAnoReferenciaFinalTemp;
				$DataReferenciaFinalTemp	= dataConv($DataReferenciaFinalTemp,"d/m/Y","Y-m-d");
				$DataReferenciaFinalTemp	= incrementaData($DataReferenciaFinalTemp,-1);

				$i = 0;
				
				while($DataBaseContrato < $DataReferenciaFinalTemp && ($DataUltimaCobrancaContrato > $DataBaseContrato || $DataUltimaCobrancaContrato == '' || $DataUltimaCobrancaContrato == null) || ($DadosContrato[Fator] > 1 && $i%$DadosContrato[Fator] != 0)){

					$DataCobrancaInicio = incrementaData($DataBaseContrato,1);

					$AnoCobrancaInicio		= substr($DataCobrancaInicio,0,4);
					$MesCobrancaInicio		= substr($DataCobrancaInicio,5,2);
					$MesAnoCobrancaInicio	= $MesCobrancaInicio."/".$AnoCobrancaInicio;

					// "$DadosContrato[Fator] > 1" Adicionado para fazer testes no processo financeiro 19/05/2012 Douglas Maurício
					if($i != 0 || $DadosContrato[Fator] > 1){
						$MesAnoCobrancaInicio	= incrementaMesReferencia($MesAnoCobrancaInicio,1);
					}
					
					$DiaCobrancaFinal		= $DiaCobranca;
					if($DiaCobrancaFinal > ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio)){
						$DiaCobrancaFinal = ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio);
					}
					
					$DataCobrancaFinal	= $DiaCobrancaFinal."/".$MesAnoCobrancaInicio;
					$DataCobrancaFinal	= dataConv($DataCobrancaFinal,"d/m/Y","Y-m-d");
					$DataCobrancaFinal	= incrementaData($DataCobrancaFinal,-1);
					$DataCobrancaFinal	= dataConv($DataCobrancaFinal,"Y-m-d","Ymd");
					
					if($DataCobrancaFinal > dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd") && $DataUltimaCobrancaContrato != ''){
						$DataCobrancaFinal = dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd");
					}

					if(dataConv($DataBaseContrato, 'Y-m-d', 'Ymd') < $DataCobrancaFinal){
						$DataBaseContrato	= dataConv($DataCobrancaFinal,"Ymd","Y-m-d");
					}

					$Cobrar[$i][DataInicio] = $DataCobrancaInicio;
					$Cobrar[$i][DataFinal] 	= $DataBaseContrato;
					$i++;
				}
			}else{
				// Mês Fechado
				$MesAnoReferenciaTemp		= incrementaMesReferencia($MesAnoReferencia,1); // Cobra o mês de referencia atual, pois ele é pré-pago

				$MesReferenciaTemp			= substr($MesAnoReferenciaTemp,0,2); // Obtem-se o Mes de Referencia
				$AnoReferenciaTemp			= substr($MesAnoReferenciaTemp,3,4); // Obtem-se o Ano de Referencia

				$DiaReferenciaFinalTemp		= ultimoDiaMes($MesReferenciaTemp, $AnoReferenciaTemp); // Ultimo dia do mes de referencia
				$DataReferenciaFinalTemp	= $DiaReferenciaFinalTemp."/".$MesAnoReferenciaTemp; // // Ultimo data do mes de referencia
				$DataReferenciaFinalTemp	= dataConv($DataReferenciaFinalTemp,"d/m/Y","Y-m-d");
			
				$i = 0;
				
				while($DataBaseContrato < $DataReferenciaFinalTemp && ($DataUltimaCobrancaContrato > $DataBaseContrato || $DataUltimaCobrancaContrato == '' || $DataUltimaCobrancaContrato == null) || ($DadosContrato[Fator] > 1 && $i%$DadosContrato[Fator] != 0)){

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

			$DataBaseContrato				= $DadosContrato[DataBaseCalculo];
			$DataUltimaCobrancaContrato		= $DadosContrato[DataUltimaCobranca];
			$DataPrimeiraCobrancaContrato 	= $DadosContrato[DataPrimeiraCobranca];
			
			if($DataBaseContrato == '' || $DataBaseContrato == '0000-00-00' || dataConv($DataBaseContrato,"Y-m-d","Ymd") < dataConv(incrementaData($DataPrimeiraCobrancaContrato,-1),"Y-m-d","Ymd")){
				$DataBaseContrato = incrementaData($DataPrimeiraCobrancaContrato,-1);
			}

			if($DadosContrato[MesFechado] == 2){
				// Mês Quebrado
				$DiaCobranca				= $DadosContrato[DiaCobranca];

				if($DiaCobranca < 10){
					$DiaCobranca = '0'.$DiaCobranca;
				}

				$DataCobrancaInicio			= incrementaData($DataBaseContrato,1);				
				$MesAnoReferenciaFinalTemp	= incrementaMesReferencia($MesAnoReferencia,1);
				$MesReferenciaFinalTemp		= substr($MesAnoReferenciaFinalTemp,0,2);
				$AnoReferenciaFinalTemp		= substr($MesAnoReferenciaFinalTemp,3,4);

				if($DiaCobranca > ultimoDiaMes($MesReferenciaFinalTemp, $AnoReferenciaFinalTemp)){
					$DiaCobranca = ultimoDiaMes($MesReferenciaFinalTemp, $AnoReferenciaFinalTemp);
				}

				$DataReferenciaFinalTemp	= $DiaCobranca."/".$MesAnoReferenciaFinalTemp;
				$DataReferenciaFinalTemp	= dataConv($DataReferenciaFinalTemp,"d/m/Y","Y-m-d");
				$DataReferenciaFinalTemp	= incrementaData($DataReferenciaFinalTemp,-1);

				$i = 0;
				
				while($DataBaseContrato < $DataReferenciaFinalTemp && ($DataUltimaCobrancaContrato > $DataBaseContrato || $DataUltimaCobrancaContrato == '' || $DataUltimaCobrancaContrato == null) || ($DadosContrato[Fator] > 1 && $i%$DadosContrato[Fator] != 0)){

					$DataCobrancaInicio = incrementaData($DataBaseContrato,1);

					$AnoCobrancaInicio		= substr($DataCobrancaInicio,0,4);
					$MesCobrancaInicio		= substr($DataCobrancaInicio,5,2);
					$MesAnoCobrancaInicio	= $MesCobrancaInicio."/".$AnoCobrancaInicio;

					if($i != 0){
						$MesAnoCobrancaInicio	= incrementaMesReferencia($MesAnoCobrancaInicio,1);
					}

					$DiaCobrancaFinal		= $DiaCobranca;
					if($DiaCobrancaFinal > ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio)){
						$DiaCobrancaFinal = ultimoDiaMes($MesCobrancaInicio, $AnoCobrancaInicio);
					}
					
					$DataCobrancaFinal	= $DiaCobrancaFinal."/".$MesAnoCobrancaInicio;
					$DataCobrancaFinal	= dataConv($DataCobrancaFinal,"d/m/Y","Y-m-d");
					$DataCobrancaFinal	= incrementaData($DataCobrancaFinal,-1);
					$DataCobrancaFinal	= dataConv($DataCobrancaFinal,"Y-m-d","Ymd");
					
					if($DataCobrancaFinal > dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd") && $DataUltimaCobrancaContrato != ''){
						$DataCobrancaFinal = dataConv($DataUltimaCobrancaContrato,"Y-m-d","Ymd");
					}

					if(dataConv($DataBaseContrato, 'Y-m-d', 'Ymd') < $DataCobrancaFinal){
						$DataBaseContrato	= dataConv($DataCobrancaFinal,"Ymd","Y-m-d");
					}

					$Cobrar[$i][DataInicio] = $DataCobrancaInicio;
					$Cobrar[$i][DataFinal] 	= $DataBaseContrato;
					$i++;
				}
			}else{
				// Mês Fechado
				$MesAnoReferenciaTemp		= $MesAnoReferencia; // Cobra o mês de referencia atual, pois ele é pós-pago

				$MesReferenciaTemp			= substr($MesAnoReferenciaTemp,0,2); // Obtem-se o Mes de Referencia
				$AnoReferenciaTemp			= substr($MesAnoReferenciaTemp,3,4); // Obtem-se o Ano de Referencia

				$DiaReferenciaFinalTemp		= ultimoDiaMes($MesReferenciaTemp, $AnoReferenciaTemp); // Ultimo dia do mes de referencia
				$DataReferenciaFinalTemp	= $DiaReferenciaFinalTemp."/".$MesAnoReferenciaTemp; // // Ultimo data do mes de referencia
				$DataReferenciaFinalTemp	= dataConv($DataReferenciaFinalTemp,"d/m/Y","Y-m-d");
			
				$i = 0;
				
				while($DataBaseContrato < $DataReferenciaFinalTemp && ($DataUltimaCobrancaContrato > $DataBaseContrato || $DataUltimaCobrancaContrato == '' || $DataUltimaCobrancaContrato == null) || ($DadosContrato[Fator] > 1 && $i%$DadosContrato[Fator] != 0)){

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