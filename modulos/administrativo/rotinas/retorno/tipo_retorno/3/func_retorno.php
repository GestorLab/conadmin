<?
	function retorno(){
		global $con, $linArquivoRetorno, $local_IdLoja ,$local_IdLocalRecebimento , $local_EnviarEmailConfirmacaoPagamento, $local_IdArquivoRetorno, $local_Login;
		
		$Dados[ValorTotal]		= 0;
		$Dados[ValorTotalTaxa]	= 0;
		$Dados[QtdLancamentos]	= 0;
		
		$tr_i = 0;
		$log = "";
		
		$ArqRetorno = file($linArquivoRetorno[EndArquivo]);
		if(strlen($ArqRetorno[0]) == 402){
			for($i=0; $i<count($ArqRetorno);$i++){

				$Dados[NumeroDocumento] = "";
				$Dados[NossoNumero]		= "";

				// Header do Arquivo
				if($i == 0){
					// DataGravacaoMovimento
					$Dados[DataGravacaoMovimento] = substr($ArqRetorno[$i],95-1,6);
					
					$Dados[DataGravacaoMovimentoDia] = substr($Dados[DataGravacaoMovimento],0,2);
					$Dados[DataGravacaoMovimentoMes] = substr($Dados[DataGravacaoMovimento],2,2);
					$Dados[DataGravacaoMovimentoAno] = substr(date("Y"),0,2).substr($Dados[DataGravacaoMovimento],4,2);				
					
					$Dados[DataGravacaoMovimento] = $Dados[DataGravacaoMovimentoAno]."-".$Dados[DataGravacaoMovimentoMes]."-".$Dados[DataGravacaoMovimentoDia];
					
					$Dados[NumSeqArquivo] = substr($ArqRetorno[$i],395-1,6);
				
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
				if($i > 0 && $i < (count($ArqRetorno)-1)){
					$Dados[IdBanco]		 			= substr($ArqRetorno[$i],166-1,3);
					$Dados[IdOcorrencia] 			= substr($ArqRetorno[$i],109-1,2);
					$Dados[NossoNumero]	 			= (int)substr($ArqRetorno[$i],63-1,13);
					$Dados[NumeroDocumento]			= (int)substr($ArqRetorno[$i],71-1,10);
					
					$Dados[ValorLancamento]			= (int)substr($ArqRetorno[$i],254-1,11);
					$Dados[ValorLancamento]			.= ".".substr($ArqRetorno[$i],265-1,2);
					
					$Dados[AgenciaRecebimento] 		= substr($ArqRetorno[$i],169-1,5);
					$Dados[IdMoeda] 				= substr($ArqRetorno[$i],89-1,1);
					
					$Dados[ValorTarifa]				= (int)substr($ArqRetorno[$i],182-1,5); 
					$Dados[ValorTarifa]				.= ".".substr($ArqRetorno[$i],186,2);

					$Dados[ValorTotalTaxa]			+= $Dados[ValorTarifa];
					
					$Dados[NumeroSeqRetBanco]		= (int)substr($ArqRetorno[$i],395-1,6);
					
					$Dados[ValorJurosMora]			= (int)substr($ArqRetorno[$i],267-1,11);
					$Dados[ValorJurosMora]			.= ".".substr($ArqRetorno[$i],278-1,2);
				
					$Dados[ValorDesconto]			= (int)substr($ArqRetorno[$i],241-1,11);
					$Dados[ValorDesconto]			.= ".".substr($ArqRetorno[$i],252-1,2);
				
					$Dados[OutrasDespesas]			= (int)substr($ArqRetorno[$i],189-1,11);
					$Dados[OutrasDespesas]			.= ".".substr($ArqRetorno[$i],200-1,2);
					
					$Dados[ValorPago]				= (int)substr($ArqRetorno[$i],254-1,11);
					$Dados[ValorPago]				.= ".".substr($ArqRetorno[$i],265-1,2);

					$Dados[ValorTotal]				+= $Dados[ValorPago];
					
					// DataRecebimento
					$Dados[DataRecebimento] = substr($ArqRetorno[$i],111-1,6);
				
					$Dados[DataRecebimentoDia] = substr($Dados[DataRecebimento],0,2);
					$Dados[DataRecebimentoMes] = substr($Dados[DataRecebimento],2,2);
					$Dados[DataRecebimentoAno] = substr(date("Y"),0,2).substr($Dados[DataRecebimento],4,2);
					
					$Dados[DataRecebimento] = $Dados[DataRecebimentoAno]."-".$Dados[DataRecebimentoMes]."-".$Dados[DataRecebimentoDia];
					
					// DataCredito
					$Dados[DataCredito] = substr($ArqRetorno[$i],83-1,6);
					
					$Dados[DataCreditoDia] = substr($Dados[DataCredito],0,2);
					$Dados[DataCreditoMes] = substr($Dados[DataCredito],2,2);
					$Dados[DataCreditoAno] = substr(date("Y"),0,2).substr($Dados[DataCredito],4,2);				
					
					$Dados[DataCredito] = $Dados[DataCreditoAno]."-".$Dados[DataCreditoMes]."-".$Dados[DataCreditoDia];
					
					include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
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
