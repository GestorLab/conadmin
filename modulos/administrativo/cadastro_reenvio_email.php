<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_IdEmail		= $_GET['IdEmail'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type="text/javascript">
		<!--
			window.onload = function () { 
				ativaNome('E-mail Enviado (E-mail´s antigos)'); 
			};
			-->
		</script>
	</head>
	<frameset rows="265pixes,*" framespacing=0 frameborder=0 border=0 scrolling=auto noresize='noresize'>
		<frame name='cadastro' src="cadastro_reenvio_email_visualizar.php?IdLoja=<?=$local_IdLoja?>&IdEmail=<?=$local_IdEmail?>">
		<frame name='email' src="../../visualizar_email.php" scrolling=auto>
	</frameset>
</html>