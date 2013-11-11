<?
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_GET['IdLoja'];
	$IdContaReceber		= $_GET['IdContaReceber'];

	include("../../local_cobranca/informacoes_default.php");
	include("layout_pdf.php");
?>