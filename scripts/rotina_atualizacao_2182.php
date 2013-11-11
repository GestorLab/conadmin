<?
	include("../files/conecta.php");

	$exp = "Migrado do Contrato nº";

	$sql = "SELECT
				IdLoja,
				IdContrato,
				Obs
			FROM
				Contrato
			WHERE
				Obs LIKE '%$exp%'";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$Obs = explode($exp,$lin[Obs]);
		$Obs = end($Obs);
		$Obs = explode(".",$Obs);
		$lin[IdContratoMigrado] = trim($Obs[0]);

		$sql = "UPDATE Contrato SET IdContratoMigrado=$lin[IdContratoMigrado] WHERE IdLoja=$lin[IdLoja] AND IdContrato=$lin[IdContrato]";
		mysql_query($sql,$con);
	}
?>