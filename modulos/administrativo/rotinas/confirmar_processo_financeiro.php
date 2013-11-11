<?
	set_time_limit(0);
	ini_set("memory_limit",getParametroSistema(138,1));

	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false && $localOperacao != 2){
		$local_Erro = 2;
	}else{
		
		// debug
		if(getParametroSistema(278,1) == 1){
			$debug = true;
			echo date("Y-m-d H:i:s")." >> Iniciou o processo financeiro! LJ: $local_IdLoja PF: $local_IdProcessoFinanceiro\n";
		}
	
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$ContaReceber = null;

		// Verifica o status inicial para os contas a receber de acordo com o tipo do local de cobrança.
		$sql = "select
					LocalCobranca.IdTipoLocalCobranca,
					ProcessoFinanceiro.Filtro_TipoCobranca
				from
					ProcessoFinanceiro,
					LocalCobranca
				where
					ProcessoFinanceiro.IdLoja = $local_IdLoja and
					ProcessoFinanceiro.IdLoja = LocalCobranca.IdLoja and
					ProcessoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
					LocalCobranca.IdLocalCobranca = ProcessoFinanceiro.Filtro_IdLocalCobranca";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		switch($lin[IdTipoLocalCobranca]){
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
			case 5:
				$StatusContaReceber = 1;
				break;
			case 6:
				$StatusContaReceber = 3;
				break;
		}

		if($lin[Filtro_TipoCobranca] == 2){		
			include('confirmar_processo_financeiro_etapa1.php');	// Contrato - Agrupar = S -> Carne
			include('confirmar_processo_financeiro_etapa2.php');	// Contrato - Agrupar = N -> Carne
		}else{
			include('confirmar_processo_financeiro_etapa3.php');	// Contrato - Agrupar = S -> Boleto
			include('confirmar_processo_financeiro_etapa4.php');	// Contrato - Agrupar = N -> Boleto
		}

		$sql = "SELECT
					DISTINCT
					LancamentoFinanceiroContaReceber.IdContaReceber
				FROM
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber
				WHERE
					LancamentoFinanceiro.IdLoja = $local_IdLoja AND
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
					LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro";
		$res = mysql_query($sql,$con);
		$lin[Qtd] = mysql_num_rows($res);

		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - $lin[Qtd] conta(s) à receber gerado(s).";

		if($debug == true){
			echo "$LogProcessamento\n";
		}
		
		$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
										IdStatus=3,
										LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento),
										LoginConfirmacao='$local_Login',
										DataConfirmacao=concat(curdate(),' ',curtime())
									  WHERE 
										IdLoja=$local_IdLoja AND 
										IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
		$local_transaction[$tr_i]	=	mysql_query($sqlProcessoFinanceiro,$con);
		$tr_i++;

		include('nota_fiscal_processo_financeiro.php');

		if($ContNotaFiscal > 0){
			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - $ContNotaFiscal nota(s) fiscal gerado(s).";
			if($debug == true){
				echo "$LogProcessamento\n";
			}
		}
			
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
	
		if($local_transaction == true){
			$sql = "COMMIT;";
			if($local_Erro == ''){
				$local_Erro = 47;
			}
		}else{
			$sql = "ROLLBACK;";
			if($local_Erro == ''){
				$local_Erro = 50;
			}
		}
		if($debug == true){
			echo date("Y-m-d H:i:s")." >> Concluiu o processo financeiro! LJ: $local_IdLoja PF: $local_IdProcessoFinanceiro ($sql)\n";
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>