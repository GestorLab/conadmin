<?
	$localModulo		=	1;
	$localOperacao		=	150;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localIdLoja				= $_SESSION["IdLoja"];
	$localMesReferencia			= $_POST['filtro_mes_referencia'];
	$localNome					= $_POST['filtro_nome'];
	$localAssunto				= $_POST['filtro_assunto'];
	$localLocalAbertura			= $_POST['filtro_local_abertura'];
	$localStatus				= $_POST['filtro_status'];
	$localHelpDeskConcluido		= $_SESSION["filtro_help_desk_concluido"];
	$localTipo					= $_POST['filtro_tipo'];
	$localSubTipo				= $_POST['filtro_sub_tipo'];
	$localGrupoAtendimento		= $_POST['filtro_grupo_atendimento'];
	$localUsuarioAtendimento	= $_POST['filtro_usuario_atendimento'];
	$localGrupoAlteracao		= $_POST['filtro_grupo_alteracao'];
	$localUsuarioAlteracao		= $_POST['filtro_usuario_alteracao'];
	
	if($localMesReferencia == ''){
		$localMesReferencia	= date('m/Y');	
	}
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
		<script type = 'text/javascript' src = 'js/help_desk_mes.js'></script>
		<script type = 'text/javascript' src = 'js/help_desk.js'></script>
		<style type='text/css'>
			.impress { padding: 0 2px 2px 2px; cursor:pointer; border: 1px #A4A4A4 solid; height: 20px;}
		</style>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media = 'print' />
	</head>
	<body onLoad="ativaNome('Ticket/Mês')">
		<? include('filtro_help_desk_mes.php'); ?>
		<div id='carregando'>carregando</div>
		<br /><br />
		<div style='text-align:center;'><input class='impress' type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick="javascript:parent.frames['conteudo'].print();"></div>
		<br />
	</body>
</html>