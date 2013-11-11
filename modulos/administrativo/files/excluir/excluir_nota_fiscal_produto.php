<?
	$localModulo		=	1;
	$localOperacao		=	56;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja					=	$_SESSION['IdLoja'];
	$local_IdMovimentacaoProduto	=	$_GET['IdMovimentacaoProduto'];
	$local_IdProduto				=	$_GET['IdProduto'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sql	=	"DELETE FROM MovimentacaoProdutoItemSerie WHERE IdLoja = $local_IdLoja and IdMovimentacaoProduto=$local_IdMovimentacaoProduto and IdProduto = $local_IdProduto;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"DELETE FROM MovimentacaoProdutoItem WHERE IdLoja = $local_IdLoja and IdMovimentacaoProduto=$local_IdMovimentacaoProduto and IdProduto = $local_IdProduto;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			echo $local_Erro = 7;			// Mensagem de Inserção Positiva
		}else{
			$sql = "ROLLBACK;";
			echo $local_Erro = 6;			// Mensagem de Inserção Negativa
		}
		mysql_query($sql,$con);
	}
?>
