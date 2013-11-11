<?
	$localModulo		=	1;
	$localOperacao		=	117;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja			= $_SESSION["IdLoja"];
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	
	$filtro_log				= url_string_xsl($_POST['filtro_log'],'url',false);
	$filtro_data_inicio		= $_POST['filtro_data_inicio'];
	$filtro_data_termino	= $_POST['filtro_data_termino'];
	
	$filtro_limit			= $_POST['filtro_limit'];
	
	if($filtro_limit == '' && $_GET['filtro_limit']!=''){
		$filtro_limit	= $_GET['filtro_limit'];
	}
		
	$filtro_url	 			= "";
	$filtro_sql  			= "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_log!=''){
		$filtro_url .= "&Log=$filtro_log";
		$filtro_sql .=	" and Log like '%$filtro_log%'";
	}
		
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataHoraInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(DataHoraInicio,1,10) >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_termino!=""){
		$filtro_url .= "&DataHoraTermino=".$filtro_data_termino;
		$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(DataHoraInicio,1,10) <= '$filtro_data_termino'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_backup_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro != "s"){
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$cont	= 0;
				
	$sql	= "
			select			
				Backup.DataHoraInicio,
				Backup.DataHoraConclusao,
				Backup.Size,
				Log				
			from
				Backup
			where
				1 $filtro_sql
			order by
				Backup.DataHoraInicio DESC
				$Limit";
	$res	= mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$lin[DataHoraInicioTemp] 	= dataConv($lin[DataHoraInicio],"Y-m-d H:i:s","d/m/Y H:i:s");
		$lin[DataHoraConclusaoTemp] = dataConv($lin[DataHoraConclusao],"Y-m-d H:i:s","d/m/Y H:i:s");
		
		$lin[DataHoraInicio] 		= dataConv($lin[DataHoraInicio],"Y-m-d H:i:s","YmdHis");
		$lin[DataHoraConclusao] 	= dataConv($lin[DataHoraConclusao],"Y-m-d H:i:s","YmdHis");	
		
		$SizeTemp = explode(',', $lin[Size]);
		$Size = $SizeTemp[0].'.'.$SizeTemp[1];
		
		$lin[SizeTemp] = $lin[Size];
		$lin[Size] = floatval($Size);
		
		$tempLog = explode("\n", $lin[Log]);
		$Log = "";
		$i = 0;
		
		while($tempLog[$i]!=''){
			$Log .= "<regInt><texto>$tempLog[$i]</texto></regInt>";
			$i++;
		}
		
		echo "<reg>";
		echo 	"<DataHoraInicioTemp><![CDATA[$lin[DataHoraInicioTemp]]]></DataHoraInicioTemp>";
		echo 	"<DataHoraConclusaoTemp><![CDATA[$lin[DataHoraConclusaoTemp]]]></DataHoraConclusaoTemp>";
		echo 	"<DataHoraInicio><![CDATA[$lin[DataHoraInicio]]]></DataHoraInicio>";
		echo 	"<DataHoraConclusao><![CDATA[$lin[DataHoraConclusao]]]></DataHoraConclusao>";
		echo 	"<SizeTemp><![CDATA[$lin[SizeTemp]]]></SizeTemp>";
		echo 	"<Size><![CDATA[$lin[Size]]]></Size>";
		echo 	"<Log>$Log</Log>";		

		echo "</reg>";	
		
		$cont++;
		
		if($filtro_limit!= ""){
			if($cont >= $filtro_limit){
				break;
			}
		}
	}
	
	echo "</db>";
?>
