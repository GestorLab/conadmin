<?
	$localModulo		=	1;
	$localOperacao		=	14;
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
	$filtro_idpais			= $_POST['filtro_idpais'];
	$filtro_estado			= $_POST['filtro_estado'];
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
		
	if($filtro_idpais!=''){
		$filtro_url .= "&IdPais=$filtro_idpais";
		$filtro_sql .=	" and Pais.IdPais = $filtro_idpais";
	}
		
	if($filtro_estado!=""){
		$filtro_url .= "&Estado=".$filtro_estado;
		$filtro_sql .= " and Estado.IdEstado=".$filtro_estado;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_estado_xsl.php$filtro_url\"?>";
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
			      		Estado.IdPais, 
						Pais.NomePais,
						Estado.IdEstado, 
						Estado.NomeEstado,
						Estado.SiglaEstado
					from 
						Pais,
						Estado
					where 
						Pais.IdPais = Estado.IdPais 
						$filtro_sql
						$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdPais>$lin[IdPais]</IdPais>";	
		echo 	"<Pais><![CDATA[$lin[NomePais]]]></Pais>";		
		echo 	"<IdEstado>$lin[IdEstado]</IdEstado>";	
		echo 	"<Estado><![CDATA[$lin[NomeEstado]]]></Estado>";
		echo 	"<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
