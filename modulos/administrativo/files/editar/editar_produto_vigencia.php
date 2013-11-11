<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$local_Valor			=	str_replace(".", "", $local_Valor);	
		$local_Valor			= 	str_replace(",", ".", $local_Valor);
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		
		if($local_DataTermino != ''){ 	
			$local_DataTermino			= 	"'".dataConv($local_DataTermino,'d/m/Y','Y-m-d')."'";
		}else{
			$local_DataTermino			=  	'NULL';
		}
		
		if($local_ValorDesconto != ''){ 	
			$local_ValorDesconto		=	str_replace(".", "", $local_ValorDesconto);	
			$local_ValorDesconto		= 	str_replace(",", ".", $local_ValorDesconto);
			$local_ValorDesconto		= 	"'".$local_ValorDesconto."'";
		}else{
			$local_ValorDesconto		=  	'NULL';
		}
				
		$sql	=	"
				UPDATE ProdutoVigencia SET 
					IdProdutoTipoVigencia		= $local_IdProdutoTipoVigencia,
					ValorDesconto				= $local_ValorDesconto, 
					DataTermino					= $local_DataTermino,
					Valor						= $local_ValorDesconto,
					DataLimiteDesconto			= '$local_DataLimiteDesconto',
					DataAlteracao				= (concat(curdate(),' ',curtime())),
					LoginAlteracao				= '$local_Login'
				WHERE 
					IdLoja						= $local_IdLoja and
					IdProduto					= $local_IdProduto and
					DataInicio 					= '".dataConv($local_DataInicio,'d/m/Y','Y-m-d')."';";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		// Executa a Sql de Inserção de ProdutoVigencia
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$local_Erro = 4;		// Mensagem de Alteração Positiva
			$sql = "COMMIT;";
		}else{
			$local_Erro = 5;		// Mensagem de Alteração Negativa
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
