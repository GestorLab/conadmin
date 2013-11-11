<?		
	$sql = "select 
				ValorCodigoInterno 
			from 
				CodigoInterno 
			where 
				IdLoja = '$local_IdLoja' and 
				IdGrupoCodigoInterno = 10000 and 
				IdCodigoInterno = 1";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
	
	$aux = explode("\n",$lin[ValorCodigoInterno]);
	
	$bd[server]	= trim($aux[0]); //Host
	$bd[login]	= trim($aux[1]); //Login
	$bd[senha]	= trim($aux[2]); //Senha
	$bd[banco]	= trim($aux[3]); //DB		
	
	$conRadius = mysql_connect($bd[server],$bd[login],$bd[senha]) or die(mysql_error());
	
	
	$sqlId = "select (max(Id)+1) Id from radius.radippool";
	$resId = mysql_query($sqlId,$conRadius);
	$linId = @mysql_fetch_array($resId);
	
	if($linId[Id] == ""){
		$linId[Id] = 1;
	}
	
	$sql = "INSERT INTO 
				radius.radippool
			SET
				id 				= $linId[Id],
				IdLoja 			= $local_IdLoja,
				pool_name		= '$local_PoolName',
				framedipaddress = '$local_FrameIpAddress',
				nasipaddress	= '$local_NasIpAddress'";
	if(mysql_query($sql,$conRadius)){
		$local_IdRadIdPool = $linId[Id];
	}else{		
		echo mysql_error();
	}
?>