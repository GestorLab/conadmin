<?
	$localModulo	= 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_MonitorAlarme(){
		global $con;
		global $_GET;
		
		$local_IdLoja			= $_SESSION['IdLoja'];
		$local_Limit 			= $_GET['Limit'];
		$local_IdMonitor		= $_GET['IdMonitor'];
		$local_IdTipoMensagem	= $_GET['IdTipoMensagem'];
		$local_IdStatus			= $_GET['IdStatus'];
		$where					= '';
		
		if($local_Limit != ''){
			$local_Limit = "limit 0, $local_Limit";
		}
		
		if($local_IdMonitor != ''){
			$where .= " and MonitorPortaAlarme.IdMonitor = '$local_IdMonitor'";
		}
		
		if($local_IdStatus != ''){
			$where .= " and MonitorPortaAlarme.IdStatus = '$local_IdStatus'";
		}
		
		$sql = "select 
					MonitorPortaAlarme.IdMonitor,
					MonitorPortaAlarme.IdTipoMensagem,
					MonitorPortaAlarme.IdStatus,
					MonitorPortaAlarme.QtdTentativas,
					MonitorPortaAlarme.IntervaloTentativa,
					MonitorPortaAlarme.DestinatarioMensagem,
					MonitorPortaAlarme.Mensagem
				from 
					MonitorPortaAlarme
				where 
					MonitorPortaAlarme.IdLoja = '$local_IdLoja' and
					MonitorPortaAlarme.IdTipoMensagem = '17'
					$where 
				$Limit;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[Status] = getParametroSistema(234,$lin[IdStatus]);
				
				$dados .= "\n<IdMonitor>$lin[IdMonitor]</IdMonitor>";
				$dados .= "\n<IdTipoMensagem><![CDATA[$lin[IdTipoMensagem]]]></IdTipoMensagem>";
				$dados .= "\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n<Status><![CDATA[$lin[Status]]]></Status>";
				$dados .= "\n<QtdTentativas><![CDATA[$lin[QtdTentativas]]]></QtdTentativas>";
				$dados .= "\n<IntervaloTentativa><![CDATA[$lin[IntervaloTentativa]]]></IntervaloTentativa>";
				$dados .= "\n<DestinatarioMensagem><![CDATA[$lin[DestinatarioMensagem]]]></DestinatarioMensagem>";
				$dados .= "\n<Mensagem><![CDATA[$lin[Mensagem]]]></Mensagem>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_MonitorAlarme();
?>