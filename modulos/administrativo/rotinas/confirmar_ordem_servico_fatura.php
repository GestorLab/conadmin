<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql4	=	"select 
						Obs,
						FormaCobranca
					from 
						OrdemServico 
					where
						IdLoja = $local_IdLoja and 
						IdOrdemServico = $local_IdOrdemServico";
		$res4	=	@mysql_query($sql4,$con);
		$lin4	=	@mysql_fetch_array($res4);

		$local_FormaCobranca = $lin4[FormaCobranca];
		
		if($lin4[Obs] != ""){
			$local_Obs =	date("d/m/Y H:i:s")." [".$local_Login."] - Ordem de Servico Faturada"."\n".trim($lin4[Obs]);
		}else{
			$local_Obs =	date("d/m/Y H:i:s")." [".$local_Login."] - Ordem de Servico Faturada";
		}
		
		if($local_FormaCobrancaTemp == 2){
			$local_IdContratoAgrupador = $local_IdContratoIndividual;
		}
		
		if($local_IdLocalCobrancaTemp==0)											$local_IdLocalCobrancaTemp	 	= 'NULL';
		if($local_FormaCobrancaTemp==0 ||	$local_FormaCobrancaTemp=='')			$local_FormaCobrancaTemp	 	= 'NULL';
		if($local_IdContratoAgrupador==0 || $local_IdContratoAgrupador=='')			$local_IdContratoAgrupador	 	= 'NULL';
		if($local_QtdParcela==0 || $local_QtdParcela=='')							$local_QtdParcela	 	= 'NULL';
		
		$local_ValorTotal	=	str_replace(".", "", $local_ValorTotal);	
		$local_ValorTotal	= 	str_replace(",", ".", $local_ValorTotal);
		
		if($local_ValorDespesaLocalCobranca != ""){
			$local_ValorDespesaLocalCobranca	=	str_replace(".", "", $local_ValorDespesaLocalCobranca);	
			$local_ValorDespesaLocalCobranca	= 	"'".str_replace(",", ".", $local_ValorDespesaLocalCobranca)."'";
		}else{
			$local_ValorDespesaLocalCobranca	=	'NULL';	
		}
		
		if($local_PercentualParcela != ""){
			$x = $local_PercentualParcela;
			$local_PercentualParcela	=	explode('_', $local_PercentualParcela);
		}else{
			$local_PercentualParcela	=	'NULL';	
		}
		
		if($local_IdFormatoCarne == 1 && $local_FormaCobrancaTemp == 2){
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
		
		if($local_ValorTotal <=0){
			$sql	=	"UPDATE OrdemServico SET 
								IdStatus					= 200,
								QtdParcela					= $local_QtdParcela,
								ValorDespesaLocalCobranca	= $local_ValorDespesaLocalCobranca,
								IdLocalCobranca				= $local_IdLocalCobrancaTemp,
								IdContratoFaturamento		= $local_IdContratoAgrupador,
								FormaCobranca				= $local_FormaCobrancaTemp,
								ValorTotal					='$local_ValorTotal',
								IdPessoaEnderecoCobranca	='$local_IdPessoaEnderecoCobranca',
								LoginAlteracao				='$local_Login',
								DataAlteracao				= concat(curdate(),' ',curtime())
							WHERE 
								IdLoja					= $local_IdLoja and
								IdOrdemServico			= '$local_IdOrdemServico'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}else{
			if($local_IdStatus == 400){
				$sql	=	"UPDATE OrdemServico SET 
								IdStatus				= 200
							WHERE 
								IdLoja					= $local_IdLoja and
								IdOrdemServico			= '$local_IdOrdemServico'";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;				
			}
			$sql	=	"UPDATE OrdemServico SET 
							Faturado 					= 1,
							Obs							= \"$local_Obs\",
							QtdParcela					= $local_QtdParcela,
							ValorDespesaLocalCobranca	= $local_ValorDespesaLocalCobranca,
							IdLocalCobranca				= $local_IdLocalCobrancaTemp,
							IdContratoFaturamento		= $local_IdContratoAgrupador,
							FormaCobranca				= $local_FormaCobrancaTemp,
							ValorTotal					='$local_ValorTotal',
							IdPessoaEnderecoCobranca	='$local_IdPessoaEnderecoCobranca',
							DataFaturamento				= concat(curdate(),' ',curtime()),
							LoginAlteracao				='$local_Login',
							LoginFaturamento 			='$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					= $local_IdLoja and
							IdOrdemServico			= '$local_IdOrdemServico'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$sqlParcela	=	"select 
									OrdemServicoParcela.IdOrdemServicoParcela, 
									OrdemServicoParcela.Valor, 
									OrdemServicoParcela.Vencimento, 
									OrdemServico.ValorDespesaLocalCobranca, 
									OrdemServico.IdLocalCobranca,
									OrdemServico.IdContaDebito,
									OrdemServico.IdCartao,
									LocalCobranca.IdArquivoRemessaTipo, 
									LocalCobranca.DiasProtesto,
									LocalCobranca.IdTipoLocalCobranca
								from 
									OrdemServico left join LocalCobranca on (OrdemServico.IdLoja = LocalCobranca.IdLoja and OrdemServico.IdLocalCobranca = LocalCobranca.IdLocalCobranca),
									OrdemServicoParcela
								where 
									OrdemServico.IdLoja = $local_IdLoja and 
									OrdemServico.IdLoja = OrdemServicoParcela.IdLoja and
									OrdemServico.IdOrdemServico = $local_IdOrdemServico and
									OrdemServico.IdOrdemServico = OrdemServicoParcela.IdOrdemServico";
			$resParcela	= mysql_query($sqlParcela,$con);
			if(mysql_num_rows($resParcela) < 1){
				if($_POST['parcValor_1'] != ''){
					for($i=1;$i<=$local_QtdParcela;$i++){
						$local_Valor[$i][0] = $_POST['parcValor_'.$i]; 
						
						if($local_FormaCobrancaTemp == 2){
							$local_Valor[$i][1] = $_POST['parcDesp_'.$i]; 
							$local_Valor[$i][2] = $_POST['parcData_'.$i]; 
							$local_Valor[$i][2] = dataConv($local_Valor[$i][2],'d/m/Y','Y-m-d');
							$MesReferencia		= 'NULL';
							$Vencimento			= "'".$local_Valor[$i][2]."'";
							
							$local_Valor[$i][1]	=	str_replace(".", "", $local_Valor[$i][1]);	
							$local_Valor[$i][1]	= 	str_replace(",", ".", $local_Valor[$i][1]);
							
							$local_ValorDespesa = "'".$local_Valor[$i][1]."'";
						}else{
							$local_Valor[$i][1] = $_POST['parcData_'.$i]; 
							$MesReferencia		= "'".$local_Valor[$i][1]."'";
							$Vencimento			= 'NULL';
							$local_ValorDespesa = '0.00';
						}
					
						$sql3	=	"select (max(IdOrdemServicoParcela)+1) IdOrdemServicoParcela from OrdemServicoParcela where IdLoja = $local_IdLoja and IdOrdemServico = $local_IdOrdemServico";
						$res3	=	mysql_query($sql3,$con);
						$lin3	=	@mysql_fetch_array($res3);
						
						if($lin3[IdOrdemServicoParcela]!=NULL){ 
							$local_IdOrdemServicoParcela	=	$lin3[IdOrdemServicoParcela];
						}else{
							$local_IdOrdemServicoParcela	=	1;
						}
						
						$local_Valor[$i][0]	=	str_replace(".", "", $local_Valor[$i][0]);	
						$local_Valor[$i][0]	= 	str_replace(",", ".", $local_Valor[$i][0]);
						
						$sql	=	"INSERT INTO OrdemServicoParcela SET 
										IdLoja 						= $local_IdLoja,
										IdOrdemServico				= $local_IdOrdemServico,
										IdOrdemServicoParcela		= $local_IdOrdemServicoParcela,
										Valor						= '".$local_Valor[$i][0]."',
										ValorDespesaLocalCobranca	= $local_ValorDespesa,
										Vencimento					= $Vencimento,
										MesReferencia				= $MesReferencia;";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}
				}	
				
				$sqlParcela	=	"select 
									OrdemServicoParcela.IdOrdemServicoParcela, 
									OrdemServicoParcela.Valor, 
									OrdemServicoParcela.Vencimento, 
									OrdemServico.ValorDespesaLocalCobranca, 
									OrdemServico.IdLocalCobranca,
									OrdemServico.IdContaDebito,
									OrdemServico.IdCartao,
									LocalCobranca.IdArquivoRemessaTipo, 
									LocalCobranca.DiasProtesto,
									LocalCobranca.IdTipoLocalCobranca
								from 
									OrdemServico left join LocalCobranca on (OrdemServico.IdLoja = LocalCobranca.IdLoja and OrdemServico.IdLocalCobranca = LocalCobranca.IdLocalCobranca),
									OrdemServicoParcela
								where 
									OrdemServico.IdLoja = $local_IdLoja and 
									OrdemServico.IdLoja = OrdemServicoParcela.IdLoja and
									OrdemServico.IdOrdemServico = $local_IdOrdemServico and
									OrdemServico.IdOrdemServico = OrdemServicoParcela.IdOrdemServico";
				$resParcela	= mysql_query($sqlParcela,$con);
			}
			$i = 1;
			$local_ValorRepasseTerceiro = str_replace('.','',$local_ValorRepasseTerceiro); 
			$local_ValorRepasseTerceiro = str_replace(',','.',$local_ValorRepasseTerceiro); 
			$local_ValorRepasseTerceiroOutros = str_replace('.','',$local_ValorRepasseTerceiroOutros);
			$local_ValorRepasseTerceiroOutros = str_replace(',','.',$local_ValorRepasseTerceiroOutros);
			$local_ValorRepasseTerceiro = ($local_ValorRepasseTerceiro + $local_ValorRepasseTerceiroOutros);
			
			while($linParcela = mysql_fetch_array($resParcela)){

				// Verifica o status inicial para os contas a receber de acordo com o tipo do local de cobrança.
				switch($linParcela[IdTipoLocalCobranca]){
					case 1:
						$StatusContaReceber = 1;
						break;
					case 2:
						$StatusContaReceber = 1;
						break;
					case 3:
						$StatusContaReceber = 3;
						break;
					case 4:
						$StatusContaReceber = 3;
						break;
					case 6:
						$StatusContaReceber = 3;
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
					$IdStatus   = 1;				
				}
				
				$IdContrato = $local_IdContratoAgrupador;
				
				if($local_PercentualParcela[$i] != "NULL"){
					$ValorRepasseTerceiro = ($local_ValorRepasseTerceiro*$local_PercentualParcela[$i])/100;
				}
				
				$sql = "INSERT INTO LancamentoFinanceiro SET 
							IdLoja 					= $local_IdLoja,
							IdLancamentoFinanceiro 	= $IdLancamentoFinanceiro, 
							IdContrato 				= $IdContrato,
							IdOrdemServico 			= $local_IdOrdemServico, 
							NumParcelaEventual 		= $linParcela[IdOrdemServicoParcela], 
							Valor 					= '$linParcela[Valor]', 
							ValorRepasseTerceiro	= '$ValorRepasseTerceiro',
							IdProcessoFinanceiro 	= NULL,
							IdStatus 				= $IdStatus;";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				
				$i++;
				
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
	
					$sqlNossoNumeroComplemento = '';
	
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
								IdLoja				=$local_IdLoja,
								IdContaReceber		=$IdContaReceber,
								IdPessoa			= $local_IdPessoa,
								IdPessoaEndereco	= $local_IdPessoaEnderecoCobranca,
								ValorLancamento		='$linParcela[Valor]',
								ValorDespesas		='$linParcela[ValorDespesaLocalCobranca]',
								IdContaDebito		= $linParcela[IdContaDebito],
								IdCartao			= $linParcela[IdCartao],
								DataLancamento		=curdate(),
								IdCarne				=$IdCarne,
								NumeroDocumento		=NumeroDocumento($local_IdLoja, $linParcela[IdLocalCobranca]),
								IdLocalCobranca		=$linParcela[IdLocalCobranca],
								IdStatus			=$StatusContaReceber,
								LoginCriacao		='$local_Login',
								DataCriacao			=concat(curdate(),' ',curtime());";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;

					// Insiro o Conta Receber Vencimento
					$sql = "INSERT INTO ContaReceberVencimento SET 
										IdLoja						= $local_IdLoja,
										IdContaReceber				= $IdContaReceber,
										DataVencimento				= '$linParcela[Vencimento]', 
										ValorContaReceber			= '".($linParcela[Valor]+$linParcela[ValorDespesaLocalCobranca])."',
										ValorMulta					= '0',
										ValorJuros					= '0',
										ValorTaxaReImpressaoBoleto	= '0',
										ValorDesconto				= '0',
										ValorOutrasDespesas			= '0',
										LoginCriacao				= '$local_Login',
										DataCriacao					= concat(curdate(),' ',curtime());";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
		
					$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $IdContaReceber, 1, $local_Login, $local_IdContaDebitoCartao);
					$tr_i++;
					
					$sql = "insert into LancamentoFinanceiroContaReceber set
										IdLoja					= $local_IdLoja,
										IdLancamentoFinanceiro	= $IdLancamentoFinanceiro,
										IdContaReceber			= $IdContaReceber,
										IdStatus				= '1'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				
					$tr_i++;	
				}
			}
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}	

		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 62;
			mysql_query($sql,$con);
			
			//Individual
			if($local_FormaCobranca == 2){
				header("Location: cadastro_ordem_servico.php?IdOrdemServico=$local_IdOrdemServico");
			}else{
				header("Location: cadastro_ordem_servico.php?IdOrdemServico=$local_IdOrdemServico&Erro=$local_Erro");
			}
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 86;
			mysql_query($sql,$con);
		}
	}
?>
