<?
	include('../../../files/conecta.php');
	
	$local_IdBackupConta = $_GET['IdBackupConta'];
	
	if($local_IdBackupConta != ''){
		$local_IdBackupContaTemp = (int)(($local_IdBackupConta+1)*10);
	}
	
	$i = 0;
	$sql = "SELECT 
				ValorParametroSistema,
				LoginCriacao,
				DataCriacao,
				LoginAlteracao,
				DataAlteracao
			FROM 
				ParametroSistema
			WHERE 
				IdGrupoParametroSistema = '83' AND
				IdParametroSistema > 19 AND 
				IdParametroSistema >= $local_IdBackupContaTemp AND 
				IdParametroSistema <= ".($local_IdBackupContaTemp+9).";";
	$res = mysql_query($sql,$con);
	
	if(@mysql_num_rows($res) > 0){
		while($lin = @mysql_fetch_array($res)){
			$local_ParametroValor[$i] = trim($lin[ValorParametroSistema]);
			$i++;
		}
		
		$local_ServidorEndereco	= $local_ParametroValor[0];
		$local_ServidorUsuario	= $local_ParametroValor[1];
		$local_ServidorSenha	= $local_ParametroValor[2];
		$local_BackupCaminho	= $local_ParametroValor[3];
		$local_ServidorPorta	= $local_ParametroValor[4];
		$local_RemoteFile		= "backup_teste.temp";
		$local_File				= "../../../temp/$local_RemoteFile";
		$local_Handle 			= @fopen($local_File,"w");
		$local_STR				= "## - TESTE DA CONTA DE BACKUP N° $local_IdBackupConta - ##\r\n\r\nArquivo criado pelo sistema ConAdmin para fins de testes da conta N° $local_IdBackupConta.";
		
		if(@fwrite($local_Handle ,$local_STR)){
			@fclose($local_Handle );
			
			if($con_FTP = @ftp_connect($local_ServidorEndereco,$local_ServidorPorta)){
				if($res_FTP = @ftp_login($con_FTP,$local_ServidorUsuario,$local_ServidorSenha)){
					$local_BackupCaminho .= "/".$local_RemoteFile;
					
					if(@ftp_put($con_FTP,$local_BackupCaminho,$local_File,FTP_ASCII)){
						if(!@ftp_delete($con_FTP, $local_BackupCaminho)){
							## ERRO, USUÁRIO NÃO POSSUI PERMISSÃO PARA EXCLUIR ARQUIVO ##
							echo 158;
						} else{
							## TESTE DA CONTA DE BACKUP REALIZADO COM SUCESSO ##
							echo 159;
						}
					} else{
						## ERRO, USUÁRIO NÃO POSSUI PERMISSÃO PARA FAZER UPLOAD ##
						echo 157;
					}
				} else{
					## ERRO, NÃO FOI POSSÍVEL AUTENTICAR O USUÁRIO ##
					echo 156;
				}
				
				@ftp_close($con_FTP);
				@unlink($local_File);
			} else{
				## ERRO, NÃO FOI POSSÍVEL CONECTAR AO SERVIDOR PELO ENDEREÇO FORNECIDO E A PORTA ##
				echo 155;
			}
		}
	}
?>