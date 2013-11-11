<?
	$localModulo		=	1;
	$localOperacao		=	10;
	
	$local_IdGrupoPermissao	=	$_GET['IdGrupoPermissao'];
	$local_IdLoja			=	$_SESSION['IdLoja'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;	
		
		$sql	=	"DELETE FROM GrupoPermissaoSubOperacao WHERE IdLoja=$local_IdLoja and IdGrupoPermissao=$local_IdGrupoPermissao;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"DELETE FROM UsuarioGrupoPermissao WHERE IdGrupoPermissao=$local_IdGrupoPermissao;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		$sql	=	"DELETE FROM GrupoPermissao WHERE IdGrupoPermissao=$local_IdGrupoPermissao;";
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
			echo $local_Erro = 33;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
