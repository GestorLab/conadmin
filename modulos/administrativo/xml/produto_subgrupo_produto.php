<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ProdutoSubGrupoProduto(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdProduto	 			= $_GET['IdProduto'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdProduto != ''){	
			$where .= " and ProdutoSubGrupoProduto.IdProduto=$IdProduto";	
		}
		
		$sql	=	"select
						IdGrupoProduto, 
						IdSubGrupoProduto
					from 
						ProdutoSubGrupoProduto
					where
						ProdutoSubGrupoProduto.IdLoja = $IdLoja  $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdGrupoProduto><![CDATA[$lin[IdGrupoProduto]]]></IdGrupoProduto>";
			$dados	.=	"\n<IdSubGrupoProduto><![CDATA[$lin[IdSubGrupoProduto]]]></IdSubGrupoProduto>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_ProdutoSubGrupoProduto();
?>
