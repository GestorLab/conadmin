<?
	include('../files/conecta.php');
	include('../files/funcoes.php');

	$pathSistema = getParametroSistema(6,1);
	$pathPHP = getParametroSistema(6,4);
	system("$pathPHP $pathSistema/rotinas/count.php > $pathSistema/rotinas/count.log &");
	
	echo getParametroSistema(115,1);

	mysql_close($con);
?>
