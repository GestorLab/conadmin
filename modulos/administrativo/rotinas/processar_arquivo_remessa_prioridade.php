<?
	# Removendo os duplicados...
	# Revisado em 11/05/2012 Douglas + Weiner
 	$sql = "SELECT
				ContaReceberPosicaoCobranca.IdContaReceber,
				ContaReceberPosicaoCobranca.IdMovimentacao,
				ContaReceberPosicaoCobranca.IdPosicaoCobranca
			FROM
				(SELECT
					ContaReceberPosicaoCobranca.IdLoja,
					ContaReceberPosicaoCobranca.IdContaReceber,
					COUNT(*) Qtd,
					ContaReceberPosicaoCobranca.IdLocalCobrancaRemessa
				FROM
					ContaReceberPosicaoCobranca,
					LocalCobranca,
					ContaReceber
				WHERE
					(
						(
							LocalCobranca.IdLoja = $local_IdLoja and
							LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
						) or (
							LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and
							LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
						)
					) and
					ContaReceberPosicaoCobranca.IdLoja = ContaReceber.IdLoja AND
					ContaReceberPosicaoCobranca.IdContaReceber = ContaReceber.IdContaReceber AND
					ContaReceber.IdLoja = LocalCobranca.IdLoja AND
					ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND
					ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00'
				GROUP BY
					ContaReceberPosicaoCobranca.IdLoja,
					ContaReceberPosicaoCobranca.IdContaReceber) Qtd,
				ContaReceberPosicaoCobranca
			WHERE
				Qtd.Qtd > 1 AND
				ContaReceberPosicaoCobranca.IdLoja = Qtd.IdLoja AND
				ContaReceberPosicaoCobranca.IdContaReceber = Qtd.IdContaReceber AND
				ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00'
			ORDER BY
				ContaReceberPosicaoCobranca.IdContaReceber,
				ContaReceberPosicaoCobranca.IdPosicaoCobranca";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		if(!$PosicaoCobranca[$lin[IdContaReceber]][$lin[IdPosicaoCobranca]]){
			$PosicaoCobranca[$lin[IdContaReceber]][$lin[IdPosicaoCobranca]] = true;
		}else{
			$sql = "delete from ContaReceberPosicaoCobranca where
						IdLoja = $local_IdLoja and
						IdContaReceber = $lin[IdContaReceber] and
						IdMovimentacao = $lin[IdMovimentacao] and
						DataRemessa = '0000-00-00'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
	}

	$PosicaoCobranca = null;

	# Discartando os envios pѓs-envio
	# Revisado em 11/05/2012 Douglas + Weiner
	$sql =  "SELECT
				ContaReceberPosicaoCobranca.IdLoja,
				ContaReceberPosicaoCobranca.IdContaReceber,
				min(ContaReceberPosicaoCobranca.IdMovimentacao) IdMovimentacao,
				LocalCobranca.IdTipoLocalCobranca,
				COUNT(*) Qtd
			FROM
				ContaReceberPosicaoCobranca,
				LocalCobranca,
				ContaReceber
			WHERE
				(
					(
						LocalCobranca.IdLoja = $local_IdLoja and
						LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
					) or (
						LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and
						LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
					)
				) and
				ContaReceberPosicaoCobranca.IdLoja = ContaReceber.IdLoja AND
				ContaReceberPosicaoCobranca.IdContaReceber = ContaReceber.IdContaReceber AND
				ContaReceber.IdLoja = LocalCobranca.IdLoja AND
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND
				ContaReceberPosicaoCobranca.IdPosicaoCobranca = 1 and
				LocalCobranca.IdTipoLocalCobranca != 3
			GROUP BY
				ContaReceberPosicaoCobranca.IdLoja,
				ContaReceberPosicaoCobranca.IdContaReceber
			ORDER BY
				ContaReceberPosicaoCobranca.IdLoja,
				ContaReceberPosicaoCobranca.IdContaReceber,
				ContaReceberPosicaoCobranca.IdPosicaoCobranca";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		if($lin[Qtd] > 1){
			$sql = "delete from ContaReceberPosicaoCobranca where
						IdLoja = $lin[IdLoja] and
						IdContaReceber = $lin[IdContaReceber] and
						DataRemessa = '0000-00-00' and
						IdPosicaoCobranca = 1 and
						IdMovimentacao > $lin[IdMovimentacao]";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
	}

	$PosicaoCobranca = null;

	# Discartando as alteraчѕes antes do envio...
	# Revisado em 11/05/2012 Douglas + Weiner
	$sql =  "SELECT
				ContaReceberPosicaoCobranca.IdLoja,
				ContaReceberPosicaoCobranca.IdContaReceber,
				ContaReceberPosicaoCobranca.IdMovimentacao,
				ContaReceberPosicaoCobranca.IdPosicaoCobranca,
				COUNT(*) Qtd
			FROM
				ContaReceberPosicaoCobranca,
				LocalCobranca,
				ContaReceber
			WHERE
				(
					(
						LocalCobranca.IdLoja = $local_IdLoja and
						LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
					) or (
						LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and
						LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
					)
				) and
				ContaReceberPosicaoCobranca.IdLoja = ContaReceber.IdLoja AND
				ContaReceberPosicaoCobranca.IdContaReceber = ContaReceber.IdContaReceber AND
				
				ContaReceber.IdLoja = LocalCobranca.IdLoja AND
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND
				
				ContaReceberPosicaoCobranca.IdPosicaoCobranca IN (1, 6, 9) AND
				ContaReceberPosicaoCobranca.IdArquivoRemessa IS NULL
			GROUP BY
				ContaReceberPosicaoCobranca.IdLoja,
				ContaReceberPosicaoCobranca.IdContaReceber,
				ContaReceberPosicaoCobranca.IdPosicaoCobranca
			order by
				ContaReceberPosicaoCobranca.IdLoja,
				ContaReceberPosicaoCobranca.IdContaReceber,
				ContaReceberPosicaoCobranca.IdPosicaoCobranca";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		if($lin[Qtd] > 0){
			$PosicaoCobranca[$lin[IdContaReceber]][$lin[IdPosicaoCobranca]] = $lin[IdMovimentacao];

			if(($PosicaoCobranca[$lin[IdContaReceber]][9] != '' || $PosicaoCobranca[$lin[IdContaReceber]][6] != '') && $PosicaoCobranca[$lin[IdContaReceber]][1] != ''){
				$sql = "delete from ContaReceberPosicaoCobranca where
							IdLoja = $lin[IdLoja] and
							IdContaReceber = $lin[IdContaReceber] and
							DataRemessa = '0000-00-00' and
							IdPosicaoCobranca in (6,9)";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
		}
	}

	# Discartando as remessas seguidas de cancelamento
	# Revisado 11/05/2012 Douglas + Weiner
	$sql =  "SELECT
				ContaReceberAguardandoEnvio.IdLoja,
				ContaReceberAguardandoEnvio.IdContaReceber
			FROM
				(SELECT
					IdLoja,
					IdContaReceber
				FROM
					ContaReceberPosicaoCobranca
				WHERE
					IdPosicaoCobranca = 1 AND
					DataRemessa = '0000-00-00') ContaReceberAguardandoEnvio,

				(SELECT
					IdLoja,
					IdContaReceber
				FROM
					ContaReceberPosicaoCobranca
				WHERE
					IdPosicaoCobranca IN (5,8) AND
					DataRemessa = '0000-00-00') ContaReceberAguardandoCancelamento
			WHERE
				ContaReceberAguardandoCancelamento.IdLoja = $local_IdLoja AND
				ContaReceberAguardandoCancelamento.IdLoja = ContaReceberAguardandoEnvio.IdLoja AND
				ContaReceberAguardandoCancelamento.IdContaReceber = ContaReceberAguardandoEnvio.IdContaReceber";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where
					IdLoja = $lin[IdLoja] and
					IdContaReceber = $lin[IdContaReceber]";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	}
	
	#Discartando alteraчуo de dados de contas a receber cancelados
	# Revisado 11/05/2012 Douglas + Weiner
	$sql = "select
				ContaReceber.IdLoja,
				ContaReceber.IdContaReceber
			from
				ContaReceber,
				LocalCobranca
			where
				(
					(
						LocalCobranca.IdLoja = $local_IdLoja and
						LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
					) or (
						LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and
						LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
					)
				) and

				ContaReceber.IdLoja = LocalCobranca.IdLoja and				
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				ContaReceber.IdStatus = 0";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where
					IdLoja = $lin[IdLoja] and
					IdContaReceber = $lin[IdContaReceber] and
					IdPosicaoCobranca = 9 and
					DataRemessa = '0000-00-00'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	}
	
	#Discartando contas a receber quitados antes de ser registrados na primeira vez
	# Revisado 11/05/2012 Douglas + Weiner
	$sql = "SELECT
				ContaReceberPosicaoCobranca.IdLoja,
				ContaReceberPosicaoCobranca.IdContaReceber,
				ContaReceber.IdStatus
			FROM
				ContaReceberPosicaoCobranca,
				ContaReceber,
				LocalCobranca
			WHERE
				(
					(
						LocalCobranca.IdLoja = $local_IdLoja and
						LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
					) or (
						LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and
						LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
					)
				) and
				ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00' AND
				ContaReceberPosicaoCobranca.IdPosicaoCobranca = 1 AND
				ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND
				ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber AND
				ContaReceber.IdLoja = LocalCobranca.IdLoja AND
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND
				ContaReceber.IdStatus IN (0,2)";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where
					IdLoja = $lin[IdLoja] and
					IdContaReceber = $lin[IdContaReceber] and						
					DataRemessa = '0000-00-00'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	}	

	# Discartando as remessas seguidas de quitaчуo
	$sql =  "SELECT
				ContaReceberAlteracaoOutrosDados.IdLoja,
				ContaReceberAlteracaoOutrosDados.IdContaReceber,
				ContaReceberAlteracaoOutrosDados.IdPosicaoCobranca
			FROM
				(SELECT
					IdLoja,
					IdContaReceber,
					IdPosicaoCobranca
				FROM
					ContaReceberPosicaoCobranca
				WHERE
					IdPosicaoCobranca = 9 AND
					DataRemessa = '0000-00-00') ContaReceberAlteracaoOutrosDados,

				(SELECT
					IdLoja,
					IdContaReceber
				FROM
					ContaReceberPosicaoCobranca
				WHERE
					IdPosicaoCobranca = 4 AND
					DataRemessa = '0000-00-00') ContaReceberPedidoBaixa
			WHERE
				ContaReceberPedidoBaixa.IdLoja = $local_IdLoja AND
				ContaReceberPedidoBaixa.IdLoja = ContaReceberAlteracaoOutrosDados.IdLoja AND
				ContaReceberPedidoBaixa.IdContaReceber = ContaReceberAlteracaoOutrosDados.IdContaReceber";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "delete from ContaReceberPosicaoCobranca where
					IdLoja = $lin[IdLoja] and
					IdContaReceber = $lin[IdContaReceber] and
					DataRemessa = '0000-00-00' and
					IdPosicaoCobranca = $lin[IdPosicaoCobranca]";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	}
?>