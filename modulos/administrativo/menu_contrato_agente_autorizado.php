<?
	$localModulo		=	1;
	$localOperacao		=	122;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Pessoa , Contrato , Lanç. FInanc. , Contas a Receber , Conta Eventual , Email(s) Enviado(s) , Proc. Financeiro , OS, Agente Autorizado
	$array_operacao = array( "1", "2", "18", "17", "31", "27", "3", "26", "122") ;
	
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
		<script type = 'text/javascript' src = 'js/contrato_agente_autorizado.js'></script>
	</head>
	<body onLoad="ativaNome('<?=dicionario(299)?>')">
		<? include('filtro_contrato_agente_autorizado.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
	</body>
</html>
