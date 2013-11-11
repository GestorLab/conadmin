<?
	$sql = "select
				IdLicenca,
				id,
				pool_name,
				FramedIPAddress,
				NASIPAddress,
				CalledStationId,
				CallingStationID,
				expiry_time,
				username,
				pool_key
			from
				radius.radippool";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){	
				
		$sql = "insert into	
					radius2.radippool 
				set
					IdLicenca			= '$lin[IdLicenca]',
					IdLoja				= '$IdLoja',
					id					= '$lin[id]',
					pool_name			= '$lin[pool_name]',
					framedipaddress		= '$lin[FramedIPAddress]',
					nasipaddress		= '$lin[NASIPAddress]',
					calledstationid		= '$lin[CalledStationId]',
					callingstationid	= '$lin[CallingStationID]',
					expiry_time			= '$lin[expiry_time]',
					username			= '$lin[username]',
					pool_key			= '$lin[pool_key]'";			
		$transaction[$tr_i]	=	mysql_query($sql,$con);		
		if($transaction[$tr_i] == false){
			echo $sql.mysql_error()."<br><br>";
		}
		$tr_i++;
	}
?>