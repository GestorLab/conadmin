<?
function retorno(){
	global $con, $linArquivoRetorno, $local_IdLoja ,$local_IdLocalRecebimento , $local_EnviarEmailConfirmacaoPagamento, $local_IdArquivoRetorno, $local_Login;
	
	$Dados[ValorTotal]		= 0;
	$Dados[ValorTotalTaxa]	= 0;
	$Dados[QtdLancamentos]	= 0;
	
	$tr_i = 0;
	$sql  = "START TRANSACTION;";
	mysql_query($sql,$con);
	
	$log = "";
	
	$ArqRetorno = file($linArquivoRetorno[EndArquivo]);
	if(strlen($ArqRetorno[0]) == 152){
		for($i=1; $i<count($ArqRetorno)-1;$i++){

			$Dados[NumeroDocumento] = "";
			$Dados[NossoNumero]		= "";

			if(substr($ArqRetorno[$i],1-1,1) == 'F'){
				$Dados[NossoNumero]				= (int)(substr($ArqRetorno[$i],70-1,15));
				
				$Dados[ValorLancamento]			= (int)substr($ArqRetorno[$i],53-1,13);
				$Dados[ValorLancamento]			.= ".".substr($ArqRetorno[$i],66-1,2);

				$Dados[AgenciaRecebimento] 		= substr($ArqRetorno[$i],27-1,4);
					
				$Dados[ValorPago]				= $Dados[ValorLancamento];

				$Dados[ValorTotal]				+= $Dados[ValorPago];

				$Dados[MotivoRejeicao]			= substr($ArqRetorno[$i],68-1,2);

				$MotivoRejeicao['XX'] = 'Débito não efetuado';

				$MotivoRejeicao['XX_01'] = 'Insuficiência de fundos';
				$MotivoRejeicao['XX_02'] = 'Conta corrente não cadastrada';
				$MotivoRejeicao['XX_04'] = 'Outras restrições';
				$MotivoRejeicao['XX_05'] = 'Valor do débito excede valor limite aprovado';
				$MotivoRejeicao['XX_10'] = 'Agência em regime de encerramento';
				$MotivoRejeicao['XX_12'] = 'Valor inválido';
				$MotivoRejeicao['XX_13'] = 'Data de lançamento inválida';
				$MotivoRejeicao['XX_14'] = 'Agência inválida';
				$MotivoRejeicao['XX_15'] = 'Conta corrente inválida';
				$MotivoRejeicao['XX_18'] = 'Data do débito anterior à do processamento';
				$MotivoRejeicao['XX_30'] = 'Sem contrato de débito automático';

				$Dados[MotivoRejeicao2]	 = $Dados[MotivoRejeicao];
		
				// DataRecebimento
				$Dados[DataRecebimento] = substr($ArqRetorno[$i],45-1,8);
			
	 			$Dados[DataRecebimentoDia] = substr($Dados[DataRecebimento],6,2);
				$Dados[DataRecebimentoMes] = substr($Dados[DataRecebimento],4,2);
				$Dados[DataRecebimentoAno] = substr($Dados[DataRecebimento],0,4);
				
				$Dados[DataRecebimento] = $Dados[DataRecebimentoAno]."-".$Dados[DataRecebimentoMes]."-".$Dados[DataRecebimentoDia];
				
				$LogErro = false;

				switch($Dados[MotivoRejeicao]){
					case '00':
						// Quita título
						include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
						break;

					case '31':
						// Quita título
						include("retorno/tipo_retorno/arquivo_retorno_baixa.php");
						break;

					default:
						$LogErro = true;
						$Dados[MotivoRejeicao] = "XX";
						include("retorno/tipo_retorno/salva_log_conta_receber.php");
						include("retorno/tipo_retorno/salva_log_retorno.php");
						include("retorno/tipo_retorno/bloqueia_conta_receber.php");
						break;
				}
			}
		}
	}

#	include("retorno/tipo_retorno/arquivo_retorno_baixa_arquivo.php");
	
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
