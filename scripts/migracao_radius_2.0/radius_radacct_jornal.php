<?	
	$i=0;

	while($qtd > 0 || $i==0){
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
					radius.radacctJornal
				limit $i,1000";
		$res = mysql_query($sql,$con);
		$qtd = mysql_num_rows($res);
		
		$i += 1000;
#		echo $i."\n";

		while($lin = mysql_fetch_array($res)){	
					
			$sql = "insert into	
						radius2.radacctJornal 
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
			$transaction = mysql_query($sql,$con);		
			if($transaction == false){
#				echo $sql.mysql_error();
				break;
				break;
			}
		}
	}
?>