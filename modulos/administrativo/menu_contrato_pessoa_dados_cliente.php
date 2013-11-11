<?
	$localModulo		=	1;
	$localOperacao		=	204;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Pessoa , Contrato , Lanç. FInanc. , Contas a Receber , Conta Eventual , Email(s) Enviado(s) , Proc. Financeiro , OS, Contrato/Datas, Contrato/Pessoa (Dados de Cliente)
	$array_operacao = array(  "1", "2", "18",	"17", "31",	"27", "3",	"26", "129","204") ;
	
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
		<script type = 'text/javascript' src = 'js/contrato.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_datas.js'></script>
	</head>
	<body onLoad="ativaNome('<?=dicionario(992)?>')">
		<div id='carregando'><?=dicionario(17)?></div>
		<? include('filtro_contrato_pessoa_dados_cliente.php'); ?>
	</body>
</html>
