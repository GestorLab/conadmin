<?
	# radius.radacct
	$sqlServidores = "select
							ValorCodigoInterno
						from
							CodigoInterno
						where
							IdGrupoCodigoInterno = 10000 and
							IdCodigoInterno <= 19 and
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

		$sql = "SELECT 
					username 
				FROM 
					radacct
				WHERE 
					processado IS NULL AND
					IdLoja IS NULL AND
					IdContrato IS NULL
				LIMIT 1";
		$res = @mysql_query($sql,$con_radius);
		if($lin = @mysql_fetch_array($res)){
			
			mysql_close($con);
			include($Path.'files/conecta.php');

			$sql = "SELECT
						IdLoja,
						IdContrato
					FROM
						ContratoParametro
					WHERE
						Valor = '$lin[username]' AND
						IdParametroServico = 1
					ORDER BY
						IdLoja,
						IdContrato DESC";
			$res = mysql_query($sql,$con);
			if($lin2 = mysql_fetch_array($res)){
				$sql = "UPDATE radius.radacct SET 
								IdLoja = $lin2[IdLoja],
								IdContrato = $lin2[IdContrato],
								processado = 1
							WHERE
								username = '$lin[username]' AND
								processado IS NULL AND
								IdLoja IS NULL AND
								IdContrato IS NULL";
								
			}else{
				$sql = "UPDATE radius.radacct SET 
								processado = 1
							WHERE
								username = '$lin[username]' AND
								processado IS NULL AND
								IdLoja IS NULL AND
								IdContrato IS NULL";
			}
			
			# Dados de Conexão com o Banco de dados
			$con_radius	=	@mysql_connect(trim($Servidor[0]),trim($Servidor[1]),trim($Servidor[2]));
			@mysql_select_db(trim($Servidor[3]),$con_radius);

			# Executa 
			@mysql_query($sql,$con_radius);

			# Fecha a conexão
			mysql_close($con_radius);
		}
	}
	
	# radius.radacctJornal
	if(date("H") <= 6){
		$sqlServidores = "select
								ValorCodigoInterno
							from
								CodigoInterno
							where
								IdGrupoCodigoInterno = 10000 and
								IdCodigoInterno <= 19 and
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

			$sql = "SELECT 
						username 
					FROM 
						radacctJornal 
					WHERE 
						processado IS NULL AND
						IdLoja IS NULL AND
						IdContrato IS NULL
					LIMIT 1";
			$res = @mysql_query($sql,$con_radius);
			if($lin = @mysql_fetch_array($res)){
				
				mysql_close($con);
				include($Path.'files/conecta.php');

				$sql = "SELECT
							IdLoja,
							IdContrato
						FROM
							ContratoParametro
						WHERE
							Valor = '$lin[username]' AND
							IdParametroServico = 1
						ORDER BY
							IdLoja,
							IdContrato DESC";
				$res = mysql_query($sql,$con);
				if($lin2 = mysql_fetch_array($res)){
					$sql = "UPDATE radius.radacctJornal SET 
									IdLoja = $lin2[IdLoja],
									IdContrato = $lin2[IdContrato],
									processado = 1
								WHERE
									username = '$lin[username]' AND
									processado IS NULL AND
									IdLoja IS NULL AND
									IdContrato IS NULL";
									
				}else{
					$sql = "UPDATE radius.radacctJornal SET 
									processado = 1
								WHERE
									username = '$lin[username]' AND
									processado IS NULL AND
									IdLoja IS NULL AND
									IdContrato IS NULL";
				}
				
				# Dados de Conexão com o Banco de dados
				$con_radius	=	@mysql_connect(trim($Servidor[0]),trim($Servidor[1]),trim($Servidor[2]));
				@mysql_select_db(trim($Servidor[3]),$con_radius);

				# Executa 
				@mysql_query($sql,$con_radius);

				# Fecha a conexão
				mysql_close($con_radius);
			}
		}
	}
?>