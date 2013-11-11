<?
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");
	
	$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento];		// Data de Vencimento do Boleto
	$dadosboleto["numero_documento"] 	= $linContaReceber[NumeroDocumento]; 	// Nosso Numero
	$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
	$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
	$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento];	// Valor do Boleto (Utilizar virgula como separador decimal, não use pontos)
	$dadosboleto["agencia"]				= $CobrancaParametro[Agencia];
	$dadosboleto["agencia_digito"]		= $CobrancaParametro[DigitoAgencia];
	
	$dadosboleto["convenio"]			= $CobrancaParametro[Convenio];
	$dadosboleto["conta_corrente"]		= $CobrancaParametro[Conta];
	$dadosboleto["agencia_conta"]		= $CobrancaParametro["Agencia"]."-".$CobrancaParametro["DigitoAgencia"]."/".$CobrancaParametro["Conta"]."-".$CobrancaParametro["DigitoConta"];
	$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];		  // Código da Carteira
	$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];			
	$dadosboleto["uso_banco"]			= $CobrancaParametro[UsoBanco]; 	
	$dadosboleto["especie"]				= $CobrancaParametro[Especie]; 
	$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento]; 
	$dadosboleto["quantidade"]			= "1";
	$dadosboleto["valor_unitario"]		= $dadosboleto["valor_boleto"];

	// NÃO ALTERAR!
	require("include/funcoes_diversas.php");
	include("include/funcoes_amazonia.php"); 
	include("include/layout_amazonia.php");
?>