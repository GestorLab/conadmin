<?
	$sqlLocalCobrancaUnificada = "select
									IdLojaCobrancaUnificada,
									IdLocalCobrancaUnificada
								from
									LocalCobranca
								where
									IdLoja = $local_IdLoja and
									IdLocalCobranca = $local_IdLocalRecebimento";
	$resLocalCobrancaUnificada = mysql_query($sqlLocalCobrancaUnificada,$con);
	$linLocalCobrancaUnificada = mysql_fetch_array($resLocalCobrancaUnificada);

	if($linLocalCobrancaUnificada[IdLojaCobrancaUnificada] != '' && $linLocalCobrancaUnificada[IdLocalCobrancaUnificada] != ''){
		$sqlContaReceberAux = "((IdLoja = $local_IdLoja and IdLocalCobranca = $local_IdLocalRecebimento) or (IdLoja = $linLocalCobrancaUnificada[IdLojaCobrancaUnificada] and IdLocalCobranca = $linLocalCobrancaUnificada[IdLocalCobrancaUnificada]))";
	}else{
		$sqlContaReceberAux = "IdLoja = $local_IdLoja and IdLocalCobranca = $local_IdLocalRecebimento";
	}

	$sqlContaReceber = "select
							IdLoja,
							IdContaReceber
						from 
							ContaReceber 
						where 
							$sqlContaReceberAux and
							NumeroDocumento='$Dados[NossoNumero]'";
	$resContaReceber = mysql_query($sqlContaReceber,$con);
	if($linContaReceber = mysql_fetch_array($resContaReceber)){

		$sql	=	"UPDATE ContaReceber SET
							IdStatus = 6
						WHERE 
							IdLoja			= '$linContaReceber[IdLoja]' and
							IdContaReceber	= '$linContaReceber[IdContaReceber]' and
							IdStatus = 1";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	}
?>