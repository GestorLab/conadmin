<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"C") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$sqlLancamentoFinanceiro = "select
										LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro,
										LancamentoFinanceiroContaReceber.IdContaReceber
									from
										LancamentoFinanceiro,
										LancamentoFinanceiroContaReceber
									where
										LancamentoFinanceiro.IdLoja = $local_IdLoja and
										LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
										LancamentoFinanceiro.IdContaEventual = $local_IdContaEventual and
										LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro";
		$resLancamentoFinanceiro = mysql_query($sqlLancamentoFinanceiro,$con);
		while($linLancamentoFinanceiro = mysql_fetch_array($resLancamentoFinanceiro)){
			$sql = "delete from LancamentoFinanceiroContaReceber where IdLoja=$local_IdLoja and IdLancamentoFinanceiro=$linLancamentoFinanceiro[IdLancamentoFinanceiro] and IdContaReceber=$linLancamentoFinanceiro[IdContaReceber]";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);				
			$tr_i++;
		
			$sql = "delete from LancamentoFinanceiro where IdLoja=$local_IdLoja and IdLancamentoFinanceiro=$linLancamentoFinanceiro[IdLancamentoFinanceiro]";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
			$tr_i++;			
			
			
			$sql = "delete from ContaReceberVencimento where IdLoja=$local_IdLoja and IdContaReceber=$linLancamentoFinanceiro[IdContaReceber]";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
			$tr_i++;
			
			$sql = "delete from HistoricoEmail where IdLoja=$local_IdLoja and IdContaReceber=$linLancamentoFinanceiro[IdContaReceber]";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$sql = "delete from ContaReceber where IdLoja=$local_IdLoja and IdContaReceber=$linLancamentoFinanceiro[IdContaReceber]";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);						
			$tr_i++;		
		}
		
		$sql	=	"UPDATE ContaEventual SET 
							IdStatus					= 0,
							LoginAlteracao				='$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja						= $local_IdLoja and
							IdContaEventual				= '$local_IdContaEventual'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);	
		$tr_i++;

		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
	
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 75;
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 76;
		}
		mysql_query($sql,$con);
	}
?>
