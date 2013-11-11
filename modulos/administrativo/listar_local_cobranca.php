<?
	$localModulo		=	1;
	$localOperacao		=	30;
	$localSuboperacao	=	"R";
		
	$localTituloOperacao	= "Local de Cobrança";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja				= $_SESSION["IdLoja"]; 
		
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_descricao		= url_string_xsl($_POST['filtro_descricao'],"URL",false);
	$filtro_abreviacao		= url_string_xsl($_POST['filtro_abreviacao'],"URL",false);
	$filtro_tipoDado		= $_POST['filtro_tipoDado'];
	$filtro_tipo			= $_POST['filtro_tipo'];
	$filtro_local_cobranca	= $_GET['IdLocalCobranca'];
	$filtro_limit			= $_POST['filtro_limit'];
	$filtro_status			= $_POST['filtro_status'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_tipoDado";
		
	if($filtro_descricao!=""){
		$filtro_url .= "&DescricaoLocalCobranca=".$filtro_descricao;
		$filtro_sql .= " and (DescricaoLocalCobranca like '%$filtro_descricao%')";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and (IdLocalCobranca = '$filtro_local_cobranca')";
	}
	
	if($filtro_tipo!=""){
		$filtro_url .= "&IdTipoLocalCobranca=".$filtro_tipo;
		$filtro_sql .= " and (IdTipoLocalCobranca = '$filtro_tipo')";
	}
	
	if($filtro_abreviacao!=""){
		$filtro_url .= "&AbreviacaoNomeLocalCobranca=".$filtro_abreviacao;
		$filtro_sql .= " and (AbreviacaoNomeLocalCobranca like '%$filtro_abreviacao%')";
	}
	
	if($filtro_status!=""){
		$filtro_url .= "&IdStatus=".$filtro_status;
		$filtro_sql .= " and LocalCobranca.IdStatus = $filtro_status";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_local_cobranca_xsl.php$filtro_url\"?>";
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
					IdLocalCobranca, 
					IdTipoLocalCobranca,
					substr(DescricaoLocalCobranca,1,60) DescricaoLocalCobranca,
					AbreviacaoNomeLocalCobranca,
					ValorDespesaLocalCobranca,
					substr(ArquivoRetornoTipo.DescricaoArquivoRetornoTipo,1,30) DescricaoArquivoRetornoTipo,
					substr(ArquivoRemessaTipo.DescricaoArquivoRemessaTipo,1,30) DescricaoArquivoRemessaTipo,
					LocalCobranca.IdStatus,
					ParametroSistema.ValorParametroSistema as Status
				from 
					LocalCobranca LEFT JOIN ArquivoRetornoTipo ON (
						LocalCobranca.IdArquivoRetornoTipo = ArquivoRetornoTipo.IdArquivoRetornoTipo
					) LEFT JOIN ArquivoRemessaTipo ON (
						LocalCobranca.IdArquivoRemessaTipo = ArquivoRemessaTipo.IdArquivoRemessaTipo
					),
					ParametroSistema
				where
					LocalCobranca.IdLoja = $local_IdLoja and
					ParametroSistema.IdGrupoParametroSistema = 170 and
					ParametroSistema.IdParametroSistema = LocalCobranca.IdStatus 
					$filtro_sql
				order by
					IdLocalCobranca desc
					$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=78 and IdParametroSistema=$lin[IdTipoLocalCobranca]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		echo "<reg>";
		echo 	"<IdLocalCobranca>$lin[IdLocalCobranca]</IdLocalCobranca>";	
		echo 	"<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
		echo 	"<TipoLocalCobranca><![CDATA[$lin2[ValorParametroSistema]]]></TipoLocalCobranca>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
		echo 	"<ValorDespesaLocalCobranca><![CDATA[$lin[ValorDespesaLocalCobranca]]]></ValorDespesaLocalCobranca>";
		echo 	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo 	"<DescricaoArquivoRetornoTipo><![CDATA[$lin[DescricaoArquivoRetornoTipo]]]></DescricaoArquivoRetornoTipo>";		
		echo 	"<DescricaoArquivoRemessaTipo><![CDATA[$lin[DescricaoArquivoRemessaTipo]]]></DescricaoArquivoRemessaTipo>";		
		echo "</reg>";	
	}
	
	echo "</db>";
?>
