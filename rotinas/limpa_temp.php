<?
	// Limpa a pasta temporária
	$PathPastaTemp	= $Path."temp/";
	$DataApagar		= dataConv(incrementaData(date("Y-m-d"),-30),'Y-m-d','Ymd');

	if ($handle = opendir($PathPastaTemp)){
		# Esta é a forma correta de varrer o diretório
		while (false !== ($file = readdir($handle))) {
			$FilePath	= $PathPastaTemp.$file;
			$FileInfo	= stat($FilePath);
			$FileData	= date("Ymd",$FileInfo[10]);

			if($FileData < $DataApagar){
				@unlink($FilePath);
			}
		}
		closedir($handle);
	}

	// Apaga os registros do log com mais de 30 dias
	$sql = "delete from MonitorMikrotikUsuario where Data < '".dataConv(date("d/").incrementaMesReferencia(date("m-Y"),-1),"d/m/Y","Y-m-d")."'";
	mysql_query($sql,$con);

	system("rm -r /var/spool/clientmqueue/*");
?>