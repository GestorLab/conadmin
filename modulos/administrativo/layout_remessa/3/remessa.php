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

	// Campo  1 (Identificação do Registro Header)				[1]   1-1		N
	$Campo[1] = "0";
	$Campo[1] = preenche_tam($Campo[1], 1, '');

	// Campo  2 (Identificação do Arquivo Remessa)				[1]   2-2		N
	$Campo[2] = "1";
	$Campo[2] = preenche_tam($Campo[2], 1, '');

	// Campo  3 (Literal Remessa)								[7]   3-9		X
	$Campo[3] = "REMESSA";
	$Campo[3] = preenche_tam($Campo[3], 7, 'X');

	// Campo  4 (Código do Serviço de Cobrança)					[2]   10-11		N
	$Campo[4] = "01";
	$Campo[4] = preenche_tam($Campo[4], 2, '');

	// Campo  5 (Literal cobrança)								[15]  12-26		X
	$Campo[5] = "COBRANCA";
	$Campo[5] = preenche_tam($Campo[5], 15, 'X');

	// Campo  6 (Código do cedente)								[5]   27-31		N 
	$Campo[6] = $ParametroLocalCobranca['CodigoCedente'];
	$Campo[6] = preenche_tam($Campo[6], 5, '');

	// Campo  7 (CIC/CGC do cedente)							[14]  32-45		N
	$Campo[7] = removeMascaraCPF_CNPJ($DadosEmpresa[CPF_CNPJ]);
	$Campo[7] = preenche_tam($Campo[7], 14, '');
	
	// Campo  8 (Filler)										[31]  46-76		X
	$Campo[8] = preenche_tam(" ", 31, 'X');

	// Campo  9 (Número do SICREDI)								[3]   77-79     N
	$Campo[9] = "748";
	$Campo[9] = preenche_tam($Campo[9], 3, '');

	// Campo 10 (Literal SICREDI)								[15]  80-94		X
	$Campo[10] = "SICREDI";
	$Campo[10] = preenche_tam($Campo[10], 15, 'X');
	
	// Campo 11 (Data de gravação do arquivo)					[8]	  95-102    N
	$Campo[11] = Date("Ymd");
	$Campo[11] = preenche_tam($Campo[11], 8, '');
	
	// Campo 12 (Filler)										[8]	  101-108   X
	$Campo[12] = preenche_tam(" ", 8, 'X');

	// Campo 13 (Número da remessa)								[7]	  111-117   N
	$Campo[13] = $NumSeqArquivo;
	$Campo[13] = preenche_tam($Campo[13], 7, '');

	// Campo 14 (Filler)										[273] 118-390   X
	$Campo[14] = preenche_tam(" ", 273, 'X');

	// Campo 15 (Versão do sistema (o ponto deve ser colocado))	[4]	  391-394	X
	$Campo[15] = "2.00";
	$Campo[15] = preenche_tam($Campo[15], 4, 'X');
	
	// Campo 16 (Número sequencial do registro)					[6]	  395-400	N
	$NumSeqRegistro = 0;
	$NumSeqRegistro++;
	$Campo[16] = $NumSeqRegistro;
	$Campo[16] = preenche_tam($Campo[16], 6, '');

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

				$Instrucao = "19"; // Pedido sustação de protesto
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

		if($Instrucao != 31){
			$TipoInstrucao = " ";
		}

		# Registro detalhe - cobrança com registro
		
		// Campo  1 (Identificação do Registro detalhe)				[1]   1-1		N
		$Campo[1] = "1";
		$Campo[1] = preenche_tam($Campo[1], 1, '');

		// Campo  2 (Tipo de cobrança)								[1]   2-2		X
		$Campo[2] = "A";
		$Campo[2] = preenche_tam($Campo[2], 1, 'X');

		// Campo  3 (Tipo de carteira)								[1]   3-3		X
		$Campo[3] = $ParametroLocalCobranca[Carteira];				# A
		$Campo[3] = preenche_tam($Campo[3], 1, 'X');

		// Campo  4 (Tipo de Impressão)								[1]   4-4		X			
		$Campo[4] = "A";		
		$Campo[4] = preenche_tam($Campo[4], 1, 'X');
	
		// Campo  5 (Filler)										[12]  5-16		X
		$Campo[5] = preenche_tam(" ", 12, 'X');

		// Campo  6 (Tipo da moeda)									[1]  17-17		X
		$Campo[6] = "A";
		$Campo[6] = preenche_tam($Campo[6], 1, 'X');

		// Campo  7 (Tipo de desconto)								[1] 18-18		X
		$Campo[7] = "A";
		$Campo[7] = preenche_tam($Campo[7], 1, 'X');
		
		// Campo  8 (Tipo de juros)									[1] 19-19		X
		$Campo[8] = "A";
		$Campo[8] = preenche_tam($Campo[8], 1, 'X');
		
		// Campo  9 (Filler)										[28] 20-47	    X
		$Campo[9] = preenche_tam(" ", 28, 'X');
		
		// Campo  10 (Nosso número SICREDI sem edição)				[9]  48-56		N
		$lin[NumeroDocumento] = preenche_tam($lin[NumeroDocumento], 5, '');				
		$Campo[10] = $ParametroLocalCobranca[Agencia].$ParametroLocalCobranca[Posto].$ParametroLocalCobranca[CodigoCedente].$lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento];		
		$Campo[10] = mod11($Campo[10]);
		$Campo[10] = $lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento].$Campo[10];
		$Campo[10] = preenche_tam($Campo[10], 9, '');		
				
		// Campo  11 (Filler)										[6]  57-62	    X
		$Campo[11] = preenche_tam(" ", 6, 'X');
		
		// Campo  12 (Data da Instrução)							[8] 63-70		N  
		$Campo[12] = date("Ymd");
		$Campo[12] = preenche_tam($Campo[12], 8, '');

		// Campo  13 (Campo alterado, quando instrução 31)			[1] 71-71		X  
		$Campo[13] = $TipoInstrucao;
		$Campo[13] = preenche_tam($Campo[13], 1, 'X');
		
		// Campo  14 (Postagem do Título)							[1] 72-72		X  
		if($ParametroLocalCobrancaCR[LocalImpressao] == 'A'){
			$Campo[14] = "S";
		}else{
			$Campo[14] = "N";
		}		
		$Campo[14] = preenche_tam($Campo[14], 1, 'X');
		
		// Campo  15 (Filler)										[1] 73-73	    X
		$Campo[15] = preenche_tam(" ", 1, 'X');
		
		// Campo  16 (Emissão do bloqueto)							[1] 74-74		X  
		$Campo[16] = $ParametroLocalCobrancaCR[LocalImpressao];
		$Campo[16] = preenche_tam($Campo[16], 1, 'X');

		// Campo  17 (Número da parcela do carnê)					[2] 75-76		N  
		$Campo[17] = "0";
		$Campo[17] = preenche_tam($Campo[17], 2, '');		
		
		// Campo  18 (Número total de parcelas do carnê)			[2] 77-78		N  
		$Campo[18] = "0";
		$Campo[18] = preenche_tam($Campo[18], 2, '');
		
		// Campo  19 (Filler)										[4]  79-82	    X
		$Campo[19] = preenche_tam(" ", 4, 'X');
		
		// Campo  20 (Valor de desconto por dia de antecipação)		[10] 83-92		N  
		$Campo[20] = "0";
		$Campo[20] = preenche_tam($Campo[20], 10, '');
		
		// Campo  21 (% multa por pagamento em atraso)				[4] 93-96		N  
		$Campo[21] = number_format($linLocalCobrancaCR[PercentualMulta], 2, '.', '');
		$Campo[21] = str_replace(".","",$Campo[21]);
		$Campo[21] = preenche_tam($Campo[21], 4, '');

		// Campo  22 (Filler)										[12] 97-108	    X
		$Campo[22] = preenche_tam(" ", 12, 'X');
		
		// Campo  23 (Instrução)									[2] 109-110		N  
		$Campo[23] = $Instrucao;
		$Campo[23] = preenche_tam($Campo[23], 2, '');
		
		// Campo  24 (Seu número (nunca se repete))					[10] 111-120	N  
		$lin[NumeroDocumento]	= preenche_tam($lin[NumeroDocumento], 5, '');		
		$Campo[24] = $ParametroLocalCobranca[Agencia].$ParametroLocalCobranca[Posto].$ParametroLocalCobranca[CodigoCedente].$lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento];		
		$Campo[24] = mod11($Campo[24]);
		$Campo[24] = $lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento].$Campo[24];	
		$Campo[24] = preenche_tam($Campo[24], 10, '');
		
		// Campo  25 (Data de vencimento)							[6] 121-126		N  
		$Campo[25] = dataConv($lin[DataVencimento],'Y-m-d','dmy');
		$Campo[25] = preenche_tam($Campo[25], 6, '');

		// Campo  26 (Valor do titulo)								[13] 127-139	N  
		$Campo[26] = number_format($lin[ValorFinal], 2, '.', '');			
		$Campo[26] = str_replace(".","",$Campo[26]);
		$Campo[26] = preenche_tam($Campo[26], 13, '');

		// Campo  27 (Filler)										[9] 140-148	    X
		$Campo[27] = preenche_tam(" ", 9, 'X');
		
		// Campo  28 (Espécie de documento)							[1] 149-149		X  
		$Campo[28] = "J";
		$Campo[28] = preenche_tam($Campo[28], 1, 'X');
		
		// Campo  29 (Aceite do titulo)								[1] 150-150		X  
		$Campo[29] = $ParametroLocalCobranca[Aceite];
		$Campo[29] = preenche_tam($Campo[29], 1, 'X');
		
		// Campo  30 (Data de emissão)								[6] 151-156		N  
		$Campo[30] = dataConv($lin[DataLancamento],'Y-m-d','dmy');
		$Campo[30] = preenche_tam($Campo[30], 6, '');

		// Campo  31 (Instrução de protesto automático)				[2] 157-158		N  		
		if($ParametroLocalCobrancaCR[QuantidadeDiasProtesto] > 0){
			$Campo[31] = "06";
		}else{
			$Campo[31]										  = "00";
			$ParametroLocalCobrancaCR[QuantidadeDiasProtesto] = "00";
		}
		$Campo[31] = preenche_tam($Campo[31], 2, '');
		
		// Campo  32 (Numero de dias p/ protesteo automatico)		[2] 159-160		N
		$Campo[32] = $ParametroLocalCobrancaCR[QuantidadeDiasProtesto];
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
	
		// Campo  36 (Filler)										[13] 193-205	N
		$Campo[36] = preenche_tam("0", 13, '');
		
		// Campo  37 (Valor do abatimento)							[13] 206-218	N  	
		$Campo[37] = "0";
		$Campo[37] = preenche_tam($Campo[37], 13, '');

		// Campo  38 (Tipo de pessoa do sacado: PF ou PJ)			[1] 219-219		N  
		if($lin[TipoPessoa] == 1){
			// Jurídica
			$Campo[38] = 2;	
		}else{
			// Física
			$Campo[38] = 1;
		}
		$Campo[38] = preenche_tam($Campo[38], 1, '');
		
		// Campo  39 (Filler)										[1]  220-220	N
		$Campo[39] = preenche_tam("0", 1, '');
		
		// Campo  40 (CIC/CGC do sacado)							[14] 221-234	N
		$Campo[40] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[40] = preenche_tam($Campo[40], 14, '');

		// Campo  41 (Nome do sacado)								[40] 235-274	X
		if($lin[TipoPessoa] == 1){
			$Campo[41] = $lin[RazaoSocial];
		}else{
			$Campo[41] = $lin[Nome];
		}		

		$Campo[41] = preenche_tam(removeCaracters($Campo[41]), 40, 'X');
		
		// Campo  42 (Endereço do sacado)							[40] 275-314	X
		$Campo[42] = removeCaracters($lin[EnderecoCompleto]);
		$Campo[42] = preenche_tam($Campo[42], 40, 'X');
		
		// Campo  43 (Codigo do sacado na cooperativa cedente)		[5] 315-319		X 
		$Campo[43] = "0";											// Esta informção é repassada pelo banco apos o cadastro do cliente 
		$Campo[43] = preenche_tam($Campo[43], 5, '');
	
		// Campo  44 (Código da praça do sacado)					[6] 320-325		N 
		$Campo[44] = "0";											// Esta informção é repassada pelo banco apos o cadastro do cliente 
		$Campo[44] = preenche_tam($Campo[44], 6, '');
		
		// Campo  45 (Filler)										[1] 326-326		X
		$Campo[45] = preenche_tam(" ", 1, 'X');
		
		// Campo  46 (CEP do sacado)								[8] 327-334		N
		$Campo[46] = str_replace("-","",$lin[CEP]);
		$Campo[46] = preenche_tam($Campo[46], 8, '');	
		
		// Campo  47 (Codigo do Sacado junto ao cliente)			[5] 335-339		N 
		$Campo[47] = "0";											// Esta informção é repassada pelo banco apos o cadastro do cliente 
		$Campo[47] = preenche_tam($Campo[47], 5, '');		
		
		// Campo  48 (CIC/CGC do sacador avalista)					[14] 340-353	X 
		$Campo[48] = " ";
		$Campo[48] = preenche_tam($Campo[48], 14, 'X');	
		
		// Campo  49 (Nome do sacador avalista)						[41] 354-394	X 
		$Campo[49] = " ";
		$Campo[49] = preenche_tam($Campo[49], 41, 'X');			
		
		// Campo  52 (Numero sequencial do registro)				[6] 395-400		N 
		$NumSeqRegistro +=1;
		$Campo[50] = $NumSeqRegistro;
		$Campo[50] = preenche_tam($Campo[50], 6, '');

		// Salva
		$Linha[$i] = concatVar($Campo);
		
		$i++;
		
		$Campo = null;
		
		
		# Registro Mensagem
		
		// Campo  1 (Identificação do Registro detalhe)				[1]   1-1		N
		$Campo[1] = "2";
		$Campo[1] = preenche_tam($Campo[1], 1, '');
		
		// Campo  2 (Filler)										[12]  2-12		X
		$Campo[2] = preenche_tam(" ", 11, 'X');

		// Campo  3 (Nosso número)									[9]   13-21		N  
		$lin[NumeroDocumento]	= preenche_tam($lin[NumeroDocumento], 5, '');		
		$Campo[3] = $ParametroLocalCobranca[Agencia].$ParametroLocalCobranca[Posto].$ParametroLocalCobranca[CodigoCedente].$lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento];		
		$Campo[3] = mod11($Campo[3]);
		$Campo[3] = $lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento].$Campo[3];
		$Campo[3] = preenche_tam($Campo[3], 9, '');				

		// Campo  4 (1ª Instrução para impressão no bloqueto)		[80]   22-101	X
		$Campo[4] = removeCaracters($ParametroLocalCobrancaCR['Instrucoes1']);
		$Campo[4] = preenche_tam($Campo[4], 80, 'X');
		
		// Campo  5 (2ª Instrução para impressão no bloqueto)		[80]   102-181	X
		$Campo[5] = removeCaracters($ParametroLocalCobrancaCR['Instrucoes2']);
		$Campo[5] = preenche_tam($Campo[5], 80, 'X');
		
		// Campo  6 (3ª Instrução para impressão no bloqueto)		[80]   182-261	X
		$Campo[6] = removeCaracters($ParametroLocalCobrancaCR['Instrucoes3']);
		$Campo[6] = preenche_tam($Campo[6], 80, 'X');
		
		// Campo  7 (4ª Instrução para impressão no bloqueto)		[80]   262-341	X
		$Campo[7] = removeCaracters($ParametroLocalCobrancaCR['Instrucoes4']);
		$Campo[7] = preenche_tam($Campo[7], 80, 'X');
		
		// Campo  8 (Seu Número (nunca se repete))					[10]  342-351	N
		$lin[NumeroDocumento]	= preenche_tam($lin[NumeroDocumento], 5, '');		
		$Campo[8] = $ParametroLocalCobranca[Agencia].$ParametroLocalCobranca[Posto].$ParametroLocalCobranca[CodigoCedente].$lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento];		
		$Campo[8] = mod11($Campo[8]);
		$Campo[8] = $lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento].$Campo[8];
		$Campo[8] = preenche_tam($Campo[8], 10, '');		

		// Campo  9 (Filler)										[43]  352-394	X
		$Campo[9] = preenche_tam(" ", 43, 'X');

		// Campo  10 (Número sequencial do registro)				[6]  395-400	N
		$NumSeqRegistro +=1;
		$Campo[10] = $NumSeqRegistro;
		$Campo[10] = preenche_tam($Campo[10], 6, '');
		
		// Salva
		$Linha[$i] = concatVar($Campo);

		$i++;
		
		$Campo = null;
		
		
		### Registro informativo
		
		// Campo  1 (Identificação do Registro Informativo)			[1]   1-1		N
		$Campo[1] = "5";
		$Campo[1] = preenche_tam($Campo[1], 1, '');
		
		// Campo  2 (Tipo de Informativo)							[1]  2-2		X
		$Campo[2] = "E";
		$Campo[2] = preenche_tam($Campo[2], 1, 'X');
		
		// Campo  3 (Código do Cedente)								[5]  3-7		N
		$Campo[3] = $ParametroLocalCobranca['CodigoCedente'];
		$Campo[3] = preenche_tam($Campo[3], 5, '');

		// Campo  4 (Identificação do título seu número)			[10]  8-17		N
		$lin[NumeroDocumento]	= preenche_tam($lin[NumeroDocumento], 5, '');		
		$Campo[4] = $ParametroLocalCobranca[Agencia].$ParametroLocalCobranca[Posto].$ParametroLocalCobranca[CodigoCedente].$lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento];		
		$Campo[4] = mod11($Campo[4]);
		$Campo[4] = $lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento].$Campo[4];
		$Campo[4] = preenche_tam($Campo[4], 10, '');		
		
		// Campo  5 (Filler)										[1]  18-18		X
		$Campo[5] = preenche_tam(" ", 1, 'X');

		// Campo  6 (Tipo de cobrança)								[1]  19-19		X  
		$Campo[6] = "A";
		$Campo[6] = preenche_tam($Campo[6], 1, 'X');
		
		// Campo  7 (Numero da linha do informativo)				[2]  20-21		N  
		$Campo[7] = "1";
		$Campo[7] = preenche_tam($Campo[7], 2, '');
		
		// Campo  8 (Texto da linha do informativo)					[80]  22-101	X  
		$Campo[8] = removeCaracters($ParametroLocalCobrancaCR['Instrucoes1']);
		$Campo[8] = preenche_tam($Campo[8], 80, 'X');
		
		// Campo  9 (Numero da linha do informativo)				[2]  102-103	N  
		$Campo[9] = "2";
		$Campo[9] = preenche_tam($Campo[9], 2, '');
		
		// Campo  10 (Texto da linha do informativo)				[80]  104-183	X  
		$Campo[10] = removeCaracters($ParametroLocalCobrancaCR['Instrucoes2']);
		$Campo[10] = preenche_tam($Campo[10], 80, 'X'); 
		
		// Campo  11 (Numero da linha do informativo)				[2]  184-185	N  
		$Campo[11] = "3";
		$Campo[11] = preenche_tam($Campo[11], 2, '');
		
		// Campo  12 (Texto da linha do informativo)				[80]  186-265	X  
		$Campo[12] = removeCaracters($ParametroLocalCobrancaCR['Instrucoes3']);
		$Campo[12] = preenche_tam($Campo[12], 80, 'X'); 
		
		// Campo  13 (Numero da linha do informativo)				[2]  266-267	N  
		$Campo[13] = "4";
		$Campo[13] = preenche_tam($Campo[13], 2, '');
		
		// Campo  14 (Texto da linha do informativo)				[80]  268-347	X  
		$Campo[14] = removeCaracters($ParametroLocalCobrancaCR['Instrucoes4']);
		$Campo[14] = preenche_tam($Campo[14], 80, 'X'); 

		// Campo  15 (Filler)										[47]  348-394	X
		$Campo[15] = preenche_tam(" ", 47, 'X');
		
		// Campo  16 (Numero sequencial do registro)				[6]  395-400	N
		$NumSeqRegistro +=1;
		$Campo[16] = $NumSeqRegistro;
		$Campo[16] = preenche_tam($Campo[16], 6, '');
		
		// Salva
		$Linha[$i] = concatVar($Campo);
		$i++;
		
		$Campo = null;
		
		### Registro sacador avalista
		
		// Campo  1 (Identificação do Registro Detalhe)				[1]  1-1		N
		/*$Campo[1] = "6";
		$Campo[1] = preenche_tam($Campo[1], 1, '');
		
		// Campo  2 (Nosso número SICREDI sem edição)				[15]  2-16		N 
		$lin[NumeroDocumento]	= preenche_tam($lin[NumeroDocumento], 5, '');		
		$Campo[2] = $ParametroLocalCobranca[Agencia].$ParametroLocalCobranca[Posto].$ParametroLocalCobranca[CodigoCedente].$lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento];		
		$Campo[2] = mod11($Campo[2]);
		$Campo[2] = $lin[AnoLancamento].$ParametroLocalCobranca[ByteIDT].$lin[NumeroDocumento].$Campo[2];
		$Campo[2] = preenche_tam($Campo[2], 15, '');
		
		// Campo  3 (Seu numero (nunca se repete))					[10]  17-26		N
		$Campo[3] = preenche_tam($lin[NumeroDocumento], 10, '');

		// Campo  4 (Codigo do sacado junto ao cliente)				[5]  27-31		N 
		$Campo[4] = $lin[IdPessoa];
		$Campo[4] = preenche_tam($Campo[4], 5, '');
		
		// Campo  5 (CIC/CGC do sacador avalista)					[14] 32-45		N
		$Campo[5] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[5] = preenche_tam($Campo[5], 14, '');		
				
		// Campo  6 (Nome do sacador avalista)						[41] 46-86		X
		if($lin[TipoPessoa] == 1){
			$Campo[6] = $lin[RazaoSocial];
		}else{
			$Campo[6] = $lin[Nome];
		}		
		$Campo[6] = preenche_tam(removeCaracters($Campo[6]), 41, 'X');
		
		// Campo  7 (Endereco)										[45] 87-131		X		
		if($lin[Numero] != ""){
			$lin[Endereco] .= ", nº".$lin[Numero];	
		}
		$Campo[7] = removeCaracters($lin[Endereco]);
		$Campo[7] = preenche_tam($Campo[7], 45, 'X');
		
		// Campo  8 (Cidade)										[20] 132-159	X				
		$Campo[8] = removeCaracters($lin[NomeCidade]);
		$Campo[8] = preenche_tam($Campo[8], 20, 'X');
		
		// Campo  9 (CEP)											[8] 152-159		X				
		$Campo[9] = str_replace("-","",$lin[CEP]);
		$Campo[9] = preenche_tam($Campo[9], 8, 'X');
					
		// Campo  10 (Estado)										[2] 160-161		X				
		$Campo[10] = $lin[SiglaEstado];
		$Campo[10] = preenche_tam($Campo[10], 2, 'X');			
		
		// Campo  11 (Filer)										[233] 160-161	X				
		$Campo[11] = preenche_tam(" ", 233, 'X');
							
		// Campo  12 (Numero sequencial do registro)				[6]  395-400	N
		$Campo[12] = $NumSeqRegistro;
		$Campo[12] = preenche_tam($Campo[12], 6, '');	
				
		// Salva
		$Linha[$i] = concatVar($Campo);
		$i++;
		
		$Campo = null;*/				
	}	
	
	### Registro Trailer
	
	// Campo  1 (Identificação do registro trailer)					[9]  1-1		N
	$Campo[1] = "9";
		
	// Campo  2 (Identificação do arquivo remessa)					[1]  2-2		N
	$Campo[2] = "1";
	
	// Campo  3 (Número do SICREDI)									[3]  3-5		N
	$Campo[3] = "748";
	
	// Campo  4 (Código do cedente)									[5]  6-10		N
	$Campo[4] = $ParametroLocalCobranca['CodigoCedente'];
	$Campo[4] = preenche_tam($Campo[4], 5, '');

	// Campo  5 (Reservado (uso Banco)								[384] 11-394	X
	$Campo[5] = preenche_tam(" ", 384, 'X');
	
	// Campo  6 (Numero sequencial do registro)						[6]  395-400	N
	$NumSeqRegistro +=1;
	$Campo[6] = $NumSeqRegistro;
	$Campo[6] = preenche_tam($Campo[6], 6, '');	

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;		
?>
