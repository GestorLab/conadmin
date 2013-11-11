<?
	$localModulo		= 1;
	$localOperacao		= 140;
	$localSuboperacao	= "R";
	$localCadComum		= true;
	
	/* array(Agrupar Contas) */
	$array_operacao = array("140");
	
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
		<script type = 'text/javascript' src = 'js/conta_receber.js'></script>
		<script type = 'text/javascript' src = 'js/agrupar_conta_receber.js'></script>
	</head>
	<body onLoad="ativaNome('Agrupar Contas a Receber')">
		<? include('filtro_agrupar_conta_receber.php'); ?>
	</body>
</html>
