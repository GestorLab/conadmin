<?
	include("funcao_layout.php");
	// Gera a nomenclatura do arquivo
	
	$DataRemessa = explode("/",$local_DataRemessa);
	$DataSimples = $DataRemessa[0].$DataRemessa[1]."01";
		
	$NomeArquivo = "CB".$DataSimples.".REM";

	$Patch = "remessa/local_cobranca/$local_IdLoja/$local_IdLocalCobranca/$local_IdArquivoRemessa";	

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
			$NomeArquivo = "CB".substr($DataSimples,0,4).str_pad($lin[Qtd], 2, "0", STR_PAD_LEFT).".REM";					
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

### // Registro 0 - Header Label

	// Campo  1 (Identificação do Registro)						[1]   1-1		N
	$Campo[1] = "0";
	$Campo[1] = preenche_tam($Campo[1], 1, '');

	// Campo  2 (Identificação do Arquivo Remessa)				[1]   2-2		N
	$Campo[2] = "1";
	$Campo[2] = preenche_tam($Campo[2], 1, '');

	// Campo  3 (Literal Remessa)								[7]   3-9		X
	$Campo[3] = "REMESSA";
	$Campo[3] = preenche_tam($Campo[3], 7, 'X');

	// Campo  4 (Código de Serviço)								[2]   10-11		N
	$Campo[4] = "01";
	$Campo[4] = preenche_tam($Campo[4], 2, '');

	// Campo  5 (Literal Serviço)								[15]  12-26		X
	$Campo[5] = "COBRANCA";
	$Campo[5] = preenche_tam($Campo[5], 15, 'X');


	// Campo  6 (Código da Empresa)								[20]  27-46		N
	$Campo[6] = $LocalCobrancaParametro[Convenio];
	$Campo[6] = preenche_tam($Campo[6], 20, '');

	// Campo  7 (Nome da Empresa)								[30]  47-76		X
	$Campo[7] = $DadosEmpresa[RazaoSocial];
	$Campo[7] = preenche_tam($Campo[7], 30, 'X');

	// Campo  8 (Número do Bradesco na Câmara de Compensação)	[3]   77-79		N
	$Campo[8] = "237";
	$Campo[8] = preenche_tam($Campo[8], 3, '');

	// Campo  9 (Nome do Banco por Extenso)						[15]  80-94     X
	$Campo[9] = "BRADESCO";
	$Campo[9] = preenche_tam($Campo[9], 15, 'X');

	// Campo 10 (Data da Gravação do Arquivo)					[6]   95-100	N
	$Campo[10] = date("dm").substr(date("Y"),2,2);
	$Campo[10] = preenche_tam($Campo[10], 6, '');
	
	// Campo 11 (Branco)										[8]	  101-108   X
	$Campo[11] = preenche_tam(" ", 8, 'X');

	// Campo 12 (Identificação do Sistema)						[2]	  109-110   X
	$Campo[12] = "MX";
	$Campo[12] = preenche_tam($Campo[12], 2, 'X');

	// Campo 13 (Nº Seqüencial de Remessa)						[7]	  111-117   N
	$Campo[13] = $NumSeqArquivo;
	$Campo[13] = preenche_tam($Campo[13], 7, '');

	// Campo 14 (Branco)										[277] 118-394   X
	$Campo[14] = preenche_tam(" ", 277, 'X');

	// Campo 15 (Nº Seqüencial do Registro de Um em Um)			[6]	  395-400	N
	$Campo[15] = $i+1;
	$Campo[15] = preenche_tam($Campo[15], 6, '');

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;


### // Registro 1 - Registro da Transação	
	$sql = "select
				ContaReceberDados.IdLoja,
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.NossoNumero,
				ContaReceberDados.IdPessoa,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.DataLancamento,
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

		// Campo  1 (Identificação do Registro)						[1]   1-1		N
		$Campo[1] = "1";
		$Campo[1] = preenche_tam($Campo[1], 1, '');

		// Campo  2 (Agência de Débito)								[5]   2-6		N
		$Campo[2] = "";
		$Campo[2] = preenche_tam($Campo[2], 5, '');

		// Campo  3 (Dígito da Agência de Débito)					[1]   7-7		X
		$Campo[3] = "";
		$Campo[3] = preenche_tam($Campo[3], 1, 'X');

		// Campo  4 (Razão da Conta Corrente)						[5]   8-12		N
		$Campo[4] = "0";
		$Campo[4] = preenche_tam($Campo[4], 5, '');

		// Campo  5 (Conta Corrente)								[7]  13-19		N
		$Campo[5] = "";
		$Campo[5] = preenche_tam($Campo[5], 7, '');

		// Campo  6 (Dígito da Conta Corrente)						[1]  20-20		N
		$Campo[6] = "";
		$Campo[6] = preenche_tam($Campo[6], 1, '');

		// Campo  7 (Identificação da Empresa)						[17] 21-37		X

			# 21 a 21 - Zero										[1] 21-21
			$Campo[7] = "0";

			# 22 a 24 - código da carteira							[3] 22-24
			$Campo[7] .= preenche_tam($LocalCobrancaParametro[Carteira], 3, '');

			# 25 a 29 - código da Agência Cedente, sem o dígito		[5] 25-29
			$Campo[7] .= preenche_tam($LocalCobrancaParametro[Agencia], 5, '');

			# 30 a 36 - Conta Corrente								[7] 30-36
			$Campo[7] .= preenche_tam($LocalCobrancaParametro[Conta], 7, '');

			# 37 a 37 - dígito da Conta								[1] 37-37
			$Campo[7] .= preenche_tam($LocalCobrancaParametro[DigitoConta], 1, '');

		$Campo[7] = preenche_tam($Campo[7], 17, 'X');

		// Campo  8 (Nº Controle do Participante)					[25] 38-62		X
		$Campo[8] = $lin[IdPessoa];
		$Campo[8] = preenche_tam($Campo[8], 25, '');

		// Campo  9 (Cód do Banco a ser deb na Câmara de Comp.)		[3]  63-65		N
		$Campo[9] = "";
		$Campo[9] = preenche_tam($Campo[9], 3, '');

		// Campo 10 (Campo de Multa)								[1]  66-66		N
		$Campo[10] = "2";
		$Campo[10] = preenche_tam($Campo[10], 1, '');

		// Campo 11 (Percentual de multa)							[4]  67-70		N
		$Campo[11] = number_format($DadosLocalCobranca[PercentualMulta], 2, '.', '');
		$Campo[11] = str_replace(".","",$Campo[11]);
		$Campo[11] = preenche_tam($Campo[11], 4, '');

		// Campo 12 (Identificação do Título no Banco)				[11] 71-81		N
		if($ParametroLocalCobranca[LocalImpressao] == 2 || $ParametroLocalCobranca[ForcarPreenchimentoNossoNumero] == 1){
			$Campo[12] = $lin[NumeroDocumento];
		}else{
			if($lin[IdPosicaoCobranca] == 1){
				$Campo[12] = "0";
			}else{
				$Campo[12] = $lin[NossoNumero];
			}
		}
		$Campo[12] = preenche_tam($Campo[12], 11, '');

		// Campo 13 (Digito de Auto Conferencia do Nosso Número)	[1]  82-82		X
		if($ParametroLocalCobranca[LocalImpressao] == 2 || $ParametroLocalCobranca[ForcarPreenchimentoNossoNumero] == 1){
			$Campo[13] = $LocalCobrancaParametro[Carteira].$Campo[12];
			$Campo[13] = mod11($Campo[13],7);
		}else{
			$Campo[13] = "0";
		}
		$Campo[13] = preenche_tam($Campo[13], 1, '');

		// Campo 14 (Desconto Bonificação por dia)					[10] 83-92		N
		$Campo[14] = 0;
		$Campo[14] = preenche_tam($Campo[14], 10, '');

		// Campo 15 (Condição para Emissão da Papeleta de Cob)		[1]  93-93		N
		$Campo[15] = $ParametroLocalCobranca[LocalImpressao];
		$Campo[15] = preenche_tam($Campo[15], 1, '');

		// Campo 16 (Ident. se emite papeleta para Débito Aut.)		[1]  94-94		X
		$Campo[16] = " ";
		$Campo[16] = preenche_tam($Campo[16], 1, 'X');

		// Campo 17 (Identificação da Operação do Banco)			[10] 95-104		X
		$Campo[17] = " ";
		$Campo[17] = preenche_tam($Campo[17], 10, 'X');

		// Campo 18 (Indicador Rateio Crédito)						[1] 105-105		X
		$Campo[18] = " ";
		$Campo[18] = preenche_tam($Campo[18], 1, 'X');

		// Campo 19 (End. para Aviso do Déb. Aut. em C. Corrente)	[1] 106-106		N
		$Campo[19] = "0";
		$Campo[19] = preenche_tam($Campo[19], 1, '');

		// Campo 20 (Branco)										[2] 107-108		X
		$Campo[20] = " ";
		$Campo[20] = preenche_tam($Campo[20], 2, 'X');

		// Campo 21 (Identificação ocorrência)						[2] 109-110		N		
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
				
				$Campo[21] = "01"; // Remessa
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

				$Campo[21] = "09"; // Pedido de protesto
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

				$Campo[21] = "19"; // Pedido sustação de protesto
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

				$Campo[21] = "02"; // Pedido de Baixa
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

				$Campo[21] = "02"; // Pedido de Baixa quando cancela o recebimento do contas a receber
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

				$Campo[21] = "31"; // Alteração de outros dados
				break;
			default:
				$local_transaction[$tr_i] = false;
				$tr_i++;
				break;
		}		
		$Campo[21] = preenche_tam($Campo[21], 2, '');

		// Campo 22 (Nº do Documento)								[10]111-120		X
		if(strlen($lin[NumeroDocumento]) > 10){
			$NumeroDocumento = 	substr($lin[NumeroDocumento], 1, 10);
		}else{
			$NumeroDocumento = 	$lin[NumeroDocumento];
		}
		$Campo[22] = $NumeroDocumento;
		$Campo[22] = preenche_tam($Campo[22], 10, '');

		// Campo 23 (Data do Vencimento do Título)					[6] 121-126		N
		$Campo[23] = dataConv($lin[DataVencimento],"Y-m-d","dmy");
		$Campo[23] = preenche_tam($Campo[23], 6, '');

		// Campo 24 (Valor do Título)								[13]127-139		N
		$Campo[24] = str_replace(".","",$lin[ValorFinal]);
		$Campo[24] = preenche_tam($Campo[24], 13, '');

		// Campo 25 (Banco Encarregado da Cobrança)					[3] 140-142		N
		$Campo[25] = "0";
		$Campo[25] = preenche_tam($Campo[25], 3, '');

		// Campo 26 (Agência Depositária)							[5] 143-147		N
		$Campo[26] = "0";
		$Campo[26] = preenche_tam($Campo[26], 5, '');

		// Campo 27 (Espécie de Título)								[2] 148-149		N
		$Campo[27] = $ParametroLocalCobranca[EspecieTitulo];
		$Campo[27] = preenche_tam($Campo[27], 2, '');

		// Campo 28 (Identificação)									[1] 150-150		X
		$Campo[28] = $ParametroLocalCobranca[Aceite];
		$Campo[28] = preenche_tam($Campo[28], 1, '');

		// Campo 29 (Data da emissão do Título)						[6] 151-156		N
		$Campo[29] = dataConv($lin[DataLancamento],"Y-m-d","dmy");
		$Campo[29] = preenche_tam($Campo[29], 6, '');

		// Campo 30 (1ª instrução)									[2] 157-158		N
		if(!empty($ParametroLocalCobranca[QtdDiasProtesto])){
			$Campo[30] = '06';			
		}else{
			$Campo[30] = 0;
		}		
		$Campo[30] = preenche_tam($Campo[30], 2, '');

		// Campo 31 (2ª instrução)									[2] 159-160		N
		if(!empty($ParametroLocalCobranca[QtdDiasProtesto])){
			if($ParametroLocalCobranca[QtdDiasProtesto] >= 5){
				$Campo[31] = $ParametroLocalCobranca[QtdDiasProtesto];
			}else{
				echo "Informação de protesto inválido.";
				$local_transaction[$tr_i] = false;
				$tr_i++;
				break;
			}
		}else{
			$Campo[31] = 0;
		}		
		$Campo[31] = preenche_tam($Campo[31], 2, '');

		// Campo 32 (Valor a ser cobrado por Dia de Atraso)			[13]161-173		N
		$Campo[32] = $lin[ValorFinal]*$DadosLocalCobranca[PercentualJurosDiarios]/100;
		$Campo[32] = number_format($Campo[32], 2, '.', '');	
		$Campo[32] = str_replace(".","",$Campo[32]);
		$Campo[32] = preenche_tam($Campo[32], 13, '');		
		
		// Campo 33 (Data Limite P/Concessão de Desconto)			[6] 174-179		N
		$Campo[33] = 0;
		$Campo[33] = preenche_tam($Campo[33], 6, '');

		// Campo 34 (Valor do Desconto)								[13]180-192		N
		$Campo[34] = 0;
		$Campo[34] = preenche_tam($Campo[34], 13, '');

		// Campo 35 (Valor do IOF)									[13]193-205		N
		$Campo[35] = 0;
		$Campo[35] = preenche_tam($Campo[35], 13, '');

		// Campo 36 (Valor do Abatimento a ser concedido ou canc)	[13]206-218		N		
		$Campo[36] = 0;
		$Campo[36] = preenche_tam($Campo[36], 13, '');

		// Campo 37 (Identificação do Tipo de Inscrição do Sacado)	[2] 219-220		N
		if($lin[TipoPessoa] == 2){	
			$Campo[37] = 1;	
		}else{	
			$Campo[37] = 2;	
			$lin[Nome] = $lin[RazaoSocial];
		}
		$Campo[37] = preenche_tam($Campo[37], 2, '');

		// Campo 38 (Nº Inscrição do Sacado)						[14]221-234		N	
		$Campo[38] = $lin[CPF_CNPJ];
		$Campo[38] = str_replace(".","",$Campo[38]);
		$Campo[38] = str_replace("-","",$Campo[38]);
		$Campo[38] = str_replace("/","",$Campo[38]);
		$Campo[38] = preenche_tam($Campo[38], 14, '');

		// Campo 39 (Nome do Sacado)								[40]235-274		X
		$Campo[39] = $lin[Nome];
		$Campo[39] = preenche_tam($Campo[39], 40, 'X');

		// Campo 40 (Endereço Completo)								[40]275-314		X
		$Campo[40] = $lin[Endereco].", ".$lin[Numero];
		if($lin[Complemento] != ''){
			$Campo[40] .= " - ".$lin[Complemento];
		}
		$Campo[40] .= " - ".$lin[Bairro];	
		$Campo[40] .= $lin[NomeCidade]."-".$lin[SiglaEstado];
		$Campo[40] = preenche_tam($Campo[40], 40, 'X');

		// Campo 41 (1ª Mensagem)									[12]315-326		X
		$Campo[41] = "";
		$Campo[41] = preenche_tam($Campo[41], 12, 'X');

		// Campo 42 (CEP)											[5] 327-331		N
		$CEP = explode("-",str_replace(".","",$lin[CEP]));
		$Campo[42] = preenche_tam($CEP[0], 5, '');

		// Campo 43 (Sufixo do CEP)									[3] 332-334		N
		$Campo[43] = preenche_tam($CEP[1], 3, '');

		// Campo 44 (Sacador/Avalista ou 2ª Mensagem)				[60]335-394		X
		$Campo[44] = $ParametroLocalCobranca[Instrucoes1];
		$Campo[44] = preenche_tam($Campo[44], 60, 'X');

		// Campo 45 (Nº Seqüencial do Registro)						[6] 395-400		N
		$Campo[45] = $i+1;
		$Campo[45] = preenche_tam($Campo[45], 6, '');

		// Salva
		$Linha[$i] = concatVar($Campo);
		$i++;		
	}

	$Campo = null;

	// Campo 1 (Identificação Registro)						[1] 1-1		N
	$Campo[1] = preenche_tam("9", 1, '');

	// Campo 2 (Branco)										[393] 2-394	X
	$Campo[2] = preenche_tam("", 393, 'X');

	// Campo 3 (Identificação Registro)						[6]	395-400	N
	$Campo[3] = $i+1;
	$Campo[3] = preenche_tam($Campo[3], 6, '');

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++
?>