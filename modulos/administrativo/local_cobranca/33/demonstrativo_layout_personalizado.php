<?
	global $Background;
	global $dadosboleto;
	global $posY;
	
	include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	
	$nnum = $CobrancaParametro[InicioNossoNumero] . formata_numero($linContaReceber[NumeroDocumento],8,0);
	$dv_nosso_numero = digitoVerificador_nossonumero($nnum);
	$nossonumero_dv ="$nnum$dv_nosso_numero";
	$nossonumero = substr($nossonumero_dv,0,10).'-'.substr($nossonumero_dv,10,1);
	
	$this->Text(66.7,36.5,'X');
	$this->Text(108.3,36.5,'=');

	$this->Cell(77.5,3.0,'','T',0,'L',0);
	$this->Cell(113.0,2.9,'','T',0,'L',0);
	
	$this->Ln();
	$this->SetFont('Arial','',7.0);
	//$this->SetFillColor(0,0,0);
	$this->Cell(0.4,3.0,'',0,0,'',0);
	$this->Cell(77.5,3.0,'Cedente','TL',0,'L',0);
	$this->Cell(0.4,3.0,'',0,0,'',0);
	$this->Cell(37,3.0,'Agência/Código do Cedende','TL',0,'L',0);
	$this->Cell(0.4,3.0,'',0,0,'',0);
	$this->Cell(36.9,3.0,'Data de Emissão','TL',0,'L',0);
	$this->Cell(0.4,3.0,'',0,0,'',0);
	$this->Cell(37.1,3.0,'Vencimento','RLT',0,'L',0);
	$this->Cell(0.3,3.0,'',0,0,'',0);
	
	$this->Ln();
	$this->SetFont('Arial','',8.0);
	$this->Cell(0.4,3.5,'',0,0,'',0);
	$this->Cell(77.5,3.5,$dadosboleto['cedente'],'L',0,'L',0);
	$this->Cell(0.4,3.5,'',0,0,'',0);
	$this->Cell(37,3.5,$CobrancaParametro[Agencia]." / ".$CobrancaParametro[ContaCedente]."-".$CobrancaParametro[ContaCedenteDigito],'L',0,'C',0);
	$this->Cell(0.4,3.5,'',0,0,'',0);
	$this->Cell(36.9,3.5,$linContaReceber[DataLancamento],'L',0,'C',0);
	$this->setFont('Arial','',9);
	$this->Cell(0.4,3.5,'','',0,'',0);
	$this->Cell(37.1,3.5,$linContaReceber[DataVencimento],'LR',0,'C',0);
	$this->Cell(0.3,3.5,'',0,0,'',0);
	
	$this->Ln();
	$this->SetFont('Arial','',7.0);
	//$this->SetFillColor(0,0,0);
	$this->Cell(0.4,3.0,'','',0,'',0);
	$this->Cell(114.9,3.0,'Sacado','TL',0,'L',0);
	$this->Cell(0.4,3.0,'',0,0,'',0);
	$this->Cell(37.0,3.0,'Número Documento','TL',0,'L',0);
	$this->Cell(0.3,3.0,'',0,0,'',0);
	$this->Cell(37.1,3.0,'Nosso Número','TRL',0,'L',0);
	$this->Cell(0.3,3.0,'',0,0,'',0);
	
	$this->Ln();
	$this->SetFont('Arial','',9);
	//$this->SetFillColor(0,0,0);
	$this->Cell(0.4,3.5,'',0,0,'',0);
	$this->Cell(114.9,3.5,$dadosboleto["nome_sacado"],'L',0,'',0);
	$this->SetFont('Arial','',8);
	$this->Cell(0.4,3.5,'',0,0,'',0);
	$this->Cell(37.0,3.5,$linContaReceber[NumeroDocumento],'L',0,'C',0);
	$this->Cell(0.3,3.5,'',0,0,'',0);
	$this->Cell(37.1,3.5,$nossonumero,'RL',0,'C',0);
	$this->Cell(0.3,3.5,'',0,0,'',0);
	
	$this->Ln();
	$this->SetFont('Arial','',7.0);
	//$this->SetFillColor(0,0,0);
	$this->Cell(0.4,3.0,'',0,0,'',0);
	$this->Cell(20.55,3.0,"Espécie",'TL',0,'L',0);
	$this->Cell(0.4,3.0,'','',0,'',0);
	$this->Cell(36.1,3.0,'Quantidade Moeda','TL',0,'L',0);
	$this->Cell(0.4,3.0,'','',0,'',0);
	$this->Cell(41.1,3.0,'Valor','TL',0,'L',0);
	$this->Cell(0.4,3.0,'',0,0,'',0);
	$this->Cell(42.6,3.0,'Valor Documento','TL',0,'L',0);
	$this->Cell(0.4,3.0,'',0,0,'',0);
	$this->Cell(47.8,3.0,'Descontos / Abatimentos','TLR',0,'L',0);
	$this->Cell(0.3,3.0,'',0,0,'',0);
	
	$this->SetFont('Arial','',9);
	
	$this->Ln();
	$this->SetFont('Arial','',9);
	//$this->SetFillColor(0,0,0);
	$this->Cell(0.4,3.65,'',0,0,'',0);
	$this->Cell(20.55,3.65,"         ".$CobrancaParametro[Especie],'LB',0,'C',0);
	$this->SetFont('Arial','',8);
	$this->Cell(0.4,3.75,'',0,0,'',0);
	$this->Cell(36.1,3.65,"",'LB',0,'C',0);
	$this->Cell(0.4,3.65,'',0,0,'',0);
	$this->Cell(41.1,3.65,'','LB',0,'C',0);
	$this->Cell(0.4,3.65,'',0,0,'',0);
	$this->Cell(42.6,3.65,$linContaReceber[ValorLancamento],'LB',0,'C',0);
	$this->Cell(0.4,3.65,'',0,0,'',0);
	$this->Cell(47.8,3.65,number_format($linContaReceberVencimento[ValorDesconto], 2, ',', ''),'RBL',0,'C',0);
	$this->Cell(0.3,3.75,'',0,0,'',0);
	
	$this->Ln();
	$this->SetFont('Arial','',7);
	$this->SetX(12);
	$this->Cell(78,8.5,'Demonstrativo',0,0,'L');
	
	$sqlMoeda = "select
					*
				from
					ParametroSistema
				where
					IdGrupoParametroSistema = 5 and
					IdParametroSistema = 1";
	$resMoeda = mysql_query($sqlMoeda,$con);
	$linMoeda = mysql_fetch_array($resMoeda);
	
	$this->Ln();
	$this->SetFont('Arial','',8);
	$this->SetX(11);
	$this->Cell(7.1,2.5,'Tipo',0,0,'R');
	$this->Cell(7.5,2.5,'Cod.',0,0,'R');
	$this->Cell(17.4,2.5,'Descrição',0,0,'R');
	$this->Cell(75.1,2.5,'Qtd.',0,0,'R');
	$this->Cell(24.9,2.5,"Valor Un.(".$linMoeda[ValorParametroSistema].")",0,0,'R');
	$this->Cell(28.5,2.5,'Referência',0,0,'R');
	$this->Cell(25.0,2.5,"Valor (".$linMoeda[ValorParametroSistema].")",0,0,'R');
	
	$this->Ln();
	$this->Cell(0.4,0.75,'',0,0,'',0);
	$this->Line(10.4,37.2,10.4,153);
	$this->Line(200.15,37.2,200.15,153);
	$this->Line(10.4,153,200.15,153);
	$this->Line(10.4,140,200.15,140);
	
	$this->Ln();
	$this->SetFont('Arial','',7);
	//$this->SetFillColor(0,0,0);
	$this->SetFont('Arial','',8);
	
	$MsgAuxiliarCobranca = Array();
	$MsgAuxiliarCobrancaTemp = Array();
	$cont = 0;
	$contador=0;
	$total=0;
	$linha = 55;
	$height=2.5;
	$sql = "select
				Demonstrativo.Descricao,
				Demonstrativo.Valor,
				Demonstrativo.Tipo,
				Demonstrativo.Codigo,
				Demonstrativo.Referencia,
				Demonstrativo.IdServico
			from
				LancamentoFinanceiroContaReceber left join (LancamentoFinanceiro,Demonstrativo) on(
					LancamentoFinanceiroContaReceber.IdLoja = $IdLoja and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					Demonstrativo.IdLoja = LancamentoFinanceiro.IdLoja
				)
			where
				LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and
				LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
				Demonstrativo.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro
				Limit 0,19";
	$res = mysql_query($sql,$con);
	if(mysql_num_rows($res) > 0){
		while($linDesc = mysql_fetch_array($res)){
			if($cont < 17){
				$this->Ln();
				$this->SetFillColor(0,0,0);
				$this->SetX(11);
				$this->Cell(7.0,3.65,$linDesc[Tipo],0,0,'L',0);
				$this->Cell(10.5,3.65,$linDesc[Codigo],0,0,'L',0);
				$this->Cell(85.2,3.65,$linDesc[Descricao],0,0,'L',0);
				$this->Cell(5.5,3.65,"1",0,0,'L',0);
				$this->Cell(4.1,3.65,"X",0,0,'L',0);
				$this->Cell(20.0,3.65,number_format($linDesc[Valor],2,',',''),0,0,'R',0);
				if(strlen($linDesc[Referencia]) > 15){
					$this->Cell(41,3.65,$linDesc[Referencia],0,0,'C',0);
				}else{
					$this->Cell(41,3.65,$linDesc[Referencia],0,0,'C',0);
				}
				$this->Cell(2,3.65,'',0,0,'L',0);
				$this->Cell(10.6,3.65,number_format($linDesc[Valor],2,',',''),0,0,'R',0);
				$this->Cell(0.4,4.0,'',0,0,'L',0);
				$total += $linDesc[Valor];
				
				if($linDesc[IdServico] != '' && $linDesc[IdServico] != null){
					$sqlMensagem = "select
										Servico.MsgAuxiliarCobranca
									from
										Servico
									where
										Servico.IdServico = $linDesc[IdServico]";		
					$resMensagem = mysql_query($sqlMensagem,$con);
					$linMensagem = mysql_fetch_array($resMensagem);

					if($linMensagem[MsgAuxiliarCobranca] != ''){					
						if(!in_array(trim($linMensagem[MsgAuxiliarCobranca]), $MsgAuxiliarCobranca)){						
							$MsgAuxiliarCobranca[$cont] = trim($linMensagem[MsgAuxiliarCobranca]);
							
							$QtdAst = count($MsgAuxiliarCobranca);
							$QtdAst++;
							
							$MsgAuxiliarCobranca[$linMensagem[MsgAuxiliarCobranca]] = str_pad("",$QtdAst, "*").$MsgAuxiliarCobranca[$linMensagem[MsgAuxiliarCobranca]];
							$Descricao .= str_pad("",$QtdAst, "*");
						}
					}
				}
				$cont++;
			}
			$contador = $cont;
		}
	}
	for($i=0; $i < $cont; $i++){
		if($i>=1){
			$linha+=11.5;
		}
	}
	$this->Ln();
	$this->Cell(172.5,9.5,'',0,0,'L',0);
	$this->Cell(16.2,0.1,'',1,0,'L',0);
	$this->Cell(18,2.5,'',0,0,'L',0);
	$this->SetFont('Arial','',8.0);
	$this->Ln();
	$this->Cell(178.0,0.1,"",0,0,'R',0);
	if($contador >= 17){
		$this->Cell(9.1,0.1,""."R$".number_format($total,2,',','')."*",0,0,'R',0);
		$this->Cell(175.5,3.1,"",0,0,'R',0);
		$this->SetFont('Arial','',7.5);
		$this->Ln();
		$this->Cell(3,0.1,"",0,0,'L',0);
		$this->MultiCell(175.5,3,"* Valor Parcial, A quantidade de lançamentos excede o limite máximo no demonstrativo. Para a lista completa acesse a central do assinante (".$UrlSistema = getParametroSistema(6,3).'/central'.").",0,'J');
	}else{
		$this->Cell(9.1,0.1,""."R$".number_format($total,2,',','')."",0,0,'R',0);
		
	}
	$this->Ln();
	$this->SetFont('Arial','',10.0);
	$this->Cell(18,0,'',0,0,'L',0);
	$this->Ln();
	
	for($iiii = 0; $iiii < count($MsgAuxiliarCobranca); $iiii++){
			$this->SetFont('Arial','',7);
			$this->SetXY(12,132);
			$this->MultiCell(188, 3, $MsgAuxiliarCobranca[$iiii], 0, 'J');
			$height += 3;
			$cont++;
	}
	
	$this->SetFont('Arial','',7);
	$this->Text(11.6,144,"Observações");
	$this->Text(11.6,147.5,$CobrancaParametro[ObservacoesDemonstrativoLinha1]);
	$this->Text(11.6,151.0,$CobrancaParametro[ObservacoesDemonstrativoLinha2]);
	
?>