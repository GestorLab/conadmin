<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	} else{
		$sql = "SELECT
					DescricaoContaDebito,
					IdLocalCobranca,
					NumeroAgencia,
					DigitoAgencia,
					NumeroConta,
					DigitoConta,
					IdStatus,
					Obs
				FROM
					PessoaContaDebito
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdPessoa = '$local_IdPessoa' AND
					IdContaDebito = '$local_IdContaDebito';";
		$res = mysql_query($sql, $con);
		$lin = @mysql_fetch_array($res);
		$lf = '';
		$TempObsServico = str_replace("'", "\'", $lin[Obs]);
		$ObsServico = '';
		
		if($lin[DescricaoContaDebito] != $local_DescricaoContaDebito){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteraзгo da Descriзгo [$lin[DescricaoContaDebito] > $local_DescricaoContaDebito].";
			$lf = "\n";
		}
		
		if($local_IdLocalCobranca == ""){
			$local_IdLocalCobranca = $local_IdLocalCobrancaTemp;
		}
		
		if($lin[IdLocalCobranca] != $local_IdLocalCobranca){
			$sql0 = "SELECT
						DescricaoLocalCobranca
					FROM
						LocalCobranca
					WHERE
						IdLoja = '$local_IdLoja' AND
						IdLocalCobranca = '$lin[IdLocalCobranca]';";
			$res0 = mysql_query($sql0, $con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "SELECT
						DescricaoLocalCobranca
					FROM
						LocalCobranca
					WHERE
						IdLoja = '$local_IdLoja' AND
						IdLocalCobranca = '$local_IdLocalCobranca';";
			$res1 = mysql_query($sql1, $con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteraзгo de Local Cobranзa [$lin0[DescricaoLocalCobranca] > $lin1[DescricaoLocalCobranca]].";
			$lf = "\n";
		}
		
		if($lin[NumeroAgencia] != $local_NumeroAgencia){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteraзгo do Nъmero da Agкncia [$lin[NumeroAgencia] > $local_NumeroAgencia].";
			$lf = "\n";
		}
		
		if($lin[DigitoAgencia] != $local_DigitoAgencia){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteraзгo do Dнg. da Agкncia [$lin[DigitoAgencia] > $local_DigitoAgencia].";
			$lf = "\n";
		}
		
		if($lin[NumeroConta] != $local_NumeroConta){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteraзгo do Nъmero da Conta [$lin[NumeroConta] > $local_NumeroConta].";
			$lf = "\n";
		}
		
		if($lin[DigitoConta] != $local_DigitoConta){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteraзгo do Dнg. da Conta [$lin[DigitoConta] > $local_DigitoConta].";
			$lf = "\n";
		}
		
		if($lin[IdStatus] != $local_IdStatus){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteraзгo do Status [".getParametroSistema(188, $lin[IdStatus])." > ".getParametroSistema(188, $local_IdStatus)."].";
			$lf = "\n";
		}
		
		$ObsServico = str_replace("'", "\'", $ObsServico);
		
		if($TempObsServico != '' && $ObsServico != ''){
			$ObsServico .= "\n".$TempObsServico;
		} else{
			$ObsServico .= $TempObsServico;
		}
		
		$sql = "UPDATE PessoaContaDebito SET
					IdPessoa				='$local_IdPessoa',
					DescricaoContaDebito	='$local_DescricaoContaDebito',
					IdLocalCobranca			='$local_IdLocalCobranca',
					NumeroAgencia			='$local_NumeroAgencia',
					DigitoAgencia			='$local_DigitoAgencia',
					NumeroConta				='$local_NumeroConta',
					DigitoConta				='$local_DigitoConta',
					Obs						='$ObsServico',
					IdStatus				='$local_IdStatus',
					LoginAlteracao			='$local_Login',
					DataAlteracao			= concat(curdate(),' ',curtime())
				WHERE 
					IdLoja = '$local_IdLoja' AND
					IdPessoa = '$local_IdPessoa' AND
					IdContaDebito = '$local_IdContaDebito';";
					
		
		if(mysql_query($sql, $con) == true){
			$local_Erro = 4;			
		} else{
			$local_Erro = 5;
		}
	}
?>