<?
	include("funcao_layout.php");
	
	// Gera a nomenclatura do arquivo
	$DataRemessa = explode("/",$local_DataRemessa);
	$DataSimples = $DataRemessa[0].$DataRemessa[1].substr($DataRemessa[2],2,2);

	$NomeArquivo = "CB".$DataSimples.".REM";

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

### // Registro Header 

	// Campo  1 (Identificação do Registro Header)				[1]   1-1		N 
	$Campo[1] = "0";								

	// Campo  2 (Identificação do tipo de arquivo)				[1]   2-2		N
	$Campo[2] = '1';

	// Campo  3 (Identificação por extenso)						[7]   3-9		X 
	$Campo[3] = "REMESSA";	
	$Campo[3] = preenche_tam($Campo[3], 7, 'X');

	// Campo  4 (Identificação do tipo de serviço)				[2]   10-11		N 
	$Campo[4] = preenche_tam("01", 2, '');

	// Campo  5 (Identificação do serviço por extenso)			[15]  12-26		X 
	$Campo[5] = "COBRANCA";
	$Campo[5] = preenche_tam($Campo[5], 15, 'X');

	// Campo  6 (Zero)											[1]  27-27		X
	$Campo[6] = preenche_tam("0", 1, '');	
	
	// Campo  7 (Código da agência)								[4]  28-31		N 
	$Campo[7] = $ParametroLocalCobranca[Agencia];
	$Campo[7] = preenche_tam($Campo[7], 4, '');

	// Campo  8 (Sub-conta da conta do Cliente)					[2]  32-33		N
	$Campo[8] =	preenche_tam("55", 2, '');
	
	// Campo  9 (Número da conta Corrente do Cliente)			[11] 34-44		N  
	$Campo[9] = $ParametroLocalCobranca[Conta];
	$Campo[9] = preenche_tam($Campo[9], 1, '');

	// Campo  10 (Uso do Banco)									[2]  45-46      X 
	$Campo[10] = preenche_tam(' ', 2, '');

	// Campo 11 (Razão Social / Nome do Cliente por extenso)	[30] 47-76		X  
	$Campo[11] = $DadosEmpresa[RazaoSocial];
	$Campo[11] = preenche_tam($Campo[11], 30, 'X');
	
	// Campo 12 (Número do Banco na Compensação)				[3]  77-79		N 
	$Campo[12] = '399';
	$Campo[12] = preenche_tam($Campo[12], 3, '');

	// Campo 13 (Nome do Banco por Extenso)						[15] 80-94 		X 
	$Campo[13] = 'HSBC';
	$Campo[13] = preenche_tam($Campo[13], 15, 'X');

	// Campo 14 (Data da gravação do Arquivo)					[6] 95-100		N verificar
	$Campo[14] = dataConv(date('Ymd'),'Ymd','DDMMAA');
	$Campo[14] = preenche_tam($Campo[14], 6, '');

	// Campo 15 (Densidade de gravação)							[5] 101-105		N  
	$Campo[15] = '01600';
	$Campo[15] = preenche_tam($Campo[15], 5, '');
		
	// Campo 16 (Unidade de densidade de gravação)				[3]	 106-108	X  
	$Campo[16] = 'BPI';
	$Campo[16] = preenche_tam($Campo[16], 3, 'X');
	
	// Campo 17 (Uso do Banco)									[2] 109-110		X 
	$Campo[17] = ' ';
	$Campo[17] = preenche_tam($Campo[17], 2, 'X');
	
	// Campo 18 (Sigla do Layout técnico)						[7] 111-117     X 
	$Campo[18] = "LANCV08";
	$Campo[18] = preenche_tam($Campo[18], 7, 'X');
	
	// Campo 19 (Uso do Banco)									[277] 118-394   X 
	$Campo[19] = " ";
	$Campo[19] = preenche_tam($Campo[19], 277, 'X');
	
	// Campo 20 (Número seqüencial do registro no arquivo)		[6]  395-400    N 
	$Campo[20] = '000001';
	$Campo[20] = preenche_tam($Campo[20], 6, '');

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
				ContaReceberDados.LimiteDesconto,
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
				PessoaEndereco.CEP
			from
				ContaReceberDados,
				Pessoa,
				PessoaEndereco,
				Estado,
				Cidade
			where
				ContaReceberDados.IdLoja = $local_IdLoja and
				ContaReceberDados.IdLocalCobranca = $local_IdLocalCobranca and
				ContaReceberDados.IdArquivoRemessa = $local_IdArquivoRemessa and
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
		
		$sql = "select
					sum(LancamentoFinanceiro.ValorDescontoAConceber) ValorDescontoAConceber,
					min(LancamentoFinanceiro.LimiteDesconto) LimiteDesconto					
				from	
					LancamentoFinanceiroContaReceber,
					LancamentoFinanceiro					
				where
					LancamentoFinanceiroContaReceber.IdLoja = $local_IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
					LancamentoFinanceiroContaReceber.IdContaReceber = $lin[IdContaReceber] and
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro
				";
		$res2 = mysql_query($sql,$con);
		$lin2 = mysql_fetch_array($res2);		
		
		if($lin2[ValorDescontoAConceber] > 0){
			$DataDesconto = incrementaData($lin[DataVencimento],$lin2[LimiteDesconto]);  
			$DataDesconto = dataConv($DataDesconto,'Y-m-d','dmY');
			$CodigoDesconto = 1;
		}else{
			$DataDesconto = "";
			$CodigoDesconto = 0;
		}
		
		if($lin[TipoPessoa] == 1){
			$TipoInscricaoSacado = 2;
			$NomeSacado = $lin[RazaoSocial];
		}else{
			$TipoInscricaoSacado = 1;
			$NomeSacado = $lin[Nome];
		}		
		
		$CEP = explode("-",$lin[CEP]);
		$CepSacado = $CEP[0];
		$CepSufixo = $CEP[1];
		
		// Registro Detalhe

		// Campo  1 (Identificação do Registro Detalhe)					[3]   1-1		N 
		$Campo[1] = "001";
	
		// Campo  2 (Identificação do código de inscrição do Cliente)   [2]   2-3		N  
		$Campo[2] = $TipoInscricaoSacado;
		$Campo[2] = preenche_tam($Campo[2], 2, '');
	
		// Campo  3 (Número de inscrição do Cliente (CPF/CNPJ))			[14]  4-17		N  
		$Campo[3] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);	
		$Campo[3] = preenche_tam($Campo[3], 14, '');

		// Campo  4 (Zero)												[1]   18-18		N  
		$Campo[4] = 0;
		$Campo[4] = preenche_tam($Campo[4], 1, '');
				
		// Campo  5 (Código da agência onde o Cliente mantém conta)		[4]   19-22		N		
		$Campo[5] = "";
		$Campo[5] = preenche_tam($Campo[5], 4, '');	
		
		// Campo  6 (Sub-conta da conta do Cliente)						[2]   23-24		N 	
		$Campo[6] = 55;
		$Campo[6] = preenche_tam($Campo[6], 2, '');
	
		// Campo  7 (Número da conta corrente do Cliente)				[11]  25-35		N 
		$Campo[7] = "";
		$Campo[7] = preenche_tam($Campo[7], 11, '');
	
		// Campo  8 (Uso do Banco)										[2]   36-37		X 
		$Campo[8] = preenche_tam(" ", 2, 'X');
		
		// Campo  9 (Identificação do título no sistema do Cliente)		[25]  38-62		X 
		$Campo[9] = $lin[NumeroDocumento];	
		$Campo[9] = preenche_tam($Campo[9], 25, 'X');
		
		// Campo  10 (Identificação do título no Banco)					[11]  63-73		N 
		$Campo[10] = mod11($ParametroLocalCobranca[CodigoCedente].$lin[NumeroDocumento],7);
		$Campo[10] = preenche_tam($Campo[10], 11, '');		
		
		// Campo  11 (Data limite para o desconto-(2))					[6]   74-79	    N verificar
		$Campo[11] = '';
		$Campo[11] = preenche_tam($Campo[11], 6, 'X');
	
		// Campo  12 (Valor do desconto a conceder-(2))					[11]  80-90		N verificar
		$Campo[12] = '';	
		$Campo[12] = preenche_tam($Campo[12], 11, '');

		// Campo  13 (Data limite para o desconto-(3)					[6]   91-96		N verificar
		$Campo[13] = '';
		$Campo[13] = preenche_tam($Campo[13], 6, '');
		
		// Campo  14 (Valor do desconto a conceder-(3))					[11]   97-107	N verificar
		$Campo[14] = "";
		$Campo[14] = preenche_tam($Campo[14], 11, '');
	
		// Campo  15 (Identifica o tipo da carteira de cobrança)		[1]   108-108	N verificar
		$Campo[15] = "";	
		$Campo[15] = preenche_tam($Campo[15], 1, '');
	
		// Campo  16 (Identificação da ocorrência)						[2]   109-110	N 
		$Campo[16] = 1;
		$Campo[16] = preenche_tam($Campo[16], 2, '');
	
		// Campo  17 (Número da duplicata, nota promissória, etc.)		[10]   111-120	N verificar 
		$Campo[17] = "";
		$Campo[17] = preenche_tam($Campo[17], 10, '');
	
		// Campo  18 (Data do vencimento do título)						[6]   121-126	N 
		$Campo[18] = dataConv($lin[DataVencimento],"Y-m-d","DDMMAA");	
		$Campo[18] = preenche_tam($Campo[18], 6, '');

		// Campo  19 (Valor nominal do título)							[13]  127-139	N 
		$Campo[19] = number_format($lin[ValorFinal], 2, '.', '');	
		$Campo[19] = str_replace(".","",$Campo[19]); 	
		$Campo[19] = preenche_tam($Campo[19], 13, '');	
		
		// Campo  20 (Número do banco cobrador)							[3]  140-142	N
		$Campo[20] = "399";
		$Campo[20] = preenche_tam($Campo[20], 3, '');	
		
		// Campo  21 (Agência encarregada da cobrança)					[5] 143-147		N 
		$Campo[21] = 0;		
		$Campo[21] = preenche_tam($Campo[21], 5, '');
			
		// Campo 22 (Espécie do título)									[2]	 148-149    N 
		if($ParametroLocalCobranca[EspecieDocumento] == 'DM'){
			$Campo[22] = 01;	
		}else{
			$local_transaction[$tr_i]	= false;
			break;
		}	
		$Campo[22] = preenche_tam($Campo[22], 2, '');

		// Campo 23 (Identificação de aceito/ não - aceito)				[1] 150-150     X 
		$Campo[23] = $ParametroLocalCobranca[Aceite];
		$Campo[23] = preenche_tam($Campo[23], 1, 'X');

		// Campo 24 (Data de emissão do título)							[6] 151-156     N 
		$Campo[24] = dataConv($lin[DataLancamento],"Y-m-d","DDMMAA");
		$Campo[24] = preenche_tam($Campo[24], 6, '');		
		
		// Campo 25 (Primeira instrução de cobrança)					[2] 157-158     N verificar
		$Campo[25] = "";
		$Campo[25] = preenche_tam($Campo[25], 2, '');
		
		// Campo 26 (Segunda instrução de cobrança)						[2]  159-160    N verifivar
		$Campo[26] = "";
		$Campo[26] = preenche_tam($Campo[26], 2, '');	
		
		// Campo 27 (Valor dos juros a ser cobrado por dia de atraso)	[13] 161-173    N verificar
		$Campo[27] = "";
		$Campo[27] = preenche_tam($Campo[27], 13, '');
		
		// Campo 28 (Data limite para o desconto)						[6]  174-179    N verificar
		$Campo[28] = dataConv('','Y-m-d H:i:s','dmY');
		$Campo[28] = preenche_tam($Campo[28], 6, '');
		
		// Campo 29 (Valor do desconto a ser concedido)					[13] 180-192     N verificar
		$Campo[29] = '';
		$Campo[29] = preenche_tam($Campo[29], 13, '');
		
		// Campo 30 (Valor do IOF a ser recolhido pelo banco)			[2]  193-205     N verificar 
		$Campo[30] = '';
		$Campo[30] = preenche_tam($Campo[30], 2, '');
		
		// Campo 31 (Valor do abatimento concedido (MULTA))				[2] 206-218      N verificar
		$Campo[31] = '';
		$Campo[31] = preenche_tam($Campo[31], 2, '');
		
		// Campo 32 (Identifica o tipo de inscrição do sacado)			[2] 219-220		 N verificar 
		$Campo[32] = '';
		$Campo[32] = preenche_tam($Campo[32], 2, '');				
		
		// Campo 33 (Número de inscrição do sacado (CPF/CNPJ))			[14] 221-234     N verificar
		$Campo[33] = '';	
		$Campo[33] = preenche_tam($Campo[33], 14, '');				
		
		// Campo 34 (Razão Social / Nome do sacado)						[40] 235-274     X verificar 
		$Campo[34] = '';
		$Campo[34] = preenche_tam($Campo[34], 40, 'X');
		
		// Campo 35 (Logradouro, número, complemento, etc.)				[38] 275-312     X verificar 
		$Campo[35] = '';
		$Campo[35] = preenche_tam($Campo[35], 38, 'X');
		
		// Campo 36 (Instrução de não recebimento do bloqueto.)			[2] 313-314      N verificar 
		$Campo[36] = "";
		$Campo[36] = preenche_tam($Campo[36], 2, '');
		
		// Campo 37 (Bairro do sacado)									[12] 315-326     X verificar 
		$Campo[37] = "";
		$Campo[37] = preenche_tam($Campo[37], 12, 'X');
		
		// Campo 38 (Código de endereço Postal)							[1] 327-331      N verificar 
		$Campo[38] = "";
		$Campo[38] = preenche_tam($Campo[38], 5, '');
		
		// Campo 39 (Complemento do CEP)								[3] 332-334      N verificar
		$Campo[39] = "";
		$Campo[39] = preenche_tam($Campo[39], 3, '');
		
		// Campo 40 (Cidade do sacado Praça de pagamento)				[15] 335-349     X verificar 
		$Campo[40] = "";
		$Campo[40] = preenche_tam($Campo[40], 15, 'X');
		
		// Campo 41 (Estado do sacado)									[2] 350-351		 X verificar
		$Campo[41] = "";
		$Campo[41] = preenche_tam($Campo[41], 2, 'X');

		// Campo 42 (Nome do sacador ou avalista)						[39] 352-390	 X 
		$Campo[42] = $NomeSacado;
		$Campo[42] = preenche_tam($Campo[42], 39, 'X');

		// Campo 43 (Tipo de Bloqueto Utilizado)						[1] 391-391		 X 
		$Campo[43] = "";
		$Campo[43] = preenche_tam($Campo[43], 1, 'X');

		// Campo 44 (Número de dias para protesto)						[2] 392-393		 N verificar
		$Campo[44] = "";
		$Campo[44] = preenche_tam($Campo[44], 2, '');

		// Campo 45 (Tipo de Moeda)										[1] 394-394		 N 
		$Campo[45] = 9;
		$Campo[45] = preenche_tam($Campo[45], 1, '');
		
		// Campo 46 (Número seqüencial do registro no arquivo)			[6] 395-400		 N 
		$Campo[46] = $NumSeqArquivo;
		$Campo[46] = preenche_tam($Campo[46], 6, '');
	
		// Salva
		$Linha[$i] = concatVar($Campo);	
		$i++;
	
		$Campo = null;
	}

###	// Registro Trailer
	
	// Campo  1 (Identificação do Registro Trailer)					[1]   1-1		N
	$Campo[1] = "9";
		
	// Campo  2 (Uso do Banco)										[393] 4-7		X
	$Campo[2] = " ";
	$Campo[2] = preenche_tam($Campo[2], 394, 'X');

	// Campo  3 (Número seqüencial do registro no arquivo)			[6]  395-400	N
	$Campo[3] = $NumSeqArquivo;
	$Campo[3] = preenche_tam($Campo[3], 6, '');	
		
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;	
	
	$Campo = null;
?>
