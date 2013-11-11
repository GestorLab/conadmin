<?php

	$localModulo		=	1;
	$localOperacao		=	59;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;

	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include ('../../../rotinas/verifica.php');
	include ("../../../classes/jpgraph/src/jpgraph.php");
	include ("../../../classes/jpgraph/src/jpgraph_bar.php");
		
	
	$IdLoja		 			= $_SESSION["IdLoja"];
	$IdPessoaLogin			= $_SESSION['IdPessoa'];	
	$ano 					= $_GET['ano'];
	$DataFaturamento 		= $_GET['DataFaturamento'];
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
	
	switch($DataFaturamento){
	
		case 'DataLancamento':
		
			$sqlAux  	.= " , ContaReceber ";
			$where   	.= " and SUBSTR(ContaReceber.DataLancamento,1,4) ='$ano'
							 and ContaReceber.IdLoja = ContaReceberDados.IdLoja  
						     and ContaReceber.IdContaReceber = ContaReceberDados.IdContaReceber";
			break;
		
		case 'DataVencimentoOriginal':
			$sqlAux 	.= " , ContaReceberVencimento
								,(select 
								IdLoja,
								IdContaReceber, 
								min(DataVencimento) DataVencimentoOriginal 
							from 
								ContaReceberVencimento 
							group by IdLoja, IdContaReceber) ContaReceberVencimentoOriginal";
			$where 		.= " and 
								ContaReceberDados.IdLoja = ContaReceberVencimentoOriginal.IdLoja and 
								ContaReceberDados.IdContaReceber = ContaReceberVencimentoOriginal.IdContaReceber
								and ContaReceberVencimento.IdLoja = ContaReceberDados.IdLoja  
								and ContaReceberVencimento.IdContaReceber = ContaReceberDados.IdContaReceber";
			break;
	
		case 'DataVencimentoAtual':
		
			$sqlAux  	.= " , ContaReceberVencimento ";
			$where 		.= " and substr(ContaReceberVencimento.DataVencimento,1,4) = '$ano'
							 and ContaReceberVencimento.IdLoja = ContaReceberDados.IdLoja  
						     and ContaReceberVencimento.IdContaReceber = ContaReceberDados.IdContaReceber";
			break;
			
		default:
			$sqlAux 	.= " , ContaReceberVencimento
								,(select 
								IdLoja,
								IdContaReceber, 
								min(DataVencimento) DataVencimentoOriginal 
							from 
								ContaReceberVencimento 
							group by IdLoja, IdContaReceber) ContaReceberVencimentoOriginal";
			$where 		.= " and 
								ContaReceberDados.IdLoja = ContaReceberVencimentoOriginal.IdLoja and 
								ContaReceberDados.IdContaReceber = ContaReceberVencimentoOriginal.IdContaReceber
								and ContaReceberVencimento.IdLoja = ContaReceberDados.IdLoja  
								and ContaReceberVencimento.IdContaReceber = ContaReceberDados.IdContaReceber";
		
	}

	 $sql	=	"select
					MesReferencia,
					sum(ValorTotal) ValorTotal
				from
					(select 
						concat(substring(ContaReceberDados.DataLancamento,1,4),
						substring(ContaReceberDados.DataLancamento,6,2)) MesReferencia, 
						(ContaReceberDados.ValorLancamento - ContaReceberDados.ValorDesconto + ContaReceberDados.ValorDespesas) ValorTotal 
					from 
						ContaReceberDados,
						Pessoa 
						$sqlAux 
					where 
						ContaReceberDados.IdLoja = $IdLoja and 
						ContaReceberDados.IdPessoa = Pessoa.IdPessoa and 
						ContaReceberDados.IdStatus != 0 $where) MesReferencia
				where
					MesReferencia like '$ano%'
				group by
					MesReferencia
				order by
					MesReferencia";
							
	$res = mysql_query($sql,$con);
	$i = 0;
	
	while($lin = mysql_fetch_array($res)){
		$ValorMes[$i] 	= $lin[ValorTotal];
		$Mes[$i]		= substr(mes(substr($lin[MesReferencia],4,2)),0,3);
		$i++;
	}
	
	
	if($i == 0){
		$ValorMes[$i] 	= 0;
		$Mes[$i]		= 0;
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
	$graph->xaxis->SetTickLabels($Mes);
	$graph->xaxis->SetLabelAngle(90);
	
	// Set title and subtitle
	$graph->title->Set("Faturamento $ano");
	$graph->subtitle->Set("(".getParametroSistema(5,1)." x Mes)");
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));
	
	// Create the bar plot
	$b1 = new BarPlot($ValorMes);
	#$b1->SetLegend("Total de Vendas");
	
	$b1->SetAbsWidth(25);
	$b1->SetShadow();
	
	// The order the plots are added determines who's ontop
	$graph->Add($b1);
	
	// Finally output the  image
	$graph->Stroke();
?>
