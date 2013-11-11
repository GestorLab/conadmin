<?
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_MonitorMap(){
		global $con;
		global $_SESSION;
		global $_GET;
		
		$localIdLoja	= $_SESSION["IdLoja"];
		$localIdMonitor	= $_GET["filtro_id_monitor"];
		$While			= "";
		
		if($localIdMonitor != ''){
			$While .= " AND MonitorPorta.IdMonitor = '$localIdMonitor'";
		}
		
		if($localLimit != ""){
			$Limit	= " LIMIT $localLimit";
		}
		
	 	$sql = "SELECT 
					MonitorPorta.IdLoja, 
					MonitorPorta.DescricaoMonitor, 
					MonitorPorta.HostAddress, 
					MonitorPorta.Porta, 
					MonitorPorta.IdMonitor, 
					MonitorPorta.Latitude, 
					MonitorPorta.Longitude, 
					MonitorPorta.IdStatus 
				FROM 
					MonitorPorta 
				WHERE 
					MonitorPorta.IdLoja = '$localIdLoja' AND 
					MonitorPorta.Latitude != '' AND 
					MonitorPorta.Longitude != ''
					$While
				ORDER BY
					MonitorPorta.Latitude DESC
				$Limit";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin["Status"] = getParametroSistema(232, $lin["IdStatus"]);
				
				$dados .= "\n\t<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
				$dados .= "\n\t<IdMonitor><![CDATA[$lin[IdMonitor]]]></IdMonitor>";
				$dados .= "\n\t<DescricaoMonitor><![CDATA[$lin[DescricaoMonitor]]]></DescricaoMonitor>";
				$dados .= "\n\t<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n\t<Status><![CDATA[$lin[Status]]]></Status>";
				$dados .= "\n\t<Localizacao>";
				$dados .= "\n\t<HostAddress><![CDATA[$lin[HostAddress]]]></HostAddress>";
				$dados .= "\n\t<Porta><![CDATA[$lin[Porta]]]></Porta>";
				$dados .= "\n\t<Latitude><![CDATA[$lin[Latitude]]]></Latitude>";
				$dados .= "\n\t<Longitude><![CDATA[$lin[Longitude]]]></Longitude>";
				$dados .= "\n\t</Localizacao>";
			}
			
			$dados .= "\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_MonitorMap();
?>