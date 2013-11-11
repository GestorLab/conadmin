<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_IdEmail		= $_GET['IdTipoEmail'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
<frameset rows="380pixes,*" framespacing=0 frameborder=0 border=0 scrolling=auto noresize='noresize'>
  	<frame name='cadastro' src="cadastro_tipo_email_visualizar.php?IdLoja=<?=$local_IdLoja?>&IdEmail=<?=$local_IdEmail?>">
  	<frame name='email' src="../../visualizar_tipo_email.php">
</frameset>
