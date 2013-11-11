<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 	

	$local_IdLoja				= $_SESSION['IdLoja'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_pessoa				= $_POST['filtro_pessoa'];
	$filtro_descricao_servico	= $_POST['filtro_descricao_servico'];
	$filtro_data_inicio			= $_POST['filtro_data_inicio'];
	$filtro_data_termino		= $_POST['filtro_data_termino'];
	$filtro_valor				= $_POST['filtro_valor'];
	$filtro_IdPessoa			= $_GET['IdPessoa'];
	$filtro_IdContrato			= $_GET['IdContrato'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	if($filtro_IdPessoa == ''){
		$filtro_IdPessoa		= $_POST['IdPessoa'];
	}
	
	if($filtro_IdContrato == ''){
		$filtro_IdContrato		= $_POST['IdContrato'];
	}
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&Pessoa=".$filtro_pessoa;
		$filtro_sql .= " and (Pessoa.Nome like '%$filtro_pessoa%' or Pessoa.RazaoSocial like '%$filtro_pessoa%')";
	}
	
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_IdPessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_IdPessoa'";
	}
	
	$filtro_url .= "&IdContrato=".$filtro_IdContrato;
			
	if($filtro_descricao_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContratoVigencia.DataInicio >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_termino!=""){
		$filtro_url .= "&DataTermino=".$filtro_data_termino;
		$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContratoVigencia.DataTermino <= '$filtro_data_termino'";
	}
	
	if($filtro_valor!=''){
		$filtro_url .= "&Valor=".$filtro_valor;
		$filtro_valor	=	str_replace('.','',$filtro_valor);
		$filtro_valor	=	str_replace(',','.',$filtro_valor);
		$filtro_sql .= " and ContratoVigencia.Valor = $filtro_valor";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
		if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_vigencia_xsl.php$filtro_url\"?>";
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
	
	$contrato	=	"";
	$sqlTemp	=	"select IdContrato from ContratoAutomatico where IdLoja = $local_IdLoja and (IdContratoAutomatico = $filtro_IdContrato or IdContrato = $filtro_IdContrato) group by IdContrato";
	$resTemp	=	mysql_query($sqlTemp,$con);
	$linTemp	=	mysql_fetch_array($resTemp);
	if($linTemp[IdContrato] != ""){
		if($linTemp[IdContrato] == $filtro_IdContrato){
			$sql2	=	"select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $filtro_IdContrato";
		}else{
			$sql2	=	"select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $linTemp[IdContrato]";
		}
		$res2	=	mysql_query($sql2,$con);
		while($lin2	=	mysql_fetch_array($res2)){
			if($contrato != ""){
				 $contrato .= ",".$lin2[IdContratoAutomatico];
			}else{
				$contrato .= $linTemp[IdContrato].",".$lin2[IdContratoAutomatico];
			}		
		}
		$filtro_sql .= " and Contrato.IdContrato in ($contrato)";
	}else{
		$filtro_sql .= " and Contrato.IdContrato = '$filtro_IdContrato'";
	}
	
		
	$sql	=	"select
					ContratoVigencia.IdContrato,
					ContratoVigencia.DataInicio,
					ContratoVigencia.DataTermino,
					ContratoVigencia.Valor,
					ContratoVigencia.ValorDesconto,
					ContratoVigencia.LimiteDesconto,
					ContratoTipoVigencia.DescricaoContratoTipoVigencia,
					ContratoVigencia.IdTipoDesconto
				from
					Loja,
					Contrato,
					ContratoVigencia LEFT JOIN ContratoTipoVigencia ON (ContratoVigencia.IdLoja = ContratoTipoVigencia.IdLoja and ContratoVigencia.IdContratoTipoVigencia = ContratoTipoVigencia.IdContratoTipoVigencia),
					Servico
				where
					Loja.IdLoja = $local_IdLoja and
					ContratoVigencia.IdLoja = Loja.IdLoja and
					ContratoVigencia.IdLoja = Contrato.IdLoja and
					ContratoVigencia.IdLoja = Servico.IdLoja and
					Contrato.IdServico = Servico.IdServico and
					ContratoVigencia.IdContrato = Contrato.IdContrato $filtro_sql 
				order by
					ContratoVigencia.IdContrato ASC
					$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin[ValorFinal]		=	$lin[Valor]	-	$lin[ValorDesconto];
		$lin[ValorFinal]		=	formata_double($lin[ValorFinal]);
		$lin[ValorFinalTemp]	=	str_replace(".", ",",$lin[ValorFinal]);
		
		if($lin[Valor] != Null){
			$lin[ValorTemp] = str_replace(".", ",",$lin[Valor]);
		}else{
			$lin[Valor]	=	0;
		}
		
		$sqlTemp	=	"select IdContrato from ContratoAutomatico where IdLoja = $local_IdLoja and (IdContratoAutomatico = $filtro_IdContrato or IdContrato = $filtro_IdContrato) group by IdContrato";
		$resTemp	=	mysql_query($sqlTemp,$con);
		$linTemp	=	mysql_fetch_array($resTemp);
		
		if($linTemp[IdContrato]!=""){
			$lin[IdContratoFilho]	=	$lin[IdContrato];	
			$lin[IdContrato]		=	$linTemp[IdContrato];
		}else{
			$lin[IdContratoFilho]	=	$lin[IdContrato];
		}
		
		if($lin[ValorDesconto] != Null){
			$lin[ValorDescontoTemp] = str_replace(".", ",",$lin[ValorDesconto]);
		}else{
			$lin[ValorDesconto]	=	0;
		}
		
		$lin[DataInicioTemp] 			= dataConv($lin[DataInicio],"Y-m-d","d/m/Y");
		$lin[DataTerminoTemp] 			= dataConv($lin[DataTermino],"Y-m-d","d/m/Y");
		
		$lin[DataInicioBusca] 		= dataConv($lin[DataInicio],"Y-m-d","Ymd");
		$lin[DataTerminoBusca] 		= dataConv($lin[DataTermino],"Y-m-d","Ymd");
		
		if($lin[IdTipoDesconto] == '1'){
			$lin[LimiteDesconto] 			= dataConv($lin[LimiteDesconto],"Y-m-d","d/m/Y");
		}
		//Tipo de desconto
		if($lin[IdTipoDesconto] == 1){
			$lin[IdTipoDesconto] = "Concebido";
		}
		if($lin[IdTipoDesconto] == 2){
			$lin[IdTipoDesconto] = "À Conceber";
		}
		if($lin[IdTipoDesconto] == 3){
			$lin[IdTipoDesconto] = "Sem Desconto";
		}
		
		echo "<reg>";	
		echo 	"<IdContrato>$lin[IdContrato]</IdContrato>";	
		echo 	"<IdContratoFilho><![CDATA[$lin[IdContratoFilho]]]></IdContratoFilho>";
		echo 	"<DescricaoContratoTipoVigencia><![CDATA[$lin[DescricaoContratoTipoVigencia]]]></DescricaoContratoTipoVigencia>";
		
		echo 	"<DataInicioVigencia><![CDATA[$lin[DataInicio]]]></DataInicioVigencia>";
		echo 	"<DataInicioVigenciaBusca><![CDATA[$lin[DataInicioBusca]]]></DataInicioVigenciaBusca>";
		echo 	"<DataInicioVigenciaTemp><![CDATA[$lin[DataInicioTemp]]]></DataInicioVigenciaTemp>";
		
		echo 	"<DataTerminoVigencia><![CDATA[$lin[DataTermino]]]></DataTerminoVigencia>";
		echo 	"<DataTerminoVigenciaBusca><![CDATA[$lin[DataTerminoBusca]]]></DataTerminoVigenciaBusca>";
		echo 	"<DataTerminoVigenciaTemp><![CDATA[$lin[DataTerminoTemp]]]></DataTerminoVigenciaTemp>";
		
		echo 	"<IdTipoDesconto><![CDATA[$lin[IdTipoDesconto]]]></IdTipoDesconto>";
		echo 	"<LimiteDesconto><![CDATA[$lin[LimiteDesconto]]]></LimiteDesconto>";
		
		echo 	"<Valor>$lin[Valor]</Valor>";
		echo 	"<ValorTemp><![CDATA[$lin[ValorTemp]]]></ValorTemp>";
		
		echo 	"<ValorDesconto>$lin[ValorDesconto]</ValorDesconto>";
		echo 	"<ValorDescontoTemp><![CDATA[$lin[ValorDescontoTemp]]]></ValorDescontoTemp>";
		
		echo 	"<ValorFinal>$lin[ValorFinal]</ValorFinal>";
		echo 	"<ValorFinalTemp><![CDATA[$lin[ValorFinalTemp]]]></ValorFinalTemp>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
