<?	
	global $ExtLogoPDF;
	global $Background;
	
	$logo				= $ExtLogoPDF;
	$ExtLogo 			= endArray(explode(".",$logo));
	$imagem_retangulo	= "imagens/Exemploquadrado.gif";
	$imagem_retangulo2	= "imagens/Exemploquadrado2.gif";

	
	if($Background == 's'){
		$PatchImagens = $Path."/modulos/administrativo/local_cobranca/38/";
	}
	
	$this->AddPage();
	$this->SetFont('Arial','',8);
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(0);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0.3);
	include($Path."modulos/administrativo/local_cobranca/informacoes_default.php");
	
	$this->SetFont('Arial','B',7.5);
	$this->SetY(6);
	$this->Image($PatchImagens."imagens/logo_principal.png",8.5,2.1,45,17,"png");
	$this->Cell(123,25.5,"",0,'L',0);
	$this->SetFillColor(0,0,0);
	$this->MultiCell(70,3.0,"Endereзo para devoluзгo: \n$dadosboleto[endereco] \nFone: $linDadosEmpresa[Telefone]",0,'L',false);
	$this->Ln();
	$this->line(8.5,19,203,19);
	$this->line(8.5,19,8.5,41.7);
	$this->line(8.5,41.7,203,41.7);
	$this->line(203,19,203,41.7);
	$this->SetFont('Arial','B',10);
	$this->Text(11.5,23,"Para uso do Correio");
	$this->Image($PatchImagens."imagens/uso_correios.png",11.5,25.5,190,13,"png");
	$this->Image($PatchImagens."imagens/corpo_parte1.png",-0.5,43.3,211.1,110,"png");
	$this->Image($PatchImagens."imagens/corpo_parte2.png",-0.5,159.4,211.6,75,"png");
	
	$this->Text(19,124.9,$dadosboleto["nome_sacado"]);
	$this->Text(19,128.9,$dadosboleto["endereco01"]);
	$this->Text(19,132.9,$dadosboleto["endereco02"]);
	$this->Text(19,136.9,$dadosboleto["endereco03"]);
	
	$this->cod_Cep_Barra($linDadosClienteCobranca[CEP]);

	// Criar funзгo para gerar via "blocos" o cуdigo de barras.
#	$this->Image($PatchImagens."imagens/cep_cod_barra.png",19,115,60.2,7.0,"png");
?>