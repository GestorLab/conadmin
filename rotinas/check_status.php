<?
	# Reseta o Status do processo financeiro quando estiver a mais de 1*60*60 (1 hora) em processamento para confirmação
	$sql = "UPDATE ProcessoFinanceiro SET 
				IdStatus = 2, 
				DataConfirmacaoInicio = NULL
			WHERE
				IdStatus = 4 AND
				DataConfirmacaoInicio < '".date("Y-m-d H:i:s", mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-(1*60*60))."'";
	mysql_query($sql,$con);
?>