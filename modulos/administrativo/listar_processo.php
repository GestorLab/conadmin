<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	
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
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_processo_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	$sql	=	"SHOW FULL PROCESSLIST;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdProcesso>$lin[Id]</IdProcesso>";	
		echo 	"<User><![CDATA[$lin[User]]]></User>";
		echo 	"<Host><![CDATA[$lin[Host]]]></Host>";
		echo 	"<DB><![CDATA[$lin[db]]]></DB>";
		echo 	"<Command><![CDATA[$lin[Command]]]></Command>";
		echo 	"<Time><![CDATA[$lin[Time]]]></Time>";
		echo 	"<State><![CDATA[$lin[State]]]></State>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
