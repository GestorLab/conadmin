<?php
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");

	if($linContaReceber[NossoNumero] != ''){
		$dadosboleto["nosso_numero"]		= $linContaReceber[NossoNumero];
	}else{
		$dadosboleto["nosso_numero"]		= $linContaReceber[NumeroDocumento];
	}
	$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Num do pedido ou do documento
	$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento]; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; // Data de emiss�o do Boleto
	$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento]; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula
	$dadosboleto["quantidade"]			= "1";
	$dadosboleto["valor_unitario"]		= $dadosboleto["valor_boleto"];
	$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];
	$dadosboleto["especie"]				= $CobrancaParametro[Especie];
	$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento];
	$dadosboleto["agencia"]				= $CobrancaParametro[Agencia]; // Num da agencia, sem digito
	$dadosboleto["agenciaVisual"]		= $CobrancaParametro[AgenciaVisual]; // Num da agencia, sem digito
	$dadosboleto["digito_agencia"]		= $CobrancaParametro[DigitoAgencia]; // Num do digito da agencia
	$dadosboleto["digito_agenciaVisual"]= $CobrancaParametro[DigitoAgenciaVisual]; // Num do digito da agencia
	$dadosboleto["conta"]				= $CobrancaParametro[Conta]; 	// Num da conta, sem digito
	$dadosboleto["contaVisual"]			= $CobrancaParametro[ContaVisual]; 	// Num da conta, sem digito
	$dadosboleto["digito_conta"]		= $CobrancaParametro[DigitoConta]; 	// Digito do Num da conta
	$dadosboleto["digito_contaVisual"]	= $CobrancaParametro[DigitoContaVisual]; 	// Digito do Num da conta
	$dadosboleto["conta_cedente"]		= $CobrancaParametro[Conta]; // ContaCedente do Cliente, sem digito (Somente N�meros)
	$dadosboleto["conta_cedente_dv"]	= $CobrancaParametro[DigitoConta]; // Digito da ContaCedente do Cliente
	$dadosboleto["local_pagamento"]		= $CobrancaParametro[LocalPagamento];
	$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];
	$dadosboleto["cod_carteira"]		= $CobrancaParametro[Carteira];

	include("include/funcoes_banpara.php");
	include("include/vars_banpara.php");
	include("include/layout_banpara.php");
?>