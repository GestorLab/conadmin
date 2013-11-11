<?
	$localModulo		=	1;
	$localOperacao		=	42;
	
	$local_IdLoja 					= 	$_SESSION["IdLoja"];
	$local_IdLoteRepasse			=	$_GET['IdLoteRepasse'];
	$local_IdLancamentoFinanceiro	=	$_GET['IdLancamentoFinanceiro'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM LoteRepasseTerceiroItem WHERE IdLoja = $local_IdLoja and IdLoteRepasse=$local_IdLoteRepasse and IdLancamentoFinanceiro=$local_IdLancamentoFinanceiro;";
		if(@mysql_query($sql,$con) == true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
