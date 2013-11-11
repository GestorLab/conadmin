<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_nota_fiscal(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdMovimentacaoProduto	= $_GET['IdMovimentacaoProduto'];
		$NumeroNF			  	= $_GET['NumeroNF'];
		$IdFornecedor		  	= $_GET['IdFornecedor'];
		$SerieNF			  	= $_GET['SerieNF'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdMovimentacaoProduto != ''){	
			$where .= " and MovimentacaoProduto.IdMovimentacaoProduto=$IdMovimentacaoProduto";	
		}
		if($NumeroNF !=''){	
			$where .= " and NumeroNF = '$NumeroNF'";	
		}
		if($SerieNF !=''){	
			$where .= " and SerieNF like '$SerieNF'";	
		}
		if($IdFornecedor !=''){	
			$where .= " and MovimentacaoProduto.IdFornecedor = '$IdFornecedor'";	
		}
		
		$sql	=	"select
						IdMovimentacaoProduto, 
						NumeroNF,
						MovimentacaoProduto.IdFornecedor,
						SerieNF,
						TipoMovimentacao,
						IdEstoque, 
						MovimentacaoProduto.CFOP,
						CFOP.NaturezaOperacao,
						DataNF,
						ValorNF,
						ValorTotalProduto,
						ValorBaseCalculoICMS,
						ValorICMS,
						ValorTotalIPI,
						ValorFrete,
						ValorSeguro,
						ValorOutrasDespesas,
						MovimentacaoProduto.Obs,
						IdRequisicaoProduto,
						IdDevolucaoProduto,
						MovimentacaoProduto.DataCriacao, 
						MovimentacaoProduto.LoginCriacao, 
						MovimentacaoProduto.DataAlteracao, 
						MovimentacaoProduto.LoginAlteracao
					from 
						MovimentacaoProduto,						
						CFOP
					where
						MovimentacaoProduto.IdLoja = $IdLoja and
						MovimentacaoProduto.CFOP = CFOP.CFOP $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){			
			
			$dados	.=	"\n<IdMovimentacaoProduto>$lin[IdMovimentacaoProduto]</IdMovimentacaoProduto>";
			$dados	.=	"\n<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
			$dados	.=	"\n<IdFornecedor><![CDATA[$lin[IdFornecedor]]]></IdFornecedor>";			
			$dados	.=	"\n<SerieNF><![CDATA[$lin[SerieNF]]]></SerieNF>";
			$dados	.=	"\n<TipoMovimentacao><![CDATA[$lin[TipoMovimentacao]]]></TipoMovimentacao>";
			$dados	.=	"\n<IdEstoque><![CDATA[$lin[IdEstoque]]]></IdEstoque>";
			$dados	.=	"\n<CFOP><![CDATA[$lin[CFOP]]]></CFOP>";
			$dados	.=	"\n<NaturezaOperacao><![CDATA[$lin[NaturezaOperacao]]]></NaturezaOperacao>";
			$dados	.=	"\n<DataNF><![CDATA[$lin[DataNF]]]></DataNF>";
			$dados	.=	"\n<ValorNF><![CDATA[$lin[ValorNF]]]></ValorNF>";
			$dados	.=	"\n<ValorTotalProduto><![CDATA[$lin[ValorTotalProduto]]]></ValorTotalProduto>";
			$dados	.=	"\n<ValorBaseCalculoICMS><![CDATA[$lin[ValorBaseCalculoICMS]]]></ValorBaseCalculoICMS>";
			$dados	.=	"\n<ValorICMS><![CDATA[$lin[ValorICMS]]]></ValorICMS>";
			$dados	.=	"\n<ValorTotalIPI><![CDATA[$lin[ValorTotalIPI]]]></ValorTotalIPI>";
			$dados	.=	"\n<ValorFrete><![CDATA[$lin[ValorFrete]]]></ValorFrete>";
			$dados	.=	"\n<ValorSeguro><![CDATA[$lin[ValorSeguro]]]></ValorSeguro>";
			$dados	.=	"\n<ValorOutrasDespesas><![CDATA[$lin[ValorOutrasDespesas]]]></ValorOutrasDespesas>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<IdRequisicaoProduto><![CDATA[$lin[IdRequisicaoProduto]]]></IdRequisicaoProduto>";
			$dados	.=	"\n<IdDevolucaoProduto><![CDATA[$lin[IdDevolucaoProduto]]]></IdDevolucaoProduto>";			
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
	echo get_nota_fiscal();
?>
