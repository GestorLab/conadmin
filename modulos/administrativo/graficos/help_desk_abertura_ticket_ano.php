<?php
	$localModulo		=	1;
	$localOperacao		=	148;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include ('../../../rotinas/verifica.php');	
	include ("../../../classes/jpgraph/src/jpgraph.php");
	include ("../../../classes/jpgraph/src/jpgraph_bar.php");
	include ("../../../classes/jpgraph/src/jpgraph_line.php");
	
	$ano = $_GET['ano'];
	
	for($i = 0; $i <12; $i++){
		$QtdMes[$i]				= 0;
		$QtdMesFinalizada[$i]	= 0;
		$Mes[$i]				= substr(mes($i+1),0,3);
	}
		
	$sql = "select
					MesReferencia,
					count(*) Qtd
				from
					(select
					substring(DataCriacao,6,2) MesReferencia
				from
					HelpDesk
				where
					DataCriacao like '$ano%') MesReferencia
				group by
					MesReferencia";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$QtdMes[$lin[MesReferencia]-1] = $lin[Qtd];
	}

	$sql = "select
				DataConclusao.DataConclusao,
				sum(Qtd) Qtd
			from
				(select
					substring(TicketConcluido.DataConclusao,6,2) DataConclusao,
					count(*) Qtd
				from
					(select 
						IdTicket,
						min(DataConclusao) DataConclusao 
					from
						(select 
							HelpDeskHistorico.IdTicket,
							max(HelpDeskHistorico.DataCriacao) DataConclusao,
							HelpDeskHistorico.IdStatusTicket 
						from
							HelpDeskHistorico,
							HelpDesk 
						where 
							HelpDeskHistorico.IdStatusTicket = 400 and 
							HelpDeskHistorico.IdTicket = HelpDesk.IdTicket and 
							HelpDesk.IdStatus in (400, 600) and 
							HelpDeskHistorico.DataCriacao like '$ano%' 
						group by HelpDeskHistorico.IdTicket 
						union
						select 
							HelpDeskHistorico.IdTicket,
							max(HelpDeskHistorico.DataCriacao) DataConclusao,
							HelpDeskHistorico.IdStatusTicket 
						from
							HelpDeskHistorico,
							HelpDesk 
						where 
							HelpDeskHistorico.IdStatusTicket = 600 and 
							HelpDeskHistorico.IdTicket = HelpDesk.IdTicket and 
							HelpDesk.IdStatus in (400, 600) and 
							HelpDeskHistorico.DataCriacao like '$ano%' 
						group by HelpDeskHistorico.IdTicket) Temp 
					group by IdTicket) TicketConcluido
				group by
					TicketConcluido.IdTicket
				order by
					TicketConcluido.DataConclusao) DataConclusao
			group by
				DataConclusao.DataConclusao;";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$QtdMesFinalizada[$lin[DataConclusao]-1] = $lin[Qtd];
	}

	// Get a list of month using the current locale	
	$months = $gDateLocale->GetShortMonth();

	// Create the graph. 
	$graph = new Graph(820,400);
	$graph->SetScale("textlin");
	
	// Adjust the margin slightly so that we use the 
	// entire area (since we don't use a frame)
	$graph->img->SetMargin(60,15,50,50);

	// Box around plotarea
	$graph->SetBox(false); 

	// No frame around the image
	$graph->SetFrame(false);
	
	// Setup the tab title
	$graph->title->Set("Tickets $ano");	
	$graph->subtitle->Set("Novos x Conclusão");
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));

	// Setup the X and Y grid
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle('solid');
	$graph->xgrid->SetColor('#E3E3E3');
	
	// Setup month as labels on the X-axis
	$graph->xaxis->SetTickLabels($Mes);
	$graph->xaxis->SetLabelAngle(90);

	// Create a bar pot
	$bplot = new BarPlot($QtdMes);
	$fcol='#57930F';
	$tcol='#64B81B';

	$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);

	// Set line weigth to 0 so that there are no border
	// around each bar
	$bplot->SetWeight(0);	
	$graph->Add($bplot);
	
	$bplot->SetLegend("Novos Tickets ".array_sum($QtdMes));
	$bplot->SetWidth(0.6);
	$bplot->SetColor("#5B9E12");
	$bplot->SetFillColor("#72C229");
	
	// Create filled line plot
	$lplot = new LinePlot($QtdMesFinalizada);
	$lplot->SetFillColor('skyblue@0.5');
	$lplot->SetColor('navy@0.7');
	$lplot->SetLegend("Concluídos ".array_sum($QtdMesFinalizada));
	$lplot->SetBarCenter();

	$lplot->mark->SetType(MARK_SQUARE);
	$lplot->mark->SetColor('blue@0.5');
	$lplot->mark->SetFillColor('lightblue');
	$lplot->mark->SetSize(6);

	$graph->Add($lplot); 
	// .. and finally send it back to the browser
	$graph->Stroke(); 
?>