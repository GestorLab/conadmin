<?
	$localModulo	=	0;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
	</head>
	<body>
		<div id='sem_permissao'>
			<p id='p1'>Você não possui permissão para acessar esta área do sistema!</p>
			<p id='p2'>Para acessar, entre em contato com o administrador do sistema, solicitando permissão.</p>
		</div>
	</body>
</html>
