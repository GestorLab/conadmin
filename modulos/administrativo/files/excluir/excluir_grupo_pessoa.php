<?
	$localModulo		=	1;
	$localOperacao		=	12;
	
	$local_IdGrupoPessoa		=	$_GET['IdGrupoPessoa'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM GrupoPessoa WHERE IdLoja=$local_IdLoja and IdGrupoPessoa=$local_IdGrupoPessoa;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
