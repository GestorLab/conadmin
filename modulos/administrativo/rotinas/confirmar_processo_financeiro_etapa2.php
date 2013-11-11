<?
// 2a ETAPA ------------------------------------------------------------------
// Agrupar	= N
// Carne	= S

$Carne						= null;
$Datas						= null;
$ValorDespesaLocalCobranca	= null;

if($debug == true){
	echo date("Y-m-d H:i:s")." >> Etapa 2 - PT1\n";
	sleep(1);
}

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
								Contrato.DiaCobranca,
								Pessoa.Cob_CobrarDespesaBoleto,
								Contrato.DataUltimaCobranca,
								Contrato.IdContrato,
								Contrato.IdContratoAgrupador,
								ProcessoFinanceiro.MesReferencia MesReferenciaProcessoFinanceiro
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
								Pessoa.AgruparContratos = 2 and
								Contrato.IdPeriodicidade = 8 and
								Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca
							order by
								LancamentoFinanceiro.IdContrato,
								IdPessoaEnderecoCobranca,
								MesReferencia";
$resLancamentoFinanceiro = mysql_query($sqlLancamentoFinanceiro,$con);
while($linLancamentoFinanceiro = mysql_fetch_array($resLancamentoFinanceiro)){

	if($debug == true){
		echo date("Y-m-d H:i:s")." PE: ".$linLancamentoFinanceiro[IdPessoa]." >> CO: ".$linLancamentoFinanceiro[IdContrato];
	}

	$linLancamentoFinanceiro[MesReferenciaProcessoFinanceiro] = dataConv($linLancamentoFinanceiro[MesReferenciaProcessoFinanceiro],"m/Y","Y-m");

	// Tudo que for gerado antes, vai agrupado na primeira parcela
	if(str_replace("-","",$linLancamentoFinanceiro[MesReferencia]) < str_replace("-","",$linLancamentoFinanceiro[MesReferenciaProcessoFinanceiro])){
		$linLancamentoFinanceiro[MesReferencia] = $linLancamentoFinanceiro[MesReferenciaProcessoFinanceiro];
	}
	
	if($linLancamentoFinanceiro[MesReferencia] == ''){
		$sql = "SELECT
					SUBSTRING(DataReferenciaInicial,1,7) MesReferencia
				FROM
					LancamentoFinanceiro
				WHERE
					IdLoja = $local_IdLoja and
					IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
					IdContrato = $linLancamentoFinanceiro[IdContrato]
				ORDER BY
					IdLancamentoFinanceiro
				LIMIT 0,1";
		$res = @mysql_query($sql,$con);
		if($lin = @mysql_fetch_array($res)){
			$linLancamentoFinanceiro[MesReferencia] = $lin[MesReferencia];
		}
	}

/*	# Destivando devido a regra da antecipação
	$sql = "select
				substring(DataReferenciaInicial,1,7) MesReferencia
			from
				LancamentoFinanceiro
			where
				IdLoja = $local_IdLoja and
				IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
				IdContrato = $linLancamentoFinanceiro[IdContrato] and
				IdContaEventual is null and
				IdOrdemServico is null
			order by
				DataReferenciaFinal
			limit 0,1";
	$res = @mysql_query($sql,$con);
	if($lin = @mysql_fetch_array($res)){
		if(str_replace("-","",$linLancamentoFinanceiro[MesReferencia]) < str_replace("-","",$lin[MesReferencia])){
			$linLancamentoFinanceiro[MesReferencia] = $lin[MesReferencia];
		}
	}*/

	$DataMenorVencimento = $linLancamentoFinanceiro[MenorVencimento]."/".$linLancamentoFinanceiro[MesVencimento];

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
	}else{
		$sqlPaiFilho = "select
							PrimeiraReferenciaFilho.PrimeiraReferenciaContratoFilho,
							PrimeiraReferenciaFilho.IdLancamentoFinanceiro,
							PrimeiraReferenciaPai.PrimeiraReferenciaContratoPai
						from
							(select
								IdLancamentoFinanceiro,
								substring(LancamentoFinanceiro.DataReferenciaInicial,1,7) PrimeiraReferenciaContratoFilho
							from
								LancamentoFinanceiro
							where
								LancamentoFinanceiro.IdLoja = $local_IdLoja and
								LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
								LancamentoFinanceiro.IdContrato = $linLancamentoFinanceiro[IdContrato]
							limit 0,1) PrimeiraReferenciaFilho,
							(select
								substring(LancamentoFinanceiro.DataReferenciaInicial,1,7) PrimeiraReferenciaContratoPai
							from
								Contrato,
								LancamentoFinanceiro
							where
								Contrato.IdLoja = $local_IdLoja and
								Contrato.IdLoja = LancamentoFinanceiro.IdLoja and
								Contrato.IdContratoAgrupador = $linLancamentoFinanceiro[IdContrato] and
								Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
								LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro
							order by
								LancamentoFinanceiro.IdLancamentoFinanceiro
							limit 0,1) PrimeiraReferenciaPai";
		$resPaiFilho = mysql_query($sqlPaiFilho,$con);
		echo mysql_error();
		$linPaiFilho = mysql_fetch_array($resPaiFilho);

		if($linPaiFilho[IdLancamentoFinanceiro] == $linLancamentoFinanceiro[IdLancamentoFinanceiro] && $linPaiFilho[PrimeiraReferenciaContratoFilho] != $linPaiFilho[PrimeiraReferenciaContratoPai]){
			$linLancamentoFinanceiro[MesReferencia] = $linPaiFilho[PrimeiraReferenciaContratoPai];
		}
	}

	if($linLancamentoFinanceiro[DiaCobranca] != substr($linLancamentoFinanceiro[DataReferenciaInicial],8,2) && str_replace("-","",$linLancamentoFinanceiro[MesReferencia]) < str_replace("-","",$lin[MesReferencia])){
		$linLancamentoFinanceiro[MesReferencia] = dataConv(incrementaMesReferencia(dataConv($linLancamentoFinanceiro[MesReferencia],"Y-m","m-Y"),-1),"m/Y","Y-m");
	}

#	echo "Etapa 2 -> Agrupar = N -> Carne = S (Lancamentos Financeiros)<br>";

	if($linLancamentoFinanceiro[IdContratoAgrupador] == ''){
		$linLancamentoFinanceiro[IdContratoAgrupador] = $linLancamentoFinanceiro[IdContrato];
	}

	$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca] = $linLancamentoFinanceiro[DiaCobranca];

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

	if($linLancamentoFinanceiro[DataReferenciaFinal] == $linLancamentoFinanceiro[DataUltimaCobranca] && $linLancamentoFinanceiro[DataUltimaCobranca] != ''){
		$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][ForcaCobranca] = true;
	}else{
		$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][ForcaCobranca] = false;
	}

	$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][MesVencimento] = $linLancamentoFinanceiro[MesVencimento];

	$IdLocalCobranca		= $linLancamentoFinanceiro[IdLocalCobranca];
	$ValorCobrancaMinima	= $linLancamentoFinanceiro[ValorCobrancaMinima];

	// Cobrar taxa do boleto S/N
	if($linLancamentoFinanceiro[Cob_CobrarDespesaBoleto] == 2){
		$ValorDespesaLocalCobranca[$linLancamentoFinanceiro[IdContratoAgrupador]] = 0;
	}else{
		$ValorDespesaLocalCobranca[$linLancamentoFinanceiro[IdContratoAgrupador]] = $linLancamentoFinanceiro[ValorDespesaLocalCobranca];
	}

	if($linLancamentoFinanceiro[Valor] == 0){
		$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=0 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$linLancamentoFinanceiro[IdLancamentoFinanceiro];
		$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
		$tr_i++;
		
		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$linLancamentoFinanceiro[IdLancamentoFinanceiro]." foi cancelado. Valor = 0,00";
					
		$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
									LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
								  WHERE 
									IdLoja=$local_IdLoja AND 
									IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
		$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
		$tr_i++;
	}else{
		$Carne[$linLancamentoFinanceiro[IdContratoAgrupador]][$linLancamentoFinanceiro[IdPessoaEnderecoCobranca]][$linLancamentoFinanceiro[MesReferencia]][IdLancamentoFinanceiro][$linLancamentoFinanceiro[IdLancamentoFinanceiro]][Valor] = $linLancamentoFinanceiro[Valor];
		$Carne[$linLancamentoFinanceiro[IdContratoAgrupador]][$linLancamentoFinanceiro[IdPessoaEnderecoCobranca]][$linLancamentoFinanceiro[MesReferencia]][ValorTotal] += $linLancamentoFinanceiro[Valor];
	}

	if($debug == true){
		for($iDebug=0; $iDebug<$tr_i; $iDebug++){
			if($local_transaction[$iDebug] == false){
				echo "\nERRO\n";
				break;
				break;
			}
		}
		echo "\n";
	}
}

if($debug == true){
	echo date("Y-m-d H:i:s")." >> PT2\n";
}

if(count($Carne) > 0){
	
	$IdContratoAgrupador = @array_keys($Carne);

	for($i=0; $i< count($IdContratoAgrupador); $i++){

		$IdPessoaEnderecoCobranca = @array_keys($Carne[$IdContratoAgrupador[$i]]);

		for($ii=0; $ii< count($IdPessoaEnderecoCobranca); $ii++){

	#		echo "Etapa 2 -> Agrupar = N -> Carne = S (Carne)<br>";
			
			// Cria um carne
			$sqlCarne = "select max(IdCarne) IdCarne from Carne where IdLoja = $local_IdLoja";
			$resCarne = @mysql_query($sqlCarne,$con);
			$linCarne = @mysql_fetch_array($resCarne);
			
			$IdCarne = $linCarne[IdCarne];
			if($IdCarne == null){
				$IdCarne = 1;
			}else{
				$IdCarne++;
			}

			$sqlCarne = "insert into Carne (IdLoja, IdCarne) values ($local_IdLoja, $IdCarne)";
			$local_transaction[$tr_i]	=	mysql_query($sqlCarne,$con);
			$tr_i++;

			if(count($Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]]) > 0){

				$MesReferencia = @array_keys($Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]]);

				for($iii=0; $iii< count($MesReferencia); $iii++){
	#				echo "Etapa 2 -> Agrupar = N -> Carne = S (Conta a Receber)<br>";

					$DiaCobrancaTemp = $Datas[$IdContratoAgrupador[$i]][DiaCobranca];

					if($local_DiaPrimeiroVencimento != ''){
						$DiaCobrancaTemp = $local_DiaPrimeiroVencimento;
					}

					if($DiaCobrancaTemp < 10 && strlen($DiaCobrancaTemp) == 1){
						$DiaCobrancaTemp = '0'.$DiaCobrancaTemp;
					}

					$MesTemp = substr($Datas[$IdContratoAgrupador[$i]][MesVencimento],0,2); // Mes
					$AnoTemp = substr($Datas[$IdContratoAgrupador[$i]][MesVencimento],3,4); // Ano
					$DiaTemp = ultimoDiaMes($MesTemp, $AnoTemp);

					$DiaVencimento = $DiaCobrancaTemp."/".$Datas[$IdContratoAgrupador[$i]][MesVencimento];
					$DiaVencimento = dataConv($DiaVencimento,'d/m/Y','Y-m-d');

					$Datas[$IdContratoAgrupador[$i]][MesVencimento] = incrementaMesReferencia($Datas[$IdContratoAgrupador[$i]][MesVencimento], 1);

					// Calcula o Id do Conta A Receber
					$sqlContaReceber = "select max(IdContaReceber) IdContaReceber from ContaReceber where IdLoja = $local_IdLoja";
					$resContaReceber = mysql_query($sqlContaReceber,$con);
					$linContaReceber = mysql_fetch_array($resContaReceber);

					$IdContaReceber = $linContaReceber[IdContaReceber];
					if($IdContaReceber == null){
						$IdContaReceber = 1;
					}else{
						$IdContaReceber++;
					}

					// Cobrar taxa do boleto S/N
					if($linLancamentoFinanceiro[Cob_CobrarDespesaBoleto] == 2){
						$linLancamentoFinanceiro[ValorDespesaLocalCobranca] = 0;
					}

					$TotalParcela = $Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii]][ValorTotal];

					$sqlPessoa = "select
									IdPessoa
								from
									Contrato
								where
									IdLoja = $local_IdLoja and
									IdContrato = $IdContratoAgrupador[$i]";
					$resPessoa = mysql_query($sqlPessoa);
					$linPessoa = mysql_fetch_array($resPessoa);

					if($TotalParcela > 0){
						
						// Inserir o conta a receber
						$sqlContaReceber = "INSERT INTO ContaReceber SET 
									IdLoja=$local_IdLoja,
									IdContaReceber=$IdContaReceber,
									IdPessoa = $linPessoa[IdPessoa],
									IdPessoaEndereco = $IdPessoaEnderecoCobranca[$ii],
									ValorLancamento='$TotalParcela',
									ValorDespesas='".$ValorDespesaLocalCobranca[$IdContratoAgrupador[$i]]."',
									DataLancamento=curdate(),
									NumeroDocumento=NumeroDocumento($local_IdLoja, $IdLocalCobranca),
									IdLocalCobranca=$IdLocalCobranca,
									IdCarne=$IdCarne,
									IdStatus=$StatusContaReceber,
									LoginCriacao='$local_Login',
									DataCriacao=concat(curdate(),' ',curtime());";
						$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
						$tr_i++;					

						$TotalParcelaVencimento = $TotalParcela + $ValorDespesaLocalCobranca[$IdContratoAgrupador[$i]];													
						
						if(dataConv($DiaVencimento,"Y-m-d","Ymd") < dataConv($DataMenorVencimento,"d/m/Y","Ymd")){
							$DiaVencimento = dataConv($DataMenorVencimento,"d/m/Y","Y-m-d");
						}

						// Insiro o Conta Receber Vencimento
						$sqlContaReceber = "INSERT INTO ContaReceberVencimento SET 
												IdLoja=$local_IdLoja,
												IdContaReceber=$IdContaReceber,
												DataVencimento='$DiaVencimento', 
												ValorContaReceber='$TotalParcelaVencimento',
												ValorMulta='0',
												ValorJuros='0',
												ValorTaxaReImpressaoBoleto='0',
												ValorDesconto='0',
												LoginCriacao='$local_Login',
												DataCriacao=concat(curdate(),' ',curtime());";
						$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
						$tr_i++;
								
						$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $IdContaReceber, 1, $local_Login);
						$tr_i++;

						if($debug == true){
							echo date("Y-m-d H:i:s")." >> PE: $linPessoa[IdPessoa] CR: $IdContaReceber VENC: $DataVencimento PosCob: ";
							if($local_transaction[$tr_i-1] == false){
								echo "ERRO";
							}else{
								echo "OK";
							}
							echo "\n";
						}
					}

					if(count($Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii]][IdLancamentoFinanceiro]) > 0){

						if($TotalParcela < $ValorCobrancaMinima && $Datas[$IdContratoAgrupador[$i]][ForcaCobranca] == false){
							// Excluir o Conta a Receber							
							$sqlContaReceber = "delete from ContaReceberVencimento where IdLoja='$local_IdLoja' and IdContaReceber='$IdContaReceber'";
							$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
							$tr_i++;

							$sqlContaReceber = "delete from ContaReceber where IdLoja='$local_IdLoja' and IdContaReceber='$IdContaReceber'";
							$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
							$tr_i++;
						}

						$IdLancamentoFinanceiro = @array_keys($Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii]][IdLancamentoFinanceiro]);

						for($iiii=0; $iiii < count($IdLancamentoFinanceiro); $iiii++){

							if($TotalParcela == 0){
								$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=0 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$iiii];
								$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
								$tr_i++;
									
								$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$IdLancamentoFinanceiro[$iiii]." foi cancelado. Valor = 0,00";
												
								$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
										LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
									  WHERE 
										IdLoja=$local_IdLoja AND 
										IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
								$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
								$tr_i++;
							}else{
								if($TotalParcela > $ValorCobrancaMinima || $Datas[$IdContratoAgrupador[$i]][ForcaCobranca] == true){
									// Insiro os LancamentosFinanceirosContaReceber
									$sqlLancamentoFinanceiroContaReceber = "INSERT INTO LancamentoFinanceiroContaReceber SET 
																				IdLoja=$local_IdLoja, 
																				IdLancamentoFinanceiro=$IdLancamentoFinanceiro[$iiii],
																				IdContaReceber=$IdContaReceber,
																				IdStatus=1;";
									$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiroContaReceber,$con);
									$tr_i++;

									// Altero os Status do lancamento financeiro
									$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET 
														IdStatus = 1 
													WHERE 
														IdLoja=$local_IdLoja AND 
														IdLancamentoFinanceiro=$IdLancamentoFinanceiro[$iiii]";
									$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
									$tr_i++;

									// Altero a data base						
									$sqlContrato = "select
												IdContrato,
												DataReferenciaFinal
											from
												LancamentoFinanceiro
											where
												IdLoja = $local_IdLoja and
												IdLancamentoFinanceiro = $IdLancamentoFinanceiro[$iiii]";
									$resContrato = mysql_query($sqlContrato,$con);
									$linContrato = mysql_fetch_array($resContrato);

									$DataContratoTemp = explode("-", $linContrato[DataReferenciaFinal]);

									if(@checkdate($DataContratoTemp[1], $DataContratoTemp[2], $DataContratoTemp[0]) && $linContrato[DataReferenciaFinal] != ''){
										$sqlContrato = "UPDATE Contrato SET 
													DataBaseCalculo='$linContrato[DataReferenciaFinal]' 
												where
													IdLoja=$local_IdLoja AND 
													IdContrato=$linContrato[IdContrato] and
													(DataBaseCalculo < '$linContrato[DataReferenciaFinal]' or DataBaseCalculo is null)";
										$local_transaction[$tr_i]	=	mysql_query($sqlContrato,$con);
										$tr_i++;
									}

								}else{
									if($Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii]][IdLancamentoFinanceiro][$IdLancamentoFinanceiro[$iiii]][Valor] == 0){									
										$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=0 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$iiii];
										$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
										$tr_i++;

										$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$IdLancamentoFinanceiro[$iiii]." foi cancelado. Valor = 0,00";
										$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
												LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
											  WHERE 
												IdLoja=$local_IdLoja AND 
												IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
										$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
										$tr_i++;
									}else{
										if(($iii+1) < count($MesReferencia)){
											$Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii+1]][IdLancamentoFinanceiro][$IdLancamentoFinanceiro[$iiii]][Valor] = $Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii]][IdLancamentoFinanceiro][$IdLancamentoFinanceiro[$iiii]][Valor];
											$Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii]][ValorTotal] -= $Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii]][IdLancamentoFinanceiro][$IdLancamentoFinanceiro[$iiii]][Valor];
											$Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii+1]][ValorTotal] += $Carne[$IdContratoAgrupador[$i]][$IdPessoaEnderecoCobranca[$ii]][$MesReferencia[$iii+1]][IdLancamentoFinanceiro][$IdLancamentoFinanceiro[$iiii]][Valor];
										}else{										
											$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=0 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$iiii];
											$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
											$tr_i++;

											$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$IdLancamentoFinanceiro[$iiii]." foi cancelado. Valor mínimo não alcançado.";
											$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
													LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
												  WHERE 
													IdLoja=$local_IdLoja AND 
													IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
											$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
											$tr_i++;
										}
									}
								}
							}
						}
					}
				}
			}
		}
		if($debug == true){
			for($iDebug=0; $iDebug<$tr_i; $iDebug++){
				if($local_transaction[$iDebug] == false){
					echo "\nERRO\n";
					break;
					break;
				}
			}
			echo "\n";
		}
	}
}

$Carne						= null;
$Datas						= null;
$ValorDespesaLocalCobranca	= null;

// 2a ETAPA - FIM ------------------------------------------------------------
?>