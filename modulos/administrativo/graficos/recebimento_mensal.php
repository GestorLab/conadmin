<?php
	$localModulo		=	1;
	$localOperacao		=	61;
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
	$mesReferenciaTemp 		= $_GET['mesReferencia'];
	$mesReferencia 			= dataConv($mesReferenciaTemp, 'm/Y','Y-m');
	$sqlAux					= "";

	$ano = substr($mesReferencia,0,4);
	$mes = substr($mesReferencia,5,2);

	$qtdDiasMes = ultimoDiaMes($mes, $ano);
	
	for($i = 0; $i <$qtdDiasMes; $i++){
		$ValorDia[$i]			= 0;
		$ValorDiaRecebido[$i]	= 0;
		$Dia[$i]				= $i+1;
	}

	$sql	=	"select 
					Dia,
					sum(ValorTotal) ValorTotal
				from
					(select 
						substring(ContaReceberDados.DataVencimento,9,2) Dia, 
						(ContaReceberDados.ValorLancamento - ContaReceberDados.ValorDesconto + ContaReceberDados.ValorDespesas - ContaReceberDescontoAConceber.ValorDescontoAConceber) ValorTotal 
					from 
						ContaReceberDados,
						ContaReceberDescontoAConceber,
						Pessoa 
						$sqlAux 
					where 
						ContaReceberDados.IdLoja = $IdLoja and 
						ContaReceberDados.IdLoja = ContaReceberDados.IdLoja and 
						ContaReceberDados.IdLoja = ContaReceberDescontoAConceber.IdLoja and 
						ContaReceberDados.IdContaReceber = ContaReceberDados.IdContaReceber and 
						ContaReceberDados.IdContaReceber = ContaReceberDescontoAConceber.IdContaReceber and 
						ContaReceberDados.IdPessoa = Pessoa.IdPessoa and 
						ContaReceberDados.DataVencimento like '$mesReferencia%' and
						ContaReceberDados.IdStatus != 0 and
						ContaReceberDados.IdStatus != 7) PrevisaoRecebimento
				group by
					Dia
				order by
					Dia";							
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$ValorDia[(int)($lin[Dia]-1)] = $lin[ValorTotal];
	}
	
	$sql	=	"select
					Dia,
					sum(ValorRecebido) ValorTotal
				from
					(select 
						substring(ContaReceberRecebimento.DataRecebimento,9,2) Dia, 
						ContaReceberRecebimento.ValorRecebido 
					from 
						ContaReceberRecebimento
					where 
						ContaReceberRecebimento.IdLoja = $IdLoja and 
						ContaReceberRecebimento.DataRecebimento like '$mesReferencia%' and
						ContaReceberRecebimento.IdStatus = 1) Recebimento
				group by
					Dia
				order by
					Dia";							
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$ValorDiaRecebido[(int)($lin[Dia]-1)] = $lin[ValorTotal];
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
	$graph->title->Set("Prev. de Recebimento $mesReferenciaTemp");
	$graph->subtitle->Set("(".getParametroSistema(5,1)." x Dia)");
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));

	// Setup the X and Y grid
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle('solid');
	$graph->xgrid->SetColor('#E3E3E3');

	// Setup month as labels on the X-axis
	$graph->xaxis->SetTickLabels($Dia);
	$graph->xaxis->SetLabelAngle(90);

	// Create a bar pot
	$bplot = new BarPlot($ValorDia);
	$fcol='#440000';
	$tcol='#FF9090';

	$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);

	// Set line weigth to 0 so that there are no border
	// around each bar
	$bplot->SetWeight(0);

	$graph->Add($bplot);
	
	$bplot->SetLegend("a receber (".getParametroSistema(5,1)." ".formata_double(array_sum($ValorDia)).")");
	$bplot->SetWidth(0.6);
	$bplot->SetColor("#A14848");
	$bplot->SetFillColor("#E87E7E");

	// Create filled line plot
	$lplot = new LinePlot($ValorDiaRecebido);
	$lplot->SetFillColor('skyblue@0.5');
	$lplot->SetColor('navy@0.7');
	$lplot->SetLegend("recebido  (".getParametroSistema(5,1)." ".formata_double(array_sum($ValorDiaRecebido)).")");
	$lplot->SetBarCenter();

	$lplot->mark->SetType(MARK_SQUARE);
	$lplot->mark->SetColor('blue@0.5');
	$lplot->mark->SetFillColor('lightblue');
	$lplot->mark->SetSize(6);

	$graph->Add($lplot);

	// .. and finally send it back to the browser
	$graph->Stroke();
?>