<?php
	if(function_exists('snmpwalkoid')){

		$Data = date("Y-m-d H:i:00");

		$sql = "select 
					distinct
					nasipaddress
				from
					radius.radacct
				where
					callingstationid != '' and
					nasipaddress != ''";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			$mac	= @snmpwalk($lin[nasipaddress], "public", ".1.3.6.1.4.1.14988.1.1.1.2.1.1");
			$sinal	= @snmpwalk($lin[nasipaddress], "public", ".1.3.6.1.4.1.14988.1.1.1.2.1.3");
			$tx		= @snmpwalk($lin[nasipaddress], "public", ".1.3.6.1.4.1.14988.1.1.1.2.1.4");
			$rx		= @snmpwalk($lin[nasipaddress], "public", ".1.3.6.1.4.1.14988.1.1.1.2.1.5");

			//pega sinal dos clientes
			$i = 0;
			foreach ($sinal as $x) {
				$pieces = explode("INTEGER: ", $sinal[$i]);
				$si[$i] = $pieces[1];
				$i++;
			}
			
			//pega pacotes consumidos
			$i = 0;
			foreach ($tx as $x) {
				$txx = explode("Counter32: ", $tx[$i]);
				$txxx[$i] = $txx[1];
				$i++;
			}
			
			//pega pacotes consumidos
			$i = 0;
			foreach ($rx as $x) {
				$rxx = explode("Counter32: ", $rx[$i]);
				$rxxx[$i] = $rxx[1];
				$i++;
			}
			
			//pega mac dos clientes conectados
			$i = 0;
			foreach ($mac as $x) {

				$pieces = explode("Hex-STRING: ", $mac[$i]);
				$macfind = str_replace(" ", ":", $pieces);
				$mcaddr = substr($macfind[1], 0, -1);

				$sqlContrato = "select
									ContratoParametro.IdLoja,
									ContratoParametro.IdContrato
								from
									ContratoParametro,
									ServicoParametro,
									Contrato
								where
									Contrato.IdLoja = ContratoParametro.IdLoja and
									Contrato.IdContrato = ContratoParametro.IdContrato and
									ContratoParametro.IdLoja = ServicoParametro.IdLoja and
									ContratoParametro.IdServico = ServicoParametro.IdServico and
									ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and
									ServicoParametro.IdMascaraCampo = 5 and
									Contrato.IdStatus >= 200 and
									upper(ContratoParametro.Valor) = '$mcaddr'";
				$resContrato = mysql_query($sqlContrato,$con);
				if($linContrato = mysql_fetch_array($resContrato)){
					$sqlSalva = "insert into MonitorMikrotikUsuario set 
										IdLoja		= $linContrato[IdLoja], 
										IdContrato	= $linContrato[IdContrato], 
										Data		= '$Data', 
										dBm			= $si[$i],
										Tx			= $txxx[$i],
										Rx			= $rxxx[$i]";
					mysql_query($sqlSalva,$con);
				}
				$i++;
			}
		}
	}
?>