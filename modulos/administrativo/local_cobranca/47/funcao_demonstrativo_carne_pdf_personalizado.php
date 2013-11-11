<?
$height = 0;

	// Default
	$this->margin_left 	= 10;
	$this->height_cell	= 3;
	$this->width_cell  	= 26.5;	
	$this->SetLineWidth(0.3);
	$IdLancamento		= "";
	$height_cell		= 4.5;
	$contLimet			= 0;
	$tipoLimet			= 1;
	$LancamentoTotal	= 0;
	$ValorTotal			= 0;
	$Height				= 1;
	$HeightAux			= 27.8;
	$WidthLancamento	= 0;
	$Ativo				= false;
	$Cont				= 1;
	$lanc				= 0;
	
	//Controladores de espaçamento
	$espacamento_Tipo 		= 41.8;
	$espacamento_NumeroDoc 	= 29;
	$espacamento_Vencimento = 155.5;

	// Conteúdo - Demonstrativo
	$this->ln(2);
	
	$this->Cell($this->width_cell,-1.5,'');
	$this->Ln();
	$this->SetFont('Arial','B',8);
	$this->Cell(-3.0,$this->height_cell,'',0,0,'C',1);
	$this->Cell($this->width_cell,$this->height_cell,'Nº Documento',0,0,'C',1);
	$this->Cell($this->width_cell+22.3,$this->height_cell,'',0,0,'L',1);
	$this->Text(43.5,23.3,'Cod.');
	$this->Cell($this->width_cell+17,$this->height_cell,'Referência',0,0,'L',1);
	$this->Cell($this->width_cell-6,$this->height_cell,'Valor ('.getParametroSistema(5,1).')',0,0,'R',1);
	$this->Cell($this->width_cell+5.5,$this->height_cell,'Vencimento',0,0,'C',1);
	$this->Cell($this->width_cell-5,$this->height_cell,'Valor Total ('.getParametroSistema(5,1).')',0,0,'C',1);
	$this->Ln();
	$this->Cell(190,1,'','T');
	
	$sql = "SELECT
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.ValorFinal,
				date_format(ContaReceberDados.DataVencimento,'%d/%m/%Y') DataVencimento,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.IdPessoa
			FROM
				ContaReceberDados
			WHERE
				ContaReceberDados.IdLoja = $IdLoja AND
				ContaReceberDados.IdCarne = $IdCarne AND
				(ContaReceberDados.IdStatus  != 2 AND ContaReceberDados.IdStatus != 0)
			ORDER BY
				NumeroDocumento";
	$res = mysql_query($sql,$con);
	$qtdRows = mysql_num_rows($res);
	while($lin = mysql_fetch_array($res)){
		$sql2 = "SELECT
					LancamentoFinanceiro.IdLancamentoFinanceiro,
					LancamentoFinanceiro.Valor,
					LancamentoFinanceiro.IdContaEventual,
					LancamentoFinanceiro.IdContrato,
					LancamentoFinanceiro.IdOrdemServico,
					LancamentoFinanceiro.IdEncargoFinanceiro,
					Demonstrativo.Tipo,
					Demonstrativo.Referencia 
				FROM
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber,
					Demonstrativo
				WHERE
					LancamentoFinanceiro.IdLoja = $IdLoja AND
					LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja AND
					Demonstrativo.IdLoja = LancamentoFinanceiro.IdLoja AND
					LancamentoFinanceiroContaReceber.IdContaReceber = $lin[IdContaReceber] AND
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro AND
					Demonstrativo.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro";
		$res2 = mysql_query($sql2,$con);
		$lanc = mysql_num_rows($res2);
		$this->SetFont('Arial','',8);
		while($lin2 = mysql_fetch_array($res2)){
			switch($lin2[Tipo]){
				case 'CO':
					$IdLancamento = $lin2[Tipo].".".$lin2[IdContrato];
					break;
				case 'OS':
					$IdLancamento = $lin2[Tipo].".".$lin2[IdOrdemServico];
					break;
				case 'EV':
					$IdLancamento = $lin2[Tipo].".".$lin2[IdContaEventual];
					break;
			}
			if($lanc == 1){
				$this->Ln();
				$this->Cell(22,$height_cell,$lin[NumeroDocumento],0,0,'C',0);
				$this->Cell(1.5,$height_cell,'',0,0,'C',0);
				$this->Cell(25.9,$height_cell,$IdLancamento,0,0,'C',0);
				$this->Cell(10,$height_cell,'',0,0,'C',0);
				$this->Cell(43.0,$height_cell,$lin2[Referencia],0,0,'C',0);
				$this->Cell(13.0,$height_cell,'',0,0,'C',0);
				$this->Cell($this->width_cell-5.5,$height_cell,number_format($lin2[Valor],2,',','.'),0,0,'R',0);
				$this->Cell(6.5,$height_cell,'',0,0,'C',0);
				$this->Cell(19,$height_cell,$lin[DataVencimento],0,0,'C',0);
				$this->Cell(7,$height_cell,'',0,0,'C',0);
				$this->Cell(22,$height_cell,number_format($lin[ValorFinal],2,',','.'),0,0,'R',0);
			}
			if($lanc == 2){
				if($Cont==2){
					$this->Ln();
					$this->Cell(22,$height_cell-4,$lin[NumeroDocumento],0,0,'C',0);
					$this->Cell(1.5,$height_cell,'',0,0,'C',0);
					$this->Cell(25.9,$height_cell,$IdLancamento,0,0,'C',0);
					$this->Cell(10,$height_cell,'',0,0,'C',0);
					$this->Cell(43.0,$height_cell,$lin2[Referencia],0,0,'C',0);
					$this->Cell(13.0,$height_cell,'',0,0,'C',0);
					$this->Cell($this->width_cell-5.5,$height_cell,number_format($lin2[Valor],2,',','.'),0,0,'R',1);
					$this->Cell(6.5,$height_cell,'',0,0,'C',0);
					$this->Cell(19,$height_cell-4,$lin[DataVencimento],0,0,'C',0);
					$this->Cell(7,$height_cell,'',0,0,'C',0);
					$this->Cell(22,$height_cell-4,number_format($lin[ValorFinal],2,',','.'),0,0,'R',0);
					$this->Ln();
					$this->Cell(22,4,'',0,0,'C',0);
				}else{
					$this->Ln();
					$this->Cell(22,$height_cell,'',0,0,'C',1);
					$this->Cell(1.5,$height_cell,'',0,0,'C',1);
					$this->Cell(25.9,$height_cell,$IdLancamento,0,0,'C',1);
					$this->Cell(10,$height_cell,'',0,0,'C',1);
					$this->Cell(43.0,$height_cell,$lin2[Referencia],0,0,'C',1);
					$this->Cell(13.0,$height_cell,'',0,0,'C',1);
					$this->Cell($this->width_cell-5.5,$height_cell,number_format($lin2[Valor],2,',','.'),0,0,'R',1);
					$this->Cell(6.5,$height_cell,'',0,0,'C',1);
					$this->Cell(19,$height_cell,'',0,0,'C',1);
					$this->Cell(7,$height_cell,'',0,0,'C',1);
					$this->Cell(22,$height_cell,'',0,0,'C',1);
				}
			}elseif($lanc > 2){
				if($Cont==round(($lanc/2))){
					if(($Cont % 2 == 0) && ($lanc % 2 == 0)){
						$this->Ln();
						$this->Cell(22,$height_cell+2,$lin[NumeroDocumento],0,0,'C',0);
						$this->Cell(1.5,$height_cell,'',0,0,'C',0);
						$this->Cell(25.9,$height_cell,$IdLancamento,0,0,'C',0);
						$this->Cell(10,$height_cell,'',0,0,'C',0);
						$this->Cell(43.0,$height_cell,$lin2[Referencia],0,0,'C',0);
						$this->Cell(13.0,$height_cell,'',0,0,'C',0);
						$this->Cell($this->width_cell-5.5,$height_cell,number_format($lin2[Valor],2,',','.'),0,0,'R',0);
						$this->Cell(6.5,$height_cell,'',0,0,'C',0);
						$this->Cell(19,$height_cell+2,$lin[DataVencimento],0,0,'C',0);
						$this->Cell(7,$height_cell,'',0,0,'C',0);
						$this->Cell(22,$height_cell+2,number_format($lin[ValorFinal],2,',','.'),0,0,'R',0);
						$this->Ln();
						$this->Cell(22,-1.95,'',0,0,'C',0);
					}else{
						$this->Ln();
						$this->Cell(22,$height_cell-1,$lin[NumeroDocumento],0,0,'C',0);
						$this->Cell(1.5,$height_cell,'',0,0,'C',0);
						$this->Cell(25.9,$height_cell,$IdLancamento,0,0,'C',0);
						$this->Cell(10,$height_cell,'',0,0,'C',0);
						$this->Cell(43.0,$height_cell,$lin2[Referencia],0,0,'C',0);
						$this->Cell(13.0,$height_cell,'',0,0,'C',1);
						$this->Cell($this->width_cell-5.5,$height_cell,number_format($lin2[Valor],2,',','.'),0,0,'R',0);
						$this->Cell(6.5,$height_cell,'',0,0,'C',1);
						$this->Cell(19,$height_cell,$lin[DataVencimento],0,0,'C',0);
						$this->Cell(7,$height_cell,'',0,0,'C',1);
						$this->Cell(22,$height_cell,number_format($lin[ValorFinal],2,',','.'),0,0,'R',0);
						$this->Ln();
						$this->Cell(7,-0.1,'',0,0,'C',1);
					}	
				}else{
					$this->Ln();
					$this->Cell(22,$height_cell,'',0,0,'C',0);
					$this->Cell(1.5,$height_cell,'',0,0,'C',0);
					$this->Cell(25.9,$height_cell,$IdLancamento,0,0,'C',0);
					$this->Cell(10,$height_cell,'',0,0,'C',0);
					$this->Cell(43.0,$height_cell,$lin2[Referencia],0,0,'C',0);
					$this->Cell(13.0,$height_cell,'',0,0,'C',0);
					$this->Cell($this->width_cell-5.5,$height_cell,number_format($lin2[Valor],2,',','.'),0,0,'R',0);
					$this->Cell(6.5,$height_cell,'',0,0,'C',0);
					$this->Cell(19,$height_cell,'',0,0,'C',0);
					$this->Cell(7,$height_cell,'',0,0,'C',0);
					$this->Cell(22,$height_cell,'',0,0,'C',0);
				}
			}
			
			$contLimet  += $lanc;
			$Cont++;
			$valorTotal += $lin2[Valor];
		}
		$this->Ln();
		$this->Cell(190,0.5,'','T',0,'C',1);
		$this->Ln();
		$this->Cell(190,0.5,'',0,0,'C',0);
		$Cont=1;
	}
	$this->Ln();
	$this->Cell(190,1,'',0,0,'R',0);
	$this->Ln();
	$this->Cell(191,1,number_format($valorTotal,2,',','.'),0,0,'R',0);
		
	if($contLimet > 12){
		$this->AddPage();
	}
	// Reaviso
	include($Path."modulos/administrativo/local_cobranca/reaviso_pdf_simples.php");

	// Destinatario	
	include($Path."modulos/administrativo/local_cobranca/destinatario_pdf_simples.php");
?>