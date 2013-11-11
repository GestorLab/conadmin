<?
	global $ExtLogoPDF;
	
	
	include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	include($Path."modulos/administrativo/local_cobranca/49/include/vars_santander_banespa.php");

	$this->AddPage();
	#$this->Image("imagens/boleto_externo.jpg",0,0,210,290,"jpg");
	$this->Image("imagens/boleto_navex_verso.JPG",0,0,210,290,"JPG");
	$this->SetFont('Arial','',8.7);
	$this->SetY(152);
	$this->setX(34);
	$this->Cell(99,4.5,'NOME DO USUÁRIO ',0,0,'L',0);
	$this->Cell(50,4.5,$linDadosCliente['IdPessoa'],0,1,'L',0);
	
	$this->setX(34);
	$this->Cell(90,4.5,$linDadosCliente['Nome'],0,0,'L',0);
	$this->setX(133);
	$this->Cell(50,4.5,'VENCIMENTO',0,1,'L',0);
	
	$this->setX(34);
	$this->Cell(90,4.5,$dadosboleto["endereco1"],0,0,'L',0);
	$this->setX(133);
	$this->Cell(42,4.5,$linContaReceber[DataVencimento],0,1,'L',0);
	
	$this->setX(34);
	$this->Cell(47,4.5,$linDadosCliente[NomeCidade]." - ".$linDadosCliente[SiglaEstado]."   CEP: ".$dadosboleto["cep"],0,0,'L',0);
	
	
	$this->SetFont('Arial','B',7);
	$this->TextWithDirection(191.8,269.5,'REMETENTE:','L');
	$this->SetFont('Arial','',7);
	$this->TextWithDirection(191.8,264,'DEVOLUÇÃO ELETRÔNICA - CEDO','L');
	$this->TextWithDirection(191.8,261,'CAIXA POSTAL 42.481','L');
	$this->TextWithDirection(191.8,258,'SÃO PAULO - SP','L');
	$this->TextWithDirection(191.8,255,'CEP 04218-970','L');
	
	$this->SetFont('Arial','',8.5);
	$this->TextWithDirection(50,263,'04218-970','L');
	
	#$this->RotatedImage("imagens/cep_cod_barra.png",95,240.1,61.2,6.9,180,"png");
	
	
	$i = 0;
	$i2 = 0;
	$cep_binario = "";
	$cep_binario_invertido="";
	$cep="04218-970";
	if($cep != ""){
		$bar[1] = '00011';
		$bar[2] = '00101';
		$bar[3] = '00110';
		$bar[4] = '01001';
		$bar[5] = '01010';
		$bar[6] = '01100';
		$bar[7] = '10001';
		$bar[8] = '10010';
		$bar[9] = '10100';
		$bar[0] = '11000';
		
		$coluna=14.8;
		$numero = str_replace('-','',$cep).$this->digito(str_replace('-','',$cep));
	
		
		while($i < strlen($numero)){
			$cep_binario .= $bar[$numero[$i]];
			
			$i++;
		}
		
		$cep_binario_invertido=strrev($cep_binario);
		$this->SetY(267);
		$this->setX($coluna);
		$this->Cell(0.4,3.4,'','R',0,0);
		$this->Cell(0.1,3.4,'','L',0,0);
		$this->Cell(0.1,3.4,'','L',0,0);
		$this->Cell(0.1,3.4,'','L',0,0);
		
		$coluna+=1.32;
		while($i2<strlen($cep_binario_invertido)){
			$this->SetFillColor(0, 0, 0);
			if($cep_binario_invertido[$i2]== '1'){
				$this->SetY(267);
				$this->setX($coluna);
				$this->Cell(0.4,3.4,'','R',0,0);
				$this->Cell(0.1,3.4,'','L',0,0);
				$this->Cell(0.1,3.4,'','L',0,0);
				$this->Cell(0.1,3.4,'','L',0,0);
				
			}else{
				$this->SetY(267);
				$this->setX($coluna);
				$this->Cell(0.4,1.5,'','R',0,0,0,true);
				$this->Cell(0.1,1.5,'','L',0,0,0,true);
				$this->Cell(0.1,1.5,'','L',0,0,0,true);
				$this->Cell(0.1,1.5,'','L',0,0,0,true);
				
			}
			$coluna+=1.32;
			$i2++;
		}
		$this->setX($coluna);
		$this->Cell(0.4,3.4,'','R',0,0);
		$this->Cell(0.1,3.4,'','L',0,0);
		$this->Cell(0.1,3.4,'','L',0,0);
		$this->Cell(0.1,3.4,'','L',0,0);
	}

?>