<?
	$localModulo		=	1;
	$localOperacao		=	15;
	
	$local_IdPais		=	$_GET['IdPais'];
	$local_IdEstado		=	$_GET['IdEstado'];
	$local_IdCidade		=	$_GET['IdCidade'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM Cidade WHERE IdPais=$local_IdPais and IdEstado=$local_IdEstado and IdCidade=$local_IdCidade;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 6;
		}
	}
?>
