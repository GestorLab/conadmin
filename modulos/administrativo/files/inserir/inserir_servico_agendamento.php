<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$local_IdLoja		=	$_SESSION["IdLoja"];
		
		if($local_IdServico == ""){
			$local_IdServico = 'NULL';
		}
		
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
		
		/* Sql de alterações. */
		$sql = "START TRANSACTION;";
		mysql_query($sql, $con);
		$tr_i = 0;
		
		$sql = "
			INSERT INTO ServicoAgendamento SET
				IdLoja			= $local_IdLoja,
				IdServico		= $local_IdServico,
				QtdMes			= $local_IdQtdMeses,
				IdStatus		= $local_IdStatus,
				IdNovoStatus	= $local_IdNovoStatus,
				LoginCriacao	='$local_Login', 
				DataCriacao		=(concat(curdate(),' ',curtime())),
				LoginAlteracao	= NULL,
				DataAlteracao	= NULL;";
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
		
		for($i = 0; $i < $tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Acao = "alterar";
			$local_Erro = 3;
		} else{
			$sql = "ROLLBACK;";
			$local_Acao = "inserir";
			$local_Erro = 8;
		}
		
		mysql_query($sql, $con);
	}
?>