<?
	$localModulo		=	1;
	$localOperacao		=	13;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$sql	=	"SHOW TABLE STATUS WHERE Name='Cidade'";
	$res	=	@mysql_query($sql,$con);
	$lin	=	@mysql_fetch_array($res);
	
	if($lin[Comment] == 'VIEW'){
		header("Location: sem_permissao.php");
	}
		
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_pais			= url_string_xsl($_POST['filtro_pais'],'url',false);
	$filtro_limit			= $_POST['filtro_limit'];
	
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
			
	if($filtro_pais!=""){
		$filtro_url .= "&Pais=".$filtro_pais;
		$filtro_sql .= " and (NomePais like '%$filtro_pais%')";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	if($filtro_sql != "")
		$filtro_sql = "where IdPais!=''".$filtro_sql;

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_pais_xsl.php$filtro_url\"?>";
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
			      		IdPais, 
						Pais.NomePais
					from 
						Pais
						$filtro_sql
						$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdPais>$lin[IdPais]</IdPais>";	
		echo 	"<Pais><![CDATA[$lin[NomePais]]]></Pais>";		
		echo "</reg>";	
	}
	
	echo "</db>";
?>
