<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"C";
	
	ob_start();
	
	include_once('../../../../files/conecta.php');
	if($_POST["Local"] != "ContratoServico"){
		include_once('../../../../files/funcoes.php');
	}
	include_once('../../../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"C") == false){
		$local_Erro = 2;
	}else{
		$local_Local					= $_POST['Local'];
		$local_IdContaReceberAux		= $_POST['IdContaReceber'];
		$local_IdCarne					= $_POST['IdCarne'];
		$local_IdLoja					= $_SESSION["IdLoja"];
		$local_ObsCancelamento			= formatText($_POST['ObsCancelamento'],NULL);
		$local_IdCancelarNotaFiscal		= $_POST['IdCancelarNotaFiscal'];
		$local_NumeroNF					= $_POST['NumeroNF'];
		$local_DataNF					= $_POST['DataNF'];
		$local_CancelarNotaFiscal		= $_POST['CancelarNotaFiscal'];
		$local_CancelarContaReceber		= $_POST['CancelarContaReceber'];
		$local_CancelarOrdemServico		= $_POST['CancelarOrdemServico'];
		
		$tr_i = 0;
		
		if($_GET['CancelarContaReceber'] != ""){
			$local_CancelarContaReceber		= $_GET['CancelarContaReceber'];
		}
		if($_GET['Local'] != ""){
			$local_Local					= $_GET['Local'];
		}
		
		if($local_CancelarNotaFiscal == '1'){
			if($local_IdCancelarNotaFiscal == '1'){
				if(cancela_nf($local_IdLoja, $local_IdContaReceberAux, $local_NumeroNF) == 68){
					$local_transaction[$tr_i] = false;
					$tr_i++;
				} else{
					$local_ObsNFC = date("d/m/Y H:i:s")." [".$local_Login."] - Nota Fiscal n° ".$local_NumeroNF." cancelada de acordo com Cancelamento do Contas a Receber.";
				}
			} else {
				$local_ObsNFC = date("d/m/Y H:i:s")." [".$local_Login."] - Nota Fiscal n° ".$local_NumeroNF." não foi cancelada de acordo com Cancelamento do Contas a Receber.";
			}
		}
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$qtd = 0;
		
		if($local_IdContaReceberAux == "" && $local_IdCarne !=""){
			$where = '';
			if($local_Local == "Carne"){
				if($local_CancelarContaReceber != ''){
					$where .= " and ContaReceber.IdContaReceber in ($local_CancelarContaReceber)";
				} else{
					$where .= " and ContaReceber.IdContaReceber in (0)";
				}
			}
			
			$sqlCarne	=	"select
						 		ContaReceber.IdContaReceber
							from
					     		ContaReceber	
							where
					     		ContaReceber.IdLoja = $local_IdLoja and
					     		ContaReceber.IdCarne = $local_IdCarne
								$where";
			$resCarne	=	mysql_query($sqlCarne,$con);
			while($linCarne	= mysql_fetch_array($resCarne)){
				$ContaReceber[$qtd]	=	$linCarne[IdContaReceber];
				$qtd++;	
			}
		}else{
			$ContaReceber[$qtd]	=	$local_IdContaReceberAux;
			$qtd++;	
		}
		
		if(($local_IdCarne =="" && $local_Local == "CancelarMultiplasContasReceber") || ($local_IdCarne == "" && $local_Local == "ContratoStatus") || ($local_IdCarne == "" && $local_Local == "ContratoServico")){
			$where = '';
			$qtd--;
			if($local_CancelarContaReceber != ''){
				$where .= " and ContaReceber.IdContaReceber in ($local_CancelarContaReceber)";
			} else{
				$where .= " and ContaReceber.IdContaReceber in (0)";
			}
			
			$sqlConta	=	"select
						 		ContaReceber.IdContaReceber
							from
					     		ContaReceber	
							where
					     		ContaReceber.IdLoja = $local_IdLoja
								$where";
			$resConta	=	mysql_query($sqlConta,$con);
			while($linConta	= mysql_fetch_array($resConta)){
				$ContaReceber[$qtd]	=	$linConta[IdContaReceber];
				$qtd++;	
			}
		}
		
		$qtd--;
		
		while(-1 < $qtd){
			$local_IdContaReceber	=	$ContaReceber[$qtd];
		
			$sql3 = "select
						Demonstrativo.IdLoja,
						Demonstrativo.IdContaReceber,
						Demonstrativo.IdLancamentoFinanceiro,
						Demonstrativo.Tipo,
						Demonstrativo.Codigo
					from
						Demonstrativo,
						Pessoa
					where
						Demonstrativo.IdLoja = $local_IdLoja and
						Demonstrativo.IdPessoa = Pessoa.IdPessoa and
						Demonstrativo.IdContaReceber = $local_IdContaReceber
					order by
						Demonstrativo.Tipo,
						Demonstrativo.Codigo,
						Demonstrativo.IdLancamentoFinanceiro DESC";
			$res3 = @mysql_query($sql3,$con);
			while($lin3	= @mysql_fetch_array($res3)){
				$sql = "select
						IdLancamentoFinanceiro,
						IdOrdemServico,
						ObsLancamentoFinanceiro
					from
						LancamentoFinanceiro
					where
						IdLoja = '$local_IdLoja' and
						IdLancamentoFinanceiro = '$lin3[IdLancamentoFinanceiro]'";
				$res = mysql_query($sql,$con);
				if($lin = mysql_fetch_array($res)){
					if($local_ObsCancelamento !=""){
						$local_Obs = date("d/m/Y H:i:s")." [".$local_Login."] - Motivo cancelamento: $local_ObsCancelamento";
					}
						
					if($lin[ObsLancamentoFinanceiro] != ""){
						if($local_Obs != ""){
							$local_Obs .= "\n";
						}
						$local_Obs .= trim($lin[ObsLancamentoFinanceiro]);
					}
				}
				
				$sql	=	"UPDATE LancamentoFinanceiro SET
								IdStatus				= '0',
								ObsLancamentoFinanceiro = '$local_Obs',
								DataCancelamento 		= concat(curdate(),' ',curtime()),
								LoginCancelamento  		= '$local_Login'
							WHERE 
								IdLoja					= '$local_IdLoja' and
								IdLancamentoFinanceiro	= '$lin3[IdLancamentoFinanceiro]'";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
				$tr_i++;

				switch($lin3[Tipo]){
					case 'CO': 
						if($_POST["VoltarDataBase_".$lin3[IdLancamentoFinanceiro]] == '1'){
							
							$sql2	=	"select 
											DataReferenciaInicial
										 from
											LancamentoFinanceiro
										 where
											IdLoja = $local_IdLoja and
											IdLancamentoFinanceiro = $lin3[IdLancamentoFinanceiro] and
											IdContaEventual is null and
											DataReferenciaInicial is not null;";
	
							$res2	=	mysql_query($sql2,$con);
							while($lin2	=	mysql_fetch_array($res2)){
								$sqlObsContrato = "select 
														DataBaseCalculo,
														Obs											
													from
														Contrato
													where
														IdLoja = '$local_IdLoja' and
														IdContrato = '$lin3[Codigo]';";
								$resObsContrato = mysql_query($sqlObsContrato,$con);
								$linObsContrato = @mysql_fetch_array($resObsContrato);
					
								$lin2[DataReferenciaInicialTemp] = incrementaData($lin2[DataReferenciaInicial],-1);
								$local_ObsContrato = date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Base LF:".$lin3[IdLancamentoFinanceiro]." [".dataConv($linObsContrato[DataBaseCalculo],"Y-m-d","d/m/Y")." > ".dataConv($lin2[DataReferenciaInicialTemp],"Y-m-d","d/m/Y")."]";
							
								if($linObsContrato[Obs] != ""){
									if($local_ObsContrato != ""){
										$local_ObsContrato .= "\n";
									}
									$local_ObsContrato .= trim($linObsContrato[Obs]);
								}
				
								$sql	=	"UPDATE Contrato SET
												DataBaseCalculo		= '$lin2[DataReferenciaInicialTemp]', 
												LoginAlteracao		= '$local_Login',
												DataAlteracao		= concat(curdate(),' ',curtime()),
												Obs					= '$local_ObsContrato'
											 WHERE 
												IdLoja				= '$local_IdLoja' and
												IdContrato			= '$lin3[Codigo]';";
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);

								//$lin2[DataReferenciaInicialTemp] = explode("-",$lin2[DataReferenciaInicialTemp]);
								/*if(!@checkdate($lin2[DataReferenciaInicialTemp][1], $lin2[DataReferenciaInicialTemp][2], $lin2[DataReferenciaInicialTemp][0])){
									$local_transaction[$tr_i] = false;
								}*/
								$tr_i++;
							}
						}
						if($_POST["ReaproveitarCredito_".$lin3[IdLancamentoFinanceiro]] == '1'){
							$sql2	=	"select
											LancamentoFinanceiro.IdLancamentoFinanceiro,
											LancamentoFinanceiro.IdContrato,
											LancamentoFinanceiro.Valor
										from
											LancamentoFinanceiro
										where
											LancamentoFinanceiro.IdLoja = $local_IdLoja and
											LancamentoFinanceiro.IdLancamentoFinanceiro = $lin3[IdLancamentoFinanceiro]";
							$res2	=	@mysql_query($sql2,$con);
							$lin2	=	@mysql_fetch_array($res2);
							
							$sql = "select
										(max(IdLancamentoFinanceiro)+1) IdLancamentoFinanceiro
									from
										LancamentoFinanceiro
									where
										IdLoja = $local_IdLoja";
							$res = mysql_query($sql,$con);
							$lin = mysql_fetch_array($res);
			
							$sql = "INSERT INTO LancamentoFinanceiro SET 
										IdLoja = $local_IdLoja,
										IdLancamentoFinanceiro = $lin[IdLancamentoFinanceiro], 
										IdContrato = $lin2[IdContrato],
										Valor = '$lin2[Valor]', 
										IdProcessoFinanceiro = NULL,
										IdStatus = 2";
							$local_transaction[$tr_i]	=	mysql_query($sql,$con);						
							$tr_i++;
						}
						break;
					case 'EV':
						if($_POST["CancelarContaEventual_".$lin3[IdLancamentoFinanceiro]] != '1'){
							$sql2	=	"select
											LancamentoFinanceiro.IdLancamentoFinanceiro,
											LancamentoFinanceiro.IdContaEventual,
											LancamentoFinanceiro.IdContrato,
											LancamentoFinanceiro.NumParcelaEventual,
											LancamentoFinanceiro.Valor
										from
											LancamentoFinanceiro
										where
											LancamentoFinanceiro.IdLoja = $local_IdLoja and
											LancamentoFinanceiro.IdLancamentoFinanceiro = $lin3[IdLancamentoFinanceiro]";
							$res2	=	@mysql_query($sql2,$con);
							$lin2	=	@mysql_fetch_array($res2);
							
							if($lin2[IdContrato] == ''){
								$lin2[IdContrato] = "NULL";
							}
							
							$sql = "select
										(max(IdLancamentoFinanceiro)+1) IdLancamentoFinanceiro
									from
										LancamentoFinanceiro
									where
										IdLoja = $local_IdLoja";
							$res = mysql_query($sql,$con);
							$lin = mysql_fetch_array($res);

							if($lin2[IdContrato] == ''){	
								$sqlContrato = "select
													IdContrato,
													FormaCobranca
												from
													ContaEventual
												where
													IdLoja = $local_IdLoja and
													IdContaEventual = $lin2[IdContaEventual]";
								$resContrato = mysql_query($sqlContrato,$con);
								if($linContrato = mysql_fetch_array($resContrato)){
									$lin2[IdContrato] = $linContrato[IdContrato];

									if($linContrato[FormaCobranca] == 2){
										$sql = "update ContaEventualParcela set 
													MesReferencia=concat(substring(Vencimento,6,2),'/',substring(Vencimento,1,4)) 
												where 
													IdLoja = $local_IdLoja and 
													IdContaEventual = $lin2[IdContaEventual] and 
													IdContaEventualParcela = $lin2[NumParcelaEventual]";
										$local_transaction[$tr_i]	=	mysql_query($sql,$con);									
										$tr_i++;
									}
								}else{
									$lin2[IdContrato] = 'NULL';
								}
							}
							if($lin2[IdContrato] == "") $lin2[IdContrato] = 'NULL';
							
							$local_ObsTemp = date("d/m/Y H:i:s")." [".$local_Login."] - Lançamento Financeiro Original: $lin3[IdLancamentoFinanceiro].";
							
							$sql = "INSERT INTO LancamentoFinanceiro SET 
										IdLoja 					= $local_IdLoja,
										IdLancamentoFinanceiro 	= $lin[IdLancamentoFinanceiro], 
										IdContrato 				= $lin2[IdContrato],
										IdContaEventual 		= $lin2[IdContaEventual], 
										NumParcelaEventual 		= $lin2[NumParcelaEventual], 
										Valor 					= '$lin2[Valor]', 
										IdProcessoFinanceiro 	= NULL,
										ObsLancamentoFinanceiro = '$local_ObsTemp',
										IdStatus 				= 2";
							$local_transaction[$tr_i]	=	mysql_query($sql,$con);							
							$tr_i++;
							
							if($local_Obs != '' && $lin[IdLancamentoFinanceiro] != ''){
								$local_Obs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Novo Lançamento Financeiro: $lin[IdLancamentoFinanceiro].";
								
								$sql	=	"UPDATE LancamentoFinanceiro SET
												ObsLancamentoFinanceiro = '$local_Obs',
												DataCancelamento 		= concat(curdate(),' ',curtime()),
												LoginCancelamento  		= '$local_Login'
											WHERE 
												IdLoja					= '$local_IdLoja' and
												IdLancamentoFinanceiro	= '$lin3[IdLancamentoFinanceiro]'";
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
								$tr_i++;
							}
						} else {
							$sql2 ="select
										count(IdLancamentoFinanceiro) Qtd
									from
										LancamentoFinanceiro
									where
										IdLoja = '$local_IdLoja' and
										IdLancamentoFinanceiro = '$lin3[IdLancamentoFinanceiro]' and
										IdStatus != 0";
							$res2 = mysql_query($sql2,$con);
							$lin2 = mysql_fetch_array($res2);
							
							if($lin2[Qtd] == 0) {
								$sql = "update 
											ContaEventual 
										set
											IdStatus		= 0,
											LoginAlteracao	='$local_Login',
											DataAlteracao	= concat(curdate(),' ',curtime())
										where 
											IdLoja = $local_IdLoja and
											IdContaEventual = $lin3[Codigo];";
								$local_transaction[$tr_i] = mysql_query($sql, $con);
								$tr_i++;
							}
						}
						break;
					case 'OS':					
						if($_POST["CancelarOrdemServico_".$lin3[IdLancamentoFinanceiro]] != '1'){
							$sql2	=	"select
											LancamentoFinanceiro.IdLancamentoFinanceiro,
											LancamentoFinanceiro.IdOrdemServico,
											LancamentoFinanceiro.IdContrato,
											LancamentoFinanceiro.NumParcelaEventual,
											LancamentoFinanceiro.Valor
										from
											LancamentoFinanceiro
										where
											LancamentoFinanceiro.IdLoja = $local_IdLoja and
											LancamentoFinanceiro.IdLancamentoFinanceiro = $lin3[IdLancamentoFinanceiro]";
							$res2	=	@mysql_query($sql2,$con);
							$lin2	=	@mysql_fetch_array($res2);
							
							if($lin2[IdContrato] == ''){
								$lin2[IdContrato] = "NULL";
							}
							
							$sql = "select
										(max(IdLancamentoFinanceiro)+1) IdLancamentoFinanceiro
									from
										LancamentoFinanceiro
									where
										IdLoja = $local_IdLoja";
							$res = mysql_query($sql,$con);
							$lin = mysql_fetch_array($res);
							
							$local_ObsTemp = date("d/m/Y H:i:s")." [".$local_Login."] - Lançamento Financeiro Original: $lin3[IdLancamentoFinanceiro].";
								
							$sql = "INSERT INTO LancamentoFinanceiro SET 
										IdLoja = $local_IdLoja,
										IdLancamentoFinanceiro = $lin[IdLancamentoFinanceiro], 
										IdContrato = $lin2[IdContrato],
										IdOrdemServico = $lin2[IdOrdemServico], 
										NumParcelaEventual = $lin2[NumParcelaEventual], 
										Valor = '$lin2[Valor]', 
										IdProcessoFinanceiro = NULL,
										ObsLancamentoFinanceiro = '$local_ObsTemp',
										IdStatus = 2";
							$local_transaction[$tr_i]	=	mysql_query($sql,$con);						
							$tr_i++;
							
							if($local_Obs != '' && $lin[IdLancamentoFinanceiro] != ''){
								$local_Obs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Novo Lançamento Financeiro: $lin[IdLancamentoFinanceiro].";
								
								$sql	=	"UPDATE LancamentoFinanceiro SET
												ObsLancamentoFinanceiro = '$local_Obs',
												DataCancelamento 		= concat(curdate(),' ',curtime()),
												LoginCancelamento  		= '$local_Login'
											WHERE 
												IdLoja					= '$local_IdLoja' and
												IdLancamentoFinanceiro	= '$lin3[IdLancamentoFinanceiro]'";
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
								$tr_i++;
							}
						}
						break;
					case 'EF':					
						if($_POST["CancelarEncargoFinanceiro_".$lin3[IdLancamentoFinanceiro]] == '1'){
							$sql2 = "select
										LancamentoFinanceiro.IdLancamentoFinanceiro,
										LancamentoFinanceiro.IdEncargoFinanceiro,
										LancamentoFinanceiro.IdContrato,
										LancamentoFinanceiro.NumParcelaEventual,
										LancamentoFinanceiro.ObsLancamentoFinanceiro,
										LancamentoFinanceiro.Valor
									from
										LancamentoFinanceiro
									where
										LancamentoFinanceiro.IdLoja = $local_IdLoja and
										LancamentoFinanceiro.IdLancamentoFinanceiro = $lin3[IdLancamentoFinanceiro]";
							$res2 = @mysql_query($sql2,$con);
							$lin2 = @mysql_fetch_array($res2);
							
							$sql = "UPDATE LancamentoFinanceiro SET
										IdStatus			= 0,
										DataCancelamento	= concat(curdate(),' ',curtime()),
										LoginCancelamento	= '$local_Login'
									WHERE 
										IdLoja = '$local_IdLoja' and
										IdLancamentoFinanceiro = '$lin3[IdLancamentoFinanceiro]'";
							$local_transaction[$tr_i] = mysql_query($sql,$con);			
							$tr_i++;
							
							if($lin2[IdEncargoFinanceiro] != ''){
								$sql = "UPDATE ContaReceberEncargoFinanceiro SET
											IdStatus		= 0
										WHERE 
											IdLoja = '$local_IdLoja' and
											IdEncargoFinanceiro = '$lin2[IdEncargoFinanceiro]'";
								$local_transaction[$tr_i] = mysql_query($sql,$con);			
								$tr_i++;
							}
						}
						break;
					case 'CR':	
						if($_POST["CancelarContaReceber_".$lin3[IdLancamentoFinanceiro]] == '1'){
							$sql2 ="select 
										IdContaReceber 
									from
										Demonstrativo 
									where 
										IdLoja = '$local_IdLoja' and
										IdLancamentoFinanceiro = '$lin3[IdLancamentoFinanceiro]'";
							$res2 = @mysql_query($sql2,$con);
							$lin2 = @mysql_fetch_array($res2);
							
							$sql = "update LancamentoFinanceiro set
										IdStatus			= 0,
										DataCancelamento	= concat(curdate(),' ',curtime()),
										LoginCancelamento	= '$local_Login'
									where 
										IdLoja = '$local_IdLoja' and
										IdLancamentoFinanceiro = '$lin3[IdLancamentoFinanceiro]'";
							$local_transaction[$tr_i] = mysql_query($sql,$con);			
							$tr_i++;
						
							if($lin2[IdContaReceberAgrupado] != ''){
								$sql = "update ContaReceber set
											IdStatus		= 0
										where 
											IdLoja = '$local_IdLoja' and
											IdContaReceber = '$lin2[IdContaReceber]'";
								$local_transaction[$tr_i] = mysql_query($sql,$con);
								$tr_i++;
							}
						}
						break;
				}
			}
			
			$sql	=	"select Obs, IdStatusConfirmacaoPagamento from ContaReceber where IdLoja = $local_IdLoja and IdContaReceber = $local_IdContaReceber";
			$res	=	@mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
			
			if($lin[Obs]!=""){
				$lin[Obs]	=	trim($lin[Obs]);
			}
			
			if($lin[IdStatusConfirmacaoPagamento] == '' || $lin[IdStatusConfirmacaoPagamento] == 1){
				$lin[IdStatusConfirmacaoPagamento] = "NULL";
			}
			
			if($local_ObsCancelamento != ""){
				$local_Obs		=	date("d/m/Y H:i:s")." [".$local_Login."] - Observações do Cancelamento: ".trim($local_ObsCancelamento);
				if($lin[Obs] !=""){
					"\n".$lin[Obs];
				}
			}else{
				$local_Obs		=	$lin[Obs];
			}
			
			if($local_ObsNFC != ""){
				$local_Obs		=	$local_ObsNFC."\n".$local_Obs;
			}
			
			$sql	=	"UPDATE ContaReceber SET
								IdStatus						= '0',
								IdStatusConfirmacaoPagamento	=  $lin[IdStatusConfirmacaoPagamento],
								Obs								= '$local_Obs',
								LoginAlteracao					= '$local_Login',
								DataAlteracao					= concat(curdate(),' ',curtime())
							WHERE 
								IdLoja							= '$local_IdLoja' and
								IdContaReceber					= '$local_IdContaReceber'";
								
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
			$tr_i++;

			$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $local_IdContaReceber, 5, $local_Login);
			$tr_i++;
			
			$qtd--;
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;	
				break;
			}
		}
		$cancelamento = false;
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 67;			// Mensagem de Alteração Positiva
			$cancelamento = true;
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 68;			// Mensagem de Alteração Negativa
			$cancelamento = false;
		}
		mysql_query($sql,$con);
		
		$local = $_POST['Local'];
		
		if($local_CancelarOrdemServico == 1){
			$local_IdOrdemServico = $_POST['IdOrdemServico'];
			include('editar_ordem_servico.php');
		}else{
			if(getCodigoInterno(60,1) == '1'){//cancelar automaticamente a OS
				$sql5 = "select
							Demonstrativo.Codigo
						from
							Demonstrativo,
							Pessoa
						where
							Demonstrativo.IdLoja = $local_IdLoja and
							Demonstrativo.IdPessoa = Pessoa.IdPessoa and
							Demonstrativo.IdContaReceber = $local_IdContaReceber";
				$res5 = @mysql_query($sql5,$con);
				$lin5 = @mysql_fetch_array($res5);
				
				$sql6 ="select 
							IdStatus 
						from
							OrdemServico 
						where
							IdLoja = $local_IdLoja and
							IdOrdemServico = $lin5[Codigo]";
				$res6 = @mysql_query($sql6,$con);
				$lin6 = @mysql_fetch_array($res6);
				
				if($lin6['IdStatus'] == '200'){//Só cancela automaticamente o Status da OS quando a mesma está concluida!
					$local_CancelarOrdemServico = 1;
					$local_IdOrdemServico = $lin5['Codigo'];
					include('editar_ordem_servico.php');
				}
			}
		}
		
		if($local == "Carne"){
			if($cancelamento == false){
				header("Location: ../../cadastro_carne.php?Erro=$local_Erro");
			} else{
				header("Location: ../../listar_carne.php?Erro=$local_Erro");
			}
		} else{
			if($local_Local == "ContratoStatus"){
				return;
			}
			if($local_Local=="CancelarMultiplasContasReceber"){
				header("Location: ../../listar_conta_receber.php?IdContaCancelada=$local_CancelarContaReceber&Erro=$local_Erro");
				return;
			}
			if($local_IdCarne==""){
				header("Location: ../../cadastro_conta_receber.php?IdContaReceber=$local_IdContaReceber&Erro=$local_Erro");
			}else{
				header("Location: ../../listar_conta_receber.php?IdCarne=$local_IdCarne&Erro=$local_Erro");
			}
		}
	}
?>