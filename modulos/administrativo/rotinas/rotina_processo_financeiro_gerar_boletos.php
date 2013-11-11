<?
	$sql = "select
				count(*) Qtd
			from
				Demonstrativo
			where
				IdLoja = $local_IdLoja and
				IdProcessoFinanceiro = $local_IdProcessoFinanceiro";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	if($lin[Qtd] > getCodigoInterno(17,1)){
		header("Location: processo_financeiro_confirmar_gerar_boletos.php?IdProcessoFinanceiro=$local_IdProcessoFinanceiro");
	}else{
		$sql = "select
					LocalCobranca.IdLocalCobrancaLayout
				from
					ProcessoFinanceiro,
					LocalCobranca
				where
					ProcessoFinanceiro.IdLoja = $local_IdLoja and
					ProcessoFinanceiro.IdLoja = LocalCobranca.IdLoja and
					ProcessoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
					ProcessoFinanceiro.Filtro_IdLocalCobranca = LocalCobranca.IdLocalCobranca";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$file="local_cobranca/$lin[IdLocalCobrancaLayout]/pdf_all.php";
		$fileurl = $file."?IdLoja=$local_IdLoja&IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
		if(file_exists($file)){
			header("Location: $fileurl");
		}else{				
			$local_Erro=58;
		}
	}
?>