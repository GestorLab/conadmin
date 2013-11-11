<?php
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");

	$dadosboleto["nosso_numero1"]		= $CobrancaParametro[InicioNossoNumero]."0"; // tamanho 3
	$dadosboleto["nosso_numero_const1"] = "2"; //constanto 1 , 1=registrada
	$dadosboleto["nosso_numero2"]		= "000"; // tamanho 3
	$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=para emissão do cedente
	$dadosboleto["nosso_numero3"]		= $linContaReceber[NumeroDocumento]; // tamanho 9
	$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Num do pedido ou do documento
	$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento]; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; // Data de emissão do Boleto
	$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento]; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
	$dadosboleto["local_pagamento"]		= $CobrancaParametro[LocalPagamento];
	$dadosboleto["quantidade"]			= "1";
	$dadosboleto["valor_unitario"]		= $dadosboleto["valor_boleto"];
	$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];
	$dadosboleto["especie"]				= $CobrancaParametro[Especie];
	$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento];
	$dadosboleto["agencia"]				= $CobrancaParametro[Agencia]; // Num da agencia, sem digito
	$dadosboleto["conta"]				= $CobrancaParametro[Conta]; 	// Num da conta, sem digito
	$dadosboleto["conta_dv"]			= $CobrancaParametro[ContaDigito]; 	// Digito do Num da conta
	$dadosboleto["conta_cedente"]		= $CobrancaParametro[ContaCedente]; // Código Cedente do Cliente, com 6 digitos (Somente Números)
	$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)

	// NÃO ALTERAR!
	include("include/funcoes_cef_sigcb.php"); 
	include("include/vars_cef.php"); 
	include("include/layout_cef.php");
?>