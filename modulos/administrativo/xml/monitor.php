<?
	$localModulo	= 1;
 	$Key			= $_GET['Key'];

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	
	if($Key == ''){	
		include('../../../rotinas/verifica.php');
		
		$local_IdLoja = $_SESSION["IdLoja"];
	} else{
		$sqlVars = "SELECT
						Loja.IdLoja,
						Usuario.Login
					FROM
						Loja,
						Usuario
					WHERE
						MD5(CONCAT(Loja.IdLoja,
						Usuario.Login,
						Usuario.Password)) = '$Key'";
		$resVars = mysql_query($sqlVars,$con);
		$linVars = mysql_fetch_array($resVars);

		$local_IdLoja = $linVars[IdLoja];
	}
	
	function get_Monitor(){
		global $con;
		global $_GET;
		global $local_IdLoja;
		
		$local_Limit 			= $_GET['Limit'];
		$local_IdMonitor		= $_GET['IdMonitor'];
		$local_DescricaoMonitor	= $_GET['DescricaoMonitor'];
		$local_IdStatus			= $_GET['IdStatus'];
		$where					= '';
		
		if($local_Limit != ''){
			$local_Limit = "limit 0, $local_Limit";
		}
		
		if($local_IdMonitor != ''){
			$where .= " and MonitorPorta.IdMonitor = '$local_IdMonitor'";
		}
		
		if($local_DescricaoMonitor != ''){
			$where .= " and MonitorPorta.DescricaoMonitor like '%$local_DescricaoMonitor%'";
		}
		
		if($local_IdStatus != ''){
			$where .= " and MonitorPorta.IdStatus = '$local_IdStatus'";
		}
		
		$sql = "select 
					MonitorPorta.IdLoja, 
					MonitorPorta.IdMonitor, 
					MonitorPorta.DescricaoMonitor, 
					MonitorPorta.IdStatus, 
					MonitorPorta.HostAddress, 
					MonitorPorta.Porta,
					MonitorPorta.Timeout,
					MonitorPorta.Latitude,
					MonitorPorta.Longitude,
					MonitorPorta.LoginCriacao, 
					MonitorPorta.DataCriacao, 
					MonitorPorta.LoginAlteracao, 
					MonitorPorta.DataAlteracao
				from 
					MonitorPorta
				where 
					MonitorPorta.IdLoja = '$local_IdLoja'
					$where 
				$Limit;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[Status] = getParametroSistema(232,$lin[IdStatus]);

				$monitor = checkMonitor($lin[IdLoja], $lin[IdMonitor]);

				if($monitor[conectado]){
					// UP
					$lin[Atualmente] = 1;
				}else{
					// DOWN
					$lin[Atualmente] = 0;
				}
				
				$dados .= "\n<IdMonitor>$lin[IdMonitor]</IdMonitor>";
				$dados .= "\n<DescricaoMonitor><![CDATA[$lin[DescricaoMonitor]]]></DescricaoMonitor>";
				$dados .= "\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n<Status><![CDATA[$lin[Status]]]></Status>";
				$dados .= "\n<HostAddress><![CDATA[$lin[HostAddress]]]></HostAddress>";
				$dados .= "\n<Porta><![CDATA[$lin[Porta]]]></Porta>";
				$dados .= "\n<Timeout><![CDATA[$lin[Timeout]]]></Timeout>";
				$dados .= "\n<Latitude><![CDATA[$lin[Latitude]]]></Latitude>";
				$dados .= "\n<Longitude><![CDATA[$lin[Longitude]]]></Longitude>";
				$dados .= "\n<Latencia><![CDATA[$monitor[latencia]]]></Latencia>";
				$dados .= "\n<Atualmente><![CDATA[$lin[Atualmente]]]></Atualmente>";
				$dados .= "\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados .= "\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados .= "\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados .= "\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_Monitor();
?>