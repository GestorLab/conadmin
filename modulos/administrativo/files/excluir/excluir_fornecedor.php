<?
	$localModulo		=	1;
	$localOperacao		=	51;
	
	$local_IdLoja		=	$_SESSION["IdLoja"];	
	$local_IdPessoa		=	$_GET['IdPessoa'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM Fornecedor WHERE IdLoja = $local_IdLoja and IdFornecedor=$local_IdPessoa;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
