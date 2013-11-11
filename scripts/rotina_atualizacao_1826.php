<?
	include("../files/conecta.php");

	$i=0;

	$sql = "select
				IdLoja,
				IdContaReceber,
				IdPosicaoCobranca,
				DataRemessa
			from
				ContaReceberPosicaoCobranca";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$i++;

		$sql = "update ContaReceberPosicaoCobranca set IdMovimentacao = $i where 
				IdLoja = $lin[IdLoja] and
				IdContaReceber = $lin[IdContaReceber] and
				IdPosicaoCobranca = $lin[IdPosicaoCobranca] and
				DataRemessa = '$lin[DataRemessa]'";
		mysql_query($sql,$con);
	}
?>