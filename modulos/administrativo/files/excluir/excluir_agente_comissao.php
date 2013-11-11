<?
	$localModulo		=	1;
	$localOperacao		=	34;
	
	$local_IdLoja					=	$_SESSION["IdLoja"];	
	$local_IdAgenteAutorizado		=	$_GET['IdAgenteAutorizado'];
	$local_IdServico				=	$_GET['IdServico'];
	$local_Parcela					=	$_GET['Parcela'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM ComissionamentoAgenteAutorizado WHERE IdLoja = $local_IdLoja and IdAgenteAutorizado=$local_IdAgenteAutorizado and IdServico = $local_IdServico and Parcela = $local_Parcela;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
