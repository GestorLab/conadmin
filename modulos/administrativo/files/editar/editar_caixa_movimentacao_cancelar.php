<?php
	if(!permissaoSubOperacao($localModulo, $localOperacao, "C")){
		$local_Erro = 2;
	} else{
		$sql_cx = "SELECT 
						Caixa.LoginAbertura
					FROM 
						Caixa
					WHERE 
						Caixa.IdLoja = '$local_IdLoja' AND 
						Caixa.IdCaixa = '$local_IdCaixa' AND 
						Caixa.LoginAbertura = '$local_Login'";
		$res_cx = @mysql_query($sql_cx, $con);
		
		if(@mysql_num_rows($res_cx) == 0){
			$local_Erro = 2;
		} else {
			$sql = "START TRANSACTION;";
			@mysql_query($sql,$con);
			$tr_i = 0;
			
			$local_Obs = trim($local_Obs);
			$local_Obs = str_replace("'", "\'", $local_Obs);
			$local_ObsCX = date("d/m/Y H:i:s")." [".$local_Login."] - Observaчѕes: $local_Obs";
			
			$sql = "SELECT
						CaixaMovimentacao.Obs
					FROM
						CaixaMovimentacao
					WHERE
						CaixaMovimentacao.IdLoja = '$local_IdLoja' AND 
						CaixaMovimentacao.IdCaixa = '$local_IdCaixa' AND 
						CaixaMovimentacao.IdCaixaMovimentacao = '$local_IdCaixaMovimentacao'";
			$res = @mysql_query($sql, $con);
			$lin = @mysql_fetch_array($res);
			
			if($lin["Obs"] != ""){
				$local_ObsCX .= "\n".trim($lin["Obs"]);
			}
			
			$sql = "UPDATE
						CaixaMovimentacao
					SET
						CaixaMovimentacao.IdStatus = '0',
						CaixaMovimentacao.Obs = '$local_ObsCX'
					WHERE
						CaixaMovimentacao.IdLoja = '$local_IdLoja' AND 
						CaixaMovimentacao.IdCaixa = '$local_IdCaixa' AND 
						CaixaMovimentacao.IdCaixaMovimentacao = '$local_IdCaixaMovimentacao'";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
			
			$sql = "SELECT
						ContaReceber.IdLoja,
						ContaReceber.IdContaReceber,
						ContaReceberRecebimento.IdContaReceberRecebimento
					FROM
						CaixaMovimentacao,
						CaixaMovimentacaoItem, 
						ContaReceber LEFT JOIN ContaReceberRecebimento ON (
							ContaReceber.IdLoja = ContaReceberRecebimento.IdLoja AND 
							ContaReceber.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
							(ContaReceberRecebimento.IdStatus != 0)
						)
					WHERE
						CaixaMovimentacao.IdLoja = '$local_IdLoja' AND 
						CaixaMovimentacao.IdCaixa = '$local_IdCaixa' AND 
						CaixaMovimentacao.IdCaixaMovimentacao = '$local_IdCaixaMovimentacao' AND 
						CaixaMovimentacao.IdLoja = CaixaMovimentacaoItem.IdLoja AND 
						CaixaMovimentacao.IdCaixa = CaixaMovimentacaoItem.IdCaixa AND 
						CaixaMovimentacao.IdCaixaMovimentacao = CaixaMovimentacaoItem.IdCaixaMovimentacao AND 
						CaixaMovimentacaoItem.IdLoja = ContaReceber.IdLoja AND 
						CaixaMovimentacaoItem.IdContaReceber = ContaReceber.IdContaReceber";
			$res = @mysql_query($sql, $con);
			
			while($lin = @mysql_fetch_array($res)) {
				$dados = array(
					"IdLoja"						=> $lin["IdLoja"],
					"IdContaReceber"				=> $lin["IdContaReceber"],
					"IdContaReceberRecebimento"		=> $lin["IdContaReceberRecebimento"],
					"Login"							=> $local_Login,
					"CreditoFuturo"					=> 2,
					"CancelarNotaFiscalRecebimento"	=> 2,
					"ObsCancelamento"				=> $local_Obs
				);
				
				$local_transaction[$tr_i] = conta_receber_cancelar_caixa_recebimento($dados);
				$tr_i++;
				
				$sql_obs = "SELECT 
								Obs 
							FROM 
								ContaReceberRecebimento
							WHERE
								IdLoja = '".$lin["IdLoja"]."' AND
								IdContaReceber = '".$lin["IdContaReceber"]."' AND
								IdContaReceberRecebimento = '".$lin["IdContaReceberRecebimento"]."'";
				$res_obs = @mysql_query($sql_obs, $con);
				$lin_obs = @mysql_fetch_array($res_obs);
				
				$local_ObsRC = date("d/m/Y H:i:s")." [".$local_Login."] - Cancelamento via caixa.";
				
				if($lin_obs["Obs"] != ""){
					$local_ObsRC .= "\n".trim($lin_obs["Obs"]);
				}
				
				$sql_rc = "UPDATE ContaReceberRecebimento SET
								Obs = '$local_ObsRC'
							WHERE 
								IdLoja = '".$lin["IdLoja"]."' AND
								IdContaReceber = '".$lin["IdContaReceber"]."' AND
								IdContaReceberRecebimento = '".$lin["IdContaReceberRecebimento"]."'";
				$local_transaction[$tr_i] = @mysql_query($sql_rc, $con);
				$tr_i++;
			}
			
			if(!in_array(false, $local_transaction)){
				$sql = "COMMIT;";
				$local_Acao = "alterar";
				$local_Erro = 67;			// Mensagem de Inserчуo Positiva
			} else{
				$sql = "ROLLBACK;";
				$local_Acao = "inserir";
				$local_Erro = 68;			// Mensagem de Inserчуo Negativa
			}
			
			@mysql_query($sql,$con);
		}
	}
?>