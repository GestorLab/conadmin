<?
	if($Dados[MotivoRejeicao] != "" && $Dados[MotivoRejeicao] != "00"){
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
								IdContaReceber,
								Obs
							from 
								ContaReceber 
							where 
								$sqlContaReceberAux and
								NumeroDocumento='$Dados[NossoNumero]'";
		$resContaReceber = mysql_query($sqlContaReceber,$con);
		if($linContaReceber = mysql_fetch_array($resContaReceber)){

			$linContaReceber[Obs] = $MotivoRejeicao[$Dados[MotivoRejeicao]]." -> ".$MotivoRejeicao[$Dados[MotivoRejeicao]."_".$Dados[MotivoRejeicao2]]." (LR$local_IdLocalRecebimento AR$local_IdArquivoRetorno)\n".$linContaReceber[Obs];

			if($LogErro == true){
				$linContaReceber[Obs] = '[OCORRÊNCIA] '.$linContaReceber[Obs];
			}

			$linContaReceber[Obs] = date("d/m/Y H:i:s")." [$local_Login] - ".$linContaReceber[Obs];

			$sql	=	"UPDATE ContaReceber SET
								Obs = '$linContaReceber[Obs]'
							WHERE 
								IdLoja			= '$linContaReceber[IdLoja]' and
								IdContaReceber	= '$linContaReceber[IdContaReceber]'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
	}
?>
