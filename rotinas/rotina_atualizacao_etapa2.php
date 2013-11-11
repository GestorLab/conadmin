<?
	include("files/conecta_conadmin.php");

	// Conecta no servidor de update
	$FileDownload = "www_compilado.tar.bz2";
	$FileVersao	  = "../".$FileDownload;

	$sql = "select
				IdParametroSistema,
				ValorParametroSistema
			from
				ParametroSistema
			where
				IdGrupoParametroSistema = 3";
	$res = mysql_query($sql,$conConAdmin);
	while($lin = mysql_fetch_array($res)){
		$UpdateFTP[$lin[IdParametroSistema]] = $lin[ValorParametroSistema];
	}

	$conn_id		= ftp_connect($UpdateFTP[1],$UpdateFTP[2]);
	$login_result	= ftp_login($conn_id, $UpdateFTP[3], $UpdateFTP[4]);

	@unlink($FileVersao);

	ftp_chdir($conn_id, "/versoes/automatica/$IdVersao");
	$get = @ftp_get($conn_id, $FileVersao, $FileDownload, FTP_BINARY);

	if(!$get){
		@ftp_pasv($conn_id, true);
		ftp_chdir($conn_id, "/versoes/automatica/$IdVersao");
		ftp_get($conn_id, $FileVersao, $FileDownload, FTP_BINARY);
	}

	ftp_close($conn_id);

	if(file_exists($FileVersao)){

		$FileExport		= "../www_compilado";

		@system("rm -r $FileExport");
		system("cd .. && tar -xjpf $FileDownload");
		system("cd .. && cp -r www_compilado/* www");
		@system("rm -r $FileExport*");

		@mysql_close($con);
		include($Path."files/conecta.php");

		$sql = "update Atualizacao set DataEtapa2 = concat(curdate(),' ',curtime()) where IdAtualizacao = $IdAtualizacao";
		mysql_query($sql,$con);

		@mysql_close($con);
	}
?>