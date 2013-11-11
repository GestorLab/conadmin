<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
		$sql = "SELECT
					IdLoteRepasse
				FROM
					LoteRepasseTerceiro
				WHERE
					IdLoja = $local_IdLoja AND
					IdTerceiro = $local_IdPessoa AND
					IdStatus != 3 AND IdStatus != 1 AND
					IdLoteRepasse < $local_IdLoteRepasse";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) >=1 && $localOperacao != 2){
			$local_Erro	=	150;
		}else{
		
			$sql	=	"START TRANSACTION;";
			mysql_query($sql,$con);
			
			$tr_i = 0;	
			
			$where1	=	"";
			$where2	=	"";

			if($local_Filtro_IdServico != ''){
				$where2	.=	" and Servico.IdServico in ($local_Filtro_IdServico)";	
			}
			
			if($local_Filtro_IdPessoa != ''){
				$where1	.=	" and (Contrato.IdPessoa in ($local_Filtro_IdPessoa) or OrdemServico.IdPessoa in ($local_Filtro_IdPessoa))";
			}
			
			if($local_Filtro_IdLocalRecebimento != ''){
				$where1	.=	" and ContaReceberRecebimento.IdLocalCobranca in ($local_Filtro_IdLocalRecebimento)";
			}
			
			if($local_Filtro_IdAgenteAutorizadoCarteira != ''){
				
				$IdAgenteAutorizadoCarteiraTemp = explode(",",$local_Filtro_IdAgenteAutorizadoCarteira);

				$whereTemp = " and (";

				for($i=0; $i<count($IdAgenteAutorizadoCarteiraTemp); $i++){

					$IdAgenteAutorizadoCarteiraTemp2 = explode("_",$IdAgenteAutorizadoCarteiraTemp[$i]);

					if($i > 0){
						$whereTemp .= " or ";
					}
					if($IdAgenteAutorizadoCarteiraTemp2[1] != 0){
						$whereTemp .= "(";
					}
					
					$whereTemp .= "Contrato.IdAgenteAutorizado = $IdAgenteAutorizadoCarteiraTemp2[0]";

					if($IdAgenteAutorizadoCarteiraTemp2[1] != 0){
						$whereTemp .= " and Contrato.IdCarteira = $IdAgenteAutorizadoCarteiraTemp2[1]";
						$whereTemp .= ")";
					}

					$whereTemp .= " or ";

					if($IdAgenteAutorizadoCarteiraTemp2[1] != 0){
						$whereTemp .= "(";
					}
					
					$whereTemp .= "OrdemServico.IdAgenteAutorizado = $IdAgenteAutorizadoCarteiraTemp2[0]";

					if($IdAgenteAutorizadoCarteiraTemp2[1] != 0){
						$whereTemp .= " and OrdemServico.IdCarteira = $IdAgenteAutorizadoCarteiraTemp2[1]";
						$whereTemp .= ")";
					}
				}
				
				$whereTemp .= ")";
				$where1	.= $whereTemp;

			}

			if($local_Filtro_IdPaisEstadoCidade != ''){
				$IdPaisEstadoCidadeTemp = explode("^",$local_Filtro_IdPaisEstadoCidade);

				$whereTemp = " and ("; 
				for($i=0; $i<count($IdPaisEstadoCidadeTemp); $i++){

					$IdPaisEstadoCidadeTemp2 = explode(",",$IdPaisEstadoCidadeTemp[$i]);

					if($i > 0){
						$whereTemp .= " or ";
					}
					$whereTemp .= "(PessoaEndereco.IdPais = $IdPaisEstadoCidadeTemp2[0] and PessoaEndereco.IdEstado = $IdPaisEstadoCidadeTemp2[1] and PessoaEndereco.IdCidade = $IdPaisEstadoCidadeTemp2[2])";
				}
				$whereTemp .= ")";
				$where2	.= $whereTemp;
			}
			
			if($local_Filtro_MenorVencimento != ''){
				$local_Filtro_MenorVencimento = dataConv($local_Filtro_MenorVencimento,'d/m/Y','Y-m-d');
				$where1	.=	" and ContaReceberMenorVencimento.DataVencimento >= '$local_Filtro_MenorVencimento'";
			}

			$MesReferencia	= explode("/",$local_Filtro_MesReferencia);
			$Mes			= $MesReferencia[0];
			$Ano			= $MesReferencia[1];
			$local_MesReferencia	=	'31/'.$local_Filtro_MesReferencia;
			$local_MesReferencia	=	dataConv($local_MesReferencia,'d/m/Y','Y-m-d');

			$where1	.=	" and ContaReceberRecebimento.DataRecebimento <= '$local_MesReferencia'";	

			$local_Valor	= 0;
			$sql = "select
						LancamentoFinanceiroQuitado.IdLoja,
						LancamentoFinanceiroQuitado.IdLancamentoFinanceiro,
						LancamentoFinanceiroQuitado.ValorContaReceber,
						LancamentoFinanceiroQuitado.ValorLancamentoFinanceiro,
						LancamentoFinanceiroQuitado.ValorDesconto,
						
						if(LancamentoFinanceiroQuitado.IdOrdemServico != '',
							if(ServicoTerceiro.PercentualRepasseTerceiro = 0 and ServicoTerceiro.PercentualRepasseTerceiroOutros = 0,
								LancamentoFinanceiroQuitado.ValorRepasseTerceiro,
								(
									LancamentoFinanceiroQuitado.OrdemServicoValor * (
										ServicoTerceiro.PercentualRepasseTerceiro / 100
									)
								) + (
									LancamentoFinanceiroQuitado.OrdemServicoValorOutros * (
										ServicoTerceiro.PercentualRepasseTerceiroOutros / 100
									)
								)
							),
							(
								LancamentoFinanceiroQuitado.ValorRepasseTerceiro
							)
						) ValorRepasseTerceiro,
						
						if(LancamentoFinanceiroQuitado.IdOrdemServico != '',
							(
								if(ServicoTerceiro.PercentualRepasseTerceiro = 0 and ServicoTerceiro.PercentualRepasseTerceiroOutros = 0,
									(
										(
											LancamentoFinanceiroQuitado.ValorDesconto / LancamentoFinanceiroQuitado.ValorContaReceber
										) * LancamentoFinanceiroQuitado.ValorRepasseTerceiro
									),
									(
										(
											LancamentoFinanceiroQuitado.ValorDesconto / LancamentoFinanceiroQuitado.ValorContaReceber * LancamentoFinanceiroQuitado.OrdemServicoValor
										) * ServicoTerceiro.PercentualRepasseTerceiro / 100
									) + (
										(
											LancamentoFinanceiroQuitado.ValorDesconto / LancamentoFinanceiroQuitado.ValorContaReceber * LancamentoFinanceiroQuitado.OrdemServicoValorOutros
										) * ServicoTerceiro.PercentualRepasseTerceiroOutros / 100
									)
								)
							),
							(
								(
									LancamentoFinanceiroQuitado.ValorDesconto / LancamentoFinanceiroQuitado.ValorContaReceber
								) * LancamentoFinanceiroQuitado.ValorRepasseTerceiro
							)
						) ValorDescontoRepasseTerceiro
					from
						(select
							LancamentoFinanceiro.IdLoja,
							LancamentoFinanceiro.IdLancamentoFinanceiro,
							LancamentoFinanceiro.Valor ValorLancamentoFinanceiro,
							LancamentoFinanceiro.ValorRepasseTerceiro,
							LancamentoFinanceiro.ValorDescontoAConceber,
							if(OrdemServico.IdServico != '', OrdemServico.IdServico, Contrato.IdServico) IdServico,
							if(OrdemServico.IdServico != '', OrdemServico.IdPessoa, Contrato.IdPessoa) IdPessoa,
							if(OrdemServico.IdServico != '', OrdemServico.IdPessoaEndereco, Contrato.IdPessoaEndereco) IdPessoaEndereco,
							if(OrdemServico.IdServico != '', OrdemServico.IdTerceiro, Contrato.IdTerceiro) IdTerceiro,
							Contrato.IdContrato,
							OrdemServico.IdOrdemServico,
							OrdemServico.Valor OrdemServicoValor,
							OrdemServico.ValorOutros OrdemServicoValorOutros,
							ContaReceberMenorVencimento.ValorContaReceber,
							ContaReceberRecebimento.ValorDesconto,
							ContaReceberRecebimento.ValorRecebido
						from
							LancamentoFinanceiro 
								left join Contrato on 
									(LancamentoFinanceiro.IdLoja = Contrato.IdLoja and LancamentoFinanceiro.IdContrato = Contrato.IdContrato) 
								left join OrdemServico on 
									(LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico),
							LancamentoFinanceiroContaReceber,
							ContaReceberRecebimento,
							(select
								IdLoja,	
								IdContaReceber,
								min(DataVencimento) DataVencimento,
								ValorContaReceber
							from
								ContaReceberVencimento
							group by
								IdLoja,	
								IdContaReceber) ContaReceberMenorVencimento
						where
							LancamentoFinanceiro.IdLoja = $local_IdLoja and
							LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
							LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
							LancamentoFinanceiroContaReceber.IdLoja = ContaReceberRecebimento.IdLoja and
							LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
							LancamentoFinanceiroContaReceber.IdLoja = ContaReceberMenorVencimento.IdLoja and
							LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberMenorVencimento.IdContaReceber and
							(OrdemServico.IdServico != '' or Contrato.IdServico != '') and
							(LancamentoFinanceiro.ValorRepasseTerceiro != 0 or OrdemServico.Valor > 0 or OrdemServico.ValorOutros > 0) $where1) LancamentoFinanceiroQuitado,
						Servico,
						ServicoTerceiro,
						PessoaEndereco
					where
						ServicoTerceiro.IdPessoa = $local_IdPessoa and
						ServicoTerceiro.IdPessoa = LancamentoFinanceiroQuitado.IdTerceiro and
						LancamentoFinanceiroQuitado.IdLoja = Servico.IdLoja and
						LancamentoFinanceiroQuitado.IdServico = Servico.IdServico and
						(LancamentoFinanceiroQuitado.IdPessoa = PessoaEndereco.IdPessoa and LancamentoFinanceiroQuitado.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco) and
						Servico.IdLoja = ServicoTerceiro.IdLoja and
						Servico.IdServico = ServicoTerceiro.IdServico $where2";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){

				$sqlVerificaRepasse = "select 
										IdLancamentoFinanceiro,
										IdLoteRepasse
									from 
										LoteRepasseTerceiroItem 
									where 
										IdLoja = $local_IdLoja and
										IdLancamentoFinanceiro = $lin[IdLancamentoFinanceiro]";
				$resVerificaRepasse = mysql_query($sqlVerificaRepasse,$con);

				if(mysql_num_rows($resVerificaRepasse) == 0 && $lin[ValorRepasseTerceiro] > 0){
				
					$lin[ValorRepasseTerceiro]	-= $lin[ValorDescontoRepasseTerceiro];
					$local_Valor				+=	$lin[ValorRepasseTerceiro];
					
					$sql	=	"INSERT INTO LoteRepasseTerceiroItem SET
									IdLoja						=	$local_IdLoja,
									IdLoteRepasse				=	$local_IdLoteRepasse,
									IdLancamentoFinanceiro		=	$lin[IdLancamentoFinanceiro],
									ValorDescontoItemRepasse	=	'$lin[ValorDescontoRepasseTerceiro]',
									ValorItemRepasse			=	'$lin[ValorRepasseTerceiro]'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
			}

			$sql	=	"UPDATE LoteRepasseTerceiro SET
								IdStatus			= 2,	
								ValorTotalItens		= '$local_Valor',
								DataProcessamento	= concat(curdate(),' ',curtime()),
								LoginProcessamento	= '$local_Login'
						 WHERE 
								IdLoja			= $local_IdLoja and
								IdLoteRepasse	= $local_IdLoteRepasse";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;

			for($i=0; $i<$tr_i; $i++){
				if($local_transaction[$i] == false){
					$local_transaction = false;				
				}
			}
			
			if($local_transaction == true){
				$sql = "COMMIT;";
				$local_Erro = 62;
			}else{
				$sql = "ROLLBACK;";
				$local_Erro = 86;
			}
			mysql_query($sql,$con);
		}
	}
?>