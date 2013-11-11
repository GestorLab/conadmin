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

### // Registro Header de Lote

	// Campo  1 (Identiicação do registro header)				[1]   1-1		N 
	$Campo[1] = "0";								

	// Campo  2 (Tipo de Operação - Remessa)					[1]   2-2		N
	$Campo[2] = "1";
	$Campo[2] = preenche_tam($Campo[2], 1, '');

	// Campo  3 (Identificação por extenso do movimento)		[7]   3-9		X 
	$Campo[3] = "REMESSA";	
	$Campo[3] = preenche_tam($Campo[3], 7, 'X');

	// Campo  4 (Identificação por tipo de serviço)				[2]  10-11		N 
	$Campo[4] = "01";	
	$Campo[4] = preenche_tam($Campo[4], 2, '');

	// Campo  5 (Identificação por extenso do tipo de serviço)	[15] 12-26		X 
	$Campo[5] = "COBRANÇA";
	$Campo[5] = preenche_tam($Campo[5], 15, 'X');

	// Campo  6 (Agência mantedora da conta)					[4]  27-30		N 
	$Campo[6] = $LocalCobrancaParametro[Agencia];
	$Campo[6] = preenche_tam($Campo[6], 4, '');	
	
	// Campo  7 (Complemento de registro)						[2]  31-32		N		
	$Campo[7] = "00";
	$Campo[7] = preenche_tam($Campo[7], 2, '');

	// Campo  8 (Número da conta corrente da empresa)			[5]  33-37		N 
	$Campo[8] = $LocalCobrancaParametro[Conta];
	$Campo[8] =	preenche_tam($Campo[8], 5, '');

	// Campo  9 (Digito de auto coferencia AG/Conta empresa)	[1]  38-38		N 
	$Campo[9] = $LocalCobrancaParametro[ContaDigito];
	$Campo[9] = preenche_tam($Campo[9], 1, '');

	// Campo  10 (Complemento do registro "Brancos")			[8] 39-46		X  
	$Campo[10] = " ";
	$Campo[10] = preenche_tam($Campo[10], 8, 'X');

	// Campo 11 (Nome por extenso da empresa mãe)				[30] 47-76		X 
	if($DadosEmpresa[TipoPessoa] == 1){
		$Campo[11] = $DadosEmpresa[RazaoSocial];
	}else{
		$Campo[11] = $DadosEmpresa[Nome];
	}		
	$Campo[11] = preenche_tam($Campo[11], 30, 'X');

	// Campo 12 (Nº do banco na câmara de compesação)			[3]  77-79		N 
	$Campo[12] = "341";
	$Campo[12] = preenche_tam($Campo[12], 3, '');

	// Campo 13 (Nome por extenso do banco cobrador)			[15] 80-94 		X 
	$Campo[13] = "BANCO ITAU SA";
	$Campo[13] = preenche_tam($Campo[13], 15, 'X');

	// Campo 14 (Data de geração do arquivo)					[6] 95-100  	N
	$Campo[14] = dataConv(date('Ymd'),'Ymd','DDMMAA');
	$Campo[14] = preenche_tam($Campo[14], 6, '');

	// Campo 15 (Complento do registro "Brancos")				[294] 101-394 	X  
	$Campo[15] = " ";
	$Campo[15] = preenche_tam($Campo[15], 294, 'X');
		
	// Campo 16 (Numero sequencial do registro no arquivo)		[6]	 395-400	N  
	$Campo[16] = "000001";
	$Campo[16] = preenche_tam($Campo[16], 6, '');
	
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;
		
### // Registro Detalhe
	$NumeroSequencialRegistro	 = 1;
	$ValorTotalTitulos			 = 0;
	$QtdContaReceber 			 = 0;

	if($DadosEmpresa[TipoPessoa] == 1){
		$TipoInscricaoCedente = 2;
	}else{
		$TipoInscricaoCedente = 1;
	}		
	
	$sql = "select
				ContaReceberDados.IdLoja,
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.IdPessoa,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.DataLancamento,
				ContaReceberDados.LimiteDesconto,
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
				(SELECT
						ContaReceberPosicaoCobranca.IdLoja,
						ContaReceberPosicaoCobranca.IdContaReceber,
						ContaReceberPosicaoCobranca.IdMovimentacao,
						ContaReceberPosicaoCobranca.IdPosicaoCobranca
					FROM
						(SELECT
						ContaReceberPosicaoCobranca.IdLoja,
						ContaReceberPosicaoCobranca.IdContaReceber,
						MIN(ContaReceberPosicaoCobranca.IdMovimentacao) IdMovimentacao
					FROM
						ContaReceberPosicaoCobranca
					WHERE
						ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00'
					GROUP BY
						ContaReceberPosicaoCobranca.IdLoja,
						ContaReceberPosicaoCobranca.IdContaReceber) ContaReceberPosicaoCobrancaTemp,
						ContaReceberPosicaoCobranca
					WHERE
						ContaReceberPosicaoCobranca.IdLoja = ContaReceberPosicaoCobrancaTemp.IdLoja AND
						ContaReceberPosicaoCobranca.IdContaReceber = ContaReceberPosicaoCobrancaTemp.IdContaReceber AND
						ContaReceberPosicaoCobranca.IdMovimentacao = ContaReceberPosicaoCobrancaTemp.IdMovimentacao) ContaReceberPosicaoCobranca,
				Pessoa,
				PessoaEndereco,
				Estado,
				Cidade,
				LocalCobranca
			where
				(
					(
						LocalCobranca.IdLoja = $local_IdLoja and
						LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
					) or
					(
						LocalCobranca.IdLojaCobrancaUnificada = $local_IdLocalCobranca and
						LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
					)
				) and	
				ContaReceberDados.IdLojaRemessa = LocalCobranca.IdLoja and
				ContaReceberDados.IdLocalCobrancaRemessa = LocalCobranca.IdLocalCobranca and				
				ContaReceberDados.IdArquivoRemessa = $local_IdArquivoRemessa and

				ContaReceberDados.IdLoja = ContaReceberPosicaoCobranca.IdLoja and		
				
				ContaReceberDados.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber and
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
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro";
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
			$TipoInscricaoSacado = 4;
			$NomeSacado = $lin[RazaoSocial];
		}else{
			$TipoInscricaoSacado = 3;
			$NomeSacado = $lin[Nome];
		}		
		
		$CEP = explode("-",$lin[CEP]);
		$CepSacado = $CEP[0];
		$CepSufixo = $CEP[1];

		switch($lin[IdPosicaoCobranca]){
			case 1:
				$sql	=	"UPDATE ContaReceberPosicaoCobranca SET						
								DataRemessa			= curdate(),
								IdArquivoRemessa	= $local_IdArquivoRemessa
							WHERE 
								IdLoja				= '$lin[IdLoja]' and
								IdContaReceber		= '$lin[IdContaReceber]' and	
								IdPosicaoCobranca	= $lin[IdPosicaoCobranca] and
								DataRemessa			= '0000-00-00'";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);		
				$tr_i++;
				
				$Instrucao = "01"; // Remessa
				break;
			case 2:				
				$sql	=	"UPDATE ContaReceberPosicaoCobranca SET						
								DataRemessa			= curdate(),
								IdArquivoRemessa	= $local_IdArquivoRemessa
							WHERE 
								IdLoja				= '$lin[IdLoja]' and
								IdContaReceber		= '$lin[IdContaReceber]' and
								IdPosicaoCobranca	= $lin[IdPosicaoCobranca] and
								DataRemessa			= '0000-00-00'";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);		
				$tr_i++;

				$Instrucao = "09"; // Pedido de protesto
				break;
			case 3:				
				$sql	=	"UPDATE ContaReceberPosicaoCobranca SET						
								DataRemessa			= curdate(),
								IdArquivoRemessa	= $local_IdArquivoRemessa
							WHERE 
								IdLoja				= '$lin[IdLoja]' and
								IdContaReceber		= '$lin[IdContaReceber]' and
								IdPosicaoCobranca	= $lin[IdPosicaoCobranca] and
								DataRemessa			= '0000-00-00'";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);		
				$tr_i++;

				$Instrucao = "18"; // Pedido sustação de protesto
				break;
			case 4:				
				$sql	=	"UPDATE ContaReceberPosicaoCobranca SET						
								DataRemessa			= curdate(),
								IdArquivoRemessa	= $local_IdArquivoRemessa
							WHERE 
								IdLoja				= '$lin[IdLoja]' and
								IdContaReceber		= '$lin[IdContaReceber]' and
								IdPosicaoCobranca	= $lin[IdPosicaoCobranca] and
								DataRemessa			= '0000-00-00'";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);		
				$tr_i++;

				$Instrucao = "02"; // Pedido de Baixa
				break;	
			case 5:				
				$sql	=	"UPDATE ContaReceberPosicaoCobranca SET						
								DataRemessa			= curdate(),
								IdArquivoRemessa	= $local_IdArquivoRemessa
							WHERE 
								IdLoja				= '$lin[IdLoja]' and
								IdContaReceber		= '$lin[IdContaReceber]' and
								IdPosicaoCobranca	= $lin[IdPosicaoCobranca] and
								DataRemessa			= '0000-00-00'";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);		
				$tr_i++;

				$Instrucao = "02"; // Pedido de Baixa quando cancela o recebimento do contas a receber
				break;		
			case 9:
				$sql	=	"UPDATE ContaReceberPosicaoCobranca SET						
								DataRemessa			= curdate(),
								IdArquivoRemessa	= $local_IdArquivoRemessa
							WHERE 
								IdLoja				= '$lin[IdLoja]' and
								IdContaReceber		= '$lin[IdContaReceber]' and
								IdPosicaoCobranca	= $lin[IdPosicaoCobranca] and
								DataRemessa			= '0000-00-00'";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);		
				$tr_i++;

				$Instrucao = "31"; // Alteração de vencimento
				break;
			default:
				$local_transaction[$tr_i] = false;
				$tr_i++;
				break;
		}		
		
		// Registro seguimento P

		// Campo  1 (Identificação do registro transação)			[1]   1-1		N 
		$Campo[1] = "1";
	
		// Campo  2 (Tipo de inscrição da empresa)					[2]   2-3		N 
		$Campo[2] = $TipoInscricaoCedente;
		$Campo[2] = preenche_tam($Campo[2], 2, '');
	
		// Campo  3 (Nº de Inscrição da empresa (CPF/CNPJ))			[14]  4-17		N
		$Campo[3] = $DadosEmpresa[CPF_CNPJ];	
		$Campo[3] = str_replace(".","",$Campo[3]);
		$Campo[3] = str_replace("-","",$Campo[3]);
		$Campo[3] = str_replace("/","",$Campo[3]);
		$Campo[3] = preenche_tam($Campo[3], 14, '');
	
		// Campo  4 (Agencia mantedora da conta)					[4]   18-21		N
		$Campo[4] = $LocalCobrancaParametro[Agencia];
		$Campo[4] = preenche_tam($Campo[4], 4, '');
				
		// Campo  5 (Complemento de Registro)						[2]   22-23		N	
		$Campo[5] = "00";
		$Campo[5] = preenche_tam($Campo[5], 2, '');	
		
		// Campo  6 (Numero da conta corrente da empresa)			[5]   24-28		N
		$Campo[6] = $LocalCobrancaParametro[Conta];
		$Campo[6] = preenche_tam($Campo[6], 5, '');
	
		// Campo  7 (Digito de auto conferencia AG/Conta Empresa)	[1]   29-29		N
		$Campo[7] = $LocalCobrancaParametro[ContaDigito];
		$Campo[7] = preenche_tam($Campo[7], 1, '');
	
		// Campo  8 (Complemento de Registro "Brancos")				[4]   30-33		X
		$Campo[8] = " ";
		$Campo[8] = preenche_tam($Campo[8], 4, 'X');
		
		// Campo  9 (Cod. Instrução/Alegação a ser cancelada)		[4]   34-37		N 
		$Campo[9] = "0";	
		$Campo[9] = preenche_tam($Campo[9], 4, '');
		
		// Campo  10 (Identificação do titulo na empresa)			[25]  38-62		X 
		$Campo[10] = $lin[NumeroDocumento];
		$Campo[10] = preenche_tam($Campo[10], 25, 'X');
		
		// Campo  11 (Identificação do titulo no banco)				[8]   63-70	    N 
		$Campo[11] = $lin[NumeroDocumento];
		$Campo[11] = preenche_tam($Campo[11], 8, '');
	
		// Campo  12 (Quantidade de moeda variavel)					[13]   71-83	N 
		$Campo[12] = "0";		
		$Campo[12] = preenche_tam($Campo[12], 13, '');

		// Campo  13 (Numero da carteira no banco)					[3]   84-86		N 
		$Campo[13] = $LocalCobrancaParametro[Carteira];
		$Campo[13] = preenche_tam($Campo[13], 3, '');

		// Campo  14 (Identificação da operação no banco)			[21]  87-107	X
		$Campo[14] = " ";
		$Campo[14] = preenche_tam($Campo[14], 21, 'X');
	
		// Campo  15 (Código da carteira)							[1]  108-108	X 
		switch($LocalCobrancaParametro[Carteira]){
			case '147':
				$Campo[15] = 'E';		
				break;
			case '150':
				$Campo[15] = 'U';		
				break;
			default:
				$Campo[15] = 'I';						
		}
		$Campo[15] = preenche_tam($Campo[15], 1, 'X');
	
		// Campo  16 (Identificação da ocorrencia)					[2]  109-110	N
		$Campo[16] = $Instrucao;
		$Campo[16] = preenche_tam($Campo[16], 2, '');
	
		// Campo  17 (Nº do documento de cobrança (Dupl.NP ETC.))	[10] 111-120	X
		$NumeroDocumento = preenche_tam($lin[NumeroDocumento], 8, '');
		$Numeracao = $LocalCobrancaParametro[Agencia].$LocalCobrancaParametro[Conta].$LocalCobrancaParametro[Carteira].$NumeroDocumento;		
		$Campo[17] = $NumeroDocumento.mod10($Numeracao);		
		$Campo[17] = preenche_tam($Campo[17], 10, 'X');

		// Campo  18 (Data de vencimento do titulo)					[6]  121-126	N 
		$Campo[18] = dataConv($lin[DataVencimento],'Y-m-d','DDMMAA');		
		$Campo[18] = preenche_tam($Campo[18], 6, '');

		// Campo  19 (Valor nominal do titulo)						[13]  127-139	N 
		$Campo[19] = number_format($lin[ValorFinal], 2, '.', '');			
		$Campo[19] = str_replace(".","",$Campo[19]);
		$Campo[19] = preenche_tam($Campo[19], 13, '');
		
		// Campo  20 (Nº do Banco na camara de compensação)			[3]  140-142	N 
		$Campo[20] = "341";
		$Campo[20] = preenche_tam($Campo[20], 3, '');	
		
		// Campo  21 (Agencia onde o titulo será cobrado)			[5]  143-147	N 
		$Campo[21] = "0";		
		$Campo[21] = preenche_tam($Campo[21], 5, '');
			
		// Campo 22 (ESPÉCIE DO TÍTULO)								[2]	 148-149    X 01 Duplicata Mercantil
		switch($LocalCobrancaParametro[EspecieDocumento]){
			case "DM":
				$Campo[22] = "01";
				break;
			default:
				$local_transaction[$tr_i] = false;
				$tr_i++;
		}
		
		$Campo[22] = preenche_tam($Campo[22], 2, 'X');

		// Campo 23 (Identificação de titulo aceito ou não aceito)	[1] 150-150     X 
		$Campo[23] = $LocalCobrancaParametro[Aceite];
		$Campo[23] = preenche_tam($Campo[23], 1, 'X');

		// Campo 24 (Data da emissão do titulo)						[6] 151-156     N
		$Campo[24] = dataConv($lin[DataLancamento],'Y-m-d','DDMMAA');		
		$Campo[24] = preenche_tam($Campo[24], 6, '');
		
		// Campo 25 (1ª Instrução de cobrança)						[2] 157-158     X 		
		$Campo[25] = $LocalCobrancaParametro[InstrucaoCobracaPrimeira];
		$Campo[25] = preenche_tam($Campo[25], 2, 'X');
		
		// Campo 26 (2ª Instrução de cobrança)						[2] 159-160		X 
		$Campo[26] = $LocalCobrancaParametro[InstrucaoCobracaSegunda];
		$Campo[26] = preenche_tam($Campo[26], 2, 'X');	
		
		// Campo 27 (Valor de mora por dia de atraso)				[13] 161-173    N 
		$Campo[27] = $lin[ValorFinal]*$DadosLocalCobranca[PercentualJurosDiarios]/100;
		$Campo[27] = number_format($Campo[27], 2, '.', '');	
		$Campo[27] = str_replace(".","",$Campo[27]);
		$Campo[27] = preenche_tam($Campo[27], 13, '');
		
		// Campo 28 (Data limite para concessão de desconto)		[6] 174-179     N 
		$Campo[28] = dataConv($DataDesconto,'Y-m-d','DDMMAA');
		$Campo[28] = preenche_tam($Campo[28], 6, '');
		
		// Campo 29 (Valor do desconto a ser concedido)				[13] 180-192    N
		$Campo[29] = number_format($lin2[ValorDescontoAConceber], 2, '.', '');
		$Campo[29] = str_replace(".","",$Campo[29]);
		$Campo[29] = preenche_tam($Campo[29], 13, '');
		
		// Campo 30 (Valor do I.O.F recolhido p/ notas seguro)		[13] 193-205    N 
		$Campo[30] = 0;
		$Campo[30] = preenche_tam($Campo[30], 13, '');
		
		// Campo 31 (Valor do abatimento a ser concedido)			[13] 206-218    N 
		$Campo[31] = 0;
		$Campo[31] = preenche_tam($Campo[31], 13, '');
		
		// Campo 32 (Identificação do tipo de inscrição/sacado)		[2] 219-220		N 
		$Campo[32] = $TipoInscricaoSacado;	
		$Campo[32] = preenche_tam($Campo[32], 2, '');				
		
		// Campo 33 (Nº de inscricao do sacado (CPF/CNPJ))			[14] 221-234    N 
		$Campo[33] = $lin[CPF_CNPJ];
		$Campo[33] = str_replace(".","",$Campo[33]);
		$Campo[33] = str_replace("-","",$Campo[33]);
		$Campo[33] = str_replace("/","",$Campo[33]);		
		$Campo[33] = preenche_tam($Campo[33], 14, '');				
		
		// Campo 34 (Nome do Sacado)								[30] 235-264    X 
		$Campo[34] = $NomeSacado;
		$Campo[34] = preenche_tam($Campo[34], 30, 'X');
		
		// Campo 35 (Complemento de Registro "Brancos")				[10] 265-274    X 
		$Campo[35] = " ";
		$Campo[35] = preenche_tam($Campo[35], 10, 'X');
		
		// Campo 36 (Rua, Numero e complemento do sacado)			[40] 275-314    X 
		$Campo[36] = $lin[Endereco].", ".$lin[Numero];
		if($lin[Complemento] != ''){
			$Campo[36] .= " - ".$lin[Complemento];
		}
		$Campo[36] = preenche_tam($Campo[36], 40, 'X');
		
		// Campo 37 (Bairro do sacado)								[12] 315-326    N 
		$Campo[37] = $lin[Bairro];
		$Campo[37] = preenche_tam($Campo[37], 12, '');
		
		// Campo 38 (CEP do Sacado)									[8] 327-334     N
		$Campo[38] = str_replace("-","",$lin[CEP]);
		$Campo[38] = preenche_tam($Campo[38], 8, '');
		
		// Campo 39 (Cidade do sacado)								[15] 335-349    X
		$Campo[39] = $lin[NomeCidade];
		$Campo[39] = preenche_tam($Campo[39], 15, 'X');
		
		// Campo 40 (UF do sacado)									[2] 350-351	    X
		$Campo[40] = $lin[SiglaEstado];
		$Campo[40] = preenche_tam($Campo[40], 2, 'X');
		
		// Campo 41 (Nome do sacador ou avalista)					[30] 352-381    X 
		$Campo[41] = "";
		$Campo[41] = preenche_tam($Campo[41], 30, 'X');

		// Campo 42 (Complemento do registro)						[4]  382-385    X 
		$Campo[42] = " ";
		$Campo[42] = preenche_tam($Campo[42], 4, 'X');			

		// Campo 43 (Data de MORA)									[6]  386-391    N 
		$Campo[43] = "";
		$Campo[43] = preenche_tam($Campo[43], 6, '');			

		// Campo 44 (Quantidade de Dias)							[2]  392-393    N 
		$Campo[44] = $LocalCobrancaParametro[QuantidadeDias];
		$Campo[44] = preenche_tam($Campo[44], 2, '');
		
		// Campo 45 (Complemento do registro)						[1]  394-394    X
		$Campo[45] = " ";
		$Campo[45] = preenche_tam($Campo[45], 1, 'X');

		// Campo 46 (Nº Sequencial do registro no arquivo)			[6]  395-400    N
		$NumeroSequencialRegistro += 1;
		$Campo[46] = $NumeroSequencialRegistro;
		$Campo[46] = preenche_tam($Campo[46], 6, '');

		// Salva
		$Linha[$i] = concatVar($Campo);	
		$i++;
	
		$Campo = null;			
	}

###	// Trailer de Arquivo
	
	// Campo  1 (Identificação do registro trailer)					[1]  1-1		N
	$Campo[1] = "9";
		
	// Campo  2 (Complemento do registro)							[393] 2-394		X
	$Campo[2] = " ";
	$Campo[2] = preenche_tam($Campo[2], 393, 'X');

	// Campo  3 (Numero sequencial do registro no arquivo)			[6]  395-400	N
	$NumeroSequencialRegistro += 1;
	$Campo[3] = $NumeroSequencialRegistro;
	$Campo[3] = preenche_tam($Campo[3], 6, '');
		
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;	
?>
