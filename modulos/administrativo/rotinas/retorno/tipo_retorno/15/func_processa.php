<?
	function retorno(){
		global $con, $linArquivoRetorno, $local_IdLoja ,$local_IdLocalRecebimento ,$local_IdArquivoRetorno, $local_Login, $return_dados;
		
		$Dados[ValorTotal]		= 0;
		$Dados[ValorTotalTaxa]	= 0;
		$Dados[QtdLancamentos]	= 0;
		
		$tr_i = 0;
		$log = "";
		$VerificaAgenciaConta	= "";

		$ArqRetorno = file($linArquivoRetorno[EndArquivo]);
		if(strlen($ArqRetorno[0]) == 242 || strlen($ArqRetorno[0]) == 243){
			for($i=0; $i<count($ArqRetorno);$i++){
				// Header do Arquivo
				if($i == 0){
					// DataGravacaoMovimento
					$Dados[DataGravacaoMovimento] = substr($ArqRetorno[$i],144-1,8);
					
					$Dados[DataGravacaoMovimentoDia] = substr($Dados[DataGravacaoMovimento],0,2);
					$Dados[DataGravacaoMovimentoMes] = substr($Dados[DataGravacaoMovimento],2,2);
					$Dados[DataGravacaoMovimentoAno] = substr($Dados[DataGravacaoMovimento],4,4);				
					
					$Dados[DataGravacaoMovimento] = $Dados[DataGravacaoMovimentoAno]."-".$Dados[DataGravacaoMovimentoMes]."-".$Dados[DataGravacaoMovimentoDia];
					$Dados[NumSeqArquivo] = (int)substr($ArqRetorno[$i],158-1,6);
					
					#Não foi testado pois não tem ennhuem cliente que usa este layout Weiner 28/03/2013\
					/*$Dados[AgenciaCedente]	= substr($ArqRetorno[$i],33-1,4);
					$Dados[ContaCedente]	= substr($ArqRetorno[$i],53-1,9);

					$VerificaAgenciaConta = verificaAgenciaContaArquivoRetorno($local_IdLoja, $Dados[AgenciaCedente], $Dados[ContaCedente], $local_IdLocalRecebimento);*/
				
					if($return_dados){
						return $Dados;
					}
					
					$sqlArquivoRetorno = "UPDATE ArquivoRetorno SET 
											DataRetorno='$Dados[DataGravacaoMovimento]',
											NumSeqArquivo='$Dados[NumSeqArquivo]'
										WHERE 
											IdLoja = $local_IdLoja and
											IdLocalCobranca = $local_IdLocalRecebimento and
											IdArquivoRetorno = $local_IdArquivoRetorno;";
					$local_transaction[$tr_i]	= mysql_query($sqlArquivoRetorno,$con);
					if($local_transaction[$tr_i] == false){		$lancamento_n_erro++;	}
					$tr_i++;	
					break;
				}
			}
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

		if($VerificaAgenciaConta != ''){
			$local_Erro = $VerificaAgenciaConta;
		}
		
		return $local_Erro;
	}
?>