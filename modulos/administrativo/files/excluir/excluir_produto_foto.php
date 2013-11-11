<?
	$localModulo		=	1;
	$localOperacao		=	46;
	$pathHost = "../../../../";
	
	include ($pathHost."files/conecta.php");
	include ($pathHost."files/funcoes.php");
	include ($pathHost."rotinas/verifica.php");
	
	$local_IdLoja			=	$_SESSION['IdLoja'];
	$local_IdProduto		=	$_GET['IdProduto'];
	$local_IdProdutoFoto	=	$_GET['IdProdutoFoto'];

	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		$local_Erro = "2";
	}else{
		//$local_IdProdutoFoto	= 	reset(explode(".",endArray(explode("/",$local_IdProdutoFoto))));
		$local_IdProdutoFoto 	= 	explode(".",endArray(explode("/",$local_IdProdutoFoto)));
		$local_IdProdutoFoto 	=	$local_IdProdutoFoto[0]; 
		
		$sql	=	"select  
						    ProdutoFoto.ExtFoto 
						from 
						    Produto, 
							ProdutoFoto						    
						where 
						    Produto.IdLoja = $local_IdLoja and
						    Produto.IdLoja = ProdutoFoto.IdLoja and 
							ProdutoFoto.IdProduto = Produto.IdProduto and 
						    ProdutoFoto.IdProduto=$local_IdProduto and 
						    ProdutoFoto.IdProdutoFoto=$local_IdProdutoFoto";
		$res	=	mysql_query($sql,$con);
		if($lin	=	mysql_fetch_array($res)){
			$sql	=	"DELETE FROM ProdutoFoto WHERE ProdutoFoto.IdLoja = $local_IdLoja and ProdutoFoto.IdProduto=$local_IdProduto and ProdutoFoto.IdProdutoFoto=$local_IdProdutoFoto;";
			if(@mysql_query($sql,$con) == true){
				@unlink($pathHost."img/produtos/$local_IdLoja/$local_IdProduto/$local_IdProdutoFoto.$lin[ExtFoto]");
				@unlink($pathHost."img/produtos/$local_IdLoja/$local_IdProduto/tumb/$local_IdProdutoFoto.$lin[ExtFoto]");
				$local_Erro =	"91";
			}else{
				$local_Erro = "6";
			}
			
		}
	}
	echo $local_Erro;
?>
