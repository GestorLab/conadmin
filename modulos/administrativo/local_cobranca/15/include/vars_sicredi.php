<?
	$codigobanco = "748";
	$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
	$nummoeda = "9";
	$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

	//valor tem 10 digitos, sem virgula
	$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
	//agencia é 4 digitos
	$agencia = formata_numero($dadosboleto["agencia"],4,0);
	//posto da cooperativa de credito é dois digitos
	$posto = formata_numero($dadosboleto["posto"],2,0);
	
	if($dadosboleto["IdTipoLocalCobranca"] == 4){					
		// Codigo referente ao tipo de cobrança: "1" - SICREDI COM REGISTRO
		$tipo_cobranca = 1;
	}else{		
		// Codigo referente ao tipo de cobrança: "3" - SICREDI SEM REGISTRO
		$tipo_cobranca = 3;
	}
	
	//codigo do cedente é 5 digitos
	$conta = formata_numero($dadosboleto["codigo_cedente"],5,0);
	
	//dv da conta
	$conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
	//carteira é 2 caracteres
	$carteira = $dadosboleto["carteira"];

	//fillers - zeros Obs: filler1 contera 1 quando houver valor expresso no campo valor
	$filler1 = 1;
	$filler2 = 0;

	// Byte de Identificação do cedente 1 - Cooperativa; 2 a 9 - Cedente
	$byteidt = $dadosboleto["byte_idt"];
	
	// Codigo referente ao tipo de carteira: "1" - Carteira Simples 
	$tipo_carteira = 1;

	//nosso número (sem dv) é 8 digitos
	$nnum = $dadosboleto["inicio_nosso_numero"] . $byteidt . formata_numero($dadosboleto["nosso_numero"],5,0);

	//calculo do DV do nosso número
	$dv_nosso_numero = digitoVerificador_nossonumero("$agencia$posto$conta$nnum");

	$nossonumero_dv ="$nnum$dv_nosso_numero";

	//formação do campo livre
	$campolivre = "$tipo_cobranca$tipo_carteira$nossonumero_dv$agencia$posto$conta$filler1$filler2";
	$campolivre_dv = $campolivre . digitoVerificador_campolivre($campolivre); 

	// 43 numeros para o calculo do digito verificador do codigo de barras
	$dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$campolivre_dv", 9, 0);

	// Numero para o codigo de barras com 44 digitos
	$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$campolivre_dv";

	// Formata strings para impressao no boleto
	$nossonumero = substr($nossonumero_dv,0,2).'/'.substr($nossonumero_dv,2,6).'-'.substr($nossonumero_dv,8,1);
	$agencia_codigo = $agencia.".". $posto.".".$conta;

	$dadosboleto["codigo_barras"] = $linha;
	$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
	$dadosboleto["agencia_codigo"] = $agencia_codigo;
	$dadosboleto["nosso_numero"] = $nossonumero;
	$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
?>
