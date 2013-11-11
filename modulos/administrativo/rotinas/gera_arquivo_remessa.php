<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{		
	
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;

		$sql = "select
					IdArquivoRemessaTipo
				from
					ArquivoRemessa
				where
					ArquivoRemessa.IdLoja = $local_IdLoja and
					ArquivoRemessa.IdLocalCobranca = $local_IdLocalCobranca and
					ArquivoRemessa.IdArquivoRemessa = $local_IdArquivoRemessa and
					ArquivoRemessa.IdStatus = 1";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$fileLayoutRemessa = "layout_remessa/$lin[IdArquivoRemessaTipo]/remessa.php";

		if(file_exists($fileLayoutRemessa)){

			include($fileLayoutRemessa);

		}
		
/*		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - $ContContaReceber conta(s) à receber gerado(s).";

		$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
										IdStatus=3,
										LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento),
										LoginConfirmacao='$local_Login',
										DataConfirmacao=concat(curdate(),' ',curtime())
									  WHERE 
										IdLoja=$local_IdLoja AND 
										IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
		$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
		$tr_i++;*/
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
	
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 47;
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 50;
		}
		mysql_query($sql,$con);
	}	
?>
