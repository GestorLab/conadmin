<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_nota_fiscal_produto(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdMovimentacaoProduto	= $_GET['IdMovimentacaoProduto'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdMovimentacaoProduto != ''){	
			$where .= " and MovimentacaoProdutoItem.IdMovimentacaoProduto=$IdMovimentacaoProduto";	
		}
	
		$sql	=	"select
						MovimentacaoProdutoItem.IdMovimentacaoProduto, 
						MovimentacaoProdutoItem.Quantidade,
						MovimentacaoProdutoItem.IdProduto,
						MovimentacaoProdutoItem.ValorUnitario,
						MovimentacaoProdutoItem.AliquotaIPI,
						MovimentacaoProdutoItem.AliquotaICMS, 						
						Produto.NumeroSerie NSerie,						
						Produto.NumeroSerieObrigatorio,
						Produto.DescricaoReduzidaProduto,
						Produto.IdUnidade
					from 
						MovimentacaoProdutoItem,						
						Produto
					where
						MovimentacaoProdutoItem.IdLoja = $IdLoja and
						MovimentacaoProdutoItem.IdLoja = Produto.IdLoja and					
						MovimentacaoProdutoItem.IdProduto = Produto.IdProduto $where 
					order by
						Produto.DescricaoReduzidaProduto ASC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=66 and IdParametroSistema = $lin[IdUnidade]";
			$res2 = @mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
			
			$lin[NumeroSerie] = '';
			
			$sql3 = "select 
						NumeroSerie 
					from 
						MovimentacaoProdutoItemSerie 
					where 
						IdLoja=$IdLoja and 
						IdMovimentacaoProduto = $lin[IdMovimentacaoProduto] and 
						IdProduto = $lin[IdProduto] 
					order by 
						NumeroSerie ASC";
			$res3 = @mysql_query($sql3,$con);
			while($lin3 = @mysql_fetch_array($res3)){
				if($lin[NumeroSerie]!=''){
					$lin[NumeroSerie]	.=	"\n";
				}
			
				$lin[NumeroSerie]	.=	$lin3[NumeroSerie];
			}
			
			$lin[ValorIPI]	=	($lin[AliquotaICMS]*$lin[AliquotaIPI])/100;
			
			$dados	.=	"\n<IdMovimentacaoProduto>$lin[IdMovimentacaoProduto]</IdMovimentacaoProduto>";
			$dados	.=	"\n<Quantidade><![CDATA[$lin[Quantidade]]]></Quantidade>";
			$dados	.=	"\n<IdProduto><![CDATA[$lin[IdProduto]]]></IdProduto>";
			$dados	.=	"\n<ValorUnitario><![CDATA[$lin[ValorUnitario]]]></ValorUnitario>";
			$dados	.=	"\n<AliquotaIPI><![CDATA[$lin[AliquotaIPI]]]></AliquotaIPI>";
			$dados	.=	"\n<AliquotaICMS><![CDATA[$lin[AliquotaICMS]]]></AliquotaICMS>";
			$dados	.=	"\n<ValorIPI><![CDATA[$lin[ValorIPI]]]></ValorIPI>";
			$dados	.=	"\n<DescricaoReduzidaProduto><![CDATA[$lin[DescricaoReduzidaProduto]]]></DescricaoReduzidaProduto>";
			$dados	.=	"\n<Unidade><![CDATA[$lin2[ValorParametroSistema]]]></Unidade>";
			$dados	.=	"\n<NSerie><![CDATA[$lin[NSerie]]]></NSerie>";
			$dados	.=	"\n<NumeroSerieObrigatorio><![CDATA[$lin[NumeroSerieObrigatorio]]]></NumeroSerieObrigatorio>";
			$dados	.=	"\n<NumeroSerie><![CDATA[$lin[NumeroSerie]]]></NumeroSerie>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_nota_fiscal_produto();
?>
