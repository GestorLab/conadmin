<?
	$codigobanco = "356";
	$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
	$nummoeda = "9";
	$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

	//valor tem 10 digitos, sem virgula
	$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
	//agencia é 4 digitos
	$agencia = formata_numero($dadosboleto["agencia"],4,0);
	//conta é 7 digitos
	$conta = formata_numero($dadosboleto["conta"],7,0);
	//carteira é 2 caracteres
	$carteira = $dadosboleto["carteira"];

	//nosso número com maximo de 13 digitos
	$nossonumero = formata_numero($dadosboleto["nosso_numero"],13,0);

	// Digitao - Digito de Cobranca do banco Real
	$digitao_cobranca = modulo_10("$nossonumero$agencia$conta");

	$linha = "$codigobanco$nummoeda" . "0$fator_vencimento$valor$agencia$conta$digitao_cobranca$nossonumero";
	dvBarra($linha);

	$agencia_codigo = $agencia."/". $conta ."/". $digitao_cobranca;


	$dadosboleto["codigo_barras"] = $linha;
	$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
	$dadosboleto["agencia_codigo"] = $agencia_codigo;
	$dadosboleto["nosso_numero"] = $nossonumero;
	$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
?>