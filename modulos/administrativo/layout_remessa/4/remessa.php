<?
	//include("funcao_layout.php");
	
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

	// Campo  1 (Cуdigo do Banco na compensaзгo)				[3]   1-3		N 
	$Campo[1] = "001";								

	// Campo  2 (Lote de servico)								[4]   4-7		N
	$NumeroLoteServico = 0;
	$Campo[2] = $NumeroLoteServico;
	$Campo[2] = preenche_tam($Campo[2], 4, '');

	// Campo  3 (Tipo de Registro)								[1]   8-8		N 
	$Campo[3] = 1;	

	// Campo  4 (Tipo de Operaзгo)								[1]   9-9		X R -> Arquivo de Remessa
	$Campo[4] = preenche_tam("R", 1, 'X');

	// Campo  5 (Tipo de Serviзo)								[2]  10-11		N 
	$Campo[5] = preenche_tam("01", 2, '');

	// Campo  6 (Uso Exclusivo FEBRABAN/CNAB)					[2]  12-13		X
	$Campo[6] = preenche_tam(" ", 2, 'X');	
	
	// Campo  7 (Nє da Versгo do Layout do Lote)				[3]  14-16		N		
	$Campo[7] = "041";
	$Campo[7] = preenche_tam($Campo[7], 3, '');

	// Campo  8 (Uso Exclusivo FEBRABAN/CNAB)					[1]  17-17		X
	$Campo[8] =	preenche_tam(" ", 1, 'X');
	
	// Campo  9 (Tipo de Inscriзгo da Empresa)					[1]  18-18		N 
	if($DadosEmpresa[TipoPessoa] == 1){
		$Campo[9] = 2;
	}else{
		$Campo[9] = 1;
	}
	$Campo[9] = preenche_tam($Campo[9], 1, '');

	// Campo  10 (Nє de Inscriзгo da Empresa)					[15]  19-33     N 
	$Campo[10] = removeMascaraCPF_CNPJ($DadosEmpresa[CPF_CNPJ]);
	$Campo[10] = preenche_tam($Campo[10], 15, '');

	// Campo 11 (Cуdigo do Convкnio no Banco)					[20]  34-53		X 
	$Campo[11] = $LocalCobrancaParametro[Convenio];
	$Campo[11] = preenche_tam($Campo[11], 20, 'X');
	
	// Campo 12 (Agкncia Mantenedora da Conta)					[5]  54-58		N 
	$Campo[12] = $LocalCobrancaParametro[Agencia];
	$Campo[12] = preenche_tam($Campo[12], 5, '');

	// Campo 13 (Dнgito Verificador da Conta)					[1]	 59-59 		X 
	$Campo[13] = $LocalCobrancaParametro[DigitoAgencia];
	$Campo[13] = preenche_tam($Campo[13], 1, 'X');

	// Campo 14 (Nъmero da Conta Corrente)						[12] 60-71  	N 
	$Campo[14] = $LocalCobrancaParametro[Conta];
	$Campo[14] = preenche_tam($Campo[14], 12, '');

	// Campo 15 (Dнgito Verificador da Conta)					[1]  72-72 		X  
	if(count($LocalCobrancaParametro[DigitoConta]) > 1){
		$LocalCobrancaParametro[DigitoConta] = $LocalCobrancaParametro[DigitoConta][0];
	}
	$Campo[15] = $LocalCobrancaParametro[DigitoConta];
	$Campo[15] = preenche_tam($Campo[15], 1, 'X');
		
	// Campo 16 (Dнgito Verificador da Ag/Conta)				[1]	 73-73		X  
	$Campo[16] = $LocalCobrancaParametro[DigitoAgencia];
	$Campo[16] = preenche_tam($Campo[16], 1, 'X');
	
	// Campo 17 (Nome da Empresa)								[30] 74-103		X 
	$Campo[17] = $DadosEmpresa[RazaoSocial];
	$Campo[17] = preenche_tam($Campo[17], 30, 'X');
	
	// Campo 18 (Mensagem 1)									[40]  104-143   X 
	$Campo[18] = " ";
	$Campo[18] = preenche_tam($Campo[18], 40, 'X');
	
	// Campo 19 (Mensagem 2)									[40]  144-183   X 
	$Campo[19] = " ";
	$Campo[19] = preenche_tam($Campo[19], 40, 'X');
	
	// Campo 20 (Nъmero Remessa/Retorno)						[8]  184-191    N 
	$Campo[20] = $NumSeqArquivo;
	$Campo[20] = preenche_tam($Campo[20], 8, '');

	// Campo 21 (Data de Gravaзгo Remessa/Retorno)				[8]  192-199    N 
	$Campo[21] = Date("dmY");
	$Campo[21] = preenche_tam($Campo[21], 8, '');
	
	// Campo 22 (Data do Crйdito)								[8]  200-207    N 	
	$Campo[22] = "0";
	$Campo[22] = preenche_tam($Campo[22], 8, '');

	// Campo 23 (Uso Exclusivo FEBRABAN/CNAB)					[33]  200-207    X 
	$Campo[23] = " ";
	$Campo[23] = preenche_tam($Campo[23], 33, 'X');

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;

		
### // Registro Detalhe
	$NumeroLoteRemessaSeguimento = 0;
	$ValorTotalTitulos			 = 0;
	$QtdContaReceber 			 = 0;
	
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
			$TipoInscricaoSacado = 2;
			$NomeSacado = $lin[RazaoSocial];
		}else{
			$TipoInscricaoSacado = 1;
			$NomeSacado = $lin[Nome];
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

				$Instrucao = "19"; // Pedido sustaзгo de protesto
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

				$Instrucao = "06"; // Alteraзгo de vencimento
				break;
			default:
				$local_transaction[$tr_i] = false;
				$tr_i++;
				break;
		}		
		
		$CEP = explode("-",$lin[CEP]);
		$CepSacado = $CEP[0];
		$CepSufixo = $CEP[1];
		
		// Registro seguimento P

		// Campo  1 (Cуdigo do Banco na compensaзгo)				[3]   1-3		N 
		$Campo[1] = "001";
	
		// Campo  2 (Lote de Serviзo)								[4]   4-7		N	
		$NumeroLoteRemessa++;
		$Campo[2] = $NumeroLoteRemessa;
		$Campo[2] = preenche_tam($Campo[2], 4, '');
	
		// Campo  3 (Tipo de Registro)								[1]   8-8		N
		$Campo[3] = 3;	
	
		// Campo  4 (Nє Sequencial do Registro no Lote)				[5]   9-13		N
		$Campo[4] = $NumeroLoteRemessaSeguimento;
		$Campo[4] = preenche_tam($Campo[4], 5, '');
				
		// Campo  5 (Cуd. Segmento do Registro Detalhe)				[1]   14-14		X		
		$Campo[5] = "P";
		$Campo[5] = preenche_tam($Campo[5], 1, 'X');	
		
		// Campo  6 (Uso Exclusivo FEBRABAN/CNAB)					[1]   15-15		X 	
		$Campo[6] = preenche_tam(" ", 1, 'X');
	
		// Campo  7 (Cуdigo de Movimento Remessa)					[2]   16-17		N 
		$Campo[7] = "01";
		$Campo[7] = preenche_tam($Campo[7], 2, '');
	
		// Campo  8 (Agкncia Mantenedora da Conta)					[5]   18-22		N Verificar
		$Campo[8] = $LocalCobrancaParametro[Agencia];
		$Campo[8] = preenche_tam($Campo[8], 5, '');
		
		// Campo  9 (Dнgito Verificador da Agкncia)					[1]   23-23		X Verificar
		$Campo[9] = $LocalCobrancaParametro[DigitoAgencia];	
		$Campo[9] = preenche_tam($Campo[9], 1, 'X');
		
		// Campo  10 (Nъmero da Conta Corrente)						[12]   24-35	N Verificar
		$Campo[10] = $LocalCobrancaParametro[Conta];
		$Campo[10] = preenche_tam($Campo[10], 12, '');
		
		// Campo  11 (Dнgito Verificador da Conta)					[1]   36-36	    X Verificar
		$Campo[11] = $LocalCobrancaParametro[DigitoConta];
		$Campo[11] = preenche_tam($Campo[11], 1, 'X');
	
		// Campo  12 (Dнgito Verificador da Ag/Conta)				[1]   37-37		X Verificar
		if(strlen($LocalCobrancaParametro[DigitoConta]) > 1){
			$Campo[12] = $LocalCobrancaParametro[DigitoConta][1];
		}else{
			$Campo[12] = $LocalCobrancaParametro[DigitoConta];	
		}
		$Campo[12] = preenche_tam($Campo[12], 1, 'X');

		// Campo  13 ((Nosso nє) Identificaзгo do Tнtulo no Banco)  [20]   38-57	X Verificar
		$Campo[13] = preenche_tam("0", 20, 'X');
		
		// Campo  14 (Cуdigo da Carteira)							[1]   58-58		X 
		$Campo[14] = "1";
		$Campo[14] = preenche_tam($Campo[14], 1, 'X');
	
		// Campo  15 (Forma de Cadastr. do Tнtulo no Banco)			[1]   59-59		X 
		$Campo[15] = "1";	
		$Campo[15] = preenche_tam($Campo[15], 1, 'X');
	
		// Campo  16 (Tipo de Documento)							[1]  60-60		X 
		$Campo[16] = "1";
		$Campo[16] = preenche_tam($Campo[16], 1, 'X');
	
		// Campo  17 (Identificaзгo da Emissгo do Bloqueto)			[1]   61-61		N Verificar
		$Campo[17] = $LocalCobrancaParametro[IdentificacaoEmissaoBloqueto];
		$Campo[17] = preenche_tam($Campo[17], 1, '');
	
		// Campo  18 (Identificaзгo da Distribuiзгo)				[1]   62-62		X Verificar
		if($LocalCobrancaParametro[IdentificacaoEmissaoBloqueto] > 2){
			$Campo[18] = 0;
		}else{
			$Campo[18] = $LocalCobrancaParametro[IdentificacaoEmissaoBloqueto];
		}		
		$Campo[18] = preenche_tam($Campo[18], 1, 'X');

		// Campo  19 (Nъmero do Documento de Cobranзa)				[15]  63-77		X Verificar
		$Campo[19] = $lin[NumeroDocumento];
		$Campo[19] = preenche_tam($Campo[19], 15, 'X');
		
		// Campo  20 (Data de Vencimento do Tнtulo)					[8]  78-85		N Verificar
		$Campo[20] = dataConv($lin[DataVencimento],'Y-m-d H:i:s','dmY');
		$Campo[20] = preenche_tam($Campo[20], 8, '');	
		
		// Campo  21 (Valor Nominal do Tнtulo)						[15] 86-100		N Verificar
		$Campo[21] = number_format($lin[ValorFinal], 2, '.', '');
		$Campo[21] = str_replace(".","",$Campo[21]);		
		$Campo[21] = preenche_tam($Campo[21], 15, '');
			
		// Campo 22 (Agкncia Encarregada da Cobranзa)				[5]	 101-105    N Verificar
		$Campo[22] = $LocalCobrancaParametro[Agencia];
		$Campo[22] = preenche_tam($Campo[22], 5, '');

		// Campo 23 (Dнgito Verificador da Agкncia)					[1] 106-106     X Verificar
		$Campo[23] = $LocalCobrancaParametro[DigitoAgencia];
		$Campo[23] = preenche_tam($Campo[23], 1, 'X');

		// Campo 24 (Espйcie do Tнtulo)								[2] 107-108     N
		$Campo[24] = "02";
		$Campo[24] = preenche_tam($Campo[24], 2, '');
		
		// Campo 25 (Identific. de Tнtulo Aceito/Nгo Aceito)		[1] 109-109     X Verificar
		$Campo[25] = $LocalCobrancaParametro[Aceite];
		$Campo[25] = preenche_tam($Campo[25], 1, 'X');
		
		// Campo 26 (Data da Emissгo do Tнtulo)						[8]  110-117    N Verificar
		$Campo[26] = dataConv($lin[DataLancamento],'Y-m-d H:i:s','dmY');
		$Campo[26] = preenche_tam($Campo[26], 8, '');	
		
		// Campo 27 (Cуdigo do Juros de Mora)						[1] 118-118     N 
		$Campo[27] = "1";
		$Campo[27] = preenche_tam($Campo[27], 1, '');
		
		// Campo 28 (Data do Juros de Mora)							[8] 119-126     N Verificar
		$Campo[28] = dataConv($lin[DataLancamento],'Y-m-d H:i:s','dmY');
		$Campo[28] = preenche_tam($Campo[28], 8, '');
		
		// Campo 29 (Juros de Mora por Dia/Taxa)					[15] 127-141    N Verificar
		$Campo[29] = $lin[ValorFinal]*$DadosLocalCobranca[PercentualJurosDiarios]/100;
		$Campo[29] = number_format($Campo[29], 2, '.', '');	
		$Campo[29] = str_replace(".","",$Campo[29]);
		$Campo[29] = preenche_tam($Campo[29], 15, '');
		
		// Campo 30 (Cуdigo do Desconto 1)							[1] 142-142     N 
		$Campo[30] = $CodigoDesconto;
		$Campo[30] = preenche_tam($Campo[30], 1, '');
		
		// Campo 31 (Data do Desconto 1)							[8] 143-150     N Verificar
		$Campo[31] = $DataDesconto;
		$Campo[31] = preenche_tam($Campo[31], 8, '');
		
		// Campo 32 (Valor/Percentual a ser Concedido)				[13] 151-165    N Verificar
		$Campo[32] = number_format($lin2[ValorDescontoAConceber], 2, '.', '');
		$Campo[32] = str_replace(".","",$Campo[32]);
		$Campo[32] = preenche_tam($Campo[32], 15, '');				
		
		// Campo 33 (Valor do IOF a ser Recolhido)					[15] 166-180    N 
		$Campo[33] = number_format(0, 2, '.', '');
		$Campo[33] = str_replace(".","",$Campo[33]);	
		$Campo[33] = preenche_tam($Campo[33], 15, '');				
		
		// Campo 34 (Valor do Abatimento)							[15] 181-195    N 
		$Campo[34] = number_format(0, 2, '.', '');
		$Campo[34] = str_replace(".","",$Campo[34]);
		$Campo[34] = preenche_tam($Campo[34], 15, '');
		
		// Campo 35 (Identificaзгo do Tнtulo na Empresa)			[25] 196-220    X Verificar
		$Campo[35] = $lin[NumeroDocumento];
		$Campo[35] = preenche_tam($Campo[35], 25, 'X');
		
		// Campo 36 (Cуdigo para Protesto)							[1] 221-221     N Verificar
		$Campo[36] = "3";
		$Campo[36] = preenche_tam($Campo[36], 1, '');
		
		// Campo 37 (Nъmero de Dias para Protesto)					[2] 222-223     N Verificar
		$Campo[37] = "0";
		$Campo[37] = preenche_tam($Campo[37], 2, '');
		
		// Campo 38 (Cуdigo para Baixa/Devoluзгo)					[1] 224-224     N Verificar
		$Campo[38] = "1";
		$Campo[38] = preenche_tam($Campo[38], 1, '');
		
		// Campo 39 (Nъmero de Dias para Baixa/Devoluзгo)			[3] 225-227     X Verificar
		$Campo[39] = "1";
		$Campo[39] = preenche_tam($Campo[39], 3, 'X');
		
		// Campo 40 (Cуdigo da Moeda)								[2] 228-229     N 
		$Campo[40] = "09";
		$Campo[40] = preenche_tam($Campo[40], 2, '');
		
		// Campo 41 (Nє do Contrato da Operaзгo de Crйd.)			[10] 230-239    N // verificar
		$Campo[41] = $LocalCobrancaParametro[Contrato];
		$Campo[41] = preenche_tam($Campo[41], 10, '');

		//////////////////////////////////////////////////////////////////////////////
		// Campo 42 (Uso Exclusivo FEBRABAN/CNAB)					[1]  240-240    X
		$Campo[42] = preenche_tam(" ", 1, 'X');			
			
		// Salva
		$Linha[$i] = concatVar($Campo);	
		$i++;
	
		$Campo = null;

		// Registro Detalhe  - Seguimento Q				
		// Campo  1 (Cуdigo do Banco na compensaзгo)				[3] 1-3			N 
		$Campo[1] = "001";
		
		// Campo  2 (Lote de Servico)								[4] 4-7			N 
		$NumeroLoteRemessa++;
		$Campo[2] = $NumeroLoteRemessa;
		$Campo[2] = preenche_tam($Campo[2], 4, '');
	
		// Campo  3 (Tipo de registro)								[1]   8-8		N
		$Campo[3] = 3;
	
		// Campo  4 (Nє sequencial do registro de lote)				[5]   9-13		N
		$NumeroLoteRemessaSeguimento++;
		$Campo[4] = $NumeroLoteRemessaSeguimento;
		$Campo[4] = preenche_tam($Campo[4], 5, '');
		
		// Campo  5 (Cуd. Segmento do registro detalhe)				[1]  14-14		X
		$Campo[5] = "Q";
		
		// Campo  6 (Uso Exclusivo FEBRABAN/CNAB)					[1]  15-15		X
		$Campo[6] = preenche_tam(" ", 1, 'X');
	
		// Campo  7 (Cуdigo de movimento remessa)					[2]  16-17		N 
		$Campo[7] = "01";
			
		// Campo  8 (Tipo de Inscriзгo)								[1]  18-18		N		
		$Campo[8] = $TipoInscricaoSacado;
		$Campo[8] = preenche_tam($Campo[8], 1, '');
		
		// Campo  9 (Numero de inscriзгo do sacado)					[15]  19-33		N		
		$Campo[9] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[9] = preenche_tam($Campo[9], 15, '');
		
		// Campo  10 (Nome)											[40]  34-73		X
		$Campo[10] = $NomeSacado;
		$Campo[10] = preenche_tam($Campo[10], 40, 'X');				
		
		// Campo  11 (Endereзo sacado)								[40]  74-113	X Verificar
		$Campo[11] = $lin[Endereco];
		$Campo[11] = preenche_tam($Campo[11], 40, 'X');	
		
		// Campo  12 (Bairro sacado)								[15]  114-128	X		
		$Campo[12] = $lin[Bairro];
		$Campo[12] = preenche_tam($Campo[12], 15, 'X');	

		// Campo  13 (Cep)											[5]  129-133	N
		$Campo[13] = $CepSacado;
		$Campo[13] = preenche_tam($Campo[13], 5, '');	
		
		// Campo  14 (Sufixo do Cep)								[3]  134-136	N
		$Campo[14] = $CepSufixo;
		$Campo[14] = preenche_tam($Campo[14], 3, '');	
	
		// Campo  15 (Cidade do sacado)								[15]  137-151	X
		$Campo[15] = $lin[NomeCidade];
		$Campo[15] = preenche_tam($Campo[15], 15, 'X');	
			
		// Campo  16 (Unidade da federaзгo)							[2]  152-153	X
		$Campo[16] = $lin[SiglaEstado];
		$Campo[16] = preenche_tam($Campo[16], 2, 'X');	
		
		// Campo  17 (Tipo de inscriзгo)							[1]  154-154	N
		if($DadosEmpresa[TipoPessoa] == 1){
			$SacadorAvalista = 0;
		}else{
			$SacadorAvalista = 1;
		}	
		$Campo[17] = $SacadorAvalista;
		$Campo[17] = preenche_tam($Campo[17], 1, '');	
	
		// Campo  18 (Nє de inscriзгo)								[15]  155-169	N
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

		// Campo  20 (Cуd. Bco. Corresp. na Compensaзгo)			[3]  210-212	N		
		$Campo[20] = "001";
			
		// Campo  21 (Nosso Nє no Banco Correspondente)				[20]  213-232	X   verificar
		$Campo[21] = 0;	
		$Campo[21] = preenche_tam($Campo[21], 20, 'X');
		
		// Campo  22 Uso Exclusivo FEBRABAN/CNAB)					[8]  233-240	X
		$Campo[22] = preenche_tam($Campo[22], 8, 'X');
							
		// Salva
		$Linha[$i] = concatVar($Campo);
		$i++;
	
		$Campo = null;
		
		
		// Registro 4 - Seguimento S		
		// Campo  1 (Cуdigo do Banco na compensaзгo)				[3] 1-3			N   
		$Campo[1] = "001";
		
		// Campo  2 (Numero do lote remessa)						[4] 4-7			N		
		$NumeroLoteRemessa++;
		$Campo[2] = $NumeroLoteRemessa;
		$Campo[2] = preenche_tam($Campo[2], 4, '');
		
		// Campo  3 (Tipo de registro)								[1]   8-8		N
		$Campo[3] = 3;
	
		// Campo  4 (Nє sequencial do registro de lote)				[5]   9-13		N
		$NumeroLoteRemessaSeguimento++;
		$Campo[4] = $NumeroLoteRemessaSeguimento;
		$Campo[4] = preenche_tam($Campo[4], 5, '');
		
		// Campo  5 (Cуd. Segmento do registro detalhe)				[1]  14-14		X
		$Campo[5] = "S";
		
		// Campo  6 (Reservado (uso Banco))							[1]  15-15		X
		$Campo[6] = preenche_tam(" ", 1, 'X');
		
		// Campo  7 (Cуdigo de movimento remessa)					[2]  16-17		N
		$Campo[7] = "01";
		
		# PARA  TIPO  DE  IMPRESSГO  1  FORMULARIO ESPECIAL:      (continuaзгo SEGMENTO S)
		
		// Campo  8 (Identificaзгo da impressгo) 					[1]  18-18		N
		$Campo[8] = 1;
		$Campo[8] = preenche_tam($Campo[8], 1, '');
		
		// Campo  9 (Nъmero da linha a ser impressa) 				[2]  19-20		N
		$Campo[9] = 1;
		$Campo[9] = preenche_tam($Campo[9], 2, '');
		
		// Campo  10 (Mensagem a ser impressa) 						[140]  21-160	X
		$Campo[10] = preenche_tam("", 140, 'X');

		// Campo  11 (Tipo do Caracter a ser Impresso) 				[2]  161-162	N
		$Campo[11] = preenche_tam("01", 2, '');
						
		// Campo  12 (Reservado (uso do Banco))						[78]  163-240	X
		$Campo[12] = preenche_tam(" ", 78, 'X');	

		
		
		# PARA  Para Tipo de Impressгo 3:		 (continuaзгo SEGMENTO S)
		
		// Campo  8 (Identificaзгo da impressгo) 					[1]  18-18		N
		$Campo[8] = 3;
		$Campo[8] = preenche_tam($Campo[8], 1, '');
		
		// Campo  9 (Mensagem 5) 									[40]  19-58		X
		$Campo[9] = $LocalCobrancaParametro[Instrucoes0];
		$Campo[9] = preenche_tam($Campo[9], 40, '');

		// Campo  10 (Mensagem 6) 									[40]  59-98		X
		$Campo[10] = $LocalCobrancaParametro[Instrucoes1];
		$Campo[10] = preenche_tam($Campo[10], 40, '');
	
		// Campo  11 (Mensagem 7) 									[40]  99-138	X
		$Campo[11] = $LocalCobrancaParametro[Instrucoes2];
		$Campo[11] = preenche_tam($Campo[11], 40, '');

		// Campo  12 (Mensagem 8) 									[40]  139-178	X
		$Campo[12] = $LocalCobrancaParametro[Instrucoes3];
		$Campo[12] = preenche_tam($Campo[12], 40, '');

		// Campo  13 (Mensagem 9) 									[2]  179-218	X
		$Campo[13] = $LocalCobrancaParametro[Instrucoes4];
		$Campo[13] = preenche_tam($Campo[13], 40, '');

		// Campo  14 (Uso Exclusivo FEBRABAN/CNAB)					[22]  219-240	X
		$Campo[14] = preenche_tam(" ", 22, 'X');		
		
		
		//Salva
		$Linha[$i] = concatVar($Campo);
		$i++;
		
		$Campo = null;
				
		$QtdContaReceber++;		
		$ValorTotalTitulos += $lin[ValorFinal];			
	}

###	// Trailer de Lote Remessa
	
	// Campo  1 (Cуdigo do Banco na compensaзгo)					[3]  1-3		N
	$Campo[1] = "001";
		
	// Campo  2 (Lote de Serviзo)									[4]  4-7		N
	$NumeroLoteRemessa++;
	$Campo[2] = $NumeroLoteRemessa;
	$Campo[2] = preenche_tam($Campo[2], 4, '');

	// Campo  3 (Tipo de Registro)									[1]  8-8		N
	$Campo[3] = 5;
	
	// Campo  4 (Uso Exclusivo FEBRABAN/CNAB)						[9]  9-17		X
	$Campo[4] = preenche_tam(" ", 9, 'X');
	
	// Campo  5 (Quantidade de Registros no Lote)					[6]  18-23		N
	$Campo[5] = count($Linha);
	$Campo[5] = preenche_tam($Campo[5], 6, '');
	
	#Totalizaзгo da Cobranзa Simples
	// Campo  6 (Quantidade de Tнtulos em Cobranзa)					[6]  24-29		N 
	$Campo[6] = $QtdContaReceber;
	$Campo[6] = preenche_tam($Campo[6], 6, '');
	
	// Campo  7 (Valor Total dos Tнtulos em Carteiras)				[17]  30-46		N 
	$Campo[7] = number_format($ValorTotalTitulos, 2, '.', '');
	$Campo[7] = str_replace(".","",$Campo[7]);	
	$Campo[7] = preenche_tam($Campo[7], 17, '');

	#Totalizaзгo da Cobranзa Vinculada
	// Campo  8 (Quantidade de Tнtulos em Cobranзa)					[6]  47-52		N 
	$Campo[8] = "0";
	$Campo[8] = preenche_tam($Campo[8], 6, '');
	
	// Campo  9 (Valor Total dos Tнtulos em Carteiras)				[17]  53-69		N 
	$Campo[9] = "0";
	$Campo[9] = preenche_tam($Campo[9], 17, '');
	
	#Totalizaзгo da Cobranзa Caucionada		
	// Campo  10 Quantidade de Tнtulos em Cobranзa					[6]  70-75		N 
	$Campo[10] = "0";
	$Campo[10] = preenche_tam($Campo[10], 6, '');
	
	// Campo 11 Quantidade de Tнtulos em Carteiras					[17]  76-92		N 
	$Campo[11] = "0";
	$Campo[11] = preenche_tam($Campo[11], 17, '');
	
	#Totalizaзгo da Cobranзa Descontada		
	// Campo  12 Quantidade de Tнtulos em Cobranзa					[6]  93-98		N 
	$Campo[12] = "0";
	$Campo[12] = preenche_tam($Campo[12], 6, '');
	
	// Campo  13 Quantidade de Tнtulos em Carteiras					[17]  99-115	N 
	$Campo[13] = "0";
	$Campo[13] = preenche_tam($Campo[13], 17, '');
	
	// Campo  14 Nъmero do Aviso de Lanзamento						[8]  116-123	X 
	$Campo[14] = "0";
	$Campo[14] = preenche_tam($Campo[14], 8, 'X');
		
	// Campo  15 (Reservado (uso Banco))							[117] 124-240	X 
	$Campo[15] = preenche_tam(" ", 117, 'X');
		
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;	
	
	$Campo = null;
?>