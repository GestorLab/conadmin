<?
	$sqlPessoa = "select
						IdPessoa
					from
						Loja
					where
						IdLoja = $IdLoja";
	$resPessoa = mysql_query($sqlPessoa,$con);
	$linPessoa = mysql_fetch_array($resPessoa);

	if($IdContaReceber == '' && $IdRecibo != ''){
		$sqlContaReceber = "select
								IdContaReceber
							from
								ContaReceberRecebimento
							where
								IdLoja = $IdLoja and
								IdRecibo = $IdRecibo";
		$resContaReceber = mysql_query($sqlContaReceber,$con);
		$linContaReceber = mysql_fetch_array($resContaReceber);

		$IdContaReceber = $linContaReceber[IdContaReceber];
	}
	
	if($IdContaReceber == '' && $IdCarne != ''){
		$sqlContaReceber = "select
								IdContaReceber
							from
								ContaReceber
							where
								IdLoja = $IdLoja and
								IdCarne = $IdCarne
							limit 0,1";
		$resContaReceber = mysql_query($sqlContaReceber,$con);
		$linContaReceber = mysql_fetch_array($resContaReceber);

		$IdContaReceber = $linContaReceber[IdContaReceber];
	}

	$sqlLocalCobranca = "select
							LocalCobranca.IdLocalCobranca,
							LocalCobranca.IdTipoLocalCobranca,
							LocalCobranca.IdPessoa,
							LocalCobranca.ExtLogo,
							LocalCobranca.ContraApresentacao,
							LocalCobranca.MsgDemonstrativo
						from
							ContaReceber,
							LocalCobranca
						where
							ContaReceber.IdLoja = $IdLoja and
							ContaReceber.IdContaReceber = $IdContaReceber and
							ContaReceber.IdLoja = LocalCobranca.IdLoja and
							ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
	$resLocalCobranca = mysql_query($sqlLocalCobranca,$con);
	$linLocalCobranca = mysql_fetch_array($resLocalCobranca);
	
	
	$dadosboleto["IdLocalCobranca"] 	=  $linLocalCobranca[IdLocalCobranca];	
	$dadosboleto["IdTipoLocalCobranca"] =  $linLocalCobranca[IdTipoLocalCobranca];
	$dadosboleto["ContraApresentacao"]  =  $linLocalCobranca[ContraApresentacao];

	if($linLocalCobranca[IdPessoa] != ''){
		$linPessoa[IdPessoa] = $linLocalCobranca[IdPessoa];
	}
	
	$sqlLogoLoja = "select
						IdLoja,
						LogoPersonalizada 
					from
						Loja 
					where
						IdLoja = $IdLoja";
	$resLogoLoja = mysql_query($sqlLogoLoja,$con);
	$linLogoLoja = mysql_fetch_array($resLogoLoja);

	if($linLocalCobranca[ExtLogo] != ''){
		$ExtLogoHTML		= "../../local_cobranca/personalizacao/$IdLoja/$linLocalCobranca[IdLocalCobranca].$linLocalCobranca[ExtLogo]";
		$ExtLogoPDF			= "../../local_cobranca/personalizacao/$IdLoja/$linLocalCobranca[IdLocalCobranca].$linLocalCobranca[ExtLogo]";
		$ExtLogoPDF_BCK		= "/modulos/administrativo/local_cobranca/personalizacao/$IdLoja/$linLocalCobranca[IdLocalCobranca].$linLocalCobranca[ExtLogo]";			
		$ExtLogoReciboHTML	= "local_cobranca/personalizacao/$IdLoja/$linLocalCobranca[IdLocalCobranca].$linLocalCobranca[ExtLogo]";
	}else{
		if($linLogoLoja[LogoPersonalizada] == 1){
			$ExtLogoHTML		= "../../../../img/personalizacao/".$linLogoLoja[IdLoja]."/logo_cab.gif";
			$ExtLogoPDF			= $ExtLogoHTML;
			$ExtLogoPDF_BCK		= "/img/personalizacao/".$linLogoLoja[IdLoja]."/logo_cab.gif";
			$ExtLogoReciboHTML	= "../../img/personalizacao/".$linLogoLoja[IdLoja]."/logo_cab.gif";
		}else{
			$ExtLogoHTML		= "../../../../img/personalizacao/logo_cab.gif";
			$ExtLogoPDF			= $ExtLogoHTML;
			$ExtLogoPDF_BCK		= "/img/personalizacao/logo_cab.gif";
			$ExtLogoReciboHTML	= "../../img/personalizacao/logo_cab.gif";
		}
	}

	/* Coleta as informações da Loja */
	$sql = "select 
				Pessoa.IdPessoa, 
				Pessoa.TipoPessoa, 
				Pessoa.Nome, 
				Pessoa.RazaoSocial, 
				Pessoa.CPF_CNPJ, 
				Pessoa.RG_IE, 
				Pessoa.IdEnderecoDefault,
				PessoaEndereco.Endereco, 
				PessoaEndereco.Numero, 
				PessoaEndereco.Complemento, 
				PessoaEndereco.Bairro,
				PessoaEndereco.CEP,
				Cidade.NomeCidade, 
				Estado.SiglaEstado,
				Pessoa.Telefone1, 
				Pessoa.Telefone2, 
				Pessoa.Fax
			from 
				Pessoa,
				PessoaEndereco,
				Cidade, 
				Estado 
			where 
				Pessoa.IdPessoa = $linPessoa[IdPessoa] and 
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				Cidade.IdPais = PessoaEndereco.IdPais and 
				Cidade.IdEstado = PessoaEndereco.IdEstado and 
				Cidade.IdCidade = PessoaEndereco.IdCidade and 
				Cidade.IdPais = Estado.IdPais and 
				Cidade.IdEstado = Estado.IdEstado";
	$res = mysql_query($sql,$con);
	$linDadosEmpresa = mysql_fetch_array($res);

	if($linDadosEmpresa[Telefone1] != ''){
		if($linDadosEmpresa[Fax] != ""){
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone1]." / ".$linDadosEmpresa[Fax];
		}else{
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone1];
		}
	}else{
		if($linDadosEmpresa[Fax] != ""){
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone2]." / ".$linDadosEmpresa[Fax];
		}else{
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone2];
		}
	}

	if($linDadosEmpresa[TipoPessoa] == 1){
		$CPF_CNPJ = "CNPJ";
	}else{			
		$CPF_CNPJ = "CPF";
	}

	//=============Dados da Sua empresa===============
	// SEUS DADOS
	$dadosboleto["cpf_cnpj"]	= $linDadosEmpresa[CPF_CNPJ];

	$dadosboleto["endereco"]	= $linDadosEmpresa[Endereco].", ".$linDadosEmpresa[Numero];

	if($linDadosEmpresa[Complemento] != ''){
		$dadosboleto["endereco"] .= " - ".$linDadosEmpresa[Complemento];
	}

	$dadosboleto["endereco"] .= " - ".$linDadosEmpresa[Bairro];
	
	$dadosboleto["cidade"]		= $linDadosEmpresa[NomeCidade]."-".$linDadosEmpresa[SiglaEstado]." - Cep: ".$linDadosEmpresa[CEP];
	if($linDadosEmpresa[TipoPessoa] == 1){
		$dadosboleto["cedente"]		= substr($linDadosEmpresa[RazaoSocial],0,65);
	}else{
		$dadosboleto["cedente"]		= substr($linDadosEmpresa[Nome],0,65);
	}
	$dadosboleto["cedenteTit"]	= $linDadosEmpresa[Nome];

	if($linDadosEmpresa[Telefone] != ''){
		if($linDadosEmpresa[Fax] != ""){
			$dadosboleto["fone"] 				= " - Fone / Fax: ".$linDadosEmpresa[Telefone];
		}else{
			$dadosboleto["fone"] 				= " - Fone: ".$linDadosEmpresa[Telefone];
		}
		$dadosboleto["tele"]				= $linDadosEmpresa[Telefone];
	}

	if($linDadosEmpresa[RG_IE] != ''){
		$dadosboleto["ie"] 				= " - IE: ".$linDadosEmpresa[RG_IE];
	}

	if($IdContaReceber != ''){
		/* Coleta de Informações do Conta Receber */
		$sql	= "select 
					DataVencimento,
					NumeroDocumento,
					NossoNumero,
					DataLancamento, 
					ValorFinal ValorLancamento, 
					IdLocalCobranca,
					IdLocalCobrancaLayout
				from 
					ContaReceberDados
				where 
					IdLoja=$IdLoja and 
					IdContaReceber=$IdContaReceber";

		$res	= mysql_query($sql,$con);
		$linContaReceber = mysql_fetch_array($res);

		$linContaReceber[DataVencimento]	= dataConv($linContaReceber[DataVencimento],"Y-m-d","d/m/Y");
		$linContaReceber[DataLancamento]	= dataConv($linContaReceber[DataLancamento],"Y-m-d","d/m/Y");
		$linContaReceber[ValorLancamento]	= number_format($linContaReceber[ValorLancamento], 2, ',', '');

		if($linContaReceber[NossoNumero] == ''){	$linContaReceber[NossoNumero] = $linContaReceber[NumeroDocumento];		}

		switch($linContaReceber[IdLocalCobrancaLayout]){
			case 5:				
				if($dadosboleto["ContraApresentacao"] == 1){
					$linContaReceber[DataVencimento]			= dataConv(incrementaData(dataConv($linContaReceber[DataLancamento],"d/m/Y","Y-m-d"),15),"Y-m-d","d/m/Y"); 					
					$dadosboleto["data_vencimento_visual"]	= "CONTRA APRESENTAÇÃO";				
				}else{
					$dadosboleto["data_vencimento_visual"]	= $linContaReceber[DataVencimento];				
				}
				break;
			default:
				if($dadosboleto["ContraApresentacao"] == 1){
					$linContaReceber[DataVencimento] = null;
				}
				break;
		}		

		/* Coleta Informações do Local de Cobrança */
		$sql = "select 
					ValorDespesaLocalCobranca, 
					PercentualMulta, 
					PercentualJurosDiarios 
				from 
					LocalCobranca 
				where 
					IdLoja=$IdLoja and 
					IdLocalCobranca=$linContaReceber[IdLocalCobranca]";
		$res = mysql_query($sql,$con);
		$linLocalCobranca = mysql_fetch_array($res);

		$ValorMulta = $linContaReceber[ValorLancamento] * $linLocalCobranca[PercentualMulta] / 100;
		$ValorMulta = number_format($ValorMulta, 2, ',', '');
		$ValorMulta = getParametroSistema(5,1).$ValorMulta;

		$ValorJurosDiarios = $linContaReceber[ValorLancamento] * $linLocalCobranca[PercentualJurosDiarios] / 100;
		$ValorJurosDiarios = number_format($ValorJurosDiarios, 2, ',', '');
		$ValorJurosDiarios = getParametroSistema(5,1).$ValorJurosDiarios;

		$sql = "select ValorDespesas from ContaReceber where IdLoja=$IdLoja and IdContaReceber=$IdContaReceber";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$ValorDespesaLocalCobranca = number_format($lin[ValorDespesas], 2, ',', '');
		$ValorDespesaLocalCobranca = getParametroSistema(5,1).$ValorDespesaLocalCobranca;


		/* Coleta de Informações dos Parâmetros */
		$sql = "select 
					IdLocalCobrancaParametro, 
					ValorLocalCobrancaParametro 
				from
					LocalCobrancaParametro 
				where 
					IdLoja=$IdLoja and 
					IdLocalCobranca=$linContaReceber[IdLocalCobranca]";
		$res	= mysql_query($sql,$con);
		while($linLocalCobrancaParametro = mysql_fetch_array($res)){

			$linLocalCobrancaParametro[ValorLocalCobrancaParametro] = str_replace('$ValorMulta', $ValorMulta, $linLocalCobrancaParametro[ValorLocalCobrancaParametro]);

			$linLocalCobrancaParametro[ValorLocalCobrancaParametro] = str_replace('$ValorJurosDiarios', $ValorJurosDiarios, $linLocalCobrancaParametro[ValorLocalCobrancaParametro]);

			$linLocalCobrancaParametro[ValorLocalCobrancaParametro] = str_replace('$ValorDespesaLocalCobranca', $ValorDespesaLocalCobranca, $linLocalCobrancaParametro[ValorLocalCobrancaParametro]);

			$CobrancaParametro[$linLocalCobrancaParametro[IdLocalCobrancaParametro]] = $linLocalCobrancaParametro[ValorLocalCobrancaParametro];				
		}

		if($linDadosEmpresa[Telefone] == ''){
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone1];
		}else{
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone2];
		}

		if($linDadosEmpresa[TipoPessoa] == 1){
			$CPF_CNPJ = "CNPJ";
		}else{			
			$CPF_CNPJ = "CPF";
		}

		if($linDadosEmpresa[Telefone] != ''){
			$entra["fone"] 				= " - Fone / Fax: ".$linDadosEmpresa[Telefone];
		}

		//===Dados do seu Cliente (Opcional)===============
		$sql = "select 
					ContaReceber.IdLoja,
					ContaReceber.IdPessoa,
					Pessoa.Nome,
					Pessoa.RazaoSocial,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.CPF_CNPJ,
					Pessoa.RG_IE,
					Pessoa.Fax,
					PessoaEndereco.Endereco,
					PessoaEndereco.Numero,
					PessoaEndereco.Complemento,
					PessoaEndereco.Bairro,
					PessoaEndereco.CEP,
					Cidade.NomeCidade,
					Estado.SiglaEstado,
					Pessoa.Cob_FormaOutro,
					Pessoa.CampoExtra1
				from
					Pessoa,
					PessoaEndereco,
					ContaReceber,
					Estado,
					Cidade
				where 
					ContaReceber.IdLoja = $IdLoja and 
					ContaReceber.IdContaReceber = $IdContaReceber and
					ContaReceber.IdPessoa = Pessoa.IdPessoa and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
					PessoaEndereco.IdPais = Estado.IdPais and
					PessoaEndereco.IdPais = Cidade.IdPais and
					PessoaEndereco.IdEstado = Estado.IdEstado and
					PessoaEndereco.IdEstado = Cidade.IdEstado and
					PessoaEndereco.IdCidade = Cidade.IdCidade";
		$res = mysql_query($sql,$con);
		$linDadosCliente = mysql_fetch_array($res);

		if($linDadosCliente[Numero] != ''){
			$linDadosCliente[Numero] = ", ".$linDadosCliente[Numero];
		}else{
			$linDadosCliente[Numero] = $linDadosCliente[Numero];
		}
		if($linDadosCliente[Complemento] != ''){
			$linDadosCliente[Complemento] = " - ".$linDadosCliente[Complemento];
		}else{
			$linDadosCliente[Complemento] = $linDadosCliente[Complemento];
		}
		if($linDadosCliente[TipoPessoa]== '1'){
			$dadosboleto['CPF_CNPJ_Adicional'] = "CNPJ: ".$linDadosCliente[CPF_CNPJ];			
		} else{
			$dadosboleto['CPF_CNPJ_Adicional'] = "CPF: ".$linDadosCliente[CPF_CNPJ];
		}
        
        if(getCodigoInterno(3,180) == '1'){
            if($linDadosCliente[TipoPessoa]== '1'){
                $dinamic_cpf_cnpj = "CNPJ: ".$linDadosCliente[CPF_CNPJ];
            } else{
                $dinamic_cpf_cnpj = "CPF: ".$linDadosCliente[CPF_CNPJ];
            }
        } else{
            $dinamic_cpf_cnpj = "";
        }
		if(getCodigoInterno(3,226) == '1'){
			$IdPessoaImpressao = " [$linDadosCliente[IdPessoa]] ";
		}else{
			$IdPessoaImpressao = " ";
		}
		$dadosboleto["nome_sacado"] 		= $linDadosCliente[Nome];
		$dadosboleto["sacado"]				= $linDadosCliente[Nome].$IdPessoaImpressao.$dinamic_cpf_cnpj;
		$dadosboleto["campoextra1"]			= $linDadosCliente[CampoExtra1];
		
		if($linDadosCliente[TipoPessoa] == 1){
            if(getCodigoInterno(3,179) == '1'){
                $dadosboleto["sacado"] = $linDadosCliente[Nome]." ($linDadosCliente[RazaoSocial])".$IdPessoaImpressao.$dinamic_cpf_cnpj;
            }
            if(getCodigoInterno(3,179) == '2'){
                $dadosboleto["sacado"] = $linDadosCliente[RazaoSocial]." ($linDadosCliente[Nome])".$IdPessoaImpressao.$dinamic_cpf_cnpj;
            }
            if(getCodigoInterno(3,179) != '1' && getCodigoInterno(3,179) != '2'){
                $dadosboleto["sacado"] = $linDadosCliente[Nome]." ($linDadosCliente[RazaoSocial])".$IdPessoaImpressao.$dinamic_cpf_cnpj;
            }
			
			 if(getCodigoInterno(3,203) == '1'){
                $dadosboleto["nome_sacado"] = $linDadosCliente[RazaoSocial];
            }
            if(getCodigoInterno(3,203) == '2'){
                $dadosboleto["nome_sacado"] = $linDadosCliente[Nome];
            }
            if(getCodigoInterno(3,203) != '1' && getCodigoInterno(3,179) != '2'){
                $dadosboleto["nome_sacado"] = $linDadosCliente[Nome];
            }
		}

		$dadosboleto["representante"]	= $linDadosCliente[NomeRepresentante];
		$dadosboleto["endereco1"]		= $linDadosCliente[Endereco].$linDadosCliente[Numero].$linDadosCliente[Complemento];
		$dadosboleto["endereco2"]		= $linDadosCliente[Bairro]." - ".$linDadosCliente[NomeCidade]."-".$linDadosCliente[SiglaEstado]." - CEP: ".$linDadosCliente[CEP];
		$dadosboleto["endereco3"]		= $linDadosCliente[NomeCidade]."-".$linDadosCliente[SiglaEstado]." - CEP: ".$linDadosCliente[CEP];
		$dadosboleto["cob_outro"]		= $linDadosCliente[Cob_FormaOutro];
		$dadosboleto["fax"]				= $linDadosCliente[Fax];
		$dadosboleto["cep"]		= $linDadosCliente[CEP];
		$dadosboleto["endereco_2"]		= $linDadosCliente[Bairro]." - ".$linDadosCliente[NomeCidade]."-".$linDadosCliente[SiglaEstado];
		
		$sql = "select 
			Pessoa.Nome,
			Pessoa.NomeRepresentante, 
			Pessoa.RazaoSocial,
			Pessoa.TipoPessoa,
			Pessoa.CPF_CNPJ,
			Pessoa.RG_IE,
			Cidade.NomeCidade,
			Estado.SiglaEstado,
			Pessoa.Cob_FormaOutro,
			PessoaEndereco.NomeResponsavelEndereco NomeResponsavel, 
			PessoaEndereco.Endereco, 
			PessoaEndereco.Complemento, 
			PessoaEndereco.Numero, 
			PessoaEndereco.Bairro, 
			PessoaEndereco.IdPais, 
			PessoaEndereco.IdEstado, 
			PessoaEndereco.IdCidade, 
			PessoaEndereco.CEP
		from 
			Pessoa,
			PessoaEndereco,
			Estado,
			Cidade,
			ContaReceberDados
		where 
			ContaReceberDados.IdLoja = $IdLoja and
			Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
			Pessoa.IdPessoa = ContaReceberDados.IdPessoa and
			ContaReceberDados.IdContaReceber = $IdContaReceber and
			PessoaEndereco.IdPessoaEndereco = ContaReceberDados.IdPessoaEndereco and
			PessoaEndereco.IdPais = Estado.IdPais and
			PessoaEndereco.IdPais = Cidade.IdPais and
			PessoaEndereco.IdEstado = Estado.IdEstado and
			PessoaEndereco.IdEstado = Cidade.IdEstado and
			PessoaEndereco.IdCidade = Cidade.IdCidade";
		$res3 = mysql_query($sql,$con);	
		$linDadosClienteCobranca = mysql_fetch_array($res3);

		if($linDadosClienteCobranca[Complemento] != ''){
			$linDadosClienteCobranca[Complemento] = $linDadosClienteCobranca[Complemento]." - ";
		}
		
		$dadosboleto["endereco01"]		= $linDadosClienteCobranca[Endereco].", ".$linDadosClienteCobranca[Numero];
		$dadosboleto["endereco02"]		= $linDadosClienteCobranca[Complemento].$linDadosClienteCobranca[Bairro];
		$dadosboleto["endereco03"]		= $linDadosClienteCobranca[NomeCidade]."-".$linDadosClienteCobranca[SiglaEstado]." - CEP: ".$linDadosClienteCobranca[CEP];
	}
	
	for($i=1; $i<=3; $i++){
		if(trim($dadosboleto["endereco0".$i]) == ''){
			$dadosboleto["endereco0".$i] = $dadosboleto["endereco0".($i+1)];
			$dadosboleto["endereco0".($i+1)] = '';
		}
	}

	$sql = "select
			ValorContaReceber,
			ValorMulta,
			ValorJuros,
			ValorTaxaReImpressaoBoleto,
			ValorOutrasDespesas,
			ValorDesconto,
			ValorContaReceber		
		from
			ContaReceber,
			ContaReceberVencimento
		where
			ContaReceber.IdLoja = $IdLoja and
			ContaReceber.IdLoja = ContaReceberVencimento.IdLoja and
			ContaReceber.IdContaReceber = $IdContaReceber and
			ContaReceber.IdContaReceber = ContaReceberVencimento.IdContaReceber and
			ContaReceber.DataVencimento = ContaReceberVencimento.DataVencimento";
	$res4 = mysql_query($sql,$con);	
	$linContaReceberVencimento = mysql_fetch_array($res4);
	
	$dadosboleto["valor_desconto"]			= $linContaReceberVencimento[ValorDesconto];
	$dadosboleto["valor_outras_deducoes"]	= '0.00';
	$dadosboleto["valor_mora_multa"]		= $linContaReceberVencimento[ValorMulta] + $linContaReceberVencimento[ValorJuros];		
	$dadosboleto["valor_outros_acrescimos"]	= $linContaReceberVencimento[ValorTaxaReImpressaoBoleto] + $linContaReceberVencimento[ValorOutrasDespesas];
	$dadosboleto["valor_cobrado"]			= ((($linContaReceberVencimento["ValorContaReceber"] + $dadosboleto["valor_mora_multa"] + $dadosboleto["valor_outros_acrescimos"])-$dadosboleto["valor_desconto"])-$dadosboleto["valor_outras_deducoes"]);	
	$dadosboleto["valor_cobrado"] 			= number_format($dadosboleto["valor_cobrado"], 2, ',', '');
	$dadosboleto["valor_outros_acrescimos"] = number_format($dadosboleto["valor_outros_acrescimos"], 2, ',', '');
	$dadosboleto["valor_mora_multa"] 		= number_format($dadosboleto["valor_mora_multa"], 2, ',', '');
	$dadosboleto["valor_outras_deducoes"]	= number_format($dadosboleto["valor_outras_deducoes"], 2, ',', '');
	$dadosboleto["valor_documento"]			= $linContaReceberVencimento["ValorContaReceber"];
	$dadosboleto["valor_documento"]			= number_format($dadosboleto["valor_documento"], 2, ',', '');
	$dadosboleto["valor_desconto"]			= number_format($dadosboleto["valor_desconto"], 2, ',', '');
	
	$dadosboleto["valor_desconto"]			= "";
	$dadosboleto["valor_outras_deducoes"]	= "";
	$dadosboleto["valor_mora_multa"]		= "";		
	$dadosboleto["valor_outros_acrescimos"]	= "";
	$dadosboleto["valor_cobrado"]			= "";

	$tituloBoleto = $dadosboleto["sacado"]." - ".getParametroSistema(4,1);
	
	$sqlParcela = "SELECT 
						Demonstrativo.Referencia Referencia,
						REPLACE(Demonstrativo.Referencia,'Parcela ','') ReferenciaResulmida
					FROM 
						Demonstrativo,
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber
					WHERE 
						LancamentoFinanceiroContaReceber.IdLoja = $IdLoja AND
						LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber AND
						LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja  AND
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
						Demonstrativo.IdLoja = LancamentoFinanceiroContaReceber.IdLoja  AND
						Demonstrativo.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber AND
						Demonstrativo.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro";
	
	$resParcela = mysql_query($sqlParcela,$con);
	$linParcela = mysql_fetch_array($resParcela);
	
	$dadosboleto["parcela"] = $linParcela[Referencia];
	$dadosboleto["parcela_resulmida"] = $linParcela[ReferenciaResulmida];
?>
