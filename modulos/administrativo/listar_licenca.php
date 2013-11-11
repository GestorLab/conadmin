<?
	$localModulo		=	1;
	$localOperacao		=	90;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION['Login'];
	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_data			= $_POST['filtro_data'];
	$filtro_tipo			= $_POST['filtro_tipo'];
	$filtro_limit			= $_POST['filtro_limit'];
	
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
		
	if($filtro_data != ''){
		$filtro_url .= "&DataGeracao=".$filtro_data;
		$filtro_data = dataConv($filtro_data,'d/m/Y','Y-m-d');
		$filtro_sql .= " and DataGeracao like '".$filtro_data."%'";
	}
	if($filtro_tipo!=""){
		$filtro_url	.= "&TipoSolicitacao=".$filtro_tipo;
		$filtro_sql	.= " and TipoSolicitacao =".$filtro_tipo;
	}
	
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_licenca_xsl.php$filtro_url\"?>";
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
				      	IdAutorizacao, 
						substr(DataGeracao,1,10) Data,
						substr(DataGeracao,12,5) as Hora,
						TipoSolicitacao,
						DiasLicenca,
						DataCriacao
					from 
						Licenca
					where
						1
						$filtro_sql
					order by
						IdAutorizacao desc
						$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin[DataTemp]	= dataConv($lin[Data],'Y-m-d','d/m/Y');
		$lin[Data]		= dataConv($lin[Data],'Y-m-d','Ymd');
		
		$lin[HoraTemp]	= $lin[Hora];
		$lin[Hora]		= str_replace(':','',$lin[Hora]);	
		
		$lin[DataCriacaoTemp]	= dataConv($lin[DataCriacao],'Y-m-d','d/m/Y');
		$lin[DataCriacao]		= dataConv($lin[DataCriacao],'Y-m-d','Ymd');
		
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=119 and IdParametroSistema = $lin[TipoSolicitacao]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		echo "<reg>";			
		echo 	"<IdAutorizacao>$lin[IdAutorizacao]</IdAutorizacao>";
		echo 	"<Data><![CDATA[$lin[Data]]]></Data>";
		echo	"<DataTemp><![CDATA[$lin[DataTemp]]]></DataTemp>";
		echo 	"<Hora><![CDATA[$lin[Hora]]]></Hora>";
		echo 	"<HoraTemp><![CDATA[$lin[HoraTemp]]]></HoraTemp>";
		echo 	"<DescricaoTipoSolicitacao><![CDATA[$lin2[ValorParametroSistema]]]></DescricaoTipoSolicitacao>";
		echo 	"<DiasLicenca><![CDATA[$lin[DiasLicenca]]]></DiasLicenca>";
		echo	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
