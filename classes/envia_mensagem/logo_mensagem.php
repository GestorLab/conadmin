<?
	include("../../files/conecta.php");

	$local_Mensagem		= $_GET[Mensagem];
	$local_Imagem		= $_GET[Imagem];
	$local_IPLeitura	= $_SERVER[REMOTE_ADDR];

	$sql = "update HistoricoMensagem set
				DataLeitura = (concat(curdate(),' ',curtime())),
				IPLeitura	= '$local_IPLeitura',
				IdStatus	= 4
			where				
				MD5 = '$local_Mensagem'";
	@mysql_query($sql,$con);

	header("Location: $local_Imagem");
?>