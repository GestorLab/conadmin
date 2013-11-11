<?
	$codigobanco = "004";
	$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
	$nummoeda = "9";
	$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

	//valor tem 10 digitos, sem virgula
	$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
	//agencia é 4 digitos
	$agencia = formata_numero($dadosboleto["agencia"],4,0);
	//conta é 5 digitos
	$conta = formata_numero($dadosboleto["conta"],5,0);
	//dv da conta
	$conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
	//carteira é 2 caracteres
	$carteira = $dadosboleto["carteira"];
	$cod_carteira = $dadosboleto["cod_carteira"];

	//nosso número (sem dv) é 7 digitos
	$nnum = formata_numero($dadosboleto["nosso_numero"],7,0);

	//dv do nosso número
	$dv_nosso_numero = digitoVerificador_nossonumero($nnum);
	$nossonumero_dv ="$nnum$dv_nosso_numero";

	//conta cedente (sem dv) é 7 digitos
	$conta_cedente = formata_numero($dadosboleto["conta_cedente"],7,0);
	//dv da conta cedente
	$conta_cedente_dv = formata_numero($dadosboleto["conta_cedente_dv"],1,0);

	$ag_contacedente = $agencia . $conta_cedente;

	$livre = "$agencia$conta_cedente$conta_cedente_dv$nnum$dv_nosso_numero$cod_carteira"."000";

	// 43 numeros para o calculo do digito verificador do codigo de barras
	$dv = digitoVerificador_barra("$codigobanco$nummoeda$dv$fator_vencimento$valor$livre", 9, 0);
	// Numero para o codigo de barras com 44 digitos

	$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$livre";

	$nossonumero = substr($nossonumero_dv,0,7).'-'.substr($nossonumero_dv,7,1);
	$agencia_codigo = $agencia." / ". $conta_cedente."-". $conta_cedente_dv;

	$dadosboleto["codigo_barras"] = $linha;
	$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
	$dadosboleto["agencia_codigo"] = $agencia_codigo;
	$dadosboleto["nosso_numero"] = $nossonumero;
	$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
?>