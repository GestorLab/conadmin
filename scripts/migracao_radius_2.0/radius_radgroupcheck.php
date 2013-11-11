<?
	$sql = "select
				IdLicenca,
				id,
				GroupName,
				Attribute,
				op,
				Value
			from
				radius.radgroupcheck";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){	
				
		$sql = "insert into	
					radius2.radgroupcheck 
				set
					IdLicenca	= '$lin[IdLicenca]',
					IdLoja		= '$IdLoja',
					id			= '$lin[id]',	
					groupname	= '$lin[GroupName]',
					attribute	= '$lin[Attribute]',
					op			= '$lin[op]',
					value		= '$lin[Value]'";			
		$transaction[$tr_i]	=	mysql_query($sql,$con);		
		if($transaction[$tr_i] == false){
			echo $sql.mysql_error()."<br><br>";
		}
		$tr_i++;
	}
?>