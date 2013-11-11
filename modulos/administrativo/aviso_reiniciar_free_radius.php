<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	$array_operacao 	= array("58") ;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');
		
	// Solicita a reinicialização do radius.
	$sql = "UPDATE ParametroSistema SET
				ValorParametroSistema = 1
			WHERE
				IdGrupoParametroSistema = 203 and
				IdParametroSistema = 1";
	@mysql_query($sql,$con);	
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
		<meta http-equiv="refresh" content="59; url=conteudo.php">
	</head>
	<body onLoad="ativaNome('FreeRadius')">
		<? include('filtro_operacao.php'); ?>
		<div id='sem_permissao'>
			<p id='p1'>Aguarde 60 segundos para conclusão.</p>
			<p id='p1'>&nbsp;</p>
		</div>		
	</body>
</html>
