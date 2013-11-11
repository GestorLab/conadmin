<?
//print_r($_POST['dados']);
//echo $_POST['dados'][6]['value'];
	$localModulo		=	1;
	$localOperacao		=	206;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	/*echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_grupo_device_xsl.php$filtro_url\"?>";*/
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_grupo_device_xsl.php\"?>";
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
		$order = " order by DescricaoGrupoDevice DESC";
		echo "<img>";
		echo 	"<src>../../img/estrutura_sistema/seta_descending.gif</src>";
		echo	"<id>DESC</id>";
		echo 	"<tipo>DescricaoGrupoDevice</tipo>";
		echo "</img>";
	}
	$sql = "SELECT IdGrupoDevice, DescricaoGrupoDevice, DisponivelContrato 
			FROM GrupoDevice 
			where IdLoja = 1 $order $limit";
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
