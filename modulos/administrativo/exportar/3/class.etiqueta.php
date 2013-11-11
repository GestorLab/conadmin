<?
class Etiqueta extends FPDF
{
	function Conteudo($Cedulas, $QTDPagina, $con){
	
		include("../vetor.php");
		
		$qtd_linha		=	7;
		$qtd_coluna		=	2;
		$qtd_etiqueta	=	$ii;
		
		$pagina		=	explode("\n",$Cedulas);
		$qtd_pagina	=	$QTDPagina;
		$tam		=	0;
		$y			=	0;
		
		for($i=0;$i<$QTDPagina;$i++){
			$temp_etiqueta	=	explode("¬",$pagina[$i]);		
			
			for($ii=0;$ii<count($temp_etiqueta);$ii++){
				$imprimir	=	explode("_",$temp_etiqueta[$ii]);
				
				if($imprimir[3]	==	1){
					$etiqueta[$y]	=	$vetor[$tam];
					$tam++;		
				}else{
					$etiqueta[$y]	=   "";	
				}
				
				$y++;
			}
		}		
		
		// tam -> So as impressas
		// y -> impressas + vazias
		if($tam < $qtd_etiqueta){
			while($tam < $qtd_etiqueta){
				$etiqueta[$y]	=	$vetor[$tam];
				$tam++;
				$y++;
			}
		}
		
		$qtd_etiqueta	=	$y;
		$qtd_pagina		=	ceil($y/($qtd_linha*$qtd_coluna));
	
		//Cell(w, h, txt, border, ln, align) 
		$this->SetFont('Arial','',10);
	    //Largura	=	10.2 e 0.5
		//Altura	=	3.4	
		
	    $i   = 0;
	    $aux = 0;
		while($i < $qtd_pagina){
			if($i>0){
				$this->AddPage();
			}
			
			for($ii=0;$ii<($qtd_linha*$qtd_coluna);$ii=$ii+2){	
				if($aux < $qtd_etiqueta){
					$pos	=	$i*($qtd_linha*$qtd_coluna)+$ii;
					
					$this->Cell(10.2,0.4,'',0,0,'L');
					$this->Cell(0.5,0.4,'',0,0,'L');
					$this->Cell(10.2,0.4,'',0,1,'L');
							
					$this->SetFont('Arial','B',10);
					$this->Cell(10.2,0.54,$etiqueta[$pos][0],0,0,'L');
					$this->Cell(0.5,0.54,'',0,0,'L');
					$this->Cell(10.2,0.54,$etiqueta[$pos+1][0],0,1,'L');
					
					$this->SetFont('Arial','',10);
					$this->Cell(10.2,0.54,$etiqueta[$pos][1],0,0,'L');
					$this->Cell(0.5,0.54,'',0,0,'L');
					$this->Cell(10.2,0.54,$etiqueta[$pos+1][1],0,1,'L');
					
					$this->Cell(10.2,0.54,$etiqueta[$pos][2],0,0,'L');
					$this->Cell(0.5,0.54,'',0,0,'L');
					$this->Cell(10.2,0.54,$etiqueta[$pos+1][2],0,1,'L');
					
					$this->Cell(10.2,0.54,$etiqueta[$pos][3],0,0,'L');
					$this->Cell(0.5,0.54,'',0,0,'L');
					$this->Cell(10.2,0.54,$etiqueta[$pos+1][3],0,1,'L');
					
					$this->Cell(10.2,0.54,$etiqueta[$pos][4],0,0,'L');
					$this->Cell(0.5,0.54,'',0,0,'L');
					$this->Cell(10.2,0.54,$etiqueta[$pos+1][4],0,1,'L');
					
					$this->Cell(10.2,0.4,'',0,0,'L');
					$this->Cell(0.5,0.4,'',0,0,'L');
					$this->Cell(10.2,0.4,'',0,1,'L');
				}
				$aux=$aux+2;
			}
			$i++;
		}
	}			
}
?>