<?
	$sql = "select
				id,
				user,
				pass,
				reply,
				date
			from
				radius.radpostauth";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){	
				
		$sql = "insert into	
					radius2.radpostauth 
				set
					id			= '$lin[id]',
					username	= '$lin[user]',
					pass		= '$lin[pass]',
					reply		= '$lin[reply]',
					authdate	= '$lin[date]'";			
		$transaction[$tr_i]	=	mysql_query($sql,$con);		
		if($transaction[$tr_i] == false){
			echo $sql.mysql_error()."<br><br>";
		}
		$tr_i++;
	}
?>