<?
	$localModulo		=	1;
	$localOperacao		=	10002;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');	 
		
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_nas_name		= $_POST['filtro_nas_name'];	
	$filtro_secret		= $_POST['filtro_secret'];	
	$filtro_limit			= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != ""){
		$filtro_url		.= "&Ordem=$filtro_ordem";
	}
	
	if($filtro_ordem_direcao != ""){
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	}
	if($filtro_localTipoDado != ""){
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";		
	}	
	
	if($filtro_nas_name != ''){
		$filtro_url	.= "&nasname=".$filtro_nas_name;
		$filtro_sql .= " and nasname like '%$filtro_nas_name%'";
	}
	if($filtro_secret != ''){
		$filtro_url	.= "&secret=".$filtro_secret;
		$filtro_sql .= " and secret like '%$filtro_secret%'";
	}
	if($filtro_limit!=""){
		$filtro_url .= "&Limit=".$filtro_limit;
	}
	
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_nas_xsl.php$filtro_url\"?>";
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
					id,
					nasname,
					shortname,
					type,
					ports,
					secret,
					server,
					community,
					description
				FROM 
					radius.nas
					where
					id <> 0
					$filtro_sql $Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
				
		
		
		echo "<reg>";	
		echo 	"<id><![CDATA[$lin[id]]]></id>";
		echo 	"<nasname><![CDATA[$lin[nasname]]]></nasname>";	
		echo 	"<shortname><![CDATA[$lin[shortname]]]></shortname>";
		echo 	"<TYPE><![CDATA[$lin[type]]]></TYPE>";
		echo 	"<ports><![CDATA[$lin[ports]]]></ports>";	
		echo 	"<secret><![CDATA[$lin[secret]]]></secret>";	
		echo 	"<community><![CDATA[$lin[community]]]></community>";	
		echo 	"<description><![CDATA[$lin[description]]]></description>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
