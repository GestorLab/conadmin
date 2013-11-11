<?
	$localModulo		=	1;
	$localOperacao		=	10000;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja			= $_SESSION['IdLoja']; 
	$local_Login			= $_SESSION['Login'];
	$local_IdLicenca		= $_SESSION["IdLicenca"];	
	
	$filtro					= $_POST['filtro'];

	$filtro_PoolName		= $_POST['filtro_PoolName'];
	$filtro_FrameIpAddress	= $_POST['filtro_FrameIpAddress'];
	$filtro_NasIpAddress	= $_POST['filtro_NasIpAddress'];
	
	$filtro_limit			= $_POST['filtro_limit'];
	$filtro_id				= $_POST['id'];
	$where					= true;
	
	if($_GET['id']!=''){
		$filtro_id	= $_GET['id'];
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";

	if($filtro_PoolName != ""){
		$filtro_url	.= "&filtro_PoolName=$filtro_PoolName";
		$filtro_sql .= "and pool_name LIKE '%$filtro_PoolName%'";
	}
	
	if($filtro_FrameIpAddress != ""){
		$filtro_url	.= "&filtro_FrameIpAddress=$filtro_FrameIpAddress";
		$filtro_sql .= "and framedipaddress LIKE '%$filtro_FrameIpAddress%'";
	}
	
	if($filtro_NasIpAddress != ""){
		$filtro_url	.= "&filtro_NasIpAddress=$filtro_NasIpAddress";
		$filtro_sql .= "and nasipaddress LIKE '%$filtro_NasIpAddress%'";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}


	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_radippool_xsl.php$filtro_url\"?>";
	echo "<db>";

	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= $filtro_limit;
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= getCodigoInterno(7,5);
		}else{
			$Limit 	= $filtro_limit;
		}
	}
	
	
	$sql = "select 
			IdCodigoInterno,
			ValorCodigoInterno 
		from 
			CodigoInterno 
		where 
			IdLoja = $local_IdLoja and 
			IdGrupoCodigoInterno = 10000 and 
			IdCodigoInterno = 1";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
	
	
	$aux		=	explode("\n",$lin[ValorCodigoInterno]);
	
	$bd[server]	=	trim($aux[0]); //Host
	$bd[login]	=	trim($aux[1]); //Login
	$bd[senha]	=	trim($aux[2]); //Senha
	$bd[banco]	=	trim($aux[3]); //DB
	
	$conRadius	=	mysql_connect($bd[server],$bd[login],$bd[senha]);
	
	$sql = "SELECT
				id,
				pool_name,
				framedipaddress,
				nasipaddress,
				calledstationid,
				callingstationid,
				expiry_time,
				username,
				pool_key
			FROM 
				radius.radippool
			WHERE
				id > 0
			$filtro_sql";
	$res = mysql_query($sql,$conRadius) or die(mysql_error());
	while($lin	=	@mysql_fetch_array($res)){
		$Tipo	=	substr($linRadius[DescTipo],0,1);
		
		echo "\n<reg>";	
		echo 	"\n<id><![CDATA[$lin[id]]]></id>";
		echo 	"\n<pool_name><![CDATA[$lin[pool_name]]]></pool_name>";
		echo 	"\n<framedipaddress><![CDATA[$lin[framedipaddress]]]></framedipaddress>";
		echo 	"\n<nasipaddress><![CDATA[$lin[nasipaddress]]]></nasipaddress>";
		echo 	"\n<calledstationid><![CDATA[$lin[calledstationid]]]></calledstationid>";
		echo 	"\n<callingstationid><![CDATA[$lin[callingstationid]]]></callingstationid>";
		echo "\n</reg>";				
		
	}
		
		mysql_close($conRadius);
	
	echo "</db>";
?>
