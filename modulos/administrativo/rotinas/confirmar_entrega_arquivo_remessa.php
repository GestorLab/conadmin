<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	} else{
		$LogRemessa = date("d/m/Y H:i:s")." [$local_Login] - Entrega Confirmada do Arquivo de Remessa.";
				
		$sql = "update ArquivoRemessa set
					LogRemessa=concat('$LogRemessa','\n',LogRemessa), 
					IdStatus = 4
				where 
					IdLoja='$local_IdLoja' and 
					IdLocalCobranca = $local_IdLocalCobranca and 
					IdArquivoRemessa=$local_IdArquivoRemessa";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$local_Erro = 47;
	}
?>
