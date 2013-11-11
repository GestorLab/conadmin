<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_produto(){
		
		global $con;
		global $_GET;
		
		$IdLoja						= $_SESSION['IdLoja'];
		$Limit 						= $_GET['Limit'];
		$IdProduto   				= $_GET['IdProduto'];
		$DescricaoProduto  	    	= $_GET['DescricaoProduto'];
		$DescricaoFabricante    	= $_GET['DescricaoFabricante'];
		$DescricaoGrupoProduto  	= $_GET['DescricaoGrupoProduto'];
		$DescricaoSubGrupoProduto   = $_GET['DescricaoSubGrupoProduto'];
		$where						= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdProduto !=''){					$where .= " and Produto.IdProduto = $IdProduto";	}
		if($DescricaoProduto !=''){				$where .= " and DescricaoProduto like '$DescricaoProduto%'";	}
		if($DescricaoFabricante !=''){			$where .= " and DescricaoFabricante like '$DescricaoFabricante%'";	}
		if($DescricaoGrupoProduto !=''){		$where .= " and DescricaoGrupoProduto like '$DescricaoGrupoProduto%'";	}
		if($DescricaoSubGrupoProduto !=''){		$where .= " and DescricaoSubGrupoProduto like '$DescricaoSubGrupoProduto%'";	}
		
		$sql	=	"select
						Produto.IdProduto,
						Produto.DescricaoReduzidaProduto,
						Fabricante.DescricaoFabricante
					from 
						Produto left join (select ProdutoSubGrupoProduto.IdProduto,	DescricaoGrupoProduto, DescricaoSubGrupoProduto from Produto, ProdutoSubGrupoProduto, GrupoProduto,	SubGrupoProduto where ProdutoSubGrupoProduto.IdLoja = $IdLoja and ProdutoSubGrupoProduto.IdLoja = GrupoProduto.IdLoja and ProdutoSubGrupoProduto.IdLoja = SubGrupoProduto.IdLoja and ProdutoSubGrupoProduto.IdLoja = Produto.IdLoja and ProdutoSubGrupoProduto.IdGrupoProduto = GrupoProduto.IdGrupoProduto and ProdutoSubGrupoProduto.IdSubGrupoProduto = SubGrupoProduto.IdSubGrupoProduto and ProdutoSubGrupoProduto.IdProduto = Produto.IdProduto and GrupoProduto.IdGrupoProduto = SubGrupoProduto.IdGrupoProduto) ProdutoSubGrupoProduto ON (Produto.IdProduto = ProdutoSubGrupoProduto.IdProduto), 
						Fabricante
					where   
						Produto.IdLoja = $IdLoja and
						Produto.IdLoja = Fabricante.IdLoja and 
						Produto.IdFabricante = Fabricante.IdFabricante 
						$where
					group by 
						Produto.IdProduto $Limit";
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
			$dados	.=	"\n<DescricaoProduto><![CDATA[$lin[DescricaoReduzidaProduto]]]></DescricaoProduto>";
			$dados	.=	"\n<DescricaoFabricante><![CDATA[$lin[DescricaoFabricante]]]></DescricaoFabricante>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_produto();
?>
