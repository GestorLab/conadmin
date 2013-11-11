<?
	include("../files/conecta.php");

	$sql = "SELECT
				ContaReceberRecebimento.IdContaReceber
			FROM
				ContaReceberRecebimento,
				ContaReceberPosicaoCobranca
			WHERE
				ContaReceberRecebimento.IdLoja = 1 AND
				ContaReceberRecebimento.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND
				ContaReceberRecebimento.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber AND
				ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' AND
				ContaReceberRecebimento.IdArquivoRetorno > 0";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where 
						IdLoja = 1 and 
						IdContaReceber = $lin[IdContaReceber] and
						ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00'";
		mysql_query($sql,$con);
	}

	$sql = "SELECT
				ContaReceber.IdContaReceber
			FROM
				ContaReceber,
				ContaReceberPosicaoCobranca
			WHERE
				ContaReceber.IdLoja = 1 AND
				ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND
				ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber AND
				ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' AND
				ContaReceber.IdStatus = 0";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where 
						IdLoja = 1 and 
						IdContaReceber = $lin[IdContaReceber] and
						ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00'";
		mysql_query($sql,$con);
	}

	$sql = "SELECT
				ContaReceberPosicaoCobranca.IdContaReceber
			FROM
				ContaReceberPosicaoCobranca
			WHERE
				IdContaReceber IN (SELECT
				IdContaReceber
			FROM
				(SELECT
				IdContaReceber,
				COUNT(*) Qtd
			FROM
				ContaReceberVencimento
			GROUP BY
				IdLoja,
				IdContaReceber) Temp
			WHERE
				Qtd = 1) AND
				DataRemessa = '0000-00-00' AND
				IdPosicaoCobranca = 9";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where 
						IdLoja = 1 and 
						IdContaReceber = $lin[IdContaReceber] and
						ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' and
						IdPosicaoCobranca = 9";
		mysql_query($sql,$con);
	}

	$sql = "SELECT
				ContaReceber.IdContaReceber
			FROM
				ContaReceber,
				ContaReceberPosicaoCobranca
			WHERE
				ContaReceber.IdLoja = 1 AND
				ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND
				ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber AND
				ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' AND
				ContaReceber.IdStatus = 6";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where 
						IdLoja = 1 and 
						IdContaReceber = $lin[IdContaReceber] and
						ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00'";
		mysql_query($sql,$con);
	}

	$sql = "SELECT
				ContaReceber.IdContaReceber
			FROM
				ContaReceber,
				ContaReceberPosicaoCobranca
			WHERE
				ContaReceber.IdLoja = 1 AND
				ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND
				ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber AND
				ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' AND
				ContaReceber.IdStatus = 2 AND
				ContaReceberPosicaoCobranca.IdPosicaoCobranca = 9";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where 
						IdLoja = 1 and 
						IdContaReceber = $lin[IdContaReceber] and
						ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' and
						ContaReceberPosicaoCobranca.IdPosicaoCobranca = 9";
		mysql_query($sql,$con);
	}

	$sql = "SELECT
				ContaReceber.IdContaReceber
			FROM
				ContaReceber,
				ContaReceberPosicaoCobranca
			WHERE
				ContaReceber.IdLoja = 1 AND
				ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND
				ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber AND
				ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' AND
				ContaReceber.IdStatus = 2 AND
				ContaReceberPosicaoCobranca.IdPosicaoCobranca = 1";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where 
						IdLoja = 1 and 
						IdContaReceber = $lin[IdContaReceber] and
						ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' and
						ContaReceberPosicaoCobranca.IdPosicaoCobranca = 1";
		mysql_query($sql,$con);
	}
?>