<?
	require("classes/fpdf/class.fpdf.php");
	require("class.etiqueta.php");

	// Inicia o boleto
	$pdf=new Etiqueta();
	
	// Processos e configurações
	$pdf->SetDisplayMode('real');
	$pdf->SetMargins(10, 5, 10); // Em mm // SetMargins(left, top, right) 
	$pdf->AddPage();

	$pdf->Quadro(1, 'S');

	$pdf->Output();
?>
