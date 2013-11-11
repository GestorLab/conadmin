<?
	$localModulo		=	1;
	$localOperacao		=	3;
	
	$local_IdLoja				=	$_SESSION["IdLoja"];
	$local_IdProcessoFinanceiro	=	$_GET['IdProcessoFinanceiro'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql = "select IdStatus from ProcessoFinanceiro where IdLoja=$local_IdLoja and IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);		
		
		if($lin[IdStatus] == 1){
			$sql	=	"DELETE FROM ProcessoFinanceiro WHERE IdLoja=$local_IdLoja and IdProcessoFinanceiro=$local_IdProcessoFinanceiro;";
			if(mysql_query($sql,$con)==true){
				echo $local_Erro = 7;
			}else{
				echo $local_Erro = 6;
			}
		}else{
			echo $local_Erro = 46;
		}
	}
?>
