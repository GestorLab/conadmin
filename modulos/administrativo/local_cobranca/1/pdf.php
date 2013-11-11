<?
	require("../../../../classes/fpdf/class.fpdf.php");
	require("class.boleto.php");
	require("funcoes_diversas.php");
		
	include("../../../../files/conecta.php");
	include("../../../../files/funcoes.php");
	
	$IdLoja 			= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];

	$Path = "../../../../";		
	
	// Inicia o boleto
	$pdf=new Boleta();
	
	// Processos e configurações
	$pdf->SetDisplayMode('real');
	$pdf->SetMargins(10, 5, 10);
	$pdf->AddPage();
	$pdf->Cabecalho($IdLoja, $con);
	$pdf->Demonstrativo($IdLoja, $IdContaReceber, $con);
	$pdf->Titulo($IdLoja, $IdContaReceber, $con);
	$pdf->Output();
?>