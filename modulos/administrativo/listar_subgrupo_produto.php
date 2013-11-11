<?
	$localModulo		=	1;
	$localOperacao		=	49;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_descricao_grupo			= $_POST['filtro_descricao_grupo'];
	$filtro_descricao_subgrupo		= $_POST['filtro_descricao_subgrupo'];
	$filtro_grupo_produto			= $_GET['IdGrupoProduto'];
	$filtro_subgrupo_produto		= $_GET['IdSubGrupoProduto'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_grupo_produto!=''){
		$filtro_url .= "&IdGrupoProduto=$filtro_grupo_produto";
		$filtro_sql .=	" and SubGrupoProduto.IdGrupoProduto = '$filtro_grupo_produto'";
	}
	
	if($filtro_subgrupo_produto!=''){
		$filtro_url .= "&IdSubGrupoProduto=$filtro_subgrupo_produto";
		$filtro_sql .=	" and SubGrupoProduto.IdSubGrupoProduto = '$filtro_subgrupo_produto'";
	}
		
	if($filtro_descricao_grupo!=''){
		$filtro_url .= "&DescricaoGrupoProduto=$filtro_descricao_grupo";
		$filtro_sql .=	" and DescricaoGrupoProduto like '%$filtro_descricao_grupo%'";
	}
	
	if($filtro_descricao_subgrupo!=''){
		$filtro_url .= "&DescricaoSubGrupoProduto=$filtro_descricao_subgrupo";
		$filtro_sql .=	" and DescricaoSubGrupoProduto like '%$filtro_descricao_subgrupo%'";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_subgrupo_produto_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql	=	"select 
					SubGrupoProduto.IdLoja,
					SubGrupoProduto.IdGrupoProduto, 
					DescricaoGrupoProduto,
					SubGrupoProduto.IdSubGrupoProduto,
					DescricaoSubGrupoProduto 
				from 
					GrupoProduto,
					SubGrupoProduto 
				where
					SubGrupoProduto.IdLoja = $local_IdLoja and
					SubGrupoProduto.IdLoja = GrupoProduto.IdLoja and
					SubGrupoProduto.IdGrupoProduto = GrupoProduto.IdGrupoProduto
					$filtro_sql
				order by
					SubGrupoProduto.IdGrupoProduto desc,
					SubGrupoProduto.IdSubGrupoProduto desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";		
		echo 	"<IdGrupoProduto>$lin[IdGrupoProduto]</IdGrupoProduto>";	
		echo 	"<DescricaoGrupoProduto><![CDATA[$lin[DescricaoGrupoProduto]]]></DescricaoGrupoProduto>";	
		echo 	"<IdSubGrupoProduto>$lin[IdSubGrupoProduto]</IdSubGrupoProduto>";	
		echo 	"<DescricaoSubGrupoProduto><![CDATA[$lin[DescricaoSubGrupoProduto]]]></DescricaoSubGrupoProduto>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
