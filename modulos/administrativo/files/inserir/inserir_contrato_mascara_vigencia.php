<?
	$sqlMasc = "select 
					Mes,
					Fator,
					IdTipoDesconto,
					ValorRepasseTerceiro,
					PercentualRepasseTerceiro,
					IdContratoTipoVigencia,
					LimiteDesconto,
					VigenciaDefinitiva 
				from 
					ServicoMascaraVigencia 
				where 
					IdLoja = $local_IdLoja and 
					IdServico = $local_IdServico
				order by 
					Mes asc";
	$resMasc = mysql_query($sqlMasc,$con);
	
	if(@mysql_num_rows($resMasc) > 0){
		$local_DataInicioVigencia = $local_DataPrimeiraCobranca;
		
		while($linMasc = @mysql_fetch_array($resMasc)){
			if($linMasc[IdTipoDesconto] == ''){
				$linMasc[IdTipoDesconto] = 3;
			}
			
			if($linMasc[IdContratoTipoVigencia] == ''){
				$linMasc[IdContratoTipoVigencia] = 'NULL';
			}
			
			$local_ValorDesconto = ($local_ValorServico - ($local_ValorServico * $linMasc[Fator]));
			
			if($linMasc[ValorRepasseTerceiro] != '' || $linMasc[PercentualRepasseTerceiro] != ''){
				$local_ValorRepasse = $linMasc[ValorRepasseTerceiro];
				
				if($linMasc[PercentualRepasseTerceiro] > 0){
					if($linMasc[IdTipoDesconto] == 1){ // ser for desconto concebido o valor do repasse a terceiro não pode ser sobre o valor cheio.
						$local_ValorRepasse = (($local_ValorServico * $linMasc[PercentualRepasseTerceiro]) / 100);
					}else{
						$local_ValorRepasse = ((($local_ValorServico - $local_ValorDesconto) * $linMasc[PercentualRepasseTerceiro]) / 100);
					}
				}
			} else{
				$local_ValorRepasse = $local_ValorRepasseTerceiro;
			}
			
			$dia		= substr($local_DataInicioVigencia,8,2);
			$mes		= substr($local_DataInicioVigencia,5,2);
			$ano		= substr($local_DataInicioVigencia,0,4);
			$diaTermino	= $dia - 1;
			
			if($diaTermino == 0){
				$diaTermino	= ultimoDiaMes($mes,$ano);
				$mesProx = $mes."/".$ano;
			} else{
				$mesProx = incrementaMesReferencia($mes."/".$ano,1);
				$ultimoDiaMes = ultimoDiaMes(substr($mesProx,0,2),substr($mesProx,3,4));
				
				if($diaTermino > $ultimoDiaMes){
					$diaTermino	= $ultimoDiaMes;
				}
			}
			
			$diaTermino = str_pad($diaTermino, 2, "0", STR_PAD_LEFT);
			$local_DataTerminoVigencia = $diaTermino.'/'.$mesProx;
			$local_DataTerminoVigencia = dataConv($local_DataTerminoVigencia,'d/m/Y','Y-m-d');
			
			if($linMasc[IdContratoTipoVigencia] == ''){
				$linMasc[IdContratoTipoVigencia] = getCodigoInterno(3,46);
			}
			
			if($linMasc[IdContratoTipoVigencia] == ''){
				$linMasc[IdContratoTipoVigencia] = 'NULL';
			}
			
			$VigenciaDefinitiva = $linMasc[VigenciaDefinitiva];
			
			if($linMasc[VigenciaDefinitiva] == 1){ 
				$local_DataTerminoVigenciaTemp = 'NULL';
			} else{
				$local_DataTerminoVigenciaTemp = "'".$local_DataTerminoVigencia."'";
			}
			
			$sql = "INSERT INTO ContratoVigencia SET 
						IdLoja					= $local_IdLoja,
						IdContrato				= $local_IdContrato,
						DataInicio				= '$local_DataInicioVigencia', 
						DataTermino				= $local_DataTerminoVigenciaTemp,
						Valor					= '$local_ValorServico', 
						ValorRepasseTerceiro	= '$local_ValorRepasse', 
						ValorDesconto			= '$local_ValorDesconto', 
						IdContratoTipoVigencia	= $linMasc[IdContratoTipoVigencia],
						IdTipoDesconto			= $linMasc[IdTipoDesconto],
						LimiteDesconto			= '$linMasc[LimiteDesconto]',
						DataCriacao				= (concat(curdate(),' ',curtime())),
						LoginCriacao			= '$local_Login';";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			$local_DataInicioVigencia = incrementaData($local_DataTerminoVigencia,1);
		}
		
		$local_IdTipoDesconto			= getCodigoInterno(3,53);
		$local_IdContratoTipoVigencia 	= getCodigoInterno(3,46);
		
		if($local_IdTipoDesconto == ''){
			$local_IdTipoDesconto = 3;
		}
		
		if($local_IdContratoTipoVigencia == ''){
			$local_IdContratoTipoVigencia = 'NULL';
		}
		
		if($VigenciaDefinitiva != 1){
			$sql = "INSERT INTO ContratoVigencia SET 
						IdLoja					= $local_IdLoja,
						IdContrato				= $local_IdContrato,
						DataInicio				= '$local_DataInicioVigencia', 
						DataTermino				= NULL,
						Valor					= '$local_ValorServico', 
						ValorRepasseTerceiro	= '$local_ValorRepasseTerceiro', 
						ValorDesconto			= '0.00', 
						IdContratoTipoVigencia	= $local_IdContratoTipoVigencia,
						IdTipoDesconto			= $local_IdTipoDesconto,
						DataCriacao				= (concat(curdate(),' ',curtime())),
						LoginCriacao			= '$local_Login';";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
		}
	} else{
		$local_IdTipoDesconto			= getCodigoInterno(3,53);
		$local_IdContratoTipoVigencia 	= getCodigoInterno(3,46);
		
		if($local_IdTipoDesconto == ''){
			$local_IdTipoDesconto = 3;
		}
		
		if($local_IdContratoTipoVigencia == ''){
			$local_IdContratoTipoVigencia = 'NULL';
		}
		
		$sql = "INSERT INTO ContratoVigencia SET 
					IdLoja					= $local_IdLoja,
					IdContrato				= $local_IdContrato,
					DataInicio				= '$local_DataPrimeiraCobranca', 
					DataTermino				= NULL,
					Valor					= '$local_ValorServico', 
					ValorRepasseTerceiro	= '$local_ValorRepasseTerceiro', 
					ValorDesconto			= '0.00', 
					IdContratoTipoVigencia	= $local_IdContratoTipoVigencia,
					IdTipoDesconto			= $local_IdTipoDesconto,
					DataCriacao				= (concat(curdate(),' ',curtime())),
					LoginCriacao			= '$local_Login';";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
	}
?>