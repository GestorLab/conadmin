<?
	$localModulo		=	1;
	$localOperacao		=	28;
	
	$local_IdLoja					=	$_SESSION["IdLoja"];	
	$local_DataHoraAgendamento		=	$_GET['DataHoraAgendamento'];
	$local_IdOrdemServico			=	$_GET['IdOrdemServico'];
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM AgendamentoOrdemServico WHERE IdLoja='$local_IdLoja' and IdOrdemServico=$local_IdOrdemServico and DataHoraAgendamento='$local_DataHoraAgendamento';";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 6;
		}
	}
?>
