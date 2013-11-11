<?
	if(!permissaoSubOperacao($localModulo, $localOperacao, "I")){
		$local_Erro = 2;
	} else{
		$sql = "START TRANSACTION;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "SELECT 
					(MAX(IdCaixaMovimentacao) + 1) AS IdCaixaMovimentacao 
				FROM 
					CaixaMovimentacao 
				WHERE 
					IdLoja = '$local_IdLoja' AND 
					IdCaixa = '$local_IdCaixa';";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdCaixaMovimentacao] != null){ 
			$local_IdCaixaMovimentacao = $lin[IdCaixaMovimentacao];
		} else{
			$local_IdCaixaMovimentacao = 1;
		}
		
		$local_TotalFormaPagamentoValorTotal = str_replace(".", "", $_POST["TotalFormaPagamentoValorTotal"]);
		$local_TotalFormaPagamentoValorTotal = str_replace(",", ".", $local_TotalFormaPagamentoValorTotal);
		
		if(!empty($local_Obs)){
			$local_Obs = str_replace("'", "\\'", $local_Obs);
			$local_ObsCX = date("d/m/Y H:i:s")." [".$local_Login."] - Observaчѕes: $local_Obs";
		} else 
			$local_ObsCX = "";
		
		$sql = "INSERT INTO 
					CaixaMovimentacao
				SET
					IdLoja				= '$local_IdLoja',
					IdCaixa				= '$local_IdCaixa',
					IdCaixaMovimentacao	= '$local_IdCaixaMovimentacao',
					TipoMovimentacao	= '$local_TipoMovimentacao',
					ValorTotal			= $local_TotalFormaPagamentoValorTotal,
					Obs					= '$local_ObsCX',
					IdStatus			= '$local_IdStatus',
					DataHoraCriacao		= (concat(curdate(),' ',curtime()))";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		echo mysql_error();
		$local_ContasReceber = explode(",", $local_ContasReceber);
		
		for($i = 1; $i <= $local_ItemMax; $i++){
			if(array_key_exists("IdContaReceberItem_".$i, $_POST)) {
				$sql = "SELECT 
							(MAX(IdCaixaItem) + 1) IdCaixaItem 
						FROM 
							CaixaMovimentacaoItem 
						WHERE 
							IdLoja = '$local_IdLoja' AND 
							IdCaixa = '$local_IdCaixa' AND
							IdCaixaMovimentacao = '$local_IdCaixaMovimentacao';";
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				
				if($lin[IdCaixaItem] != null){ 
					$local_IdCaixaItem = $lin[IdCaixaItem];
				} else{
					$local_IdCaixaItem = 1;
				}
				
				$IdContaReceberTemp	= $_POST["IdContaReceberItem_".$i];
				$ValorItemTemp		= $_POST["ValorItem_".$i];
				$ValorMultaTemp		= $_POST["ValorMultaItem_".$i];
				$ValorJurosTemp		= $_POST["ValorJurosItem_".$i];
				$ValorDescontoTemp	= $_POST["ValorDescontoItem_".$i];
				$ValorCredito		= $_POST["ValorCreditoItem_".$i];
				$ValorFinalTemp		= $_POST["ValorFinalItem_".$i];
				
				$sql = "SELECT 
							ContaReceberDados.IdPessoa,
							ContaReceberDados.IdPessoaEndereco,
							ContaReceberDados.ValorDesconto,
							ContaReceberDados.IdStatus,
							LocalCobranca.IdTipoLocalCobranca
						FROM
							ContaReceberDados,
							LocalCobranca
						WHERE
							ContaReceberDados.IdLoja = '$local_IdLoja' AND 
							ContaReceberDados.IdContaReceber = '$IdContaReceberTemp' AND 
							ContaReceberDados.IdLoja = LocalCobranca.IdLoja AND 
							ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
				$res = @mysql_query($sql, $con);
				$lin = @mysql_fetch_array($res);
				
				$dados = array(
					"IdLoja"					=> $local_IdLoja,
					"IdContaReceber"			=> $IdContaReceberTemp, 
					"IdTipoLocalCobranca"		=> $lin["IdTipoLocalCobranca"], 
					"IdStatus"					=> $lin["IdStatus"],
					"DataRecebimento"			=> date("d/m/Y"),
					"ValorReceber"				=> $ValorFinalTemp,
					"ValorDescontoRecebimento"	=> $ValorDescontoTemp,
					"ValorOutrasDespesas"		=> "0,00",
					"ValorMoraMulta"			=> $ValorMultaTemp,
					"IdLojaRecebimento"			=> $local_IdLoja,
					"IdCaixa"					=> $local_IdCaixa,
					"IdCaixaMovimentacao"		=> $local_IdCaixaMovimentacao,
					"IdCaixaItem"				=> $local_IdCaixaItem,
					"IdPessoa"					=> $lin["IdPessoa"],
					"ValorDesconto"				=> number_format($lin["ValorDesconto"], 2, ",", ""),
					"IdPessoaEndereco"			=> $lin["IdPessoaEndereco"],
					"Obs"						=> $local_Obs,
					"Login"						=> $local_Login
				);
				
				$ValorItemTemp		= str_replace(".", "", $ValorItemTemp);
				$ValorItemTemp		= str_replace(",", ".", $ValorItemTemp);
				$ValorMultaTemp		= str_replace(".", "", $ValorMultaTemp);
				$ValorMultaTemp		= str_replace(",", ".", $ValorMultaTemp);
				$ValorJurosTemp		= str_replace(".", "", $ValorJurosTemp);
				$ValorJurosTemp		= str_replace(",", ".", $ValorJurosTemp);
				$ValorDescontoTemp	= str_replace(".", "", $ValorDescontoTemp);
				$ValorDescontoTemp	= str_replace(",", ".", $ValorDescontoTemp);
				$ValorFinalTemp		= str_replace(".", "", $ValorFinalTemp);
				$ValorFinalTemp		= str_replace(",", ".", $ValorFinalTemp);
				
				echo $ValorCredito;
				
				$sql = "INSERT INTO
							CaixaMovimentacaoItem
						SET
							IdLoja				= '".$local_IdLoja."',
							IdCaixa				= '".$local_IdCaixa."',
							IdCaixaMovimentacao	= '".$local_IdCaixaMovimentacao."',
							IdCaixaItem			= '".$local_IdCaixaItem."',
							IdContaReceber		= '".$IdContaReceberTemp."',
							ValorItem			= '".$ValorItemTemp."',
							ValorCredito		= '".ValorCredito."',
							ValorMulta			= '".$ValorMultaTemp."',
							ValorJuros			= '".$ValorJurosTemp."',	
							ValorDesconto		= '".$ValorDescontoTemp."',
							ValorFinal			= '".$ValorFinalTemp."';";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);
				$tr_i++;
				echo mysql_error();
				if($local_TipoMovimentacao != 4){
					$local_transaction[$tr_i] = receber_conta_receber($dados);
					$tr_i++;
					
					if($local_transaction[$tr_i-1]){
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
									ContaReceberAgrupadoParcela.IdContaReceber = '$IdContaReceberTemp' AND 
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
													ContaReceberAgrupadoParcela.IdContaReceber = '$IdContaReceberTemp'
											) AND 
											ContaReceberAgrupadoParcela.IdLoja = ContaReceber.IdLoja AND 
											ContaReceberAgrupadoParcela.IdContaReceber = ContaReceber.IdContaReceber AND 
											ContaReceber.IdStatus != 2 AND 
											ContaReceber.IdContaReceber != '$IdContaReceberTemp'
									) = 0";
						$res = mysql_query($sql, $con);
						
						if(mysql_num_rows($res) > 0){
							while($lin = mysql_fetch_array($res)){
								$dados[IdContaReceber]				= $lin[IdContaReceber];
								$dados[IdStatus]					= $lin[IdStatus];
								$dados[NumeroNF]					= $lin[NumeroNF];
								$dados[DataNF]						= $lin[DataNF];
								$dados[ModeloNF]					= $lin[ModeloNF];
								$dados[ValorOutrasDespesas]			= "0,00";
								$dados[ValorDescontoRecebimento]	= "0,00";
								$dados[ValorReceber]				= "0,00";
								$dados[ValorMoraMulta]				= "0,00";
								$dados[ValorDespesas]				= str_replace('.', ',', $lin[ValorDespesas]);
								$dados[ValorDesconto]				= str_replace('.', ',', $lin[ValorDesconto]);
								$dados[DataVencimento]				= $lin[DataVencimento];
								$dados[IdPosicaoCobranca]			= $lin[IdPosicaoCobranca];
								$dados[IdTipoLocalCobranca]			= $lin[IdTipoLocalCobranca];
								/* RECEBER OS ITENS DO AGRUPADOR */
								if(!receber_conta_receber($dados)){
									$local_transaction = false;
									break;
								}
							}
						}
					}
				}else{
					/*$local_Obs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Estorno via Caixa.";
					$sql = "UPDATE ContaReceberRecebimento
								SET 
								  	IdStatus = '3',
								  	LoginAlteracao = '$local_Login',
								  	DataAlteracao = (concat(curdate(),' ',curtime()))
								WHERE IdLoja = '$local_IdLoja'
								    AND IdContaReceber = '$dados[IdContaReceber]'";
					
					$local_transaction[$tr_i] = @mysql_query($sql,$con);
					$tr_i++;
					
					$sql = "UPDATE ContaReceber
								SET 
								  	IdStatus = '1',
								  	IdPosicaoCobranca = '8',
									Obs	= '$local_Obs',
								  	LoginAlteracao = '$local_Login',
								  	DataAlteracao = (concat(curdate(),' ',curtime()))
								WHERE 
									IdLoja = '$local_IdLoja' and
								    IdContaReceber = '$dados[IdContaReceber]'";
					
					$local_transaction[$tr_i] = @mysql_query($sql,$con);
					$tr_i++;
					
					$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $dados[IdContaReceber], 8, $local_Login, "");
					$tr_i++;
					
					if($local_transaction[$tr_i-1]){
						 PEGAR OS ITENS DO AGRUAPDOR, COM TODAS AS PARCELAR DO AGRUPADOR QUITADAS 
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
									ContaReceberAgrupadoParcela.IdContaReceber = '$IdContaReceberTemp' AND 
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
													ContaReceberAgrupadoParcela.IdContaReceber = '$IdContaReceberTemp'
											) AND 
											ContaReceberAgrupadoParcela.IdLoja = ContaReceber.IdLoja AND 
											ContaReceberAgrupadoParcela.IdContaReceber = ContaReceber.IdContaReceber AND 
											ContaReceber.IdStatus != 2 AND 
											ContaReceber.IdContaReceber != '$IdContaReceberTemp'
									) = 0";
						$res = mysql_query($sql, $con);
						
						if(mysql_num_rows($res) > 0){
							while($lin = mysql_fetch_array($res)){
								$local_Obs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Estorno via Caixa.";
								$sql = "UPDATE ContaReceberRecebimento
											SET 
											  	IdStatus = '3',
											  	LoginAlteracao = '$local_Login',
											  	DataAlteracao = (concat(curdate(),' ',curtime()))
											WHERE IdLoja = '$local_IdLoja'
											    AND IdContaReceber = '$dados[IdContaReceber]'";
								
								$local_transaction[$tr_i] = @mysql_query($sql,$con);
								$tr_i++;
								
								$sql = "UPDATE ContaReceber
											SET 
											  	IdStatus = '1',
											  	IdPosicaoCobranca = '8',
											  	LoginAlteracao = '$local_Login',
											  	DataAlteracao = (concat(curdate(),' ',curtime()))
											WHERE 
												IdLoja = '$local_IdLoja' and
											    IdContaReceber = '$dados[IdContaReceber]'";
								
								$local_transaction[$tr_i] = @mysql_query($sql,$con);
								$tr_i++;
								
								$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $dados[IdContaReceber], 8, $local_Login, "");
								$tr_i++;
			
							}
						}
					}*/
					
					$sql = "SELECT
								ContaReceber.IdLoja,
								ContaReceber.IdContaReceber,
								ContaReceberRecebimento.IdContaReceberRecebimento
							FROM
								CaixaMovimentacao,
								CaixaMovimentacaoItem, 
								ContaReceber LEFT JOIN ContaReceberRecebimento ON (
									ContaReceber.IdLoja = ContaReceberRecebimento.IdLoja AND 
									ContaReceber.IdContaReceber = ContaReceberRecebimento.IdContaReceber
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
						
						$local_transaction[$tr_i] = conta_receber_cancelar_recebimento($dados);
						$tr_i++;
						
						$sql = "UPDATE ContaReceberRecebimento
									SET 
									  	IdStatus = '3',
									  	LoginAlteracao = '$local_Login',
									  	DataAlteracao = (concat(curdate(),' ',curtime()))
									WHERE IdLoja = '$local_IdLoja'
									    AND IdContaReceber = '$dados[IdContaReceber]'";
						
						$local_transaction[$tr_i] = @mysql_query($sql,$con);
						$tr_i++;
						echo mysql_error();
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
						
						echo mysql_error();
					}
				}
			}
		}
		
		$sql = "SELECT DISTINCT
					CaixaFormaPagamento.IdFormaPagamento,
					CaixaFormaPagamento.ValorAbertura,
					FormaPagamento.DescricaoFormaPagamento
				FROM 
					CaixaFormaPagamento,
					FormaPagamento
				WHERE
					CaixaFormaPagamento.IdLoja = '$local_IdLoja' AND
					CaixaFormaPagamento.IdCaixa = '$local_IdCaixa' AND
					CaixaFormaPagamento.IdLoja = FormaPagamento.IdLoja AND
					CaixaFormaPagamento.IdFormaPagamento = FormaPagamento.IdFormaPagamento;";
		$res = mysql_query($sql,$con);
		
		while($lin = mysql_fetch_array($res)){
			$local_IdFormaPagamento		= $lin["IdFormaPagamento"];
			$local_ValorFormaPagamento	= $_POST["FormaPagamentoValor_".$local_IdFormaPagamento];
			$local_ValorFormaPagamento	= str_replace(".", "", $local_ValorFormaPagamento);
			$local_ValorFormaPagamento	= str_replace(",", ".", $local_ValorFormaPagamento);
			$local_QtdParcelas			= preg_replace("/([^_]*_)/", null, $_POST["FormaPagamentoQtdParcela_".$local_IdFormaPagamento]);
			$local_ValorParcela			= $_POST["FormaPagamentoValorParcela_".$local_IdFormaPagamento];
			$local_ValorParcela			= str_replace(".", "", $local_ValorParcela);
			$local_ValorParcela			= str_replace(",", ".", $local_ValorParcela);
			$local_ValorJuros			= $_POST["FormaPagamentoJurosMes_".$local_IdFormaPagamento];
			$local_ValorJuros			= str_replace(".", "", $local_ValorJuros);
			$local_ValorJuros			= str_replace(",", ".", $local_ValorJuros);
			$local_ValorTotal			= $_POST["FormaPagamentoValorTotal_".$local_IdFormaPagamento];
			$local_ValorTotal			= str_replace(".", "", $local_ValorTotal);
			$local_ValorTotal			= str_replace(",", ".", $local_ValorTotal);
			
			$sql = "INSERT INTO
						CaixaMovimentacaoFormaPagamento
					SET
						IdLoja				= '$local_IdLoja',
						IdCaixa				= '$local_IdCaixa',
						IdCaixaMovimentacao	= '$local_IdCaixaMovimentacao', 
						IdFormaPagamento	= '$local_IdFormaPagamento',
						ValorFormaPagamento	= '$local_ValorFormaPagamento',
						QtdParcelas			= '$local_QtdParcelas',
						ValorParcela		= '$local_ValorParcela',
						ValorJuros			= '$local_ValorJuros',
						ValorTotal			= '$local_ValorTotal';";
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
			
			echo mysql_error();
		}
		
		if(!in_array(false, $local_transaction)){
			$sql = "COMMIT;";
			$local_Acao = 'alterar';
			$local_Erro = 168;		
										// Mensagem de Inserчуo Positiva
		} else{
			$sql = "ROLLBACK;";
			$local_Acao = 'inserir';
			$local_Erro = 169;			// Mensagem de Inserчуo Negativa
		}
		
		@mysql_query($sql,$con);
	}
?>