<?
	$localModulo		=	1;
	$localOperacao		=	18;
	$localSuboperacao	=	"R";
	$localCadComum		=	true;
	
	//array( Pessoa , Contrato, Lanc. Financ., Contas a Receber, Conta Eventual, Email Enviados, Proc. FInanc.)
	$array_operacao 	= array(  "1", "2", "18", "17", "31", "27", "3") ;
	
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
		<script type = 'text/javascript' src = 'js/lancamento_financeiro.js'></script>
	</head>
	<body onLoad="ativaNome('Lançamento Financeiro')">
		<? include('filtro_lancamento_financeiro.php'); ?>
		<div id='carregando'>carregando</div>
	</body>
</html>
