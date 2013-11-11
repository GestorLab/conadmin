<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_BackupConta(){
		global $con;
		global $_GET;
		
		$local_IdBackupConta	= $_GET['IdBackupConta'];
		$local_ParametroValor	= array();
		$local_LoginCriacao		= '';
		$local_DataCriacao		= '';
		$local_LoginAlteracao	= '';
		$local_DataAlteracao	= '';
		$where					= '';
		
		if($local_IdBackupConta != ''){
			$where = " AND IdParametroSistema = ".(int)(($local_IdBackupConta+1)*10);
		}
		
		$sql = "SELECT 
					(REPLACE((IdParametroSistema/10),'.0000','')-1) IdBackupConta 
				FROM
					ParametroSistema 
				WHERE 
					IdGrupoParametroSistema = 83 AND 
					IdParametroSistema > 19 AND 
					(IdParametroSistema%10) = 0
					$where;";
		$res = mysql_query($sql,$con);
		
		if(mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$local_IdBackupContaTemp = (int)(($lin[IdBackupConta]+1)*10);
				$i = 0;
				$sql_Temp = "SELECT 
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
				$res_Temp = mysql_query($sql_Temp,$con);
				
				if(mysql_num_rows($res_Temp) > 0){
					while($lin_Temp = @mysql_fetch_array($res_Temp)){
						$local_LoginCriacao			= $lin_Temp[LoginCriacao];
						$local_DataCriacao			= $lin_Temp[DataCriacao];
						$local_LoginAlteracao		= $lin_Temp[LoginAlteracao];
						$local_DataAlteracao		= $lin_Temp[DataAlteracao];
						$local_ParametroValor[$i]	= $lin_Temp[ValorParametroSistema];
						$i++;
					}
					
					$dados .= "\n<IdBackupConta><![CDATA[$lin[IdBackupConta]]]></IdBackupConta>";
					$dados .= "\n<ServidorEndereco><![CDATA[$local_ParametroValor[0]]]></ServidorEndereco>";
					$dados .= "\n<ServidorUsuario><![CDATA[$local_ParametroValor[1]]]></ServidorUsuario>";
					$dados .= "\n<ServidorSenha><![CDATA[$local_ParametroValor[2]]]></ServidorSenha>";
					$dados .= "\n<BackupCaminho><![CDATA[$local_ParametroValor[3]]]></BackupCaminho>";
					$dados .= "\n<ServidorPorta><![CDATA[$local_ParametroValor[4]]]></ServidorPorta>";
					$dados .= "\n<HistoricoObs><![CDATA[$local_ParametroValor[5]]]></HistoricoObs>";
					$dados .= "\n<LoginCriacao><![CDATA[$local_LoginCriacao]]></LoginCriacao>";
					$dados .= "\n<DataCriacao><![CDATA[$local_DataCriacao]]></DataCriacao>";
					$dados .= "\n<LoginAlteracao><![CDATA[$local_LoginAlteracao]]></LoginAlteracao>";
					$dados .= "\n<DataAlteracao><![CDATA[$local_DataAlteracao]]></DataAlteracao>";
				}
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_BackupConta();
?>