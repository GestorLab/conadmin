<?
	$localModulo		=	1;
	$localOperacao		=	3;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Proc. Financ., Lanc. Financ., Contas a Receber, Pessoa/Forma Cobranca )
	$array_operacao 	= array(  "3", "18", "17", "29") ;
	
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
		<script type = 'text/javascript' src = 'js/processo_financeiro.js'></script>
	</head>
	<body onLoad="ativaNome('Processo Financeiro')">
		<? include('filtro_processo_financeiro.php'); ?>
	</body>
</html>
