<?
	$localModulo	=	1;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_conadmin.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
	</head>
	<body onLoad="ativaNome('Atenção !')">
		<div id='sem_permissao'>
			<p id='p1'>Atenção!</p>
			<p id='p2'>
				O arquivo para a operação solicitada não pode ser encontrado!
				<br /> 
				Contacte o suporte.
			</p>
		</div>
	</body>
</html>