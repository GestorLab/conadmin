<?
	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	@include ("../../../rotinas/verifica.php");
	include ("../../../classes/fpdf/class.fpdf.php");
	//include ("../../administrativo/nota_fiscal/1/class.nf.php");
	set_time_limit(0);
	$Path = "../../../";
	
	global $Background;
	global $Modelo;

	$IdLoja					= $_SESSION['IdLoja'];
	$IdLayoutNotaFiscal 	= $_GET['IdLayoutNotaFiscal'];
	$Periodo				= $_GET['Periodo'];
	$FormatoExportacao		= $_GET['FormatoExportacao'];
	$Local					= $_GET['Local'];
	
	$where = " ";
	$CabecalhoY				= 1;
	$DemonstrativoY1		= 20;
	$DemonstrativoY2		= 23.5;
	$DemonstrativoY3		= 40;
	$PosicaoImagemY			= 3.5;
	$DemonstrativoY4		= 95.5;
	$contadorfinal			= 0;
	
	if($IdLayoutNotaFiscal != ""){
		$where .= "AND NotaFiscal.IdNotaFiscalLayout = $IdLayoutNotaFiscal";
	}
	
	if($FormatoExportacao == 1){
		$Periodo = dataConv($Periodo,'m/Y','Y-m');
		
		$pdf = new FPDF();
		$pdf->SetMargins(12, 1, 12);
		$pdf->AddPage();
		$pdf->SetFont('Arial','',7);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(0.3);
		
		$sql = "SELECT
					NotaFiscal.IdContaReceber,
					NotaFiscal.IdNotaFiscal
				FROM
					NotaFiscal
				WHERE
					NotaFiscal.IdLoja =  $IdLoja AND
					NotaFiscal.PeriodoApuracao = '$Periodo'
					$where
					ORDER BY IdNotaFiscal ASC";
		$res = mysql_query($sql,$con);
		$quant = mysql_num_rows($res);
		
		if($quant > 0){
			$i 	  = 0;
			$cont = 0;
			while($linContaReceber = mysql_fetch_array($res)){
				$IdContaReceber  = $linContaReceber[IdContaReceber];
				$IdNotaFiscal	 = $linContaReceber[IdNotaFiscal]; 
				$dadosboleto["cpf_cnpj_cedente"] 	= $linDadosEmpresa[CPF_CNPJ];
				
				// Default
				$pdf->margin_left = 10;

				// Conteúdo - Cabeçalho
				$divisor = 5.57256;

				if($Background == 's'){
					$logo = $Path.$ExtLogoPDF_BCK;
				}else{
					$logo = "../../../img/personalizacao/logo_cab.gif";
				}
					
				$ExtLogo = endArray(explode(".",$logo));
				
				$dadosImg			= getimagesize($logo);
				$dadosImgLargura	= ($dadosImg[0]/$divisor);
				$dadosImgAltura		= ($dadosImg[1]/$divisor);

				$sql = "select
							IdNotaFiscalLayout
						from
							NotaFiscal
						where
							Idloja = $IdLoja and
							IdContaReceber = $IdContaReceber and
							IdNotaFiscal   = $IdNotaFiscal
							order by IdNotaFiscal";
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
					$resPessoa = mysql_query($sql,$con);
					if($dadosNF = mysql_fetch_array($resPessoa)){
						
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
						$pdf->SetY($CabecalhoY);
						$pdf->MultiCell(190,3.5,"$dadosNF[cedente]\n$dadosNF[endereco] - $dadosNF[cidade]\nCNPJ: $dadosNF[cpf_cnpj_cedente]".$dadosNF[ie].$dadosNF[fone],0,0,'R',1);
					}
				}else{
					$pdf->SetY($CabecalhoY);
					$pdf->MultiCell(190,3.5,"$dadosboleto[cedente]\n$dadosboleto[endereco] - $dadosboleto[cidade]\n$CPF_CNPJ: $dadosboleto[cpf_cnpj_cedente]".$dadosboleto[ie].$dadosboleto[fone],0,0,'R',1);
				}
				
				$IdNotaFiscalLayout = $linNotaFiscal[IdNotaFiscalLayout];
				
				if($IdNotaFiscalLayout == '' or $IdNotaFiscalLayout == null){
					$DescricaoTipoNotaFiscal = "NOTA FISCAL/FATURA DE SERVIÇO DE COMUNICAÇÃO";
					$IdNotaFiscalLayout	= 1;
				}else{
					if($IdNotaFiscalLayout == '1' or $IdNotaFiscalLayout == 1){
						$DescricaoTipoNotaFiscal = "NOTA FISCAL/FATURA DE SERVIÇO DE COMUNICAÇÃO";
					}else{
						$DescricaoTipoNotaFiscal = "NOTA FISCAL/FATURA DE SERVIÇO DE TELECOMUNICAÇÃO";
					}
				}
				$pdf->Cell(190,1,'','T');
				$pdf->Image($logo,13,$PosicaoImagemY,$dadosImgLargura,$dadosImgAltura,$ExtLogo);
				
				$v1 = 3.5;
				$v2 = 3.0;
					
				$height = 0;
				$cont	= 0;
				
			//	if($IdNotaFiscalLayout == '' or $IdNotaFiscalLayout == null){
			//		$IdNotaFiscalLayout	= 1;
			//	}
			//	$DescricaoTipoNotaFiscal = "NOTA FISCAL/FATURA DE SERVIÇO DE COMUNICAÇÃO";

				// Dados Tomador do Servico
				$sqlTomador = "select
									Pessoa.TipoPessoa,
									Pessoa.RazaoSocial, 
									Pessoa.Nome,
									Pessoa.NomeRepresentante,
									Pessoa.CPF_CNPJ, 
									Pessoa.RG_IE,
									PessoaEndereco.Endereco, 
									PessoaEndereco.Numero, 
									PessoaEndereco.Complemento, 
									PessoaEndereco.Bairro,
									PessoaEndereco.CEP,
									Cidade.NomeCidade, 
									Estado.SiglaEstado
								from
									ContaReceber,
									Pessoa,
									PessoaEndereco,
									Estado,
									Cidade
								where
									ContaReceber.IdLoja = $IdLoja and
									ContaReceber.IdContaReceber = $IdContaReceber and
									ContaReceber.IdPessoa = Pessoa.IdPessoa and
									Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
									Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
									PessoaEndereco.IdPais = Estado.IdPais and
									PessoaEndereco.IdPais = Cidade.IdPais and
									PessoaEndereco.IdEstado = Estado.IdEstado and
									PessoaEndereco.IdEstado = Cidade.IdEstado and
									PessoaEndereco.IdCidade = Cidade.IdCidade";
				$resTomador = mysql_query($sqlTomador,$con);
				$Tomador = mysql_fetch_array($resTomador);

				$Tomador[EnderecoFull] = $Tomador[Endereco].", ".$Tomador[Numero]." - ".$Tomador[Complemento]."\n".$Tomador[Bairro]." - ".$Tomador[NomeCidade]."-".$Tomador[SiglaEstado]." - CEP: ".$Tomador[CEP];

				if($Tomador[TipoPessoa] == 1){
					$Tomador[Nome]			= $Tomador[RazaoSocial];
					$Tomador[CPF_CNPJFull]	= "CNPJ: ";
					$Tomador[RG_IEFull]		= " - IE: ".$Tomador[RG_IE];
				}else{
					$Tomador[CPF_CNPJFull]	= "CPF: ";
				}
				$Tomador[CPF_CNPJFull] .= $Tomador[CPF_CNPJ];

				// Dados da Nota Fiscal
				$sqlNF = "select
							NotaFiscal.IdNotaFiscal,
							NotaFiscal.Modelo,
							NotaFiscal.Serie,
							NotaFiscal.DataEmissao,
							NotaFiscal.ValorBaseCalculoICMS,
							NotaFiscal.ValorICMS,
							NotaFiscal.ValorOutros,
							NotaFiscal.ValorTotal,
							NotaFiscal.ObsVisivel,
							NotaFiscal.ValorDesconto,
							NotaFiscal.CodigoAutenticacaoDocumento,
							NotaFiscal.PeriodoApuracao
						from
							NotaFiscal
						where
							NotaFiscal.IdLoja = $IdLoja and
							NotaFiscal.IdContaReceber = $IdContaReceber and
							NotaFiscal.IdNotaFiscal	  = $IdNotaFiscal and
							NotaFiscal.IdNotaFiscalLayout = $IdNotaFiscalLayout
							order by IdNotaFiscal";
				$resNF = mysql_query($sqlNF,$con);
				$linNF = mysql_fetch_array($resNF);

				$linNF[DataEmissao]			= dataConv($linNF[DataEmissao],'Y-m-d','d/m/Y');

				// Formata o Número da NF
				$linNF[IdNotaFiscalTemp]	= str_pad($linNF[IdNotaFiscal], 9, "0", STR_PAD_LEFT);
				$linNF[IdNotaFiscalTemp]	= substr($linNF[IdNotaFiscalTemp],0,3).".".substr($linNF[IdNotaFiscalTemp],3,3).".".substr($linNF[IdNotaFiscalTemp],6,3);
				
				// Formata Valores
				$linNF[ValorTotal]				= number_format($linNF[ValorTotal], 2, ',', '');
				$linNF[ValorOutros]				= number_format($linNF[ValorOutros], 2, ',', '');
				$linNF[ValorBaseCalculoICMS]	= number_format($linNF[ValorBaseCalculoICMS], 2, ',', '');
				
				if($linNF[ValorICMS] != NULL){
					$linNF[ValorICMS] = number_format($linNF[ValorICMS], 2, ',', '');
				}else{
					$linNF[ValorICMS] = '-';
				}

				// Formata o Periodo de Apuracao
				$linNF[PeriodoApuracao]			= explode('-',$linNF[PeriodoApuracao]);
				$linNF[PeriodoApuracao]			= strtoupper(substr(mes($linNF[PeriodoApuracao][1]),0,3))."/".$linNF[PeriodoApuracao][0];

				// Formata o Codigo de Autenticacao do Documento
				$linNF[CodigoAutenticacaoDocumento]	= strtoupper(substr($linNF[CodigoAutenticacaoDocumento],0,4).".".substr($linNF[CodigoAutenticacaoDocumento],4,4).".".substr($linNF[CodigoAutenticacaoDocumento],8,4).".".substr($linNF[CodigoAutenticacaoDocumento],12,4).".".substr($linNF[CodigoAutenticacaoDocumento],16,4).".".substr($linNF[CodigoAutenticacaoDocumento],20,4).".".substr($linNF[CodigoAutenticacaoDocumento],24,4).".".substr($linNF[CodigoAutenticacaoDocumento],28,4));


				// Formata a Série
				switch($linNF[Serie]){
					case 'U':
						$linNF[Serie] = 'Única';
						break;
				}

				// Default
				$pdf->margin_left = 10;
				$pdf->height_cell = 3;
				$pdf->SetFillColor(255,255,255);
				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(0,0,0);
				$pdf->SetLineWidth(0.3);
				$pdf->ln();

				// Tomador do Serviço
				$pdf->SetFont('Arial','B',7.5);
				$pdf->MultiCell(101,4,"Tomador do Serviço",0,'L');
				$pdf->SetFont('Arial','',8);
				$pdf->MultiCell(101,3.5,$Tomador[Nome],0,'L');
				$pdf->MultiCell(101,3.5,$Tomador[EnderecoFull],0,'L');
				$pdf->MultiCell(101,3.5,$Tomador[CPF_CNPJFull].$Tomador[RG_IEFull],0,'L');

				// Quadro Nota Fiscal
				$pdf->SetXY(101,$DemonstrativoY1);
				$pdf->SetFont('Arial','B',9);
				$pdf->MultiCell(0,7,$DescricaoTipoNotaFiscal,0,'C');
				$pdf->SetX(111);
				$pdf->SetFont('Arial','',8);
				$pdf->MultiCell(30,3.5,"Modelo $linNF[Modelo]\nSérie $linNF[Serie]\nEmissão: $linNF[DataEmissao]",0,'C');
				$pdf->SetXY(130,$DemonstrativoY2);
				$pdf->SetFont('Arial','B',16);
				$pdf->MultiCell(0,9.5,"Nº $linNF[IdNotaFiscalTemp]",0,'C');

				// Linha
				$pdf->SetY($DemonstrativoY3);
				$pdf->Cell(190,0,'','T');

				// Lançamentos
				$pdf->ln();
				$pdf->SetFont('Arial','B',7.5);
				$pdf->MultiCell(90,4,"Lançamentos",0,'L');
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(6.5,3.5,'Item',0,0,'C');
				$pdf->Cell(20,3.5,'Serviço',0,0,'C');
				$pdf->Cell(70,3.5,'Descrição',0,0,'C');
				$pdf->Cell(30,3.5,'Referência',0,0,'C');
				$pdf->Cell(25,3.5,'Valor (R$)',0,0,'R');
				$pdf->Cell(23.5,3.5,'Desconto (R$)',0,0,'R');
				$pdf->Cell(15,3.5,'ICMS',0,0,'R');
				$pdf->ln();
				$pdf->Cell(190,0,'','T');
				$cont++;

				$msgPos = 0;

				$sqlItensNF = "select
								NotaFiscalItem.IdNotaFiscalItem,
								NotaFiscalItem.IdLancamentoFinanceiro,
								Demonstrativo.Tipo,
								Demonstrativo.Codigo,
								Demonstrativo.Descricao,
								if(Demonstrativo.ExibirReferencia != 2, Demonstrativo.Referencia, '-') Referencia,
								NotaFiscalItem.ValorTotal,
								NotaFiscalItem.ValorDesconto,
								NotaFiscalItem.AliquotaICMS
							from
								NotaFiscalItem
									left join Demonstrativo on (NotaFiscalItem.IdLoja = Demonstrativo.IdLoja and NotaFiscalItem.IdLancamentoFinanceiro = Demonstrativo.IdLancamentoFinanceiro)
							where
								NotaFiscalItem.IdNotaFiscalLayout = $IdNotaFiscalLayout and
								NotaFiscalItem.IdLoja = $IdLoja and
								NotaFiscalItem.IdNotaFiscal = '$linNF[IdNotaFiscal]' and
								NotaFiscalItem.IdContaReceber = $IdContaReceber
							order by
								NotaFiscalItem.IdNotaFiscalItem";
				$resItensNF = mysql_query($sqlItensNF,$con);
				$ValorFinal	= 0;
				$ValorFinalDesconto = 0;
				while($linItensNF = mysql_fetch_array($resItensNF)){

					// Trata as Variáveis
					$linItensNF[IdNotaFiscalItem]	= str_pad($linItensNF[IdNotaFiscalItem], 3, "0", STR_PAD_LEFT);
					$ValorFinal					   += $linItensNF[ValorTotal];
					$ValorFinalDesconto			   += $linItensNF[ValorDesconto];
					$linItensNF[ValorTotal]			= number_format($linItensNF[ValorTotal], 2, ',', '');
					$linItensNF[ValorDesconto]		= number_format($linItensNF[ValorDesconto], 2, ',', '');

					if($linItensNF[AliquotaICMS] != NULL){
						$linItensNF[AliquotaICMS]		= number_format($linItensNF[AliquotaICMS], 2, ',', '')."%";
					}

					$linNF[AloquotaICMS]			= $linItensNF[AliquotaICMS];

					if($linItensNF[IdLancamentoFinanceiro] == ''){
						$linItensNF[Descricao]	= getParametroSistema(5,3);
						$linItensNF[Referencia]	= 'Única';
					}

					$linItensNF[Descricao] = substr($linItensNF[Descricao], 0, 55);

					// Itens da Nota
					$pdf->ln();
					$pdf->SetFont('Arial','',7.5);
					$pdf->Cell(6.5,3,$linItensNF[IdNotaFiscalItem],0,0,'C');
					$pdf->Cell(20,3,$linItensNF[Tipo]." ".$linItensNF[Codigo],0,0,'C');
					$pdf->Cell(70,3,$linItensNF[Descricao],0,0,'C');
					$pdf->Cell(30,3,$linItensNF[Referencia],0,0,'C');
					$pdf->Cell(25,3,$linItensNF[ValorTotal],0,0,'R');
					$pdf->Cell(23.5,3,$linItensNF[ValorDesconto],0,0,'R');
					$pdf->Cell(15,3,$linItensNF[AliquotaICMS],0,0,'R');
					$cont++;

					if($linItensNF[Codigo] != ''){
						$sqlParametrosNF = "select
												ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro,
												ServicoNotaFiscalLayoutParametro.Valor
											from
												Contrato,
												ServicoNotaFiscalLayoutParametro
											where
												Contrato.IdLoja = $IdLoja and
												Contrato.IdLoja = ServicoNotaFiscalLayoutParametro.IdLoja and
												Contrato.IdContrato = $linItensNF[Codigo] and
												Contrato.IdServico = ServicoNotaFiscalLayoutParametro.IdServico and
												ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayout = $IdNotaFiscalLayout";
						$resParametrosNF = mysql_query($sqlParametrosNF,$con);
						while($linParametrosNF = mysql_fetch_array($resParametrosNF)){
							$ParametrosNF[$linParametrosNF[IdNotaFiscalLayoutParametro]] = $linParametrosNF[Valor];
						}

						$MensagemNF[$msgPos] = $ParametrosNF[3];
						$msgPos++;
					}
				}
				
				$MensagemNF = @array_unique($MensagemNF);
				
				/*for($j=0; $j<count($MensagemNF); $j++){
					$msg .= " ".$MensagemNF[$j];
				}*/

				$ValorFinal			= number_format($ValorFinal, 2, ',', '');
				$ValorFinalDesconto = number_format($ValorFinalDesconto, 2, ',', '');

				$msg = trim($msg);
				
				if($msg != ''){
					$msg .= "\n$linNF[ObsVisivel]";
				} else{
					$msg = $linNF[ObsVisivel];
				}
				
				$pdf->ln();
				$pdf->Cell(190,0,'','T');
				$pdf->ln();
				$pdf->SetFont('Arial','B',7.5);
				$pdf->Cell(126.5,3.5,'Total',0,0,'R');
				$pdf->Cell(25,3.5,$ValorFinal,0,0,'R');
				$pdf->Cell(23.5,3.5,$ValorFinalDesconto,0,0,'R');
				$pdf->Cell(15,3.5,'',0,0,'R');
				$pdf->ln();
				$pdf->Cell(190,0,'','T');
				$cont++;
				$ValorFinal = 0;
				
				if($cont > 19){
					$cont = 26;
				}else{
					if($Reaviso == true){
						$pdf->SetY(138);
					}
				}

				$pdf->ln();
				$pdf->Cell(190,0,'','T');

				$pdf->ln();
				$pdf->SetFont('Arial','B',7.5);
				$pdf->Cell(22,3.5,'Base de Cálculo',0,0,'C');
				$pdf->Cell(14,3.5,'ICMS',0,0,'C');
				$pdf->Cell(22,3.5,'Outras',0,0,'C');
				$pdf->Cell(22,3.5,'Total',0,0,'C');
				$pdf->Cell(0.01,3.5,'',1,0,'C');
				$pdf->Cell(30,3.5,'Reservado ao fisco',0,0,'L');
				$pdf->Cell(68.1,3.5,'Período Fiscal:  ',0,0,'R');
				$pdf->SetFont('Arial','',7.5);
				$pdf->Cell(13,3.5,$linNF[PeriodoApuracao],0,0,'R');

				$pdf->ln();
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(22,8,$linNF[ValorBaseCalculoICMS],0,0,'C');
				$pdf->Cell(14,8,$linNF[ValorICMS],0,0,'C');
				$pdf->Cell(22,8,$linNF[ValorOutros],0,0,'C');
				$pdf->Cell(22,8,$linNF[ValorTotal],0,0,'C');
				$pdf->Cell(0.01,8,'',1,0,'C');
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(0,8,$linNF[CodigoAutenticacaoDocumento],0,0,'C');

				$pdf->ln();
				$pdf->Cell(190,0,'','T');
				
				$pdf->ln();
				$pdf->SetFont('Arial','',7);
				$pdf->MultiCell(0,3.5,$msg,0,'J');
				
				$i++;
				$cont++;
				$contadorfinal++;
				$CabecalhoY 		+= 140;
				$DemonstrativoY1	+= 140;
				$DemonstrativoY2	+= 140;
				$DemonstrativoY3	+= 140;
				$DemonstrativoY4	+= 130;
				$PosicaoImagemY		+= 140;		
				if($i == 2 && $cont-1 < $quant){
					if($contadorfinal == $quant){
						break;
						return false;
					} else{
						$pdf->AddPage();
						$i = 0;
						$CabecalhoY 		= 1;
						$DemonstrativoY1	= 20;
						$DemonstrativoY2    = 23.5;
						$DemonstrativoY3	= 40.0;
						$DemonstrativoY4	= 95.5;
						$PosicaoImagemY		= 3.5;
					}
				}
			}	
			$pdf->Output("Notas_Fiscais_".dataConv($Periodo,'Y-m','m-Y').".pdf","D");
		}else{
			header("Location: ../cadastro_nota_fiscal_exportacao.php?Erro=182&IdLayoutNotaFiscal=$IdLayoutNotaFiscal&Periodo=$Periodo&FormatoExportacao=$FormatoExportacao");
		}
	} else{
		header("Location: ../cadastro_nota_fiscal_exportacao.php?Erro=182&IdLayoutNotaFiscal=$IdLayoutNotaFiscal&Periodo=$Periodo&FormatoExportacao=$FormatoExportacao");
	}
 ?>