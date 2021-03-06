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
					<td class='titulo_campo' style='width:111px;'>N�</td>
				</tr>
				<tr>
					<td colspan='2'><h1>RECIBO ESTORNO</h1></td>
					<td style='text-align:center;'><h1><?=$IdCaixa."".$IdCaixaMovimentacao?></h1></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' colspan="3">Estornado por</td>
				</tr>
				<tr>
					<? 
						if(getCodigoInterno(3,225) == '1'){
							$IdPessoaImpressao = " [$linDadosCliente[IdPessoa]]";
						}else{
							$IdPessoaImpressao = "";
						}
					?>
					<td colspan="3"><?=$linLoginRecebimento[LoginAbertura]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' style="width: 360px">Local de Estorno</td>
					<td class='titulo_campo'>C�digo da Movimenta��o</td>
					<td class='titulo_campo'>Data Movimenta��o</td>
				</tr>
				<tr>
					<td><?=$linDadosCliente[DescricaoLocalCobranca]?></td>
					<td style='text-align:right;'><?=$IdCaixaMovimentacao?></td>
					<td style='text-align:right;'><?=$linDadosCliente[DataMovimentacao]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' colspan='2'>Referente:</td>
					<td class='titulo_campo'>(=) Valor documento</td>
				</tr>
				<tr>
					<td rowspan='9' valign='top' colspan='2'>
						<table cellspacing='0' id='tabela_demonstrativo' border="0">
							<tr>
								<td colspan="4" style="font-size: 7pt">Informa��es dos Titulos</td>
							</tr>
							<tr>
								<th style='text-align:left;'>Id</th>
								<th style='text-align:left;'>Numero Documento</th>
								<th style='text-align:left;'>Sacado</th>
								<th style='text-align:right;'>Valor Lan�amento</th>
							</tr>
							<?
								$TotalValorFormaPagamento = 0;
								$TotalValorTotal = 0;
								
								$sql3 = "SELECT
											IdContaReceber
										FROM
											CaixaMovimentacaoItem
										WHERE
										    IdLoja   = $IdLoja AND
											IdCaixa  = $IdCaixa AND
											IdCaixaMovimentacao = $IdCaixaMovimentacao";
								$resContaReceber = mysql_query($sql3,$con);
								while($linContaReceber = mysql_fetch_array($resContaReceber)){
									$sql12 = "SELECT
													ContaReceber.IdContaReceber,
													ContaReceber.NumeroDocumento,
													ContaReceber.ValorLancamento,
													Pessoa.Nome
												FROM
													ContaReceber,
													Pessoa
												WHERE
													ContaReceber.IdContaReceber = $linContaReceber[IdContaReceber] AND
													Pessoa.IdPessoa = ContaReceber.IdPessoa";
									$resContaReceberDados = mysql_query($sql12,$con);
									$linContaReceberDados = mysql_fetch_array($resContaReceberDados);
									
									echo "<tr>
												<th style='text-align:left;'>$linContaReceberDados[IdContaReceber]</th>
												<th style='text-align:left;'>$linContaReceberDados[NumeroDocumento]</th>
												<th style='text-align:left;'>$linContaReceberDados[Nome]</th>
												<th style='text-align:right;'>".number_format($linContaReceberDados[ValorLancamento],2,',','.')."</th>
											</tr>";
											
									$TotalValorTotal 			+= 	number_format($linContaReceberDados[ValorLancamento],2,',','.');
									
								}
								
								$TotalValorFormaPagamento 	= number_format($TotalValorFormaPagamento,2,',','');
								$TotalValorTotal 			= number_format($TotalValorTotal,2,',','');

								echo "
									<tr style='border: solid 1px #AAA'>
										<td style='text-align:left;border-top: solid 1px #AAA'>Total</td>	
										<td style='text-align:right;border-top: solid 1px #AAA'></td>	
										<td style='text-align:right;border-top: solid 1px #AAA'></td>	
										<td style='text-align:right;border-top: solid 1px #AAA'>$TotalValorTotal</td>
									</tr>
								";
								
							?>
						</table>
						<table cellspacing='0' id='tabela_demonstrativo'>
							<tr>
								<td colspan="4" style="font-size: 7pt">Informa��es do Estorno</td>
							</tr>
							<tr>
								<th style='text-align:left;'>Forma de Pagamento</th>
								<th style='text-align:center;'>Quant. Parcelas</th>
								<th style='text-align:right;'>Valor Parcela</th>
								<th style='text-align:right;'>Valor Total</th>
							</tr>
							<?
								$TotalValorFormaPagamento = 0;
								$TotalValorTotal = 0;
								
								$sql4 = "SELECT
										  	IdLoja,
										  	IdCaixa,
										  	IdCaixaMovimentacao,
										  	IdFormaPagamento,
										  	ValorFormaPagamento,
										  	QtdParcelas,
										  	ValorParcela,
										  	ValorJuros,
										  	ValorTotal
										 FROM 
											CaixaMovimentacaoFormaPagamento
										 WHERE
										    IdLoja = $IdLoja AND
											IdCaixaMovimentacao = '$IdCaixaMovimentacao' AND
											IdCaixa = '$IdCaixa' AND
											ValorFormaPagamento != '0.00' AND
											ValorTotal != '0.00'";
								$resFormaPagamento = mysql_query($sql4,$con);
								while($linPagamentoFormaPagamento = mysql_fetch_array($resFormaPagamento)){
									$sql12 = "SELECT
												  IdLoja,
												  IdFormaPagamento,
												  DescricaoFormaPagamento,
												  LoginCriacao,
												  DataCriacao,
												  LoginAlteracao,
												  DataAlteracao
												FROM 
												  FormaPagamento
												WHERE
												  IdFormaPagamento = $linPagamentoFormaPagamento[IdFormaPagamento]";
									$ResDescricaoFormaPagamento = mysql_query($sql12,$con);
									$linDescricaoFormaPagamento = mysql_fetch_array($ResDescricaoFormaPagamento);
									
									echo "<tr>
												<th style='text-align:left;'>$linDescricaoFormaPagamento[DescricaoFormaPagamento]</th>
												<th style='text-align:center;'>$linPagamentoFormaPagamento[QtdParcelas]</th>
												<th style='text-align:right;'>$linPagamentoFormaPagamento[ValorFormaPagamento]</th>
												<th style='text-align:right;'>$linPagamentoFormaPagamento[ValorTotal]</th>
											</tr>";
											
									$TotalValorFormaPagamento 	+= 	$linPagamentoFormaPagamento[ValorFormaPagamento];
									$TotalValorTotal 			+= 	$linPagamentoFormaPagamento[ValorTotal];
									
								}
								
								$TotalValorFormaPagamento 	= number_format($TotalValorFormaPagamento,2,',','');
								$TotalValorTotal 			= number_format($TotalValorTotal,2,',','');

								echo "
									<tr>
										<td style='text-align:left;border-top: 1px solid #AAA'>Total</td>	
										<td style='text-align:right;border-top: 1px solid #AAA'></td>
										<td style='text-align:right;border-top: 1px solid #AAA'>$TotalValorFormaPagamento</td>	
										<td style='text-align:right;border-top: 1px solid #AAA'>$TotalValorTotal</td>
									</tr>
								";
								
							?>
						</table>
					</td>
					<td style='text-align:right;'><?=$linDadosCliente[ValorTotal]?></td>
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
					<td class='titulo_campo'>(=) Valor Estornado</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorTotal]?></td>
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
				<table cellspacing='0' class='via1' style='margin-top:3px;'>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' colspan='2'>Via Cliente</td>
					<td class='titulo_campo' style='width:111px;'>N�</td>
				</tr>
				<tr>
					<td colspan='2'><h1>RECIBO ESTORNO</h1></td>
					<td style='text-align:center;'><h1><?=$IdCaixa."".$IdCaixaMovimentacao?></h1></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' colspan="3">Estornado por</td>
				</tr>
				<tr>
					<? 
						if(getCodigoInterno(3,225) == '1'){
							$IdPessoaImpressao = " [$linDadosCliente[IdPessoa]]";
						}else{
							$IdPessoaImpressao = "";
						}
					?>
					<td colspan="3"><?=$linLoginRecebimento[LoginAbertura]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' style="width: 360px">Local de Estorno</td>
					<td class='titulo_campo'>C�digo da Movimenta��o</td>
					<td class='titulo_campo'>Data Movimenta��o</td>
				</tr>
				<tr>
					<td><?=$linDadosCliente[DescricaoLocalCobranca]?></td>
					<td style='text-align:right;'><?=$IdCaixaMovimentacao?></td>
					<td style='text-align:right;'><?=$linDadosCliente[DataMovimentacao]?></td>
				</tr>
				<tr style='font-size:9px;'>
					<td class='titulo_campo' colspan='2'>Referente:</td>
					<td class='titulo_campo'>(=) Valor documento</td>
				</tr>
				<tr>
					<td rowspan='9' valign='top' colspan='2'>
						<table cellspacing='0' id='tabela_demonstrativo' border="0">
							<tr>
								<td colspan="4" style="font-size: 7pt">Informa��es dos Titulos</td>
							</tr>
							<tr>
								<th style='text-align:left;'>Id</th>
								<th style='text-align:left;'>Numero Documento</th>
								<th style='text-align:left;'>Sacado</th>
								<th style='text-align:right;'>Valor Lan�amento</th>
							</tr>
							<?
								$TotalValorFormaPagamento = 0;
								$TotalValorTotal = 0;
								
								$sql3 = "SELECT
											IdContaReceber
										FROM
											CaixaMovimentacaoItem
										WHERE
										    IdLoja   = $IdLoja AND
											IdCaixa  = $IdCaixa AND
											IdCaixaMovimentacao = $IdCaixaMovimentacao";
								$resContaReceber = mysql_query($sql3,$con);
								while($linContaReceber = mysql_fetch_array($resContaReceber)){
									$sql12 = "SELECT
													ContaReceber.IdContaReceber,
													ContaReceber.NumeroDocumento,
													ContaReceber.ValorLancamento,
													Pessoa.Nome
												FROM
													ContaReceber,
													Pessoa
												WHERE
													ContaReceber.IdContaReceber = $linContaReceber[IdContaReceber] AND
													Pessoa.IdPessoa = ContaReceber.IdPessoa";
									$resContaReceberDados = mysql_query($sql12,$con);
									$linContaReceberDados = mysql_fetch_array($resContaReceberDados);
									
									echo "<tr>
												<th style='text-align:left;'>$linContaReceberDados[IdContaReceber]</th>
												<th style='text-align:left;'>$linContaReceberDados[NumeroDocumento]</th>
												<th style='text-align:left;'>$linContaReceberDados[Nome]</th>
												<th style='text-align:right;'>".number_format($linContaReceberDados[ValorLancamento],2,',','.')."</th>
											</tr>";
											
									$TotalValorTotal 			+= 	number_format($linContaReceberDados[ValorLancamento],2,',','.');
									
								}
								
								$TotalValorFormaPagamento 	= number_format($TotalValorFormaPagamento,2,',','');
								$TotalValorTotal 			= number_format($TotalValorTotal,2,',','');

								echo "
									<tr style='border: solid 1px #AAA'>
										<td style='text-align:left;border-top: solid 1px #AAA'>Total</td>	
										<td style='text-align:right;border-top: solid 1px #AAA'></td>	
										<td style='text-align:right;border-top: solid 1px #AAA'></td>	
										<td style='text-align:right;border-top: solid 1px #AAA'>$TotalValorTotal</td>
									</tr>
								";
								
							?>
						</table>
						<table cellspacing='0' id='tabela_demonstrativo'>
							<tr>
								<td colspan="4" style="font-size: 7pt">Informa��es do Estorno</td>
							</tr>
							<tr>
								<th style='text-align:left;'>Forma de Pagamento</th>
								<th style='text-align:center;'>Quant. Parcelas</th>
								<th style='text-align:right;'>Valor Parcela</th>
								<th style='text-align:right;'>Valor Total</th>
							</tr>
							<?
								$TotalValorFormaPagamento = 0;
								$TotalValorTotal = 0;
								
								$sql4 = "SELECT
										  	IdLoja,
										  	IdCaixa,
										  	IdCaixaMovimentacao,
										  	IdFormaPagamento,
										  	ValorFormaPagamento,
										  	QtdParcelas,
										  	ValorParcela,
										  	ValorJuros,
										  	ValorTotal
										 FROM 
											CaixaMovimentacaoFormaPagamento
										 WHERE
										    IdLoja = $IdLoja AND
											IdCaixaMovimentacao = '$IdCaixaMovimentacao' AND
											IdCaixa = '$IdCaixa' AND
											ValorFormaPagamento != '0.00' AND
											ValorTotal != '0.00'";
								$resFormaPagamento = mysql_query($sql4,$con);
								while($linPagamentoFormaPagamento = mysql_fetch_array($resFormaPagamento)){
									$sql12 = "SELECT
												  IdLoja,
												  IdFormaPagamento,
												  DescricaoFormaPagamento,
												  LoginCriacao,
												  DataCriacao,
												  LoginAlteracao,
												  DataAlteracao
												FROM 
												  FormaPagamento
												WHERE
												  IdFormaPagamento = $linPagamentoFormaPagamento[IdFormaPagamento]";
									$ResDescricaoFormaPagamento = mysql_query($sql12,$con);
									$linDescricaoFormaPagamento = mysql_fetch_array($ResDescricaoFormaPagamento);
									
									echo "<tr>
												<th style='text-align:left;'>$linDescricaoFormaPagamento[DescricaoFormaPagamento]</th>
												<th style='text-align:center;'>$linPagamentoFormaPagamento[QtdParcelas]</th>
												<th style='text-align:right;'>$linPagamentoFormaPagamento[ValorFormaPagamento]</th>
												<th style='text-align:right;'>$linPagamentoFormaPagamento[ValorTotal]</th>
											</tr>";
											
									$TotalValorFormaPagamento 	+= 	$linPagamentoFormaPagamento[ValorFormaPagamento];
									$TotalValorTotal 			+= 	$linPagamentoFormaPagamento[ValorTotal];
									
								}
								
								$TotalValorFormaPagamento 	= number_format($TotalValorFormaPagamento,2,',','');
								$TotalValorTotal 			= number_format($TotalValorTotal,2,',','');

								echo "
									<tr>
										<td style='text-align:left;border-top: 1px solid #AAA'>Total</td>	
										<td style='text-align:right;border-top: 1px solid #AAA'></td>
										<td style='text-align:right;border-top: 1px solid #AAA'>$TotalValorFormaPagamento</td>	
										<td style='text-align:right;border-top: 1px solid #AAA'>$TotalValorTotal</td>
									</tr>
								";
								
							?>
						</table>
					</td>
					<td style='text-align:right;'><?=$linDadosCliente[ValorTotal]?></td>
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
					<td class='titulo_campo'>(=) Valor Estornado</td>
				</tr>
				<tr>
					<td style='text-align:right;'><?=$linDadosCliente[ValorTotal]?></td>
				</tr>
				<tr>
					<td class='titulo_campo' colspan='2'>&nbsp;</td>
					<td class='titulo_campo' rowspan='2' style='text-align:center; border-bottom:1px solid #000;'><? CodigoBarras($linDadosCliente[CodigoDeBarras],40); ?></td>
				</tr>
				<tr>
					<td style='text-align:center; border-bottom:1px solid #000; padding-bottom:6px;' colspan='2'>
						<div style="height: 50px">
							<p style='margin:4px 0;'></p>
						</div>
					</td>
				</tr>
			</table>
			<div style='font-size:9px; line-height:16px; border-bottom:1px dashed #000;'>Corte na linha pontilhada</div>
		</div>
	</body>
</html>