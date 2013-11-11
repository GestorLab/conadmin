<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Versуo Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo estс disponэvel sob a Licenчa GPL disponэvel pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Vocъ deve ter recebido uma cѓpia da GNU Public License junto com     |
// | esse pacote; se nуo, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaboraчѕes de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de Joуo Prado Maia e Pablo Martins F. Costa                |
// | 																	  |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordenaчуo Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto Banco Amazєnia: Weiner Soares de Lima		  |
// +----------------------------------------------------------------------+



	$codigobanco 			= "003";
	$codigo_banco_com_dv 	= geraCodigoBanco($codigobanco);
	$agencia_com_dv			= $dadosboleto["agencia"].$dadosboleto["agencia_digito"];
	$convenio				= $dadosboleto["convenio"];
	$nummoeda 				= "9";
	$fator_vencimento 		= fator_vencimento($dadosboleto["data_vencimento"]);
	
	//valor tem 10 digitos, sem virgula
	$valor 			= formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
	//carteira щ CNR
	$carteira 		= $dadosboleto["carteira"];
	//codigocedente deve possuir 7 caracteres
	$codigocedente 	= formata_numero($dadosboleto["codigo_cedente"],7,0);
	
	$ndoc 			= $dadosboleto["numero_documento"];
	$vencimento 	= $dadosboleto["data_vencimento"];
	
	// nњmero do documento (sem dvs) щ 13 digitos
	$nnum 			= $dadosboleto["numero_documento"];
	$nnum			= str_pad($nnum, 16, "0", STR_PAD_RIGHT); // Substituindo a linha acima;
	
	$nnum2 			= $dadosboleto["numero_documento"];
	$nnum2			= str_pad($nnum, 16, "0", STR_PAD_RIGHT); // Substituindo a linha acima;

	// nosso nњmero (com dvs) щ 16 digitos
	//$nossonumero 	= geraNossoNumero($nnum,$codigocedente,$vencimento,'4');
	$nossonumero = $dadosboleto["numero_documento"];
	$nossonumero = str_pad($ndoc, 16, "0", STR_PAD_LEFT); // Substituindo a linha acima;
	
	$vencjuliano 	= dataJuliano($vencimento);
	$app 			= "8"; // Identificador Sistema
	
	// 43 numeros para o calculo do digito verificador do codigo de barras
	//echo $fator_vencimento;

	$barra 			= "$codigobanco$nummoeda$fator_vencimento$valor$agencia_com_dv$convenio$nnum$app";
	
	$dv 			= digitoVerificador_barra($barra, 9, 0);

	$DAC_Barra		= modulo_11($barra);
	$DAC_Linha		= modulo_10($barra);
	
	$linha 			= "$codigobanco$nummoeda$DAC_Linha$fator_vencimento$valor$agencia_com_dv$convenio$nnum$app";
	$barra 			= "$codigobanco$nummoeda$DAC_Barra$fator_vencimento$valor$agencia_com_dv$convenio$nnum2$app";

	

	// Numero para o codigo de barras com 44 digitos
	//$linha 			= substr($barra,0,4) . $dv . substr($barra,4);
	
	//$agencia_codigo = $codigocedente;
		
	$dadosboleto["codigo_barras"] 		= $barra;
	$dadosboleto["linha_digitavel"] 	= monta_linha_digitavel($linha);
	$linha								= $dadosboleto["linha_digitavel"];
	$linha{38}							= $dv; 
	$dadosboleto["linha_digitavel"]		= $linha;
	$dadosboleto["agencia_codigo"] 		= $agencia_codigo;
	$dadosboleto["nosso_numero"] 		= $nossonumero;
	$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
?>