<?
	if($Path != ''){
		include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	}else{
		include("../informacoes_default.php");
	}

	// Reaviso
	if($cont > 16){
		$this->AddPage();
		$this->Cabecalho($IdLoja, $con);
	}

	$this->Ln();

	if($IdContaReceber != ''){
		$sqlIdPessoa = "select
							IdPessoa
						from
							ContaReceber
						where
							IdLoja = $IdLoja and
							IdContaReceber = $IdContaReceber";

		$sqlContaReceberEmAbertoComplemento = "and ContaReceberDados.IdContaReceber != $IdContaReceber";
	}

	if($IdCarne != ''){
		$sqlIdPessoa = "select
							IdPessoa
						from
							ContaReceber
						where
							IdLoja = $IdLoja and
							IdCarne = $IdCarne
						limit 0,1";
	}

	$resIdPessoa = mysql_query($sqlIdPessoa,$con);
	$linIdPessoa = mysql_fetch_array($resIdPessoa);
	
		
	$sql = "
			select 
				LocalCobranca.AvisoFaturaAtraso 
			from 
				ContaReceber,
				LocalCobranca
			where 
				ContaReceber.IdLoja = $IdLoja and 
				ContaReceber.IdLoja = LocalCobranca.IdLoja and
				ContaReceber.IdContaReceber = $IdContaReceber and
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
	$resContaReceber = mysql_query($sql,$con);	
	$linContaReceber = mysql_fetch_array($resContaReceber);	
	
	if($linContaReceber[AvisoFaturaAtraso] == 1){
		if($CobrancaParametro[Reaviso] != 2){
	
			$i = 0;
			$valorTotal = 0;
			$sql = "select
						ContaReceberDados.IdContaReceber,
						ContaReceberDados.NumeroDocumento,
						ContaReceberDados.ValorFinal Valor,
						ContaReceberDados.DataVencimento,
						ContaReceberDados.DataLancamento
					from
						ContaReceberDados
					where
						ContaReceberDados.IdLoja = $IdLoja and
						ContaReceberDados.IdPessoa = $linIdPessoa[IdPessoa] and
						ContaReceberDados.IdStatus = 1 and
						ContaReceberDados.DataVencimento < curdate()
	
						$sqlContaReceberEmAbertoComplemento
	
					order by				
						ContaReceberDados.NumeroDocumento";
			$res = @mysql_query($sql,$con);
			while($lin = @mysql_fetch_array($res)){
	
				$i++;
	
				if($i == 1){
	
					// Quadro de Fundo
					$this->SetY(55.5);
					$this->SetFillColor(215, 215, 215);
					$this->Cell(79,39,'',0,0,'L',1);
					$this->SetY(55.5);
					
					// Conteúdo - Reaviso de Vencimento
					$this->SetX(12);
					$this->SetFont('Arial','B',11);
					$this->Write(5,'Fatura(s) em atraso*');
					$this->Ln();
					$height += 6;
					$cont++;
			
					// Conteúdo - Reaviso de Vencimento - Título
					$this->SetX(12);
					$this->SetFont('Arial','B',8);
					$this->Cell(25,$this->height_cell,'Data Lançamento',0,0,'L',1);
					$this->Cell(25,$this->height_cell,'Vencimento',0,0,'C',1);
					$this->Cell(25,$this->height_cell,'Valor ('.getParametroSistema(5,1).')',0,0,'C',1);
					$this->Ln();
					$height += $this->height_cell;
					$cont++;
	
					$this->SetX(12);
					$this->Cell(75,1,'','T');
					$height += 1;
	
				}
	
				$valorTotal += $lin[Valor];
	
				if($i <= 5){
					$lin[ValorLancamento] = number_format($lin[Valor],2,',','');
			
					$lin[DataVencimento] = dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
					$lin[DataLancamento] = dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
			
					$this->Ln();
					$this->SetX(12);
					$this->SetFont('Arial','',8);
					$this->Cell(25,$this->height_cell,$lin[DataLancamento],0,0,'L',1);
					$this->Cell(25,$this->height_cell,$lin[DataVencimento],0,0,'C',1);
					$this->Cell(25,$this->height_cell,$lin[ValorLancamento],0,0,'R',1);
					$this->Ln();			
					$height += $this->height_cell;
			
					$this->SetX(12);
					$this->Cell(75,1,'','T');
					$height += 1;
					$cont++;
	
				}
			}
			if($i > 0){			
				$valorTotal = getParametroSistema(5,1).' '.number_format($valorTotal,2,',','');
	
				$this->Ln();
				$this->SetX(12);
				$this->Cell(20);
				$this->SetFont('Arial','B',8);
				$this->Cell(23,$this->height_cell,'Total em atraso:',0,0,'C',1);
				$this->Cell(15,$this->height_cell,$valorTotal,0,0,'C',1);
				$this->Ln();
				$height += $this->height_cell;
				$cont++;
	
				$this->SetX(12);
				$this->Cell(75,1,'','T');			
				$height += 1;
				$this->Ln();
				$cont++;		
	
				$this->SetFont('Arial','',7);
				$this->Write(3,'*Caso o pagamento já tenha sido efetuado, desconsidere este aviso.');		
				$height += 3;
				$this->Ln();
				$cont++;		
				
				$this->SetFont('Arial','',7);
				$this->Write(3,'*Relação parcial de fatura(s) em atraso.');		
				$height += 3;		
				$this->Ln();
				$cont++;
			}
		}
	}
?>
