<?
	$localModulo		=	1;
	$localOperacao		=	20;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_centro_custo	= url_string_xsl($_POST['filtro_centro_custo'],"url",false);
	$filtro_idstatus		= $_POST['filtro_idstatus'];
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
		
	if($filtro_centro_custo!=''){
		$filtro_url .= "&CentroCusto=$filtro_centro_custo";
		$filtro_sql .=	" and CentroCusto.DescricaoCentroCusto like '%$filtro_centro_custo%'";
	}
		
	if($filtro_idstatus!=""){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and CentroCusto.IdStatus = $filtro_idstatus";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

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
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_centro_custo_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	$sql = "select
				IdLoja,
				IdCentroCusto,
				DescricaoCentroCusto,
				IdStatus
			from
				CentroCusto
			where
				IdLoja = $local_IdLoja
				$filtro_sql
			order by
				IdCentroCusto desc
			$Limit";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=32 and IdParametroSistema=$lin[IdStatus]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";
		echo 	"<IdCentroCusto>$lin[IdCentroCusto]</IdCentroCusto>";	
		echo 	"<DescricaoCentroCusto><![CDATA[$lin[DescricaoCentroCusto]]]></DescricaoCentroCusto>";
		echo 	"<IdStatus><![CDATA[$lin2[ValorParametroSistema]]]></IdStatus>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
