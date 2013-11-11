<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
			
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;

		if($local_Cancelar == ""){
			
			$sql = "select
						IdStatus
					from
						ProcessoFinanceiro
					where
						IdLoja = $local_IdLoja and
						IdProcessoFinanceiro = $local_IdProcessoFinanceiro";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
	
			$IdStatus = $lin[IdStatus];			
			
			if($local_Filtro_IdLocalCobranca == '') {
				$local_Filtro_IdLocalCobranca  = $local_Filtro_IdLocalCobrancaTemp;
			}
			
			$sql = "select 
						IdProcessoFinanceiro
					from
						ProcessoFinanceiro
					where
						IdLoja = $local_IdLoja and 
						Filtro_IdLocalCobranca	= $local_Filtro_IdLocalCobranca and
						IdStatus = 3
					order by 
						IdProcessoFinanceiro DESC 
					limit 0,1";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
		}
			
		// Verifica se já processos financeiros maiores gerados para este local de cobrança
		if($lin[IdProcessoFinanceiro] != '' && $lin[IdProcessoFinanceiro] != $local_IdProcessoFinanceiro && $IdStatus == 3){
			$local_Erro = 77;
		}else{
			$sql = "SELECT
						NotaFiscal.PeriodoApuracao,
						MAX(NotaFiscal.IdNotaFiscal) UltimaNotaFiscalProcessoFinanceiro,
						NotaFiscal.IdNotaFiscalLayout
					FROM
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber,
						NotaFiscal
					WHERE
						LancamentoFinanceiro.IdLoja = $local_IdLoja AND
						LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro AND
						LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
						LancamentoFinanceiroContaReceber.IdLoja = NotaFiscal.IdLoja AND
						LancamentoFinanceiroContaReceber.IdContaReceber = NotaFiscal.IdContaReceber";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			$sql = "SELECT
						MAX(NotaFiscal.IdNotaFiscal) UltimaNotaFiscal
					FROM
						NotaFiscal
					WHERE
						NotaFiscal.IdLoja = $local_IdLoja AND
						NotaFiscal.PeriodoApuracao = '$lin[PeriodoApuracao]'";
			$res = mysql_query($sql,$con);
			$lin2 = mysql_fetch_array($res);

			// Verifica se tem notas fiscais superiores a gerada neste processo financeiro
			if($lin2[UltimaNotaFiscal] != $lin[UltimaNotaFiscalProcessoFinanceiro] && $lin[UltimaNotaFiscalProcessoFinanceiro] != ''){
				$local_Erro = 151;
			}else{
				$sql = "SELECT
							IdStatus
						FROM
							NotaFiscal2ViaEletronicaArquivo
						WHERE
							IdLoja = $local_IdLoja AND
							IdNotaFiscalLayout = $lin[IdNotaFiscalLayout] AND
							MesReferencia = '".dataConv($lin[PeriodoApuracao],'Y-m','m/Y')."'";
				$res = @mysql_query($sql,$con);
				$lin2 = @mysql_fetch_array($res);

				// Verifica se já foi transmitido as notas fiscais para o período de apuração
				if($lin2[IdStatus] >= 2){
					$local_Erro = 152;
				}else{					
					$sql = "SELECT 
								count(*) Qtd
							FROM
								LancamentoFinanceiro,
								LancamentoFinanceiroContaReceber,
								ContaReceber
							WHERE 
								LancamentoFinanceiro.IdLoja = $local_IdLoja AND 
								LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro AND
								LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
								LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
								LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND
								LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber AND
								ContaReceber.IdStatus = 2";
					$res = @mysql_query($sql,$con);
					$lin2 = @mysql_fetch_array($res);

					// Verifica se há contas a receber quitados
					if($lin2[Qtd] >= 1){
						$local_Erro = 153;
					}else{

						$sql = "SELECT
									COUNT(*) Qtd
								FROM
									LancamentoFinanceiro,
									LoteRepasseTerceiroItem
								WHERE
									LancamentoFinanceiro.IdLoja = $local_IdLoja AND 
									LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro AND
									LancamentoFinanceiro.IdLoja = LoteRepasseTerceiroItem.IdLoja AND
									LancamentoFinanceiro.IdLancamentoFinanceiro = LoteRepasseTerceiroItem.IdLancamentoFinanceiro";
						$res = @mysql_query($sql,$con);
						$lin2 = @mysql_fetch_array($res);

						// Verifica se já foi feito repasse
						if($lin2[Qtd] >= 1){
							$local_Erro = 154;
						}else{
							$sql = "select
										IdStatus
									from
										ProcessoFinanceiro
									where
										IdLoja = $local_IdLoja and		
										IdProcessoFinanceiro = $local_IdProcessoFinanceiro";
							$res = mysql_query($sql,$con);
							if($lin = mysql_fetch_array($res)){
								switch($lin[IdStatus]){
									case 2:
										// Em análise
										$sql = "DELETE from LancamentoFinanceiro where IdLoja = $local_IdLoja and IdProcessoFinanceiro=$local_IdProcessoFinanceiro and IdMudancaStatus > 0";
										$local_transaction[$tr_i]	=	mysql_query($sql,$con);
										$tr_i++;

										$sql = "UPDATE LancamentoFinanceiro SET
													IdStatus=2,
													IdProcessoFinanceiro= null
												WHERE 
													IdLoja = $local_IdLoja and 
													IdProcessoFinanceiro=$local_IdProcessoFinanceiro and
													(IdContaEventual != '' or IdOrdemServico != '' or Valor < 0)";
										$local_transaction[$tr_i]	=	mysql_query($sql,$con);
										$tr_i++;

										$sql = "select
													IdEncargoFinanceiro
												from
													LancamentoFinanceiro
												where
													LancamentoFinanceiro.IdLoja = $local_IdLoja and
													LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
													LancamentoFinanceiro.IdEncargoFinanceiro > 0";
										$res = mysql_query($sql,$con);
										while($lin = mysql_fetch_array($res)){

											// Apago os encargos financeiros calculados
											$sql = "DELETE from LancamentoFinanceiro where IdLoja = $local_IdLoja and IdEncargoFinanceiro=$lin[IdEncargoFinanceiro] and IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
											$local_transaction[$tr_i]	=	mysql_query($sql,$con);
											$tr_i++;

											$sql = "DELETE FROM ContaReceberEncargoFinanceiro WHERE IdLoja=$local_IdLoja and IdEncargoFinanceiro=$lin[IdEncargoFinanceiro]";
											$local_transaction[$tr_i]	=	mysql_query($sql,$con);
											$tr_i++;

										}

										$sql = "DELETE from LancamentoFinanceiro where IdLoja = $local_IdLoja and IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
										$local_transaction[$tr_i]	=	mysql_query($sql,$con);
										$tr_i++;
										break;

									case 3:
										// Confirmado
										$sql = "select
													DISTINCT
													LancamentoFinanceiroContaReceber.IdContaReceber
												from
													LancamentoFinanceiro,
													LancamentoFinanceiroContaReceber
												where
													LancamentoFinanceiro.IdLoja = $local_IdLoja and
													LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
													LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
													LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro
												order by
													LancamentoFinanceiroContaReceber.IdContaReceber DESC";
										$res = mysql_query($sql,$con);
										while($lin = mysql_fetch_array($res)){

											// Apago os Itens da Nota Fiscal
											$sqlContaReceber = "DELETE FROM NotaFiscalItem WHERE IdLoja=$local_IdLoja and IdContaReceber=$lin[IdContaReceber]";
											$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
											$tr_i++;

											// Apago as Notas Fiscais
											$sqlContaReceber = "DELETE FROM NotaFiscal WHERE IdLoja=$local_IdLoja and IdContaReceber=$lin[IdContaReceber]";
											$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
											$tr_i++;

											// Apago os Vencimentos dos Contas a Receber Recebimento Cancelado
											$sqlContaReceber = "DELETE FROM ContaReceberRecebimentoParametro WHERE IdLoja=$local_IdLoja and IdContaReceber=$lin[IdContaReceber]";
											$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
											$tr_i++;

											// Apago os Vencimentos dos Contas a Receber Recebimento Cancelado
											$sqlContaReceber = "DELETE FROM ContaReceberRecebimento WHERE IdLoja=$local_IdLoja and IdContaReceber=$lin[IdContaReceber] and IdStatus=0";
											$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
											$tr_i++;

											// Apago os Vencimentos dos Contas a Receber
											$sqlContaReceber = "DELETE FROM ContaReceberVencimento WHERE IdLoja=$local_IdLoja and IdContaReceber=$lin[IdContaReceber]";
											$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
											$tr_i++;

											// Apago os E-mails não enviados
											$sql = "DELETE from HistoricoMensagem WHERE IdLoja = $local_IdLoja and IdContaReceber=$lin[IdContaReceber] and IdStatus=1";
											$local_transaction[$tr_i]	=	mysql_query($sql,$con);
											$tr_i++;

											// Retiro os vinculos dos e-mails enviados
											$sql = "UPDATE HistoricoMensagem SET IdContaReceber = null WHERE IdLoja = $local_IdLoja and IdContaReceber=$lin[IdContaReceber]";
											$local_transaction[$tr_i]	=	mysql_query($sql,$con);
											$tr_i++;

											// Apagos os vinculos com os lançamentos financeiros
											$sqlLancamento = "DELETE FROM LancamentoFinanceiroContaReceber where IdLoja=$local_IdLoja and IdContaReceber=$lin[IdContaReceber]";
											$local_transaction[$tr_i]	=	mysql_query($sqlLancamento,$con);
											$tr_i++;

											// Apago as Posições de Cobranca dos Contas a Receber
											$sqlContaReceber = "DELETE FROM ContaReceberPosicaoCobranca WHERE IdLoja=$local_IdLoja and IdContaReceber=$lin[IdContaReceber]";
											$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
											$tr_i++;

											// Apago os contas a receber
											$sqlContaReceber = "DELETE FROM ContaReceber WHERE IdLoja=$local_IdLoja and IdContaReceber=$lin[IdContaReceber]";
											$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
											$tr_i++;

										}

										$sql = "DELETE from LancamentoFinanceiro where IdLoja = $local_IdLoja and IdProcessoFinanceiro=$local_IdProcessoFinanceiro and IdMudancaStatus > 0";
										$local_transaction[$tr_i]	=	mysql_query($sql,$con);
										$tr_i++;										

										$sql = "select
													IdEncargoFinanceiro
												from
													LancamentoFinanceiro
												where
													LancamentoFinanceiro.IdLoja = $local_IdLoja and
													LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
													LancamentoFinanceiro.IdEncargoFinanceiro > 0";
										$res = mysql_query($sql,$con);
										while($lin = mysql_fetch_array($res)){

											// Apago os encargos financeiros calculados
											$sql = "DELETE from LancamentoFinanceiro where IdLoja = $local_IdLoja and IdEncargoFinanceiro=$lin[IdEncargoFinanceiro] and IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
											$local_transaction[$tr_i]	=	mysql_query($sql,$con);
											$tr_i++;

											$sql = "DELETE FROM ContaReceberEncargoFinanceiro WHERE IdLoja=$local_IdLoja and IdEncargoFinanceiro=$lin[IdEncargoFinanceiro]";
											$local_transaction[$tr_i]	=	mysql_query($sql,$con);
											$tr_i++;

										}
										
										$sql = "UPDATE LancamentoFinanceiro SET 
													IdStatus = 2,
													IdProcessoFinanceiro= null
												WHERE 
													IdLoja = $local_IdLoja and 
													IdProcessoFinanceiro=$local_IdProcessoFinanceiro and
													(IdContaEventual != '' or IdOrdemServico != '' or Valor < 0)";
										$local_transaction[$tr_i]	=	mysql_query($sql,$con);
										$tr_i++;

										$sql = "select
													IdLancamentoFinanceiro,
													IdContrato,
													IdOrdemServico,
													IdContaEventual,
													DataReferenciaInicial
												from
													LancamentoFinanceiro
												where
													IdLoja = $local_IdLoja and 
													LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro
												order by
													LancamentoFinanceiro.IdContrato ASC,
													LancamentoFinanceiro.DataReferenciaInicial DESC";
										$res = mysql_query($sql,$con);
										while($lin = mysql_fetch_array($res)){
										
											if($lin[IdContrato] != '' && $lin[IdContaEventual] == '' && $lin[IdOrdemServico] == ''){

												$lin[DataReferenciaInicial] = incrementaData($lin[DataReferenciaInicial],-1);
												
												$sqlContrato = "UPDATE Contrato SET DataBaseCalculo='$lin[DataReferenciaInicial]' WHERE IdLoja=$local_IdLoja AND IdContrato=$lin[IdContrato];";	
												$local_transaction[$tr_i]	=	mysql_query($sqlContrato,$con);
												$tr_i++;

											}
										}

										$sql = "DELETE from LancamentoFinanceiro where IdLoja = $local_IdLoja and IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
										$local_transaction[$tr_i]	=	mysql_query($sql,$con);
										$tr_i++;

										break;
								}
								
								$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Cancelamento do Processo Financeiro.";
															
								$sql = "UPDATE ProcessoFinanceiro SET 
										IdStatus=1, 
										IdStatusBoleto = 0,
										LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento),
										LoginProcessamento=NULL, 
										DataProcessamento=NULL, 
										LoginConfirmacao=NULL, 
										DataConfirmacao=NULL 
									WHERE 
										IdLoja = $local_IdLoja and 
										IdProcessoFinanceiro=$local_IdProcessoFinanceiro";			
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);
								$tr_i++;

								@system("rm -r ../../temp/Boletos_Loja-".$local_IdLoja."_ProcessoFinanceiro-".$local_IdProcessoFinanceiro."*");
								
								for($i=0; $i<$tr_i; $i++){
									if($local_transaction[$i] == false){
										$local_transaction = false;
									}
								}
							
								if($local_transaction == true){
									$sql = "COMMIT;";
									$local_Erro = 48;
								}else{
									$sql = "ROLLBACK;";
									$local_Erro = 68;
								}								
								mysql_query($sql,$con);
							}
						}
					}
				}
			}
		}
	}
?>