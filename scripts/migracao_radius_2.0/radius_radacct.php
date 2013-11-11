<?	
	$sql = "select
				RadAcctId,
				AcctSessionId,
				AcctUniqueId,
				UserName,
				Realm,
				NASIPAddress,
				NASPortId,
				NASPortType,
				AcctStartTime,
				AcctStopTime,
				AcctSessionTime,
				AcctAuthentic,
				ConnectInfo_start,
				ConnectInfo_stop,
				AcctInputOctets,
				AcctOutputOctets,
				CalledStationId,
				CallingStationId,
				AcctTerminateCause,
				ServiceType,
				FramedProtocol,
				FramedIPAddress,
				AcctStartDelay,
				AcctStopDelay,
				XAscendSessionSvrKey
			from
				radius.radacct";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){	
				
		$sql = "insert into	
					radius2.radacct 
				set
					radacctid				= '$lin[RadAcctId]',
					acctsessionid			= '$lin[AcctSessionId]',
					acctuniqueid			= '$lin[AcctUniqueId]',
					username				= '$lin[UserName]',
					groupname				= '',
					realm					= '$lin[Realm]',
					nasipaddress			= '$lin[NASIPAddress]',
					nasportid				= '$lin[NASPortId]',
					nasporttype				= '$lin[NASPortType]',
					acctstarttime			= '$lin[AcctStartTime]',
					acctstoptime			= '$lin[AcctStopTime]',
					acctsessiontime			= '$lin[AcctSessionTime]',
					acctauthentic			= '$lin[AcctAuthentic]',
					connectinfo_start		= '$lin[ConnectInfo_start]',
					connectinfo_stop		= '$lin[ConnectInfo_stop]',
					acctinputoctets			= '$lin[AcctInputOctets]',
					acctoutputoctets		= '$lin[AcctOutputOctets]',
					calledstationid			= '$lin[CalledStationId]',
					callingstationid		= '$lin[CallingStationId]',
					acctterminatecause		= '$lin[AcctTerminateCause]',
					servicetype				= '$lin[ServiceType]',
					framedprotocol			= '$lin[FramedProtocol]',
					framedipaddress			= '$lin[FramedIPAddress]',
					acctstartdelay			= '$lin[AcctStartDelay]',
					acctstopdelay			= '$lin[AcctStopDelay]',
					xascendsessionsvrkey	= '$lin[XAscendSessionSvrKey]'";
		
		$transaction[$tr_i]	=	mysql_query($sql,$con);		
		if($transaction[$tr_i] == false){
			echo $sql.mysql_error()."<br><br>";
		}
		$tr_i++;
	}	
?>