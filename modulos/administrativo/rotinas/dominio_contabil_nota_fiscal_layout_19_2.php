<?	
	$Linha = null;
	$Campo = null;	

	// Gera a nomenclatura do arquivo
	$MesVencimento = explode("-",$local_MesVencimento);
	$NomeArquivo = "DC".str_replace("-","",$local_MesVencimento)."_L19.TXT";

	$Patch = $PatchPeriodo."/".$NomeArquivo;

	$i=0;
	
	$DataInicial = "01/".$MesVencimento[1]."/".$MesVencimento[0];
	$DataFinal 	 = ultimoDiaMes($MesVencimento[1], $MesVencimento[0])."/".$MesVencimento[1]."/".$MesVencimento[0];

### // Cabeçalho

	// Campo  1 (Valor Fixo "01" Cabeçalho)						[2]   1-2		X
	$Campo[1] = "01";
	$Campo[1] = preenche_tam($Campo[1], 2, 'X');

	// Campo  2 (Código da Empresa)								[7]   3-9		N 
	$Campo[2] = $local_CodigoEmpresa;
	$Campo[2] = preenche_tam($Campo[2], 7, '');

	// Campo  3 (CGC da Empresa)								[14]  10-23		X 
	$Campo[3] = removeMascaraCPF_CNPJ($DadosEmpresa[CPF_CNPJ]);
	$Campo[3] = preenche_tam($Campo[3], 14, 'X');

	// Campo  4 (Data Inicial)									[10]  24-33		X 
	$Campo[4] = $DataInicial;	
	$Campo[4] = preenche_tam($Campo[4], 10, 'X');

	// Campo  5 (Data Final)									[10]  34-43		X
	$Campo[5] = $DataFinal;
	$Campo[5] = preenche_tam($Campo[5], 10, 'X');

	// Campo  6 (Valor fixo "N")								[1]  44-44		X 
	$Campo[6] = "N";
	$Campo[6] = preenche_tam($Campo[6], 1, 'X');

	// Campo  7 (Tipo de Nota)									[2]  45-46		N 
	//(01=Contabilidade, 02=Entradas, 03=Saídas, 04=Serviços, 05=Contabilidade-Lançamentos em lote)
	$Campo[7] = "04";
	$Campo[7] = preenche_tam($Campo[7], 2, '');

	// Campo  8 (Constante "00000" )							[3]   47-51		N
	$Campo[8] = "00000";
	$Campo[8] = preenche_tam($Campo[8], 5, '');

	// Campo  9 (Sistema (1=Contabilidade, 2=Caixa, 0=Outro))	[15]  52-52     N 
	$Campo[9] = "1";
	$Campo[9] = preenche_tam($Campo[9], 1, '');

	// Campo 10 (Valor fixo "18")								[2]   53-54		N
	$Campo[10] = "18";
	$Campo[10] = preenche_tam($Campo[10], 2, '');
	
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;
	
	$NumeroSequencial 		= 0;
	
	// Conferir a query com o Douglas
	
	$sql = "select
				NotaFiscal.IdNotaFiscalLayout,
				NotaFiscal.PeriodoApuracao,
				NotaFiscal.IdNotaFiscal,
				NotaFiscal.DataEmissao,	
				NotaFiscal.IdContaReceber,
				NotaFiscal.Modelo,
				NotaFiscal.ValorTotal,
				NotaFiscal.ValorDesconto,
				NotaFiscal.ValorBaseCalculoICMS,
				NotaFiscal.ValorICMS,
				NotaFiscal.ValorNaoTributado,
				NotaFiscal.ValorOutros,
				NotaFiscal.Serie,
				NotaFiscal.IdStatus,
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.IdLocalCobranca,
				ContaReceberDados.DataLancamento,
				Pessoa.IdPessoa,
				Pessoa.Nome,
				Pessoa.RG_IE,
				Pessoa.InscricaoMunicipal,
				Pessoa.TipoPessoa,
				Pessoa.CPF_CNPJ,
				Pessoa.Fax,
				PessoaEndereco.Endereco,
				PessoaEndereco.Numero,
				PessoaEndereco.Complemento,
				PessoaEndereco.Bairro,
				PessoaEndereco.Telefone,
				Estado.SiglaEstado,				
				Estado.IdPais,
				Estado.IdEstado,
				Cidade.IdCidade,
				Cidade.NomeCidade,
				PessoaEndereco.CEP
			from
				NotaFiscal,
				ContaReceberDados,
				Pessoa,
				PessoaEndereco,
				Estado,
				Cidade	
			where
				NotaFiscal.IdLoja = $local_IdLoja and
				NotaFiscal.IdLoja = ContaReceberDados.IdLoja and
				NotaFiscal.IdContaReceber = ContaReceberDados.IdContaReceber and
				NotaFiscal.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
				NotaFiscal.PeriodoApuracao = '$local_MesVencimento' and
				ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Estado.IdPais and
				PessoaEndereco.IdPais = Cidade.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		/*$sql = "select	
					Pessoa.IdPessoa
				from
					NotaFiscal,
					LancamentoFinanceiroDados,		
					Pessoa				
				where
					NotaFiscal.IdLoja = $local_IdLoja and
					NotaFiscal.IdLoja = LancamentoFinanceiroDados.IdLoja and
					NotaFiscal.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber and
					NotaFiscal.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
					NotaFiscal.PeriodoApuracao = '$local_MesVencimento' and
					LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa and
					Pessoa.IdPessoa = $lin[IdPessoa]
				group by
					Pessoa.Nome";
		$res2 = mysql_query($sql,$con);
		if($lin2 = mysql_fetch_array($res2)){
		*/
			$sql = "select
						ServicoAliquota.Aliquota,
						(FatorBaseCalculoAliquota * 100) PercentualBaseCalculoAliquota,
						Contrato.CFOP				
					from
						LancamentoFinanceiroDados,
						Contrato,
						Servico,
						ServicoAliquota
					where
						LancamentoFinanceiroDados.IdLoja = $local_IdLoja and
						LancamentoFinanceiroDados.IdLoja = Contrato.IdLoja and
						LancamentoFinanceiroDados.IdLoja = Servico.IdLoja and
						LancamentoFinanceiroDados.IdContrato = Contrato.IdContrato and
						Contrato.IdServico = Servico.IdServico and	
						Servico.IdServico = ServicoAliquota.IdServico and
						LancamentoFinanceiroDados.IdContaReceber = $lin[IdContaReceber] and
						ServicoAliquota.IdPais = $lin[IdPais] and
						ServicoAliquota.IdEstado = $lin[IdEstado] 	
					group by
						Servico.IdServico";
			$res2 = mysql_query($sql,$con);
			$lin2 = mysql_fetch_array($res2);
			
		
			if($lin[TipoPessoa] == 1){
				$lin[InscricaoEstadual] = $lin[RG_IE];
			}else{
				$lin[InscricaoEstadual] = "";
			}
						
			# Saídas (Notas)
				
			// Campo  1 (Fixo "02" Serviços)							[2]   1-2		X
			$Campo[1] = "02";
			$Campo[1] = preenche_tam($Campo[1], 2, 'X');
		
			// Campo  2 (Sequencial)									[7]   3-9		N 
			$NumeroSequencial++;
			$Campo[2] = $NumeroSequencial;
			$Campo[2] = preenche_tam($Campo[2], 7, '');		
			
			// Campo  3 (Código da Empresa)								[7]  10-16		N 
			$Campo[3] = $local_CodigoEmpresa;
			$Campo[3] = preenche_tam($Campo[3], 7, '');

			// Campo  4 (Inscrição do Cliente (CNPJ/CPF/CEI/Outros))	[14]  17-30		X 
			$Campo[4] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);	
			$Campo[4] = preenche_tam($Campo[4], 14, 'X');
		
			// Campo  5 (Código da Espécie)								[7]  31-37		N 
			$Campo[5] = $lin[Modelo];
			$Campo[5] = preenche_tam($Campo[5], 7, '');
			
			// Campo  6 (Código da Exclusão da DIEF)					[2]  38-39		N Verificar
			$Campo[6] = "";
			$Campo[6] = preenche_tam($Campo[6], 7, '');	

			// Campo  7 (Código do Acumulador)							[7]  40-46		N 
			$Campo[7] = $local_CodigoAcumulador;
			$Campo[7] = preenche_tam($Campo[7], 7, '');	
			
			// Campo  8 (CFOP)											[7]  47-53		N 
			$Campo[8] = str_replace(".","",$lin2[CFOP]);
			$Campo[8] = preenche_tam($Campo[8], 7, '');
			
			// Campo  9 (Sigla do Estado do Cliente)					[2]  54-55		N  
			$Campo[9] = $lin[SiglaEstado];
			$Campo[9] = preenche_tam($Campo[9], 2, '');
			
			// Campo  10 (Seguimento)									[2]  56-57		N Verificar
			$Campo[10] = "";
			$Campo[10] = preenche_tam($Campo[10], 2, '');
		
			// Campo  11 (Número do Documento)							[7]  58-64		N  
			$Campo[11] = $lin[NumeroDocumento];
			$Campo[11] = preenche_tam($Campo[11], 7, '');
			
			// Campo  12 (Série)										[7]  65-71		X 
			$Campo[12] = $lin[Serie];
			$Campo[12] = preenche_tam($Campo[12], 7, 'X');
					
			// Campo  13 (Docto Final)									[7]  72-78		N 
			$Campo[13] = $local_DoctoFinal;
			$Campo[13] = preenche_tam($Campo[13], 7, '');
					
			// Campo  14 (Data de Saída)								[10] 79-88		X Verificar
			$Campo[14] = dataConv($lin[DataEmissao],'Y-m-d','d/m/Y');
			$Campo[14] = preenche_tam($Campo[14], 10, 'X');
				
			// Campo  15 (Data de Emissão)								[10] 89-98		X 
			$Campo[15] = dataConv($lin[DataLancamento],'Y-m-d','d/m/Y');
			$Campo[15] = preenche_tam($Campo[15], 10, 'X');
				
			// Campo  16 (Valor Contábil (13,2))						[13] 99-111		N 		
			$Campo[16] = number_format($lin[ValorTotal], 2, '.', '');
			$Campo[16] = str_replace(".","",$Campo[16]);
			$Campo[16] = preenche_tam($Campo[16], 13, '');		

			// Campo  17 (Valor Contábil da DIEF (13,2))				[13] 112-124	N Verificar		
			$Campo[17] = number_format($lin[ValorTotal], 2, '.', '');
			$Campo[17] = str_replace(".","",$Campo[17]);
			$Campo[17] = preenche_tam($Campo[17], 13, '');		
						
			// Campo  18 (Reservado)									[30] 125-154	X 
			$Campo[18] = preenche_tam(" ", 30, 'X');
		
			// Campo  19 Modalidade de Frete ("C"=CIF ou "F"=FOB)		[1] 155-155		X Verificar
			//("S" = Sem Frete ou "T" = Terceiros)			 
			$Campo[19] = "";
			$Campo[19] = preenche_tam($Campo[19], 1, 'X');

			// Campo  20 (Código do Município)							[7] 156-162		N 
			$Campo[20] = $lin[IdEstado].preenche_tam($lin[IdCidade], 5, '');
			$Campo[20] = preenche_tam($Campo[20], 7, '');

			// Campo  21 (Fato Gerador da CRF)							[1] 163-163		X 
			// ("E"=Emissão ou "P"=Pagamento)
			$Campo[21] = "E";
			$Campo[21] = preenche_tam($Campo[21], 1, 'X');

			// Campo  22 (Alfanumérico	Fato Gerador da CRFOP)		 	[1] 164-164		X 
			// ("E"=Emissão ou "P"=Pagamento)
			$Campo[22] = "E";
			$Campo[22] = preenche_tam($Campo[22], 1, 'X');	
			
			// Campo  23 (Alfanumérico	Fato Gerador da IRRFP)		 	[1] 165-165		X 
			// ("E"=Emissão ou "P"=Pagamento)
			$Campo[23] = "E";
			$Campo[23] = preenche_tam($Campo[23], 1, 'X');
			
			// Campo  24 (Tipo da Receita:)								[1] 166-166		N Verificar
			#(1=Receita própria - serviços prestados/
			#2=Receita de terceiros (co-faturamento)/
			#3=Receita própria - cobrança de débitos/
			#4=Receita própria - venda de mercadorias/
			#5=Receita própria - venda de serviço pré-pago/
			#6=Outras receitas próprias/7=Outras receitas de terceiros.)
			$Campo[24] = " ";
			$Campo[24] = preenche_tam($Campo[24], 1, '');
			
			// Campo  25 (Código da observação)							[1] 167-167		N 
			$Campo[25] = " ";
			$Campo[25] = preenche_tam($Campo[25], 1, 'X');	
			


			// Campo  22 (Código da observação)							[7] 150-156		N 
			$Campo[22] = " ";
			$Campo[22] = preenche_tam($Campo[22], 7, 'X');
		
			// Campo  23 (Código do modelo do documento fiscal)			[7] 157-163		N 
			if($lin[IdStatus] == 0){
				$Campo[23] = 2;
			}else{
				$Campo[23] = 0;
			}
			$Campo[23] = preenche_tam($Campo[23], 7, '');	
			
			// Campo  24 (Código fiscal da prestação de serviços)		[7] 164-170		N 
			$Campo[24] = str_replace(".","",$lin2[CFOP]);
			$Campo[24] = preenche_tam($Campo[24], 7, '');
		
			// Campo  25 (Sub Série)									[7] 171-177		N
			$Campo[25] = "0";
			$Campo[25] = preenche_tam($Campo[25], 7, '');
		
			// Campo  26 (Inscrição Estadual do Cliente)				[20] 178-197	X 
			$Campo[26] = $lin[InscricaoEstadual];
			$Campo[26] = preenche_tam($Campo[26], 20, 'X');
		
			// Campo  27 (Inscrição Municipal do Cliente)				[20] 198-217	X 
			$Campo[27] = $lin[InscricaoMunicipal];
			$Campo[27] = preenche_tam($Campo[27], 20, 'X');
		
			// Campo  28 (Observação)									[300] 218-517	X 
			$Campo[28] = " ";
			$Campo[28] = preenche_tam($Campo[28], 300, 'X');
		
			// Campo  29 (Brancos)										[100] 518-617	X 
			$Campo[29] = preenche_tam(" ", 100, 'X');
			
			// Salva
			$Linha[$i] = concatVar($Campo);
			$i++;
		
			$Campo = null;
		
		
			# Impostos
		
			// Campo  1 (Fixo "03" Impostos)							[2]   1-2		X
			$Campo[1] = "03";
			$Campo[1] = preenche_tam($Campo[1], 2, 'X');
		
			// Campo  2 (Sequencial)									[7]   3-9		N 
			$NumeroSequencial++;
			$Campo[2] = $NumeroSequencial;
			$Campo[2] = preenche_tam($Campo[2], 7, '');
		
			// Campo  3 (Código do Imposto)								[7]  10-16		N 
			$Campo[3] = "1"; // 1 = ICMS
			$Campo[3] = preenche_tam($Campo[3], 7, '');
		
			// Campo  4 (Percentual de Redução da Base de Cal.)			[5]  17-21		N 
			$Campo[4] = number_format($lin2[PercentualBaseCalculoAliquota], 2, '.', '');
			$Campo[4] =	str_replace(".","",$Campo[4]);	
			$Campo[4] = preenche_tam($Campo[4], 5, '');
			
			// Campo  5 (Base de Cálculo)								[13]  22-34		N 
			$Campo[5] = number_format($lin[ValorTotal], 2, '.', '');
			$Campo[5] =	str_replace(".","",$Campo[5]);
			$Campo[5] = preenche_tam($Campo[5], 13, '');

			// Campo  6 (Alíquota)										[5]  35-39		N 
			if($lin2[Aliquota]!=''){
				$Campo[6] = number_format($lin2[Aliquota], 2, '.', '');
				$Campo[6] = str_replace(".","",$Campo[6]);
				$Campo[6] = preenche_tam($Campo[6], 5, '');
			}else{
				$Campo[6] = preenche_tam(" ", 5, 'X');
			}	
				
			// Campo  7 (Valor do Imposto)								[13] 40-52		N 
			$Campo[7] = number_format($lin[ValorICMS], 2, '.', '');
			$Campo[7] =	str_replace(".","",$Campo[7]);
			$Campo[7] = preenche_tam($Campo[7], 13, '');		
			
			// Campo  8 (Valor Isentas)									[13] 53-65		N 
			$Campo[8] = number_format($lin[ValorNaoTributado], 2, '.', '');
			$Campo[8] =	str_replace(".","",$Campo[8]);
			$Campo[8] = preenche_tam($Campo[8], 13, '');
				
			// Campo  9 (Valor de Outras)								[13] 66-78		N 
			$Campo[9] = number_format($lin[ValorOutros], 2, '.', '');
			$Campo[9] =	str_replace(".","",$Campo[9]);
			$Campo[9] = preenche_tam($Campo[9], 13, '');
		
			// Campo  10 (Valor Contábil)								[13] 79-91		N 
			$Campo[10] = number_format($lin[ValorTotal], 2, '.', '');
			$Campo[10] = str_replace(".","",$Campo[10]);
			$Campo[10] = preenche_tam($Campo[10], 13, '');
				
			// Campo  11 (Alfanumérico	Brancos)						[100] 92-191	X 
			$Campo[11] = "0";
			$Campo[11] = preenche_tam($Campo[11], 100, 'X');
		
			// Salva
			$Linha[$i] = concatVar($Campo);
			$i++;
		
			$Campo = null;
					
			
			# Parcelas	
			// Campo  1 (Fixo "09" Parcelas)							[2]   1-2		X
			$Campo[1] = "09";
			$Campo[1] = preenche_tam($Campo[1], 2, 'X');
		
			// Campo  2 (Sequencial)									[7]   3-9		N 
			$NumeroSequencial++;
			$Campo[2] = $NumeroSequencial;
			$Campo[2] = preenche_tam($Campo[2], 7, '');
		
			// Campo  3 (Data de Vencimento)							[10] 10-19		N 
			$Campo[3] = dataConv($lin[DataVencimento],'Y-m-d','d/m/Y');
			$Campo[3] = preenche_tam($Campo[3], 10, '');
		
			// Campo  4 (Tipo, Fixo "0")								[1]  20-20		N 
			$Campo[4] = "0";	
			$Campo[4] = preenche_tam($Campo[4], 1, '');
		
			// Campo  5 (Valor da Parcela)								[13] 21-33		N 
			$Campo[5] = number_format($lin[ValorTotal], 2, '.', '');
			$Campo[5] =	str_replace(".","",$Campo[5]);
			$Campo[5] = preenche_tam($Campo[5], 13, '');
		
			// Campo  6 (Alíquota da CRF)								[13] 34-46		N 
			$Campo[6] = " ";
			$Campo[6] = preenche_tam($Campo[6], 13, 'X');
		
			// Campo  7 (Valor da CRF)									[13] 47-59		N 
			$Campo[7] = " ";
			$Campo[7] = preenche_tam($Campo[7], 13, 'X');
			
			// Campo  8 (Valor do IRRF)									[13] 60-72		N 
			$Campo[8] = " ";
			$Campo[8] = preenche_tam($Campo[8], 13, 'X');
		
			// Campo  9 (Valor do ISS Retido na Fonte)					[13] 73-85		N 
			$Campo[9] = " ";
			$Campo[9] = preenche_tam($Campo[9], 13, 'X');
		
			// Campo  10 (Valor do INSS Retido)							[13] 86-98		N 
			$Campo[10] = " ";
			$Campo[10] = preenche_tam($Campo[10], 13, 'X');
		
			// Campo  11 (Alíquota do PIS da CRFOP)						[5] 99-103		N 
			$Campo[11] = " ";
			$Campo[11] = preenche_tam($Campo[11], 5, 'X');
			
			// Campo  12 (Valor do PIS da CRFOP)						[13] 104-116	N 
			$Campo[12] = " ";
			$Campo[12] = preenche_tam($Campo[12], 13, 'X');
		
			// Campo  13 (Alíquota da COFINS da CRFOP)					[5] 117-121		N 
			$Campo[13] = " ";
			$Campo[13] = preenche_tam($Campo[13], 5, 'X');
		
			// Campo  14 (Valor da COFINS da CRFOP)						[13] 122-134	N 
			$Campo[14] = " ";
			$Campo[14] = preenche_tam($Campo[14], 13, 'X');
			
			// Campo  15 (Alíquota da CSLL da CRFOP)					[5] 135-139		N 
			$Campo[15] = " ";
			$Campo[15] = preenche_tam($Campo[15], 5, 'X');
		
			// Campo  16 (Valor da CSLL da CRFOP)						[13] 140-152	N 
			$Campo[16] = " ";	
			$Campo[16] = preenche_tam($Campo[16], 13, 'X');
		
			// Campo  17 (Alíquota do IRPJ da CRFOP)					[5] 153-157		N 
			$Campo[17] = " ";	
			$Campo[17] = preenche_tam($Campo[17], 5, 'X');
		
			// Campo  18 (Valor do IRPJ da CRFOP)						[13] 158-170	N 
			$Campo[18] = " ";	
			$Campo[18] = preenche_tam($Campo[18], 13, 'X');
		
			// Campo  19 (Valor do IRRFP)								[13] 171-183	N 
			$Campo[19] = " ";	
			$Campo[19] = preenche_tam($Campo[19], 13, 'X');
		
			// Campo  20 (Valor do PIS retido)							[13] 184-196	N 
			$Campo[20] = " ";	
			$Campo[20] = preenche_tam($Campo[20], 13, 'X');
		
			// Campo  21 (Valor da COFINS retido)						[13] 197-209	N 
			$Campo[21] = " ";	
			$Campo[21] = preenche_tam($Campo[21], 13, 'X');
			
			// Campo  22 (Valor da CSLL retido)							[13] 210-222	N 
			$Campo[22] = " ";	
			$Campo[22] = preenche_tam($Campo[22], 13, 'X');
		
			// Campo  23 (Valor da CSLL retido)							[100] 223-322	X 	
			$Campo[23] = preenche_tam(" ", 100, 'X');
		
			// Salva
			$Linha[$i] = concatVar($Campo);
			$i++;
		
			$Campo = null;
		//}
	}

	# Finalizador 
	
	// Campo  22 (Valor da CSLL retido)								[100] 1-100		N 
	$Campo[1] = "9999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999";	
	$Campo[1] = preenche_tam($Campo[1], 100, '');
	
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$String = '';
			
	for($i=0; $i<count($Linha); $i++){	
		if(strlen($Linha[$i]) == 390 || strlen($Linha[$i]) == 191 || strlen($Linha[$i]) == 322 || strlen($Linha[$i]) == 100 || strlen($Linha[$i]) == 54 || strlen($Linha[$i]) == 617){
			$String .= strtoupper(substituir_string($Linha[$i]))."\r\n";
		}
	}

	$FileName = $Patch;

	@unlink($FileName);

	if($File = fopen($FileName, 'a')) {
		if(fwrite($File, $String)){
			
			$FileSize = filesize($FileName)/1024;
			$FileSize = number_format($FileSize, 2, '.', '');

		}
	}	
	@fclose($FileName);	
?>
