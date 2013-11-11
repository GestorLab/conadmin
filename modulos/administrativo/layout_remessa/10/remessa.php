<?	
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

	// Campo  2 (Código de Remessa/Retorno)						[1]   2-2		N 
	$Campo[2] = "1";
	$Campo[2] = preenche_tam($Campo[2], 1, '');

	// Campo  3 (Código do Convênio)							[13]  3-15 		X 
	$Campo[3] = $ParametroLocalCobranca['Convenio'];	
	$Campo[3] = preenche_tam($Campo[3], 13, 'X');
	
	// Campo  4 (Complemento de Registro)						[7]  16-22		X 
	$Campo[4] = preenche_tam(" ", 7, 'X');

	// Campo  5 (Nome da Empresa)								[20]  23-42		X 
	$Campo[5] = $DadosEmpresa[Nome];
	$Campo[5] = preenche_tam($Campo[5], 20, 'X');

	// Campo  6 (Código do Banco na Compensação)				[3]  43-45		N 
	$Campo[6] = "341";
	$Campo[6] = preenche_tam($Campo[6], 3, '');

	// Campo  7 (Nome do Banco)									[10] 46-55		X 
	$Campo[7] = "BANCO ITAU";
	$Campo[7] = preenche_tam($Campo[7], 10, 'X');	
	
	// Campo  8 (Complemento de Registro)						[10] 56-65		X 
	$Campo[8] = preenche_tam(" ", 10, 'X');	

	// Campo  9 (Data de Geração do Arquivo)					[8]  66-73		N 
	$Campo[9] = date("Ymd");
	$Campo[9] = preenche_tam($Campo[9], 8, '');
		
	// Campo  10 (Número Seqüencial do Arquivo (NSA))			[6]  74-79		N 
	$Campo[10] = $NumSeqArquivo;
	$Campo[10] = preenche_tam($Campo[10], 6, '');
	
	// Campo  11 (Versão do Lay-out) 04							[2]  80-81		N
	$Campo[11] = "04";
	$Campo[11] = preenche_tam($Campo[9], 2, '');
	
	// Campo  12 (Identificação do Serviço)						[17]  82-98     X
	$Campo[12] = "DEBITO AUTOMATICO";
	$Campo[12] = preenche_tam($Campo[12], 17, 'X');
	
	// Campo  13 (Reservado para o futuro)						[52]  99-150	X 
	$Campo[13] = preenche_tam(' ', 52, 'X');
		
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
				PessoaContaDebito.IdContaDebito,
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
			$lin[NumeroIdentificacao]  = str_pad($local_IdLoja, 3, 0, STR_PAD_LEFT);
			$lin[NumeroIdentificacao] .= str_pad($lin[IdPessoa], 11, 0, STR_PAD_LEFT);
			$lin[NumeroIdentificacao] .= str_pad($lin[IdContaDebito], 11, 0, STR_PAD_LEFT);
			$Campo[2] = $lin[NumeroIdentificacao];			
			$Campo[2] = preenche_tam($Campo[2], 25, '');
			
			// Campo  3 (Agência para Débito)							[4]   27-30		X
			$Campo[3] = $lin[NumeroAgencia];	
			$Campo[3] = preenche_tam($Campo[3], 4, 'X');

			// Campo  4 (Complemento de Registro)						[8]   31-38		X
			$Campo[4] = preenche_tam(" ", 8, 'X');			
				
			// Campo  5 (Número da Conta Débito)						[5]   39-43		N 
			$Campo[5] = $lin[NumeroConta];
			$Campo[5] = preenche_tam($Campo[5], 5, '');

			// Campo  6 (Dígito verificador da AG/CONTA)				[1]   44-44		N verificar
			$Campo[6] = $lin[DigitoConta];
			$Campo[6] = preenche_tam($Campo[6], 1, '');
							
			// Campo  7 (Data para Lancamento do Débito)				[8]   45-52		N 
			$Campo[7] = dataConv($lin[DataVencimento],'Y-m-d','Ymd');
			$Campo[7] = preenche_tam($Campo[7], 8, '');	
			
			// Campo  8 (Valor do Lançamento para Débito)				[15]  53-67		N 
			$Campo[8] = number_format($lin[ValorFinal], 2, '.', '');			
			$Campo[8] = str_replace(".","",$Campo[8]);
			$Campo[8] = preenche_tam($Campo[8], 15, '');		
		
			// Campo  9 (Tipo da Moeda)									[2]   68-69		N 
			$Campo[9] = "03";
			$Campo[9] = preenche_tam($Campo[9], 2, '');
		
			#Uso da Empresa								
							
			// Campo  10 (Número do documento)							[15]  70-85		N 
			$Campo[10] = $lin[NumeroDocumento];
			$Campo[10] = preenche_tam($Campo[10], 15, '');
			
			// Campo  11 (Restante Reservado para a empresa)			[10]  86-94	    X 
			$Campo[11] = preenche_tam(" ", 10, 'X');
			
			// Campo  12 (Valor do Encargo por Dia de Atraso)			[15]  95-109	N 
			$Campo[12] = $lin[ValorFinal]*$DadosLocalCobranca[PercentualJurosDiarios]/100;
			$Campo[12] = number_format($Campo[12], 2, '.', '');	
			$Campo[12] = str_replace(".","",$Campo[12]);		
			$Campo[12] = preenche_tam($Campo[12], 15, '');
			
			// Campo  13 (Informação COMPL. P/Historico de C/C)			[16]  110-125	X 
			$Campo[13] = " ";
			$Campo[13] = preenche_tam($Campo[13], 16, 'X');
			
			// Campo  14 (Complemento de Registro)						[10]  126-135   X 
			$Campo[14] = preenche_tam(" ", 10, 'X');

			// Campo  15 (Nº de Inscrição do Debitado (CPF/CNPJ))		[14]  136-149   N 
			$lin[CPF_CNPJ] = str_replace(".","",$lin[CPF_CNPJ]);
			$lin[CPF_CNPJ] = str_replace("/","",$lin[CPF_CNPJ]);
			$lin[CPF_CNPJ] = str_replace("-","",$lin[CPF_CNPJ]);
			$Campo[15] = $lin[CPF_CNPJ];
			$Campo[15] = preenche_tam($Campo[15], 14, '');
		
			// Campo  16 (Código do Movimento)							[1]   150-150	N 
			$Campo[16] = "0";	
			$Campo[16] = preenche_tam($Campo[16], 1, '');
		
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
	$i++;			
?>
