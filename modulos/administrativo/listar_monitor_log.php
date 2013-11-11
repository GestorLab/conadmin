<?
	$localModulo		= 1;
	$localOperacao		= 177;
	$localSuboperacao	= "R";		
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja				= $_SESSION['IdLoja'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado			= $_POST['filtro_tipoDado'];
	$filtro_descricao_monitor	= url_string_xsl($_POST['filtro_descricao_monitor'],'url',false);
	$filtro_resultado			= $_POST['filtro_resultado'];
	$filtro_id_status			= $_POST['filtro_id_status'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_id_monitor			= $_GET['IdMonitor'];
	
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
		
	if($filtro_descricao_monitor!="") {
		$filtro_url .= "&DescricaoMonitor=".$filtro_descricao_monitor;
		$filtro_sql .= " AND (MonitorPorta.DescricaoMonitor like '%$filtro_descricao_monitor%')";
	}
	
	if($filtro_resultado!="") {
		$filtro_url .= "&Resultado=".$filtro_resultado;
		$filtro_sql .= " AND (MonitorPortaLog.Resultado like '%$filtro_resultado%')";
	}
	
	if($filtro_id_status != '') {
		$filtro_url .= "&IdStatus=".$filtro_id_status;
		$filtro_sql .= " and MonitorPortaLog.IdStatus=".$filtro_id_status;
	}
	
	if($filtro_id_monitor != '') {
		$filtro_sql .= " and MonitorPortaLog.IdMonitor=".$filtro_id_monitor;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_monitor_log_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s") {
		if($filtro_limit != "") {
			$Limit	= " limit $filtro_limit";
		}
	} else {
		if($filtro_limit == "") {
			$Limit 	= " limit 0," . getCodigoInterno(7,5);
		} else {
			$Limit 	= " limit 0," . $filtro_limit;
		}
	}
	
	$sql = "select
				MonitorPortaLog.IdMonitorLog, 
				MonitorPortaLog.DataCriacao, 
				MonitorPortaLog.Resultado, 
				MonitorPortaLog.IdStatus,
				MonitorPorta.IdMonitor, 
				MonitorPorta.DescricaoMonitor
			from 
				MonitorPortaLog,
				MonitorPorta
			where
				MonitorPortaLog.IdLoja = $local_IdLoja and
				MonitorPortaLog.IdLoja = MonitorPorta.IdLoja and
				MonitorPortaLog.IdMonitor = MonitorPorta.IdMonitor
				$filtro_sql
			order by
				MonitorPortaLog.IdMonitorLog desc
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[Status] = getParametroSistema(234, $lin[IdStatus]);
		$lin[DataCriacaoTemp] = dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
		$lin[DataCriacao] = dataConv($lin[DataCriacao],"Y-m-d","Ymd");
		
		echo "<reg>";			
		echo 	"<IdMonitorLog>$lin[IdMonitorLog]</IdMonitorLog>";
		echo 	"<IdMonitor>$lin[IdMonitor]</IdMonitor>";
		echo 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo 	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
		echo	"<Resultado><![CDATA[$lin[Resultado]]]></Resultado>";
		echo	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo	"<DescricaoMonitor><![CDATA[$lin[DescricaoMonitor]]]></DescricaoMonitor>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>