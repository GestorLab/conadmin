<?
	global $Background;

	$height = 0;
	$cont	= 0;

	// Default
	$this->margin_left = 10;
	$this->height_cell = 3;
	$this->SetLineWidth(0.3);

	$i					= 0;
	$valorTotal			= 0;
	$DadosLancamento	= null;
	$IdLocalCobranca	= "";

	$sql = "select
				Tipo,
				Codigo,
				Descricao,
				if(ExibirReferencia != 2, Referencia, '-') Referencia,
				Valor,
				ValorDespesas,
				IdTerceiro,
				IdLocalCobranca
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
		$IdLocalCobranca 									= $lin[IdLocalCobranca];
		$i++;
	}

	$IdTerceiro = array_keys($DadosLancamento);

	for($i = 0; $i <count($DadosLancamento); $i++){
	
		$Lancamentos = array_keys($DadosLancamento[$IdTerceiro[$i]]);

		$DescricaoGrupo = 'Demonstrativo';
						
		$sqlTerceiro = "SELECT
							Pessoa.Nome,
							Pessoa.RazaoSocial,
							Pessoa.CPF_CNPJ
						FROM
							Pessoa,
							Terceiro
						WHERE
							Pessoa.IdPessoa = Terceiro.IdPessoa AND
							Terceiro.DestaqueDemonstrativo = 1 AND
							Pessoa.IdPessoa = $IdTerceiro[$i]";
		$resTerceiro = mysql_query($sqlTerceiro,$con);
		if($linTerceiro = @mysql_fetch_array($resTerceiro)){
			$DescricaoGrupo = "Serviços de Terceiros - $linTerceiro[Nome] ($linTerceiro[RazaoSocial]) - $linTerceiro[CPF_CNPJ]";
			$this->ln(4);
			$cont++;	
		}

		// Conteúdo - Demonstrativo
		$this->ln();
		$this->SetFont('Arial','B',9);
		$this->Cell(15,$this->height_cell,$DescricaoGrupo,0,0,'L',1);
		$this->ln(4);
		$cont++;
		
		// Conteúdo - Demonstrativo - Título
		$this->SetFont('Arial','B',8);
		$this->Cell(10,$this->height_cell,'Tipo',0,0,'L',1);
		$this->Cell(15,$this->height_cell,'Cod.',0,0,'L',1);
		$this->Cell(110,$this->height_cell,'Descrição',0,0,'L',1);
		$this->Cell(40,$this->height_cell,'Referência',0,0,'C',1);
		$this->Cell(15,$this->height_cell,'Valor ('.getParametroSistema(5,1).')',0,0,'C',1);
		$this->Ln();
		$this->Cell(190,1,'','T');
		$cont++;
		
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

		$this->Ln();
		$Msgs = @array_keys($MsgAuxiliarCobranca);
		
		for($iiii = 0; $iiii < count($MsgAuxiliarCobranca); $iiii++){
			$this->SetFont('Arial','',8);
			$this->MultiCell(190, 3.5, $MsgAuxiliarCobranca[$Msgs[$iiii]], 0, 'J');
			$height += 3;
			$cont++;
		}
		
	
	}
	
	$sqlMsgDemonstrativo = "select
							  MsgDemonstrativo 
							from
							  LocalCobranca
							where
							  IdLocalCobranca = $IdLocalCobranca and
							  IdLoja = $IdLoja ";
	$resMsgDemonstrativo = mysql_query($sqlMsgDemonstrativo,$con);	
	$linMsgDemonstrativo = mysql_fetch_array($resMsgDemonstrativo);
	if($linMsgDemonstrativo[MsgDemonstrativo]){
	//$this->Ln();
	$this->SetFont('Arial','',7.9);
	$this->MultiCell(190, 3.7, $linMsgDemonstrativo[MsgDemonstrativo], 0, 'J');
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
					DataVencimento
				from
					ContaReceberVencimento 
				where
					IdLoja = $IdLoja and
					IdContaReceber = $lin[IdContaReceber] 
				order by
					DataCriacao ASC 
				limit 1";
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
	
	include("reaviso_pdf_simples.php");
	include("destinatario_pdf_simples.php");
	
?>