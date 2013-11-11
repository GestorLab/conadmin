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
			<p id='p1'>Você está prestes a acessar uma área restrita a pessoas<br/>que conheçam o sistema o ConAdmin a fundo.</p>
			<p id='p2'>Para continuar, clique em avançar.</p>
		</div>
		<BR>
		<div id='sem_permissao'>
			<p id='p1'>Você acabou de alterar o valor do serviço.</p>
			<p id='p2'>Deseja atualizar automaticamente o valor de todos os contratos?</p>
		</div>
	</body>
</html>
