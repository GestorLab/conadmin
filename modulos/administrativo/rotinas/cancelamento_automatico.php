<?
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
		
	$tr_i = 0;	

	// Filtro todos que tenham contratos que não estão cancelados
	$sqlContrato = "select
						Contrato.IdLoja,
						Contrato.IdContrato,
						Contrato.Obs,
						Servico.UrlRotinaCancelamento
					from
						Contrato,
						Servico
					where
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico and
						Contrato.DataTermino != '' and
						Contrato.DataTermino < curdate() and
						Contrato.IdStatus != 1 and
						Contrato.IdStatus != 101 and
						Contrato.IdStatus != 102";
	$resContrato = mysql_query($sqlContrato,$con);
	while($linContrato = mysql_fetch_array($resContrato)){
		
		$IdLoja		= $linContrato[IdLoja];
		$IdContrato = $linContrato[IdContrato];
		
		$local_Obs	= date("d/m/Y H:i:s")." [automatico] - Mudou status para Cancelado.";
		$BackupParametro = "";
		# BKP DE PARÂMETRO, SERVIÇO NORMAL
		$sqlContratoHis = "SELECT 
								ContratoParametro.IdServico,
								ContratoParametro.Valor,
								ServicoParametro.DescricaoParametroServico, 
								ServicoParametro.SalvarHistorico,
								ServicoParametro.IdTipoTexto,
								ServicoParametro.ExibirSenha
							FROM
								ContratoParametro,
								ServicoParametro 
							WHERE 
								ContratoParametro.IdLoja = '$linContrato[IdLoja]' AND 
								ContratoParametro.IdContrato = '$linContrato[IdContrato]' AND 
								ContratoParametro.IdLoja = ServicoParametro.IdLoja AND 
								ContratoParametro.IdServico = ServicoParametro.IdServico AND 
								ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico;";
		$resContratoHis = @mysql_query($sqlContratoHis, $con);
		
		while($linContratoHis = @mysql_fetch_array($resContratoHis)){
			if($linContratoHis[SalvarHistorico] == 1){
				$BackupParametro .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - $linContratoHis[DescricaoParametroServico]";
				
				if(($linContratoHis[IdTipoTexto] == 2 && $linContratoHis[ExibirSenha] == 1) || $linContratoHis[IdTipoTexto] != 2){
					$BackupParametro .=	": $linContratoHis[Valor]";
				}
			}
		}
		# BKP DE PARÂMETRO, SERVIÇO AUTOMATICO
		$sqlContratoHis = "SELECT 
								ContratoParametro.IdServico,
								ContratoParametro.Valor,
								ServicoParametro.DescricaoParametroServico, 
								ServicoParametro.SalvarHistorico,
								ServicoParametro.IdTipoTexto,
								ServicoParametro.ExibirSenha
							FROM
								ContratoAutomatico,
								ContratoParametro,
								ServicoParametro 
							WHERE 
								ContratoAutomatico.IdLoja = '$linContrato[IdLoja]' AND 
								ContratoAutomatico.IdContrato = '$linContrato[IdContrato]' AND 
								ContratoParametro.IdLoja = ContratoAutomatico.IdLoja AND 
								ContratoParametro.IdContrato = ContratoAutomatico.IdContratoAutomatico AND 
								ContratoParametro.IdLoja = ServicoParametro.IdLoja AND 
								ContratoParametro.IdServico = ServicoParametro.IdServico AND 
								ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico;";
		$resContratoHis = @mysql_query($sqlContratoHis, $con);
		
		while($linContratoHis = @mysql_fetch_array($resContratoHis)){
			if($linContratoHis[SalvarHistorico] == 1){
				$BackupParametro .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Parâmetro Serviço Auto. ($linContratoHis[IdServico]) - $linContratoHis[DescricaoParametroServico]";
				
				if(($linContratoHis[IdTipoTexto] == 2 && $linContratoHis[ExibirSenha] == 1) || $linContratoHis[IdTipoTexto] != 2){
					$BackupParametro .=	": $linContratoHis[Valor]";
				}
			}
		}
		
		if(!empty($BackupParametro)){
			$local_Obs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Concluído backup dos parâmetros do contrato.";
			$local_Obs .= $BackupParametro;
			$local_Obs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Iniciando backup dos parâmetros do contrato.";
		}
		
		$local_Obs .= "\n$linContrato[Obs]";
		$local_Obs	= str_replace('"',"'",$local_Obs);

		$sqlBloqueio = "update Contrato set IdStatus='1', Obs = \"$local_Obs\" where IdLoja=$linContrato[IdLoja] and IdContrato=$linContrato[IdContrato]";
		$local_transaction[$tr_i]	=	mysql_query($sqlBloqueio,$con);
		$tr_i++;

		if($linContrato[UrlRotinaCancelamento] != ''){
			$IdLoja				= $linContrato[IdLoja];
			$local_IdLoja		= $linContrato[IdLoja];
			$local_IdContrato	= $linContrato[IdContrato];

			if(file_exists($Path."modulos/administrativo/".$linContrato[UrlRotinaCancelamento])){
				include($Path."modulos/administrativo/".$linContrato[UrlRotinaCancelamento]);
			}
		}

		enviarEmailCancelamentoServico($IdLoja, $IdContrato, "");
	}

	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;				
		}
	}
		
	if($local_transaction == true){
		$sql = "COMMIT;";
	}else{
		$sql = "ROLLBACK;";
	}
	mysql_query($sql,$con);
?>
