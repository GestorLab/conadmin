<?php
	set_time_limit(0);
	include('../files/conecta.php');
	include('../files/funcoes.php');

	$sql = "START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;
	
	$sql = "select
				Contrato.IdLoja,
				Contrato.IdContrato,
				Contrato.IdServico,
				Contrato.DataBaseCalculo,
				case when ContratoVigenciaAtiva.DataInicio > Contrato.DataBaseCalculo then 
					date_add(date_add(ContratoVigenciaAtiva.DataInicio,interval 1 month),interval -1 day)
				else 
					Contrato.DataBaseCalculo
				end DataTerminoVigencia,
				ContratoVigenciaAtiva.IdLoja,
				ContratoVigenciaAtiva.IdContrato, 
				ContratoVigenciaAtiva.IdTipoDesconto, 
				ContratoVigenciaAtiva.IdContratoTipoVigencia,
				ContratoVigenciaAtiva.ValorDesconto, 
				ContratoVigenciaAtiva.DataInicio,
				ContratoVigenciaAtiva.DataTermino,
				ContratoVigenciaAtiva.Valor,
				ContratoVigenciaAtiva.ValorRepasseTerceiro,
				ContratoVigenciaAtiva.LimiteDesconto
			from
				Contrato,
				ContratoVigenciaAtiva
			where
				Contrato.IdStatus >= 200 and
				Contrato.DiaCobranca IN (10,15,20) and
				Contrato.IdServico IN (1,2,3,6,9,10) and
				Contrato.DataBaseCalculo <= '2012-07-31' and
				Contrato.IdStatus != 302 and
				Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja and
				Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato";
	$res = mysql_query($sql, $con);
	
	while($lin = mysql_fetch_array($res)){
		switch((int) $lin[IdServico]){
			case 1:
				$lin[ValorDescontoNew] = 9.90;
				break;
			case 2:
				$lin[ValorDescontoNew] = 15.30;
				break;
			case 3:
				$lin[ValorDescontoNew] = 23.30;
				break;
			case 6:
				$lin[ValorDescontoNew] = 8.60;
				break;
			case 9:
				$lin[ValorDescontoNew] = 11.90;
				break;
			case 10:
				$lin[ValorDescontoNew] = 16.60;
		}
		
		$ObsVigencia = '';
		
		if($lin[DataTermino] != $lin[DataTerminoVigencia]){
			$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Data Término de: [".dataConv($lin[DataTermino],'Y-m-d','d/m/Y')." > ".dataConv($lin[DataTerminoVigencia],'Y-m-d','d/m/Y')."]";
		}
		
		$ObsVigencia = trim($ObsVigencia);
		$sqlObsVigencia = "select 
								Obs
							from 
								ContratoVigencia
							where 
								IdLoja = $lin[IdLoja] and 
								IdContrato = $lin[IdContrato] and
								DataInicio = '$lin[DataInicio]'";
		$resObsVigencia = mysql_query($sqlObsVigencia,$con);
		$linObsVigencia = mysql_fetch_array($resObsVigencia);
		
		if($linObsVigencia[Obs] != '' && $ObsVigencia != ''){
			$ObsVigencia .= "\n";
		}
		
		$ObsVigencia .= $linObsVigencia[Obs];
		
		if((int) str_replace("-", "", $lin[DataInicio]) > (int) str_replace("-", "", $lin[DataBaseCalculo])){
			$sql = "UPDATE ContratoVigencia SET 
						ValorDesconto	= $lin[ValorDescontoNew], 
						DataTermino		= '$lin[DataTerminoVigencia]',
						Obs				= '$ObsVigencia',
						DataAlteracao	= (concat(curdate(),' ',curtime())),
						LoginAlteracao	= '$local_Login'
					WHERE 
						IdLoja = $lin[IdLoja] and
						IdContrato = $lin[IdContrato] and
						DataInicio = '$lin[DataInicio]';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		} else{
			$sql = "update ContratoVigencia set 
						DataTermino		= '$lin[DataTerminoVigencia]',
						Obs				= '$ObsVigencia',
						DataAlteracao	= (concat(curdate(),' ',curtime())),
						LoginAlteracao	= '$local_Login'
					where 
						IdLoja = $lin[IdLoja] and
						IdContrato = $lin[IdContrato] and
						DataInicio = '$lin[DataInicio]';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$lin[DataTerminoVigenciaTemp] = $lin[DataTerminoVigencia];
			
			$sqlTemp = "select date_add('".$lin[DataTerminoVigenciaTemp]."',interval 1 month) DataTerminoVigencia";
			$resTemp = mysql_query($sqlTemp, $con);
			$linTemp = mysql_fetch_array($resTemp);
			$lin[DataTerminoVigencia] = $linTemp[DataTerminoVigencia];
			
			$sql = "INSERT INTO ContratoVigencia SET 
						IdLoja					= $lin[IdLoja],
						IdContrato				= $lin[IdContrato], 
						IdTipoDesconto			= 1, 
						IdContratoTipoVigencia	= $lin[IdContratoTipoVigencia],
						ValorDesconto			= $lin[ValorDescontoNew], 
						DataInicio				= date_add('".$lin[DataTerminoVigenciaTemp]."',interval 1 day),
						DataTermino				= '$lin[DataTerminoVigencia]',
						Valor					= $lin[Valor],
						ValorRepasseTerceiro	= $lin[ValorRepasseTerceiro],
						LimiteDesconto			= $lin[LimiteDesconto],
						DataCriacao				= (concat(curdate(),' ',curtime())),
						LoginCriacao			= '$local_Login';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		$sql = "INSERT INTO ContratoVigencia SET 
					IdLoja					= $lin[IdLoja],
					IdContrato				= $lin[IdContrato], 
					IdTipoDesconto			= $lin[IdTipoDesconto], 
					IdContratoTipoVigencia	= $lin[IdContratoTipoVigencia],
					ValorDesconto			= $lin[ValorDesconto], 
					DataInicio				= date_add('".$lin[DataTerminoVigencia]."',interval 1 day),
					DataTermino				= NULL,
					Valor					= $lin[Valor],
					ValorRepasseTerceiro	= $lin[ValorRepasseTerceiro],
					LimiteDesconto			= $lin[LimiteDesconto],
					DataCriacao				= (concat(curdate(),' ',curtime())),
					LoginCriacao			= '$local_Login';";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	}
	
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;				
		}
	}
	
	if($local_transaction == true){
		$sql = "COMMIT;";
	} else{
		$sql = "ROLLBACK;";
	}
	
	echo $sql;		
	$sql = "ROLLBACK;";
	mysql_query($sql,$con);
?>