<?
	$localModulo		=	1;
	$localOperacao		=	1;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Pessoa , Contrato , Lan�. FInanc. , Contas a Receber , Conta Eventual , Email(s) Enviado(s) , Proc. Financeiro , OS
	//$operacao = array(  "1", "2", "18",	"17", "31",	"27", "3",	"26" ) ;	
	
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
		<script type='text/javascript' src='js/pessoa_endereco.js'></script>
		<script type='text/javascript' src='js/pessoa_busca_pessoa_aproximada.js'></script>
	</head>
	<body onLoad="ativaNome('<?=dicionario(1054)?>')">
		<? include('filtro_pessoa_endereco.php'); ?>
	</body>
</html>

