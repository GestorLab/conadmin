<?
	require("../../../../classes/fpdf/class.fpdf.php");
	require("class.nf.php");
			
	$IdLoja 			= $_GET['IdLoja'];
	$IdContaReceber		= $_GET['IdContaReceber'];

	$Path = "../../../../";
	$Reaviso = false;
	
	// Inicia o boleto
	$pdf=new NF();
	
	// Processos e configurações
	$pdf->SetDisplayMode('real');
	$pdf->SetMargins(10, 5, 10);
	$pdf->AddPage();
	$pdf->Cabecalho($IdLoja, $con);
	$pdf->NotaFiscal($IdLoja, $IdContaReceber, $con);
	$pdf->Output();
?>