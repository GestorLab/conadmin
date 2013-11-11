<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$IdLoja					= $_SESSION['IdLoja'];
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_IdTipoEmail		= $_POST['IdTipoEmail'];
	$filtro_assunto			= url_string_xsl($_POST['filtro_assunto'],'url',false);
	$filtro_descricao		= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_limit			= $_POST['filtro_limit'];
	
	if($filtro_IdTipoEmail == ''&& $_GET['IdTipoEmail']!=''){
		$filtro_IdTipoEmail	= $_GET['IdTipoEmail'];
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
		
	if($filtro_descricao!=""){
		$filtro_url .= "&DescricaoTipoEmail=".$filtro_descricao;
		$filtro_sql .= " and (DescricaoTipoEmail like '%$filtro_descricao%')";
	}
	if($filtro_assunto!=""){
		$filtro_url .= "&AssuntoEmail=".$filtro_assunto;
		$filtro_sql .= " and (AssuntoEmail like '%$filtro_assunto%')";
	}
	if($filtro_IdTipoEmail!=""){
		$filtro_url	.= "&IdTipoEmail=".$filtro_IdTipoEmail;
		$filtro_sql	.= " and IdTipoEmail =".$filtro_IdTipoEmail;
	}	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_tipo_email_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select
						IdTipoEmail, 
						DescricaoTipoEmail, 
						DiasParaEnvio,
						AssuntoEmail
					from 
						TipoEmail
					where
						IdLoja = $IdLoja 
						$filtro_sql 
					order by 
						IdTipoEmail desc
					$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdTipoEmail>$lin[IdTipoEmail]</IdTipoEmail>";
		echo 	"<DescricaoTipoEmail><![CDATA[$lin[DescricaoTipoEmail]]]></DescricaoTipoEmail>";
		echo	"<DiasParaEnvio><![CDATA[$lin[DiasParaEnvio]]]></DiasParaEnvio>";
		echo 	"<AssuntoEmail><![CDATA[$lin[AssuntoEmail]]]></AssuntoEmail>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
