<?
	$localModulo		=	1;
	$localOperacao		=	16;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_descricao_tipo_vigencia	= url_string_xsl($_POST['filtro_descricao_tipo_vigencia'],"url",false);
	$filtro_insento					= $_POST['filtro_isento'];
	$filtro_tipo_vigencia			= $_GET['IdContratoTipoVigencia'];
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
	
	if($filtro_tipo_vigencia!=''){
		$filtro_url .= "&IdContratoTipoVigencia=$filtro_tipo_vigencia";
		$filtro_sql .=	" and IdContratoTipoVigencia = '$filtro_tipo_vigencia'";
	}
		
	if($filtro_descricao_tipo_vigencia!=''){
		$filtro_url .= "&DescricaoTipoVigencia=$filtro_descricao_tipo_vigencia";
		$filtro_sql .=	" and DescricaoContratoTipoVigencia like '%$filtro_descricao_tipo_vigencia%'";
	}
	
	if($filtro_insento!=''){
		$filtro_url .= "&Isento=$filtro_insento";
		$filtro_sql .=	" and Isento = '$filtro_insento'";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
		
	if($filtro_sql != "")
		$filtro_sql = " and IdContratoTipoVigencia!=''".$filtro_sql;

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_atualizacao_versao_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"SELECT
					IdLicenca,
					IdAtualizacao,
					IdVersao,
					IdVersaoOld,
					DescricaoVersao,
					Login,
					DATE_FORMAT(DataEtapa0,\"%d/%m/%Y %H:%i:%s\") DataEtapa0,
					DATE_FORMAT(DataEtapa1,\"%d/%m/%Y %H:%i:%s\") DataEtapa1,
					DATE_FORMAT(DataEtapa2,\"%d/%m/%Y %H:%i:%s\") DataEtapa2,
					DATE_FORMAT(DataEtapa3,\"%d/%m/%Y %H:%i:%s\") DataEtapa3,
					SUBSTRING(LogUpdateMySQL,1,30) LogUpdateMySQL,
					DATE_FORMAT(DataTermino,\"%d/%m/%Y %H:%i:%s\") DataTermino
				FROM 
					Atualizacao
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		
		echo "<reg>";	
		echo 	"<IdLicenca>$lin[IdLicenca]</IdLicenca>";		
		echo 	"<IdAtualizacao>$lin[IdAtualizacao]</IdAtualizacao>";	
		echo 	"<IdVersao><![CDATA[$lin[IdVersao]]]></IdVersao>";	
		echo 	"<IdVersaoOld><![CDATA[$lin[IdVersaoOld]]]></IdVersaoOld>";
		echo 	"<DescricaoVersao><![CDATA[$lin[DescricaoVersao]]]></DescricaoVersao>";
		echo 	"<Login><![CDATA[$lin[Login]]]></Login>";
		echo 	"<DataEtapa0><![CDATA[$lin[DataEtapa0]]]></DataEtapa0>";
		echo 	"<DataEtapa1><![CDATA[$lin[DataEtapa1]]]></DataEtapa1>";
		echo 	"<DataEtapa2><![CDATA[$lin[DataEtapa2]]]></DataEtapa2>";
		echo 	"<DataEtapa3><![CDATA[$lin[DataEtapa3]]]></DataEtapa3>";
		echo 	"<LogUpdateMySQL><![CDATA[$lin[LogUpdateMySQL]]]></LogUpdateMySQL>";
		echo 	"<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
