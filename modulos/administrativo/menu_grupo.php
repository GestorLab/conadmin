<?
	$localModulo		=	1;
	$localOperacao		=	12;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Grupo Pessoa , Grupo Servico, Grupo Produto, SubGrupo Produto, Tipo OS, SubTipo OS )
	$array_operacao 	= array(  "12", "19", "48", "49", "82", "83") ;
	
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
	</head>
	<body onLoad="ativaNome('Grupo Pessoa')">
		<? include('filtro_grupo_pessoa.php'); ?>
	</body>
</html>
