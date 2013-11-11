<?
	$localModulo		=	1;
	$localOperacao		=	39;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_descricao		= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_data			= $_POST['filtro_data'];
	$filtro_limit			= $_POST['filtro_limit'];
	$filtro_tipoData		= $_POST['filtro_tipoData'];
	$local_IdLoja			= $_SESSION["IdLoja"];
	
	if($filtro_data == ''&& $_GET['Data']!=''){
		$filtro_data		= $_GET['Data'];
	}
	
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
		
	if($filtro_descricao!=""){
		$filtro_url .= "&DescricaoData=".$filtro_descricao;
		$filtro_sql .= " and (DescricaoData like '%$filtro_descricao%')";
	}
	if($filtro_data!=""){
		$filtro_url	.= "&Data=".$filtro_data;
		$filtro_data = dataConv($filtro_data,'d/m/Y','Y-m-d');
		$filtro_sql	.= " and Data='".$filtro_data."'";
	}
	if($filtro_tipoData!=""){
		$filtro_url .= "&TipoData=".$filtro_tipoData;
		$filtro_sql .= " and TipoData = '$filtro_tipoData'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_datas_especiais_xsl.php$filtro_url\"?>";
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
					Data, 
					DescricaoData,
					ValorParametroSistema
				from 
					DatasEspeciais,
					ParametroSistema
				where    
					IdLoja=".$local_IdLoja." and
					IdGrupoParametroSistema=52 and 
					DatasEspeciais.TipoData=ParametroSistema.IdParametroSistema
					$filtro_sql
				order by
					DatasEspeciais.DataCriacao desc
				$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin_datatemp  = dataConv($lin[Data],'Y-m-d','d/m/Y');
		$lin_datatemp2 = dataConv($lin[Data],'Y-m-d','Ymd'); 
	
		$lin[ValorParametroSistema]	=	explode("\n",$lin[ValorParametroSistema]);
		$lin[ValorParametroSistema]	=	$lin[ValorParametroSistema][0];
				
		echo "<reg>";			
		echo 	"<Data><![CDATA[$lin[Data]]]></Data>";	
		echo 	"<Datatemp><![CDATA[$lin_datatemp]]></Datatemp>";	
		echo 	"<Datatemp2><![CDATA[$lin_datatemp2]]></Datatemp2>";	
		echo 	"<DescricaoData><![CDATA[$lin[DescricaoData]]]></DescricaoData>";
		echo 	"<TipoData><![CDATA[$lin[ValorParametroSistema]]]></TipoData>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
