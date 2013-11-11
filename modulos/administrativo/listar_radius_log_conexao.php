<?
	$localModulo		=	1;
	$localOperacao		=	10001;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION['Login'];
	$local_IdLoja			= $_SESSION['IdLoja'];
	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_IdAgenda		= $_POST['IdAgenda'];
	$filtro_data			= $_POST['filtro_data'];
	$filtro_hora			= $_POST['filtro_hora'];
	$filtro_status			= $_POST['filtro_status'];
	$filtro_IdPessoa		= $_POST['IdPessoa'];
	$filtro_descricao		= $_POST['filtro_descricao'];
	$MesReferenciaTemp		= $_POST['MesReferencia'];
	$filtro_limit			= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($Login != ''){
		$filtro_sql = " and username='$Login'";
	}

	if($Mac != ''){
		$filtro_sql = " and CallingStationId='$Mac'";
	}
	
	if($filtro_status!=""){
		$filtro_url	.= "&IdStatus=".$filtro_status;
		$filtro_sql	.= " and Status =".$filtro_status;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_radius_log_conexao_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= "$filtro_limit";
		}
	}else{
		$Limit 	= getCodigoInterno(7,5);
	}
	
	
	if($filtro_servidor!=""){	$servidor	=	" and IdCodigoInterno = '$filtro_servidor'";	}
	
	$cont	=	0;
	$sql	=	"select IdCodigoInterno,DescricaoCodigoInterno,ValorCodigoInterno from CodigoInterno where IdLoja = '$local_IdLoja' and IdGrupoCodigoInterno = 10000 $servidor";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		
		$IdServidor	=	$lin[IdCodigoInterno];	
		$Servidor	=	$lin[DescricaoCodigoInterno];	
		$aux		=	explode("\n",$lin[ValorCodigoInterno]);
				
		$bd[server]	=	trim($aux[0]); //Host
		$bd[login]	=	trim($aux[1]); //Login
		$bd[senha]	=	trim($aux[2]); //Senha
		$bd[banco]	=	trim($aux[3]); //DB
	
		$conRadius	=	mysql_connect($bd[server],$bd[login],$bd[senha]);
			
		mysql_select_db($bd[banco],$conRadius);
	
		$sqlRadius	=	"select
						date_format(AcctStartTime, '%Y%m%d') DataIniConexao,
						date_format(AcctStopTime, '%Y%m%d') DataFimConexao
					from
						radius.radacct
					where
						AcctSessionTime != 0 and
						(AcctStartTime >= '$MesReferenciaTemp-01' or AcctStopTime >= '$MesReferenciaTemp-01') 
						$filtro_sql
					order by
						AcctStartTime";
		$res = mysql_query($sqlRadius,$conRadius);
		while($linRadius = mysql_fetch_array($resRadius)){
			if($MenorData == 0){
				$MenorData = $linRadius[DataIniConexao];
			}else{
				if($MenorData > $linRadius[DataIniConexao]){
					$MenorData = $linRadius[DataIniConexao];
				}
			}
			if($MaiorData == 0){
				$MaiorData = $linRadius[DataIniConexao];
			}else{
				if($MaiorData < $linRadius[DataFimConexao]){
					$MaiorData = $linRadius[DataFimConexao];
				}
			}
		}
	
		$MenorData = dataConv($MenorData,'Ymd', 'Y-m-d');
		$MaiorData = dataConv($MaiorData,'Ymd', 'Y-m-d');
	
		for($i=0; $i <nDiasIntervalo($MenorData,$MaiorData); $i++){
			$Pos[substr(dataConv(incrementaData($MenorData,$i), 'Y-m-d','d/m/Y'), 0, 5)] = $i;
			$Dias[$i] = substr(dataConv(incrementaData($MenorData,$i), 'Y-m-d','d/m/Y'), 0, 5);
		}
	
		$Dias[$i] = '';
	
		$sqlRadius2	=	"select
						RadAcctId,
						username Login,
						CallingStationId Mac,
						date_format(AcctStartTime, '%Y-%m-%d') DataIniConexao,
						date_format(AcctStopTime, '%Y-%m-%d') DataFimConexao,
						AcctSessionTime TempoConexao,
						AcctInputOctets Download,
						AcctOutputOctets Upload
					from
						radius.radacct
					where
						AcctSessionTime != 0 and
						(AcctStartTime >= '$MesReferenciaTemp-01' or AcctStopTime >= '$MesReferenciaTemp-01')
						$filtro_sql 
					order by
						RadAcctId desc, 
						AcctStartTime
					$Limit";
		$res	=	mysql_query($sqlRadius2,$conRadius);
		while($linRadius2	=	mysql_fetch_array($resRadius2)){
			$cont++;
			
			$linRadius2[DataIniConexaoTemp]	=	dataConv($linRadius2[DataIniConexao],'Y-m-d',d/m/Y);
			$linRadius2[DataIniConexao]		=	dataConv($linRadius2[DataIniConexao],'Y-m-d',Ymd);
			
			$linRadius2[DataFimConexaoTemp]	=	dataConv($linRadius2[DataFimConexao],'Y-m-d',d/m/Y);
			$linRadius2[DataFimConexao]		=	dataConv($linRadius2[DataFimConexao],'Y-m-d',Ymd);
		
			echo "<reg>";			
			echo 	"<RadAcctId><![CDATA[$linRadius2[RadAcctId]]]></RadAcctId>";
			echo 	"<Login><![CDATA[$linRadius2[Login]]]></Login>";
			echo 	"<Mac><![CDATA[$linRadius2[Mac]]]></Mac>";
			echo 	"<DataIniConexao><![CDATA[$linRadius2[DataIniConexao]]]></DataIniConexao>";
			echo 	"<DataIniConexaoTemp><![CDATA[$linRadius2[DataIniConexaoTemp]]]></DataIniConexaoTemp>";
			echo 	"<DataFimConexao><![CDATA[$linRadius2[DataFimConexao]]]></DataFimConexao>";
			echo	"<DataFimConexaoTemp><![CDATA[$linRadius2[DataFimConexaoTemp]]]></DataFimConexaoTemp>";
			echo	"<TempoConexao><![CDATA[$linRadius2[TempoConexao]]]></TempoConexao>";
			echo	"<Download><![CDATA[$linRadius2[Download]]]></Download>";
			echo	"<Upload><![CDATA[$linRadius2[Upload]]]></Upload>";
			echo "</reg>";	
			
			if($cont >= $Limit)	break;
		}
		
		mysql_close($conRadius);
	}
	
	echo "</db>";
?>
