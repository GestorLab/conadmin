<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<?
			if($localTituloOperacao != ''){
				$localTituloSite = $localTituloOperacao." - ".getParametroSistema(4,1);
			} else{
				$localTituloSite = getParametroSistema(4,1);
			}
		?>
		<title><?=$localTituloSite?></title>
		
		<link rel='stylesheet' type='text/css' href='../../css/recibo.css' />
	</head>
	<body>
		<div id='container'>
			<div id='cabecalho' style='line-height:13px;'>
				<img src='<?=$ExtLogoReciboHTML?>' style='float:left' />
				<?="$dadosboleto[cedente]<br>$dadosboleto[endereco] - $dadosboleto[cidade]<br>$CPF_CNPJ: $dadosboleto[cpf_cnpj]".$dadosboleto[fone]?>
			</div>
			<table cellspacing='0' class='via1' style='margin-top:3px;'>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' colspan='2'>Via Cliente</td>
					<td class='titulo_campo' style='width:111px;'>Nº</td>
				</tr>
				<tr>
					<td colspan='2'><h1>RECIBO</h1></td>
					<td style='text-align:center;'><h1><?=$IdRecibo?></h1></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>Recebemos de</td>
					<td class='titulo_campo'style='width:111px;'>Recebido por</td>
					<td class='titulo_campo'>Vencimento em</td>
				</tr>
				<tr>
					<? 
						if(getCodigoInterno(3,225) == '1'){
							$IdPessoaImpressao = " [$linDadosCliente[IdPessoa]]";
						}else{
							$IdPessoaImpressao = "";
						}
					?>
					<td><?=$linDadosCliente[Nome].$IdPessoaImpressao?></td>
					<td><?=$linLoginRecebimento[LoginAbertura]?></td>
					<td style='text-align:right;'><?=$linDadosCliente[DataVencimento]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>Local de recebimento</td>
					<td class='titulo_campo'style='width:111px;'>Número Documento</td>
					<td class='titulo_campo'>Recebido em</td>
				</tr>
				<tr>
					<td><?=$linDadosCliente[DescricaoLocalCobranca]?></td>
					<td><?=$linDadosCliente[NumeroDocumento]?></td>
					<td style='text-align:right;'><?=$linDadosCliente[DataRecebimento]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' colspan='2'>Referente:</td>
					<td class='titulo_campo'>(=) Valor documento</td>
				</tr>
				<tr>
					<td rowspan='9' valign='top' colspan='2'>
						<table cellspacing='0' id='tabela_demonstrativo'>
							<tr>
								<th style='text-align:left;'>Tipo</th>
								<th style='text-align:left;'>Cod.</th>
								<th style='text-align:left;'>Descrição</th>
								<th style='text-align:center;'>Referência</th>
								<th style='text-align:right;'>Valor&nbsp;(<?=getParametroSistema(5,1)?>)</th>
							</tr>
							<?
								$valorTotal = 0;
								$sql	= "select
									      Tipo,
									      Codigo,
									      Descricao,
									      Referencia,
									      (Valor + ValorDespesas) Valor
									from
									     Demonstrativo
									where
									     IdLoja = $IdLoja and
										  IdContaReceber = $linDadosCliente[IdContaReceber]
									order by
									     Demonstrativo.IdPessoa,Tipo,Codigo,IdLancamentoFinanceiro";			
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$valorTotal += $lin[Valor];
									$lin[Valor] = number_format($lin[Valor],2,',','');

									echo "
										<tr>
											<td style='font-weight:normal'>$lin[Tipo]</td>
											<td style='font-weight:normal'>$lin[Codigo]</td>
											<td style='font-weight:normal'>$lin[Descricao]</td>
											<td style='text-align:center; font-weight:normal'>$lin[Referencia]</td>
											<td style='text-align:right; font-weight:normal'>$lin[Valor]</td>
										</tr>
									";
								}
								
								$valorTotal = number_format($valorTotal,2,',','');

								echo "
									<tr>
										<td>&nbsp;</td>	
										<td>&nbsp;</td>	
										<td>&nbsp;</td>
										<td style='text-align:center;'>Total</td>
										<td style='text-align:right;'>$valorTotal</td>
									</tr>
								";
								
							?>
						</table>
					</td>
					<td style='text-align:right;'><?=$linDadosCliente[ValorLancamento]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>(-) Desconto / Abatimento</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorDesconto]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>(+) Mora / Multa</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorMoraMulta]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>(+) Outras Despesas</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorOutrasDespesas]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>(=) Valor pago</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorRecebido]?></td>
				</tr>
				<tr>
					<td class='titulo_campo' colspan='2'>&nbsp;</td>
					<td class='titulo_campo' rowspan='2' style='text-align:center; border-bottom:1px solid #000;'><? CodigoBarras($linDadosCliente[CodigoDeBarras],40); ?></td>
				</tr>
				<tr>
					<td style='text-align:center; border-bottom:1px solid #000; padding-bottom:6px;' colspan='2'>
						<div id='ass'>
							<p style='margin:4px 0;'><?=$linDadosEmpresa[RazaoSocial]?></p>
						</div>
					</td>
				</tr>
			</table>
			<div style='font-size:9px; line-height:16px; border-bottom:1px dashed #000;'>Corte na linha pontilhada</div>
			<?if(getCodigoInterno(32,2) == 2){?>
			<table cellspacing='0' class='via1' style='border-bottom:1px solid #000; margin-top:16px;'>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' colspan='2'>Via Empresa</td>
					<td class='titulo_campo' style='width:111px;'>Nº</td>
				</tr>
				<tr>
					<td colspan='2'><h1>RECIBO</h1></td>
					<td style='text-align:center;'><h1><?=$IdRecibo?></h1></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>Recebemos de</td>
					<td class='titulo_campo'style='width:111px;'>Recebido por</td>
					<td class='titulo_campo'>Vencimento em</td>
				</tr>
				<tr>
					<? 
						if(getCodigoInterno(3,225) == '1'){
							$IdPessoaImpressao = " [$linDadosCliente[IdPessoa]]";
						}else{
							$IdPessoaImpressao = "";
						}
					?>
					<td><?=$linDadosCliente[Nome].$IdPessoaImpressao?></td>
					<td><?=$linLoginRecebimento[LoginAbertura]?></td>
					<td style='text-align:right;'><?=$linDadosCliente[DataVencimento]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>Local de recebimento</td>
					<td class='titulo_campo'style='width:111px;'>Número Documento</td>
					<td class='titulo_campo'>Recebido em</td>
				</tr>
				<tr>
					<td><?=$linDadosCliente[DescricaoLocalCobranca]?></td>
					<td><?=$linDadosCliente[NumeroDocumento]?></td>
					<td style='text-align:right;'><?=$linDadosCliente[DataRecebimento]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' colspan='2'>Referente:</td>
					<td class='titulo_campo'>(=) Valor documento</td>
				</tr>
				<tr>
					<td rowspan='9' valign='top' colspan='2'>
						<table cellspacing='0' id='tabela_demonstrativo'>
							<tr>
								<th style='text-align:left;'>Tipo</th>
								<th style='text-align:left;'>Cod.</th>
								<th style='text-align:left;'>Descrição</th>
								<th style='text-align:center;'>Referência</th>
								<th style='text-align:right;'>Valor&nbsp;(<?=getParametroSistema(5,1)?>)</th>
							</tr>
							<?
								$valorTotal = 0;
								$sql	= "select
									      Tipo,
									      Codigo,
									      Descricao,
									      Referencia,
									      (Valor + ValorDespesas) Valor
									from
									     Demonstrativo
									where
									     IdLoja = $IdLoja and
										  IdContaReceber = $linDadosCliente[IdContaReceber]
									order by
									     Demonstrativo.IdPessoa,Tipo,Codigo,IdLancamentoFinanceiro";			
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$valorTotal += $lin[Valor];
									$lin[Valor] = number_format($lin[Valor],2,',','');

									echo "
										<tr>
											<td style='font-weight:normal'>$lin[Tipo]</td>
											<td style='font-weight:normal'>$lin[Codigo]</td>
											<td style='font-weight:normal'>$lin[Descricao]</td>
											<td style='text-align:center; font-weight:normal'>$lin[Referencia]</td>
											<td style='text-align:right; font-weight:normal'>$lin[Valor]</td>
										</tr>
									";
								}
								
								$valorTotal = number_format($valorTotal,2,',','');

								echo "
									<tr>
										<td>&nbsp;</td>	
										<td>&nbsp;</td>	
										<td>&nbsp;</td>
										<td style='text-align:center;'>Total</td>
										<td style='text-align:right;'>$valorTotal</td>
									</tr>
								";
								
							?>
						</table>
					</td>
					<td style='text-align:right;'><?=$linDadosCliente[ValorLancamento]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>(-) Desconto / Abatimento</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorDesconto]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>(+) Mora / Multa</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorMoraMulta]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>(+) Outras Despesas</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorOutrasDespesas]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo'>(=) Valor pago</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorRecebido]?></td>
				</tr>
			</table>
			<div style='font-size:9px; line-height:16px; border-bottom:1px dashed #000;'>Corte na linha pontilhada</div>
			<? } ?>
		</div>
	</body>
</html>