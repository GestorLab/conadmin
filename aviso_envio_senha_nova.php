<?
	include("files/conecta.php");
	include("files/funcoes.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<script type = 'text/javascript' src = 'js/funcoes.js'></script>
		<script type = 'text/javascript' src = 'js/index.js'></script>
		<script type = 'text/javascript' src = 'js/mensagens.js'></script>
		<script type = 'text/javascript' src = 'js/event.js'></script>
		<link REL="SHORTCUT ICON" HREF="img/estrutura_sistema/favicon.ico">
	</head>
	<body>
		<?
			include("files/cabecalho.php");
		?>
		<div id='sem_permissao'>
			<p id='p1'>Foi enviado um email para você contendo sua nova senha.</p>
			<p id='p2'>Por favor, verifique a sua caixa de email.</p>
		</div>		
	</body>
</html>
