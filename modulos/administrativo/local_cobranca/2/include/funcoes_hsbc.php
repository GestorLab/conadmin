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
// | Desenvolvimento Boleto HSBC: Bruno Leonardo M. F. Gonчalves          |
// +----------------------------------------------------------------------+



$codigobanco = "399";
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";
$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

//valor tem 10 digitos, sem virgula
$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
//carteira щ CNR
$carteira = $dadosboleto["carteira"];
//codigocedente deve possuir 7 caracteres
$codigocedente = formata_numero($dadosboleto["codigo_cedente"],7,0);

$ndoc = $dadosboleto["numero_documento"];
$vencimento = $dadosboleto["data_vencimento"];

// nњmero do documento (sem dvs) щ 13 digitos
$nnum = formata_numero($dadosboleto["numero_documento"],13,0);
// nosso nњmero (com dvs) щ 16 digitos
$nossonumero = geraNossoNumero($nnum,$codigocedente,$vencimento,'4');
#$nossonumero = str_pad($ndoc, 16, "0", STR_PAD_LEFT); // Substituindo a linha acima;

$vencjuliano = dataJuliano($vencimento);
$app = "2";

// 43 numeros para o calculo do digito verificador do codigo de barras
$barra = "$codigobanco$nummoeda$fator_vencimento$valor$codigocedente$nnum$vencjuliano$app";
$dv = digitoVerificador_barra($barra, 9, 0);
// Numero para o codigo de barras com 44 digitos
$linha = substr($barra,0,4) . $dv . substr($barra,4);

$agencia_codigo = $codigocedente;

$dadosboleto["codigo_barras"] = $linha;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
$dadosboleto["agencia_codigo"] = $agencia_codigo;
$dadosboleto["nosso_numero"] = $nossonumero;
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
?>