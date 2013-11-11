<?
	$localModulo		=	1;
	$localOperacao		=	99;
	$localSuboperacao	=	"R";
	$localCadComum		=	true;
	
	//array( Pessoa , Contrato, Lanc. Financ., Contas a Receber, Conta Eventual, Email Enviados, Proc. FInanc., Lançamento Financeiro/Tipo Lançamento, Conta Receber Tipo)
	$array_operacao 	= array(  "1", "2", "18", "17", "31", "27", "3", "99") ;
	
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
		<script type = 'text/javascript' src = 'js/conta_receber_tipo.js'></script>
	</head>
	<body onLoad="ativaNome('Conta a Receber/Tipo')">
		<? include('filtro_conta_receber_tipo.php'); ?>
		<div id='carregando'>carregando</div>
	</body>
</html>
