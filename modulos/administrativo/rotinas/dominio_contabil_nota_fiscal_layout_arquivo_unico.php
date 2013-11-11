<?php	
	$Linha	   = null;
	$Campo	   = null;	
	$i		   = 0;
	$Separador = "|";

	//Leyout ssc2231982-2_leiautecompletodominiosistemas
	
	// Gera a nomenclatura do arquivo
	$MesVencimento	= explode("-",$local_MesVencimento);
	$NomeArquivo	= "DC".str_replace("-","",$local_MesVencimento).".TXT";

	$Patch = $PatchPeriodo."/".$NomeArquivo;
	
	$DataInicial = "01/".$MesVencimento[1]."/".$MesVencimento[0];
	$DataFinal 	 = ultimoDiaMes($MesVencimento[1], $MesVencimento[0])."/".$MesVencimento[1]."/".$MesVencimento[0];

	#Registro: 0000 – Identificaзгo da empresa
	$Campo[1]  = $Separador;

	//Identificaзгo do registro
	$Campo[1] .= "0000";
	$Campo[1] .= $Separador;

	//Inscriзгo da empresa
	$Campo[2] .= $DadosEmpresa[CPF_CNPJ];
	$Campo[2] .= $Separador;

	$Linha[$i] = concatVar($Campo);
	$i++;

	$Campo = null;


	$sql = "select	
				Pessoa.Nome,
				Pessoa.RazaoSocial,
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

		$TelAux		   = explode(")",$lin[Telefone]);
		if($TelAux[1] != ""){
			$lin[Telefone] = $TelAux[1];
		}

		#Registro: 0010 – Cadastro de cliente
		$Campo[1]  = $Separador;
		
		//Identificaзгo
		$Campo[1] .= "0010";
		$Campo[1] .= $Separador;

		//Inscriзгo									
		$Campo[2] .= removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[2] .= $Separador;

		//Razгo Social
		$Campo[3] .= $lin[RazaoSocial];
		$Campo[3] .= $Separador;

		//Apelido									
		$Campo[4] .= substr($lin[Nome], 0, 10);
		$Campo[4] .= $Separador;

		//Endereзo									
		$Campo[5] .= $lin[Endereco];
		$Campo[5] .= $Separador;

		//Nъmero do endereзo
		$Campo[6] .= $lin[Numero];
		$Campo[6] .= $Separador;

		//Complemento
		$Campo[7] .= $lin[Complemento];
		$Campo[7] .= $Separador;

		//Bairro
		$Campo[8] .= $lin[Bairro];
		$Campo[8] .= $Separador;

		//Cуdigo do municнpio
		$Campo[9] .= $lin[IdEstado].$lin[IdCidade];
		$Campo[9] .= $Separador;

		//UF
		$Campo[10] .= $lin[SiglaEstado];
		$Campo[10] .= $Separador;

		//Cуdigo do Paнs
		$Campo[11] .= 30;
		$Campo[11] .= $Separador;

		//CEP
		$Campo[12] .= str_replace("-","",$lin[CEP]);
		$Campo[12] .= $Separador;

		//Inscriзгo Estadual
		$Campo[13] .= $lin[InscricaoEstadual];
		$Campo[13] .= $Separador;

		//Inscriзгo Municipal
		$Campo[14] .= $lin[InscricaoMunicipal];
		$Campo[14] .= $Separador;

		//Inscriзгo Suframa							Nгo й obrigatуrio
		$Campo[15] .= "";
		$Campo[15] .= $Separador;

		//DDD
		$Campo[16] .= $lin[TelefoneDDD];
		$Campo[16] .= $Separador;

		//Telefone
		$Campo[17] .= $lin[Telefone];
		$Campo[17] .= $Separador;

		//Fax
		$Campo[18] .= $lin[Fax];
		$Campo[18] .= $Separador;

		//Data do cadastro							Data do cadastro do cliente no sistema
		$Campo[19] .= "";
		$Campo[19] .= $Separador;

		//Conta contбbil							Dominio escrita Fiscal
		$Campo[20] .= "";
		$Campo[20] .= $Separador;

		//Conta contбbil fornecedor					Dominio escrita Fiscal
		$Campo[21] .= "";
		$Campo[21] .= $Separador;

		//Agropecuбrio
		$Campo[22] .= "N";
		$Campo[22] .= $Separador;

		//Natureza jurнdica							Informar o 7 quando for empresa privada ou pessoa fisica
		$Campo[23] .= 7;
		$Campo[23] .= $Separador;

		//Regime de apuraзгo						Quando for pessoa fisica informar outros.
		$Campo[24] .= "";
		$Campo[24] .= $Separador;

		//Contribuinte ICMS							Verifica se o cliente й contribuinte; Correзгo й ICMS
		$Campo[25] .= "";
		$Campo[25] .= $Separador;

		//Alнquota do ICMS							Valor da Aliquota do contribuinte
		$Campo[26] .= "";
		$Campo[26] .= $Separador;

		//Categoria do estabelecimento				Quando for pessoa fisica deixar vazio.
		$Campo[27] .= "";
		$Campo[27] .= $Separador;

		//Interdependкncia com a empresa			Adicionar campo para ser preenchido
		$Campo[28] .= "";
		$Campo[28] .= $Separador;
		
		$Linha[$i] = concatVar($Campo);
		$i++;	

		$Campo = null;
	}
	
	$sql = "select
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
				NotaFiscal.Serie,
				NotaFiscal.IdStatus,
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.IdLocalCobranca,
				ContaReceberDados.DataLancamento,
				Pessoa.IdPessoa,
				Pessoa.Nome,
				Pessoa.RG_IE,
				Pessoa.InscricaoMunicipal,
				Pessoa.TipoPessoa,
				Pessoa.CPF_CNPJ,
				Pessoa.Fax,
				PessoaEndereco.Endereco,
				PessoaEndereco.Numero,
				PessoaEndereco.Complemento,
				PessoaEndereco.Bairro,
				PessoaEndereco.Telefone,
				Estado.SiglaEstado,				
				Estado.IdPais,
				Estado.IdEstado,
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
				PessoaEndereco.IdCidade = Cidade.IdCidade";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql = "select
					ServicoAliquota.Aliquota,
					ServicoAliquota.IdAliquotaTipo,
					(FatorBaseCalculoAliquota * 100) PercentualBaseCalculoAliquota,
					Contrato.CFOP				
				from
					LancamentoFinanceiroDados,
					Contrato,
					Servico,
					ServicoAliquota
				where
					LancamentoFinanceiroDados.IdLoja = $local_IdLoja and
					LancamentoFinanceiroDados.IdLoja = Contrato.IdLoja and
					LancamentoFinanceiroDados.IdLoja = Servico.IdLoja and
					LancamentoFinanceiroDados.IdContrato = Contrato.IdContrato and
					Contrato.IdServico = Servico.IdServico and	
					Servico.IdServico = ServicoAliquota.IdServico and
					LancamentoFinanceiroDados.IdContaReceber = $lin[IdContaReceber] and
					ServicoAliquota.IdPais = $lin[IdPais] and
					ServicoAliquota.IdEstado = $lin[IdEstado] 	
				group by
					Servico.IdServico";
		$res2 = mysql_query($sql,$con);
		$lin2 = mysql_fetch_array($res2);
		
		switch($lin2[IdAliquotaTipo]){
			case '1': //ICMS
				$lin2[IdAliquotaTipo] = 1;
				break;
			case '2': // ISS
				$lin2[IdAliquotaTipo] = 3;
				break;
			default:
				$lin2[IdAliquotaTipo] = "";
		}

		if($lin[TipoPessoa] == 1){
			$lin[InscricaoEstadual] = $lin[RG_IE];
		}else{
			$lin[InscricaoEstadual] = "";
		}

		#Registro: 3000 – Notas Fiscais de Serviзo
		$Campo[1]  = $Separador;
		
		//Identificaзгo do registro
		$Campo[1] .= "3000";
		$Campo[1] .= $Separador;

		//Cуdigo da espйcie
		$Campo[2] .= $lin[Modelo];
		$Campo[2] .= $Separador;

		//Inscriзгo do cliente
		$Campo[3] .= removeMascaraCPF_CNPJ($lin[CPF_CNPJ]);
		$Campo[3] .= $Separador;

		//Cуdigo do acumulador
		$Campo[4] .= $local_CodigoAcumulador;
		$Campo[4] .= $Separador;

		//Sйrie
		$Campo[5] .= $lin[Serie];
		$Campo[5] .= $Separador;

		//Nъmero do Documento
		$Campo[6] .= $lin[NumeroDocumento];
		$Campo[6] .= $Separador;

		//Segmento
		$Campo[7] .= str_replace(".","",$lin2[CFOP]);
		$Campo[7] .= $Separador;

		//Documento Final
		$Campo[8] .= $local_DoctoFinal;
		$Campo[8] .= $Separador;

		//Data da Serviзo
		$Campo[9] .= dataConv($lin[DataEmissao],'Y-m-d','d/m/Y');
		$Campo[9] .= $Separador;

		//Data da Emissгo
		$Campo[10] .= dataConv($lin[DataLancamento],'Y-m-d','d/m/Y');
		$Campo[10] .= $Separador;

		//Valor Contбbil
		$Campo[11] = number_format($lin[ValorTotal], 2, '.', '');
		$Campo[11] = str_replace(".",",",$Campo[11]);					
		$Campo[11] .= $Separador;

		//Observaзгo
		$Campo[12] .= "";
		$Campo[12] .= $Separador;

		//Cуdigo da observaзгo
		$Campo[13] .= "";
		$Campo[13] .= $Separador;
		
		//Fato Gerador da CRF ("E"=Emissгo ou "P"=Pagamento)
		$Campo[14] .= "E";
		$Campo[14] .= $Separador;

		//Fato Gerador do IRRF ("E"=Emissгo ou "P"=Pagamento)
		$Campo[15] .= "E";
		$Campo[15] .= $Separador;

		//Fato Gerador da CRFOP ("E"=Emissгo ou "P"=Pagamento)
		$Campo[16] .= "E";
		$Campo[16] .= $Separador;

		//Fato Gerador da IRRFP ("E"=Emissгo ou "P"=Pagamento)
		$Campo[17] .= "E";
		$Campo[17] .= $Separador;

		//Cуdigo do Municнpio
		$Campo[18] .= $lin[IdEstado].$lin[IdCidade];;
		$Campo[18] .= $Separador;

		//Cуdigo do modelo do documento
		if($lin[IdStatus] == 0){
			$Campo[19] = 2;
		}else{
			$Campo[19] = 0;
		}
		$Campo[19] .= $Separador;

		//Cуdigo fiscal da prestaзгo de serviзo
		$Campo[20] .= str_replace(".","",$lin2[CFOP]);
		$Campo[20] .= $Separador;

		//Sub-Sйrie
		$Campo[21] .= "0";
		$Campo[21] .= $Separador;

		//Inscriзгo Estadual do Cliente
		$Campo[22] .= $lin[InscricaoEstadual];
		$Campo[22] .= $Separador;

		//Inscriзгo Municipal do Cliente
		$Campo[23] .= $lin[InscricaoMunicipal];
		$Campo[23] .= $Separador;
		
		$Linha[$i] = concatVar($Campo);
		$i++;

		$Campo = null;
			
		#Registro: 3020 – Notas Fiscais de Serviзo - Impostos
		$Campo[1]  = $Separador;
		
		//Identificaзгo do registro
		$Campo[1] .= "3020";
		$Campo[1] .= $Separador;

		//Cуdigo do imposto 
		$Campo[2] .= $lin2[IdAliquotaTipo];
		$Campo[2] .= $Separador;

		//Percentual de Reduзгo da Base de Cбlculo
		$Campo[3] = number_format($lin2[PercentualBaseCalculoAliquota], 2, '.', '');
		$Campo[3] =	str_replace(".",",",$Campo[3]);
		$Campo[3] .= $Separador;

		//Base de Cбlculo
		$Campo[4] = number_format($lin[ValorTotal], 2, '.', '');
		$Campo[4] =	str_replace(".",",",$Campo[4]);
		$Campo[4] .= $Separador;

		//Alнquota
		if($lin2[Aliquota]!=''){
			$Campo[5] = number_format($lin2[Aliquota], 2, '.', '');
			$Campo[5] = str_replace(".",",",$Campo[5]);
		}
		$Campo[5] .= $Separador;

		//Valor do Imposto
		$Campo[6] = number_format($lin[ValorICMS], 2, '.', '');
		$Campo[6] =	str_replace(".",",",$Campo[6]);
		$Campo[6] .= $Separador;

		//Valor de Isentas
		$Campo[7] = number_format($lin[ValorNaoTributado], 2, '.', '');
		$Campo[7] =	str_replace(".",",",$Campo[7]);
		$Campo[7] .= $Separador;

		//Valor de Outras
		$Campo[8] = number_format($lin[ValorOutros], 2, '.', '');
		$Campo[8] =	str_replace(".",",",$Campo[8]);
		$Campo[8] .= $Separador;

		//Valor Contбbil
		$Campo[9] = number_format($lin[ValorTotal], 2, '.', '');
		$Campo[9] = str_replace(".",",",$Campo[9]);
		$Campo[9] .= $Separador;
		
		$Linha[$i] = concatVar($Campo);
		$i++;

		$Campo = null;
	}

	$String = '';
			
	for($i=0; $i<count($Linha); $i++){			
		$String .= strtoupper(substituir_string($Linha[$i]))."\r\n";		
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