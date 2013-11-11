<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>
<TITLE><?=getParametroSistema(4,1)?></TITLE>
<link REL="SHORTCUT ICON" HREF="../../../../img/estrutura_sistema/favicon.ico">
<style type=text/css>
body{
	FONT: 11px Arial;
}
#cabecalho, #quadro{
	width:665px; 
}
#cabecalho{
	height: 45px; 
	padding: 5px;
	text-align: right;
	border-bottom: 1px #000 solid;
}
#quadro{
	margin:auto;
}
</style> 
</head>

<BODY>
<DIV id='quadro'>
	<?
		$IdNotaFiscalLayout = 1;

		include("../../local_cobranca/cabecalho_html.php");

		// Dados Tomador do Servico
		$sqlTomador = "select
							Pessoa.TipoPessoa,
							Pessoa.RazaoSocial, 
							Pessoa.Nome,
							Pessoa.CPF_CNPJ, 
							Pessoa.RG_IE,
							PessoaEndereco.Endereco, 
							PessoaEndereco.Numero, 
							PessoaEndereco.Complemento, 
							PessoaEndereco.Bairro,
							PessoaEndereco.CEP,
							Cidade.NomeCidade, 
							Estado.SiglaEstado
						from
							ContaReceber,
							Pessoa,
							PessoaEndereco,
							Estado,
							Cidade
						where
							ContaReceber.IdLoja = $IdLoja and
							ContaReceber.IdContaReceber = $IdContaReceber and
							ContaReceber.IdPessoa = Pessoa.IdPessoa and
							Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
							ContaReceber.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
							PessoaEndereco.IdPais = Estado.IdPais and
							PessoaEndereco.IdPais = Cidade.IdPais and
							PessoaEndereco.IdEstado = Estado.IdEstado and
							PessoaEndereco.IdEstado = Cidade.IdEstado and
							PessoaEndereco.IdCidade = Cidade.IdCidade";
		$resTomador = mysql_query($sqlTomador,$con);
		$Tomador = mysql_fetch_array($resTomador);
		
		if($Tomador[Numero] != ""){
			$Tomador[Numero] = ", ".$Tomador[Numero];
		}else{
			$Tomador[Numero] = $Tomador[Numero];
		}
		
		if($Tomador[Complemento] != ""){
			$Tomador[Complemento] = " - ".$Tomador[Complemento];
		}else{
			$Tomador[Complemento] = $Tomador[Complemento];
		}
		
		$Tomador[EnderecoFull] = $Tomador[Endereco].$Tomador[Numero].$Tomador[Complemento]."\n".$Tomador[Bairro]." - ".$Tomador[NomeCidade]."-".$Tomador[SiglaEstado]." - CEP: ".$Tomador[CEP];

		if($Tomador[TipoPessoa] == 1){
			$Tomador[Nome]			= $Tomador[RazaoSocial];
			$Tomador[CPF_CNPJFull]	= "CNPJ: ";
			$Tomador[RG_IEFull]		= " - IE: ".$Tomador[RG_IE];
		}else{
			$Tomador[CPF_CNPJFull]	= "CPF: ";
		}
		$Tomador[CPF_CNPJFull] .= $Tomador[CPF_CNPJ];

		// Dados da Nota Fiscal
		$sqlNF = "select
					NotaFiscal.IdNotaFiscal,
					NotaFiscal.Modelo,
					NotaFiscal.Serie,
					NotaFiscal.DataEmissao,
					NotaFiscal.ValorBaseCalculoICMS,
					NotaFiscal.ValorICMS,
					NotaFiscal.ValorOutros,
					NotaFiscal.ValorTotal,
					NotaFiscal.ObsVisivel,
					NotaFiscal.CodigoAutenticacaoDocumento,
					NotaFiscal.PeriodoApuracao
				from
					NotaFiscal
				where
					NotaFiscal.IdLoja = $IdLoja and
					NotaFiscal.IdContaReceber = $IdContaReceber and
					NotaFiscal.IdNotaFiscalLayout = $IdNotaFiscalLayout and
					NotaFiscal.IdStatus = 1";
		$resNF = mysql_query($sqlNF,$con);
		$linNF = mysql_fetch_array($resNF);

		$linNF[DataEmissao]			= dataConv($linNF[DataEmissao],'Y-m-d','d/m/Y');

		// Formata o Número da NF
		$linNF[IdNotaFiscalTemp]	= str_pad($linNF[IdNotaFiscal], 9, "0", STR_PAD_LEFT);
		$linNF[IdNotaFiscalTemp]	= substr($linNF[IdNotaFiscalTemp],0,3).".".substr($linNF[IdNotaFiscalTemp],3,3).".".substr($linNF[IdNotaFiscalTemp],6,3);
		
		// Formata Valores
		$linNF[ValorTotal]				= number_format($linNF[ValorTotal], 2, ',', '');
		$linNF[ValorOutros]				= number_format($linNF[ValorOutros], 2, ',', '');
		$linNF[ValorBaseCalculoICMS]	= number_format($linNF[ValorBaseCalculoICMS], 2, ',', '');

		if($linNF[ValorICMS] != NULL){
			$linNF[ValorICMS]			= number_format($linNF[ValorICMS], 2, ',', '');
		}else{
			$linNF[ValorICMS]			= '-';
		}

		// Formata o Periodo de Apuracao
		$linNF[PeriodoApuracao]			= explode('-',$linNF[PeriodoApuracao]);
		$linNF[PeriodoApuracao]			= strtoupper(substr(mes($linNF[PeriodoApuracao][1]),0,3))."/".$linNF[PeriodoApuracao][0];

		// Formata o Codigo de Autenticacao do Documento
		$linNF[CodigoAutenticacaoDocumento]	= strtoupper(substr($linNF[CodigoAutenticacaoDocumento],0,4).".".substr($linNF[CodigoAutenticacaoDocumento],4,4).".".substr($linNF[CodigoAutenticacaoDocumento],8,4).".".substr($linNF[CodigoAutenticacaoDocumento],12,4).".".substr($linNF[CodigoAutenticacaoDocumento],16,4).".".substr($linNF[CodigoAutenticacaoDocumento],20,4).".".substr($linNF[CodigoAutenticacaoDocumento],24,4).".".substr($linNF[CodigoAutenticacaoDocumento],28,4));


		// Formata a Série
		switch($linNF[Serie]){
			case 'U':
				$linNF[Serie] = 'Única';
				break;
		}
		
		$Tomador[EnderecoFull] = str_replace("\n", "<br>", $Tomador[EnderecoFull]);
	?>
	<div id='quadro' style='border-bottom: 1px #000 solid;'>
	<table width=100%>
		<tr>
			<td><B>Tomador do Serviço</B><br><?=$Tomador[Nome]?><br><?=$Tomador[EnderecoFull]?><br><?=$Tomador[CPF_CNPJFull].$Tomador[RG_IEFull]?></td>
			<td>				
				<table width=100%>
					<tr>
						<td colspan=2 style='text-align:center; font-size: 13px;'><B>NOTA FISCAL DE SERVIÇO DE COMUNICAÇÃO</B></td>
					</tr>
					<tr>
						<td style='text-align:center;'><?="Modelo $linNF[Modelo]<br>Série $linNF[Serie]<br>Emissão: $linNF[DataEmissao]"?></td>
						<td style='font-size: 14px'><B><?="Nº $linNF[IdNotaFiscalTemp]"?></B></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</div>
	<div id='quadro'>
		<table width=100% cellspacing=0>
			<tr>
				<td colspan=6><B>Lançamentos</B></td>
			</tr>
			<tr>
				<th style='border-bottom: 1px #000 solid;'>Item</th>
				<th style='border-bottom: 1px #000 solid;'>Serviço</th>
				<th style='border-bottom: 1px #000 solid;'>Descrição</th>
				<th style='border-bottom: 1px #000 solid;'>Referência</th>
				<th style='border-bottom: 1px #000 solid; text-align:right'>Valor (R$)</th>
				<th style='border-bottom: 1px #000 solid; text-align:right'>Desconto (R$)</th>
				<th style='border-bottom: 1px #000 solid; text-align:right'>ICMS</th>
			</tr>

			<?
				$msgPos = 0;
				$sqlItensNF = "select
								NotaFiscalItem.IdNotaFiscalItem,
								NotaFiscalItem.IdLancamentoFinanceiro,
								Demonstrativo.Tipo,
								Demonstrativo.Codigo,
								Demonstrativo.Descricao,
								Demonstrativo.Referencia,
								NotaFiscalItem.ValorTotal,
								NotaFiscalItem.ValorDesconto,
								NotaFiscalItem.AliquotaICMS
							from
								NotaFiscalItem
									left join Demonstrativo on (NotaFiscalItem.IdLoja = Demonstrativo.IdLoja and NotaFiscalItem.IdLancamentoFinanceiro = Demonstrativo.IdLancamentoFinanceiro)
							where
								NotaFiscalItem.IdNotaFiscalLayout = $IdNotaFiscalLayout and
								NotaFiscalItem.IdLoja = $IdLoja and
								NotaFiscalItem.IdNotaFiscal = $linNF[IdNotaFiscal] and
								NotaFiscalItem.IdContaReceber = $IdContaReceber
							order by
								NotaFiscalItem.IdNotaFiscalItem";
				$resItensNF = mysql_query($sqlItensNF,$con);
				while($linItensNF = mysql_fetch_array($resItensNF)){

					// Trata as Variáveis
					$linItensNF[IdNotaFiscalItem]	= str_pad($linItensNF[IdNotaFiscalItem], 3, "0", STR_PAD_LEFT);
					$ValorFinal					   += $linItensNF[ValorTotal];
					$ValorFinalDesconto			   += $linItensNF[ValorDesconto];
					$linItensNF[ValorTotal]			= number_format($linItensNF[ValorTotal], 2, ',', '');
					$linItensNF[ValorDesconto]		= number_format($linItensNF[ValorDesconto], 2, ',', '');

					if($linItensNF[AliquotaICMS] != NULL){
						$linItensNF[AliquotaICMS]	= number_format($linItensNF[AliquotaICMS], 2, ',', '')."%";
					}

					$linNF[AloquotaICMS] = $linItensNF[AliquotaICMS];

					if($linItensNF[IdLancamentoFinanceiro] == ''){
						$linItensNF[Descricao]	= getParametroSistema(5,3);
						$linItensNF[Referencia]	= 'Única';
					}

					// Itens da Nota
					echo "
						<tr>
							<td style='text-align:center'>$linItensNF[IdNotaFiscalItem]</td>
							<td style='text-align:center'>$linItensNF[Tipo] $linItensNF[Codigo]</td>
							<td>$linItensNF[Descricao]</td>
							<td style='text-align:center'>$linItensNF[Referencia]</td>
							<td style='text-align:right'>$linItensNF[ValorTotal]</td>
							<td style='text-align:right'>$linItensNF[ValorDesconto]</td>
							<td style='text-align:right'>$linItensNF[AliquotaICMS]</td>
						</tr>
					";

					$cont++;

					if($linItensNF[Codigo] != ''){
						$sqlParametrosNF = "select
												ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro,
												ServicoNotaFiscalLayoutParametro.Valor
											from
												Contrato,
												ServicoNotaFiscalLayoutParametro
											where
												Contrato.IdLoja = $IdLoja and
												Contrato.IdLoja = ServicoNotaFiscalLayoutParametro.IdLoja and
												Contrato.IdContrato = $linItensNF[Codigo] and
												Contrato.IdServico = ServicoNotaFiscalLayoutParametro.IdServico and
												ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayout = $IdNotaFiscalLayout";
						$resParametrosNF = mysql_query($sqlParametrosNF,$con);
						while($linParametrosNF = mysql_fetch_array($resParametrosNF)){
							$ParametrosNF[$linParametrosNF[IdNotaFiscalLayoutParametro]] = $linParametrosNF[Valor];
						}

						$MensagemNF[$msgPos] = $ParametrosNF[3];
						$msgPos++;
						
						$sqlMsgAuxNF = "select 
											NotaFiscalTipoParametro.Valor 
										from
											Contrato,
											Servico,
											NotaFiscalTipoParametro,
											NotaFiscalLayout,
											NotaFiscalLayoutParametro 
										where
											Contrato.IdLoja = $IdLoja and
											Contrato.IdLoja = Servico.IdLoja and
											Contrato.IdContrato = $linItensNF[Codigo] and
											Contrato.IdServico = Servico.IdServico and
											NotaFiscalTipoParametro.IdNotaFiscalTipo = Servico.IdNotaFiscalTipo and
											NotaFiscalLayoutParametro.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout and
											NotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro = 3 and
											NotaFiscalTipoParametro.IdNotaFiscalLayoutParametro = NotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro";
						$resMsgAuxNF = mysql_query($sqlMsgAuxNF,$con);
						$linMsgAuxNF = mysql_fetch_array($resMsgAuxNF);
					}
				}
				
				if($msg != ""){
					$msg .= " ".$linMsgAuxNF[Valor];
				}else{
					$msg .= $linMsgAuxNF[Valor];
				}
				if($MensagemNF != null){
					$MensagemNF = array_unique($MensagemNF);
				}

				for($i=0; $i<count($MensagemNF); $i++){
					$msg .= " ".$MensagemNF[$i];
				}
				$msg = trim($msg);
				
				if($msg != ''){
					$msg .= "&nbsp;".$linNF[ObsVisivel];
				} else{
					$msg = $linNF[ObsVisivel];
				}
				
				$ValorFinal			= number_format($ValorFinal, 2, ',', '');
				$ValorFinalDesconto = number_format($ValorFinalDesconto, 2, ',', '');

				echo "
					<tr>
						<th colspan=4 style='border-bottom: 1px #000 solid; border-top: 1px #000 solid; text-align:right'>Total</th>
						<th style='border-bottom: 1px #000 solid; border-top: 1px #000 solid; text-align:right'>$ValorFinal</th>
						<th style='border-bottom: 1px #000 solid; border-top: 1px #000 solid; text-align:right'>$ValorFinalDesconto</th>
						<th style='border-bottom: 1px #000 solid; border-top: 1px #000 solid; '>&nbsp;</th>
					</tr>";
			?>
		</table>
		<table width=100% cellspacing=0>
			<tr>
				<th style='width: 100px'>Base de Cálculo</th>
				<th>ICMS</th>
				<th>Outras</th>
				<th style='border-right: 1px #000 solid;'>Total</th>
				<th style='text-align:left' width=25%>&nbsp;Reservado ao Fisco</th>
				<th style='text-align:right' width=25%>Período Fiscal: <B style='font-weight: normal'><?=$linNF[PeriodoApuracao]?></B></th>
			</tr>
			<tr>
				<td style='text-align:center; border-bottom: 1px #000 solid;'><?=$linNF[ValorBaseCalculoICMS]?></td>
				<td style='text-align:center; border-bottom: 1px #000 solid;'><?=$linNF[ValorICMS]?></td>
				<td style='text-align:center; border-bottom: 1px #000 solid;'><?=$linNF[ValorOutros]?></td>
				<td style='border-right: 1px #000 solid;  border-bottom: 1px #000 solid; text-align:center'><?=$linNF[ValorTotal]?></td>
				<td style='text-align:center; border-bottom: 1px #000 solid;' colspan=2><?=$linNF[CodigoAutenticacaoDocumento]?></td>
			</tr>
		</table>
		<p style='text-align:justify'><?=$msg?></p>
	</div>
</div>
</BODY></HTML>
