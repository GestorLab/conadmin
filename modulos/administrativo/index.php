<?
	$localModulo	=	1;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_login 	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION["IdLoja"];

	$local_url		= getURL();	//Ricardo foi essa linha que eu barrei
	
	if($local_url == ''){
		$local_url = "conteudo.php";
	}
	
	include("../../files/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link REL="SHORTCUT ICON" HREF="../../img/estrutura_sistema/favicon.ico">
	</head>
	<frameset rows="60pixes,*" border=0>
		<frame id='cabecalho' name='cabecalho' src="cabecalho.php" frameborder=0 scrolling=auto noresize='noresize' />
		<frameset id='l2' cols="150pixes,*" border=0 id='frameHorizontal'>
			<frame id='menu' name='menu' src="menu.php" frameborder=0 scrolling='auto' noresize='noresize' />
  			<frame id='conteudo' name='conteudo' src="<?=$local_url?>" frameborder=0 scrolling='auto' noresize='noresize'/>
		</frameset>
	</frameset>
</html>