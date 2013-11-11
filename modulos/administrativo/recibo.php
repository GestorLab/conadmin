<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../classes/codigo_barra/class.codigo_barra.php');
	include ('../../rotinas/verifica.php');
	
	$MD5 = $_GET['Recibo'];
	
	$sql = "select
				ContaReceberRecebimento.IdLoja,
				ContaReceberRecebimento.IdRecibo,
				ContaReceberRecebimento.IdCaixa,
				ContaReceberRecebimento.IdCaixaMovimentacao
			from
				ContaReceberRecebimento
			where
				ContaReceberRecebimento.MD5 = '$MD5';";
	$res = mysql_query($sql,$con);
	$linDadosCliente = mysql_fetch_array($res);
	$IdLoja = $linDadosCliente['IdLoja'];
	$IdRecibo = $linDadosCliente['IdRecibo'];
	
	if(($linDadosCliente['IdCaixa'] != '' || $linDadosCliente['IdCaixa'] != NULL) && ($linDadosCliente['IdCaixaMovimentacao'] != '' || $linDadosCliente['IdCaixaMovimentacao'] != NULL)){
		header("Location: estorno.php?IdCaixa=".$linDadosCliente['IdCaixa']."&IdCaixaMovimentacao=".$linDadosCliente['IdCaixaMovimentacao']."&IdTipoMovimentacao=1&MD5=".$MD5);
	}
	
	include("local_cobranca/informacoes_default.php");

	/* Coleta Informações sobre o Cliente *///[leonardo]-"essa Query esta retardando o carregamento do recibo"
	$sql = "select
				distinct
				LancamentoFinanceiroDados.IdPessoa,
				Pessoa.IdPessoa,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				Pessoa.TipoPessoa,
				Pessoa.CPF_CNPJ,
				Pessoa.RG_IE,
				PessoaEndereco.Endereco,
				PessoaEndereco.Numero,
				PessoaEndereco.Complemento,
				PessoaEndereco.CEP,
				Estado.SiglaEstado,
				Cidade.NomeCidade,
				ContaReceberRecebimento.DataRecebimento,
				ContaReceberDados.ValorLancamento,
				ContaReceberDados.ValorDesconto ValorDescontoLancamento,
				ContaReceberDados.ValorDespesas,
				ContaReceberRecebimento.ValorDesconto,
				ContaReceberRecebimento.ValorMoraMulta,
				ContaReceberRecebimento.ValorOutrasDespesas,
				ContaReceberRecebimento.ValorRecebido,
				ContaReceberRecebimento.IdCaixa,
				ContaReceberRecebimento.IdLocalCobranca,
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.NumeroDocumento
			from
				ContaReceberRecebimento,
				LancamentoFinanceiroDados,
				ContaReceberDados,
				Pessoa,
				PessoaEndereco,
				Estado,
				Cidade,
				LocalCobranca
			where
				ContaReceberRecebimento.IdLoja = $IdLoja and
				ContaReceberRecebimento.IdLoja = LancamentoFinanceiroDados.IdLoja and
				ContaReceberRecebimento.IdLoja = ContaReceberDados.IdLoja and
				ContaReceberRecebimento.IdLoja = LocalCobranca.IdLoja and
				ContaReceberRecebimento.IdRecibo = $IdRecibo and
				ContaReceberRecebimento.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber and
				ContaReceberRecebimento.IdContaReceber = ContaReceberDados.IdContaReceber and
				LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Estado.IdPais and
				Estado.IdPais = Cidade.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				Estado.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade and
				ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
	$res = mysql_query($sql,$con);
	if($linDadosCliente = mysql_fetch_array($res)){
		
		if($linDadosCliente[IdLocalCobranca] != NULL || $linDadosCliente[IdLocalCobranca] != ""){
			$sql2 = "Select
						DescricaoLocalCobranca
					From
						LocalCobranca
					Where
						IdLocalCobranca = '".$linDadosCliente[IdLocalCobranca]."'";
			$resLocalCobranca = mysql_query($sql2,$con);
			if($dadosLocalCobranca = mysql_fetch_array($resLocalCobranca)){
				$linDadosCliente[DescricaoLocalCobranca] = $dadosLocalCobranca[DescricaoLocalCobranca];
			}
		}
		if($linDadosCliente[RazaoSocial] != ''){
			$linDadosCliente[Nome] = $linDadosCliente[RazaoSocial];
		}
		
		if($linDadosCliente[IdCaixa] != ''){
			$linDadosCliente[DescricaoLocalCobranca] = "Caixa ".$linDadosCliente[IdCaixa];
		}
		//Login Recebimento
		$sqlLoginRecebimento ="	select 
									LoginAbertura 
								from
									Caixa 
								where
									IdLoja = $IdLoja and
									IdCaixa = '$linDadosCliente[IdCaixa]'";
		$resLoginRecebimento = mysql_query($sqlLoginRecebimento,$con);
		$linLoginRecebimento = mysql_fetch_array($resLoginRecebimento);
		
		$linDadosCliente[ValorLancamento]	=	$linDadosCliente[ValorLancamento] + $linDadosCliente[ValorDespesas] - $linDadosCliente[ValorDescontoLancamento];

		$linDadosCliente[DataVencimento]		= dataConv($linDadosCliente[DataVencimento],"Y-m-d","d/m/Y");
		$linDadosCliente[DataRecebimento]		= dataConv($linDadosCliente[DataRecebimento],"Y-m-d","d/m/Y");
		$linDadosCliente[ValorLancamento]		= getParametroSistema(5, 1)." ".number_format($linDadosCliente[ValorLancamento], 2, ',', '');
		$linDadosCliente[ValorDesconto]			= getParametroSistema(5, 1)." ".number_format($linDadosCliente[ValorDesconto], 2, ',', '');
		$linDadosCliente[ValorMoraMulta]		= getParametroSistema(5, 1)." ".number_format($linDadosCliente[ValorMoraMulta], 2, ',', '');
		$linDadosCliente[ValorOutrasDespesas]	= getParametroSistema(5, 1)." ".number_format($linDadosCliente[ValorOutrasDespesas], 2, ',', '');
		$linDadosCliente[ValorRecebido]			= getParametroSistema(5, 1)." ".number_format($linDadosCliente[ValorRecebido], 2, ',', '');
		$linDadosCliente[CodigoDeBarras]		= str_pad($IdRecibo, 10, "0", STR_PAD_LEFT);

		include('recibo/'.getCodigoInterno(32,1).'/modelo.php');

	}else{
		header("Location: index.php");
	}
?>
