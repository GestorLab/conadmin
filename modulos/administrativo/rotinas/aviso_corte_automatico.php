<?
	// Aviso de Inadimplência
	if(getParametroSistema(175,date('w')+1) == 1){
		$sql = "select
					Data
				from
					DatasEspeciais
				where
					Data = curdate()";
		$res = mysql_query($sql,$con);
		if(mysql_num_rows($res) == 0){
			$sql = "SELECT 
						ContaReceberBaseVencimento.IdLoja,
						Contrato.IdContrato,
						ContaReceber.IdContaReceber,
						Contrato.IdStatus,
						ContaReceberBaseVencimento.BaseVencimento
					FROM 
						ContaReceberBaseVencimento,
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber,
						Contrato,
						Servico,
						ContaReceber,
						Pessoa,
						LocalCobranca,
						ServicoPeriodicidade
					WHERE 
						ContaReceberBaseVencimento.IdLoja = LancamentoFinanceiro.IdLoja AND
						ContaReceberBaseVencimento.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
						ContaReceberBaseVencimento.IdLoja = Servico.IdLoja AND
						ContaReceberBaseVencimento.IdLoja = ServicoPeriodicidade.IdLoja AND
						ContaReceberBaseVencimento.IdLoja = ContaReceber.IdLoja AND
						ContaReceber.IdLoja = LocalCobranca.IdLoja AND
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
						LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber AND
						ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND
						ContaReceberBaseVencimento.IdContaReceber = ContaReceber.IdContaReceber AND
						ContaReceberBaseVencimento.IdStatus = 1 AND
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato AND
						ContaReceber.IdPessoa = Pessoa.IdPessoa AND
						Contrato.IdServico = Servico.IdServico AND
						Contrato.IdServico = ServicoPeriodicidade.IdServico AND
						Contrato.IdPeriodicidade = ServicoPeriodicidade.IdPeriodicidade AND
						Contrato.QtdParcela = ServicoPeriodicidade.QtdParcela AND
						Contrato.TipoContrato = ServicoPeriodicidade.TipoContrato AND
						Contrato.IdLocalCobranca = ServicoPeriodicidade.IdLocalCobranca AND
						(
							FIND_IN_SET(ContaReceberBaseVencimento.BaseVencimento,Servico.DiasAvisoAposVencimento) != 0 OR 
							(
								Servico.DiasAvisoAposVencimento LIKE '%x%' and
								ContaReceberBaseVencimento.BaseVencimento > 0
							)
						) AND
						Servico.EmailCobranca = 1
					GROUP BY
						ContaReceberBaseVencimento.IdLoja,
						ContaReceberBaseVencimento.IdContaReceber";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
				if($lin[IdStatus] >= 200 && $lin[IdStatus] <= 299 && $lin[BaseVencimento] > 0){
					avisoVencimento($lin[IdLoja], $lin[IdContaReceber]);
				}else{
					avisoVencimentoIndependente($lin[IdLoja], $lin[IdContaReceber]);
				}
			}
			
			// Aviso do monitor financeiro
			$sql = "SELECT
						Loja.IdLoja,
						Pessoa.IdPessoa
					FROM
						Pessoa,
						Loja
					WHERE
						Pessoa.MonitorFinanceiro = 1";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
				enviarEmailMonitorarFinanceiro($lin[IdLoja],$lin[IdPessoa]);
			}
		}
	}	
?>