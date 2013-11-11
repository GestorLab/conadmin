<?
	$Aviso[debito]	= false;
	$Msg[debito]	= '';

	if($Aviso2 == 1){
		$sql = "select
					ContaReceberDados.IdLoja,
					ContaReceberDados.IdContaReceber,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.NumeroDocumento,
					ContaReceberDados.ValorFinal,
					ContaReceberDados.MD5,
					LocalCobranca.IdLocalCobrancaLayout
				from
					ContaReceberDados,
					ContaReceberBaseVencimento,
					LocalCobranca
				where
					ContaReceberDados.IdPessoa = $IdPessoa and
					ContaReceberDados.IdStatus = 1 and
					ContaReceberDados.IdLoja = ContaReceberBaseVencimento.IdLoja and
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
					ContaReceberDados.IdContaReceber = ContaReceberBaseVencimento.IdContaReceber and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberBaseVencimento.BaseVencimento >= LocalCobranca.DiasAvisoRegressivo
				order by
					ContaReceberDados.DataVencimento,
					ContaReceberDados.ValorFinal";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$local_IdLoja 				= $lin[IdLoja];
			$Aviso[geral]				= true;
			$Aviso[debito]				= true;

			$lin[DataVencimento]	= dataConv($lin[DataVencimento], 'Y-m-d','d/m/Y');
			$lin[ValorFinal]		= $Moeda." ".numberFormat($lin[ValorFinal]);

			if($lin[IdLocalCobrancaLayout] != ''){
				$lin[UrlBoleto] = $UrlSistema."/modulos/administrativo/boleto.php?ContaReceber=$lin[MD5]";
				$lin[LinkBoleto] = "<a href='$lin[UrlBoleto]' target='_blank'>Imprimir</a>";
			}

			$Msg[debito] .= "
				<tr>
					<td class='quadroAviso_center' style='border-bottom: 1px #333 solid'>$lin[NumeroDocumento]</td>
					<td class='quadroAviso_center' style='border-bottom: 1px #333 solid'>$lin[DataVencimento]</td>
					<td class='quadroAviso_center' style='border-bottom: 1px #333 solid'>$lin[ValorFinal]</td>
					<td class='quadroAviso_center' style='border-bottom: 1px #333 solid'>$lin[LinkBoleto]</td>
				</tr>
			";
		}

		if($Msg[debito] != ''){

			// Estrutura tabela
			$Msg[debito] = "<table border='0' cellpadding='0' cellspacing='0' class='quadroAviso' align='center'>
								<tr>
									<th class='quadroAviso_center'>Número</th>
									<th class='quadroAviso_center'>Data Vencimento</th>
									<th class='quadroAviso_center'>Valor</th>
									<th class='quadroAviso_center'>2ª via</th>
								</tr>\n".$Msg[debito]."</table>";

			// Descrição do quadro
			$Msg[debito] = "<p style='text-align:center; font-weight: bold; color: red'>".getParametroSistema(225,3)."</p>\n".$Msg[debito];
			
			// Descrição do quadro
			$Msg[debito] .= "<p style='font-size: 11px'>".getParametroSistema(225,4)."</p>\n";
			
			$sql = "select
						Contrato.IdLoja,
						Contrato.IdStatus,
						Contrato.VarStatus,
						ContaReceberBloqueio.DataVencimento,
						Servico.DiasLimiteBloqueio
					from
						(
							select
								ContaReceber.IdLoja,
								ContaReceber.DataVencimento,
								if((OrdemServico.IdContratoFaturamento <> ''),
									OrdemServico.IdContratoFaturamento,
									if((OrdemServico.IdContrato <> ''),
										OrdemServico.IdContrato,
										if((LancamentoFinanceiro.IdContrato <> ''),
											LancamentoFinanceiro.IdContrato,
											Contrato.IdContrato
										)
									)
								) AS IdContrato
							from
								ContaReceber,
								LancamentoFinanceiroContaReceber,
								(
									LancamentoFinanceiro LEFT JOIN OrdemServico ON
									(
										LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and
										LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico
									)
								) LEFT JOIN Contrato ON
								(
									LancamentoFinanceiro.IdLoja = Contrato.IdLoja and
									LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
									Contrato.IdStatus = 200
								)
							where					
								ContaReceber.IdStatus = 1 and
								ContaReceber.IdPessoa = $IdPessoa and
								ContaReceber.DataVencimento < curdate() and
								ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
								ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
								LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
								LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro
							order by
								ContaReceber.DataVencimento
						) AS ContaReceberBloqueio,
						Contrato,
						Servico
					where
						ContaReceberBloqueio.IdLoja = Contrato.IdLoja and
						ContaReceberBloqueio.IdContrato = Contrato.IdContrato and
						ContaReceberBloqueio.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico and
						Contrato.IdStatus >= 200 and
						Contrato.IdStatus <= 299
					order by
						ContaReceberBloqueio.DataVencimento
					limit
						0,1;";
			$res = mysql_query($sql,$con);
			if($lin = mysql_fetch_array($res)){
				if($lin[DiasLimiteBloqueio] != ''){
					$local_IdLoja 		= $lin[IdLoja];
					$lin[DataSuspensao] = incrementaData($lin[DataVencimento],$lin[DiasLimiteBloqueio]);

					if($lin[IdStatus] == 201){
						$lin[DataSuspensao2] = dataConv($lin[VarStatus],'d/m/Y', 'Y-m-d');

						if(dataConv($lin[DataSuspensao2], 'Y-m-d', 'Ymd') > dataConv($lin[DataSuspensao], 'Y-m-d', 'Ymd')){
							$lin[DataSuspensao] = $lin[DataSuspensao2];
						}
					}

					$lin[DataSuspensao] = dataConv($lin[DataSuspensao],'Y-m-d', 'd/m/Y');

					$Msg[debito] .= "\n<p>A data prevista para <U>suspensão do serviço</U> é <B style='font-size: 18px; color: red;'>$lin[DataSuspensao]$lin[IdContrato]</B>.</p>";
				}
			}
		}
	}
?>