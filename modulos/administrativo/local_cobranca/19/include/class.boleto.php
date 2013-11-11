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

		$SeparadorCampos	= "  -  ";
		
		include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");

		$dadosboleto["nosso_numero"]		= $linContaReceber[NumeroDocumento];
		$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Num do pedido ou do documento
		$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento]; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; // Data de emissão do Boleto
		$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; // Data de processamento do boleto (opcional)
		$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento]; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
		$dadosboleto["quantidade"]			= "1";
		$dadosboleto["valor_unitario"]		= $dadosboleto["valor_boleto"];
/*		$dadosboleto["aceite"]				= $CobrancaParametro[Aceite];
		$dadosboleto["especie"]				= $CobrancaParametro[Especie];
		$dadosboleto["especie_doc"]			= $CobrancaParametro[EspecieDocumento];
		$dadosboleto["banco_deposito"]		= $CobrancaParametro[BancoDeposito];
		$dadosboleto["numero_agencia"]		= $CobrancaParametro[AgenciaNumero];
		$dadosboleto["digito_agencia"]		= $CobrancaParametro[AgenciaDigito];
		$dadosboleto["numero_conta"]		= $CobrancaParametro[ContaNumero];
		$dadosboleto["digito_conta"]		= $CobrancaParametro[ContaDigito];	
		
		
*/				
		$cont = 0;
		$i = -1;		
	 	
		$sql = "select
					LocalCobrancaParametro.IdLocalCobrancaParametro,
					LocalCobrancaParametro.ValorLocalCobrancaParametro,
					LocalCobrancaLayoutParametro.ObsLocalCobrancaParametro
				from
					LocalCobrancaParametro,
					LocalCobrancaLayoutParametro														
				where
					LocalCobrancaParametro.IdLoja = $IdLoja and
					LocalCobrancaParametro.IdLocalCobranca = $dadosboleto[IdLocalCobranca] and
					LocalCobrancaParametro.IdLocalCobrancaLayout = LocalCobrancaLayoutParametro.IdLocalCobrancaLayout and
					LocalCobrancaParametro.IdLocalCobrancaParametro = LocalCobrancaLayoutParametro.IdLocalCobrancaParametro";
		$res2 = mysql_query($sql,$con);
		while($lin2 = mysql_fetch_array($res2)){

			$LocalCobrancaParametro[$lin2[IdLocalCobrancaParametro]] = $lin2[ValorLocalCobrancaParametro];					
			$j = 0;
			if($lin2[IdLocalCobrancaParametro] == "Banco1NumeroBanco"){
				if($lin2[ValorLocalCobrancaParametro] != ""){
					$aux = explode("\n",$lin2[ObsLocalCobrancaParametro]);							
					while($aux[$j] != ""){								
						$aux2 = explode("-",$aux[$j]);	
						if(trim($aux2[0]) == $lin2[ValorLocalCobrancaParametro]){
							$LocalCobrancaParametro['Banco1NomeBanco'] = substr($aux2[1],0,28);
							break;
						}								
						$j++;
					}														
					
					$lin2['Banco1NumeroBanco'] = $lin2[ObsLocalCobrancaParametro];
					$i++;
					$Banco[$i] = 1;
					$cont++;																					
				}
			}								
			if($lin2[IdLocalCobrancaParametro] == "Banco2NumeroBanco"){
				if($lin2[ValorLocalCobrancaParametro] != ""){
					$aux = explode("\n",$lin2[ObsLocalCobrancaParametro]);							
					while($aux[$j] != ""){								
						$aux2 = explode("-",$aux[$j]);	
						if(trim($aux2[0]) == $lin2[ValorLocalCobrancaParametro]){
							$LocalCobrancaParametro['Banco2NomeBanco'] = substr($aux2[1],0,32);
							break;
						}								
						$j++;
					}														
					
					$lin2['Banco2NumeroBanco'] = $lin2[ObsLocalCobrancaParametro];
					$i++;
					$Banco[$i] = 2;
					$cont++;							
				
				}
			}	
			if($lin2[IdLocalCobrancaParametro] == "Banco3NumeroBanco"){
				if($lin2[ValorLocalCobrancaParametro] != ""){
					$aux = explode("\n",$lin2[ObsLocalCobrancaParametro]);							
					while($aux[$j] != ""){								
						$aux2 = explode("-",$aux[$j]);	
						if(trim($aux2[0]) == $lin2[ValorLocalCobrancaParametro]){
							$LocalCobrancaParametro['Banco3NomeBanco'] = substr($aux2[1],0,32);
							break;
						}								
						$j++;
					}														
					
					$lin2['Banco3NumeroBanco'] = $lin2[ObsLocalCobrancaParametro];
					$i++;							
					$Banco[$i] = 3;
					$cont++;						
				}
			}	
			if($lin2[IdLocalCobrancaParametro] == "Banco4NumeroBanco"){
				if($lin2[ValorLocalCobrancaParametro] != ""){
					$aux = explode("\n",$lin2[ObsLocalCobrancaParametro]);							
					while($aux[$j] != ""){								
						$aux2 = explode("-",$aux[$j]);	
						if(trim($aux2[0]) == $lin2[ValorLocalCobrancaParametro]){
							$LocalCobrancaParametro['Banco4NomeBanco'] = substr($aux2[1],0,32);
							break;
						}								
						$j++;
					}
																			
					$lin2['Banco4NumeroBanco'] = $lin2[ObsLocalCobrancaParametro];
					$i++;
					$Banco[$i] = 4;
					$cont++;						
				}
			}	
		}
		
		if($LocalCobrancaParametro["Fax"] == ""){
			$LocalCobrancaParametro["Fax"] = $dadosboleto["fax"];	
		}	

		////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if($dadosboleto["digito_agencia"] != ''){
			$dadosboleto["numero_agencia"] = $dadosboleto["numero_agencia"]."-".$dadosboleto["digito_agencia"];
		}
		if($dadosboleto["digito_conta"] != ''){
			$dadosboleto["numero_conta"] = $dadosboleto["numero_conta"]."-".$dadosboleto["digito_conta"];	
		}

		$Instrucoes = InstrucoesBoleto($IdContaReceber);

		include("vars_ficha.php");

		//============================Não mude o valores abaixo=============
		// Default
		if($posY == null){
			$this->SetY(138.5);
			$posTemp = 122.5;
		}else{
			$this->SetY($posY);
			$posTemp = $posY;
		}
		$this->height_cell = 3;
		$this->margin_left = 10;
	    $this->SetLineWidth(0.3);
	
		// L1
		if($Background == 's'){
			$PatchImagens = $Path."/modulos/administrativo/local_cobranca/3/";
		}
				
		$this->Ln();
		$this->SetX(10.2);
		$this->Cell(0.1,18.5,'','',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(10.1,25.5,'Corte na linha pontilhada','',0,'L',0);
		$this->Cell(3,6.5,'','',0,'',0);	

		// L1
		$this->Ln();
		$i=11;
		while($i < 200){
			$this->Image($PatchImagens."imagens/6.jpg",$i,($posTemp + 30.5),1,0.1,jpg);
			$i += 3;
		}
		
		// L2
		$this->Ln();
		$this->SetFont('Arial','B',9);
		$this->Cell(0,3.5,'Ficha para Depósito','',0,'C',0);
		
		// L3
		$this->Cell(0,2.5,'','',1,'',0);
		$this->SetFont('Arial','B',7);		
		$this->Cell(0,4.5,'Informações do Título','',0,'L',0);
		
		//L4
		$this->Ln();
		$this->SetFont('Arial','B',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.2,5.5,'','',0,'',1);
		$this->Cell(28.9,3.5,'Nº do Conta a Receber: ','T',0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(65,3.5,$IdContaReceber,'T',0,'L',0); // valor do campo
			
		$this->SetFont('Arial','B',7);	
		$this->Cell(28.9,3.5,'Número do Documento: ','T',0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(66,3.5,$dadosboleto["numero_documento"],'T',0,'L',0); // valor do campo
		$this->Cell(0.2,4.5,'','T',1,'',1);	
		
		//L5	
		$this->Cell(0.2,4.5,'','',0,'',1);	
		$this->SetFont('Arial','B',7);
		$this->Cell(29.9,2,'Data do Processamento: ','',0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(64,2,$dadosboleto["data_processamento"],'',0,'L',0); // valor do campo
		
		$this->SetFont('Arial','B',7);
		$this->Cell(26.0,2,'Data do Vencimento: ','',0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(69.0,2,$dadosboleto["data_vencimento"],'',0,'L',0); // valor do campo
		$this->Cell(0.1,2.5,'','T',1,'',1);
						
		//L6	
		$this->Cell(0.1,5.5,'','',0,'',1);
		$this->SetFont('Arial','B',7);
		$this->Cell(5.9,7,'Fax: ','',0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(87.8,7,$LocalCobrancaParametro["Fax"],'',0,'L',0); // valor do campo
		
		$this->SetFont('Arial','',10);
		$this->Cell(34,6,'Valor para Depósito: ','',0,'L',0);
		$this->SetFont('Arial','B',10);
		$this->Cell(61.3,6,getParametroSistema(5,1)." ".$dadosboleto["valor_documento"],'',0,'L',0); // valor do campo
		$this->Cell(0.1,5.5,'','T',1,'',1);		
		$this->Cell(189.2,0.01,'','T',1,'',1); // borda bottom
		
		//L7 
		$this->Ln();

		//////////////////////////////////////////////////////////////Quadro de Informações para Déposito/////////////////////////////////////////////////////////
		$i = 0;
		//$cont = 4;
		if($cont >= 1){		
			$this->Cell(0,2.5,'','',1,'',0);
			$this->SetFont('Arial','B',7);		
			$this->Cell(0,4.5,'Informações para Depósito','',0,'L',0);
		
			//L9
			$this->Ln();
			$this->SetFont('Arial','B',9);
			$this->SetFillColor(0,0,0);
			$this->Cell(0.2,5.5,'','',0,'',1);
			$this->Cell(60.9,5,trim($LocalCobrancaParametro['Banco'.$Banco[$i].'NomeBanco']),'T',0,'L',0); //valor campo		
			$this->SetFont('Arial','B',7);
			$this->Cell(17,5,'Nº do Banco: ','T',0,'L',0); 		
			$this->SetFont('Arial','',7);
			$this->Cell(17.3,5,$LocalCobrancaParametro['Banco'.$Banco[$i].'NumeroBanco'],'T',0,'L',0); // valor do campo
			if($cont > 1){
				$this->Cell(0.2,5.5,'','T',0,'',1); // add borda central
			}
		}
		
		if($cont >= 2){		
			//quadro da direita
			$this->SetFont('Arial','B',9);	
			$this->Cell(60.9,5,trim($LocalCobrancaParametro['Banco'.$Banco[$i+1].'NomeBanco']),'T',0,'L',0);		
			$this->SetFont('Arial','B',7);		
			$this->Cell(17,5,'Nº do Banco: ','T',0,'L',0); // valor do campo		
			$this->SetFont('Arial','',7);		
			$this->Cell(15.5,5,$LocalCobrancaParametro['Banco'.$Banco[$i+1].'NumeroBanco'],'T',0,'L',0); // valor do campo
			$this->Cell(0.2,4.5,'','T',1,'',1);	
		}
		
		if($cont >= 1){		
			if($cont < 2){
				$this->Cell(0.2,4.5,'','T',1,'',1);	
			}
			//L10
			$this->SetFont('Arial','B',7);	
			$this->Cell(0.2,4.5,'','',0,'',1);
			$this->Cell(18.9,3.5,'Nº da Agência: ','',0,'L',0);
			$this->SetFont('Arial','',7);		
			$this->Cell(42,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i].'Agencia'],'',0,'L',0); // valor do campo		
			$this->SetFont('Arial','B',7);		
			$this->Cell(16,3.5,'Nº da Conta: ','',0,'L',0); // valor do campo
			$this->SetFont('Arial','',7);		
			$this->Cell(18.3,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i].'Conta'],'',0,'L',0); // valor do campo
			if($cont > 1){
				$this->Cell(0.2,4.5,'','',0,'',1);	
			}
		}
		
		if($cont >= 2){		
			//quadro da direita
			$this->SetFont('Arial','B',7);	
			$this->Cell(18.9,3.5,'Nº da Agência: ','',0,'L',0);		
			$this->SetFont('Arial','',7);		
			$this->Cell(42,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+1].'Agencia'],'',0,'L',0); // valor do campo			
			$this->SetFont('Arial','B',7);		
			$this->Cell(16,3.5,'Nº da Conta: ','',0,'L',0); // valor do campo		
			$this->SetFont('Arial','',7);		
			$this->Cell(16.5,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+1].'Conta'],'',0,'L',0); // valor do campo
			$this->Cell(0.2,4.5,'','',1,'',1);	
		}
		
		if($cont >= 1){	
			if($cont < 2){
				$this->Cell(0.2,4.5,'','',1,'',1);	
			}	
			//L11
			$this->SetFont('Arial','B',7);	
			$this->Cell(0.2,4.5,'','',0,'',1);
			$this->Cell(14.9,3,'CPF/CNPJ: ','',0,'L',0);
			$this->SetFont('Arial','',7);		
			$this->Cell(80.3,3,$LocalCobrancaParametro['Banco'.$Banco[$i].'CPFCNPJ'],'',0,'L',0); // valor do campo			
			if($cont > 1){
				$this->Cell(0.2,4.5,'','',0,'',1);
			}
		}
		
		if($cont >= 2){		
			//quadro da direita
			$this->SetFont('Arial','B',7);	
			$this->Cell(14.9,3.5,'CPF/CNPJ: ','',0,'L',0);		
			$this->SetFont('Arial','',7);		
			$this->Cell(78.5,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+1].'CPFCNPJ'],'',0,'L',0); // valor do campo					
			$this->Cell(0.2,4.0,'','',1,'',1);
		}
		
		if($cont >= 1){		
			if($cont < 2){
				$this->Cell(0.2,4.0,'','',1,'',1);
			}
			//L12
			$this->SetFont('Arial','B',7);	
			$this->Cell(0.2,4.0,'','',0,'',1);
			$this->Cell(10.4,3,'Títular: ','',0,'L',0);
			$this->SetFont('Arial','',7);		
			$this->Cell(84.6,3,$LocalCobrancaParametro['Banco'.$Banco[$i].'Titular'],'',0,'L',0); // valor do campo			
			$this->Cell(0.2,4.0,'','',0,'',1);
			if($cont < 2){
				$this->Cell(0.2,4.0,'','',1,'',1);
				$this->Cell(95.2,0.01,'','T',1,'',1); // borda bottom
			}
		}
		if($cont >= 2){		
			//quadro da direita
			$this->SetFont('Arial','B',7);	
			$this->Cell(10.4,3.5,'Títular: ','',0,'L',0);		
			$this->SetFont('Arial','',7);		
			$this->Cell(83.2,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+1].'Titular'],'',0,'L',0); // valor do campo					
			$this->Cell(0.2,4.0,'','',1,'',1);	
			$this->Cell(189.2,0.01,'','T',1,'',1); // borda bottom
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//////////////////////////////////////////////////////////////Quadro de Informações para Déposito 2/////////////////////////////////////////////////////////
		$this->Cell(0,2.5,'','',1,'',0); // espaço entre as tabelas
		
		if($cont >= 3){		
			//L9
			$this->Ln();
			$this->SetFont('Arial','B',9);
			$this->SetFillColor(0,0,0);
			$this->Cell(0.2,5.5,'','',0,'',1);
			$this->Cell(60.9,5,trim($LocalCobrancaParametro['Banco'.$Banco[$i+2].'NomeBanco']),'T',0,'L',0); //valor campo		
			$this->SetFont('Arial','B',7);
			$this->Cell(17,5,'Nº do Banco: ','T',0,'L',0); 		
			$this->SetFont('Arial','',7);
			$this->Cell(17.3,5,$LocalCobrancaParametro['Banco'.$Banco[$i+2].'NumeroBanco'],'T',0,'L',0); // valor do campo
			if($cont > 3){
				$this->Cell(0.2,5.5,'','T',0,'',1); // add borda central
			}
		}
		
		if($cont >= 4){		
			//quadro da direita
			$this->SetFont('Arial','B',9);	
			$this->Cell(60.9,5,trim($LocalCobrancaParametro['Banco'.$Banco[$i+3].'NomeBanco']),'T',0,'L',0);		
			$this->SetFont('Arial','B',7);		
			$this->Cell(17,5,'Nº do Banco: ','T',0,'L',0); // valor do campo		
			$this->SetFont('Arial','',7);		
			$this->Cell(15.5,5,$LocalCobrancaParametro['Banco'.$Banco[$i+3].'NumeroBanco'],'T',0,'L',0); // valor do campo
			$this->Cell(0.2,4.5,'','T',1,'',1);	
		}
		
		if($cont >= 3){		
			if($cont < 4){
				$this->Cell(0.2,4.5,'','T',1,'',1);	
			}
			//L10
			$this->SetFont('Arial','B',7);	
			$this->Cell(0.2,4.5,'','',0,'',1);
			$this->Cell(18.9,3.5,'Nº da Agência: ','',0,'L',0);
			$this->SetFont('Arial','',7);		
			$this->Cell(42,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+2].'Agencia'],'',0,'L',0); // valor do campo		
			$this->SetFont('Arial','B',7);		
			$this->Cell(16,3.5,'Nº da Conta: ','',0,'L',0); // valor do campo
			$this->SetFont('Arial','',7);		
			$this->Cell(18.3,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+2].'Conta'],'',0,'L',0); // valor do campo
			if($cont > 3){
				$this->Cell(0.2,4.5,'','',0,'',1);	
			}
		}
		
		if($cont >= 4){		
			//quadro da direita
			$this->SetFont('Arial','B',7);	
			$this->Cell(18.9,3.5,'Nº da Agência: ','',0,'L',0);		
			$this->SetFont('Arial','',7);		
			$this->Cell(42,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+3].'Agencia'],'',0,'L',0); // valor do campo			
			$this->SetFont('Arial','B',7);		
			$this->Cell(16,3.5,'Nº da Conta: ','',0,'L',0); // valor do campo		
			$this->SetFont('Arial','',7);		
			$this->Cell(16.5,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+3].'Conta'],'',0,'L',0); // valor do campo
			$this->Cell(0.2,4.5,'','',1,'',1);	
		}
		
		if($cont >= 3){	
			if($cont < 4){
				$this->Cell(0.2,4.5,'','',1,'',1);	
			}	
			//L11
			$this->SetFont('Arial','B',7);	
			$this->Cell(0.2,4.5,'','',0,'',1);
			$this->Cell(14.9,3,'CPF/CNPJ: ','',0,'L',0);
			$this->SetFont('Arial','',7);		
			$this->Cell(80.3,3,$LocalCobrancaParametro['Banco'.$Banco[$i+2].'CPFCNPJ'],'',0,'L',0); // valor do campo			
			if($cont > 3){
				$this->Cell(0.2,4.5,'','',0,'',1);
			}
		}
		
		if($cont >= 4){		
			//quadro da direita
			$this->SetFont('Arial','B',7);	
			$this->Cell(14.9,3.5,'CPF/CNPJ: ','',0,'L',0);		
			$this->SetFont('Arial','',7);		
			$this->Cell(78.5,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+3].'CPFCNPJ'],'',0,'L',0); // valor do campo					
			$this->Cell(0.2,4.0,'','',1,'',1);
		}
		
		if($cont >= 3){		
			if($cont < 4){
				$this->Cell(0.2,4.0,'','',1,'',1);
			}
			//L12
			$this->SetFont('Arial','B',7);	
			$this->Cell(0.2,4.0,'','',0,'',1);
			$this->Cell(10.4,3,'Títular: ','',0,'L',0);
			$this->SetFont('Arial','',7);		
			$this->Cell(84.6,3,$LocalCobrancaParametro['Banco'.$Banco[$i+2].'Titular'],'',0,'L',0); // valor do campo			
			$this->Cell(0.2,4.0,'','',0,'',1);
			if($cont < 4){
				$this->Cell(0.2,4.0,'','',1,'',1);
				$this->Cell(95.2,0.01,'','T',1,'',1); // borda bottom
			}
		}
		if($cont >= 4){		
			//quadro da direita
			$this->SetFont('Arial','B',7);	
			$this->Cell(10.4,3.5,'Títular: ','',0,'L',0);		
			$this->SetFont('Arial','',7);		
			$this->Cell(83.2,3.5,$LocalCobrancaParametro['Banco'.$Banco[$i+3].'Titular'],'',0,'L',0); // valor do campo					
			$this->Cell(0.2,4.0,'','',1,'',1);	
			$this->Cell(189.2,0.01,'','T',1,'',1); // borda bottom
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$this->Ln();
		$this->Cell(0,2.5,'','',1,'',0); // espaço entre as tabelas
		$this->SetFont('Arial','B',9);
		$this->Cell(0,3.5,'Procedimentos e Normas','',0,'C',0);
		
		$this->Ln();
		$this->SetFont('Arial','',8);
		$this->Cell(0,2.5,'','',1,'',0); 
				
		for($i=0; $i<count($Instrucoes); $i++){
			$this->MultiCell(0, 3, $Instrucoes[$i], 0, 'J', false); // imprime as instruções do local de cobrança
		}
		
		
				
		$this->Cell(0,12.5,'','',1,'',0); // quebra de linha
		
		//L17	
		$this->Cell(0,2.5,'','',1,'',0);
		$this->SetFont('Arial','B',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.2,7.5,'','',0,'',1);
		$this->Cell(188.9,5,'Cole aqui seu comprovante de depósito/transferência.','T',0,'C',0); //valor campo		
		$this->Cell(0.2,7.5,'','T',1,'',1); // add borda central
		
		//L18		
		$this->SetFont('Arial','B',7);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.2,0,'','',0,'',1);

		if(trim($LocalCobrancaParametro["Email"]) == ""){
			$this->MultiCell(0, -3,"                                                                                     Envie esta ficha de depósito para o", 0, 'J', false); // imprime as instruções do local de cobrança
			$this->SetFont('Arial','B',9);
			$this->MultiCell(0, 2.5,"                                                                                                                  Fax: ".$LocalCobrancaParametro["Fax"], 0, 'J', false); // imprime as instruções do local de cobrança
		}else{
			$this->MultiCell(0, -3,"                                                                                  Envie uma cópia digitalizada para o:", 0, 'J', false); // imprime as instruções do local de cobrança
			$this->SetFont('Arial','B',9);
			$this->MultiCell(0, 2.5,"                                                                                                                  E-mail: ".$LocalCobrancaParametro["Email"], 0, 'J', false); // imprime as instruções do local de cobrança
		}
	}

	function Tracejado($Posicao){
		global $Path;
		include($Path."modulos/administrativo/local_cobranca/funcao_tracejado_pdf.php");
	}
}
?>
