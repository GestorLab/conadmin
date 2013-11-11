<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;

		$sql = "select
					distinct
					IdLoja,
					IdContaReceber,
					IdContaReceberRecebimento
				from
					ContaReceberRecebimento
				where
					IdLojaRecebimento=$local_IdLoja and 
					IdLocalCobranca=$local_IdLocalRecebimento and 
					IdArquivoRetorno=$local_IdArquivoRetorno";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			
			$sql2 = "select
						count(*) Qtd
					from
						ContaReceberRecebimento
					where
						IdLojaRecebimento = $lin[IdLoja] and
						IdContaReceber = $lin[IdContaReceber] and
						IdStatus != 0";
			$res2 = mysql_query($sql2,$con);
			$lin2 = mysql_fetch_array($res2);

			if($lin2[Qtd] <= 1){
				$sql3 = "update ContaReceber set 
							IdStatus='1' 
						where 
							IdLoja = '$lin[IdLoja]' and 
							IdContaReceber = '$lin[IdContaReceber]' and 
							IdStatus != 0 and 
							IdStatus != 7";
				$local_transaction[$tr_i]	=	mysql_query($sql3,$con);
				$tr_i++;				
			}	
			
			$sql = "delete from ContaReceberRecebimentoParametro where IdLoja=$local_IdLoja and IdContaReceber=$lin[IdContaReceber] and IdContaReceberRecebimento = $lin[IdContaReceberRecebimento]";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
			$tr_i++;
		}
	
		$sql = "delete from ContaReceberRecebimento where IdLojaRecebimento=$local_IdLoja and IdLocalCobranca=$local_IdLocalRecebimento and IdArquivoRetorno=$local_IdArquivoRetorno";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);	
		$tr_i++;

		$sql = "update ArquivoRetorno set IdStatus=1 where IdLoja=$local_IdLoja and IdLocalCobranca=$local_IdLocalRecebimento and IdArquivoRetorno=$local_IdArquivoRetorno";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";			
			mysql_query($sql,$con);
			header("Location: cadastro_arquivo_retorno.php?IdLocalRecebimento=$local_IdLocalRecebimento&IdArquivoRetorno=$local_IdArquivoRetorno");
		}else{
			$sql = "ROLLBACK;";
			mysql_query($sql,$con);
			$local_Erro = 132;
		}
	}
?>
