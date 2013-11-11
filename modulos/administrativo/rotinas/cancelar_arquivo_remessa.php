<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
			
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;

		$sql = "select
					EndArquivo
				from
					ArquivoRemessa
				where
					IdLoja = $local_IdLoja and
					IdLocalCobranca = $local_IdLocalCobranca and 
					IdArquivoRemessa= $local_IdArquivoRemessa";
		$res = mysql_query($sql, $con);
		$lin = mysql_fetch_array($res);

		$sql = "update ContaReceber set 
					IdStatus = 3, 
					IdLojaRemessa=NULL, 
					IdLocalCobrancaRemessa=NULL,
					IdArquivoRemessa=NULL
				where 
					IdLojaRemessa = $local_IdLoja and 
					IdLocalCobrancaRemessa = $local_IdLocalCobranca and 
					IdArquivoRemessa=$local_IdArquivoRemessa and 
					IdStatus = 1";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;

		$sql = "update ContaReceber set
					IdLojaRemessa=NULL, 
					IdLocalCobrancaRemessa=NULL,
					IdArquivoRemessa=NULL
				where 
					IdLojaRemessa = $local_IdLoja and 
					IdLocalCobrancaRemessa = $local_IdLocalCobranca and 
					IdArquivoRemessa=$local_IdArquivoRemessa";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
		$tr_i++;		
			
		$sql = "update ContaReceberPosicaoCobranca set 
					DataRemessa = '0000-00-00',
					IdLojaRemessa=NULL, 
					IdLocalCobrancaRemessa=NULL,
					IdArquivoRemessa=NULL
				where
					IdLojaRemessa = $local_IdLoja and 
					IdLocalCobrancaRemessa = $local_IdLocalCobranca and 
					IdArquivoRemessa=$local_IdArquivoRemessa";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		$LogRemessa = date("d/m/Y H:i:s")." [$local_Login] - Cancelamento do Arquivo de Remessa.";

		$sql = "update ArquivoRemessa set 
					IdArquivoRemessaTipo=NULL, 
					EndArquivo=NULL,  
					ValorTotal=NULL, 
					DataRemessa=NULL, 
					QtdRegistro=NULL,  
					FileSize=NULL, 
					NomeArquivo=NULL, 
					NumSeqArquivo=NULL, 
					LoginProcessamento=NULL, 
					DataProcessamento=NULL, 
					LogRemessa=concat('$LogRemessa','\n',LogRemessa), 
					IdStatus = 1 
				where 
					IdLoja='$local_IdLoja' and 
					IdLocalCobranca = $local_IdLocalCobranca and 
					IdArquivoRemessa = $local_IdArquivoRemessa";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;

		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
	
		if($local_transaction == true){
			$sql = "COMMIT;";
			mysql_query($sql,$con);

			@unlink($lin[EndArquivo]);	
			@unlink($lin[EndArquivo].".zip");

			$local_Erro = 48;			
		}else{
			$sql = "ROLLBACK;";
			mysql_query($sql,$con);
			$local_Erro = 68;
		}		
	}
?>