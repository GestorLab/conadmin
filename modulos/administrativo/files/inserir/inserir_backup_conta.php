<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I")){
		$local_Erro = 2;
	} else{
		# SQL PARA OBTER O PROXIMO ID
		$sql = "SELECT
					MAX(IdParametroSistema) IdBackupConta					
				FROM 
					ParametroSistema 
				WHERE 
					IdGrupoParametroSistema = 83 AND 
					IdParametroSistema > 19;";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdBackupConta] != NULL){
			$local_IdBackupContaTemp = ((int)($lin[IdBackupConta]/10)+1)*10;
		} else{
			$local_IdBackupContaTemp = 20;
		}
		
		$local_IdBackupConta = (int)(($local_IdBackupContaTemp/10)-1);		
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
	 	$sql = "INSERT INTO ParametroSistema SET
					IdGrupoParametroSistema		= '83', 
					IdParametroSistema			= '$local_IdBackupContaTemp',
					DescricaoParametroSistema	= 'Backup - FTP - Endereço FTP',
					ValorParametroSistema		= '$local_ServidorEndereco',
					LoginCriacao				= '$local_Login', 
					DataCriacao					= (concat(curdate(),' ',curtime()));";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;		
		
		$local_IdBackupContaTemp++;
	 	$sql = "INSERT INTO ParametroSistema SET
					IdGrupoParametroSistema		= '83', 
					IdParametroSistema			= '$local_IdBackupContaTemp',
					DescricaoParametroSistema	= 'Backup - FTP - Usuário FTP',
					ValorParametroSistema		= '$local_ServidorUsuario',
					LoginCriacao				= '$local_Login', 
					DataCriacao					= (concat(curdate(),' ',curtime()));";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		$local_IdBackupContaTemp++;
	 	$sql = "INSERT INTO ParametroSistema SET
					IdGrupoParametroSistema		= '83', 
					IdParametroSistema			= '$local_IdBackupContaTemp',
					DescricaoParametroSistema	= 'Backup - FTP - Senha FTP',
					ValorParametroSistema		= '$local_ServidorSenha',
					LoginCriacao				= '$local_Login', 
					DataCriacao					= (concat(curdate(),' ',curtime()));";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		$local_IdBackupContaTemp++;
	 	$sql = "INSERT INTO ParametroSistema SET
					IdGrupoParametroSistema		= '83', 
					IdParametroSistema			= '$local_IdBackupContaTemp',
					DescricaoParametroSistema	= 'Backup - FTP - Caminho (Pasta)',
					ValorParametroSistema		= '$local_BackupCaminho',
					LoginCriacao				= '$local_Login', 
					DataCriacao					= (concat(curdate(),' ',curtime()));";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		$local_IdBackupContaTemp++;
	 	$sql = "INSERT INTO ParametroSistema SET
					IdGrupoParametroSistema		= '83', 
					IdParametroSistema			= '$local_IdBackupContaTemp',
					DescricaoParametroSistema	= 'Backup - FTP - Porta',
					ValorParametroSistema		= '$local_ServidorPorta',
					LoginCriacao				= '$local_Login', 
					DataCriacao					= (concat(curdate(),' ',curtime()));";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
			
		$local_IdBackupContaTemp++;
	 	$sql = "INSERT INTO ParametroSistema SET
					IdGrupoParametroSistema		= '83', 
					IdParametroSistema			= '$local_IdBackupContaTemp',
					DescricaoParametroSistema	= 'Backup - FTP - Histórico Obs.',
					ValorParametroSistema		= '$local_HistoricoObs' ,
					LoginCriacao				= '$local_Login', 
					DataCriacao					= (concat(curdate(),' ',curtime()));";
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
			$local_Acao = "alterar";
			$local_Erro = 3;			# MENSAGEM DE INSERÇÃO POSITIVA
		} else{
			$sql = "ROLLBACK;";
			$local_Acao = "inserir";
			$local_Erro = 8;			# MENSAGEM DE INSERÇÃO NEGATIVA
		}
		
		@mysql_query($sql,$con);
	}
?>