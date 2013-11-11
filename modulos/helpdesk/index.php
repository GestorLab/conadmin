<?
	$localModulo	=	2;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('rotinas/verifica.php');
	
	$local_login 	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION["IdLoja"];

	$local_url		= $_GET['url'];
	
	if($local_url == ''){
		$local_url = "conteudo.php";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		
		<title><?=getParametroSistema(4,1)?></title>
		<link REL="SHORTCUT ICON" HREF="../../img/estrutura_sistema/favicon.ico">
	</head>
	<frameset rows="60pixes,*" border=0>
		<frame id='cabecalho' name='cabecalho' src="cabecalho.php" frameborder=0 scrolling=auto noresize='noresize' />
		<frameset cols="150pixes,*" border=0>
			<frame name='menu' src="menu.php" frameborder=0 scrolling='auto' noresize='noresize' />
  			<frame name='conteudo' src="<?=$local_url?>" frameborder=0 scrolling='auto' noresize='noresize'/>
		</frameset>
	</frameset>
</html>
