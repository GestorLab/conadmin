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
		$IdOrdemServico	= $_GET['IdOrdemServico'];
		$IdStatus		= $_GET['IdStatus'];
		$where			= "";
		
		if($IdOrdemServico != ''){
			$where .= " AND LancamentoFinanceiroDados.IdOrdemServico = $IdOrdemServico";
		}
		
		header ("content-type: text/xml");
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
						LancamentoFinanceiroDados.IdOrdemServico,
						COUNT(LancamentoFinanceiroDados.IdOrdemServico) QtdParcelaAtiva,
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
						LancamentoFinanceiroDados.IdOrdemServico
				)Parcela,
				LancamentoFinanceiroDados
			WHERE
				LancamentoFinanceiroDados.IdLoja = Parcela.IdLoja AND
				LancamentoFinanceiroDados.IdOrdemServico = Parcela.IdOrdemServico AND
				LancamentoFinanceiroDados.IdStatus = Parcela.IdStatus AND
				LancamentoFinanceiroDados.IdStatusContaReceber = '2'";
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
		
		if($IdStatus <= 99){
			$IdStatusTemp = 0;
			$Parcela = "";
		}
		
		switch($IdStatus[0]){
			case 1:
				$IdStatusTemp = 1;
				break;
			case 2:
				$IdStatusTemp = 2;
				break;
			case 3:
				$IdStatusTemp = 3;
				break;
			case 4:
				$IdStatusTemp = 4;
				break;
		}
		
		list($Cor) = explode("\r\n",getCodigoInterno(16, $IdStatusTemp));
		if($IdStatus == "500"){
			$IdStatus = '200';
		}
		$ValorParametroSistema = getParametroSistema(40, $IdStatus);
		
		$dados	.=	"\n<ValorParametroSistema><![CDATA[$ValorParametroSistema]]></ValorParametroSistema>";
		$dados	.=	"\n<Parcela><![CDATA[$Parcela]]></Parcela>";
		$dados	.=	"\n<Cor><![CDATA[$Cor]]></Cor>";		
		$dados	.=	"\n</reg>";
		
		return $dados;
	}
	echo get_ordem_servico_status();
?>