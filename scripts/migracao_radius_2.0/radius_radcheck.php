<?
	$sql = "select
				IdLicenca,
				IdLoja,
				id,
				UserName,
				Attribute,
				op,
				Value,
				Status,
				Referencia
			from
				radius.radcheck";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){	
				
		$sql = "insert into	
					radius2.radcheck 
				set
					IdLicenca	= '$lin[IdLicenca]',
					IdLoja		= '$lin[IdLoja]',
					id			= '$lin[id]',
					username	= '$lin[UserName]',
					attribute	= '$lin[Attribute]',
					op			= '$lin[op]',
					value		= '$lin[Value]',
					Status		= '$lin[Status]',
					Referencia  = '$lin[Referencia]'";		
		$transaction[$tr_i]	=	mysql_query($sql,$con);		
		if($transaction[$tr_i] == false){
			echo $sql.mysql_error()."<br><br>";
			break;
		}
		$tr_i++;
	}
?>