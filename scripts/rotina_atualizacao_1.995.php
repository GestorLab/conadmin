<?
	include ("../files/conecta.php");
	include ("../files/criptografo.php");

	$sql = "select
				Login,
				Password
			from
				Usuario";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		if(strlen($lin[Password]) != 32){

			$lin[Password] = md5(dcript($lin[Password]));

			$sql = "update Usuario set Password='$lin[Password]' where Login='$lin[Login]'";
			mysql_query($sql,$con);
			
			echo "COMMIT<BR>";
		}
	}
?>