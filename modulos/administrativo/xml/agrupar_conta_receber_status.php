<?
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_agrupar_conta_receber_status(){
		global $con;
		global $_SESSION;
		global $_GET;
		
		$IdLoja						= $_SESSION["IdLoja"];
		$IdContaReceberAgrupador	= $_GET['IdContaReceberAgrupador'];
		$where						= "";
		
		if($IdContaReceberAgrupador != ''){
			$where .= " and ContaReceberAgrupadoParcela.IdContaReceberAgrupador = $IdContaReceberAgrupador";
		}
		
		header("content-type: text/xml");
		
		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";
		
		$sql = "
			SELECT
				COUNT(*) QtdParcelaQuitada,
				Parcela.QtdParcelaAtiva,
				((
					SELECT 
						COUNT(*) 
					FROM
						LancamentoFinanceiroDados, 
						ContaReceberAgrupadoParcela
					WHERE 
						LancamentoFinanceiroDados.IdLoja = '$IdLoja' AND
						LancamentoFinanceiroDados.IdLoja = ContaReceberAgrupadoParcela.IdLoja AND
						LancamentoFinanceiroDados.IdContaReceber = ContaReceberAgrupadoParcela.IdContaReceber 
						$where
					GROUP BY
						ContaReceberAgrupadoParcela.IdContaReceberAgrupador
				) = (
					SELECT 
						COUNT(*) 
					FROM
						LancamentoFinanceiroDados, 
						ContaReceberAgrupadoParcela
					WHERE 
						LancamentoFinanceiroDados.IdLoja = '$IdLoja' AND 
						LancamentoFinanceiroDados.IdStatus = '0' AND
						LancamentoFinanceiroDados.IdLoja = ContaReceberAgrupadoParcela.IdLoja AND
						LancamentoFinanceiroDados.IdContaReceber = ContaReceberAgrupadoParcela.IdContaReceber 
						$where
					GROUP BY
						ContaReceberAgrupadoParcela.IdContaReceberAgrupador
				)) LancamentosCancelados 
			FROM
				(
					SELECT
						LancamentoFinanceiroDados.IdLoja,
						LancamentoFinanceiroDados.IdContaReceber,
						COUNT(LancamentoFinanceiroDados.IdContaReceber) QtdParcelaAtiva,
						ContaReceberAgrupadoParcela.IdContaReceberAgrupador,
						LancamentoFinanceiroDados.IdStatus
					FROM
						LancamentoFinanceiroDados, 
						ContaReceberAgrupadoParcela
					WHERE
						LancamentoFinanceiroDados.IdLoja = '$IdLoja' AND
						(
							LancamentoFinanceiroDados.IdStatus = '1' OR
							LancamentoFinanceiroDados.IdStatus = '2'
						) AND 
						LancamentoFinanceiroDados.IdLoja = ContaReceberAgrupadoParcela.IdLoja AND
						LancamentoFinanceiroDados.IdContaReceber = ContaReceberAgrupadoParcela.IdContaReceber 
						$where
					GROUP BY
						ContaReceberAgrupadoParcela.IdContaReceberAgrupador
				) Parcela,
				LancamentoFinanceiroDados,
				ContaReceberAgrupadoParcela
			WHERE
				LancamentoFinanceiroDados.IdLoja = Parcela.IdLoja AND
				LancamentoFinanceiroDados.IdStatus = Parcela.IdStatus AND
				LancamentoFinanceiroDados.IdStatusContaReceber = '2' AND 
				LancamentoFinanceiroDados.IdLoja = ContaReceberAgrupadoParcela.IdLoja AND 
				LancamentoFinanceiroDados.IdContaReceber = ContaReceberAgrupadoParcela.IdContaReceber AND 
				ContaReceberAgrupadoParcela.IdContaReceberAgrupador = Parcela.IdContaReceberAgrupador;";
		$res = @mysql_query($sql,$con);
		
		if($lin = @mysql_fetch_array($res)){
			if($lin[QtdParcelaAtiva] > 0){
				if($lin[QtdParcelaQuitada] == $lin[QtdParcelaAtiva]){
					$Parcela = " (Quitado)";
				} else{
					if($lin[QtdParcelaQuitada] == 0){
						$Parcela = " (Aguard. Pagamento)";
					} else{
						$Parcela = " (Quitado $lin[QtdParcelaQuitada] - $lin[QtdParcelaAtiva])";
					}
				}
			} elseif($lin[LancamentosCancelados] == 1) {
				$Parcela = " (Lançamentos Cancelados)";
			}
		}
		
		$dados	.=	"\n<ValorParametroSistema><![CDATA[Cadastrado]]></ValorParametroSistema>";
		$dados	.=	"\n<Parcela><![CDATA[$Parcela]]></Parcela>";
		$dados	.=	"\n</reg>";
		
		return $dados;
	}
	
	echo get_agrupar_conta_receber_status();
?>