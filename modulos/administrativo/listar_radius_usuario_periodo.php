<?
	$localModulo		=	1;
	$localOperacao		=	10001;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja				= $_SESSION['IdLoja']; 
	$local_Login				= $_SESSION['Login'];
	$local_IdLicenca			= $_SESSION["IdLicenca"];	
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_servidor				= $_POST['filtro_servidor'];
	$filtro_login					= $_POST['filtro_login'];
	$filtro_mac						= $_POST['filtro_mac'];
	$filtro_data_hora_inicio		= $_POST['filtro_data_hora_inicio'];
	$filtro_data_hora_fim			= $_POST['filtro_data_hora_fim'];
	$filtro_data_hora_especifica	= $_POST['filtro_data_hora_especifica'];
	$filtro_ip						= $_POST['filtro_ip'];
	$filtro_nas						= $_POST['filtro_nas'];	
	$filtro_limit					= $_POST['filtro_limit'];
	$servidor						= "";	
	
	$filtro_oculta_ip			= $_SESSION['filtro_oculta_ip'];	
	$filtro_oculta_mac			= $_SESSION['filtro_oculta_mac'];
	$localOcultaNAS				= $_SESSION["filtro_oculta_nas"];
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_servidor!=""){
		$filtro_url .= "&IdServidor=".$filtro_servidor;
	}
	if($filtro_login != ''){
		$filtro_url .= "&Login=".$filtro_login;
		$filtro_sql .= " and UserName = '$filtro_login'";
	}
	if($filtro_mac!=""){
		$filtro_url	.= "&MAC=".$filtro_mac;
		$filtro_sql	.= " and CallingStationId = '$filtro_mac'";
	}
	if($filtro_ip!=""){
		$filtro_url	.= "&IP=".$filtro_ip;
		$filtro_sql	.= " and FramedIPAddress = '$filtro_ip'";
	}	
	if($filtro_nas!=""){
		$filtro_url	.= "&NAS=".$filtro_nas;
		$filtro_sql	.= " and CalledStationId = '$filtro_nas'";
	}
	if($filtro_data_hora_especifica != ""){
		$filtro_url	.= "&DataHoraEspecifica=".$filtro_data_hora_especifica;
		$filtro_data_hora_especifica = dataConv($filtro_data_hora_especifica, "d/m/Y H:i:s", "Y-m-d H:i:s");
		$filtro_sql	.= " and (AcctStartTime <= '$filtro_data_hora_especifica' and AcctStopTime >= '$filtro_data_hora_especifica')";
	}else{
		if($filtro_data_hora_inicio!=""){
			$filtro_url	.= "&DataHoraInicio=".$filtro_data_hora_inicio;
			$filtro_data_hora_inicio = dataConv($filtro_data_hora_inicio, "d/m/Y H:i:s", "Y-m-d H:i:s");
			$filtro_sql	.= " and AcctStartTime >= '$filtro_data_hora_inicio'";
			
			if($filtro_data_hora_fim!=""){
				$filtro_url .= "&DataHoraFim=".$filtro_data_hora_fim;
				$filtro_data_hora_fim = dataConv($filtro_data_hora_fim, "d/m/Y H:i:s", "Y-m-d H:i:s");
				$filtro_sql	.= " and AcctStartTime <= '$filtro_data_hora_fim'";
			}
		} elseif($filtro_data_hora_fim!=""){
			$filtro_url .= "&DataHoraFim=".$filtro_data_hora_fim;
			$filtro_data_hora_fim = dataConv($filtro_data_hora_fim, "d/m/Y H:i:s", "Y-m-d H:i:s");
			$filtro_sql	.= " and AcctStopTime <= '$filtro_data_hora_fim'";
		}
	}
	if($filtro_oculta_ip!="")
		$filtro_url .= "&OcultaIP=".$filtro_oculta_ip;
		
	if($filtro_oculta_mac!="")
		$filtro_url .= "&OcultaMAC=".$filtro_oculta_mac;
	
	if($filtro_oculta_torre_ssid!="")
		$filtro_url .= "&OcultaTorreSSID=".$filtro_oculta_torre_ssid;
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_radius_usuario_periodo_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
	
	if($filtro_servidor!=""){	$servidor	=	" and IdCodigoInterno = '$filtro_servidor'";	}
	
	$sql	=	"select IdCodigoInterno,DescricaoCodigoInterno,ValorCodigoInterno from CodigoInterno where IdLoja = '$local_IdLoja' and IdGrupoCodigoInterno = 10000 $servidor";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		
		$IdServidor	=	$lin[IdCodigoInterno];	
		$aux		=	explode("\n",$lin[ValorCodigoInterno]);
				
		$bd[server]	=	trim($aux[0]); //Host
		$bd[login]	=	trim($aux[1]); //Login
		$bd[senha]	=	trim($aux[2]); //Senha
		$bd[banco]	=	trim($aux[3]); //DB
	
		$conRadius	=	mysql_connect($bd[server],$bd[login],$bd[senha]);
			
		mysql_select_db($bd[banco],$conRadius);
		
		$sqlRadius = "(
						select
							RadAcctId,
							UserName,
							AcctStartTime DataInicio,
							AcctStopTime DataFim,
							AcctInputOctets Upload,
							AcctOutputOctets Download,
							FramedIPAddress IP,
							CallingStationId MAC,
							CalledStationId NAS
						from
							radacctJornal
						where
							UserName != '' 
							$filtro_sql
						$Limit
					) UNION (
						select
							RadAcctId,
							UserName,
							AcctStartTime DataInicio,
							AcctStopTime DataFim,
							AcctInputOctets Upload,
							AcctOutputOctets Download,
							FramedIPAddress IP,
							CallingStationId MAC,
							CalledStationId NAS
						from
							radacct
						where
							UserName != '' 
							$filtro_sql
						$Limit
					)
					order by 
						RadAcctId desc
					$Limit";
		$resRadius = mysql_query($sqlRadius,$conRadius);
		while($linRadius = @mysql_fetch_array($resRadius)){
			$linRadius[DataInicioTemp]	=	dataConv($linRadius[DataInicio],'Y-m-d H:i:s','d/m/Y H:i:s');
			$linRadius[DataInicio]		=	dataConv($linRadius[DataInicio],'Y-m-d H:i:s','YmdHis');
			
			$linRadius[DataFimTemp]		=	dataConv($linRadius[DataFim],'Y-m-d H:i:s','d/m/Y H:i:s');
			$linRadius[DataFim]			=	dataConv($linRadius[DataFim],'Y-m-d H:i:s','YmdHis');
			
			$linRadius[Duracao]			= 	SubHora($linRadius[DataFimTemp],$linRadius[DataInicioTemp],'s');    
			$linRadius[Duracao]			=	SegHora($linRadius[Duracao]);

			$Duracao					+= horaSegundo($linRadius[Duracao]);
			$Download					+= $linRadius[Download];
			$Upload						+= $linRadius[Upload];
			
			$linRadius[DownloadTemp]	=	byte_convert($linRadius[Download],2);
			$linRadius[UploadTemp]		=	byte_convert($linRadius[Upload],2);
			
			$count	=	substr_count($linRadius[UserName], ':'); 
		
			echo "<reg>";	
			echo 	"<RadAcctId><![CDATA[$linRadius[RadAcctId]]]></RadAcctId>";	
			echo 	"<DataInicio><![CDATA[$linRadius[DataInicio]]]></DataInicio>";
			echo 	"<DataInicioTemp><![CDATA[$linRadius[DataInicioTemp]]]></DataInicioTemp>";
			echo 	"<DataFim><![CDATA[$linRadius[DataFim]]]></DataFim>";
			echo 	"<Duracao><![CDATA[$linRadius[Duracao]]]></Duracao>";
			echo 	"<Download><![CDATA[$linRadius[Download]]]></Download>";
			echo 	"<DownloadTemp><![CDATA[$linRadius[DownloadTemp]]]></DownloadTemp>";
			echo 	"<Upload><![CDATA[$linRadius[Upload]]]></Upload>";
			echo 	"<UploadTemp><![CDATA[$linRadius[UploadTemp]]]></UploadTemp>";
			echo 	"<DataFim><![CDATA[$linRadius[DataFim]]]></DataFim>";
			echo 	"<DataFimTemp><![CDATA[$linRadius[DataFimTemp]]]></DataFimTemp>";
			echo 	"<UserName><![CDATA[$linRadius[UserName]]]></UserName>";
			echo 	"<MAC><![CDATA[$linRadius[MAC]]]></MAC>";
			echo 	"<IP><![CDATA[$linRadius[IP]]]></IP>";
			echo 	"<NAS><![CDATA[$linRadius[NAS]]]></NAS>";
			echo "</reg>";	
				
		}
		$Duracao	= segHora($Duracao);
		$Download	= byte_convert($Download,2);
		$Upload		= byte_convert($Upload,2);

		echo "<somatorio>";	
		echo 	"<Duracao><![CDATA[$Duracao]]></Duracao>";
		echo 	"<Download><![CDATA[$Download]]></Download>";
		echo 	"<Upload><![CDATA[$Upload]]></Upload>";
		echo "</somatorio>";	
		
		mysql_close($conRadius);
	}
	echo "</db>";
?>
