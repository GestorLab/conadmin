<?		
	$IdLocalCobranca	= "";

    $sql = "select
				Tipo,
				Codigo,
				Descricao,
				if(ExibirReferencia != 2, Referencia, '-') Referencia,
				Valor,
				ValorDespesas,
				IdLocalCobranca
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
<div style='margin-top:-12px'>
<div id='quadro' <? if($qtd > 10){ echo "style='height: auto'"; } ?>>
<p style='margin-top:-5px'><B>Demonstrativo</B></p>
<table cellspacing=0>
	<tr>
		<th style='text-align:left; width: 30px'>Tipo</th>
		<th style='text-align:left; width: 55px'>Cod.</th>
		<th style='text-align:left;'>Descrição</th>
		<th style='width: 130px'>Referência</th>
		<th style='width: 60px'>Valor (<?=getParametroSistema(5,1)?>)</th>
	</tr>
	<?
		$valorTotal					= 0;
		$i							= 0;
		$DadosLancamento[$i][Valor]	= 0;
		$cont						= 1;
		$PosMSG						= 0;
		$MsgAuxiliarCobranca		= array();

		while($lin = mysql_fetch_array($res)){
		
			$valorTotal += $lin[Valor];

			$DadosLancamento[$i][Tipo]			= $lin[Tipo];
			$DadosLancamento[$i][Cod]			= $lin[Codigo];
			$DadosLancamento[$i][Descricao]		= $lin[Descricao];
			$DadosLancamento[$i][Referencia]	= $lin[Referencia];
			$DadosLancamento[$i][Valor]			= $lin[Valor];
			$IdLocalCobranca 					= $lin[IdLocalCobranca];

			switch($DadosLancamento[$i][Tipo]){
				case 'CO':
					$sqlMSG = "select
									Servico.MsgAuxiliarCobranca
								from
									Contrato,
									Servico
								where
									Contrato.IdLoja = $IdLoja and
									Contrato.IdLoja = Servico.IdLoja and
									Contrato.IdContrato = ".$DadosLancamento[$i][Cod]." and
									Contrato.IdServico = Servico.IdServico";
					$resMSG = mysql_query($sqlMSG,$con);
					$linMSG = mysql_fetch_array($resMSG);

					if(!in_array($linMSG[MsgAuxiliarCobranca], $MsgAuxiliarCobranca)){
						$MsgAuxiliarCobranca[$PosMSG] = $linMSG[MsgAuxiliarCobranca];
						$PosMSG++;
					}
					break;

				case 'OS':
					$sqlMSG = "select
									Servico.MsgAuxiliarCobranca
								from
									OrdemServico,
									Servico
								where
									OrdemServico.IdLoja = $IdLoja and
									OrdemServico.IdLoja = Servico.IdLoja and
									OrdemServico.IdContrato = ".$DadosLancamento[$i][Cod]." and
									OrdemServico.IdServico = Servico.IdServico";
					$resMSG = mysql_query($sqlMSG,$con);
					$linMSG = mysql_fetch_array($resMSG);

					if(!in_array($linMSG[MsgAuxiliarCobranca], $MsgAuxiliarCobranca)){	
						$MsgAuxiliarCobranca[$PosMSG] = $linMSG[MsgAuxiliarCobranca];
						$PosMSG++;
					}
					break;
			}
			
			$i++;
		}

		for($i = 0; $i <=count($DadosLancamento); $i++){
			if($DadosLancamento[$i][Tipo] != ''){
				$Tipo		= $DadosLancamento[$i][Tipo];
				$Cod		= $DadosLancamento[$i][Cod];
				$Descricao	= $DadosLancamento[$i][Descricao];
				$Referencia	= $DadosLancamento[$i][Referencia];
				$Valor		= number_format($DadosLancamento[$i][Valor],2,',','');
				$Descricao = str_replace("\n", "<br>", $Descricao);

				echo "
					<tr>
						<td>$Tipo</td>
						<td>$Cod</td>
						<td>$Descricao</td>
						<td style='text-align:center'>$Referencia</td>
						<td style='text-align:right'>$Valor</td>
					</tr>
				";
				$cont++;
			}
		}

		$sql = "select ValorDespesas from ContaReceber where IdLoja=$IdLoja and IdContaReceber=$IdContaReceber";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		if($lin[ValorDespesas] > 0){
			$valorTotal += $lin[ValorDespesas];

			$lin[ValorDespesas] = number_format($lin[ValorDespesas],2,',','');
			echo "
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>	
					<td>Despesas boleto</td>
					<td>&nbsp;</td>
					<td style='text-align:right'>$lin[ValorDespesas]</td>
				</tr>
			";
			$cont++;
		}
		$valorTotal = number_format($valorTotal,2,',','');
		$cont++;
	?>	
	<tr>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>Total</th>
		<th style='text-align:right'><?echo getParametroSistema(5,1).' '.$valorTotal?></th>
	</tr>
</table>

<?
	$MsgAuxiliarCobranca = @array_unique($MsgAuxiliarCobranca);

	for($i=0; $i<$PosMSG; $i++){
		if(trim($MsgAuxiliarCobranca[$i]) != ""){
			echo trim($MsgAuxiliarCobranca[$i])."<br>";
		}
	}
	
	$sqlMsgDemonstrativo = "select
							  MsgDemonstrativo 
							from
							  LocalCobranca
							where
							  IdLocalCobranca = $IdLocalCobranca and
							  IdLoja = $IdLoja ";
	$resMsgDemonstrativo = mysql_query($sqlMsgDemonstrativo,$con);	
	$linMsgDemonstrativo = mysql_fetch_array($resMsgDemonstrativo);
	if($linMsgDemonstrativo[MsgDemonstrativo]){
		echo $linMsgDemonstrativo[MsgDemonstrativo]."<br/>";
	}
	
	echo "<br>";
	
	$sql = "select
			ContaReceber.IdContaReceber,
			ValorContaReceber,
			ValorMulta,
			ValorJuros,
			ValorTaxaReImpressaoBoleto,
			ValorOutrasDespesas,
			ValorDesconto,
			ValorContaReceber		
		from
			ContaReceber,
			ContaReceberVencimento
		where
			ContaReceber.IdLoja = $IdLoja and
			ContaReceber.IdLoja = ContaReceberVencimento.IdLoja and
			ContaReceber.IdContaReceber = $IdContaReceber and
			ContaReceber.IdContaReceber = ContaReceberVencimento.IdContaReceber and
			ContaReceber.DataVencimento = ContaReceberVencimento.DataVencimento";
	$res = mysql_query($sql,$con);	
	$lin = mysql_fetch_array($res);
		
	$lin["valor_desconto"]			= $lin[ValorDesconto];
	$lin["valor_outras_deducoes"]	= '0.00';
	$lin["valor_mora_multa"]		= $lin[ValorMulta] + $lin[ValorJuros];		
	$lin["valor_outros_acrescimos"]	= $lin[ValorTaxaReImpressaoBoleto] + $lin[ValorOutrasDespesas];
	$lin["valor_cobrado"]			= ((($lin["ValorContaReceber"] + $lin["valor_mora_multa"] + $lin["valor_outros_acrescimos"])-$lin["valor_desconto"])-$lin["valor_outras_deducoes"]);	
	$lin["valor_cobrado"] 			= number_format($lin["valor_cobrado"], 2, ',', '');
	$lin["valor_outros_acrescimos"] = number_format($lin["valor_outros_acrescimos"], 2, ',', '');
	$lin["valor_mora_multa"] 		= number_format($lin["valor_mora_multa"], 2, ',', '');
	$lin["valor_outras_deducoes"]	= number_format($lin["valor_outras_deducoes"], 2, ',', '');
	$lin["valor_documento"]			= $lin["ValorContaReceber"];
	$lin["valor_documento"]			= number_format($lin["valor_documento"], 2, ',', '');
	$lin["valor_desconto"]			= number_format($lin["valor_desconto"], 2, ',', '');
	
	if(str_replace(",",".",$lin["valor_desconto"]) > 0 || str_replace(",",".",$lin["valor_mora_multa"]) > 0 || str_replace(",",".",$lin["valor_outras_deducoes"]) > 0 || str_replace(",",".",$lin["valor_outros_acrescimos"]) > 0){
		$sql = "select
					min(DataVencimento) DataVencimento
				from
					ContaReceberVencimento
				where
					IdLoja = $IdLoja and
					IdContaReceber = $lin[IdContaReceber]";
		$res2 = mysql_query($sql,$con);	
		$lin2 = mysql_fetch_array($res2);
		$lin2[DataVencimento] = dataConv($lin2[DataVencimento], "Y-m-d", "d/m/Y");
		echo"
		<table cellpadding='0' cellspacing='0'>
			<tr>
				<th>(-) Desconto / Abatimentos</th>
				<th>(-) Outras deduções</th>
				<th>(+) Mora / Multa</th>
				<th>(+) Outros acréscimos</th>
				<th>(=) Valor cobrado</th>
			</tr>
			<tr>
				<th>$lin[valor_desconto]</th>
				<th>$lin[valor_outras_deducoes]</th>
				<th>$lin[valor_mora_multa]</th>
				<th>$lin[valor_outros_acrescimos]</th>
				<th style='text-align:right'>".getParametroSistema(5,1).' '.$lin[valor_cobrado]."</th>
			</tr>
		</table>
		<p style='margin-top: -15px; font-size: 11px'>Vencimento original: $lin2[DataVencimento]</p><br />";		
	}
	
	$sql = "
			select 
				LocalCobranca.AvisoFaturaAtraso 
			from 
				ContaReceber,
				LocalCobranca
			where 
				ContaReceber.IdLoja = $IdLoja and 
				ContaReceber.IdLoja = LocalCobranca.IdLoja and
				ContaReceber.IdContaReceber = $IdContaReceber and
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
	$res = mysql_query($sql,$con);	
	$linContaReceber = mysql_fetch_array($res);	
	
	if($linContaReceber[AvisoFaturaAtraso] == 1 && $_GET['ctt'] != 'pagamento_online_demonstrativo.php'){	
		if($CobrancaParametro[Reaviso] != 2){
			$i = 0;
			$valorTotal = 0;
			$sql = "select
					ContaReceberDados.IdContaReceber,
					ContaReceberDados.NumeroDocumento,
					ContaReceberDados.ValorFinal Valor,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.DataLancamento
				from
					ContaReceberDados,
					(select
						IdPessoa
					from
						ContaReceber
					where
						IdLoja = $IdLoja and
						IdContaReceber = $IdContaReceber) Pessoa
				where
					ContaReceberDados.IdLoja = $IdLoja and
					ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
					ContaReceberDados.IdContaReceber != $IdContaReceber and
					ContaReceberDados.IdStatus = 1 and
					ContaReceberDados.DataVencimento < curdate()
				order by				
					ContaReceberDados.NumeroDocumento";
			$res = @mysql_query($sql,$con);
			while($lin = @mysql_fetch_array($res)){
				if($i == 0){
					echo "
						<p style='font-size: 14px;'><i><B>Fatura(s) em atraso*</B></i></p>
						<table cellspacing=0 style='margin-bottom: 0'>
							<tr>
								<th style='text-align:left;'>Data Lançamento</th>
								<th>Conta Receber</th>
								<th>Número Documento</th>
								<th>Vencimento</th>
								<th>Valor (".getParametroSistema(5,1).")</th>
							</tr>
						";
						$cont++;
				}
		
				$valorTotal += $lin[Valor];
				if($i < 4){
					$lin[Valor] = number_format($lin[Valor],2,',','');
			
					$lin[DataVencimento] = dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
					$lin[DataLancamento] = dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
			
					echo "
						<tr>
							<td>$lin[DataLancamento]</td>
							<td style='text-align:center'>$lin[IdContaReceber]</td>
							<td style='text-align:center'>$lin[NumeroDocumento]</td>
							<td style='text-align:center'>$lin[DataVencimento]</td>
							<td style='text-align:right'>$lin[Valor]</td>
						</tr>
					";
					$cont++;
				}
				$i++;				
			}
			if($i > 0){			
				$valorTotal = number_format($valorTotal,2,',','');
				echo "	
					<tr>							
						<th colspan='5'>Total em atraso: ".getParametroSistema(5,1).' '.$valorTotal."</th>
					</tr>
				</table>*Caso o pagamento já tenha sido efetuado, favor desconsiderar este aviso. *Relação parcial de fatura(s) em atraso.";
				$cont++;
				$cont++;
			}			
		}
	}
?>
</div>
<?	
	if($cont > 21){
		echo "<p style='page-break-after: always' /><div align=center>";
	}
	
?>

</div>