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
		include($Path."modulos/administrativo/local_cobranca/seletor_demonstrativo_carne.php");
	}
	
	function DemonstrativoVerso($IdLoja, $IdContaReceber, $con){
		global $Path;
		$sql = "SELECT 
					ValorLocalCobrancaParametro ImprimirVerso 
				FROM
					LocalCobrancaParametro 
				WHERE
					IdLocalCobrancaLayout = 38 AND
					IdLocalCobrancaParametro = 'ImprimirVerso'";
		$res = mysql_query($sql,$con);
		$linParametroLayout = mysql_fetch_array($res);
		
		if($linParametroLayout[ImprimirVerso] == 'S'){		
			include($Path."modulos/administrativo/local_cobranca/38/funcao_demonstrativo_verso_pdf.php");
		}
	}

	function Titulo($IdLoja, $IdContaReceber, $con){

		global $Background;
		global $Path;
		global $dadosboleto;
		global $posY;
		
		include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");

		$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento];		// Data de Vencimento do Boleto
		$dadosboleto["numero_documento"] 	= $linContaReceber[NumeroDocumento]; 	// Nosso Numero
		$dadosboleto["nosso_numero"]		= $linContaReceber[NossoNumero];	// Nosso numero sem o DV - REGRA: Máximo de 8 caracteres!
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
			$PatchImagens = $Path."/modulos/administrativo/local_cobranca/38/";
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
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
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

	function Tracejado($Posicao){
		global $Path;
		include($Path."modulos/administrativo/local_cobranca/funcao_tracejado_pdf.php");
	}
	
	function Rotate($angle,$x=-1,$y=-1)
	{
		if($x==-1)
			$x=$this->x;
		if($y==-1)
			$y=$this->y;
		if($this->angle!=0)
			$this->_out('Q');
		$this->angle=$angle;
		if($angle!=0)
		{
			$angle*=M_PI/180;
			$c=cos($angle);
			$s=sin($angle);
			$cx=$x*$this->k;
			$cy=($this->h-$y)*$this->k;
			$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		}
	}

	function _endpage()
	{
		if($this->angle!=0)
		{
			$this->angle=0;
			$this->_out('Q');
		}
		parent::_endpage();
	}
	
	function TextWithDirection($x, $y, $txt, $direction='R')
	{
		if ($direction=='R')
			$s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',1,0,0,1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		elseif ($direction=='L')
			$s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',-1,0,0,-1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		elseif ($direction=='U')
			$s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,1,-1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		elseif ($direction=='D')
			$s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,-1,1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		else
			$s=sprintf('BT %.2F %.2F Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		if ($this->ColorFlag)
			$s='q '.$this->TextColor.' '.$s.' Q';
		$this->_out($s);
	}

	function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
	{
		$font_angle+=90+$txt_angle;
		$txt_angle*=M_PI/180;
		$font_angle*=M_PI/180;

		$txt_dx=cos($txt_angle);
		$txt_dy=sin($txt_angle);
		$font_dx=cos($font_angle);
		$font_dy=sin($font_angle);

		$s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		if ($this->ColorFlag)
			$s='q '.$this->TextColor.' '.$s.' Q';
		$this->_out($s);
	}
	
	function RotatedImage($file,$x,$y,$w,$h,$angle)
	{
		//Image rotated around its upper-left corner
		$this->Rotate($angle,$x,$y);
		$this->Image($file,$x,$y,$w,$h);
		$this->Rotate(0);
	}
	
	function digito ($valor) {
		for ($i=0; $i<strlen($valor); $i++) {
		   $soma += $valor[$i];
		}
		settype($soma,'string');
		if ($soma[1]) {
			return 10-$soma[1];
		}
		else {
			return 10- $soma[0];
		}
	}
	
	function cod_Cep_Barra($cep){

/*		global $Background;

		if($Background == 's'){
			$PatchImagens = $Path."/modulos/administrativo/local_cobranca/38/";
		}

		if($cep != ""){
			$numero = str_replace('-','',$cep).$this->digito(str_replace('-','',$cep));
		}
		
		$bar[1] = '00011';
		$bar[2] = '00101';
		$bar[3] = '00110';
		$bar[4] = '01001';
		$bar[5] = '01010';
		$bar[6] = '01100';
		$bar[7] = '10001';
		$bar[8] = '10010';
		$bar[9] = '10100';
		$bar[0] = '11000';
		
		for ($i = 0; $i < strlen($numero); $i++) {
		   $temp = $numero[$i];
		   $numerobin .=  $bar[$temp];
		}

		$img = ImageCreate(233,25);
		$preto  = ImageColorAllocate($img, 0, 0, 0);
		$branco = ImageColorAllocate($img, 255, 255, 255);

		ImageFilledRectangle($img, 0, 0, $lw*95+1000, $hi+30, $branco);
		ImageFilledRectangle($img, 0,5,1,17,$preto);
		
		for($i = 0; $i <= 44; $i++) {
			$px = ($i * 5) + 5;
			
			if($numerobin[$i]) {
				$py = 12;
			} else{
				$py = 5;
			}
			
			ImageFilledRectangle($img, $px,17-$py,$px+1,17,$preto);
		}
		
		ImageFilledRectangle($img, $px+5,5,$px+6,17,$preto);
		if(file_exists($PatchImagens."imagens/cep_cod_barra.png")){
			unlink($PatchImagens."imagens/cep_cod_barra.png");
			ImagePNG($img,$PatchImagens."imagens/cep_cod_barra.png");
		}else{
			ImagePNG($img,$PatchImagens."imagens/cep_cod_barra.png");
		}*/
	}
}
?>