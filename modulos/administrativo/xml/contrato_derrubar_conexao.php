<?
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_IdContrato	= $_GET['IdContrato'];
	
	derrubaConexaoRadius($local_IdLoja, $local_IdContrato);
?>