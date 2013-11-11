<?
		$sql = "select
					Tipo,
					Codigo,
					Descricao,
					if(ExibirReferencia != 2, Referencia, '-') Referencia,
					Valor,
					ValorDespesas
				from
					Demonstrativo
				where
					IdLoja = $IdLoja and
					IdContaReceber = $IdContaReceber
				order by
					Tipo,
					Codigo,
					IdLancamentoFinanceiro";
		$res = mysql_query($sql,$con);
		$qtd = mysql_num_rows($res);
?>
<div id='quadro' <? if($qtd > 10){ echo "style='height: auto'"; } ?>>
	<style type="text/css">
		.ct1{
			border-left: solid 1px black;
			border-top: solid 1px black;
			border-bottom: none;
			text-align: left;
			height: -10px;
			font-size: 10px;
			text-indent: 3px;
		}
		.cp1{
			border-left: solid 1px black;
			text-align: left;
			text-indent: 3px;
		}
		.ctf{
			border-left: solid 1px black;
			border-top: solid 1px black;
			border-right: solid 1px black;
			border-bottom: none;
			font-size: 10px;
			text-indent: 3px;
		}
		.cpf{
			border-right: solid 1px black;
			border-left: solid 1px black;
			border-bottom: none;
			text-indent: 3px;
		}
		.diferenca{
			margin-top: -20px;
		}
		#quadrodemontrativo{
			width: 662px;
			border: solid 1px black;
			border-top: none;
			height: 260px;
			margin-top: 0px;
		}
		#mensagemservico{
			margin-top: 7px;
		}
	</style>
	
	<table border='0' cellspacing='0' cellpadding='0'>
		<tr>
			<td colspan='5' style='border-bottom: none'>
				<table cellspacing='0' cellpadding='0' border='0'>
					<tr>
						<td class='ct1' style='border-bottom: none' valign='top' width='267' colspan=''>Cedente</td>
						<td class='ct1' style='border-bottom: none' valign='top' width='147'>Agência/Codigo Cendente</td>
						<td class='ct1' style='border-bottom: none' valign='top' width='122'>Data de Emissão</td>
						<td class='ctf' style='border-bottom: none' valign='top' width='122'>Vencimento</td>
					</tr>
					<tr>
						<td class='cp1' valign='top' style='border-bottom: none'><?=$dadosboleto["cedente"]?></td>
						<td class='cp1' valign='top' style='text-align: center;border-bottom: none'><?=$dadosboleto["agencia_codigo"]?></td>
						<td class='cp1' valign='top' style='text-align: center;border-bottom: none'><?=$linContaReceber[DataLancamento]?></td>
						<td class='cpf' valign='top' style='text-align: center;border-bottom: none'><span style='font-size: 13px'><?=$dadosboleto["data_vencimento"]?></span></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan='5' style='border-bottom: none'>
				<table cellspacing='0' cellpadding='0' border='0'>
					<tr>
						<td class='ct1' valign='top' style='border-bottom: none' width='416' height='10'>Sacado</td>
						<td class='ct1' valign='top' style='border-bottom: none' width='122' height='10' colspan='2'>Número do Documento</td>
						<td class='ctf' valign='top' style='border-bottom: none' width='122' height='10'>Nosso Número</td>
					</tr>				
					<tr>
						<td class='cp1' valign='top' style='border-bottom: none'><span style='font-size: 13px'><?=$dadosboleto["nome_sacado"]?></span></td>
						<td class='cp1' valign='top' height='10' colspan='2' style='text-align: center;border-bottom: none'><?=$dadosboleto["numero_documento"]?></td>
						<td class='cpf' valign='top' style='text-align: center;border-bottom: none'><?=$dadosboleto["nosso_numero"]?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan='5'>
				<table cellspacing='0' cellpadding='0' border='0'>
					<tr>
						<td class='ct1' style='border-bottom: none' valign='top' width='75'>Espécie</td>
						<td class='ct1' style='border-bottom: none' valign='top' width='131'>Quantidade Moeda</td>
						<td class='ct1' style='border-bottom: none' valign='top' width='131'>Valor</td>
						<td class='ct1' style='border-bottom: none' valign='top' width='131'>Valor Documento</td>
						<td class='ctf' style='border-bottom: none' valign='top' width='194'>Descontos / Abatimentos</td>
					</tr>
					<tr>
						<td class='cp1' valign='top' style='text-align: center;border-bottom: none'><span style="margin-left: 23px;font-size: 13px"><?=$dadosboleto["especie"]?></span></td>
						<td class='cp1' valign='top' style='text-align: center;border-bottom: none'>&nbsp;</td>
						<td class='cp1' valign='top' style='text-align: center;border-bottom: none'>&nbsp;</td>
						<td class='cp1' valign='top' style='text-align: center;border-bottom: none'><?=$dadosboleto["valor_boleto"]?></td>
						<td class='cpf' valign='top' style='text-align: center;border-bottom: none'><?=number_format($linContaReceberVencimento[ValorDesconto], 2, ',', '')?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<div style='margin-top: -19px'>
		<span style='margin-left: 203px;'>X</span>
		<span style='margin-left: 122px;'>=</span>
	</div>
	<div style='margin-top: 05px'></div>
	<div id='quadrodemontrativo'>
		<span style='font-size: 10px;margin-left: 6px; width: 656px'>Demonstrativo</span>
		<table border='0' style='width: 659px'>
			<tr>
				<td style='width: 25px; border-bottom: none'>Tipo</td>
				<td style='width: 39px; border-bottom: none'>Cod.</td>
				<td style='width: 295px; border-bottom: none'>Descrição</td>
				<td style='width: 20px; border-bottom: none'>Qtd.</td>
				<td style='width: 3px; border-bottom: none'>&nbsp;</td>
				<td style='width: 74px; border-bottom: none; text-align: right'>Valor Un.(<?=getParametroSistema(5,1)?>)</td>
				<td style='width: 148px; border-bottom: none; text-align: center'>Referência</td>
				<td style='width: 61px; border-bottom: none;text-align: right'>Valor (<?=getParametroSistema(5,1)?>)</td>
			</tr>
			<?
				$total		= 0;
				$cont		= 0;
				$contador 	= 0;
				$MsgAuxiliarCobranca = array();
				$sql = "select
						substr(Demonstrativo.Descricao,1,56) Descricao,
						Demonstrativo.Valor,
						Demonstrativo.Referencia,
						Demonstrativo.IdServico,
						Demonstrativo.Tipo,
						Demonstrativo.Codigo,
						Demonstrativo.ValorDespesas
					from
						LancamentoFinanceiroContaReceber left join (LancamentoFinanceiro,Demonstrativo) on(
							LancamentoFinanceiroContaReceber.IdLoja = $IdLoja and
							LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
							Demonstrativo.IdLoja = LancamentoFinanceiro.IdLoja
						)
					where
						LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and
						LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
						Demonstrativo.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro
						Limit 0,6";
				$res = mysql_query($sql,$con);
				$contador = mysql_num_rows($res);
				if(mysql_num_rows($res) > 0){
					while($linDesc = mysql_fetch_array($res)){
						if($contador <= 4 && $cont < 5){
							echo "<tr style='text-align: right'>
									<td style='border-bottom:none;text-align: left'>$linDesc[Tipo]</td>
									<td style='border-bottom:none;text-align:left'>$linDesc[Codigo]</td>
									<td style='border-bottom: none;text-align:left'>".$linDesc[Descricao]."</td>
									<td style='border-bottom: none; text-align: center'><span style='margin-left: 7px'>1</span></td>
									<td style='border-bottom: none'>X</td>
									<td style='text-align: center; border-bottom: none; text-align: right'>".number_format($linDesc[Valor],2,',','')."</td>
									<td style='border-bottom: none; text-align: center'>$linDesc[Referencia]</span></td>
									<td style='text-align: right; border-bottom: none'>".number_format($linDesc[Valor],2,',','')."</td>
								</tr>";
							$total += $linDesc[Valor];
							if($linDesc[IdServico] != '' && $linDesc[IdServico] != null){
								$sqlMensagem = "select
													Servico.MsgAuxiliarCobranca
												from
													Servico
												where
													Servico.IdServico = $linDesc[IdServico]";		
								$resMensagem = mysql_query($sqlMensagem,$con);
								$linMensagem = mysql_fetch_array($resMensagem);
								
								if($linMensagem[MsgAuxiliarCobranca] != ''){					
									if(!in_array(trim($linMensagem[MsgAuxiliarCobranca]), $MsgAuxiliarCobranca)){						
										$MsgAuxiliarCobranca[$cont] = trim($linMensagem[MsgAuxiliarCobranca]);
										
										$QtdAst = count($MsgAuxiliarCobranca);
										$QtdAst++;
										
										$MsgAuxiliarCobranca[$linMensagem[MsgAuxiliarCobranca]] = str_pad("",$QtdAst, "*").$MsgAuxiliarCobranca[$linMensagem[MsgAuxiliarCobranca]];
										$Descricao .= str_pad("",$QtdAst, "*");
									}
								}
							}
						}else{
							if($cont < 3){
								echo "<tr>
										<td style='border-bottom:none;text-align:left'>$linDesc[Tipo]</td>
										<td style='border-bottom:none;text-align:left'>$linDesc[Codigo]</td>
										<td style='border-bottom: none;text-align:left'>".$linDesc[Descricao]."</td>
										<td style='border-bottom: none;'><span style='margin-left: 10px'>1</span></td>
										<td style='border-bottom: none'>X</td>
										<td style='text-align: center; border-bottom: none; text-align: right'>".number_format($linDesc[Valor],2,',','')."</td>
										<td style='border-bottom: none; text-align: center'>$linDesc[Referencia]</td>
										<td style='text-align: right; border-bottom: none'>".number_format($linDesc[Valor],2,',','')."</td>
									</tr>";
								$total += $linDesc[Valor];
								if($linDesc[IdServico] != '' && $linDesc[IdServico] != null){
									$sqlMensagem = "select
														Servico.MsgAuxiliarCobranca
													from
														Servico
													where
														Servico.IdServico = $linDesc[IdServico]";		
									$resMensagem = mysql_query($sqlMensagem,$con);
									$linMensagem = mysql_fetch_array($resMensagem);
									
									if($linMensagem[MsgAuxiliarCobranca] != ''){					
										if(!in_array(trim($linMensagem[MsgAuxiliarCobranca]), $MsgAuxiliarCobranca)){						
											$MsgAuxiliarCobranca[$cont] = trim($linMensagem[MsgAuxiliarCobranca]);
											
											$QtdAst = count($MsgAuxiliarCobranca);
											$QtdAst++;
											
											$MsgAuxiliarCobranca[$linMensagem[MsgAuxiliarCobranca]] = str_pad("",$QtdAst, "*").$MsgAuxiliarCobranca[$linMensagem[MsgAuxiliarCobranca]];
											$Descricao .= str_pad("",$QtdAst, "*");
										}
									}
								}
							}
						}
						$cont++;
					}
					$contador = $cont;
				}
			?>
			<tr>
				<td colspan='7' style="border-bottom: none"></td>
				<td style='border-top: solid 1px black; text-align: right; border-bottom: none' height='10'>
					<?
						if($contador > 4){
							echo ("R$ ".number_format($total,2,',','')."*");
						}else{
							echo ("R$ ".number_format($total,2,',',''));
						}
					?>
				</td>
			</tr>
			<tr style='margin-top: -10px'>
				<td colspan='8' style="border-bottom: none; margin-top: -8px" valign='top'>
					<?
						if($contador > 4){
							echo ("<span>* Valor Parcial, A quantidade de lançamentos excede o limite máximo no demonstrativo. Para a lista completa acesse a central do assinante (".$UrlSistema = getParametroSistema(6,3).'/central'.").</span>");
						}
					?>
				</td>
			</tr>
		</table>
		<div id='mensagemservico'>
			<?
				for($iiii = 0; $iiii < count($MsgAuxiliarCobranca); $iiii++){
					echo("<span style='margin-left: 10px'>$MsgAuxiliarCobranca[$iiii]</span>");
				}
			?>
		</div>
	</div>
	<div style='border-top: solid 1px black; height: 63px; margin-top: -55px;width: 663px'>
		<span style='margin-left: 5px; margin-top: 5px'>Observações</span><br/>
		<?
			echo ("<span style='margin-left: 05px'>".substr($CobrancaParametro[ObservacoesDemonstrativoLinha1],0,120)."</span><br/>");
			echo ("<span style='margin-left: 05px'>".substr($CobrancaParametro[ObservacoesDemonstrativoLinha2],0,120)."</span><br/>");
		?>
	</div>
</div>
<?	
	if($cont > 21){
		echo "<p style='page-break-after: always' /><div align=center>";
	}
?>