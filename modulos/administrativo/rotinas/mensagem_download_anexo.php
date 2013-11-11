<?php
	$IdLoja						= $_GET[IdLoja];
	$IdHistoricoMensagemAnexo	= $_GET[IdHistoricoMensagemAnexo];
	$IdAnexo					= $_GET[IdAnexo];
	$NomeArquivo				= $_GET[NomeArquivo];

	$EndArquivo	= "../anexos/mensagem/".$IdLoja."/".$IdHistoricoMensagemAnexo."/".$IdAnexo;			

	header("Content-Type: application/save;");
	header("Content-Length: ".filesize($EndArquivo).";"); 
	header('Content-Disposition: attachment; filename="' . $NomeArquivo . '";'); 
	header("Content-Transfer-Encoding: binary;");
	header('Expires: 0;'); 
	header('Pragma: no-cache;');

	readfile($EndArquivo);
?>