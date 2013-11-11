<?
	if(!permissaoSubOperacao($localModulo, $localOperacao, "P")){
		$local_Erro = 2;
	} else{
		$local_PeriodoApuracao = dataConv($local_PeriodoApuracao, "m/Y", "Y-m");
		
		$sql = "UPDATE SICI SET
					IdStatus				= '4', 
					LoginConfirmacaoEntrega	= '$local_Login', 
					DataConfirmacaoEntrega	= (concat(curdate(),' ',curtime()))
				WHERE
					PeriodoApuracao = '$local_PeriodoApuracao';";
		
		if(@mysql_query($sql, $con)){
			$local_Erro = 176;
		} else{
			$local_Erro = 177;
		}
	}
?>