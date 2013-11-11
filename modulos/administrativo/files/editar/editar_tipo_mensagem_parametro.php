<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{	
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);		
		$tr_i = 0;
		
		$sql = "SELECT 
					IdTipoMensagem,
					IdTipoMensagemParametro							
				FROM
					TipoMensagemParametro
				WHERE									
					IdLoja = $local_IdLoja and
					IdTipoMensagem = $local_IdTipoMensagem";
		$res	=	mysql_query($sql,$con);
		while($lin	=	mysql_fetch_array($res)){
		
			$local_ValorTipoMensagemParametro 	= $_POST['ValorTipoMensagemParametro_'.$lin[IdTipoMensagem].'_'.$lin[IdTipoMensagemParametro]];
					
			$sql2	=	"	UPDATE TipoMensagemParametro SET 				
								ValorTipoMensagemParametro	= '$local_ValorTipoMensagemParametro'			
							WHERE 
								IdLoja						= $local_IdLoja and
								IdTipoMensagem				= $lin[IdTipoMensagem] and
								IdTipoMensagemParametro		= '$lin[IdTipoMensagemParametro]'";
			$local_transaction[$tr_i]	=	mysql_query($sql2,$con);			
			$tr_i++;	
		}	
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}	
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 4;			// Mensagem de Alteraчуo Positiva
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;			// Mensagem de Alteraчуo Negativa
		}
		mysql_query($sql,$con);
	}
?>