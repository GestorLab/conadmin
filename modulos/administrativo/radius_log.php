<?
	$localModulo		=	1;
	$localOperacao		=	10001;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja					= $_SESSION["IdLoja"];

	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_usuario					= $_POST['filtro_usuario'];
	$filtro_tempo					= $_POST['filtro_tempo'];
	$filtro_ano						= $_POST['filtro_ano'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	if($_GET['filtro_usuario'] != ""){
		$filtro_usuario = $_GET['filtro_usuario'];
	}
	
	if($_GET['filtro_limit'] != ""){
		$filtro_limit = $_GET['filtro_limit'];
	}
	
	if($_GET['filtro_local'] != ""){
		$filtro_local = $_GET['filtro_local'];
	}
	
	$filtro_url	= "&Ano=$filtro_ano";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_usuario!=''){
		$filtro_url .= "&Usuario=$filtro_usuario";
		$filtro_sql .= "Login=$filtro_usuario&";
	}
	
	if($filtro_tempo!=''){
		if($filtro_tempo < 15){
			$filtro_tempo = 15;
		}
		$filtro_url .= "&Tempo=$filtro_tempo";
	}
	
	if($filtro_limit!=""){
		$filtro_url .= "&Limit=".$filtro_limit;
	}else{
		$filtro_limit 	= getCodigoInterno(7,5);
		$filtro_limit 	= 20;
	}
	
	if($filtro_local == "Contrato"){
		if(trim(getParametroSistema(6,7)) != ""){
			$file= file(getParametroSistema(6,7)."/modulos/administrativo/rotinas/log_radius_fonte.php?".$filtro_sql."Qtd=$filtro_limit&Ano=$filtro_ano&IdLoja=$local_IdLoja");
		}else{
			$file= file(getParametroSistema(6,3)."/modulos/administrativo/rotinas/log_radius_fonte.php?".$filtro_sql."Qtd=$filtro_limit&Ano=$filtro_ano&IdLoja=$local_IdLoja");
		}
		echo "<db>";
		
		for($i=0; $i < count($file); $i++){
			echo "<reg>";
			echo "	<retorno><![CDATA[".$file[$i]."]]></retorno>";
			echo "</reg>";
		}
		
		echo "</db>";
	}else{
		if($filtro_url != ""){
			$filtro_url	= "?f=t".$filtro_url;
			$filtro_url	= url_string_xsl($filtro_url,'convert');
		}
		
		if(trim(getParametroSistema(6,7)) != ""){
			$file= file(getParametroSistema(6,7)."/modulos/administrativo/rotinas/log_radius_fonte.php?".$filtro_sql."Qtd=$filtro_limit&Ano=$filtro_ano&IdLoja=$local_IdLoja");
		}else{
			$file= file(getParametroSistema(6,3)."/modulos/administrativo/rotinas/log_radius_fonte.php?".$filtro_sql."Qtd=$filtro_limit&Ano=$filtro_ano&IdLoja=$local_IdLoja");
		}

		header ("content-type: text/xml");
		
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_log_radius_xsl.php$filtro_url\"?>";
		echo "<db>";
		
		for($i=0; $i < count($file); $i++){
			echo "<reg>";
			echo "	<retorno><![CDATA[".$file[$i]."]]></retorno>";
			echo "</reg>";
		}
		
		echo "</db>";
	}
?>
