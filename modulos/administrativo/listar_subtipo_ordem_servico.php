<?
	$localModulo		=	1;
	$localOperacao		=	83;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_descricao_tipo			= url_string_xsl($_POST['filtro_descricao_tipo'],'url',false);
	$filtro_descricao_subtipo		= url_string_xsl($_POST['filtro_descricao_subtipo'],'url',false);
	$filtro_tipo_os					= $_GET['IdTipoOrdemServico'];
	$filtro_subtipo_os				= $_GET['IdSubTipoOrdemServico'];
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
	
	if($filtro_tipo_os!=''){
		$filtro_url .= "&IdTipoOrdemServico=$filtro_tipo_os";
		$filtro_sql .=	" and SubTipoOrdemServico.IdTipoOrdemServico = '$filtro_tipo_os'";
	}
	
	if($filtro_subtipo_os!=''){
		$filtro_url .= "&IdSubTipoOrdemServico=$filtro_subtipo_os";
		$filtro_sql .=	" and SubTipoOrdemServico.IdSubTipoOrdemServico = '$filtro_subtipo_os'";
	}
		
	if($filtro_descricao_tipo!=''){
		$filtro_url .= "&DescricaoTipoOrdemServico=$filtro_descricao_tipo";
		$filtro_sql .=	" and DescricaoTipoOrdemServico like '%$filtro_descricao_tipo%'";
	}
	
	if($filtro_descricao_subtipo!=''){
		$filtro_url .= "&DescricaoSubTipoOrdemServico=$filtro_descricao_subtipo";
		$filtro_sql .=	" and DescricaoSubTipoOrdemServico like '%$filtro_descricao_subtipo%'";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_subtipo_ordem_servico_xsl.php$filtro_url\"?>";
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
					SubTipoOrdemServico.IdLoja,
					SubTipoOrdemServico.IdTipoOrdemServico, 
					TipoOrdemServico.DescricaoTipoOrdemServico,
					SubTipoOrdemServico.IdSubTipoOrdemServico,
					SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
					SubTipoOrdemServico.Cor
				from 
					TipoOrdemServico,
					SubTipoOrdemServico 
				where
					TipoOrdemServico.IdLoja = $local_IdLoja and
					TipoOrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
					SubTipoOrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico
					$filtro_sql
				order by
					SubTipoOrdemServico.IdTipoOrdemServico desc,
					SubTipoOrdemServico.IdSubTipoOrdemServico desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";		
		echo 	"<IdTipoOrdemServico>$lin[IdTipoOrdemServico]</IdTipoOrdemServico>";	
		echo 	"<DescricaoTipoOrdemServico><![CDATA[$lin[DescricaoTipoOrdemServico]]]></DescricaoTipoOrdemServico>";	
		echo 	"<IdSubTipoOrdemServico>$lin[IdSubTipoOrdemServico]</IdSubTipoOrdemServico>";	
		echo 	"<DescricaoSubTipoOrdemServico><![CDATA[$lin[DescricaoSubTipoOrdemServico]]]></DescricaoSubTipoOrdemServico>";
		echo 	"<Cor><![CDATA[$lin[Cor]]]></Cor>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
