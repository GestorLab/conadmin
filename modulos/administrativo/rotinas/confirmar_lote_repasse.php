<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
	
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$sqlLanc = "select
						sum(Demonstrativo.Valor) ValorTotal,
						sum(LoteRepasseTerceiroItem.ValorItemRepasse) ValorTotalRepasse
					from
						LoteRepasseTerceiroItem,
						Demonstrativo
					where
						LoteRepasseTerceiroItem.IdLoja = $local_IdLoja and
						LoteRepasseTerceiroItem.IdLoja = Demonstrativo.IdLoja and
						LoteRepasseTerceiroItem.IdLoteRepasse = $local_IdLoteRepasse and
						LoteRepasseTerceiroItem.IdLancamentoFinanceiro = Demonstrativo.IdLancamentoFinanceiro";
		$resLanc = mysql_query($sqlLanc,$con);
		$linLanc = mysql_fetch_array($resLanc);

		$sql	=	"UPDATE LoteRepasseTerceiro SET
							IdStatus			= 3,	
							DataConfirmacao		= concat(curdate(),' ',curtime()),
							LoginConfirmacao	= '$local_Login',
							ValorTotalItens		= '$linLanc[ValorTotal]',
							ValorTotalRepasse	= '$linLanc[ValorTotalRepasse]' 
					 WHERE 
							IdLoja			= $local_IdLoja and
							IdLoteRepasse	= $local_IdLoteRepasse";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
	
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 87;
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 88;
		}
		mysql_query($sql,$con);
	}
?>
