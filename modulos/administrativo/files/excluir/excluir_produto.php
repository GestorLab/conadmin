<?
	$localModulo		=	1;
	$localOperacao		=	46;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja		=	$_SESSION['IdLoja'];
	$local_IdProduto	=	$_GET['IdProduto'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
	
	
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql	=	"SELECT IdProdutoFoto,ExtFoto FROM ProdutoFoto WHERE IdLoja = $local_IdLoja and IdProduto=$local_IdProduto";
		$res	=	mysql_query($sql,$con);
		while($lin	=	mysql_fetch_array($res)){
			
			@unlink("../../img/produtos/$local_IdLoja/$local_IdProduto/$lin[IdProdutoFoto].$lin[ExtFoto]");
			@unlink("../../img/produtos/$local_IdLoja/$local_IdProduto/tumb/$lin[IdProdutoFoto].$lin[ExtFoto]");
			
			$sql	=	"DELETE FROM ProdutoFoto WHERE IdLoja = $local_IdLoja and IdProduto=$local_IdProduto and IdProdutoFoto=$lin[IdProdutoFoto];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		@rmdir("../../img/produtos/$local_IdLoja/$local_IdProduto/tumb");
		@rmdir("../../img/produtos/$local_IdLoja/$local_IdProduto");
		
		$sql	=	"DELETE FROM ProdutoSubGrupoProduto WHERE IdLoja = $local_IdLoja and IdProduto=$local_IdProduto;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"DELETE FROM Produto WHERE IdLoja = $local_IdLoja and IdProduto=$local_IdProduto;";
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
			echo $local_Erro = 33;			// Mensagem de Inserção Negativa
		}
		mysql_query($sql,$con);
	}
?>
