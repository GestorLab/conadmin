<?php
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");

// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
	
	// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
	$dadosboleto["quantidade"]			= "1";
	$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];
	$dadosboleto["especie"]				= $CobrancaParametro[Especie];
	$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento];

	// DADOS DA SUA CONTA - CEF
	$dadosboleto["agencia"]			 	= $CobrancaParametro[Agencia]; // Num da agencia, sem digito
	$dadosboleto["conta"]			 	= $CobrancaParametro[Conta]; 	// Num da conta, sem digito
	$dadosboleto["conta_dv"]		 	= $CobrancaParametro[ContaDigito]; 	// Digito do Num da conta
	$dadosboleto["conta_cedente"] 	 	= $CobrancaParametro[Conta]; // ContaCedente do Cliente, sem digito (Somente Números)
	$dadosboleto["conta_cedente_dv"] 	= $CobrancaParametro[ContaDigito]; // Digito da ContaCedente do Cliente

	// DADOS PERSONALIZADOS - CEF
	$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];
	$dadosboleto["cod_carteira"]		= $dadosboleto["carteira"];
	
	$dadosboleto["nosso_numero"]		= $linContaReceber[NumeroDocumento];
	$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Num do pedido ou do documento
	$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento]; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; // Data de emissão do Boleto
	$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento]; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
	$dadosboleto["valor_unitario"]		= $dadosboleto["valor_boleto"];	
	
	
	include("include/funcoes_bnb.php");
	include("include/vars_bnb.php");
	include("include/layout_bnb.php");
?>
