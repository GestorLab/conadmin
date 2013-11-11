<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ProdutoTipoVigencia(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja					 		= $_SESSION["IdLoja"];
		$IdProdutoTipoVigencia	 		= $_GET['IdProdutoTipoVigencia'];
		$DescricaoProdutoTipoVigencia   = $_GET['DescricaoProdutoTipoVigencia'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdProdutoTipoVigencia != ''){	
			$where .= " and IdProdutoTipoVigencia=$IdProdutoTipoVigencia";	
		}
		if($DescricaoProdutoTipoVigencia !=''){	
			$where .= " and DescricaoProdutoTipoVigencia like '$DescricaoProdutoTipoVigencia%'";	
		}
		
		$sql	=	"select
						IdProdutoTipoVigencia, 
						DescricaoProdutoTipoVigencia, 
						DataCriacao, 
						LoginCriacao, 
						DataAlteracao, 
						LoginAlteracao 
					from 
						ProdutoTipoVigencia
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
			$dados	.=	"\n<IdProdutoTipoVigencia>$lin[IdProdutoTipoVigencia]</IdProdutoTipoVigencia>";
			$dados	.=	"\n<DescricaoProdutoTipoVigencia><![CDATA[$lin[DescricaoProdutoTipoVigencia]]]></DescricaoProdutoTipoVigencia>";
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
	echo get_ProdutoTipoVigencia();
?>
