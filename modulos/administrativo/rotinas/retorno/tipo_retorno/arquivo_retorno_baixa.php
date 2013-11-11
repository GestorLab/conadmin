<?
	$Dados[NossoNumero] = trim($Dados[NossoNumero]);
	$Dados[NumeroDocumento] = trim($Dados[NumeroDocumento]);

	if($Dados[NossoNumero] == ''){
		$Dados[NossoNumero] = $Dados[NumeroDocumento];
	}

	if($Dados[NumeroDocumento] == ''){
		$Dados[NumeroDocumento] = $Dados[NossoNumero];
	}

	if($Dados[ValorPago] > 0){
		$sqlContaReceber = "select
								ContaReceberDados.IdLoja,
								ContaReceberDados.IdContaReceber,
								LocalCobranca.IdTipoLocalCobranca,
								ContaReceberDados.ValorContaReceber,
								ContaReceberDados.IdStatus
							from
								ContaReceberDados,
								LocalCobranca
							where
								(
									(
										LocalCobranca.IdLoja = $local_IdLoja AND
										LocalCobranca.IdLocalCobranca = $local_IdLocalRecebimento
									) OR (
										LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja AND
										LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalRecebimento
									)
								) AND
								LocalCobranca.IdLoja = ContaReceberDados.IdLoja AND
								LocalCobranca.IdLocalCobranca = ContaReceberDados.IdLocalCobranca AND 
								(
									ContaReceberDados.NumeroDocumento = $Dados[NumeroDocumento] or 
									ContaReceberDados.NumeroDocumento = $Dados[NossoNumero] or

									ContaReceberDados.NossoNumero = $Dados[NossoNumero] or 
									ContaReceberDados.NossoNumero = $Dados[NumeroDocumento]

								)";
		$resContaReceber = mysql_query($sqlContaReceber,$con);
		if($linContaReceber = mysql_fetch_array($resContaReceber)){

			$Dados[ValorDescontoTemp] = $linContaReceber[ValorContaReceber] - ($Dados[ValorPago]-$linContaReceber[ValorOutrasDespesas]-$linContaReceber[ValorMoraMulta]);

			if($Dados[ValorDesconto] == 0 && $Dados[ValorDescontoTemp] > 0){
				$Dados[ValorDesconto] = $Dados[ValorDescontoTemp];
			}

			$linContaReceber[DataRecebimento]			= dataConv($Dados[DataRecebimento],"Y-m-d","d/m/Y");

			$linContaReceber[ValorReceber]				= str_replace(".",",",$Dados[ValorPago]);
			$linContaReceber[ValorDescontoRecebimento]	= str_replace(".",",",$Dados[ValorDesconto]);
			$linContaReceber[ValorOutrasDespesas]		= str_replace(".",",",$Dados[OutrasDespesas]);
			$linContaReceber[ValorMoraMulta]			= str_replace(".",",",$Dados[ValorJurosMora]);

			$linContaReceber[IdLojaRecebimento]					= $local_IdLoja;
			$linContaReceber[IdLocalRecebimento]				= $local_IdLocalRecebimento;
			$linContaReceber[EnviarEmailConfirmacaoPagamento]	= $local_EnviarEmailConfirmacaoPagamento;

			$linContaReceber[Login]								= $local_Login;
			$linContaReceber[IdArquivoRetorno]					= $local_IdArquivoRetorno;

			$local_transaction[$tr_i]	=	receber_conta_receber($linContaReceber);
			$tr_i++;

		}else{
			// Número Documento não encontrado.
			$log = date("d/m/Y H:i:s")." [$local_Login] - Dcto. $Dados[NossoNumero] não encontrado (R$ $Dados[ValorPago]).\n".$log;
		}
		$Dados[QtdLancamentos]++;
	}else{
		// Valor R$ 0,00
		$log = date("d/m/Y H:i:s")." [$local_Login] - Dcto. $Dados[NossoNumero]. Valor pago: R$ 0,00.\n".$log;
	}
?>