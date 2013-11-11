<?
	include("funcao_layout.php");
	// Gera a nomenclatura do arquivo

	$Patch = "remessa/local_cobranca/$local_IdLoja/$local_IdLocalCobranca/$local_IdArquivoRemessa";	
	
	$NomeArquivo = $ParametroLocalCobranca['CodigoCedente'].codigoMesDia().".CRM";

	$sql= "select
				count(*) Qtd
			from
				ArquivoRemessa
			where
				IdLoja = $local_IdLoja and
				IdLocalCobranca = $local_IdLocalCobranca and
				DataRemessa = curdate()";
	$res = mysql_query($sql, $con);
	if($lin = mysql_fetch_array($res)){
		if($lin[Qtd] > 1){
			$NomeArquivo = $ParametroLocalCobranca['CodigoCedente'].codigoMesDia().".RM".$lin[Qtd];		
		}
	}

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

### // Registro header

	// Campo  1 (Código do Registro)							[1]   1-1		N
	$Campo[1] = "0";
	$Campo[1] = preenche_tam($Campo[1], 1, '');

	// Campo  2 (Código da Remessa)								[1]   2-2		N
	$Campo[2] = "1";
	$Campo[2] = preenche_tam($Campo[2], 1, '');

	// Campo  3 (Literal da Remessa)							[7]   3-9		X
	$Campo[3] = "REM.TST";
	$Campo[3] = preenche_tam($Campo[3], 7, 'X');

	// Campo  4 (Código do Serviço)								[2]   10-11		N
	$Campo[4] = "01";
	$Campo[4] = preenche_tam($Campo[4], 2, '');

	// Campo  5 (Literal de Serviço)							[15]  12-26		X
	$Campo[5] = "COBRANCA";
	$Campo[5] = preenche_tam($Campo[5], 15, 'X');

	// Campo  6 (Código da Empresa)								[16]   27-42	N #verificar
	$Campo[6] = 0;
	$Campo[6] = preenche_tam($Campo[6], 16, '');

	// Campo  7 (Brancos)										[4]   43-46		X	
	$Campo[7] = preenche_tam(" ", 4, 'X');
	
	// Campo  8 (Nome da Empresa)								[30]  47-76		X
	$Campo[8] = $DadosEmpresa[RazaoSocial];
	$Campo[8] = preenche_tam($Campo[8], 30, 'X');	

	// Campo  9 (Código do Banco)								[3]   77-79     N
	$Campo[9] = "104";
	$Campo[9] = preenche_tam($Campo[9], 3, '');

	// Campo 10 (Nome do Banco)									[15]  80-94		X
	$Campo[10] = "C ECON FEDERAL";
	$Campo[10] = preenche_tam($Campo[10], 15, 'X');
	
	// Campo 11 (Data de Gravação)								[6]	  95-100    N
	$Campo[11] = Date("Ymd");
	$Campo[11] = preenche_tam($Campo[11], 6, '');
	
	// Campo 12 (Filler)										[289] 101-389   X
	$Campo[12] = preenche_tam(" ", 289, 'X');

	// Campo 13 (Número da remessa)								[5]	  390-394   N
	$Campo[13] = $NumSeqArquivo;
	$Campo[13] = preenche_tam($Campo[13], 5, '');
	
	// Campo 14 (Número sequencial do registro)					[6]	  395-400	N
	$NumSeqRegistro = 0;
	$NumSeqRegistro++;
	$Campo[14] = $NumSeqRegistro;
	$Campo[14] = preenche_tam($Campo[14], 6, '');

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;


### // Registro 1 - Registro da Transação

	$sql = "select
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.IdPessoa,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.DataLancamento,
				ContaReceberDados.IdCarne,
				ContaReceberPosicaoCobranca.IdPosicaoCobranca,
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
				ContaReceberPosicaoCobranca,
				Pessoa,
				PessoaEndereco,
				Estado,
				Cidade
			where
				ContaReceberDados.IdLoja = $local_IdLoja and
				ContaReceberDados.IdLoja = ContaReceberPosicaoCobranca.IdLoja and
				ContaReceberDados.IdLocalCobranca = $local_IdLocalCobranca and
				ContaReceberDados.IdArquivoRemessa = $local_IdArquivoRemessa and
				ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
				ContaReceberDados.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber and
				ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Estado.IdPais and
				PessoaEndereco.IdPais = Cidade.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		
		$lin[AnoLancamento] = substr($lin[DataLancamento],2,2);
			
		$lin[EnderecoCompleto] = $lin[Endereco];	
		if($lin[Numero] != ""){
			$lin[EnderecoCompleto] .= ", nº".$lin[Numero];
		}
		if($lin[Complemento] != ""){
			$lin[EnderecoCompleto] .= $lin[Complemento];	
		}
		$lin[EnderecoCompleto] .= ", ".$lin[NomeCidade]." - ".$lin[SiglaEstado];
		
		$sql = "
			select
				sum(LancamentoFinanceiro.ValorDescontoAConceber) ValorDescontoAConceber,
				min(LancamentoFinanceiro.LimiteDesconto) LimiteDesconto					
			from	
				LancamentoFinanceiroContaReceber,
				LancamentoFinanceiro					
			where
				LancamentoFinanceiroContaReceber.IdLoja = $local_IdLoja and
				LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
				LancamentoFinanceiroContaReceber.IdContaReceber = $lin[IdContaReceber] and
				LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro";
		$res2 = mysql_query($sql,$con);
		$lin2 = mysql_fetch_array($res2);		
		
		if($lin2[ValorDescontoAConceber] > 0){
			$DataDesconto = incrementaData($lin[DataVencimento],$lin2[LimiteDesconto]);  
			$DataDesconto = dataConv($DataDesconto,'Y-m-d','dmY');
		}else{
			$DataDesconto = "";		
		}
		
	
		# Registro detalhe - cobrança sem registro
		
		// Campo  1 (Código do Registro)							[1]   1-1		N
		$Campo[1] = "1";
		$Campo[1] = preenche_tam($Campo[1], 1, '');

		// Campo  2 (Inscrição da Empresa)							[1]   2-3		N 
		if($DadosEmpresa[TipoPessoa] == 1){
			$Campo[2] = 02;	
		}else{
			$Campo[2] = 01;	
		}		
		$Campo[2] = preenche_tam($Campo[2], 2, '');
		
		// Campo  3 (Número da inscrição)							[14]  4-17		N
		$Campo[3] = removeMascaraCPF_CNPJ($DadosEmpresa[CPF_CNPJ]);
		$Campo[3] = preenche_tam($Campo[3], 14, '');		

		// Campo  4 (Código da Empresa)								[16]  18-33		N #verificar	
		$Campo[4] = " ";		
		$Campo[4] = preenche_tam($Campo[4], 16, '');
	
		// Campo  5 (Filler)										[2]   34-35		X
		$Campo[5] = preenche_tam(" ", 2, 'X');

		// Campo  6 (Taxa de permanência)							[2]  36-37		N #verificar
		$Campo[6] = $ParametroLocalCobranca[TaxaComissaoPermanencia];
		$Campo[6] = preenche_tam($Campo[6], 2, '');

		// Campo  7 (Uso da Empresa)								[25] 38-62		X #verificar
		$Campo[7] = "";
		$Campo[7] = preenche_tam($Campo[7], 25, 'X');
		
		// Campo  8 (Nosso Número)									[11] 63-73		N #verificar
		$Campo[8] = "";
		$Campo[8] = preenche_tam($Campo[8], 11, '');
		
		// Campo  9 (Brancos)										[3] 74-76	    X
		$Campo[9] = preenche_tam(" ", 3, 'X');
		
		// Campo  10 (Mensagem a ser impressa no bloqueto)			[30] 77-106		X #verficar
		$Campo[10] = "";
		$Campo[10] = preenche_tam($Campo[10], 30, 'X');		
				
		// Campo  11 (Carteira)										[2] 107-108	    N #verificar
		switch($ParametroLocalCobranca[Carteira]){
			case 'CS':
				$Campo[11] = 11;
				break;
			case 'CR':
				$Campo[11] = 12;
				break;
			case 'SR':
				$Campo[11] = 14;
				break;
			case 'DE':
				$Campo[11] = 41;
				break;
			default:
				$local_transaction[$tr_i] = false;
				$tr_i++;
				break;
		}		
		$Campo[11] = preenche_tam($Campo[11], 2, '');
		
		// Campo  12 (Código de Ocorrência)							[1] 109-110		N #verificar 
		$Campo[12] = "";
		$Campo[12] = preenche_tam($Campo[12], 1, '');

		// Campo  13 (Seu número)									[10] 111-120	X #verificar
		$Campo[13] = "";
		$Campo[13] = preenche_tam($Campo[13], 10, 'X');
		
		// Campo  14 (Data de vencimento do Título)	DDMMAA			[6] 121-126		N 
		$Campo[14] = dataConv($lin[DataVencimento],"Y-m-d","DDMMAA");
		$Campo[14] = preenche_tam($Campo[14], 6, '');
		
		// Campo  15 (Valor nominal do Título)						[13] 127-139    N 
		$Campo[15] = number_format($lin[ValorFinal], 2, '.', '');			
		$Campo[15] = str_replace(".","",$Campo[15]);
		$Campo[15] = preenche_tam(" ", 13, '');
		
		// Campo  16 (Código de compensação da CAIXA)				[3] 140-142		N  
		$Campo[16] = "104";
		$Campo[16] = preenche_tam($Campo[16], 3, '');		
		
		// Campo  17 (Agência encarregada da Cobrança)				[5] 143-147		N #verificar  
		$Campo[17] = $ParametroLocalCobranca[Agencia].$ParametroLocalCobranca[AgenciaDigito];
		$Campo[17] = preenche_tam($Campo[17], 5, '');
		
		// Campo  18 (Espécie do Título)							[2] 148-149     N 
		switch($ParametroLocalCobranca[EspecieDocumento]){
			case 'DM':
				$Campo[18] = 01;
				break;
			case 'NP':
				$Campo[18] = 02;
				break;
			case 'DS':
				$Campo[18] = 03;	
				break;
			case 'NS':
				$Campo[18] = 05;	
				break;
			case 'LC':
				$Campo[18] = 06;	
				break;
			case 'OU':
				$Campo[18] = 09;	
				break;
			default:
				$local_transaction[$tr_i] = false;
				$tr_i++;
				break;				
		}
		$Campo[18] = preenche_tam($Campo[18], 2, '');
	
		// Campo  19 (Identificação do Título aceito ou não aceito)	[1] 150-150		X #verificar  
		$Campo[19] = "";
		$Campo[19] = preenche_tam($Campo[19], 1, 'X');

		// Campo  20 (Data de emissão do Título)					[6] 151-156		N  
		$Campo[20] = dataConv($lin[DataLancamento],'Y-m-d','DDMMAA');
		$Campo[20] = preenche_tam($Campo[20], 6, '');

		// Campo  21 (Primeira instrução de Cobrança)				[2] 157-158		N #verificar  
		$Campo[21] = "";
		$Campo[21] = preenche_tam($Campo[21], 2, '');

		// Campo  22 (Segunda instrução de Cobrança)				[2] 159-160		N   
		$Campo[22] = "0";
		$Campo[22] = preenche_tam($Campo[22], 2, '');

		// Campo  23 (Valor/% de juros por dia de atrazo)			[13] 161-173	N 
		$Campo[23] = $lin[ValorFinal]*$DadosLocalCobranca[PercentualJurosDiarios]/100;			
		$Campo[23] = number_format($Campo[23], 2, '.', '');		
		$Campo[23] = str_replace(".","",$Campo[23]);
		$Campo[23] = preenche_tam($Campo[23], 13, '');
		
		// Campo  24 (Data limite para concessão do desconto)		[6] 174-179		N 		
		$Campo[24] = dataConv($DataDesconto,'Y-m-d','dmy');;
		$Campo[24] = preenche_tam($Campo[24], 6, '');
		
		// Campo  25 (Valor do Desconto a ser concedido)			[13] 180-192	N  
		$Campo[25] = number_format($lin2[ValorDescontoAConceber], 2, '.', '');
		$Campo[25] = str_replace(".","",$Campo[25]);
		$Campo[25] = preenche_tam($Campo[25], 13, '');
		
		// Campo  26 (Filler)										[13] 193-205    N
		$Campo[26] = preenche_tam("0", 12, '');

		// Campo  27 (Valor do abatimento a ser concedido)			[13] 206-218    N
		$Campo[27] = preenche_tam("0", 13, '');
	
		// Campo  28 (Identificador do tipo de inscrição do Sacado)	[2] 219-220     N #verificar
		$Campo[28] = "";
		$Campo[28] = preenche_tam("", 2, '');
		
		// Campo  29 (CIC/CGC do sacado)								[14] 221-234	N
		$Campo[29] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[29] = preenche_tam($Campo[29], 14, '');

		// Campo  30 (CIC/CGC do sacado)								[14] 235-274	X
		if($lin[TipoPessoa] == 1){
			$Campo[30] = $lin[RazaoSocial];
		}else{
			$Campo[30] = $lin[Nome];
		}		
		$Campo[30] = preenche_tam(removeCaracters($Campo[30]), 40, 'X');
		
		// Campo  31 (Logradouro)										[25] 275-314	X $verificar
		if($lin[Numero] != ""){
			$lin[Endereco] .= ", nº".$lin[Numero];	
		}
		$Campo[31] = removeCaracters($lin[Endereco]);
		$Campo[31] = preenche_tam($Campo[31], 25, 'X');	

		// Campo  32 (Bairro do Sacado)									[12] 315-326	X $verificar
		$Campo[32] = removeCaracters($lin[Endereco]);
		$Campo[32] = preenche_tam($Campo[32], 12, 'X');	

		// Campo  33 (CEP do sacado)									[8] 327-334		N
		$Campo[33] = str_replace("-","",$lin[CEP]);
		$Campo[33] = preenche_tam($Campo[33], 8, '');	

		// Campo  34 (Cidade do sacado)									[15] 335-349	X 
		$Campo[34] = removeCaracters($lin[NomeCidade]);
		$Campo[34] = preenche_tam($Campo[34], 15, 'X');	

		// Campo  35 (Estado do sacado)									[2] 350-351		X 
		$Campo[35] = $lin[SiglaEstado];
		$Campo[35] = preenche_tam($Campo[35], 2, 'X');
		
		// Campo  36 (Definição da data para pagamento de multa)		[6] 352-357		N #verificar
		$Campo[36] = "";
		$Campo[36] = preenche_tam($Campo[36], 6, '');

		// Campo  37 (Valor nominal da multa)							[10] 352-357	N 
		$Campo[37] = number_format($DadosLocalCobranca[PercentualMulta], 2, '.', '');
		$Campo[37] = str_replace(".","",$Campo[37]);
		$Campo[37] = preenche_tam($Campo[37], 10, '');

		// Campo  38 (Nome do Sacador Avalista)							[22] 368-389	X #verificar
		$Campo[38] = " ";
		$Campo[38] = preenche_tam($Campo[38], 22, 'X');

		// Campo  39 (Terceira instrução de Cobrança)					[2] 390-391		N  #verificar
		$Campo[39] = " ";
		$Campo[39] = preenche_tam($Campo[39], 2, '');

		// Campo  40 (Quantidade de dias para início da ação de			[2] 392-393		N  #verificar
		//				protesto ou devolução do Título)
		$Campo[40] = " ";
		$Campo[40] = preenche_tam($Campo[40], 2, '');

		// Campo  41 (Código da Moeda)									[1] 394-394		N  #verificar
		$Campo[41] = " ";
		$Campo[41] = preenche_tam($Campo[41], 1, '');

		// Campo  52 (Numero sequencial do registro)				[6] 395-400		N 
		$NumSeqRegistro +=1;
		$Campo[48] = $NumSeqRegistro;
		$Campo[48] = preenche_tam($Campo[48], 6, '');

		// Salva
		$Linha[$i] = concatVar($Campo);		
		$i++;
		
		$Campo = null;
		
		
		# Registro Mensagem
		
		// Campo  1 (Código do Registro)							[1]   1-1		N
		$Campo[1] = "2";
		$Campo[1] = preenche_tam($Campo[1], 1, '');
		
		// Campo  2 (Inscrição da Empresa)							[2]    2-3		N $verificar
		$Campo[2] = preenche_tam(" ", 2, '');

		// Campo  3 (Número da inscrição)							[14]  4-17		N $verificar 		
		$Campo[3] = "";
		$Campo[3] = preenche_tam($Campo[3], 14, '');
		
		// Campo  4 (Identificação da Empresa na CAIXA)				[18]  18-33		N $verificar 		
		$Campo[4] = "";
		$Campo[4] = preenche_tam($Campo[4], 16, '');
		
		// Campo  5 (Filler)										[29]  34-62		X
		$Campo[5] = preenche_tam(" ", 43, 'X');	

		// Campo  6 (Identificação do Titulo na CAIXA)				[11]  63-73		X #verificar
		$Campo[6] = "";
		$Campo[6] = preenche_tam($Campo[6], 11, 'X');	
		
		// Campo  7 (Brancos)										[33]  74-106	X #verificar
		$Campo[7] = preenche_tam(" ", 33, 'X');	
		
		// Campo  8 (Código da Carteira)							[2]  107-108	X #verificar
		$Campo[8] = "";
		$Campo[8] = preenche_tam($Campo[8], 2, 'X');

		// Campo  9 (Código de Ocorrência)							[2]  109-110	X #verificar
		$Campo[9] = "";
		$Campo[9] = preenche_tam($Campo[9], 2, 'X');

		// Campo  10 (Brancos)										[29] 111-139	X #verificar
		$Campo[10] = "";
		$Campo[10] = preenche_tam($Campo[10], 2, 'X');

		// Campo  11 (Código de Compensação da CAIXA)				[3] 140-142		N 
		$Campo[11] = "104";
		$Campo[11] = preenche_tam($Campo[11], 3, '');

		// Campo  12 (Mensagem 1 a ser impressa no Bloqueto)		[40]  143-183	X
		$Campo[12] = removeCaracters($ParametroLocalCobranca['Instrucoes1']);
		$Campo[12] = preenche_tam($Campo[12], 40, 'X');
		
		// Campo  13 (Mensagem 2 a ser impressa no Bloqueto)		[40]  183-222	X
		$Campo[13] = removeCaracters($ParametroLocalCobranca['Instrucoes2']);
		$Campo[13] = preenche_tam($Campo[13], 40, 'X');
		
		// Campo  14 (Mensagem 3 a ser impressa no Bloqueto)		[40]   223-262	X
		$Campo[14] = removeCaracters($ParametroLocalCobranca['Instrucoes3']);
		$Campo[14] = preenche_tam($Campo[14], 40, 'X');
		
		// Campo  15 (Mensagem 4 a ser impressa no Bloqueto)		[40]   263-302	X
		$Campo[15] = removeCaracters($ParametroLocalCobranca['Instrucoes4']);
		$Campo[15] = preenche_tam($Campo[15], 40, 'X');

		// Campo  16 (Mensagem 5 a ser impressa no Bloqueto)		[40]   303-342	X
		$Campo[16] = removeCaracters($ParametroLocalCobranca['Instrucoes5']);
		$Campo[16] = preenche_tam($Campo[16], 40, 'X');
		
		// Campo  17 (Mensagem 5 a ser impressa no Bloqueto)		[40]   303-342	X
		$Campo[17] = removeCaracters($ParametroLocalCobranca['Instrucoes6']);
		$Campo[17] = preenche_tam($Campo[17], 40, 'X');
		
		// Campo  18 (Seu Número (nunca se repete))					[12]  383-394	X
		$Campo[18] = " ";
		$Campo[18] = preenche_tam($Campo[18], 12, '');		

		// Campo  19 (Número sequencial do registro)				[6]  395-400	N
		$NumSeqRegistro +=1;
		$Campo[19] = $NumSeqRegistro;
		$Campo[19] = preenche_tam($Campo[19], 6, '');
		
		// Salva
		$Linha[$i] = concatVar($Campo);		
		$i++;
		
		$Campo = null;		
	}	
	
	### Registro Trailer
	
	// Campo  1 (Código do Registro)								[9]  1-1		N
	$Campo[1] = "9";
		
	// Campo  2 (Brancos)											[393] 2-294		N
	$Campo[2] = " ";
	$Campo[2] = preenche_tam($Campo[2], 393, '');
	
	// Campo  3 (Numero sequencial do registro)						[6]  395-400	N
	$NumSeqRegistro +=1;
	$Campo[3] = $NumSeqRegistro;
	$Campo[3] = preenche_tam($Campo[3], 6, '');	

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;		
?>
