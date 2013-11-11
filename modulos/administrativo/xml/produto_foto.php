<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ProdutoFoto(){
		
		global $con;
		global $_GET;
		
		$IdLoja						= $_SESSION['IdLoja'];
		$Limit 						= $_GET['Limit'];
		$IdProduto					= $_GET['IdProduto'];
		$IdProdutoFoto				= $_GET['IdProdutoFoto'];
		$where						= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
			
		if($IdProduto!= ''){
			$where	.=	" and ProdutoFoto.IdProduto = ".$IdProduto;
		}
		if($IdProdutoFoto!= ''){
			$where	.=	" and ProdutoFoto.IdProdutoFoto = ".$IdProdutoFoto;
		}
		
		$sql =   "select
					  ProdutoFoto.IdLoja,
					  ProdutoFoto.IdProduto,
					  Produto.DescricaoProduto,	
				      ProdutoFoto.IdProdutoFoto,
					  ProdutoFoto.DescricaoFoto,
				      ProdutoFoto.ExtFoto,
				      ProdutoFoto.DataCriacao,
				      ProdutoFoto.LoginCriacao,
				      ProdutoFoto.DataAlteracao,
				      ProdutoFoto.LoginAlteracao
				from
				      Produto,
					  ProdutoFoto			
				where	   
					  ProdutoFoto.IdLoja = $IdLoja and
					  ProdutoFoto.IdLoja = Produto.IdLoja and
					  ProdutoFoto.IdProduto = Produto.IdProduto
					  $where 
				ORDER BY IdProdutoFoto ASC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
			$dados	.=	"\n<IdProduto><![CDATA[$lin[IdProduto]]]></IdProduto>";
			$dados	.=	"\n<DescricaoProduto><![CDATA[$lin[DescricaoProduto]]]></DescricaoProduto>";
			$dados	.=	"\n<IdProdutoFoto>$lin[IdProdutoFoto]</IdProdutoFoto>";
			$dados	.=	"\n<DescricaoFoto><![CDATA[$lin[DescricaoFoto]]]></DescricaoFoto>";
			$dados	.=	"\n<ExtFoto><![CDATA[$lin[ExtFoto]]]></ExtFoto>";
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
	echo get_ProdutoFoto();
?>
