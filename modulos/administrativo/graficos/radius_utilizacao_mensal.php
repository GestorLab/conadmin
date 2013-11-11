<?php

	$localModulo		=	1;
	$localOperacao		=	126;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;

	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include ('../../../rotinas/verifica.php');
	include ("../../../classes/jpgraph/src/jpgraph.php");
	include ("../../../classes/jpgraph/src/jpgraph_bar.php");
	include ("../../../classes/jpgraph/src/jpgraph_line.php");
		
	
	$IdLoja		 			= $_SESSION["IdLoja"];
	$IdPessoaLogin			= $_SESSION['IdPessoa'];	
	$ano 					= $_GET['ano'];	
	$vencimento				= $_GET['vencimento'];
	$sqlAux					= "";
	
/*	if($ano != ''){
		$DataInicial	= "$ano-01-01";
		$DataFinal		= "$ano-12-31";
	}

	if($vencimento == 2){
		// Atual
		$where	= "ContaReceber.DataVencimento >= '$DataInicial' and ContaReceber.DataVencimento <= '$DataFinal' and ";
		$select = "substring(ContaReceber.DataVencimento,6,2) MesVencimento,";
	}else{
		// Original
		$where = "ContaReceberVencimento.DataVencimento >= '$DataInicial' and ContaReceberVencimento.DataVencimento <= '$DataFinal' and";
		$select = "substring(min(ContaReceberVencimento.DataVencimento),6,2) MesVencimento,";
	}

	$sql	=	"select
				    MesVendimentoReceber.MesVencimento,
				    MesVendimentoReceber.ValorReceber,
				    if(MesVencimentoRecebido.ValorRecebido is null,0,MesVencimentoRecebido.ValorRecebido) ValorRecebido,
				    ((MesVendimentoReceber.ValorReceber - if(MesVencimentoRecebido.ValorRecebido is null,0,MesVencimentoRecebido.ValorRecebido)) * 100 / MesVendimentoReceber.ValorReceber) TaxaInadimplencia
				from
				    (select
				        MesVencimentoReceber.MesVencimento,
				        sum(MesVencimentoReceber.ValorContaReceber) ValorReceber
				    from
				        (select
							ContaReceberVencimento.IdLoja,
							ContaReceberVencimento.IdContaReceber,
							$select							
							ContaReceberVencimento.ValorContaReceber
						from
							ContaReceberVencimento,
							ContaReceber
						where
							ContaReceberVencimento.IdLoja = $IdLoja and
							ContaReceber.IdLoja = ContaReceberVencimento.IdLoja and
							ContaReceber.IdContaReceber = ContaReceberVencimento.IdContaReceber and
							$where
							ContaReceberVencimento.DataVencimento < curdate()
						group by    
							ContaReceberVencimento.IdContaReceber) MesVencimentoReceber
				    group by
				        MesVencimento) MesVendimentoReceber left join 
				    (select
				        MesVencimento,
				        sum(ValorRecebido) ValorRecebido
				    from
				        (select
				            IdLoja,
				            IdContaReceber,
				            substring(min(DataVencimento),6,2) MesVencimento
				        from
				            ContaReceberVencimento
				        where
				            IdLoja = $IdLoja and
				            DataVencimento >= '$DataInicial' and
				            DataVencimento <= '$DataFinal' and 
				            DataVencimento < curdate()
				        group by
				            IdContaReceber) ContaReceber,
				        ContaReceberRecebimento
				    where
				        ContaReceber.IdLoja = ContaReceberRecebimento.IdLoja and
				        ContaReceber.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
				        ContaReceberRecebimento.IdStatus != 0
				    group by
				        MesVencimento) MesVencimentoRecebido on (MesVendimentoReceber.MesVencimento = MesVencimentoRecebido.MesVencimento)
				order by
				    MesVencimento";
							
	$res = mysql_query($sql,$con);
	$i = 0;
	while($lin = mysql_fetch_array($res)){
		if($lin[TaxaInadimplencia] > 0){
			$Inadimplencia[$i] 	= $lin[TaxaInadimplencia];
		}else{
			$Inadimplencia[$i] 	= 0;
		}
		$Mes[$i]			= substr(mes($lin[MesVencimento]),0,3);
		$i++;
	}
	
	if($i == 0){
		$Inadimplencia[$i] 	= 0;
		$Mes[$i]			= 0;
	}
	
	$Inadimplencia = array(5,8,10);
	$Inadimplencia2 = array(1,2,3);
	$Mes = array(1,2,3);
	// New graph with a drop shadow
	$graph = new Graph(820,400);
	$graph->SetShadow();	
	
	// Use a "text" X-scale
	$graph->SetScale("textlin");
	
	// Box around plotarea
	$graph->SetBox(false); 

	// No frame around the image
	$graph->SetFrame(false);
	
	// Make the margin around the plot a little bit bigger then default
	$graph->img->SetMargin(60,15,30,50);
	
	// Setup the X and Y grid
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle('solid');
	$graph->xgrid->SetColor('#E3E3E3');
	
	// Specify X-labels
	$graph->xaxis->SetTickLabels($Mes);
	$graph->xaxis->SetLabelAngle(90);
	
	// Set title and subtitle
	$graph->title->Set("Inadimplencia $ano");
	$graph->subtitle->Set("(% x Mes)");
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));
	
	// Create the bar plot
	$b1 = new BarPlot($Inadimplencia);
	#$b1->SetLegend("Total Inadimplencia");
	
	$b1->SetAbsWidth(25);
	$b1->SetShadow();
	
	// The order the plots are added determines who's ontop
	$graph->Add($b1);
	
	$b2 = new BarPlot($Inadimplencia2);
	#$b1->SetLegend("Total Inadimplencia");
	
	$b2->SetAbsWidth(25);
	$b2->SetShadow();
	
	// The order the plots are added determines who's ontop
	$graph->Add($b2);
	
	// Finally output the  image
	$graph->Stroke();*/
	
	$Ano = date("Y");
	$Mes = date("m");
	$Dia = array();
	$Download = array();
	$Upload = array();
	
	for($i = 0; $i < ultimoDiaMes($Mes, $Ano); $i++) {
		$Dia[] = $i+1;
		$Download[] = rand(1000, 10000);
		$Upload[] = rand(0, 1000);
	}
	
	// New graph with a drop shadow
	$graph = new Graph(820,400);
	$graph->SetShadow();	
	
	// Use a "text" X-scale
	$graph->SetScale("textlin");
	
	// Box around plotarea
	$graph->SetBox(false); 

	// No frame around the image
	$graph->SetFrame(false);
	
	// Make the margin around the plot a little bit bigger then default
	$graph->img->SetMargin(60,15,30,50);
	
	// Setup the X and Y grid
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle('solid');
	$graph->xgrid->SetColor('#E3E3E3');
	
	// Specify X-labels
	$graph->xaxis->SetTickLabels($Dia);
	$graph->xaxis->SetLabelAngle(90);
	
	// Set title and subtitle
	$graph->title->Set("Inadimplencia $ano");
	$graph->subtitle->Set("(% x Mes)");
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));
	
	// Create the bar plot
	$dw = new BarPlot($Download);
	
	$dw->SetLegend("Download");
	$dw->SetShadow();
	
	$graph->Add($dw);
	
	$dw->SetFillColor('#61a9f3');
	$dw->SetColor('#1a84ee');
	
	// Create the bar plot
	$up = new BarPlot($Upload);
	
	$up->SetLegend("Upload");
	$up->SetShadow();
	
	$graph->Add($up);
	
	$up->SetFillColor('#6fd76f');
	$up->SetColor('#2b9b2b');
	
	
/*	$dw = new LinePlot($Download);
	$graph->Add($dw);

	$dw->SetLegend("Download");
	$dw->SetFillColor('#61a9f3');
	$dw->SetColor('#1a84ee');

	$up = new LinePlot($Upload);
	$graph->Add($up);

	$up->SetLegend("Upload");
	$up->SetFillColor('#6fd76f');
	$up->SetColor('#2b9b2b');*/
	
	// Finally output the  image
	$graph->Stroke();
?>
