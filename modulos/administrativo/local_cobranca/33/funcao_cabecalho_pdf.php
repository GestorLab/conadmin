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
	
	$ExtLogo = end(explode(".",$logo));
	
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
				NotaFiscalTipo.IdNotaFiscalLayout = $linNotaFiscal[IdNotaFiscalLayout] and
				NotaFiscalTipo.IdStatus = 1";
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
			
			$dadosNF[cpf_cnpj_cedente] = $dadosNF[CNPJ];
		
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
			
			$this->MultiCell(0,3.5,"$dadosNF[cedente]\n$dadosNF[endereco] - $dadosNF[cidade]\nCNPJ: $dadosNF[cpf_cnpj_cedente]".$dadosNF[ie].$dadosNF[fone],0,0,'R',1);
		}
	}else{
		$this->MultiCell(0,3.5,"$dadosboleto[cedente]\n$dadosboleto[endereco] - $dadosboleto[cidade]\n$CPF_CNPJ: $dadosboleto[cpf_cnpj_cedente]".$dadosboleto[ie].$dadosboleto[fone],0,0,'R',1);
	}
	$this->Image($logo,13,3.5,$dadosImgLargura,$dadosImgAltura,$ExtLogo);
?>
