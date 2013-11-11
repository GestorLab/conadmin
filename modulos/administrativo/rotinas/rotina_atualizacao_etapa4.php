<?
	$FileDownload	= "www_compilado.tar.bz2";
	$FileExport		= $Path."../www_compilado";

	@system("rm -r $FileExport");
	system("cd $Path../ && tar -xjpf $FileDownload");
	system("cd $Path../ && cp -r www_compilado/* www");
	@system("rm -r $FileExport*");

	@mysql_close($con);
	include("../../../files/conecta.php");

	$sql = "update Atualizacao set DataUpdateFiles = concat(curdate(),' ',curtime()) where IdAtualizacao = $IdAtualizacao";
	mysql_query($sql,$con);

	@mysql_close($con);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<body onLoad="proximaEtapa()">&nbsp;</body>
</html>
<script>
	function proximaEtapa(){
		parent.superior.location.replace('../cadastro_rotina_atualizacao_etapas.php?Etapa=5');
		parent.inferior.location.replace("rotina_atualizacao.php?Etapa=5&IdAtualizacao=<?=$IdAtualizacao?>");
	}
</script>