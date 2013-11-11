<?
	$sql = "select			
				Pessoa.RazaoSocial		
			from
				Loja,
				Pessoa
			where
				Loja.IdLoja = $local_IdLoja and
				Loja.IdPessoa = Pessoa.IdPessoa";
	$res = mysql_query($sql,$con);
	$DadosEmpresa = mysql_fetch_array($res);
	
	$sql = "select
				Pessoa.Nome,
				Pessoa.CPF_CNPJ,
				PessoaContaDebito.NumeroAgencia Agencia,
				PessoaContaDebito.DigitoAgencia,
				PessoaContaDebito.NumeroConta Conta,
				PessoaContaDebito.DigitoConta,
				PessoaContaDebito.IdLoja,
				PessoaContaDebito.IdPessoa,
				PessoaContaDebito.IdContaDebito,
				PessoaContaDebito.DataCriacao,
				PessoaContaDebito.IdLocalCobranca				
			from
				Pessoa,
				PessoaContaDebito
			where
				PessoaContaDebito.IdLoja = '$local_IdLoja' and
				PessoaContaDebito.IdPessoa = '$local_IdPessoa' and
				PessoaContaDebito.IdContaDebito = '$local_IdContaDebito' and
				Pessoa.IdPessoa = PessoaContaDebito.IdPessoa;";
	$res = @mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);
	
	$lin[NumeroIdentificacao] = str_pad($lin[IdLoja], 3, 0, STR_PAD_LEFT);
	$lin[NumeroIdentificacao] .= str_pad($lin[IdPessoa], 11, 0, STR_PAD_LEFT);
	$lin[NumeroIdentificacao] .= str_pad($lin[IdContaDebito], 11, 0, STR_PAD_LEFT);

	if($lin[DigitoAgencia] != ''){
		$lin[Agencia] .= "-".$lin[DigitoAgencia];
	}
	if($lin[DigitoConta] != ''){
		$lin[Conta] .= "-".$lin[DigitoConta];
	}

	$sql = "select
				IdLocalCobrancaParametro,
				ValorLocalCobrancaParametro
			from
				LocalCobrancaParametro
			where
				IdLoja = $local_IdLoja and
				IdLocalCobranca = $lin[IdLocalCobranca]";
	$res = mysql_query($sql,$con);
	while($lin2 = mysql_fetch_array($res)){
		$ParametroLocalCobranca[$lin2[IdLocalCobrancaParametro]] = $lin2[ValorLocalCobrancaParametro];
	}
?>