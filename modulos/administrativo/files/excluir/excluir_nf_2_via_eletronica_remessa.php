<?
	$localModulo		=	1;
	$localOperacao		=	110;
		
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja					=	$_SESSION["IdLoja"];
	$local_IdNotaFiscalLayout		=	$_GET['IdNotaFiscalLayout'];
	$local_MesReferencia			=	$_GET['MesReferencia'];
	$local_Status					=	$_GET['Status'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM NotaFiscal2ViaEletronicaArquivo WHERE IdLoja = $local_IdLoja and IdNotaFiscalLayout=$local_IdNotaFiscalLayout and MesReferencia like '$local_MesReferencia'  and Status like '$local_Status';";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 6;
		}
	}
?>