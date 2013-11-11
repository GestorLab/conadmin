<?
	$localModulo		=	1;
	$localOperacao		=	35;
	
	$local_IdLoja					=	$_SESSION["IdLoja"];	
	$local_IdAgenteAutorizado		=	$_GET['IdAgenteAutorizado'];
	$local_IdCarteira				=	$_GET['IdCarteira'];
	$local_IdServico				=	$_GET['IdServico'];
	$local_Parcela					=	$_GET['Parcela'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM ComissionamentoCarteira WHERE IdLoja = $local_IdLoja and IdAgenteAutorizado=$local_IdAgenteAutorizado and IdCarteira = $local_IdCarteira and IdServico = $local_IdServico and Parcela = $local_Parcela;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
