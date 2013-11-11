<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$IdContaReceber	= $_GET['IdContaReceber'];
	
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
		<script type = 'text/javascript' src = 'js/conta_receber.js'></script>
	</head>
	<body onLoad="ativaNome('Boleto Quitado')">
		<div id='sem_permissao'>
			<p id='p1' style='margin-left:15px'>Título Quitado</p>
			<p id='p2' style='margin-left:15px'>Este Título está Quitado. </br>Caso queira imprimir mesmo assim, clique no botão abaixo para continuar.</p>
			</br>
			<input type='button' value='Continuar' style="cursor:pointer" onClick="visualizar(<?=$IdContaReceber?>)" />
		</div>	
	</body>
</html>
<script>
	function visualizar(IdContaReceber){
		abrir_boleto(IdContaReceber);
	}
</script>