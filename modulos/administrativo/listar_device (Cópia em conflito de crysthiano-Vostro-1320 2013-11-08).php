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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_device_xsl.php\"?>";
	echo "<db>";
	
	if(isset($_GET['filtro_limit'])){
		$limit = " limit 0, ".$_GET['filtro_limit'];
	}
	
	if($_GET['order']){
		$order = " order by " . $_GET['order'] . " " . $_GET['tipoOrder'];
		//$order = " order by DescricaoGrupoDevice DESC";
		if($_GET['tipoOrder'] == "DESC"){
			$imageSrc = '../../img/estrutura_sistema/seta_descending.gif';
		}else{
			$imageSrc = "../../img/estrutura_sistema/seta_ascending.gif";
		}
		echo "<img>";
		echo 	"<src>$imageSrc</src>";
		echo	"<id>".$_GET['tipoOrder']."</id>";
		echo 	"<tipo>".$_GET['order']."</tipo>";
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
			WHERE D.IdLoja = $local_IdLoja $order $limit";
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
