<?php
	$local_IdLoja 		= $_GET['IdLoja'];
	$local_IdContrato 	= $_GET['IdContrato'];
	
	$comunidade = getCodigoInterno(43,3);

	$sql = "SELECT
				Valor UserName,
				IdServico
			FROM
				ContratoParametro
			WHERE
				IdLoja = $local_IdLoja AND
				IdContrato = $local_IdContrato AND
				IdParametroServico = 1";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$UserName = $lin[UserName];

	$sqlMonitor = "SELECT
						IdConsulta,
						IdParametroServico,
						FiltroContratoParametro
					FROM
						ServicoMonitor
					WHERE
						IdLoja = $local_IdLoja AND
						IdServico = $lin[IdServico] AND
						TipoMonitor = 1 AND
						IdSNMP = 2";
	$resMonitor = mysql_query($sqlMonitor,$con);
	$linMonitor = mysql_fetch_array($resMonitor);

	$sql = "SELECT
				nasipaddress,
				callingstationid,
				framedipaddress
			FROM
				radius.radacct
			WHERE
				username='$lin[UserName]'
			ORDER BY
				radacctid DESC
			LIMIT 0,1";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	switch($linMonitor[IdConsulta]){
		case 1:
			// NAS do Radius
			$ipConsulta = $lin[nasipaddress];
			break;
		case 2:
			// IP do Cliente
			$ipConsulta = $lin[framedipaddress];
			break;
		case 4:
			// Parametro do Contrato
			$sqlParametro = "SELECT
								ContratoParametro.Valor,
								ServicoParametro.Calculavel,
								ServicoParametro.RotinaOpcoesContrato
							FROM
								ContratoParametro,
								ServicoParametro
							WHERE
								ContratoParametro.IdLoja = $local_IdLoja AND
								ContratoParametro.IdContrato = $local_IdContrato AND
								ContratoParametro.IdParametroServico = $linMonitor[IdParametroServico] AND
								ContratoParametro.IdLoja = ServicoParametro.IdLoja AND
								ContratoParametro.IdServico = ServicoParametro.IdServico AND
								ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico";
			$resParametro = mysql_query($sqlParametro,$con);
			$linParametro = mysql_fetch_array($resParametro);

			$rotina = $linParametro[RotinaOpcoesContrato];
			
			// Se tiver quer ser calculado por CI ou PS
			if(substr($rotina,0,2) == "$[" && $linMonitor[FiltroContratoParametro] != ''){
				$rotinaOpcoes = substr($rotina,0,strpos($rotina,")")+1)."]";
				$separador = substr($rotina,strpos($rotina,")")+1,1);
				$fitroOpcaoContrato = end(explode($separador,$rotina));
				$fitroOpcaoContrato = substr($fitroOpcaoContrato,0,strpos($fitroOpcaoContrato,"]"));
				
				$Opcoes = opcoesServicoParametro($rotinaOpcoes);

				for($i=0; $i<count($Opcoes); $i++){
					$SubOpcao = explode($separador,$Opcoes[$i]);
					if($SubOpcao[$fitroOpcaoContrato-1] == $linParametro[Valor]){
						$fitroOpcao = substr($linMonitor[FiltroContratoParametro],1,strlen($linMonitor[FiltroContratoParametro]));
						$ipConsulta = $SubOpcao[$fitroOpcao-1];
						break;
					}
				}
			}else{
				$ipConsulta = $linParametro[Valor];
			}
			break;
	}

	$sinal = @snmpwalk($ipConsulta, $comunidade, ".1.3.6.1.4.1.14988.1.1.1.2.1.3.".StrToObjectSNMP($lin[callingstationid], ":"));
	if($sinal){
		$sinal = end(explode("INTEGER:",$sinal[0]));
	}

	if(!$sinal){
		$sinal = 0;
	}
?>