<?
	function retorno(){
		global $con, $linArquivoRetorno, $local_IdLoja ,$local_IdLocalRecebimento, $local_EnviarEmailConfirmacaoPagamento, $local_IdArquivoRetorno, $local_Login;
		
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
					$Dados[DataGravacaoMovimento] = substr($ArqRetorno[$i],95-1,8);
					
					$Dados[DataGravacaoMovimentoAno] = substr($Dados[DataGravacaoMovimento],0,4);
					$Dados[DataGravacaoMovimentoMes] = substr($Dados[DataGravacaoMovimento],4,2);
					$Dados[DataGravacaoMovimentoDia] = substr($Dados[DataGravacaoMovimento],6,2);
					
					$Dados[DataGravacaoMovimento] = $Dados[DataGravacaoMovimentoAno]."-".$Dados[DataGravacaoMovimentoMes]."-".$Dados[DataGravacaoMovimentoDia];
					
					$Dados[NumSeqArquivo] = (int)substr($ArqRetorno[$i],111-1,7);
				
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
					$Dados[NossoNumero]	 			= (int)substr($ArqRetorno[$i],51-1,5);
					$Dados[NumeroDocumento]			= (int)substr($ArqRetorno[$i],51-1,5);
					
					$Dados[ValorLancamento]			= (int)substr($ArqRetorno[$i],153-1,11);
					$Dados[ValorLancamento]			.= ".".substr($ArqRetorno[$i],164-1,2);

					$Dados[NumeroSeqRetBanco]		= (int)substr($ArqRetorno[$i],395-1,6);

					$Dados[ValorJurosMora]			= (int)substr($ArqRetorno[$i],267-1,11);
					$Dados[ValorJurosMora]			.= ".".substr($ArqRetorno[$i],278-1,2);

					$Dados[ValorMulta]			= (int)substr($ArqRetorno[$i],280-1,11);
					$Dados[ValorMulta]			.= ".".substr($ArqRetorno[$i],291-1,2);

					$Dados[ValorJurosMora]          += $Dados[ValorMulta];
					
					$Dados[ValorTitulo]				= (int)substr($ArqRetorno[$i],153-1,11);
					$Dados[ValorTitulo]				.= ".".substr($ArqRetorno[$i],164-1,2);

					$Dados[ValorDesconto]			= (int)substr($ArqRetorno[$i],241-1,11);
					$Dados[ValorDesconto]			.= ".".substr($ArqRetorno[$i],252-1,2);
					
					$Dados[ValorPago]				= (int)substr($ArqRetorno[$i],254-1,11);
					$Dados[ValorPago]				.= ".".substr($ArqRetorno[$i],265-1,2);

					if($i%2 == 0){
						$Dados[ValorTotal] += $Dados[ValorPago];
					}
					
					// DataRecebimento
					$Dados[DataRecebimento] = substr($ArqRetorno[$i],111-1,6);
				
					$Dados[DataRecebimentoDia] = substr($Dados[DataRecebimento],0,2);
					$Dados[DataRecebimentoMes] = substr($Dados[DataRecebimento],2,2);
					$Dados[DataRecebimentoAno] = substr(date("Y"),0,2).substr($Dados[DataRecebimento],4,2);
					
					$Dados[DataRecebimento] = $Dados[DataRecebimentoAno]."-".$Dados[DataRecebimentoMes]."-".$Dados[DataRecebimentoDia];
		
					$MotivoRejeicao['02'] = 'Entrada confirmada';
					$MotivoRejeicao['03'] = 'Entrada rejeitada';
					$MotivoRejeicao['06'] = 'Liquida��o normal';
					$MotivoRejeicao['09'] = 'Baixado automaticamente via arquivo'; 
					$MotivoRejeicao['10'] = 'Baixado conforme instru��es da cooperativa de cr�dito';
					$MotivoRejeicao['12'] = 'Abatimento concedido';
					$MotivoRejeicao['13'] = 'Abatimento cancelado';
					$MotivoRejeicao['14'] = 'Vencimento alterado';
					$MotivoRejeicao['15'] = 'Liquida��o em cart�rio';
					$MotivoRejeicao['17'] = 'Liquida��o ap�s baixa';
					$MotivoRejeicao['19'] = 'Confirma��o de recebimento de instru��o de protesto';
					$MotivoRejeicao['20'] = 'Confirma��o de recebimento de instru��o de susta��o de protesto';
					$MotivoRejeicao['23'] = 'Entrada de t�tulo em cart�rio';
					$MotivoRejeicao['24'] = 'Entrada rejeitada por CEP irregular';
					$MotivoRejeicao['27'] = 'Baixa rejeitada';
					$MotivoRejeicao['28'] = 'Tarifa';
					$MotivoRejeicao['29'] = 'Rejei��o do sacado';
					$MotivoRejeicao['30'] = 'Altera��o rejeitada';
					$MotivoRejeicao['32'] = 'Instru��o rejeitada';
					$MotivoRejeicao['33'] = 'Confirma��o de pedido de altera��o de outros dados';
					$MotivoRejeicao['34'] = 'Retirado de cart�rio e manuten��o em carteira';
					$MotivoRejeicao['35'] = 'Aceite do sacado'; 


					$MotivoRejeicao['02_00'] = 'Ocorr�ncia aceita';
					$MotivoRejeicao['03_02'] = 'C�digo do registro detalhe inv�lido';
					$MotivoRejeicao['03_03'] = 'C�digo da ocorr�ncia inv�lido';
					$MotivoRejeicao['03_04'] = 'C�digo de ocorr�ncia n�o permitida para a carteira';
					$MotivoRejeicao['03_05'] = 'C�digo de ocorr�ncia n�o num�rico';
					$MotivoRejeicao['03_08'] = 'Nosso n�mero inv�lido';
					$MotivoRejeicao['03_09'] = 'Nosso n�mero duplicado';
					$MotivoRejeicao['03_10'] = 'Carteira inv�lida';
					$MotivoRejeicao['03_16'] = 'Data de vencimento inv�lida';
					$MotivoRejeicao['03_18'] = 'Vencimento fora do prazo de opera��o';
					$MotivoRejeicao['03_20'] = 'Valor do t�tulo inv�lido';
					$MotivoRejeicao['03_21'] = 'Esp�cie do t�tulo inv�lida';

					$MotivoRejeicao['03_22'] = 'Esp�cie n�o permitida para a carteira';
					$MotivoRejeicao['03_24'] = 'Data de emiss�o inv�lida';
					$MotivoRejeicao['03_38'] = 'Prazo para protesto inv�lido';
					$MotivoRejeicao['03_44'] = 'Cooperativa de cr�dito/agencia cedente n�o prevista';
					$MotivoRejeicao['03_45'] = 'Nome do sacado inv�lido';
					$MotivoRejeicao['03_46'] = 'Tipo/n�mero de inscri��o do sacado inv�lidos';
					$MotivoRejeicao['03_47'] = 'Endere�o do sacado n�o informado';
					$MotivoRejeicao['03_48'] = 'CEP irregular';
					$MotivoRejeicao['03_49'] = 'N�mero de Inscri��o do sacador/avalista inv�lido';
					$MotivoRejeicao['03_50'] = 'Sacador/avalista n�o informado';
					$MotivoRejeicao['03_63'] = 'Entrada para titulo j� cadastrado';

					$MotivoRejeicao['03_A1'] = 'Pra�a do sacado n�o cadastrada';
					$MotivoRejeicao['03_A2'] = 'Tipo de cobran�a de t�tulo divergente com a pra�a do sacado.';
					$MotivoRejeicao['03_A3'] = 'Cooperativa/ag�ncia deposit�ria divergente: atulaliza o cadastro de pra�as da Coop./ag�ncia cedente';
					$MotivoRejeicao['03_B4'] = 'Tipo de moeda inv�lido';
					$MotivoRejeicao['03_B5'] = 'Tipo de desconto/juros inv�lido';
					$MotivoRejeicao['03_B6'] = 'Mensagem padr�o n�o cadastrada';
					$MotivoRejeicao['03_B7'] = 'Seu n�mero inv�lido';
					$MotivoRejeicao['03_B8'] = 'Percentual de multa inv�lido';
					$MotivoRejeicao['03_B9'] = 'Valor ou percentual de juros inv�lido';
					$MotivoRejeicao['03_C6'] = 'T�tulo j� liquidado';

					$MotivoRejeicao['03_D5'] = 'Quantidade inv�lida no pedido de bloquetos pr�-impressos da cobran�a sem registro';
					$MotivoRejeicao['03_D7'] = 'Cidade ou Estado do sacado n�o informado';
					$MotivoRejeicao['03_F6'] = 'Nosso n�mero / n�mero da parcela fora de sequencia - total de parcelas inv�ldo';
					$MotivoRejeicao['03_H7'] = 'Esp�cie de documento necessita cedente ou avalista PJ';
					$MotivoRejeicao['03_H9'] = 'Dados do titulo n�o conferem com disquete';
					$MotivoRejeicao['03_l1'] = 'Sacado e sacador abalista s�o a mesma pessoa';
					$MotivoRejeicao['03_l2'] = 'Aguardar um dia �til ap�s o vencimento para protestar';
					$MotivoRejeicao['03_l3'] = 'Data do vencimento rasurado';
					$MotivoRejeicao['03_l4'] = 'Vencimento - extenso n�o confere com o n�mero';
					$MotivoRejeicao['03_l5'] = 'Falata data de vencimento no t�tulo';
					$MotivoRejeicao['03_l6'] = 'DM/DMI sem comprovante autenticado ou declara��o';
					$MotivoRejeicao['03_l7'] = 'Comprovante ilegivel para conferencia e microfilmagem';
					$MotivoRejeicao['03_l8'] = 'Nome solicitado n�o confere com emitente ou sacado';
					$MotivoRejeicao['03_l9'] = 'Confirmar se s�o 2 emitentes. Se sim, indicar os dados dos 2';

					$MotivoRejeicao['03_J1'] = 'Endere�o do sacado igual ao do sacador ou do portador';
					$MotivoRejeicao['03_J2'] = 'Endere�o do apresentante incompleto ou n�o informado';
					$MotivoRejeicao['03_J3'] = 'Rua/n�mero inexistente no endere�o';
					$MotivoRejeicao['03_J4'] = 'Falta endosso do favorecido para o apresentante';
					$MotivoRejeicao['03_J5'] = 'Data da emiss�o rasurada';
					$MotivoRejeicao['03_J6'] = 'Falta assinatura do sacador no titulo';
					$MotivoRejeicao['03_J7'] = 'Nome do apresentate n�o informado/incompleto/incorreto';
					$MotivoRejeicao['03_J8'] = 'Erro de preenchimento do t�tulo';
					$MotivoRejeicao['03_J9'] = 'Titulo com direito de regresso vencido';
					
					$MotivoRejeicao['03_K1'] = 'Titulo apresentado em duplicidade ';
					$MotivoRejeicao['03_K2'] = 'Titulo j� protestad';
					$MotivoRejeicao['03_K3'] = 'Letra de cambio vencida - falta aceite do sacado';
					$MotivoRejeicao['03_K4'] = 'Falta declara��o de saldo assinada no t�tulo';
					$MotivoRejeicao['03_K5'] = 'Contrato de cambio - Falta conta gr�fica';
					$MotivoRejeicao['03_K6'] = 'Aus�ncia do documento f�sico';
					$MotivoRejeicao['03_K7'] = 'Sacado falecido';
					$MotivoRejeicao['03_K8'] = 'Sacado apresentou quita��o do t�tulo';
					$MotivoRejeicao['03_K9'] = 'Titulo de outra jurisdi��o territorial';

					$MotivoRejeicao['03_L2'] = 'Sacado consta na lista de fal�ncia';
					$MotivoRejeicao['03_L3'] = 'Apresentante n�o aceita publica��o de edital';
					$MotivoRejeicao['03_L4'] = 'Dados dos Sacado em Branco ou inv�lido';

					$MotivoRejeicao['06_00'] = 'Ocorr�ncia aceita';
					$MotivoRejeicao['06_A8'] = 'Recebimento da liquida��o fora da rede SICREDI - via compensa��o eletr�nica';
					$MotivoRejeicao['06_H5'] = 'Recebimento de liquida��o fora da rede SICREDI - VLB Inferior - Via Compensa��o';
					$MotivoRejeicao['06_H6'] = 'Recebimento de liquida��o fora da rede SICREDI - VLB Superior - Via Compensa��o';
					$MotivoRejeicao['06_H8'] = 'Recebimento de liquida��o fora da rede SICREDI - Conting�ncia Via Compensa��o';
					$MotivoRejeicao['06_X1'] = 'Regulariza��o centralizadora - Rede SICREDI';
					$MotivoRejeicao['06_X2'] = 'Regulariza��o centralizadora - Compensa��o';
					$MotivoRejeicao['06_X3'] = 'Regulariza��o centralizadora - Banco correspondente';
					$MotivoRejeicao['06_X4'] = 'Regulariza��o centralizadora - VLB Inferior - via compensa��o';
					$MotivoRejeicao['06_X5'] = 'Regulariza��o centralizadora - VLB Superior - via compensa��o';
					$MotivoRejeicao['06_X0'] = 'Pago com cheque';
					$MotivoRejeicao['06_X6'] = 'Pago com cheque - bloqueado 24 horas';
					$MotivoRejeicao['06_X7'] = 'Pago com cheque - bloqueado 48 horas';
					$MotivoRejeicao['06_X8'] = 'Pago com cheque - bloqueado 72 horas';
					$MotivoRejeicao['06_X9'] = 'Pago com cheque - bloqueado 96 horas';
					$MotivoRejeicao['06_XA'] = 'Pago com cheque - bloqueado 120 horas';
					$MotivoRejeicao['06_XB'] = 'Pago com cheque - bloqueado 144 horas';

					$MotivoRejeicao['09_00'] = 'Ocorr�ncia aceita';
					$MotivoRejeicao['10_00'] = 'Ocorr�ncia aceita';
					$MotivoRejeicao['10_14'] = 'Titulo protestado';
					$MotivoRejeicao['12_00'] = 'Ocorr�ncia aceita';
					$MotivoRejeicao['13_00'] = 'Ocorr�ncia aceita';
					$MotivoRejeicao['14_00'] = 'Ocorr�ncia aceita';
					$MotivoRejeicao['15_00'] = 'Ocorr�ncia aceita';
					$MotivoRejeicao['17_00'] = 'Ocorr�ncia aceita';

					$MotivoRejeicao['17_A8'] = 'Recebimento da liquida��o fora da rede SICREDI - via compensa��o eletr�nica';
					$MotivoRejeicao['17_H5'] = 'Recebimento de liquida��o fora da rede SICREDI - VLB Inferior - Via Compensa��o';
					$MotivoRejeicao['17_H6'] = 'Recebimento de liquida��o fora da rede SICREDI - VLB Superior - Via Comprensa��o';
					$MotivoRejeicao['17_H8'] = 'Recebimento de liquida��o fora da rede SICREDI - Contingencia Via Compensa��o';

					$MotivoRejeicao['19_A']  = 'Aceito';
					$MotivoRejeicao['19_D']  = 'Desprezado';

					$MotivoRejeicao['20_00'] = 'Ocorr�ncia aceita';

					$MotivoRejeicao['23_G2'] = 'Titulo aceito: sem a assinatura do sacado';
					$MotivoRejeicao['23_G3'] = 'Titulo aceito: rasurado ou rasgado';
					$MotivoRejeicao['23_G4'] = 'Titulo aceito: falta titulo (cooperativa/ag. cedente dever� envi�-lo)';
					$MotivoRejeicao['23_G6'] = 'Titulo aceito: sem endosso ou cedente irregular';
					$MotivoRejeicao['23_G7'] = 'Titulo aceito : valor por extenso diferente do valor num�rico';

					$MotivoRejeicao['24_48'] = 'CEP irregular';

					$MotivoRejeicao['27_00'] = 'Ocorr�ncia aceita';
					$MotivoRejeicao['27_07'] = 'Cooperativa/agencia/conta/d�gito inv�lidos';
					$MotivoRejeicao['27_08'] = 'Nosso n�mero inv�lido';
					$MotivoRejeicao['27_10'] = 'Carteira inv�lida';
					$MotivoRejeicao['27_15'] = 'Cooperativa/carteira/agencia/conta/nosso numero invalidos';
					$MotivoRejeicao['27_40'] = 'Titulo com ordem de protesto emitida';
					$MotivoRejeicao['27_60'] = 'Movimento para titulo n�o cadastrado';
					$MotivoRejeicao['27_A1'] = 'Pra�a do sacado n�o cadastrada';
					$MotivoRejeicao['27_C6'] = 'Titulo j� liquidado';
					$MotivoRejeicao['27_C7'] = 'Titulo j� baixado';

					$MotivoRejeicao['28_03'] = 'Tarifa de susta��o';
					$MotivoRejeicao['28_04'] = 'Tarifa de protesto';
					$MotivoRejeicao['28_08'] = 'Tarifa de custas de protesto';
					$MotivoRejeicao['28_A9'] = 'Tarifa de manuten��o de titulo vencido';
					$MotivoRejeicao['28_B1'] = 'Tarifa de baixa da carteira';
					$MotivoRejeicao['28_B2'] = 'Motivo desconhecido';
					$MotivoRejeicao['28_B3'] = 'Tarifa de registro de entrado do titulo';
					$MotivoRejeicao['28_E1'] = 'Motivo desconhecido';
					$MotivoRejeicao['28_F5'] = 'Tarifa de entrado na rede SICREDI';

					$MotivoRejeicao['29_M2'] = 'Reconhecimento da divida pelo sacado';

					$MotivoRejeicao['30_01'] = 'C�digo do banco inv�lido';
					$MotivoRejeicao['30_05'] = 'C�digo de ocorrencia n�o num�rico';
					$MotivoRejeicao['30_08'] = 'Nosso n�mero inv�lido';
					$MotivoRejeicao['30_15'] = 'Cooperativa/carteira/ag�ncia/conta/nosso n�mero inv�lidos';
					$MotivoRejeicao['30_28'] = 'Motivo desconhecido';
					$MotivoRejeicao['30_29'] = 'Valor do desconto maior/igual ao valor do t�tulo';
					$MotivoRejeicao['30_33'] = 'Valor do abatimento inv�lido';
					$MotivoRejeicao['30_34'] = 'Valor do abatimento maior/igual ao valor do t�tulo';
					$MotivoRejeicao['30_38'] = 'Prazo para protesto inv�lido';
					$MotivoRejeicao['30_39'] = 'Pedido para protesto n�o permitido para o t�tulo';
					$MotivoRejeicao['30_40'] = 'T�tulo com ordem de protesto emitida';
					$MotivoRejeicao['30_60'] = 'Movimento para titulo n�o cadastrado';
					$MotivoRejeicao['30_C6'] = 'Titulo j� liquidado';
					$MotivoRejeicao['30_C7'] = 'Titulo j� baixado';

					$MotivoRejeicao['32_01'] = 'C�digo do banco invalido';
					$MotivoRejeicao['32_02'] = 'C�digo do registro detalhe inv�lido';
					$MotivoRejeicao['32_03'] = 'C�digo da ocorr�ncia inv�lido';
					$MotivoRejeicao['32_04'] = 'C�digo de ocorr�ncia n�o permitida para a carteira';
					$MotivoRejeicao['32_05'] = 'C�digo de ocorrencia n�o num�rico';
					$MotivoRejeicao['32_07'] = 'Cooperativa/agencia/conta/digito inv�lidos';
					$MotivoRejeicao['32_08'] = 'Nosso n�mero inv�lido';
					$MotivoRejeicao['32_10'] = 'Carteira inv�lida';
					$MotivoRejeicao['32_15'] = 'Cooperativa/carteira/ag�ncia/conta/nosso n�mero inv�lidos';
					$MotivoRejeicao['32_16'] = 'Data de vencimento inv�lida';
					$MotivoRejeicao['32_17'] = 'Data de vencimento anterior � data de emiss�o';
					$MotivoRejeicao['32_21'] = 'Esp�cie do t�tulo inv�lida';
					$MotivoRejeicao['32_22'] = 'Esp�cie n�o permitida para a carteira';
					$MotivoRejeicao['32_24'] = 'Data de emis�o inv�lida';
					$MotivoRejeicao['32_29'] = 'Valor do desconto maior/igual ao valor do t�tulo';
					$MotivoRejeicao['32_31'] = 'Concess�o de desconto - existe desconto anterior';
					$MotivoRejeicao['32_33'] = 'Valor do abatimento inv�lido';
					$MotivoRejeicao['32_34'] = 'Valor do abatimento maior/igual ao valor do t�tulo';
					$MotivoRejeicao['32_36'] = 'Concess�o de abatimento - existe abatimento anterior';
					$MotivoRejeicao['32_38'] = 'Prazo para protesto inv�lido';
					$MotivoRejeicao['32_39'] = 'Pedido para protesto n�o permitido para t�tulo';
					$MotivoRejeicao['32_40'] = 'T�tulo com ordem de protesto emitida';
					$MotivoRejeicao['32_41'] = 'Pedido cancelamento/susta��o sem instru��o de protesto';
					$MotivoRejeicao['32_45'] = 'Nome do sacado inv�lido';
					$MotivoRejeicao['32_46'] = 'Tipo/n�mero de inscri��o do sacado inv�lidos';
					$MotivoRejeicao['32_47'] = 'Endere�o do sacado n�o informado';
					$MotivoRejeicao['32_60'] = 'Movimento para t�tulo n�o cadastrado';
					$MotivoRejeicao['32_A1'] = 'Pra�a do sacado n�o cadastrada';
					$MotivoRejeicao['32_A2'] = 'Tipo de cobran�a do titulo divergente com a pra�a do sacado';
					$MotivoRejeicao['32_A4'] = 'Cedente n�o cadastro ou possui CGC/CIC inv�lido';
					$MotivoRejeicao['32_A5'] = 'Sacado n�o cadastrado';
					$MotivoRejeicao['32_A6'] = 'Data de instru��o/ocorr�ncia inv�lida';
					$MotivoRejeicao['32_B4'] = 'Tipo de moeda inv�lido';
					$MotivoRejeicao['32_B5'] = 'Tipo de desconto/juros inv�lido';
					$MotivoRejeicao['32_B6'] = 'Mensagem padr�o n�o cadastrada';
					$MotivoRejeicao['32_B7'] = 'Seu n�mero inv�lido';
					$MotivoRejeicao['32_B8'] = 'Percentual de multa inv�lido';
					$MotivoRejeicao['32_B9'] = 'Valor ou percentual de juros inv�lido';
					$MotivoRejeicao['32_C6'] = 'Titulo j� liquidado';
					$MotivoRejeicao['32_C7'] = 'Titulo j� baixado';
					$MotivoRejeicao['32_D2'] = 'Esp�cie de documento n�o permite protesto de t�tulo';
					$MotivoRejeicao['32_F7'] = 'Falta de comprovante de presta��o de servi�o';
					$MotivoRejeicao['32_F8'] = 'Nome do cedente incompleto / incorreto';
					$MotivoRejeicao['32_F9'] = 'CNPJ / CPF do sacador Incompativel com a esp�cie';
					$MotivoRejeicao['32_G1'] = 'CNPJ / CPF do sacador Incompativel com a esp�cie';
					$MotivoRejeicao['32_G5'] = 'Pra�a de pagamento incompativel com o endere�o';
					$MotivoRejeicao['32_G8'] = 'Saldo maior que o valor do titulo';
					$MotivoRejeicao['32_G9'] = 'Tipo de endosso inv�lido';
					$MotivoRejeicao['32_H1'] = 'Nome do sacador incompleto / Incorreto';
					$MotivoRejeicao['32_L3'] = 'Apresentante n�o aceita publica��o de edital';
					$MotivoRejeicao['32_L4'] = 'Dados do Sacado em Branco ou inv�lido';
					$MotivoRejeicao['32_J8'] = 'Erro de preenchimento do titulo';
					$MotivoRejeicao['35_M1'] = 'N�o reconhecimento da d�vida pelo sacado';

					$Dados[MotivoRejeicao]	= substr($ArqRetorno[$i],109-1,2);
					$Dados[MotivoRejeicao2] = substr($ArqRetorno[$i],319-1,2);

					switch($Dados[MotivoRejeicao]){
						case '02':
							// Salva no log
							include("retorno/tipo_retorno/salva_log_conta_receber.php");
							break;
						case '06':
							// Quita o t�tulo
							include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
							break;
						case '09':
							switch($Dados[MotivoRejeicao2]){
								case '00': 
									// Baixado automaticamento via arquivo
									include("retorno/tipo_retorno/salva_log_conta_receber.php");
									break;
								default:
									$LogErro = true;
									include("retorno/tipo_retorno/salva_log_conta_receber.php");
									include("retorno/tipo_retorno/salva_log_retorno.php");
									include("retorno/tipo_retorno/bloqueia_conta_receber.php");
									break;
							}
							break;
						case '10':
							switch($Dados[MotivoRejeicao2]){
								case '00': 
									// Baixado conforme instru��es da cooperativa de cr�dito
									include("retorno/tipo_retorno/salva_log_conta_receber.php");
									break;
								case '14': 
									// Titulo protestado
									include("retorno/tipo_retorno/salva_log_conta_receber.php");
									break;
								default:
									$LogErro = true;
									include("retorno/tipo_retorno/salva_log_conta_receber.php");
									include("retorno/tipo_retorno/salva_log_retorno.php");
									include("retorno/tipo_retorno/bloqueia_conta_receber.php");
									break;
							}
							break;
						case '14':
							// Salva no log
							include("retorno/tipo_retorno/salva_log_conta_receber.php");
							break;
						case '28':
							// Salva no log
							include("retorno/tipo_retorno/salva_log_conta_receber.php");
							break;
						case '32':
							switch($Dados[MotivoRejeicao2]){
								case 'C7':
									include("retorno/tipo_retorno/salva_log_conta_receber.php");
									break;
								default:
									$LogErro = true;
									include("retorno/tipo_retorno/salva_log_conta_receber.php");
									include("retorno/tipo_retorno/salva_log_retorno.php");
									include("retorno/tipo_retorno/bloqueia_conta_receber.php");
									break;
							}
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