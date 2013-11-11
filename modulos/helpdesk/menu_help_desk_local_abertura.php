<?
	$localModulo		=	2;
	$localOperacao		=	2;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Agenda )
	$array_operacao 	= array(  "1" ) ;
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('rotinas/verifica.php');	
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
		<script type = 'text/javascript' src = 'js/help_desk.js'></script>
	</head>
	<body onLoad="ativaNomeHelpDesk('Ticket/Local de Abertura')">
		<? include('filtro_help_desk_local_abertura.php'); ?>
		<div id='carregando'>carregando</div>
	</body>
</html>

