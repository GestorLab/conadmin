<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_produto_tabela_preco(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdProduto		 		= $_GET['IdProduto'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdProduto != ''){	
			$where .= " and IdProduto=$IdProduto";	
		}
		
		$sql	=	"select 
						IdProduto,
						IdTabelaPreco,
						IdFormaPagamento,
						ValorPrecoMinimo,
						ValorPreco 
					from 
						ProdutoTabelaPreco 
					where 
						IdLoja = $IdLoja $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdProduto>$lin[IdProduto]</IdProduto>";
			$dados	.=	"\n<IdTabelaPreco><![CDATA[$lin[IdTabelaPreco]]]></IdTabelaPreco>";
			$dados	.=	"\n<IdFormaPagamento><![CDATA[$lin[IdFormaPagamento]]]></IdFormaPagamento>";
			$dados	.=	"\n<ValorPrecoMinimo><![CDATA[$lin[ValorPrecoMinimo]]]></ValorPrecoMinimo>";
			$dados	.=	"\n<ValorPreco><![CDATA[$lin[ValorPreco]]]></ValorPreco>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_produto_tabela_preco();
?>
