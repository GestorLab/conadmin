<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_produto(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];
		$Limit 					= $_GET['Limit'];
		$IdProduto   			= $_GET['IdProduto'];
		$DescricaoProduto  	    = $_GET['DescricaoProduto'];
		$DescricaoFabricante    = $_GET['DescricaoFabricante'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdProduto !=''){			$where .= " and Produto.IdProduto = $IdProduto";	}
		if($DescricaoProduto !=''){		$where .= " and DescricaoProduto like '$DescricaoProduto%'";	}
		if($DescricaoFabricante !=''){	$where .= " and DescricaoFabricante like '$DescricaoFabricante%'";	}
		
		$sql	=	"select 
						IdProduto, 
						DescricaoProduto,
						DescricaoReduzidaProduto,
						Produto.IdFabricante,
						DescricaoFabricante,
						IdUnidade,
						Garantia,
						IdUnidadeGarantia,
						IdTipoGarantia,
						QtdMinima,
						QtdMaxima,
						CodigoBarra,
						PesoKG,
						EspecificacaoProduto,
						ObsProduto,
						Produto.IdUltimoFornecedor,
						Nome,
						RazaoSocial,
						ValorPrecoMedio,
						ValorPrecoUltimaCompra,
						DataUltimaCompra,
						NumeroSerie,
						NumeroSerieObrigatorio,
						Produto.DataCriacao,
						Produto.LoginCriacao,
						Produto.LoginAlteracao,
						Produto.DataAlteracao
					from 
						Produto LEFT JOIN (select Fornecedor.IdLoja, Fornecedor.IdFornecedor, Pessoa.Nome, Pessoa.RazaoSocial from Pessoa,Fornecedor where Fornecedor.IdLoja = $IdLoja and Fornecedor.IdFornecedor = Pessoa.IdPessoa)Fornecedor ON (Produto.IdLoja = Fornecedor.IdLoja and Produto.IdUltimoFornecedor = Fornecedor.IdFornecedor),
						Fabricante
					where 
						Produto.IdLoja = $IdLoja and
						Produto.IdFabricante = Fabricante.IdFabricante $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
			
			$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=66 and IdParametroSistema=$lin[IdUnidade]";
			$res2 = @mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
			
			$dados	.=	"\n<IdProduto>$lin[IdProduto]</IdProduto>";
			$dados	.=	"\n<DescricaoProduto><![CDATA[$lin[DescricaoProduto]]]></DescricaoProduto>";
			$dados	.=	"\n<DescricaoReduzidaProduto><![CDATA[$lin[DescricaoReduzidaProduto]]]></DescricaoReduzidaProduto>";
			$dados	.=	"\n<IdUnidade><![CDATA[$lin[IdUnidade]]]></IdUnidade>";
			$dados	.=	"\n<Unidade><![CDATA[$lin2[ValorParametroSistema]]]></Unidade>";
			$dados	.=	"\n<IdFabricante><![CDATA[$lin[IdFabricante]]]></IdFabricante>";
			$dados	.=	"\n<DescricaoFabricante><![CDATA[$lin[DescricaoFabricante]]]></DescricaoFabricante>";
			$dados	.=	"\n<Garantia><![CDATA[$lin[Garantia]]]></Garantia>";
			$dados	.=	"\n<IdUnidadeGarantia><![CDATA[$lin[IdUnidadeGarantia]]]></IdUnidadeGarantia>";
			$dados	.=	"\n<IdTipoGarantia><![CDATA[$lin[IdTipoGarantia]]]></IdTipoGarantia>";
			$dados	.=	"\n<QtdMinima><![CDATA[$lin[QtdMinima]]]></QtdMinima>";
			$dados	.=	"\n<QtdMaxima><![CDATA[$lin[QtdMaxima]]]></QtdMaxima>";
			$dados	.=	"\n<CodigoBarra><![CDATA[$lin[CodigoBarra]]]></CodigoBarra>";
			$dados	.=	"\n<PesoKG><![CDATA[$lin[PesoKG]]]></PesoKG>";
			$dados	.=	"\n<EspecificacaoProduto><![CDATA[$lin[EspecificacaoProduto]]]></EspecificacaoProduto>";
			$dados	.=	"\n<ObsProduto><![CDATA[$lin[ObsProduto]]]></ObsProduto>";
			$dados	.=	"\n<IdUltimoFornecedor><![CDATA[$lin[IdUltimoFornecedor]]]></IdUltimoFornecedor>";
			$dados	.=	"\n<NomeFornecedor><![CDATA[$lin[Nome]]]></NomeFornecedor>";
			$dados	.=	"\n<ValorPrecoMedio><![CDATA[$lin[ValorPrecoMedio]]]></ValorPrecoMedio>";
			$dados	.=	"\n<ValorPrecoUltimaCompra><![CDATA[$lin[ValorPrecoUltimaCompra]]]></ValorPrecoUltimaCompra>";
			$dados	.=	"\n<DataUltimaCompra><![CDATA[$lin[DataUltimaCompra]]]></DataUltimaCompra>";
			$dados	.=	"\n<NumeroSerie><![CDATA[$lin[NumeroSerie]]]></NumeroSerie>";
			$dados	.=	"\n<NumeroSerieObrigatorio><![CDATA[$lin[NumeroSerieObrigatorio]]]></NumeroSerieObrigatorio>";
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
	echo get_produto();
?>
