<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
	
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql	=	"UPDATE ContaEventual SET 
							IdStatus				= 2,
							LoginConfirmacao		='$local_Login',
							DataConfirmacao			= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					= $local_IdLoja and
							IdContaEventual			= '$local_IdContaEventual'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		if($local_IdFormatoCarne == 1 && $local_FormaCobranca == 2){
			$sqlCarne = "select max(IdCarne) IdCarne from Carne where IdLoja = $local_IdLoja";
			$resCarne = mysql_query($sqlCarne,$con);
			$linCarne = mysql_fetch_array($resCarne);
				
			$IdCarne = $linCarne[IdCarne];
			if($IdCarne == null){
				$IdCarne = 1;
			}else{
				$IdCarne++;
			}
			
			$sql = "INSERT INTO Carne SET
						IdLoja=$local_IdLoja,
						IdCarne=$IdCarne";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		} else{
			$IdCarne = "NULL";
		}
		
		$sqlParcela	=	"select 
								IdContaEventualParcela, 
								Valor, 
								ContaEventualParcela.ValorDespesaLocalCobranca, 
								Vencimento, 
								ContaEventual.IdLocalCobranca,
								ContaEventual.IdCartao,
								ContaEventual.IdContaDebito,
								LocalCobranca.IdArquivoRemessaTipo, 
								LocalCobranca.DiasProtesto,
								LocalCobranca.IdTipoLocalCobranca
							from 
								ContaEventual 
									left join LocalCobranca on (ContaEventual.IdLoja = LocalCobranca.IdLoja and ContaEventual.IdLocalCobranca = LocalCobranca.IdLocalCobranca),
								ContaEventualParcela
							where 
								ContaEventual.IdLoja = $local_IdLoja and 
								ContaEventual.IdLoja = ContaEventualParcela.IdLoja and 
								ContaEventual.IdContaEventual = ContaEventualParcela.IdContaEventual and 
								ContaEventual.IdContaEventual = $local_IdContaEventual";
		$resParcela	= mysql_query($sqlParcela,$con);
		while($linParcela = mysql_fetch_array($resParcela)){

			// Verifica o status inicial para os contas a receber de acordo com o tipo do local de cobrança.
			switch($linParcela[IdTipoLocalCobranca]){
				case 3:
					$StatusContaReceber = 3;
					break;
				case 4:
					$StatusContaReceber = 3;
					break;
				default:
					$StatusContaReceber = 1;
					break;
			}
		
			$sqlLancamento = "select
							    max(IdLancamentoFinanceiro) IdLancamentoFinanceiro
							from
							    LancamentoFinanceiro
							where
							    IdLoja=$local_IdLoja";
			$resLancamento = mysql_query($sqlLancamento);
			$linLancamento = mysql_fetch_array($resLancamento);
					
			$IdLancamentoFinanceiro = $linLancamento[IdLancamentoFinanceiro];
			if($IdLancamentoFinanceiro == null){
				$IdLancamentoFinanceiro = 1;
			}else{
				$IdLancamentoFinanceiro++;
			}

			if($local_FormaCobranca == 1){
				$IdStatus = 2;
			}else{
				$IdStatus = 1;
			}
			
			$IdContrato = $local_IdContratoAgrupador;
			
			if($IdContrato == 0 || $IdContrato == ''){
				if($local_IdContrato != 0 && $local_IdContrato != ''){
					$IdContrato = $local_IdContrato;
				}else{
					$IdContrato = 'NULL';
				}
			}
			
			$sql = "INSERT INTO LancamentoFinanceiro SET 
						IdLoja = $local_IdLoja,
						IdLancamentoFinanceiro = $IdLancamentoFinanceiro, 
						IdContrato = $IdContrato,
						IdContaEventual = $local_IdContaEventual, 
						NumParcelaEventual = $linParcela[IdContaEventualParcela], 
						Valor = '$linParcela[Valor]', 
						IdProcessoFinanceiro = null,
						IdStatus = $IdStatus";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;

			//Individual
			if($local_FormaCobranca == 2){

				$sqlContaReceber = "select max(IdContaReceber) IdContaReceber from ContaReceber where IdLoja = $local_IdLoja";
				$resContaReceber = mysql_query($sqlContaReceber,$con);
				$linContaReceber = mysql_fetch_array($resContaReceber);
					
				$IdContaReceber = $linContaReceber[IdContaReceber];
				if($IdContaReceber == null){
					$IdContaReceber = 1;
				}else{
					$IdContaReceber++;
				}
					
				// Calcula o numero do documento
				$sqlNossoNumero = "select 
										LocalCobranca.IdLoja, 
										LocalCobranca.IdLocalCobranca										
									from 
										LocalCobranca, 
										(select 
											IdLojaCobrancaUnificada IdLoja, 
											IdLocalCobrancaUnificada IdLocalCobranca 
										from 
											LocalCobranca 
										where 
											IdLoja = $local_IdLoja and 
											IdLocalCobranca = $linParcela[IdLocalCobranca]) LocalCobrancaTemp 
									where 
										(LocalCobranca.IdLoja = LocalCobrancaTemp.IdLoja and 
										 LocalCobranca.IdLocalCobranca = LocalCobrancaTemp.IdLocalCobranca) or
										(LocalCobranca.IdLojaCobrancaUnificada = LocalCobrancaTemp.IdLoja and 
										LocalCobranca.IdLocalCobrancaUnificada = LocalCobrancaTemp.IdLocalCobranca) or
										(LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and 
										 LocalCobranca.IdLocalCobrancaUnificada = $linParcela[IdLocalCobranca])";
				$resNossoNumero = mysql_query($sqlNossoNumero,$con);

				$sqlNossoNumeroComplemento = '';
				
				while($linNossoNumero = @mysql_fetch_array($resNossoNumero)){
					$sqlNossoNumeroComplemento .= " or (IdLoja = $linNossoNumero[IdLoja] and IdLocalCobranca = $linNossoNumero[IdLocalCobranca])";
				}

				if($linParcela[IdContaDebito] == ''){
					$linParcela[IdContaDebito] = 'NULL';
				}

				if($linParcela[IdCartao] == ''){
					$linParcela[IdCartao] = 'NULL';
				}
				
				$sql = "INSERT INTO ContaReceber SET 
							IdLoja=$local_IdLoja,
							IdContaReceber=$IdContaReceber,
							IdPessoa=$local_IdPessoa,
							IdPessoaEndereco=$local_IdPessoaEnderecoCobranca,
							ValorLancamento='$linParcela[Valor]',
							ValorDespesas='$linParcela[ValorDespesaLocalCobranca]',
							DataLancamento=curdate(),
							IdContaDebito = $linParcela[IdContaDebito],
							IdCartao = $linParcela[IdCartao],
							NumeroDocumento=NumeroDocumento($local_IdLoja, $linParcela[IdLocalCobranca]),
							IdLocalCobranca=$linParcela[IdLocalCobranca],							
							IdCarne=$IdCarne,
							IdArquivoRemessa = NULL,
							IdStatus=$StatusContaReceber,
							LoginCriacao='$local_Login',
							DataCriacao=concat(curdate(),' ',curtime());";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;

				// Insiro o Conta Receber Vencimento
				$sql = "INSERT INTO ContaReceberVencimento SET 
										IdLoja=$local_IdLoja,
										IdContaReceber=$IdContaReceber,
										DataVencimento='$linParcela[Vencimento]', 
										ValorContaReceber='".($linParcela[Valor]+$linParcela[ValorDespesaLocalCobranca])."',
										ValorMulta='0',
										ValorJuros='0',
										ValorTaxaReImpressaoBoleto='0',
										ValorDesconto='0',
										ValorOutrasDespesas='0',
										LoginCriacao='$local_Login',
										DataCriacao=concat(curdate(),' ',curtime());";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;

				$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $IdContaReceber, 1, $local_Login, $local_IdContaDebitoCartao);
				$tr_i++;
				
				$sql = "insert into LancamentoFinanceiroContaReceber (IdLoja, IdLancamentoFinanceiro, IdContaReceber, IdStatus) values ($local_IdLoja,  $IdLancamentoFinanceiro,  $IdContaReceber,  '1' )";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
	
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 74;
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 73;
		}
		mysql_query($sql,$con);
	}
?>
