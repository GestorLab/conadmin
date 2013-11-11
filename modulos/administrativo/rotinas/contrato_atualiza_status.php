<?
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	// Iguala Status dos Contratos Pai e Filho
	$sql = "select
				Contrato.IdLoja,
				Contrato.IdContrato,
				Contrato.IdStatus,
				ContratoAutomatico.IdContratoAutomatico
			from
				ContratoAutomatico,
				Contrato
			where
				ContratoAutomatico.IdLoja = Contrato.IdLoja and
				ContratoAutomatico.IdContrato = Contrato.IdContrato";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sqlUpdate = "update Contrato set IdStatus='$lin[IdStatus]' where IdLoja = $lin[IdLoja] and IdContrato = $lin[IdContratoAutomatico] and IdStatus != $lin[IdStatus]";
		$local_transaction[$tr_i]	=	mysql_query($sqlUpdate,$con);
		$tr_i++;

	}

	// Expira a data de ativo temporariamente
	$sqlContrato = "select
						IdLoja,
						IdContrato,
						IdStatus,
						Obs
					from
						Contrato
					where
						IdStatus = 201 and
						concat(substring(VarStatus,7,4),'-',substring(VarStatus,4,2),'-',substring(VarStatus,1,2)) < curdate()";
	$resContrato = mysql_query($sqlContrato,$con);
	while($linContrato = mysql_fetch_array($resContrato)){

		$local_Obs	=	date("d/m/Y H:i:s")." [automatico] - Mudou status para Ativo.\n$linContrato[Obs]";
		$local_Obs  = str_replace('"',"'",$local_Obs);		

		$sqlBloqueio = "update Contrato set IdStatus='200', Obs = \"$local_Obs\" where IdLoja=$linContrato[IdLoja] and IdContrato=$linContrato[IdContrato]";
		$local_transaction[$tr_i]	=	mysql_query($sqlBloqueio,$con);
		$tr_i++;
	}
	
	// Expira a data de ativo agendado
	$sqlContrato = "select
						Contrato.IdLoja,
						Contrato.IdContrato,
						Contrato.IdStatus,
						Contrato.Obs,
						Servico.UrlRotinaDesbloqueio
					from
						Contrato,
						Servico
					where
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico and
						Contrato.IdStatus = 204 and
						concat(substring(Contrato.VarStatus,7,4),'-',substring(Contrato.VarStatus,4,2),'-',substring(Contrato.VarStatus,1,2)) <= curdate()";
	$resContrato = mysql_query($sqlContrato,$con);
	while($linContrato = mysql_fetch_array($resContrato)){		

		$local_Obs	=	date("d/m/Y H:i:s")." [automatico] - Mudou status para Ativo.\n$linContrato[Obs]";
		$local_Obs  = str_replace('"',"'",$local_Obs);		

		$sqlBloqueio = "update Contrato set IdStatus='200', Obs = \"$local_Obs\" where IdLoja=$linContrato[IdLoja] and IdContrato=$linContrato[IdContrato]";
		$local_transaction[$tr_i]	=	mysql_query($sqlBloqueio,$con);
		$tr_i++;
		
		$local_IdLoja		= $linContrato[IdLoja];
		$local_IdContrato	= $linContrato[IdContrato];

		include($Path."modulos/administrativo/".$linContrato[UrlRotinaDesbloqueio]);
	}
	
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;				
		}
	}
		
	if($local_transaction == true || $tr_i==0){
		$sql = "COMMIT;";
	}else{
		$sql = "ROLLBACK;";
	}
	mysql_query($sql,$con);
	
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	// Agendamento de Status de Contrato
	$sqlServicoAgendamento = "select
								IdLoja,
								IdServico,
								QtdMes,
								IdStatus,
								IdNovoStatus
							from
								ServicoAgendamento";
	$resServicoAgendamento = mysql_query($sqlServicoAgendamento,$con);
	while($linServicoAgendamento = mysql_fetch_array($resServicoAgendamento)){

		$DiaMes		= date("d");
		$MesAno		= incrementaMesReferencia(date("m-Y"),($linServicoAgendamento[QtdMes]*(-1)));
		$DiaMesTemp = ultimoDiaMes(substr($MesAno,0,2), substr($MesAno,3,4));

		if($DiaMesTemp < $DiaMes){
			$DiaMes = $DiaMesTemp;
		}

		$DataPassada = dataConv($DiaMes."/".$MesAno,"d/m/Y","Y-m-d");

		$sql2 = "select 
					* 
				from(
					select 
						Contrato.IdLoja,
						Contrato.IdContrato,
						Contrato.Obs,
						Servico.UrlRotinaCancelamento,
						Servico.UrlRotinaDesbloqueio,
						Servico.UrlRotinaBloqueio,
						ContratoStatus.DataAlteracao,
						IdMudancaStatus 
					from
						Contrato,
						ContratoStatus,
						Servico 
					where Contrato.IdLoja = $linServicoAgendamento[IdLoja] and
					Contrato.IdLoja = ContratoStatus.IdLoja and
					Contrato.IdLoja = Servico.IdLoja and
					Contrato.IdContrato = ContratoStatus.IdContrato and
					Contrato.IdServico = $linServicoAgendamento[IdServico] and
					ContratoStatus.IdStatus = $linServicoAgendamento[IdStatus] and
					Contrato.IdStatus = ContratoStatus.IdStatus and
					Contrato.IdServico = Servico.IdServico
					order by IdMudancaStatus desc
				) TblVirtual
				group by
					TblVirtual.IdLoja,TblVirtual.IdContrato";
		$res2 = mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			$lin2[DataAlteracao] = dataConv($lin2[DataAlteracao],"Y-m-d H:i:s","Y-m-d");
			$lin2[DataAlteracao] = str_replace("-","",$lin2[DataAlteracao]);
			$DataPassada = str_replace("-","",$DataPassada);
			if((int)$lin2[DataAlteracao] <= (int)$DataPassada){
				$sql4 ="select 
							Contrato.IdLoja,
							Contrato.IdContrato,
							Contrato.Obs,
							Servico.UrlRotinaCancelamento,
							Servico.UrlRotinaDesbloqueio,
							Servico.UrlRotinaBloqueio,
							ContratoStatus.DataAlteracao,
							IdMudancaStatus 
						from
							Contrato,
							ContratoStatus,
							Servico 
						where
							Contrato.IdLoja = $linServicoAgendamento[IdLoja] and
							Contrato.IdLoja = ContratoStatus.IdLoja and
							Contrato.IdLoja = Servico.IdLoja and
							Contrato.IdContrato = ContratoStatus.IdContrato and
							Contrato.IdContrato = $lin2[IdContrato] and
							Contrato.IdServico = $linServicoAgendamento[IdServico] and
							ContratoStatus.IdStatus = $linServicoAgendamento[IdStatus] and
							Contrato.IdStatus = ContratoStatus.IdStatus and
							Contrato.IdServico = Servico.IdServico 
						group by
							Contrato.IdLoja,
							ContratoStatus.IdContrato";
				$res4 = mysql_query($sql4,$con);
				while($lin4 = mysql_fetch_array($res4)){
					if($linServicoAgendamento[QtdMes] > 1){
						$meses = "meses";
					}elseif($linServicoAgendamento[QtdMes] == 1){
						$meses = "mês";
					}
					
					$local_Obs	=	date("d/m/Y H:i:s")." [automatico] - Agendamento realizado: ".getParametroSistema(69,$linServicoAgendamento[IdStatus])."[$linServicoAgendamento[QtdMes] $meses] > ".getParametroSistema(69,$linServicoAgendamento[IdNovoStatus]).".\n$lin4[Obs]";
					$local_Obs  = str_replace('"',"'",$local_Obs);	
					
					$sql3 = "update 
								Contrato 
							set 
								IdStatus='$linServicoAgendamento[IdNovoStatus]',
								Obs = \"$local_Obs\"
							where 
								IdLoja='$linServicoAgendamento[IdLoja]' and 
								IdContrato='$lin4[IdContrato]'";
					$local_transaction[$tr_i] = mysql_query($sql3,$con);
					$tr_i++;

					$IdLoja				= $linServicoAgendamento[IdLoja];
					$local_IdLoja		= $linServicoAgendamento[IdLoja];
					$local_IdContrato	= $lin4[IdContrato];

					// EXECUTA CANCELAMENTO
					if($linServicoAgendamento[IdNovoStatus] >= 1 && $linServicoAgendamento[IdNovoStatus] <= 199){

						$sql3 = "update Contrato set DataTermino=curdate(), DataUltimaCobranca=curdate() where IdLoja='$linServicoAgendamento[IdLoja]' and IdContrato='$lin4[IdContrato]'";
						$local_transaction[$tr_i] = mysql_query($sql3,$con);
						$tr_i++;

						if(file_exists($Path."modulos/administrativo/".$lin4[UrlRotinaCancelamento]) && $lin4[UrlRotinaCancelamento] != ''){
							include($Path."modulos/administrativo/".$lin4[UrlRotinaCancelamento]);
						}
					}

					// EXECUTA ATIVAÇÃO
					if($linServicoAgendamento[IdNovoStatus] >= 200 && $linServicoAgendamento[IdNovoStatus] <= 299){
						if(file_exists($Path."modulos/administrativo/".$lin4[UrlRotinaDesbloqueio]) && $lin4[UrlRotinaDesbloqueio] != ''){
							include($Path."modulos/administrativo/".$lin4[UrlRotinaDesbloqueio]);
						}
					}

					// EXECUTA BLOQUEIO
					if($linServicoAgendamento[IdNovoStatus] >= 300 && $linServicoAgendamento[IdNovoStatus] <= 399){
						if(file_exists($Path."modulos/administrativo/".$lin4[UrlRotinaBloqueio]) && $lin4[UrlRotinaBloqueio] != ''){
							include($Path."modulos/administrativo/".$lin4[UrlRotinaBloqueio]);
						}
					}
				}
			}
		}
	}	
	
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;				
		}
	}
		
	if($local_transaction == true || $tr_i==0){
		$sql = "COMMIT;";
	}else{
		$sql = "ROLLBACK;";
	}
	mysql_query($sql,$con);
?>
