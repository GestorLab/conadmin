<?
	$localModulo		=	1;
	$localOperacao		=	72;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localIdLoja								= $_SESSION['IdLoja'];	
	$localFiltro								= $_POST['Filtro'];
	$localDescricaoServico						= $_POST['DescricaoServico'];
	$localIdServico								= $_POST['IdServico'];
	$filtro_somar_contrato_cancelado 			= $_POST['filtro_somar_contrato_cancelado'];
	$filtro_somar_contrato_cancelado_migrado 	= $_POST['filtro_somar_contrato_cancelado_migrado'];
	if($localFiltro == ""){
		$localFiltro	=	getCodigoInterno(3,77);
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
		<script type = 'text/javascript' src = 'js/contrato_crescimento_anual.js'></script>
		<style type='text/css'>
			.impress { padding: 0 2px 2px 2px; cursor:pointer; border: 1px #A4A4A4 solid; height: 20px;}
		</style>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media = 'print' />
	</head>
	<body onLoad="ativaNome('<?=dicionario(379)?>')">
		<div id='carregando'><?=dicionario(17)?></div>
		<? include('filtro_contrato_crescimento_anual.php'); ?>
		<br /><br />
		<div style='text-align:center;'><input class='impress' type='button' name='bt_imprimir' value='<?=dicionario(380)?>' class='botao' onClick="javascript:parent.frames['conteudo'].print();"></div>
		<br />
	</body>
</html>