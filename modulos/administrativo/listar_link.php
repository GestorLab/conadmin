<?
	$localModulo		=	1;
	$localOperacao		=	37;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_descricao		= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_link			= $_POST['IdLink'];
	$filtro_limit			= $_POST['filtro_limit'];
	
	if($filtro_link == ''&& $_GET['IdLink']!=''){
		$filtro_link		= $_GET['IdLink'];
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
		$filtro_url .= "&DescricaoLink=".$filtro_descricao;
		$filtro_sql .= " and (DescricaoLink like '%$filtro_descricao%')";
	}
	if($filtro_link!=""){
		$filtro_url	.= "&IdLink=".$filtro_link;
		$filtro_sql	.= " and IdLink=".$filtro_link;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	if($filtro_sql != "")
		$filtro_sql = "where IdLink!=''".$filtro_sql;

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_link_xsl.php$filtro_url\"?>";
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
			      		IdLink, 
						DescricaoLink,
						Link
					from 
						Link
						$filtro_sql
					order by
						IdLink desc
						$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdLink>$lin[IdLink]</IdLink>";	
		echo 	"<DescricaoLink><![CDATA[$lin[DescricaoLink]]]></DescricaoLink>";
		echo 	"<Link><![CDATA[$lin[Link]]]></Link>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
