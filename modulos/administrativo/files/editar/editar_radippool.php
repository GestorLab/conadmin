<?	

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		$local_Erro = 2;
	} else{
		$sql = "SELECT 
					ValorCodigoInterno 
				FROM
					CodigoInterno 
				WHERE 
					IdLoja = $local_IdLoja 
					AND IdCodigoInterno = 1 
					AND IdGrupoCodigoInterno = 10000 ";	
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$aux = explode("\n",$lin[ValorCodigoInterno]);
		
		$bd[server]	= trim($aux[0]); //Host
		$bd[login]	= trim($aux[1]); //Login
		$bd[senha]	= trim($aux[2]); //Senha
		$bd[banco]	= trim($aux[3]); //DB
		
		$conRadius = mysql_connect($bd[server],$bd[login],$bd[senha]);
	
		
		$sqlRadius = "UPDATE radius.radippool SET
							pool_name = '$local_PoolName',
							framedipaddress = '$local_FrameIpAddress',
							nasipaddress = '$local_NasIpAddress'
						WHERE
							IdLoja = $local_IdLoja
							AND id = $local_IdRadIdPool";
		if(mysql_query($sqlRadius,$conRadius) == true){
			$local_Erro = 4;
		} else{
			$local_Erro = 5;
		}
	}
?>