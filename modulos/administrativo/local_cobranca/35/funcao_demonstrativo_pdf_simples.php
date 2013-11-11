<?
	global $Background;

	$height 	= 0;
	$cont		= 0;
	$contador	= 0;
	$altura		= 35;
	
	// Default
	$this->margin_left = 10;
	$this->height_cell = 3;
	$this->SetLineWidth(0.3);

	$i					= 0;
	$valorTotal			= 0;
	$DadosLancamento	= null;
	include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	
	$sql = "select
				Tipo,
				Codigo,
				Descricao,
				if(ExibirReferencia != 2, Referencia, '-') Referencia,
				Valor,
				ValorDespesas,
				IdTerceiro
			from
				Demonstrativo
			where
				IdLoja = $IdLoja and
				IdContaReceber = $IdContaReceber
			order by
				IdTerceiro,
				Tipo,
				Codigo,
				IdLancamentoFinanceiro";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$valorTotal += $lin[Valor];

		$DadosLancamento[$lin[IdTerceiro]][$i][Tipo]		= $lin[Tipo];
		$DadosLancamento[$lin[IdTerceiro]][$i][Cod]			= $lin[Codigo];
		$DadosLancamento[$lin[IdTerceiro]][$i][Descricao]	= $lin[Descricao];
		$DadosLancamento[$lin[IdTerceiro]][$i][Referencia]	= $lin[Referencia];
		$DadosLancamento[$lin[IdTerceiro]][$i][Valor]		= $lin[Valor];
		$DadosLancamento[$lin[IdTerceiro]][Valor]			+= $lin[Valor];

		$i++;
	}

	$IdTerceiro = array_keys($DadosLancamento);

	for($i = 0; $i <count($DadosLancamento); $i++){
	
		$Lancamentos = array_keys($DadosLancamento[$IdTerceiro[$i]]);

		if($IdTerceiro[$i] == ''){
			$DescricaoGrupo = 'Demonstrativo';
		}else{				
			$sqlTerceiro = "select
								Nome,
								RazaoSocial,
								CPF_CNPJ
							from
								Pessoa
							where
								IdPessoa = $IdTerceiro[$i]";
			$resTerceiro = mysql_query($sqlTerceiro,$con);
			$linTerceiro = mysql_fetch_array($resTerceiro);

			$DescricaoGrupo = "Serviços de Terceiros - $linTerceiro[Nome] ($linTerceiro[RazaoSocial]) - $linTerceiro[CPF_CNPJ]";
			$this->ln(4);
			$cont++;	
		}
		$this->SetFont('Arial','',9);
		$this->cod_Cep_Barra($linDadosClienteCobranca[CEP]);
		$this->Image("imagens/cep_cod_barra.png",25,31,60.2,7.0,"png");
		$this->SetFont('Arial','',7);
		$this->Text(25,39.35,"CTC NLP U/RJ-PL1",0,'R',1);
		$this->SetFont('Arial','',10);
		$this->Text(25,42.85,"$dadosboleto[nome_sacado]",0,'R',1);
		$this->Text(25,46.95,"$dadosboleto[endereco01]",0,'R',1);
		$this->Text(25,50.75,"$dadosboleto[endereco02]",0,'R',1);
		$this->Text(25,54.75,"$linDadosClienteCobranca[CEP]\t\t\t\t\t\t\t$linDadosClienteCobranca[NomeCidade]\t\t\t\t\t\t\t$linDadosClienteCobranca[SiglaEstado]",0,'R',1);
		$this->Cell(100,12.25,'',0,'L',1);
		$this->SetFillColor(214,214,213);
		$this->SetFont('Arial','',10);
		$this->SetXY(113,31);
		$this->Cell(87,32.25,'k',0,'L',1,true);
		$this->Cell(-87,32.25,'',0,'L',1,true);

		if($CobrancaParametro[MensagemAoCliente] != ""){
			$this->SetXY(113,31);
			$CobrancaParametro[MensagemAoCliente] = str_replace('$Nome', $dadosboleto[nome_sacado], $CobrancaParametro[MensagemAoCliente]);
			$this->MultiCell(87,4.5,substr($CobrancaParametro[MensagemAoCliente],0,355),0,'J',true);
		}
		$letra = 115;
		$this->Ln();
		$this->SetY(61);
		$this->SetFillColor(255,255,255);
		$this->Ln();
		$this->SetLineWidth(0.4);
		$this->Cell(190,0.3,'',1,'L',1);
		$this->Ln();
		//Linha do quadro de numero 1
		// Conteúdo - Demonstrativo
		$this->ln(1);
		$this->SetFont('Arial','B',8);
		$this->Cell(15,2.0,$DescricaoGrupo,0,0,'L',1);
		$this->ln();
		$cont++;
		
		// Conteúdo - Demonstrativo - Título
		$this->SetFont('Arial','B',8);
		$this->SetY(69);
		$this->Cell(10,$this->height_cell,'Tipo',0,0,'L',1);
		$this->Cell(15,$this->height_cell,'Cod.',0,0,'L',1);
		$this->Cell(110,$this->height_cell,'Descrição',0,0,'L',1);
		$this->Cell(40,$this->height_cell,'Referência',0,0,'C',1);
		$this->Cell(15,$this->height_cell,'Valor ('.getParametroSistema(5,1).')',0,0,'C',1);
		$this->Ln();
		$this->SetFillColor(214,214,213);
		$this->Cell(190,0.5,'','T',1);
		$this->SetFillColor(255,255,255);
		$cont++;
		$this->SetLineWidth(0.3);
		// Conteúdo - Demonstrativo - Registros
		$this->SetFont('Arial','',8);

		$MsgAuxiliarCobranca = Array();
		$MsgAuxiliarCobrancaTemp = Array();

		for($ii = 0; $ii <count($Lancamentos); $ii++){
			if($DadosLancamento[$IdTerceiro[$i]][$Lancamentos[$ii]][Tipo] != ''){
				$Tipo		= $DadosLancamento[$IdTerceiro[$i]][$Lancamentos[$ii]][Tipo];
				$Cod		= $DadosLancamento[$IdTerceiro[$i]][$Lancamentos[$ii]][Cod];
				$Descricao	= $DadosLancamento[$IdTerceiro[$i]][$Lancamentos[$ii]][Descricao];
				$Referencia	= $DadosLancamento[$IdTerceiro[$i]][$Lancamentos[$ii]][Referencia];
				$Valor		= number_format($DadosLancamento[$IdTerceiro[$i]][$Lancamentos[$ii]][Valor],2,',','');

				if($DadosLancamento[$IdTerceiro[$i]][$Lancamentos[$ii]][Tipo] == 'CO'){
					$sqlMensagem = "select
										Servico.IdServico,
										Servico.MsgAuxiliarCobranca
									from
										Contrato,
										Servico
									where
										Contrato.IdLoja = $IdLoja and
										Contrato.IdLoja = Servico.IdLoja and
										Contrato.IdServico = Servico.IdServico and
										Contrato.IdContrato = ".$DadosLancamento[$IdTerceiro[$i]][$Lancamentos[$ii]][Cod];
					$resMensagem = mysql_query($sqlMensagem,$con);
					$linMensagem = mysql_fetch_array($resMensagem);

					if($linMensagem[MsgAuxiliarCobranca] != ''){					
						if(!in_array(trim($linMensagem[MsgAuxiliarCobranca]), $MsgAuxiliarCobrancaTemp)){						
							$MsgAuxiliarCobranca[$linMensagem[IdServico]] = $MsgAuxiliarCobrancaTemp[$linMensagem[IdServico]] = trim($linMensagem[MsgAuxiliarCobranca]);

							$QtdAst = count($MsgAuxiliarCobranca);
							$QtdAst++;
							
							$MsgAuxiliarCobranca[$linMensagem[IdServico]] = str_pad("",$QtdAst, "*").$MsgAuxiliarCobranca[$linMensagem[IdServico]];
							$Descricao .= str_pad("",$QtdAst, "*");
						}
					}
				}

				$Descricao = explode("\n", $Descricao);

				$this->Ln();
				$this->Cell(10,$this->height_cell,$Tipo,0,0,'L',1);
				$this->Cell(15,$this->height_cell,$Cod,0,0,'L',1);
				$this->Cell(110,$this->height_cell,$Descricao[0],0,0,'L',1);
				$this->Cell(40,$this->height_cell,$Referencia,0,0,'C',1);
				$this->Cell(15,$this->height_cell,$Valor,0,0,'R',1);
				$this->Ln();	
				$height += $this->height_cell;
				$cont++;

				$count = count($Descricao);

				if($count > 0){
					for($iii = 1; $iii < $count; $iii++){
						if(trim($Descricao[$iii]) != ''){
							$this->Ln(1);
							$this->Cell(10,$this->height_cell,'',0,0,'L',1);
							$this->Cell(15,$this->height_cell,'',0,0,'L',1);
							$this->Cell(110,$this->height_cell,$Descricao[$iii],0,0,'L',1);
							$this->Cell(40,$this->height_cell,'',0,0,'C',1);
							$this->Cell(15,$this->height_cell,'',0,0,'R',1);
							$this->Ln();	
							$height += $this->height_cell;
							$cont++;
						}
					}
				}

				$this->Cell(190,1,'','T');
				$height += 1;

			}
		}

		if($IdTerceiro[$i] == ''){
			$sql = "select ValorDespesas from ContaReceber where IdLoja=$IdLoja and IdContaReceber=$IdContaReceber";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			$valorTotal += $lin[ValorDespesas];

			if($lin[ValorDespesas] > 0){
				
				$lin[ValorDespesas] = number_format($lin[ValorDespesas],2,',','');

				$this->Ln();
				$this->Cell(10,$this->height_cell,"",0,0,'L',1);
				$this->Cell(15,$this->height_cell,"",0,0,'L',1);
				$this->Cell(110,$this->height_cell,getParametroSistema(5,3),0,0,'L',1);
				$this->Cell(40,$this->height_cell,"",0,0,'L',1);
				$this->Cell(15,$this->height_cell,$lin[ValorDespesas],0,0,'R',1);
				$this->Ln();
				$this->Cell(190,1,'','T');
				$cont++;
			}
			$this->SetLineWidth(0.4);
			if(count($DadosLancamento) == 1){
				$valorTotal = number_format($valorTotal,2,',','');
	
				// Conteúdo - Demonstrativo - Registros - Total
				$this->Ln();
				$this->Cell(105);
				$this->SetFont('Arial','B',8);
				$this->Cell(60,$this->height_cell,'Total',0,0,'C',1);
				$this->Cell(25,$this->height_cell,getParametroSistema(5,1).' '.$valorTotal,0,0,'R',1);
				$this->Ln();
				$this->Cell(190,1,'','T');
				$cont++;
			}
		}
		$this->SetLineWidth(0.3);
		$this->Ln();
		$Msgs = @array_keys($MsgAuxiliarCobranca);
		
		for($iiii = 0; $iiii < count($MsgAuxiliarCobranca); $iiii++){
			$this->SetFont('Arial','',7);
			$this->MultiCell(190, 3, $MsgAuxiliarCobranca[$Msgs[$iiii]], 0, 'J');
			$height += 3;
			$cont++;
		}
	}

	$sql = "
		select
			ContaReceber.IdContaReceber,
			ContaReceber.IdPessoa,
			ContaReceber.MD5,
			ValorContaReceber,
			ValorMulta,
			ValorJuros,
			ValorTaxaReImpressaoBoleto,
			ValorOutrasDespesas,
			ValorDesconto,
			ValorContaReceber
		from
			ContaReceber,
			ContaReceberVencimento
		where
			ContaReceber.IdLoja = $IdLoja and
			ContaReceber.IdLoja = ContaReceberVencimento.IdLoja and
			ContaReceber.IdContaReceber = $IdContaReceber and
			ContaReceber.IdContaReceber = ContaReceberVencimento.IdContaReceber and
			ContaReceber.DataVencimento = ContaReceberVencimento.DataVencimento";
	$res = mysql_query($sql,$con);	
	$lin = mysql_fetch_array($res);
	
	$lin["valor_desconto"]			= $lin[ValorDesconto];
	$lin["valor_outras_deducoes"]	= '0.00';
	$lin["valor_mora_multa"]		= $lin[ValorMulta] + $lin[ValorJuros];		
	$lin["valor_outros_acrescimos"]	= $lin[ValorTaxaReImpressaoBoleto] + $lin[ValorOutrasDespesas];
	$lin["valor_cobrado"]			= ((($lin["ValorContaReceber"] + $lin["valor_mora_multa"] + $lin["valor_outros_acrescimos"])-$lin["valor_desconto"])-$lin["valor_outras_deducoes"]);	
	$lin["valor_cobrado"] 			= number_format($lin["valor_cobrado"], 2, ',', '');
	$lin["valor_outros_acrescimos"] = number_format($lin["valor_outros_acrescimos"], 2, ',', '');
	$lin["valor_mora_multa"] 		= number_format($lin["valor_mora_multa"], 2, ',', '');
	$lin["valor_outras_deducoes"]	= number_format($lin["valor_outras_deducoes"], 2, ',', '');
	$lin["valor_documento"]			= $lin["ValorContaReceber"];
	$lin["valor_documento"]			= number_format($lin["valor_documento"], 2, ',', '');
	$lin["valor_desconto"]			= number_format($lin["valor_desconto"], 2, ',', '');
	
	if(str_replace(",",".",$lin["valor_desconto"]) > 0 || str_replace(",",".",$lin["valor_mora_multa"]) > 0 || str_replace(",",".",$lin["valor_outras_deducoes"]) > 0 || str_replace(",",".",$lin["valor_outros_acrescimos"]) > 0){	
	
		$sql = "select
				min(DataVencimento) DataVencimento
			from
				ContaReceberVencimento
			where
				IdLoja = $IdLoja and
				IdContaReceber = $lin[IdContaReceber]";
		$res2 = mysql_query($sql,$con);	
		$lin2 = mysql_fetch_array($res2);
		$lin2[DataVencimento] = dataConv($lin2[DataVencimento], "Y-m-d", "d/m/Y");

		$this->Ln();
		$this->Ln();
		
		// Conteúdo - Atualização do Vencimento
		$this->SetFont('Arial','B',8);
		$this->Cell(45,$this->height_cell,'(-) Desconto / Abatimento',0,0,'L',1);
		$this->Cell(40,$this->height_cell,'(-) Outras deduções',0,0,'L',1);
		$this->Cell(30,$this->height_cell,'(+) Mora / Multa',0,0,'L',1);
		$this->Cell(42,$this->height_cell,'(+) Outros acréscimos',0,0,'C',1);
		$this->Cell(40,$this->height_cell,'(=) Valor cobrado',0,0,'C',1);
		$this->Ln();
		$this->Cell(190,1,'','T');
		$cont++;
		
		$this->Ln();
		$this->Cell(45,$this->height_cell,$lin[valor_desconto],0,0,'C',1);
		$this->Cell(35,$this->height_cell,$lin[valor_outras_deducoes],0,0,'C',1);
		$this->Cell(33,$this->height_cell,$lin[valor_mora_multa],0,0,'C',1);
		$this->Cell(50,$this->height_cell,$lin[valor_outros_acrescimos],0,0,'C',1);
		$this->Cell(0,$this->height_cell,getParametroSistema(5,1).' '.$lin[valor_cobrado],0,0,'R',1);
		$this->Ln();	
		$this->Cell(190,1,'','T');
		$cont++;
		
		$this->Ln();
		$this->SetFont('Arial','',8);	
		$this->Cell(45,$this->height_cell,"Vencimento original: ".$lin2[DataVencimento],0,0,'C',1);
		$cout++;
	}
	$this->Line(10,114,200,114);
	$this->Ln();
	$this->Cell(190,29.3,"",0,0);
	$this->Ln();
	$this->SetFillColor(214,214,213);
	$this->SetFont('Arial','B',10);
	$this->SetY(116.05);
	$this->Cell(85,32,"",0,0,'L',0);
	$this->Cell(15,32,"",0,0,'L',0);
	$this->Cell(90,32,"",0,0,'L',1);
	$this->SetFont('Arial','',10);
	if($CobrancaParametro[Promocoes] != ""){
		$this->Ln();
		$this->SetY(116.1);
		$this->SetX(110);
		$this->MultiCell(85,4.5,$CobrancaParametro[Promocoes],0,'J',1);
	}
	
	$this->Ln();
	
	$this->Ln();
	$this->Cell(85,32,"",0,0,'L',0);
	$this->Ln();
	include("reaviso_pdf_simples.php");
?>
