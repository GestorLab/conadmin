<?
# CNAB 400
# Banco Bradesco

function retorno(){
	global $con, $linArquivoRetorno, $local_IdLoja ,$local_IdLocalRecebimento ,$local_IdArquivoRetorno, $local_Login, $return_dados;
	
	$Dados[ValorTotal]		= 0;
	$Dados[ValorTotalTaxa]	= 0;
	$Dados[QtdLancamentos]	= 0;
	
	$tr_i = 0;	
	$log = "";
	$VerificaAgenciaConta	= "";
	
	$ArqRetorno = file($linArquivoRetorno[EndArquivo]);
	if(strlen($ArqRetorno[0]) == 402 || strlen($ArqRetorno[0]) == 401){
		for($i=0; $i<count($ArqRetorno);$i++){

			// Header do Arquivo
			if($i == 0){
				// DataGravacaoMovimento
				$Dados[DataGravacaoMovimento] = substr($ArqRetorno[$i],95-1,6);
				
				$Dados[DataGravacaoMovimentoDia] = substr($Dados[DataGravacaoMovimento],0,2);
				$Dados[DataGravacaoMovimentoMes] = substr($Dados[DataGravacaoMovimento],2,2);
				$Dados[DataGravacaoMovimentoAno] = substr(date("Y"),0,2).substr($Dados[DataGravacaoMovimento],4,2);				
				
				$Dados[DataGravacaoMovimento] = $Dados[DataGravacaoMovimentoAno]."-".$Dados[DataGravacaoMovimentoMes]."-".$Dados[DataGravacaoMovimentoDia];
				$Dados[NumSeqArquivo] = (int)substr($ArqRetorno[$i],395-1,6);

				$Dados[Convenio]	  = substr($ArqRetorno[$i],27-1,20);

				#$VerificaAgenciaConta = verificaAgenciaContaArquivoRetorno($local_IdLoja, '', '', $local_IdLocalRecebimento, $Dados[Convenio]);
			
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

	if($VerificaAgenciaConta != ''){
		$local_Erro = $VerificaAgenciaConta;
	}

	return $local_Erro;
}
?>