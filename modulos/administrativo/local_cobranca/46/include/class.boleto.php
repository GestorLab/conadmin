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
	
	function Titulo($IdLoja, $IdContaReceber, $con){

		global $Background;
		global $Path;
		global $dadosboleto;
		global $posY;
		
		include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	
		$dadosboleto["data_vencimento"] 	= $linContaReceber[DataVencimento];		// Data de Vencimento do Boleto
		$dadosboleto["nosso_numero"]	 	= $linContaReceber[NumeroDocumento]; 	// Nosso Numero
		$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Numero do Pedido ou Nosso Numero
		$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
		$dadosboleto["valor"] 				= $linContaReceber[ValorLancamento];	// Valor do Boleto (Utilizar virgula como separador decimal, não use pontos)
		$dadosboleto["agencia"]				= $CobrancaParametro[Agencia]; 			// Numero da Agência até 4 Digitos s/DAC
		$dadosboleto["digito_agencia"]		= $CobrancaParametro[DigitoAgencia]; 	// 1 Digito da Agência
		$dadosboleto["conta"] 				= $CobrancaParametro[Conta];	 		// Numero da Conta Até 8 Digitos s/ Digito
		$dadosboleto["digito_conta"]		= $CobrancaParametro[DigitoConta]; 		// Digito da Conta Corrente 1 Digito
		$dadosboleto["convenio"]			= $CobrancaParametro[Convenio];		  	// Numero do Convenio (se a sua conta nâo tem convenio deixe este campo como "")
		$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];  		// Código da Carteira 
		$dadosboleto["layout_boleto"]	 	= $CobrancaParametro[LayoutBoleto];		// Layout do boleto Banco do Brasil 1, 2, 3, 4 ou 5 (Ver Abaixo)
		$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento];
		$dadosboleto["quantidade"]			= "1";
		$dadosboleto["valor_unitario"]	 	= $linContaReceber[ValorLancamento];
		$dadosboleto["contrato"]			= $CobrancaParametro[Contrato];
		$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];			
		$dadosboleto["uso_banco"]			= $CobrancaParametro[UsoBanco]; 	
		$dadosboleto["especie"]				= $CobrancaParametro[Especie]; 
		$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento]; 
		$dadosboleto["LocalPagamento"]		= $CobrancaParametro[LocalPagamento]; 		
		
		$Instrucoes = InstrucoesBoleto($IdContaReceber);
		
		$b = new boleto();		
		$b->banco_brasil($dadosboleto);
		
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
			$PatchImagens = $Path."/modulos/administrativo/local_cobranca/46/";
		}
	
	    $this->Image($PatchImagens."imagens/imgbb.jpg",10,($posTemp+0.5),40,6,jpg);
		$this->Cell(40,6,'','T');
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,6,'','T',0,'',1);
		$this->SetFont('Arial','B',12);
		$this->Cell(20,6,'001-9','T',0,'C',0);
		$this->Cell(0.4,6,'','T',0,'',1);
		$this->Cell(126.2,6,$dadosboleto["linha_digitavel"],'T',0,'R',0);
		$this->Cell(3,6,'','T',0,'',0);
	
		// L2
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(88,3.5,'Cedente','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(37,3.5,'Agência/Código do Cedende','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(10,3.5,'Espécie','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(15,3.5,'Quantidade','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(34.8,3.5,'Nosso número','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L3
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(88,2.5,$dadosboleto["cedente"],0,0,'L',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(37,2.5,$dadosboleto["agencia_codigo"],0,0,'R',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(10,2.5,$dadosboleto["especie"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(15,2.5,$dadosboleto["quantidade"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(34.8,2.5,$dadosboleto["nosso_numero"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L4
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(32,3.5,'Número do documento','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(21,3.5,'Contrato','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(49,3.5,'CPF/CEI/CNPJ','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(36,3.5,'Vencimento','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.8,3.5,'Valor documento','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L5
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(32,2.5,$dadosboleto["numero_documento"],0,0,'L',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(21,2.5,$dadosboleto["contrato"],0,0,'L',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(49,2.5,$dadosboleto["cpf_cnpj"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(36,2.5,$dadosboleto["data_vencimento"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.8,2.5,$dadosboleto["valor"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L6
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(32,3.5,'(-) Desconto / Abatimento','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(32,3.5,'(-) Outras deduções','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(44,3.5,'(+) Mora / Multa','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(30,3.5,'(+) Outros acréscimos','T',0,'L',0);
		$this->Cell(0.7,3.5,'','T',0,'',1);
		$this->SetFillColor(255,255,204);
		$this->Cell(46.8,3.5,'(=) Valor cobrado','T',0,'L',1);
		$this->Cell(3,3.5,'','T',0,'',1);
		$this->SetFillColor(0,0,0);
		
		// L7
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,2.5,$dadosboleto["valor_desconto"],0,0,'',1);
		$this->Cell(32,2.5,'',0,0,'L',0);
		$this->Cell(0.4,2.5,$dadosboleto["valor_outras_deducoes"],0,0,'',1);
		$this->Cell(32,2.5,'',0,0,'L',0);
		$this->Cell(0.4,2.5,$dadosboleto["valor_mora_multa"],0,0,'',1);
		$this->Cell(44,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,$dadosboleto["valor_outros_acrescimos"]	,0,0,'',1);
		$this->Cell(30,2.5,'',0,0,'C',0);
		$this->Cell(0.7,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,204);
		$this->Cell(46.8,2.5,'',0,0,'R',1);
		$this->Cell(3,2.5,'','',0,'',1);
		$this->SetFillColor(0,0,0);
	
		// L8
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(186.6,3.5,'Sacado','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L8
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(186.6,2.5,$dadosboleto["sacado"],0,0,'L',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L9
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->Cell(140.1,3.5,'Corte na linha pontilhada','T',0,'L',0);
		$this->Cell(46.8,3.5,'Autenticação mecânica','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);	

		// L10
		$this->Ln();
		$i=10;
		while($i < 200){
			$this->Image($PatchImagens."imagens/imgpxlazu.jpg",$i,($posTemp + 36.5),1,0.1,jpg);
			$i += 3;
		}
		
		// L11
		$this->Ln();
	    $this->Image($PatchImagens."imagens/imgbb.jpg",10,($posTemp + 37.5),40,6,jpg);
		$this->Cell(40,6,'','');
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,6,'','',0,'',1);
		$this->SetFont('Arial','B',12);
		$this->Cell(20,6,'001-9','',0,'C',0);
		$this->Cell(0.4,6,'','',0,'',1);
		$this->Cell(126.2,6,$dadosboleto["linha_digitavel"],'',0,'R',0);
		$this->Cell(3,6,'','',0,'',0);
	
		// L12
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(139.3,3.5,'Local de pagamento','T',0,'L',0);
		$this->Cell(0.6,3.5,'','T',0,'',1);
		$this->SetFillColor(255,255,204);
		$this->Cell(46.9,3.5,'Vencimento','T',0,'L',1);
		$this->Cell(3,3.5,'','T',0,'',1);
		$this->SetFillColor(0,0,0);
	
		// L13
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(139.3,2.5,$dadosboleto["LocalPagamento"],0,0,'L',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,204);
		$this->Cell(46.9,2.5,$dadosboleto["data_vencimento"],0,0,'R',1);
		$this->Cell(3,2.5,'','',0,'',1);
	
		// L14
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(139.3,3.5,'Cedente','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'Agência / Código cedente','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L15
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(139.3,2.5,$dadosboleto["cedente"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,$dadosboleto["agencia_codigo"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L16
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(26,3.5,'Data do documento','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(53.1,3.5,'Nº documento','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(23.1,3.5,'Espécie doc.','T',0,'L',0);
		$this->Cell(0.6,3.5,'','T',0,'',1);
		$this->Cell(10,3.5,'Aceite','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(25,3.5,'Data process.','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'Nosso número','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L17
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(26,2.5,$dadosboleto["data_documento"],0,0,'C',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(53,2.5,$dadosboleto["numero_documento"],0,0,'L',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(23,2.5,$dadosboleto["especie_doc"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(10,2.5,$dadosboleto["aceite"],0,0,'C',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(25,2.5,$dadosboleto["data_processamento"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,$dadosboleto["nosso_numero"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L18
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,3.5,'','T',0,'',1);
		$this->SetFillColor(255,255,204);
		$this->Cell(25.8,3.5,'Uso do banco','T',0,'L',1);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(33,3.5,'Carteira','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(17,3.5,'Espécie','T',0,'L',0);
		$this->Cell(0.6,3.5,'','T',0,'',1);
		$this->Cell(36.1,3.5,'Quantidade','T',0,'L',0);
		$this->Cell(0.6,3.5,'','T',0,'',1);
		$this->Cell(25,3.5,'x Valor','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'(=) Valor documento','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L19
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,204);
		$this->Cell(25.8,2.5,'',0,0,'C',1);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(33,2.5,$dadosboleto["carteira"],0,0,'L',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(16.9,2.5,$dadosboleto["especie"],0,0,'C',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(36,2.5,$dadosboleto["quantidade"],0,0,'L',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(25,2.5,$dadosboleto["valor_unitario"],0,0,'R',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,$dadosboleto["valor"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L20
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,3.5,'','T',0,'',0);
		$this->Cell(131,3.5,'Instruções (Texto de responsabilidade do cedente)','T',0,'L',0);
		$this->Cell(7.9,3.5,'','T',0,'L',0);
		$this->Cell(0.6,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'(-) Desconto / Abatimento','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L21
		$this->Ln();	
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131,3.5,$Instrucoes[0],'',0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L22
		$this->Ln();	
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131,3.5,$Instrucoes[1],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(7.9,3.5,'','',0,'L',0);
		$this->Cell(0.6,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'(-) Outras deduções','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L23
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(131,2.5,$Instrucoes[2],0,0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L24
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131,3.5,$Instrucoes[3],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(7.9,3.5,'','',0,'L',0);
		$this->Cell(0.6,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'(+) Mora / Multa','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L25
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(131,2.5,$Instrucoes[4],0,0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L26
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131,3.5,$Instrucoes[5],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(7.9,3.5,'','',0,'L',0);
		$this->Cell(0.6,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'(+) Outros acréscimos','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L27
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131,2.5,$Instrucoes[6],'',0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.6,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L28
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131,2.5,$Instrucoes[7],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(7.9,3.5,'','',0,'L',0);
		$this->Cell(0.8,3.5,'','T',0,'',1);
		$this->SetFillColor(255,255,204);
		$this->Cell(46.9,3.5,'(=) Valor cobrado','T',0,'L',1);
		$this->Cell(3,3.5,'','T',0,'',1);
		$this->SetFillColor(0,0,0);
	
		// L29
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(131,0.5,$Instrucoes[8],'',0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.8,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,204);
		$this->Cell(46.9,2.5,'',0,0,'C',1);
		$this->Cell(3,2.5,'','',0,'',1);
	
		// L30
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3,'','T',0,'',1);
		$this->Cell(186.6,3,'Sacado','T',0,'L',0);
		$this->Cell(3,3,'','T',0,'',0);
	
		// L31
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3,'',0,0,'',1);
		$this->Cell(186.6,3,$dadosboleto["sacado"],0,0,'L',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L32
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3,'',0,0,'',1);
		$this->Cell(186.6,3,$dadosboleto["endereco1"],0,0,'L',0);
		$this->Cell(3,3,'','',0,'',0);
	
		// L33
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(255,207,0);
		$this->Cell(0.4,3,'','B',0,'',1);
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
		global $Posicao;
		
		$SeparadorCampos	= "  -  ";
		
		include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");

		$dadosboleto["data_vencimento"] 	= $linContaReceber[DataVencimento];		// Data de Vencimento do Boleto
		$dadosboleto["nosso_numero"]	 	= $linContaReceber[NumeroDocumento]; 	// Nosso Numero
		$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Numero do Pedido ou Nosso Numero
		$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; 	// Data de emissão do Boleto
		$dadosboleto["valor"] 				= $linContaReceber[ValorLancamento];	// Valor do Boleto (Utilizar virgula como separador decimal, não use pontos)
		$dadosboleto["agencia"]				= $CobrancaParametro[Agencia]; 			// Numero da Agência até 4 Digitos s/DAC
		$dadosboleto["digito_agencia"]		= $CobrancaParametro[DigitoAgencia]; 	// 1 Digito da Agência
		$dadosboleto["conta"] 				= $CobrancaParametro[Conta];	 		// Numero da Conta Até 8 Digitos s/ Digito
		$dadosboleto["digito_conta"]		= $CobrancaParametro[DigitoConta]; 		// Digito da Conta Corrente 1 Digito
		$dadosboleto["convenio"]			= $CobrancaParametro[Convenio];		  	// Numero do Convenio (se a sua conta nâo tem convenio deixe este campo como "")
		$dadosboleto["carteira"]			= $CobrancaParametro[Carteira];  		// Código da Carteira 
		$dadosboleto["layout_boleto"]	 	= $CobrancaParametro[LayoutBoleto];		// Layout do boleto Banco do Brasil 1, 2, 3, 4 ou 5 (Ver Abaixo)
		$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento];
		$dadosboleto["quantidade"]			= "1";
		$dadosboleto["valor_unitario"]	 	= $linContaReceber[ValorLancamento];
		$dadosboleto["contrato"]			= $CobrancaParametro[Contrato];
		$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];			
		$dadosboleto["uso_banco"]			= $CobrancaParametro[UsoBanco]; 	
		$dadosboleto["especie"]				= $CobrancaParametro[Especie]; 
		$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento]; 
		$dadosboleto["LocalPagamento"]		= $CobrancaParametro[LocalPagamento]; 
	#	print_r($dadosboleto);
		
		$Instrucoes = InstrucoesBoleto($IdContaReceber);

		$b = new boleto();		
		$b->banco_brasil($dadosboleto); 

		//============================Não mude o valores abaixo=============
		// Default
		if($posY == null){
			$this->SetY(3.5);
			$posTemp = 3.2;
			$aux	 =	98.3;
		}else{
			$this->SetY($posY);
			$posTemp = $posY + 3.2;
			$aux	=	(98 + $posY - 0.3);
		}	
		
		$this->height_cell = 3;
		$this->margin_left = 10;
	    $this->SetLineWidth(0.3);
	
		// L1
		if($Background == 's'){
			$PatchImagens = $Path."/modulos/administrativo/local_cobranca/46/";
		}	
			
	
		$this->SetFont('Arial','',8);
		$i=$posTemp;	
	
		while($i < $aux){
	//	Image(string file , float x , float y , float w , float h , string type , mixed link)
			$this->Image($PatchImagens."imagens/imgpxlazu.jpg",58,$i,0.1,0.5,jpg);
			$this->Ln();
			$i += 3;
		}
	
			
		if($posY == null){
			$this->SetY(8);
		}else{
			$this->SetY($posY+8);
		}
		
		$fonte = "Arial";
		
		$this->SetX(0.9);
		$this->SetFillColor(0,0,0);
		
		//$this->Cell(0.2,0.3,'','',2,'',0);
		$this->SetFont('Arial','B',8);
		//----
		$this->SetFont('Arial','B',7.2);
		$this->Cell(110,0.3,"");
		$this->Cell(30,0.3,"Pagável preferencialmente nas agências do");
		
		$this->SetX(0.9);
		$this->Ln(2.8);
		$this->SetFont($fonte,'B',7.2);
		$this->Cell(122,0.3,"");
		$this->Cell(43,0.3,"Banco do Brasil");
		$this->SetFont($fonte,'B',10);
		$this->Cell(9,0.3,"");
		$this->Cell(185,0.2,"Via Banco");
		
		$this->SetX(0.9);
		$this->Ln(2);
		$this->Cell(-8,0.2,"");
		$this->Cell(49,0.2,"",1,0,"C",0,"");
		$this->Image($PatchImagens."imagens/imgbb.gif",4,$posTemp,28,7.5,gif);
		$this->Cell(13,6.5,'','');
		$this->Cell(140,0.2,"",1,0,"C",0,"");
		$this->Image($PatchImagens."imagens/imgbb.gif",63.5,$posTemp,28,7.5,gif);
		$this->Cell(30,6.5,'','');
		$this->SetFillColor(0,0,0);
		$this->SetFont($fonte,'B',6);
		$this->Text(142.5,$Posicao-41,"X");
		
		$this->SetLineWidth(0.1);
		$this->Ln(0.6);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(25.7,3.6,"Parcela/Plano","",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(16.7,3.6,"Vencimento","LR",0,"C",0,"");
		$this->Cell(18.6,3.5,'','');
		$this->Cell(24.1,3.6,"Favorecido","",0,"L",0,"");
		$this->Cell(80.6,3.5,'','');
		$this->Cell(36.1,3.6,"Data do Vencimento","L",0,"L",0,"");
		$this->Cell(140,0.2,"",0,0,"C",0,"");
		$this->Cell(30,3.5,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,2.6,"");
		$this->SetFont('','',6);
		$this->Cell(25,2.8,$dadosboleto["parcela_resulmida"],"B",0,"C",0,"");
		$this->Cell(0.8,2.8,'','B');
		$this->Cell(16.7,2.8,$dadosboleto["data_vencimento"],"LRB",0,"C",0,"");
		$this->Cell(18.9,2.5,'','');
		$this->SetFont('Arial','B',8);
		$this->Cell(24.1,2.6,$dadosboleto["cedente"],0,0,"L",0,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(80.3,2.5,'','');
		$this->Cell(36,2.6,$dadosboleto["data_vencimento"],"LB",0,"R",0,"");
		$this->Cell(140,2,"",0,0,"C",0,"");
		$this->Cell(30,2.5,'','');

		$this->Ln(2.8);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,3.6,"Agência / Cód. Favorecido","R",0,"L",0,"");
		$this->Cell(43.1,3.5,'','');
		$this->Cell(80.3,3.5,'','');
		$this->Cell(42.5,3.6,"Agência / Cód. Favorecido","L",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,2.6,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,2.6,$dadosboleto["agencia"]."-".$dadosboleto["digito_agencia"]." / ".$dadosboleto["conta"]."-".$dadosboleto["digito_conta"],"RB",0,"C",0,"");
		$this->Cell(43.1,2.5,'','');
		$this->Cell(80.3,2.5,'','');
		$this->Cell(36,2.6,$dadosboleto["agencia"]."-".$dadosboleto["digito_agencia"]." / ".$dadosboleto["conta"]."-".$dadosboleto["digito_conta"],"LB",0,"R",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(30,2.5,'','');
		
		$this->Ln(2.6);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,3.6,"Identificação do Documento","R",0,"L",0,"");
		$this->Cell(20.1,3.5,'','');
		$this->Cell(28.5,3.6,"Data de Emissão","RT",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(32.5,3.6,"Número do documento","RT",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(17.5,3.6,"Espécie doc.","RT",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(24.5,3.6,"Data do Processamento","RT",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(36,3.6,"Identificação do Documento","",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,0.2,"");
		$this->Cell(42.5,2.6,$dadosboleto["nosso_numero"],"RB",0,"C",0,"");
		$this->SetFont($fonte,'B',7);
		$this->Cell(20.1,2.5,'','');
		$this->Cell(28.5,2.6,$dadosboleto["data_documento"],"RB",0,"C",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(32.5,2.6,$dadosboleto["numero_documento"],"RB",0,"C",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(17.5,2.6,$dadosboleto["especie_doc"],"RB",0,"C",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(24.5,2.6,$dadosboleto["data_processamento"],"RB",0,"C",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->SetFont($fonte,'',6);
		$this->Cell(36,2.6,$dadosboleto["nosso_numero"],"B",0,"R",0,"");
		$this->Cell(0.1,2.5,'','');
		
		$this->Ln(2.6);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,3.6,"Número do documento","R",0,"L",0,"");
		$this->Cell(20.1,3.5,'','');
		$this->Cell(28.5,3.6,"Uso do Banco","RT",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(12.5,3.6,"Carteira","RT",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(17.5,3.6,"Espécie Moeda","RT",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(20,3.6,"Quantidade","RT",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(24.4,3.6,"Valor","R",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(36,3.6,"Valor Documento","",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,0.2,"");
		$this->Cell(42.5,2.6,$dadosboleto["numero_documento"],"RB",0,"C",0,"");
		$this->SetFont($fonte,'B',7);
		$this->Cell(20.1,2.5,'','');
		$this->Cell(28.5,2.6,"","BR",0,"C",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(12.5,2.6,$dadosboleto["carteira"],"RB",0,"C",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(17.5,2.6,$dadosboleto["especie"],"RB",0,"C",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(20.0,2.6,"","RB",0,"C",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->SetFont($fonte,'',6);
		$this->Cell(24.3,2.6,"","B",0,"R",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(36,2.6,$dadosboleto["valor"],"BL",0,"R",0,"");
		$this->Cell(0.1,2.5,'','');
		
		$this->Ln(2.8);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(18,3.6,"Espécie Moeda","",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(24.4,3.6,"Quantidade","LR",0,"L",0,"");
		$this->Cell(18.9,3.5,'','');
		$this->SetFont($fonte,'B',7.5);
		$this->Cell(23.9,3.0,"Instruções para Pagamento:","",0,"L",0,"");
		$this->Cell(80.6,3.5,'','');
		$this->SetFont($fonte,'',6);
		$this->Cell(36.1,3.6,"Desconto / Abatimento","L",0,"L",0,"");
		$this->Cell(140,0.2,"",0,0,"C",0,"");
		$this->Cell(30,3.5,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,2.6,"");
		$this->SetFont('','',6);
		$this->Cell(18,2.6,$dadosboleto["especie"],"B",0,"C",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(24.4,2.6,"","LRB",0,"C",0,"");
		$this->Cell(20.3,2.5,'','');
		$this->SetFont('Arial','',6);
		$this->Cell(42.7,2.6,$Instrucoes[0],0,0,"L",0,"");
		$this->SetFont($fonte,'',7);
		$this->Cell(60.4,2.5,'','');
		$this->Cell(36,2.6,"","LB",0,"R",0,"");
		$this->Cell(140,2,"",0,0,"C",0,"");
		$this->Cell(30,2.5,'','');
		
		$this->Ln(2.74);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,3.6,"Valor Documento","R",0,"L",0,"");
		$this->Cell(3,3.5,'','');
		$this->Cell(17.3,3.6,'','');
		$this->Cell(22.5,3.6,$Instrucoes[1],"",0,"L",0,"");
		$this->Cell(80.6,3.6,'','');
		$this->SetFont($fonte,'',6);
		$this->Cell(36.1,3.6,"Outras Deduções","L",0,"L",0,"");
		$this->Cell(140,0.2,"",0,0,"C",0,"");
		$this->Cell(30,3.6,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,2.6,"");
		$this->SetFont('','',6);
		$this->Cell(42.5,2.6,$dadosboleto["valor"],"BR",0,"L",0,"");
		$this->Cell(0.1,2.6,'','');
		$this->Cell(20.2,2.6,'','');
		$this->SetFont('Arial','',6);
		$this->Cell(24.0,2.6,$Instrucoes[2],0,0,"L",0,"");
		$this->SetFont($fonte,'',7);
		$this->Cell(79.1,2.6,'','');
		$this->Cell(36,2.6,"","LB",0,"R",0,"");
		$this->Cell(140,2,"",0,0,"C",0,"");
		$this->Cell(30,2.6,'','');
		
		$this->Ln(2.7);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,3.6,"Desconto / Abatimento","R",0,"L",0,"");
		$this->Cell(3,3.5,'','');
		$this->Cell(17.1,3.5,'','');
		$this->Cell(22.5,3.6,$Instrucoes[3],"",0,"L",0,"");
		$this->Cell(80.8,3.5,'','');
		$this->SetFont($fonte,'',6);
		$this->Cell(36.1,3.6,"Mora / Multa","L",0,"L",0,"");
		$this->Cell(140,0.2,"",0,0,"C",0,"");
		$this->Cell(30,3.5,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,2.6,"");
		$this->SetFont('','',6);
		$this->Cell(42.5,2.6,"","BR",0,"L",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(19.9,2.5,'','');
		$this->SetFont('Arial','',6);
		$this->Cell(23.9,2.6,$Instrucoes[4],0,0,"L",0,"");
		$this->SetFont($fonte,'',7);
		$this->Cell(79.5,2.5,'','');
		$this->Cell(36,2.6,"","LB",0,"R",0,"");
		$this->Cell(140,2,"",0,0,"C",0,"");
		$this->Cell(30,2.5,'','');
		
		$this->Ln(2.7);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,3.6,"Outras Deduções","R",0,"L",0,"");
		$this->Cell(3,3.5,'','');
		$this->Cell(17.2,3.5,'','');
		$this->Cell(22.5,3.6,$Instrucoes[5],"",0,"L",0,"");
		$this->Cell(80.7,3.5,'','');
		$this->SetFont($fonte,'',6);
		$this->Cell(36.1,3.6,"Outros Acréscimos","L",0,"L",0,"");
		$this->Cell(140,0.2,"",0,0,"C",0,"");
		$this->Cell(30,3.5,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,2.6,"");
		$this->SetFont('','',6);
		$this->Cell(42.5,2.6,"","BR",0,"L",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(20.1,2.5,'','');
		$this->SetFont('Arial','',6);
		$this->Cell(22.5,2.6,$Instrucoes[6],0,0,"L",0,"");
		$this->SetFont($fonte,'',7);
		$this->Cell(80.7,2.5,'','');
		$this->Cell(36,2.6,"","LB",0,"R",0,"");
		$this->Cell(140,2,"",0,0,"C",0,"");
		$this->Cell(30,2.5,'','');
		
		$this->Ln(2.6);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,3.6,"Mora / Multa","R",0,"L",0,"");
		$this->Cell(3,3.5,'','');
		$this->Cell(17.4,3.6,'','');
		$this->Cell(103.0,3.6,"Sacado / Endereço","T",0,"L",0,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(36.1,3.6,"Valor Cobrado","L",0,"L",0,"");
		$this->Cell(140,0.2,"",0,0,"C",0,"");
		$this->Cell(30,3.5,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,2.6,"");
		$this->SetFont('','',6);
		$this->Cell(42.5,2.6,"","BR",0,"L",0,"");
		$this->Cell(0.1,2.5,'','');
		$this->Cell(22.8,2.6,'','');
		$this->SetFont('Arial','B',6);
		$this->Cell(64.1,2.6,substr($dadosboleto["sacado"],0,60)." - ".$dadosboleto['CPF_CNPJ_Adicional'],0,0,"L",0,"");
		$this->SetFont($fonte,'',7);
		$this->Cell(36.4,2.5,'','');
		$this->Cell(36,2.6,"","LB",0,"R",0,"");
		$this->Cell(140,2,"",0,0,"C",0,"");
		$this->Cell(30,2.5,'','');
		
		$this->Ln(2.8);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,2.6,"Outros Acréscimos","R",0,"L",0,"");
		$this->Cell(3,2.5,'','');
		$this->Cell(19.9,2.5,'','');
		$this->SetFont($fonte,'B',6);
		$this->Cell(100.5,2.6,$dadosboleto["endereco1"],"",0,"L",0,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(28.1,2.6,"","L",0,"L",0,"");
		$this->Cell(140,0.2,"",0,0,"C",0,"");
		$this->Cell(30,2.5,'','');
		
		$this->Ln(2.6);
		$this->Cell(-8,2.6,"");
		$this->SetFont('','',6);
		$this->Cell(42.5,3.6,"","BR",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(22.8,3.5,'','');
		$this->SetFont('Arial','B',6);
		$this->Cell(64.1,3.6,$dadosboleto["endereco2"],0,0,"L",0,"");
		$this->SetFont($fonte,'',7);
		$this->Cell(36.4,3.5,'','');
		$this->Cell(36,3.6,"","L",0,"R",0,"");
		$this->Cell(140,3,"",0,0,"C",0,"");
		$this->Cell(30,3.5,'','');
		
		$this->Ln(3.7);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);
		$this->Cell(42.5,2.6,"Valor Cobrado","R",0,"L",0,"");
		$this->Cell(3,2.5,'','');
		$this->Cell(17.2,2.5,'','');
		$this->SetFont($fonte,'',6);
		$this->SetLineWidth(0.3);
		$this->Cell(103.2,2.6,"Sacador / Avalista:","B",0,"L",0,"");
		$this->SetFont($fonte,'',6);
		$this->SetLineWidth(0.1);
		$this->Cell(36,2.6,"","L",0,"L",0,"");
		
		$this->Ln(2.6);
		$this->Cell(-8,3.6,"");
		$this->SetFont('','',7);
		$this->Cell(42.5,3.6,"","BR",0,"L",0,"");
		$this->Cell(0.1,3.5,'','');
		$this->Cell(28.9,3.5,'','');
		$this->SetFont('Arial','B',7);
		$this->Cell(64.1,4.5,$dadosboleto["linha_digitavel"],0,0,"L",0,"");
		$this->SetFont($fonte,'',7);
		$this->Cell(30.4,3.5,'',0,0,"C",0,"");
		$this->SetLineWidth(0.3);
		$this->Cell(36,3.2,"Autentificação","T",0,"C",0,"");
		$this->Cell(140,3,"",0,0,"C",0,"");
		$this->Cell(30,3.5,'','');
		
		$this->SetFont($fonte,'B',10);
		
		$this->SetFont($fonte,'',7);

		$this->SetLineWidth(0.1);
		$this->Ln(3.8);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',5);
		$this->Cell(42.5,3.1,"Sacado: ".substr($dadosboleto["sacado"],0,30),"R",0,"L",0,"");
		$this->Cell(3,3.1,'','');
		$this->Cell(14.3,3.1,'','');
		
		$this->Ln(2.1);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',5);	
		$this->Cell(42.5,3.0,$dadosboleto['CPF_CNPJ_Adicional'],"R",0,"L",0,"");
		$this->Cell(3,3.0,'','');
		$this->Cell(14.3,3.0,'','');
		
		$this->Ln(2.1);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',5);
		$this->Cell(42.5,3.0,"Favorecido: ".substr($dadosboleto["cedente"],0,27),"R",0,"L",0,"");
		$this->Cell(3,3.0,'','');
		$this->Cell(14.3,3.0,'','');
		
		$this->Ln(2.1);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'',6);	
		$this->Cell(42.5,3.0,$CPF_CNPJ .": ".$dadosboleto["cpf_cnpj"],"R",0,"L",0,"");
		$this->Cell(3,3.0,'','');
		$this->Cell(14.3,3.0,'','');
		
		$this->SetLineWidth(0.5);
		$this->Ln(3.5);
		$this->Cell(-8,0.2,"");
		$this->SetFont($fonte,'B',7);
		$this->Cell(49.0,3.3,"Comprovante de Pagamento","T",0,"R",0,"");
		$this->Cell(3,2.0,'','');
		$this->Cell(14.3,2.0,'','');
		
		
	
		$this->SetFillColor(0,0,0);
		$this->SetFont($fonte,'B',10);
		
		//$this->Cell(3,4,'',0,'',0);
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
		
		$sacado1	=	substr($linDadosCliente[Nome],0,20);
		$sacado2	=	substr($linDadosCliente[Nome],20,20);
		$sacado3	=	substr($linDadosCliente[Nome],40,20);
	
		//$this->Ln(3.5); 
		//$this->Cell(37,2,'',0,'',0);
		$this->SetXY(73.4,$posY+84.5);
		
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
		
		$this->Tracejado($posTemp-290);
	}
	
	function Tracejado($Posicao){
		global $Path;
		include($Path."modulos/administrativo/local_cobranca/funcao_tracejado_pdf.php");
	}
}
?>