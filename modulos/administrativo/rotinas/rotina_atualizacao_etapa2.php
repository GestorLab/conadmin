<?
	// Gera o backup da Base de dados
	include("../../../rotinas/verifica.php");

	$Login = $_SESSION["Login"];

	$LimpaBackup = 'N';

	include("../../../rotinas/backup.php");

	mysql_close($con);
	include("../../../files/conecta.php");

	// Gerar backup dos Arquivos
	$sql = "update Atualizacao set DataBackupFiles = concat(curdate(),' ',curtime()) where IdAtualizacao = $IdAtualizacao";
	mysql_query($sql,$con);

	$PatchSistemaTemp	= substr($PatchSistema,0,strlen($PatchSistema)-1);
	$PatchBackup		= "$PatchSistemaTemp.backup.$IdAtualizacao";

	system("cp -r $PatchSistemaTemp $PatchBackup");

	@mysql_close($con);
	include("../../../files/conecta.php");

	$sql = "update Atualizacao set DataBackupMySQL = concat(curdate(),' ',curtime()) where IdAtualizacao = $IdAtualizacao";
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
		parent.superior.location.replace('../cadastro_rotina_atualizacao_etapas.php?Etapa=3');
		parent.inferior.location.replace("rotina_atualizacao.php?Etapa=3&IdAtualizacao=<?=$IdAtualizacao?>");
	}
</script>