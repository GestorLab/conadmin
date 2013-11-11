<?
	global $Background;
	global $Modelo;
	
	$v1 = 3.5;
	$v2 = 3.0;
		
	$height = 0;
	$cont	= 0;
	
	if($IdNotaFiscalLayout == '' or $IdNotaFiscalLayout == null){
		$IdNotaFiscalLayout	= 1;
		$DescricaoTipoNotaFiscal = "NOTA FISCAL/FATURA DE SERVIÇO DE COMUNICAÇÃO";
	}

	// Dados Tomador do Servico
	$sqlTomador = "select
						Pessoa.TipoPessoa,
						Pessoa.RazaoSocial, 
						Pessoa.Nome,
						Pessoa.CPF_CNPJ, 
						Pessoa.RG_IE,
						PessoaEndereco.Endereco, 
						PessoaEndereco.Numero, 
						PessoaEndereco.Complemento, 
						PessoaEndereco.Bairro,
						PessoaEndereco.CEP,
						Cidade.NomeCidade, 
						Estado.SiglaEstado
					from
						ContaReceber,
						Pessoa,
						PessoaEndereco,
						Estado,
						Cidade
					where
						ContaReceber.IdLoja = $IdLoja and
						ContaReceber.IdContaReceber = $IdContaReceber and
						ContaReceber.IdPessoa = Pessoa.IdPessoa and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
						PessoaEndereco.IdPais = Estado.IdPais and
						PessoaEndereco.IdPais = Cidade.IdPais and
						PessoaEndereco.IdEstado = Estado.IdEstado and
						PessoaEndereco.IdEstado = Cidade.IdEstado and
						PessoaEndereco.IdCidade = Cidade.IdCidade";
	$resTomador = mysql_query($sqlTomador,$con);
	$Tomador = mysql_fetch_array($resTomador);

	if($Tomador[Numero] != ""){
		$Tomador[Numero] = ", ".$Tomador[Numero];
	}else{
		$Tomador[Numero] = $Tomador[Numero];
	}
	
	if($Tomador[Complemento] != ""){
		$Tomador[Complemento] = " - ".$Tomador[Complemento];
	}else{
		$Tomador[Complemento] = $Tomador[Complemento];
	}
	
	$Tomador[EnderecoFull] = $Tomador[Endereco].$Tomador[Numero].$Tomador[Complemento]."\n".$Tomador[Bairro]." - ".$Tomador[NomeCidade]."-".$Tomador[SiglaEstado]." - CEP: ".$Tomador[CEP];

	if($Tomador[TipoPessoa] == 1){
		$Tomador[Nome]			= $Tomador[RazaoSocial];
		$Tomador[CPF_CNPJFull]	= "CNPJ: ";
		$Tomador[RG_IEFull]		= " - IE: ".$Tomador[RG_IE];
	}else{
		$Tomador[CPF_CNPJFull]	= "CPF: ";
	}
	$Tomador[CPF_CNPJFull] .= $Tomador[CPF_CNPJ];

	// Dados da Nota Fiscal
	$sqlNF = "select
				NotaFiscal.IdNotaFiscal,
				NotaFiscal.Modelo,
				NotaFiscal.Serie,
				NotaFiscal.DataEmissao,
				NotaFiscal.ValorBaseCalculoICMS,
				NotaFiscal.ValorICMS,
				NotaFiscal.ValorOutros,
				NotaFiscal.ValorTotal,
				NotaFiscal.ObsVisivel,
				NotaFiscal.ValorDesconto,
				NotaFiscal.CodigoAutenticacaoDocumento,
				NotaFiscal.PeriodoApuracao
			from
				NotaFiscal
			where
				NotaFiscal.IdLoja = $IdLoja and
				NotaFiscal.IdContaReceber = $IdContaReceber and
				NotaFiscal.IdNotaFiscalLayout = $IdNotaFiscalLayout and
				NotaFiscal.IdStatus = 1";
	$resNF = mysql_query($sqlNF,$con);
	$linNF = mysql_fetch_array($resNF);

	$linNF[DataEmissao]			= dataConv($linNF[DataEmissao],'Y-m-d','d/m/Y');

	// Formata o Número da NF
	$linNF[IdNotaFiscalTemp]	= str_pad($linNF[IdNotaFiscal], 9, "0", STR_PAD_LEFT);
	$linNF[IdNotaFiscalTemp]	= substr($linNF[IdNotaFiscalTemp],0,3).".".substr($linNF[IdNotaFiscalTemp],3,3).".".substr($linNF[IdNotaFiscalTemp],6,3);
	
	// Formata Valores
	$linNF[ValorTotal]				= number_format($linNF[ValorTotal], 2, ',', '');
	$linNF[ValorOutros]				= number_format($linNF[ValorOutros], 2, ',', '');
	$linNF[ValorBaseCalculoICMS]	= number_format($linNF[ValorBaseCalculoICMS], 2, ',', '');
	
	if($linNF[ValorICMS] != NULL){
		$linNF[ValorICMS] = number_format($linNF[ValorICMS], 2, ',', '');
	}else{
		$linNF[ValorICMS] = '-';
	}

	// Formata o Periodo de Apuracao
	$linNF[PeriodoApuracao]			= explode('-',$linNF[PeriodoApuracao]);
	$linNF[PeriodoApuracao]			= strtoupper(substr(mes($linNF[PeriodoApuracao][1]),0,3))."/".$linNF[PeriodoApuracao][0];

	// Formata o Codigo de Autenticacao do Documento
	$linNF[CodigoAutenticacaoDocumento]	= strtoupper(substr($linNF[CodigoAutenticacaoDocumento],0,4).".".substr($linNF[CodigoAutenticacaoDocumento],4,4).".".substr($linNF[CodigoAutenticacaoDocumento],8,4).".".substr($linNF[CodigoAutenticacaoDocumento],12,4).".".substr($linNF[CodigoAutenticacaoDocumento],16,4).".".substr($linNF[CodigoAutenticacaoDocumento],20,4).".".substr($linNF[CodigoAutenticacaoDocumento],24,4).".".substr($linNF[CodigoAutenticacaoDocumento],28,4));


	// Formata a Série
	switch($linNF[Serie]){
		case 'U':
			$linNF[Serie] = 'Única';
			break;
	}

	// Default
	$this->margin_left = 10;
	$this->height_cell = 3;
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(0);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0.3);
	$this->ln();

	// Tomador do Serviço
	$this->SetFont('Arial','B',7.5);
	$this->MultiCell(101,4,"Tomador do Serviço",0,'L');
	$this->SetFont('Arial','',8);
	$this->MultiCell(101,3.5,$Tomador[Nome],0,'L');
	$this->MultiCell(101,3.5,$Tomador[EnderecoFull],0,'L');
	$this->MultiCell(101,3.5,$Tomador[CPF_CNPJFull].$Tomador[RG_IEFull],0,'L');

	// Quadro Nota Fiscal
	$this->SetXY(101,20);
	$this->SetFont('Arial','B',9);
	$this->MultiCell(0,7,$DescricaoTipoNotaFiscal,0,'C');
	$this->SetX(111);
	$this->SetFont('Arial','',8);
	$this->MultiCell(30,3.5,"Modelo $linNF[Modelo]\nSérie $linNF[Serie]\nEmissão: $linNF[DataEmissao]",0,'C');
	$this->SetXY(130,23.5);
	$this->SetFont('Arial','B',16);
	$this->MultiCell(0,9.5,"Nº $linNF[IdNotaFiscalTemp]",0,'C');

	// Linha
	$this->SetY(40);
	$this->Cell(190,0,'','T');

	// Lançamentos
	$this->ln();
	$this->SetFont('Arial','B',7.5);
	$this->MultiCell(90,4,"Lançamentos",0,'L');
	$this->SetFont('Arial','B',8);
	$this->Cell(6.5,3.5,'Item',0,0,'C');
	$this->Cell(20,3.5,'Serviço',0,0,'C');
	$this->Cell(70,3.5,'Descrição',0,0,'C');
	$this->Cell(30,3.5,'Referência',0,0,'C');
	$this->Cell(25,3.5,'Valor (R$)',0,0,'R');
	$this->Cell(23.5,3.5,'Desconto (R$)',0,0,'R');
	$this->Cell(15,3.5,'ICMS',0,0,'R');
	$this->ln();
	$this->Cell(190,0,'','T');
	$cont++;

	$msgPos = 0;

	$sqlItensNF = "select
					NotaFiscalItem.IdNotaFiscalItem,
					NotaFiscalItem.IdLancamentoFinanceiro,
					Demonstrativo.Tipo,
					Demonstrativo.Codigo,
					Demonstrativo.Descricao,
					if(Demonstrativo.ExibirReferencia != 2, Demonstrativo.Referencia, '-') Referencia,
					NotaFiscalItem.ValorTotal,
					NotaFiscalItem.ValorDesconto,
					NotaFiscalItem.AliquotaICMS
				from
					NotaFiscalItem
						left join Demonstrativo on (NotaFiscalItem.IdLoja = Demonstrativo.IdLoja and NotaFiscalItem.IdLancamentoFinanceiro = Demonstrativo.IdLancamentoFinanceiro)
				where
					NotaFiscalItem.IdNotaFiscalLayout = $IdNotaFiscalLayout and
					NotaFiscalItem.IdLoja = $IdLoja and
					NotaFiscalItem.IdNotaFiscal = $linNF[IdNotaFiscal] and
					NotaFiscalItem.IdContaReceber = $IdContaReceber
				order by
					NotaFiscalItem.IdNotaFiscalItem";
	$resItensNF = mysql_query($sqlItensNF,$con);
	while($linItensNF = mysql_fetch_array($resItensNF)){

		// Trata as Variáveis
		$linItensNF[IdNotaFiscalItem]	= str_pad($linItensNF[IdNotaFiscalItem], 3, "0", STR_PAD_LEFT);
		$ValorFinal					   += $linItensNF[ValorTotal];
		$ValorFinalDesconto			   += $linItensNF[ValorDesconto];
		$linItensNF[ValorTotal]			= number_format($linItensNF[ValorTotal], 2, ',', '');
		$linItensNF[ValorDesconto]		= number_format($linItensNF[ValorDesconto], 2, ',', '');

		if($linItensNF[AliquotaICMS] != NULL){
			$linItensNF[AliquotaICMS]		= number_format($linItensNF[AliquotaICMS], 2, ',', '')."%";
		}

		$linNF[AloquotaICMS]			= $linItensNF[AliquotaICMS];

		if($linItensNF[IdLancamentoFinanceiro] == ''){
			$linItensNF[Descricao]	= getParametroSistema(5,3);
			$linItensNF[Referencia]	= 'Única';
		}

		$linItensNF[Descricao] = substr($linItensNF[Descricao], 0, 56);

		// Itens da Nota
		$this->ln();
		$this->SetFont('Arial','',7.5);
		$this->Cell(6.5,3,$linItensNF[IdNotaFiscalItem],0,0,'C');
		$this->Cell(20,3,$linItensNF[Tipo]." ".$linItensNF[Codigo],0,0,'C');
		$this->Cell(70,3,$linItensNF[Descricao],0,0,'L');
		$this->Cell(30,3,$linItensNF[Referencia],0,0,'C');
		$this->Cell(25,3,$linItensNF[ValorTotal],0,0,'R');
		$this->Cell(23.5,3,$linItensNF[ValorDesconto],0,0,'R');
		$this->Cell(15,3,$linItensNF[AliquotaICMS],0,0,'R');
		$cont++;

		if($linItensNF[Codigo] != ''){
			$sqlParametrosNF = "select
									ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro,
									ServicoNotaFiscalLayoutParametro.Valor
								from
									Contrato,
									ServicoNotaFiscalLayoutParametro
								where
									Contrato.IdLoja = $IdLoja and
									Contrato.IdLoja = ServicoNotaFiscalLayoutParametro.IdLoja and
									Contrato.IdContrato = $linItensNF[Codigo] and
									Contrato.IdServico = ServicoNotaFiscalLayoutParametro.IdServico and
									ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayout = $IdNotaFiscalLayout";
			$resParametrosNF = mysql_query($sqlParametrosNF,$con);
			while($linParametrosNF = mysql_fetch_array($resParametrosNF)){
				$ParametrosNF[$linParametrosNF[IdNotaFiscalLayoutParametro]] = $linParametrosNF[Valor];
			}

			$MensagemNF[$msgPos] = $ParametrosNF[3];
			$msgPos++;
			
			$sqlMsgAuxNF = "select 
								NotaFiscalTipoParametro.Valor 
							from
								Contrato,
								Servico,
								NotaFiscalTipoParametro,
								NotaFiscalLayout,
								NotaFiscalLayoutParametro 
							where
								Contrato.IdLoja = $IdLoja and
								Contrato.IdLoja = Servico.IdLoja and
								Contrato.IdContrato = $linItensNF[Codigo] and
								Contrato.IdServico = Servico.IdServico and
								NotaFiscalTipoParametro.IdNotaFiscalTipo = Servico.IdNotaFiscalTipo and
								NotaFiscalLayoutParametro.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout and
								NotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro = 3 and
								NotaFiscalTipoParametro.IdNotaFiscalLayoutParametro = NotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro";
			$resMsgAuxNF = mysql_query($sqlMsgAuxNF,$con);
			$linMsgAuxNF = mysql_fetch_array($resMsgAuxNF);
		}
	}

	if($msg != ""){
		$msg .= " ".$linMsgAuxNF[Valor];
	}else{
		$msg .= $linMsgAuxNF[Valor];
	}
	$MensagemNF = @array_unique($MensagemNF);

	for($i=0; $i<count($MensagemNF); $i++){
		$msg .= " ".$MensagemNF[$i];
	}
	$ValorFinal			= number_format($ValorFinal, 2, ',', '');
	$ValorFinalDesconto = number_format($ValorFinalDesconto, 2, ',', '');

	$msg = trim($msg);
	
	if($msg != ''){
		$msg .= " ".$linNF[ObsVisivel];
	} else{
		$msg = $linNF[ObsVisivel];
	}
	
	$this->ln();
	$this->Cell(190,0,'','T');
	$this->ln();
	$this->SetFont('Arial','B',7.5);
	$this->Cell(126.5,3.5,'Total',0,0,'R');
	$this->Cell(25,3.5,$ValorFinal,0,0,'R');
	$this->Cell(23.5,3.5,$ValorFinalDesconto,0,0,'R');
	$this->Cell(15,3.5,'',0,0,'R');
	$this->ln();
	$this->Cell(190,0.01,'',1,0,'T');
	$cont++;
	
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
		$this->Ln(3);
		
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

	if($cont > 19){
		$cont = 26;
	}else{
		if($Reaviso == true){
			$this->SetY(135);
		}
	}

	$this->ln();
	$this->Cell(190,0,'','T');

	$this->ln();
	$this->SetFont('Arial','B',7.5);
	$this->Cell(22,3.5,'Base de Cálculo',0,0,'C');
	$this->Cell(14,3.5,'ICMS',0,0,'C');
	$this->Cell(22,3.5,'Outras',0,0,'C');
	$this->Cell(22,3.5,'Total',0,0,'C');
	$this->Cell(0.01,3.5,'',1,0,'C');
	$this->Cell(30,3.5,'Reservado ao fisco',0,0,'L');
	$this->Cell(68,3.5,'Período Fiscal:  ',0,0,'R');
	$this->SetFont('Arial','',7.5);
	$this->Cell(0,3.5,$linNF[PeriodoApuracao],0,0,'R');

	$this->ln();
	$this->SetFont('Arial','',8);
	$this->Cell(22,8,$linNF[ValorBaseCalculoICMS],0,0,'C');
	$this->Cell(14,8,$linNF[ValorICMS],0,0,'C');
	$this->Cell(22,8,$linNF[ValorOutros],0,0,'C');
	$this->Cell(22,8,$linNF[ValorTotal],0,0,'C');
	$this->Cell(0.01,8,'',1,0,'C');
	$this->SetFont('Arial','',9);
	$this->Cell(0,8,$linNF[CodigoAutenticacaoDocumento],0,0,'C');

	$this->ln();
	$this->Cell(190,0,'','T');
	
	$this->ln();
	$this->SetFont('Arial','',7);
	$this->MultiCell(0,3.1,$msg,0,'J');

	if($Reaviso == true){
		if($Background == 's'){
			include($Path."/modulos/administrativo/local_cobranca/reaviso_pdf_simples.php");
		}else{
			include("../../local_cobranca/reaviso_pdf_simples.php");
		}
	}
	
	if($Background == 's'){
		include($Path."/modulos/administrativo/local_cobranca/destinatario_pdf_simples.php");
	}else{
		include("../../local_cobranca/destinatario_pdf_simples.php");
	}
?>