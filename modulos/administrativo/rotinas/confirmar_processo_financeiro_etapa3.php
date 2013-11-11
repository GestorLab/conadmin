<?
// 3a ETAPA ------------------------------------------------------------------
// Agrupar	= S
// Carne	= N

$ContaReceber	= null;
$Datas			= null;
$Contrato		= null;
$DespesasBoleto	= null;
$Pessoa			= null;

$sqlLancamentoFinanceiro = "select
								Pessoa.IdPessoa,
								LancamentoFinanceiro.IdLancamentoFinanceiro,
								LancamentoFinanceiro.Valor,
								if(LancamentoFinanceiro.IdOrdemServico != '', concat(substring(OrdemServicoParcela.MesReferencia,4,4),'-',substring(OrdemServicoParcela.MesReferencia,1,2)), if(LancamentoFinanceiro.IdContaEventual != '', concat(substring(ContaEventualParcela.MesReferencia,4,4),'-',substring(ContaEventualParcela.MesReferencia,1,2)), if(LancamentoFinanceiro.IdContrato != '', substring(LancamentoFinanceiro.DataReferenciaFinal,1,7), ''))) MesReferencia,
								if((ContaEventual.FormaCobranca = 1 or OrdemServico.FormaCobranca = 1), Contrato.IdPessoaEnderecoCobranca, if(LancamentoFinanceiro.IdOrdemServico != '', OrdemServico.IdPessoaEnderecoCobranca, if(LancamentoFinanceiro.IdContaEventual != '', ContaEventual.IdPessoaEnderecoCobranca, if(LancamentoFinanceiro.IdContrato != '', Contrato.IdPessoaEnderecoCobranca, '')))) IdPessoaEnderecoCobranca,
								LancamentoFinanceiro.DataReferenciaInicial,
								LancamentoFinanceiro.DataReferenciaFinal,
								ProcessoFinanceiro.Filtro_IdLocalCobranca IdLocalCobranca,
								LocalCobranca.ValorCobrancaMinima,
								LocalCobranca.ValorDespesaLocalCobranca,
								ProcessoFinanceiro.MesVencimento,
								ProcessoFinanceiro.MenorVencimento,
								Pessoa.Cob_CobrarDespesaBoleto,
								Contrato.DataUltimaCobranca,
								Contrato.IdContratoAgrupador,
								Contrato.IdContrato,
								Contrato.DiaCobranca,
								ProcessoFinanceiro.MesReferencia MesReferenciaProcessoFinanceiro,
								Contrato.IdCartao,
								Contrato.IdContaDebito,
								IF(Contrato.IdContaDebito IS NULL, IF(Contrato.IdCartao IS NULL, 0,Contrato.IdCartao),Contrato.IdContaDebito) IdAgrupadorAux
							from								
								LancamentoFinanceiro 
									left join ContaEventualParcela on 
										(LancamentoFinanceiro.IdLoja = ContaEventualParcela.IdLoja and  LancamentoFinanceiro.IdContaEventual = ContaEventualParcela.IdContaEventual and ContaEventualParcela.IdContaEventualParcela = LancamentoFinanceiro.NumParcelaEventual)

									left join ContaEventual on 
										(ContaEventual.IdLoja = ContaEventualParcela.IdLoja and  ContaEventual.IdContaEventual = ContaEventualParcela.IdContaEventual)

									left join OrdemServicoParcela on 
										(LancamentoFinanceiro.IdLoja = OrdemServicoParcela.IdLoja and  LancamentoFinanceiro.IdOrdemServico = OrdemServicoParcela.IdOrdemServico and OrdemServicoParcela.IdOrdemServicoParcela = LancamentoFinanceiro.NumParcelaEventual)

									left join OrdemServico on 
										(OrdemServico.IdLoja = OrdemServicoParcela.IdLoja and  OrdemServico.IdOrdemServico = OrdemServicoParcela.IdOrdemServico),
								Contrato,
								Pessoa,
								LocalCobranca,
								ProcessoFinanceiro
							where
								LancamentoFinanceiro.IdLoja = $local_IdLoja and 
								LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
								LancamentoFinanceiro.IdLoja = LocalCobranca.IdLoja and 
								LancamentoFinanceiro.IdLoja = ProcessoFinanceiro.IdLoja and 
								LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
								LancamentoFinanceiro.IdProcessoFinanceiro = ProcessoFinanceiro.IdProcessoFinanceiro and
								LancamentoFinanceiro.IdStatus = 3 and
								LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
								Contrato.IdPessoa = Pessoa.IdPessoa and
								Pessoa.AgruparContratos != 2 and
								Contrato.IdPeriodicidade != 8 and
								Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca
							order by
								Pessoa.IdPessoa,
								IdPessoaEnderecoCobranca,
								Contrato.DiaCobranca,
								LancamentoFinanceiro.IdContrato,
								MesReferencia,
								IdAgrupadorAux";
$resLancamentoFinanceiro = mysql_query($sqlLancamentoFinanceiro,$con);
while($linLancamentoFinanceiro = mysql_fetch_array($resLancamentoFinanceiro)){

	$linLancamentoFinanceiro[MesReferenciaProcessoFinanceiro] = dataConv($linLancamentoFinanceiro[MesReferenciaProcessoFinanceiro],"m/Y","Y-m");

	// Tudo que for gerado antes, vai agrupado na primeira parcela
	if(str_replace("-","",$linLancamentoFinanceiro[MesReferencia]) < str_replace("-","",$linLancamentoFinanceiro[MesReferenciaProcessoFinanceiro])){
		$linLancamentoFinanceiro[MesReferencia] = $linLancamentoFinanceiro[MesReferenciaProcessoFinanceiro];
	}

	if($linLancamentoFinanceiro[IdContratoAgrupador] != ''){
		$sqlDiaCobranca = "select
								DiaCobranca
							from
								Contrato
							where
								IdLoja = $local_IdLoja and
								IdContrato = $linLancamentoFinanceiro[IdContratoAgrupador]";
		$resDiaCobranca = mysql_query($sqlDiaCobranca,$con);
		$linDiaCobranca = mysql_fetch_array($resDiaCobranca);

		$linLancamentoFinanceiro[DiaCobranca] = $linDiaCobranca[DiaCobranca];
	}

#	echo "Etapa 3 -> Agrupar = S -> Carne = N (Lancamento)<br>";

	if($linLancamentoFinanceiro[DiaCobranca] < $linLancamentoFinanceiro[MenorVencimento]){
		$linLancamentoFinanceiro[DiaCobranca] = $linLancamentoFinanceiro[MenorVencimento];
	}

	if($local_DiaPrimeiroVencimento != ''){
		$linLancamentoFinanceiro[DiaCobranca] = $local_DiaPrimeiroVencimento;
	}

	if($linLancamentoFinanceiro[DiaCobranca] < 10 && strlen($linLancamentoFinanceiro[DiaCobranca]) == 1){
		$linLancamentoFinanceiro[DiaCobranca] = '0'.$linLancamentoFinanceiro[DiaCobranca];
	}

	if($linLancamentoFinanceiro[DiaCobranca] != substr($linLancamentoFinanceiro[DataReferenciaInicial],8,2) && str_replace("-","",$linLancamentoFinanceiro[MesReferencia]) < str_replace("-","",$lin[MesReferencia])){
		$linLancamentoFinanceiro[MesReferencia] = dataConv(incrementaMesReferencia(dataConv($linLancamentoFinanceiro[MesReferencia],"Y-m","m-Y"),-1),"m/Y","Y-m");
	}

	if($linLancamentoFinanceiro[IdPessoaEnderecoCobranca] == 0 || $linLancamentoFinanceiro[IdPessoaEnderecoCobranca] == ''){
		$sqlEnderecoCobranca = "select
									IdEnderecoDefault
								from
									Pessoa
								where
									IdPessoa = $linLancamentoFinanceiro[IdPessoa]";
		$resEnderecoCobranca = mysql_query($sqlEnderecoCobranca,$con);
		$linEnderecoCobranca = mysql_fetch_array($resEnderecoCobranca);

		$linLancamentoFinanceiro[IdPessoaEnderecoCobranca] = $linEnderecoCobranca[IdEnderecoDefault];
	}
	
	if($linLancamentoFinanceiro[Valor] == 0){
		$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=0 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$linLancamentoFinanceiro[IdLancamentoFinanceiro];
		$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
		$tr_i++;
			
		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$linLancamentoFinanceiro[IdLancamentoFinanceiro]." foi cancelado. Valor = 0,00 (ETA3A)";
						
		$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
				LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
			  WHERE 
				IdLoja=$local_IdLoja AND 
				IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
		$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
		$tr_i++;
	}else{
		$ContaReceber[$linLancamentoFinanceiro[IdPessoa]][$linLancamentoFinanceiro[IdPessoaEnderecoCobranca]][$linLancamentoFinanceiro[DiaCobranca]][$linLancamentoFinanceiro[IdAgrupadorAux]][$linLancamentoFinanceiro[IdLancamentoFinanceiro]] = $linLancamentoFinanceiro[Valor];
		$Datas[$linLancamentoFinanceiro[IdLancamentoFinanceiro]][DataReferenciaFinal] = $linLancamentoFinanceiro[DataReferenciaFinal];
		$Datas[$linLancamentoFinanceiro[IdLancamentoFinanceiro]][DataTerminoContrato] = $linLancamentoFinanceiro[DataUltimaCobranca];
		$Contrato[$linLancamentoFinanceiro[IdLancamentoFinanceiro]] = $linLancamentoFinanceiro[IdContrato];
	}

	$DespesasBoleto[$linLancamentoFinanceiro[IdPessoa]]	= $linLancamentoFinanceiro[Cob_CobrarDespesaBoleto];

	$MesVencimento				= $linLancamentoFinanceiro[MesVencimento];
	$IdLocalCobranca			= $linLancamentoFinanceiro[IdLocalCobranca];
	$ValorDespesaLocalCobranca	= $linLancamentoFinanceiro[ValorDespesaLocalCobranca];
	$ValorCobrancaMinima		= $linLancamentoFinanceiro[ValorCobrancaMinima];
	$MenorVencimento			= $linLancamentoFinanceiro[MenorVencimento];
}

if(count($ContaReceber) > 0){

	$Pessoa = array_keys($ContaReceber);

	for($i=0; $i <count($Pessoa); $i++){

		$sqlOrdemServico = "select
								LancamentoFinanceiroDados.IdOrdemServico,
								Pessoa.IdPessoa,
								LancamentoFinanceiro.IdLancamentoFinanceiro,
								LancamentoFinanceiro.Valor,
								OrdemServico.IdPessoaEnderecoCobranca
							from
								LancamentoFinanceiroDados,
								OrdemServico,
								Pessoa,
								LancamentoFinanceiro
							where
								LancamentoFinanceiroDados.IdLoja = $local_IdLoja and
								LancamentoFinanceiroDados.IdLoja = OrdemServico.IdLoja and
								LancamentoFinanceiroDados.IdLoja = LancamentoFinanceiro.IdLoja and
								LancamentoFinanceiroDados.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
								LancamentoFinanceiroDados.IdOrdemServico = OrdemServico.IdOrdemServico and
								OrdemServico.DataFaturamento is null and
								OrdemServico.IdPessoa = Pessoa.IdPessoa and
								LancamentoFinanceiroDados.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
								Pessoa.IdPessoa = $Pessoa[$i]";
		$resOrdemServico = mysql_query($sqlOrdemServico,$con);
		while($linOrdemServico = mysql_fetch_array($resOrdemServico)){

#			echo "Etapa 3 -> Agrupar = S -> Carne = N (OS)<br>";

			$ContaReceber[$linOrdemServico[IdPessoa]][$linOrdemServico[IdPessoaEnderecoCobranca]][$linOrdemServico[IdLancamentoFinanceiro]] = $linOrdemServico[Valor];
			$OrdemServico[$linOrdemServico[IdLancamentoFinanceiro]] = $linOrdemServico[IdOrdemServico];
		}
	}
}

for($i=0; $i <count($Pessoa); $i++){

#	echo "Etapa 3 -> Agrupar = S -> Carne = N (Conta a Receber)<br>";
		
	// Cobrar taxa do boleto S/N
	if($DespesasBoleto[$Pessoa[$i]] == 2){
		$ValorDespesaLocalCobrancaTemp = 0;
	}else{
		$ValorDespesaLocalCobrancaTemp = $ValorDespesaLocalCobranca;
	}

	$IdPessoaEnderecoCobranca = @array_keys($ContaReceber[$Pessoa[$i]]);

	for($ii = 0; $ii < count($IdPessoaEnderecoCobranca); $ii++){

		$DiaCobranca = @array_keys($ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]]);

		for($iii = 0; $iii < count($DiaCobranca); $iii++){

			$IdAgrupadorAux = @array_keys($ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]][$DiaCobranca[$iii]]);

			for($iiii = 0; $iiii < count($IdAgrupadorAux); $iiii++){

				$MesVencimentoTemp	= $MesVencimento;
				$DiaVencimento		= $DiaCobranca[$iii];

 				$TotalParcela			= @array_sum($ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]][$DiaCobranca[$iii]][$IdAgrupadorAux[$iiii]]);

				$Cobrar					= false;
				$LancamentoFinanceiro	= null;

				$MesTemp = substr($MesVencimentoTemp,0,2); // Mes
				$AnoTemp = substr($MesVencimentoTemp,3,4); // Ano
				$DiaTemp = ultimoDiaMes($MesTemp, $AnoTemp);

				if($DiaTemp < $DiaVencimento){
					$DiaVencimento = $DiaTemp;
				}

				if($local_DataPrimeiroVenc != ''){
					$dataVencimento = dataConv($local_DataPrimeiroVenc,'d/m/Y','Y-m-d');
				}else{
					$dataVencimento = dataConv($MesVencimentoTemp,'m/Y','Y-m')."-".$DiaVencimento;
				}

				$sqlContaReceber = "select max(IdContaReceber) IdContaReceber from ContaReceber where IdLoja = $local_IdLoja";
				$resContaReceber = mysql_query($sqlContaReceber,$con);
				$linContaReceber = mysql_fetch_array($resContaReceber);
					
				$IdContaReceber = $linContaReceber[IdContaReceber];
				if($IdContaReceber == null){
					$IdContaReceber = 1;
				}else{
					$IdContaReceber++;
				}
					
				$IdLancamentoFinanceiro = @array_keys($ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]][$DiaCobranca[$iii]][$IdAgrupadorAux[$iiii]]);

				for($iiiii=0; $iiiii < count($IdLancamentoFinanceiro); $iiiii++){
					
					if($TotalParcela == 0){
						$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=0 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$iiiii];
						$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
						$tr_i++;
							
						$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$IdLancamentoFinanceiro[$iiiii]." foi cancelado. Valor total do Contas a Receber = 0,00 (ETA3B)";
										
						$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
								LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
							  WHERE 
								IdLoja=$local_IdLoja AND 
								IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
						$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
						$tr_i++;
					}else{//<-
						if($TotalParcela < $ValorCobrancaMinima){//<-
							if($Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal] == $Datas[$IdLancamentoFinanceiro[$iiiii]][DataTerminoContrato] && $Datas[$IdLancamentoFinanceiro[$iiiii]][DataTerminoContrato] != ''){
								#Se o contrato estiver com data de término , gero o gero o último boleto
								$Cobrar = true;
							}else{
								# Se o contrato estiver normal cancelo o lancamento financeiro
								$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=2 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$iiiii];
								$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
								$tr_i++;//1
								
								$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$IdLancamentoFinanceiro[$iiiii]." foi cancelado. Valor mínimo não alcançado (ETA3C)";
											
								$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
										LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
									  WHERE 
										IdLoja=$local_IdLoja AND 
										IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
								$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
								$tr_i++;//2
							}
						}else{
							$Cobrar = true;
						}
					}
				}
				if($Cobrar == true){
				
					// Insiro o Conta Receber
					$sqlContaReceber = "INSERT INTO ContaReceber SET 
											IdLoja=$local_IdLoja,
											IdContaReceber=$IdContaReceber,
											IdPessoa = $Pessoa[$i],
											IdPessoaEndereco = $IdPessoaEnderecoCobranca[$ii],
											ValorLancamento='$TotalParcela',
											ValorDespesas='$ValorDespesaLocalCobrancaTemp',
											DataLancamento=curdate(),
											NumeroDocumento=NumeroDocumento($local_IdLoja, $IdLocalCobranca),
											IdLocalCobranca=$IdLocalCobranca,
											IdStatus=$StatusContaReceber,
											LoginCriacao='$local_Login',
											DataCriacao=concat(curdate(),' ',curtime());";
					$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
					$tr_i++;//3

					$TotalParcelaVencimento = $TotalParcela + $ValorDespesaLocalCobrancaTemp;

					// Insiro o Conta Receber Vencimento
					$sqlContaReceber = "INSERT INTO ContaReceberVencimento SET 
											IdLoja=$local_IdLoja,
											IdContaReceber=$IdContaReceber,
											DataVencimento='$dataVencimento', 
											ValorContaReceber='$TotalParcelaVencimento',
											ValorMulta='0',
											ValorJuros='0',
											ValorTaxaReImpressaoBoleto='0',
											ValorDesconto='0',
											ValorOutrasDespesas='0',
											LoginCriacao='$local_Login',
											DataCriacao=concat(curdate(),' ',curtime());";
					$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
					$tr_i++;//4
						
					$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $IdContaReceber, 1, $local_Login, $IdAgrupadorAux[$iiii]);
					$tr_i++;//5

					for($iiiii=0; $iiiii < count($IdLancamentoFinanceiro); $iiiii++){
						// Insiro os LancamentosFinanceirosContaReceber
						$sqlLancamentoFinanceiroContaReceber = "INSERT INTO LancamentoFinanceiroContaReceber SET 
																	IdLoja=$local_IdLoja, 
																	IdLancamentoFinanceiro=$IdLancamentoFinanceiro[$iiiii],
																	IdContaReceber=$IdContaReceber,
																	IdStatus=1;";
						$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiroContaReceber,$con);
						$tr_i++;
						
						// Altero os Status do lancamento financeiro
						$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET 
											IdStatus = 1 
										WHERE 
											IdLoja=$local_IdLoja AND 
											IdLancamentoFinanceiro=$IdLancamentoFinanceiro[$iiiii]";
						$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
						$tr_i++;//6
						
						if($Contrato[$IdLancamentoFinanceiro[$iiiii]] !='' && $Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal] != ''){
							
							$DataContratoTemp = explode("-", $Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal]);

							if(@checkdate($DataContratoTemp[1], $DataContratoTemp[2], $DataContratoTemp[0])){
								
								$sqlContrato = "select
													IdContrato
												from
													LancamentoFinanceiro
												where
													IdLoja = $local_IdLoja and
													IdLancamentoFinanceiro = $IdLancamentoFinanceiro[$iiiii]";
								$resContrato = mysql_query($sqlContrato,$con);
								$linContrato = mysql_fetch_array($resContrato);

								// Altero a data base						
								$sqlContrato = "UPDATE Contrato SET 
													DataBaseCalculo='".$Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal]."' 
												WHERE 
													IdLoja=$local_IdLoja AND 
													IdContrato=$linContrato[IdContrato] and
													(DataBaseCalculo < '".$Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal]."' or DataBaseCalculo is null)";
								$local_transaction[$tr_i]	=	mysql_query($sqlContrato,$con);
								$tr_i++;
							}
						}
						
						if($OrdemServico[$IdLancamentoFinanceiro[$iiiii]] !=''){
							// Adiciono uma data de faturamento					
							$sqlOrdemServico = "UPDATE OrdemServico SET 
												DataFaturamento=curdate()
											WHERE 
												IdLoja=$local_IdLoja AND 
												IdOrdemServico=".$OrdemServico[$IdLancamentoFinanceiro[$iiiii]];
							$local_transaction[$tr_i]	=	mysql_query($sqlOrdemServico,$con);
							$tr_i++;
						}
					}
				}
			}
		}
	}
}

$ContaReceber	= null;
$Datas			= null;
$Contrato		= null;
$DespesasBoleto	= null;
$Pessoa			= null;

// 3a ETAPA - FIM ------------------------------------------------------------
?>