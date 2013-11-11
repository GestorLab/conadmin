<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"C")){
		$local_Erro = 2;
	} else{
		$sql = "start transaction;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "delete from MalaDiretaEmail where IdLoja = $local_IdLoja and IdMalaDireta = $local_IdMalaDireta;";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "update MalaDireta set 
					IdStatus			= 1,
					LoginProcessamento	= NULL, 
					DataProcessamento	= NULL
				where
					IdLoja = '$local_IdLoja' and
					IdMalaDireta = '$local_IdMalaDireta';";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		for($i = 0; $i < $tr_i; $i++){
			if(!$local_transaction[$i]){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction){
			$sql = "commit;";
			$local_Erro = 67;
		} else{
			$sql = "rollback;";
			$local_Erro = 68;
		}
		
		@mysql_query($sql,$con);
	}
?>