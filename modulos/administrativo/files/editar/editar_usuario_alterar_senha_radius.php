<?
	$IdLoja		= $_SESSION["IdLoja"];
	$IdLicenca	= $_SESSION["IdLicenca"];
	$where		= '';
	$ii			= 0;
	
	if($IdLoja != ''){
		$where .= " and IdLoja = $IdLoja";
	}
	
	$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno = 10000 $where and IdCodigoInterno < 20 order by ValorCodigoInterno;";
	$res = @mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
		$aux = explode("\n",$lin[ValorCodigoInterno]);
		
		$bd[server][$ii]	= trim($aux[0]); //Host
		$bd[login][$ii]		= trim($aux[1]); //Login
		$bd[senha][$ii]		= trim($aux[2]); //Senha
		$bd[banco][$ii]		= trim($aux[3]); //DB
		$bd[Id][$ii]		= $lin[IdCodigoInterno];
		
		$ii++;
	}
	
	@mysql_close($con);
	
	if($IdLicenca != ''){
		$where .= " and IdLicenca = '$IdLicenca'";
	}
	
	for($i=0; $i<$ii; $i++){
		$conRadius	= @mysql_connect($bd[server][$i],$bd[login][$i],$bd[senha][$i]);
		@mysql_select_db($bd[banco][$i], $conRadius);
		
		$sqlRadius	= "select 
							usergroup.Id, 
							radgroupreply.GroupName
						from 
							radgroupreply, 
							(select
								usergroup.id AS Id,
								usergroup.IdLicenca,
								usergroup.GroupName
							 from
								usergroup
							 where
								UserName = '$local_Login' and
								Id > 99999 
								$where
							) usergroup
						where 
							radgroupreply.GroupName = usergroup.GroupName and
							radgroupreply.IdLicenca = usergroup.IdLicenca and
							radgroupreply.Attribute = 'Mikrotik-Group';";
		$resRadius = @mysql_query($sqlRadius, $conRadius);
		while($linRadius = @mysql_fetch_array($resRadius)){
			$sql1Radius	= "UPDATE radcheck SET 
								Value		= '$local_NovaSenha'
							WHERE 
								Id			= '$linRadius[Id]' and 
								Attribute	= 'Password' and
								UserName	= '$local_Login' 
								$where;";
			@mysql_query($sql1Radius, $conRadius);
		}
		
		@mysql_query($sql,$conRadius);
		@mysql_close($conRadius);
	}
?>
