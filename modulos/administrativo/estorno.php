<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../classes/codigo_barra/class.codigo_barra.php');
	include ('../../rotinas/verifica.php');
	
	$MD5 					= $_GET['Recibo'];
	$IdCaixa 				= $_GET["IdCaixa"];
	$IdCaixaMovimentacao 	= $_GET["IdCaixaMovimentacao"];
	$TipoMovimentacao		= $_GET["IdTipoMovimentacao"];
	
	$sql = "SELECT
				IdContaReceber,
				IdLoja
			FROM
				CaixaMovimentacaoItem
			WHERE
				IdCaixa = $IdCaixa AND
				IdCaixaMovimentacao = $IdCaixaMovimentacao
			order by IdCaixaItem desc";
	$resCaixaTotal = mysql_query($sql,$con);
	echo mysql_error();
	if($linDadosCliente = mysql_fetch_array($resCaixaTotal)){
		$IdLoja = $linDadosCliente['IdLoja'];
		$IdContaReceber = $linDadosCliente['IdContaReceber'];
		$contador = 0;
		$FormaPagamento = Array();
		$ValorParcela 	= Array();
		$ValorPago = Array();
		
		include("local_cobranca/informacoes_default.php");

		/* Coleta Informações sobre o Cliente *///[leonardo]-"essa Query esta retardando o carregamento do recibo"
		$sql = "SELECT 
					CaixaMovimentacao.IdLoja,
					CaixaMovimentacao.IdCaixa,
					CaixaMovimentacao.IdCaixaMovimentacao,
					CaixaMovimentacao.TipoMovimentacao,
					CaixaMovimentacao.ValorTotal,
					CaixaMovimentacao.Obs,
					CaixaMovimentacao.IdStatus,
					Caixa.LoginAbertura,
					CaixaMovimentacao.DataHoraCriacao,
					CaixaMovimentacaoItem.ValorMulta,
					CaixaMovimentacaoItem.ValorJuros
				FROM
					CaixaMovimentacao,
					Caixa,
					CaixaMovimentacaoItem
				WHERE CaixaMovimentacao.IdLoja = $IdLoja 
					AND CaixaMovimentacao.IdCaixa = $IdCaixa 
					AND CaixaMovimentacao.IdCaixaMovimentacao = $IdCaixaMovimentacao
					AND Caixa.IdLoja = CaixaMovimentacao.IdLoja 
					AND Caixa.IdCaixa = CaixaMovimentacao.IdCaixa ";
		$resCr = mysql_query($sql,$con);
		if($linDadosCliente = mysql_fetch_array($resCr)){			
			if($linDadosCliente[IdCaixa] != ''){
				$linDadosCliente[DescricaoLocalCobranca] = "Caixa ".$linDadosCliente[IdCaixa];
			}
			//Login Recebimento
			$sqlLoginRecebimento ="	select 
										Pessoa.Nome LoginAbertura
									from
										Caixa,
										Pessoa,
										Usuario
									where
										Caixa.IdCaixa = $IdCaixa AND
										Usuario.Login = Caixa.LoginAbertura AND
										Pessoa.IdPessoa = Usuario.IdPessoa";
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
			$linDadosCliente[ValorTotal]			= getParametroSistema(5, 1)." ".number_format($linDadosCliente[ValorTotal], 2, ',', '');
			$linDadosCliente[CodigoDeBarras]		= str_pad($IdCaixa."".$IdCaixaMovimentacao, 10, "0", STR_PAD_LEFT);
			$linDadosCliente[DataMovimentacao]		= dataConv($linDadosCliente[DataHoraCriacao],"Y-m-d","d/m/Y");

		}
		if($TipoMovimentacao != 1)
			include('recibo/3/modelo.php');
		else
			include('recibo/5/modelo.php');
	}
?>