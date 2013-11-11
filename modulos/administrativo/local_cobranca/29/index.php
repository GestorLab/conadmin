<?
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");

	$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento];		// Data de Vencimento do Boleto
	$dadosboleto["numero_documento"] 	= $linContaReceber[NumeroDocumento]; 	// Nosso Numero
	$dadosboleto["nosso_numero"]		= $linContaReceber[NumeroDocumento];	// Nosso numero sem o DV - REGRA: Máximo de 8 caracteres!
	$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
	$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
	$dadosboleto["valor"]				= $linContaReceber[ValorLancamento];	// Valor do Boleto (Utilizar virgula como separador decimal, não use pontos)
	$dadosboleto["agencia"]				= $CobrancaParametro[Agencia]; 		// Numero da Agência 4 Digitos s/DAC
	$dadosboleto["conta"] 				= $CobrancaParametro[Conta]; 		// Numero da Conta 5 Digitos s/ DAC
	$dadosboleto["contadigito"]			= $CobrancaParametro[ContaDigito];  // Digito da Conta Corrente 1 Digito
	$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];		// Código da Carteira
	$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento];
	$dadosboleto["quantidade"]			= 1;
	$dadosboleto["valor_unitario"] 		= $linContaReceber[ValorLancamento];
	$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];			
	$dadosboleto["uso_banco"]			= $CobrancaParametro[UsoBanco];
	$dadosboleto["especie"]				= $CobrancaParametro[Especie]; 


	include("include/funcoes-itau.php"); 

	$b	= new boleto();
	$b->banco_itau($dadosboleto);
	$codigobanco = "341-7";

	include("include/layout-itau.php");
?>