<?
	$localModulo	=	1;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$IdVersao	= versao();
	$IdVersao	= $IdVersao[IdVersao];
	$IdLicenca	= $_SESSION[IdLicenca];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/data.js'></script>
		<script type = 'text/javascript' src = 'js/conteudo.js'></script>
	</head>
	<body  onLoad="ativaNome('Novidades')">
		<div class='tit'>�ltimas Not�cias</div>
		<?				
			include("conteudo_quadro_news.php"); // Visualiza os quadro News
		?>
		<div class='tit'>Atualiza��es Dispon�veis</div>
		<?				
			include("conteudo_quadro_atualizacoes.php"); // Visualiza os quadro atualiza��es
		?>
		<div class='tit'>Change Log - �ltima Atualiza��o</div>
		<?				
			include("conteudo_quadro_change_log_ultima.php"); // Visualiza os quadro atualiza��es
		?>
		<br />
	</body>
</html>