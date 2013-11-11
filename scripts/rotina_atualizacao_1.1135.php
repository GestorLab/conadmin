<?
	set_time_limit(0);

	include("../files/conecta.php");

	$sqlServidores = "select
							ValorCodigoInterno
						from
							CodigoInterno
						where
							IdGrupoCodigoInterno = 10000 and
							ValorCodigoInterno != ''";
	$resServidores = mysql_query($sqlServidores,$con);
	while($linServidores = mysql_fetch_array($resServidores)){

		$Servidor = explode("\n",$linServidores[ValorCodigoInterno]);

		# [0] local
		# [1] usr
		# [2] pass
		# [3] bd

		# Dados de Conexão com o Banco de dados
		$con_radius	=	@mysql_connect(trim($Servidor[0]),trim($Servidor[1]),trim($Servidor[2]));
		@mysql_select_db(trim($Servidor[3]),$con_radius);

		# Renomear a tabela radacct > radacctJornal
		mysql_query("rename table radacct to radacctJornal",$con_radius);

		# Cria a tabela radacct
		$res = mysql_query("SHOW CREATE TABLE radacctJornal",$con_radius);
		$lin = mysql_fetch_row($res);
		$Struct = $lin[1];
		$Struct = str_replace("radacctJornal", "radacct", $Struct);
		$Struct = str_replace("MyISAM", "InnoDB", $Struct);
		mysql_query($Struct,$con_radius);

		# Pega os registros não concluídos e devolve para a tabela antiga...
		$sql = "select * from radacctJornal where AcctStopTime is NULL";
		$res = @mysql_query($sql,$con_radius);
		$row = mysql_num_rows($res);
		$lin = @mysql_fetch_array($res);

		if($row > 0){

			$tr_i = 0;

			while($lin = @mysql_fetch_array($res)){

				// Copiar registro
				$sqlInsert = "insert into radacct set 
								RadAcctId = '$lin[RadAcctId]', 
								AcctSessionId = '$lin[AcctSessionId]', 
								AcctUniqueId = '$lin[AcctUniqueId]', 
								UserName = '$lin[UserName]', 
								Realm = '$lin[Realm]', 
								NASIPAddress = '$lin[NASIPAddress]', 
								NASPortId = '$lin[NASPortId]', 
								NASPortType = '$lin[NASPortType]', 
								AcctStartTime = '$lin[AcctStartTime]', 
								AcctSessionTime = '$lin[AcctSessionTime]', 
								AcctAuthentic = '$lin[AcctAuthentic]', 
								ConnectInfo_start = '$lin[ConnectInfo_start]', 
								ConnectInfo_stop = '$lin[ConnectInfo_stop]', 
								AcctInputOctets = '$lin[AcctInputOctets]', 
								AcctOutputOctets = '$lin[AcctOutputOctets]', 
								CalledStationId = '$lin[CalledStationId]', 
								CallingStationId = '$lin[CallingStationId]', 
								AcctTerminateCause = '$lin[AcctTerminateCause]', 
								ServiceType = '$lin[ServiceType]', 
								FramedProtocol = '$lin[FramedProtocol]', 
								FramedIPAddress = '$lin[FramedIPAddress]', 
								AcctStartDelay = '$lin[AcctStartDelay]', 
								AcctStopDelay = '$lin[AcctStopDelay]', 
								XAscendSessionSvrKey = '$lin[XAscendSessionSvrKey]'";
				$local_transaction[$tr_i]	=	mysql_query($sqlInsert,$con_radius);

				// Apagar registro anterior
				if($local_transaction[$tr_i] == true){
					$sqlDelete = "delete from radacctJornal where RadAcctId = '$lin[RadAcctId]'";
					mysql_query($sqlDelete,$con_radius);
				}

				$tr_i++;
			}
		}
		@mysql_close($con_radius);
	}
?>
OK