<?
	$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
	$local_ContaReceber			=	$_GET['ContaReceber'];
	$local_Erro					=	$_GET['Erro'];
	
	$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema";
	$res	=	@mysql_query($sql,$con);
	$lin	=	@mysql_fetch_array($res);
	
	$local_Descricao	=	$lin[DescricaoParametroSistema];
?>
<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
		<td id="tit" width="387"><h1><img src="img/icones/<?=$local_IdParametroSistema?>.png" /> <?=$local_Descricao?> - Detalhes</h1></td>
		<td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="?ctt=index.php">Página Inicial</a></td>
		<td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
	</tr>
</table>
<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class='coluna2main'>
		<?
			$iFormaPagamento = 0;

			$sql = "select
						IdLocalCobrancaLayout
					from
						LocalCobranca
					where
						IdLoja = $IdLoja and
						IdTipoLocalCobranca = 5 and
						IdStatus = 1";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
				$IdLocalCobrancaLayout = $lin[IdLocalCobrancaLayout];
				include("../administrativo/local_cobranca/$lin[IdLocalCobrancaLayout]/forma_pagamento.php");
			}

			if($iFormaPagamento == 0){
				echo "<p style='font-size:12px; text-align:center;'>O módulo de pagamento on-line não está disponível para sua central do assinante.<br>Entre em contato com o suporte para identificar o motivo.</p>";
			}

			/*else{
				echo "<table border=0 cellpadding=0 id='formas_pagamento'>";
				for($i=0; $i<$iFormaPagamento; $i++){
					if($FormaPagamento[$i][parcelas] > 1){
						$FormaPagamento[$i][descricao]	.= " (".$FormaPagamento[$i][parcelas]."x sem juros)";
					}
					echo "<tr>
							<td class='formas_pagamento_bandeira'>".$FormaPagamento[$i][img]."</td>
							<td class='formas_pagamento_descricao'>".$FormaPagamento[$i][descricao]."</td>
							<td class='formas_pagamento_bt'><INPUT type='button' onclick='javascript: direciona()' value='Pagar Agora!' class='BotaoPadrao' /></td>
						</tr>";
				}
				echo "</table>";
			}*/
		?>
		</td>
	</tr>
</table>
<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class='coluna2main'>
		<?
			$sql = "select
						IdLoja,
						IdContaReceber,
						DataVencimento,
						ValorFinal,
						PercentualMulta,
						PercentualJurosDiarios
					from
						ContaReceberDados
					where
						MD5 = '$local_ContaReceber'";
			$res = @mysql_query($sql,$con);
			if($lin = @mysql_fetch_array($res)){
				
				$IdLoja				= $lin[IdLoja];
				$IdContaReceber		= $lin[IdContaReceber];
				$DataVencimento		= $lin[DataVencimento];

				$DiasAtraso			= diferencaDiaData($lin[DataVencimento],date("Y-m-d"));				
				$ValorContaReceber	= $lin[ValorFinal];

				if($DiasAtraso < 0){	
					$DiasAtraso = 0; 
				}else{
					$ValorMulta			= $lin[PercentualMulta]*$ValorContaReceber/100;
					$ValorJuros			= ($lin[PercentualJurosDiarios]*$ValorContaReceber/100)*$DiasAtraso;
				}

				$ValorFinal			= $ValorContaReceber + $ValorMulta + $ValorJuros;

				echo "<STYLE>
					#quadro{
						text-align:left;
						margin-top: 10px;
						font-size: 11px;
					}
					#quadro table{
						font-size: 11px;
						margin: 0 0 15px 0;
						width:100%;
					}
					#quadro table tr th, #quadro table tr td{
						border-bottom: 1px #7C8286 solid;
					}
					#quadro p{
						margin: 0;
						font-size: 12px;
					}
					#formas_pagamento tr td{
						padding: 5px;
						border: 1px #7C8286 solid;
					}
					#formas_pagamento tr .formas_pagamento_bandeira{
						text-align: center;
						width: 80px;
						border
					}
					#formas_pagamento tr .formas_pagamento_descricao{
						font-weight: bold;
						width: 400px;
					}
					#formas_pagamento tr .formas_pagamento_bt{
						width: 80px;
					}
					</STYLE>";

				include("../administrativo/local_cobranca/demonstrativo_html.php");
				
				echo "<div id='quadro'>
						<table cellspacing=0>
							<tr>
								<th>Data Vencimento</th>
								<th style='text-align:right;'>Valor (".getParametroSistema(5,1).")</th>
								<th>Dias Atraso</th>
								<th style='text-align:right;'>Valor Multa (".getParametroSistema(5,1).")</th>
								<th style='text-align:right;'>Valor Juros (".getParametroSistema(5,1).")</th>
								<th style='text-align:right;'>Valor Atualizado (".getParametroSistema(5,1).")</th>
							</tr>
							<tr>
								<td style='text-align:center'>".dataConv($DataVencimento,'Y-m-d','d/m/Y')."</td>
								<td style='text-align:right;'>".number_format($ValorContaReceber,2,",","")."</td>
								<td style='text-align:center'>$DiasAtraso</td>
								<td style='text-align:right;'>".number_format($ValorMulta,2,",","")."</td>
								<td style='text-align:right;'>".number_format($ValorJuros,2,",","")."</td>
								<td style='text-align:right; font-weight: bold; font-size: 15px'>".getParametroSistema(5,1)." ".number_format($ValorFinal,2,",","")."</td>
							</tr>
						</table>
					</div>";
					
					echo "<p style='text-align: right;'><INPUT type='button' onclick='javascript: direciona()' value='Pagar Agora!' class='BotaoPadrao' /></p>
					
					<script>
						function direciona(){
							window.location.replace('ctt/pagamento_online_layout$IdLocalCobrancaLayout.php?ContaReceber=$local_ContaReceber');
							window.open('ctt/pagamento_online_layout$IdLocalCobrancaLayout.php?ContaReceber=$local_ContaReceber');
							window.location.replace('menu.php?ctt=pagamento_online_aguarde.php&IdParametroSistema=10');
						}
					</script>";
			}
		?>
		</td>
	</tr>
	<tr>
		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
	</tr>
</table>