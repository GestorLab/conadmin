<?
	$localModulo		=	1;
	$localOperacao		=	10001;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
			
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/radius_usuario_periodo.js'></script>
	</head>
	<body onLoad="ativaNome('Radius/Usu�rios/Per�odo')">
		<? include('filtro_radius_usuario_periodo.php'); ?>
	</body>
</html>
