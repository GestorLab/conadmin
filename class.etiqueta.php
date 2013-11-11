<?
class Etiqueta extends FPDF
{
	function Quadro($IdPessoa, $EndCob){
	
		$this->margin_left = 10;
		$this->SetFont('Arial','',8);
		$this->SetFillColor(255,255,255);
	    $this->SetTextColor(0);
	    $this->SetDrawColor(0,0,0);
	    $this->SetLineWidth(0.3);
	    $this->MultiCell(0,3.5,"Oieee",0,0,'R',1);
	    $this->Cell(190,1,'','T');
	}
}
?>