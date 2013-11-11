<?
	$localModulo		=	1;
	$localOperacao		=	50;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_descricao				= $_POST['filtro_descricao'];
	$filtro_produto_tipo_vigencia	= $_GET['IdProdutoTipoVigencia'];
	$filtro_limit					= $_POST['filtro_limit'];
	
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
	
	if($filtro_produto_tipo_vigencia!=''){
		$filtro_url .= "&IdProdutoTipoVigencia=$filtro_produto_tipo_vigencia";
		$filtro_sql .=	" and IdProdutoTipoVigencia = '$filtro_produto_tipo_vigencia'";
	}
		
	if($filtro_descricao!=''){
		$filtro_url .= "&DescricaoProdutoTipoVigencia=$filtro_descricao";
		$filtro_sql .=	" and DescricaoProdutoTipoVigencia like '%$filtro_descricao%'";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_produto_tipo_vigencia_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
	$sql	=	"select 
					IdLoja,
					IdProdutoTipoVigencia, 
					DescricaoProdutoTipoVigencia 
				from 
					ProdutoTipoVigencia 
				where
					IdLoja = $local_IdLoja
					$filtro_sql
				order by
					IdProdutoTipoVigencia desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";		
		echo 	"<IdProdutoTipoVigencia>$lin[IdProdutoTipoVigencia]</IdProdutoTipoVigencia>";	
		echo 	"<DescricaoProdutoTipoVigencia><![CDATA[$lin[DescricaoProdutoTipoVigencia]]]></DescricaoProdutoTipoVigencia>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
