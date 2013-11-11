<?php
	include ("../../../../files/conecta.php");
	include ("../../../../files/funcoes.php");

	$IdLoja				= $_POST['IdLoja'];
	$IdContaReceber		= $_POST['IdContaReceber'];
	$SeparadorCampos	= "&nbsp;&nbsp;-&nbsp;&nbsp;";

	include("../informacoes_default.php");

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
	$dadosboleto["banco_deposito"]		= $CobrancaParametro[BancoDeposito];
	$dadosboleto["numero_agencia"]		= $CobrancaParametro[AgenciaNumero];
	$dadosboleto["digito_agencia"]		= $CobrancaParametro[AgenciaDigito];
	$dadosboleto["numero_conta"]		= $CobrancaParametro[ContaNumero];
	$dadosboleto["digito_conta"]		= $CobrancaParametro[ContaDigito];	
	
	if($dadosboleto["digito_agencia"] != ''){
		$dadosboleto["numero_agencia"] = $dadosboleto["numero_agencia"]."-".$dadosboleto["digito_agencia"];
	}
	if($dadosboleto["digito_conta"] != ''){
		$dadosboleto["numero_conta"] = $dadosboleto["numero_conta"]."-".$dadosboleto["digito_conta"];	
	}
	
	include("include/funcoes_ficha.php");
	include("include/vars_ficha.php");
	include("include/layout_ficha.php");
?>
