<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_produto_vigencia(){
		
		global $con;
		global $_GET;
		
		$IdLoja						= $_SESSION['IdLoja'];
		$Limit 						= $_GET['Limit'];
		$IdProduto					= $_GET['IdProduto'];
		$DataInicio					= $_GET['DataInicio'];
		$where						= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
				
		if($DataInicio !=''){	 				 
			$where  .= " and ProdutoVigencia.DataInicio =  '$DataInicio'";	 
		}
		
		if($IdProduto!= ''){
			$where	.=	" and Produto.IdProduto = ".$IdProduto;
		}
		
		$sql	=	"select
				      Produto.IdProduto,
				      Produto.DescricaoProduto,
				      ProdutoVigencia.DataInicio,
				      ProdutoVigencia.DataTermino,
				      ProdutoVigencia.Valor,
				      ProdutoVigencia.ValorDesconto,
				      DataLimiteDesconto,
				      ProdutoVigencia.IdProdutoTipoVigencia,
				      DescricaoProdutoTipoVigencia,
				      ProdutoVigencia.DataAlteracao,
				      ProdutoVigencia.LoginAlteracao,
				      ProdutoVigencia.DataCriacao,
				      ProdutoVigencia.LoginCriacao
				from
				      Produto,ProdutoVigencia,ProdutoTipoVigencia
				where      
					  Produto.IdLoja = $IdLoja and
					  ProdutoVigencia.IdLoja = Produto.IdLoja and
					  ProdutoTipoVigencia.IdLoja = ProdutoVigencia.IdLoja and
					  Produto.IdProduto = ProdutoVigencia.IdProduto and
					  ProdutoVigencia.IdProdutoTipoVigencia = ProdutoTipoVigencia.IdProdutoTipoVigencia $where 
				order by
					  ProdutoVigencia.DataInicio DESC	
				$Limit";
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
			$dados	.=	"\n<DescricaoProduto><![CDATA[$lin[DescricaoProduto]]]></DescricaoProduto>";
			$dados	.=	"\n<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
			$dados	.=	"\n<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
			$dados	.=	"\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";
			$dados	.=	"\n<IdProdutoTipoVigencia><![CDATA[$lin[IdProdutoTipoVigencia]]]></IdProdutoTipoVigencia>";
			$dados	.=	"\n<DescricaoProdutoTipoVigencia><![CDATA[$lin[DescricaoProdutoTipoVigencia]]]></DescricaoProdutoTipoVigencia>";
			$dados	.=	"\n<DataLimiteDesconto><![CDATA[$lin[DataLimiteDesconto]]]></DataLimiteDesconto>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_produto_vigencia();
?>
