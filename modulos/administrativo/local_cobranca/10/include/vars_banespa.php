<?
	$codigobanco = "033";
	$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
	$nummoeda = "9";
	$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

	//valor tem 10 digitos, sem virgula
	$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
	//Modalidade Carteira
	$carteira = $dadosboleto["carteira"];
	//codigocedente deve possuir 11 caracteres
	$codigocliente = formata_numero($dadosboleto["codigo_cedente"],11,0,"valor");

	// Formata no pedido para colocar zero à esquerda
	$nossonumero   = substr("0000000", strlen($dadosboleto['nosso_numero'])).$dadosboleto['nosso_numero'];

	// Calcula vencimento juliano
	$vencjuliano = dataJuliano($vencimento);

	// Calcula Campo Livre
	$campoLivre = calculaCampoLivre($codigocliente.$nossonumero."00".$codigobanco);

	// 43 números para o cálculo do dígito verificador do código de barras
	// retorna 44 números que são 43 + 1 dígito verificador formando 44 posições
	$linha = monta_codigo_de_barras($codigobanco.$nummoeda.$fator_vencimento.$valor.$codigocliente.$nossonumero."00".$codigobanco.substr($campoLivre, strlen($campoLivre)-2));

	$dadosboleto["codigo_barras"] = $linha;
	$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
	$dadosboleto["nosso_numero"] = calcula_verificador_nosso_numero($dadosboleto["ponto_venda"], $nossonumero);
	$dadosboleto["agencia_conta"] = substr($dadosboleto["codigo_cedente"],0,3)." ".substr($dadosboleto["codigo_cedente"],3,2)." ".substr($dadosboleto["codigo_cedente"],5,5)." ".substr($dadosboleto["codigo_cedente"],10);
	$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
?>