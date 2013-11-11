<?
	$sql = "select
				id,
				nasname,
				shortname,
				type,
				ports,
				secret,
				community,
				description
			from
				radius.nas";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){	
				
		$sql = "insert into	
					radius2.nas 
				set
					id			= '$lin[id]',
					nasname		= '$lin[nasname]',
					shortname	= '$lin[shortname]',
					type		= '$lin[type]',
					ports		= '$lin[ports]',
					secret		= '$lin[secret]',
					server		= '',	
					community	= '$lin[community]',
					description = '$lin[description]'";
		
		$transaction[$tr_i]	=	mysql_query($sql,$con);		
		if($transaction[$tr_i] == false){
			echo $sql.mysql_error()."<br><br>";
		}
		$tr_i++;
	}
?>