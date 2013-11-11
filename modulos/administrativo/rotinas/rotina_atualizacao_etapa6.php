<?
	system("cd $Path../ && tar -cvjpf www/temp/www.backup.$IdAtualizacao.tar.bz2 www.backup.$IdAtualizacao");
	system("rm -r $Path../www.backup.$IdAtualizacao");

	@mysql_close($con);
	include("../../../files/conecta.php");
	
	$sql = "update Atualizacao set DataTermino = concat(curdate(),' ',curtime()) where IdAtualizacao = $IdAtualizacao";
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
		parent.superior.location.replace('../cadastro_rotina_atualizacao_etapas.php?Etapa=7');
		parent.inferior.location.replace("rotina_atualizacao.php?Etapa=7&IdAtualizacao=<?=$IdAtualizacao?>");
	}
</script>