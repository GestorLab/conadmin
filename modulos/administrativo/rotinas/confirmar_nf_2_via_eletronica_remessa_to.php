<?
	require('../../classes/fpdf/class.fpdf.php');

	// Dados do Contribuinte
	$sqlContribuinte = "select
							Pessoa.RazaoSocial,
							Pessoa.RG_IE,
							Pessoa.CPF_CNPJ,
							Pessoa.Telefone3 Telefone,
							NotaFiscalLayout.Modelo,
							NotaFiscalTipo.IdNotaFiscalTipo
						from
							NotaFiscalTipo,
							NotaFiscalLayout,
							Pessoa,
							PessoaEndereco,
							Estado,
							Cidade
						where
							NotaFiscalTipo.IdLoja = $local_IdLoja and
							NotaFiscalTipo.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
							NotaFiscalTipo.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout and
							Pessoa.IdPessoa = NotaFiscalTipo.IdPessoa and
							Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
							Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
							PessoaEndereco.IdPais = Estado.IdPais and
							PessoaEndereco.IdPais = Cidade.IdPais and
							PessoaEndereco.IdEstado = Estado.IdEstado and
							PessoaEndereco.IdEstado = Cidade.IdEstado and
							PessoaEndereco.IdCidade = Cidade.IdCidade";
	$resContribuinte = mysql_query($sqlContribuinte,$con);
	$Contribuinte = mysql_fetch_array($resContribuinte);

	$Contribuinte[Endereco] .= ", ".$Contribuinte[Numero];

	if($Contribuinte[Complemento] != ''){
		$Contribuinte[Endereco] .= " - ".$Contribuinte[Complemento];
	}

	// FIM - Dados Contribuinte

	// Parвmetros da Nota Fiscal
	$sqlParametro = "select
						IdNotaFiscalLayoutParametro,
						Valor
					from
						NotaFiscalTipoParametro
					where
						IdLoja = $local_IdLoja and
						IdNotaFiscalTipo = $Contribuinte[IdNotaFiscalTipo] and
						IdNotaFiscalLayout = $local_IdNotaFiscalLayout";
	$resParametro = mysql_query($sqlParametro, $con);
	while($linParametro = mysql_fetch_array($resParametro)){
		$Parametro[$linParametro[IdNotaFiscalLayoutParametro]] = $linParametro[Valor];
	}

	// FIM - Parвmetros da Nota Fiscal

	// Dados da Apuraзгo
	$Apuracao[Serie] = "001";
	$Apuracao[StatusArquivoExtra] = "N";

	$Arquivo[NomeArquivoExtra]		= formata_CPF_CNPJ($Contribuinte[CPF_CNPJ]).dataConv($local_MesReferencia,"m/Y","Ym")."C".$Apuracao[StatusArquivoExtra].".TXT";

	// FIM - Dados da Apuraзгo

	// Dados da NF
	$sqlNotaFiscal = "select
						count(*) QtdNF,
						min(NotaFiscal.DataEmissao) DataPrimeiraNF,
						max(NotaFiscal.DataEmissao) DataUltimaNF,
						min(NotaFiscal.IdNotaFiscal) NumeroPrimeiraNF,
						max(NotaFiscal.IdNotaFiscal) NumeroUltimaNF,
						sum(NotaFiscal.ValorTotal) ValorTotal,
						sum(NotaFiscal.ValorBaseCalculoICMS) ValorTotalBaseCalculoICMS,
						sum(NotaFiscal.ValorICMS) ValorTotalICMS,
						sum(NotaFiscal.ValorNaoTributado) ValorTotalNaoTributado,
						sum(NotaFiscal.ValorOutros) ValorTotalOutros
					from
						NotaFiscal,
						NotaFiscalTipo
					where
						NotaFiscal.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
						NotaFiscal.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND 
						NotaFiscalTipo.IdLojaCompartilhada = $local_IdLoja and
						NotaFiscal.IdLoja = NotaFiscalTipo.IdLoja AND 
						NotaFiscal.PeriodoApuracao = '$local_PeriodoApuracao'
					group by
						NotaFiscal.IdNotaFiscalLayout,
						NotaFiscal.PeriodoApuracao";
	$resNotaFiscal = mysql_query($sqlNotaFiscal,$con);
	$NotaFiscal = mysql_fetch_array($resNotaFiscal);

	if($NotaFiscal[QtdNF] == ''){
		$NotaFiscal[QtdNF] = 0;
	}

	// Dados da NF Cancelada
	$sqlNotaFiscalCancelada = "select
									count(*) QtdNF,
									sum(ValorTotal) ValorTotalCancelado,
									sum(ValorBaseCalculoICMS) ValorBaseCalculoICMSCancelado
								from
									NotaFiscal
								where
									IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
									IdLoja = $local_IdLoja and
									PeriodoApuracao = '$local_PeriodoApuracao' and
									IdStatus = 0
								group by
									IdNotaFiscalLayout,
									IdLoja,
									PeriodoApuracao";
	$resNotaFiscalCancelada = mysql_query($sqlNotaFiscalCancelada,$con);
	$NotaFiscalCancelada = mysql_fetch_array($resNotaFiscalCancelada);

	if($NotaFiscalCancelada[ValorTotalCancelado] == ""){
		$NotaFiscalCancelada[ValorTotalCancelado] = "0.00";
	}

	if($NotaFiscalCancelada[QtdNF] == ''){
		$NotaFiscalCancelada[QtdNF] = 0;
	}

	$Campo	= null;

	// Campo 1 (CNPJ) [14] 1-14 X
	$Campo[1] = formata_CPF_CNPJ($Contribuinte[CPF_CNPJ]);

	// Campo 2 (IE) [14] 15-28 X
	$Campo[2] = formata_IE($Contribuinte[RG_IE]);

	// Campo 3 (Razгo Social) [35] 29-63 X
	$Campo[3] = str_replace("'","",$Contribuinte[RazaoSocial]);
	$Campo[3] = preenche_tam($Campo[3], 35, 'X');

	// Campo 4 (Responsбvel pela apresentaзгo) [35] 64-98 X
	$Campo[4] = preenche_tam($Parametro[4], 35, 'X');

	// Campo 5 (Cargo do responsбvel pela apresentaзгo) [20] 99-118 X
	$Campo[5] = preenche_tam($Parametro[5], 20, 'X');

	// Campo 6 (Telefone do responsбvel pela apresentaзгo) [10] 119-128 X
	$Campo[6] = preenche_tam(formata_telefone($Contribuinte[Telefone]), 10, 'X');

	// Campo 7 (e-mail do responsбvel pela apresentaзгo) [35] 129-163 X
	$Campo[7] = preenche_tam($Parametro[6], 35, 'X');

	// Campo 8 (Perнodo Referencia) [6] 164-169 N
	$Campo[8] = preenche_tam(dataConv($local_MesReferencia,"m/Y","Ym"), 6, 'N');

	// Campo 9 (Modelo do documento fiscal) [2] 170-171 N
	$Campo[9] = preenche_tam($Contribuinte[Modelo], 2, 'N');

	// Campo 10 (Sйrie / Subsйrie) [3] 172-174 X
	$Campo[10] = preenche_tam($Apuracao[Serie], 3, 'X');

	// Campo 11 (Numero inicial da NF) [9] 175-183 N
	$Campo[11] = preenche_tam($NotaFiscal[NumeroPrimeiraNF], 9, 'N');

	// Campo 12 (Numero final da NF) [9] 184-192 N
	$Campo[12] = preenche_tam($NotaFiscal[NumeroUltimaNF], 9, 'N');

	// Campo 13 (Valor Total (com 2 decimais)) [12] 193-204 N
	$Campo[13] =  preenche_tam(formata_valor($NotaFiscal[ValorTotal]-$NotaFiscalCancelada[ValorTotalCancelado]), 12, 'N');

	// Campo 14 (BC ICMS (com 2 decimais)) [12] 205-216 N
	$Campo[14] =  preenche_tam(formata_valor($NotaFiscal[ValorTotalBaseCalculoICMS]-$NotaFiscalCancelada[ValorBaseCalculoICMSCancelado]), 12, 'N');

	// Campo 15 (ICMS (com 2 decimais)) [12] 217-228 X
	$Campo[15] =  preenche_tam(formata_valor($NotaFiscal[ValorTotalICMS]), 12, 'N');

	// Campo 16 (Operaзхes Isentas ou nгo tributadas (com 2 decimais)) [12] 229-240 N
	$Campo[16] =  preenche_tam(formata_valor($NotaFiscal[ValorTotalNaoTributado]), 12, 'N');

	// Campo 17 (Outros valores que nгo compхe a BC do ICMS (com 2 decimais)) [12] 241-252 N
	$Campo[17] =  preenche_tam(formata_valor($NotaFiscal[ValorTotalOutros]), 12, 'N');

	// Campo 18 (Situaзгo) [1] 253-253 X
	$Campo[18] =  preenche_tam("N", 1, 'X');

	// Gera a Linha
	$Linha = concatVar($Campo);

	$FileName = $PatchFile."/".$Arquivo[NomeArquivoExtra];

	@unlink($FileName);

	if($File = fopen($FileName, 'a')) {
		if(fwrite($File, $Linha)){

			$FileSize = filesize($FileName)/1024;
			$FileSize = number_format($FileSize, 2, '.', '');

			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Arquivo Extra (1 registro) $FileSize KB.";
		}else{			
			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Geraзгo do Arquivo Extra.";
		}
    }else{
		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Geraзгo do Arquivo Extra.";
	}
    fclose($File);

	$sql = "update NotaFiscal2ViaEletronicaArquivo set 
				NomeArquivoExtra = '$Arquivo[NomeArquivoExtra]',
				ConteudoArquivoExtra = '$Linha',
				LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento)
			where 
				IdLoja='$local_IdLoja' and 
				IdNotaFiscalLayout='$local_IdNotaFiscalLayout' and 
				MesReferencia='$local_MesReferencia' and
				Status = '$local_IdStatusArquivoMestre'";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;

	// Gera o recibo da entrega
	$Apuracao[StatusRecibo] = "N";
	$Arquivo[NomeArquivoRecibo] = "recibo_".formata_CPF_CNPJ($Contribuinte[CPF_CNPJ]).dataConv($local_MesReferencia,"m/Y","Ym").$Apuracao[StatusRecibo].".pdf";
	
	$pdf = new FPDF('P','cm','A4');
	
	$pdf->SetMargins(1.64, 1.5, 1.64);
	$pdf->AddPage();
	
	// INICIANDO CABEЗARIO //
	$pdf->SetFont('Arial','',8.5);
	$pdf->Cell(18,0.05,"","T",0,"L",false);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0.01,0.5,"",0,0,"L",false);
	$pdf->Cell(18,0.5,"Governo do Estado do Tocantins",0,0,"L",false);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0.01,0.5,"",0,0,"L",false);
	$pdf->Cell(0.01,0.5,"",0,0,"L",false);
	$pdf->Cell(18,0.5,"Secretaria da Fazenda",0,0,"L",false);
	$pdf->Ln();
	$pdf->Cell(0.01,0.5,"",0,0,"L",false);
	$pdf->Cell(18,0.5,"Recibo de Entrega de Arquivos - Convкnio ICMS 115/2003 – TARE ".$Parametro[11],0,0,"L",false);
	$pdf->Cell(1,0.5,"",0,0,"L",false);
	$pdf->Ln();
	$pdf->Cell(18,0.01,"","T",0,"L",false);
	$pdf->Cell(1,0.5,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->Cell(0.01,0.3,"",0,0,"L",false);
	$pdf->Cell(18,0.03,"A. EMPRESA EMITENTE DO DOCUMENTO FISCAL",0,0,"L",false);
	$pdf->Cell(1,0.3,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->SetFont('Arial','',8.5);
	$pdf->Cell(18,0.5,"Razгo Social","TLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(18,0.5,$Contribuinte[RazaoSocial],"BLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(9,0.5,"Inscriзгo Estadual","TLR",0,"L",false);
	$pdf->Cell(9,0.5,"CNPJ","TLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(9,0.5,$Contribuinte[RG_IE],"BLR",0,"L",false);
	$pdf->Cell(9,0.5,$Contribuinte[CPF_CNPJ],"BLR",0,"L",false);
	$pdf->Cell(9,1.2,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0.01,0.3,"",0,0,"L",false);
	$pdf->Cell(18,0.03,"B. IDENTIFICAЗГO DO ARQUIVO",0,0,"L",false);
	$pdf->Cell(1,0.3,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->SetFont('Arial','',8.5);
	$pdf->Cell(7.5,0.5,"Perнodo de referкncia","TLR",0,"L",false);
	$pdf->Cell(7.5,0.5,"Nome do Arquivo","TLR",0,"L",false);
	$pdf->Cell(3,0.5,"Status","TLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(7.5,0.5,$local_MesReferencia,"BLR",0,"L",false);
	$pdf->Cell(7.5,0.5,$Arquivo[NomeArquivoExtra],"BLR",0,"L",false);
	$pdf->Cell(3,0.5,$Apuracao[StatusArquivoExtra],"BLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(18,0.5,"Cуdigo de Autenticaзгo Digital","TLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(18,0.5,md5($Linha),"BLR",0,"L",false);
	$pdf->Cell(18,1.2,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0.01,0.3,"",0,0,"L",false);
	$pdf->Cell(18,0.03,"C. DADOS DO ARQUIVO",0,0,"L",false);
	$pdf->Cell(1,0.3,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->SetFont('Arial','',8.5);
	$pdf->Cell(18,0.5,"Sйrie das NF","TLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(18,0.5,$Apuracao[Serie],"BLR",0,"L",false);
	$pdf->Cell(1,1.2,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0.01,0.3,"",0,0,"L",false);
	$pdf->Cell(18,0.03,"D. RESPONSБVEL PELA APRESENTAЗГO DO ARQUIVO",0,0,"L",false);
	$pdf->Cell(1,0.3,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->SetFont('Arial','',8.5);
	$pdf->Cell(7.0,0.5,"Nome","TLR",0,"L",false);
	$pdf->Cell(7.0,0.5,"Cargo","TLR",0,"L",false);
	$pdf->Cell(4,0.5,"Assinatura","TLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(7.0,0.5,$Parametro[4],"BLR",0,"L",false);
	$pdf->Cell(7.0,0.5,$Parametro[5],"BLR",0,"L",false);
	$pdf->Cell(4,0.5,"","BLR",0,"L",false);
	
	$pdf->Ln();
	$pdf->Cell(7.0,0.5,"Telefone","TLR",0,"L",false);
	$pdf->Cell(7.0,0.5,"E-mail","TLR",0,"L",false);
	$pdf->Cell(4,0.5,"Data","TLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(7.0,0.5,$Contribuinte[Telefone],"BLR",0,"L",false);
	$pdf->Cell(7.0,0.5,$Parametro[6],"BLR",0,"L",false);
	$pdf->Cell(4,0.5,date("d/m/Y"),"BLR",0,"L",false);
	$pdf->Cell(1,1.2,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0.01,0.3,"",0,0,"L",false);
	$pdf->Cell(18,0.03,"E. RECEBIMENTO",0,0,"L",false);
	$pdf->Cell(1,0.3,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->SetFont('Arial','',8.5);
	$pdf->Cell(9.0,0.5,"Local e Data","TLR",0,"L",false);
	$pdf->Cell(9.0,0.5,"Assinatura e Carimbo","TLR",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(9.0,0.5,"","BLR",0,"L",false);
	$pdf->Cell(9.0,0.5,"","BLR",0,"L",false);
	
	$pdf->Ln();
	$pdf->Cell(9.0,11.2,"",0,0,"L",false);
	
	$pdf->Ln();
	$pdf->Cell(18.0,0.3,"","T",0,"L",false);
	$pdf->Ln();
	$pdf->Cell(18.0,0.3," ConAdmin - Sistema Administrativo de Qualidade",0,0,"L",false);

	$FileName = $PatchFile."/".$Arquivo[NomeArquivoRecibo];
	@unlink($FileName);
	$pdf->Output($FileName,"F");
?>