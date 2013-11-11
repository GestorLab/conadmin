<?
	$localModulo		=	1;
	$localOperacao		=	19;
	
	$local_IdLoja			=	$_SESSION["IdLoja"];
	$local_IdServicoGrupo	=	$_GET['IdServicoGrupo'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM ServicoGrupo WHERE IdLoja = $local_IdLoja and IdServicoGrupo=$local_IdServicoGrupo;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
