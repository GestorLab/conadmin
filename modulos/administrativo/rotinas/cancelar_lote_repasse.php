<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"C") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;	

		
		$sql	=	"DELETE FROM LoteRepasseTerceiroItem WHERE IdLoja =	$local_IdLoja and IdLoteRepasse = $local_IdLoteRepasse";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"UPDATE LoteRepasseTerceiro SET
							IdStatus			= 1,
							ValorTotalItens		= 0,
							DataProcessamento	= NULL,
							LoginProcessamento	= NULL
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
			$local_Erro = 48;
			$sql = "COMMIT;";
		}else{
			$local_Erro = 68;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}	
?>