<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_nota_fiscal_produto_serie(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdMovimentacaoProduto	= $_GET['IdMovimentacaoProduto'];
		$IdProduto				= $_GET['IdProduto'];
		$NumeroSerie			= $_GET['NumeroSerie'];
		
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdMovimentacaoProduto != ''){	
			$where .= " and MovimentacaoProdutoItemSerie.IdMovimentacaoProduto!=$IdMovimentacaoProduto";	
		}
		if($IdProduto != ''){	
			$where .= " and MovimentacaoProdutoItemSerie.IdProduto=$IdProduto";	
		}
		if($NumeroSerie != ''){	
			$NumeroSerie	=	str_replace("\n",",",$NumeroSerie);
			
			$where .= " and MovimentacaoProdutoItemSerie.NumeroSerie in ($NumeroSerie)";	
		}
	
		$sql	=	"select
							count(IdMovimentacaoProduto) IdMovimentacaoProduto
						from 
							MovimentacaoProdutoItemSerie
						where
							MovimentacaoProdutoItemSerie.IdLoja = $IdLoja $where
						group by
							IdMovimentacaoProduto $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdMovimentacaoProduto><![CDATA[$lin[IdMovimentacaoProduto]]]></IdMovimentacaoProduto>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_nota_fiscal_produto_serie();
?>
