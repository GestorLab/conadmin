<?php
	$$localModulo		=	1;
	$localOperacao		=	10001;
	$localSuboperacao	=	"R";
	$localCadComum		=	true;

	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include ('../../../rotinas/verifica.php');
	include ("../../../classes/jpgraph/src/jpgraph.php");
	include ("../../../classes/jpgraph/src/jpgraph_gantt.php");
	
	$IdLoja				=	$_SESSION['IdLoja'];
	$IdServidor			=	$_GET['filtro_servidor'];	
	$UserName			=	$_GET['filtro_login'];	
	$MAC				=	$_GET['filtro_mac'];	
	$MesReferenciaTemp	=	$_GET['filtro_mes_referencia'];
		
	$where				=	"";
	
	if($UserName != ""){
		$where		.=	" and UserName = '$UserName'";
	}
	if($MAC != ""){
		$UserName = $MAC;
		$where		.=	" and UserName = '$MAC'";
	}	
	
	$MesReferencia 		= 	dataConv($MesReferenciaTemp, 'm/Y','Y-m');

	$ano = substr($MesReferencia,0,4);
	$mes = substr($MesReferencia,5,2);

	$qtdDiasMes = ultimoDiaMes($mes, $ano);
	
	$sql	=	"select ValorCodigoInterno from CodigoInterno where IdLoja = '$IdLoja' and IdGrupoCodigoInterno = 10000 and IdCodigoInterno = '$IdServidor'";
	$res	=	mysql_query($sql,$con);
	$lin	=	mysql_fetch_array($res);
	
	$aux	=	explode("\n",$lin[ValorCodigoInterno]);
			
	$bd[server]	=	trim($aux[0]); //Host
	$bd[login]	=	trim($aux[1]); //Login
	$bd[senha]	=	trim($aux[2]); //Senha
	$bd[banco]	=	trim($aux[3]); //DB

	$conRadius	=	@mysql_connect($bd[server],$bd[login],$bd[senha]);
	@mysql_select_db($bd[banco],$conRadius);	
	
	// Some sample Gantt data
	$pos = 0;
	$array = "";
	$IdConexao = "";

	for($i=0;$i<$qtdDiasMes;$i++){
	
		if($i+1<10){
			$dd	=	"0".($i+1);
		}else{
			$dd	=	$i+1;
		}
		
		$Data	=	$ano."-".$mes."-".$dd;	
		$sql2	=	"(select
						RadAcctId IdConexao,
						substr(AcctStartTime,1,10) DataInicio,
						substr(AcctStopTime,1,10) DataFim,
						substr(AcctStartTime,11,6) HoraInicio,
						substr(AcctStopTime,11,6) HoraFim,
						AcctSessionTime Duracao,
						AcctInputOctets Download,
						AcctOutputOctets Upload
					from
						radacct
					where
						AcctStopTime != '0000-00-00 00:00:00' and 
						AcctStartTime <= '$Data 23:59:59' and
						AcctStopTime >= '$Data 00:00:00'  $where
					order by 
						AcctStartTime) 
					
					UNION
					
					(select
						RadAcctId IdConexao,
						substr(AcctStartTime,1,10) DataInicio,
						substr(AcctStopTime,1,10) DataFim,
						substr(AcctStartTime,11,6) HoraInicio,
						substr(AcctStopTime,11,6) HoraFim,
						AcctSessionTime Duracao,
						AcctInputOctets Download,
						AcctOutputOctets Upload
					from
						radacctJornal
					where
						AcctStopTime != '0000-00-00 00:00:00' and 
						AcctStartTime <= '$Data 23:59:59' and
						AcctStopTime >= '$Data 00:00:00'  $where
					order by 
						AcctStartTime)";
		$res2	=	mysql_query($sql2,$conRadius);
		if(mysql_num_rows($res2) > 0){
			while($lin2	=	mysql_fetch_array($res2)){

				if($IdConexao[$lin2[IdConexao]] == ""){

					$IdConexao[$lin2[IdConexao]] = $lin2[IdConexao];

					if(dataConv($lin2[DataInicio],'Y-m-d','Ymd') < (dataConv($MesReferenciaTemp,'m/Y','Ym').'00')){
						$lin2[DataInicio] = dataConv($MesReferenciaTemp,'m/Y','Y-m').'-'.$dd;
						$lin2[HoraInicio] = '00:00';
					}

					if($lin2[DataInicio] != $lin2[DataFim]){
						$HoraFim = '23:59';
					}else{
						$HoraFim = $lin2[HoraFim];
					}

					$HoraInicio = $lin2[HoraInicio];

					$arrayTemp[0] = $i;
					$arrayTemp[1] = "$dd";
					$arrayTemp[2] = $lin2[HoraInicio];
					$arrayTemp[3] = $HoraFim;
					$array[$pos] = $arrayTemp;
					$pos++;

					$loop = 1;

#					echo "$dd ($i) - Data Inicio: $lin2[DataInicio] $HoraInicio - Data Fim: $lin2[DataFim] $HoraFim (".$IdConexao[$lin2[IdConexao]].")<br>";

					while($lin2[DataInicio] != $lin2[DataFim]){

						if(($i+$loop)>=$qtdDiasMes){
							break;
						}

						$lin2[DataInicio] = incrementaData($lin2[DataInicio],1);

						if($lin2[DataInicio] == $lin2[DataFim]){
							$HoraFim = $lin2[HoraFim];
						}
						
						$ddTemp = $i+1+$loop;
						if($ddTemp < 10){
							$ddTemp = "O".$ddTemp;
						}

						$HoraInicio = "00:00";

						$arrayTemp[0] = $i+$loop;
						$arrayTemp[1] = "$ddTemp";
						$arrayTemp[2] = $HoraInicio;
						$arrayTemp[3] = $HoraFim;
						$array[$pos] = $arrayTemp;
						$pos++;

						$loop++;

#						echo "$dd ($i) - (LOOP) Data Inicio: $lin2[DataInicio] $HoraInicio - Data Fim: $lin2[DataFim] $HoraFim (".$IdConexao[$lin2[IdConexao]].")<br>";
					}
				}
			}
		}else{
			$arrayTemp[0] = $i;
			$arrayTemp[1] = "$dd";
			$arrayTemp[2] = "00:00";
			$arrayTemp[3] = "00:00";
			$array[$pos] = $arrayTemp;
			$pos++;
		}
	}

#	print_r($array);

	// Basic graph parameters
	$graph = new GanttGraph();
	$graph->SetMarginColor('#004492');
	$graph->SetColor('white');
	$graph->img->SetMargin(16,16,40,16);
	
	// We want to display day, hour and minute scales
	$graph->ShowHeaders(GANTT_HHOUR | GANTT_HMIN);
	
	// We want to have the following titles in our columns
	// describing each activity
	$graph->scale->actinfo->SetColTitles(array('Dia'));
	$graph->scale->actinfo->SetFont(FF_FONT1,FF_FONT1,10);
	$graph->scale->actinfo->vgrid->SetColor('gray');
	$graph->scale->actinfo->SetColor('darkgray');
	
	// Setup day format
	$graph->scale->day->SetBackgroundColor('lightyellow:1.5');
	$graph->scale->day->SetFont(FF_FONT1);
	$graph->scale->day->SetStyle(DAYSTYLE_SHORTDAYDATE1);
	
	// Setup hour format
	$graph->scale->hour->SetIntervall(1);
	$graph->scale->hour->SetBackgroundColor('#E2E7ED');
	$graph->scale->hour->SetFont(FF_FONT0);
	$graph->scale->hour->SetStyle(HOURSTYLE_H24);
	$graph->scale->hour->grid->SetColor('gray:0.8');
	
	// Setup minute format
	$graph->scale->minute->SetIntervall(30);
	$graph->scale->minute->SetBackgroundColor('#E2E7ED');
	$graph->scale->minute->SetFont(FF_FONT0);
	$graph->scale->minute->SetStyle(MINUTESTYLE_MM);
	$graph->scale->minute->grid->SetColor('lightgray');
	
	$graph->title->Set("Tempo de Conexão $MesReferenciaTemp - Gerado em ".date("d/m/Y H:i:s"));
	$graph->title->SetColor('white');
	$graph->title->SetFont(FF_FONT1,FS_BOLD,14);
	$graph->subtitle->Set("Login/Mac: $UserName");
	$graph->subtitle->SetColor('white');
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));
	$graph->subsubtitle->SetColor('#ffffff');
	
	for($i=0; $i<count($array); ++$i) {
	   	$bar = new GanttBar($array[$i][0],$array[$i][1],$array[$i][2],$array[$i][3]);
	    $bar->SetPattern(BAND_RDIAG,"#FF9090");
	   	$bar->SetFillColor("gray");
	   	$graph->Add($bar);
	}
	
	$graph->Stroke();
?>
