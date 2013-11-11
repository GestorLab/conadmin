<?
	$localModulo		=	1;
	$localOperacao		=	2;
	
	$local_IdLoja		=	$_SESSION["IdLoja"];
	$local_IdContrato	=	$_GET['IdContrato'];
	$local_DataInicio	=	$_GET['DataInicioVigencia'];
		
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sql	=	"DELETE FROM ContratoVigencia WHERE IdLoja = $local_IdLoja and IdContrato=$local_IdContrato and DataInicio='$local_DataInicio'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		// Executa a Sql de Inserção de Codigo Interno
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			echo $local_Erro = 7;		
			$sql = "COMMIT;";
		}else{
			echo $local_Erro = 6;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
