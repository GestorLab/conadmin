<?
	$localModulo		=	1;
	$localOperacao		=	6;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Usuarios , Permissao , Grupo Permissao , Grupo Usuario , Alterar Senha , Log Acesso
	$array_operacao = array(  "6", "8", "9",	"33", "7",	"36" ) ;
	
	
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
		<script type = 'text/javascript' src = 'js/usuario_busca_pessoa_aproximada.js'></script>
	</head>
	<body onLoad="ativaNome('Usuários')">
		<? include('filtro_usuario.php'); ?>
	</body>
</html>
