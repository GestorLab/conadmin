<?
	$localModulo		=	1;
	$localOperacao		=	16;
	
	$local_IdLoja					=	$_SESSION["IdLoja"];	
	$local_IdContratoTipoVigencia	=	$_GET['IdContratoTipoVigencia'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM ContratoTipoVigencia WHERE IdLoja = $local_IdLoja and IdContratoTipoVigencia=$local_IdContratoTipoVigencia;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
