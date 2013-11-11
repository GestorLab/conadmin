<?
	$localModulo		=	1;
	$localOperacao		=	30;
	
	$local_IdLoja			=	$_SESSION["IdLoja"];	
	$local_IdLocalCobranca	=	$_GET['IdLocalCobranca'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;	
	
		$sql	=	"DELETE FROM LocalCobrancaParametro WHERE IdLoja = $local_IdLoja and IdLocalCobranca=$local_IdLocalCobranca;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql 	= 	"SELECT ExtLogo FROM LocalCobranca WHERE IdLocalCobranca = $local_IdLocalCobranca";
		$res 	= 	mysql_query($sql,$con);
		$lin 	= 	mysql_fetch_array($res);
		$local_transaction[$tr_i] = $res;		
		$tr_i++;								
		
		$sql	=	"DELETE FROM LocalCobranca WHERE IdLoja = $local_IdLoja and IdLocalCobranca=$local_IdLocalCobranca;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){	
			echo $local_Erro = 7;			
			$sql = "COMMIT;";			
			if(file_exists("../../local_cobranca/personalizacao/$local_IdLocalCobranca.$lin[ExtLogo]")){
				if(!unlink("../../local_cobranca/personalizacao/$local_IdLocalCobranca.$lin[ExtLogo]")){
					$sql = "ROLLBACK;";	
				}				
			}
			
		}else{
			echo $local_Erro = 33;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
