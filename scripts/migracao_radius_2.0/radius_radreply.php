<?
	$sql = "select
				IdLicenca,
				IdLoja,
				id,
				UserName,
				Attribute,
				op,
				Value,
				Referencia
			from
				radius.radreply";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){	
				
		$sql = "insert into	
					radius2.radreply 
				set
					IdLicenca	= '$lin[IdLicenca]',
					IdLoja		= '$lin[IdLoja]',
					id			= '$lin[id]',
					username	= '$lin[UserName]',
					attribute	= '$lin[Attribute]',
					op			= '$lin[op]',
					value		= '$lin[Value]',	
					Referencia	= '$lin[Referencia]'";			
		$transaction[$tr_i]	=	mysql_query($sql,$con);		
		if($transaction[$tr_i] == false){
			echo $sql.mysql_error()."<br><br>";
		}
		$tr_i++;
	}
?>