<?	/*
	// Gera a nomenclatura do arquivo
	$NomeArquivo = "DBT_".$ParametroLocalCobranca['Convenio']."_".str_pad($NumSeqArquivo,6,0,STR_PAD_LEFT).".rem";

	$Patch = "remessa/local_cobranca";
	@mkdir($Patch);

	$Patch .= "/$local_IdLoja";
	@mkdir($Patch);

	$Patch .= "/$local_IdLocalCobranca";
	@mkdir($Patch);

	$Patch .= "/$local_IdArquivoRemessa";
	@mkdir($Patch);

	$Patch .= "/".$NomeArquivo;

	$i=0;

### // Registro Header de Lote

	// Campo  1 ("Código do Registro")							[1]   1-1		X 
	$Campo[1] = "A";								

	// Campo  2 (Código de Remessa)								[1]   2-2		N 
	$Campo[2] = "1";
	$Campo[2] = preenche_tam($Campo[2], 1, '');

	// Campo  3 (Código do Convênio)							[20]  3-22 		X 
	$Campo[3] = $ParametroLocalCobranca['Convenio'];	
	$Campo[3] = preenche_tam($Campo[3], 20, 'X');
	
	// Campo  4 (Nome da Empresa)								[20]  23-42		X 
	$Campo[4] = $DadosEmpresa[Nome];
	$Campo[4] = preenche_tam($Campo[4], 20, 'X');

	// Campo  5 (Código do Banco)								[3]  43-45		N 
	$Campo[5] = "001";
	$Campo[5] = preenche_tam($Campo[5], 3, '');

	// Campo  6 (Nome do Banco)									[20] 46-65		X 
	$Campo[6] = "Banco do Brasil S.A.";
	$Campo[6] = preenche_tam($Campo[6], 20, 'X');	

	// Campo  7 (Data de Geração)								[8]  66-73		N 
	$Campo[7] = date("Ymd");
	$Campo[7] = preenche_tam($Campo[7], 8, '');
		
	// Campo  8 (Número Seqüencial do Arquivo (NSA))			[6]  74-79		N 
	$Campo[8] = $NumSeqArquivo;
	$Campo[8] =	preenche_tam($Campo[8], 6, '');
	
	// Campo  9 (Versão do Lay-out) 04-02052007.doc 20/4/2007	[2]  80-81		N
	$Campo[9] = "04";
	$Campo[9] = preenche_tam($Campo[9], 2, '');
	
	// Campo  10 (Identificação do Serviço)						[17]  82-98     X
	$Campo[10] = "DÉBITO AUTOMÁTICO";
	$Campo[10] = preenche_tam($Campo[10], 17, 'X');
	
	// Campo 11 (Reservado para o futuro)						[47]  99-145	X 
	$Campo[11] = preenche_tam(' ', 47, 'X');

	// Campo 11 (Mensagem de TESTE)								[5]  146-150	X 
	$Campo[12] = preenche_tam($ParametroLocalCobranca['MensagemTeste'], 5, 'X');
		
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;
	
	$Campo = null;



		
### // Registro Detalhe
	$NumeroLoteRemessaSeguimento = 0;
	$ValorTotalTitulos			 = 0;
	$QtdContaReceber 			 = 0;
	
	$sql = "select
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.IdPessoa,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.DataLancamento,	
				Pessoa.IdPessoa,
				Pessoa.TipoPessoa,
				Pessoa.CPF_CNPJ,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				PessoaEndereco.Endereco,
				PessoaEndereco.Numero,
				PessoaEndereco.Complemento,
				PessoaEndereco.Bairro,
				Estado.SiglaEstado,
				Cidade.NomeCidade,
				PessoaEndereco.CEP,
				PessoaContaDebito.NumeroAgencia,
				PessoaContaDebito.DigitoAgencia,
				PessoaContaDebito.NumeroConta,
				PessoaContaDebito.DigitoConta,
				LancamentoFinanceiroDados.IdContrato
			from
				ContaReceberDados,
				LancamentoFinanceiroDados,
				Pessoa,
				PessoaEndereco,
				PessoaContaDebito,
				Estado,
				Cidade				
			where
				ContaReceberDados.IdLoja = $local_IdLoja and
				ContaReceberDados.IdLoja = PessoaContaDebito.IdLoja and
				ContaReceberDados.IdLoja = LancamentoFinanceiroDados.IdLoja and
				ContaReceberDados.IdLocalCobranca = $local_IdLocalCobranca and
				ContaReceberDados.IdArquivoRemessa = $local_IdArquivoRemessa and
				ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Estado.IdPais and
				PessoaEndereco.IdPais = Cidade.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade and
				Pessoa.IdPessoa = PessoaContaDebito.IdPessoa and
				ContaReceberDados.IdLocalCobranca = PessoaContaDebito.IdLocalCobranca and
				ContaReceberDados.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber and	
				PessoaContaDebito.IdStatus = 1
			group by
				ContaReceberDados.IdContaReceber";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		#Se as informações do Cliente for vazia, não deve gerar a remessa para o conta receber.
		
		if($lin[NumeroAgencia] != '' && $lin[DigitoAgencia] != '' && $lin[NumeroConta] != '' && $lin[DigitoConta] != ''){
			
			### Registro E 

			// Campo  1 (Código do Registro)							[1]   1-1		X 
			$Campo[1] = "E";
		
			// Campo  2 (Identificação do Cliente na Empresa)			[25]  2-26		N  // segundo o banco este campo é numérico
			$Campo[2] = $lin[IdPessoa];
			$Campo[2] = preenche_tam($Campo[2], 25, '');
				
			// Campo  3 (Agência para Débito)							[4]   27-30		X
			$Campo[3] = $lin[NumeroAgencia];	
			$Campo[3] = preenche_tam($Campo[3], 4, 'X');
				
			// Campo  4 (Identificação do Cliente no Banco)				[14]  31-44		N // segundo o banco este campo é numérico
			$Campo[4] = $lin[NumeroConta].$lin[DigitoConta];
			$Campo[4] = preenche_tam($Campo[4], 14, '');
							
			// Campo  5 (Data do Vencimento)							[8]   45-52		N 
			$Campo[5] = dataConv($lin[DataVencimento],'Y-m-d','Ymd');
			$Campo[5] = preenche_tam($Campo[5], 8, '');	
			
			// Campo  6 (Valor do Débito)								[15]  53-67		N 
			$Campo[6] = number_format($lin[ValorFinal], 2, '.', '');			
			$Campo[6] = str_replace(".","",$Campo[6]);
			$Campo[6] = preenche_tam($Campo[6], 15, '');		
		
			// Campo  7 (Código da moeda)								[2]   68-69		X 
			$Campo[7] = "03";
			$Campo[7] = preenche_tam($Campo[7], 2, 'X');
		
			#Uso da Empresa				
			#Os tratamentos relativos ao FIDC e a Lei n. 10.833 deverão ser tratados previamente entre as Empresas e os Bancos.		
							
			// Campo  8 (Esta informação não será tratada pelo Banco)	[49]  70-118	X verificar
			$Campo[8] = preenche_tam(" ", 49, 'X');
			
			// Campo  8 (Valor total dos tributos – Lei n. 10.833)		[10]  119-128	X verifique
			$Campo[9] = " ";	
			$Campo[9] = preenche_tam($Campo[9], 10, 'X');
			
			// Campo  8 (FIDC ou Lei n. 10.833)							[1]   129-129	N verificar
			$Campo[10] = "X";
			$Campo[10] = preenche_tam($Campo[10], 1, '');
			
			// Campo  9 (Reservado para o futuro)						[20]  130-149   X 
			$Campo[11] = preenche_tam(" ", 20, 'X');
		
			// Campo  10 (Código do Movimento)							[1]   150-150	N 
			$Campo[12] = "0";	
			$Campo[12] = preenche_tam($Campo[12], 1, '');
		
			// Salva
			$Linha[$i] = concatVar($Campo);			
			$i++;
		
			$Campo = null;

			$QtdContaReceber++;		
			$ValorTotalTitulos += $lin[ValorFinal];		
		}
	}

	### Registro Z - Trailler			
	
	// Campo  1 (Código do Registro)							[1]   1-1		X
	$Campo[1] = "Z";
	
	// Campo  2 (Total de registros do arquivo)					[6]   2-7		N 
	$Campo[2] = $QtdContaReceber +=2;
	$Campo[2] = preenche_tam($Campo[2], 6, '');

	// Campo  3 (Valor total dos registros do arquivo)			[17]  8-24		N 
	$Campo[3] = number_format($ValorTotalTitulos, 2, '.', '');
	$Campo[3] = str_replace(".","",$Campo[3]);
	$Campo[3] = preenche_tam($Campo[3], 17, '');	

	// Campo  4 (Reservado para o futuro)						[126] 25-150	X 		
	$Campo[4] = preenche_tam(" ", 126, 'X');
								
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;		*/	
?>
