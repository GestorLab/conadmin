<?
	// Gera a nomenclatura do arquivo
	$NomeArquivo = "DC".str_replace("-","",$local_MesVencimento)."_L3.TXT";

	$Patch = $PatchPeriodo."/".$NomeArquivo;

	$i=0;
	
	// Conferir a query com o Douglas

	$sql = "select	
				Pessoa.Nome,		
				Pessoa.RG_IE,
				Pessoa.TipoPessoa,
				Pessoa.CPF_CNPJ,
				Pessoa.Fax,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Telefone3,
				PessoaEndereco.Endereco,
				PessoaEndereco.Numero,
				PessoaEndereco.Complemento,
				PessoaEndereco.Bairro,			
				Estado.SiglaEstado,
				Cidade.IdCidade,
				Cidade.NomeCidade,
				PessoaEndereco.CEP,	
				NotaFiscal.Modelo,		
				LancamentoFinanceiroDados.IdServico,
				Estado.IdPais,
				Estado.IdEstado
			from
				NotaFiscal,
				LancamentoFinanceiroDados,
				ContaReceber,
				Pessoa,
				PessoaEndereco,
				Pais,
				Estado,
				Cidade	
			where
				NotaFiscal.IdLoja = $local_IdLoja and
				NotaFiscal.IdLoja = LancamentoFinanceiroDados.IdLoja and
				NotaFiscal.IdLoja = ContaReceber.IdLoja and
				NotaFiscal.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber and
				NotaFiscal.IdContaReceber = ContaReceber.IdContaReceber and
				NotaFiscal.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
				NotaFiscal.PeriodoApuracao = '$local_MesVencimento' and
				LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa and	
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				ContaReceber.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Estado.IdPais and
				PessoaEndereco.IdPais = Cidade.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade
			group by
				Pessoa.IdPessoa";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
	
		$sql = "select
					Aliquota
				from
					ServicoAliquota
				where
					IdLoja	= $local_IdLoja and
					IdServico = $lin[IdServico] and
					IdPais = $lin[IdPais] and
					IdEstado = $lin[IdEstado]";					
		$resServicoAliquota = mysql_query($sql,$con);
		$linServicoAliquota = mysql_fetch_array($resServicoAliquota);	

		if($lin[TipoPessoa] == 1){
			$lin[InscricaoEstadual] = $lin[RG_IE];
			if($lin[InscricaoEstadual] != ""){
				$lin[CobrarICMS] = "S";
			}else{
				$lin[CobrarICMS] = "N";
			}			
		}else{
			$lin[InscricaoEstadual] = "";
			$lin[CobrarICMS]		= "N";
		}
	
		if($lin[Telefone1] != ""){
			$lin[Telefone] = $lin[Telefone1];
		}else{
			if($lin[Telefone2] != ""){
				$lin[Telefone] = $lin[Telefone2];	
			}else{
				if($lin[Telefone3] != ""){
					$lin[Telefone] = $lin[Telefone3];	
				}
			}
		}
	
		$aux = explode(")",$lin[Telefone]);
		$lin[TelefoneDDD] = $aux[0];
		$lin[TelefoneDDD] =	str_replace("(","",$lin[TelefoneDDD]);
		
		### // Clientes
		
		// Campo  1 (Fixo "22" Clientes)							[2]   1-2		X
		$Campo[1] = "22";
		$Campo[1] = preenche_tam($Campo[1], 2, 'X');
	
		// Campo  2 (Código da empresa)								[7]   3-9		N 
		$Campo[2] = $local_CodigoEmpresa;
		$Campo[2] = preenche_tam($Campo[2], 7, '');		
		
		// Campo  3 (Sigla de estado)								[2]   10-11		X 
		$Campo[3] = $lin[SiglaEstado];
		$Campo[3] = preenche_tam($Campo[3], 2, 'X');
	
		// Campo  4 (Código da conta)								[7]   12-18		N 
		$Campo[4] = "0";	
		$Campo[4] = preenche_tam($Campo[4], 7, '');
	
		// Campo  5 (Código do Município)							[7]  19-25		N 
		$Campo[5] = $lin[IdEstado].preenche_tam($lin[IdCidade], 5, '');
		$Campo[5] = preenche_tam($Campo[5], 7, '');
	
		// Campo  6 (Nome reduzido)									[10]  26-35		X 
		$Campo[6] = $lin[Nome];
		$Campo[6] = preenche_tam($Campo[6], 10, 'X');
	
		// Campo  7 (Nome do cliente)								[40]  36-75		X 
		$Campo[7] = $lin[Nome];
		$Campo[7] = preenche_tam($Campo[7], 40, 'X');
	
		// Campo  8 (Endereço)										[40]  76-115	X 
		$Campo[8] = $lin[Endereco];
		$Campo[8] = preenche_tam($Campo[8], 40, 'X');
	
		// Campo  9 (Número do endereço)							[7]  116-122    N 
		$Campo[9] = $lin[Numero];
		$Campo[9] = preenche_tam($Campo[9], 7, '');
	
		// Campo 10 (Brancos)										[30] 123-152	X
		$Campo[10] = preenche_tam(" ", 30, 'X');
		
		// Campo 11 (CEP)											[8]  153-160    X 
		$Campo[11] = str_replace("-","",$lin[CEP]);
		$Campo[11] = preenche_tam($Campo[11], 8, 'X');
		
		// Campo  12 (Inscrição)									[14] 161-174    X 
		$Campo[12] = removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[12] = preenche_tam($Campo[12], 14, 'X');
		
		// Campo  13 (Inscrição Estadual)							[20] 175-194    X
		$Campo[13] = $lin[InscricaoEstadual];
		$Campo[13] = preenche_tam($Campo[13], 20, 'X');
			
		// Campo  14 (Fone)											[14] 195-208    X 
		$Campo[14] = $lin[Telefone];
		$Campo[14] = preenche_tam($Campo[14], 14, 'X');
		
		// Campo  15 (Fax)											[14] 209-222    X 
		$Campo[15] = $lin[Fax];
		$Campo[15] = preenche_tam($Campo[15], 14, 'X');
		
		// Campo  16 (Agropecuário (S,N))							[1] 223-223     X 
		$Campo[16] = "N";
		$Campo[16] = preenche_tam($Campo[16], 1, 'X');
		
		// Campo  17 (ICMS (S, N))									[1] 224-224     X 
		$Campo[17] = $lin[CobrarICMS];
		$Campo[17] = preenche_tam($Campo[17], 1, 'X');
		
		// Campo  18 (Tipo de inscrição)							[1] 225-225     N 
		// (1=CGC, 2=CPF, 3=CEI, 4=Outros)	
		$Campo[18] = $lin[TipoPessoa];
		$Campo[18] = preenche_tam($Campo[18], 1, '');
		
		// Campo  19 (Inscrição Municipal)							[20] 226-245    X 
		$Campo[19] = $lin[InscricaoMunicipal];
		$Campo[19] = preenche_tam($Campo[19], 20, 'X');
		
		// Campo  20 (Bairro)										[20] 246-265    X 
		$Campo[20] = $lin[Bairro];
		$Campo[20] = preenche_tam($Campo[20], 20, 'X');
		
		// Campo  21 (DDD do Fone)									[4] 266-269	    N 
		$Campo[21] = $lin[TelefoneDDD];
		$Campo[21] = preenche_tam($Campo[21], 4, '');
		
		// Campo  22 (Alíquota do ICMS)								[5] 270-274	    N 
		$Campo[22] = number_format($linServicoAliquota[Aliquota], 2, '.', '');
		$Campo[22] = str_replace(".","",$Campo[22]);
		$Campo[22] = preenche_tam($Campo[22], 5, '');

		// Campo  23 (Código do País)								[7] 275-281	    N  // 1058 O pessoal da contabilidade pediu para trocar temporariamente por 30.
		$Campo[23] = "30";
		$Campo[23] = preenche_tam($Campo[23], 7, '');
		
		// Campo  24 (Número de Inscrição na Suframa)				[9] 282-290	    X 
		$Campo[24] = " ";
		$Campo[24] = preenche_tam($Campo[24], 9, 'X');
		
		// Campo  25 (Brancos)										[100] 291-390   X 
		$Campo[25] = preenche_tam(" ", 100, 'X');
		
		// Salva
		$Linha[$i] = concatVar($Campo);		
		$i++;
	
		$Campo = null;		
	}


/*



	# Fornecedores

	// Campo  1 (Fixo "11" Fornecedor)							[2]   1-2		X
	$Campo[1] = "11";
	$Campo[1] = preenche_tam($Campo[1], 2, 'X');

	// Campo  2 (Código da empresa)								[7]   3-9		N // verificar
	$Campo[2] = "0";
	$Campo[2] = preenche_tam($Campo[2], 7, '');

	// Campo  3 (Sigla de estado)								[2]  10-11		X // verificar
	$Campo[3] = "0";
	$Campo[3] = preenche_tam($Campo[3], 2, 'X');

	// Campo  4 (Código da conta)								[7]  12-18		N // verificar
	$Campo[4] = "0";	
	$Campo[4] = preenche_tam($Campo[4], 7, '');

	// Campo  5 (Código do Município)							[7]  19-25		N // verificar
	$Campo[5] = "0";
	$Campo[5] = preenche_tam($Campo[5], 7, '');

	// Campo  6 (Nome reduzido)									[10] 26-35		X // verificar 
	$Campo[6] = "0";	
	$Campo[6] = preenche_tam($Campo[6], 10, 'X');

	// Campo  7 (Nome do cliente)								[40] 36-75		X // verificar 
	$Campo[7] = "0";
	$Campo[7] = preenche_tam($Campo[7], 40, 'X');
	
	// Campo  8 (Endereço)										[40] 76-115		X // verificar 
	$Campo[8] = "0";
	$Campo[8] = preenche_tam($Campo[8], 40, 'X');

	// Campo  9 (Número do endereço)							[7] 116-122		X // verificar 
	$Campo[9] = "0";
	$Campo[9] = preenche_tam($Campo[9], 7, '');

	// Campo  10 (Brancos)										[30] 123-152	N // verificar 
	$Campo[10] = "0";
	$Campo[10] = preenche_tam($Campo[10], 30, '');

	// Campo  11 (CEP)											[8] 153-160		X // verificar 
	$Campo[11] = "0";
	$Campo[11] = preenche_tam($Campo[11], 8, 'X');

	// Campo  12 (Inscrição)									[14] 161-174	X // verificar 
	$Campo[12] = "0";
	$Campo[12] = preenche_tam($Campo[12], 14, 'X');

	// Campo  13 (Inscrição Estadual)							[20] 175-194	X // verificar 
	$Campo[13] = "0";
	$Campo[13] = preenche_tam($Campo[13], 20, 'X');

	// Campo  14 (Fone)											[14] 195-208	X // verificar 
	$Campo[14] = "0";
	$Campo[14] = preenche_tam($Campo[14], 14, 'X');

	// Campo  15 (Fax)											[14] 209-222	X // verificar 
	$Campo[15] = "0";
	$Campo[15] = preenche_tam($Campo[15], 14, 'X');

	// Campo  16 (Agropecuário (S, N))							[1] 223-223		X // verificar 
	$Campo[16] = "N";
	$Campo[16] = preenche_tam($Campo[16], 1, 'X');

	// Campo  17 (ICMS (S, N))								 	[1] 224-124		X // verificar 
	$Campo[17] = "S";
	$Campo[17] = preenche_tam($Campo[17], 1, 'X');

	// Campo  18 (Tipo de inscrição )						 	[1] 225-225		X // verificar 
	// (1=CGC, 2=CPF, 3=CEI, 4=Outros)
	$Campo[18] = "0";
	$Campo[18] = preenche_tam($Campo[18], 1, 'X');

	// Campo  19 (Inscrição Municipal)		 					[20] 226-226	X // verificar 
	$Campo[19] = "0";
	$Campo[19] = preenche_tam($Campo[19], 1, 'X');

	// Campo  20 (Bairro)										[20] 246-265 	X // verificar 
	$Campo[20] = "0";
	$Campo[20] = preenche_tam($Campo[20], 20, 'X');

	// Campo  21 (DDD do Fone)									[4] 266-269		N // verificar 
	$Campo[21] = "0";
	$Campo[21] = preenche_tam($Campo[21], 4, '');

	// Campo  22 (Código do País)								[7] 270-276		N // verificar 
	$Campo[22] = "0";
	$Campo[22] = preenche_tam($Campo[22], 7, '');

	// Campo  23 (Número de inscrição na Suframa)				[7] 277-287		X // verificar 
	$Campo[23] = "0";
	$Campo[23] = preenche_tam($Campo[23], 9, 'X');

	// Campo  24 (Brancos)										[100] 286-385	X // verificar 
	$Campo[24] = "0";
	$Campo[24] = preenche_tam($Campo[24], 100, 'X');
	
	// Salva
	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;
	*/
	
	/*	$sql = "select
				NotaFiscal.IdNotaFiscalLayout,
				NotaFiscal.PeriodoApuracao,
				NotaFiscal.IdNotaFiscal,
				NotaFiscal.DataEmissao,	
				NotaFiscal.IdContaReceber,
				NotaFiscal.Modelo,
				NotaFiscal.ValorTotal,
				NotaFiscal.ValorDesconto,
				NotaFiscal.ValorBaseCalculoICMS,
				NotaFiscal.ValorICMS,
				NotaFiscal.ValorNaoTributado,
				NotaFiscal.ValorOutros,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.IdPessoa,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.DataNF,
				ContaReceberDados.IdLocalCobranca,
				ContaReceberDados.IdPessoaEndereco,
				Pessoa.Nome,
				Pessoa.RG_IE,
				Pessoa.TipoPessoa,
				Pessoa.CPF_CNPJ,
				Pessoa.Fax,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Telefone3,
				PessoaEndereco.Endereco,
				PessoaEndereco.Numero,
				PessoaEndereco.Complemento,
				PessoaEndereco.Bairro,			
				Estado.SiglaEstado,
				Cidade.IdCidade,
				Cidade.NomeCidade,				
				PessoaEndereco.CEP
			from
				NotaFiscal,
				ContaReceberDados,
				Pessoa,
				PessoaEndereco,
				Estado,
				Cidade	
			where
				NotaFiscal.IdLoja = $local_IdLoja and
				NotaFiscal.IdLoja = ContaReceberDados.IdLoja and
				NotaFiscal.IdContaReceber = ContaReceberDados.IdContaReceber and
				NotaFiscal.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
				NotaFiscal.PeriodoApuracao = '$local_MesVencimento' and
				ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Estado.IdPais and
				PessoaEndereco.IdPais = Cidade.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade";*/

	$String = '';
	
	for($i=0; $i<count($Linha); $i++){	
		if(strlen($Linha[$i]) == 390 || strlen($Linha[$i]) == 191 || strlen($Linha[$i]) == 322 || strlen($Linha[$i]) == 100 || strlen($Linha[$i]) == 54 || strlen($Linha[$i]) == 617){
			$String .= strtoupper(substituir_string($Linha[$i]))."\r\n";
		}
	}

	$FileName = $Patch;

	@unlink($FileName);

	if($File = fopen($FileName, 'a')) {
		if(fwrite($File, $String)){
			
			$FileSize = filesize($FileName)/1024;
			$FileSize = number_format($FileSize, 2, '.', '');

		}
	}	
	@fclose($FileName);
?>
