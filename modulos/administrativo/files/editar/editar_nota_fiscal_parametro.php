<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
	
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);		
		$tr_i = 0;
		
		$sql = "SELECT 
					NotaFiscalLayoutParametro.IdNotaFiscalLayout,
					NotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro							
				FROM
					NotaFiscalLayoutParametro
				WHERE														
					NotaFiscalLayoutParametro.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
					Destino like '';";
		$res	=	mysql_query($sql,$con);
		while($lin	=	mysql_fetch_array($res)){
		
			$local_ValorTextFild 	= $_POST['Valor_'.$lin[IdNotaFiscalLayout].'_'.$lin[IdNotaFiscalLayoutParametro]];
			$local_ValorSelect 		= $_POST['OpcaoValor_'.$lin[IdNotaFiscalLayout].'_'.$lin[IdNotaFiscalLayoutParametro]];
			
			if($local_ValorTextFild != ""){
				$local_ValorNotaFiscalParametro = $local_ValorTextFild; 
			}
			if($local_ValorSelect != ""){
				$local_ValorNotaFiscalParametro = $local_ValorSelect; 
			}
			
			$sql2 	=	"
				INSERT INTO 
					NotaFiscalTipoParametro
				SET
					IdLoja 			 			    = '$local_IdLoja',
					IdNotaFiscalTipo 			    = '$local_IdNotaFiscalTipo',
					IdNotaFiscalLayout 				= '$lin[IdNotaFiscalLayout]',
					IdNotaFiscalLayoutParametro		= '$lin[IdNotaFiscalLayoutParametro]',
					Valor							= '$local_ValorNotaFiscalParametro';";
			
			$local_transaction[$tr_i]	=	mysql_query($sql2,$con);
			
			if($local_transaction[$tr_i] == false){
			
				$sql3	=	"
					UPDATE NotaFiscalTipoParametro SET 				
						Valor						= '$local_ValorNotaFiscalParametro'					
					WHERE 
						IdLoja						= $local_IdLoja and
						IdNotaFiscalTipo			= $local_IdNotaFiscalTipo and
						IdNotaFiscalLayout			= $lin[IdNotaFiscalLayout] and	
						IdNotaFiscalLayoutParametro	= $lin[IdNotaFiscalLayoutParametro];";
				$local_transaction[$tr_i]	=	mysql_query($sql3,$con);
							
			}
			$tr_i++;	
		}	
		
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

