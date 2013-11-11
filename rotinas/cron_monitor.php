<?
/*	// Monitor ON-OFF
	$sql = "SELECT
				MonitorPorta.IdLoja,
				MonitorPorta.IdMonitor,
				MonitorPortaAlarme.IdStatus,
				MonitorPortaAlarme.IdTipoMensagem,
				MonitorPortaAlarme.Mensagem,
				MonitorPortaAlarme.QtdTentativas,
				MonitorPortaAlarme.IntervaloTentativa
			FROM
				MonitorPorta,
				MonitorPortaAlarme
			WHERE
				MonitorPorta.IdStatus = 1 AND
				MonitorPorta.IdLoja = MonitorPortaAlarme.IdLoja AND
				MonitorPorta.IdMonitor = MonitorPortaAlarme.IdMonitor";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		
		$monitor = checkMonitor($lin[IdLoja], $lin[IdMonitor]);

		if($monitor[conectado]){
			// UP
			$Status = 1;
		}else{
			// DOWN
			$Status = 2;
		}

		// Alarmaaaaa
		if($Status == $lin[IdStatus]){

			@mysql_close($con);
			include($Path.'files/conecta.php');

			$sql = "select
						max(IdMonitorLog) IdMonitorLog
					from
						MonitorPortaLog
					where
						IdLoja = $lin[IdLoja]";
			$res2 = mysql_query($sql,$con);
			$lin2 = mysql_fetch_array($res2);

			$lin[IdMonitorLog] += $lin2[IdMonitorLog]+1;

			// Inserчуo
			$sql = "insert into MonitorPortaLog set 
							IdLoja = $lin[IdLoja],
							IdMonitorLog = $lin[IdMonitorLog],
							IdMonitor = $lin[IdMonitor],
							DataCriacao = concat(curdate(),' ',curtime()),
							IdStatus = $Status";
			mysql_query($sql,$con);

			// Verifica a quantidade de tentativas e intervalo

			for($iT = 1; $iT <= $lin[QtdTentativas]; $iT++){

				sleep($lin[IntervaloTentativa]);
				
				$monitor = checkMonitor($lin[IdLoja], $lin[IdMonitor]);

				if($monitor[conectado]){
					// UP
					$StatusTemp = 1;
				}else{
					// DOWN
					$StatusTemp = 2;
				}

				$sql = "select
							max(IdMonitorLog) IdMonitorLog
						from
							MonitorPortaLog
						where
							IdLoja = $lin[IdLoja]";
				$res2 = mysql_query($sql,$con);
				$lin2 = mysql_fetch_array($res2);

				$lin[IdMonitorLog] += $lin2[IdMonitorLog]+1;

				// Inserчуo
				$sql = "insert into MonitorPortaLog set 
								IdLoja = $lin[IdLoja],
								IdMonitorLog = $lin[IdMonitorLog],
								IdMonitor = $lin[IdMonitor],
								Resultado = 'Tentativa $iT',
								DataCriacao = concat(curdate(),' ',curtime()),
								IdStatus = $Status";
				mysql_query($sql,$con);

				if($StatusTemp != $Status){
					// Stop Alarme
					break;
				}
			}

			if($iT > $lin[QtdTentativas]){
				@mysql_close($con);
				include($Path.'files/conecta.php');
				enviarEmailCronMonitor($lin[IdLoja], $lin[IdMonitor], $Status);
			}
		}
	}*/
?>