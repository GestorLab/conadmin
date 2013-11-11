<?
	include("../files/conecta.php");
	include("../files/funcoes.php");

	$pastaApagar = getParametroSistema(6,1)."backup";

	system("rm -r $pastaApagar");
?>