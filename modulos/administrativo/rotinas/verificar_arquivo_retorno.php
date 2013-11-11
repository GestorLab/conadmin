<?
	$localModulo	= 1;
	$localOperacao	= 22;
	
	$local_IdLoja				= $_SESSION["IdLoja"];	
	$local_IdLocalRecebimento	= $_GET['IdLocalRecebimento'];
	$local_IdArquivoRetorno		= $_GET['IdArquivoRetorno'];
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		echo $local_Erro = 2;
	} else{
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
								ArquivoRetorno.IdLocalCobranca = LocalCobranca.IdLocalCobranca# and
								#ArquivoRetorno.IdStatus = 1";
		$resArquivoRetorno = mysql_query($sqlArquivoRetorno,$con);
		if($linArquivoRetorno = @mysql_fetch_array($resArquivoRetorno)){				
			// Se o arquivo de retorno existir...
			$linArquivoRetorno[EndArquivo] = "../".$linArquivoRetorno[EndArquivo];
			
			if(file_exists($linArquivoRetorno[EndArquivo])){
				$endFuncRetorno = "../retorno/tipo_retorno/$linArquivoRetorno[IdArquivoRetornoTipo]/func_processa.php";
				
				include($endFuncRetorno);
				
				$return_dados = true;
				$local_DadosArquivoRetorno = retorno();
				
				if($local_DadosArquivoRetorno[NumSeqArquivo] != ''){
					$local_DadosArquivoRetorno[NumSeqArquivo]--;
#					print_r($local_DadosArquivoRetorno);
				}
				
				$sql = "select 
							count(*) Qtd
						from
							ArquivoRetorno  
						where 
							IdLoja = $local_IdLoja and 
							IdLocalCobranca = $local_IdLocalRecebimento and 
							NumSeqArquivo = $local_DadosArquivoRetorno[NumSeqArquivo];";
				$res = mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				
				if(!$lin[Qtd] > 0 && $local_DadosArquivoRetorno[NumSeqArquivo] > 0){
					// ERRO NA ORDEM DO ARQUIVO DE RETORNO
					echo 1;
				} else{
					// ARQUIVO DE RETORNO NA ORDEM
					echo 0;
				}
			}else{
				echo "ARQUIVO DE RETORNO NÃO ENCONTRADO";
			}
		}
	}
?>