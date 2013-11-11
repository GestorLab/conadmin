<?php
	$localModulo		=	1;
	$localOperacao		=	60;
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
	$where					= "";
	$sqlAux					= "";

	for($i = 0; $i <12; $i++){
		$ValorMes[$i]	= 0;
		$ValorMesRecebido[$i]	= 0;
		$Mes[$i]		= substr(mes($i+1),0,3);
	}
		
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$where    .=	" and Pessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	if($_SESSION["RestringirAgenteCarteira"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado,
							Carteira
						where 
							AgenteAutorizado.IdLoja = $IdLoja  and 
							AgenteAutorizado.IdLoja = Carteira.IdLoja and
							AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
							Carteira.IdCarteira = '$IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$where    .=	" and Pessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	$sql	=	"select
					MesReferencia,
					sum(ValorFinal) ValorTotal
				from
					(select 
						concat(substring(ContaReceberDados.DataVencimento,1,4), substring(ContaReceberDados.DataVencimento,6,2)) MesReferencia, 
						(ContaReceberDados.ValorFinal - ContaReceberDescontoAConceber.ValorDescontoAConceber) ValorFinal
					from 
						ContaReceberDados,
						ContaReceberDescontoAConceber,
						Pessoa 
						$sqlAux 
					where 
						ContaReceberDados.IdLoja = ContaReceberDados.IdLoja and 
						ContaReceberDados.IdLoja = ContaReceberDescontoAConceber.IdLoja and 
						ContaReceberDados.IdLoja = $IdLoja and 
						ContaReceberDados.IdPessoa = Pessoa.IdPessoa and 
						ContaReceberDados.IdContaReceber = ContaReceberDados.IdContaReceber and 
						ContaReceberDados.IdContaReceber = ContaReceberDescontoAConceber.IdContaReceber and 
						ContaReceberDados.DataVencimento >= '$ano-01-01' and
						ContaReceberDados.DataVencimento <= '$ano-12-31' and
						ContaReceberDados.IdStatus not in (0,7) $where) MesReferencia
				group by
					MesReferencia";							
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$ValorMes[(int)substr($lin[MesReferencia],4,2)-1] = $lin[ValorTotal];
	}

	$sql	=	"select
					MesReferencia,
					sum(ValorRecebido) ValorTotal
				from
					(select 
						concat(substring(DataRecebimento,1,4),
						substring(DataRecebimento,6,2)) MesReferencia, 
						ValorRecebido 
					from 
						ContaReceberRecebimento,
						ContaReceber,
						Pessoa $sqlAux 
					where 
						ContaReceberRecebimento.IdLoja = $IdLoja and 
						ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja and 
						ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber and 
						ContaReceberRecebimento.DataRecebimento >= '$ano-01-01' and
						ContaReceberRecebimento.DataRecebimento <= '$ano-12-31' and
						ContaReceberRecebimento.IdStatus != 0 and
						ContaReceber.IdPessoa = Pessoa.IdPessoa $where) Recebimento
				group by
					MesReferencia";							
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$ValorMesRecebido[(int)substr($lin[MesReferencia],4,2)-1] = $lin[ValorTotal];
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
	$graph->title->Set("Recebimento $ano");	
	$graph->subtitle->Set("(".getParametroSistema(5,1)." x Mes)");
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
	$bplot = new BarPlot($ValorMes);
	$fcol='#440000';
	$tcol='#FF9090';

	$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);

	// Set line weigth to 0 so that there are no border
	// around each bar
	$bplot->SetWeight(0);	
	$graph->Add($bplot);
	
	$bplot->SetLegend("a receber (".getParametroSistema(5,1)." ".array_sum($ValorMes).")");
	$bplot->SetWidth(0.6);
	$bplot->SetColor("#A14848");
	$bplot->SetFillColor("#E87E7E");
	
	// Create filled line plot
	$lplot = new LinePlot($ValorMesRecebido);
	$lplot->SetFillColor('skyblue@0.5');
	$lplot->SetColor('navy@0.7');
	$lplot->SetLegend("recebido (".getParametroSistema(5,1)." ".array_sum($ValorMesRecebido).")");
	$lplot->SetBarCenter();

	$lplot->mark->SetType(MARK_SQUARE);
	$lplot->mark->SetColor('blue@0.5');
	$lplot->mark->SetFillColor('lightblue');
	$lplot->mark->SetSize(6);

	$graph->Add($lplot); 
	// .. and finally send it back to the browser
	$graph->Stroke(); 
?>