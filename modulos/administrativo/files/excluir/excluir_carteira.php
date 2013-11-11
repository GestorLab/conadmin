<?
	$localModulo		=	1;
	$localOperacao		=	24;
	
	$local_IdLoja					=	$_SESSION["IdLoja"];	
	$local_IdCarteira				=	$_GET['IdCarteira'];
	$local_IdAgenteAutorizado		=	$_GET['IdAgenteAutorizado'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM Carteira WHERE IdLoja = $local_IdLoja and IdAgenteAutorizado=$local_IdAgenteAutorizado and IdCarteira=$local_IdCarteira;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
