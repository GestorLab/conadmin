<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ordem_servico_status(){
		global $con;
		global $_SESSION;
		global $_GET;
		
		$IdLoja			= $_SESSION["IdLoja"];
		$IdContaEventual	= $_GET['IdContaEventual'];
		$IdStatus		= $_GET['IdStatus'];
		$where			= "";
		
		if($IdContaEventual != ''){
			$where .= " and LancamentoFinanceiroDados.IdContaEventual = $IdContaEventual";
		}
		
		header ("content-type: text/xml");
		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";
		
		$sql = "
			SELECT
				ParcelaQuitada.QtdParcelaQuitada,
				Parcela.QtdParcelaAtiva,
				((
					SELECT 
						COUNT(*) 
					FROM
						LancamentoFinanceiroDados 
					WHERE 
						LancamentoFinanceiroDados.IdLoja = '$IdLoja' 
						$where
					GROUP BY 
						LancamentoFinanceiroDados.IdOrdemServico
				) = (
					SELECT 
						COUNT(*) 
					FROM
						LancamentoFinanceiroDados 
					WHERE 
						LancamentoFinanceiroDados.IdLoja = '$IdLoja' AND 
						LancamentoFinanceiroDados.IdStatus = '0' 
						$where
					GROUP BY 
						LancamentoFinanceiroDados.IdOrdemServico
				)) LancamentosCancelados 
			FROM
				(
					SELECT
						LancamentoFinanceiroDados.IdLoja,
						LancamentoFinanceiroDados.IdContaEventual,
						COUNT(LancamentoFinanceiroDados.IdContaEventual) QtdParcelaAtiva,
						LancamentoFinanceiroDados.IdStatus
					FROM
						LancamentoFinanceiroDados
					WHERE
						LancamentoFinanceiroDados.IdLoja = '$IdLoja' AND
						(
							LancamentoFinanceiroDados.IdStatus = '1' OR
							LancamentoFinanceiroDados.IdStatus = '2'
						)
						$where
					GROUP BY
						LancamentoFinanceiroDados.IdContaEventual
				)Parcela,
				(
					select 
						count(*) QtdParcelaQuitada
					from
						LancamentoFinanceiroDados 
					where
						IdContaEventual = $IdContaEventual and
						IdStatusContaReceber = 2
				) ParcelaQuitada,
				LancamentoFinanceiroDados
			WHERE
				LancamentoFinanceiroDados.IdLoja = Parcela.IdLoja AND
				LancamentoFinanceiroDados.IdContaEventual = Parcela.IdContaEventual AND
				LancamentoFinanceiroDados.IdStatus = Parcela.IdStatus;";
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
		
		$Cor = getCodigoInterno(34, $IdStatus);
		$ValorParametroSistema = getParametroSistema(46, $IdStatus);
		
		$dados	.=	"\n<ValorParametroSistema><![CDATA[$ValorParametroSistema]]></ValorParametroSistema>";
		$dados	.=	"\n<Parcela><![CDATA[$Parcela]]></Parcela>";
		$dados	.=	"\n<Cor><![CDATA[$Cor]]></Cor>";		
		$dados	.=	"\n</reg>";
		
		return $dados;
	}
	echo get_ordem_servico_status();
?>