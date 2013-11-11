<?
	global $ExtLogoPDF;
	
	$logo				= $ExtLogoPDF;
	$ExtLogo 			= endArray(explode(".",$logo));
	$imagem_retangulo	= "imagens/Exemploquadrado.gif";
	$imagem_retangulo2	= "imagens/Exemploquadrado2.gif";
	
	$this->AddPage();
	$this->SetFont('Arial','',8);
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(0);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0.3);
	include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	
	$this->SetFont('Arial','B',14);
	$this->SetY(12);
	$this->Cell(15,8.5,'','',0,'L',0);
	$this->Cell(155,25.5,"",0,'L',0);
	$this->Ln();
	$this->Cell(1,3.55,'',0,0,'L',0);
	$this->SetFont('Arial','',14);
	$this->SetFillColor(233,243,218);
	$this->Ln();
	$this->MultiCell(190,5.5,substr($CobrancaParametro[AvisoImportante],0,525),0,'C',false);
	$this->SetFont('Arial','B',32);
	
	$this->Text(64,38.5,"AVISO IMPORTANTE");
	
	$this->Ln();
	$this->Cell(26.5,-35.5,'','',0,'L',0);
	$this->Ln();
	$this->Cell(12,9.5,'',0,0,'L',0);
	$this->Cell(167,42.5,'','',0,'L',false);
	$this->SetFont('Arial','',10);
	$this->TextWithDirection(179,126,"$linDadosClienteCobranca[CEP]\t\t\t\t\t\t\t$linDadosClienteCobranca[NomeCidade]\t\t\t\t\t\t\t$linDadosClienteCobranca[SiglaEstado]",'L');
	$this->TextWithDirection(179,130,"$dadosboleto[endereco02]",'L');
	$this->TextWithDirection(179,134,"$dadosboleto[endereco01]",'L');
	$this->TextWithDirection(179,138,"$dadosboleto[nome_sacado]",'L');
	$this->SetFont('Arial','',7);
	$this->TextWithDirection(179,142,"CTC NLP U/RJ-PL1",'L');
	$this->RotatedImage("imagens/cep_cod_barra.png",179,152.1,61.2,6.9,180,"png");
	$this->SetFont('Arial','B',16);
	$this->TextWithDirection(65,120,"",'L');
	$this->SetFont('Arial','B',9.5);
	$this->TextWithDirection(65,116.5,"Pode ser aberto pela ECT.",'L');
	
?>