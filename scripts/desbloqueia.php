<?php
	include ("../files/conecta.php");

	$sql = "select 
				IdLicenca,
				IdLoja,
				id 
			from 
				radius.radcheck 
			where 
				IdLicenca = '2007A002' and 
				Attribute = 'User-Name' and 
				Status = 'B'";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sqlAltera = "update radius.radcheck set Status='A' where IdLicenca = '$lin[IdLicenca]' and IdLoja = $lin[IdLoja] and id = $lin[id]";
		mysql_query($sqlAltera,$con);
	}
?>