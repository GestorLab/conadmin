<?
	$localModulo		= 1;
	$localOperacao		= 151;
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
	$filtro_host_address		= $_POST['filtro_host_address'];
	$filtro_id_status			= $_POST['filtro_id_status'];
	$filtro_limit				= $_POST['filtro_limit'];
	
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
	
	if($filtro_host_address!="") {
		$filtro_url .= "&HostAddress=".$filtro_host_address;
		$filtro_sql .= " AND (MonitorPorta.HostAddress like '%$filtro_host_address%')";
	}
	
	if($filtro_id_status != '') {
		$filtro_url .= "&IdStatus=".$filtro_id_status;
		$filtro_sql .= " and MonitorPorta.IdStatus=".$filtro_id_status;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_monitor_xsl.php$filtro_url\"?>";
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
				MonitorPorta.IdMonitor, 
				MonitorPorta.DescricaoMonitor, 
				MonitorPorta.IdStatus, 
				MonitorPorta.HostAddress, 
				MonitorPorta.Porta, 
				MonitorPorta.LoginCriacao, 
				MonitorPorta.DataCriacao
			from 
				MonitorPorta
			where
				MonitorPorta.IdLoja = $local_IdLoja
				$filtro_sql
			order by
				MonitorPorta.IdMonitor desc
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[Status] = getParametroSistema(232, $lin[IdStatus]);
		
		echo "<reg>";			
		echo 	"<IdMonitor>$lin[IdMonitor]</IdMonitor>";
		echo 	"<DescricaoMonitor><![CDATA[$lin[DescricaoMonitor]]]></DescricaoMonitor>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo	"<HostAddress><![CDATA[$lin[HostAddress]]]></HostAddress>";
		echo	"<Porta><![CDATA[$lin[Porta]]]></Porta>";
		echo	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>