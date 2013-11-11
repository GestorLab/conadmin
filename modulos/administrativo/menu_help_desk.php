<?
	$localModulo		=	1;
	$localOperacao		=	65;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;	
	
	//array( Suporte Chamado )
	$array_operacao 	= array(  "65"	) ;
	
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
		<script type = 'text/javascript' src = 'js/help_desk_busca_pessoa_aproximada.js'></script>
	</head>
	<body onLoad="ativaNome('Help Desk')">
		<? include('filtro_help_desk.php'); ?>
		<div id='carregando'>carregando</div>
	</body>
</html>

