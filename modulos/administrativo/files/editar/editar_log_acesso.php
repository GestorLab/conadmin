<?
	
	$localModulo		=	1;
	$localOperacao		=	36;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja		=	$_SESSION['IdLoja'];	
	$local_IdLogAcesso	=	$_GET['IdLogAcesso'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"UPDATE LogAcesso SET
							Fechada			= '1'
					 WHERE 
							IdLoja			= $local_IdLoja
							and IdLogAcesso = '$local_IdLogAcesso'";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 117;
		}else{
			echo $local_Erro = 118;
		}
	}
?>
