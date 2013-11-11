<?
	include("funcao_layout.php");
	// Gera a nomenclatura do arquivo
	$Patch = "remessa/local_cobranca/$local_IdLoja/$local_IdLocalCobranca/$local_IdArquivoRemessa";	

	$NomeArquivo = "REM".str_pad($local_IdArquivoRemessa, 5, "0", STR_PAD_LEFT)."00".".RES";
	
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
			$NomeArquivo = "REM".str_pad($local_IdArquivoRemessa, 5, "0", STR_PAD_LEFT).codigoMesDia().str_pad($lin[Qtd], 2, "0", STR_PAD_LEFT).".RES";		
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

	$i				 = 0;
	$QtdContaReceber = 0;

### // Registro header

	// Campo  1 (TIPO DE REGISTRO)								[2]   1-2		N
	$Campo[1] = preenche_tam("00", 2, '');

	// Campo  2 (DATA DE GERAÇÃO DO LOTE)						[8]   3-10		N
	$Campo[2] = Date("dmY");
	$Campo[2] = preenche_tam($Campo[2], 8, '');

	// Campo  3 (NÚMERO DO RESUMO DE OPERAÇÕES (RO))			[7]   11-17		N // Número sequencial de escolha da empresa
	$Campo[3] = $local_IdArquivoRemessa;
	$Campo[3] = preenche_tam($Campo[3], 7, '');	

	// Campo  4 (RESERVADO)										[10]   18-27	X
	$Campo[4] = "";
	$Campo[4] = preenche_tam($Campo[4], 10, 'X');

	// Campo  5 (IDENTIFICADOR DO ESTABELECIMENTO)				[13]  28-40		N // Este campo é preenchido quando a a necessidade de variaos terminais ou varias loja.
	$Campo[5] = "0";
	$Campo[5] = preenche_tam($Campo[5], 13, '');

	// Campo  6 (MOEDA)											[3]   41-43		N 
	$Campo[6] = 986;
	$Campo[6] = preenche_tam($Campo[6], 3, '');

	// Campo  7 (INDICADOR DE TESTE)							[1]	  44-44		X // Verificar 'P' - Produção
	$Campo[7] = $ParametroLocalCobranca['IndicadorTeste'];
	$Campo[7] = preenche_tam($Campo[7], 1, 'X');
		
	// Campo  8 (FILLER)										[206] 45-250	X
	$Campo[8] = preenche_tam(" ", 206, 'X');	

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
				Pessoa.IdPessoa,
				Pessoa.TipoPessoa,
				Pessoa.CPF_CNPJ,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				PessoaCartao.NumeroCartao,
				PessoaCartao.Validade,
				PessoaCartao.CodigoSeguranca,
				PessoaCartao.DiaVencimentoFatura,
				PessoaCartao.IdStatus,
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
				Pessoa,
				PessoaCartao,
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

				ContaReceberDados.IdLoja = PessoaCartao.IdLoja and
				ContaReceberDados.IdLoja = ContaReceberPosicaoCobranca.IdLoja and
				
				ContaReceberDados.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber and
				ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
				ContaReceberDados.IdPessoa = PessoaCartao.IdPessoa and
				ContaReceberDados.IdCartao = PessoaCartao.IdCartao and
				PessoaCartao.IdStatus = 1
			group by
				ContaReceberDados.IdLoja,
				ContaReceberDados.IdContaReceber";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		switch($lin[IdPosicaoCobranca]){
			case 1://Cadastro de Débito 
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

		# Registro detalhe - cobrança com registro
		
		// Campo  1 (TIPO DE REGISTRO)								[2]   1-2		N
		$Campo[1] = "01";
		$Campo[1] = preenche_tam($Campo[1], 2, '');

		// Campo  2 (NÚMERO DO REGISTRO)							[7]   3-9		N
		$NumSeqRegistro +=1;
		$Campo[2] = $NumSeqRegistro;
		$Campo[2] = preenche_tam($Campo[2], 7, '');

		// Campo  3 (NÚMERO DO CARTÃO)								[19]  10-28	    N 
		$Campo[3] = str_replace(" ","",$lin[NumeroCartao]);
		$Campo[3] = preenche_tam($Campo[3], 19, '');

		// Campo  4 (CÓDIGO DE AUTORIZAÇÃO)							[6]   29-34		X // Não Preencher			
		$Campo[4] = "";		
		$Campo[4] = preenche_tam($Campo[4], 6, 'X');
		
		// Campo  5 (DATA DA VENDA)									[8]  35-42	    N
		$Campo[5] = dataConv($lin[DataLancamento],'Y-m-d','dmY');
		$Campo[5] = preenche_tam($Campo[5], 8, '');
				
		// Campo  6 (OPÇÃO DE FINANCIAMENTO)						[1]  43-43	    N
		$Campo[6] = 0;
		$Campo[6] = preenche_tam($Campo[6], 1, '');

		// Campo  7 (VALOR TOTAL DA TRANSAÇÃO)						[15]  44-58	    N
		$Campo[7] = number_format($lin[ValorFinal], 2, '.', '');			
		$Campo[7] = str_replace(".","",$Campo[7]);
		$Campo[7] = preenche_tam($Campo[7], 15, '');

		// Campo  8 (QUANTIDADE DE PARCELAS)						[3]  59-61	    N
		$Campo[8] = '001';
		$Campo[8] = preenche_tam($Campo[8], 3, '');

		// Campo  9 (RESERVADO)										[60]  62-121    N
		$Campo[9] = 0;
		$Campo[9] = preenche_tam($Campo[9], 60, '');

		// Campo  10 (NÚMERO DO RESUMO DE OPERAÇÕES (RO))			[7]  122-128    N 
		$Campo[10] = $local_IdArquivoRemessa;
		$Campo[10] = preenche_tam($Campo[10], 7, '');

		// Campo  11 (IDENTIFICADOR DO ESTABELECIMENTO)				[13]  129-141   N 
		$Campo[11] = 0;
		$Campo[11] = preenche_tam($Campo[11], 13, '');

		// Campo  12 (IDENTIFICADOR DA TRANSAÇÃO)					[30]  142-171   X 
		$Campo[12] = $lin[NumeroDocumento];
		$Campo[12] = preenche_tam($Campo[12], 30, 'X');

		// Campo  13 (CÓDIGO DE REJEIÇÃO)							[2]  172-173    X // Não Preencher
		$Campo[13] = "";
		$Campo[13] = preenche_tam($Campo[13], 2, 'X');

		// Campo  14 (RESERVADO)									[8]  174-181    N
		$Campo[14] = 0;
		$Campo[14] = preenche_tam($Campo[14], 8, '');

		// Campo  15 (DATA DE VALIDADE DO CARTÃO)					[4]  182-185    N 
		$Campo[15] = dataConv($lin[Validade],'m/Y','AAMM');
		$Campo[15] = preenche_tam($Campo[15], 4, '');

		// Campo  16 (RESERVADO)									[2]  186-187    X
		$Campo[16] = "";
		$Campo[16] = preenche_tam($Campo[16], 2, 'X');
	
		// Campo  17 (CÓDIGO DE ERRO PAY&GO)						[6]  188-193    N // Não Preencher
		$Campo[17] = "";
		$Campo[17] = preenche_tam($Campo[17], 6, '');

		// Campo  18 (MENSAGEM DE ERRO)								[40]  194-233   X // Não Preencher
		$Campo[18] = "";
		$Campo[18] = preenche_tam($Campo[18], 40, 'X');

		// Campo  19 (CÓDIGO DE RESPOSTA)							[3]  234-236    X // Não Preencher
		$Campo[19] = "";
		$Campo[19] = preenche_tam($Campo[19], 3, 'X');

		// Campo  20 (CÓDIGO DE SEGURANÇA)							[5]  237-241    X // Colocar os 3 ultimos digitos do verso do cartão
		$Campo[20] = $lin[CodigoSeguranca];
		$Campo[20] = preenche_tam($Campo[20], 5, 'X');

		// Campo  21 (FILLER)										[9]  242-250    X // Não Preencher
		$Campo[21] = "";
		$Campo[21] = preenche_tam($Campo[21], 9, 'X');
		
		// Salva		
		$Linha[$i] = concatVar($Campo);
		$i++;
		
		$Campo = null;			
		$QtdContaReceber++;	
		$ValorTotalTitulos += $lin[ValorFinal];		
	}	
	
	### Registro Trailer
	
	// Campo  1 (TIPO DE REGISTRO)									[2]    1-2		N
	$Campo[1] = "99";
		
	// Campo  2 (QUANTIDADE DE REGISTROS)							[7]    3-9		N 
	$Campo[2] = $QtdContaReceber;
	$Campo[2] = preenche_tam($Campo[2], 7, '');
	
	// Campo  3 (VALOR TOTAL BRUTO)									[15]   10-24	N 
	$Campo[3] = number_format($ValorTotalTitulos, 2, '.', '');
	$Campo[3] = str_replace(".","",$Campo[3]);
	$Campo[3] = preenche_tam($Campo[3], 15, '');
	
	// Campo  4 (VALOR TOTAL ACEITO)								[15]   25-39	N 
	$Campo[4] = "";
	$Campo[4] = preenche_tam($Campo[4], 15, '');

	// Campo  5 (RESERVADO)											[23]   40-62	N
	$Campo[5] = preenche_tam("", 23, '');
	
	// Campo  6 (FILLER)											[188]  63-250	X
	$Campo[6] = preenche_tam(" ", 188, 'X');	

	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;		
?>
