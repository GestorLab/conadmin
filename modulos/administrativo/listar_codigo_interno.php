<?
	$localModulo		=	1;
	$localOperacao		=	5;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_grupo_codigo_interno		= $_POST['filtro_grupo_codigo_interno'];
	$filtro_descricao_codigo_interno	= url_string_xsl($_POST['filtro_descricao_codigo_interno'],'url',false);
	$filtro_valor_codigo_interno		= url_string_xsl($_POST['filtro_valor_codigo_interno'],'url',false);
	$filtro_limit						= $_POST['filtro_limit'];
	
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
		
	if($filtro_grupo_codigo_interno!=''){
		$filtro_url .= "&GrupoCodigoInterno=$filtro_grupo_codigo_interno";
		$filtro_sql .=	" and GrupoCodigoInterno.IdGrupoCodigoInterno = $filtro_grupo_codigo_interno";
	}
		
	if($filtro_descricao_codigo_interno!=""){
		$filtro_url .= "&CodigoInterno=".$filtro_descricao_codigo_interno;
		$filtro_sql .= " and CodigoInterno.DescricaoCodigoInterno like '%$filtro_descricao_codigo_interno%'";
	}
	
	if($filtro_valor_codigo_interno!=''){
		$filtro_url .= "&ValorCodigoInterno=".$filtro_valor_codigo_interno;
		$filtro_sql .= " and CodigoInterno.ValorCodigoInterno like '%$filtro_valor_codigo_interno%'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_codigo_interno_xsl.php$filtro_url\"?>";
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

	$sql = "select
				GrupoCodigoInterno.IdGrupoCodigoInterno,
				GrupoCodigoInterno.DescricaoGrupoCodigoInterno,
				CodigoInterno.IdCodigoInterno,
				CodigoInterno.DescricaoCodigoInterno,
				substr(CodigoInterno.ValorCodigoInterno,1,40) ValorCodigoInterno
			from 
				Loja,
				CodigoInterno,
				GrupoCodigoInterno
			where
				CodigoInterno.IdLoja = $local_IdLoja and
				CodigoInterno.IdLoja = Loja.IdLoja and
				GrupoCodigoInterno.IdGrupoCodigoInterno = CodigoInterno.IdGrupoCodigoInterno
				$filtro_sql
			$Limit";
	$res = mysql_query($sql,$con);
	
	while($lin = mysql_fetch_array($res)){
#		$lin[ValorCodigoInterno] 		= subTexto($lin[ValorCodigoInterno],30);
#		$lin[DescricaoCodigoInterno] 	= subTexto($lin[DescricaoCodigoInterno],50);
		
		echo "<reg>";	
		echo 	"<IdGrupoCodigoInterno>$lin[IdGrupoCodigoInterno]</IdGrupoCodigoInterno>";
		echo 	"<DescricaoGrupoCodigoInterno><![CDATA[$lin[DescricaoGrupoCodigoInterno]]]></DescricaoGrupoCodigoInterno>";
		echo 	"<IdCodigoInterno>$lin[IdCodigoInterno]</IdCodigoInterno>";	
		echo 	"<DescricaoCodigoInterno><![CDATA[$lin[DescricaoCodigoInterno]]]></DescricaoCodigoInterno>";
		echo 	"<ValorCodigoInterno><![CDATA[$lin[ValorCodigoInterno]]]></ValorCodigoInterno>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>