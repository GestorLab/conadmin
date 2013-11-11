<?php
	$localModulo		= 1;
	$localOperacao		= 185;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");
	include('../../../rotinas/verifica.php');
	include("../../../classes/jpgraph/src/jpgraph.php");
	include("../../../classes/jpgraph/src/jpgraph_pie.php");
	include("../../../classes/jpgraph/src/jpgraph_pie3d.php");
	
	clearstatcache();
	
	$IdLoja = $_SESSION['IdLoja'];
	$sql_temp = " (
					(
						SELECT 
							Pessoa.IdPessoa 
						FROM
							Pessoa,
							Contrato 
						WHERE 
							Pessoa.IdPessoa = Contrato.IdPessoa AND 
							Contrato.IdLoja = $IdLoja AND 
							Contrato.IdStatus > 199
					) UNION (
						SELECT 
							Pessoa.IdPessoa 
						FROM
							Pessoa,
							ContaEventual 
						WHERE 
							Pessoa.IdPessoa = ContaEventual.IdPessoa AND 
							ContaEventual.IdLoja = $IdLoja AND 
							ContaEventual.IdStatus > 0
					) UNION (
						SELECT 
							Pessoa.IdPessoa 
						FROM
							Pessoa,
							OrdemServico 
						WHERE 
							Pessoa.IdPessoa = OrdemServico.IdPessoa AND 
							OrdemServico.IdLoja = $IdLoja AND 
							(
								(
									OrdemServico.IdStatus > 99 AND 
									OrdemServico.IdStatus < 199
								) OR 
								OrdemServico.IdStatus > 299
							)
					)
				) QTDTemp";
	$sql = "SELECT
				QTDAcesso.QTDAcesso,
				QTD.QTD
			FROM
				(
					SELECT 
						COUNT(*) QTDAcesso
					FROM
						(
							SELECT 
								QTDTemp.IdPessoa 
							FROM
								$sql_temp,
								LogAcessoCDA 
							WHERE 
								QTDTemp.IdPessoa = LogAcessoCDA.IdPessoa 
							GROUP BY 
								QTDTemp.IdPessoa
						) QTDTemp
				) QTDAcesso,
				(
					SELECT
						COUNT(*) QTD
					FROM
						(
							SELECT 
								QTDTemp.IdPessoa
							FROM
								$sql_temp
							GROUP BY
								QTDTemp.IdPessoa
						) QTDTemp
				) QTD;";
	$res = mysql_query($sql, $con);
	$lin = mysql_fetch_array($res);
	$Percentual = (((int)$lin[QTDAcesso]*100)/(int)$lin[QTD]);
	$Legenda = array(
		"Já Acessaram (".((int)$lin[QTDAcesso]).")",
		"Nunca Acessaram (".((int)$lin[QTD]-(int)$lin[QTDAcesso]).")"
	);
	$Percentual = array(
		$lin[QTDAcesso],
		($lin[QTD])
	);
	
	$graph = new PieGraph(820,400);
	
	$theme_class = new OceanTheme;
	$graph->SetTheme($theme_class);
	
	$graph->title->Set("Clientes que já acessam a CDA");
	$graph->title->SetColor("#444");
	$graph->subtitle->Set("Total de clientes: ".((int)$lin[QTD]));
	$graph->subtitle->SetColor("#444");
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));
	$graph->subsubtitle->SetColor("#444");
	
	$p1 = new PiePlot3D($Percentual);
	$graph->Add($p1);
	
	$p1->ExplodeSlice(1);
	$p1->SetSize(150);
	$p1->SetCenter(0.29, 0.49);
	$p1->SetLegends($Legenda);
	
	$graph->legend->Pos(0.54,0.22,"left","top");
	$graph->SetShadow();
	$graph->Stroke();
?>