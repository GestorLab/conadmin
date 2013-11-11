<?
	include ("../files/conecta.php");
	include ("../files/funcoes.php");

	$sql = "select
				IdLoja,
				IdContaReceber,
				DataVencimento,
				ValorDesconto,
				(ValorLancamento + ValorDespesas) ValorContaReceber,
				LoginCriacao,
				DataCriacao
				
			from
				ContaReceber";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql = "insert into ContaReceberVencimento (IdLoja, IdContaReceber, DataVencimento, ValorContaReceber, ValorMulta, ValorJuros, ValorTaxaReImpressaoBoleto, ValorDesconto, ValorFinal, LoginCriacao, DataCriacao) values ('$lin[IdLoja]',  '$lin[IdContaReceber]',  '$lin[DataVencimento]',  '$lin[ValorContaReceber]',  '0',  '0',  '0',  '0',  '$lin[ValorContaReceber]',  '$lin[LoginCriacao]',  '$lin[DataCriacao]');";
		mysql_query($sql,$con);
	}
?>