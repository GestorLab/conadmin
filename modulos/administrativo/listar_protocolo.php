<?
	$localModulo		= 1;
	$localOperacao		= 162;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION['IdLoja'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado				= $_POST['filtro_tipoDado'];
	$filtro_assunto					= url_string_xsl($_POST['filtro_assunto'],'url',false);
	$filtro_protocolo_tipo			= $_POST['filtro_protocolo_tipo'];
	$filtro_local_abertura			= $_POST['filtro_local_abertura'];
	$filtro_id_status				= $_POST['filtro_id_status'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	if($filtro_protocolo_tipo == ''){
		$filtro_protocolo_tipo = $_GET['IdProtocoloTipo'];
	}
	
	if($filtro_id_protocolo == ''){
		$filtro_id_protocolo = $_GET['IdProtocolo'];
	}
	
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
	
	if($filtro_id_protocolo != "")
		$filtro_sql .= " and Protocolo.IdProtocolo = $filtro_id_protocolo";
	
	if($filtro_assunto != "") {
		$filtro_url .= "&Assunto=".$filtro_assunto;
		$filtro_sql .= " and (Protocolo.Assunto like '%$filtro_assunto%')";
	}
	
	if($filtro_protocolo_tipo != "") {
		$filtro_url .= "&IdProtocoloTipo=".$filtro_protocolo_tipo;
		$filtro_sql .= " and Protocolo.IdProtocoloTipo = $filtro_protocolo_tipo";
	}
	
	if($filtro_local_abertura != "") {
		$filtro_url .= "&LocalAbertura=".$filtro_local_abertura;
		$filtro_sql .= " and Protocolo.LocalAbertura = $filtro_local_abertura";
	}
	
	if($filtro_id_status != "") {
		$filtro_url .= "&IdStatus=".$filtro_id_status;
		$filtro_sql .= " and Protocolo.IdStatus = $filtro_id_status";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_protocolo_xsl.php$filtro_url\"?>";
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
				Protocolo.IdProtocolo,
				Protocolo.IdProtocoloTipo,
				substring(Protocolo.Assunto,1,80) Assunto,
				Protocolo.IdStatus,
				Protocolo.LoginResponsavel,
				Protocolo.DataCriacao,
				Protocolo.PrevisaoEtapa,
				Protocolo.LoginCriacao,
				Protocolo.LoginConclusao,
				ProtocoloTipo.DescricaoProtocoloTipo
			from 
				Protocolo left join ProtocoloTipo on (
					Protocolo.IdLoja = ProtocoloTipo.IdLoja and
					Protocolo.IdProtocoloTipo = ProtocoloTipo.IdProtocoloTipo
				)
			where
				Protocolo.IdLoja = $local_IdLoja 
				$filtro_sql
			order by
				Protocolo.IdProtocolo desc
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[DataCriacao]		= dataConv($lin[DataCriacao],"Y-m-d H:i:s","Ymd");
		$lin[PrevisaoEtapa]		= dataConv($lin[PrevisaoEtapa],"Y-m-d H:i:s","d/m/y");
		$lin[DataCriacaoTemp]	= dataConv($lin[DataCriacao],"Ymd","d/m/Y");
		$lin[Status]			= getParametroSistema(239, $lin[IdStatus]);
		
		$temp = explode("\n", getCodigoInterno(49, $lin[IdStatus][0]));
		$CorReg = str_replace("\r", "", $temp[1]);
		$sql_tmp = "select ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno = '53' and IdCodigoInterno = '".$lin[IdStatus]."';";
		$res_tmp = mysql_query($sql_tmp, $con);
		$lin_tmp = mysql_fetch_array($res_tmp);
		$temp = explode("\n", $lin_tmp[ValorCodigoInterno]);
		
		if(!empty($temp[1])){
			$CorReg = str_replace("\r", "", $temp[1]);
		}
		
		echo "<reg>";
		echo 	"<IdProtocolo>$lin[IdProtocolo]</IdProtocolo>";
		echo 	"<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo 	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo 	"<CorReg><![CDATA[$CorReg]]></CorReg>";
		echo 	"<LoginResponsavel><![CDATA[$lin[LoginResponsavel]]]></LoginResponsavel>";
		echo 	"<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		echo 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo 	"<PrevisaoEtapa><![CDATA[$lin[PrevisaoEtapa]]]></PrevisaoEtapa>";
		echo 	"<LoginConclusao><![CDATA[$lin[LoginConclusao]]]></LoginConclusao>";
		echo 	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
		echo 	"<DescricaoProtocoloTipo><![CDATA[$lin[DescricaoProtocoloTipo]]]></DescricaoProtocoloTipo>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>