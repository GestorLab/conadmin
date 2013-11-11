<?
	$codigobanco = "756";
	$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
	$nummoeda = "9";
	
	$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

	//valor tem 10 digitos, sem virgula
	$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
	//agencia é sempre 4 digitos
	$agencia = formata_numero($dadosboleto["agencia"],4,0);
	//conta é sempre 8 digitos

	$conta = formata_numero($dadosboleto["codigo_cliente"].$dadosboleto["codigo_cliente_digito"],7,0);
	$conta_digito = $dadosboleto["codigo_cliente_digito"];

	$carteira = $dadosboleto["carteira"];

	//Zeros: usado quando convenio de 7 digitos
	$livre_zeros='000000';
	$modalidadecobranca = $dadosboleto["modalidade_cobranca"];
	$numeroparcela      = $dadosboleto["numero_parcela"];

	#$convenio = formata_numero($dadosboleto["convenio"],7,0);
	$convenio = $conta;
	//agencia e conta
	$agencia_codigo = $agencia ." / ". $conta;

	// Nosso número de até 8 dígitos - 2 digitos para o ano e outros 6 numeros sequencias por ano 
	// deve ser gerado no programa boleto_bancoob.php

	$nossonumero = formata_numero($dadosboleto["nosso_numero"],7,0);
	$nossonumero_dv = $agencia.str_pad($dadosboleto["codigo_cliente"], 9, "0", STR_PAD_LEFT).$conta_digito.str_pad($nossonumero, 7, "0", STR_PAD_LEFT);
	$nossonumero_dv = mod11($nossonumero_dv);
	#$nossonumero_dv = nosso_numero_dv($agencia, formata_numero($dadosboleto["codigo_cliente"],7,0), $conta_digito, $nossonumero);
	$nossonumero .= $nossonumero_dv;


	$campolivre  = "$modalidadecobranca$convenio$nossonumero$numeroparcela";

	$dv=modulo_11("$codigobanco$nummoeda$fator_vencimento$valor$carteira$agencia$campolivre");

	$linha="$codigobanco$nummoeda$dv$fator_vencimento$valor$carteira$agencia$campolivre";

	$dadosboleto["codigo_barras"] = $linha;
	$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
	$dadosboleto["agencia_codigo"] = $agencia_codigo;
	$dadosboleto["nosso_numero"] = $nossonumero;
	$dadosboleto["nosso_numero_visual"] = substr($nossonumero,0,7)."-".substr($nossonumero,7,1);
	$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
?>