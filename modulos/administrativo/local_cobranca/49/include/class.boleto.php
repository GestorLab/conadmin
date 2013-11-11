<?
class Boleta extends FPDF
{
	
	
	function Demonstrativo($IdLoja, $IdContaReceber, $con){
		global $Path;
		include($Path."modulos/administrativo/local_cobranca/49/funcao_demonstrativo_pdf.php");
	}
	
	function DemonstrativoCarne($IdLoja, $IdCarne, $con){
		global $Path;
		include($Path."modulos/administrativo/local_cobranca/49/funcao_demonstrativo_carne_pdf.php");
	}
	
	function Titulo($IdLoja,$con){
	
	
	global $Background;
	global $IdLoja;
	global $IdContaReceber;
	global $ExtLogoPDF;
	global $Path;
	include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	Global $lin;
	
	$i_contrato=0;
	$achou_contrato=false;
	
	$dadosboleto["nosso_numero"]			= $linContaReceber[NumeroDocumento]; // tamanho 9
	$dadosboleto["numero_documento"]		= $linContaReceber[NumeroDocumento];	// Num do pedido ou do documento
	$dadosboleto["data_vencimento"]			= $linContaReceber[DataVencimento]; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"]			= $linContaReceber[DataLancamento]; // Data de emissão do Boleto
	$dadosboleto["data_processamento"]		= $linContaReceber[DataLancamento]; // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"]			= $linContaReceber[ValorLancamento]; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
	$dadosboleto["local_pagamento"]			= $CobrancaParametro[DescricaoLocalPagamento];
	$dadosboleto["quantidade"]				= "1";
	$dadosboleto["valor_unitario"]			= $dadosboleto["valor_boleto"];
	$dadosboleto["aceite"]					= $CobrancaParametro[Aceite];
	$dadosboleto["especie"]					= $CobrancaParametro[Especie];
	$dadosboleto["especie_doc"]				= $CobrancaParametro[EspecieDocumento];
	$dadosboleto["ponto_venda"]				= $CobrancaParametro[Agencia]; // Num da agencia, sem digito
	$dadosboleto["codigo_cliente"]			= $CobrancaParametro[CodigoCedente];
	$dadosboleto["carteira"]				= $CobrancaParametro[Carteira];
	//=============Dados da Sua empresa===============
	include($Path."modulos/administrativo/local_cobranca/49/include/vars_santander_banespa.php");
	$contador_contrato		=	0;
	$contador_ordem_servico	=	0;
	$contador_conta_eventual=	0;
	
	$contrato;
	$ordem_servico;
	$conta_eventual;
	
	$valor_contrato;
	$valor_ordem_servico;
	$valor_conta_eventual;
	
	$valor_contrato_despesa;
	$valor_ordem_servico_despesa;
	$valor_conta_eventual_despesa;
	
	
	$sql = "select
				Tipo,
				Codigo,
				Descricao,
				Referencia,
				Valor,
				ValorDespesas,
				IdTerceiro,
				Codigo
			from
				Demonstrativo
			where
				IdLoja = $IdLoja and
				IdContaReceber = $IdContaReceber 
			order by
				IdTerceiro,
				Tipo,
				Codigo,
				IdLancamentoFinanceiro";
	$res		 = mysql_query($sql,$con);
	while($lin	 = mysql_fetch_array($res)){
		if($lin[Tipo]=='CO'){
			$contrato[$contador_contrato]["Codigo"] 	= $lin[Codigo];
			$contrato[$contador_contrato]["Tipo"] 		= $lin[Tipo];
			$contrato[$contador_contrato]["Valor"] 		= $lin[Valor];
			$valor_contrato_despesa            			 += $lin[ValorDespesas];
			$valor_contrato            					 += $lin[Valor];
			$contrato[$contador_contrato]["Descricao"] 	= $lin[Descricao];
			$contrato[$contador_contrato]["Referencia"] = $lin[Referencia];
			$contador_contrato++;
			
		}if($lin[Tipo]=='OS'){
			$ordem_servico[$contador_ordem_servico]["Codigo"] 	= $lin[Codigo];
			$ordem_servico[$contador_ordem_servico]["Tipo"] 	= $lin[Tipo];
			$ordem_servico[$contador_ordem_servico]["Valor"] 	= $lin[Valor];
			$valor_ordem_servico_despesa						+= $lin[ValorDespesas];
			$valor_ordem_servico								+= $lin[Valor];
			$ordem_servico[$contador_ordem_servico]["Descricao"] = $lin[Descricao];
			$ordem_servico[$contador_ordem_servico]["Referencia"]= $lin[Referencia];
			$contador_ordem_servico++;
		}if($lin[Tipo]=='EV'){
			$conta_eventual[$contador_conta_eventual]["Codigo"]    = $lin[Codigo];
			$conta_eventual[$contador_conta_eventual]["Tipo"]    = $lin[Tipo];
			$conta_eventual[$contador_conta_eventual]["Valor"] 	   = $lin[Valor];
			$valor_conta_eventual_despesa							+= $lin[ValorDespesas];
			$valor_conta_eventual									+= $lin[Valor];
			$conta_eventual[$contador_conta_eventual]["Descricao"] = $lin[Descricao];
			$conta_eventual[$contador_conta_eventual]["Referencia"]= $lin[Referencia];
			$contador_conta_eventual++;
		}
	}
	
	// Default
	$this->margin_left = 10;

	// Conteúdo - Cabeçalho
	$divisor = 3.57256;

	if($Background == 's'){
		$logo = $Path.$ExtLogoPDF_BCK;
	}else{
		$logo = $ExtLogoPDF;
	}
	
	$ExtLogo = endArray(explode(".",$logo));
	
	$dadosImg			= getimagesize($logo);
	$dadosImgLargura	= ($dadosImg[0]/$divisor);
	$dadosImgAltura		= ($dadosImg[1]/$divisor);
	$this->SetFont('Arial','',8);
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(0);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0.3);

	$sql = "select
				IdNotaFiscalLayout
			from
				NotaFiscal
			where
				Idloja = $IdLoja and
				IdContaReceber = $IdContaReceber and
				IdStatus = 1";
	$resNotaFiscal = @mysql_query($sql,$con);
	if($linNotaFiscal = @mysql_fetch_array($resNotaFiscal)){
		$sql = "select 
				Pessoa.IdPessoa, 
				Pessoa.TipoPessoa, 
				Pessoa.Nome, 
				Pessoa.RazaoSocial, 
				Pessoa.CPF_CNPJ, 
				Pessoa.RG_IE, 
				PessoaEndereco.Endereco, 
				PessoaEndereco.Numero, 
				PessoaEndereco.Complemento, 
				PessoaEndereco.Bairro,
				PessoaEndereco.CEP,
				Cidade.NomeCidade, 
				Estado.SiglaEstado,
				Pessoa.Telefone1, 
				Pessoa.Telefone2, 
				Pessoa.Fax
			from 
				Pessoa,
				PessoaEndereco,
				Cidade, 
				Estado,
				NotaFiscalTipo
			where 
				NotaFiscalTipo.IdLoja = $IdLoja and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				Cidade.IdPais = PessoaEndereco.IdPais and 
				Cidade.IdEstado = PessoaEndereco.IdEstado and 
				Cidade.IdCidade = PessoaEndereco.IdCidade and 
				Cidade.IdPais = Estado.IdPais and 
				Cidade.IdEstado = Estado.IdEstado and
				Pessoa.IdPessoa = NotaFiscalTipo.IdPessoa and			
				NotaFiscalTipo.IdNotaFiscalLayout = $linNotaFiscal[IdNotaFiscalLayout]";
		$res = mysql_query($sql,$con);
		if($dadosNF = mysql_fetch_array($res)){
			
			if($linDadosPessoaNotaFiscal[Telefone1] != ''){
				$dadosNF[Telefone] = $dadosNF[Telefone1];
			}else{
				$dadosNF[Telefone] = $dadosNF[Telefone2];
			}
		
			if($dadosNF[TipoPessoa] == 1){
				$CPF_CNPJ = "CNPJ";
			}else{			
				$CPF_CNPJ = "CPF";
			}	
			
			$dadosNF[cpf_cnpj_cedente] = $dadosNF[CPF_CNPJ];
		
			$dadosNF[endereco]	= $dadosNF[Endereco].", ".$dadosNF[Numero];
		
			if($dadosNF[Complemento] != ''){
				$dadosNF[endereco] .= " - ".$dadosNF[Complemento];
			}
		
			$dadosNF[endereco] .= " - ".$dadosNF[Bairro];
			
			$dadosNF[cidade]	= $dadosNF[NomeCidade]."-".$dadosNF[SiglaEstado]." - Cep: ".$dadosNF[CEP];
			$dadosNF[cedente]	= substr($dadosNF[RazaoSocial],0,65);
		
			if($dadosNF[Telefone] != ''){
				$dadosNF[fone] 				= " - Fone / Fax: ".$dadosNF[Telefone];
			}
		
			if($dadosNF[RG_IE] != ''){
				$dadosNF[ie] 				= " - IE: ".$dadosNF[RG_IE];
			}
			//$this->Cell(190,1,'','T');
			//$this->Image($logo,13,3.5,$dadosImgLargura,$dadosImgAltura,$ExtLogo);
			//$this->MultiCell(0,3.5,"$dadosNF[cedente]\n$dadosNF[endereco] - $dadosNF[cidade]\nCNPJ: $dadosNF[cpf_cnpj_cedente]".$dadosNF[ie].$dadosNF[fone],0,0,'L',1);
			}
		}else{
				$cep="04218970";
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
				if(file_exists("imagens/cep_cod_barra.png")){
					unlink("imagens/cep_cod_barra.png");
					ImagePNG($img,"imagens/cep_cod_barra.png");
				}else{
					ImagePNG($img,"imagens/cep_cod_barra.png");
				}
			
		#$this->Image("imagens/boleto_interno.jpg",0,0,210,290,"jpg");
		$this->Image("imagens/boleto_navex.JPG",0,0,210,290,"JPG");
		
		$this->setY(7.5);
		$valor_total=number_format(($valor_contrato+$valor_contrato_despesa)+($valor_ordem_servico+$valor_ordem_servico_despesa)+($valor_conta_eventual+$valor_conta_eventual_despesa),2,",",".");
		$this->Cell(41,4,'',0,0,'L',0);
		$this->SetFont('Arial','B',7.3);
		$this->Cell(35,4,'NOME DO USUÁRIO',0,0,'L',0);
		$this->Cell(12,4,'',0,0,'L',0);
		$this->SetFont('Arial','B',6.5);
		$this->Cell(30,4,'CÓDIGO NAVEX',0,0,'L',0);
		$this->Cell(5,4,'',0,0,'L',0);
		$this->SetFont('Arial','',7.5);
		$this->Cell(25,4,'VENCIMENTO',0,0,'L',0);
		$this->Cell(19,4,'',0,0,'L',0);
		$this->Cell(35,4,'VALOR',0,1,'L',0);
			
		$this->Cell(41,3,'',0,0,'L',0);
		$this->SetFont('Arial','',7.3);
		$this->Cell(37,3,$linDadosCliente['Nome'],0,'','L',0);
		$this->Cell(10,3,'',0,0,'L',0);
		$this->SetFont('Arial','B',7.3);
		$this->Cell(33,3,$linDadosCliente['IdPessoa'],0,0,'L',0);
		
		$this->SetFont('Arial','B',9);
		$this->Cell(20,3,$dadosboleto['data_vencimento'],0,0,0,0);
		$this->Cell(10,3,'',0,0,'L',0);
		$this->SetFont('Arial','B',16.7);
		$this->Cell(16,3,'',0,0,0,0,0);
		$this->Cell(34,3.5,$valor_total,0,1,'L',0);
		
		$this->SetFont('Arial','',7.3);
		$this->Cell(41,3,'',0,0,'L',0);
		$this->Cell(47,3,$dadosboleto["endereco1"],0,0,'L',0);
		$this->Cell(40,3,'',0,0,'L',0);
		$this->Cell(40,3,'',0,'T',0,0,0);
		$this->Cell(40,3,'',0,1,'L',0);
		
		$this->Cell(41,3,'',0,0,'L',0);
		$this->Cell(47,3,$linDadosCliente[NomeCidade]." - ".$linDadosCliente[SiglaEstado],0,0,'L',0);
		$this->SetFont('Arial','B',6.5);
		$this->Cell(35,3,'CPF / CNPJ',0,0,'L',0);
		$this->SetFont('Arial','',7.5);
		$this->Cell(40,3,'FORMA DE PAGAMENTO',0,0,'L',0);
		$this->Cell(10,3,'',0,0,'L',0);
		$this->Cell(40,3,'',0,1,'L',0);
		
		$this->SetFont('Arial','',6.7);
		$this->Cell(41,3,'',0,0,'L',0);
		$this->Cell(47,3,"CEP: ".$dadosboleto["cep"],0,0,'L',0);
		$this->SetFont('Arial','',7);
		$this->Cell(35,3,$linDadosCliente['CPF_CNPJ'],0,0,'L',0);
		$this->SetFont('Arial','B',9);
		$this->Cell(30,3,'BOLETO BANCÁRIO',0,0,'L',0);
		$this->Cell(40,3,'',0,1,'L',0);
		
		$this->Ln(3.3);
		$this->SetFont('Arial','B',7);
		$this->Cell(193,12,'',0,1,'L',0);
		$this->Cell(106,12,'',0,0,'L',0);
		$achou_contrato=false;
		if($contador_contrato>0){
			$i_contrato=0;
			while($i_contrato<$contador_contrato){
				if($i_contrato==0){
					$this->SetFont('Arial','B',7);
					$this->Cell(60,4,$contrato[$i_contrato]["Tipo"]."-".$contrato[$i_contrato]["Codigo"]."   ".substr($contrato[$i_contrato]["Descricao"],0,48),0,2,'L',0);
					$this->SetFont('Arial','',7);
					$this->Cell(66,3,substr($contrato[$i_contrato]["Referencia"],0,60),0,0,'L',0);
					$valor=number_format($contrato[$i_contrato]["Valor"],2,",",".");
					$this->SetFont('Arial','B',7.5);
					$this->Cell(12,3,$valor,0,1,'R',0);
					$this->Ln(0.6);
					$this->SetX(116);
					$achou_contrato=true;
				}else{
						$this->SetFont('Arial','B',7);
						$this->Cell(60,3,$contrato[$i_contrato]["Tipo"]."-".$contrato[$i_contrato]["Codigo"]."   ".substr($contrato[$i_contrato]["Descricao"],0,48),0,2,'L',0);
						$this->SetFont('Arial','',7);
						$this->Cell(66,3,substr($contrato[$i_contrato]["Referencia"],0,60),0,0,'L',0);
						$valor=number_format($contrato[$i_contrato]["Valor"],2,",",".");
						$this->SetFont('Arial','B',7.5);
						$this->Cell(12,3,$valor,0,1,'R',0);
						$this->SetX(116);
						$achou_contrato=true;
				}
				$i_contrato++;
			}
		}if($achou_contrato==false){
			$this->Cell(60,3,'NAVEX Internet',0,0,'L',0);
		}
		
		$this->SetY(52);
		$this->SetX(22);
		$this->SetFont('Arial','',7);
		$this->Cell(75,3.5,'Cedente','TRL',1,'L',0);
		
		$this->SetX(22);
		$this->SetFont('Arial','B',8);
		$this->Cell(75,3.5,$dadosboleto["cedenteTit"],'LR',1,'L',0);
		$this->SetX(22);
		$this->SetFont('Arial','',7);
		$this->Cell(50,3.5,'CPF/CEI/CNPJ','L',0,'L',0);
		$this->Cell(25,3.5,'N° do Documento','R',1,'L',0);
		$this->SetX(22);
		$this->SetFont('Arial','B',8);
		$this->Cell(50,3.5,$dadosboleto["cpf_cnpj"],'L',0,'L',0);
		$this->Cell(25,3.5,$dadosboleto["numero_documento"],'R',1,'L',0);
		
		$this->SetX(22);
		$this->SetFont('Arial','',7);
		$this->Cell(50,3.5,'Agência/Código do Cedente','L',0,'L',0);
		$this->Cell(25,3.5,'Espécie','R',1,'L',0);
		
		$this->SetX(22);
		$this->SetFont('Arial','B',8);
		$this->Cell(50,3.5,$dadosboleto["ponto_venda"]." / ".$dadosboleto["codigo_cliente"],'L',0,'L',0);
		$this->Cell(25,3.5,$dadosboleto["especie"],'R',1,'L',0);
		
		$this->SetX(22);
		$this->SetFont('Arial','',7);
		$this->Cell(75,3.5,'Sacado','TRL',1,'L',0);
		
		$this->SetFont('Arial','B',8);
		$this->SetX(22);
		$this->Cell(75,3.5,$linDadosCliente['Nome']." [".$linDadosCliente['IdPessoa']."]",'RL',1,'L',0);
		$this->SetX(22);
		$this->SetFont('Arial','',7);
		$this->Cell(75,3.5,'Sacado/Avalista','RL',1,'L',0);
		$this->SetX(22);
		$this->Cell(75,3.5,'','BRL',1,'L',0);
		
		$this->setY(96);
		$this->SetFont('Arial','B',7);
		$this->Cell(38,7,'Importante:',0,0,'C',0);
		$this->Cell(70,7,'',0,0,0,0);
		$this->SetFont('Arial','',5.5);
		$this->setY(55);
		$this->setX(118);
		if($achou_contrato==false){
			$this->Cell(64,3,'',0,1,'L',0);
		}
		
		$this->Cell(15,0,'',0,1,'C',0);
		$this->Cell(10,0,'',0,0,'C',0);
		
		$this->setY(103);
		$this->setX(21);
		$this->SetFont('Arial','',6.6);
		$this->MultiCell(77,3,substr($CobrancaParametro[Importante],0,650),0,1,0,0);
		
		$this->setY(55);
		$this->setX(97);
		
		$this->SetFont('Arial','',7.5);
		
		if($achou_contrato==false){
			$valor_contrato='0,00';
		}
		
		$this->SetY(65);
		$this->Cell(106,5,'',0,0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFont('Arial','B',7);
		$this->Cell(54,5,'Total Itens Contrato ',0,0,'L',0);
		$this->SetFont('Arial','B',9);
		$valor_contrato=number_format($valor_contrato,2,",",".");
		$this->Cell(24,5,$valor_contrato,0,0,'R',0);
		
		$this->SetFont('Arial','B',6.7);
		$this->Cell(150,17,'',0,1,'L',0);
		$this->Cell(106,17,'',0,0,'L',0);
		$achou_ordem_servico=false;
		$i_ordem_servico=0;
		if($contador_ordem_servico>0){
			$achou_ordem_servico=true;
			while($i_ordem_servico<$contador_ordem_servico){
				$this->SetFont('Arial','B',7);
				$this->Cell(65,4,$ordem_servico[$i_ordem_servico]["Tipo"]."-".$ordem_servico[$i_ordem_servico]["Codigo"]."   ".substr($ordem_servico[$i_ordem_servico]["Descricao"],0,48),0,2,'L',0);
				$this->SetFont('Arial','',7);
				$this->Cell(66,3,substr($ordem_servico[$i_ordem_servico]["Referencia"],0,60),0,0,'L',0);
				$valor=number_format($ordem_servico[$i_ordem_servico]["Valor"],2,",",".");
				$this->SetFont('Arial','B',7.5);
				$this->Cell(12,3,$valor,0,1,'R',0);
				$this->SetX(116);
				$i_ordem_servico++;
				
			}
		}
		if($achou_ordem_servico==false){
			$this->SetY(81.5);
			$this->SetX(116);
			$this->Cell(60,5,'Minha NAVEX',0,2,'L',0);
		}
		$this->SetY(80);
		$this->SetFont('Arial','',6.6);
		$this->Cell(150,3,'',0,1,'L',0);
		$this->Cell(106,3,'',0,0,'L',0);
		
		
		$this->Cell(150,5,'',0,1,'L',0);
		$this->Cell(113,5,'',0,0,'L',0);
		$this->Cell(41,5,'',0,0,'L',0);
		$this->SetFont('Arial','',7.5);
		
		$this->SetY(106);
		$this->SetFont('Arial','',6.5);
		$this->Cell(106,5,'',0,0,'L',0);
		if($achou_ordem_servico==false){
			$this->Cell(60,5,'',0,0,'L',0);
			$this->SetFont('Arial','B',7.5);
			$this->Cell(18,5,'',0,1,'R',0);
			$valor_ordem_servico='0,00';
		}else{
			$this->Cell(61,5,'',0,0,'L',0);
			$this->SetFont('Arial','B',7.5);
			$this->Cell(16,5,'',0,1,'R',0);
		}
		$this->Cell(106,5,'',0,0,'L',0);
		$this->SetFont('Arial','B',7);
		$this->Cell(55,5,'Total Itens Ordem de Serviço ',0,0,'L',0);
		$this->SetFont('Arial','B',9);
		$valor_ordem_servico=number_format($valor_ordem_servico,2,",",".");
		$this->Cell(23,5,$valor_ordem_servico,0,1,'R',0);
		
		$this->setY(140.5);
		$this->SetFont('Arial','B',7);
		$this->Cell(106,7.5,'',0,0,'L',0);
		$achou_conta_eventual=false;
		if($contador_conta_eventual>0){
			$i_conta_eventual=0;
			while($i_conta_eventual<$contador_conta_eventual){
				$this->SetFont('Arial','B',7);
				$this->Cell(65,4,$conta_eventual[$i_conta_eventual]["Tipo"]."-".$conta_eventual[$i_conta_eventual]["Codigo"]."   ".substr($conta_eventual[$i_conta_eventual]["Descricao"],0,48),0,2,'L',0);
				$this->SetFont('Arial','',7);
				$this->Cell(66,3,substr($conta_eventual[$i_conta_eventual]["Referencia"],0,60),0,0,'L',0);
				$valor=number_format($conta_eventual[$i_conta_eventual]["Valor"],2,",",".");
				$this->SetFont('Arial','B',7.5);
				$this->Cell(12,3,$valor,0,1,'R',0);
				$this->SetX(110);
				$achou_conta_eventual=true;
				$i_conta_eventual++;
			}
		}if($achou_conta_eventual==false){
				$this->Cell(116,4,'',0,1,'L',0);
		}
		
		$this->Ln(4.2);
		
		$this->Cell(100,5,'',0,0,'C',0);
		$this->SetFont('Arial','',5.5);
		$this->setY(142.5);
		$this->setX(106);
		
		$this->setY(137);
		$this->SetFont('Arial','B',7);
		$this->Cell(11,0,' ',0,0,'L',0);
		$this->Cell(30,12,'Descrição',0,1,'L',0);
		$this->SetFont('Arial','',7);
		
		$this->SetFont('Arial','',7);
		$this->SetY(150.5);
		if($achou_contrato==true){
			$this->Cell(17,6,'',0,0,'R',0);
			$this->Cell(55,6,'Total de Serviços do Contrato',0,0,'L',0);
			$this->SetFont('Arial','',7.5);
			$this->Cell(15,6,$valor_contrato,0,1,'R',0);
		}
		if($achou_ordem_servico==true){
			$this->Cell(17,6,'',0,0,'R',0);
			$this->SetFont('Arial','',7);
			$this->Cell(55,6,'Total de Serviços de Ordem de Serviço',0,0,'L',0);
			$this->SetFont('Arial','',7.5);
			$this->Cell(15,6,$valor_ordem_servico,0,1,'R',0);
		}
		if($achou_conta_eventual==true){
			$this->Cell(17,6,'',0,0,'R',0);
			$this->SetFont('Arial','',7);
			$this->Cell(55,6,'Total de Serviços do Conta Eventual',0,0,'L',0);
			$this->SetFont('Arial','',7.5);
			$valor_conta_eventual=number_format($valor_conta_eventual,2,",",".");
			$this->Cell(15,6,$valor_conta_eventual,0,1,'R',0);
		}
		
		$this->setY(170);
		$this->setX(116);
		if($achou_conta_eventual==true){
			$this->Cell(61,5,'',0,1,'L',0);
			
		}else{
			$this->Cell(61,5,'',0,0,'L',0);
			$this->SetFont('Arial','B',8);
			$this->Cell(22,5,'',0,1,'R',0);
			$valor_conta_eventual='0,00';
		}
		
		$this->setY(173);
		$this->Cell(15,5,'',0,1,'R',0);
		$this->SetFont('Arial','',7);
		$this->Cell(58,5,'',0,0,'R',0);
		$this->Cell(33,4,'VALOR',0,1,'R',0);
		$this->Cell(70,5,'',0,0,'R',0);
		$this->SetFont('Arial','B',16.3);
		$this->Cell(21,5,$valor_total,0,0,'R',0);
		$this->SetFont('Arial','B',7);
		$this->setY(180.3);
		$this->setX(116);
		
		$this->Cell(68,4,'Total Itens Eventuais',0,0,'L',0);
		$this->SetFont('Arial','B',9);
		$this->Cell(29,5,$valor_conta_eventual,0,0,'L',0);
		
		$this->setY(203.5);
		$this->setX(27);
		$this->SetFont('Arial','',6.3);
		$this->MultiCell(39,2.5,substr($CobrancaParametro[Aviso1],0,300),0);
		
		$this->setY(203.5);
		$this->setX(80);
		$this->SetFont('Arial','',6.3);
		$this->MultiCell(39,2.5,substr($CobrancaParametro[Aviso2],0,300),0);
		
		$this->setY(203.5);
		$this->setX(131);
		$this->SetFont('Arial','',6.3);
		$this->MultiCell(39,2.5,substr($CobrancaParametro[Aviso3],0,300),0);
		
		$this->setY(225);
		$this->setX(87);
		$this->SetFont('Arial','',8);
		$this->Cell(25,4,'Instruçôes',0,0,'R',0);
		
		$this->setY(229);
		$this->setX(50);
		$this->SetFont('Arial','',7);
		$this->Cell(184,2.7,substr($CobrancaParametro[Instrucoes0],0,110),0,1,'L',0);
		$this->setX(50);
		$this->Cell(184,2.7,substr($CobrancaParametro[Instrucoes1],0,110),0,1,'L',0);
		$this->setX(50);
		$this->Cell(184,2.7,substr($CobrancaParametro[Instrucoes2],0,110),0,1,'L',0);
		$this->setX(50);
		$this->Cell(184,2.7,substr($CobrancaParametro[Instrucoes3],0,110),0,1,'L',0);
		$this->setX(50);
		$this->Cell(184,2.7,substr($CobrancaParametro[Instrucoes4],0,110),0,1,'L',0);
		$this->setX(50);
		$this->Cell(184,2.7,substr($CobrancaParametro[Instrucoes5],0,110),0,1,'L',0);
		$this->setX(50);
		$this->Cell(184,2.7,substr($CobrancaParametro[Instrucoes6],0,110),0,1,'L',0);
		$this->setX(50);
		$this->Cell(184,2.7,substr($CobrancaParametro[Instrucoes7],0,110),0,1,'L',0);
		
		$this->SetFont('Arial','B',7.5);
		$this->setY(252);
		$this->setX(12);
		$this->Cell(61,4,'Sacado','TLBR',0,'C',0);
		$this->Cell(25,4,'Cedente','TLBR',0,'C',0);
		
		$this->Cell(30,4,'N° Documento','TLBR',0,'L',0);
	
		$this->SetFont('Arial','',8);
		$this->Cell(34,4,$dadosboleto["numero_documento"],'TLBR',0,'C',0);
		
		$this->SetFont('Arial','B',8);
		
		$this->Cell(21,4,' Vencimento','TLBR',0,'C',0);
		
		$this->Cell(16,4,'Valor','TLBR',1,'C',0);
		
		#INFORMATIVO
		$this->SetFont('Arial','',6.5);
		$this->setY(256);
		$this->setX(12);
		$this->Cell(61,3.5,substr($linDadosCliente[Nome],0,35)." [".$linDadosCliente['IdPessoa']."] ,CPF: ".$linDadosCliente['CPF_CNPJ'],'RL',0,'L',0);
		$this->Cell(25,3.5,substr($dadosboleto["cedenteTit"],0,20),'RL',0,'L',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(30,3.5,'Nosso Número','TLRB',0,'L',0);
		$this->SetFont('Arial','',8);
		$this->Cell(34,3.5, $dadosboleto['nosso_numero'],'LRBT',0,'C',0);
		
		$this->Cell(21,3.5,	$dadosboleto["data_vencimento"]	,'LR',0,'C',0);	
		$this->Cell(16,3.5,$valor_total,'LR',1,'C',0);	
		
		$this->setX(12);	
		$this->SetFont('Arial','',6.7);		
		$this->Cell(61,3.5,$dadosboleto["endereco1"],'LR',0,'L',0);
			
		$this->Cell(25,3.5,'','',0,'C',0);
		$this->SetFont('Arial','B',7.5);	
		$this->SetFont('Arial','',6.7);		
		$this->SetFont('Arial','B',7.5);
		$this->Cell(30,3.5,'Agência/Cód. Cedente','LTB',0,'L',0);
		$this->SetFont('Arial','',8);		
		$this->Cell(34,3.5,$dadosboleto["ponto_venda"]." / ".$dadosboleto["codigo_cliente"],'LTBR',0,'C',0);
		$this->SetFont('Arial','',6.7);	
		
		$this->Cell(21,3.5,'','L',0,'L',0);
		$this->Cell(16,3.5,'','LR',1,'L',0);
		
		$this->setX(12);
		$this->Cell(61,3.5,$linDadosCliente[NomeCidade]." - ".$linDadosCliente[SiglaEstado]." CEP: ".$dadosboleto["cep"],'LRB',0,'L',0);
		$this->Cell(25,3.5,$dadosboleto["cpf_cnpj"],'RLB',0,'L',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(30,3.5,'Carteira ','LTRB',0,'L',0);
		$this->SetFont('Arial','',8);
		$this->Cell(34,3.5,$dadosboleto['carteira'],'LTB',0,'C',0);
		
		$this->Cell(21,3.5,'','LB',0,'L',0);
		$this->Cell(16,3.5,'','LRB',1,'L',0);
		
		$this->SetY(266);
		$this->setX(42);
		$this->SetFont('Arial','',11);
		$this->Cell(126.2,6,$dadosboleto["linha_digitavel"],0,0,'C',0);
		
		$this->SetY(269);
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
		
		$this->Ln(2);
		$this->setX(43);
		// Preto - Fino
		$this->SetFillColor(0,0,0);
		$this->Cell($fino,$altura,'',0,0,'C',1);

		// Branco - Fino
		$this->SetFillColor(255,255,255);
		$this->Cell($fino,$altura,'',0,0,'C',1);

		// Preto - Fino
		$this->SetFillColor(0,0,0);
		$this->Cell($fino,$altura,'',0,0,'C',1);

		// Branco - Fino
		$this->SetFillColor(255,255,255);
		$this->Cell($fino,$altura,'',0,0,'C',1);

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
					$this->Cell($fino,$altura,'',0,0,'C',1);
				} 

				if ($CodPreto[$y] == '1') { // Se o binario for preto e grosso ecoa 
					// Preto - Largo
					$this->SetFillColor(0,0,0);
					$this->Cell($largo,$altura,'',0,0,'C',1);
				} 

				if ($CodBranco[$y] == '0') { // Se o binario for branco e fino ecoa 
					// Branco - Fino
					$this->SetFillColor(255,255,255);
					$this->Cell($fino,$altura,'',0,0,'C',1);
				} 

				if($CodBranco[$y] == '1') { // Se o binario for branco e grosso ecoa 
					// Branco - Largo
					$this->SetFillColor(255,255,255);
					$this->Cell($largo,$altura,'',0,0,'C',1);
				} 
			} 

		} // Fechamos nosso looping maior 

		// Encerramos o código ecoando o final(encerramento) 
		// Final padrão do Codigo de Barras 

		// Preto - Largo
		$this->SetFillColor(0,0,0);
		$this->Cell($largo,$altura,'',0,0,'C',1);

		// Branco - Fino
		$this->SetFillColor(255,255,255);
		$this->Cell($fino,$altura,'',0,0,'C',1);

		// Preto - Fino
		$this->SetFillColor(0,0,0);
		$this->Cell($fino,$altura,'',0,0,'C',1);

		if($dadosboleto["cob_outro"] == 'S'){
			if($Background == 's'){
				$PatchImagens = $Path;
			}else{
				$PatchImagens = "../../../../";
			}
		}
	}
	
	
	}
	function DemonstrativoVerso($IdLoja, $IdContaReceber, $con){
		global $Path;
		$sql = "SELECT 
					ValorLocalCobrancaParametro ImprimirVerso 
				FROM
					LocalCobrancaParametro 
				WHERE
					IdLocalCobrancaLayout = 49 AND
					IdLocalCobrancaParametro = 'ImprimirVerso'";
		$res = mysql_query($sql,$con);
		$linParametroLayout = mysql_fetch_array($res);
		
		if($linParametroLayout[ImprimirVerso] == 'S'){		
			include($Path."modulos/administrativo/local_cobranca/49/funcao_demonstrativo_verso_pdf.php");
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
		if(file_exists("imagens/cep_cod_barra.png")){
			unlink("imagens/cep_cod_barra.png");
			ImagePNG($img,"imagens/cep_cod_barra.png");
		}else{
			ImagePNG($img,"imagens/cep_cod_barra.png");
		}
	}
	
	
	
}

?>