<?
	$localModulo		=	1;
	$localOperacao		=	146;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;	
	
	//array( Usuário Atendimento )
	$array_operacao 	= array("146") ;
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');	
	
	$localIdLoja		= $_SESSION["IdLoja"];
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
		<script type = 'text/javascript' src = 'js/help_desk_usuario_atendimento.js'></script>
		<script type = 'text/javascript' src = 'js/help_desk.js'></script>
	</head>
	<body onLoad="ativaNome('Help Desk/Usuário Atendimento')">
		<? include('filtro_help_desk_usuario_atendimento.php'); ?>
		<div id='carregando'>carregando</div>
	</body>
</html>

