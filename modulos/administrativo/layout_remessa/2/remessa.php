<?
	include("funcao_layout.php");
	
	// Gera a nomenclatura do arquivo
	$DataRemessa = explode("/",$local_DataRemessa);
	$DataSimples = $DataRemessa[0].$DataRemessa[1].substr($DataRemessa[2],2,2);

	$NomeArquivo = "CB".$DataSimples."01.REM";

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
			$NomeArquivo = "CB".$DataSimples.str_pad($lin[Qtd], 2, "0", STR_PAD_LEFT).".REM"; 					
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
	
	if($DadosLocalCobranca[IdPessoa] != ""){

		$sql = "select		
					Pessoa.Nome,
					Pessoa.TipoPessoa,
					Pessoa.CPF_CNPJ
				from
					Pessoa
				where						
					Pessoa.IdPessoa = $DadosLocalCobranca[IdPessoa]";
		$res = mysql_query($sql,$con);
		$DadosEmpresaLocalCobranca = mysql_fetch_array($res);			

		$DadosEmpresa[CPF_CNPJ] = $DadosEmpresaLocalCobranca[CPF_CNPJ];		
	}

### // Registro 0 - Header Label

	// Campo  1 (Código do Banco na compensação)				[3]   1-3		N
	$Campo[1] = "033";

	// Campo  2 (Lote de servico)								[4]   4-7		N
	$NumeroLoteServico = 0;
	$Campo[2] = $NumeroLoteServico;
	$Campo[2] = preenche_tam($Campo[2], 4, '');

	// Campo  3 (Tipo de Registro)								[1]   8-8		N
	$Campo[3] = 0;	

	// Campo  4 (Reservado (uso Banco))							[8]   9-16		X
	$Campo[4] = preenche_tam(" ", 8, 'X');

	// Campo  5 (Tipo de inscrição da empresa)					[1]  17-17		N
	if($DadosEmpresa[TipoPessoa] == 1){
		$Campo[5] = 2;
	}else{
		$Campo[5] = 1;
	}
	
	// Campo  6 (Nº de inscrição da empresa)					[15]  18-32		N		
	$Campo[6] = removeMascaraCPF_CNPJ($DadosEmpresa[CPF_CNPJ]);
	$Campo[6] = preenche_tam($Campo[6], 15, '');

	// Campo  7 (Código de Transmissão)							[15]  33-47		N
	$Campo[7] = $LocalCobrancaParametro[CodigoTransmissao];
	$Campo[7] =	preenche_tam($Campo[7], 15, '');

	// Campo  8 (Reservado (uso Banco))							[25]  48-72		X	
	$Campo[8] = preenche_tam(" ", 25, 'X');

	// Campo  9 (Nome da empresa)								[30]  73-102    X
	if($DadosEmpresaLocalCobranca[TipoPessoa] == 2){
		$Campo[9] = $DadosEmpresaLocalCobranca[Nome];
	}else{
		$Campo[9] = $DadosEmpresa[RazaoSocial];
	}
	$Campo[9] = preenche_tam($Campo[9], 30, 'X');

	// Campo 10 (Nome do Banco)									[30]   103-132	X
	$Campo[10] = "Banco Santander";
	$Campo[10] = preenche_tam($Campo[10], 30, 'X');

	// Campo 11 (Reservado (uso Banco))							[10]  133-142   X	
	$Campo[11] = preenche_tam(" ", 10, 'X');

	// Campo 12 (Código remessa)								[1]	  143-143   N
	$Campo[12] = 1;
	$Campo[12] = preenche_tam($Campo[12], 1, '');

	// Campo 13 (Data de geração do arquivo)					[8]	  144-151   N
	$Campo[13] = date('dmY');
	$Campo[13] = preenche_tam($Campo[13], 8, '');

	// Campo 14 (Reservado (uso Banco))							[6]  152-157    X
	$Campo[14] = preenche_tam(" ", 6, 'X');

	// Campo 15 (Nº Seqüencial do arquivo)						[6]	 158-163	N
	$Campo[15] = $NumSeqArquivo;
	$Campo[15] = preenche_tam($Campo[15], 6, '');
	
	// Campo 16 (Nº da versão do layout do arquivo)				[3]	 164-166	N
	$Campo[16] = "040";	
	
	// Campo 17 (Reservado (uso Banco))							[74]  167-240   X	
	$Campo[17] = preenche_tam(" ", 74, 'X');

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;


### // Registro Header do Lote Remessa

	// Campo  1 (Código do Banco na compensação)				[3]   1-3		N
	$Campo[1] = "033";

	// Campo  2 (Numero do lote remessa)						[4]   4-7		N	
	$NumeroLoteRemessa = 1;	
	$Campo[2] = $NumeroLoteRemessa;
	$Campo[2] = preenche_tam($Campo[2], 4, '');

	// Campo  3 (Tipo de Registro)								[1]   8-8		N
	$Campo[3] = 1;	

	// Campo  4 (Tipo de Operacao)								[1]   9-9		X
	$Campo[4] = preenche_tam("R", 1, 'X');

	// Campo  5 (Tipo de serviço)								[2]   10-11		N		
	$Campo[5] = 01;
	$Campo[5] = preenche_tam($Campo[5], 2, '');	
	
	// Campo  6 (Reservado (uso Banco))							[2]   12-13		X	
	$Campo[6] = preenche_tam(" ", 2, 'X');

	// Campo  7 (Nº da versão do layout do lote)				[3]   14-16		N	
	$Campo[7] = "030";
	$Campo[7] = preenche_tam($Campo[7], 3, '');

	// Campo  8 (Reservado (uso Banco))							[1]   17-17		X	
	$Campo[8] = preenche_tam(" ", 1, 'X');
	
	// Campo  9 (Tipo de inscrição da empresa)					[1]   18-18		N	
	if($DadosEmpresa[TipoPessoa] == 1){
		$Campo[9] = 2;
	}else{
		$Campo[9] = 1;
	}
	$Campo[9] = preenche_tam($Campo[9], 1, '');

	// Campo  10 (Nº de inscrição da empresa)					[15]   19-33	N	
	$Campo[10] = removeMascaraCPF_CNPJ($DadosEmpresa[CPF_CNPJ]);
	$Campo[10] = preenche_tam($Campo[10], 15, '');

	// Campo  11 (Reservado (uso Banco))						[20]   34-53	X	
	$Campo[11] = preenche_tam(" ", 20, 'X');

	// Campo  12 (Código de Transmissão)						[15]   54-68	N	
	$Campo[12] = $LocalCobrancaParametro[CodigoTransmissao];
	$Campo[12] = preenche_tam($Campo[12], 15, '');

	// Campo  13 (Reservado (uso Banco))						[5]    69-73	X	
	$Campo[13] = preenche_tam(" ", 5, 'X');

	// Campo  14 (Nome do Cedente)								[30]   74-103	X	
	if($DadosEmpresa[TipoPessoa] == 1){
		$Campo[14] = $DadosEmpresa[RazaoSocial];
	}else{
		$Campo[14] = $DadosEmpresa[Nome];
	}		
	$Campo[14] = preenche_tam($Campo[14], 30, 'X');

	// Campo  15 (Mensagem 1)									[40]   104-143	X	
	$Campo[15] = preenche_tam(" ", 40, 'X');

	// Campo  16 (Mensagem 2)									[40]   144-183	X		
	$Campo[16] = preenche_tam(" ", 40, 'X');

	// Campo  17 (Número remessa/retorno)						[8]   184-191	N		
	$Campo[17] = $NumSeqArquivo;
	$Campo[17] = preenche_tam($Campo[17], 8, '');

	// Campo  18 (Data da gravação remessa/retorno)				[8]   192-199	N	
	$Campo[18] = Date("dmY");
	$Campo[18] = preenche_tam($Campo[18], 8, '');

	// Campo  19 (Reservado (uso Banco))						[41]  200-240	X	
	$Campo[19] = preenche_tam(" ", 41, 'X');

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;
			
### // Registro 1 - Registro da Transação
	$NumeroLoteRemessaSeguimento = 0;
	
	$QtdContaReceber 			 = 0;
	
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

				$Instrucao = "06"; // Alteração de vencimento
				break;
			default:
				$local_transaction[$tr_i] = false;
				$tr_i++;
				break;
		}		
		
		// Registro seguimento P

		// Campo  1 (Código do Banco na compensação)				[3]   1-3		N
		$Campo[1] = "033";		

		// Campo  2 (Número do lote remessa)						[4]   4-7		N		
		$Campo[2] = 1; 
		$Campo[2] = preenche_tam($Campo[2], 4, '');

		// Campo  3 (Tipo de registro)								[1]   8-8		N
		$Campo[3] = 3;	
		
		// Campo  4 (Nº sequencial do registro de lote)				[5]   9-13		N
		$NumeroLoteRemessaSeguimento++;
		$Campo[4] = $NumeroLoteRemessaSeguimento;
	 	$Campo[4] = preenche_tam($Campo[4], 5, '');

		// Campo  5 (Cód. Segmento do registro detalhe)				[1]  14-14		X
		$Campo[5] = "P";

		// Campo  6 (Reservado (uso Banco))							[1]  15-15		X
		$Campo[6] = preenche_tam(" ", 1, 'X');
		
		// Campo  7 (Código de movimento remessa)					[2]  16-17		N
		$Campo[7] = $Instrucao;
				
		// Campo  8 (Agência do Cedente)							[4]  18-21		N
		$Campo[8] = preenche_tam($LocalCobrancaParametro[Agencia], 4, '');
		
		// Campo  9 (Dígito da Agência do Cedente)					[1]  22-22		N
		$Campo[9] = preenche_tam($LocalCobrancaParametro[AgenciaDigito], 1, '');

		// Campo  10 (Número da conta corrente)						[9]  23-31		N		
		$Campo[10] = preenche_tam($LocalCobrancaParametro[ContaNumero], 9, '');
		
		// Campo  11 (Dígito verificador da conta)					[1]  32-32		N		
		$Campo[11] = preenche_tam($LocalCobrancaParametro[ContaDigito], 1, '');
		
		// Campo  12 (Conta cobrança)								[9]  33-41		N
		$Campo[12] = $LocalCobrancaParametro[ContaNumero];
		$Campo[12] = preenche_tam($Campo[12], 9, '');
		
		// Campo  13 (Digito da conta cobrança)						[1]  42-42		N
		$Campo[13] = $LocalCobrancaParametro[ContaDigito];
		$Campo[13] = preenche_tam($Campo[13], 1, '');
				
		// Campo  14 (Reservado (uso Banco))						[2]  43-44		X
		$Campo[14] = preenche_tam(" ", 2, 'X');
		
		// Campo  15 (Identificação do título no banco)				[13] 45-57		N		
		$Campo[15] = $lin[NumeroDocumento];
		$Campo[15] = mod11($Campo[15]);
		$Campo[15] = $lin[NumeroDocumento].$Campo[15];			
		$Campo[15] = preenche_tam($Campo[15], 13, '');
		
		// Campo  16 (Tipo de Cobrança)								[1] 58-58		N
		$Campo[16] = $LocalCobrancaParametro[LocalImpressaoBoleto];
		$Campo[16] = preenche_tam($Campo[16], 1, '');
		
		// Campo  17 (Forma de cadastramento)						[1] 59-59	    N
		if($LocalCobrancaParametro[FormaCadastramento] != ''){	
			$Campo[17] = $LocalCobrancaParametro[FormaCadastramento];
		}else{
			$Campo[17] = 1;
		}
		$Campo[17] = preenche_tam($Campo[17], 1, '');
		
		// Campo  18 (Tipo de documento)							[1] 60-60	    N
		$Campo[18] = "1";		
		
		// Campo  19 (Reservado (uso Banco))						[1]  61-61		X
		$Campo[19] = preenche_tam(" ", 1, 'X');	
		
		// Campo  20 (Reservado (uso Banco))						[1]  62-62		X
		$Campo[20] = preenche_tam(" ", 1, 'X');	
		
		// Campo  21 (Identificação do título no banco)				[15] 63-77		X
		$Campo[21] = $lin[NumeroDocumento];
		$Campo[21] = preenche_tam($Campo[21], 15, 'X');
		
		// Campo 22 (Data de vencimento do título)					[8]	 78-85      N
		$Campo[22] = dataConv($lin[DataVencimento],'Y-m-d H:i:s','dmY');
		$Campo[22] = preenche_tam($Campo[22], 8, '');
	
		// Campo 23 (Valor nominal do título)						[15] 86-100      N
		$Campo[23] = number_format($lin[ValorFinal], 2, '.', '');
		$Campo[23] = str_replace(".","",$Campo[23]);		
		$Campo[23] = preenche_tam($Campo[23], 15, '');
		
		// Campo 24 (Agencia encarregada da cobrança)				[4] 101-104      N
		$Campo[24] = 0;
		$Campo[24] = preenche_tam($Campo[24], 4, '');
		
		// Campo 25 (Dígito da Agencia do Cedente)					[1] 105-105      N
		$Campo[25] = $LocalCobrancaParametro[AgenciaDigito];
		$Campo[25] = preenche_tam($Campo[25], 1, '');
		
		// Campo 26 (Reservado (uso Banco))							[1]  106-106     X
		$Campo[26] = preenche_tam(" ", 1, 'X');	
		
		// Campo 27 (Espécie do título)								[2] 107-108      N
		$Campo[27] = $LocalCobrancaParametro[EspecieDocumento];
		$Campo[27] = preenche_tam($Campo[27], 2, '');
	
		// Campo 28 (Identif. de título Aceito/Não Aceito)			[1] 109-109      X
		$Campo[28] = 'N';
		
		// Campo 29 (Data da emissão do título)						[8]	110-117      N
		$Campo[29] = dataConv($lin[DataLancamento],'Y-m-d H:i:s','dmY');
		$Campo[29] = preenche_tam($Campo[29], 8, '');
		
		// Campo 30 (Código do juros de mora)						[1] 118-118      N
		$Campo[30] = 2;
		$Campo[30] = preenche_tam($Campo[30], 1, '');
		
		// Campo 31 (Data do juros de mora)							[8] 119-126      N
		$Campo[31] = dataConv($lin[DataVencimento],'Y-m-d H:i:s','dmY');
		$Campo[31] = preenche_tam($Campo[31], 8, '');
		
		// Campo 32 (Valor da mora/dia ou Taxa mensal)				[15] 127-141     N 
		$Campo[32] = $DadosLocalCobranca[PercentualJurosDiarios] * 30;		
		$Campo[32] = number_format($Campo[32], 5, '.', '');	
		$Campo[32] = str_replace(".","",$Campo[32]);
		$Campo[32] = preenche_tam($Campo[32], 15, '');	
				
		// Campo 33 (Código do desconto 1)							[1] 142-142      N
		$Campo[33] = $CodigoDesconto;	
				
		// Campo 34 (Data de desconto 1)							[8] 143-150      N
		$Campo[34] = $DataDesconto;
		$Campo[34] = preenche_tam($Campo[34], 8, '');
		
		// Campo 35 (Valor ou Percentual do desconto concedido)		[15] 151-165     N		
		$Campo[35] = number_format($lin2[ValorDescontoAConceber], 2, '.', '');
		$Campo[35] = str_replace(".","",$Campo[35]);
		$Campo[35] = preenche_tam($Campo[35], 15, '');

		// Campo 36 (Valor do IOF a ser recolhido)					[15] 166-180     N
		$Campo[36] = number_format(0, 2, '.', '');
		$Campo[36] = str_replace(".","",$Campo[36]);
		$Campo[36] = preenche_tam($Campo[36], 15, '');
		
		// Campo 37 (Valor do abatimento)							[15] 181-195     N
		$Campo[37] = number_format(0, 2, '.', '');
		$Campo[37] = str_replace(".","",$Campo[37]);
		$Campo[37] = preenche_tam($Campo[37], 15, '');
		
		// Campo 38 (Identificação do título na empresa)			[25] 196-220     X
		$Campo[38] = $lin[NumeroDocumento];
		$Campo[38] = preenche_tam($Campo[38], 25, '');
		
		// Campo 39 (Codigo para protesto)							[1] 221-221     N
		$Campo[39] = $LocalCobrancaParametro[CodigoProtesto];
		
		// Campo 40 (Numero de dias para protesto)					[2] 222-223     N
		$Campo[40] = $LocalCobrancaParametro[NumeroDiasProtesto];
		$Campo[40] = preenche_tam($Campo[40], 2, '');

		// Campo 41 (Codigo para Baixa/Devolução)					[1] 224-224     N
		$Campo[41] = 2;
		
		// Campo 42 (Reservado (uso Banco))							[1]  225-225     X
		$Campo[42] = preenche_tam("0", 1, 'X');	
		
		// Campo 43 (Numero de dias para Baixa/Devolução)			[2]  226-227     N
		$Campo[43] = "00";
		$Campo[43] = preenche_tam($Campo[43], 2, '');
		
		// Campo 44 (Codigo da moeda)								[2]  228-229     N
		$Campo[44] = "00";
		
		// Campo 45 (Reservado (uso Banco))							[11]  230-240    X
		$Campo[45] = preenche_tam(" ", 11, 'X');
			
		// Salva
		$Linha[$i] = concatVar($Campo);	
		$i++;
	
		$Campo = null;
		
		if($Instrucao == "01"){

			// Registro 2 - Seguimento Q				
			// Campo  1 (Código do Banco na compensação)				[3] 1-3			N
			$Campo[1] = "033";
			
			// Campo  2 (Numero do lote remessa)						[4] 4-7			N		
			$Campo[2] = 1;
			$Campo[2] = preenche_tam($Campo[2], 4, '');
		
			// Campo  3 (Tipo de registro)								[1]   8-8		N
			$Campo[3] = 3;
			
			// Campo  4 (Nº sequencial do registro de lote)				[5]   9-13		N
			$NumeroLoteRemessaSeguimento++;
			$Campo[4] = $NumeroLoteRemessaSeguimento;
			$Campo[4] = preenche_tam($Campo[4], 5, '');
		
			// Campo  5 (Cód. Segmento do registro detalhe)				[1]  14-14		X
			$Campo[5] = "Q";
			
			// Campo  6 (Reservado (uso Banco))							[1]  15-15		X
			$Campo[6] = preenche_tam(" ", 1, 'X');
			
			// Campo  7 (Código de movimento remessa)					[2]  16-17		N
			$Campo[7] = $Instrucao;
			
			// Campo  8 (Tipo de inscrição do sacado)					[1]  18-18		N		
			$Campo[8] = $TipoInscricaoSacado;
			$Campo[8] = preenche_tam($Campo[8], 1, '');
			
			// Campo  9 (Numero de inscrição do sacado)					[15]  19-33		N		
			$Campo[9] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
			$Campo[9] = preenche_tam($Campo[9], 15, '');
			
			// Campo  10 (Nome sacado)									[40]  34-73		X
			$Campo[10] = $NomeSacado;
			$Campo[10] = preenche_tam($Campo[10], 40, 'X');				
			
			// Campo  11 (Endereço sacado)								[40]  74-113	X
			if($lin[Numero] != ''){
				$lin[Endereco] .= ", nº".$lin[Numero];
			}
			if($lin[Complemento] != ''){
				$lin[Endereco] .= ", ".$lin[Complemento];
			}
			$Campo[11] = $lin[Endereco];
			$Campo[11] = preenche_tam($Campo[11], 40, 'X');	
			
			// Campo  12 (Bairro sacado)								[15]  114-128	X		
			$Campo[12] = $lin[Bairro];
			$Campo[12] = preenche_tam($Campo[12], 15, 'X');	

			// Campo  13 (Cep sacado)									[5]  129-133	N
			$Campo[13] = $CepSacado;
			$Campo[13] = preenche_tam($Campo[13], 5, '');	
			
			// Campo  14 (Sufixo do Cep sacado)							[3]  134-136	N
			$Campo[14] = $CepSufixo;
			$Campo[14] = preenche_tam($Campo[14], 3, '');	
			
			// Campo  15 (Cidade do sacado)								[15]  137-151	X
			$Campo[15] = $lin[NomeCidade];
			$Campo[15] = preenche_tam($Campo[15], 15, 'X');	
			
			// Campo  16 (Unidade da federação do sacado)				[2]  152-153	X
			$Campo[16] = $lin[SiglaEstado];
			$Campo[16] = preenche_tam($Campo[16], 2, 'X');	
			
			// Campo  17 (Tipo de inscrição sacador/avalista)			[1]  154-154	N
			if($DadosEmpresa[TipoPessoa] == 1){
				$SacadorAvalista = 0;
			}else{
				$SacadorAvalista = 1;
			}	
			$Campo[17] = $SacadorAvalista;
			$Campo[17] = preenche_tam($Campo[17], 1, '');	
			
			// Campo  18 (Nº de inscrição sacador/avalista)				[15]  155-169	N
			if($SacadorAvalista != 0){
				$Campo[18] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);	
			}else{
				$Campo[18] = 0;
			}		
			$Campo[18] = preenche_tam($Campo[18], 15, '');	
			
			// Campo  19 (Nome do sacador/avalista)						[40]  170-209	X
			if($SacadorAvalista != 0){
				$Campo[19] = $NomeSacado;	
			}else{
				$Campo[19] = "";
			}		
			$Campo[19] = preenche_tam($Campo[19], 40, 'X');	
			
			// Campo  20 (Identificador de carne)						[3]  210-212	N		
			$Campo[20] = "000";
			
			// Campo  21 (Sequencial da Parcela)						[3]  213-215	N
			$Campo[21] = "000";
			
			// Campo  22 (Quantidade total de parcelas)					[3]  216-218	N
			$Campo[22] = "000";
			
			// Campo  23 (Número do plano)								[3]  219-221	N
			$Campo[23] = "000";
			
			// Campo  24 (Reservado (uso Banco))						[19]  222-240	X
			$Campo[24] = preenche_tam($Campo[24], 19, 'X');
								
			// Salva
			$Linha[$i] = concatVar($Campo);
			$i++;
		
			$Campo = null;
			
			// Registro 3 - Seguimento R		
			// Campo  1 (Código do Banco na compensação)				[3] 1-3			N
		/*	$Campo[1] = "033";
			
			// Campo  2 (Numero do lote remessa)						[4] 4-7			N		
			$Campo[2] = 1;
			$Campo[2] = preenche_tam($Campo[2], 4, '');
			
			// Campo  3 (Tipo de registro)								[1]   8-8		N
			$Campo[3] = 3;
			
			// Campo  4 (Nº sequencial do registro de lote)				[5]   9-13		N
			$NumeroLoteRemessaSeguimento++;
			$Campo[4] = $NumeroLoteRemessaSeguimento;
			$Campo[4] = preenche_tam($Campo[4], 5, '');
		
			// Campo  5 (Cód. Segmento do registro detalhe)				[1]  14-14		X
			$Campo[5] = "R";
			
			// Campo  6 (Reservado (uso Banco))							[1]  15-15		X
			$Campo[6] = preenche_tam(" ", 1, 'X');
			
			// Campo  7 (Código de movimento)							[2]  16-17		N		
			$Campo[7] = $Instrucao;
							
			// Campo  8 (Código do desconto 2)							[1]  18-18		N
			$Campo[8] = $CodigoDesconto;

			// Campo  9 (Data  desconto 2)							    [8]  19-26		N
			$Campo[9] = $DataDesconto;		
			$Campo[9] = preenche_tam($Campo[9], 8, '');	
			
			// Campo  10 (Valor/Percentual a ser concedido)				[15]  27-41		N
			$Campo[10] = number_format($lin2[ValorDescontoAConceber], 2, '.', '');		
			$Campo[10] = str_replace(".","",$Campo[10]);
			$Campo[10] = preenche_tam($Campo[10], 15, '');	

			// Campo  11 (Reservado (uso Banco))						[24]  42-65		X
			$Campo[11] = preenche_tam(" ", 24, 'X');
			
			// Campo  12 (Código da multa)								[1]  66-66		N
			$Campo[12] = 2;		
			
			// Campo  13 (Data da Multa)								[8]  67-74		N
			$Campo[13] = dataConv($lin[DataVencimento],'Y-m-d H:i:s','dmY');		
			$Campo[13] = preenche_tam($Campo[13], 8, '');	
			
			// Campo  14 (Valor/Percentual a ser aplicado)				[15]  75-89		N
			$Campo[14] = $DadosLocalCobranca[PercentualMulta];				
			$Campo[14] = number_format($Campo[14], 2, '.', '');			
			$Campo[14] = str_replace(".","",$Campo[14]);		
			$Campo[14] = preenche_tam($Campo[14], 15, '');	  		
			
			// Campo  15 (Reservado (uso Banco))						[10]  90-99		X
			$Campo[15] = preenche_tam(" ", 10, 'X');		
			
			// Campo  16 (Mensagem 3)									[40]  100-139	X
			$Campo[16] = " ";
			$Campo[16] = preenche_tam($Campo[16], 40, 'X');								
			
			// Campo  17 (Mensagem 4)									[40]  140-179	X
			$Campo[17] = " ";
			$Campo[17] = preenche_tam($Campo[17], 40, 'X');								
					
			// Campo  18 (Reservado)									[61]  90-99		X
			$Campo[18] = preenche_tam(" ", 61, 'X');
			
			// Salva
			$Linha[$i] = concatVar($Campo);
			$i++;
		
			$Campo = null;
		*/	
			
			// Registro 4 - Seguimento S		
			// Campo  1 (Código do Banco na compensação)				[3] 1-3			N
			$Campo[1] = "033";
			
			// Campo  2 (Numero do lote remessa)						[4] 4-7			N		
			$Campo[2] = 1;
			$Campo[2] = preenche_tam($Campo[2], 4, '');
			
			// Campo  3 (Tipo de registro)								[1]   8-8		N
			$Campo[3] = 3;
			
			// Campo  4 (Nº sequencial do registro de lote)				[5]   9-13		N
			$NumeroLoteRemessaSeguimento++;
			$Campo[4] = $NumeroLoteRemessaSeguimento;
			$Campo[4] = preenche_tam($Campo[4], 5, '');
		
			// Campo  5 (Cód. Segmento do registro detalhe)				[1]  14-14		X
			$Campo[5] = "S";
			
			// Campo  6 (Reservado (uso Banco))							[1]  15-15		X
			$Campo[6] = preenche_tam(" ", 1, 'X');
			
			// Campo  7 (Código de movimento remessa)					[2]  16-17		N
			$Campo[7] = $Instrucao;
			
			# PARA  TIPO  DE  IMPRESSÃO  1  FORMULARIO ESPECIAL:      (continuação SEGMENTO S)
			
			// Campo  8 (Identificação da impressão) 					[1]  18-18		N
			$Campo[8] = 1;
			$Campo[8] = preenche_tam($Campo[8], 1, '');
			
			// Campo  9 (Número da linha a ser impressa) 				[2]  19-20		N
			$Campo[9] = 1;
			$Campo[9] = preenche_tam($Campo[9], 2, '');
			
			// Campo  10 (Mensagem para recibo do sacado) 				[1]  21-21		N
			$Campo[10] = 4;
			$Campo[10] = preenche_tam($Campo[10], 1, '');
			
			// Campo  11 (Mensagem a ser impressa) 						[1]  22-121		X
			$Campo[11] = preenche_tam(" ", 100, 'X');
					
			// Campo  12 (Reservado (uso do Banco))						[1]  122-240	X
			$Campo[12] = preenche_tam(" ", 119, 'X');	
		

			//Salva
			$Linha[$i] = concatVar($Campo);
			$i++;
			
			$Campo = null;
		}
				
		$QtdContaReceber++;										
	}

###	// Trailer de Lote Remessa
	
	// Campo  1 (Código do Banco na compensação)					[3]  1-3		N
	$Campo[1] = "033";
		
	// Campo  2 (Numero do lote remessa)							[4]  4-7		N
	$Campo[2] = 1;
	$Campo[2] = preenche_tam($Campo[2], 4, '');

	// Campo  3 (Tipo de registro)									[1]  8-8		N
	$Campo[3] = 5;
	
	// Campo  4 (Reservado (uso Banco))								[9]  9-17		X
	$Campo[4] = preenche_tam(" ", 9, 'X');
		
	// Campo  5 (Quantidade de registros do arquivo)				[6]  18-23		N
	$Campo[5] = count($Linha);
	$Campo[5] = preenche_tam($Campo[5], 6, '');

	// Campo  6 (Reservado (uso Banco)								[217]  24-240	X
	$Campo[6] = preenche_tam(" ", 217, 'X');
		
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;	
	
	$Campo = null;
	
###	// Trailler de arquivo remessa
	
	// Campo  1 (Código do Banco na compensação)					[3]  1-3		N
	$Campo[1] = "033";
		
	// Campo  2 (Numero do lote remessa)							[4]  4-7		N
	$Campo[2] = 9999;
	
	// Campo  3 (Tipo de Registro)									[1]  8-8		N
	$Campo[3] = 9;
	
	// Campo  4 (Reservado (uso Banco))								[9]  9-17		X
	$Campo[4] = preenche_tam(" ", 9, 'X');
	
	// Campo  5 (Quantidade de lotes do arquivo)					[6]  18-23		N
	//$Campo[5] = $NumeroLoteRemessa+1;
	$Campo[5] = 1;
	$Campo[5] = preenche_tam($Campo[5], 6, '');

	// Campo  6 (Quantidade de registros do arquivo)				[6]  24-29		N
	$Campo[6] = count($Linha)+1;
	$Campo[6] = preenche_tam($Campo[6], 6, '');

	// Campo  7 (Reservado (uso Banco)								[211]  30-240	X
	$Campo[7] = preenche_tam(" ", 211, 'X');
		
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;	
?>
