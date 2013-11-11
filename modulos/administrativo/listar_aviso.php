<?
	$localModulo		=	1;
	$localOperacao		=	74;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja			= $_SESSION["IdLoja"]; 
	$local_Login			= $_SESSION['Login'];
	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_IdAviso			= $_POST['IdAviso'];
	$filtro_data_expiracao	= $_POST['filtro_data_expiracao'];
	$filtro_aviso_forma		= $_POST['filtro_aviso_forma'];
	$filtro_titulo			= url_string_xsl($_POST['filtro_titulo'],'url',false);
	$filtro_visivel			= $_POST['filtro_visivel'];
	$filtro_limit			= $_POST['filtro_limit'];
	
	if($filtro_IdAviso == ''&& $_GET['IdAviso']!=''){
		$filtro_IdAviso	= $_GET['IdAviso'];
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_titulo!=""){
		$filtro_url .= "&TituloAviso=".$filtro_titulo;
		$filtro_sql .= " and (Aviso.TituloAviso like '%$filtro_titulo%')";
	}
	if($filtro_data_expiracao != ''){
		$filtro_url .= "&DataExpiracao=".$filtro_data_expiracao;
		$filtro_data_expiracao = dataConv($filtro_data_expiracao,'d/m/Y','Y-m-d');
		$filtro_sql .= " and Aviso.DataExpiracao like '".$filtro_data_expiracao."%'";
	}
	if($filtro_aviso!=""){
		$filtro_url	.= "&IdAviso=".$filtro_aviso;
		$filtro_sql	.= " and Aviso.IdAviso =".$filtro_aviso;
	}
	if($filtro_aviso_forma!=""){
		$filtro_url	.= "&IdAvisoForma=".$filtro_aviso_forma;
		$filtro_sql	.= " and Aviso.IdAvisoForma =".$filtro_aviso_forma;
	}
	if($filtro_visivel!=""){
		$filtro_url	.= "&Visivel=".$filtro_visivel;
		
		if($filtro_visivel == 1){
			$filtro_sql	.= " and Aviso.DataExpiracao > concat(curdate(),' ',curtime())";
		}else{
			$filtro_sql	.= " and Aviso.DataExpiracao <= concat(curdate(),' ',curtime())";
		}
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_aviso_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "SELECT
				Aviso.IdAviso, 
				Aviso.DataExpiracao,
				substr(Aviso.DataExpiracao,12,5) as Hora,
				substr(Aviso.TituloAviso,1,60) as TituloAviso,
				AvisoForma.DescricaoAvisoForma
			FROM 
				Aviso LEFT JOIN AvisoForma ON (
					Aviso.IdAvisoForma = AvisoForma.IdAvisoForma
				)
			WHERE
				IdLoja = $local_IdLoja
				$filtro_sql 
			order by
				Aviso.IdAviso desc
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
		if($lin[DataExpiracao] > date('Y-m-d H:i:s') && $lin[DataExpiracao] != date('Y-m-d H:i:s')){
			$lin[Visivel]	=	'Sim';
		}else{
			$lin[Visivel]	=	'Não';
		}
		
		$lin[DataExpiracaoTemp]	= dataConv($lin[DataExpiracao],'Y-m-d','d/m/Y');
		$lin[DataExpiracao]		= dataConv($lin[DataExpiracao],'Y-m-d','Ymd');
		
		$lin[HoraTemp]	= $lin[Hora];
		$lin[Hora]		= str_replace(':','',$lin[Hora]);	
		
		echo "<reg>";			
		echo 	"<IdAviso>$lin[IdAviso]</IdAviso>";
		echo 	"<DataExpiracao><![CDATA[$lin[DataExpiracao]]]></DataExpiracao>";
		echo	"<DataExpiracaoTemp><![CDATA[$lin[DataExpiracaoTemp]]]></DataExpiracaoTemp>";
		echo 	"<Hora><![CDATA[$lin[Hora]]]></Hora>";
		echo 	"<HoraTemp><![CDATA[$lin[HoraTemp]]]></HoraTemp>";
		echo 	"<DescricaoAvisoForma><![CDATA[$lin[DescricaoAvisoForma]]]></DescricaoAvisoForma>";
		echo 	"<TituloAviso><![CDATA[$lin[TituloAviso]]]></TituloAviso>";
		echo 	"<Visivel><![CDATA[$lin[Visivel]]]></Visivel>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>