<?
	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_IdHistoricoMensagem	= $_GET['IdHistoricoMensagem'];
	$local_IdTipoMensagem		= $_GET['IdTipoMensagem'];
	
	$sql="	select 
				TipoMensagem.IdTipoMensagem
			from
				HistoricoMensagem,
				TipoMensagem 
			where HistoricoMensagem.IdLoja = $local_IdLoja 
				and HistoricoMensagem.IdLoja = TipoMensagem.IdLoja 
				and HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem  
				and HistoricoMensagem.IdHistoricoMensagem = '$local_IdHistoricoMensagem'";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
	if($lin[IdTipoMensagem] != 29 && $lin[IdTipoMensagem] != 32){
		$frame_mensagem = "<frame name='mensagem' src=\"../../visualizar_mensagem.php\">";
		$rows = ",*";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type="text/javascript">
		<!--
			window.onload = function () {
				ativaNome('Mensagem Enviada');
			};
			-->
		</script>
	</head>
	<frameset rows="415pixes<?=$rows?>" framespacing=0 frameborder=0 border=0 scrolling=auto noresize='noresize'>
		<frame name='cadastro' src="cadastro_reenvio_mensagem_visualizar.php?IdLoja=<?=$local_IdLoja?>&IdHistoricoMensagem=<?=$local_IdHistoricoMensagem?>">
		<?=$frame_mensagem;?>
	</frameset>
</html>
