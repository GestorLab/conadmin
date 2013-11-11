<?php	
	$Perfil  = logoPerfil();
	$UrlLogo = $Perfil[UrlLogoGIF];
	$Title	 = $Perfil[DescricaoPerfil];
	
	echo "
	<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
	'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
	<html>	
		<head>
			<title>ConAdmin - Sistema Administrativo de Qualidade</title>
			<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
			<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
			<link REL='SHORTCUT ICON' HREF='img/estrutura_sistema/favicon.ico'>
		</head>
		<body>
			<div id='l1'>
				<div style='width: 166px; float: left; margin-left: 5px; margin-top: 10px;'>
					<a href='index.php'><img src='".$UrlLogo."' title='".$Title."' /></a>
				</div>
				<img id='l1_img2' src='img/estrutura_sistema/logo_sistema.gif' title='ConAdmin - Sistema Administrativo de Qualidade' />
			</div>
			<div id='l2'>INFORMATIVO - ".getParametroSistema(4,1)."</div>
			<div id='sem_permissao'>
				<p id='p1'>".$lin[TituloAviso]."</p>
				<p id='p2'>".dataConv($lin[DataCriacao],'Y-m-d','d/m/Y')."</p>
				<p id='p2' style='text-decoration: underline'>".$lin[ResumoAviso]."</p>
				<br>
				<p id='p3'>$lin[Aviso]</p>
			</div>
			<form name='formulario' method='post' action='informativo_cntsistemas.php'>
				<input type='hidden' name='IdAviso' value='".$lin[IdAviso]."'>
				<br>
				<center>
					<input type='submit' name='bt_confirmar' value='Continuar' style='cursor:pointer; width: 80px; height: 25px'>
				<center>
				<br/>
				<br/>&nbsp;
			</form>
		</body>
	</html>";
?>