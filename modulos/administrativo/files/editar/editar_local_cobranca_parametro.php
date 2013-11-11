<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
	
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);		
		$tr_i = 0;
		
		$sql = "SELECT 
					LocalCobrancaLayoutParametro.IdLocalCobrancaLayout,
					LocalCobrancaLayoutParametro.IdLocalCobrancaParametro							
				FROM
					LocalCobrancaLayoutParametro
				WHERE														
					LocalCobrancaLayoutParametro.IdLocalCobrancaLayout = $local_IdLocalCobrancaLayout
				";
		$res	=	mysql_query($sql,$con);
		while($lin	=	mysql_fetch_array($res)){
		
			$local_ValorLocalCobrancaParametro 	= $_POST['ValorLocalCobrancaParametro_'.$lin[IdLocalCobrancaLayout].'_'.$lin[IdLocalCobrancaParametro]];

			$sql2 	=	"
				INSERT INTO 
					LocalCobrancaParametro
				SET
					IdLoja 			 			    = '$local_IdLoja',
					IdLocalCobranca 			    = '$local_IdLocalCobranca',
					IdLocalCobrancaLayout 			= '$lin[IdLocalCobrancaLayout]',
					IdLocalCobrancaParametro		= '$lin[IdLocalCobrancaParametro]',
					ValorLocalCobrancaParametro		= '$local_ValorLocalCobrancaParametro'";
			
			$local_transaction[$tr_i]	=	mysql_query($sql2,$con);
			
			if($local_transaction[$tr_i] == false){
				$sqlHistoricoObs = "SELECT
										IdLoja,
										IdLocalCobranca,
										IdLocalCobrancaLayout,
										IdLocalCobrancaParametro,
										ValorLocalCobrancaParametro,
										LogParametro
									FROM 
										LocalCobrancaParametro
									WHERE
										IdLoja						= $local_IdLoja and
										IdLocalCobranca				= $local_IdLocalCobranca and
										IdLocalCobrancaParametro	like '$lin[IdLocalCobrancaParametro]'";
				$resHistoricoObs = mysql_query($sqlHistoricoObs, $con);
				$linHistoricoObs = mysql_fetch_array($resHistoricoObs);
				
				$histocoObs = "";
				
				if($local_ValorLocalCobrancaParametro != $linHistoricoObs[ValorLocalCobrancaParametro]){
					$histocoObs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alterou Valor: ".$linHistoricoObs[ValorLocalCobrancaParametro]." > ".$local_ValorLocalCobrancaParametro."<br>";
				}
				$histocoObs .= $linHistoricoObs[LogParametro];
				$sql3	=	"
						UPDATE LocalCobrancaParametro SET 				
							ValorLocalCobrancaParametro	= '$local_ValorLocalCobrancaParametro',
							LogParametro				= '$histocoObs'
						WHERE 
							IdLoja						= $local_IdLoja and
							IdLocalCobranca				= $local_IdLocalCobranca and
							IdLocalCobrancaParametro	like '$lin[IdLocalCobrancaParametro]';";
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
