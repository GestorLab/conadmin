<?
// 4a ETAPA ------------------------------------------------------------------
// Agrupar	= N
// Carne	= N

$ContaReceber	= null;
$Datas			= null;

$sqlLancamentoFinanceiro = "select
								Pessoa.IdPessoa,
								LancamentoFinanceiro.IdLancamentoFinanceiro,
								LancamentoFinanceiro.Valor,
								if(LancamentoFinanceiro.IdOrdemServico != '', concat(substring(OrdemServicoParcela.MesReferencia,4,4),'-',substring(OrdemServicoParcela.MesReferencia,1,2)), if(LancamentoFinanceiro.IdContaEventual != '', concat(substring(ContaEventualParcela.MesReferencia,4,4),'-',substring(ContaEventualParcela.MesReferencia,1,2)), if(LancamentoFinanceiro.IdContrato != '', substring(LancamentoFinanceiro.DataReferenciaFinal,1,7), ''))) MesReferencia,
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
								IF(Contrato.IdContaDebito IS NULL, IF(Contrato.IdCartao IS NULL, 0,Contrato.IdCartao),Contrato.IdContaDebito) IdAgrupadorAux,
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
								Contrato.IdPeriodicidade != 8 and
								Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca
							order by
								Pessoa.IdPessoa,
								LancamentoFinanceiro.IdContrato,
								MesReferencia,
								IdLancamentoFinanceiro";
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

	$IdLocalCobranca = $linLancamentoFinanceiro[IdLocalCobranca];

#	echo "Etapa 4 -> Agrupar = N -> Carne = N (Lançamento)<br>";

	if($linLancamentoFinanceiro[IdContratoAgrupador] == ''){
		$linLancamentoFinanceiro[IdContratoAgrupador] = $linLancamentoFinanceiro[IdContrato];
	}

	if($Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca] == ''){
		// Calcula a Data do Vencimento
		if($linLancamentoFinanceiro[DiaCobranca] < $linLancamentoFinanceiro[MenorVencimento]){
			$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca] = $linLancamentoFinanceiro[MenorVencimento];
		}else{
			$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca] = $linLancamentoFinanceiro[DiaCobranca];
		}

		if($local_DiaPrimeiroVencimento != ''){
			$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca] = $local_DiaPrimeiroVencimento;
		}

		if($Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca] < 10 && strlen($Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca]) == 1){
			$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca] = '0'.$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca];
		}

		$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][MesVencimento] = $linLancamentoFinanceiro[MesVencimento];

		$MesTemp = substr($Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][MesVencimento],0,2); // Mes
		$AnoTemp = substr($Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][MesVencimento],3,4); // Ano
		$DiaTemp = ultimoDiaMes($MesTemp, $AnoTemp);

		if($DiaTemp < $Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca]){
			$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca] = $DiaTemp;
		}

		$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaVencimento] = $Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaCobranca]."/".$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][MesVencimento];
		$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaVencimento] = dataConv($Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][DiaVencimento],'d/m/Y','Y-m-d');

		if($linLancamentoFinanceiro[DataReferenciaFinal] == $linLancamentoFinanceiro[DataUltimaCobranca] && $linLancamentoFinanceiro[DataUltimaCobranca] != ''){
			$Datas[$linLancamentoFinanceiro[IdContratoAgrupador]][ForcaCobranca] = true;
		}
	}

	$ContaReceber[$linLancamentoFinanceiro[IdContratoAgrupador]][IdAgrupadorAux] = $linLancamentoFinanceiro[IdAgrupadorAux];
	$ContaReceber[$linLancamentoFinanceiro[IdContratoAgrupador]][IdLocalCobranca] = $linLancamentoFinanceiro[IdLocalCobranca];
	$ContaReceber[$linLancamentoFinanceiro[IdContratoAgrupador]][ValorCobrancaMinima] = $linLancamentoFinanceiro[ValorCobrancaMinima];

	// Cobrar taxa do boleto S/N
	if($linLancamentoFinanceiro[Cob_CobrarDespesaBoleto] == 2){
		$ContaReceber[$linLancamentoFinanceiro[IdContratoAgrupador]][ValorDespesaLocalCobranca] = 0;
	}else{
		$ContaReceber[$linLancamentoFinanceiro[IdContratoAgrupador]][ValorDespesaLocalCobranca] = $linLancamentoFinanceiro[ValorDespesaLocalCobranca];
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
		$ContaReceber[$linLancamentoFinanceiro[IdContratoAgrupador]][IdLancamentoFinanceiro][$linLancamentoFinanceiro[IdLancamentoFinanceiro]][Valor] = $linLancamentoFinanceiro[Valor];
		$ContaReceber[$linLancamentoFinanceiro[IdContratoAgrupador]][ValorTotal] += $linLancamentoFinanceiro[Valor];
	}
}

if(count($ContaReceber) > 0){
	
	$IdContratoAgrupador = @array_keys($ContaReceber);

	for($i=0; $i< count($IdContratoAgrupador); $i++){

#		echo "Etapa 4 -> Agrupar = N -> Carne = N (Conta a Receber)<br>";

		$IdLancamentoFinanceiro = @array_keys($ContaReceber[$IdContratoAgrupador[$i]][IdLancamentoFinanceiro]);

		if($ContaReceber[$IdContratoAgrupador[$i]][ValorTotal] > $ContaReceber[$IdContratoAgrupador[$i]][ValorCobrancaMinima] || $Datas[$IdContratoAgrupador[$i]][ForcaCobranca] == true){

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

			$sqlEnderecoCobranca = "select
										IdPessoa,
										IdPessoaEnderecoCobranca
									from
										Contrato
									where
										IdLoja = $local_IdLoja and
										IdContrato = $IdContratoAgrupador[$i]";
			$resEnderecoCobranca = mysql_query($sqlEnderecoCobranca,$con);
			$linEnderecoCobranca = mysql_fetch_array($resEnderecoCobranca);
			
			// Inserir o conta a receber
			$sqlContaReceber = "INSERT INTO ContaReceber SET 
									IdLoja=$local_IdLoja,
									IdContaReceber=$IdContaReceber,
									IdPessoa = $linEnderecoCobranca[IdPessoa],
									IdPessoaEndereco = $linEnderecoCobranca[IdPessoaEnderecoCobranca],
									ValorLancamento='".$ContaReceber[$IdContratoAgrupador[$i]][ValorTotal]."',
									ValorDespesas='".$ContaReceber[$IdContratoAgrupador[$i]][ValorDespesaLocalCobranca]."',
									DataLancamento=curdate(),
									NumeroDocumento=NumeroDocumento($local_IdLoja, ".$ContaReceber[$IdContratoAgrupador[$i]][IdLocalCobranca]."),
									IdLocalCobranca=".$ContaReceber[$IdContratoAgrupador[$i]][IdLocalCobranca].",
									IdStatus=$StatusContaReceber,
									LoginCriacao='$local_Login',
									DataCriacao=concat(curdate(),' ',curtime());";
			$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
			$tr_i++;

			$TotalParcelaVencimento = $ContaReceber[$IdContratoAgrupador[$i]][ValorTotal] + $ContaReceber[$IdContratoAgrupador[$i]][ValorDespesaLocalCobranca];

			// Insiro o Conta Receber Vencimento
			$sqlContaReceber = "INSERT INTO ContaReceberVencimento SET 
									IdLoja=$local_IdLoja,
									IdContaReceber=$IdContaReceber,
									DataVencimento='".$Datas[$IdContratoAgrupador[$i]][DiaVencimento]."', 
									ValorContaReceber='$TotalParcelaVencimento',
									ValorMulta='0',
									ValorJuros='0',
									ValorTaxaReImpressaoBoleto='0',
									ValorDesconto='0',
									ValorOutrasDespesas='0',
									LoginCriacao='$local_Login',
									DataCriacao=concat(curdate(),' ',curtime());";
			$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber,$con);
			$tr_i++;
		
			$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $IdContaReceber, 1, $local_Login, $ContaReceber[$IdContratoAgrupador[$i]][IdAgrupadorAux]);
			$tr_i++;

			for($ii=0; $ii < count($IdLancamentoFinanceiro); $ii++){

#				echo "Etapa 4 -> Agrupar = N -> Carne = N (Lancamento - Conta a Receber)<br>";

				$sqlLancamentoFinanceiroContaReceber = "INSERT INTO LancamentoFinanceiroContaReceber SET 
						IdLoja=$local_IdLoja, 
						IdLancamentoFinanceiro=$IdLancamentoFinanceiro[$ii],
						IdContaReceber=$IdContaReceber,
						IdStatus=1;";
				$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiroContaReceber,$con);
				$tr_i++;			
				
				// Altero os Status do lancamento financeiro
				$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET 
									IdStatus = 1 
								WHERE 
									IdLoja=$local_IdLoja AND 
									IdLancamentoFinanceiro=$IdLancamentoFinanceiro[$ii]";
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
							IdLancamentoFinanceiro = $IdLancamentoFinanceiro[$ii]";
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
			}
		}else{
			for($ii=0; $ii < count($IdLancamentoFinanceiro); $ii++){
				$sqlLancamentoFinanceiro = "UPDATE LancamentoFinanceiro SET IdStatus=2 WHERE IdLoja=$local_IdLoja AND IdLancamentoFinanceiro=".$IdLancamentoFinanceiro[$ii];
				$local_transaction[$tr_i]	=	mysql_query($sqlLancamentoFinanceiro,$con);
				$tr_i++;
		
				$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº".$IdLancamentoFinanceiro[$ii]." foi cancelado. Valor mínimo não alcançado.";

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

$ContaReceber	= null;
$Datas			= null;

// 4a ETAPA - FIM ------------------------------------------------------------
?>