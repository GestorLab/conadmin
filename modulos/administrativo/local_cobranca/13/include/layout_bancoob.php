<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<HTML>
<HEAD>
<TITLE><?=$tituloBoleto?></TITLE>
<META http-equiv=Content-Type content=text/html charset=ISO-8859-1>
<style type=text/css>
body{
	FONT: 10px Arial;
}

<!--.cp {  font: bold 10px Arial; color: black}
<!--.ti {  font: 9px Arial, Helvetica, sans-serif}
<!--.ld { font: bold 15px Arial; color: #000000}
<!--.ct { FONT: 9px "Arial Narrow"; COLOR: #000033} 
<!--.cn { FONT: 9px Arial; COLOR: black }
<!--.bc { font: bold 20px Arial; color: #000000 }
<!--.ld2 { font: bold 12px Arial; color: #000000 }

#cabecalho, #quadro{
	width:665px; 
	border-bottom: 1px #000 solid;
}
#cabecalho{
	height: 45px; 
	padding: 5px;
	text-align: right;
}
#quadro{
	height: 360px;
	text-align:left;
	margin-top: 10px;
	font-size: 11px;
}
#quadro table{
	font-size: 11px;
	margin: 0 0 15px 0;
	width:665px;
}
#quadro table tr th, #quadro table tr td{
	border-bottom: 1px #7C8286 solid;
}
#quadro p{
	margin: 0;
	font-size: 12px;
}
-->
	
<style type="text/css">
<!--
.ti {font: 9px Arial, Helvetica, sans-serif}
-->

/* *** LINHAS GERAIS *** */

#container {
	width: 665px;
}

#instructions {
	margin: 0;
	padding: 0 0 20px 0;
}

#boleto {
	width: 665px;
	margin: 0;
	padding: 0;
}

p{
	margin: 0;
	text-align:left;
}
/* *** CABECALHO *** */

#instr_content h2 {
	font-size: 10px;
	font-weight: bold;
}

#instr_content p {
	font-size: 10px;
	margin: 4px 0px;
}

#instr_content ol {
	font-size: 10px;
	margin: 5px 0;
}

#instr_content ol li {
	font-size: 10px;
	text-indent: 10px;
	margin: 2px 0px;
	list-style-position: inside;
}

#instr_content ol li p {
	font-size: 10px;
	padding-bottom: 4px;
}


/* *** BOLETO *** */

.cut {
	width: 665px;
	text-align:left;
	margin-bottom: 10px;
}
table.header {
	width: 665px;
	border-bottom: 2px black solid;	
}


table.header div.field_cod_banco {
	width: 46px;
	height: 19px;
  margin-left: 5px;
	padding-top: 3px;
	text-align: center;
	font-size: 14px;
	font-weight: bold;
	color: black;
	border-right: 2px solid black;
	border-left: 2px solid black;
}

table.header td.linha_digitavel {
	text-align: right;
	font: bold 15px Arial;
}

table.line {
	padding-bottom: 1px;
	border-bottom: 1px black solid;
}

table.line tr.titulos td {
	height: 13px;
	font-size: 9px;
	color: black;
	border-left: 1px #000000 solid;
	padding-left: 2px;
}

table.line tr.campos td {
	height: 12px;
	font-size: 10px;
	color: black;
	border-left: 1px #000000 solid;
	padding-left: 2px;
}

table.line td p {
	font-size: 10px;
}


table.line tr.campos td.ag_cod_cedente,
table.line tr.campos td.nosso_numero,
table.line tr.campos td.valor_doc,
table.line tr.campos td.vencimento2,
table.line tr.campos td.ag_cod_cedente2,
table.line tr.campos td.nosso_numero2,
table.line tr.campos td.xvalor,
table.line tr.campos td.valor_doc2
{
	text-align: right;
}

table.line tr.campos td.especie,
table.line tr.campos td.qtd,
table.line tr.campos td.vencimento,
table.line tr.campos td.especie_doc,
table.line tr.campos td.aceite,
table.line tr.campos td.carteira,
table.line tr.campos td.especie2,
table.line tr.campos td.qtd2
{
	text-align: center;
}

table.line td.last_line {
	vertical-align: top;
	height: 25px;
}

table.line td.last_line table.line {
	margin-bottom: -5px;
	border: 0 white none;
}

td.last_line table.line td.instrucoes {
	border-left: 0 white none;
	padding-left: 5px;
	padding-bottom: 0;
	margin-bottom: 0;
	height: 20px;
	vertical-align: top;
}

table.line td.cedente {
	width: 315px;
}

table.line td.valor_cobrado2 {
	padding-bottom: 0;
	margin-bottom: 0;
}


table.line td.ag_cod_cedente {
	width: 126px;
}

table.line td.especie {
	width: 35px;
}

table.line td.qtd {
	width: 53px;
}

table.line td.nosso_numero {
	/* width: 120px; */
	width: 115px;
	padding-right: 5px;
}

table.line td.num_doc {
	width: 218px;
}

table.line td.cpf_cei_cnpj {
	width: 132px;
}

table.line td.vencimento {
	width: 134px;
}

table.line td.valor_doc {
	width: 175px;
	padding-right: 5px;
}

table.line td.desconto {
	width: 120px;
}

table.line td.outras_deducoes {
	width: 120px;
}

table.line td.mora_multa {
	width: 120px;
}

table.line td.outros_acrescimos {
	width: 119px;
}

table.line td.valor_cobrado {
	width: 175px;
	padding-right: 5px;
}

table.line td.sacado {
	width: 665px;
}

table.line td.local_pagto {
	width: 492px;
}

table.line td.vencimento2 {
	width: 175px;
	padding-right: 5px;
}

table.line td.cedente2 {
	width: 492px;
}

table.line td.ag_cod_cedente2 {
	width: 175px;
	padding-right: 5px;
}

table.line td.data_doc {
	width: 100px;
}

table.line td.num_doc2 {
	width: 173px;
}

table.line td.especie_doc {
	width: 72px;
}

table.line td.aceite {
	width: 40px;
}

table.line td.data_process {
	width: 90px;
}

table.line td.nosso_numero2 {
	width: 175px;
	padding-right: 5px;
}

table.line td.reservado {
	width: 102px;
}

table.line td.carteira {
	width: 84px;
}

table.line td.especie2 {
	width: 85px;
}

table.line td.qtd2 {
	width: 72px;
}

table.line td.xvalor {
	width: 130px;
	padding-right: 5px;
}

table.line td.valor_doc2 {
	width: 175px;
	padding-right: 5px;
}
table.line td.instrucoes {
	width: 490px;
}

table.line td.desconto2 {
	width: 175px;
	padding-right: 5px;
}

table.line td.outras_deducoes2 {
	width: 175px;
	padding-right: 5px;
}

table.line td.mora_multa2 {
	width: 175px;
	padding-right: 5px;
}

table.line td.outros_acrescimos2 {
	width: 175px;
	padding-right: 5px;
}

table.line td.valor_cobrado2 {
	width: 175px;
	padding-right: 5px;
}

table.line td.sacado2 {
	width: 665px;
}

div.footer {
	margin: 0;
	padding: 0;
	text-align:right;
}
div.barcode {
	width: 665px;
	margin-bottom: 20px;
}
</STYLE>
</head>
<BODY text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>
<DIV align=center>
	<div id="container">
		<div id='cabecalho'>
			<img src='../../../../img/personalizacao/logo_cab.jpg' / style='float:left'>
			<?="$dadosboleto[cedente]<br>$dadosboleto[endereco] - $dadosboleto[cidade]<br>$CPF_CNPJ: $dadosboleto[cpf_cnpj]".$dadosboleto[fone]?>
		</div>
		<?
			include("../demonstrativo_html.php");
		?>
		<table class="header" border=0 cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td width=150><IMG SRC="imagens/logo.jpg"></td>
				<td class="linha_digitavel" style='width:50px; border: 2px #000 solid; border-top: 0; border-bottom: 0; text-align:center'><?php echo $dadosboleto["codigo_banco_com_dv"]?></td>
				<td class="linha_digitavel"><?php echo $dadosboleto["linha_digitavel"]?></td>
			</tr>
			</tbody>
			</table>

			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="cedente">Cedente</TD>
				<td class="ag_cod_cedente">Ag&ecirc;ncia / C&oacute;digo do Cedente</td>
				<td class="especie">Esp&eacute;cie</TD>
				<td class="qtd">Quantidade</TD>
				<td class="nosso_numero">Nosso n&uacute;mero</td>
			</tr>

			<tr class="campos">
				<td class="cedente"><?php echo $dadosboleto["cedente"]; ?>&nbsp;</td>
				<td class="ag_cod_cedente"><?php echo $dadosboleto["agencia_codigo"]?> &nbsp;</td>
				<td class="especie"><?php echo $dadosboleto["especie"]?>&nbsp;</td>
				<TD class="qtd"><?php echo $dadosboleto["quantidade"]?>&nbsp;</td>
				<TD class="nosso_numero"><?php echo $dadosboleto["nosso_numero_visual"]?>&nbsp;</td>
			</tr>
			</tbody>
			</table>

			<table class="line" cellspacing="0" cellPadding="0">
			<tbody>
			<tr class="titulos">
				<td class="num_doc">N&uacute;mero do documento</td>
				<td class="cpf_cei_cnpj">CPF/CEI/CNPJ</TD>
				<td class="vencmento">Vencimento</TD>
				<td class="valor_doc">Valor documento</TD>
			</tr>
			<tr class="campos">
				<td class="num_doc"><?php echo $dadosboleto["numero_documento"]?></td>
				<td class="cpf_cei_cnpj"><?php echo $dadosboleto["cpf_cnpj"]?></td>
				<td class="vencimento"><?php echo $dadosboleto["data_vencimento"]?></td>
				<td class="valor_doc"><?php echo $dadosboleto["valor_boleto"]?></td>
			</tr>
		  </tbody>
		  </table>

			<table class="line" cellspacing="0" cellPadding="0">
			<tbody>
			<tr class="titulos">
				<td class="desconto">(-) Desconto / Abatimento</td>
				<td class="outras_deducoes">(-) Outras dedu&ccedil;&otilde;es</td>
				<td class="mora_multa">(+) Mora / Multa</td>
				<td class="outros_acrescimos">(+) Outros acr&eacute;scimos</td>
				<td class="valor_cobrado">(=) Valor cobrado</td>
			</tr>
			<tr class="campos">
				<td class="desconto"><?php echo $dadosboleto["valor_desconto"]?></td>
				<td class="outras_deducoes"><?php echo $dadosboleto["valor_outras_deducoes"]?></td>
				<td class="mora_multa"><?php echo $dadosboleto["valor_mora_multa"]?></td>
				<td class="outros_acrescimos"><?php echo $dadosboleto["valor_outros_acrescimos"]?></td>
				<td class="valor_cobrado" style='text-align:right'><?php echo $dadosboleto["valor_cobrado"]?></td>
			</tr>
			</tbody>
			</table>

		  
			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="sacado">Sacado</td>
			</tr>
			<tr class="campos">
				<td class="sacado"><?php echo $dadosboleto["sacado"]?></td>
			</tr>
			</tbody>
			</table>
			<div class="footer">Autentica&ccedil;&atilde;o mec&acirc;nica</div>
			<div class="cut">Corte na linha pontilhada<br><img height=1 src=imagens/6.png width=665 border=0></div>
			<table class="header" border=0 cellspacing="0" cellpadding="0">
			<tbody>
			<tr>
				<td width=150><IMG SRC="imagens/logo.jpg"></td>
				<td width=50>
			<div class="field_cod_banco"><?php echo $dadosboleto["codigo_banco_com_dv"]?></div>
				</td>
				<td class="linha_digitavel"><?php echo $dadosboleto["linha_digitavel"]?></td>
			</tr>
			</tbody>
			</table>

			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="local_pagto">Local de pagamento</td>
				<td class="vencimento2">Vencimento</td>
			</tr>
			<tr class="campos">
				<td class="local_pagto"><?=$CobrancaParametro[LocalPagamento]?></td>
				<td class="vencimento2"><?php echo $dadosboleto["data_vencimento"]?></td>
			</tr>
			</tbody>
			</table>
			
			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="cedente2">Cedente</td>
				<td class="ag_cod_cedente2">Ag&ecirc;ncia/C&oacute;digo cedente</td>
			</tr>
			<tr class="campos">
				<td class="cedente2"><?php echo $dadosboleto["cedente"]?></td>
				<td class="ag_cod_cedente2"><?php echo $dadosboleto["agencia_codigo"]?></td>
			</tr>
			</tbody>
			</table>

			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="data_doc">Data do documento</td>
				<td class="num_doc2">No. documento</td>
				<td class="especie_doc">Esp&eacute;cie doc.</td>
				<td class="aceite">Aceite</td>
				<td class="data_process">Data process.</td>
				<td class="nosso_numero2">Nosso n&uacute;mero</td>
			</tr>
			<tr class="campos">
				<td class="data_doc"><?php echo $dadosboleto["data_documento"]?></td>
				<td class="num_doc2"><?php echo $dadosboleto["numero_documento"]?></td>
				<td class="especie_doc"><?php echo $dadosboleto["especie_doc"]?></td>
				<td class="aceite"><?php echo $dadosboleto["aceite"]?></td>
				<td class="data_process"><?php echo $dadosboleto["data_processamento"]?></td>
				<td class="nosso_numero2"><?php echo $dadosboleto["nosso_numero_visual"]?></td>
			</tr>
			</tbody>
			</table>

			<table class="line" cellspacing="0" cellPadding="0">
			<tbody>
			<tr class="titulos">
				<td class="reservado">Uso do  banco</td>
				<td class="carteira">Carteira</td>
				<td class="especie2">Espécie</td>
				<td class="qtd2">Quantidade</td>
				<td class="xvalor">x Valor</td>
				<td class="valor_doc2">(=) Valor documento</td>
			</tr>
			<tr class="campos">
				<td class="reservado">&nbsp;</td>
				<td class="carteira"><?php echo $dadosboleto["carteira"]?> <?php echo isset($dadosboleto["variacao_carteira"]) ? $dadosboleto["variacao_carteira"] : '&nbsp;' ?></td>
				<td class="especie2"><?php echo $dadosboleto["especie"]?></td>
				<td class="qtd2"><?php echo $dadosboleto["quantidade"]?></td>
				<td class="xvalor"><?php echo $dadosboleto["valor_unitario"]?></td>
				<td class="valor_doc2"><?php echo $dadosboleto["valor_boleto"]?></td>
			</tr>
			</tbody>
			</table>
			
			
			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr><td class="last_line" rowspan="6">
				<table class="line" cellspacing="0" cellpadding="0">
				<tbody>
				<tr class="titulos">
					<td class="instrucoes">
							Instru&ccedil;&otilde;es (Texto de responsabilidade do cedente)
					</td>
				</tr>
				<tr class="campos" style='margin:0'>
					<td class="instrucoes" rowspan="5"><? InstrucoesBoletoHTML($IdContaReceber); ?></td>
				</tr>
				</tbody>
				</table>
			</td></tr>
			
			<tr><td>
				<table class="line" cellspacing="0" cellpadding="0">
				<tbody>
				<tr class="titulos">
					<td class="desconto2">(-) Desconto / Abatimento</td>
				</tr>
				<tr class="campos">
					<td class="desconto2">&nbsp;</td>
				</tr>
				</tbody>
				</table>
			</td></tr>
			
			<tr><td>
				<table class="line" cellspacing="0" cellpadding="0">
				<tbody>
				<tr class="titulos">
					<td class="outras_deducoes2">(-) Outras dedu&ccedil;&otilde;es</td>
				</tr>
				<tr class="campos">
					<td class="outras_deducoes2">&nbsp;</td>
				</tr>
				</tbody>
				</table>
			</td></tr>

			<tr><td>
				<table class="line" cellspacing="0" cellpadding="0">
				<tbody>
				<tr class="titulos">
					<td class="mora_multa2">(+) Mora / Multa</td>
				</tr>
				<tr class="campos">
					<td class="mora_multa2">&nbsp;</td>
				</tr>
				</tbody>
				</table>
			</td></tr>

			<tr><td>
				<table class="line" cellspacing="0" cellpadding="0">
				<tbody>
				<tr class="titulos">
					<td class="outros_acrescimos2">(+) Outros Acr&eacute;scimos</td>
				</tr>
				<tr class="campos">
					<td class="outros_acrescimos2">&nbsp;</td>
				</tr>
				</tbody>
				</table>
			</td></tr>

			<tr><td class="last_line">
				<table class="line" cellspacing="0" cellpadding="0">
				<tbody>
				<tr class="titulos">
					<td class="valor_cobrado2">(=) Valor cobrado</td>
				</tr>
				<tr class="campos">
					<td class="valor_cobrado2">&nbsp;</td>
				</tr>
				</tbody>
				</table>
			</td></tr>
			</tbody>
			</table>
			
			
			<table class="line" cellspacing="0" cellPadding="0">
			<tbody>
			<tr class="titulos">
				<td class="sacado2">Sacado</td>
			</tr>
			<tr class="campos">
				<td class="sacado2">
					<p><?php echo $dadosboleto["sacado"]?></p>
					<p><?php echo $dadosboleto["endereco1"]?></p>
					<p><?php echo $dadosboleto["endereco2"]?></p>
				</td>
			</tr>
			</tbody>
			</table>		
			<div class="barcode">
				<p><?php fbarcode($dadosboleto["codigo_barras"]); ?><span style='text-align:right'><? if($dadosboleto["cob_outro"] == 'S'){ echo "<img src='../../../../img/estrutura_sistema/ico_estrela.jpg'>"; } ?></span></p>
			</div>
		</div>
	</div>
</div>
</body>
</html>
