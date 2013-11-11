<?
# CNAB 400
# Banco Bradesco

function retorno(){
	global $con, $linArquivoRetorno, $local_IdLoja ,$local_IdLocalRecebimento ,$local_IdArquivoRetorno, $local_EnviarEmailConfirmacaoPagamento, $local_Login;
	
	$Dados[ValorTotal]		= 0;
	$Dados[ValorTotalTaxa]	= 0;
	$Dados[QtdLancamentos]	= 0;
	
	$tr_i = 0;
	$sql  = "START TRANSACTION;";
	mysql_query($sql,$con);
	
	$log = "";
	
	$ArqRetorno = file($linArquivoRetorno[EndArquivo]);
	if(strlen($ArqRetorno[0]) == 402 || strlen($ArqRetorno[0]) == 401){
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
			if($i > 0 && $i < (count($ArqRetorno)-1) && substr($ArqRetorno[$i],0,1) == 1){
				$Dados[IdBanco]		 			= substr($ArqRetorno[$i],166-1,3);
				$Dados[IdOcorrencia] 			= substr($ArqRetorno[$i],109-1,2);
				$Dados[NossoNumero]	 			= substr($ArqRetorno[$i],71-1,11);
				$Dados[NumeroDocumento]			= substr($ArqRetorno[$i],71-1,11);
				
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
				$Dados[ValorDesconto]			.= ".".substr($ArqRetorno[$i],242,2);
				
				$Dados[OutrasDespesas]			= (int)substr($ArqRetorno[$i],189-1,11);
				$Dados[OutrasDespesas]			.= ".".substr($ArqRetorno[$i],200-1,2);
				
				$Dados[ValorPago]				= (int)substr($ArqRetorno[$i],254-1,11);
				$Dados[ValorPago]				.= ".".substr($ArqRetorno[$i],265-1,2);

				$Dados[ValorTotal]				+= $Dados[ValorPago];

				$Dados[MotivoRejeicao]			= substr($ArqRetorno[$i],109-1,2);

				$MotivoRejeicao['02'] = 'Entrada Confirmada';
				$MotivoRejeicao['03'] = 'Entrada Rejeitada';
				$MotivoRejeicao['06'] = 'Liquida��o normal (sem motivo)';
				$MotivoRejeicao['09'] = 'Baixado Automat. via Arquivo';
				$MotivoRejeicao['10'] = 'Baixado conforme instru��es da Ag�ncia';
				$MotivoRejeicao['11'] = 'Em Ser - Arquivo de T�tulos pendentes (sem motivo)';
				$MotivoRejeicao['12'] = 'Abatimento Concedido (sem motivo)';
				$MotivoRejeicao['13'] = 'Abatimento Cancelado (sem motivo)';
				$MotivoRejeicao['14'] = 'Vencimento Alterado (sem motivo)';
				$MotivoRejeicao['15'] = 'Liquida��o em Cart�rio (sem motivo)';
				$MotivoRejeicao['16'] = 'T�tulo Pago em Cheque � Vinculado';
				$MotivoRejeicao['17'] = 'Liquida��o ap�s baixa ou T�tulo n�o registrado (sem motivo)';
				$MotivoRejeicao['18'] = 'Acerto de Deposit�ria (sem motivo)';
				$MotivoRejeicao['19'] = 'Confirma��o Receb. Inst. de Protesto';
				$MotivoRejeicao['20'] = 'Confirma��o Recebimento Instru��o Susta��o de Protesto (sem motivo)';
				$MotivoRejeicao['22'] = 'T�tulo Com Pagamento Cancelado';
				$MotivoRejeicao['21'] = 'Acerto do Controle do Participante (sem motivo)';
				$MotivoRejeicao['23'] = 'Entrada do T�tulo em Cart�rio (sem motivo)';
				$MotivoRejeicao['24'] = 'Entrada rejeitada por CEP Irregular';
				$MotivoRejeicao['27'] = 'Baixa Rejeitada';
				$MotivoRejeicao['28'] = 'D�bito de tarifas/custas';
				$MotivoRejeicao['30'] = 'Altera��o de Outros Dados Rejeitados';
				$MotivoRejeicao['32'] = 'Instru��o Rejeitada';
				$MotivoRejeicao['33'] = 'Confirma��o Pedido Altera��o Outros Dados (sem motivo)';
				$MotivoRejeicao['34'] = 'Retirado de Cart�rio e Manuten��o Carteira (sem motivo)';
				$MotivoRejeicao['35'] = 'Desagendamento do d�bito autom�tico';
				$MotivoRejeicao['68'] = 'Acerto dos dados do rateio de Cr�dito';
				$MotivoRejeicao['69'] = 'Cancelamento dos dados do rateio';

				$MotivoRejeicao['02_00'] = 'Ocorr�ncia aceita';
				$MotivoRejeicao['02_01'] = 'C�digo do Banco inv�lido';
				$MotivoRejeicao['02_17'] = 'Data de vencimento anterior a data de emiss�o';
				$MotivoRejeicao['02_21'] = 'Esp�cie do T�tulo inv�lido';
				$MotivoRejeicao['02_24'] = 'Data da emiss�o inv�lida';
				$MotivoRejeicao['02_38'] = 'Prazo para protesto inv�lido';
				$MotivoRejeicao['02_39'] = 'Pedido para protesto n�o permitido para t�tulo';
				$MotivoRejeicao['02_43'] = 'Prazo para baixa e devolu��o inv�lido';
				$MotivoRejeicao['02_45'] = 'Nome do Sacado inv�lido';
				$MotivoRejeicao['02_46'] = 'Tipo/num. de inscri��o do Sacado inv�lidos';
				$MotivoRejeicao['02_47'] = 'Endere�o do Sacado n�o informado';
				$MotivoRejeicao['02_48'] = 'CEP irregular';
				$MotivoRejeicao['02_50'] = 'CEP referente a Banco correspondente';
				$MotivoRejeicao['02_53'] = 'N� de inscri��o do Sacador/avalista inv�lidos (CPF/CNPJ)';
				$MotivoRejeicao['02_54'] = 'Sacador/avalista n�o informado';
				$MotivoRejeicao['02_67'] = 'D�bito autom�tico agendado';
				$MotivoRejeicao['02_68'] = 'D�bito n�o agendado - erro nos dados de remessa';
				$MotivoRejeicao['02_69'] = 'D�bito n�o agendado - Sacado n�o consta no cadastro de autorizante';
				$MotivoRejeicao['02_70'] = 'D�bito n�o agendado - Cedente n�o autorizado pelo Sacado';
				$MotivoRejeicao['02_71'] = 'D�bito n�o agendado - Cedente n�o participa da modalidade de d�b.autom�tico';
				$MotivoRejeicao['02_72'] = 'D�bito n�o agendado - C�digo de moeda diferente de R$';
				$MotivoRejeicao['02_73'] = 'D�bito n�o agendado - Data de vencimento inv�lida';
				$MotivoRejeicao['02_75'] = 'D�bito n�o agendado - Tipo do n�mero de inscri��o do sacado debitado inv�lido';
				$MotivoRejeicao['02_86'] = 'Seu n�mero do documento inv�lido';
				$MotivoRejeicao['03_02'] = 'C�digo do registro detalhe inv�lido';
				$MotivoRejeicao['03_03'] = 'C�digo da ocorr�ncia inv�lida';
				$MotivoRejeicao['03_04'] = 'C�digo de ocorr�ncia n�o permitida para a carteira';
				$MotivoRejeicao['03_05'] = 'C�digo de ocorr�ncia n�o num�rico';
				$MotivoRejeicao['03_07'] = 'Ag�ncia/conta/Digito - |Inv�lido';
				$MotivoRejeicao['03_08'] = 'Nosso n�mero inv�lido';
				$MotivoRejeicao['03_09'] = 'Nosso n�mero duplicado';
				$MotivoRejeicao['03_10'] = 'Carteira inv�lida';
				$MotivoRejeicao['03_16'] = 'Data de vencimento inv�lida';
				$MotivoRejeicao['03_18'] = 'Vencimento fora do prazo de opera��o';
				$MotivoRejeicao['03_20'] = 'Valor do T�tulo inv�lido';
				$MotivoRejeicao['03_21'] = 'Esp�cie do T�tulo inv�lida';
				$MotivoRejeicao['03_22'] = 'Esp�cie n�o permitida para a carteira';
				$MotivoRejeicao['03_24'] = 'Data de emiss�o inv�lida';
				$MotivoRejeicao['03_38'] = 'Prazo para protesto inv�lido';
				$MotivoRejeicao['03_44'] = 'Ag�ncia Cedente n�o prevista';
				$MotivoRejeicao['03_50'] = 'CEP irregular - Banco Correspondente';
				$MotivoRejeicao['03_63'] = 'Entrada para T�tulo j� cadastrado';
				$MotivoRejeicao['03_68'] = 'D�bito n�o agendado - erro nos dados de remessa';
				$MotivoRejeicao['03_69'] = 'D�bito n�o agendado - Sacado n�o consta no cadastro de autorizante';
				$MotivoRejeicao['03_70'] = 'D�bito n�o agendado - Cedente n�o autorizado pelo Sacado';
				$MotivoRejeicao['03_71'] = 'D�bito n�o agendado - Cedente n�o participa do d�bito Autom�tico';
				$MotivoRejeicao['03_72'] = 'D�bito n�o agendado - C�digo de moeda diferente de R$';
				$MotivoRejeicao['03_73'] = 'D�bito n�o agendado - Data de vencimento inv�lida';
				$MotivoRejeicao['03_74'] = 'D�bito n�o agendado - Conforme seu pedido, T�tulo n�o registrado';
				$MotivoRejeicao['03_75'] = 'D�bito n�o agendado � Tipo de n�mero de inscri��o do debitado inv�lido';
				$MotivoRejeicao['06_00'] = 'T�tulo pago com dinheiro';
				$MotivoRejeicao['06_15'] = 'T�tulo pago com cheque';
				$MotivoRejeicao['09_10'] = 'Baixa Comandada pelo cliente';
				$MotivoRejeicao['10_00'] = 'Baixado Conforme Instru��es da Ag�ncia';
				$MotivoRejeicao['10_14'] = 'T�tulo Protestado';
				$MotivoRejeicao['10_15'] = 'T�tulo exclu�do';
				$MotivoRejeicao['10_16'] = 'T�tulo Baixado pelo Banco por decurso Prazo';
				$MotivoRejeicao['10_20'] = 'T�tulo Baixado e transferido para desconto';
				$MotivoRejeicao['15_00'] = 'T�tulo pago com dinheiro';
				$MotivoRejeicao['15_15'] = 'T�tulo pago com cheque';
				$MotivoRejeicao['17_00'] = 'T�tulo pago com dinheiro';
				$MotivoRejeicao['17_15'] = 'T�tulo pago com cheque';
				$MotivoRejeicao['24_48'] = 'CEP inv�lido';
				$MotivoRejeicao['27_04'] = 'C�digo de ocorr�ncia n�o permitido para a carteira';
				$MotivoRejeicao['27_07'] = 'Ag�ncia/Conta/d�gito inv�lidos';
				$MotivoRejeicao['27_08'] = 'Nosso n�mero inv�lido';
				$MotivoRejeicao['27_10'] = 'Carteira inv�lida';
				$MotivoRejeicao['27_15'] = 'Carteira/Ag�ncia/Conta/nosso n�mero inv�lidos';
				$MotivoRejeicao['27_40'] = 'T�tulo com ordem de protesto emitido';
				$MotivoRejeicao['27_42'] = 'C�digo para baixa/devolu��o via Telebradesco inv�lido';
				$MotivoRejeicao['27_60'] = 'Movimento para T�tulo n�o cadastrado';
				$MotivoRejeicao['27_77'] = 'Transfer�ncia para desconto n�o permitido para a carteira';
				$MotivoRejeicao['27_85'] = 'T�tulo com pagamento vinculado';
				$MotivoRejeicao['28_03'] = 'Tarifa de susta��o';
				$MotivoRejeicao['28_04'] = 'Tarifa de protesto';
				$MotivoRejeicao['28_08'] = 'Custas de protesto';
				$MotivoRejeicao['30_01'] = 'C�digo do Banco inv�lido';
				$MotivoRejeicao['30_04'] = 'C�digo de ocorr�ncia n�o permitido para a carteira';
				$MotivoRejeicao['30_05'] = 'C�digo da ocorr�ncia n�o num�rico';
				$MotivoRejeicao['30_08'] = 'Nosso n�mero inv�lido';
				$MotivoRejeicao['30_15'] = 'Caracter�stica da cobran�a imcop�tivel';
				$MotivoRejeicao['30_16'] = 'Data de vencimento inv�lido';
				$MotivoRejeicao['30_17'] = 'Data de vencimento anterior a data de emiss�o';
				$MotivoRejeicao['30_18'] = 'Vencimento fora do prazo de opera��o';
				$MotivoRejeicao['30_24'] = 'Data de emiss�o Inv�lida';
				$MotivoRejeicao['30_29'] = 'Valor do desconto maior/igual ao valor do T�tulo';
				$MotivoRejeicao['30_30'] = 'Desconto a conceder n�o confere';
				$MotivoRejeicao['30_31'] = 'Concess�o de desconto j� existente ( Desconto anterior )';
				$MotivoRejeicao['30_33'] = 'Valor do abatimento inv�lido';
				$MotivoRejeicao['30_34'] = 'Valor do abatimento maior/igual ao valor do T�tulo';
				$MotivoRejeicao['30_38'] = 'Prazo para protesto inv�lido';
				$MotivoRejeicao['30_39'] = 'Pedido de protesto n�o permitido para o T�tulo';
				$MotivoRejeicao['30_40'] = 'T�tulo com ordem de protesto emitido';
				$MotivoRejeicao['30_42'] = 'C�digo para baixa/devolu��o inv�lido';
				$MotivoRejeicao['30_60'] = 'Movimento para T�tulo n�o cadastrado';
				$MotivoRejeicao['30_85'] = 'T�tulo com Pagamento Vinculado.';
				$MotivoRejeicao['32_01'] = 'C�digo do Banco inv�lido';
				$MotivoRejeicao['32_02'] = 'C�digo do registro detalhe inv�lido';
				$MotivoRejeicao['32_04'] = 'C�digo de ocorr�ncia n�o permitido para a carteira';
				$MotivoRejeicao['32_05'] = 'C�digo de ocorr�ncia n�o num�rico';
				$MotivoRejeicao['32_07'] = 'Ag�ncia/Conta/d�gito inv�lidos';
				$MotivoRejeicao['32_08'] = 'Nosso n�mero inv�lido';
				$MotivoRejeicao['32_10'] = 'Carteira inv�lida';
				$MotivoRejeicao['32_15'] = 'Caracter�sticas da cobran�a incompat�veis';
				$MotivoRejeicao['32_16'] = 'Data de vencimento inv�lida';
				$MotivoRejeicao['32_17'] = 'Data de vencimento anterior a data de emiss�o';
				$MotivoRejeicao['32_18'] = 'Vencimento fora do prazo de opera��o';
				$MotivoRejeicao['32_20'] = 'Valor do t�tulo inv�lido';
				$MotivoRejeicao['32_21'] = 'Esp�cie do T�tulo inv�lida';
				$MotivoRejeicao['32_22'] = 'Esp�cie n�o permitida para a carteira';
				$MotivoRejeicao['32_24'] = 'Data de emiss�o inv�lida';
				$MotivoRejeicao['32_28'] = 'C�digo de desconto via Telebradesco inv�lido';
				$MotivoRejeicao['32_29'] = 'Valor do desconto maior/igual ao valor do T�tulo';
				$MotivoRejeicao['32_30'] = 'Desconto a conceder n�o confere';
				$MotivoRejeicao['32_31'] = 'Concess�o de desconto - J� existe desconto anterior';
				$MotivoRejeicao['32_33'] = 'Valor do abatimento inv�lido';
				$MotivoRejeicao['32_34'] = 'Valor do abatimento maior/igual ao valor do T�tulo';
				$MotivoRejeicao['32_36'] = 'Concess�o abatimento - J� existe abatimento anterior';
				$MotivoRejeicao['32_38'] = 'Prazo para protesto inv�lido';
				$MotivoRejeicao['32_39'] = 'Pedido de protesto n�o permitido para o T�tulo';
				$MotivoRejeicao['32_40'] = 'T�tulo com ordem de protesto emitido';
				$MotivoRejeicao['32_41'] = 'Pedido cancelamento/susta��o para T�tulo sem instru��o de protesto';
				$MotivoRejeicao['32_42'] = 'C�digo para baixa/devolu��o inv�lido';
				$MotivoRejeicao['32_45'] = 'Nome do Sacado n�o informado';
				$MotivoRejeicao['32_46'] = 'Tipo/n�mero de inscri��o do Sacado inv�lidos';
				$MotivoRejeicao['32_47'] = 'Endere�o do Sacado n�o informado';
				$MotivoRejeicao['32_48'] = 'CEP Inv�lido';
				$MotivoRejeicao['32_50'] = 'CEP referente a um Banco correspondente';
				$MotivoRejeicao['32_53'] = 'Tipo de inscri��o do sacador avalista inv�lidos';
				$MotivoRejeicao['32_60'] = 'Movimento para T�tulo n�o cadastrado';
				$MotivoRejeicao['32_85'] = 'T�tulo com pagamento vinculado';
				$MotivoRejeicao['32_86'] = 'Seu n�mero inv�lido';
				$MotivoRejeicao['35_81'] = 'Tentativas esgotadas, baixado';
				$MotivoRejeicao['35_82'] = 'Tentativas esgotadas, pendente';

 				$Dados[MotivoRejeicao2]	 = substr($ArqRetorno[$i],319-1,2);
		
				// DataRecebimento
				$Dados[DataRecebimento] = substr($ArqRetorno[$i],111-1,6);
			
				$Dados[DataRecebimentoDia] = substr($Dados[DataRecebimento],0,2);
				$Dados[DataRecebimentoMes] = substr($Dados[DataRecebimento],2,2);
				$Dados[DataRecebimentoAno] = substr(date("Y"),0,2).substr($Dados[DataRecebimento],4,2);
				
				$Dados[DataRecebimento] = $Dados[DataRecebimentoAno]."-".$Dados[DataRecebimentoMes]."-".$Dados[DataRecebimentoDia];
				
				$LogErro = false;

				switch($Dados[MotivoRejeicao]){
					case '02':
						include("retorno/tipo_retorno/salva_log_conta_receber.php");
						break;

					case '06':
						include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
						break;

					case '09':
						if($Dados[MotivoRejeicao2] == '00'){
							include("retorno/tipo_retorno/salva_log_conta_receber.php");
						}else{
							$LogErro = true;
							include("retorno/tipo_retorno/salva_log_conta_receber.php");
							include("retorno/tipo_retorno/salva_log_retorno.php");
							include("retorno/tipo_retorno/bloqueia_conta_receber.php");
						}
						break;

					case '10':
						include("retorno/tipo_retorno/salva_log_conta_receber.php");
						#include("retorno/tipo_retorno/conta_receber_posicao_cobranca_cancela_baixa.php");					
						break;
					
					case '14':
						include("retorno/tipo_retorno/salva_log_conta_receber.php");
						break;

					case '15':
						include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
						break;					

					case '16':
						include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
						break;

					case '17':
						include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
						break;
					
					case '33':
						include("retorno/tipo_retorno/salva_log_conta_receber.php");
						break;

					default:
						$LogErro = true;
						include("retorno/tipo_retorno/salva_log_conta_receber.php");
						include("retorno/tipo_retorno/salva_log_retorno.php");
						include("retorno/tipo_retorno/bloqueia_conta_receber.php");
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