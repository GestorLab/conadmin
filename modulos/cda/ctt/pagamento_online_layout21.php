<?	
	# Vars Cabeçalho **********************************
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');	
	include('../files/funcoes.php');	
	include('../rotinas/verifica.php');
	
	$local_IdLoja	= $_SESSION["IdLojaCDA"];
	$local_IdPessoa	= $_SESSION["IdPessoaCDA"];	

	# Fim Vars Cabeçalho *******************************

	$local_ContaReceber	=	$_GET['ContaReceber'];

	$sql = "select
				IdLocalCobranca,
				IdPessoa
			from
				LocalCobranca
			where
				IdLoja = $local_IdLoja and
				IdLocalCobrancaLayout = 21 and
				IdStatus = 1
			limit 0,1";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$IdPessoaLoja = $lin[IdPessoa];
	$IdLocalCobranca = $lin[IdLocalCobranca];

	$sql = "select
				IdLocalCobrancaParametro,
				ValorLocalCobrancaParametro
			from
				LocalCobrancaParametro
			where
				IdLoja = $local_IdLoja and
				IdLocalCobrancaLayout = 21 and
				IdLocalCobranca = $lin[IdLocalCobranca]";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$LocalCobrancaParametro[$lin[IdLocalCobrancaParametro]] = $lin[ValorLocalCobrancaParametro];
	}

	$sql = "select
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.ValorDespesas,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.DataVencimento,
				sum(LancamentoFinanceiro.ValorDescontoAConceber) ValorDescontoAConceber,
				min(LancamentoFinanceiro.LimiteDesconto) LimiteDesconto
			from
				ContaReceberDados,
				LancamentoFinanceiroContaReceber,
				LancamentoFinanceiro
			where
				ContaReceberDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
				LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
				ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
				LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
				ContaReceberDados.MD5 = '$local_ContaReceber'
			group by
				ContaReceberDados.IdContaReceber";
	$res = mysql_query($sql,$con);
	$linContaReceber = mysql_fetch_array($res);

	// Tratamento de variáveis
	$LocalCobrancaParametro[Conta] = trim(str_replace(' ','',$LocalCobrancaParametro[Conta]));

	$LocalCobrancaParametro[TipoCobranca] = '';
	if($LocalCobrancaParametro[TipoCobrancaBoleto]			== 'Sim'){	$LocalCobrancaParametro[TipoCobranca] .= 'B';	}
	if($LocalCobrancaParametro[TipoCobrancaCartaoCredito]	== 'Sim'){	$LocalCobrancaParametro[TipoCobranca] .= 'C';	}
	if($LocalCobrancaParametro[TipoCobrancaCartaoDebito]	== 'Sim'){	$LocalCobrancaParametro[TipoCobranca] .= 'D';	}
	if($LocalCobrancaParametro[TipoCobrancaTransferencia]	== 'Sim'){	$LocalCobrancaParametro[TipoCobranca] .= 'T';	}

	// Dados do Sacador
	if($IdPessoaLoja == ''){
		$sql = "select
					IdPessoa
				from
					Loja
				where
					IdLoja = $local_IdLoja";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$IdPessoaLoja = $lin[IdPessoa];
	}

	$sql = "select
				Nome,
				RazaoSocial,
				TipoPessoa
			from
				Pessoa
			where
				Pessoa.IdPessoa = $IdPessoaLoja";
	$res = mysql_query($sql,$con);
	$linPessoaLoja = mysql_fetch_array($res);									

	if($linPessoaLoja[TipoPessoa] == 1){
		$linPessoaLoja[Nome] .= " ($linPessoaLoja[RazaoSocial])";
	}


	$sql	=	"select 
					   Pessoa.IdPessoa,
					   Pessoa.Nome,
					   Pessoa.Email,
					   PessoaEndereco.Endereco,
					   PessoaEndereco.Numero,
					   PessoaEndereco.Complemento,
					   PessoaEndereco.Bairro,
					   Estado.SiglaEstado,
					   Cidade.NomeCidade,
					   PessoaEndereco.Cep,
					   Pessoa.TipoPessoa,
					   Pessoa.CPF_CNPJ,
					   Pessoa.IdGrupoPessoa
				from 
					   Pessoa,
					   PessoaEndereco,
					   Estado,
					   Cidade
				where
					 Pessoa.IdPessoa = $local_IdPessoa and
					 Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					 Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
					 PessoaEndereco.IdPais = Estado.IdPais and
					 PessoaEndereco.IdPais = Cidade.IdPais and
					 PessoaEndereco.IdEstado = Estado.IdEstado and
					 PessoaEndereco.IdEstado = Cidade.IdEstado and
					 PessoaEndereco.IdCidade = Cidade.IdCidade";
	$res	=	@mysql_query($sql,$con);
	$linPessoa	=	@mysql_fetch_array($res);

	$linPessoa[Email]	= explode(";",$linPessoa[Email]);

	$linPessoa[Cep]		= str_replace("-","",$linPessoa[Cep]);
	$linPessoa[Cep]		= str_replace(".","",$linPessoa[Cep]);

	$linPessoa[CPF_CNPJ]= str_replace(".","",$linPessoa[CPF_CNPJ]);
	$linPessoa[CPF_CNPJ]= str_replace("/","",$linPessoa[CPF_CNPJ]);
	$linPessoa[CPF_CNPJ]= str_replace("-","",$linPessoa[CPF_CNPJ]);

	if($linPessoa[TipoPessoa] == 1){
		$linPessoa[CNPJ] = $linPessoa[CPF_CNPJ];
	}else{
		$linPessoa[CPF] = $linPessoa[CPF_CNPJ];
	}

	if($linContaReceber[ValorDescontoAConceber] <= 0){
		$linContaReceber[ValorDescontoAConceber] = '';
	}else{
		$linContaReceber[ValorFinal] -= $linContaReceber[ValorDescontoAConceber];
	}

	// Sql de Inserção de Unidade
	$sql3	=	"select (max(IdContaReceberRecebimento)+1) IdContaReceberRecebimento from ContaReceberRecebimento where IdLoja=$local_IdLoja and IdContaReceber = $linContaReceber[IdContaReceber]";
	$res3	=	mysql_query($sql3,$con);
	$lin3	=	@mysql_fetch_array($res3);
		
	if($lin3[IdContaReceberRecebimento]!=NULL){
		$local_IdContaReceberRecebimento	=	$lin3[IdContaReceberRecebimento];
	}else{
		$local_IdContaReceberRecebimento	=	1;
	}

	// Parametros de Recebimento
	$sql = "insert into ParametroRecebimento set
				IdLoja = $local_IdLoja,
				IdLocalCobranca = $IdLocalCobranca,
				IdParametroRecebimento = 'NumeroF2B'";
	mysql_query($sql,$con);

	$sql = "insert into ContaReceberRecebimento set 
				IdLoja = $local_IdLoja,
				IdContaReceber = $linContaReceber[IdContaReceber],
				IdContaReceberRecebimento = $local_IdContaReceberRecebimento,
				DataRecebimento = curdate(),
				ValorRecebido = 0,
				IdLojaRecebimento = $local_IdLoja,
				IdLocalCobranca = $IdLocalCobranca,
				IdArquivoRetorno = null,
				IdStatus = 2,
				LoginCriacao = 'cda',
				DataCriacao = concat(curdate(),' ',curtime())";
	mysql_query($sql,$con);
	
	$linContaReceber[CodigoContaReceber] = str_pad($local_IdLoja.$linContaReceber[IdContaReceber].$local_IdContaReceberRecebimento, 9, "0", STR_PAD_LEFT);

	if($linContaReceber[LimiteDesconto] < 0){
		$linContaReceber[LimiteDesconto] = ($linContaReceber[LimiteDesconto] * -1);
	}else{
		$linContaReceber[LimiteDesconto] = 0;
	}

#	include("../../administrativo/local_cobranca/21/F2bCobranca/WSBillingPHP.php");
?>