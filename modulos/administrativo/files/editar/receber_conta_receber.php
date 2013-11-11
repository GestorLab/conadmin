<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"A") == false){
		$local_Erro = 2;
	} else{
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		
		$dados_conta_receber = array(
			"IdLoja"					=> $local_IdLoja,
			"IdLojaRecebimento"			=> $local_IdLoja,
			"IdContaReceber"			=> $local_IdContaReceber,
			"ValorDespesas"				=> $local_ValorDespesas,
			"NumeroNF"					=> $local_NumeroNF,
			"ObsNotaFiscal"				=> $local_ObsNotaFiscal,
			"DataNF"					=> $local_DataNF,
			"ModeloNF"					=> $local_ModeloNF,
			"IdTipoLocalCobranca"		=> $local_IdTipoLocalCobranca, 
			"IdStatus"					=> $local_IdStatus,
			"IdPosicaoCobranca"			=> $local_IdPosicaoCobranca,
			"Obs"						=> $local_Obs,
			"DataRecebimento"			=> $local_DataRecebimento,
			"ValorReceber"				=> $local_ValorReceber,
			"ValorDescontoRecebimento"	=> $local_ValorDescontoRecebimento,
			"ValorOutrasDespesas"		=> $local_ValorOutrasDespesas,
			"ValorMoraMulta"			=> $local_ValorMoraMulta,
			"IdLocalRecebimento"		=> $local_IdLocalRecebimento,
			"Login"						=> $local_Login,
			"DataVencimento"			=> $local_DataVencimento,
			"IdPessoa"					=> $local_IdPessoa,
			"ValorDesconto"				=> $local_ValorDesconto,
			"IdPessoaEndereco"			=> $local_IdPessoaEndereco);
		$local_transaction = receber_conta_receber($dados_conta_receber);
		
		if($local_transaction){
			/* PEGAR OS ITENS DO AGRUAPDOR, COM TODAS AS PARCELAR DO AGRUPADOR QUITADAS */
			$sql = "SELECT 
						ContaReceberDados.IdLoja,
						ContaReceberDados.IdContaReceber,
						ContaReceberDados.ValorDespesas,
						ContaReceberDados.ValorDesconto,
						ContaReceberDados.IdStatus,
						ContaReceberDados.NumeroNF,
						ContaReceberDados.DataNF,
						ContaReceberDados.ModeloNF,
						ContaReceberDados.DataVencimento,
						ContaReceberDados.IdPosicaoCobranca,
						LocalCobranca.IdTipoLocalCobranca
					FROM 
						ContaReceberAgrupado, 
						ContaReceberAgrupadoItem,
						ContaReceberAgrupadoParcela,
						ContaReceberDados,
						LocalCobranca
					WHERE
						ContaReceberAgrupado.IdLoja = ContaReceberAgrupadoItem.IdLoja AND 
						ContaReceberAgrupado.IdContaReceberAgrupador = ContaReceberAgrupadoItem.IdContaReceberAgrupador AND 
						ContaReceberAgrupado.IdLoja = ContaReceberAgrupadoParcela.IdLoja AND 
						ContaReceberAgrupado.IdContaReceberAgrupador = ContaReceberAgrupadoParcela.IdContaReceberAgrupador AND
						ContaReceberAgrupadoParcela.IdLoja = '$local_IdLoja' AND  
						ContaReceberAgrupadoParcela.IdContaReceber = '$local_IdContaReceber' AND 
						ContaReceberAgrupadoItem.IdLoja = ContaReceberDados.IdLoja AND 
						ContaReceberAgrupadoItem.IdContaReceber = ContaReceberDados.IdContaReceber AND 
						ContaReceberDados.IdLoja = LocalCobranca.IdLoja AND 
						ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND 
						(
							SELECT 
								COUNT(*)  
							FROM 
								ContaReceberAgrupadoParcela, 
								ContaReceber 
							WHERE 
								ContaReceberAgrupadoParcela.IdLoja = '$local_IdLoja' AND  
								ContaReceberAgrupadoParcela.IdContaReceberAgrupador = (
									SELECT 
										ContaReceberAgrupadoParcela.IdContaReceberAgrupador 
									FROM 
										ContaReceberAgrupadoParcela
									WHERE 
										ContaReceberAgrupadoParcela.IdLoja = '$local_IdLoja' AND 
										ContaReceberAgrupadoParcela.IdContaReceber = '$local_IdContaReceber'
								) AND 
								ContaReceberAgrupadoParcela.IdLoja = ContaReceber.IdLoja AND 
								ContaReceberAgrupadoParcela.IdContaReceber = ContaReceber.IdContaReceber AND 
								ContaReceber.IdStatus != 2 AND 
								ContaReceber.IdContaReceber != '$local_IdContaReceber'
						) = 0";
			$res = mysql_query($sql, $con);
			
			if(mysql_num_rows($res) > 0){
				while($lin = mysql_fetch_array($res)){
					$dados_conta_receber[IdContaReceber]			= $lin[IdContaReceber];
					$dados_conta_receber[IdStatus]					= $lin[IdStatus];
					$dados_conta_receber[NumeroNF]					= $lin[NumeroNF];
					$dados_conta_receber[DataNF]					= $lin[DataNF];
					$dados_conta_receber[ModeloNF]					= $lin[ModeloNF];
					$dados_conta_receber[ValorOutrasDespesas]		= "0,00";
					$dados_conta_receber[ValorDescontoRecebimento]	= "0,00";
					$dados_conta_receber[ValorReceber]				= "0,00";
					$dados_conta_receber[ValorMoraMulta]			= "0,00";
					$dados_conta_receber[ValorDespesas]				= str_replace('.', ',', $lin[ValorDespesas]);
					$dados_conta_receber[ValorDesconto]				= str_replace('.', ',', $lin[ValorDesconto]);
					$dados_conta_receber[DataVencimento]			= $lin[DataVencimento];
					$dados_conta_receber[IdPosicaoCobranca]			= $lin[IdPosicaoCobranca];
					$dados_conta_receber[IdTipoLocalCobranca]		= $lin[IdTipoLocalCobranca];
					/* RECEBER OS ITENS DO AGRUPADOR */
					if(!receber_conta_receber($dados_conta_receber)){
						$local_transaction = false;
						break;
					}
				}
			}
		}
		
		if($local_transaction){
			$sql = "COMMIT;";
			$local_Erro = 4;	
						// Mensagem de Alteraчуo Positiva
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;			// Mensagem de Alteraчуo Negativa
		}
		
		mysql_query($sql,$con);
	}
?>