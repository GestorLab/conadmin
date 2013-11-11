<?
	$localModulo	= 1;
	$localOperacao	= 172;
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../../../rotinas/verifica.php');
	
	$local_IdBackupConta	= $_GET['IdBackupConta'];
	
	if(!permissaoSubOperacao($localModulo,$localOperacao,"D")){
		echo $local_Erro = 2;
	} else{	
		$local_IdBackupConta = (int)(($local_IdBackupConta+1)*10);
		
		$sql = "DELETE FROM 
					ParametroSistema 
				WHERE 
					IdGrupoParametroSistema = '83' AND 
					IdParametroSistema >= '$local_IdBackupConta' AND
					IdParametroSistema <= '".($local_IdBackupConta+9)."';";
		
		if(@mysql_query($sql,$con)){
			$sql = "commit;";
			echo $local_Erro = 7;
		} else{
			$sql = "rollback;";
			echo $local_Erro = 33;
		}
	}
?>