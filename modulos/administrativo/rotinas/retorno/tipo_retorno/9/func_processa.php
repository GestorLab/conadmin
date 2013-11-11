<?
# CNAB 400
# Centro  Nacional  de  Automa��o  Banc�ria

#TipoRegistro
#Cabecalho = 0
#Registro = 1
#Trailler = 9

#QtdLinhaRegistro
#QtdLinhaRegistro = 1
#RegistroPos1 = 0

#TipoRegistro     �Campo                      �PosInicial     �PosFinal     �Descri��o                                                     �Conte�do
#0                �DataGravacaoMovimento      �95             �100          �Data de Grava��o do Movimento                                 �Num�rico no formato Dia/M�s/Ano (DDMMAA)
#0                �NumSeqArquivo              �395            �400          �N�mro seq�encial do arquivo

#1                �NossoNumero                �63             �78           �Identifica��o do T�tulo no banco                              �Num�rico. C�digo do documento informado pela empresa
#1                �DataCredito                �83             �88           �Data de Cr�dito                                               �Num�rico no formato Dia/M�s/Ano (DDMMAA)
#1                �IdMoeda                    �89             �89           �Moeda                                                         �Identificador da Moeda 9 - Real
#1                �IdOcorrencia               �109            �110          �Identifica��o da Ocorr�ncia                                   �N�mero refer�nte a ocorr�ncia
#1                �DataRecebimento            �111            �116          �Data de Recebimento da Parcela                                �Num�rico no formato Dia/M�s/Ano (DDMMAA)
#1                �ValorLancamento            �153            �165          �Valor nominal da parcela                                      �Num�rico identificando o valor da parcela na moeda corrente
#1                �BancoRecebimento           �166            �168          �Banco onde ocorreu a liquida��o                               �Num�rico com o c�digo do banco onde ocorreu a liquida��o
#1                �AgenciaRecebimento         �169            �173          �Ag�ncia onde ocorreu a liquida��o                             �Num�rico com o c�digo da ag�ncia onde ocorreu a liquida��o
#1                �EspecieTitulo              �174            �175          �Esp�cie T�tulo                                                �N�mero. C�digo refer�nte a esp�cie do t�tulo
#1                �ValorDesconto              �241            �253          �Valor do desconto concedido                                   �Num�rico contendo o valor do desconto concedido na liquida��o
#1                �ValorPago                  �254            �266          �Valor pago                                                    �Num�rico contendo o valor pago pelo sacado
#1                �ValorJurosMora             �267            �279          �Valor de mora pago pelo sacado                                �Num�rico contendo o valor do acr�scimo pago pelo sacado
#1                �StatusParcela              �309            �309          �Indica se � parcela correta, regularizada ou pendente         �0 - Correta<br />1 - Regularizada<br />2 - Pendente de regulariza��o
#1                �NumeroLancamentoExtrato    �310            �315          �N�mero que identifica o lan�amento no extrato                 �Cont�m o n�mero referente ao lan�amento no extrato
#1                �TipoLiquidacao             �342            �342          �Tipo liquida��o                                               �1 - Pgto em Cheque<br />2 - Pgto em Dinheiro<br />3 - Pgto por Compensa��o
#1                �OrigemTarifa               �343            �343          �Origem da Tarifa                                              �1 - Liq Caixa<br />2 - Liq Auto Atendimento<br />3 - Interbanc�ria
#1                �NumeroSeqRetBanco          �395            �400          �N�mero seq�encial do registro no arquivo                      �Num�rico
#
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
		if(strlen($ArqRetorno[0]) == 402){
			for($i=0; $i<count($ArqRetorno);$i++){
				// Header do Arquivo
				if($i == 0){
					// DataGravacaoMovimento
					$Dados[DataGravacaoMovimento] = substr($ArqRetorno[$i],95-1,8);
					
					$Dados[DataGravacaoMovimentoAno] = substr($Dados[DataGravacaoMovimento],0,4);
					$Dados[DataGravacaoMovimentoMes] = substr($Dados[DataGravacaoMovimento],4,2);
					$Dados[DataGravacaoMovimentoDia] = substr($Dados[DataGravacaoMovimento],6,2);
					
					$Dados[DataGravacaoMovimento] = $Dados[DataGravacaoMovimentoAno]."-".$Dados[DataGravacaoMovimentoMes]."-".$Dados[DataGravacaoMovimentoDia];
					$Dados[NumSeqArquivo] = (int)substr($ArqRetorno[$i],111-1,7);
					
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