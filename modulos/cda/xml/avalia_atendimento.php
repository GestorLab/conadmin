<?php
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');	
	include('../files/funcoes.php');	
	include('../rotinas/verifica.php');
	
	$local_IdLoja			= $_SESSION["IdLojaCDA"];
	$local_IdOrdemServico	= $_GET['IdOrdemServico'];
	$local_Nota				= $_GET['Nota'];
	
	if($local_Nota == '') {
		$local_Nota = "NULL";
	}
	
	$sql = "UPDATE OrdemServico SET
				NotaAtendimento = $local_Nota
			WHERE
				IdLoja = '$local_IdLoja' AND
				IdOrdemServico = '$local_IdOrdemServico';";
	
	print(@mysql_query($sql,$con));
?>