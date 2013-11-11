<?
//print_r($_POST['dados']);
//echo $_POST['dados'][6]['value'];
	$localModulo		=	1;
	$localOperacao		=	206;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja = $_SESSION['IdLoja'];
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	/*echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_grupo_device_xsl.php$filtro_url\"?>";*/
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_grupo_device_xsl.php?filtro_nome_grupo_device=$_REQUEST[filtro_nome_grupo_device]&amp;filtro_limit=$_REQUEST[filtro_limit]\"?>";
	echo "<db>";

	$filtro_sql = "";
	
	if($_REQUEST['filtro_nome_grupo_device'] != ""){
		$filtro_sql .= " AND (DescricaoGrupoDevice LIKE '%{$_REQUEST[filtro_nome_grupo_device]}%')";
	}
	
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
		$order = " order by DescricaoGrupoDevice DESC";
		echo "<img>";
		echo 	"<src>../../img/estrutura_sistema/seta_descending.gif</src>";
		echo	"<id>DESC</id>";
		echo 	"<tipo>DescricaoGrupoDevice</tipo>";
		echo "</img>";
	}
	$sql = "SELECT IdGrupoDevice, DescricaoGrupoDevice, DisponivelContrato 
			FROM GrupoDevice 
			where IdLoja = $local_IdLoja $filtro_sql $order $limit";
	//echo $sql;die;
	$res	=	mysql_query($sql,$con);
	$count = 1;
	while($lin	=	mysql_fetch_assoc($res)){
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
		echo 	"<IdGrupoDevice>$lin[IdGrupoDevice]</IdGrupoDevice>";	
		echo 	"<DescricaoGrupoDevice><![CDATA[$lin[DescricaoGrupoDevice]]]></DescricaoGrupoDevice>";
		echo    "<DisponivelContrato><![CDATA[$lin[DisponivelContrato]]]></DisponivelContrato>";
		echo 	"<dadosUrl><![CDATA[$dadosUrl]]></dadosUrl>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
