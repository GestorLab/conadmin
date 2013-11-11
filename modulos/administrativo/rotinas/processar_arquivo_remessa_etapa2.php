<?
	$ExisteContaReceberVencido = 0;
	$QtdContaReceberVencido	   = 0;

	$sql = "select
				distinct
				ContaReceber.IdLoja,
				ContaReceber.IdContaReceber,
				ContaReceber.IdLocalCobranca,
				ContaReceber.DataVencimento,
				ContaReceberPosicaoCobranca.IdPosicaoCobranca,
				LocalCobranca.IdTipoLocalCobranca
			from
				ContaReceber,
				ContaReceberPosicaoCobranca,
				LocalCobranca
			where
				(
					(
						LocalCobranca.IdLoja = $local_IdLoja and
						LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
					) or (
						LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and
						LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
					)
				) and

				ContaReceber.IdLoja = LocalCobranca.IdLoja and
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja and
				ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber and
				ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' and
				ContaReceberPosicaoCobranca.IdContaReceber not in (select IdContaReceber from ContaReceberPosicaoCobranca where IdLoja = $local_IdLoja and IdPosicaoCobranca = 10) and
				ContaReceberPosicaoCobranca.IdContaReceber not in (select IdContaReceber from ContaReceberPosicaoCobranca where IdLoja = $local_IdLoja and DataRemessa = curdate())
			order by
				ContaReceber.DataVencimento ASC";
	$res2 = mysql_query($sql,$con);
	while($lin2 = mysql_fetch_array($res2)){
		
		$lin2[DataVencimento] = str_replace("-","",$lin2[DataVencimento]);
		
		if(($lin2[IdTipoLocalCobranca] == 6 && $lin2[DataVencimento] <= date("Ymd")) || $lin2[IdTipoLocalCobranca] != 6){
			$sql	= "update ContaReceber set 
							IdLojaRemessa='$local_IdLoja',
							IdLocalCobrancaRemessa='$local_IdLocalCobranca',
							IdArquivoRemessa='$local_IdArquivoRemessa'
						where
							ContaReceber.IdLoja = $lin2[IdLoja] and
							ContaReceber.IdContaReceber = $lin2[IdContaReceber]";
			$local_transaction[$tr_i]	= mysql_query($sql,$con);
			if($local_transaction[$tr_i] == false){
				echo $sql." ".mysql_error();
			}
			$tr_i++;
			
			$sql	= "update ContaReceberPosicaoCobranca set 
							IdLojaRemessa = $local_IdLoja,
							IdLocalCobrancaRemessa='$local_IdLocalCobranca'
						where
							IdLoja = $lin2[IdLoja] and
							IdContaReceber = $lin2[IdContaReceber] and
							DataRemessa = '0000-00-00'";
			$local_transaction[$tr_i]	= mysql_query($sql,$con);
			if($local_transaction[$tr_i] == false){
				echo $sql." ".mysql_error();
			}
			$tr_i++;
			
			$QtdContaReceberEtapa2  = mysql_num_rows($res2);
		}
	}		
?>