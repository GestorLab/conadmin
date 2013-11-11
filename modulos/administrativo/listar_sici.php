<?
	$localModulo		= 1;
	$localOperacao		= 159;
	$localSuboperacao	= "R";
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado				= $_POST['filtro_tipoDado'];
	$filtro_periodo_apuracao		= $_POST['filtro_periodo_apuracao'];
	$filtro_numero_fistel			= $_POST['filtro_numero_fistel'];
	$filtro_id_status				= $_POST['filtro_id_status'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	if($filtro_protocolo_tipo == ''){
		$filtro_protocolo_tipo = $_GET['IdProtocoloTipo'];
	}
	
	LimitVisualizacao("listar");
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipo_dado != "")
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_periodo_apuracao != "") {
		$filtro_url .= "&PeriodoApuracao=".$filtro_periodo_apuracao;
		$filtro_periodo_apuracao = dataConv($filtro_periodo_apuracao,"m/Y","Y-m");
		$filtro_sql .= " AND SICI.PeriodoApuracao = '$filtro_periodo_apuracao'";
	}
	
	if($filtro_numero_fistel != "") {
		$filtro_url .= "&NumeroFistel=".$filtro_numero_fistel;
		$filtro_sql .= " AND SICI.Fistel = $filtro_numero_fistel";
	}
	
	if($filtro_id_status != "") {
		$filtro_url .= "&IdStatus=".$filtro_id_status;
		$filtro_sql .= " AND SICI.IdStatus = $filtro_id_status";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_sici_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s") {
		if($filtro_limit != "") {
			$Limit	= " limit $filtro_limit";
		}
	} else {
		if($filtro_limit == "") {
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		} else {
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "SELECT
				SICI.PeriodoApuracao,
				SICI.IAU1,
				SICI.IPL1TotalKMCaboPrestadora,
				SICI.IPL1TotalKMCaboTerceiro,
				SICI.IPL1CrescimentoPrevistoKMCaboPrestadora,
				SICI.IPL1CrescimentoPrevistoKMCaboTerceiro,
				SICI.IPL2TotalKMFibraPrestadora,
				SICI.IPL2TotalKMFibraTerceiro,
				SICI.IPL2CrescimentoPrevistoKMFibraPrestadora,
				SICI.IPL2CrescimentoPrevistoKMFibraTerceiro,
				SICI.IEM1Indicador,
				SICI.IEM1ValorTotalAplicadoEquipamento,
				SICI.IEM1ValorTotalAplicadoPesquisaDesenvolvimento,
				SICI.IEM1ValorTotalAplicadoMarketing,
				SICI.IEM1ValorTotalAplicadoSoftware,
				SICI.IEM1ValorTotalAplicadoServico,
				SICI.IEM1ValorTotalAplicadoCentralAtendimento,
				SICI.IEM3,
				SICI.IEM2ValorFaturamentoServico,
				SICI.IEM2ValorFaturamentoIndustrizalizacaoServico,
				SICI.IEM2ValorFaturamentoServicoAdicional,
				SICI.IEM8ValorTotalCustos,
				SICI.IEM8ValorDespesaPublicidade,
				SICI.IEM8ValorDespesaInterconexao,
				SICI.IEM8ValorDespesaOperacaoManutencao,
				SICI.IEM8ValorDespesaVenda,
				SICI.Fistel,
				SICI.IdStatus,
				SICI.LoginCriacao,
				SICI.DataCriacao,
				SICI.LoginConfirmacao,
				SICI.DataConfirmacao
			FROM
				SICI
			WHERE
				1
				$filtro_sql
			ORDER BY
				SICI.PeriodoApuracao
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[PeriodoApuracao]		= dataConv($lin[PeriodoApuracao],"Y-m","Ym");
		$lin[PeriodoApuracaoTemp]	= dataConv($lin[PeriodoApuracao],"Ym","m/Y");
		
		$lin[DataCriacao]		= dataConv($lin[DataCriacao],"Y-m-d H:i:s","YmdHis");
		$lin[DataCriacaoTemp]	= dataConv($lin[DataCriacao],"YmdHis","d/m/Y H:i:s");
		
		$lin[Status] = getParametroSistema(240, $lin[IdStatus]);
		
		echo "<reg>";
		echo 	"<PeriodoApuracao>$lin[PeriodoApuracao]</PeriodoApuracao>";
		echo 	"<PeriodoApuracaoTemp>$lin[PeriodoApuracaoTemp]</PeriodoApuracaoTemp>";
		echo 	"<Fistel><![CDATA[$lin[Fistel]]]></Fistel>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo 	"<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		echo 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo 	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
		echo 	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>