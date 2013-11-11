<?php
	$localModulo		= 1;
	$localOperacao		= 192;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	# INCLUDE PADRÃO
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");
	include('../../../rotinas/verifica.php');
	include("../../../classes/jpgraph/src/jpgraph.php");
	include("../../../classes/jpgraph/src/jpgraph_bar.php");
	include("../../../classes/jpgraph/src/jpgraph_line.php");
	# VAR LOCAL
	$IdLoja			= $_SESSION["IdLoja"];
	$MesReferencia	= $_GET["MesReferencia"];
	$IdServico		= $_GET["IdServico"];
	$Where			= "";
	
	list($Mes, $Ano) = explode("/", $MesReferencia);
	
	$qtdDiasMes = ultimoDiaMes($Mes, $Ano);
	$MesReferenciaTemp = $Ano."-".$Mes;
	$QTDFinalizado = $QTDAberto = $Dia = array(0);
	# SET OS DIAS DO MÊS
	for($i = 0; $i < $qtdDiasMes; $i++){
		$Dia[$i] = ($i + 1);
		$QTDAberto[$i] = $QTDFinalizado[$i] = 0;
	}
	# MONTAR CONDIÇÕES DO FILTRO
	if($IdServico != ""){
		$Where .= " AND OrdemServico.IdServico = $IdServico ";
	}
	# BUSCAR AS OS. ABERTAS
	$sql = "
		SELECT 
			SUBSTRING(OrdemServico.DataCriacao, 9, 2) Dia,
			COUNT(OrdemServico.IdOrdemServico) QTDAberto 
		FROM
			OrdemServico 
		WHERE 
			OrdemServico.IdStatus > 99 AND 
			OrdemServico.IdStatus < 200 AND 
			OrdemServico.DataCriacao LIKE '$MesReferenciaTemp%'
			$Where
		GROUP BY 
			Dia
		ORDER BY 
			Dia;";
	$res = mysql_query($sql, $con);
	
	while($lin = mysql_fetch_array($res)) {
		$QTDAberto[(int) $lin["Dia"] - 1] = $lin["QTDAberto"];
	}
	# BUSCAR AS OS. CONCLUÍDAS
	$sql = "
		SELECT 
			SUBSTRING(OrdemServico.DataConclusao, 9, 2) Dia,
			COUNT(OrdemServico.IdOrdemServico) QTDFinalizado 
		FROM
			OrdemServico 
		WHERE 
			(
				(
					OrdemServico.IdStatus > 199 AND 
					OrdemServico.IdStatus < 300 
				) OR (
					OrdemServico.IdStatus > 399 AND 
					OrdemServico.IdStatus < 600 
				)
			) AND
			OrdemServico.DataConclusao LIKE '$MesReferenciaTemp%'
			$Where
		GROUP BY 
			Dia
		ORDER BY 
			Dia;";
	$res = mysql_query($sql, $con);
	
	while($lin = mysql_fetch_array($res)) {
		$QTDFinalizado[(int) $lin["Dia"] - 1] = $lin["QTDFinalizado"];
	}
	
	$QTDFinalizadoTotal = array_sum($QTDFinalizado);
	$QTDAbertoTotal = array_sum($QTDAberto);
	$QTDFinalizadoPorcento = number_format($QTDFinalizadoTotal * 100 / ($QTDAbertoTotal > 0 ? $QTDAbertoTotal : 1), 2, ",", "");
	$months = $gDateLocale->GetShortMonth();
	# CRIANDO A IMAGEM
	$graph = new Graph(820,400);
	$graph->SetScale("textlin");
	# AJUSTANDO A IMAGEM
	$graph->img->SetMargin(60,15,50,50);
	$graph->SetBox(false); 
	$graph->SetFrame(false);
	# SET O TITULO
	$graph->title->Set("Ordem de Serviço $MesReferencia");
	$graph->subtitle->Set("Novos x Conclusão");
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));
	# SET X E Y
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle('solid');
	$graph->xgrid->SetColor('#E3E3E3');
	# SET OS DIAS DO MÊS
	$graph->xaxis->SetTickLabels($Dia);
	$graph->xaxis->SetLabelAngle(90);
	# CRIAR GRÁFICO DE BARRA HORIZONTAL
	$bplot = new BarPlot($QTDAberto);
	$fcol='#57930F';
	$tcol='#64B81B';
	$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);
	$bplot->SetWeight(0);
	$graph->Add($bplot);
	# SET A COR DO MARCADOR
	$bplot->SetLegend("Abertos (".array_sum($QTDAberto).")");
	$bplot->SetWidth(0.6);
	$bplot->SetColor("#5B9E12");
	$bplot->SetFillColor("#72C229");
	# CRIAR GRÁFICO DE LINHA CHEIA
	$lplot = new LinePlot($QTDFinalizado);
	$lplot->SetFillColor('skyblue@0.5');
	$lplot->SetColor('navy@0.7');
	$lplot->SetLegend("Concluídos (".$QTDFinalizadoTotal.") ".$QTDFinalizadoPorcento."%");
	$lplot->SetBarCenter();
	# SET A COR DO MARCADOR
	$lplot->mark->SetType(MARK_SQUARE);
	$lplot->mark->SetColor('blue@0.5');
	$lplot->mark->SetFillColor('lightblue');
	$lplot->mark->SetSize(6);
	$graph->Add($lplot); 
	# SAÍDA 
	$graph->Stroke();
?>