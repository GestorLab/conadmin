<?
class Boleta extends FPDF
{
	function Cabecalho($IdLoja, $con){
	
		/* Coleta as informações da Loja */
		$sql = "select
			    Pessoa.IdPessoa,
				Pessoa.TipoPessoa,
			    Pessoa.Nome,
			    Pessoa.RazaoSocial,
			    Pessoa.CPF_CNPJ,
			    Pessoa.Endereco,
			    Pessoa.Numero,
			    Pessoa.Complemento,
				Pessoa.CEP,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Fax,
			    Cidade.NomeCidade,
			    Estado.SiglaEstado
			from
			    Loja,
			    Pessoa,
			    Cidade,
			    Estado
			where
			    Loja.IdLoja=$IdLoja and
			    Loja.IdPessoa = Pessoa.IdPessoa and
			    Cidade.IdPais = Pessoa.IdPais and
			    Cidade.IdEstado = Pessoa.IdEstado and
			    Cidade.IdCidade = Pessoa.IdCidade and
			    Cidade.IdPais = Estado.IdPais and
			    Cidade.IdEstado = Estado.IdEstado";
		$res = mysql_query($sql,$con);
		$linDadosEmpresa = mysql_fetch_array($res);

		if($linDadosEmpresa[Telefone] == ''){
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone1];
		}else{
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone2];
		}

		if($linDadosEmpresa[TipoPessoa] == 1){
			$CPF_CNPJ = "CNPJ";
		}else{			
			$CPF_CNPJ = "CPF";
		}

		//=============Dados da Sua empresa===============
		$dadosboleto["cpf_cnpj_cedente"] 	= $linDadosEmpresa[CPF_CNPJ];
		$dadosboleto["endereco"] 			= $linDadosEmpresa[Endereco].", ".$linDadosEmpresa[Numero]." - ".$linDadosEmpresa[Complemento];
		$dadosboleto["cidade"] 				= $linDadosEmpresa[NomeCidade]."-".$linDadosEmpresa[SiglaEstado]." - Cep: ".$linDadosEmpresa[CEP];
		$dadosboleto["cedente"] 			= $linDadosEmpresa[Nome];
		$dadosboleto["fone"]				= "";
		
		if($linDadosEmpresa[RazaoSocial] != ''){
			$dadosboleto["cedente"] .=" (".$linDadosEmpresa[RazaoSocial].")";
		}
		if($linDadosEmpresa[Telefone] != ''){
			$dadosboleto["fone"] 				= " - Fone / Fax: ".$linDadosEmpresa[Telefone];
		}

		
		// Default
		$this->margin_left = 10;
	
		// Conteúdo - Cabeçalho
		$divisor = 3.57256;
		$logo = "../../../../img/personalizacao/logo_cab.jpg";
		$dadosImg			= getimagesize($logo);
		$dadosImgLargura	= ($dadosImg[0]/$divisor);
		$dadosImgAltura		= ($dadosImg[1]/$divisor);
	    $this->Image($logo,13,3.5,$dadosImgLargura,$dadosImgAltura,jpg);
		$this->SetFont('Arial','',8);
		$this->SetFillColor(255,255,255);
	    $this->SetTextColor(0);
	    $this->SetDrawColor(0,0,0);
	    $this->SetLineWidth(0.3);
	    $this->MultiCell(0,3.5,"$dadosboleto[cedente]\n$dadosboleto[endereco] - $dadosboleto[cidade]\n$CPF_CNPJ: $dadosboleto[cpf_cnpj_cedente]".$dadosboleto[fone],0,0,'R',1);
	    $this->Cell(190,1,'','T');
	}
	
	function Demonstrativo($IdLoja, $IdContaReceber, $con){

		global $Background;
		global $Path;

		$height = 0;
		$cont	= 0;

		// Default
		$this->margin_left = 10;
		$this->height_cell = 3;
	    $this->SetLineWidth(0.3);
	
	    // Conteúdo - Demonstrativo
		$this->ln();
		$this->SetFont('Arial','B',9);
	    $this->Cell(15,$this->height_cell,'Demonstrativo',0,0,'L',1);
		$this->ln(4);
		$cont++;
	    
	    // Conteúdo - Demonstrativo - Título
		$this->SetFont('Arial','B',8);
	    $this->Cell(10,$this->height_cell,'Tipo',0,0,'L',1);
	    $this->Cell(15,$this->height_cell,'Cod.',0,0,'L',1);
	    $this->Cell(110,$this->height_cell,'Descrição',0,0,'L',1);
	    $this->Cell(40,$this->height_cell,'Referência',0,0,'C',1);
	    $this->Cell(15,$this->height_cell,'Valor ('.getParametroSistema(5,1).')',0,0,'C',1);
	    $this->Ln();
	    $this->Cell(190,1,'','T');
		$cont++;
	    
		// Conteúdo - Demonstrativo - Registros
   		$this->SetFont('Arial','',8);

		$i					= 0;
	    $valorTotal			= 0;
		$DadosLancamento	= null;

		$sql = "select
					Tipo,
					Codigo,
					Descricao,
					Referencia,
					Valor,
					ValorDespesas
				from
					Demonstrativo
				where
					IdLoja = $IdLoja and
					IdContaReceber = $IdContaReceber
				order by
					Tipo,
					Codigo,
					IdLancamentoFinanceiro";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
		
			$valorTotal += $lin[Valor];

			$DadosLancamento[$i][Tipo]			= $lin[Tipo];
			$DadosLancamento[$i][Cod]			= $lin[Codigo];
			$DadosLancamento[$i][Descricao]		= $lin[Descricao];
			$DadosLancamento[$i][Referencia]	= $lin[Referencia];
			$DadosLancamento[$i][Valor]			= $lin[Valor];
			$i++;
		}

		for($i = 0; $i <=count($DadosLancamento); $i++){
			
			if($DadosLancamento[$i][Tipo] != ''){
				$Tipo		= $DadosLancamento[$i][Tipo];
				$Cod		= $DadosLancamento[$i][Cod];
				$Descricao	= $DadosLancamento[$i][Descricao];
				$Referencia	= $DadosLancamento[$i][Referencia];
				$Valor		= number_format($DadosLancamento[$i][Valor],2,',','');

				$Descricao = explode("\n", $Descricao);

				$this->Ln();
				$this->Cell(10,$this->height_cell,$Tipo,0,0,'L',1);
				$this->Cell(15,$this->height_cell,$Cod,0,0,'L',1);
				$this->Cell(110,$this->height_cell,$Descricao[0],0,0,'L',1);
				$this->Cell(40,$this->height_cell,$Referencia,0,0,'C',1);
				$this->Cell(15,$this->height_cell,$Valor,0,0,'R',1);
				$this->Ln();	
				$height += $this->height_cell;
				$cont++;

				$count = count($Descricao);


				if($count > 0){
					for($ii = 1; $ii < $count; $ii++){
						if(trim($Descricao[$ii]) != ''){
							$this->Ln(1);
							$this->Cell(10,$this->height_cell,'',0,0,'L',1);
							$this->Cell(15,$this->height_cell,'',0,0,'L',1);
							$this->Cell(110,$this->height_cell,$Descricao[$ii],0,0,'L',1);
							$this->Cell(40,$this->height_cell,'',0,0,'C',1);
							$this->Cell(15,$this->height_cell,'',0,0,'R',1);
							$this->Ln();	
							$height += $this->height_cell;
							$cont++;
						}
					}
				}
				
				$this->Cell(190,1,'','T');
				$height += 1;
			}
		}

		$sql = "select ValorDespesas from ContaReceber where IdLoja=$IdLoja and IdContaReceber=$IdContaReceber";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$valorTotal += $lin[ValorDespesas];


		if($lin[ValorDespesas] > 0){
			
			$lin[ValorDespesas] = number_format($lin[ValorDespesas],2,',','');

			$this->Ln();
			$this->Cell(10,$this->height_cell,"",0,0,'L',1);
			$this->Cell(15,$this->height_cell,"",0,0,'L',1);
			$this->Cell(110,$this->height_cell,"Despesas boleto",0,0,'L',1);
			$this->Cell(40,$this->height_cell,"",0,0,'L',1);
		    $this->Cell(15,$this->height_cell,$lin[ValorDespesas],0,0,'R',1);
		    $this->Ln();
    		$this->Cell(190,1,'','T');
			$cont++;
		}

		$valorTotal = number_format($valorTotal,2,',','');
		
		// Conteúdo - Demonstrativo - Registros - Total
		$this->Ln();
		$this->Cell(105);
   		$this->SetFont('Arial','B',8);
    	$this->Cell(60,$this->height_cell,'Total',0,0,'C',1);
	    $this->Cell(25,$this->height_cell,$valorTotal,0,0,'R',1);
	    $this->Ln();
    	$this->Cell(190,1,'','T');
		$cont++;

		// Reaviso

		$this->Ln();
		if($cont < 25){
			$this->SetY(98.5);
		}
	
		$i = 0;
		$valorTotal = 0;
		$sql = "select
					ContaReceber.IdContaReceber,
					ContaReceber.NumeroDocumento,
					(ContaReceber.ValorLancamento + ContaReceber.ValorDespesas) Valor,
					ContaReceber.DataVencimento,
					ContaReceber.DataLancamento
				from
					(select Contrato.IdLoja, Contrato.IdPessoa from Contrato, LancamentoFinanceiro, LancamentoFinanceiroContaReceber where Contrato.IdLoja = $IdLoja and Contrato.IdLoja = LancamentoFinanceiro.IdLoja and Contrato.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and Contrato.IdContrato = LancamentoFinanceiro.IdContrato and LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber group by Contrato.IdPessoa) Pessoa,
					Contrato,
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber,
					ContaReceber
				where
					Contrato.IdLoja = Pessoa.IdLoja and
					Contrato.IdLoja = LancamentoFinanceiro.IdLoja and
					Contrato.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					Contrato.IdLoja = ContaReceber.IdLoja and
					LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					Contrato.IdPessoa = Pessoa.IdPessoa and
					LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and
					ContaReceber.IdContaReceber not in (select LancamentoFinanceiroContaReceber.IdContaReceber from (select Contrato.IdLoja, Contrato.IdPessoa from Contrato, LancamentoFinanceiro, LancamentoFinanceiroContaReceber where Contrato.IdLoja = $IdLoja and Contrato.IdLoja = LancamentoFinanceiro.IdLoja and Contrato.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and Contrato.IdContrato = LancamentoFinanceiro.IdContrato and LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber group by Contrato.IdPessoa) Pessoa, Contrato, LancamentoFinanceiro, LancamentoFinanceiroContaReceber, ContaReceber, ContaReceberRecebimento where Contrato.IdLoja = Pessoa.IdLoja and Contrato.IdLoja = LancamentoFinanceiro.IdLoja and Contrato.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and Contrato.IdLoja = ContaReceber.IdLoja and Contrato.IdLoja = ContaReceberRecebimento.IdLoja and LancamentoFinanceiro.IdContrato = Contrato.IdContrato and LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and Contrato.IdPessoa = Pessoa.IdPessoa and LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and ContaReceber.IdContaReceber = ContaReceberRecebimento.IdContaReceber group by LancamentoFinanceiroContaReceber.IdContaReceber) and
					ContaReceber.DataVencimento < curdate() and
					ContaReceber.IdContaReceber not in ($IdContaReceber) and
					ContaReceber.IdStatus = 1
				group by
					LancamentoFinanceiroContaReceber.IdContaReceber";
		$res = @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){

			$i++;

			if($i > 6){
				break;
			}

			if($i == 1){
				
			    // Conteúdo - Reaviso de Vencimento
				$this->SetFont('Arial','B',11);
			    $this->Write(5,'Reaviso de Vencimento*');
				$this->Ln();
				$height += 6;
				$cont++;
	    
			    // Conteúdo - Reaviso de Vencimento - Título
				$this->SetFont('Arial','B',8);
			    $this->Cell(25,$this->height_cell,'Data Lançamento',0,0,'L',1);
			    $this->Cell(25,$this->height_cell,'Vencimento',0,0,'C',1);
			    $this->Cell(25,$this->height_cell,'Valor ('.getParametroSistema(5,1).')',0,0,'C',1);
			    $this->Ln();
				$height += $this->height_cell;
				$cont++;

			    $this->Cell(75,1,'','T');
				$height += 1;

			}

			$valorTotal += $lin[Valor];
			$lin[ValorLancamento] = number_format($lin[Valor],2,',','');

			$lin[DataVencimento] = dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
			$lin[DataLancamento] = dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");

		    $this->Ln();
			$this->SetFont('Arial','',8);
		    $this->Cell(25,$this->height_cell,$lin[DataLancamento],0,0,'L',1);
		    $this->Cell(25,$this->height_cell,$lin[DataVencimento],0,0,'C',1);
		    $this->Cell(25,$this->height_cell,$lin[Valor],0,0,'R',1);
		    $this->Ln();			
			$height += $this->height_cell;

		    $this->Cell(75,1,'','T');
			$height += 1;
			$cont++;
		}
		if($i > 0){			
			$valorTotal = number_format($valorTotal,2,',','');

			$this->Ln();
			$this->Cell(25);
   			$this->SetFont('Arial','B',8);
	    	$this->Cell(25,$this->height_cell,'Total',0,0,'C',1);
		    $this->Cell(25,$this->height_cell,$valorTotal,0,0,'R',1);
		    $this->Ln();
			$height += $this->height_cell;
			$cont++;

	    	$this->Cell(75,1,'','T');			
			$height += 1;
			$this->Ln();
			$cont++;

   			$this->SetFont('Arial','',7);
		    $this->Write(3,'*Caso o pagamento já tenha sido efetuado, desconsidere este reaviso.');
			$height += 3;
			$cont++;
		}

		if($cont > 25){
			$this->AddPage();
			$this->Cabecalho($IdLoja, $con);
		}

		//===Dados do seu Cliente ===============
		$sql = "select
					distinct 
					Demonstrativo.IdLoja,				
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante, 
					Pessoa.RazaoSocial,
					Pessoa.TipoPessoa,
					Pessoa.CPF_CNPJ,
					Pessoa.RG_IE,
					Pessoa.Endereco,
					Pessoa.Numero,
					Pessoa.Complemento,
					Pessoa.Bairro, 
					Pessoa.CEP,
					Cidade.NomeCidade,
					Estado.SiglaEstado,
					Pessoa.Cob_FormaOutro,
					Pessoa.Cob_NomeResponsavel, 
					Pessoa.Cob_Endereco, 
					Pessoa.Cob_Complemento, 
					Pessoa.Cob_Numero, 
					Pessoa.Cob_Bairro, 
					Pessoa.Cob_IdPais, 
					Pessoa.Cob_IdEstado, 
					Pessoa.Cob_IdCidade, 
					Pessoa.Cob_CEP
				from 
					Demonstrativo,
					Pessoa,
					Estado,
					Cidade
				where
					Demonstrativo.IdLoja = $IdLoja and
					Demonstrativo.IdContaReceber = $IdContaReceber and
					Pessoa.IdPessoa = Demonstrativo.IdPessoa and
					Pessoa.IdPais = Estado.IdPais and
					Pessoa.IdPais = Cidade.IdPais and
					Pessoa.IdEstado = Estado.IdEstado and
					Pessoa.IdEstado = Cidade.IdEstado and
					Pessoa.IdCidade = Cidade.IdCidade";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		if($lin[Cob_Endereco]!='' && $lin[Cob_IdPais] != '' && $lin[Cob_IdEstado] != '' && $lin[Cob_IdCidade]){
			$Dados[NomeRepresentante]	=	$lin[Cob_NomeResponsavel];
			$Dados[Nome]				=	$lin[Nome];
			$Dados[Endereco]			=	$lin[Cob_Endereco];
			$Dados[Complemento]			=	$lin[Cob_Complemento];
			$Dados[Numero]				=	$lin[Cob_Numero];
			$Dados[Bairro]				=	$lin[Cob_Bairro];
			$Dados[CEP]					=	$lin[Cob_CEP];

			$sql = "select
						Cidade.NomeCidade, 
						Estado.SiglaEstado
					from
						Cidade,
						Estado
					where
						Cidade.IdPais=$lin[Cob_IdPais] and
						Cidade.IdPais = Estado.IdPais and
						Cidade.IdEstado=$lin[Cob_IdEstado] and
						Cidade.IdEstado = Estado.IdEstado and
						Cidade.IdCidade=$lin[Cob_IdCidade]";
			$res2	=	mysql_query($sql,$con);
			$lin2	=	mysql_fetch_array($res2);

			$Dados[NomeCidade]			=	$lin2[NomeCidade];
			$Dados[SiglaEstado]			=	$lin2[SiglaEstado];
		}else{		
			$Dados[NomeRepresentante]	=	$lin[NomeRepresentante];
			$Dados[Nome]				=	$lin[Nome];
			$Dados[Endereco]			=	$lin[Endereco];
			$Dados[Complemento]			=	$lin[Complemento];
			$Dados[Numero]				=	$lin[Numero];
			$Dados[Bairro]				=	$lin[Bairro];
			$Dados[NomeCidade]			=	$lin[NomeCidade];
			$Dados[SiglaEstado]			=	$lin[SiglaEstado];
			$Dados[CEP]					=	$lin[CEP];
		}

		# Linha 1 
		# Razão Social ou Nome Cliente
		$Dados[0]	=	$Dados[Nome];
		
		# Linha 2
		# Representante
		if($Dados[NomeRepresentante] != $Dados[Nome]){
			$Dados[1]	=	$Dados[NomeRepresentante];
		}
		
		# Linha 3
		# Endereço
		$Dados[2]	=	$Dados[Endereco];
		if($Dados[Numero] != ''){
			$Dados[2] .= ", ".$Dados[Numero];
		}

		# Linha 4
		# Complemento e Bairro
		$Dados[3]	=	$Dados[Complemento];
		if($Dados[3] != ''){
			$Dados[3] .= " - ";
		}
		$Dados[3] .= $Dados[Bairro];
		
		# Linha 5
		# Cidade e Estado
		$Dados[4]	=	$Dados[NomeCidade]." - ".$Dados[SiglaEstado];
		
		# Linha 6
		# CEP
		if($Dados[CEP] != ''){
			$Dados[4]	.=	" - CEP: ".$Dados[CEP];
		}

		for($i=0; $i<4; $i++){
			
			$Dados[$i] = trim($Dados[$i]);

			if($Dados[$i] == '' || $Dados[$i] == null){
				$Dados[$i]	 = $Dados[$i+1];
				$Dados[$i+1] = '';
			}
		}

		$this->SetY(95.5);
		$this->SetX(90);
		

		$this->SetFillColor(0,0,0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,4.5,'','L',0,'',0);
		$this->Cell(105,4.5,'','',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,4.5,'','L',0,'',0);
		$this->Cell(105,4.5,'','',0,'L',0);

		$this->Ln();
		$this->SetX(90);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,4.5,'','L',0,'',0);
		$this->Cell(105,4.5,'Destinatário','',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFont('Arial','',8.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[0],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[1],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[2],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[3],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[4],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,10.5,'','L',0,'',0);
		$this->Cell(105,22,'','',0,'L',0);
	}
	
	function DemonstrativoCarne($IdLoja, $IdCarne, $con){

		$height = 0;

		// Default
		$this->margin_left = 10;
		$this->height_cell = 3;
	    $this->SetLineWidth(0.3);
	
	    // Conteúdo - Demonstrativo
		$this->ln(2);

		$i=0;
	    $valorTotal = 0;
		$sql = "select
					distinct
					ContaReceber.IdContaReceber,
					(ContaReceber.ValorLancamento -	ContaReceber.ValorDesconto + ContaReceber.ValorDespesas) Valor,
					ContaReceber.DataVencimento,
					ContaReceber.NumeroDocumento,
					Contrato.IdPessoa
				from
					ContaReceber,
					LancamentoFinanceiroContaReceber,
					LancamentoFinanceiro,
					Contrato
				where
					ContaReceber.IdLoja = $IdLoja and
					ContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
					ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					ContaReceber.IdLoja = Contrato.IdLoja and
					ContaReceber.IdCarne = $IdCarne and
					ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
					LancamentoFinanceiro.IdContrato = Contrato.IdContrato
				order by
					DataVencimento, IdContaReceber
				limit 0,36";
		$res = mysql_query($sql,$con);
		$qtdRows = mysql_num_rows($res);
		while($lin = mysql_fetch_array($res)){

			$ContaReceber[$i][NumeroDocumento]	= $lin[NumeroDocumento];
			$ContaReceber[$i][Valor]			= $lin[Valor];
			$ContaReceber[$i][DataVencimento]	= $lin[DataVencimento];
			$IdPessoa = $lin[IdPessoa];
			$i++;
		}

		$limit		= 18;

		if(($qtdRows/2) <= $limit){
			$limit = (int)($qtdRows/2);
			if($qtdRows%2 != 0){
				$limit++;
			}
		}

		$x			= 110;
		$y			= 17.5;
		$heightTemp	= 0;

		for($i = 0; $i <=$qtdRows; $i++){
			if($i == 0 || $i == $limit){
				// Conteúdo - Demonstrativo - Título (1)
				
				if($i >= $limit){
					$this->SetY($y+$heightTemp);
					$this->SetX($x);
					$heightTemp += $this->height_cell;
				}

				$this->SetFont('Arial','B',8);
				$this->Cell(15,$this->height_cell,'Boleto',0,0,'C',1);
				$this->Cell(25,$this->height_cell,'No. Documento',0,0,'C',1);
				$this->Cell(20,$this->height_cell,'Valor ('.getParametroSistema(5,1).')',0,0,'C',1);
				$this->Cell(30,$this->height_cell,'Vencimento',0,0,'C',1);
				$this->Ln();
				
				if($i >= $limit){
					$this->SetY($y+$heightTemp);
					$this->SetX($x);
				}
				
				$this->Cell(90,1,'','T');
				
				// Conteúdo - Demonstrativo - Registros
				$this->SetFont('Arial','',8);
			}
			
			if($ContaReceber[$i][NumeroDocumento] != ''){
				$NumeroDocumento = $ContaReceber[$i][NumeroDocumento];
				$DataVencimento	= dataConv($ContaReceber[$i][DataVencimento],'Y-m-d', 'd/m/Y');
				$Valor			= number_format($ContaReceber[$i][Valor],2,',','');

				$this->Ln();
				
				if($i >= $limit){
					$this->SetY($y+$heightTemp+1);
					$this->SetX($x);
				}

				$this->Cell(15,$this->height_cell,($i+1)."/".$qtdRows,0,0,'C',1);
				$this->Cell(25,$this->height_cell,$NumeroDocumento,0,0,'C',1);
				$this->Cell(20,$this->height_cell,$Valor,0,0,'R',1);
				$this->Cell(30,$this->height_cell,$DataVencimento,0,0,'C',1);
				$this->Ln();	
				
				if($i >= $limit){
					$this->SetY($y+$heightTemp);
					$this->SetX($x);
					$heightTemp += $this->height_cell + 1;
				}

				$height += $this->height_cell;
				
				$this->Cell(90,1,'','T');
				$height += 1;
			}

			if(($i+1) == $qtdRows){				
				$this->Ln();

				$this->SetY($y+$heightTemp);
				$this->SetX($x);
				$heightTemp += $this->height_cell + 1;
				
				$this->Cell(90,1,'','T');
				$height += 1;
			}
		}

		// Reaviso

		$this->Ln();
		$this->SetY(98.5);
	
		$i = 0;
		$valorTotal = 0;
		$sql = "select
					distinct
					ContaReceber.IdContaReceber,
					ContaReceber.NumeroDocumento,
					(ContaReceber.ValorLancamento + ContaReceber.ValorDespesas) Valor,
					ContaReceber.DataVencimento,
					ContaReceber.DataLancamento
				from
					Pessoa,
					Contrato,
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber,
					ContaReceber
				where
					Contrato.IdLoja = $IdLoja and
					Contrato.IdLoja = LancamentoFinanceiro.IdLoja and
					Contrato.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					Contrato.IdLoja = ContaReceber.IdLoja and
					LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					Pessoa.IdPessoa = $IdPessoa and
					Contrato.IdPessoa = Pessoa.IdPessoa and
					LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and
					ContaReceber.DataVencimento < curdate() and
					ContaReceber.IdStatus = 1
				group by
					LancamentoFinanceiroContaReceber.IdContaReceber";
		$res = @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){

			$i++;

			if($i > 6){
				break;
			}

			if($i == 1){
				
			    // Conteúdo - Reaviso de Vencimento
				$this->SetFont('Arial','B',11);
			    $this->Write(5,'Reaviso de Vencimento*');
				$this->Ln();
				$height += 6;
	    
			    // Conteúdo - Reaviso de Vencimento - Título
				$this->SetFont('Arial','B',8);
			    $this->Cell(25,$this->height_cell,'Data Lançamento',0,0,'L',1);
			    $this->Cell(25,$this->height_cell,'Vencimento',0,0,'C',1);
			    $this->Cell(25,$this->height_cell,'Valor ('.getParametroSistema(5,1).')',0,0,'C',1);
			    $this->Ln();
				$height += $this->height_cell;

			    $this->Cell(75,1,'','T');
				$height += 1;

			}

			$valorTotal += $lin[Valor];
			$lin[ValorLancamento] = number_format($lin[Valor],2,',','');

			$lin[DataVencimento] = dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
			$lin[DataLancamento] = dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");

		    $this->Ln();
			$this->SetFont('Arial','',8);
		    $this->Cell(25,$this->height_cell,$lin[DataLancamento],0,0,'L',1);
		    $this->Cell(25,$this->height_cell,$lin[DataVencimento],0,0,'C',1);
		    $this->Cell(25,$this->height_cell,$lin[Valor],0,0,'R',1);
		    $this->Ln();			
			$height += $this->height_cell;

		    $this->Cell(75,1,'','T');
			$height += 1;
		}
		if($i > 0){			
			$valorTotal = number_format($valorTotal,2,',','');

			$this->Ln();
			$this->Cell(25);
   			$this->SetFont('Arial','B',8);
	    	$this->Cell(25,$this->height_cell,'Total',0,0,'C',1);
		    $this->Cell(25,$this->height_cell,$valorTotal,0,0,'R',1);
		    $this->Ln();
			$height += $this->height_cell;

	    	$this->Cell(75,1,'','T');			
			$height += 1;
			$this->Ln();

   			$this->SetFont('Arial','',7);
		    $this->Write(3,'*Caso o pagamento já tenha sido efetuado, desconsidere este reaviso.');
			$height += 3;
		}

		//===Dados do seu Cliente ===============
		$sql = "select 
				Pessoa.Nome,
			    Pessoa.NomeRepresentante, 
				Pessoa.RazaoSocial,
				Pessoa.TipoPessoa,
				Pessoa.CPF_CNPJ,
				Pessoa.RG_IE,
				Pessoa.Endereco,
				Pessoa.Numero,
				Pessoa.Complemento,
				Pessoa.Bairro, 
				Pessoa.CEP,
				Cidade.NomeCidade,
				Estado.SiglaEstado,
				Pessoa.Cob_FormaOutro,
			    Pessoa.Cob_NomeResponsavel, 
			    Pessoa.Cob_Endereco, 
			    Pessoa.Cob_Complemento, 
			    Pessoa.Cob_Numero, 
			    Pessoa.Cob_Bairro, 
			    Pessoa.Cob_IdPais, 
			    Pessoa.Cob_IdEstado, 
			    Pessoa.Cob_IdCidade, 
			    Pessoa.Cob_CEP
			from 
				Pessoa,
				Estado,
				Cidade
			where 
				Pessoa.IdPessoa = $IdPessoa and 
				Pessoa.IdPais = Estado.IdPais and
				Pessoa.IdPais = Cidade.IdPais and
				Pessoa.IdEstado = Estado.IdEstado and
				Pessoa.IdEstado = Cidade.IdEstado and
				Pessoa.IdCidade = Cidade.IdCidade";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		if($lin[Cob_Endereco]!='' && $lin[Cob_IdPais] != '' && $lin[Cob_IdEstado] != '' && $lin[Cob_IdCidade]){
			$Dados[NomeRepresentante]	=	$lin[Cob_NomeResponsavel];
			$Dados[Nome]				=	$lin[Nome];
			$Dados[Endereco]			=	$lin[Cob_Endereco];
			$Dados[Complemento]			=	$lin[Cob_Complemento];
			$Dados[Numero]				=	$lin[Cob_Numero];
			$Dados[Bairro]				=	$lin[Cob_Bairro];
			$Dados[CEP]					=	$lin[Cob_CEP];

			$sql = "select
						Cidade.NomeCidade, 
						Estado.SiglaEstado
					from
						Cidade,
						Estado
					where
						Cidade.IdPais=$lin[Cob_IdPais] and
						Cidade.IdPais = Estado.IdPais and
						Cidade.IdEstado=$lin[Cob_IdEstado] and
						Cidade.IdEstado = Estado.IdEstado and
						Cidade.IdCidade=$lin[Cob_IdCidade]";
			$res2	=	mysql_query($sql,$con);
			$lin2	=	mysql_fetch_array($res2);

			$Dados[NomeCidade]			=	$lin2[NomeCidade];
			$Dados[SiglaEstado]			=	$lin2[SiglaEstado];
		}else{		
			$Dados[NomeRepresentante]	=	$lin[NomeRepresentante];
			$Dados[Nome]				=	$lin[Nome];
			$Dados[Endereco]			=	$lin[Endereco];
			$Dados[Complemento]			=	$lin[Complemento];
			$Dados[Numero]				=	$lin[Numero];
			$Dados[Bairro]				=	$lin[Bairro];
			$Dados[NomeCidade]			=	$lin[NomeCidade];
			$Dados[SiglaEstado]			=	$lin[SiglaEstado];
			$Dados[CEP]					=	$lin[CEP];
		}

		# Linha 1 
		# Razão Social ou Nome Cliente
		$Dados[0]	=	$Dados[Nome];
		
		# Linha 2
		# Representante
		if($Dados[NomeRepresentante] != $Dados[Nome]){
			$Dados[1]	=	$Dados[NomeRepresentante];
		}
		
		# Linha 3
		# Endereço
		$Dados[2]	=	$Dados[Endereco];
		if($Dados[Numero] != ''){
			$Dados[2] .= ", ".$Dados[Numero];
		}

		# Linha 4
		# Complemento e Bairro
		$Dados[3]	=	$Dados[Complemento];
		if($Dados[3] != ''){
			$Dados[3] .= " - ";
		}
		$Dados[3] .= $Dados[Bairro];
		
		# Linha 5
		# Cidade e Estado
		$Dados[4]	=	$Dados[NomeCidade]." - ".$Dados[SiglaEstado];
		
		# Linha 6
		# CEP
		if($Dados[CEP] != ''){
			$Dados[4]	.=	" - CEP: ".$Dados[CEP];
		}

		for($i=0; $i<4; $i++){
			
			$Dados[$i] = trim($Dados[$i]);

			if($Dados[$i] == '' || $Dados[$i] == null){
				$Dados[$i]	 = $Dados[$i+1];
				$Dados[$i+1] = '';
			}
		}

		$this->SetY(95.5);
		$this->SetX(90);
		

		$this->SetFillColor(0,0,0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,4.5,'','L',0,'',0);
		$this->Cell(105,4.5,'','',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,4.5,'','L',0,'',0);
		$this->Cell(105,4.5,'','',0,'L',0);

		$this->Ln();
		$this->SetX(90);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,4.5,'','L',0,'',0);
		$this->Cell(105,4.5,'Destinatário','',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFont('Arial','',8.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[0],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[1],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[2],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[3],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,$Dados[4],'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,'','',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,3.5,'','L',0,'',0);
		$this->Cell(105,3.5,'PE'.$IdPessoa.'CA'.$IdCarne,'',0,'R',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->SetFillColor(0,0,0);
		$this->Cell(7,10.5,'','L',0,'',0);
		$this->Cell(105,15,'','',0,'L',0);

		
		// Mensagem qualquer		
#		$this->Ln();
#		$this->SetY(50);
#		$this->SetFont('Arial','B',11);
#		$this->MultiCell(190,7,'*A troca do carne se faz necessária em decorrência de erro no código de barra ao constar em duplicidade o número da agência, impossibilitando o pagamento através da rede bancária.',1,'C');
	}

	function Titulo($IdLoja, $IdContaReceber, $con){

		global $dadosboleto;
		global $posY;

		$SeparadorCampos	= "  - ";

		/* Coleta as informações da Loja */
		$sql = "select
				Pessoa.IdPessoa,
				Pessoa.TipoPessoa,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				Pessoa.CPF_CNPJ,
				Pessoa.Endereco,
				Pessoa.Numero,
				Pessoa.Complemento,
				Pessoa.CEP,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Fax,
				Cidade.NomeCidade,
				Estado.SiglaEstado
			from
				Loja,
				Pessoa,
				Cidade,
				Estado
			where
				Loja.IdLoja=$IdLoja and
				Loja.IdPessoa = Pessoa.IdPessoa and
				Cidade.IdPais = Pessoa.IdPais and
				Cidade.IdEstado = Pessoa.IdEstado and
				Cidade.IdCidade = Pessoa.IdCidade and
				Cidade.IdPais = Estado.IdPais and
				Cidade.IdEstado = Estado.IdEstado";
		$res = mysql_query($sql,$con);
		$linDadosEmpresa = mysql_fetch_array($res);

		if($linDadosEmpresa[Telefone] == ''){
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone1];
		}else{
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone2];
		}

		if($linDadosEmpresa[TipoPessoa] == 1){
			$CPF_CNPJ = "CNPJ";
		}else{			
			$CPF_CNPJ = "CPF";
		}

		//=============Dados da Sua empresa===============
		// SEUS DADOS
		$dadosboleto["cpf_cnpj"]	= $linDadosEmpresa[CPF_CNPJ];
		$dadosboleto["endereco"]	= $linDadosEmpresa[Endereco].", ".$linDadosEmpresa[Numero]." - ".$linDadosEmpresa[Complemento];
		$dadosboleto["cidade"]		= $linDadosEmpresa[NomeCidade]."-".$linDadosEmpresa[SiglaEstado]." - Cep: ".$linDadosEmpresa[CEP];
		$dadosboleto["cedente"]		=  $linDadosEmpresa[Nome];

		if($linDadosEmpresa[RazaoSocial] != ''){
			$dadosboleto["cedente"] .=" (".$linDadosEmpresa[RazaoSocial].")";
		}
		if($linDadosEmpresa[Telefone] != ''){
			$dadosboleto["fone"] 				= " - Fone / Fax: ".$linDadosEmpresa[Telefone];
		}

		/* Coleta de Informações do Conta Receber */
		$sql	= "select 
					DataVencimento, 
					NumeroDocumento, 
					DataLancamento, 
					(ValorLancamento + ValorDespesas) ValorLancamento, 
					IdLocalCobranca 
				from 
					ContaReceber 
				where 
					IdLoja=$IdLoja and 
					IdContaReceber=$IdContaReceber";

		$res	= @mysql_query($sql,$con);
		$linContaReceber = @mysql_fetch_array($res);

		$linContaReceber[DataVencimento]	= dataConv($linContaReceber[DataVencimento],"Y-m-d","d/m/Y");
		$linContaReceber[DataLancamento]	= dataConv($linContaReceber[DataLancamento],"Y-m-d","d/m/Y");
		$linContaReceber[ValorLancamento]	= number_format($linContaReceber[ValorLancamento], 2, ',', '');

		/* Coleta Informações do Local de Cobrança */
		$sql = "select 
					ValorDespesaLocalCobranca, 
					PercentualMulta, 
					PercentualJurosDiarios,
					DescricaoLocalPagamento
				from 
					LocalCobranca 
				where 
					IdLoja=$IdLoja and 
					IdLocalCobranca=$linContaReceber[IdLocalCobranca]";
		$res = @mysql_query($sql,$con);
		$linLocalCobranca = @mysql_fetch_array($res);

		$dadosboleto["local_pagamento"]	= $linLocalCobranca[DescricaoLocalPagamento];
		
		$ValorMulta = $linContaReceber[ValorLancamento] * $linLocalCobranca[PercentualMulta] / 100;
		$ValorMulta = number_format($ValorMulta, 2, ',', '');
		$ValorMulta = getParametroSistema(5,1).$ValorMulta;

		$ValorJurosDiarios = $linContaReceber[ValorLancamento] * $linLocalCobranca[PercentualJurosDiarios] / 100;
		$ValorJurosDiarios = number_format($ValorJurosDiarios, 2, ',', '');
		$ValorJurosDiarios = getParametroSistema(5,1).$ValorJurosDiarios;

		$sql = "select ValorDespesas from ContaReceber where IdLoja=$IdLoja and IdContaReceber=$IdContaReceber";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$ValorDespesaLocalCobranca = number_format($lin[ValorDespesas], 2, ',', '');
		$ValorDespesaLocalCobranca = getParametroSistema(5,1).$ValorDespesaLocalCobranca;


		/* Coleta de Informações dos Parâmetros */
		$sql = "select 
					IdLocalCobrancaParametro, 
					ValorLocalCobrancaParametro 
				from 
					LocalCobrancaParametro 
				where 
					IdLoja=$IdLoja and 
					IdLocalCobranca=$linContaReceber[IdLocalCobranca]";
		$res	= @mysql_query($sql,$con);
		while($linLocalCobrancaParametro = @mysql_fetch_array($res)){

			$linLocalCobrancaParametro[ValorLocalCobrancaParametro] = str_replace('$ValorMulta', $ValorMulta, $linLocalCobrancaParametro[ValorLocalCobrancaParametro]);

			$linLocalCobrancaParametro[ValorLocalCobrancaParametro] = str_replace('$ValorJurosDiarios', $ValorJurosDiarios, $linLocalCobrancaParametro[ValorLocalCobrancaParametro]);

			$linLocalCobrancaParametro[ValorLocalCobrancaParametro] = str_replace('$ValorDespesaLocalCobranca', $ValorDespesaLocalCobranca, $linLocalCobrancaParametro[ValorLocalCobrancaParametro]);

			$CobrancaParametro[$linLocalCobrancaParametro[IdLocalCobrancaParametro]] = $linLocalCobrancaParametro[ValorLocalCobrancaParametro];		
		}

		$dadosboleto["nosso_numero"]		= $linContaReceber[NumeroDocumento];
		$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Num do pedido ou do documento
		$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento]; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; // Data de emissão do Boleto
		$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; // Data de processamento do boleto (opcional)
		$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento]; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

		/* Coleta as informações da Loja */
		$sql = "select
				Pessoa.IdPessoa,
				Pessoa.TipoPessoa,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				Pessoa.CPF_CNPJ,
				Pessoa.Endereco,
				Pessoa.Numero,
				Pessoa.Complemento,
				Pessoa.CEP,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Fax,
				Cidade.NomeCidade,
				Estado.SiglaEstado
			from
				Loja,
				Pessoa,
				Cidade,
				Estado
			where
				Loja.IdLoja=$IdLoja and
				Loja.IdPessoa = Pessoa.IdPessoa and
				Cidade.IdPais = Pessoa.IdPais and
				Cidade.IdEstado = Pessoa.IdEstado and
				Cidade.IdCidade = Pessoa.IdCidade and
				Cidade.IdPais = Estado.IdPais and
				Cidade.IdEstado = Estado.IdEstado";
		$res = mysql_query($sql,$con);
		$linDadosEmpresa = mysql_fetch_array($res);

		if($linDadosEmpresa[Telefone] == ''){
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone1];
		}else{
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone2];
		}

		if($linDadosEmpresa[TipoPessoa] == 1){
			$CPF_CNPJ = "CNPJ";
		}else{			
			$CPF_CNPJ = "CPF";
		}

		if($linDadosEmpresa[RazaoSocial] != ''){
			$entra["cedente"] .=" (".$linDadosEmpresa[RazaoSocial].")";
		}
		if($linDadosEmpresa[Telefone] != ''){
			$entra["fone"] 				= " - Fone / Fax: ".$linDadosEmpresa[Telefone];
		}

		//===Dados do seu Cliente (Opcional)===============
		$sql = "select 
					LancamentoFinanceiro.IdLoja,				
					CASE WHEN Contrato.IdPessoa is null THEN ContaEventual.IdPessoa ELSE Contrato.IdPessoa END IdPessoa,
					Pessoa.Nome,
					Pessoa.RazaoSocial,
					Pessoa.TipoPessoa,
					Pessoa.CPF_CNPJ,
					Pessoa.RG_IE,
					Pessoa.Endereco,
					Pessoa.Numero,
					Pessoa.Complemento,
					Pessoa.Bairro, 
					Pessoa.CEP,
					Cidade.NomeCidade,
					Estado.SiglaEstado,
					Pessoa.Cob_FormaOutro
				from 
					LancamentoFinanceiro left join Contrato on 
										  (LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
							   LancamentoFinanceiro.IdContrato = Contrato.IdContrato) left join ContaEventual on 
										  (LancamentoFinanceiro.IdLoja = ContaEventual.IdLoja and 
							   LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual), 
					Pessoa,
					LancamentoFinanceiroContaReceber,
					Estado,
					Cidade
				where 
					(Contrato.IdPessoa = Pessoa.IdPessoa or ContaEventual.IdPessoa = Pessoa.IdPessoa) and
					LancamentoFinanceiro.IdLoja = $IdLoja and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and
					Pessoa.IdPais = Estado.IdPais and
					Pessoa.IdPais = Cidade.IdPais and
					Pessoa.IdEstado = Estado.IdEstado and
					Pessoa.IdEstado = Cidade.IdEstado and
					Pessoa.IdCidade = Cidade.IdCidade";
		$res = @mysql_query($sql,$con);
		$linDadosCliente = @mysql_fetch_array($res);

		if($linDadosCliente[RazaoSocial] != ''){
			$linDadosCliente[Nome] = $linDadosCliente[RazaoSocial];
		}

		if($linDadosCliente[CPF_CNPJ] != ""){
				if($linDadosCliente[TipoPessoa] == 1){
					$linDadosCliente[CPF_CNPJ] = "CNPJ: ".$linDadosCliente[CPF_CNPJ];
				}else{
					$linDadosCliente[CPF_CNPJ] = "CPF: ".$linDadosCliente[CPF_CNPJ];
				}
				$linDadosCliente[CPF_CNPJ] = $SeparadorCampos.$linDadosCliente[CPF_CNPJ];
		}


		if($linDadosCliente[RG_IE] != "" && $linDadosCliente[TipoPessoa] == 1){
			$linDadosCliente[RG_IE] = "Insc. Estadual: ".$linDadosCliente[RG_IE];
			$linDadosCliente[RG_IE] = $SeparadorCampos.$linDadosCliente[RG_IE];
		}

		if($linDadosCliente[Complemento] != ''){
			$linDadosCliente[Complemento] = " - ".$linDadosCliente[Complemento];
		}

		if($linDadosCliente[Bairro] != ''){
			$linDadosCliente[Bairro] = " - ".$linDadosCliente[Bairro];
		}

		// DADOS DO SEU CLIENTE
		$dadosboleto["sacado"]		= $linDadosCliente[Nome]."          ".$linDadosCliente[CPF_CNPJ]."          ".$linDadosCliente[RG_IE];
		$dadosboleto["endereco1"]	= $linDadosCliente[Endereco].", ".$linDadosCliente[Numero].$linDadosCliente[Complemento].$linDadosCliente[Bairro];
		$dadosboleto["endereco2"]	= $linDadosCliente[NomeCidade]."-".$linDadosCliente[SiglaEstado]." - CEP: ".$linDadosCliente[CEP];

		// INSTRUÇÕES PARA O CAIXA
		$dadosboleto["instrucoes1"]	= $CobrancaParametro[Instrucoes1]; //Instruçoes para o Cliente
		$dadosboleto["instrucoes2"]	= $CobrancaParametro[Instrucoes2]; // Por exemplo "Não receber apos o vencimento" ou "Cobrar Multa de 1% ao mês"
		$dadosboleto["instrucoes3"]	= $CobrancaParametro[Instrucoes3];
		$dadosboleto["instrucoes4"]	= $CobrancaParametro[Instrucoes4];
		$dadosboleto["instrucoes5"]	= $CobrancaParametro[Instrucoes5];

		// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
		$dadosboleto["quantidade"]		= "1";
		$dadosboleto["valor_unitario"]	= $dadosboleto["valor_boleto"];
		$dadosboleto["aceite"]			= $CobrancaParametro[Aceite];
		$dadosboleto["especie"]			= $CobrancaParametro[Especie];
		$dadosboleto["especie_doc"]		= $CobrancaParametro[EspecieDocumento];


	// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


		// DADOS DA SUA CONTA - CEF
		$dadosboleto["agencia"]		= $CobrancaParametro[Agencia]; // Num da agencia, sem digito
		$dadosboleto["conta"]		= $CobrancaParametro[Conta]; 	// Num da conta, sem digito
		$dadosboleto["conta_dv"]	= $CobrancaParametro[ContaDigito]; 	// Digito do Num da conta
		$dadosboleto["conta_cedente"] = $CobrancaParametro[Conta]; // ContaCedente do Cliente, sem digito (Somente Números)
		$dadosboleto["conta_cedente_dv"] = $CobrancaParametro[ContaDigito]; // Digito da ContaCedente do Cliente

		// DADOS PERSONALIZADOS - CEF
		$dadosboleto["carteira"]		= $CobrancaParametro[Carteira];
		$dadosboleto["cod_carteira"]	= $dadosboleto["carteira"];

		//==================================================================

		include("vars_bnb.php");

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
	    $this->Image("imagens/logobnb.jpg",11,($posTemp + 0.5),19.5,5,jpg);
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
		$this->Cell(49,2.5,$dadosboleto["cpf_cnpj_cedente"],0,0,'L',0);
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
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(32,2.5,'',0,0,'L',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(32,2.5,'',0,0,'L',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(44,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(30,2.5,'',0,0,'C',0);
		$this->Cell(0.55,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(46.8,2.5,'',0,0,'R',1);
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
			$this->Image("imagens/imgpxlazu.jpg",$i,($posTemp + 36.5),1,0.1,jpg);
			$i += 3;
		}
		
		// L11
		$this->Ln();
	    $this->Image("imagens/logobnb.jpg",11,($posTemp + 37.5),19.5,5,jpg);
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
		$this->Cell(7.9,3.5,'27','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(46.9,3.5,'(-) Desconto / Abatimento','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L21
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(131.3,2.5,'',0,0,'C',0);
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
		$this->Cell(131.2,3.5,$dadosboleto["instrucoes"],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(8,3.5,'35','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->Cell(46.9,3.5,'(-) Outras deduções','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L23
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(131.2,2.5,$dadosboleto["instrucoes1"],0,0,'L',0);
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
		$this->Cell(131.2,3.5,$dadosboleto["instrucoes2"],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(8,3.5,'19','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->Cell(46.9,3.5,'(+) Mora / Multa','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L25
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(131.3,2.5,$dadosboleto["instrucoes3"],0,0,'L',0);
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
		$this->Cell(131.2,3.5,$dadosboleto["instrucoes4"],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(8,3.5,'','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->Cell(46.9,3.5,'(+) Outros acréscimos','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L27
		$this->Ln();
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(131.2,2.5,'',0,0,'L',0);
		$this->Cell(8,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(46.9,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L28
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->Cell(131.2,3.5,'','',0,'L',0);
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
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(131.2,2.5,'',0,0,'L',0);
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
			$this->Image("../../../../img/estrutura_sistema/ico_estrela.jpg",195,($posTemp + 109.5),3.35,3.35,jpg);
		}
	}


	function TituloCarne($IdLoja, $IdContaReceber, $con){

		global $dadosboleto;
		global $posY;

		$SeparadorCampos	= "  - ";

		/* Coleta as informações da Loja */
		$sql = "select
				Pessoa.IdPessoa,
				Pessoa.TipoPessoa,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				Pessoa.CPF_CNPJ,
				Pessoa.Endereco,
				Pessoa.Numero,
				Pessoa.Complemento,
				Pessoa.CEP,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Fax,
				Cidade.NomeCidade,
				Estado.SiglaEstado
			from
				Loja,
				Pessoa,
				Cidade,
				Estado
			where
				Loja.IdLoja=$IdLoja and
				Loja.IdPessoa = Pessoa.IdPessoa and
				Cidade.IdPais = Pessoa.IdPais and
				Cidade.IdEstado = Pessoa.IdEstado and
				Cidade.IdCidade = Pessoa.IdCidade and
				Cidade.IdPais = Estado.IdPais and
				Cidade.IdEstado = Estado.IdEstado";
		$res = mysql_query($sql,$con);
		$linDadosEmpresa = mysql_fetch_array($res);

		if($linDadosEmpresa[Telefone] == ''){
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone1];
		}else{
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone2];
		}

		if($linDadosEmpresa[TipoPessoa] == 1){
			$CPF_CNPJ = "CNPJ";
		}else{			
			$CPF_CNPJ = "CPF";
		}

		//=============Dados da Sua empresa===============
		// SEUS DADOS
		$dadosboleto["cpf_cnpj"]	= $linDadosEmpresa[CPF_CNPJ];
		$dadosboleto["endereco"]	= $linDadosEmpresa[Endereco].", ".$linDadosEmpresa[Numero]." - ".$linDadosEmpresa[Complemento];
		$dadosboleto["cidade"]		= $linDadosEmpresa[NomeCidade]."-".$linDadosEmpresa[SiglaEstado]." - Cep: ".$linDadosEmpresa[CEP];
		$dadosboleto["cedente"]		=  $linDadosEmpresa[Nome];

		if($linDadosEmpresa[RazaoSocial] != ''){
			$dadosboleto["cedente"] .=" (".$linDadosEmpresa[RazaoSocial].")";
		}
		if($linDadosEmpresa[Telefone] != ''){
			$dadosboleto["fone"] 				= " - Fone / Fax: ".$linDadosEmpresa[Telefone];
		}

		/* Coleta de Informações do Conta Receber */
		$sql	= "select 
					DataVencimento, 
					NumeroDocumento, 
					DataLancamento, 
					(ValorLancamento + ValorDespesas) ValorLancamento, 
					IdLocalCobranca 
				from 
					ContaReceber 
				where 
					IdLoja=$IdLoja and 
					IdContaReceber=$IdContaReceber";

		$res	= @mysql_query($sql,$con);
		$linContaReceber = @mysql_fetch_array($res);

		$linContaReceber[DataVencimento]	= dataConv($linContaReceber[DataVencimento],"Y-m-d","d/m/Y");
		$linContaReceber[DataLancamento]	= dataConv($linContaReceber[DataLancamento],"Y-m-d","d/m/Y");
		$linContaReceber[ValorLancamento]	= number_format($linContaReceber[ValorLancamento], 2, ',', '');

		/* Coleta Informações do Local de Cobrança */
		$sql = "select 
					ValorDespesaLocalCobranca, 
					PercentualMulta, 
					PercentualJurosDiarios,
					DescricaoLocalPagamento
				from 
					LocalCobranca 
				where 
					IdLoja=$IdLoja and 
					IdLocalCobranca=$linContaReceber[IdLocalCobranca]";
		$res = @mysql_query($sql,$con);
		$linLocalCobranca = @mysql_fetch_array($res);

		$dadosboleto["local_pagamento"]	= $linLocalCobranca[DescricaoLocalPagamento];
		
		$ValorMulta = $linContaReceber[ValorLancamento] * $linLocalCobranca[PercentualMulta] / 100;
		$ValorMulta = number_format($ValorMulta, 2, ',', '');
		$ValorMulta = getParametroSistema(5,1).$ValorMulta;

		$ValorJurosDiarios = $linContaReceber[ValorLancamento] * $linLocalCobranca[PercentualJurosDiarios] / 100;
		$ValorJurosDiarios = number_format($ValorJurosDiarios, 2, ',', '');
		$ValorJurosDiarios = getParametroSistema(5,1).$ValorJurosDiarios;

		$sql = "select ValorDespesas from ContaReceber where IdLoja=$IdLoja and IdContaReceber=$IdContaReceber";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$ValorDespesaLocalCobranca = number_format($lin[ValorDespesas], 2, ',', '');
		$ValorDespesaLocalCobranca = getParametroSistema(5,1).$ValorDespesaLocalCobranca;


		/* Coleta de Informações dos Parâmetros */
		$sql = "select 
					IdLocalCobrancaParametro, 
					ValorLocalCobrancaParametro 
				from 
					LocalCobrancaParametro 
				where 
					IdLoja=$IdLoja and 
					IdLocalCobranca=$linContaReceber[IdLocalCobranca]";
		$res	= @mysql_query($sql,$con);
		while($linLocalCobrancaParametro = @mysql_fetch_array($res)){

			$linLocalCobrancaParametro[ValorLocalCobrancaParametro] = str_replace('$ValorMulta', $ValorMulta, $linLocalCobrancaParametro[ValorLocalCobrancaParametro]);

			$linLocalCobrancaParametro[ValorLocalCobrancaParametro] = str_replace('$ValorJurosDiarios', $ValorJurosDiarios, $linLocalCobrancaParametro[ValorLocalCobrancaParametro]);

			$linLocalCobrancaParametro[ValorLocalCobrancaParametro] = str_replace('$ValorDespesaLocalCobranca', $ValorDespesaLocalCobranca, $linLocalCobrancaParametro[ValorLocalCobrancaParametro]);

			$CobrancaParametro[$linLocalCobrancaParametro[IdLocalCobrancaParametro]] = $linLocalCobrancaParametro[ValorLocalCobrancaParametro];		
		}

		$dadosboleto["nosso_numero"]		= $linContaReceber[NumeroDocumento];
		$dadosboleto["numero_documento"]	= $linContaReceber[NumeroDocumento];	// Num do pedido ou do documento
		$dadosboleto["data_vencimento"]		= $linContaReceber[DataVencimento]; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		$dadosboleto["data_documento"]		= $linContaReceber[DataLancamento]; // Data de emissão do Boleto
		$dadosboleto["data_processamento"]	= $linContaReceber[DataLancamento]; // Data de processamento do boleto (opcional)
		$dadosboleto["valor_boleto"]		= $linContaReceber[ValorLancamento]; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

		/* Coleta as informações da Loja */
		$sql = "select
				Pessoa.IdPessoa,
				Pessoa.TipoPessoa,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				Pessoa.CPF_CNPJ,
				Pessoa.Endereco,
				Pessoa.Numero,
				Pessoa.Complemento,
				Pessoa.CEP,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Fax,
				Cidade.NomeCidade,
				Estado.SiglaEstado
			from
				Loja,
				Pessoa,
				Cidade,
				Estado
			where
				Loja.IdLoja=$IdLoja and
				Loja.IdPessoa = Pessoa.IdPessoa and
				Cidade.IdPais = Pessoa.IdPais and
				Cidade.IdEstado = Pessoa.IdEstado and
				Cidade.IdCidade = Pessoa.IdCidade and
				Cidade.IdPais = Estado.IdPais and
				Cidade.IdEstado = Estado.IdEstado";
		$res = mysql_query($sql,$con);
		$linDadosEmpresa = mysql_fetch_array($res);

		if($linDadosEmpresa[Telefone] == ''){
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone1];
		}else{
			$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone2];
		}

		if($linDadosEmpresa[TipoPessoa] == 1){
			$CPF_CNPJ = "CNPJ";
		}else{			
			$CPF_CNPJ = "CPF";
		}

		if($linDadosEmpresa[RazaoSocial] != ''){
			$entra["cedente"] .=" (".$linDadosEmpresa[RazaoSocial].")";
		}
		if($linDadosEmpresa[Telefone] != ''){
			$entra["fone"] 				= " - Fone / Fax: ".$linDadosEmpresa[Telefone];
		}

		//===Dados do seu Cliente (Opcional)===============
		$sql = "select 
					LancamentoFinanceiro.IdLoja,				
					CASE WHEN Contrato.IdPessoa is null THEN ContaEventual.IdPessoa ELSE Contrato.IdPessoa END IdPessoa,
					Pessoa.Nome,
					Pessoa.RazaoSocial,
					Pessoa.TipoPessoa,
					Pessoa.CPF_CNPJ,
					Pessoa.RG_IE,
					Pessoa.Endereco,
					Pessoa.Numero,
					Pessoa.Complemento,
					Pessoa.Bairro, 
					Pessoa.CEP,
					Cidade.NomeCidade,
					Estado.SiglaEstado,
					Pessoa.Cob_FormaOutro
				from 
					LancamentoFinanceiro left join Contrato on 
										  (LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
							   LancamentoFinanceiro.IdContrato = Contrato.IdContrato) left join ContaEventual on 
										  (LancamentoFinanceiro.IdLoja = ContaEventual.IdLoja and 
							   LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual), 
					Pessoa,
					LancamentoFinanceiroContaReceber,
					Estado,
					Cidade
				where 
					(Contrato.IdPessoa = Pessoa.IdPessoa or ContaEventual.IdPessoa = Pessoa.IdPessoa) and
					LancamentoFinanceiro.IdLoja = $IdLoja and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and
					Pessoa.IdPais = Estado.IdPais and
					Pessoa.IdPais = Cidade.IdPais and
					Pessoa.IdEstado = Estado.IdEstado and
					Pessoa.IdEstado = Cidade.IdEstado and
					Pessoa.IdCidade = Cidade.IdCidade";
		$res = @mysql_query($sql,$con);
		$linDadosCliente = @mysql_fetch_array($res);

		if($linDadosCliente[RazaoSocial] != ''){
			$linDadosCliente[Nome] = $linDadosCliente[RazaoSocial];
		}

		if($linDadosCliente[CPF_CNPJ] != ""){
				if($linDadosCliente[TipoPessoa] == 1){
					$linDadosCliente[CPF_CNPJ] = "CNPJ: ".$linDadosCliente[CPF_CNPJ];
				}else{
					$linDadosCliente[CPF_CNPJ] = "CPF: ".$linDadosCliente[CPF_CNPJ];
				}
				$linDadosCliente[CPF_CNPJ] = $SeparadorCampos.$linDadosCliente[CPF_CNPJ];
		}


		if($linDadosCliente[RG_IE] != "" && $linDadosCliente[TipoPessoa] == 1){
			$linDadosCliente[RG_IE] = "Insc. Estadual: ".$linDadosCliente[RG_IE];
			$linDadosCliente[RG_IE] = $SeparadorCampos.$linDadosCliente[RG_IE];
		}

		if($linDadosCliente[Complemento] != ''){
			$linDadosCliente[Complemento] = " - ".$linDadosCliente[Complemento];
		}

		if($linDadosCliente[Bairro] != ''){
			$linDadosCliente[Bairro] = " - ".$linDadosCliente[Bairro];
		}

		// DADOS DO SEU CLIENTE
		$dadosboleto["sacado"]		= $linDadosCliente[Nome]."          ".$linDadosCliente[CPF_CNPJ]."          ".$linDadosCliente[RG_IE];
		$dadosboleto["endereco1"]	= $linDadosCliente[Endereco].", ".$linDadosCliente[Numero].$linDadosCliente[Complemento].$linDadosCliente[Bairro];
		$dadosboleto["endereco2"]	= $linDadosCliente[NomeCidade]."-".$linDadosCliente[SiglaEstado]." - CEP: ".$linDadosCliente[CEP];

		// INSTRUÇÕES PARA O CAIXA
		$dadosboleto["instrucoes1"]	= $CobrancaParametro[Instrucoes1]; //Instruçoes para o Cliente
		$dadosboleto["instrucoes2"]	= $CobrancaParametro[Instrucoes2]; // Por exemplo "Não receber apos o vencimento" ou "Cobrar Multa de 1% ao mês"
		$dadosboleto["instrucoes3"]	= $CobrancaParametro[Instrucoes3];
		$dadosboleto["instrucoes4"]	= $CobrancaParametro[Instrucoes4];
		$dadosboleto["instrucoes5"]	= $CobrancaParametro[Instrucoes5];

		// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
		$dadosboleto["quantidade"]		= "1";
		$dadosboleto["valor_unitario"]	= $dadosboleto["valor_boleto"];
		$dadosboleto["aceite"]			= $CobrancaParametro[Aceite];
		$dadosboleto["especie"]			= $CobrancaParametro[Especie];
		$dadosboleto["especie_doc"]		= $CobrancaParametro[EspecieDocumento];


	// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


		// DADOS DA SUA CONTA - CEF
		$dadosboleto["agencia"]		= $CobrancaParametro[Agencia]; // Num da agencia, sem digito
		$dadosboleto["conta"]		= $CobrancaParametro[Conta]; 	// Num da conta, sem digito
		$dadosboleto["conta_dv"]	= $CobrancaParametro[ContaDigito]; 	// Digito do Num da conta
		$dadosboleto["conta_cedente"] = $CobrancaParametro[Conta]; // ContaCedente do Cliente, sem digito (Somente Números)
		$dadosboleto["conta_cedente_dv"] = $CobrancaParametro[ContaDigito]; // Digito da ContaCedente do Cliente

		// DADOS PERSONALIZADOS - CEF
		$dadosboleto["carteira"]		= $CobrancaParametro[Carteira];
		$dadosboleto["cod_carteira"]	= $dadosboleto["carteira"];

		//==================================================================

		include("vars_bnb.php");

		//============================Não mude o valores abaixo=============
		// Default
		if($posY == null){
			$this->SetY(2.5);
			$posTemp = 3.2;
			$aux	 =	94.3;
		}else{
			$this->SetY($posY+2.5);
			$posTemp = $posY + 2.7;
			$aux	=	(94 + $posY - 0.3);
		}
		$this->height_cell = 3;
		$this->margin_left = 10;
	    $this->SetLineWidth(0.3);
						
		// L1
		
		$this->SetFont('Arial','B',8);
		$this->Cell(35,6,"RECIBO DO SACADO",'',0,'L',0);
		
		$this->SetFont('Arial','',8);
		$i=$posTemp;
		
		while($i < $aux){
	//	Image(string file , float x , float y , float w , float h , string type , mixed link)
			$this->Image("imagens/imgpxlazu.jpg",45,$i,0.1,1,jpg);
			$this->Ln();
			$i += 3;
		}
	
		
		if($posY == null){
			$this->SetY(2.5);
		}else{
			$this->SetY($posY+2.5);
		}
	    $this->Image("imagens/logobnb.jpg",50,$posTemp,19.5,5,jpg);
		$this->Cell(65,6,'','');
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,6,'','',0,'',1);
		$this->SetFont('Arial','B',10);
		$this->Cell(15,6,$dadosboleto["codigo_banco_com_dv"],'',0,'C',0);
		$this->Cell(0.4,6,'','',0,'',1);
		$this->Cell(105.2,6,$dadosboleto["linha_digitavel"],'',0,'R',0);
		$this->Cell(3,6,'','',0,'',0);
		
		// L2
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'Cedente','LTR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(114.5,3.5,'Local de pagamento','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(33.5,3.5,'Vencimento','T',0,'L',1);
		$this->Cell(2.9,3.5,'','T',0,'',1);
		$this->SetFillColor(0,0,0);
		
		$cedente1	=	substr($dadosboleto["cedente"],0,20);
		$cedente2	=	substr($dadosboleto["cedente"],20,20);
		$cedente3	=	substr($dadosboleto["cedente"],40,20);
		
		// L3
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,$cedente1,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(114.5,2.5,$dadosboleto["local_pagamento"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(33.5,2.5,$dadosboleto["data_vencimento"],0,0,'R',1);
		$this->Cell(3,2.5,'','',0,'',1);
	
		// L4
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,$cedente2,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(114.3,3.5,'Cedente','T',0,'L',0);
		$this->Cell(0.5,3.5,'',0,0,'',1);
		$this->Cell(33.5,3.5,'Agência / Código cedente','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L5
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,$cedente3,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(114.3,2.5,$dadosboleto["cedente"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(33.5,2.5,$dadosboleto["agencia_codigo"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L6
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'Vencimento','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'',0,0,'',1);
		$this->Cell(25,3.5,'Data do documento','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(36.5,3.5,'Nº documento','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(17,3.5,'Espécie doc.','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(9,3.5,'Aceite','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(25,3.5,'Data process.','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(33.5,3.5,'Nosso número','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L7
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,$dadosboleto["data_vencimento"],'LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(25,2.5,$dadosboleto["data_documento"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(36.5,2.5,$dadosboleto["numero_documento"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(17,2.5,$dadosboleto["especie_doc"],0,0,'C',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(9,2.5,$dadosboleto["aceite"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(25,2.5,$dadosboleto["data_processamento"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(33.5,2.5,$dadosboleto["nosso_numero"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);

		// L8
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'Agência / Código cedente','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(25,3.5,'Uso do banco','T',0,'L',1);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(20.5,3.5,'Carteira','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(15.4,3.5,'Espécie','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(17,3.5,'Quantidade','T',0,'L',0);
		$this->Cell(0.4,3.5,'','T',0,'',1);
		$this->Cell(34.4,3.5,'Valor','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(33.5,3.5,'(=) Valor documento','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
		
		// L9
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,$dadosboleto["agencia_codigo"],'LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(25,2.5,'',0,0,'C',1);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(20.5,2.5,$dadosboleto["carteira"],0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(15.4,2.5,$dadosboleto["especie"],0,0,'C',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(17,2.5,$dadosboleto["quantidade"],0,0,'L',0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(34.4,2.5,$dadosboleto["valor_unitario"],0,0,'R',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(33.5,2.5,$dadosboleto["valor_unitario"],0,0,'R',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L10
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'Nosso Número','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','T',0,'',0);
		$this->Cell(106.2,3.5,'Instruções (Texto de responsabilidade do cedente)','T',0,'L',0);
		$this->Cell(7.9,3.5,'27','T',0,'L',0);
		$this->Cell(0.5,3.5,'','T',0,'',1);
		$this->Cell(33.5,3.5,'(-) Desconto / Abatimento','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L11
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,$dadosboleto["nosso_numero"],'LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(106.2,2.5,'',0,0,'C',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(33.5,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L12
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'(=) Valor documento','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(106.2,3.5,$dadosboleto["instrucoes"],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(7.9,3.5,'35','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->Cell(33.5,3.5,'(-) Outras deduções','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L13
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,$dadosboleto["valor_unitario"],'LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(106.2,2.5,$dadosboleto["instrucoes1"],0,0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(33.5,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L14
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'(-) Desconto / Abatimento','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(106.2,3.5,$dadosboleto["instrucoes2"],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(7.9,3.5,'19','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->Cell(33.5,3.5,'(+) Mora / Multa','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L15
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(106.2,2.5,$dadosboleto["instrucoes3"],0,0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(33.5,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L16
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'(-) Outras deduções','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->Cell(106.2,3.5,$dadosboleto["instrucoes4"],'',0,'L',0);
		$this->SetFont('Arial','',6.5);
		$this->Cell(7.9,3.5,'','',0,'L',0);
		$this->Cell(0.5,3.5,'','',0,'',1);
		$this->Cell(33.5,3.5,'(+) Outros acréscimos','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L17
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(106.2,2.5,'',0,0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.5,2.5,'',0,0,'',1);
		$this->Cell(33.5,2.5,'',0,0,'C',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L18
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'(+) Mora / Multa','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,3.5,'','',0,'',0);
		$this->Cell(106.2,3.5,'','',0,'L',0);
		$this->Cell(7.9,3.5,'','',0,'L',0);
		$this->Cell(0.7,3.5,'','',0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(33.5,3.5,'(=) Valor cobrado','T',0,'L',1);
		$this->Cell(3,3.5,'','',0,'',0);
	
		// L19
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.6,2.5,'',0,0,'',0);
		$this->Cell(106.2,2.5,'',0,0,'L',0);
		$this->Cell(7.9,2.5,'',0,0,'L',0);
		$this->Cell(0.7,2.5,'',0,0,'',1);
		$this->SetFillColor(255,255,255);
		$this->Cell(33.5,2.5,'',0,0,'C',1);
		$this->Cell(3,2.5,'','',0,'',1);
	
		// L20
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'(+) Outros acréscimos','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'','',0,'',1);
		$this->Cell(148.4,3.5,'Sacado','T',0,'L',0);
		$this->Cell(3,3.5,'','T',0,'',0);
	
		// L21
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,2.5,'',0,0,'',1);
		$this->Cell(148.4,2.5,$dadosboleto["sacado"],0,0,'L',0);
		$this->Cell(3,2.5,'','',0,'',0);
	
		// L22
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'(=) Valor cobrado','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3.5,'',0,0,'',1);
		$this->Cell(148.4,3.5,$dadosboleto["endereco1"],0,0,'L',0);
		$this->Cell(3,3.5,'','',0,'',0);
	
		// L23
		$this->Ln();
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3,'','LR',0,'R',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3,'',0,'',0);
		$this->SetFont('Arial','B',7.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(0.4,3,'','',0,'',1);
		$this->Cell(148.4,3,$dadosboleto["endereco2"],'B',0,'L',0);
		$this->Cell(3,3,'','B',0,'',0);

		// L24 - Codigo de Barras
		$this->Ln(1.5);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.5,'Sacado','LRT',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,3.5,'',0,'',0);
		
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

		$this->Ln(3);
		
		$sacado1	=	substr($linDadosCliente[Nome],0,20);
		$sacado2	=	substr($linDadosCliente[Nome],20,20);
		$sacado3	=	substr($linDadosCliente[Nome],40,20);
		$sacado4	=	substr($linDadosCliente[Nome],60,20);
		$sacado5	=	substr($linDadosCliente[Nome],80,20);
		$sacado6	=	substr($linDadosCliente[Nome],100,20);
		
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,$sacado1,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->Cell(3,2.5,'',0,'',0);
		
		// Preto - Fino
		//$this->SetX(46.5);
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
			$this->Image("../../../../img/estrutura_sistema/ico_estrela.jpg",195,($posTemp + 109.5),3.35,3.35,jpg);
		}
		
		$this->SetY($posY+79.2);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,$sacado2,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);
	
		$this->SetY($posY+81.7);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,2.5,$sacado3,'LR',0,'L',0);
		$this->SetFillColor(0,0,0);
		
		$this->SetY($posY+84.2);
		$this->SetFont('Arial','',6.5);
		$this->SetFillColor(0,0,0);
		$this->Cell(33.5,3.8,$sacado4,'LRB',0,'L',0);
		$this->SetFillColor(0,0,0);
	}

	function Tracejado($Posicao){
		$i=11;
		while($i < 200){
			$this->Image("imagens/imgpxlazu.jpg",$i,$Posicao,1,0.1,jpg);
			$i += 3;
		}
	}
}
?>
