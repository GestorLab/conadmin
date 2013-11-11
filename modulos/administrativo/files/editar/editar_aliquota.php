<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
	
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);		
		$tr_i = 0;
		
		$sql	=	"select 
						IdEstado
					from 
						Estado
					where 
						IdPais = $local_IdPais;";
		$res	=	mysql_query($sql,$con);
		while($lin	=	mysql_fetch_array($res)){	
			
			$local_AliquotaICMS 				= $_POST['AliquotaICMS_'.$local_IdPais.'_'.$lin[IdEstado]];
			$local_FatorBaseCalculoAliquotaICMS = $_POST['FatorBaseCalculoAliquotaICMS_'.$local_IdPais.'_'.$lin[IdEstado]];
			
			if($local_AliquotaICMS == ''){
				$local_AliquotaICMS = "NULL";
			} else{
				$local_AliquotaICMS	= str_replace(".", "", $local_AliquotaICMS);
				$local_AliquotaICMS = "'".str_replace(",", ".",	$local_AliquotaICMS)."'";
			}	
			
			$local_FatorBaseCalculoAliquotaICMS 	= str_replace(".", "", $local_FatorBaseCalculoAliquotaICMS);
			$local_FatorBaseCalculoAliquotaICMS 	= str_replace(",", ".",	$local_FatorBaseCalculoAliquotaICMS);
			
			$sql2	=	"INSERT INTO 
							ServicoAliquota 
						SET 
							IdLoja							= '$local_IdLoja',
							IdServico						= '$local_IdServico',	
							IdPais							= '$local_IdPais',
							IdEstado						= '$lin[IdEstado]',	
							IdAliquotaTipo					=  $local_IdAliquotaTipo,					
							Aliquota						=  $local_AliquotaICMS,
							FatorBaseCalculoAliquota		= '$local_FatorBaseCalculoAliquotaICMS';";	
			$local_transaction[$tr_i]	=	mysql_query($sql2,$con);
			
			if($local_transaction[$tr_i] == false){
				$sql3	=	"
					UPDATE ServicoAliquota SET 
							IdLoja							= '$local_IdLoja',
							IdAliquotaTipo					=  $local_IdAliquotaTipo,														
							Aliquota						=  $local_AliquotaICMS,
							FatorBaseCalculoAliquota		= '$local_FatorBaseCalculoAliquotaICMS'
						WHERE 
							IdLoja							= $local_IdLoja and
							IdServico						= $local_IdServico and
							IdEstado						= $lin[IdEstado];";	
				$local_transaction[$tr_i]	=	mysql_query($sql3,$con);
			}
			$tr_i++;
		}
		
		$sql	=	"
				UPDATE Servico SET 				
					DataAlteracao				= (concat(curdate(),' ',curtime())),
					LoginAlteracao				= '$local_Login'
				WHERE 
					IdLoja						= $local_IdLoja and
					IdServico					= $local_IdServico;";	
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;	
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}	
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 4;			// Mensagem de Alteração Positiva
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;			// Mensagem de Alteração Negativa
		}
		mysql_query($sql,$con);
	}
?>
