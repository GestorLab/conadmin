<?php

	$localModulo		=	1;
	$localOperacao		=	104;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;

	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include ('../../../rotinas/verifica.php');
	include ("../../../classes/jpgraph/src/jpgraph.php");
	include ("../../../classes/jpgraph/src/jpgraph_bar.php");	

	$IdLoja		 								= $_SESSION["IdLoja"];
	$IdPessoaLogin								= $_SESSION['IdPessoa'];	
	$filtro										= $_GET['filtro'];
	$filtro_descricao_servico					= $_GET['filtro_descricao_servico'];	
	$filtro_id_servico							= $_GET['filtro_id_servico'];
	$localMigrado								= $_SESSION["filtro_contrato_migrado"];	
	$filtro_somar_contrato_cancelado 			= $_GET['filtro_somar_contrato_cancelado'];
	$filtro_somar_contrato_cancelado_migrado    = $_GET['filtro_somar_contrato_cancelado_migrado'];
	$DataInicio									= dataConv($_GET['DataInicio'],'d/m/Y','Y-m-d');
	$DataTermino								= dataConv($_GET['DataTermino'],'d/m/Y','Y-m-d');

	$where					= "";
	$where2 				= "";
	$sqlAux					= "";	
	$Ano 					= date('Y');
	$DataAtual				= date('Y-m-d');
		
	if($filtro != 'DataCadastroPessoa'){	
		if($DataInicio != "" && $DataTermino == ""){
			$DataTermino = date("Y-m-d");
		}
		if($DataInicio != ''){
			$where2 .= " and substring(Contrato.$filtro,1,10) >= '$DataInicio'";
		}
		if($DataTermino != ''){
			$where2 .= " and substring(Contrato.$filtro,1,10) <= '$DataTermino'";
		}
		if($DataInicio == "" && $DataTermino == ""){
			$DataInicio  = "$Ano-01-01";	
			$DataTermino = $DataAtual;	
			$where2 .= " and (substring(Contrato.$filtro,1,10) >= '$DataInicio' and substring(Contrato.$filtro,1,10) <= '$DataTermino')";
		}		
	}else{
		if($DataInicio != "" && $DataTermino == ""){
			$DataTermino = date("Y-m-d");
		}
		if($DataInicio != ''){
			$where2 .= " and substring(Pessoa.DataCriacao,1,10) >= '$DataInicio'";
		}
		if($DataTermino != ''){
			$where2 .= " and substring(Pessoa.DataCriacao,1,10) <= '$DataTermino'";
		}
		if($DataInicio == "" && $DataTermino == ""){
			$DataInicio  = "$Ano-01-01";	
			$DataTermino = $DataAtual;				
			$where2 .= " and (substring(Pessoa.DataCriacao,1,10) >= '$DataInicio' and substring(Pessoa.DataCriacao,1,10) <= '$DataTermino')";
		}
	}
	if($filtro_somar_contrato_cancelado == '2'){
		$where2 .= " AND Contrato.IdStatus != 1 ";
	}
	if($filtro_somar_contrato_cancelado_migrado == '2'){
		$where2 .= " AND Contrato.IdStatus != 102 ";
	}
	if($_SESSION["RestringirCarteira"] == true){
		$sqlAux		.=	",(select 
								AgenteAutorizadoPessoa.IdContrato 
						   from 
								AgenteAutorizadoPessoa,
								Carteira 
						   where 
								AgenteAutorizadoPessoa.IdLoja = $IdLoja and 
								AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
								AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
								Carteira.IdCarteira = $IdPessoaLogin and 
								Carteira.Restringir = 1 and 
								Carteira.IdStatus = 1
							) ContratoCarteira";
		$filtro_sql .=  " and  Contrato.IdContrato = ContratoCarteira.IdContrato";
	}else{
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado
								where 
									AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
									AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = $IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1
								) PessoaAgenteAutorizado";
			$filtro_sql .=  " and Contrato.IdContrato = PessoaAgenteAutorizado.IdContrato";
		}
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado,
									Carteira
								where 
									AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
									AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
									AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
									Carteira.IdCarteira = $IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1
								) ContratoAgenteCarteira";
			$filtro_sql .=  " and  Contrato.IdContrato = ContratoAgenteCarteira.IdContrato";
		}
	}
	
	if($filtro_descricao_servico != ''){
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_id_servico != ''){
		$filtro_sql .= " and Servico.IdServico = '$filtro_id_servico'";
	}
	
	if($localMigrado == '2'){
		$filtro_sql .= " and Contrato.IdStatus != '102'";
	}
	
	if($filtro != 'DataCadastroPessoa'){		
		 $sql	=	"select 
						substring(Contrato.$filtro,1,7) Mes,
						count(Contrato.IdContrato) QTD 
					from 
						Contrato,
						Pessoa,
						Servico $sqlAux
					where
						Contrato.IdLoja = $IdLoja and
						Contrato.IdPessoa = Pessoa.IdPessoa and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico 
						$where2				
						$filtro_sql
					group by 
						Mes 
					order by 
						Mes";
	}else{		
		 $sql	=	"select 
						substring(Pessoa.DataCriacao,1,7) Mes,
						count(Contrato.IdContrato) QTD 
					from 
						Contrato,
						Pessoa,
						Servico $sqlAux
					where
						Contrato.IdLoja = $IdLoja and
						Contrato.IdPessoa = Pessoa.IdPessoa and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico 
						$where2
						$filtro_sql
					group by 
						Mes 
					order by 
						Mes";		
	}						
	$res = mysql_query($sql,$con);	
	$i 			= 	0;
	$total[$i]	=	0;
	while($lin = mysql_fetch_array($res)){
		$QTD[$i] 		= $lin[QTD];
		$total[$i]	    = $total[$i-1] + $QTD[$i];
		$Mes[$i]		= substr(mes(substr($lin[Mes],5,2)),0,3);
		$i++;
	}	
	if($i == 0){
		$Mes[$i]	= 0;
		$total[$i] 	= 0;
		$QTD[$i]   	= 0;
	}
	
	// New graph with a drop shadow
	$graph = new Graph(820,400);
	$graph->SetShadow();
		
	// Use a "text" X-scale
	$graph->SetScale("textlin");
	
	// Make the margin around the plot a little bit bigger then default
	$graph->img->SetMargin(60,15,30,50);
	
	// Box around plotarea
	$graph->SetBox(false); 

	// No frame around the image
	$graph->SetFrame(false);
	
	// Specify X-labels
	$graph->xaxis->SetTickLabels($Mes);
	//$graph->xaxis->SetLabelAngle(180);
		
	$DataInicio  = dataConv($DataInicio,'Y-m-d','d/m/Y');
	$DataTermino = dataConv($DataTermino,'Y-m-d','d/m/Y');		
		
	// Set title and subtitle
	$graph->title->Set("Quantidade de Novos Contratos por Período - $DataInicio a $DataTermino");
	$graph->subtitle->Set("(QTD x Mes)");
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));
	
	// Setup the X and Y grid
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle('solid');
	$graph->xgrid->SetColor('#E3E3E3');
	
	// Create the bar plot
	$b1 = new BarPlot($QTD);
	//$b1->SetLegend("Total de Vendas");
	
	$b1->SetAbsWidth(25);
	$b1->SetShadow();
	
	// The order the plots are added determines who's ontop
	$graph->Add($b1);
	
	// Finally output the  image
	$graph->Stroke(); 
?>