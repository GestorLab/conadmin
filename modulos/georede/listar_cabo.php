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
	$filtro_especificacao	= url_string_xsl($_POST['filtro_especificacao'], "URL", false);
	$fitro_Tipo_Cabo		= $_POST['fitro_Tipo_Cabo'];
	$filtro_nomeCabo		= $_POST['filtro_nomeCabo'];
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
		
	if($filtro_especificacao != ""){
		$filtro_url .= "&filtro_especificacao=$filtro_especificacao";
		$filtro_sql .= "AND Especificacao LIKE '%$filtro_especificacao%'";
	}
	
	if($fitro_Tipo_Cabo != ""){
		$filtro_url .= "&fitro_Tipo_Cabo=$fitro_Tipo_Cabo";
		$filtro_sql .= "AND IdTipoCabo = $fitro_Tipo_Cabo";
	}	
	
	if($filtro_nomeCabo != ""){
		$filtro_url .= "&filtro_nomeCabo=$filtro_nomeCabo";
		$filtro_sql .= "AND NomeCabo LIKE '%$filtro_nomeCabo%'";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_cabo_xsl.php$filtro_url\"?>";
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
					Cabo.IdCabo,
					Cabo.NomeCabo,
					Cabo.IdTipoCabo,
					Cabo.Especificacao,
					Cabo.QtdFibra,
					Cabo.DataInstalacao, 
					CaboTipo.DescricaoCaboTipo
				FROM
					Cabo,
					CaboTipo 
				WHERE Cabo.IdLoja = $local_IdLoja 
					AND CaboTipo.IdLoja = Cabo.IdLoja 
					AND CaboTipo.IdCaboTipo = Cabo.IdTipoCabo
					$filtro_sql
				order by
					IdCabo desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdCabo>$lin[IdCabo]</IdCabo>";	
		echo 	"<NomeCabo>$lin[NomeCabo]</NomeCabo>";	
		echo 	"<DescricaoCaboTipo><![CDATA[$lin[DescricaoCaboTipo]]]></DescricaoCaboTipo>";		
		echo 	"<Especificacao><![CDATA[$lin[Especificacao]]]></Especificacao>";		
		echo 	"<QtdFibra><![CDATA[$lin[QtdFibra]]]></QtdFibra>";		
		echo "</reg>";	
	}
	
	echo "</db>";
?>
