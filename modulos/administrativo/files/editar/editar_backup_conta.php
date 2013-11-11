<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	} else{
		$local_IdBackupContaTemp = (int)(($local_IdBackupConta+1)*10);		
		
		
		//Verificação do Histórico Obs. valor Endereço FTP
		$sqlHistorico = "SELECT
							*
						FROM
							ParametroSistema
						WHERE
							IdGrupoParametroSistema = '83' AND
							IdParametroSistema = '$local_IdBackupContaTemp'";
		$resHistorico = mysql_query($sqlHistorico, $con);
		$linHistorico = mysql_fetch_array($resHistorico);		
		
		if($linHistorico[ValorParametroSistema] != $local_ServidorEndereco){
			$local_HistoricoObs .= date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Endereço do Servidor (FTP) [".$linHistorico[ValorParametroSistema]." > ".$local_ServidorEndereco."]\n";
		}
		
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
	 	$sql = "UPDATE ParametroSistema SET
					DescricaoParametroSistema	= 'Backup - FTP - Endereço FTP',
					ValorParametroSistema		= '$local_ServidorEndereco',
					LoginAlteracao				= '$local_Login', 
					DataAlteracao				= (concat(curdate(),' ',curtime()))
				WHERE
					IdGrupoParametroSistema = '83' AND
					IdParametroSistema = '$local_IdBackupContaTemp';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		$local_IdBackupContaTemp++;
		
		//Verificação do Histórico Obs. valor Usuário FTP
		$sqlHistorico = "SELECT
							*
						FROM
							ParametroSistema
						WHERE
							IdGrupoParametroSistema = '83' AND
							IdParametroSistema = '$local_IdBackupContaTemp'";
		$resHistorico = mysql_query($sqlHistorico, $con);
		$linHistorico = mysql_fetch_array($resHistorico);		
		
		if($linHistorico[ValorParametroSistema] != $local_ServidorUsuario){
			$local_HistoricoObs .= date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Usuário (FTP) [".$linHistorico[ValorParametroSistema]." > ".$local_ServidorUsuario."]\n";
		}
		
	 	$sql = "UPDATE ParametroSistema SET
					DescricaoParametroSistema	= 'Backup - FTP - Usuário FTP',
					ValorParametroSistema		= '$local_ServidorUsuario',
					LoginAlteracao				= '$local_Login', 
					DataAlteracao				= (concat(curdate(),' ',curtime()))
				WHERE
					IdGrupoParametroSistema = '83' AND
					IdParametroSistema = '$local_IdBackupContaTemp';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		$local_IdBackupContaTemp++;
		
		//Verificação do Histórico Obs. valor Senha FTP
		$sqlHistorico = "SELECT
							*
						FROM
							ParametroSistema
						WHERE
							IdGrupoParametroSistema = '83' AND
							IdParametroSistema = '$local_IdBackupContaTemp'";
		$resHistorico = mysql_query($sqlHistorico, $con);
		$linHistorico = mysql_fetch_array($resHistorico);		
		
		if($linHistorico[ValorParametroSistema] != $local_ServidorSenha){
			$local_HistoricoObs .= date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Senha [".$linHistorico[ValorParametroSistema]." > ".$local_ServidorSenha."]\n";
		}
		
	 	$sql = "UPDATE ParametroSistema SET
					DescricaoParametroSistema	= 'Backup - FTP - Senha FTP',
					ValorParametroSistema		= '$local_ServidorSenha',
					LoginAlteracao				= '$local_Login', 
					DataAlteracao				= (concat(curdate(),' ',curtime()))
				WHERE
					IdGrupoParametroSistema = '83' AND
					IdParametroSistema = '$local_IdBackupContaTemp';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		$local_IdBackupContaTemp++;
		
		//Verificação do Histórico Obs. valor Caminho (Pasta)
		$sqlHistorico = "SELECT
							*
						FROM
							ParametroSistema
						WHERE
							IdGrupoParametroSistema = '83' AND
							IdParametroSistema = '$local_IdBackupContaTemp'";
		$resHistorico = mysql_query($sqlHistorico, $con);
		$linHistorico = mysql_fetch_array($resHistorico);		
		
		if($linHistorico[ValorParametroSistema] != $local_BackupCaminho){
			$local_HistoricoObs .= date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Caminho (Pasta) [".$linHistorico[ValorParametroSistema]." > ".$local_BackupCaminho."]\n";
		}
		
	 	$sql = "UPDATE ParametroSistema SET
					DescricaoParametroSistema	= 'Backup - FTP - Caminho (Pasta)',
					ValorParametroSistema		= '$local_BackupCaminho',
					LoginAlteracao				= '$local_Login', 
					DataAlteracao				= (concat(curdate(),' ',curtime()))
				WHERE
					IdGrupoParametroSistema = '83' AND
					IdParametroSistema = '$local_IdBackupContaTemp';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		$local_IdBackupContaTemp++;
		
		//Verificação do Histórico Obs. valor Porta
		$sqlHistorico = "SELECT
							*
						FROM
							ParametroSistema
						WHERE
							IdGrupoParametroSistema = '83' AND
							IdParametroSistema = '$local_IdBackupContaTemp'";
		$resHistorico = mysql_query($sqlHistorico, $con);
		$linHistorico = mysql_fetch_array($resHistorico);		
		
		if($linHistorico[ValorParametroSistema] != $local_ServidorPorta){
			$local_HistoricoObs .= date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Porta [".$linHistorico[ValorParametroSistema]." > ".$local_ServidorPorta."]\n";
		}
		
	 	$sql = "UPDATE ParametroSistema SET
					DescricaoParametroSistema	= 'Backup - FTP - Porta',
					ValorParametroSistema		= '$local_ServidorPorta',
					LoginAlteracao				= '$local_Login', 
					DataAlteracao				= (concat(curdate(),' ',curtime()))
				WHERE
					IdGrupoParametroSistema = '83' AND
					IdParametroSistema = '$local_IdBackupContaTemp';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		$local_IdBackupContaTemp++;
		
		//Verificação para deixar o antigo histórico e inserir o atual.
		$sqlHistorico = "SELECT
							*
						FROM
							ParametroSistema
						WHERE
							IdGrupoParametroSistema = '83' AND
							IdParametroSistema = '$local_IdBackupContaTemp'";
		$resHistorico = mysql_query($sqlHistorico, $con);
		$linHistorico = mysql_fetch_array($resHistorico);		
		
		if($linHistorico[ValorParametroSistema] != $local_HistoricoObs){
			$local_HistoricoObs .= $linHistorico[ValorParametroSistema];
		}
		
		$sql = "UPDATE ParametroSistema SET
					DescricaoParametroSistema	= 'Backup - FTP - Histórico Obs.',
					ValorParametroSistema		= '$local_HistoricoObs',
					LoginAlteracao				= '$local_Login', 
					DataAlteracao				= (concat(curdate(),' ',curtime()))
				WHERE
					IdGrupoParametroSistema = '83' AND
					IdParametroSistema = $local_IdBackupContaTemp ;";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
			
		for($i = 0; $i < $tr_i; $i++){
			if(!$local_transaction[$i]){
				$local_transaction = false;
				break;
			} elseif($i == ($tr_i-1)){
				$local_transaction = true;
			}
		}

		if($local_transaction){
			$sql = "COMMIT;";
			$local_Erro = 4;# MENSAGEM DE ALTERAÇÃO POSITIVA
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;# MENSAGEM DE ALTERAÇÃO NEGATIVA
		}
		
		@mysql_query($sql,$con);
	}
?>