<?php
	$localModulo		=	1;
	$localOperacao		=	150;
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
	$Nome	 				= $_GET['Nome'];
	$Assunto	 			= $_GET['Assunto'];
	$LocalAbertura 			= $_GET['LocalAbertura'];
	$Status 				= $_GET['Status'];
	$Tipo					= $_GET['Tipo'];
	$SubTipo				= $_GET['SubTipo'];
	$GrupoAtendimento		= $_GET['GrupoAtendimento'];
	$UsuarioAtendimento		= $_GET['UsuarioAtendimento'];
	$GrupoAlteracao			= $_GET['GrupoAlteracao'];
	$UsuarioAlteracao		= $_GET['UsuarioAlteracao'];
	$mesReferencia 			= dataConv($mesReferenciaTemp, 'm/Y','Y-m');
	$where					= "";
	$sqlAux					= "";

	$ano		= substr($mesReferencia,0,4);
	$mes		= substr($mesReferencia,5,2);
	$IdTicket	= "0";
	$filtro_sql	= '';

	$qtdDiasMes = ultimoDiaMes($mes, $ano);
	
	for($i = 0; $i <$qtdDiasMes; $i++){
		$QtdDia[$i]			= 0;
		$Dia[$i]			= $i+1;
		$QtdFinalizada[$i]	= 0;
	}
	
	if($Nome != ''){
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$Nome%' or RazaoSocial like '%$Nome%')";
	}
	
	if($Assunto != ''){
		$filtro_sql	.= " and HelpDesk.Assunto like '%".$Assunto."%'";
	}
	
	if($LocalAbertura != ''){
		$filtro_sql	.= " and HelpDesk.IdLocalAbertura=".$LocalAbertura;
	}
	
	if($Status != ''){
		$filtro_sql	.= " and HelpDesk.IdStatus=".$Status;
	}
	
	if($Tipo != ''){
		$filtro_sql	.= " and HelpDesk.IdTipoHelpDesk = $Tipo";
	}else{
		$filtro_sql	.= " and HelpDesk.IdTipoHelpDesk != 61";
	}
	
	if($SubTipo != ''){
		$filtro_sql	.= " and HelpDesk.IdSubTipoHelpDesk = $SubTipo";
	}
	
	if($GrupoAtendimento != '' || $UsuarioAtendimento != ''){
		$filtro_sql	.= " and HelpDesk.IdTicket in (select distinct HelpDeskHistorico.IdTicket from HelpDeskHistorico where 1";
		
		if($UsuarioAtendimento != ''){
			$filtro_sql	.= " and HelpDeskHistorico.LoginCriacao like '%$UsuarioAtendimento%'";
		} elseif($GrupoAtendimento != ''){
			$filtro_sql	.= " and HelpDeskHistorico.LoginCriacao in (select distinct UsuarioGrupoUsuario.Login from UsuarioGrupoUsuario where UsuarioGrupoUsuario.IdLoja = $IdLoja and UsuarioGrupoUsuario.IdGrupoUsuario = $GrupoAtendimento)";
		}
		
		$filtro_sql	.= ")";
	}
	
	if($UsuarioAlteracao != ""){
		$filtro_sql	.= " and HelpDesk.LoginAlteracao = '$UsuarioAlteracao'";
	}
	
	if($GrupoAlteracao != ""){
		$filtro_sql	.= " and HelpDesk.IdGrupoUsuario = $GrupoAlteracao";
	}
	
	if($filtro_sql != ''){
		$sql = "select
					HelpDesk.IdTicket
				from
					HelpDesk,
					Pessoa
				where
					HelpDesk.IdPessoa = Pessoa.IdPessoa
					$filtro_sql
				order by 
					HelpDesk.IdTicket DESC";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$IdTicket .= ",$lin[IdTicket]";
		}

		$filtro_sql = "and HelpDesk.IdTicket in ($IdTicket)";
	}

	$sql = "select
				DiaAbertura,
				count(*) Qtd
			from
				(select
					substring(DataCriacao,9,2) DiaAbertura
				from
					HelpDesk
				where
					DataCriacao like '$mesReferencia%'
					$filtro_sql
				) DiaAbertura
			group by
				DiaAbertura";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$QtdDia[(int)($lin[DiaAbertura]-1)] = $lin[Qtd];
	}
	
	$sql = "select
				DataConclusao.DataConclusao,
				sum(Qtd) Qtd
			from
				(select
					substring(TicketConcluido.DataConclusao,9,2) DataConclusao,
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
							HelpDeskHistorico.DataCriacao like '$mesReferencia%' 
							$filtro_sql
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
							HelpDeskHistorico.DataCriacao like '$mesReferencia%' 
							$filtro_sql
						group by HelpDeskHistorico.IdTicket) Temp 
					group by IdTicket) TicketConcluido
				group by
					TicketConcluido.IdTicket) DataConclusao
			group by
				DataConclusao.DataConclusao;";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$QtdFinalizada[$lin[DataConclusao]-1] = $lin[Qtd];
	}
	
	$QtdFinalizadaTotal = array_sum($QtdFinalizada);
	$QtdDiaTotal = array_sum($QtdDia);
	$QtdFinalizadaTotalPercentual = number_format(($QtdDiaTotal > 0 ? (($QtdFinalizadaTotal * 100) / $QtdDiaTotal) : 0), 1, '.', '');
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
	$graph->title->Set("Tickets $mesReferenciaTemp");
	$graph->subtitle->Set("Novos x Conclusão");
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
	$bplot = new BarPlot($QtdDia);
	$fcol='#57930F';
	$tcol='#64B81B';

	$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);
	$bplot->SetWeight(0);
	$graph->Add($bplot);
	
	$bplot->SetLegend("Qtd. Tickets Abertos (".array_sum($QtdDia).")");
	$bplot->SetWidth(0.6);
	$bplot->SetColor("#5B9E12");
	$bplot->SetFillColor("#72C229");

	// Create filled line plot
	$lplot = new LinePlot($QtdFinalizada);
	$lplot->SetFillColor('skyblue@0.5');
	$lplot->SetColor('navy@0.7');
	$lplot->SetLegend("Concluídos ($QtdFinalizadaTotal) $QtdFinalizadaTotalPercentual%");
	$lplot->SetBarCenter();

	$lplot->mark->SetType(MARK_SQUARE);
	$lplot->mark->SetColor('blue@0.5');
	$lplot->mark->SetFillColor('lightblue');
	$lplot->mark->SetSize(6);

	$graph->Add($lplot); 

	$graph->Stroke();
?>