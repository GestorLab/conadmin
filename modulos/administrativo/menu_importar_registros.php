<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Operacao )
	$array_operacao 	= array(  "58" ) ;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');
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
	</head>
	<body onLoad="ativaNome('Importa��o de Registros')">
		<? include('filtro_importar_registros.php'); ?>
	</body>
</html>
