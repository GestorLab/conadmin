<?
	global $Background;
	global $IdLoja;
	global $IdContaReceber;
	global $ExtLogoPDF;

	include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");

	//=============Dados da Sua empresa===============
	$dadosboleto["cpf_cnpj_cedente"] 	= $linDadosEmpresa[CPF_CNPJ];

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
			$this->Cell(190,1,'','T');
			$this->Image($logo,13,3.5,$dadosImgLargura,$dadosImgAltura,$ExtLogo);
			$this->MultiCell(0,3.5,"$dadosNF[cedente]\n$dadosNF[endereco] - $dadosNF[cidade]\nCNPJ: $dadosNF[cpf_cnpj_cedente]".$dadosNF[ie].$dadosNF[fone],0,0,'L',1);
		}
	}else{
		$this->Image($logo,13,3.5,$dadosImgLargura,$dadosImgAltura,$ExtLogo);
		$this->Line(85,4,200,4);
		$this->Line(85,12,200,12);
		$this->SetFont("Arial","","12");
		$this->Line(142.5,4,142.5,12);
		$this->Text(86,10,"Fatura Mensal");
		$this->SetFont("Arial","","7.5");
		$this->Text(143.5,7.5,"Código do");
		$this->Text(143.5,10.5,"Assinante");
		$this->SetFont("Arial","B","13");
		$this->SetY(05);
		$this->Cell(190,7.5,$linDadosCliente["CampoExtra1"],false,'R',0); //Aqui e inserido o valor codigo assinante
		#$this->Ln();
		$this->SetY(16);
		$this->SetFont("Arial","B","7");
		$this->MultiCell(80,3.0,"$dadosboleto[cedente]\n",0,'L',1);
		$this->SetFont("Arial","","6.5");
		$this->MultiCell(81,3.0,"$dadosboleto[endereco] - CEP: $linDadosEmpresa[CEP]\n",0,'L',1);
		$this->MultiCell(81,3.0,"$linDadosEmpresa[NomeCidade] - $linDadosEmpresa[SiglaEstado] - CNPJ: $dadosboleto[cpf_cnpj_cedente]",0,'L',1);
		$this->SetFont("Arial","","10");
		$this->Text(100,17,"Vencimento:");
		$this->Text(145,17,"Valor:");
		$this->SetFont("Arial","B","16");
		$this->Text(100,22,"$linContaReceber[DataVencimento]");
		$this->Text(145,22,"".getParametroSistema(5,1)." $linContaReceber[ValorLancamento]");
		$this->Ln();
		$this->SetLineWidth(0.6);
		$this->Line(10,28.1,199.8,28.1);
		$this->Ln(2);
	}
?>