<?
	include ('files/conecta_cntsistemas.php');
	
	if($conCNT){
		header("Location: helpdesk.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title>ConAdmin - Sistema Administrativo de Qualidade</title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<link REL="SHORTCUT ICON" HREF="img/estrutura_sistema/favicon.ico">
		<meta http-equiv="refresh" content="20">
	</head>
	<body>
		<div id='l1'>
			<div style='width: 166px; float: left; margin-left: 5px; margin-top: 12px;'>
				<a href='index.php'><img src='img/personalizacao/logo_princ.gif' alt='' /></a>
			</div>
			<img id='l1_img2' src='img/estrutura_sistema/logo_sistema.gif' alt='ConAdmin - Sistema Administrativo de Qualidade' />
		</div>
		<div id='l2'>ConAdmin - Sistema Administrativo de Qualidade</div>
		<div id='sem_permissao'>
			<p id='p1'>Sem comunicação com o servidor!</p>
			<p id='p2'>Não foi possível conectar ao servidor de Help Desk.<br>Favor entrar em contato com a CNT Sistemas.</p>
		</div>
	</body>
</html>
