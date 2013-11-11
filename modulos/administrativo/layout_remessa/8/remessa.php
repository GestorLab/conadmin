<?	
	include("funcao_layout.php");
	// Gera a nomenclatura do arquivo	

	$Patch = "remessa/local_cobranca/$local_IdLoja/$local_IdLocalCobranca/$local_IdArquivoRemessa";	
	
	$NomeArquivo = "ECX".$ParametroLocalCobranca['Convenio'].codigoMesDia().".TXT";

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

#D�bito Autom�tico - Vers�o 05 - REMESSA - Vrs. 2.0 150 posi��es

### // Registro Header de Lote

	// Campo  1 ("C�digo do Registro")							[1]   1-1		X 
	$Campo[1] = "A";								

	// Campo  2 (C�digo de Remessa)								[1]   2-2		N 
	$Campo[2] = 1;
	$Campo[2] = preenche_tam($Campo[2], 1, '');

	// Campo  3 (C�digo do Conv�nio)							[20]  3-22 		X 
	$Campo[3] = $ParametroLocalCobranca['Convenio'];	
	$Campo[3] = preenche_tam($Campo[3], 20, 'X');
	
	// Campo  4 (Nome da Empresa)								[20]  23-42		X 
	$Campo[4] = $DadosEmpresa[Nome];
	$Campo[4] = preenche_tam($Campo[4], 20, 'X');

	// Campo  5 (C�digo do Banco)								[3]  43-45		N 
	$Campo[5] = "748";
	$Campo[5] = preenche_tam($Campo[5], 3, '');

	// Campo  6 (Nome do Banco)									[20] 46-65		X 
	$Campo[6] = "SICREDI";
	$Campo[6] = preenche_tam($Campo[6], 20, 'X');	

	// Campo  7 (Data de Gera��o)								[8]  66-73		N 
	$Campo[7] = date("Ymd");
	$Campo[7] = preenche_tam($Campo[7], 8, '');
		
	// Campo  8 (N�mero Seq�encial do Arquivo (NSA))			[6]  74-79		N 
	$Campo[8] = $NumSeqArquivo;
	$Campo[8] =	preenche_tam($Campo[8], 6, '');
	
	// Campo  9 (Vers�o do Lay-out)								[2]  80-81		N
	$Campo[9] = "05";
	$Campo[9] = preenche_tam($Campo[9], 2, '');
	
	// Campo  10 (Identifica��o do Servi�o)						[17]  82-98     X
	$Campo[10] = "DEBITO AUTOMATICO";
	$Campo[10] = preenche_tam($Campo[10], 17, 'X');
	
	// Campo 11 (Reservado para o futuro)						[47]  99-150	X 
	$Campo[11] = preenche_tam(' ', 52, 'X');
		
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
				Pessoa.IdPessoa,
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
				PessoaEndereco.CEP,
				PessoaContaDebito.IdContaDebito,
				PessoaContaDebito.NumeroAgencia,
				PessoaContaDebito.DigitoAgencia,
				PessoaContaDebito.NumeroConta,
				PessoaContaDebito.DigitoConta,
				LancamentoFinanceiroDados.IdContrato,
				ContaReceberPosicaoCobranca.IdPosicaoCobranca
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
				LancamentoFinanceiroDados,
				Pessoa,
				PessoaEndereco,
				PessoaContaDebito,
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

				ContaReceberDados.IdLoja = PessoaContaDebito.IdLoja and
				ContaReceberDados.IdLoja = LancamentoFinanceiroDados.IdLoja and
				ContaReceberDados.IdLoja = ContaReceberPosicaoCobranca.IdLoja and
			
				ContaReceberDados.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber and
				ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Estado.IdPais and
				PessoaEndereco.IdPais = Cidade.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade and
				Pessoa.IdPessoa = PessoaContaDebito.IdPessoa and
				ContaReceberDados.IdContaDebito = PessoaContaDebito.IdContaDebito and
				ContaReceberDados.IdLocalCobranca = PessoaContaDebito.IdLocalCobranca and
				ContaReceberDados.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber and	
				PessoaContaDebito.IdStatus = 1
			group by
				ContaReceberDados.IdLoja,
				ContaReceberDados.IdContaReceber";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		#Se as informa��es do Cliente for vazia, n�o deve gerar a remessa para o conta receber.
		
		if($lin[NumeroAgencia] != '' && $lin[NumeroConta] != '' && $lin[DigitoConta] != ''){

			switch($lin[IdPosicaoCobranca]){
				case 1:
					$CodigoMovimento = 0; //Cadastro de D�bito 
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
					break;
				case 5:
					$CodigoMovimento = 1; //Cancelamento de D�bito	
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
					break;			
			}
			
			### Registro E 

			// Campo  1 (C�digo do Registro)							[1]   1-1		X 
			$Campo[1] = "E";
		
			// Campo  2 (Identifica��o do Cliente na Empresa)			[25]  2-26		N  
			$lin[NumeroIdentificacao]  = str_pad($local_IdLoja, 3, 0, STR_PAD_LEFT);
			$lin[NumeroIdentificacao] .= str_pad($lin[IdPessoa], 11, 0, STR_PAD_LEFT);
			$lin[NumeroIdentificacao] .= str_pad($lin[IdContaDebito], 11, 0, STR_PAD_LEFT);
			$Campo[2] = $lin[NumeroIdentificacao];	
			$Campo[2] = preenche_tam($Campo[2], 25, '');
				
			// Campo  3 (Ag�ncia para D�bito)							[4]   27-30		X
			$Campo[3] = $lin[NumeroAgencia];	
			$Campo[3] = preenche_tam($Campo[3], 4, 'X');
				
			// Campo  4 (Identifica��o do Cliente no Banco)				[14]  31-44		X 
			$Campo[4] = $lin[NumeroConta].$lin[DigitoConta];		
			$Campo[4] = preenche_tam($Campo[4], 14, 'X');
							
			// Campo  5 (Data do Vencimento)							[8]   45-52		N 
			$Campo[5] = dataConv($lin[DataVencimento],'Y-m-d','Ymd');
			$Campo[5] = preenche_tam($Campo[5], 8, '');	
			
			// Campo  6 (Valor do D�bito)								[15]  53-67		N 
			$Campo[6] = number_format($lin[ValorFinal], 2, '.', '');			
			$Campo[6] = str_replace(".","",$Campo[6]);
			$Campo[6] = preenche_tam($Campo[6], 15, '');		
		
			// Campo  7 (C�digo da moeda)								[2]   68-69		X 
			$Campo[7] = "03";
			$Campo[7] = preenche_tam($Campo[7], 2, 'X');		
						
			// Campo  8 (N�mero do documento)							[15]  70-85		N gerado com posi��es do uso da empresa
			$Campo[8] = $lin[NumeroDocumento];
			$Campo[8] = preenche_tam($Campo[8], 15, '');
						
			// Campo  9 (Uso da Empresa)								[44]  86-128	X 
			$Campo[9] = preenche_tam(" ", 44, 'X');								
			
			// Campo  10 (Uso da Empresa (verificar manual))			[1]  129-129    X 
			$Campo[10] = preenche_tam(" ", 1, 'X');
		
			// Campo  11 (Tipo de identifica��o)						[1]  130-130	N 
			$Campo[11] = $lin[TipoPessoa];
			$Campo[11] = preenche_tam($Campo[11], 1, '');

			// Campo  12 (Identifica��o)								[15] 131-145	N 
			$Campo[12] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);	
			$Campo[12] = preenche_tam($Campo[12], 15, '');

			// Campo  13 (Reservado para o futuro)						[4] 146-149		X 
			$Campo[13] = " ";	
			$Campo[13] = preenche_tam($Campo[13], 4, 'X');

			// Campo  14 (Codigo do movimento)							[1] 150-150		N 
			$Campo[14] = $CodigoMovimento;	
			$Campo[14] = preenche_tam($Campo[14], 1, '');
		
			// Salva
			$Linha[$i] = concatVar($Campo);			
			$i++;
		
			$Campo = null;

			$QtdContaReceber++;		
			$ValorTotalTitulos += $lin[ValorFinal];		
		}
	}

	### Registro Z - Trailler			
	
	// Campo  1 (C�digo do Registro)							[1]   1-1		X
	$Campo[1] = "Z";
	
	// Campo  2 (Total de registros do arquivo)					[6]   2-7		N 
	$Campo[2] = $QtdContaReceber +=2;
	$Campo[2] = preenche_tam($Campo[2], 6, '');

	// Campo  3 (Valor total dos registros do arquivo)			[17]  8-24		N 
	$Campo[3] = number_format($ValorTotalTitulos, 2, '.', '');
	$Campo[3] = str_replace(".","",$Campo[3]);
	$Campo[3] = preenche_tam($Campo[3], 17, '');	

	// Campo  4 (Reservado para o futuro)						[126] 25-150	X 		
	$Campo[4] = preenche_tam(" ", 126, 'X');
								
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;			
?>
