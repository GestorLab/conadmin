<?
	$localModulo		=	1;
	$localOperacao		=	116;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$IdLoja = $_SESSION["IdLoja"];
	
	$IdTipoOrdemServico			= $_POST[filtro_ordem_servico_tipo];
	$IdSubTipoOrdemServico		= $_POST[filtro_ordem_servico_sub_tipo];
	$IdGrupoUsuarioAtendimento	= $_POST[filtro_grupo_usuario_atendimento];		
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
		<style type='text/css'>
			.impress { padding: 0 2px 2px 2px; cursor:pointer; border: 1px #A4A4A4 solid; height: 20px;}
		</style>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media = 'print' />
	</head>
	<body onLoad="ativaNome('<?=dicionario(494)?>')">
		<? include('filtro_ordem_servico_status.php'); ?>
		<br /><br />
		<div style='text-align:center;'><input class='impress' type='button' name='bt_imprimir' value='<?=dicionario(380)?>' class='botao' onClick="javascript:parent.frames['conteudo'].print();"></div>
		<br />
	</body>
</html>