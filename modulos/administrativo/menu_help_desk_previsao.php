<?
	$localModulo		=	1;
	$localOperacao		=	161;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Previs�o )
	$array_operacao 	= array(  "161"	) ;
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
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
		<script type = 'text/javascript' src = 'js/help_desk.js'></script>
		<script type = 'text/javascript' src = 'js/help_desk_previsao.js'></script>
	</head>
	<body onLoad="ativaNome('Help Desk/Previs�o')">
		<? include('filtro_help_desk_previsao.php'); ?>
		<div id='carregando'>carregando</div>
	</body>
</html>

