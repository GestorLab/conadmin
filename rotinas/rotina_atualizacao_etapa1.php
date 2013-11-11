<?
	// Gera o backup da Base de dados
	$LimpaBackup = 'N';
	$BackupCompleto = 'S';
	
	if($Path == ''){
		$Path = getParametroSistema(6,1);
	}

	include("rotinas/backup.php");

	// Gerar backup dos Arquivos
	$PatchBackup = "../www.backup.$IdAtualizacao";

	@system("rm -r $PatchBackup");
	system("cp -r ../www $PatchBackup");

	@mysql_close($con);
	include("files/conecta.php");

	$sql = "update LogAcesso set Fechada='1'";
	mysql_query($sql,$con);

	$sql = "update Atualizacao set DataEtapa1 = concat(curdate(),' ',curtime()) where IdAtualizacao = $IdAtualizacao";
	mysql_query($sql,$con);
?>