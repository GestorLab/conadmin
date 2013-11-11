<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
			$local_Erro = 2;
	}else{		
		$sql	=	"UPDATE ContaSMS SET
							IdLoja					= '$local_IdLoja',
							IdContaSMS				= '$local_IdContaSMS',
							DescricaoContaSMS		= '$local_Descricao',
							IdOperadora				= '$local_IdOperadora',
							IdStatus				= '$local_IdStatus',
							LoginAlteracao			= '$local_Login',
							DataAlteracao			= concat(curdate(),' ',curtime())
					WHERE 
							IdContaSMS				= $local_IdContaSMS";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
			$sql3 = "SELECT 
					IdParametroOperadoraSMS
				FROM
					OperadoraSMSParametro
				WHERE
					IdOperadora = $local_IdOperadora";
			$res3 = mysql_query($sql3, $con);
			while($lin3 = mysql_fetch_array($res3)){		
				$ValorParametroSistema = $_POST["ParametroOperadoraSMS_".$lin3[IdParametroOperadoraSMS]];
				if($ValorParametroSistema != ""){
					$sql =	"UPDATE ContaSMSParametro SET
								IdOperadora				= '$local_IdOperadora',
								ValorParametroSMS		= '$ValorParametroSistema'
							WHERE 
								IdContaSMS				= $local_IdContaSMS and
								IdParametroOperadoraSMS = $lin3[IdParametroOperadoraSMS]";
					$res = mysql_query($sql,$con);
				}
			}
		}else{
			$local_Erro = 5;
		}
	}	
?>