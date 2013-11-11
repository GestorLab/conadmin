<?
	function retorno(){
		global $con, $linArquivoRetorno, $local_IdLoja ,$local_IdLocalRecebimento ,$local_IdArquivoRetorno, $local_Login, $return_dados;
		
		$Dados[ValorTotal]		= 0;
		$Dados[ValorTotalTaxa]	= 0;
		$Dados[QtdLancamentos]	= 0;
		
		$tr_i = 0;
		$log = "";
		
		$ArqRetorno = file($linArquivoRetorno[EndArquivo]);

		if(strlen($ArqRetorno[0]) == 152){
			for($i=0; $i<count($ArqRetorno);$i++){
				// Header do Arquivo
				if($i == 0){

					// Verifica o tipo do retorno
					if(substr($ArqRetorno[$i],1,1) == 2){

						// Verificar o número do convênio
						$sql = "select
									ValorLocalCobrancaParametro Convenio
								from
									LocalCobrancaParametro
								where
									IdLoja = $local_IdLoja and
									IdLocalCobranca = $local_IdLocalRecebimento and
									IdLocalCobrancaParametro = 'Convenio'";
						$res = mysql_query($sql,$con);
						$lin = mysql_fetch_array($res);

						$Convenio = trim(substr($ArqRetorno[$i],2,20));
						
						if($lin[Convenio] == $Convenio){

							// DataGravacaoMovimento
							$Dados[DataGravacaoMovimento] = dataConv(substr($ArqRetorno[$i],66-1,8),"Ymd","Y-m-d");
							
							$Dados[NumSeqArquivo] = (int)substr($ArqRetorno[$i],74-1,6);
						}else{
							echo $log = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Este arquivo não é do convênio cadastrado [$Convenio]\n".$log;
							break;
						}
					}else{
						$log = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Não é um arquivo de retorno válido\n".$log;
						break;
					}
				}else{
					if(substr($ArqRetorno[$i],1-1,1) == 'F'){
						$Dados[QtdRegistro]++;
					}
				}
			}
					
			if($return_dados){
				return $Dados;
			}

			$sqlArquivoRetorno = "UPDATE ArquivoRetorno SET 
									DataRetorno='$Dados[DataGravacaoMovimento]',
									NumSeqArquivo='$Dados[NumSeqArquivo]',
									QtdRegistro = '$Dados[QtdRegistro]',
									LogRetorno = '$log'
								WHERE 
									IdLoja = $local_IdLoja and
									IdLocalCobranca = $local_IdLocalRecebimento and
									IdArquivoRetorno = $local_IdArquivoRetorno;";
			$local_transaction[$tr_i]	= mysql_query($sqlArquivoRetorno,$con);
			if($local_transaction[$tr_i] == false){		$lancamento_n_erro++;	}
			$tr_i++;
		}

		$var_transaction = true;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$var_transaction = false;				
			}
		}

		if($var_transaction == true){
			$local_Erro = 3012;
		}else{
			$local_Erro = 3020;
		}		
		return $local_Erro;
	}
?>
