<?
$height = 0;

	// Default
	$this->margin_left = 10;
	$this->height_cell = 3;
	$this->SetLineWidth(0.3);

	// Conteúdo - Demonstrativo
	$this->ln(2);
	
	$i=0;
	$valorTotal = 0;
	$j = 0;
	$sql = "select
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.ValorFinal Valor,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.IdPessoa
			from
				ContaReceberDados
			where
				ContaReceberDados.IdLoja = $IdLoja and
				ContaReceberDados.IdCarne = $IdCarne
			order by
				DataVencimento,
				IdContaReceber";
	$res = mysql_query($sql,$con);
	$qtdRows = mysql_num_rows($res);
	while($lin = mysql_fetch_array($res)){
		if($j==0){
			$local_IdContaReceber = $lin[IdContaReceber];
			$j = 1;
		}
		$ContaReceber[$i][NumeroDocumento]	= $lin[NumeroDocumento];
		$ContaReceber[$i][Valor]			= $lin[Valor];
		$ContaReceber[$i][DataVencimento]	= $lin[DataVencimento];
		$IdPessoa = $lin[IdPessoa];
		$i++;
	}

	$limit		= 18;

	if(($qtdRows/2) <= $limit){
		$limit = (int)($qtdRows/2);
		if($qtdRows%2 != 0){
			$limit++;
		}
	}

	$x			= 110;
	$y			= 17.5;
	$heightTemp	= 0;

	for($i = 0; $i <=$qtdRows; $i++){
		if($i == 0 || $i == $limit){
			
			// Conteúdo - Demonstrativo - Título (1)
			
			if($i >= $limit){
				$this->SetY($y+$heightTemp+5);
				$this->SetX($x);
				$heightTemp += ($this->height_cell)+5;
			}

			$this->SetFont('Arial','B',8);
			$this->Cell(15,$this->height_cell,'Boleto',0,0,'C',1);
			$this->Cell(25,$this->height_cell,'No. Documento',0,0,'C',1);
			$this->Cell(20,$this->height_cell,'Valor ('.getParametroSistema(5,1).')',0,0,'C',1);
			$this->Cell(30,$this->height_cell,'Vencimento',0,0,'C',1);
			$this->Ln();
			
			if($i >= $limit){
				$this->SetY($y+$heightTemp);
				$this->SetX($x);
			}
			
			$this->Cell(90,1,'','T');
			
			// Conteúdo - Demonstrativo - Registros
			$this->SetFont('Arial','',8);

		}
		
		if($ContaReceber[$i][NumeroDocumento] != ''){
			$NumeroDocumento = $ContaReceber[$i][NumeroDocumento];
			$DataVencimento	= dataConv($ContaReceber[$i][DataVencimento],'Y-m-d', 'd/m/Y');
			$Valor			= number_format($ContaReceber[$i][Valor],2,',','');

			$this->Ln();
			
			if($i >= $limit){
				$this->SetY($y+$heightTemp+1);
				$this->SetX($x);
			}

			$this->Cell(15,$this->height_cell,($i+1)."/".$qtdRows,0,0,'C',1);
			$this->Cell(25,$this->height_cell,$NumeroDocumento,0,0,'C',1);
			$this->Cell(20,$this->height_cell,$Valor,0,0,'R',1);
			$this->Cell(30,$this->height_cell,$DataVencimento,0,0,'C',1);
			$this->Ln();	
			
			if($i >= $limit){
				$this->SetY($y+$heightTemp);
				$this->SetX($x);
				$heightTemp += $this->height_cell + 1;
			}

			$height += $this->height_cell;
			
			$this->Cell(90,1,'','T');
			$height += 1;
		}

		if(($i+1) == $qtdRows){				
			$this->Ln();

			$this->SetY($y+$heightTemp);
			$this->SetX($x);
			$heightTemp += $this->height_cell + 1;
			
			$this->Cell(90,1,'','T');
			$height += 1;
		}
	}

	// Reaviso
	include($Path."modulos/administrativo/local_cobranca/reaviso_pdf_simples.php");

	// Destinatario	
	include($Path."modulos/administrativo/local_cobranca/destinatario_pdf_simples.php");
?>