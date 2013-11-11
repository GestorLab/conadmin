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
				$MotivoRejeicao['06'] = 'Liquidação normal (sem motivo)';
				$MotivoRejeicao['09'] = 'Baixado Automat. via Arquivo';
				$MotivoRejeicao['10'] = 'Baixado conforme instruções da Agência';
				$MotivoRejeicao['11'] = 'Em Ser - Arquivo de Títulos pendentes (sem motivo)';
				$MotivoRejeicao['12'] = 'Abatimento Concedido (sem motivo)';
				$MotivoRejeicao['13'] = 'Abatimento Cancelado (sem motivo)';
				$MotivoRejeicao['14'] = 'Vencimento Alterado (sem motivo)';
				$MotivoRejeicao['15'] = 'Liquidação em Cartório (sem motivo)';
				$MotivoRejeicao['16'] = 'Título Pago em Cheque – Vinculado';
				$MotivoRejeicao['17'] = 'Liquidação após baixa ou Título não registrado (sem motivo)';
				$MotivoRejeicao['18'] = 'Acerto de Depositária (sem motivo)';
				$MotivoRejeicao['19'] = 'Confirmação Receb. Inst. de Protesto';
				$MotivoRejeicao['20'] = 'Confirmação Recebimento Instrução Sustação de Protesto (sem motivo)';
				$MotivoRejeicao['22'] = 'Título Com Pagamento Cancelado';
				$MotivoRejeicao['21'] = 'Acerto do Controle do Participante (sem motivo)';
				$MotivoRejeicao['23'] = 'Entrada do Título em Cartório (sem motivo)';
				$MotivoRejeicao['24'] = 'Entrada rejeitada por CEP Irregular';
				$MotivoRejeicao['27'] = 'Baixa Rejeitada';
				$MotivoRejeicao['28'] = 'Débito de tarifas/custas';
				$MotivoRejeicao['30'] = 'Alteração de Outros Dados Rejeitados';
				$MotivoRejeicao['32'] = 'Instrução Rejeitada';
				$MotivoRejeicao['33'] = 'Confirmação Pedido Alteração Outros Dados (sem motivo)';
				$MotivoRejeicao['34'] = 'Retirado de Cartório e Manutenção Carteira (sem motivo)';
				$MotivoRejeicao['35'] = 'Desagendamento do débito automático';
				$MotivoRejeicao['68'] = 'Acerto dos dados do rateio de Crédito';
				$MotivoRejeicao['69'] = 'Cancelamento dos dados do rateio';

				$MotivoRejeicao['02_00'] = 'Ocorrência aceita';
				$MotivoRejeicao['02_01'] = 'Código do Banco inválido';
				$MotivoRejeicao['02_17'] = 'Data de vencimento anterior a data de emissão';
				$MotivoRejeicao['02_21'] = 'Espécie do Título inválido';
				$MotivoRejeicao['02_24'] = 'Data da emissão inválida';
				$MotivoRejeicao['02_38'] = 'Prazo para protesto inválido';
				$MotivoRejeicao['02_39'] = 'Pedido para protesto não permitido para título';
				$MotivoRejeicao['02_43'] = 'Prazo para baixa e devolução inválido';
				$MotivoRejeicao['02_45'] = 'Nome do Sacado inválido';
				$MotivoRejeicao['02_46'] = 'Tipo/num. de inscrição do Sacado inválidos';
				$MotivoRejeicao['02_47'] = 'Endereço do Sacado não informado';
				$MotivoRejeicao['02_48'] = 'CEP irregular';
				$MotivoRejeicao['02_50'] = 'CEP referente a Banco correspondente';
				$MotivoRejeicao['02_53'] = 'Nº de inscrição do Sacador/avalista inválidos (CPF/CNPJ)';
				$MotivoRejeicao['02_54'] = 'Sacador/avalista não informado';
				$MotivoRejeicao['02_67'] = 'Débito automático agendado';
				$MotivoRejeicao['02_68'] = 'Débito não agendado - erro nos dados de remessa';
				$MotivoRejeicao['02_69'] = 'Débito não agendado - Sacado não consta no cadastro de autorizante';
				$MotivoRejeicao['02_70'] = 'Débito não agendado - Cedente não autorizado pelo Sacado';
				$MotivoRejeicao['02_71'] = 'Débito não agendado - Cedente não participa da modalidade de déb.automático';
				$MotivoRejeicao['02_72'] = 'Débito não agendado - Código de moeda diferente de R$';
				$MotivoRejeicao['02_73'] = 'Débito não agendado - Data de vencimento inválida';
				$MotivoRejeicao['02_75'] = 'Débito não agendado - Tipo do número de inscrição do sacado debitado inválido';
				$MotivoRejeicao['02_86'] = 'Seu número do documento inválido';
				$MotivoRejeicao['03_02'] = 'Código do registro detalhe inválido';
				$MotivoRejeicao['03_03'] = 'Código da ocorrência inválida';
				$MotivoRejeicao['03_04'] = 'Código de ocorrência não permitida para a carteira';
				$MotivoRejeicao['03_05'] = 'Código de ocorrência não numérico';
				$MotivoRejeicao['03_07'] = 'Agência/conta/Digito - |Inválido';
				$MotivoRejeicao['03_08'] = 'Nosso número inválido';
				$MotivoRejeicao['03_09'] = 'Nosso número duplicado';
				$MotivoRejeicao['03_10'] = 'Carteira inválida';
				$MotivoRejeicao['03_16'] = 'Data de vencimento inválida';
				$MotivoRejeicao['03_18'] = 'Vencimento fora do prazo de operação';
				$MotivoRejeicao['03_20'] = 'Valor do Título inválido';
				$MotivoRejeicao['03_21'] = 'Espécie do Título inválida';
				$MotivoRejeicao['03_22'] = 'Espécie não permitida para a carteira';
				$MotivoRejeicao['03_24'] = 'Data de emissão inválida';
				$MotivoRejeicao['03_38'] = 'Prazo para protesto inválido';
				$MotivoRejeicao['03_44'] = 'Agência Cedente não prevista';
				$MotivoRejeicao['03_50'] = 'CEP irregular - Banco Correspondente';
				$MotivoRejeicao['03_63'] = 'Entrada para Título já cadastrado';
				$MotivoRejeicao['03_68'] = 'Débito não agendado - erro nos dados de remessa';
				$MotivoRejeicao['03_69'] = 'Débito não agendado - Sacado não consta no cadastro de autorizante';
				$MotivoRejeicao['03_70'] = 'Débito não agendado - Cedente não autorizado pelo Sacado';
				$MotivoRejeicao['03_71'] = 'Débito não agendado - Cedente não participa do débito Automático';
				$MotivoRejeicao['03_72'] = 'Débito não agendado - Código de moeda diferente de R$';
				$MotivoRejeicao['03_73'] = 'Débito não agendado - Data de vencimento inválida';
				$MotivoRejeicao['03_74'] = 'Débito não agendado - Conforme seu pedido, Título não registrado';
				$MotivoRejeicao['03_75'] = 'Débito não agendado – Tipo de número de inscrição do debitado inválido';
				$MotivoRejeicao['06_00'] = 'Título pago com dinheiro';
				$MotivoRejeicao['06_15'] = 'Título pago com cheque';
				$MotivoRejeicao['09_10'] = 'Baixa Comandada pelo cliente';
				$MotivoRejeicao['10_00'] = 'Baixado Conforme Instruções da Agência';
				$MotivoRejeicao['10_14'] = 'Título Protestado';
				$MotivoRejeicao['10_15'] = 'Título excluído';
				$MotivoRejeicao['10_16'] = 'Título Baixado pelo Banco por decurso Prazo';
				$MotivoRejeicao['10_20'] = 'Título Baixado e transferido para desconto';
				$MotivoRejeicao['15_00'] = 'Título pago com dinheiro';
				$MotivoRejeicao['15_15'] = 'Título pago com cheque';
				$MotivoRejeicao['17_00'] = 'Título pago com dinheiro';
				$MotivoRejeicao['17_15'] = 'Título pago com cheque';
				$MotivoRejeicao['24_48'] = 'CEP inválido';
				$MotivoRejeicao['27_04'] = 'Código de ocorrência não permitido para a carteira';
				$MotivoRejeicao['27_07'] = 'Agência/Conta/dígito inválidos';
				$MotivoRejeicao['27_08'] = 'Nosso número inválido';
				$MotivoRejeicao['27_10'] = 'Carteira inválida';
				$MotivoRejeicao['27_15'] = 'Carteira/Agência/Conta/nosso número inválidos';
				$MotivoRejeicao['27_40'] = 'Título com ordem de protesto emitido';
				$MotivoRejeicao['27_42'] = 'Código para baixa/devolução via Telebradesco inválido';
				$MotivoRejeicao['27_60'] = 'Movimento para Título não cadastrado';
				$MotivoRejeicao['27_77'] = 'Transferência para desconto não permitido para a carteira';
				$MotivoRejeicao['27_85'] = 'Título com pagamento vinculado';
				$MotivoRejeicao['28_03'] = 'Tarifa de sustação';
				$MotivoRejeicao['28_04'] = 'Tarifa de protesto';
				$MotivoRejeicao['28_08'] = 'Custas de protesto';
				$MotivoRejeicao['30_01'] = 'Código do Banco inválido';
				$MotivoRejeicao['30_04'] = 'Código de ocorrência não permitido para a carteira';
				$MotivoRejeicao['30_05'] = 'Código da ocorrência não numérico';
				$MotivoRejeicao['30_08'] = 'Nosso número inválido';
				$MotivoRejeicao['30_15'] = 'Característica da cobrança imcopátivel';
				$MotivoRejeicao['30_16'] = 'Data de vencimento inválido';
				$MotivoRejeicao['30_17'] = 'Data de vencimento anterior a data de emissão';
				$MotivoRejeicao['30_18'] = 'Vencimento fora do prazo de operação';
				$MotivoRejeicao['30_24'] = 'Data de emissão Inválida';
				$MotivoRejeicao['30_29'] = 'Valor do desconto maior/igual ao valor do Título';
				$MotivoRejeicao['30_30'] = 'Desconto a conceder não confere';
				$MotivoRejeicao['30_31'] = 'Concessão de desconto já existente ( Desconto anterior )';
				$MotivoRejeicao['30_33'] = 'Valor do abatimento inválido';
				$MotivoRejeicao['30_34'] = 'Valor do abatimento maior/igual ao valor do Título';
				$MotivoRejeicao['30_38'] = 'Prazo para protesto inválido';
				$MotivoRejeicao['30_39'] = 'Pedido de protesto não permitido para o Título';
				$MotivoRejeicao['30_40'] = 'Título com ordem de protesto emitido';
				$MotivoRejeicao['30_42'] = 'Código para baixa/devolução inválido';
				$MotivoRejeicao['30_60'] = 'Movimento para Título não cadastrado';
				$MotivoRejeicao['30_85'] = 'Título com Pagamento Vinculado.';
				$MotivoRejeicao['32_01'] = 'Código do Banco inválido';
				$MotivoRejeicao['32_02'] = 'Código do registro detalhe inválido';
				$MotivoRejeicao['32_04'] = 'Código de ocorrência não permitido para a carteira';
				$MotivoRejeicao['32_05'] = 'Código de ocorrência não numérico';
				$MotivoRejeicao['32_07'] = 'Agência/Conta/dígito inválidos';
				$MotivoRejeicao['32_08'] = 'Nosso número inválido';
				$MotivoRejeicao['32_10'] = 'Carteira inválida';
				$MotivoRejeicao['32_15'] = 'Características da cobrança incompatíveis';
				$MotivoRejeicao['32_16'] = 'Data de vencimento inválida';
				$MotivoRejeicao['32_17'] = 'Data de vencimento anterior a data de emissão';
				$MotivoRejeicao['32_18'] = 'Vencimento fora do prazo de operação';
				$MotivoRejeicao['32_20'] = 'Valor do título inválido';
				$MotivoRejeicao['32_21'] = 'Espécie do Título inválida';
				$MotivoRejeicao['32_22'] = 'Espécie não permitida para a carteira';
				$MotivoRejeicao['32_24'] = 'Data de emissão inválida';
				$MotivoRejeicao['32_28'] = 'Código de desconto via Telebradesco inválido';
				$MotivoRejeicao['32_29'] = 'Valor do desconto maior/igual ao valor do Título';
				$MotivoRejeicao['32_30'] = 'Desconto a conceder não confere';
				$MotivoRejeicao['32_31'] = 'Concessão de desconto - Já existe desconto anterior';
				$MotivoRejeicao['32_33'] = 'Valor do abatimento inválido';
				$MotivoRejeicao['32_34'] = 'Valor do abatimento maior/igual ao valor do Título';
				$MotivoRejeicao['32_36'] = 'Concessão abatimento - Já existe abatimento anterior';
				$MotivoRejeicao['32_38'] = 'Prazo para protesto inválido';
				$MotivoRejeicao['32_39'] = 'Pedido de protesto não permitido para o Título';
				$MotivoRejeicao['32_40'] = 'Título com ordem de protesto emitido';
				$MotivoRejeicao['32_41'] = 'Pedido cancelamento/sustação para Título sem instrução de protesto';
				$MotivoRejeicao['32_42'] = 'Código para baixa/devolução inválido';
				$MotivoRejeicao['32_45'] = 'Nome do Sacado não informado';
				$MotivoRejeicao['32_46'] = 'Tipo/número de inscrição do Sacado inválidos';
				$MotivoRejeicao['32_47'] = 'Endereço do Sacado não informado';
				$MotivoRejeicao['32_48'] = 'CEP Inválido';
				$MotivoRejeicao['32_50'] = 'CEP referente a um Banco correspondente';
				$MotivoRejeicao['32_53'] = 'Tipo de inscrição do sacador avalista inválidos';
				$MotivoRejeicao['32_60'] = 'Movimento para Título não cadastrado';
				$MotivoRejeicao['32_85'] = 'Título com pagamento vinculado';
				$MotivoRejeicao['32_86'] = 'Seu número inválido';
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