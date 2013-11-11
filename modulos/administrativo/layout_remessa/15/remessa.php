<?
	include("funcao_layout.php");
	// CONVÊNIOS COM NÚMERAÇÃO ACIMA DE 1.000.000 CNAB400
	// Gera a nomenclatura do arquivo
	$Patch = "remessa/local_cobranca/$local_IdLoja/$local_IdLocalCobranca/$local_IdArquivoRemessa";	

	$NomeArquivo = $ParametroLocalCobranca['Convenio'].codigoMesDia().".CRM";
	
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
			$NomeArquivo = $ParametroLocalCobranca['Convenio'].codigoMesDia().".RM".$lin[Qtd];		
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

	// Campo  1 (Identificação do Registro Header)				[1]   1-1		N
	$Campo[1] = "0";
	$Campo[1] = preenche_tam($Campo[1], 1, '');

	// Campo  2 (Tipo de Operação)								[1]   2-2		N
	$Campo[2] = "1";
	$Campo[2] = preenche_tam($Campo[2], 1, '');

	// Campo  3 (Identificação por Extenso do Tipo de Operação)	[7]   3-9		X
	$Campo[3] = "REMESSA";
	$Campo[3] = preenche_tam($Campo[3], 7, 'X');

	// Campo  4 (Identificação do Tipo de Serviço)				[2]   10-11		N
	$Campo[4] = "01";
	$Campo[4] = preenche_tam($Campo[4], 2, '');

	// Campo  5 (Identificação por Extenso do Tipo de Serviço)	[8]  12-19		X
	$Campo[5] = "COBRANCA";
	$Campo[5] = preenche_tam($Campo[5], 8, 'X');
	
	// Campo  6 (Complemento do Registro)						[7]  20-26		X
	$Campo[6] = preenche_tam(" ", 7, 'X');

	// Campo  7 (Prefixo da Agência)							[4]   27-30		N
	$Campo[7] = $ParametroLocalCobranca['Agencia'];
	$Campo[7] = preenche_tam($Campo[7], 4, '');

	// Campo  8 (Dígito Verificador - do Prefixo da Agência.)	[1]  31-31		N
	$Campo[8] = $ParametroLocalCobranca['DigitoAgencia'];
	$Campo[8] = preenche_tam($Campo[8], 1, '');
	
	// Campo  9 (Número da Conta Corrente)						[8]  32-39      N
	$Campo[9] = $ParametroLocalCobranca['Conta'];
	$Campo[9] = preenche_tam($Campo[9], 8, '');

	// Campo 10 (Dígito Verificador da Conta Corrente)			[1]  40-40		X
	$Campo[10] = $ParametroLocalCobranca['DigitoConta'];
	$Campo[10] = preenche_tam($Campo[10], 1, 'X');
	
	// Campo 11 (Complemento do Registro)						[6]	 41-46		N
	$Campo[11] = preenche_tam("0", 6, '');

	// Campo 12 (Nome do Cedente)								[30] 47-76      X
	if($DadosEmpresa[TipoPessoa] == 1){
		$Campo[12] = $DadosEmpresa[RazaoSocial];
	}else{
		$Campo[12] = $DadosEmpresa[Nome];
	}
	$Campo[12] = preenche_tam($Campo[12], 30, 'X');

	// Campo 13 (001BANCODOBRASIL)								[18]  77-94     X
	$Campo[13] = "001BANCODOBRASIL";
	$Campo[13] = preenche_tam($Campo[13], 18, 'X');

	// Campo 14 (Data da Gravação)								[6]  95-100		N
	$Campo[14] = dataConv(date('Ymd'),'Ymd','DDMMAA');
	$Campo[14] = preenche_tam($Campo[14], 6, '');
	
	// Campo 15 (Seqüencial da Remessa)							[7]	 101-107	N
	$Campo[15] = $NumSeqArquivo;
	$Campo[15] = preenche_tam($Campo[15], 7, '');
		
	// Campo 16 (Complemento do Registro)						[22] 47-76      X 
	$Campo[16] = preenche_tam(" ", 22, 'X');

	// Campo 17 (Número do Convênio Líder)						[7]	 130-136	N
	$Campo[17] = $ParametroLocalCobranca['Convenio'];
	$Campo[17] = preenche_tam($Campo[17], 7, '');

	// Campo 18 (Complemento do Registro)						[258] 137-394   X 
	$Campo[18] = preenche_tam(" ", 258, 'X');
	
	// Campo 19 (Número sequencial do registro)					[6]	  395-400	N
	$NumSeqRegistro = 0;
	$NumSeqRegistro++;
	$Campo[19] = $NumSeqRegistro;
	$Campo[19] = preenche_tam($Campo[19], 6, '');
	

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;


### // Registro 1 - Registro da Transação

	$sql = "select
				ContaReceberDados.IdLoja,
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.IdPessoa,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.DataLancamento,
				ContaReceberDados.IdCarne,
				ContaReceberDados.IdLocalCobranca,
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
				
				ContaReceberDados.IdPessoa = Pessoa.IdPessoa and			
				ContaReceberDados.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber and
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
					PercentualJurosDiarios,
					PercentualMulta
				from
					LocalCobranca
				where
					IdLoja = $lin[IdLoja] and
					IdLocalCobranca = $lin[IdLocalCobranca]";
		$resLocalCobrancaCR = mysql_query($sql,$con);
		$linLocalCobrancaCR = mysql_fetch_array($resLocalCobrancaCR);

		$sql = "select
					IdLocalCobrancaParametro,
					ValorLocalCobrancaParametro
				from
					LocalCobrancaParametro
				where
					IdLoja = $lin[IdLoja] and
					IdLocalCobranca = $lin[IdLocalCobranca]";
		$resLocalCobrancaParametroCR = mysql_query($sql,$con);
		while($linLocalCobrancaParametroCR = mysql_fetch_array($resLocalCobrancaParametroCR)){
			$ParametroLocalCobrancaCR[$linLocalCobrancaParametroCR[IdLocalCobrancaParametro]] = $linLocalCobrancaParametroCR[ValorLocalCobrancaParametro];
		}
		
		$lin[AnoLancamento] = substr($lin[DataLancamento],2,2);
			
		$lin[EnderecoCompleto] = $lin[Endereco];	
		if($lin[Numero] != ""){
			$lin[EnderecoCompleto] .= ", nº".$lin[Numero];
		}		
		
		$lin[EnderecoCompleto] .= ", ".$lin[Bairro];

		if($lin[Complemento] != ""){
			$lin[EnderecoCompleto] .= ", ".$lin[Complemento];	
		}
		
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

				$Instrucao = "10"; // Pedido sustação de protesto
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

				$Instrucao = "06"; // Alteração de vencimento
				break;
			default:
				$local_transaction[$tr_i] = false;
				$tr_i++;
				break;
		}			

		# Registro detalhe - cobrança com registro
		
		// Campo  1 (Identificação do Registro detalhe)				[1]   1-1		N
		$Campo[1] = "7";
		$Campo[1] = preenche_tam($Campo[1], 1, '');

		// Campo  2 (Tipo de Inscrição do Cedente)					[2]   2-3		X
		if($DadosEmpresa[TipoPessoa] == 1){
			$Campo[2] = "02";
		}else{
			$Campo[2] = "01";
		}
		$Campo[2] = preenche_tam($Campo[2], 2, 'X');

		// Campo  3 (Número do CPF/CNPJ do Cedente)					[14]  4-17		X
		$Campo[3] = removeMascaraCPF_CNPJ($DadosEmpresa[CPF_CNPJ]);
		$Campo[3] = preenche_tam($Campo[3], 14, 'X');

		// Campo  4 (Prefixo da Agência)							[4]   18-21		N
		$Campo[4] = $ParametroLocalCobrancaCR[Agencia];		
		$Campo[4] = preenche_tam($Campo[4], 4, '');
		
		// Campo  5 (Dígito Verificador da Agência)					[1]   22-22		X
		$Campo[5] = preenche_tam($ParametroLocalCobrancaCR[DigitoAgencia], 1, 'X');
				
		// Campo  6 (Número da Conta Corrente do Cedente)			[8]   23-30		N
		$Campo[6] = $ParametroLocalCobrancaCR[Conta];
		$Campo[6] = preenche_tam($Campo[6], 8, '');
			
		// Campo  7 (Dígito Verificador Conta Corrente do Cedente)	[1]  31-31		X
		$Campo[7] = $ParametroLocalCobrancaCR[DigitoConta];
		$Campo[7] = preenche_tam($Campo[7], 1, 'X');
		
		// Campo  8 (Número do Convênio de Cobrança do Cedente)		[7] 32-38		N
		$Campo[8] =  $ParametroLocalCobrancaCR[Convenio];
		$Campo[8] = preenche_tam($Campo[8], 7, '');
						
		// Campo  9 (Código de Controle da Empresa)					[25] 39-63		X
		$Campo[9] = $lin[NumeroDocumento];		
		$Campo[9] = preenche_tam($Campo[9], 25, 'X');
		
		// Campo  10 (Nosso-Número)									[17] 64-80		N		
		$Campo[10] = preenche_tam($ParametroLocalCobrancaCR[Convenio], 7, '').preenche_tam($lin[NumeroDocumento], 10, '');
				
		// Campo  11 (Número da Prestação)							[2]  81-82	    N
		$Campo[11] = preenche_tam("00", 2, '');
		
		// Campo  12 (Grupo de Valor)								[2] 83-84		N  
		$Campo[12] = preenche_tam("00", 2, '');

		// Campo  13 (Complemento do Registro)						[3] 85-87		X  
		$Campo[13] = preenche_tam(" ", 3, 'X');
		
		// Campo  14 (Indicativo de Mensagem ou Sacador/Avalista)	[1] 88-88		X
		$Campo[14] = "";
		$Campo[14] = preenche_tam($Campo[14], 1, 'X');
		
		// Campo  15 (Prefixo do Título)							[3] 89-91	    X
		$Campo[15] = preenche_tam(" ", 3, 'X');
		
		// Campo  16 (Variação da Carteira)							[3] 92-94		N
		$Campo[16] = $ParametroLocalCobrancaCR[CarteiraVariacao];
		$Campo[16] = preenche_tam($Campo[16], 3, '');

		// Campo  17 (Conta Caução)									[1] 95-95		N  
		$Campo[17] = "0";
		$Campo[17] = preenche_tam($Campo[17], 1, '');		
		
		// Campo  18 (Número do Borderô)							[6] 96-101		N  
		$Campo[18] = "000000";
		$Campo[18] = preenche_tam($Campo[18], 6, '');
		
		// Campo  19 (Tipo de Cobrança)								[5] 102-106	    X
		$Campo[19] = preenche_tam(" ", 5, 'X');
		
		// Campo  20 (Carteira de Cobrança)							[2] 107-108		N
		$Campo[20] = $ParametroLocalCobrancaCR[Carteira];
		$Campo[20] = preenche_tam($Campo[20], 2, '');

		// Campo  21 (Comando)										[2] 109-110		N
		$Campo[21] = $Instrucao;
		$Campo[21] = preenche_tam($Campo[21], 2, '');

		// Campo  22 (Seu Número/Número do Título)					[10] 111-120	X
		$Campo[22] = $lin[NumeroDocumento];
		$Campo[22] = preenche_tam($Campo[22], 10, '');

		// Campo  23 (Data de vencimento)							[6] 121-126		N
		$Campo[23] = dataConv($lin[DataVencimento],'Y-m-d','dmy');
		$Campo[23] = preenche_tam($Campo[23], 6, '');

		// Campo  24 (Valor do titulo)								[13] 127-139	N
		$Campo[24] = number_format($lin[ValorFinal], 2, '.', '');			
		$Campo[24] = str_replace(".","",$Campo[24]);
		$Campo[24] = preenche_tam($Campo[24], 13, '');
		
		// Campo  25 (Número do Banco)								[3] 140-142	    N
		$Campo[25] = preenche_tam("001", 3, '');

		// Campo  26 (Prefixo da Agência Cobradora)					[4] 143-146	    N
		$Campo[26] = preenche_tam("0000", 4, '');
		
		// Campo  27 (Dígito Verificador da Agência Cobradora)		[1] 147-147	    X
		$Campo[27] = preenche_tam(" ", 1, 'X');

		// Campo  28 (Espécie de Titulo)							[2] 148-149	    N
		if($ParametroLocalCobrancaCR[EspecieDocumento] == "DM"){
			$Campo[28] = "01";
		}
		$Campo[28] = preenche_tam($Campo[28], 2, '');
		
		// Campo  29 (Aceite do titulo)								[1] 150-150		X
		if($ParametroLocalCobrancaCR[Aceite] == 'S'){
			$Campo[29] = 'A';
		}else{		
			$Campo[29] = 'N';
		}
		$Campo[29] = preenche_tam($Campo[29], 1, 'X');
		
		// Campo  30 (Data de emissão)								[6] 151-156		N
		$Campo[30] = dataConv($lin[DataLancamento],'Y-m-d','DDMMAA');
		$Campo[30] = preenche_tam($Campo[30], 6, '');
				
		// Campo  31 (Instrução Codificada)							[2] 157-158		N
		$Campo[31] = "00";
		$Campo[31] = preenche_tam($Campo[31], 2, '');

		// Campo  32 (Instrução Codificada)							[2] 159-160		N
		$Campo[32] = "00";
		$Campo[32] = preenche_tam($Campo[32], 2, '');

		// Campo  33 (Valor/% de juros por dia de atrazo)			[13] 161-173	N
		$Campo[33] = $lin[ValorFinal]*$linLocalCobrancaCR[PercentualJurosDiarios]/100;			
		$Campo[33] = number_format($Campo[33], 2, '.', '');		
		$Campo[33] = str_replace(".","",$Campo[33]);
		$Campo[33] = preenche_tam($Campo[33], 13, '');

		// Campo  34 (Data limite p/ concessão de desconto)			[6] 174-179		N
		$Campo[34] = dataConv($DataDesconto,'Y-m-d','dmy');
		$Campo[34] = preenche_tam($Campo[34], 6, '');		

		// Campo  35 (Valor/% do desconto)							[13] 180-192	N
		$Campo[35] = number_format($lin2[ValorDescontoAConceber], 2, '.', '');
		$Campo[35] = str_replace(".","",$Campo[35]);
		$Campo[35] = preenche_tam($Campo[35], 13, '');
		
		// Campo  36 (Valor do IOF/Qtde Unidade Variável)			[13] 193-205	N
		$Campo[36] = preenche_tam("0", 13, '');

		// Campo  37 (Valor do Abatimento)							[13] 206-218	N
		$Campo[37] = preenche_tam("0", 13, '');
				
		// Campo  38 (Tipo de pessoa do sacado: PF ou PJ)			[2] 219-220		N
		if($lin[TipoPessoa] == 1){
			// Jurídica
			$Campo[38] = 2;	
		}else{
			// Física
			$Campo[38] = 1;
		}
		$Campo[38] = preenche_tam($Campo[38], 2, '');

		// Campo  39 (CIC/CGC do sacado)							[14] 221-234	N
		$Campo[39] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[39] = preenche_tam($Campo[39], 14, '');

		// Campo  40 (Nome do sacado)								[37] 235-271	X
		if($lin[TipoPessoa] == 1){
			$Campo[40] = $lin[RazaoSocial];
		}else{
			$Campo[40] = $lin[Nome];
		}		
		$Campo[40] = preenche_tam(removeCaracters($Campo[40]), 37, 'X');
		
		// Campo  41 (Complemento do Registro)						[3] 272-274	    X
		$Campo[41] = preenche_tam(" ", 3, 'X');
		
		// Campo  42 (Endereço do sacado)							[40] 275-314	X
		$Campo[42] = removeCaracters($lin[Endereco]).", nº".$lin[Numero];
		$Campo[42] = preenche_tam($Campo[42], 40, 'X');
		
		// Campo  43 (Bairro do Sacado)								[40] 315-326	X
		$Campo[43] = removeCaracters($lin[Bairro]);
		$Campo[43] = preenche_tam($Campo[43], 12, 'X');	

		// Campo  44 (CEP do Endereço do Sacado)					[8]  315-326	X
		$Campo[44] = str_replace("-","",$lin[CEP]);
		$Campo[44] = preenche_tam($Campo[44], 8, 'X');	

		// Campo  45 (Cidade do Sacado)								[15]  335-349	X
		$Campo[45] = $lin[NomeCidade];
		$Campo[45] = preenche_tam($Campo[45], 15, 'X');	

		// Campo  46 (UF da Cidade do Sacado)						[2]  350-351	X
		$Campo[46] = $lin[SiglaEstado];
		$Campo[46] = preenche_tam($Campo[46], 2, 'X');	
		
		// Campo  47 (Observações/Mensagem ou Sacador/Avalista)		[40] 352-391	X
		$Campo[47] = " ";
		$Campo[47] = preenche_tam($Campo[47], 40, 'X');
				
		// Campo  48 (Número de Dias Para Protesto)					[2] 392-393		X
		$Campo[48] = $ParametroLocalCobrancaCR[QuantidadeDiasProtesto];
		$Campo[48] = preenche_tam($Campo[48], 2, 'X');		
				
		// Campo  49 (Complemento do Registro)						[1] 394-394		X 
		$Campo[49] = preenche_tam(" ", 1, 'X');			
		
		// Campo  52 (Numero sequencial do registro)				[6] 395-400		N 
		$NumSeqRegistro +=1;
		$Campo[50] = $NumSeqRegistro;
		$Campo[50] = preenche_tam($Campo[50], 6, '');

		// Salva
		$Linha[$i] = concatVar($Campo);
		
		$i++;
		
		$Campo = null;	
	}	
	
	### Registro Trailer
	
	// Campo  1 (Identificação do registro trailer)					[1]  1-1		N
	$Campo[1] = preenche_tam(9, 1, '');;
		
	// Campo  2 (Identificação do arquivo remessa)					[393]  2-394	X
	$Campo[2] = preenche_tam(" ", 393, 'X');
	
	// Campo  3 (Numero sequencial do registro)						[6]  395-400	N
	$NumSeqRegistro +=1;
	$Campo[3] = $NumSeqRegistro;
	$Campo[3] = preenche_tam($Campo[3], 6, '');	

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;		
?>