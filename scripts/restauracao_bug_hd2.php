<?
	include ('../files/conecta.php');

	$IdLicenca	= "2007A002";
	$IdLoja		= 1;

	$sql = "select
				id
			from
				radius.radcheck
			where
				Value = ''";

	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		
		$sql2 = "select
					Value
				from
					radius_original.radcheck
				where
					id = $lin[id]";

		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);

		$sql3 = "update `radius`.`radcheck` set `Value`='$lin2[Value]' where `IdLicenca`='$IdLicenca' and `IdLoja`='$IdLoja' and `id`='$lin[id]'";
		mysql_query($sql3);		
	}
?>