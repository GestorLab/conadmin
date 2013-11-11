<?
	include($Path."modulos/administrativo/local_cobranca/21/funcoes.php");

	$sql =  "select
				distinct
				ContaReceberRecebimentoParametro.ValorParametro num_billing
			from
				ContaReceberRecebimento,
				ContaReceberRecebimentoParametro,
				ContaReceber
			where
				ContaReceberRecebimento.IdStatus = 2 and
				ContaReceber.IdStatus != 0 and
				ContaReceberRecebimento.IdLoja = ContaReceberRecebimentoParametro.IdLoja and
				ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja and
				ContaReceberRecebimento.IdContaReceber = ContaReceberRecebimentoParametro.IdContaReceber and
				ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber and
				ContaReceberRecebimentoParametro.IdParametroRecebimento = 'NumeroF2B'";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		processa_retorno_f2b($lin[num_billing]);
	}

	// Verifica quais títulos não tiveram retorno e cancela o recebimento
	$sql = "SELECT
				ContaReceberRecebimento.IdLoja,
				ContaReceberRecebimento.IdContaReceber,
				ContaReceberRecebimento.IdContaReceberRecebimento
				
			FROM
				LocalCobranca,
				ContaReceberRecebimento
			WHERE
				LocalCobranca.IdLocalCobrancaLayout = 21 AND
				LocalCobranca.IdLoja = ContaReceberRecebimento.IdLoja AND
				LocalCobranca.IdLocalCobranca = ContaReceberRecebimento.IdLocalCobranca AND
				ContaReceberRecebimento.IdStatus = 2 AND
				DATEDIFF(CURDATE(), ContaReceberRecebimento.DataRecebimento) > LocalCobranca.DiasCompensacao";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "update ContaReceberRecebimento set 
					IdStatus = 0
				where 
					IdLoja = $lin[IdLoja] and 
					IdContaReceber = $lin[IdContaReceber] and
					IdContaReceberRecebimento = $lin[IdContaReceberRecebimento]";
		mysql_query($sql,$con);
	}
?>