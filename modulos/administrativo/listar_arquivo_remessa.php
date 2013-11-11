<?
	$localModulo		=	1;
	$localOperacao		=	64;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_local_cob			= $_POST['filtro_local_cob'];
	$filtro_status				= $_POST['filtro_status'];
	$filtro_data_inicio			= $_POST['filtro_data_inicio'];
	$filtro_data_termino		= $_POST['filtro_data_termino'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_IdArquivoRemessa	= $_GET['IdArquivoRemessa'];
	
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
		
	if($filtro_local_cob!=''){
		$filtro_url .= "&IdLocalCobranca=$filtro_local_cob";
		$filtro_sql .=	" and (LocalCobranca.IdLocalCobranca = '$filtro_local_cob')";
	}
		
	if($filtro_status!=""){
		$filtro_url .= "&IdStatus=".$filtro_status;
		$filtro_sql .= " and ArquivoRemessa.IdStatus = $filtro_status";
	}
	
	if($filtro_IdArquivoRemessa != ''){
		$filtro_sql .= " and ArquivoRemessa.IdArquivoRemessa = $filtro_IdArquivoRemessa";
	}
	
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and DataRemessa >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_termino!=""){
		$filtro_url .= "&DataTermino=".$filtro_data_termino;
		$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
		$filtro_sql .= " and DataRemessa <= '$filtro_data_termino'";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_arquivo_remessa_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	$sql	=	"select
			      ArquivoRemessa.IdLoja,
			      ArquivoRemessa.IdArquivoRemessa,
			      ArquivoRemessa.IdLocalCobranca,
			      substr(LocalCobranca.AbreviacaoNomeLocalCobranca,1,30) AbreviacaoNomeLocalCobranca,
			      ArquivoRemessa.QtdRegistro,
			      ArquivoRemessa.ValorTotal,
			      ArquivoRemessa.IdStatus,
			      ArquivoRemessa.DataRemessa,
			      ArquivoRemessa.NomeArquivo,
			      ArquivoRemessa.DataCriacao
				from
				  Loja,
			      ArquivoRemessa,
			      LocalCobranca
			    where
			      ArquivoRemessa.IdLoja = $local_IdLoja and
			      ArquivoRemessa.IdLoja = Loja.IdLoja and
			      ArquivoRemessa.IdLoja = LocalCobranca.IdLoja and
			      ArquivoRemessa.IdLocalCobranca = LocalCobranca.IdLocalCobranca
			      $filtro_sql
				order by
				  ArquivoRemessa.IdArquivoRemessa desc
				  $Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=80 and IdParametroSistema=$lin[IdStatus]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$lin[DataRemessaTemp]	= dataConv($lin[DataRemessa],'Y-m-d','d/m/Y');
		$lin[DataCadastroTemp] 	= dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
		
		$lin[DataRemessa] 		= dataConv($lin[DataRemessa],"Y-m-d","Ymd");
		$lin[DataCadastro] 		= dataConv($lin[DataCriacao],"Y-m-d","Ymd");
		
		if($lin[ValorTotal] == '')		$lin[ValorTotal]  = 0; 
		if($lin[QtdRegistro] == '')		$lin[QtdRegistro] = 0; 
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";
		echo 	"<IdArquivoRemessa>$lin[IdArquivoRemessa]</IdArquivoRemessa>";	
		echo 	"<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
		echo 	"<QtdRegistro><![CDATA[$lin[QtdRegistro]]]></QtdRegistro>";
		echo 	"<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";	
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";	
		echo 	"<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";	
		echo 	"<NomeArquivo><![CDATA[$lin[NomeArquivo]]]></NomeArquivo>";	
		echo 	"<DataRemessa><![CDATA[$lin[DataRemessa]]]></DataRemessa>";
		echo 	"<DataRemessaTemp><![CDATA[$lin[DataRemessaTemp]]]></DataRemessaTemp>";
		echo 	"<DataCadastro><![CDATA[$lin[DataCadastro]]]></DataCadastro>";
		echo 	"<DataCadastroTemp><![CDATA[$lin[DataCadastroTemp]]]></DataCadastroTemp>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
