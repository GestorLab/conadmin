<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<HTML>
<HEAD>
<TITLE><?=$tituloBoleto?></TITLE>
<META http-equiv=Content-Type content=text/html charset=ISO-8859-1>
<meta name="Generator" content="Projeto BoletoPHP - www.boletophp.com.br - Licen�a GPL" />
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
--></style> 
</head>

<BODY text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>
<META NAME="keywords" CONTENT="Boleto, Dinamico, PHP, ASP, Itau, Bradesco, HSBC, Real, Banespa, Unibanco, Banco do Brasil, Sistemas, Sites, Cobran�a Bancaria"> <META NAME="description" CONTENT="Sistema para cria��o de boletos on-lines c�digo fonte em PHP ou ASP, como usar boletos bancarios.">
</HEAD>
<BODY text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>
<DIV align=center>
	<?
		include("../cabecalho_html.php");
		include("../demonstrativo_html.php");
	?>
	<table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=431 height=13 colspan=3>Cedente</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=34 height=13>Esp�cie</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=53 height=13>Quantidade</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=120 height=13>Nosso 
	n�mero</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top width=431 height=12 colspan=3> 
	<?=$dadosboleto["cedente"]?></td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=34 height=12> 
	<?=$dadosboleto["especie"]?> </td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=53 height=12> 
	<?=$dadosboleto["quantidade"]?> </td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=120 height=12> 
	<?=$dadosboleto["nosso_numero"]?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	</tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=298 height=1><img height=1 src=imagens/2.gif width=298 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=126 height=1><img height=1 src=imagens/2.gif width=126 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=34 height=1><img height=1 src=imagens/2.gif width=34 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=53 height=1><img height=1 src=imagens/2.gif width=53 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=120 height=1><img height=1 src=imagens/2.gif width=120 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top colspan=3 height=13>N�mero 
	do documento</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=132 height=13>CPF/CNPJ</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=134 height=13>Vencimento</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>Valor 
	documento</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top colspan=3 height=12> 
	<?=$dadosboleto["numero_documento"]?> </td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top width=132 height=12> 
	<?=$dadosboleto["cpf_cnpj"]?> </td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top width=134 height=12> 
	<?=$dadosboleto["data_vencimento"]?> </td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=180 height=12> 
	<?=$dadosboleto["valor_boleto"]?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	</tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=imagens/2.gif width=113 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=72 height=1><img height=1 src=imagens/2.gif width=72 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=132 height=1><img height=1 src=imagens/2.gif width=132 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=134 height=1><img height=1 src=imagens/2.gif width=134 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=113 height=13>(-) 
	Desconto / Abatimentos</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=112 height=13>(-) 
	Outras dedu��es</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=113 height=13>(+) 
	Mora / Multa</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=113 height=13>(+) 
	Outros acr�scimos</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>(=) 
	Valor cobrado</td></tr>
	<tr>
		<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td>
		<td class=cp valign=top width=110 height=12> 
			<?=$dadosboleto["valor_desconto"]?> 
		</td>
		<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td>
		<td class=cp valign=top width=110 height=12> 
			<?=$dadosboleto["valor_outras_deducoes"]?> 
		</td>
		<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td>
		<td class=cp valign=top width=110 height=12> 
			<?=$dadosboleto["valor_mora_multa"]?> 
		</td>
		<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td>
		<td class=cp valign=top width=110 height=12> 
			<?=$dadosboleto["valor_outros_acrescimos"]?> 
		</td>
		<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td>
		<td class=cp align=right width=110 height=12> 
			<span style='margin-right: 24px'><?=$dadosboleto["valor_cobrado"]?> </span>
		</td>
	</tr>
	<tr>
		<td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td>
		<td valign=top width=113 height=1><img height=1 src=imagens/2.gif width=113 border=0></td>
		<td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td>
		<td valign=top width=112 height=1><img height=1 src=imagens/2.gif width=112 border=0></td>
		<td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td>
		<td valign=top width=113 height=1><img height=1 src=imagens/2.gif width=113 border=0></td>
		<td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td>
		<td valign=top width=113 height=1><img height=1 src=imagens/2.gif width=113 border=0></td>
		<td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td>
		<td valign=top width=180 height=1><img height=1 src=imagens/2.gif width=180 border=0></td>
	</tr>
	</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0><tbody>
	<tr>
		<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td>
		<td class=ct valign=top width=659 height=13>Sacado</td>
	</tr>
	<tr>
		<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td>
		<td class=cp valign=top width=659 height=12> 
			<?=$dadosboleto["sacado"]?> 
		</td>
	</tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=659 height=1><img height=1 src=imagens/2.gif width=659 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct  width=7 height=12></td><td class=ct  width=520 >Corte na linha pontilhada</td><td class=ct  width=10 height=12></td><td class=ct  width=129 >Autentica��o 
	mec�nica</td></tr><tr><td  width=7 ></td><td  width=520 ></td><td  width=10 ></td><td  width=129 ></td></tr></tbody></table><table cellspacing=0 cellpadding=0 width=666 border=0><tbody><tr><td width=7></td><td  width=500 class=cp> 
	<?=$dadosboleto["instrucoes"]?></td><td width=159></td></tr></tbody></table><table cellspacing=0 cellpadding=0 width=666 border=0><tr><td class=ct width=666></td></tr><tbody><tr><td class=ct width=666>&nbsp;</td></tr><tr><td class=ct width=666><img height=1 src=imagens/6.gif width=665 border=0></td></tr></tbody></table>

	<table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=472 height=13>Local 
	de pagamento</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>Vencimento</td></tr><tr>
		<td class=cp valign=top width=7 height=12><img height=15 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top width=472 height=12><?=$dadosboleto["local_pagamento"]?></td><td class=cp valign=top width=7 height=12><img height=15 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=180 height=12> 
	<?=$dadosboleto["data_vencimento"]?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	</tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=472 height=1><img height=1 src=imagens/2.gif width=472 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=659 height=13 colspan=3>Cedente</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top width=659 height=12 colspan=3><?=$dadosboleto["cedente"]?></td></tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=472 height=1><img height=1 src=imagens/2.gif width=472 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13> 
	<img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=113 height=13>Data 
	do documento</td><td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=163 height=13>N<u>o</u> 
	documento</td><td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=62 height=13>Esp�cie 
	doc.</td><td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=34 height=13>Aceite</td><td class=ct valign=top width=7 height=13> 
	<img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=72 height=13>Data 
	process.</td><td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>Nosso 
	n�mero</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=113 height=12><div align=left> 
	<?=$dadosboleto["data_documento"]?> </div></td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top width=163 height=12> 
	<?=$dadosboleto["numero_documento"]?> </td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=62 height=12><div align=left> 
	<?=$dadosboleto["especie_doc"]?> </div></td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=34 height=12><div align=left> 
	<?=$dadosboleto["aceite"]?> </div></td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=72 height=12><div align=left> 
	<?=$dadosboleto["data_processamento"]?> </div></td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=180 height=12> 
	<?=$dadosboleto["nosso_numero"]?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	</tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=imagens/2.gif width=113 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=163 height=1><img height=1 src=imagens/2.gif width=163 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=62 height=1><img height=1 src=imagens/2.gif width=62 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=34 height=1><img height=1 src=imagens/2.gif width=34 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=72 height=1><img height=1 src=imagens/2.gif width=72 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1> 
	<img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr> 
	<td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top COLSPAN="3" height=13>Uso 
	do banco </td><td class=ct valign=top height=13 width=7> <img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=83 height=13>Carteira</td><td class=ct valign=top height=13 width=7> 
	<img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=53 height=13>Esp�cie</td><td class=ct valign=top height=13 width=7> 
	<img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=123 height=13>Quantidade</td><td class=ct valign=top height=13 width=7> 
	<img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=72 height=13> 
	Valor </td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>(=) 
	Valor documento</td></tr><tr> <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td valign=top class=cp height=12 COLSPAN="3"><div align=left> 
	<?=$dadosboleto["uso_banco"]?> </div></td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=83> 
	<div align=left> <?=$dadosboleto["carteira"]?> </div></td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=53><div align=left> 
	<?=$dadosboleto["especie"]?> </div></td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=123> 
	<?=$dadosboleto["quantidade"]?> </td><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top  width=72> 
	<?=$dadosboleto["valor_unitario"]?> </td><td class=cp valign=top width=7 height=12> 
	<img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=180 height=12> 
	<?=$dadosboleto["valor_boleto"]?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	</tr><tr><td valign=top width=7 height=1> <img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=75 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=31 height=1><img height=1 src=imagens/2.gif width=31 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=83 height=1><img height=1 src=imagens/2.gif width=83 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=53 height=1><img height=1 src=imagens/2.gif width=53 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=123 height=1><img height=1 src=imagens/2.gif width=123 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=72 height=1><img height=1 src=imagens/2.gif width=72 border=0></td><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody> 
	</table><table cellspacing=0 cellpadding=0 width=666 border=0><tbody><tr><td align=right width=10><table cellspacing=0 cellpadding=0 border=0 align=left><tbody> 
	<tr> <td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td></tr><tr> 
	<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td></tr><tr> 
	<td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=1 border=0></td></tr></tbody></table></td>
	<td valign=top width=468 rowspan=5><font class=ct>Instru��es 
	(Texto de responsabilidade do cedente)</font><br><span class=cp> <FONT class=campo><?
	InstrucoesBoletoHTML($IdContaReceber);
?></FONT>
	</span>
	</td><td align=right width=188><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>(-) 
	Desconto / Abatimentos</td></tr><tr> <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr> 
	<td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10> 
	<table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td></tr><tr><td valign=top width=7 height=1> 
	<img height=1 src=imagens/2.gif width=1 border=0></td></tr></tbody></table></td><td align=right width=188><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>(-) 
	Outras dedu��es</td></tr><tr><td class=cp valign=top width=7 height=12> <img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10> 
	<table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr><td class=ct valign=top width=7 height=13> 
	<img height=13 src=imagens/1.gif width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td></tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=1 border=0></td></tr></tbody></table></td><td align=right width=188> 
	<table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>(+) 
	Mora / Multa</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr> 
	<td valign=top width=7 height=1> <img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1> 
	<img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10><table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr> 
	<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td></tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=1 border=0></td></tr></tbody></table></td><td align=right width=188> 
	<table cellspacing=0 cellpadding=0 border=0><tbody><tr> <td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>(+) 
	Outros acr�scimos</td></tr><tr> <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10><table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td></tr></tbody></table></td><td align=right width=188><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=180 height=13>(=) 
	Valor cobrado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr></tbody> 
	</table></td></tr></tbody></table><table cellspacing=0 cellpadding=0 width=666 border=0><tbody><tr><td valign=top width=666 height=1><img height=1 src=imagens/2.gif width=666 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=ct valign=top width=659 height=13>Sacado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top width=659 height=12> 
	<?=$dadosboleto["sacado"]?> </td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top width=659 height=12> 
	<?=$dadosboleto["endereco1"]?> </td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.gif width=1 border=0></td><td class=cp valign=top height=13> 
	<?=$dadosboleto["endereco2"]?></td></tr><tr><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=472 height=1><img height=1 src=imagens/2.gif width=472 border=0></td><td valign=top width=7 height=1><img height=1 src=imagens/2.gif width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=imagens/2.gif width=180 border=0></td></tr></tbody></table><TABLE cellSpacing=0 cellPadding=0 width=666 border=0><TBODY><TR><TD vAlign=bottom align=left>&nbsp;</TD><TD style='text-align:right'>
		<?
			if($linDadosCliente["Cob_FormaOutro"] == 'S'){
				echo "<img src='../../../../img/estrutura_sistema/ico_estrela.jpg'>";
			}
		?>
	</TD></tr></tbody></table>
</div>
</BODY></HTML>