<?
	function retorno(){
		global $con, $linArquivoRetorno, $local_IdLoja ,$local_IdLocalRecebimento , $local_EnviarEmailConfirmacaoPagamento, $local_IdArquivoRetorno, $local_Login;
		
		$Dados[ValorTotal]		= 0;
		$Dados[ValorTotalTaxa]	= 0;
		$Dados[QtdLancamentos]	= 0;
		
		$tr_i = 0;
		$log = "";
		
		$ArqRetorno = file($linArquivoRetorno[EndArquivo]);
		if(strlen($ArqRetorno[0]) == 242 || strlen($ArqRetorno[0]) == 243){
			for($i=0; $i<count($ArqRetorno);$i++){

				$Dados[NumeroDocumento] = "";
				$Dados[NossoNumero]		= "";

				// Header do Arquivo
				if($i == 0){
					// DataGravacaoMovimento
					$Dados[DataGravacaoMovimento] = substr($ArqRetorno[$i],144-1,8);
					
					$Dados[DataGravacaoMovimentoDia] = substr($Dados[DataGravacaoMovimento],0,2);
					$Dados[DataGravacaoMovimentoMes] = substr($Dados[DataGravacaoMovimento],2,2);
					$Dados[DataGravacaoMovimentoAno] = substr($Dados[DataGravacaoMovimento],4,4);				
					
					$Dados[DataGravacaoMovimento] = $Dados[DataGravacaoMovimentoAno]."-".$Dados[DataGravacaoMovimentoMes]."-".$Dados[DataGravacaoMovimentoDia];
					
					$Dados[NumSeqArquivo] = substr($ArqRetorno[$i],158-1,6);
				
					$sqlArquivoRetorno = "UPDATE ArquivoRetorno SET 
											LoginProcessamento = '$local_Login',
											DataProcessamento = concat(curdate(),' ',curtime())
										WHERE 
											IdLoja = $local_IdLoja and
											IdLocalCobranca = $local_IdLocalRecebimento and
											IdArquivoRetorno = $local_IdArquivoRetorno;";
					$local_transaction[$tr_i]	= mysql_query($sqlArquivoRetorno,$con);
					if($local_transaction[$tr_i] == false){		$lancamento_n_erro++;	}
					$tr_i++;	
				}

				// Segmento
				if($i > 1 && $i < (count($ArqRetorno)-2)){
					// Segmento T
					$Dados[IdBanco]		 			= substr($ArqRetorno[$i],1-1,3);
					$Dados[IdOcorrencia] 			= substr($ArqRetorno[$i],16-1,2);

 					$Dados[NossoNumero]	 			= (int)(substr($ArqRetorno[$i],42-1,11));
					
					$Dados[ValorLancamento]			= (int)substr($ArqRetorno[$i],82-1,9);
 					$Dados[ValorLancamento]			.= ".".substr($ArqRetorno[$i],91-1,2);

 					$Dados[ValorTotal]				+= $Dados[ValorLancamento];
					
					$Dados[AgenciaRecebimento] 		= substr($ArqRetorno[$i],100-1,6);
					$Dados[IdMoeda] 				= substr($ArqRetorno[$i],131-1,2);
					
					$Dados[ValorTarifa]				= (int)substr($ArqRetorno[$i],199-1,8);
					$Dados[ValorTarifa]				.= ".".substr($ArqRetorno[$i],207-1,2);

					$Dados[ValorTotalTaxa]			+= $Dados[ValorTarifa];
					
					$Dados[NumeroSeqRetBanco]		= (int)substr($ArqRetorno[$i],9-1,5);
					
					// Segmento U
					$i++;
					
					$Dados[ValorJurosMora]			= (int)substr($ArqRetorno[$i],123-1,13);
					$Dados[ValorJurosMora]			.= ".".substr($ArqRetorno[$i],136-1,2);
				
					$Dados[ValorDesconto]			= (int)substr($ArqRetorno[$i],48-1,13);
					$Dados[ValorDesconto]			.= ".".substr($ArqRetorno[$i],61-1,2);

					$Dados[OutrasDespesas]			= (int)substr($ArqRetorno[$i],108-1,11);
					$Dados[OutrasDespesas]			.= ".".substr($ArqRetorno[$i],119-1,2);
					
					$Dados[ValorPago]				= (int)substr($ArqRetorno[$i],78-1,13);
					$Dados[ValorPago]				.= ".".substr($ArqRetorno[$i],90,2);
					
					// DataRecebimento
					$Dados[DataRecebimento] = substr($ArqRetorno[$i],138-1,8);
					
					$Dados[DataRecebimentoDia] = substr($Dados[DataRecebimento],0,2);
					$Dados[DataRecebimentoMes] = substr($Dados[DataRecebimento],2,2);
					$Dados[DataRecebimentoAno] = substr($Dados[DataRecebimento],4,4);				
					
					$Dados[DataRecebimento] = $Dados[DataRecebimentoAno]."-".$Dados[DataRecebimentoMes]."-".$Dados[DataRecebimentoDia];
					
					// DataCredito
					$Dados[DataCredito] = substr($ArqRetorno[$i],138-1,8);
					
					$Dados[DataCreditoDia] = substr($Dados[DataCredito],0,2);
					$Dados[DataCreditoMes] = substr($Dados[DataCredito],2,2);
					$Dados[DataCreditoAno] = substr($Dados[DataCredito],4,4);				
					
					$Dados[DataCredito] = $Dados[DataCreditoAno]."-".$Dados[DataCreditoMes]."-".$Dados[DataCreditoDia];
					
					switch($Dados[IdOcorrencia]){
						case '06':
							include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
							break;
						case '09':
							include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
							break;
						case '17':
							include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
							break;						
					}
				}
			}
		}

		include("retorno/tipo_retorno/arquivo_retorno_baixa_arquivo.php"); 		

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
