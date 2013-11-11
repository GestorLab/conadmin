<?
# CNAB 240
# Centro  Nacional  de  Automa��o  Banc�ria

#Var Tipos do Registro
# HeaderFile 		= 0
# HeaderLote 		= 1
# Segmento 			= 3
# TrailerLote 		= 5
# TrailerArquivo 	= 9

#TipoRegistro     �Campo                      �PosInicial     �PosFinal     �Descri��o                                                     �Conte�do
#0                �DataGravacaoMovimento      �144            �151          �Data de Grava��o do Movimento                                 �Num�rico no formato Dia/M�s/Ano (DDMMAA)
#0                �NumSeqArquivo              �158            �163          �N�mro seq�encial do arquivo

#1                �IdBanco                    �1              �3            �Identifica��o do Banco                                        �N�mero refer�nte ao banco
#1                �IdOcorrencia               �16             �17           �Identifica��o da Ocorr�ncia                                   �N�mero refer�nte a ocorr�ncia
#1                �NossoNumero                �47             �57           �Identifica��o do T�tulo no banco                              �Num�rico. C�digo do documento informado pela empresa
#1                �NumeroDocumento            �59             �73           �Identifica��o do T�tulo no banco                              �Num�rico. C�digo do documento informado pela empresa
#1                �ValorLancamento            �82             �96           �Valor nominal da parcela                                      �Num�rico identificando o valor da parcela na moeda corrente
#1                �AgenciaRecebimento         �100            �105          �Ag�ncia onde ocorreu a liquida��o                             �Num�rico com o c�digo da ag�ncia onde ocorreu a liquida��o
#1                �IdMoeda                    �131            �132          �Moeda                                                         �Identificador da Moeda 9 - Real
#1                �ValorTarifa                �199            �213          �Valor da Tarifa/Custos                                        �Valor da Tarifa/Custos

#2                �ValorJurosMora             �18             �32           �Valor de mora pago pelo sacado                                �Num�rico contendo o valor do acr�scimo pago pelo sacado
#2                �ValorDesconto              �33             �47           �Valor do desconto concedido                                   �Num�rico contendo o valor do desconto concedido na liquida��o
#2                �ValorPago                  �78             �92           �Valor pago                                                    �Num�rico contendo o valor pago pelo sacado
#2                �DataRecebimento            �138            �145          �Data de Recebimento da Parcela                                �Num�rico no formato Dia/M�s/Ano (DDMMAA)
#2                �DataCredito                �146            �153          �Data de Cr�dito                                               �Num�rico no formato Dia/M�s/Ano (DDMMAA)
?>

<?
	function retorno(){
		global $con, $linArquivoRetorno, $local_IdLoja ,$local_IdLocalRecebimento ,$local_IdArquivoRetorno, $local_Login, $return_dados;
		
		$Dados[ValorTotal]		= 0;
		$Dados[ValorTotalTaxa]	= 0;
		$Dados[QtdLancamentos]	= 0;
		
		$tr_i = 0;
		$sql  = "START TRANSACTION;";
		mysql_query($sql,$con);
		
		$log = "";
		
		$ArqRetorno = file($linArquivoRetorno[EndArquivo]);

		if(strlen($ArqRetorno[0]) == 242 || strlen($ArqRetorno[0]) == 241){
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

		return $local_Erro;
	}
?>