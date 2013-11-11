<?
	include("files/conecta.php");

	$IdLoja			= $_GET['IdLoja'];
	$IdContaReceber = $_GET['IdContaReceber'];

	$sql = "select
				ContaReceber.MD5
			from
				ContaReceber
			where
				ContaReceber.IdLoja = $IdLoja and
				ContaReceber.IdContaReceber = $IdContaReceber";
	$res = @mysql_query($sql,$con);
	if($lin = @mysql_fetch_array($res)){
		header("Location: modulos/administrativo/boleto.php?ContaReceber=$lin[MD5]");
	}
?>