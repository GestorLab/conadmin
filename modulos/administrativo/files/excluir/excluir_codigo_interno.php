<?
	$localModulo		=	1;
	$localOperacao		=	5;
	
	$local_IdLoja					=	$_SESSION["IdLoja"];
	$local_IdGrupoCodigoInterno		=	$_GET['IdGrupoCodigoInterno'];
	$local_IdCodigoInterno			=	$_GET['IdCodigoInterno'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if($_SESSION["Login"] != "root"){
		echo $local_Erro = 2;
		return false;	
	}
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		
		if($local_IdGrupoCodigoInterno == 13){
			$sql	=	"DELETE FROM CodigoInterno WHERE IdLoja = $local_IdLoja and IdGrupoCodigoInterno=14 and IdCodigoInterno=$local_IdCodigoInterno;";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		$sql	=	"DELETE FROM CodigoInterno WHERE IdLoja = $local_IdLoja and IdGrupoCodigoInterno=$local_IdGrupoCodigoInterno and IdCodigoInterno=$local_IdCodigoInterno;";
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
		}else{
			echo $local_Erro = 6;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
