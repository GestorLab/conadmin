<?
	$sql = "select
				IdLicenca,
				IdLoja,
				Id,
				UserName,
				GroupName,
				priority
			from
				radius.usergroup";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){	
				
		$sql = "insert into	
					radius2.radusergroup 
				set
					IdLicenca	= '$lin[IdLicenca]',
					IdLoja		= $lin[IdLoja],
					id			= $lin[Id],
					UserName    = '$lin[UserName]',
					GroupName   = '$lin[GroupName]',
					priority	= '$lin[priority]'";			
		$transaction[$tr_i]	=	mysql_query($sql,$con);		
		if($transaction[$tr_i] == false){
			echo $sql.mysql_error()."<br><br>";
		}
		$tr_i++;
	}
?>