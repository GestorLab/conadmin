<?
	if(permissaoSubOperacao($localModulo, $localOperacao, "U") == false) {
		$local_Erro = 2;
	} else {
		if($local_PercentualDesconto != '') {
			$local_PercentualDesconto = str_replace(array('.', ','), array('', '.'), $local_PercentualDesconto);
		}
		
		if($local_TaxaMudancaStatus != '') {
			$local_TaxaMudancaStatus = str_replace(array('.', ','), array('', '.'), $local_TaxaMudancaStatus);
		}
		
		$sql = "START TRANSACTION;";
		@mysql_query($sql, $con);
		$tr_i = 0;
		$sql = "SELECT
					Servico.Obs,
					ServicoMascaraStatus.PercentualDesconto,
					ServicoMascaraStatus.TaxaMudancaStatus,
					ServicoMascaraStatus.QtdMinimaDia
				FROM
					Servico,
					ServicoMascaraStatus
				WHERE
					Servico.IdLoja = '$local_IdLoja' AND
					Servico.IdServico = '$local_IdServico' AND
					Servico.IdLoja = ServicoMascaraStatus.IdLoja AND
					Servico.IdServico = ServicoMascaraStatus.IdServico AND
					ServicoMascaraStatus.IdStatus = '$local_IdStatus';";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		$TempObsServico = str_replace("'", "\'", $lin[Obs]);
		$ObsServico = '';
		$lf = '';
		
		$local_Status = getParametroSistema(69, $local_IdStatus);
		
		if($lin[PercentualDesconto] != $local_PercentualDesconto) {
			$ObsServico .= $lf . date("d/m/Y H:i:s") . " [" . $local_Login . "] - Mascará Status " . $local_Status . " - Alteração de Percentual Desconto (%) [" . str_replace('.', ',', $lin[PercentualDesconto]) . " > " . str_replace('.', ',', $local_PercentualDesconto) . "].";
			$lf = "\n";
		}
		
		if($lin[TaxaMudancaStatus] != $local_TaxaMudancaStatus) {
			$ObsServico .= $lf . date("d/m/Y H:i:s") . " [" . $local_Login . "] - Mascará Status " . $local_Status . " - Alteração de Taxa de Mudança (" . getParametroSistema(5,1) . ") [" . str_replace('.', ',', $lin[TaxaMudancaStatus]) . " > " . str_replace('.', ',', $local_TaxaMudancaStatus) . "].";
			$lf = "\n";
		}
		
		if($TempObsServico != '' && $ObsServico != '') {
			$ObsServico .= "\n" . $TempObsServico;
		} else {
			$ObsServico .= $TempObsServico;
		}
		
		$sql = "UPDATE Servico SET
					Obs				= '$ObsServico',
					LoginAlteracao	= '$local_Login',
					DataAlteracao	= concat(curdate(),' ',curtime())
				WHERE 
					IdLoja = '$local_IdLoja' AND
					IdServico = '$local_IdServico';";
		$local_transaction[$tr_i] = mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "UPDATE ServicoMascaraStatus SET	
					PercentualDesconto	= '$local_PercentualDesconto',
					TaxaMudancaStatus	= '$local_TaxaMudancaStatus',
					QtdMinimaDia		= '$local_QtdMinimaDia',
					LoginAlteracao		= '$local_Login',
					DataAlteracao		= concat(curdate(),' ',curtime())
				WHERE
					IdLoja = '$local_IdLoja' and
					IdServico = '$local_IdServico' and
					IdStatus = '$local_IdStatus'"; 
		$local_transaction[$tr_i] = mysql_query($sql, $con);
		$tr_i++;
		
		for($i = 0; $i < $tr_i; $i++) {
			if(!$local_transaction[$i]) {
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction) {
			$sql = "COMMIT;";
			$local_Acao = 'alterar';
			$local_Erro = 4;			// Mensagem de Inserção Positiva
		} else {
			$sql = "ROLLBACK;";
			$local_Acao = 'inserir';
			$local_Erro = 5;			// Mensagem de Inserção Negativa
		}
		
		@mysql_query($sql, $con);
	}
?>