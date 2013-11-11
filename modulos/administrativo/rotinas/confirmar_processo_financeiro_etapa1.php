<?
// 1a ETAPA ------------------------------------------------------------------
// Agrupar	= S
// Carne	= S

$ContaReceber	= null;
$Datas			= null;
$Contrato		= null;
$DespesasBoleto	= null;
$IdCarne		= null;

if($debug == true){
	echo date("Y-m-d H:i:s")." >> Procesos Financeiro Etapa 1 - PT1\n";
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
								Contrato.IdContratoAgrupador,
								Contrato.IdContrato,
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
								LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
								LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and 
								LancamentoFinanceiro.IdProcessoFinanceiro = ProcessoFinanceiro.IdProcessoFinanceiro and 
								LancamentoFinanceiro.IdStatus = 3 and 
								Contrato.IdPessoa = Pessoa.IdPessoa and
								Pessoa.AgruparContratos != 2 and 
								Contrato.IdPeriodicidade = 8 and 
								Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
							order by 
								Pessoa.IdPessoa,								
								IdPessoaEnderecoCobranca,
								Contrato.DiaCobranca, 
								MesReferencia, 
								LancamentoFinanceiro.IdContrato";
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
	
/*	
	# Desativando devido a regra da antecipação.
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

		$linLancamentoFinanceiro[DiaCobranca]	= $linDiaCobranca[DiaCobranca];
		$linLancamentoFinanceiro[IdContrato]	= $linLancamentoFinanceiro[IdContratoAgrupador];
	}else{
		# Desativei esta rotina 
		# Creio que ela não esteja colaborando/exercendo a função corretamente.
		# 16/08/2012
		/*$sqlPaiFilho = "select
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
		$linPaiFilho = mysql_fetch_array($resPaiFilho);

		if($linPaiFilho[IdLancamentoFinanceiro] == $linLancamentoFinanceiro[IdLancamentoFinanceiro] && $linPaiFilho[PrimeiraReferenciaContratoFilho] != $linPaiFilho[PrimeiraReferenciaContratoPai] && $linPaiFilho[PrimeiraReferenciaContratoPai] != ''){
			$linLancamentoFinanceiro[MesReferencia] = $linPaiFilho[PrimeiraReferenciaContratoPai];
		}*/
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

#	echo "Etapa 1 -> Agrupar = S -> Carne = S (Lancamentos Financeiros)<br>";

	if($local_DiaPrimeiroVencimento != ''){
		$linLancamentoFinanceiro[DiaCobranca] = $local_DiaPrimeiroVencimento;
	}

	if($linLancamentoFinanceiro[DiaCobranca] < 10 && strlen($linLancamentoFinanceiro[DiaCobranca]) == 1){
		$linLancamentoFinanceiro[DiaCobranca] = '0'.$linLancamentoFinanceiro[DiaCobranca];
	}

	if($linLancamentoFinanceiro[Valor] == 0){
		$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=0 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$linLancamentoFinanceiro[IdLancamentoFinanceiro];
		$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
		if($debug == true && $local_transaction[$tr_i] == false){
			echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
		}
		$tr_i++;
		
		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$linLancamentoFinanceiro[IdLancamentoFinanceiro]." foi cancelado. Valor = 0,00";
					
		$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
				LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
			  WHERE 
				IdLoja=$local_IdLoja AND 
				IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
		$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
		if($debug == true && $local_transaction[$tr_i] == false){
			echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
		}
		$tr_i++;
	}else{
		$ContaReceber[$linLancamentoFinanceiro[IdPessoa]][$linLancamentoFinanceiro[IdPessoaEnderecoCobranca]][$linLancamentoFinanceiro[DiaCobranca]][$linLancamentoFinanceiro[MesReferencia]][$linLancamentoFinanceiro[IdLancamentoFinanceiro]] = $linLancamentoFinanceiro[Valor];
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
	$DataMenorVencimento		= $linLancamentoFinanceiro[MenorVencimento]."/".$linLancamentoFinanceiro[MesVencimento];

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

if(count($ContaReceber) > 0){

	$Pessoa = array_keys($ContaReceber);

	for($i=0; $i <count($Pessoa); $i++){

		$IdPessoaEnderecoCobranca	= array_keys($ContaReceber[$Pessoa[$i]]);
		
		for($ii=0; $ii <count($IdPessoaEnderecoCobranca); $ii++){

			$DiaCobranca	= array_keys($ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]]);

			for($iii=0; $iii <count($DiaCobranca); $iii++){

	#			echo "Etapa 1 -> Agrupar = S -> Carne = S (Carne)<br>";

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

				$sqlCarne = "insert into `Carne` ( `IdLoja`, `IdCarne` ) values ($local_IdLoja, $IdCarne)";
				$local_transaction[$tr_i]	=	mysql_query($sqlCarne,$con);				
				if($debug == true && $local_transaction[$tr_i] == false){
					echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
				}
				$tr_i++;
				
				// Cobrar taxa do boleto S/N
				if($DespesasBoleto[$Pessoa[$i]] == 2){
					$ValorDespesaLocalCobrancaTemp = 0;
				}else{
					$ValorDespesaLocalCobrancaTemp = $ValorDespesaLocalCobranca;
				}

				$MesReferencia		= array_keys($ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]][$DiaCobranca[$iii]]);
				$MesVencimentoTemp	= $MesVencimento;

				$MesTemp = substr($MesVencimentoTemp,0,2); // Mes
				$AnoTemp = substr($MesVencimentoTemp,3,4); // Ano
				$DiaTemp = ultimoDiaMes($MesTemp, $AnoTemp);

				if($DiaTemp < $DiaCobranca[$iii]){
					$DiaVencimento = $DiaTemp;
				}else{
					$DiaVencimento = $DiaCobranca[$iii];
				}

				for($iiii=0; $iiii < count($MesReferencia); $iiii++){
					
	#				echo "Etapa 1 -> Agrupar = S -> Carne = S (Conta a Receber)<br>";
		
					$DataVencimento = dataConv($MesVencimentoTemp,'m/Y','Y-m')."-".$DiaVencimento;

					$sqlContaReceber = "select max(IdContaReceber) IdContaReceber from ContaReceber where IdLoja = $local_IdLoja";
					$resContaReceber = mysql_query($sqlContaReceber,$con);
					$linContaReceber = mysql_fetch_array($resContaReceber);
					
					$IdContaReceber = $linContaReceber[IdContaReceber];
					if($IdContaReceber == null){
						$IdContaReceber = 1;
					}else{
						$IdContaReceber++;
					}
					
					$TotalParcela = array_sum($ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]][$DiaCobranca[$iii]][$MesReferencia[$iiii]]);

					if($TotalParcela > 0){
												
						$sqlContaReceber = "insert into ContaReceber set 
												IdLoja=$local_IdLoja, 
												IdContaReceber=$IdContaReceber,
												IdPessoa = $Pessoa[$i],
												IdPessoaEndereco = $IdPessoaEnderecoCobranca[$ii],
												ValorLancamento='$TotalParcela',
												ValorDespesas='$ValorDespesaLocalCobrancaTemp',
												DataLancamento=curdate(), 
												NumeroDocumento=NumeroDocumento($local_IdLoja, $IdLocalCobranca), 
												IdLocalCobranca=$IdLocalCobranca, 
												IdCarne=$IdCarne, 
												IdStatus=$StatusContaReceber, 
												LoginCriacao='$local_Login', 
												DataCriacao=concat(curdate(),' ',curtime());";
						$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);						
						if($debug == true && $local_transaction[$tr_i] == false){
							echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
						}
						$tr_i++;								
						
						if(dataConv($DataVencimento,"Y-m-d","Ymd") < dataConv($DataMenorVencimento,"d/m/Y","Ymd")){
							$DataVencimento = dataConv($DataMenorVencimento,"d/m/Y","Y-m-d");
						}

						// Insiro o Conta Receber Vencimento
						$sqlContaReceber = "INSERT INTO ContaReceberVencimento SET 
												IdLoja=$local_IdLoja,
												IdContaReceber=$IdContaReceber,
												DataVencimento='$DataVencimento', 
												ValorContaReceber='$TotalParcela',
												ValorMulta='0',
												ValorJuros='0',
												ValorTaxaReImpressaoBoleto='0',
												ValorDesconto='0',
												ValorOutrasDespesas='0',
												LoginCriacao='$local_Login',
												DataCriacao=concat(curdate(),' ',curtime());";
						$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
						if($debug == true && $local_transaction[$tr_i] == false){
							echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
						}
						$tr_i++;
								
						$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $IdContaReceber, 1, $local_Login);						
						if($debug == true && $local_transaction[$tr_i] == false){
							echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
						}
						$tr_i++;
						
						if($debug == true){
							echo date("Y-m-d H:i:s")." >> PE: $Pessoa[$i] CR: $IdContaReceber VENC: $DataVencimento PosCob: ";
							if($local_transaction[$tr_i-1] == false){
								echo "ERRO";
							}else{
								echo "OK";
							}
							echo "\n";
						}
					}

					$IdLancamentoFinanceiro = array_keys($ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]][$DiaCobranca[$iii]][$MesReferencia[$iiii]]);

					for($iiiii=0; $iiiii < count($IdLancamentoFinanceiro); $iiiii++){

						if($Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal] == $Datas[$IdLancamentoFinanceiro[$iiiii]][DataTerminoContrato] && $Datas[$IdLancamentoFinanceiro[$iiiii]][DataTerminoContrato] != ''){

							$sqlForca = "select
										count(*) Qtd
									from
										Contrato,
										LancamentoFinanceiro
									where
										Contrato.IdLoja = $local_IdLoja and
										Contrato.IdLoja = LancamentoFinanceiro.IdLoja and
										(Contrato.IdContrato = ".$Contrato[$IdLancamentoFinanceiro[$iiiii]]." or Contrato.IdContratoAgrupador = ".$Contrato[$IdLancamentoFinanceiro[$iiiii]].") and
										Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
										LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro";
							$resForca = mysql_query($sqlForca,$con);
							$linForca = mysql_fetch_array($resForca);

							if($linForca[Qtd] <= 1){
								$ForcaCobranca = true;
							}else{
								$ForcaCobranca = false;
							}
						}else{
							$ForcaCobranca = false;
						}

						if($TotalParcela == 0){
							$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=0 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$iiiii];
							$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);							
							if($debug == true && $local_transaction[$tr_i] == false){
								echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
							}
							$tr_i++;
							
							$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$IdLancamentoFinanceiro[$iiiii]." foi cancelado. Valor = 0,00";
										
							$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
									LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
								  WHERE 
									IdLoja=$local_IdLoja AND 
									IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
							$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);							
							if($debug == true && $local_transaction[$tr_i] == false){
								echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
							}
							$tr_i++;
						}else{
							if($TotalParcela > $ValorCobrancaMinima || $ForcaCobranca == true){
								// Insiro os LancamentosFinanceirosContaReceber
								$sqlLancamentoFinanceiroContaReceber = "INSERT INTO LancamentoFinanceiroContaReceber SET 
																			IdLoja=$local_IdLoja, 
																			IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$iiiii].",
																			IdContaReceber=$IdContaReceber,
																			IdStatus=1;";
								$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiroContaReceber,$con);								
								if($debug == true && $local_transaction[$tr_i] == false){
									echo date("Y-m-d H:i:s")." >> ($sqlLancamentoFinanceiroContaReceber)".mysql_error()."\n";
								}
								$tr_i++;

								// Altero os Status do lancamento financeiro
								$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET 
																IdStatus = 1 
															WHERE 
																IdLoja=$local_IdLoja AND 
																IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$iiiii];
								$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
								if($debug == true && $local_transaction[$tr_i] == false){
									echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
								}
								$tr_i++;

								// Altero a data base
								$DataContratoTemp = explode("-", $Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal]);

								if(@checkdate($DataContratoTemp[1], $DataContratoTemp[2], $DataContratoTemp[0]) && $Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal] != ''){
									
									$sqlContrato = "select
														IdContrato
													from
														LancamentoFinanceiro
													where
														IdLoja = $local_IdLoja and
														IdLancamentoFinanceiro = $IdLancamentoFinanceiro[$iiiii]";
									$resContrato = mysql_query($sqlContrato,$con);
									$linContrato = mysql_fetch_array($resContrato);

									$sqlContrato = "UPDATE Contrato SET 
														DataBaseCalculo='".$Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal]."' 
													WHERE 
														IdLoja=$local_IdLoja AND 
														IdContrato=$linContrato[IdContrato] and
														(DataBaseCalculo < '".$Datas[$IdLancamentoFinanceiro[$iiiii]][DataReferenciaFinal]."' or DataBaseCalculo is null)";
									$local_transaction[$tr_i]	=	mysql_query($sqlContrato,$con);									
									if($debug == true && $local_transaction[$tr_i] == false){
										echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
									}
									$tr_i++;
								}
							}else{								
								// Excluir o Conta a Receber
								$sqlContaReceber = "delete from LancamentoFinanceiroContaReceber where IdLoja='$local_IdLoja' and IdContaReceber='$IdContaReceber'";
								$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
								if($debug == true && $local_transaction[$tr_i] == false){
									echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
								}
								$tr_i++;

								$sqlContaReceber = "delete from ContaReceberVencimento where IdLoja='$local_IdLoja' and IdContaReceber='$IdContaReceber'";
								$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);								
								if($debug == true && $local_transaction[$tr_i] == false){
									echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
								}
								$tr_i++;

								$sqlContaReceber = "delete from ContaReceber where IdLoja='$local_IdLoja' and IdContaReceber='$IdContaReceber'";
								$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);								
								if($debug == true && $local_transaction[$tr_i] == false){
									echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
								}
								$tr_i++;

								if(($iiii+1) < count($MesReferencia)){
									$ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]][$DiaCobranca[$iii]][$MesReferencia[$iiii+1]][$IdLancamentoFinanceiro[$iiiii]] = $ContaReceber[$Pessoa[$i]][$IdPessoaEnderecoCobranca[$ii]][$DiaCobranca[$iii]][$MesReferencia[$iiii]][$IdLancamentoFinanceiro[$iiiii]];

									# Ele compara com o negativo pois irá incrementar o próximo vencimento.
									if(dataConv($MesVencimentoTemp,"m/Y","Ym") > dataConv(incrementaMesReferencia($MesVencimento,-1),"m/Y","Ym")){
										$MesVencimentoTemp = incrementaMesReferencia($MesVencimentoTemp,-1);
									}
								}else{
									$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=2 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$iiiii];
									$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
									if($debug == true && $local_transaction[$tr_i] == false){
										echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
									}
									$tr_i++;
							
									$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$IdLancamentoFinanceiro[$iiiii]." foi cancelado. Valor mínimo não alcançado.";
		
									$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
																LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
															  WHERE 
																IdLoja=$local_IdLoja AND 
																IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
									$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);									
									if($debug == true && $local_transaction[$tr_i] == false){
										echo date("Y-m-d H:i:s")." >> ".mysql_error()."\n";
									}
									$tr_i++;
								}
							}
						}
					}
					$MesVencimentoTemp = incrementaMesReferencia($MesVencimentoTemp, 1);
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

$ContaReceber	= null;
$Datas			= null;
$Contrato		= null;
$DespesasBoleto	= null;
$IdCarne		= null;

// 1a ETAPA - FIM ------------------------------------------------------------
?>