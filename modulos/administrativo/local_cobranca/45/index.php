<?php
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");

	$dadosboleto["nosso_numero"]			= $linContaReceber[NumeroDocumento]; // tamanho 9
	$dadosboleto["numero_documento"]		= $linContaReceber[NumeroDocumento];	// Num do pedido ou do documento
	$dadosboleto["data_vencimento"]			= $linContaReceber[DataVencimento]; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"]			= $linContaReceber[DataLancamento]; // Data de emiss�o do Boleto
	$dadosboleto["data_processamento"]		= $linContaReceber[DataLancamento]; // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"]			= $linContaReceber[ValorLancamento]; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula
	$dadosboleto["local_pagamento"]			= $CobrancaParametro[DescricaoLocalPagamento];
	$dadosboleto["quantidade"]				= "1";
	$dadosboleto["valor_unitario"]			= $dadosboleto["valor_boleto"];
	$dadosboleto["aceite"]					= $CobrancaParametro[Aceite];
	$dadosboleto["especie"]					= $CobrancaParametro[Especie];
	$dadosboleto["especie_doc"]				= $CobrancaParametro[EspecieDocumento];
	$dadosboleto["ponto_venda"]				= $CobrancaParametro[Agencia]; // Num da agencia, sem digito
	$dadosboleto["codigo_cliente"]			= $CobrancaParametro[CodigoCedente];
	$dadosboleto["carteira"]				= $CobrancaParametro[Carteira];

	include("include/funcoes_santander_banespa.php"); 
	include("include/vars_santander_banespa.php");
	include("include/layout_santander_banespa.php");
?>