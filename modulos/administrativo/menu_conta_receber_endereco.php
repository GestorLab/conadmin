<?
	$localModulo		=	1;
	$localOperacao		=	189;
	$localSuboperacao	=	"R";
	$localCadComum		=	true;
	
	//array( conta receber, conta receber excel, conta receber cidade, conta receber movimentacao, conta receber obs, conta receber venc, conta receber local recebimento, faturamento, recebimento anual, recebimneto mensal,endereco
	$array_operacao = array(  "17", "77", "78", "69", "47", "79", "80", "59", "60", "61", "92","189");	
	
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
		<script type = 'text/javascript' src = 'js/conta_receber_endereco.js'></script>
		<script type = 'text/javascript' src = 'js/conta_receber_endereco_busca_pessoa_aproximada.js'></script>
	</head>
	<body onLoad="ativaNome('Contas a Receber/Endere�o')">
		<div id='carregando'>carregando</div>
		<? include('filtro_conta_receber_endereco.php'); ?>
	</body>
</html>
