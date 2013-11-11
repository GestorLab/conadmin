<?
	require_once("../../../../classes/fpdf/class.fpdf.php");

	class PDF_Cell extends FPDF{
		function VCell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false){
			//Output a cell
			$k=$this->k;
			
			if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()){
				//Automatic page break
				$x=$this->x;
				$ws=$this->ws;
				
				if($ws>0){
					$this->ws=0;
					$this->_out('0 Tw');
				}
				$this->AddPage($this->CurOrientation,$this->CurPageFormat);
				$this->x=$x;
				
				if($ws>0){
					$this->ws=$ws;
					$this->_out(sprintf('%.3F Tw',$ws*$k));
				}
			}
			
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			$s='';
			// begin change Cell function 
			if($fill || $border>0){
				if($fill)
					$op=($border>0) ? 'B' : 'f';
				else
					$op='S';
				if ($border>1) {
					$s=sprintf('q %.2F w %.2F %.2F %.2F %.2F re %s Q ',$border,
								$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
				}
				else
					$s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
			}
			if(is_string($border)){
				$x=$this->x;
				$y=$this->y;
				if(is_int(strpos($border,'L')))
					$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
				else if(is_int(strpos($border,'l')))
					$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
					
				if(is_int(strpos($border,'T')))
					$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
				else if(is_int(strpos($border,'t')))
					$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
				
				if(is_int(strpos($border,'R')))
					$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
				else if(is_int(strpos($border,'r')))
					$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
				
				if(is_int(strpos($border,'B')))
					$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
				else if(is_int(strpos($border,'b')))
					$s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
			}
			if(trim($txt)!=''){
				$cr=substr_count($txt,"\n");
				if($cr>0){ // Multi line
					$txts = explode("\n", $txt);
					$lines = count($txts);
					for($l=0;$l<$lines;$l++) {
						$txt=$txts[$l];
						$w_txt=$this->GetStringWidth($txt);
						if ($align=='U')
							$dy=$this->cMargin+$w_txt;
						elseif($align=='D')
							$dy=$h-$this->cMargin;
						else
							$dy=($h+$w_txt)/2;
						$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
						if($this->ColorFlag)
							$s.='q '.$this->TextColor.' ';
						$s.=sprintf('BT 0 1 -1 0 %.2F %.2F Tm (%s) Tj ET ',
							($this->x+.5*$w+(.7+$l-$lines/2)*$this->FontSize)*$k,
							($this->h-($this->y+$dy))*$k,$txt);
						if($this->ColorFlag)
							$s.=' Q ';
					}
				}
				else{ // Single line
					$w_txt=$this->GetStringWidth($txt);
					$Tz=100;
					if($w_txt>$h-2*$this->cMargin){
						$Tz=($h-2*$this->cMargin)/$w_txt*100;
						$w_txt=$h-2*$this->cMargin;
					}
					if ($align=='U')
						$dy=$this->cMargin+$w_txt;
					elseif($align=='D')
						$dy=$h-$this->cMargin;
					else
						$dy=($h+$w_txt)/2;
					$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
					if($this->ColorFlag)
						$s.='q '.$this->TextColor.' ';
					$s.=sprintf('q BT 0 1 -1 0 %.2F %.2F Tm %.2F Tz (%s) Tj ET Q ',
								($this->x+.5*$w+.3*$this->FontSize)*$k,
								($this->h-($this->y+$dy))*$k,$Tz,$txt);
					if($this->ColorFlag)
						$s.=' Q ';
				}
			}
			// end change Cell function 
			if($s)
				$this->_out($s);
			$this->lasth=$h;
			if($ln>0){
				//Go to next line
				$this->y+=$h;
				if($ln==1)
					$this->x=$this->lMargin;
			}
			else
				$this->x+=$w;
		}

	}
	
	//$pdf = new PDF('P','cm','A4');
	$pdf = new PDF_Cell('P','cm','A4');
	$pdf->SetMargins(1.64, 1.5, 1.64);
	$pdf->AddPage();
	//--------------------------------------Desenho Estrutura-------------------------\\
	//layout desenho caixa
	$pdf->Line(1,1.5,19.9,1.5);//H1
	$pdf->Line(1,14.80,19.9,14.80);//H2
	$pdf->Line(1,1.5,1,14.80);//VL
	$pdf->Line(19.9,1.5,19.9,14.80);//VR
	//         x1    y1   x2    y2
	$pdf->Line(1.35,1.87,19.53,1.87);//L1
	$pdf->Line(1.35,14.45,19.53,14.45);//L2
	$pdf->Line(1.35,1.87,1.35,14.45);//L3
	$pdf->Line(19.53,1.87,19.53,14.45);//L4
	//          x1   y1   x2    y2
	$pdf->Line(1.35,5.15,19.53,5.15);//L5
	//         x1  y1   x2  y2
	$pdf->Line(1.5,5.4,4.2,5.4);//L6
	$pdf->Line(1.5,11.9,4.2,11.9);//L7
	$pdf->Line(1.5,5.4,1.5,11.9);//L8
	$pdf->Line(4.2,5.4,4.2,11.9);//L9
	$pdf->Line(3.6,5.8,3.6,11.6);//L10
	//          x1  y1   x2   y2
	$pdf->Line(4.35,5.4,19.38,5.4);//L11    
	$pdf->Line(4.35,5.9,14.38,5.9);//L12    
	$pdf->Line(4.35,7.25,14.38,7.25);//L13    
	$pdf->Line(4.35,8.18,19.38,8.18);//L14    
	$pdf->Line(4.35,11,19.38,11);//L15  
	$pdf->Line(4.35,11.9,19.38,11.9);//L16
	$pdf->Line(4.35,5.4,4.35,11.9);//L17
	$pdf->Line(5.9,11,5.9,11.9);//L18
	$pdf->Line(6.4,5.4,6.4,7.25);//L19
	$pdf->Line(9.77,5.4,9.77,7.25);//L20
	$pdf->Line(12.7,5.4,12.7,7.25);//L21
	$pdf->Line(14.4,5.4,14.4,8.18);//L22
	$pdf->Line(19.38,5.4,19.38,11.9);//L23
	//          x1  y1    x2   y2
	$pdf->Line(8,12.925,13,12.925);//L24
	$pdf->Line(8,14.1,13,14.1);//L25
	$pdf->Line(8,12.925,8,14.1);//L26
	$pdf->Line(13,12.925,13,14.1);//L27
	$pdf->Line(13.7,13.55,19.02,13.55);//L28
	
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!Cabeçalho!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\\
	$pdf->SetFont('Arial','',8.5);
	//Verificar se local de cobrança tem logomarca personalizada se nao tiver pegar logo do sistema
	if($local_ExtLogo != ""){
		$pdf->Image("../../../../modulos/administrativo/local_cobranca/personalizacao/".$local_IdLoja."/".$local_IdLocalCobranca.".".$local_ExtLogo, 2.6, 2.95, 4.0, 1.1, $local_ExtLogo);//Logo Credor
	}else{
		$pdf->Image("../../../../img/personalizacao/logo_cab.gif", 2.6, 2.95, 4.0, 1.1, 'gif');//Logo Credor
	}
	$pdf->Text(7.8, 2.3, $razao);//Credor
	$pdf->Text(7.8, 2.72, $cnpj);//Credor CNPJ
	$pdf->Text(7.8, 3.12, $endereco);//Credor endereço numero
	$pdf->Text(7.8, 3.52, $cep_cre);//Credor cep
	$pdf->Text(9.8, 3.52, $bairro);//Credor bairro
	$pdf->Text(7.8, 3.92, $cidade_uf);//Credor Cidade
	$pdf->Text(7.8, 4.5, $data_emissao);//Data Emissao

	$pdf->SetFont('Arial','B',10);
	$pdf->Text(16.35, 3.5, $doc);//Nome Documento
	
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY(16.2,3.5);
	$pdf->MultiCell(2.35, 1.2, $num_duplicata,0,'C');//Numero Duplicata
	
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!Corpo!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\\
	
	$pdf->SetFont('Arial','',10);
	
	$pdf->SetFont('Arial','B',7);
	
	//--------------Labels Captions Titulos do campo de dados do sacado------------------------\\
	$pdf->Text(4.5, 5.73, $nf_fatura);//Nº Fatura
	$pdf->Text(6.55, 5.73, $valor_duplicata);//Valor Duplicata
	$pdf->Text(9.91, 5.73, $n_ordem);//Nº Ordem
	$pdf->Text(12.85, 5.73, $vencimento);//Vencimento
	$pdf->SetXY(15.14,5.4);
	$pdf->MultiCell(3.5,0.3, $insti_financeira,0,'C');//Credor estado
	$pdf->Text(4.5, 8.68, $nome_sacado);//nome sacado
	$pdf->Text(4.5, 9.18, $end_sacado);//endereco
	$pdf->Text(4.5, 9.68, $cep);//cep
	$pdf->Text(6.9, 9.68, $mun);//municipio
	$pdf->Text(13.2, 9.68, $est);//estado
	$pdf->Text(4.5, 10.18, $praca);//praça
	$pdf->Text(4.5, 10.68, $cnpj_sacado);//cnpj
	$pdf->Text(9.7, 10.68, $insc);//cnpj
	
	//-----------------------Valores conteudos do campo de dados do sacado-----------------------\\
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(4.35,5.9);
	$pdf->MultiCell(2.05, 1.35, $num_nota,0,'C');//numeroF
	$pdf->SetXY(6.4,5.9);
	$pdf->MultiCell(3.37, 1.35, "R$ ".$valor2,0,'C');//valor
	$pdf->SetXY(9.77,5.9);
	$pdf->MultiCell(2.93, 1.35, $num_duplicata,0,'C');//numero duplicata
	$pdf->SetXY(12.7,5.9);
	$pdf->MultiCell(1.7, 1.35, $venc,0,'C');//data
	//------------------------------------Juros e Descontos-----------------------------------------\\
	if($desc_val_data != ""){
		$pdf->SetXY(4.35,7.3);
		$pdf->Cell(10.05, 0.47, $desc_val_data,0,1,'L');//Desconto de
		$pdf->SetFont('Arial','',6.5);
		$pdf->SetX(4.35);
		$pdf->Cell(10.05,0.47, $condicoes,0,0,'L');//Juros de
	} else{
		$pdf->SetFont('Arial','',6.7);
		$pdf->SetXY(4.35,7.48);
		$pdf->Cell(10.05,0.47, $condicoes,0,0,'L');//Juros de$pdf->SetXY(4.35,7.25);
	}
	//-------------------------------Informações do Sacado/Cliente-------------------------------------\\
	$pdf->SetFont('Arial','',7);
	$pdf->Text(7, 8.68, $nom_sacado);//nome sacado
	$pdf->Text(6.1, 9.18, $end_sac);//endereco
	$pdf->Text(5.2, 9.68, $cep_sac);//cep
	$pdf->Text(8.4, 9.68, $mun_sac);//municipio
	$pdf->Text(13.75, 9.68, $est_sac);//estado
	$pdf->Text(7.65, 10.18, $praca_paga);//praça
	$pdf->Text(6.85, 10.68, $cnpj_sac);//cnpj
	if($insc != "RG:"){
		$pdf->Text(11.3, 10.68, $insc_sac);//Iscriçao Estadual
	} else{
		$pdf->Text(10.2, 10.68, $insc_sac);//RG
	}
	$pdf->SetFont('Arial','',8.5);
	$pdf->SetXY(4.4,11.051);
	$pdf->MultiCell(1.55, 0.4, $lbl_extenso,0,'L');//extenso
	(int)$value = strlen($extenso);
	$extenso = str_pad("(".$extenso.")",234,' *',STR_PAD_RIGHT);
	$pdf->SetXY(6.2,11.056);
	$pdf->MultiCell(13.2, 0.4, $extenso,0,'L');//extenso
	$pdf->SetY(5.15);
	$pdf->VCell(0.85,7, $razao,0,0,'C');//Celula Vertical
	$pdf->VCell(0.8,5,'',0,0,'C');
	$pdf->VCell(1,7, $ass_emitente,0,0,'L');
	$pdf->SetXY(13.7,13.55);
	$pdf->MultiCell(5.31,0.6, $ass_aceite,0,'C');//Assinatura Aceite
	$pdf->Text(3, 13.5, $data_aceite1);//data aceite
	$pdf->Text(4.15, 13.85, $data_aceite2);//data aceite
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(1.4,12);
	$pdf->MultiCell(17.98,0.25, $declaracao,0,"J");//Motivo do pagamento
	$pdf->SetXY(8,12.94);
	$pdf->MultiCell(5, 0.295, $aviso,0,'J');
	
	$pdf->Output("arquivo","I");
	
?>