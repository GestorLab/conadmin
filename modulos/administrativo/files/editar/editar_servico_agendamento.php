<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$local_IdLoja = $_SESSION["IdLoja"];
		
		if($local_IdServico == ""){
			$local_IdServico = 'NULL';
		}
		
		/* Sql de alterações. */
		$sql = "START TRANSACTION;";
		mysql_query($sql, $con);
		$tr_i = 0;
		
		if($local_IdTipoServico == 1){
			if($local_IdStatus == ""){
				$local_IdStatus = 'NULL';
			}
			
			if($local_IdQtdMeses == ""){
				$local_IdQtdMeses = 'NULL';
			}
			
			if($local_IdNovoStatus == ""){
				$local_IdNovoStatus = 'NULL';
			}
			
			if($local_IdNovoStatus != 201 && $local_IdNovoStatus != 306){
				$local_QtdDias = "NULL";
			}
			
			$sql = "
				UPDATE ServicoAgendamento SET
					QtdMes						= $local_IdQtdMeses,
					IdStatus					= $local_IdStatus,
					IdNovoStatus				= $local_IdNovoStatus,
					LoginAlteracao				= '$local_Login',
					DataAlteracao				= (concat(curdate(),' ',curtime()))
				WHERE
					IdLoja = $local_IdLoja and 
					IdServico = $local_IdServico and 
					QtdMes = $local_IdQtdMeses;";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
			
			$sql = "
				UPDATE Servico SET
					BaseDataStatusContratoOS	=  $local_QtdDias,
					LoginAlteracao				= '$local_Login',
					DataAlteracao				= (concat(curdate(),' ',curtime()))
				WHERE
					IdLoja = '$local_IdLoja' and 
					IdServico = '$local_IdServico';";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
		} else{
			if($local_MudarStatusContratoConcluirOS != 201 && $local_MudarStatusContratoConcluirOS != 306){
				$local_QtdDias = "NULL";
			}
			
			if($local_MudarStatusContratoConcluirOS == ""){
				$local_MudarStatusContratoConcluirOS = 'NULL';
			}
			
			$sql = "
				UPDATE Servico SET
					MudarStatusContratoConcluirOS	=  $local_MudarStatusContratoConcluirOS,
					BaseDataStatusContratoOS		=  $local_QtdDias,
					LoginAlteracao					= '$local_Login',
					DataAlteracao					= (concat(curdate(),' ',curtime()))
				WHERE
					IdLoja = '$local_IdLoja' and 
					IdServico = '$local_IdServico';";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
		}
		
		for($i = 0; $i < $tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 4;
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;
		}
		
		mysql_query($sql, $con);
	}
?>