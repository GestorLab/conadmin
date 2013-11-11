<?
	$localModulo		=	1;
	$localOperacao		=	1;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 	
	$IdLoja							= $_SESSION['IdLoja'];

	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_descricao				= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_operadora				= $_POST['filtro_id_operadora'];
	$filtro_id_status				= $_POST['filtro_status'];
	$filtro_valor					= $_POST['filtro_valor'];
	$filtro_quantidade				= $_POST['filtro_quantidade'];
	
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
	
	if($filtro_descricao != ""){
		$filtro_url	.= "&DescricaoContaSMS=$filtro_descricao";
		$filtro_sql .=	" and DescricaoContaSMS Like '%$filtro_descricao%'";
	}
	
	if($filtro_operadora != ""){
		$filtro_url	.= "&IdOperadora=$filtro_operadora";
		$filtro_sql .=	" and IdOperadora = $filtro_operadora";
	}

	if($filtro_id_status !=''){
		$filtro_url .= "&IdStatus=$filtro_id_status";
		$filtro_sql .=	" and IdStatus = $filtro_id_status";
	}

	if($filtro_valor!=""){
		$filtro_url .= "&Valor=$filtro_valor";
		$filtro_sql .=	" and ValorParametroSMS = $filtro_valor";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_sms_xsl.php?$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	$Status = "";
	$sql	=	"Select
					ContaSMS.IdContaSMS,
					ContaSMS.DescricaoContaSMS,
					ContaSMS.IdOperadora,
					ContaSMS.IdStatus
				From
					ContaSMS
				where
					IdLoja = $IdLoja
					$filtro_sql";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[IdStatus] == 1){
			$Status = "Ativo";
		} else{
			$Status = "Desativado";
		}
		$sql2 = "Select
					DescricaoOperadora
				from
					OperadoraSMS
				where
					IdOperadora = $lin[IdOperadora]";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		echo "<reg>";	
		echo "\n<IdContaSMS>$lin[IdContaSMS]</IdContaSMS>";
		echo "\n<DescricaoContaSMS><![CDATA[$lin[DescricaoContaSMS]]]></DescricaoContaSMS>";
		echo "\n<IdOperadora>$lin2[DescricaoOperadora]</IdOperadora>";
		echo "\n<IdStatus><![CDATA[$Status]]></IdStatus>";
		echo "</reg>";	
	}
	echo "</db>";
?>
