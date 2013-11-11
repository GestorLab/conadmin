<?
	// Conecta no servidor de update

	$FileDownload = "www_compilado.tar.bz2";
	$FileVersao	  = $Path."../".$FileDownload;

	$sql = "SELECT
				IdVersao
			FROM
				Atualizacao
			WHERE
				IdAtualizacao = $IdAtualizacao";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$IdVersao = $lin[IdVersao];

	$sql = "select
				IdParametroSistema,
				ValorParametroSistema
			from
				ParametroSistema
			where
				IdGrupoParametroSistema = 2";
	$res = mysql_query($sql,$conConAdmin);
	while($lin = mysql_fetch_array($res)){
		$UpdateFTP[$lin[IdParametroSistema]] = $lin[ValorParametroSistema];
	}

	$conn_id		= ftp_connect($UpdateFTP[1],$UpdateFTP[2]);
	$login_result	= ftp_login($conn_id, $UpdateFTP[3], $UpdateFTP[4]);
	ftp_chdir($conn_id, "/versoes/automatica/$IdVersao");

	@unlink($FileVersao);

	ftp_get($conn_id, $FileVersao, $FileDownload, FTP_BINARY);

	ftp_close($conn_id);

	@mysql_close($con);
	include("../../../files/conecta.php");

	$sql = "update Atualizacao set DataDownloadFiles = concat(curdate(),' ',curtime()) where IdAtualizacao = $IdAtualizacao";
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
		parent.superior.location.replace('../cadastro_rotina_atualizacao_etapas.php?Etapa=4');
		parent.inferior.location.replace("rotina_atualizacao.php?Etapa=4&IdAtualizacao=<?=$IdAtualizacao?>");
	}
</script>