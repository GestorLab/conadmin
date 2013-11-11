<?php
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");
	
	$dadosboleto["inicio_nosso_numero"]	= date("y");
	$dadosboleto["nosso_numero"]		= $linContaReceber[NumeroDocumento];	
	$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Num do pedido ou do documento
	$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento]; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; // Data de emissão do Boleto
	$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento]; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
	$dadosboleto["quantidade"]			= "1";
	$dadosboleto["valor_unitario"]		= $dadosboleto["valor_boleto"];
	$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];
	$dadosboleto["especie"]				= $CobrancaParametro[Especie];
	$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento];	
	$dadosboleto["agencia"]				= $CobrancaParametro[Agencia]; // Num da agencia, sem digito
	$dadosboleto["conta"]				= $CobrancaParametro[Conta]; 	// Num da conta, sem digito
	$dadosboleto["conta_dv"]			= $CobrancaParametro[ContaDigito]; 	// Digito do Num da conta
	$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];
	$dadosboleto["posto"]				= $CobrancaParametro[Posto];
	$dadosboleto["byte_idt"]			= $CobrancaParametro[ByteIDT];	

	include("include/funcoes_sicredi.php");
	include("include/vars_sicredi.php");
	include("include/layout_sicredi.php");
?>