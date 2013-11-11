<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	if($_GET['Derrubar'] == 1){
		$sql = "update LogAcesso set Fechada = 1 where Fechada = 2;";
		if(!mysql_query($sql,$con)){
			header("Location: menu_derrubar_usuarios_logados.php");
		}
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
	</head>
	<body onLoad="ativaNome('Derrubar Todos os Usuários logados')">
		<? include('filtro_operacao.php'); ?>
		<div id='sem_permissao'>
			<p id='p1'>Aguarde enquanto você esta sendo desconectado!</p>
			<p id='p2'>&nbsp;</p>
		</div>
	</body>
</html>
