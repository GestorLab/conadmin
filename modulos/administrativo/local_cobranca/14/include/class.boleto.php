<?
class Boleta extends FPDF
{
	function Cabecalho($IdLoja, $con){
		global $Path;
		include($Path."modulos/administrativo/local_cobranca/funcao_cabecalho_pdf.php");
	}
	
	function Demonstrativo($IdLoja, $IdContaReceber, $con){
		global $Path;
		include($Path."modulos/administrativo/local_cobranca/funcao_demonstrativo_pdf.php");
	}
	
	function DemonstrativoCarne($IdLoja, $IdCarne, $con){
		global $Path;
		include($Path."modulos/administrativo/local_cobranca/funcao_demonstrativo_carne_pdf.php");
	}

	function Titulo($IdLoja, $IdContaReceber, $con){

		global $Background;
		global $Path;
		global $dadosboleto;
		global $posY;
		
		include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");

		$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento];		// Data de Vencimento do Boleto
		$dadosboleto["numero_documento"] 	= $linContaReceber[NumeroDocumento]; 	// Nosso Numero
		$dadosboleto["nosso_numero"]		= $linContaReceber[NumeroDocumento];	// Nosso numero sem o DV - REGRA: Máximo de 8 caracteres!
		$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
		$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
		$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento];	// Valor do Boleto (Utilizar virgula como separador decimal, não use pontos)
		$dadosboleto["agencia"]				= $CobrancaParametro[Agencia]; 				// Agencia
		$dadosboleto["conta"]				= $CobrancaParametro[Conta]; 				// Conta
		$dadosboleto["conta_dv"]			= $CobrancaParametro[ContaDigito]; 			// Digito do Num da conta
		$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];				// Código da Carteira // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
		$dadosboleto["inicio_nosso_numero"] = $CobrancaParametro[InicioNossoNumero];	// Carteira SR: 80, 81 ou 82  -  Carteira CR: 90 (Confirmar com gerente qual usar)
		$dadosboleto["conta_cedente"]		= $CobrancaParametro[ContaCedente];			// ContaCedente do Cliente, sem digito (Somente Números)
		$dadosboleto["conta_cedente_dv"]	= $CobrancaParametro[ContaCedenteDigito];	// Digito da ContaCedente do Cliente	
		$dadosboleto["local_pagamento"] 	= $CobrancaParametro[LocalPagamento];
		$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento];
		$dadosboleto["quantidade"]			= "1";
		$dadosboleto["valor_unitario"] 		= $linContaReceber[ValorLancamento];
		$dadosboleto["contrato"]			= $CobrancaParametro[Contrato];
		$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];			
		$dadosboleto["uso_banco"]			= $CobrancaParametro[UsoBanco]; 	
		$dadosboleto["especie"]				= $CobrancaParametro[Especie]; 
		$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento]; 

		$Instrucoes = InstrucoesBoleto($IdContaReceber);

		//==================================================================

		include("vars_cef.php");

		//============================Não mude o valores abaixo=============

		// Default
		if($posY == null){
			$this->SetY(155.5);
			$posTemp = 155.5;
		}else{
			$this->SetY($posY);
			$posTemp = $posY;
		}
		$this->height_cell = 3;
		$this->margin_left = 10;
	    $this->SetLineWidth(0.3);
	
		// L1
		if($Background == 's'){
			$PatchImagens = $Path."/modulos/administrativo/local_cobranca/14/";
		}

	    $this->Image($PatchImagens."imagens/logocaixa.jpg",11,($posTemp + 0.5),19.5,5,jpg);
		$this->Cell(40,6,'','T');
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,6,'','T',0,'',1);
		$this->SetFont('Arial','B',12);
		$this->Cell(20,6,$dadosboleto["codigo_banco_com_dv"],'T',0,'C',0);
		$this->Cell(0.4,6,'','T',0,'',1);
		$this->Cell(126.2,6,$dadosboleto["linha_digitavel"],'T',0,'R',0);
		$this->Cell(3,6,'','T',0,'',0);
	
		// L2
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(91.5,3.5,'Cedente','T',0,'L',0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(37,3.5,'Agência/Código do Cedende','T',0,'L',0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(10,3.5,'Espécie','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(15,3.5,'Quantidade','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(31.3,3.5,'Nosso número','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L3
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(91.5,2.5,$dadosboleto["cedente"],0,0,'L',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(37,2.5,$dadosboleto["agencia_codigo"],0,0,'R',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(10,2.5,$dadosboleto["especie"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(15,2.5,$dadosboleto["quantidade"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(31.3,2.5,$dadosboleto["nosso_numero"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L4
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(53.4,3.5,'Número do documento','T',0,'L',0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(49,3.5,'CPF/CEI/CNPJ','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(36,3.5,'Vencimento','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.8,3.5,'Valor documento','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L5
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(53.4,2.5,$dadosboleto["numero_documento"],0,0,'L',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(49,2.5,$dadosboleto["cpf_cnpj"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(36,2.5,$dadosboleto["data_vencimento"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.8,2.5,$dadosboleto["valor_boleto"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L6
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(32,3.5,'(-) Desconto / Abatimento','T',0,'L',0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(32,3.5,'(-) Outras deduções','T',0,'L',0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(44,3.5,'(+) Mora / Multa','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(30,3.5,'(+) Outros acréscimos','T',0,'L',0);
		$this->Cell(0.55,3.5,'','T',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(46.8,3.5,'(=) Valor cobrado','T',0,'L',1);
		$this->Cell(3,3.5,'','T',0,'',1);
		$this->SetFillColor(0,0,0);
	
		// L7
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,$dadosboleto["valor_desconto"],0,0,'',1);
		$this->Cell(32,2.5,'',0,0,'L',0);
		$this->Cell(0.4,2.5,$dadosboleto["valor_outras_deducoes"],0,0,'',1);
		$this->Cell(32,2.5,'',0,0,'L',0);
		$this->Cell(0.4,2.5,$dadosboleto["valor_mora_multa"],0,0,'',1);
		$this->Cell(44,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,$dadosboleto["valor_outros_acrescimos"],0,0,'',1);
		$this->Cell(30,2.5,'',0,0,'C',0);
		$this->Cell(0.55,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(46.8,2.5,$dadosboleto["valor_cobrado"],0,0,'R',1);
		$this->Cell(3,2.5,'','',0,'',1);
		$this->SetFillColor(0,0,0);
	
		// L8
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(186.6,3.5,'Sacado','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L8
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(186.6,2.5,$dadosboleto["sacado"],0,0,'L',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L9
		$this->Ln();
		$this->SetX(10.2);
		$this->SetFont('Arial','',6.5);
		$this->Cell(140.1,3.5,'Corte na linha pontilhada','T',0,'L',0);
		$this->Cell(46.8,3.5,'Autenticação mecânica','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);	

		// L10
		$this->Ln();
		$i=11;
		while($i < 200){
			$this->Image($PatchImagens."imagens/imgpxlazu.jpg",$i,($posTemp + 36.5),1,0.1,jpg);
			$i += 3;
		}
		
		// L11
		$this->Ln();
	    $this->Image($PatchImagens."imagens/logocaixa.jpg",11,($posTemp + 37.5),19.5,5,jpg);
		$this->Cell(40,6,'','');
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,6,'','',0,'',1);
		$this->SetFont('Arial','B',12);
		$this->Cell(20,6,$dadosboleto["codigo_banco_com_dv"],'',0,'C',0);
		$this->Cell(0.4,6,'','',0,'',1);
		$this->Cell(126.2,6,$dadosboleto["linha_digitavel"],'',0,'R',0);
		$this->Cell(3,6,'','',0,'',0);
	
		// L12
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(139.4,3.5,'Local de pagamento','T',0,'L',0);
		$this->Cell(0.55,3.5,'','T',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(46.95,3.5,'Vencimento','T',0,'L',1);
		$this->Cell(2.9,3.5,'','T',0,'',1);
		$this->SetFillColor(0,0,0);
	
		// L13
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(139.4,2.5,$dadosboleto["local_pagamento"],0,0,'L',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(46.8,2.5,$dadosboleto["data_vencimento"],0,0,'R',1);
		$this->Cell(3,2.5,'','',0,'',1);
	
		// L14
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(139.4,3.5,'Cedente','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'Agência / Código cedente','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L15
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(139.4,2.5,$dadosboleto["cedente"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,$dadosboleto["agencia_codigo"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L16
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(26,3.5,'Data do documento','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(53.1,3.5,'Nº documento','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(23.1,3.5,'Espécie doc.','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(10,3.5,'Aceite','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(25.3,3.5,'Data process.','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'Nosso número','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L17
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(26,2.5,$dadosboleto["data_documento"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(53.1,2.5,$dadosboleto["numero_documento"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(23.1,2.5,$dadosboleto["especie_doc"],0,0,'C',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(10,2.5,$dadosboleto["aceite"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(25.3,2.5,$dadosboleto["data_processamento"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,$dadosboleto["nosso_numero"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L18
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(25.9,3.5,'Uso do banco','T',0,'L',1);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(33,3.5,'Carteira','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(19.6,3.5,'Espécie','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(23.1,3.5,'Quantidade','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(35.8,3.5,'Valor','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'(=) Valor documento','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L19
		$this->Ln();
		$this->SetFont('Arial','',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(25.9,2.5,'',0,0,'C',1);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(33,2.5,$dadosboleto["carteira"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(19.6,2.5,$dadosboleto["especie"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(23.1,2.5,$dadosboleto["quantidade"],0,0,'L',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(35.8,2.5,$dadosboleto["valor_unitario"],0,0,'R',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,$dadosboleto["valor_unitario"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L20
		$this->Ln();
		$this->SetX(10.2);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','T',0,'',0);
		$this->Cell(131.1,3.5,'Instruções (Texto de responsabilidade do cedente)','T',0,'L',0);
		$this->Cell(7.9,3.5,'','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'(-) Desconto / Abatimento','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L21
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131.3,3.5,$Instrucoes[0],'',0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L22
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131.2,3.5,$Instrucoes[1],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(8,3.5,'','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->Cell(46.9,3.5,'(-) Outras deduções','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L23
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(131.2,2.5,$Instrucoes[2],0,0,'L',0);
		$this->Cell(8,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L24
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131.2,3.5,$Instrucoes[3],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(8,3.5,'','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->Cell(46.9,3.5,'(+) Mora / Multa','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L25
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(131.3,2.5,$Instrucoes[4],0,0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(47,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L26
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131.2,3.5,$Instrucoes[5],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(8,3.5,'','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->Cell(46.9,3.5,'(+) Outros acréscimos','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L27
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131.2,2.5,$Instrucoes[6],'',0,'L',0);
		$this->Cell(8,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L28
		$this->Ln();
		$this->SetFont('Arial','B',7.5);	
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->Cell(131.2,2.5,$Instrucoes[7],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(8,3.5,'','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(46.9,3.5,'(=) Valor cobrado','T',0,'L',1);
		$this->Cell(3,3.5,'','T',0,'',1);
		$this->SetFillColor(0,0,0);
	
		// L29
		$this->Ln();
		$this->SetFont('Arial','B',7.5);	
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->Cell(131.2,1.0,$Instrucoes[8],'',0,'L',0);
		$this->Cell(8,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(46.9,2.5,'',0,0,'C',1);
		$this->Cell(3,2.5,'','',0,'',1);
	
		// L30
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3,'','',0,'',1);
		$this->Cell(186.6,3,'Sacado','T',0,'L',0);
		$this->Cell(3,3,'','T',0,'',0);
	
		// L31
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3,'',0,0,'',1);
		$this->Cell(186.6,3,$dadosboleto["sacado"],0,0,'L',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L32
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3,'',0,0,'',1);
		$this->Cell(186.6,3,$dadosboleto["endereco1"],0,0,'L',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L33
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3,'','',0,'',1);
		$this->Cell(186.6,3,$dadosboleto["endereco2"],'B',0,'L',0);
		$this->Cell(3,3,'','B',0,'',0);

		// L34 - Codigo de Barras
		$CodBarras = $dadosboleto["codigo_barras"];

		// Definimos as dimensões das imagens 
		$fino	= 0.3; 
		$largo	= 3*$fino; 
		$altura = 39*$fino;

		// Criamos um array associativo com os binários 
		$Bar[0] = "00110"; 
		$Bar[1] = "10001"; 
		$Bar[2] = "01001"; 
		$Bar[3] = "11000"; 
		$Bar[4] = "00101"; 
		$Bar[5] = "10100"; 
		$Bar[6] = "01100"; 
		$Bar[7] = "00011"; 
		$Bar[8] = "10010"; 
		$Bar[9] = "01010";

		$this->Ln(3.5);

		// Preto - Fino
		$this->SetFillColor(0,0,0);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		// Branco - Fino
		$this->SetFillColor(255,255,255);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		// Preto - Fino
		$this->SetFillColor(0,0,0);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		// Branco - Fino
		$this->SetFillColor(255,255,255);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		for ($a = 0; $a < strlen($CodBarras); $a++){ 

			$Preto  = $CodBarras[$a]; 
			$CodPreto  = $Bar[$Preto]; 

			$a = $a+1; // Sabemos que o Branco é um depois do Preto... 
			$Branco = $CodBarras[$a]; 
			$CodBranco = $Bar[$Branco]; 

			// Encontrado o CodPreto e o CodBranco vamos fazer outro looping dentro do nosso 
			for ($y = 0; $y < 5; $y++) { // O for vai pegar os binários 

				if ($CodPreto[$y] == '0') { // Se o binario for preto e fino ecoa 
					// Preto - Fino
					$this->SetFillColor(0,0,0);
					$this->Cell($fino,$altura,'',0,0,'L',1);
				} 

				if ($CodPreto[$y] == '1') { // Se o binario for preto e grosso ecoa 
					// Preto - Largo
					$this->SetFillColor(0,0,0);
					$this->Cell($largo,$altura,'',0,0,'L',1);
				} 

				if ($CodBranco[$y] == '0') { // Se o binario for branco e fino ecoa 
					// Branco - Fino
					$this->SetFillColor(255,255,255);
					$this->Cell($fino,$altura,'',0,0,'L',1);
				} 

				if($CodBranco[$y] == '1') { // Se o binario for branco e grosso ecoa 
					// Branco - Largo
					$this->SetFillColor(255,255,255);
					$this->Cell($largo,$altura,'',0,0,'L',1);
				} 
			} 

		} // Fechamos nosso looping maior 

		// Encerramos o código ecoando o final(encerramento) 
		// Final padrão do Codigo de Barras 

		// Preto - Largo
		$this->SetFillColor(0,0,0);
		$this->Cell($largo,$altura,'',0,0,'L',1);

		// Branco - Fino
		$this->SetFillColor(255,255,255);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		// Preto - Fino
		$this->SetFillColor(0,0,0);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		if($dadosboleto["cob_outro"] == 'S'){
			if($Background == 's'){
				$estrela = $Path."/img/estrutura_sistema/ico_estrela.jpg";
			}else{
				$estrela = "../../../../img/estrutura_sistema/ico_estrela.jpg";
			}
			$this->Image($estrela,195,265,3.35,3.35,jpg);
		}
	}
	
	function TituloCarne($IdLoja, $IdContaReceber, $con){

		global $Background;
		global $Path;
		global $dadosboleto;
		global $posY;
		
		include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");

		$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento];		// Data de Vencimento do Boleto
		$dadosboleto["numero_documento"] 	= $linContaReceber[NumeroDocumento]; 	// Nosso Numero
		$dadosboleto["nosso_numero"]		= $linContaReceber[NumeroDocumento];	// Nosso numero sem o DV - REGRA: Máximo de 8 caracteres!
		$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
		$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
		$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento];	// Valor do Boleto (Utilizar virgula como separador decimal, não use pontos)
		$dadosboleto["agencia"]				= $CobrancaParametro[Agencia]; 				// Agencia
		$dadosboleto["conta"]				= $CobrancaParametro[Conta]; 				// Conta
		$dadosboleto["conta_dv"]			= $CobrancaParametro[ContaDigito]; 			// Digito do Num da conta
		$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];				// Código da Carteira // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
		$dadosboleto["inicio_nosso_numero"] = $CobrancaParametro[InicioNossoNumero];	// Carteira SR: 80, 81 ou 82  -  Carteira CR: 90 (Confirmar com gerente qual usar)
		$dadosboleto["conta_cedente"]		= $CobrancaParametro[ContaCedente];			// ContaCedente do Cliente, sem digito (Somente Números)
		$dadosboleto["conta_cedente_dv"]	= $CobrancaParametro[ContaCedenteDigito];	// Digito da ContaCedente do Cliente	
		$dadosboleto["local_pagamento"] 	= $CobrancaParametro[LocalPagamento];
		$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento];
		$dadosboleto["quantidade"]			= "1";
		$dadosboleto["valor_unitario"] 		= $linContaReceber[ValorLancamento];
		$dadosboleto["contrato"]			= $CobrancaParametro[Contrato];
		$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];			
		$dadosboleto["uso_banco"]			= $CobrancaParametro[UsoBanco]; 	
		$dadosboleto["especie"]				= $CobrancaParametro[Especie]; 
		$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento]; 

		$Instrucoes = InstrucoesBoleto($IdContaReceber);

		//==================================================================

		include("vars_cef.php");

		//============================Não mude o valores abaixo=============

		// Default
		if($posY == null){
			$this->SetY(1.5);
			$posTemp = 2.2;
			$aux	 =	98.3;
		}else{
			$this->SetY($posY+1.5);
			$posTemp = $posY + 1.7;
			$aux	=	(98 + $posY - 0.3);
		}
		$this->height_cell = 3;
		$this->margin_left = 10;
	    $this->SetLineWidth(0.3);
						
		// L1
		
		if($Background == 's'){
			$PatchImagens = $Path."/modulos/administrativo/local_cobranca/14/";
		}
		
		$this->SetFont('Arial','B',8);
		$this->Cell(35,6,"RECIBO DO SACADO",'',0,'L',0);
		
		$this->SetFont('Arial','',8);
		$i=$posTemp;
		
		while($i < $aux){
	//	Image(string file , float x , float y , float w , float h , string type , mixed link)
			$this->Image($PatchImagens."imagens/imgpxlazu.jpg",45,$i,0.1,1,jpg);
			$this->Ln();
			$i += 3;
		}
	
		
		if($posY == null){
			$this->SetY(1.5);
		}else{
			$this->SetY($posY+1.5);
		}
	    $this->Image($PatchImagens."imagens/logocaixa.jpg",50,$posTemp,19.5,5,jpg);
		$this->Cell(65,6.5,'','');
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,6.5,'','',0,'',1);
		$this->SetFont('Arial','B',10);
		$this->Cell(15,6.5,$dadosboleto["codigo_banco_com_dv"],'',0,'C',0);
		$this->Cell(0.4,6.5,'','',0,'',1);
		$this->Cell(105.2,6.5,$dadosboleto["linha_digitavel"],'',0,'R',0);
		$this->Cell(3,6.5,'','',0,'',0);
		
		// L2
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'Cedente','LTR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,4,'',0,'',0);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,4,'','',0,'',1);
		$this->Cell(114.5,4,'Local de pagamento','T',0,'L',0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(33.5,4,'Vencimento','T',0,'L',1);
		$this->Cell(2.9,4,'','T',0,'',1);
		$this->SetFillColor(0,0,0);
		
		$cedente1	=	substr($dadosboleto["cedente"],0,20);
		$cedente2	=	substr($dadosboleto["cedente"],20,20);
		$cedente3	=	substr($dadosboleto["cedente"],40,20);
		
		// L3
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,$cedente1,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3,'',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3,'',0,0,'',1);
		$this->Cell(114.5,3,$dadosboleto["local_pagamento"],0,0,'L',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(33.5,3,$dadosboleto["data_vencimento"],0,0,'R',1);
		$this->Cell(3,3,'','',0,'',1);
	
		// L4
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,$cedente2,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,4,'',0,'',0);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,4,'','',0,'',1);
		$this->Cell(114.3,4,'Cedente','T',0,'L',0);
		$this->Cell(0.5,4,'',0,0,'',1);
		$this->Cell(33.5,4,'Agência / Código cedente','T',0,'L',0);
		$this->Cell(3,4,'','T',0,'',0);
	
		// L5
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,$cedente3,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3,'',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3,'',0,0,'',1);
		$this->Cell(114.3,3,$dadosboleto["cedente"],0,0,'L',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(33.5,3,$dadosboleto["agencia_codigo"],0,0,'R',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L6
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'Vencimento','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,4,'',0,'',0);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,4,'',0,0,'',1);
		$this->Cell(25,4,'Data do documento','T',0,'L',0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->Cell(36.5,4,'Nº documento','T',0,'L',0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->Cell(17,4,'Espécie doc.','T',0,'L',0);
		$this->Cell(0.4,4,'','T',0,'',1);
		$this->Cell(9,4,'Aceite','T',0,'L',0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->Cell(25,4,'Data process.','T',0,'L',0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->Cell(33.5,4,'Nosso número','T',0,'L',0);
		$this->Cell(3,4,'','T',0,'',0);
	
		// L7
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,$dadosboleto["data_vencimento"],'LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3,'',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3,'',0,0,'',1);
		$this->Cell(25,3,$dadosboleto["data_documento"],0,0,'C',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(36.5,3,$dadosboleto["numero_documento"],0,0,'L',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(17,3,$dadosboleto["especie_doc"],0,0,'C',0);
		$this->Cell(0.4,3,'',0,0,'',1);
		$this->Cell(9,3,$dadosboleto["aceite"],0,0,'C',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(25,3,$dadosboleto["data_processamento"],0,0,'L',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(33.5,3,$dadosboleto["nosso_numero"],0,0,'R',0);
		$this->Cell(3,3,'','',0,'',0);

		// L8
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'Agência / Código cedente','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,4,'',0,'',0);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,4,'','',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(25,4,'Uso do banco','T',0,'L',1);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->Cell(20.5,4,'Carteira','T',0,'L',0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->Cell(15.4,4,'Espécie','T',0,'L',0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->Cell(17,4,'Quantidade','T',0,'L',0);
		$this->Cell(0.4,4,'','T',0,'',1);
		$this->Cell(34.4,4,'Valor','T',0,'L',0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->Cell(33.5,4,'(=) Valor documento','T',0,'L',0);
		$this->Cell(3,4,'','T',0,'',0);
		
		// L9
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,$dadosboleto["agencia_codigo"],'LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3,'',0,'',0);
		$this->SetFont('Arial','',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(25,3,'',0,0,'C',1);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(20.5,3,$dadosboleto["carteira"],0,0,'L',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(15.4,3,$dadosboleto["especie"],0,0,'C',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(17,3,$dadosboleto["quantidade"],0,0,'L',0);
		$this->Cell(0.4,3,'',0,0,'',1);
		$this->Cell(34.4,3,$dadosboleto["valor_unitario"],0,0,'R',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(33.5,3,$dadosboleto["valor_unitario"],0,0,'R',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L10
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'Nosso Número','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,4,'',0,'',0);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,4,'','T',0,'',0);
		$this->Cell(106.2,4,'Instruções (Texto de responsabilidade do cedente)','T',0,'L',0);
		$this->Cell(7.9,4,'','T',0,'L',0);
		$this->Cell(0.5,4,'','T',0,'',1);
		$this->Cell(33.5,4,'(-) Desconto / Abatimento','T',0,'L',0);
		$this->Cell(3,4,'','T',0,'',0);
	
		// L11
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,$dadosboleto["nosso_numero"],'LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3,'',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3,'',0,0,'',0);
		$this->Cell(106.2,3,$Instrucoes[0],0,0,'L',0);
		$this->Cell(7.9,3,'',0,0,'L',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(33.5,3,'',0,0,'C',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L12
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'(=) Valor documento','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,4,'',0,'',0);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,4,'','',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->Cell(106.2,3,$Instrucoes[1],'',0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(7.9,4,'','',0,'L',0);
		$this->Cell(0.5,4,'','',0,'',1);
		$this->Cell(33.5,4,'(-) Outras deduções','T',0,'L',0);
		$this->Cell(3,4,'','T',0,'',0);
	
		// L13
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,$dadosboleto["valor_unitario"],'LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3,'',0,0,'',0);
		$this->Cell(106.2,1,$Instrucoes[2],0,0,'L',0);
		$this->Cell(7.9,3,'',0,0,'L',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(33.5,3,'',0,0,'C',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L14
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'(-) Desconto / Abatimento','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,4,'',0,'',0);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,4,'','',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->Cell(106.2,1.5,$Instrucoes[3],'',0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(7.9,4,'','',0,'L',0);
		$this->Cell(0.5,4,'','',0,'',1);
		$this->Cell(33.5,4,'(+) Mora / Multa','T',0,'L',0);
		$this->Cell(3,4,'','T',0,'',0);
	
		// L15
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3,'',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3,'',0,0,'',0);
		$this->Cell(106.2,0,$Instrucoes[4],0,0,'L',0);
		$this->Cell(7.9,3,'',0,0,'L',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(33.5,3,'',0,0,'C',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L16
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'(-) Outras deduções','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,4,'',0,'',0);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,4,'','',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->Cell(106.2,1,$Instrucoes[5],'',0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(7.9,4,'','',0,'L',0);
		$this->Cell(0.5,4,'','',0,'',1);
		$this->Cell(33.5,4,'(+) Outros acréscimos','T',0,'L',0);
		$this->Cell(3,4,'','T',0,'',0);
	
		// L17
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3,'',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3,'',0,0,'',0);
		$this->Cell(106.2,0,$Instrucoes[6],0,0,'L',0);
		$this->Cell(7.9,3,'',0,0,'L',0);
		$this->Cell(0.5,3,'',0,0,'',1);
		$this->Cell(33.5,3,'',0,0,'C',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L18
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'(+) Mora / Multa','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,4,'',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,4,'','',0,'',0);
		$this->Cell(106.2,1,$Instrucoes[7],'',0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(7.9,4,'','',0,'L',0);
		$this->Cell(0.7,4,'','',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(36.3,4,'(=) Valor cobrado','T',0,'L',1);
		$this->Cell(3,4,'','',0,'',0);
	
		// L19
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3,'',0,'',0);
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3,'',0,0,'',0);
		$this->Cell(106.2,0,$Instrucoes[8],0,0,'L',0);
		$this->Cell(7.9,3,'',0,0,'L',0);
		$this->Cell(0.7,3,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(33.5,3,'',0,0,'C',1);
		$this->Cell(3,3,'','',0,'',1);
	
		// L20
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'(+) Outros acréscimos','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(148.4,3.5,'Sacado','T',0,'L',0);
		$this->Cell(3,2.9,'','T',0,'',0); // aqui
	
		// L21
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','B',6);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'',0,0,'',1);
		$this->Cell(148.4,2.5,$dadosboleto["sacado"],0,0,'L',0);
		$this->Cell(3,3.0,'','',0,'',0);
	
		// L22
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'(=) Valor cobrado','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,5,'',0,'',0);
		$this->SetFont('Arial','B',6);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.9,'',0,0,'',1);
		$this->Cell(148.4,1.5,$dadosboleto["endereco1"],0,0,'L',0);
		$this->Cell(3,3.09,'','',0,'',0);
	
		// L23
		$this->Ln();
		$this->SetFont('Arial','',6);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,0.5,'',0,'',0);
		$this->SetFont('Arial','B',6);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'','',0,'',1);
		$this->Cell(148.4,2.5,$dadosboleto["endereco2"],'B',0,'L',0);
		$this->Cell(3,2.6,'','B',0,'',0);
		
		// L24 - Codigo de Barras		
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,4,'Sacado','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$CodBarras = $dadosboleto["codigo_barras"];		
		$this->Cell(3,4,'',0,'',0);
		// Definimos as dimensões das imagens 
		$fino	= 0.3; 
		$largo	= 3*$fino; 
		$altura = 39*$fino;

		// Criamos um array associativo com os binários 
		$Bar[0] = "00110"; 
		$Bar[1] = "10001"; 
		$Bar[2] = "01001"; 
		$Bar[3] = "11000"; 
		$Bar[4] = "00101"; 
		$Bar[5] = "10100"; 
		$Bar[6] = "01100"; 
		$Bar[7] = "00011"; 
		$Bar[8] = "10010"; 
		$Bar[9] = "01010";
		
		$sacado1	=	substr($linDadosCliente[Nome],0,20);
		$sacado2	=	substr($linDadosCliente[Nome],20,20);
		$sacado3	=	substr($linDadosCliente[Nome],40,20);

		$this->Ln(0.5);
		$this->Cell(36.5,0.5,'',0,'',0);
		
		// Preto - Fino
		$this->SetFillColor(0,0,0);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		// Branco - Fino
		$this->SetFillColor(255,255,255);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		// Preto - Fino
		$this->SetFillColor(0,0,0);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		// Branco - Fino
		$this->SetFillColor(255,255,255);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		for ($a = 0; $a < strlen($CodBarras); $a++){ 

			$Preto  = $CodBarras[$a]; 
			$CodPreto  = $Bar[$Preto]; 

			$a = $a+1; // Sabemos que o Branco é um depois do Preto... 
			$Branco = $CodBarras[$a]; 
			$CodBranco = $Bar[$Branco]; 

			// Encontrado o CodPreto e o CodBranco vamos fazer outro looping dentro do nosso 
			for ($y = 0; $y < 5; $y++) { // O for vai pegar os binários 

				if ($CodPreto[$y] == '0') { // Se o binario for preto e fino ecoa 
					// Preto - Fino
					$this->SetFillColor(0,0,0);
					$this->Cell($fino,$altura,'',0,0,'L',1);
				} 

				if ($CodPreto[$y] == '1') { // Se o binario for preto e grosso ecoa 
					// Preto - Largo
					$this->SetFillColor(0,0,0);
					$this->Cell($largo,$altura,'',0,0,'L',1);
				} 

				if ($CodBranco[$y] == '0') { // Se o binario for branco e fino ecoa 
					// Branco - Fino
					$this->SetFillColor(255,255,255);
					$this->Cell($fino,$altura,'',0,0,'L',1);
				} 

				if($CodBranco[$y] == '1') { // Se o binario for branco e grosso ecoa 
					// Branco - Largo
					$this->SetFillColor(255,255,255);
					$this->Cell($largo,$altura,'',0,0,'L',1);
				} 
			} 

		} // Fechamos nosso looping maior 

		// Encerramos o código ecoando o final(encerramento) 
		// Final padrão do Codigo de Barras 

		// Preto - Largo
		$this->SetFillColor(0,0,0);
		$this->Cell($largo,$altura,'',0,0,'L',1);

		// Branco - Fino
		$this->SetFillColor(255,255,255);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		// Preto - Fino
		$this->SetFillColor(0,0,0);
		$this->Cell($fino,$altura,'',0,0,'L',1);

		if($dadosboleto["cob_outro"] == 'S'){
			if($Background == 's'){
				$estrela = $Path."/img/estrutura_sistema/ico_estrela.jpg";
			}else{
				$estrela = "../../../../img/estrutura_sistema/ico_estrela.jpg";
			}
			$this->Image($estrela,195,281,3.35,3.35,jpg);
		}

		$this->SetY($posY+86.5);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,5,$sacado1,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);

		$this->SetY($posY+91.5);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.0,$sacado2,'LRB',0,'L',0);
	}


	function Tracejado($Posicao){
		global $Path;
		include($Path."modulos/administrativo/local_cobranca/funcao_tracejado_pdf.php");
	}
}
?>
