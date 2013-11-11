<?
	// Inclui os arquivos utilizados
	include ('../../classes/zip.lib/zip.lib.php');

	$sql = "select
					EndArquivo
				from
					ArquivoRemessa
				where
					IdLoja = $local_IdLoja and
					IdLocalCobranca = $local_IdLocalCobranca and
					IdArquivoRemessa = $local_IdArquivoRemessa";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$EndArquivo = $lin[EndArquivo];

	// Compactar o arquivo
	$zip = new zipfile;
	$abre = fopen($EndArquivo, "r" );
	$com  = fread($abre,filesize($EndArquivo));
	fclose($abre);
	$zip->addFile($com,$EndArquivo);  // Adiciona arquivos ao .zip
	$strzip=$zip->file();
	$abre = fopen($EndArquivo.".zip", "w");
	$salva = fwrite($abre, $strzip);
	fclose($abre);

	header("Location: $EndArquivo.zip"); 
?>