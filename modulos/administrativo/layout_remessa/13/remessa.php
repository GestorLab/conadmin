<?
	include("funcao_layout.php");
	// Gera a nomenclatura do arquivo
	$Patch = "remessa/local_cobranca/$local_IdLoja/$local_IdLocalCobranca/$local_IdArquivoRemessa";	

	$NomeArquivo = $ParametroLocalCobranca['Conta'].codigoMesDia().".CRM";
	
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
	$Campo[5] = "COBRANÇA";
	$Campo[5] = preenche_tam($Campo[5], 8, 'X');
	
	// Campo  6 (Complemento do Registro: Brancos)				[5]   20-26		X 
	$Campo[6] = preenche_tam(" ", 7, 'X');

	// Campo  7 (Prefixo da Cooperativa)						[4]   27-30		N
	$Campo[7] = $ParametroLocalCobranca['Agencia'];
	$Campo[7] = preenche_tam($Campo[7], 4, '');

	// Campo  8 (Dígito Verificador do Prefixo)					[1]   31-31		X
	$Campo[8] = $ParametroLocalCobranca['AgenciaDigito'];
	$Campo[8] = preenche_tam($Campo[8], 1, 'X');
		
	// Campo  9 (Código do Cliente/Cedente)						[8]   32-39		N
	$Campo[9] = $ParametroLocalCobranca['CodigoCliente'];
	$Campo[9] = preenche_tam($Campo[9], 8, '');

	// Campo  10(Dígito Verificador do Prefixo)					[1]   40-40		X
	$Campo[10] = $ParametroLocalCobranca['CodigoClienteDigito'];
	$Campo[10] = preenche_tam($Campo[10], 1, 'X');
	
	// Campo  11 (Complemento do Registro: Brancos)				[6]   41-46		X 
	$Campo[11] = preenche_tam(" ", 6, 'X');

	// Campo  12 (Nome do Cedente)								[30]  47-76		X
	if($DadosEmpresaLocalCobranca[TipoPessoa] == 2){
		$Campo[12] = $DadosEmpresaLocalCobranca[Nome];
	}else{
		$Campo[12] = $DadosEmpresa[RazaoSocial];
	}
	$Campo[12] = preenche_tam($Campo[12], 30, 'X');

	// Campo  13 (Identificação do Banco)						[18]  77-94     X
	$Campo[13] = "756BANCOOBCED";
	$Campo[13] = preenche_tam($Campo[13], 18, 'X');

	// Campo 14 (Data da Gravação da Remessa) ddmmaa			[6]	  95-100    N
	$Campo[14] = dataConv(date('Ymd'),'Ymd','DDMMAA');
	$Campo[14] = preenche_tam($Campo[14],6, '');

	// Campo 15 (Número da remessa)								[7]	  101-107   N
	$Campo[15] = $NumSeqArquivo;
	$Campo[15] = preenche_tam($Campo[15], 7, '');

	// Campo 16 (Complemento do Registro: Brancos)				[287] 108-394   X
	$Campo[16] = preenche_tam(" ", 287, 'X');

	// Campo 17 (Número sequencial do registro)					[6]	  395-400	N
	$NumSeqRegistro = 0;
	$NumSeqRegistro++;
	$Campo[17] = $NumSeqRegistro;
	$Campo[17] = preenche_tam($Campo[17], 6, '');

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
		
		$lin[AnoLancamento] = substr($lin[DataLancamento],2,2);
			
		$lin[EnderecoCompleto] = $lin[Endereco];	
		if($lin[Numero] != ""){
			$lin[EnderecoCompleto] .= ", nº".$lin[Numero];
		}
		if($lin[Complemento] != ""){
			$lin[EnderecoCompleto] .= $lin[Complemento];	
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

				$Instrucao = "31"; // Alteração de vencimento
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

		// Campo  2 (Tipo de Inscrição do Cedente) 01-CPF | 02-CNPJ [2]   2-3		N 
		if($DadosEmpresa[TipoPessoa] == 1){
			$Campo[2] = 2;
		}else{
			$Campo[2] = 1;
		}
		$Campo[2] = preenche_tam($Campo[2], 2, '');

		// Campo  3 (Número do CPF/CNPJ do Cedente)					[14]  04-17		N
		$Campo[3] = removeMascaraCPF_CNPJ($DadosEmpresa[CPF_CNPJ]);
		$Campo[3] = preenche_tam($Campo[3], 14, '');

		// Campo  4 (Prefixo da Cooperativa)						[4]   18-21		N
		$Campo[4] = $ParametroLocalCobranca['Agencia'];		
		$Campo[4] = preenche_tam($Campo[4], 4, '');
	
		// Campo  5 (Dígito Verificador do Prefixo)					[1]  22-22		N
		$Campo[5] = $ParametroLocalCobranca['AgenciaDigito'];		
		$Campo[5] = preenche_tam($Campo[5], 1, '');

		// Campo  6 (Conta Corrente)								[8]  23-30		N
		$Campo[6] = $ParametroLocalCobranca['Conta'];
		$Campo[6] = preenche_tam($Campo[6], 8, '');

		// Campo  7 (Dígito Verificador da Conta)					[1]  31-31		X
		$Campo[7] = $ParametroLocalCobranca['ContaDigito'];
		$Campo[7] = preenche_tam($Campo[7], 1, 'X');
		
		// Campo  8 (Número do Convênio de Cobrança do Cedente)		[6] 32-37		N
		$Campo[8] = $ParametroLocalCobranca['Convenio'];
		$Campo[8] = preenche_tam($Campo[8], 6, '');
		
		// Campo  9 (Filler)										[25] 38-62	    X 
		$Campo[9] = preenche_tam(" ", 25, 'X');
		
		// Campo  10 (Nosso número SICREDI sem edição)				[12]  63-74		N
		$Campo[10] = $ParametroLocalCobranca['Agencia'].str_pad($ParametroLocalCobranca['CodigoCliente'], 9, "0", STR_PAD_LEFT).$ParametroLocalCobranca['CodigoClienteDigito'].str_pad($lin[NumeroDocumento], 7, "0", STR_PAD_LEFT);		
		$Campo[10] = $lin[NumeroDocumento].mod11($Campo[10]);
		$Campo[10] = preenche_tam($Campo[10], 12, '');						
		
		// Campo  11 (Número da Parcela)							[2]  75-76	    X
		$Campo[11] = "01";
		$Campo[11] = preenche_tam($Campo[11], 2, '');
		
		// Campo  12 (Grupo de Valor)								[2] 77-78		N  
		$Campo[12] = "00";
		$Campo[12] = preenche_tam($Campo[12], 2, '');

		// Campo  13 (Complemento do Registro: Brancos)				[3] 79-81		X  
		$Campo[13] = preenche_tam(" ", 3, 'X');
		
		// Campo  14 (Indicativo de Mensagem ou Sacador/Avalista)	[1] 82-82		X
		if($ParametroLocalCobranca['LocalImpressao'] == 1){
			$Campo[14] = "A";
		}else{
			$Campo[14] = " ";
		}		
		$Campo[14] = preenche_tam($Campo[14], 1, 'X');
		
		// Campo  15 (Complemento do Registro: Brancos)				[3] 83-85		X  
		$Campo[15] = preenche_tam(" ", 3, 'X');
		
		// Campo  16 (Variação da Carteira)							[3] 86-88		N  
		$Campo[16] = "000";
		$Campo[16] = preenche_tam($Campo[16], 3, '');

		// Campo  17 (Conta Caução)									[1] 89-89		N  
		$Campo[17] = "0";
		$Campo[17] = preenche_tam($Campo[17], 1, '');		
		
		// Campo  18 (Número do Contrato Garantia:)					[5] 90-94		N 
		$Campo[18] = "00000";
		$Campo[18] = preenche_tam($Campo[18], 5, '');
		
		// Campo  19 (DV do contrato)								[1] 95-95	    X 
		$Campo[19] = "0";
		$Campo[19] = preenche_tam($Campo[19], 1, 'X');
		
		// Campo  20 (Numero do borderô)							[6] 96-101		N
		$Campo[20] = "0";
		$Campo[20] = preenche_tam($Campo[20], 6, '');

		// Campo  21 (Filler)										[5] 102-106	    X
		$Campo[21] = preenche_tam(" ", 5, 'X');

		// Campo  22 (Carteira/Modalidade)							[2] 107-108		N  
		$Campo[22] = "01";
		$Campo[22] = preenche_tam($Campo[22], 2, '');
		
		// Campo  23 (Comando/Movimento:)							[2] 109-110		N  
		$Campo[23] = $Instrucao;
		$Campo[23] = preenche_tam($Campo[23], 2, '');

		// Campo  24 (Seu Número/Número atribuído pela Empresa)		[10] 111-120	X  
		$Campo[24] = $lin[NumeroDocumento];		
		$Campo[24] = preenche_tam($Campo[24], 10, 'X');
		
		// Campo  25 (Data de vencimento)							[6] 121-126		N
		$Campo[25] = dataConv($lin[DataVencimento],'Y-m-d','DDMMAA');
		$Campo[25] = preenche_tam($Campo[25], 6, '');

		// Campo  26 (Valor do titulo)								[13] 127-139	N
		$Campo[26] = number_format($lin[ValorFinal], 2, '.', '');			
		$Campo[26] = str_replace(".","",$Campo[26]);
		$Campo[26] = preenche_tam($Campo[26], 13, '');

		// Campo  27 (Número Banco)									[3] 140-142		N  
		$Campo[27] = 756;		
		$Campo[27] = preenche_tam($Campo[27], 3, '');

		// Campo  28 (Prefixo da Cooperativa)						[4] 143-146		N 
		$Campo[28] = $ParametroLocalCobranca['Agencia'];		
		$Campo[28] = preenche_tam($Campo[28], 4, '');

		// Campo  29 (Dígito Verificador do Prefixo)				[1] 147-147		X 
		$Campo[29] = $ParametroLocalCobranca['AgenciaDigito'];		
		$Campo[29] = preenche_tam($Campo[29], 1, 'X');

		// Campo  30 (Espécie do Título)							[2] 148-149		N
		switch($ParametroLocalCobranca['EspecieDocumento']){
			case 'DM':
				$Campo[30] = '01';
				break;
		}
		$Campo[30] = preenche_tam($Campo[30], 2, '');
		
		// Campo  31 (Aceite do titulo)								[1] 150-150		X  
		if($ParametroLocalCobranca[Aceite] == "N"){
			$Campo[31] = 0;	
		}else{
			$Campo[31] = 1;
		}		
		$Campo[31] = preenche_tam($Campo[31], 1, 'X');	

		// Campo  32 (Data de emissão)								[6] 151-156		N 
		$Campo[32] = dataConv($lin[DataLancamento],'Y-m-d','DDMMAA');
		$Campo[32] = preenche_tam($Campo[32], 6, '');

		// Campo  33 (Primeira instrução codificada)				[2] 157-158		N Obs: Quando o cliente quer imprimir as suas próprias instruções este campo dever ser preenchido com 01 
		$Campo[33] = "01";		
		$Campo[33] = preenche_tam($Campo[33], 2, '');

		// Campo  34 (Primeira instrução codificada)				[2] 159-160		N Obs: Quando o cliente quer imprimir as suas próprias instruções este campo dever ser preenchido com 01
		$Campo[34] = "01";		
		$Campo[34] = preenche_tam($Campo[34], 2, '');

		// Campo  35 (Taxa de mora mês)								[6] 161-166		N
		$Campo[35] = 30*$DadosLocalCobranca[PercentualJurosDiarios];			
		$Campo[35] = number_format($Campo[35], 4, '.', '');		
		$Campo[35] = str_replace(".","",$Campo[35]);
		$Campo[35] = preenche_tam($Campo[35], 6, '');
		
		// Campo  36 (Taxa de multa)								[6] 167-172		N  
		$Campo[36] = number_format($DadosLocalCobranca[PercentualMulta], 4, '.', '');
		$Campo[36] = str_replace(".","",$Campo[36]);
		$Campo[36] = preenche_tam($Campo[36], 6, '');

		// Campo  37 (Tipo Distribuição)							[1] 173-173	    X
		// 1 - Cooperativa | 2 - Cliente
		$Campo[37] = preenche_tam($ParametroLocalCobranca['LocalImpressao'], 1, 'X');
	
		// Campo  38 (Data primeiro desconto)						[6] 174-179		N  O Douglas falou se a data do desconto for maior que o vencimento é para informar a data do vencimento				
		if(dataConv($DataDesconto,'Y-m-d','Ymd') > dataConv($lin[DataVencimento],'Y-m-d','Ymd')){
			$Campo[38] = $lin[DataVencimento];
		}else{
			$Campo[38] = $DataDesconto;  
		}
		$Campo[38] = dataConv($Campo[38],'Y-m-d','DDMMAA');
		$Campo[38] = preenche_tam($Campo[38], 6, '');
			
		// Campo  39 (Valor primeiro desconto)						[13] 180-192	N
		$Campo[39] = number_format($lin2[ValorDescontoAConceber], 2, '.', '');
		$Campo[39] = str_replace(".","",$Campo[39]);
		$Campo[39] = preenche_tam($Campo[39], 13, '');
		
		// Campo  40 (Código da Moeda)								[1] 193-193		N 
		$Campo[40] = "9";
		$Campo[40] = preenche_tam($Campo[40], 1, '');

		// Campo  41 (Valor IOF / Quantidade Monetária)				[12] 194-205	N 
		$Campo[41] = "0";
		$Campo[41] = preenche_tam($Campo[41], 12, '');
		
		// Campo  42 (Valor Abatimento)								[13] 206-218	N
		$Campo[42] = "0";
		$Campo[42] = preenche_tam($Campo[42], 13, '');	

		// Campo  43 (Tipo de Inscrição do Sacado)					[2] 219-220		N
		if($lin[TipoPessoa] == 2){
			$Campo[43] = "01";
		}else{
			$Campo[43] = "02";
		}
		$Campo[43] = preenche_tam($Campo[43], 2, '');	

		// Campo  44 (Número do CNPJ ou CPF do Sacado)				[14] 221-234	N
		$Campo[44] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[44] = preenche_tam($Campo[44], 14, '');

		// Campo  45 (Nome do sacado)								[40] 235-274	X
		if($lin[TipoPessoa] == 1){
			$Campo[45] = $lin[RazaoSocial];
		}else{
			$Campo[45] = $lin[Nome];
		}
		$Campo[45] = preenche_tam(removeCaracters($Campo[45]), 40, 'X');

		// Campo  46 (Endereço do sacado)							[37] 275-311	X
		$Campo[46] = removeCaracters($lin[EnderecoCompleto]);
		$Campo[46] = preenche_tam($Campo[46], 37, 'X');

		// Campo  47 (Bairro do Sacado)								[15] 312-326	X
		$Campo[47] = removeCaracters($lin[Bairro]);
		$Campo[47] = preenche_tam($Campo[47], 15, 'X');

		// Campo  48 (CEP do sacado)								[8] 327-334		N
		$Campo[48] = str_replace("-","",$lin[CEP]);
		$Campo[48] = preenche_tam($Campo[48], 8, '');

		// Campo  49 (Cidade do Sacado)								[15] 335-349	X
		$Campo[49] = $lin[NomeCidade];
		$Campo[49] = preenche_tam($Campo[49], 15, 'X');

		// Campo  50 (UF do Sacado)									[2] 350-351		X
		$Campo[50] = $lin[SiglaEstado];
		$Campo[50] = preenche_tam($Campo[50], 2, 'X');
	
		// Campo  51 (Observações/Mensagem ou Sacador/Avalista)		[40] 352-391	X Verificar com o Morone sobre a limitação do campo. 
		if($ParametroLocalCobranca['LocalImpressao'] == 1){
			if($lin[TipoPessoa] == 1){
				$Campo[51] = $lin[RazaoSocial];
			}else{
				$Campo[51] = $lin[Nome];
			}
			$Campo[51] .= " ".$lin[CPF_CNPJ];
		}else{
			if($lin[TipoPessoa] == 1){
				$Campo[51] = $lin[RazaoSocial];
			}else{
				$Campo[51] = $lin[Nome];
			}
		}
		$Campo[51] = preenche_tam($Campo[51], 40, 'X');
		
		// Campo  52 (Numero de dias p/ protesteo automatico)		[2] 392-393		X
		$Campo[52] = $ParametroLocalCobranca[NumeroDiasProtestar];
		$Campo[52] = preenche_tam($Campo[52], 2, 'X');

		// Campo  53 (Complemento do Registro: Brancos)				[1] 394-395		X 
		$Campo[53] = preenche_tam(" ", 1, 'X');
		
		// Campo  54 (Sequencial do registro)						[6] 395-400		N 
		$NumSeqRegistro +=1;
		$Campo[54] = $NumSeqRegistro;
		$Campo[54] = preenche_tam($Campo[54], 6, '');

		// Salva
		$Linha[$i] = concatVar($Campo);
		$i++;
		
		$Campo = null;
	}	
	
	# Trailler
	
	// Campo  1 (Identificação Registro Trailler)				[1]   1-1		N
	$Campo[1] = "9";
	$Campo[1] = preenche_tam($Campo[1], 1, '');
	
	// Campo  2 (Filler)										[193] 2-194		X
	$Campo[2] = preenche_tam(" ", 193, 'X');

	// Campo  4 (Mensagem responsabilidade Cedente)				[40]  195-234	X 
	$Campo[3] = removeCaracters($ParametroLocalCobranca['Instrucoes1']);
	$Campo[3] = preenche_tam($Campo[3], 40, 'X');
	
	// Campo  5 (Mensagem responsabilidade Cedente)				[40]  235-274	X 
	$Campo[4] = removeCaracters($ParametroLocalCobranca['Instrucoes2']);
	$Campo[4] = preenche_tam($Campo[4], 40, 'X');
	
	// Campo  6 (Mensagem responsabilidade Cedente)				[40]  275-314	X 
	$Campo[5] = removeCaracters($ParametroLocalCobranca['Instrucoes3']);
	$Campo[5] = preenche_tam($Campo[5], 40, 'X');
	
	// Campo  7 (Mensagem responsabilidade Cedente)				[40]  315-354	X
	$Campo[6] = removeCaracters($ParametroLocalCobranca['Instrucoes4']);
	$Campo[6] = preenche_tam($Campo[6], 40, 'X');
	
	// Campo  8 (Mensagem responsabilidade Cedente)				[40]  355-394	X
	$Campo[7] = removeCaracters($ParametroLocalCobranca['Instrucoes5']);
	$Campo[7] = preenche_tam($Campo[7], 40, 'X');	

	// Campo  10 (Número sequencial do registro)				[6]  395-400	N
	$NumSeqRegistro +=1;
	$Campo[8] = $NumSeqRegistro; 
	$Campo[8] = preenche_tam($Campo[8], 6, '');
	
	// Salva
	$Linha[$i] = concatVar($Campo);

	$i++;
	
	$Campo = null;		
?>
