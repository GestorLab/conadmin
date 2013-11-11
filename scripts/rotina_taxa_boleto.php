<?
	include ("../files/conecta.php");

	$sql = "select IdPessoa from Contrato where IdServico in (40,41)";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "update Pessoa set Cob_CobrarDespesaBoleto='2' where IdPessoa='$lin[IdPessoa]'";
		mysql_query($sql,$con);

	}
?>