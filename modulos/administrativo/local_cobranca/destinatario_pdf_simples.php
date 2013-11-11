<?
	include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	/* HABILITAR A EXIBIÇÃO DO QR CODE 1-SIM/2-NÃO */
	if(getCodigoInterno(47,1) == 1){
		if(getParametroSistema(6,7) != ""){
			$pathSistema = getParametroSistema(6,7);
		} else {
			$pathSistema = getParametroSistema(6,3);
		}
		
		$QRCodeURL = trim($pathSistema."/central/?cda=".$lin[MD5]);
		
		$this->SetY(105.5);
		$this->SetX(90);
		$this->Image($pathSistema."/modulos/administrativo/rotinas/gerar_qrcode.php?Data=".$QRCodeURL."&Size=3",174,null,28.5,28.5,"png");
	}
	
	$this->SetY(95.5);
	$this->SetX(90);
	
	$this->Ln();
	$this->SetX(90);
	$this->SetFillColor(0,0,0);
	$this->Cell(7,4.5,'','L',0,'',0);
	$this->Cell(105,4.5,'','',0,'L',0);
	
	$this->Ln();
	$this->SetX(90);
	$this->SetFillColor(0,0,0);
	$this->Cell(7,4.5,'','L',0,'',0);
	$this->Cell(105,4.5,'','',0,'L',0);

	$this->Ln();
	$this->SetX(90);
	$this->SetFont('Arial','B',7.5);
	$this->SetFillColor(0,0,0);
	$this->Cell(7,4.5,'','L',0,'',0);
	$this->Cell(105,4.5,'Destinatário','',0,'L',0);
	
	$this->Ln();
	$this->SetX(90);
	$this->SetFont('Arial','',8.5);
	$this->SetFillColor(0,0,0);
	$this->Cell(7,3.5,'','L',0,'',0);
	$this->Cell(105,3.5,$dadosboleto["nome_sacado"],'',0,'L',0);
	
	if($dadosboleto["representante"] != ''){
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$dadosboleto["representante"],'',0,'L',0);
	}
	
	$this->Ln();
	$this->SetX(90);
	$this->SetFillColor(0,0,0);
	$this->Cell(7,3.5,'','L',0,'',0);
	$this->Cell(105,3.5,$dadosboleto["endereco01"],'',0,'L',0);
	
	$this->Ln();
	$this->SetX(90);
	$this->SetFillColor(0,0,0);
	$this->Cell(7,3.5,'','L',0,'',0);
	$this->Cell(105,3.5,$dadosboleto["endereco02"],'',0,'L',0);
	
	$this->Ln();
	$this->SetX(90);
	$this->SetFillColor(0,0,0);
	$this->Cell(7,14,'','L',0,'',0);
	$this->Cell(105,3.5,$dadosboleto["endereco03"],'',0,'L',0);
?>