<?
	$Dados[ValorLiquido] = $Dados[ValorTotal] - $Dados[ValorTotalTaxa];
			
	$sqlArquivoRetorno = "UPDATE ArquivoRetorno SET 
							ValorTotal='$Dados[ValorTotal]',
							ValorTotalTaxa='$Dados[ValorTotalTaxa]',
							ValorLiquido='$Dados[ValorLiquido]',
							LogRetorno = '$log',
							QtdRegistro = $Dados[QtdLancamentos]
						WHERE 
							IdLoja = $local_IdLoja AND 
							IdLocalCobranca = $local_IdLocalRecebimento AND 
							IdArquivoRetorno = $local_IdArquivoRetorno;";
	$local_transaction[$tr_i]	= mysql_query($sqlArquivoRetorno,$con);
	if($local_transaction[$tr_i] == false){		$lancamento_n_erro++;	}
	$tr_i++;
?>