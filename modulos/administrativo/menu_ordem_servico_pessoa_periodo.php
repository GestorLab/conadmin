<?
	$localModulo		=	1;
	$localOperacao		=	157;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( OS, OS/Pessoa/Per�odo)
	$array_operacao = array(  "26", "157") ;	

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
		<script type = 'text/javascript' src = 'js/ordem_servico.js'></script>
		<script type = 'text/javascript' src = 'js/ordem_servico_pessoa_periodo.js'></script>
	</head>
	<body onLoad="ativaNome('<?=dicionario(511)?>')">
		<? include('filtro_ordem_servico_pessoa_periodo.php'); ?>
	</body>
</html>
