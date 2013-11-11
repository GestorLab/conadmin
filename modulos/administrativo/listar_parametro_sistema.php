<?
	$localModulo		=	1;
	$localOperacao		=	4;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_grupo_parametro_sistema		= $_POST['filtro_grupo_parametro_sistema'];
	$filtro_descricao_parametro_sistema	= url_string_xsl($_POST['filtro_descricao_parametro_sistema'],'url',false);
	$filtro_valor_parametro_sistema		= $_POST['filtro_valor_parametro_sistema'];
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
		
	if($filtro_grupo_parametro_sistema!=''){
		$filtro_url .= "&GrupoParametroSistema=$filtro_grupo_parametro_sistema";
		$filtro_sql .=	" and GrupoParametroSistema.IdGrupoParametroSistema = $filtro_grupo_parametro_sistema";
	}
		
	if($filtro_descricao_parametro_sistema!=""){
		$filtro_url .= "&ParametroSistema=".$filtro_descricao_parametro_sistema;
		$filtro_sql .= " and ParametroSistema.DescricaoParametroSistema like '%$filtro_descricao_parametro_sistema%'";
	}
	
	if($filtro_valor_parametro_sistema!=''){
		$filtro_url .= "&ValorParametroSistema=".$filtro_valor_parametro_sistema;
		$filtro_sql .= " and ParametroSistema.ValorParametroSistema like '%$filtro_valor_parametro_sistema%'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_parametro_sistema_xsl.php$filtro_url\"?>";
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
			      GrupoParametroSistema.IdGrupoParametroSistema,
			      GrupoParametroSistema.DescricaoGrupoParametroSistema,
			      ParametroSistema.IdParametroSistema,
			      ParametroSistema.DescricaoParametroSistema,
			      substr(ParametroSistema.ValorParametroSistema,1,50) ValorParametroSistema
				from 
			      ParametroSistema,
	    		  GrupoParametroSistema
				where
			      GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema
			      $filtro_sql
			      $Limit
				";
			      
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
#		$lin[ValorParametroSistema] 		= subTexto($lin[ValorParametroSistema],30);
#		$lin[DescricaoParametroSistema] 	= subTexto($lin[DescricaoParametroSistema],50);
		echo "<reg>";	
		echo 	"<IdGrupoParametroSistema>$lin[IdGrupoParametroSistema]</IdGrupoParametroSistema>";
		echo 	"<DescricaoGrupoParametroSistema><![CDATA[$lin[DescricaoGrupoParametroSistema]]]></DescricaoGrupoParametroSistema>";
		echo 	"<IdParametroSistema>$lin[IdParametroSistema]</IdParametroSistema>";	
		echo 	"<DescricaoParametroSistema><![CDATA[$lin[DescricaoParametroSistema]]]></DescricaoParametroSistema>";
		echo 	"<ValorParametroSistema><![CDATA[$lin[ValorParametroSistema]]]></ValorParametroSistema>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
