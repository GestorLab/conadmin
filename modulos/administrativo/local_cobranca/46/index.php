<?php
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");

	$dadosboleto["data_vencimento"] 	= $linContaReceber[DataVencimento];		// Data de Vencimento do Boleto
	$dadosboleto["nosso_numero"]	 	= $linContaReceber[NumeroDocumento]; 	// Nosso Numero
	$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Numero do Pedido ou Nosso Numero
	$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
	$dadosboleto["valor"] 				= $linContaReceber[ValorLancamento];	// Valor do Boleto (Utilizar virgula como separador decimal, não use pontos)
	$dadosboleto["agencia"]				= $CobrancaParametro[Agencia]; 			// Numero da Agência até 4 Digitos s/DAC
	$dadosboleto["digito_agencia"]		= $CobrancaParametro[DigitoAgencia]; 	// 1 Digito da Agência
	$dadosboleto["conta"] 				= $CobrancaParametro[Conta];	 		// Numero da Conta Até 8 Digitos s/ Digito
	$dadosboleto["digito_conta"]		= $CobrancaParametro[DigitoConta]; 		// Digito da Conta Corrente 1 Digito
	$dadosboleto["convenio"]			= $CobrancaParametro[Convenio];		  	// Numero do Convenio (se a sua conta nâo tem convenio deixe este campo como "")
	$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];  		// Código da Carteira 
	$dadosboleto["layout_boleto"]	 	= $CobrancaParametro[LayoutBoleto];		// Layout do boleto Banco do Brasil 1, 2, 3, 4 ou 5 (Ver Abaixo)
	$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento];
	$dadosboleto["quantidade"]			= "1";
	$dadosboleto["valor_unitario"]	 	= $linContaReceber[ValorLancamento];
	$dadosboleto["contrato"]			= $CobrancaParametro[Contrato];
	$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];			
	$dadosboleto["uso_banco"]			= $CobrancaParametro[UsoBanco]; 	
	$dadosboleto["especie"]				= $CobrancaParametro[Especie]; 
	$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento]; 
	$dadosboleto["LocalPagamento"]		= $CobrancaParametro[LocalPagamento]; 
	include("include/funcoes_diversas.php");
	$b = new boleto();		
	$b->banco_brasil($dadosboleto);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><?=$tituloBoleto?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=windows-1252">
<META content="MSHTML 6.00.2800.1400" name=GENERATOR>
<style type="text/css">
<!--
.ld {font: bold 15px Arial; color: #000000}
.style2 {font-family: Arial, Helvetica, sans-serif}
-->
</style>
</HEAD>
<BODY>
<STYLE>BODY {
	FONT: 10px Arial
}
#cabecalho, #quadro{
	width:650px; 
	border-bottom: 1px #000 solid;
}
#cabecalho{
	height: 45px; 
	padding: 5px;
	text-align: right;
}
#quadro{
	height: 335px;
	text-align:left;
	margin-top: 10px;
	font-size: 11px;
}
#quadro table{
	font-size: 11px;
	margin: 0 0 15px 0;
	width:650px;
}
#quadro table tr th, #quadro table tr td{
	border-bottom: 1px #7C8286 solid;
}
#quadro p{
	margin: 0;
	font-size: 12px;
}
.Titulo {
	FONT: 9px Arial Narrow; COLOR: navy
}
.Campo {
	FONT: 10px Arial; COLOR: black
}
TD.Normal {
	FONT: 12px Arial; COLOR: black
}
TD.Titulo {
	FONT: 9px Arial Narrow; COLOR: navy
}
TD.Campo {
	FONT: bold 10px Arial; COLOR: black
}
TD.CampoTitulo {
	FONT: bold 15px Arial; COLOR: navy
}
</STYLE>
<DIV align=center>
<div id='cabecalho'>
	<?
		include("../cabecalho_html.php");
		include("../demonstrativo_html.php");
	?>
	<TABLE cellSpacing=0 cellPadding=0 width=650 border=0>
	  <TBODY>
	  <TR>
		<TD class=campo width=150><IMG height=22 
		  src="imagens/imgbb.gif" width=150 
		  border=0></TD>
		<TD width=3><IMG height=22 
		  src="imagens/imgbrrazu.gif" width=2 
		  border=0></TD>
		<TD class=campotitulo align=middle width=46>001-9</TD>
		<TD width=3><IMG height=22 
		  src="imagens/imgbrrazu.gif" width=2 
		  border=0></TD>
		<TD class=campotitulo align=right width=464><span class="ld">
		  <?=$dadosboleto["linha_digitavel"]?>
		</span> &nbsp;&nbsp;&nbsp; </TD>
	  </TR>
	  <TR>
		<TD colSpan=5><IMG height=2 
		  src="imagens/imgpxlazu.gif" width=650 
		  border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=282 height=13>Cedente</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=126 height=13>Agência / Código do Cedente</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=34 height=13>Espécie</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=53 height=13>Quantidade</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=120 height=13>Nosso número</TD></TR>
  <TR>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top width=282 height=12><? echo substr($dadosboleto["cedente"],0,45); ?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=right width=126 height=12><?=$dadosboleto["agencia_codigo"]?> 
	&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=middle width=34 height=12><?=$dadosboleto["especie"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=middle width=53 height=12><?=$dadosboleto["quantidade"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=right width=120 
	height=12><?=$dadosboleto["nosso_numero"]?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
  </TR>
  <TR>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=282 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=282 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=126 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=126 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=34 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=34 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=53 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=53 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=120 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=120 
	  border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=113 height=13>Número do documento</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=72 height=13>Contrato</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=132 height=13>CPF/CEI/CNPJ</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=134 height=13>Vencimento</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=164 height=13>Valor documento</TD></TR>
  <TR>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top width=113 height=12><?=$dadosboleto["numero_documento"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top width=72 height=12><?=$dadosboleto["contrato"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top width=132 height=12><?=$dadosboleto["cpf_cnpj"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=middle width=134 
	height=12><?=$dadosboleto["data_vencimento"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=right width=164 
  height=12><?=$dadosboleto["valor"]?>  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
  </TR>
  <TR>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=113 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=113 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=72 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=72 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=132 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=132 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=134 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=134 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=164 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=164 
	  border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=113 height=13>(-) Desconto / 
	Abatimento</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=112 height=13>(-) Outras deduções</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=113 height=13>(+) Mora / Multa</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=113 height=13>(+) Outros acréscimos</TD>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=164 bgColor=#ffffcc height=13>(=) Valor 
	  cobrado</TD></TR>
  <TR>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=left width=113 height=12><?=$dadosboleto["valor_desconto"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=left width=112 height=12><?=$dadosboleto["valor_outras_deducoes"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=left width=113 height=12><?=$dadosboleto["valor_mora_multa"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=left width=113 height=12><?=$dadosboleto["valor_outros_acrescimos"]?>&nbsp;</TD>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top align=right width=164 bgColor=#ffffcc 
	height=12><?=$dadosboleto["valor_cobrado"]?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD></TR>
  <TR>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=113 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=113 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=112 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=112 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=113 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=113 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=113 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=113 
	  border=0></TD>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=164 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=164 
	  border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
	<TD class=titulo vAlign=top width=7 height=13><IMG height=13 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=titulo vAlign=top width=643 height=13>Sacado</TD></TR>
  <TR>
	<TD class=campo vAlign=top width=7 height=12><IMG height=12 
	  src="imagens/imgbrrlrj.gif" width=5 
	  border=0></TD>
	<TD class=campo vAlign=top width=643 height=12><?=$dadosboleto["sacado"]?> 
	&nbsp;</TD></TR>
  <TR>
	<TD vAlign=top width=7 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=7 
	  border=0></TD>
	<TD vAlign=top width=643 height=3><IMG height=1 
	  src="imagens/imgpxlazu.gif" width=643 
	  border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
	<TD class=titulo vAlign=top width=7 height=13></TD>
	<TD class=titulo vAlign=top width=7 height=13></TD>
	<TD class=titulo vAlign=top width=150 height=13>Autenticação mecânica</TD></TR>
  <TR>
	<TD vAlign=top width=7 height=3></TD>
	<TD vAlign=top width=564 height=3></TD>
	<TD vAlign=top width=7 height=3></TD>
	<TD vAlign=top width=88 height=3></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width=650 border=0>
  <TBODY>
  <TR>
	<TD class=titulo width=650>Corte na linha pontilhada</TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width=650 border=0>
  <TBODY>
  <TR>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD>
    <TD width=5><IMG height=1 
      src="imagens/imgpxlazu.gif" width=6 
      border=0></TD>
    <TD width=5></TD></TR></TBODY></TABLE><BR>
<TABLE cellSpacing=0 cellPadding=0 width=650 border=0>
  <TBODY>
  <TR>
    <TD class=campo width=150><IMG height=22 
      src="imagens/imgbb.gif" width=150 
      border=0></TD>
    <TD width=3><IMG height=22 
      src="imagens/imgbrrazu.gif" width=2 
      border=0></TD>
    <TD class=campotitulo align=middle width=46>001-9</TD>
    <TD width=3><IMG height=22 
      src="imagens/imgbrrazu.gif" width=2 
      border=0></TD>
    <TD class=campotitulo align=right width=464><?=$dadosboleto["linha_digitavel"]?> &nbsp;&nbsp;&nbsp; </TD></TR>
  <TR>
    <TD colSpan=5><IMG height=2 
      src="imagens/imgpxlazu.gif" width=650 
      border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=472 height=13>Local de pagamento</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=164 bgColor=#ffffcc 
    height=13>Vencimento</TD></TR>
  <TR>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top width=472 height=12><?=$dadosboleto["LocalPagamento"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=right width=164 bgColor=#ffffcc 
      height=12><?=$dadosboleto["data_vencimento"]?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
  </TR>
  <TR>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=472 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=472 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=164 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=164 
      border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=472 height=13>Cedente</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=164 height=13>Agência/Código 
  cedente</TD></TR>
  <TR>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top width=472 height=12><?=$dadosboleto["cedente"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=right width=164 
      height=12><?=$dadosboleto["agencia_codigo"]?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
  </TR>
  <TR>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=472 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=472 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=164 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=164 
      border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=93 height=13>Data do documento</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=173 height=13>N<U>o</U> documento</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=72 height=13>Espécie doc.</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=34 height=13>Aceite</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=72 height=13>Data process.</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=164 height=13>Nosso número</TD></TR>
  <TR>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=middle width=93 
    height=12><?=$dadosboleto["data_documento"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top width=173 height=12><?=$dadosboleto["numero_documento"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=middle width=72 height=12><?=$dadosboleto["especie_doc"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=middle width=34 height=12><?=$dadosboleto["aceite"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=middle width=72 
    height=12><?=$dadosboleto["data_processamento"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=right width=164
    height=12><?=$dadosboleto["nosso_numero"]?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
  </TR>
  <TR>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=93 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=93 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=173 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=173 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=72 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=72 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=34 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=34 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=72 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=72 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=164 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=164
      border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=93 bgColor=#ffffcc height=13>Uso do 
    banco</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=93 height=13>Carteira</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=53 height=13>Espécie</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=133 height=13>Quantidade</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=72 height=13>x Valor</TD>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=164 height=13>(=) Valor documento</TD></TR>
  <TR>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top width=93 bgColor=#ffffcc height=12>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=middle width=93 height=12><?=$dadosboleto["carteira"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=middle width=53 height=12><?=$dadosboleto["especie"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=middle width=53 height=12><?=$dadosboleto["quantidade"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=right width=72 height=12><?=$dadosboleto["valor_unitario"]?>&nbsp;</TD>
    <TD class=campo vAlign=top width=7 height=12><IMG height=12 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top align=right width=164 
  height=12><?=$dadosboleto["valor"]?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
  </TR>
  <TR>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=93 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=93 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=93 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=93 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=53 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=53 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=133 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=133 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=72 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=72 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=164 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=164 
      border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width=650 border=0>
  <TBODY>
  <TR>
    <TD width=7 rowSpan=5></TD>
    <TD vAlign=top width=447 rowSpan=5>
		<FONT class=titulo>Instruções (Texto de responsabilidade do cedente)</FONT><BR>
		<FONT class=campo>
	  <?
		InstrucoesBoletoHTML($IdContaReceber);
	  ?>
		</FONT>
	</TD>
    <TD align=right width=212>
      <TABLE cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD class=titulo vAlign=top width=7 height=13></TD>
          <TD class=titulo vAlign=top width=18 height=13></TD>
          <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=titulo vAlign=top width=164 height=13>(-) Desconto / 
            Abatimento</TD></TR>
        <TR>
          <TD class=campo vAlign=top width=7 height=12></TD>
          <TD class=campo vAlign=top width=18 height=12>&nbsp;</TD>
          <TD class=campo vAlign=top width=7 height=12><IMG height=12 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=campo vAlign=top align=right width=164 
        height=12>&nbsp;</TD></TR>
        <TR>
          <TD vAlign=top width=7 height=3></TD>
          <TD vAlign=top width=18 height=3></TD>
          <TD vAlign=top width=7 height=3><IMG height=1 
            src="imagens/imgpxlazu.gif" width=7 
            border=0></TD>
          <TD vAlign=top width=164 height=3><IMG height=1 
            src="imagens/imgpxlazu.gif" 
            width=164 border=0></TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TD align=right width=212>
      <TABLE cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD class=titulo vAlign=top width=7 height=13></TD>
          <TD class=titulo vAlign=top width=18 height=13></TD>
          <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=titulo vAlign=top width=164 height=13>(-) Outras 
          deduções</TD></TR>
        <TR>
          <TD class=campo vAlign=top width=7 height=12></TD>
          <TD class=campo vAlign=top width=18 height=12>&nbsp;</TD>
          <TD class=campo vAlign=top width=7 height=12><IMG height=12 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=campo vAlign=top align=right width=164 
        height=12>&nbsp;</TD></TR>
        <TR>
          <TD vAlign=top width=7 height=3></TD>
          <TD vAlign=top width=18 height=3></TD>
          <TD vAlign=top width=7 height=3><IMG height=1 
            src="imagens/imgpxlazu.gif" width=7 
            border=0></TD>
          <TD vAlign=top width=164 height=3><IMG height=1 
            src="imagens/imgpxlazu.gif" 
            width=164 border=0></TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TD align=right width=212>
      <TABLE cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD class=titulo vAlign=top width=7 height=13></TD>
          <TD class=titulo vAlign=top width=18 height=13></TD>
          <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=titulo vAlign=top width=164 height=13>(+) Mora / 
        Multa</TD></TR>
        <TR>
          <TD class=campo vAlign=top width=7 height=12></TD>
          <TD class=campo vAlign=top width=18 height=12>&nbsp;</TD>
          <TD class=campo vAlign=top width=7 height=12><IMG height=12 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=campo vAlign=top align=right width=164 
        height=12>&nbsp;</TD></TR>
        <TR>
          <TD vAlign=top width=7 height=3></TD>
          <TD vAlign=top width=18 height=3></TD>
          <TD vAlign=top width=7 height=3><IMG height=1 
            src="imagens/imgpxlazu.gif" width=7 
            border=0></TD>
          <TD vAlign=top width=164 height=3><IMG height=1 
            src="imagens/imgpxlazu.gif" 
            width=164 border=0></TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TD align=right width=212>
      <TABLE cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=titulo vAlign=top width=164 height=13>(+) Outros 
          acréscimos</TD></TR>
        <TR>
          <TD class=campo vAlign=top width=7 height=12><IMG height=12 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=campo vAlign=top align=right width=164 
        height=12>&nbsp;</TD></TR>
        <TR>
          <TD vAlign=top width=7 height=3><IMG height=1 
            src="imagens/imgpxlazu.gif" width=7 
            border=0></TD>
          <TD vAlign=top width=164 height=3><IMG height=1 
            src="imagens/imgpxlazu.gif" 
            width=164 border=0></TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TD align=right width=212>
      <TABLE cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=titulo vAlign=top width=164 bgColor=#ffffcc height=13>(=) 
            Valor cobrado</TD></TR>
        <TR>
          <TD class=campo vAlign=top width=7 height=12><IMG height=12 
            src="imagens/imgbrrlrj.gif" width=5 
            border=0></TD>
          <TD class=campo vAlign=top align=right width=164 bgColor=#ffffcc 
          height=12>&nbsp;</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width=650 border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width=650 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=650 
      border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD class=titulo vAlign=top width=7 height=13><IMG height=13 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=titulo vAlign=top width=643 height=13>Sacado</TD></TR>
  <TR>
    <TD class=campo vAlign=top width=7 height=36><IMG height=36 
      src="imagens/imgbrrlrj.gif" width=5 
      border=0></TD>
    <TD class=campo vAlign=top width=643 height=36><?=$dadosboleto["sacado"]?><BR><?=$dadosboleto["endereco1"]?><BR><?=$dadosboleto["endereco2"]?>&nbsp;&nbsp;
	</TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=472 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=472 
      border=0></TD>
    <TD vAlign=top width=7 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=7 
      border=0></TD>
    <TD vAlign=top width=164 height=3><IMG height=1 
      src="imagens/imgpxlazu.gif" width=164 
      border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width=650 border=0>
  <TBODY>
  <TR>
    <TD><? fbarcode($dadosboleto["codigo_barras"]); ?></TD>
	<TD style='text-align:right'>
		<?
			if($dadosboleto["cob_outro"] == 'S'){
				echo "<img src='../../../../img/estrutura_sistema/ico_estrela.jpg'>";
			}
		?>
	</TD></TR></TBODY></TABLE>
</div>
</DIV>
</BODY></HTML>
