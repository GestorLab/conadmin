<?
	include("../files/conecta.php");

	$sql = "SELECT
				IdLoja,
				IdContratoMigrado
			FROM
				Contrato
			WHERE
				IdContratoMigrado > 0";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		// Muda o Status
		mysql_query("update Contrato set IdStatus = 102 where IdContrato = $lin[IdContratoMigrado] and IdLoja = $lin[IdLoja] and IdStatus != 102",$con);

		// Localiza a ъltima alteraзгo
		$sql2 = "SELECT
					MAX(IdMudancaStatus) IdMudancaStatus
				FROM
					ContratoStatus
				WHERE
					IdLoja = $lin[IdLoja] AND
					IdContrato = $lin[IdContratoMigrado]";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		
		$sql2 = "delete from ContratoStatus where IdContrato = $lin[IdContratoMigrado] and IdLoja = $lin[IdLoja] and IdMudancaStatus = $lin2[IdMudancaStatus]";
		mysql_query($sql2,$con);

		$lin2[IdMudancaStatus]--;

		$sql2 = "update ContratoStatus set IdStatus = 102 where IdContrato = $lin[IdContratoMigrado] and IdLoja = $lin[IdLoja] and IdMudancaStatus = $lin2[IdMudancaStatus] and IdStatus = 1";
		mysql_query($sql2,$con);
			
	}
?>