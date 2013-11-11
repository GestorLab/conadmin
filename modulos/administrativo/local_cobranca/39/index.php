<?
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");

	$dadosboleto["data_vencimento"]			= $linContaReceber[DataVencimento];		// Data de Vencimento do Boleto
	$dadosboleto["numero_documento"] 		= $linContaReceber[NumeroDocumento]; 	// Nosso Numero
	$dadosboleto["nosso_numero"]			= $linContaReceber[NossoNumero];	// Nosso numero sem o DV - REGRA: M�ximo de 8 caracteres!
	$dadosboleto["data_documento"]			= $linContaReceber[DataLancamento]; 	// Data de emiss�o do Boleto
	$dadosboleto["data_processamento"]		= $linContaReceber[DataLancamento]; 	// Data de emiss�o do Boleto
	$dadosboleto["valor_boleto"]			= $linContaReceber[ValorLancamento];	// Valor do Boleto (Utilizar virgula como separador decimal, n�o use pontos)
	$dadosboleto["agencia"]					= $CobrancaParametro[Agencia]; 				// Agencia
	$dadosboleto["conta"]					= $CobrancaParametro[Conta]; 				// Conta
	$dadosboleto["conta_dv"]				= $CobrancaParametro[ContaDigito]; 			// Digito do Num da conta
	$dadosboleto["carteira"]				= $CobrancaParametro[Carteira];				// C�digo da Carteira // C�digo da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
	$dadosboleto["conta_cedente"]			= $CobrancaParametro[ContaCedente];			// ContaCedente do Cliente, sem digito (Somente N�meros)
	$dadosboleto["conta_cedente_dv"]		= $CobrancaParametro[ContaCedenteDigito];	// Digito da ContaCedente do Cliente
	$dadosboleto["local_pagamento"]			= $CobrancaParametro[LocalPagamento];
	$dadosboleto["aceite"]					= $CobrancaParametro[Aceite];
	$dadosboleto["especie"]					= $CobrancaParametro[Especie]; 
	$dadosboleto["especie_doc"]				= $CobrancaParametro[EspecieDocumento]; 
	$dadosboleto["quantidade"]				= "1";
	$dadosboleto["campo_fixo_obrigatorio"]	= "1";       // campo fixo obrigatorio - valor = 1 
	$dadosboleto["inicio_nosso_numero"]		= "9";          
	$dadosboleto["valor_unitario"]			= $dadosboleto["valor_boleto"];


	// N�O ALTERAR!
	include("include/funcoes_cef_sinco.php"); 
	include("include/vars_cef_sinco.php"); 
	include("include/layout_cef_sinco.php");
?>