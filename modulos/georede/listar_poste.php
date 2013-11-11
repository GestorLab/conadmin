<?
	$localModulo		=	1;
	$localOperacao		=	205;
	$localSuboperacao	=	"R";
	
	$localTituloOperacao	= "poste";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja			= $_SESSION["IdLoja"];
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_nome_poste		= $_POST['filtro_nome_poste'];
	$fitro_Tipo_Poste		= $_POST['fitro_Tipo_Poste'];
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
		
	if($filtro_nome_poste != ""){
		$filtro_url .= "&filtro_nome_poste=$filtro_nome_poste";
		$filtro_sql .= "AND NomePoste LIKE '%$filtro_nome_poste%'";
	}
	
	if($fitro_Tipo_Poste != ""){
		$filtro_url .= "&fitro_Tipo_Poste=$fitro_Tipo_Poste";
		$filtro_sql .= "AND IdPosteTipo LIKE '%$fitro_Tipo_Poste%'";
	}		
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_poste_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"SELECT 
					IdPoste,
					NomePoste,
					DescricaoPoste,
					DescricaoPosteTipo,
					IdPais,
					IdEstado,
					IdCidade,
					Endereco,
					Numero,
					Complemento,
					Cep,
					Latitude,
					Longitude 
				FROM
					Poste,
					PosteTipo 
				WHERE 
					Poste.IdLoja = $local_IdLoja
					AND PosteTipo.IdPosteTipo = Poste.IdTipoPoste 
					$filtro_sql
				order by
					IdPoste DESC
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdPoste>$lin[IdPoste]</IdPoste>";	
		echo 	"<NomePoste><![CDATA[$lin[NomePoste]]]></NomePoste>";		
		echo 	"<DescricaoPoste><![CDATA[$lin[DescricaoPoste]]]></DescricaoPoste>";		
		echo 	"<DescricaoPosteTipo><![CDATA[$lin[DescricaoPosteTipo]]]></DescricaoPosteTipo>";		
		echo 	"<Latitude><![CDATA[$lin[Latitude]]]></Latitude>";		
		echo 	"<Longitude><![CDATA[$lin[Longitude]]]></Longitude>";		
		echo "</reg>";	
	}
	
	echo "</db>";
?>
