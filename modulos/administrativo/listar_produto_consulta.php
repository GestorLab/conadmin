<?
	$localModulo		=	1;
	$localOperacao		=	95;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$IdLoja						= $_SESSION['IdLoja'];	
	$IdTabelaPreco				= getCodigoInterno(3,101);
	$IdFormaPagamento			= getCodigoInterno(3,102);
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_descricao			= $_POST['filtro_descricao'];
	$filtro_fabricante			= $_POST['filtro_fabricante'];
	$filtro_grupo_produto		= $_POST['filtro_grupo_produto'];
	$filtro_subgrupo_produto	= $_POST['filtro_subgrupo_produto'];
	$filtro_produto				= $_POST['IdProduto'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	if($filtro_produto == ''&& $_GET['IdProduto']!=''){
		$filtro_produto		= $_GET['IdProduto'];
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
			
	if($filtro_descricao!=""){
		$filtro_url .= "&DescricaoProduto=".$filtro_descricao;
		$filtro_sql .= " and (DescricaoProduto like '%$filtro_descricao%' or DescricaoReduzidaProduto like '%$filtro_descricao%')";
	}
	if($filtro_fabricante!=""){
		$filtro_url .= "&DescricaoFabricante=".$filtro_fabricante;
		$filtro_sql .= " and (DescricaoFabricante like '%$filtro_fabricante%')";
	}
	if($filtro_grupo_produto!=""){
		$filtro_url .= "&DescricaoGrupoProduto=".$filtro_grupo_produto;
		$filtro_sql .= " and (DescricaoGrupoProduto like '%$filtro_grupo_produto%')";
	}
	if($filtro_subgrupo_produto!=""){
		$filtro_url .= "&DescricaoSubGrupoProduto=".$filtro_subgrupo_produto;
		$filtro_sql .= " and (DescricaoSubGrupoProduto like '%$filtro_subgrupo_produto%')";
	}
	if($filtro_produto!=""){
		$filtro_url	.= "&IdProduto=".$filtro_produto;
		$filtro_sql	.= " and Produto.IdProduto=".$filtro_produto;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_produto_consulta_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
	$sql	=	"select
					Produto.IdProduto,
					DescricaoReduzidaProduto,
					substr(Fabricante.DescricaoFabricante,1,40) DescricaoFabricante,
					ValorPrecoMinimo,
					ValorPreco
				from 
					Produto 
						left join (
									select 
										ProdutoSubGrupoProduto.IdProduto,	
										DescricaoGrupoProduto, 
										DescricaoSubGrupoProduto
									from 
										Produto, 
										ProdutoSubGrupoProduto, 
										GrupoProduto,	
										SubGrupoProduto 
									where 
										ProdutoSubGrupoProduto.IdLoja = $IdLoja and 
										ProdutoSubGrupoProduto.IdLoja = GrupoProduto.IdLoja and 
										ProdutoSubGrupoProduto.IdLoja = SubGrupoProduto.IdLoja and 
										ProdutoSubGrupoProduto.IdLoja = Produto.IdLoja and 
										ProdutoSubGrupoProduto.IdGrupoProduto = GrupoProduto.IdGrupoProduto and 
										ProdutoSubGrupoProduto.IdSubGrupoProduto = SubGrupoProduto.IdSubGrupoProduto and 
										ProdutoSubGrupoProduto.IdProduto = Produto.IdProduto and 
										GrupoProduto.IdGrupoProduto = SubGrupoProduto.IdGrupoProduto
							) ProdutoSubGrupoProduto ON (Produto.IdProduto = ProdutoSubGrupoProduto.IdProduto)
						left join 
							ProdutoTabelaPreco ON (Produto.IdLoja = ProdutoTabelaPreco.IdLoja and Produto.IdProduto = ProdutoTabelaPreco.IdProduto and ProdutoTabelaPreco.IdTabelaPreco = $IdTabelaPreco and ProdutoTabelaPreco.IdFormaPagamento = $IdFormaPagamento),
					Fabricante
				where   
					Produto.IdLoja = $IdLoja and
					Produto.IdLoja = Fabricante.IdLoja and 
					Produto.IdFabricante = Fabricante.IdFabricante 
					$filtro_sql	
				group by
					Produto.IdProduto desc 
				$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[ValorPrecoMinimo] == '')	$lin[ValorPrecoMinimo] = 0;
		if($lin[ValorPreco] == '')			$lin[ValorPreco] = 0;
	
		echo "<reg>";			
		echo 	"<IdProduto>$lin[IdProduto]</IdProduto>";	
		echo 	"<DescricaoReduzidaProduto><![CDATA[$lin[DescricaoReduzidaProduto]]]></DescricaoReduzidaProduto>";
		echo	"<DescricaoFabricante><![CDATA[$lin[DescricaoFabricante]]]></DescricaoFabricante>";
		echo	"<ValorPrecoMinimo><![CDATA[$lin[ValorPrecoMinimo]]]></ValorPrecoMinimo>";	
		echo	"<ValorPreco><![CDATA[$lin[ValorPreco]]]></ValorPreco>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
