<?php
	$localModulo		=	1;
	$localOperacao		=	116;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include ('../../../rotinas/verifica.php');
	include ("../../../classes/jpgraph/src/jpgraph.php");
	include ("../../../classes/jpgraph/src/jpgraph_pie.php");
	include ("../../../classes/jpgraph/src/jpgraph_pie3d.php");
	
	clearstatcache();
	
	$IdLoja						= $_SESSION['IdLoja'];
	$IdTipoOrdemServico			= $_GET[IdTipoOrdemServico];
	$IdSubTipoOrdemServico		= $_GET[IdSubTipoOrdemServico];
	$IdGrupoUsuarioAtendimento	= $_GET[IdGrupoUsuarioAtendimento];
	$where						= "";
	$TituloAux					= "";

	if($IdGrupoUsuarioAtendimento != ''){
		$where .= " and IdGrupoUsuarioAtendimento = $IdGrupoUsuarioAtendimento";

		$sql = "select DescricaoGrupoUsuario from GrupoUsuario where IdLoja = $IdLoja and IdGrupoUsuario = $IdGrupoUsuarioAtendimento";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$TituloAux .= "\nGrupo: $lin[DescricaoGrupoUsuario]";
	}
	if($IdTipoOrdemServico != ''){
		$where .= " and IdTipoOrdemServico = $IdTipoOrdemServico";

		$sql = "select DescricaoTipoOrdemServico from TipoOrdemServico where IdLoja = $IdLoja and IdTipoOrdemServico = $IdTipoOrdemServico";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$TituloAux .= "\n$lin[DescricaoTipoOrdemServico]";
		
		if($IdSubTipoOrdemServico != ''){
			$where .= " and IdSubTipoOrdemServico = $IdSubTipoOrdemServico";

			$sql = "select DescricaoSubTipoOrdemServico from SubTipoOrdemServico where IdLoja = $IdLoja and IdTipoOrdemServico = $IdTipoOrdemServico and IdSubTipoOrdemServico = $IdSubTipoOrdemServico";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			$TituloAux .= " - $lin[DescricaoSubTipoOrdemServico]";
		}
	}
		
	$sql = "select
				IdMarcador,
				count(*) Qtd
			from
				OrdemServico
			where
				IdLoja = $IdLoja and
				IdStatus > 0 and
				IdStatus < 500 and
				IdStatus != 200 
				$where
			group by
				IdMarcador
			order by
				IdMarcador";
	$res = mysql_query($sql,$con);
	$i = 0;
	$SliceColors = array();
	while($lin = mysql_fetch_array($res)){
		if($lin[IdMarcador] != ''){
			$Marcador[$i] = getParametroSistema(120,$lin[IdMarcador]);
			$SliceColors[$i] = getParametroSistema(155,$lin[IdMarcador]);
		} else{
			$Marcador[$i] = "Sem marcado";
			$SliceColors[$i] = "#d3d3d3";
		}
		
		$Marcador[$i] .= " (".$lin[Qtd].")";
		$Qtd[$i]	= $lin[Qtd];
		$i++;
	}

	$Total = @array_sum($Qtd);

	for($ii = 0; $ii < $i; $ii++){
		$Percentual[$ii] = 100*$Qtd[$ii]/$Total;
	}

	$graph = new PieGraph(820,400);
	
	$theme_class= new OceanTheme;
	$graph->SetTheme($theme_class);
	
	if(empty($Total)) 
		$Total = 0;
	
	$graph->title->Set(dicionario(886));
	$graph->title->SetColor("#444");
	$graph->subtitle->Set(dicionario(128).": $Total$TituloAux");
	$graph->subtitle->SetColor("#444");
	$graph->subsubtitle->Set(dicionario(369).": ".date("d/m/Y H:i:s"));
	$graph->subsubtitle->SetColor("#444");

	if($i > 0){
		$p1 = new PiePlot3D($Percentual);
		$graph->Add($p1);
		
		$p1->ExplodeSlice(1);
		$p1->SetSize(150);
		$p1->SetCenter(0.29, 0.49);
		$p1->SetLegends($Marcador);
		$p1->SetSliceColors($SliceColors);
	}

	$graph->legend->Pos(0.54,0.22,"left","top");
	$graph->SetShadow();
	$graph->Stroke();
?>
