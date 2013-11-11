<?php
	if(permissaoSubOperacao(1, 17, "C") == false){
		$local_Erro = 2;
	} else {
		$tr_i = 0;
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		
		$temp = explode(",", $local_ContaReceberOcorrencia);
		
		for($i = 0; $i < count($temp); $i++){
			list($local_IdContaReceber, $local_IdContaReceberRecebimento) = explode("_", $temp[$i]);
			
			$dados = array(
				"IdLoja"						=> $local_IdLoja,
				"IdContaReceber"				=> $local_IdContaReceber,
				"IdContaReceberRecebimento"		=> $local_IdContaReceberRecebimento,
				"Login"							=> $local_Login,
				"CreditoFuturo"					=> 2,
				"CancelarNotaFiscalRecebimento"	=> 2,
				"ObsCancelamento"				=> "Recebimento cancelado via Arquivo de Retorno"
			);
			
			
			$local_transaction[$tr_i] = conta_receber_cancelar_recebimento($dados);
			$tr_i++;
		}
		
		if(!in_array(false, $local_transaction)){
			$sql = "COMMIT;";
			$local_Erro = 67;			// Mensagem de Alteraчуo Positiva
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 68;			// Mensagem de Alteraчуo Negativa
		}
		
		@mysql_query($sql,$con);
	}
?>