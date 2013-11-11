<?
	$localModulo		=	1;
	$localOperacao		=	46;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$IdLoja					= $_SESSION['IdLoja'];	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_data_inicio		= $_POST['filtro_data_inicio'];
	$filtro_tipo_vigencia	= $_POST['filtro_tipo_vigencia'];
	$filtro_data_fim		= $_POST['filtro_data_termino'];
	$filtro_valor			= $_POST['filtro_valor'];
	$filtro_produto			= $_POST['IdProduto'];
	$filtro_limit			= $_POST['filtro_limit'];
	
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
		
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio	=	dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and (DataInicio = '$filtro_data_inicio')";
	}
	if($filtro_tipo_vigencia!=""){
		$filtro_url .= "&DescricaoProdutoTipoVigencia=".$filtro_tipo_vigencia;
		$filtro_sql .= " and (DescricaoProdutoTipoVigencia like '%$filtro_tipo_vigencia%')";
	}
	
	if($filtro_data_fim!=""){
		$filtro_url .= "&DataTermino=".$filtro_data_fim;
		$filtro_data_fim	=	dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and (DataTermino = '$filtro_data_fim')";
	}
	if($filtro_valor!=""){
		$filtro_url .= "&Valor=".$filtro_valor;
		$filtro_valor = str_replace(".","",$filtro_valor);	
		$filtro_valor = str_replace(",",".",$filtro_valor);
		$filtro_sql .= " and (Valor = '$filtro_valor')";
	}
	
	$filtro_url	.= "&IdProduto=".$filtro_produto;
	$filtro_sql	.= " and ProdutoVigencia.IdProduto='".$filtro_produto."'";
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_produto_vigencia_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
	$sql	=	"select
					ProdutoVigencia.IdProduto,
					ProdutoVigencia.DataInicio,
					ProdutoVigencia.DataTermino,
					ProdutoVigencia.Valor,
					DescricaoProdutoTipoVigencia
				from 
					Produto,
					ProdutoVigencia,
					ProdutoTipoVigencia
				where   
					ProdutoVigencia.IdLoja = $IdLoja and
					ProdutoVigencia.IdLoja = Produto.IdLoja and
					ProdutoVigencia.IdLoja = ProdutoTipoVigencia.IdLoja and
					Produto.IdProduto = ProdutoVigencia.IdProduto and
					ProdutoVigencia.IdProdutoTipoVigencia = ProdutoTipoVigencia.IdProdutoTipoVigencia
					$filtro_sql
				order by
					ProdutoVigencia.IdProduto desc,
					ProdutoVigencia.DataInicio DESC
				$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin[DataInicio2]		=	$lin[DataInicio];	
	
		$lin[DataInicioTemp]		=	dataConv($lin[DataInicio],'Y-m-d','d/m/Y');	
		$lin[DataInicio]			=	dataConv($lin[DataInicio],'Y-m-d','Ymd');
		
		$lin[DataTerminoTemp]		=	dataConv($lin[DataTermino],'Y-m-d','d/m/Y');	
		$lin[DataTermino]			=	dataConv($lin[DataTermino],'Y-m-d','Ymd');		
		
		echo "<reg>";			
		echo 	"<IdProduto>$lin[IdProduto]</IdProduto>";	
		echo	"<DataInicio2><![CDATA[$lin[DataInicio2]]]></DataInicio2>";
		echo	"<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
		echo	"<DataInicioTemp><![CDATA[$lin[DataInicioTemp]]]></DataInicioTemp>";
		echo	"<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
		echo	"<DataTerminoTemp><![CDATA[$lin[DataTerminoTemp]]]></DataTerminoTemp>";
		echo	"<DescricaoProdutoTipoVigencia><![CDATA[$lin[DescricaoProdutoTipoVigencia]]]></DescricaoProdutoTipoVigencia>";
		echo	"<Valor><![CDATA[$lin[Valor]]]></Valor>";		
		echo "</reg>";	
	}
	
	echo "</db>";
?>
