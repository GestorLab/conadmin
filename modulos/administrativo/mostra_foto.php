<?
	$localModulo		=	1;
	$localOperacao		=	46;
	$localSuboperacao	=	"V";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');	
	
	$local_EndFoto	=	$_GET['EndFoto'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title><?=getParametroSistema(4,1)?></title>
	</head>
	<style> 
		body{
			background-repeat:no-repeat;
			background-position:center center;
			margin:auto;
			text-align:center;			
		}
	</style>
	<body>
		<p style='text-align:center:center; width:100%; padding-top:5px'><img src='<?=$local_EndFoto?>'></p>
	</body>
</html>
