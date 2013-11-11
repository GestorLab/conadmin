<?
	$sql = "update NotaFiscal set IdStatus='0' where IdNotaFiscalLayout='$lin[IdNotaFiscalLayout]' and IdLoja='$IdLoja' and PeriodoApuracao='$lin[PeriodoApuracao]' and IdNotaFiscal='$lin[IdNotaFiscal]'";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;

	$sql = "update NotaFiscalItem set IdStatus='0' where IdNotaFiscalLayout='$lin[IdNotaFiscalLayout]' and IdLoja='$IdLoja' and PeriodoApuracao='$lin[PeriodoApuracao]' and IdNotaFiscal='$lin[IdNotaFiscal]'";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
?>