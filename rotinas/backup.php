<?
	include($Path.'classes/zip.lib/zip.lib.php');

	$PathRaiz = substr($Path,0,strlen($Path)-4);

	$BackupAltenativo = true;
	$DataHoraInicio = date("Y-m-d H:i:s");

	$sqlLog = "insert into Backup set
					DataHoraInicio = '$DataHoraInicio',
					Erro		   = 0";
	mysql_query($sqlLog,$con);

	$sqlLog = "update Backup set Erro=1 where DataHoraInicio < '$DataHoraInicio' and DataHoraConclusao is NULL";
	mysql_query($sqlLog,$con);

	$Versao = getParametroSistema(83,11);
	$Versao++;

	if($Versao > getParametroSistema(83,10)){
		$Versao = 1;
	}

	$sqlVersao = "update ParametroSistema set  ValorParametroSistema='$Versao' where IdGrupoParametroSistema='83' and IdParametroSistema='11'";
	mysql_query($sqlVersao, $con);
	
	$file_name = "backup_conadmin_".$_SESSION["IdLicenca"]."_".$Versao;

	$iDB = 0;
	
	# Backup ConAdmin
	$DB[$iDB][server]	= $con_bd[server];
	$DB[$iDB][login]	= $con_bd[login];
	$DB[$iDB][senha]	= $con_bd[senha];
	$DB[$iDB][banco]	= $con_bd[banco];

	# Backup Radius
	$sql = "select
				IdLoja,
				IdCodigoInterno,
				ValorCodigoInterno
			from
				CodigoInterno
			where
				IdGrupoCodigoInterno = 10000 and
				IdCodigoInterno < 20 and
				ValorCodigoInterno != ''
			order by
				IdLoja,
				IdCodigoInterno";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$iDB++;

		$Radius = explode("\n",$lin[ValorCodigoInterno]);

		$DB[$iDB][server]	= trim($Radius[0]);
		$DB[$iDB][login]	= trim($Radius[1]);
		$DB[$iDB][senha]	= trim($Radius[2]);
		$DB[$iDB][banco]	= trim($Radius[3]);
	}

	# Cria o diretório da versão
	@mkdir($PathRaiz."backup/");
	@mkdir($PathRaiz."backup/".$file_name);

	$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Início do Backup.";

	$sqlQtdBackpHoje = "select
							count(*) Qtd
						from
							Backup
						where
							DataHoraInicio >= curdate()and
							DataHoraConclusao != '' and
							Erro = 0";
	$resQtdBackpHoje = mysql_query($sqlQtdBackpHoje,$con);
	$linQtdBackpHoje = mysql_fetch_array($resQtdBackpHoje);

	$Erro = 0;

	# Gera o backup
	for($i=0; $i<count($DB); $i++){
		
		$DB[$i][file] = $PathRaiz."backup/".$file_name."/".$file_name."_".$DB[$i][server]."-".$DB[$i][banco].".sql";

		if($linQtdBackpHoje[Qtd] >= 1){
			$BackupAltenativo = false;
		}

		// Cep
		if($i == 0){
			$DB[$i][ignore] = "--ignore-table ".$DB[$i][banco].".Cep ";
		}

		// radacctJornal
		if($i >= 1 && $linQtdBackpHoje[Qtd] >= 1){
			$DB[$i][ignore] = "--ignore-table ".$DB[$i][banco].".radacctJornal ";
		}

		$ii	   = 0;
		$iiMax = 15;
		while(!file_exists($DB[$i][file]) && $ii<$iiMax){
			@system(getParametroSistema(6,6)." -h ".$DB[$i][server]." --user='".$DB[$i][login]."' --password='".$DB[$i][senha]."' ".$DB[$i][ignore].$DB[$i][banco]." > ".$DB[$i][file]);

			mysql_close($con);
			include($Path.'files/conecta.php');
			
			$ii++;

			if(!file_exists($DB[$i][file])){
				sleep(240);
			}else{
				break;
			}
		}

		if(!file_exists($DB[$i][file])){
			$Erro = 1;
		}
			
		mysql_close($con);
		include($Path.'files/conecta.php');

		if(file_exists($DB[$i][file])){

			$FileSize = calculaTamanhoArquivo($DB[$i][file]);
			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Arquivo temporário SQL de backup gerado ".$DB[$i][server]."-".$DB[$i][banco]." ($FileSize).\n".$LogProcessamento;

		}else{
			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Erro ao gerar SQL de backup ".$DB[$i][server]."-".$DB[$i][banco].".\n".$LogProcessamento;
			$Erro = 1;
		}
	}
	
	$FileCompress = $file_name.".tar.bz2";

	@unlink($PathRaiz."backup/".$FileCompress);

	if(date("w") == 0 && $BackupAltenativo == true){
		// Backup completo
		system("cp -R -P ".$PathRaiz."www ".$PathRaiz."backup/".$file_name);
		system("rm -r ".$PathRaiz."backup/".$file_name."/www/temp");
	}else{
		// Backup dos Anexos
		system("cp -R -P ".$PathRaiz."www/modulos/administrativo/anexos ".$PathRaiz."backup/".$file_name);

		// Backup das Remessas
		system("cp -R -P ".$PathRaiz."www/modulos/administrativo/remessa ".$PathRaiz."backup/".$file_name);
				
		// Backup das Retornos
		system("cp -R -P ".$PathRaiz."www/modulos/administrativo/retorno ".$PathRaiz."backup/".$file_name);
		system("rm -r ".$PathRaiz."backup/".$file_name."/retorno/tipo_retorno");
	}
	
	// Compacta
	system("tar -jcf ".$PathRaiz."backup/$FileCompress ".$PathRaiz."backup/".$file_name);

	$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Backup de arquivos diversos.\n".$LogProcessamento;
	
	$FileSize = 0;

	if(@filesize($PathRaiz."backup/".$FileCompress)>0){
		$FileSize = calculaTamanhoArquivo($PathRaiz."backup/".$FileCompress);
		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Compressão do arquivo SQL realizada. ($FileSize).\n".$LogProcessamento;
	}else{
		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Erro ao compactar arquivo.\n".$LogProcessamento;
	}
			
	mysql_close($con);
	include($Path.'files/conecta.php');

	$sqlLog = "update Backup set Size='$FileSize' where DataHoraInicio='$DataHoraInicio' ";
	mysql_query($sqlLog,$con);

	// Deleta o arquivo gerado SQL
	@system("rm -r ".$PathRaiz."backup/".$file_name);
	$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Arquivo temporário SQL removido.\n".$LogProcessamento;

	$sql = "select
				IdParametroSistema
			from
				ParametroSistema
			where
				IdGrupoParametroSistema = 83 and
				IdParametroSistema > 10 and
				IdParametroSistema %10 = 0 and
				ValorParametroSistema != ''
			order by
				IdParametroSistema";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
	
		$IdBase = $lin[IdParametroSistema];

		// sFTP
		if(getParametroSistema(83,$IdBase+5) == 1){

			include($Path.'classes/sftp/sftp.php');

			// Tratamento das pastas
			$pasta = getParametroSistema(83,$IdBase+3);
			if($pasta != ''){
				if(substr($pasta,0,1) != '/'){	
					$pasta = "/".$pasta;
				}
				if(substr($pasta,strlen($pasta)-1,1) != '/'){
					$pasta = $pasta."/";
				}
			}

		    $sftp = new SFTPConnection(getParametroSistema(83,$IdBase), getParametroSistema(83,$IdBase+4));
		    $sftp->login(getParametroSistema(83,$IdBase+1), getParametroSistema(83,$IdBase+2));

			if($sftp->erro == true){
				$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Conexão sFTP em ".getParametroSistema(83,$IdBase+1)."@".getParametroSistema(83,$IdBase)." não realizado.\n".$LogProcessamento;
				$Erro = 1;
			}else{
				$sftp->uploadFile($PathRaiz."backup/".$FileCompress, $pasta.$FileCompress);
				if(!$sftp->erro){
					$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Backup sFTP em ".getParametroSistema(83,$IdBase+1)."@".getParametroSistema(83,$IdBase)." realizado com sucesso.\n".$LogProcessamento;

					@mysql_close($con);
					include($Path.'files/conecta.php');

					$sqlDataUltimoBackup = "update ParametroSistema set  ValorParametroSistema='$DataHoraInicio' where IdGrupoParametroSistema='83' and IdParametroSistema='12'";
					mysql_query($sqlDataUltimoBackup,$con);
				}else{
					$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Backup sFTP em ".getParametroSistema(83,$IdBase+1)."@".getParametroSistema(83,$IdBase)." não realizado.\n".$LogProcessamento;
					$Erro = 1;
				}
			}
		}else{

			$conn_id = @ftp_connect(getParametroSistema(83,$IdBase),getParametroSistema(83,$IdBase+4));
				
			mysql_close($con);
			include($Path.'files/conecta.php');

			if($login_result = @ftp_login($conn_id, getParametroSistema(83,$IdBase+1),getParametroSistema(83,$IdBase+2))){
				// tenta mudar para o diretorio
				if(getParametroSistema(83,$IdBase+3) != ''){
					if(!@ftp_chdir($conn_id, getParametroSistema(83,$IdBase+3))){
						$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Impossível acessar o diretório: ".getParametroSistema(83,$IdBase+3)."\n".$LogProcessamento;
						$Erro = 1;
					}
				}

				// Apago a versão antiga do arquivo
				@ftp_delete($conn_id, $FileCompress);

				$put = @ftp_put($conn_id, $FileCompress, $PathRaiz."backup/".$FileCompress ,FTP_BINARY);

				if(!$put){
					@ftp_pasv($conn_id, true);
					$put = @ftp_put($conn_id, $FileCompress, $PathRaiz."backup/".$FileCompress ,FTP_BINARY);
				}
					
				if($put){

					@mysql_close($con);
					include($Path.'files/conecta.php');

					$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Backup FTP em ".getParametroSistema(83,$IdBase+1)."@".getParametroSistema(83,$IdBase)." realizado com sucesso.\n".$LogProcessamento;

					$sqlDataUltimoBackup = "update ParametroSistema set  ValorParametroSistema='$DataHoraInicio' where IdGrupoParametroSistema='83' and IdParametroSistema='12'";
					mysql_query($sqlDataUltimoBackup,$con);

				}else{

					@mysql_close($con);
					include($Path.'files/conecta.php');

					$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Backup FTP em ".getParametroSistema(83,$IdBase+1)."@".getParametroSistema(83,$IdBase)." não realizado.\n".$LogProcessamento;
					$Erro = 1;
				}
			}else{
				$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Conexão FTP em ".getParametroSistema(83,$IdBase+1)."@".getParametroSistema(83,$IdBase)." não realizado.\n".$LogProcessamento;
				$Erro = 1;
			}
			@ftp_close($conn_id);
		}
	}

	$DataHoraConclusao		= date("Y-m-d H:i:s");

	if($BackupAltenativo == true){
		
		include($Path.'files/conecta_conadmin.php');

		$sql = "select
					Endereco,
					Usuario,
					Senha,
					Porta
				from
					(select ValorParametroSistema Endereco from ParametroSistema where IdGrupoParametroSistema = 2 and IdParametroSistema = 1) Endereco,
					(select ValorParametroSistema Usuario from ParametroSistema where IdGrupoParametroSistema = 2 and IdParametroSistema = 2) Usuario,
					(select ValorParametroSistema Senha from ParametroSistema where IdGrupoParametroSistema = 2 and IdParametroSistema = 3) Senha,
					(select ValorParametroSistema Porta from ParametroSistema where IdGrupoParametroSistema = 2 and IdParametroSistema = 4) Porta";
		$res = mysql_query($sql,$conConAdmin);
		if($lin = mysql_fetch_array($res)){
			// Envia arquivo para ftp de backup da CNT Sistemas
			$dados_ftp2[0] = $lin[Endereco];
			$dados_ftp2[1] = $lin[Usuario];
			$dados_ftp2[2] = $lin[Senha];
			$dados_ftp2[3] = $lin[Porta];

			$conn_id2 = @ftp_connect($dados_ftp2[0],$dados_ftp2[3]);
			if($login_result2 = @ftp_login($conn_id2, $dados_ftp2[1],$dados_ftp2[2])){
				// Apago a versão antiga do arquivo
				@ftp_delete($conn_id2, $file_name.".zip");
				@ftp_delete($conn_id2, $FileCompress);
				if(!@ftp_put($conn_id2, $FileCompress, $PathRaiz."backup/".$FileCompress ,FTP_BINARY)){
					@ftp_pasv($conn_id2, true);
					@ftp_put($conn_id2, $FileCompress, $PathRaiz."backup/".$FileCompress ,FTP_BINARY);
				}
			}
			@ftp_close($conn_id2);
		}
		mysql_close($conConAdmin);
	}

	mysql_close($con);
	include($Path.'files/conecta.php');

	$sqlLog = "update Backup set DataHoraConclusao='$DataHoraConclusao',  Log='$LogProcessamento', Erro = $Erro where DataHoraInicio='$DataHoraInicio'";
	mysql_query($sqlLog,$con);

	enviaLogBackup($DataHoraInicio);
?>