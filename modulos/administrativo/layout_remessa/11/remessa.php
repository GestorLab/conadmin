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
		
		$IdParcelaCarne = 0;
		
		if($lin[IdCarne] > 0){
			$sql = "select
						IdContaReceber
					from
						ContaReceber
					where
						IdLoja = $local_IdLoja and
						IdCarne = $lin[IdCarne]
					order by
						IdContaReceber";
			$resParcelaCarne = mysql_query($sql,$con);
			while($linParcelaCarne = mysql_fetch_array($resParcelaCarne)){
				$IdParcelaCarne++;
				if($linParcelaCarne[IdContaReceber] == $lin[IdContaReceber]){
					break;		
				}			
			}
			
			$sql = "select
						count(*) QtdContaReceberCarne
					from
						ContaReceber
					where
						IdLoja = $local_IdLoja and
						IdCarne = $lin[IdCarne]";
			$resCarne = mysql_query($sql,$con);
			$linCarne = mysql_fetch_array($resCarne);
		}

		$sql = "select
					IdLocalCobrancaParametro,
					ValorLocalCobrancaParametro
				from
					LocalCobrancaParametro
				where
					IdLoja = $local_IdLoja and
					IdLocalCobranca = $local_IdLocalCobranca";
		$resLC = mysql_query($sql,$con);
		while($linLC = mysql_fetch_array($resLC)){
			$LocalCobrancaParametro[$linLC[IdLocalCobrancaParametro]] = $linLC[ValorLocalCobrancaParametro];
		}
		
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
		
		switch($lin[IdPosicaoCobranca]){
			case 1:
				$sql	=	"UPDATE ContaReceber SET						
								IdArquivoRemessa	= $local_IdArquivoRemessa
							WHERE 
								IdLoja				= '$local_IdLoja' and
								IdContaReceber		= '$lin[IdContaReceber]'";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);		
				$tr_i++;

				$sql	=	"UPDATE ContaReceberPosicaoCobranca SET						
								DataRemessa			= curdate(),
								IdArquivoRemessa	= $local_IdArquivoRemessa
							WHERE 
								IdLoja				= '$local_IdLoja' and
								IdContaReceber		= '$lin[IdContaReceber]' and								
								DataRemessa			= '0000-00-00'";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);		
				$tr_i++;
				
				$Instrucao = "01"; // Remessa
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
				echo "Contas a Receber com posição de cobrança não aceita para esse tipo de modalidade. CR -> ".$lin[IdContaReceber];
				$local_transaction[$tr_i] = false;
				$tr_i++;
				break;
		}		
	
		# Registro detalhe - cobrança sem registro
		
		// Campo  1 (Identificação do Registro detalhe)				[1]   1-1		N
		$Campo[1] = "1";
		$Campo[1] = preenche_tam($Campo[1], 1, '');

		// Campo  2 (Tipo de cobrança)								[1]   2-2		X # C = Sem Registro
		$Campo[2] = "C";
		$Campo[2] = preenche_tam($Campo[2], 1, 'X');

		// Campo  3 (Filler)										[1]   3-3		X
		$Campo[3] = preenche_tam(" ", 1, 'X');

		// Campo  4 (Tipo de Impressão)								[1]   4-4		X			
		if($lin[IdCarne] > 0){
			$Campo[4] = "B";
		}else{
			$Campo[4] = "A";
		}		
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
		$Campo[8] = "B";
		$Campo[8] = preenche_tam($Campo[8], 1, 'X');
		
		// Campo  9 (Filler)										[28] 20-47	    X
		$Campo[9] = preenche_tam(" ", 28, 'X');
		
		// Campo  10 (Nosso número)				[9]  48-56		N
		$lin[NumeroDocumento] = preenche_tam($lin[NumeroDocumento], 5, '');				
		$Campo[10] = $LocalCobrancaParametro[Agencia].$LocalCobrancaParametro[Posto].$LocalCobrancaParametro[CodigoCedente].$lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento];		
		$Campo[10] = mod11($Campo[10]);
		$Campo[10] = $lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento].$Campo[10];
		$Campo[10] = preenche_tam($Campo[10], 9, '');	
				
		// Campo  11 (Filler)										[1] 57-57	    X
		$Campo[11] = preenche_tam(" ", 1, 'X');
		
		// Campo  12 (Tipo de Impressão)							[1] 58-58		X  
		$Campo[12] = "B";
		$Campo[12] = preenche_tam($Campo[12], 1, 'X');

		// Campo  13 (Filler)										[13] 59-71		X  
		$Campo[13] = preenche_tam(" ", 13, 'X');
		
		// Campo  14 (Postagem do Título)							[1] 72-72		X  
		if($LocalCobrancaParametro[PostagemTitulo] == ''){
			echo "Atenção!!! Verifique se os títulos serão enviados para os clientes por conta do banco ou cedente.";
			$local_transaction[$tr_i] = false;
			$tr_i++;			
		}else{
			$Campo[14] = trim($LocalCobrancaParametro[PostagemTitulo]);
		}		
		$Campo[14] = preenche_tam($Campo[14], 1, 'X');
		
		// Campo  15 (Filler)										[2] 73-74	    X
		$Campo[15] = preenche_tam(" ", 2, 'X');
		
		// Campo  16 (Número da parcela do carnê)					[2] 75-76		N  
		if($lin[IdCarne] > 0){
			$Campo[16] = $IdParcelaCarne;
		}else{
			$Campo[16] = "0";
		}
		$Campo[16] = preenche_tam($Campo[16], 2, '');		
		
		// Campo  17 (Número total de parcelas do carnê)			[2] 77-78		N  
		if($lin[IdCarne] > 0){
			$Campo[17] = $linCarne[QtdContaReceberCarne];
		}else{
			$Campo[17] = 0;
		}	
		$Campo[17] = preenche_tam($Campo[17], 2, '');
		
		// Campo  18 (Filler)										[4]  79-82	    X
		$Campo[18] = preenche_tam(" ", 4, 'X');
		
		// Campo  19 (Valor de desconto por dia de antecipação)		[10] 83-92		N  
		$Campo[19] = "0";
		$Campo[19] = preenche_tam($Campo[19], 10, '');
		
		// Campo  20 (% multa por pagamento em atraso)				[4] 93-96		N  
		$Campo[20] = number_format($DadosLocalCobranca[PercentualMulta], 2, '.', '');
		$Campo[20] = str_replace(".","",$Campo[20]);
		$Campo[20] = preenche_tam($Campo[20], 4, '');

		// Campo  21 (Filler)										[12] 97-108	    X
		$Campo[21] = preenche_tam(" ", 12, 'X');
		
		// Campo  22 (Instrução)									[2] 109-110		N  
		$Campo[22] = $Instrucao;
		$Campo[22] = preenche_tam($Campo[22], 2, '');
		
		// Campo  23 (Seu número (nunca se repete))					[10] 111-120	N  
		$lin[NumeroDocumento]	= preenche_tam($lin[NumeroDocumento], 5, '');		
		$Campo[23] = $LocalCobrancaParametro[Agencia].$LocalCobrancaParametro[Posto].$LocalCobrancaParametro[CodigoCedente].$lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento];		
		$Campo[23] = mod11($Campo[23]);
		$Campo[23] = $lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento].$Campo[23];	
		$Campo[23] = preenche_tam($Campo[23], 10, '');
		
		// Campo  24 (Data de vencimento)							[6] 121-126		N  
		$Campo[24] = dataConv($lin[DataVencimento],'Y-m-d','dmy');
		$Campo[24] = preenche_tam($Campo[24], 6, '');

		// Campo  25 (Valor do titulo)								[13] 127-139	N  
		$Campo[25] = number_format($lin[ValorFinal], 2, '.', '');			
		$Campo[25] = str_replace(".","",$Campo[25]);
		$Campo[25] = preenche_tam($Campo[25], 13, '');		

		// Campo  26 (Filler)										[9] 140-148	    X
		$Campo[26] = preenche_tam(" ", 9, 'X');
		
		// Campo  27 (Espécie de documento)							[1] 149-149		X  
		$Campo[27] = "J";
		$Campo[27] = preenche_tam($Campo[27], 1, 'X');
		
		// Campo  28 (Filler)										[1] 150-150		X  
		$Campo[28] = preenche_tam($Campo[28], 1, 'X');
		
		// Campo  29 (Data de emissão)								[6] 151-156		N  
		$Campo[29] = dataConv($lin[DataLancamento],'Y-m-d','dmy');
		$Campo[29] = preenche_tam($Campo[29], 6, '');

		// Campo  30 (Filler)										[4] 157-160		X  		
		$Campo[30] = preenche_tam(" ", 4, 'X');
		
		// Campo  31 (Valor/% de juros por dia de atrazo)			[13] 161-173	N 
		$Campo[31] = $DadosLocalCobranca[PercentualJurosDiarios];			
		$Campo[31] = number_format($Campo[31], 2, '.', '');		
		$Campo[31] = str_replace(".","",$Campo[31]);
		$Campo[31] = preenche_tam($Campo[31], 13, '');		

		// Campo  32 (Data limite p/ concessão de desconto)			[6] 174-179		N 		
		$Campo[32] = dataConv($DataDesconto,'Y-m-d','dmy');;
		$Campo[32] = preenche_tam($Campo[32], 6, '');
			
		// Campo  33 (Valor/% do desconto)							[13] 180-192	N  
		$Campo[33] = number_format($lin2[ValorDescontoAConceber], 2, '.', '');
		$Campo[33] = str_replace(".","",$Campo[33]);
		$Campo[33] = preenche_tam($Campo[33], 13, '');
	
		// Campo  34 (Filler)										[26] 193-218	N
		$Campo[34] = preenche_tam("0", 26, '');

		// Campo  35 (Tipo de pessoa do sacado: PF ou PJ)			[1] 219-219		N  
		if($lin[TipoPessoa] == 1){
			// Jurídica
			$Campo[35] = 2;	
		}else{
			// Física
			$Campo[35] = 1;
		}
		$Campo[35] = preenche_tam($Campo[35], 1, '');
		
		// Campo  36 (Filler)										[1]  220-220	X
		$Campo[36] = preenche_tam(" ", 1, 'X');
		
		// Campo  37 (CIC/CGC do sacado)							[14] 221-234	N
		$Campo[37] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[37] = preenche_tam($Campo[37], 14, '');

		// Campo  38 (Nome do sacado)								[40] 235-274	X
		if($lin[TipoPessoa] == 1){
			$Campo[38] = $lin[RazaoSocial];
		}else{
			$Campo[38] = $lin[Nome];
		}		
		$Campo[38] = preenche_tam(removeCaracters($Campo[38]), 40, 'X');
		
		// Campo  39 (Endereço do sacado)							[40] 275-314	X
		$Campo[39] = removeCaracters($lin[EnderecoCompleto]);
		$Campo[39] = preenche_tam($Campo[39], 40, 'X');
		
		// Campo  40 (Codigo do sacado na cooperativa cedente)		[5] 315-319		X 
		$Campo[40] = "0";												// Esta informção é repassada pelo banco apos o cadastro do cliente 
		$Campo[40] = preenche_tam($Campo[40], 5, '');
				
		// Campo  41 (Filler)										[6] 320-325		N
		$Campo[41] = preenche_tam("0", 6, '');

		// Campo  42 (Filler)										[1] 326-326		X
		$Campo[42] = preenche_tam(" ", 1, 'X');
		
		// Campo  43 (CEP do sacado)								[8] 327-334		N
		$Campo[43] = str_replace("-","",$lin[CEP]);
		$Campo[43] = preenche_tam($Campo[43], 8, '');	

		// Campo  44 (Cidade do sacado)								[25] 335-359	X 
		$Campo[44] = removeCaracters($lin[NomeCidade]);
		$Campo[44] = preenche_tam($Campo[44], 25, 'X');	

		// Campo  45 (Estado do sacado)								[2] 335-359		N 
		$Campo[45] = $lin[SiglaEstado];
		$Campo[45] = preenche_tam($Campo[45], 2, '');	
					
		// Campo  46 (Codigo do Sacado junto ao cliente)			[5] 362-366		N 
		$Campo[46] = "0";											// Esta informção é repassada pelo banco apos o cadastro do cliente 
		$Campo[46] = preenche_tam($Campo[46], 5, '');		
		
		// Campo  47 (Filler)										[28] 367-394	X
		$Campo[47] = preenche_tam(" ", 28, 'X');

		// Campo  52 (Numero sequencial do registro)				[6] 395-400		N 
		$NumSeqRegistro +=1;
		$Campo[48] = $NumSeqRegistro;
		$Campo[48] = preenche_tam($Campo[48], 6, '');

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
		$Campo[3] = $LocalCobrancaParametro[Agencia].$LocalCobrancaParametro[Posto].$LocalCobrancaParametro[CodigoCedente].$lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento];		
		$Campo[3] = mod11($Campo[3]);
		$Campo[3] = $lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento].$Campo[3];
		$Campo[3] = preenche_tam($Campo[3], 9, '');				

		// Campo  4 (1ª Instrução para impressão no bloqueto)		[80]   22-101	X
		$Campo[4] = removeCaracters($LocalCobrancaParametro['Instrucoes1']);
		$Campo[4] = preenche_tam($Campo[4], 80, 'X');
		
		// Campo  5 (2ª Instrução para impressão no bloqueto)		[80]   102-181	X
		$Campo[5] = removeCaracters($LocalCobrancaParametro['Instrucoes2']);
		$Campo[5] = preenche_tam($Campo[5], 80, 'X');
		
		// Campo  6 (3ª Instrução para impressão no bloqueto)		[80]   182-261	X
		$Campo[6] = removeCaracters($LocalCobrancaParametro['Instrucoes3']);
		$Campo[6] = preenche_tam($Campo[6], 80, 'X');
		
		// Campo  7 (4ª Instrução para impressão no bloqueto)		[80]   262-341	X
		$Campo[7] = removeCaracters($LocalCobrancaParametro['Instrucoes4']);
		$Campo[7] = preenche_tam($Campo[7], 80, 'X');
		
		// Campo  8 (Seu Número (nunca se repete))					[10]  342-351	N
		$lin[NumeroDocumento]	= preenche_tam($lin[NumeroDocumento], 5, '');		
		$Campo[8] = $LocalCobrancaParametro[Agencia].$LocalCobrancaParametro[Posto].$LocalCobrancaParametro[CodigoCedente].$lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento];		
		$Campo[8] = mod11($Campo[8]);
		$Campo[8] = $lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento].$Campo[8];
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
		$Campo[3] = $LocalCobrancaParametro['CodigoCedente'];
		$Campo[3] = preenche_tam($Campo[3], 5, '');

		// Campo  4 (Identificação do título seu número)			[10]  8-17		N
		/*$lin[NumeroDocumento]	= preenche_tam($lin[NumeroDocumento], 5, '');		
		$Campo[4] = $LocalCobrancaParametro[Agencia].$LocalCobrancaParametro[Posto].$LocalCobrancaParametro[CodigoCedente].$lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento];		
		$Campo[4] = mod11($Campo[4]);
		$Campo[4] = $lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento].$Campo[4];*/
		$Campo[4] = "XXXXXXXXXX";
		$Campo[4] = preenche_tam($Campo[4], 10, 'X');		
		
		// Campo  5 (Filler)										[1]  18-18		X
		$Campo[5] = preenche_tam(" ", 1, 'X');

		// Campo  6 (Tipo de cobrança)								[1]  19-19		X  
		$Campo[6] = "C";
		$Campo[6] = preenche_tam($Campo[6], 1, 'X');
		
		// Campo  7 (Numero da linha do informativo)				[2]  20-21		N  
		$Campo[7] = "1";
		$Campo[7] = preenche_tam($Campo[7], 2, '');
		
		// Campo  8 (Texto da linha do informativo)					[80]  22-101	X  
		$Campo[8] = removeCaracters($LocalCobrancaParametro['Instrucoes1']);
		$Campo[8] = preenche_tam($Campo[8], 80, 'X');
		
		// Campo  9 (Numero da linha do informativo)				[2]  102-103	N  
		$Campo[9] = "2";
		$Campo[9] = preenche_tam($Campo[9], 2, '');
		
		// Campo  10 (Texto da linha do informativo)				[80]  104-183	X  
		$Campo[10] = removeCaracters($LocalCobrancaParametro['Instrucoes2']);
		$Campo[10] = preenche_tam($Campo[10], 80, 'X'); 
		
		// Campo  11 (Numero da linha do informativo)				[2]  184-185	N  
		$Campo[11] = "3";
		$Campo[11] = preenche_tam($Campo[11], 2, '');
		
		// Campo  12 (Texto da linha do informativo)				[80]  186-265	X  
		$Campo[12] = removeCaracters($LocalCobrancaParametro['Instrucoes3']);
		$Campo[12] = preenche_tam($Campo[12], 80, 'X'); 
		
		// Campo  13 (Numero da linha do informativo)				[2]  266-267	N  
		$Campo[13] = "4";
		$Campo[13] = preenche_tam($Campo[13], 2, '');
		
		// Campo  14 (Texto da linha do informativo)				[80]  268-347	X  
		$Campo[14] = removeCaracters($LocalCobrancaParametro['Instrucoes4']);
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
		$Campo[1] = "6";
		$Campo[1] = preenche_tam($Campo[1], 1, '');
		
		// Campo  2 (Nosso número SICREDI sem edição)				[15]  2-16		N 
		$lin[NumeroDocumento]	= preenche_tam($lin[NumeroDocumento], 5, '');		
		$Campo[2] = $LocalCobrancaParametro[Agencia].$LocalCobrancaParametro[Posto].$LocalCobrancaParametro[CodigoCedente].$lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento];		
		$Campo[2] = mod11($Campo[2]);
		$Campo[2] = $lin[AnoLancamento].$LocalCobrancaParametro[ByteIDT].$lin[NumeroDocumento].$Campo[2];
		$Campo[2] = preenche_tam($Campo[2], 15, '');
		
		// Campo  3 (Seu numero (nunca se repete))					[10]  17-26		N
		$Campo[3] = preenche_tam($lin[NumeroDocumento], 10, '');

		// Campo  4 (Codigo do sacado junto ao cliente)				[5]  27-31		N 
		$Campo[4] = 0;
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
		
		$Campo = null;				
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
