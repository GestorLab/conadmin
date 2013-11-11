<?php
	set_time_limit(0);
	
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include ('../../../rotinas/verifica.php');
	include ('../../../classes/snmp/mk-signal.php');
	include ("../../../classes/jpgraph/src/jpgraph.php");
	include ("../../../classes/jpgraph/src/jpgraph_bar.php");
	
	$sinal = array($sinal);
	$col = array("dBm\n$sinal[0]");
	 
	$grafico = new graph(150,250,"png");
	$grafico->img->SetMargin(40,40,20,50);
	$grafico->SetScale("textlin");
	$grafico->SetShadow();
	 
	$grafico->title->Set("Sinal");
	$grafico->subtitle->Set("Login: $UserName");
	$grafico->ygrid->Show(true);
	$grafico->xgrid->Show(true);
	 
	$gBarras = new BarPlot($sinal);
	$gBarras->SetWidth(35);

	$grafico->xaxis->SetTickLabels($col);
	$grafico->yaxis->HideLine(false);
	$grafico->yaxis->HideTicks(false,false);
	$grafico->Add($gBarras);
	$grafico->Stroke();
?>