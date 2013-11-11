<?
	$localModulo		=	1;
	$localOperacao		=	205;
	$localSuboperacao	=	"R";
	
	$localTituloOperacao	= "Device";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja = $_SESSION['IdLoja'];
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	/*echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_grupo_device_xsl.php$filtro_url\"?>";*/
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_device_xsl.php?IdTipoDevice=$_REQUEST[IdTipoDevice]&amp;filtro_nome_device=$_REQUEST[filtro_nome_device]&amp;filtro_limit=$_REQUEST[filtro_limit]\"?>";
	echo "<db>";
	
	$filtro_sql = "";
	
	if($_REQUEST['filtro_nome_device'] != ""){
		$filtro_sql .= " AND (D.DescricaoDevice LIKE '%{$_REQUEST[filtro_nome_device]}%')";
	}
	
	if($_REQUEST['IdTipoDevice'] != ""){
		$filtro_sql .= " AND D.IdTipoDevice = {$_REQUEST[IdTipoDevice]}";
	}
	
	//" AND (MonitorPorta.HostAddress like '%$filtro_host_address%')";
	
	if($_REQUEST['filtro_limit'] != ""){
		$limit = " limit 0, ".$_REQUEST['filtro_limit'];
	}
	
	if($_REQUEST['order']){
		$order = " order by " . $_REQUEST['order'] . " " . $_REQUEST['tipoOrder'];
		//$order = " order by DescricaoGrupoDevice DESC";
		if($_REQUEST['tipoOrder'] == "DESC"){
			$imageSrc = '../../img/estrutura_sistema/seta_descending.gif';
		}else{
			$imageSrc = "../../img/estrutura_sistema/seta_ascending.gif";
		}
		echo "<img>";
		echo 	"<src>$imageSrc</src>";
		echo	"<id>".$_REQUEST['tipoOrder']."</id>";
		echo 	"<tipo>".$_REQUEST['order']."</tipo>";
		echo "</img>";
	}else{
		$order = " order by D.DescricaoDevice DESC";
		echo "<img>";
		echo 	"<src>../../img/estrutura_sistema/seta_descending.gif</src>";
		echo	"<id>DESC</id>";
		echo 	"<tipo>D.DescricaoDevice</tipo>";
		echo "</img>";
	}
	
	$sql = "SELECT D.IdDevice, D.DescricaoDevice, D.IdTipoDevice, D.IdDevicePerfil, GD.DescricaoGrupoDevice FROM Device as D
	        LEFT JOIN GrupoDevice AS GD ON(D.IdGrupoDevice = GD.IdGrupoDevice) 
			WHERE D.IdLoja = $local_IdLoja $filtro_sql $order $limit";
	//echo $sql;die;
	$res	=	mysql_query($sql,$con);
	$count = 1;
	while($lin	=	@mysql_fetch_array($res, MYSQL_ASSOC)){
		$dadosUrl = $lin;
		$dadosUrl['IdLoja'] = $_SESSION['IdLoja'];
		$dadosUrl = json_encode($dadosUrl);
		if($count%2 != 0 ){
			$Color = "";
		}else{
			$Color = getParametroSistema(15,8);
		}
		$count++;
		echo "<reg>";
		echo 	"<IdDevice>$lin[IdDevice]</IdDevice>";
		echo 	"<DescricaoDevice><![CDATA[$lin[DescricaoDevice]]]></DescricaoDevice>";
		echo	"<IdDevicePerfil><![CDATA[$lin[IdDevicePerfil]]]></IdDevicePerfil>";
		echo 	"<DescricaoGrupoDevice><![CDATA[$lin[DescricaoGrupoDevice]]]></DescricaoGrupoDevice>";
		echo 	"<dadosUrl><![CDATA[$dadosUrl]]></dadosUrl>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";
	}
	
		echo "</db>";
	
?>
