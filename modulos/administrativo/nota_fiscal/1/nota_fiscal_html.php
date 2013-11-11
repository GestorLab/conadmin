<?
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	
	if($_GET['IdLoja'] != ""){
		$IdLoja	= $_GET['IdLoja'];
	}
	if($_GET['IdContaReceber'] != ""){
		$IdContaReceber	= $_GET['IdContaReceber'];
	}
	
	include("../../local_cobranca/informacoes_default.php");
	include("layout_html.php");
?>
