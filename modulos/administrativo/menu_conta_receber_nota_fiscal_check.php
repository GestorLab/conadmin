<?
	$localModulo		=	1;
	$localOperacao		=	112;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	$array_operacao 	= array("112") ;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');

	$local_IdLoja	= $_SESSION["IdLoja"];
	$local_Login	= $_SESSION["Login"];
	$local_IdAviso	= $_GET["IdAviso"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<? include('../../files/head.php'); ?>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<!--script type = 'text/javascript' src = '../../js/funcoes.js'></script-->
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
	</head>
	<body onLoad="ativaNome('Conta Receber / Nota Fiscal')">
		<div id='sem_permissao'>
			<p id='p1'>Não foi possível gerar nota fiscal!</p>
			<p id='p2'><?=getParametroSistema(13,$local_IdAviso)?></p>
		</div>
	</body>
</html>