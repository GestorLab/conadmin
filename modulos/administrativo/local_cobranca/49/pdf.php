<?
	require("../../../../classes/fpdf/class.fpdf.php");
	require("include/class.boleto.php");
	require("include/funcoes_santander_banespa.php");
		
	include("../../../../files/conecta.php");
	include("../../../../files/funcoes.php");
	
	$IdLoja 			= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];

	$Path = "../../../../";		
	
	// Inicia o boleto
	$pdf=new Boleta();
	
	// Processos e configurações
	$pdf->SetDisplayMode('real');
	$pdf->SetAutoPageBreak(true, 0);
	$pdf->AddPage();
	//$pdf->Cabecalho($IdLoja, $con);
	//$pdf->Demonstrativo($IdLoja, $IdContaReceber, $con);
	$pdf->Titulo($IdLoja,$con);
	$pdf->DemonstrativoVerso($IdLoja, $IdContaReceber, $con);
	$pdf->Output();
?>