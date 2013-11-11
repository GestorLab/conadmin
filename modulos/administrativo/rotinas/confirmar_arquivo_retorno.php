<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
		$sqlArquivoRetorno = "select
								ArquivoRetorno.EndArquivo,
								LocalCobranca.IdArquivoRetornoTipo
							from
								ArquivoRetorno,
								LocalCobranca
							where
								ArquivoRetorno.IdLoja = $local_IdLoja and
								ArquivoRetorno.IdLoja = LocalCobranca.IdLoja and
								ArquivoRetorno.IdArquivoRetorno = $local_IdArquivoRetorno and
								ArquivoRetorno.IdLocalCobranca = $local_IdLocalRecebimento and
								ArquivoRetorno.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
								ArquivoRetorno.IdStatus = 2";
		$resArquivoRetorno = mysql_query($sqlArquivoRetorno,$con);
		if($linArquivoRetorno = @mysql_fetch_array($resArquivoRetorno)){				
			// Se o arquivo de retorno existir...
			if(file_exists($linArquivoRetorno[EndArquivo])){
				$tr_i = 0;
				$sql  = "START TRANSACTION;";
				mysql_query($sql,$con);

				$sqlArquivoRetorno = "UPDATE ArquivoRetorno SET IdStatus = 3 WHERE IdLoja = $local_IdLoja AND IdLocalCobranca = $local_IdLocalRecebimento AND IdArquivoRetorno = $local_IdArquivoRetorno;";
				$local_transaction[$tr_i]	= mysql_query($sqlArquivoRetorno,$con);
				$tr_i++;

				$endFuncRetorno = "retorno/tipo_retorno/$linArquivoRetorno[IdArquivoRetornoTipo]/func_retorno.php";	
				include($endFuncRetorno);
				$local_Erro = retorno();

				$var_transaction = true;
				for($i=0; $i<$tr_i; $i++){
					if($local_transaction[$i] == false){
						$var_transaction = false;
					}
				}

				if($var_transaction == true && $local_Erro == 3012){
					$sql = "COMMIT;";
					$local_Erro = 47;
				}else{
					$sql = "ROLLBACK;";
					$local_Erro = 50;
				}
				mysql_query($sql,$con);
			}else{
#				echo "ARQUIVO DE RETORNO NÃO ENCONTRADO";
			}
		}
	}	
?>