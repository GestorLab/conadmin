<?php
	$localModulo		=	1;
	$localOperacao		=	145;
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
	$where					= "";
	$sqlAux					= "";
		
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
					AnoReferencia,
					sum(ValorRecebido) ValorTotal
				from
					(select 
						substring(DataRecebimento,1,4) AnoReferencia, 
						ValorRecebido 
					from 
						ContaReceberRecebimento,
						ContaReceber,
						Pessoa $sqlAux 
					where 
						ContaReceberRecebimento.IdLoja = $IdLoja and 
						ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja and 
						ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber and 
						ContaReceberRecebimento.IdStatus != 0 and
						ContaReceber.IdPessoa = Pessoa.IdPessoa $where) Recebimento
				group by
					AnoReferencia";							
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$ValorAnoRecebidoTemp[$lin[AnoReferencia]] = $lin[ValorTotal];
	}

	$Ano = array_keys($ValorAnoRecebidoTemp);

	sort($Ano);

	for($i=0; $i<count($Ano); $i++){
		$ValorAnoRecebido[$i] = 0;
		$ValorAnoRecebido[$i] += $ValorAnoRecebidoTemp[$Ano[$i]];
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
	$graph->title->Set("Recebimentos");	
	$graph->subtitle->Set("Crestimento Anual");
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));

	// Setup the X and Y grid
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle('solid');
	$graph->xgrid->SetColor('#E3E3E3');

	// Setup month as labels on the X-axis
	$graph->xaxis->SetTickLabels($Ano);
	$graph->xaxis->SetLabelAngle(90);

	// Create a bar pot
	$bplot = new BarPlot($ValorAnoRecebido);
	$bplot->SetLegend("Total Recebido (".getParametroSistema(5,1)." ".array_sum($ValorAnoRecebido).")");
	$fcol='#004492';
	$tcol='#BADEEC';

	$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);
	$bplot->SetWeight(0);	
	$graph->Add($bplot);
	$graph->Stroke();
?>