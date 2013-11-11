<?
	$localModulo		=	1;
	$localOperacao		=	63;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$filtro									= $_POST['filtro'];
	$filtro_ordem							= $_POST['filtro_ordem'];
	$filtro_ordem_direcao					= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado					= $_POST['filtro_tipoDado'];
	$filtro_descricao_parametro_contrato	= url_string_xsl($_POST['filtro_descricao_parametro_contrato'],"URL",false);
	$filtro_valor_default					= url_string_xsl($_POST['filtro_valor_default'],"URL",false);
	$filtro_idstatus						= $_POST['filtro_idstatus'];
	$filtro_limit							= $_POST['filtro_limit'];
	$filtro_obrigatorio						= $_POST['filtro_obrigatorio'];
	
	if($_GET['IdLocalCobranca'] != ''){
		$filtro_local_cobranca					= $_GET['IdLocalCobranca'];
	}
	if($_POST['IdLocalCobranca'] != ''){
		$filtro_local_cobranca					= $_POST['IdLocalCobranca'];
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro_local_cobranca == ''){
		$filtro_sql .=	" and LocalCobranca.IdLocalCobranca = '0'";
	}
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_local_cobranca!=''){
		$filtro_url .= "&IdLocalCobranca=$filtro_local_cobranca";
		$filtro_sql .=	" and LocalCobranca.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_obrigatorio!=''){
		$filtro_url .= "&Obrigatorio=$filtro_obrigatorio";
		$filtro_sql .=	" and LocalCobrancaParametroContrato.Obrigatorio = '$filtro_obrigatorio'";
	}
		
	if($filtro_descricao_parametro_contrato!=''){
		$filtro_url .= "&DescricaoParametroContrato=$filtro_descricao_parametro_contrato";
		$filtro_sql .=	" and LocalCobrancaParametroContrato.DescricaoParametroContrato like '%$filtro_descricao_parametro_contrato%'";
	}
		
	if($filtro_valor_default!=''){
		$filtro_url .= "&ValorDefault=".$filtro_valor_default;
		$filtro_sql .= " and LocalCobrancaParametroContrato.ValorDefault like '%$filtro_valor_default%'";
	}
	
	if($filtro_idstatus!=''){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and LocalCobrancaParametroContrato.IdStatus = $filtro_idstatus";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_local_cobranca_parametro_contrato_xsl.php$filtro_url\"?>";
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
					LocalCobranca.IdLoja,
				    LocalCobranca.IdLocalCobranca,
					LocalCobrancaParametroContrato.IdLocalCobrancaParametroContrato,
				    LocalCobrancaParametroContrato.DescricaoParametroContrato,
					LocalCobrancaParametroContrato.ValorDefault,
					LocalCobrancaParametroContrato.Obrigatorio,
					LocalCobrancaParametroContrato.Editavel,
					LocalCobrancaParametroContrato.Calculavel,
					LocalCobrancaParametroContrato.IdStatus
				from
					LocalCobranca,
					LocalCobrancaParametroContrato
				where
				    LocalCobranca.IdLoja = $local_IdLoja and
				    LocalCobranca.IdLoja = LocalCobrancaParametroContrato.IdLoja and
				    LocalCobranca.IdLocalCobranca = LocalCobrancaParametroContrato.IdLocalCobranca
					$filtro_sql
				order by
					LocalCobranca.IdLocalCobranca desc
				$Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$sql2 = "select ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno=5 and IdCodigoInterno = $lin[Obrigatorio]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=19 and IdParametroSistema=$lin[Editavel]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=79 and IdParametroSistema=$lin[IdStatus]";
		$res4 = @mysql_query($sql4,$con);
		$lin4 = @mysql_fetch_array($res4);
		
		$sql5 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=45 and IdParametroSistema=$lin[Calculavel]";
		$res5 = @mysql_query($sql5,$con);
		$lin5 = @mysql_fetch_array($res5);
		
		if($lin[Valor] != ""){
			$lin[ValorTemp] = str_replace(".", ",", $lin[Valor]);
		}else{
			$lin[Valor] = 0;
		}
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<IdLocalCobranca>$lin[IdLocalCobranca]</IdLocalCobranca>";	
		echo 	"<IdLocalCobrancaParametroContrato>$lin[IdLocalCobrancaParametroContrato]</IdLocalCobrancaParametroContrato>";
		echo 	"<DescricaoParametroContrato><![CDATA[$lin[DescricaoParametroContrato]]]></DescricaoParametroContrato>";
		echo 	"<Obrigatorio><![CDATA[$lin2[ValorCodigoInterno]]]></Obrigatorio>";
		echo 	"<ValorDefault><![CDATA[$lin[ValorDefault]]]></ValorDefault>";
		echo 	"<Editavel><![CDATA[$lin3[ValorParametroSistema]]]></Editavel>";
		echo 	"<Calculavel><![CDATA[$lin5[ValorParametroSistema]]]></Calculavel>";
		echo 	"<Status><![CDATA[$lin4[ValorParametroSistema]]]></Status>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
