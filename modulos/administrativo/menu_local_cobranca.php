<?
	$localModulo		=	1;
	$localOperacao		=	30;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Local Cobranca , Parametro Contrato, Forma de Pagamento )
	$array_operacao 	= array(  "30", "63", "53") ;
	
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
	<body onLoad="ativaNome('Local de Cobrança')">
		<? include('filtro_local_cobranca.php'); ?>
	</body>
</html>
