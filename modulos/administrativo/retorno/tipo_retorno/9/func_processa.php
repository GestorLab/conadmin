<?
# CNAB 400
# Centro  Nacional  de  Automação  Bancária

#TipoRegistro
#Cabecalho = 0
#Registro = 1
#Trailler = 9

#QtdLinhaRegistro
#QtdLinhaRegistro = 1
#RegistroPos1 = 0

#TipoRegistro     §Campo                      §PosInicial     §PosFinal     §Descrição                                                     §Conteúdo
#0                §DataGravacaoMovimento      §95             §100          §Data de Gravação do Movimento                                 §Numérico no formato Dia/Mês/Ano (DDMMAA)
#0                §NumSeqArquivo              §395            §400          §Númro seqüencial do arquivo

#1                §NossoNumero                §63             §78           §Identificação do Título no banco                              §Numérico. Código do documento informado pela empresa
#1                §DataCredito                §83             §88           §Data de Crédito                                               §Numérico no formato Dia/Mês/Ano (DDMMAA)
#1                §IdMoeda                    §89             §89           §Moeda                                                         §Identificador da Moeda 9 - Real
#1                §IdOcorrencia               §109            §110          §Identificação da Ocorrência                                   §Número referênte a ocorrência
#1                §DataRecebimento            §111            §116          §Data de Recebimento da Parcela                                §Numérico no formato Dia/Mês/Ano (DDMMAA)
#1                §ValorLancamento            §153            §165          §Valor nominal da parcela                                      §Numérico identificando o valor da parcela na moeda corrente
#1                §BancoRecebimento           §166            §168          §Banco onde ocorreu a liquidação                               §Numérico com o código do banco onde ocorreu a liquidação
#1                §AgenciaRecebimento         §169            §173          §Agência onde ocorreu a liquidação                             §Numérico com o código da agência onde ocorreu a liquidação
#1                §EspecieTitulo              §174            §175          §Espécie Título                                                §Número. Código referênte a espécie do título
#1                §ValorDesconto              §241            §253          §Valor do desconto concedido                                   §Numérico contendo o valor do desconto concedido na liquidação
#1                §ValorPago                  §254            §266          §Valor pago                                                    §Numérico contendo o valor pago pelo sacado
#1                §ValorJurosMora             §267            §279          §Valor de mora pago pelo sacado                                §Numérico contendo o valor do acréscimo pago pelo sacado
#1                §StatusParcela              §309            §309          §Indica se é parcela correta, regularizada ou pendente         §0 - Correta<br />1 - Regularizada<br />2 - Pendente de regularização
#1                §NumeroLancamentoExtrato    §310            §315          §Número que identifica o lançamento no extrato                 §Contém o número referente ao lançamento no extrato
#1                §TipoLiquidacao             §342            §342          §Tipo liquidação                                               §1 - Pgto em Cheque<br />2 - Pgto em Dinheiro<br />3 - Pgto por Compensação
#1                §OrigemTarifa               §343            §343          §Origem da Tarifa                                              §1 - Liq Caixa<br />2 - Liq Auto Atendimento<br />3 - Interbancária
#1                §NumeroSeqRetBanco          §395            §400          §Número seqüencial do registro no arquivo                      §Numérico
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