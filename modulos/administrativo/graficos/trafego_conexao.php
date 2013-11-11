<?php
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include ('../../../rotinas/verifica.php');
	include ("../../../classes/jpgraph/src/jpgraph.php");
	include ("../../../classes/jpgraph/src/jpgraph_line.php");
	include ("../../../classes/jpgraph/src/jpgraph_date.php");
	
	$IdLoja		 	= $_SESSION["IdLoja"];
	$IdPessoaLogin	= $_SESSION['IdPessoa'];	
	$IdContrato 	= $_GET['IdContrato'];
	$IdGrafico 		= $_GET['IdGrafico'];
	
	switch($IdGrafico) {
		case '1': # INTERVALO DE MINUTOS
			$exp = "/([\d]*)-([\d]*)| /";
			$subtitulo = "Últimos 60 minutos";
			$Data = "SUBTIME((CONCAT(CURDATE(),' ',CURTIME())), '0 1:0:0')";
			$sql = "SELECT SUBSTRING(SUBTIME((CONCAT(CURDATE(),' ',CURTIME())), '0 0:1:0'),1,16) NewDate;";
			
			for($i = 0; $i < 60; $i++) {
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				$sql = "SELECT SUBSTRING(SUBTIME('$lin[NewDate]', '0 0:1:0'),1,16) NewDate;";
				$tdr[$i] = $lin[NewDate];
				# INTERVALO DA ESCALA
				if($i % 2 == 0) {
					$x_sca[$i] = preg_replace("/[\w\W]* /", '', $tdr[$i]);
				} else {
					$x_sca[$i] = '';
				}
				# TRATAMENTO CONTRA ERRO CASO NÃO HAJÁ NEM UMA INFORMAÇÃO
				if($i == 0) {
					$ent[$i] = $i;
					$sai[$i] = $i;
				} else {
					$ent[$i] = '';
					$sai[$i] = '';
				}
			}
			
			$select = " Data, SUBSTRING(Data, 1, 16) DataTemp, Tx, Rx";
			$group = "";
			break;
		case '2': # INTERVALO DE MINUTOS
			$exp = "/([\d]*)-([\d]*)| /";
			$subtitulo = "Últimas 24 horas";
			$Data = "SUBTIME((CONCAT(CURDATE(),' ',CURTIME())), '1 0:0:0')";
			$sql = "SELECT SUBSTRING(SUBTIME((CONCAT(CURDATE(),' ',CURTIME())), '0 0:1:0'),1,16) NewDate;";
			
			for($i = 0; $i < 1440; $i++) {
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				$sql = "SELECT SUBSTRING(SUBTIME('$lin[NewDate]', '0 0:1:0'),1,16) NewDate;";
				$tdr[$i] = $lin[NewDate];
				# INTERVALO DA ESCALA
				if($i % 30 == 0) {
					$x_sca[$i] = preg_replace("/[\w\W]* /", '', $tdr[$i]);
				} else {
					$x_sca[$i] = '';
				}
				# TRATAMENTO CONTRA ERRO CASO NÃO HAJÁ NEM UMA INFORMAÇÃO
				if($i == 0) {
					$ent[$i] = $i;
					$sai[$i] = $i;
				} else {
					$ent[$i] = '';
					$sai[$i] = '';
				}
			}
			
			$select = " Data, SUBSTRING(Data, 1, 16) DataTemp, Tx, Rx";
			$group = "";
			break;
		case '3': # INTERVALO DE HORAS
			$exp = "/([\d]*)-([\d]*)| /";
			$Data = "SUBTIME((CONCAT(CURDATE(),' ',CURTIME())), '31 0:0:0')";
			$sql = "SELECT $Data DataInicial, CONCAT(CURDATE(),' ',CURTIME()) DataFinal;";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			$subtitulo = "Último Mês (".dataConv($lin[DataInicial], "Y-m-d H:i:s", "d/m/Y").' - '.dataConv($lin[DataFinal], "Y-m-d H:i:s", "d/m/Y").")";
			$sql = "SELECT SUBSTRING(SUBTIME((CONCAT(CURDATE(),' ',CURTIME())), '0 1:0:0'),1,13) NewDate;";
			
			for($i = 0; $i < 720; $i++){
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				$sql = "SELECT SUBSTRING(SUBTIME('$lin[NewDate]', '0 1:0:0'),1,13) NewDate;";
				$tdr[$i] = $lin[NewDate];
				# INTERVALO DA ESCALA
				if($i % 24 == 0) {
					$x_sca[$i] = dataConv($tdr[$i], "Y-m-d", "d/m");
				} else {
					$x_sca[$i] = '';
				}
				# TRATAMENTO CONTRA ERRO CASO NÃO HAJÁ NEM UMA INFORMAÇÃO
				if($i == 0) {
					$ent[$i] = $i;
					$sai[$i] = $i;
				} else {
					$ent[$i] = '';
					$sai[$i] = '';
				}
			}
			
			$select = " SUBTIME(CONCAT(CURDATE(), ' ', CURTIME()), '0 0:0:55') Data, SUBSTRING(Data, 1, 13) DataTemp, (SUM(Tx) / COUNT(Tx)) Tx, (SUM(Rx) / COUNT(Rx)) Rx ";
			$group = " GROUP BY SUBSTRING(Data, 1, 13)";
			break;
	}
	
	$md = array(
		0 => "B",
		1 => "kB",
		2 => "MB",
		3 => "GB",
		4 => "TB"
	);
	$subtitulo .= " - Contrato: ".$IdContrato;
	$id = 0;
	$data_break = " CONCAT(CURDATE(), ' ', CURTIME())";
	$sql = "select 
				$select 
			from
				MonitorMikrotikUsuario 
			where
				IdLoja = $IdLoja and
				IdContrato = $IdContrato and
				Data >= $Data 
			$group;";
	$res = @mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$key = @array_search($lin[DataTemp], array_reverse($tdr));
		# PASSANDO VALORES DE ENTRA E SAIDA PARA MONTAGEM DO GRÁFICO
		if($key != '') {
			$ent[$key] = $lin[Rx];
			$sai[$key] = $lin[Tx];
			$temp = $lin[Rx];
			$i = 0;
			$data_break = " '$lin[Data]'";
			# OBTER A MEDIDA DE ARMAZENAMENTO
			if($temp > 1023) {
				do {
					$i++;
					$temp = $temp/1024;
				} while($temp > 1023);
			}
			
			if($id < $i) {
				$id = $i;
			}
		}
	}
	# PEGAR O VALOR INICIAL DO GRAFO CASO TENHA
	if($ent[1] != '') {
		$sql = "select 
					$select 
				from
					MonitorMikrotikUsuario 
				where
					IdLoja = $IdLoja and
					IdContrato = $IdContrato and
					Data < $Data 
				$group 
				order by
					Data desc
				limit 1;";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		$ent[0] = $lin[Rx];
		$sai[0] = $lin[Tx];
	}
	
	$sql = "SELECT
				MonitorMikrotikUsuario.Rx AtuRx,
				(SumRx / QTD) MedRx,
				MaxRx,
				SumRx,
				MonitorMikrotikUsuario.Tx AtuTx,
				(SumTx / QTD) MedTx,
				MaxTx,
				SumTx
			FROM
				(
					SELECT
						COUNT(Rx) QTD,
						MAX(Tx) MaxTx,
						MAX(Rx) MaxRx,
						SUM(Tx) SumTx,
						SUM(Rx) SumRx
					FROM
						MonitorMikrotikUsuario 
					WHERE
						IdLoja = $IdLoja AND
						IdContrato = $IdContrato AND
						Data >= $Data AND
						Data <= $data_break
				) MonitorMikrotikUsuarioTmp,
				MonitorMikrotikUsuario
			WHERE
				IdLoja = $IdLoja AND
				IdContrato = $IdContrato AND
				Data >= $Data AND
				Data <= $data_break
			ORDER BY
				Data DESC
			LIMIT 1;";
	$res = @mysql_query($sql,$con);
	if(@mysql_num_rows($res) > 0) {
		$lin = @mysql_fetch_array($res);
		$ent_atual = $lin[AtuRx];
		$ent_media = $lin[MedRx];
		$ent_maximo = $lin[MaxRx];
		$ent_total = $lin[SumRx];
		$sai_atual = $lin[AtuTx];
		$sai_media = $lin[MedTx];
		$sai_maximo = $lin[MaxTx];
		$sai_total = $lin[SumTx];
	} else {
		$ent_atual = $ent_media = $ent_maximo = $ent_total = $sai_atual = $sai_media = $sai_maximo = $sai_total = 0;
	}
	
	$i = 0;
	
	if($ent_atual > 1023) {
		do {
			$i++;
			$ent_atual = $ent_atual / 1024;
		} while($ent_atual > 1023);
	}
	
	$ent_atual = number_format($ent_atual, 2, '.', '').' '.$md[$i];
	$i = 0;
	
	if($ent_media > 1023) {
		do {
			$i++;
			$ent_media = $ent_media / 1024;
		} while($ent_media > 1023);
	}
	
	$ent_media = number_format($ent_media, 2, '.', '').' '.$md[$i];
	$i = 0;
	
	if($ent_maximo > 1023) {
		do {
			$i++;
			$ent_maximo = $ent_maximo / 1024;
		} while($ent_maximo > 1023);
	}
	
	$ent_maximo = number_format($ent_maximo, 2, '.', '').' '.$md[$i];
	$i = 0;
	
	if($ent_total > 1023) {
		do {
			$i++;
			$ent_total = $ent_total / 1024;
		} while($ent_total > 1023);
	}
	
	$ent_total = number_format($ent_total, 2, '.', '').' '.$md[$i];
	$i = 0;
	
	if($sai_atual > 1023) {
		do {
			$i++;
			$sai_atual = $sai_atual / 1024;
		} while($sai_atual > 1023);
	}
	
	$sai_atual = number_format($sai_atual, 2, '.', '').' '.$md[$i];
	$i = 0;
	
	if($sai_media > 1023) {
		do {
			$i++;
			$sai_media = $sai_media / 1024;
		} while($sai_media > 1023);
	}
	
	$sai_media = number_format($sai_media, 2, '.', '').' '.$md[$i];
	$i = 0;
	
	if($sai_maximo > 1023) {
		do {
			$i++;
			$sai_maximo = $sai_maximo / 1024;
		} while($sai_maximo > 1023);
	}
	
	$sai_maximo = number_format($sai_maximo, 2, '.', '').' '.$md[$i];
	$i = 0;
	
	if($sai_total > 1023) {
		do {
			$i++;
			$sai_total = $sai_total / 1024;
		} while($sai_total > 1023);
	}
	
	$sai_total = number_format($sai_total, 2, '.', '').' '.$md[$i];
	
	foreach($ent as $key => $value) {
		if($ent[$key] != '') {
			# OBTER O VALOR REAL COM BASE NA MEDIDA DE ARMAZENAMENTO
			for($i = 0; $i < $id; $i++) {
				$ent[$key] /= 1024;
				$sai[$key] /= 1024;
			}
		}
	}
	
	# MONTAGEM DAS LEGENDAS
	$leg_ent = "Entrada";
	$leg_ent_atual = "Atual: ".$ent_atual;
	$leg_ent_media = "Média: ".$ent_media;
	$leg_ent_maximo = "Máximo: ".$ent_maximo;
	$leg_ent_total = "Total: ".$ent_total;
	$leg_sai = "Saida";
	$leg_sai_atual = "Atual: ".$sai_atual;
	$leg_sai_media = "Média: ".$sai_media;
	$leg_sai_maximo = "Máximo: ".$sai_maximo;
	$leg_sai_total = "Total: ".$sai_total;
	# INSERÇÃO PARA TESTE
/*	$sql = "SELECT 
				IdLoja,
				IdContrato,
				Data,
				CONCAT('2011-11-03 10',SUBSTRING(DATA,14,6)) NewDate
			FROM
				MonitorMikrotikUsuario 
			WHERE	IdLoja = 1 
				AND IdContrato = 3373 
				AND SUBSTRING(DATA,1,10) = '2011-11-03'";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$Rx = rand(0,10000);
		
		do {
			$Tx = rand(0,10000);
		} while($Rx < $Tx);
		
		$sql = "INSERT INTO MonitorMikrotikUsuario SET
					IdLoja = $lin[IdLoja],
					IdContrato = $lin[IdContrato],
					Data = '$lin[NewDate]',
					dBm = ".rand(-100,0).",
					Tx = $Tx,
					Rx = $Rx";
		mysql_query($sql,$con);
	}*/

	# MONTAR TAMANHO
	$graph = new Graph(820,303);
	$graph->SetScale("textlin");
	$graph->img->SetMargin(60,5,48,72);
	$graph->SetBox(false);
	
	# TITULO E SUBTITULO
	$graph->title->Set("Tráfego de Conexão em $md[$id] (Tx-Rx)");
	$graph->subtitle->Set($subtitulo);
	$graph->subsubtitle->Set("Gráfico gerado no dia ".date("d/m/Y H:i:s"));
	
	# CRIAR LINHA DE DEMOSTRAÇÃO DO GRÁFO 
	$lplot_ent = new LinePlot($ent);
	$graph->Add($lplot_ent);
	$lplot_ent->SetFillColor('#3FD823@0.6');
	$lplot_ent->SetColor('#408334');
	$lplot_ent->SetLegend($leg_ent);
	
	$ent_atual = new LinePlot(false);
	$graph->Add($ent_atual);
	$ent_atual->SetColor("#fffff");
	$ent_atual->SetLegend($leg_ent_atual);
	
	$ent_media = new LinePlot(false);
	$graph->Add($ent_media);
	$ent_media->SetColor("#fffff");
	$ent_media->SetLegend($leg_ent_media);
	
	$ent_maximo = new LinePlot(false);
	$graph->Add($ent_maximo);
	$ent_maximo->SetColor("#fffff");
	$ent_maximo->SetLegend($leg_ent_maximo);
	
	$ent_total = new LinePlot(false);
	$graph->Add($ent_total);
	$ent_total->SetColor("#fffff");
	$ent_total->SetLegend($leg_ent_total);
	
	$lplot_sai = new LinePlot($sai);
	$graph->Add($lplot_sai);
	$lplot_sai->SetFillColor('#48ADEB@0.6');
	$lplot_sai->SetColor("#0C4181");
	$lplot_sai->SetLegend($leg_sai);
	
	$sai_atual = new LinePlot(false);
	$graph->Add($sai_atual);
	$sai_atual->SetColor("#fffff");
	$sai_atual->SetLegend($leg_sai_atual);
	
	$sai_media = new LinePlot(false);
	$graph->Add($sai_media);
	$sai_media->SetColor("#fffff");
	$sai_media->SetLegend($leg_sai_media);
	
	$sai_maximo = new LinePlot(false);
	$graph->Add($sai_maximo);
	$sai_maximo->SetColor("#fffff");
	$sai_maximo->SetLegend($leg_sai_maximo);
	
	$sai_total = new LinePlot(false);
	$graph->Add($sai_total);
	$sai_total->SetColor("#fffff");
	$sai_total->SetLegend($leg_sai_total);
	
	$graph->legend->SetPos(0.002,0.946,"right","center");
	$graph->legend->SetColumns(5);
	
	# POSICIONAR MARCADOR DA ESCALA
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	$graph->yaxis->SetPos('min');
	$graph->yaxis->SetLabelSide(SIDE_LEFT);
	$graph->yaxis->scale->ticks->SetSide(SIDE_LEFT);
	$graph->yaxis->SetLabelMargin(10);
	$graph->yaxis->SetTitleSide(SIDE_LEFT);
	$graph->yaxis->title->Set("Tráfego [$md[$id]]"); 
	$graph->yaxis->title->SetMargin(19); 
	
	$graph->xaxis->SetTickLabels(array_reverse($x_sca));
	$graph->xaxis->SetPos('min');
	$graph->xaxis->SetLabelSide(SIDE_DOWN);
	$graph->xaxis->scale->ticks->SetSide(false);
	$graph->xaxis->SetLabelMargin(8);
	$graph->xaxis->SetLabelAngle(50);
	
	# SEM FUNDO 
	$graph->SetFrame(false);
	
	# LINHA DE SAIDA DO GRÁFO 
	$graph->Stroke();
?>