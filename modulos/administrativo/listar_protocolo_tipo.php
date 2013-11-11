<?
	$localModulo		= 1;
	$localOperacao		= 169;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja						= $_SESSION['IdLoja'];
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado					= $_POST['filtro_tipoDado'];
	$filtro_descricao_protocolo_tipo	= url_string_xsl($_POST['filtro_descricao_protocolo_tipo'],'url',false);
	$filtro_abertura_cda				= $_POST['filtro_abertura_cda'];
	$filtro_status						= $_POST['filtro_status'];
	$filtro_limit						= $_POST['filtro_limit'];
	
	LimitVisualizacao("listar");
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipo_dado != "")
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_descricao_protocolo_tipo != "") {
		$filtro_url .= "&DescricaoProtocoloTipo=".$filtro_descricao_protocolo_tipo;
		$filtro_sql .= " and (ProtocoloTipo.DescricaoProtocoloTipo like '%$filtro_descricao_protocolo_tipo%')";
	}
	
	if($filtro_abertura_cda != "") {
		$filtro_url .= "&IdAberturaCDA=".$filtro_abertura_cda;
		$filtro_sql .= " and ProtocoloTipo.AberturaCDA = $filtro_abertura_cda";
	}
	
	if($filtro_status != "") {
		$filtro_url .= "&IdStatus=".$filtro_status;
		$filtro_sql .= " and ProtocoloTipo.IdStatus = $filtro_status";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_protocolo_tipo_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s") {
		if($filtro_limit != "") {
			$Limit	= " limit $filtro_limit";
		}
	} else {
		if($filtro_limit == "") {
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		} else {
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "select
				ProtocoloTipo.IdProtocoloTipo,
				ProtocoloTipo.DescricaoProtocoloTipo,
				ProtocoloTipo.AberturaCDA,
				ProtocoloTipo.IdStatus
			from 
				ProtocoloTipo
			where
				ProtocoloTipo.IdLoja = $local_IdLoja
				$filtro_sql
			order by
				ProtocoloTipo.IdProtocoloTipo desc
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[Status] = getParametroSistema(221,$lin[IdStatus]);
		$lin[IdAberturaCDA] = $lin[AberturaCDA];
		$lin[AberturaCDA] = getParametroSistema(220,$lin[IdAberturaCDA]);
		
		echo "<reg>";
		echo 	"<IdProtocoloTipo>$lin[IdProtocoloTipo]</IdProtocoloTipo>";
		echo 	"<DescricaoProtocoloTipo><![CDATA[$lin[DescricaoProtocoloTipo]]]></DescricaoProtocoloTipo>";
		echo 	"<IdAberturaCDA><![CDATA[$lin[IdAberturaCDA]]]></IdAberturaCDA>";
		echo 	"<AberturaCDA><![CDATA[$lin[AberturaCDA]]]></AberturaCDA>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo 	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>