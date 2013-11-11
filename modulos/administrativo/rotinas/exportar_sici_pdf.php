<?php
	set_time_limit(0);
	
	$localModulo				= 1;
	$localOperacao				= 159;
	$localSuboperacao			= "V";
	$pathPDF					= "../../";
	$local_PeriodoApuracao		= $_GET["PeriodoApuracao"];
	$local_IdMetodoExportacao	= $_GET["IdMetodoExportacao"];
	
	if($PeriodoApuracao != ""){
		$local_PeriodoApuracao = $PeriodoApuracao;
	}
	
	if($IdMetodoExportacao != ""){
		$local_IdMetodoExportacao = $IdMetodoExportacao;
	}
	
	if((int)$local_IdMetodoExportacao != 2){
		$pathPDF .= "../";
		
		include($pathPDF."files/conecta.php");
		include($pathPDF."files/funcoes.php");
		include($pathPDF."rotinas/verifica.php");
	}
	
	$Perfil			= logoPerfil();
	$Moeda			= "(" . getParametroSistema(5,1) . ")";
	# MONTANDO COMPARAÇÃO PARA TODAS A CONSULTAS
	if($local_PeriodoApuracao != ''){
		if(@ereg("([0-9])/([0-9])", $local_PeriodoApuracao)){
			$local_PeriodoApuracao = dataConv($local_PeriodoApuracao, "m/Y", "Y-m");
		}
		
		list($Ano, $Mes) = explode("-", $local_PeriodoApuracao);
		
		if((int)$Mes == 12){ // ANUAL
			$local_IdTipoApuracao = 3;
		} elseif((int)$Mes == 6){ // SEMESTRAL
			$local_IdTipoApuracao = 2;
		} else{ // MENSAL
			$local_IdTipoApuracao = 1;
		}
		
		$TipoApuracao = getParametroSistema(237, $local_IdTipoApuracao);
	}
	
	if($local_IAU1NumeroCAT == ""){
		$local_IAU1NumeroCAT = "0000-000000";
	}
	
	ini_set("memory_limit", getParametroSistema(138, 1));
	define("FPDF_FONTPATH", $pathPDF."classes/fpdf/font/");
	include($pathPDF."classes/fpdf/class.fpdf.php");
	
	class PDF extends FPDF {
		function Footer() {
			$this->Line(0.4, 28, 20.6, 28);
			$this->SetY(28.3);
			$this->SetTextColor(0, 0, 0);
			$this->SetFont("Arial", '', 7);
			$this->Cell(0, 0.4, "ConAdmin - Sistema Administrativo de Qualidade", 0, 0, 'L');
			$this->Cell(0, 0.4, $this->PageNo(), 0, 1, 'R');
		}
	}
	
	$pdf = new PDF('P', "cm", "A4");
	$pdf->SetMargins(0.6, 1, 0.6);
	$pdf->AddPage();
	$pdf->Image($Perfil[UrlLogoGIF], 0.7, 0.8);
	$sql = "SELECT 
				Pessoa.IdPessoa, 
				Pessoa.TipoPessoa, 
				Pessoa.Nome, 
				SUBSTRING(Pessoa.RazaoSocial, 1, 65) Cedente, 
				Pessoa.CPF_CNPJ, 
				Pessoa.RG_IE, 
				Pessoa.IdEnderecoDefault,
				CONCAT(PessoaEndereco.Endereco, ',', PessoaEndereco.Numero) Endereco, 
				PessoaEndereco.Complemento, 
				PessoaEndereco.Bairro,
				PessoaEndereco.CEP,
				Cidade.NomeCidade, 
				Estado.SiglaEstado,
				Pessoa.Telefone1, 
				Pessoa.Telefone2, 
				Pessoa.Fax
			FROM
				Loja,
				Pessoa,
				PessoaEndereco,
				Cidade, 
				Estado 
			WHERE 
				Pessoa.IdPessoa = Loja.IdPessoa AND 
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa AND
				Cidade.IdPais = PessoaEndereco.IdPais AND 
				Cidade.IdEstado = PessoaEndereco.IdEstado AND 
				Cidade.IdCidade = PessoaEndereco.IdCidade AND 
				Cidade.IdPais = Estado.IdPais AND 
				Cidade.IdEstado = Estado.IdEstado;";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
	
	if($lin["TipoPessoa"] == 1){
		$lin["Pessoa"] = "CNPJ: ";
	} else{			
		$lin["Pessoa"] = "CPF: ";
	}
	
	$lin["Pessoa"] .= $lin["CPF_CNPJ"];
	
	if($lin["RG_IE"] != ''){
		$lin["Pessoa"] .= " - IE: ".$lin["RG_IE"];
	}
	
	if($lin["Telefone1"] != ''){
		$lin["Telefone"] = $lin["Telefone1"];
	} else{
		$lin["Telefone"] = $lin["Telefone2"];
	}
	
	if($lin["Telefone"] != ''){
		$lin["Pessoa"] .= " - Fone / Fax: ".$lin["Telefone"];
	}
	
	if($lin["Complemento"] != ''){
		$lin["Endereco"] .= " - ".$lin["Complemento"];
	}
	
	$lin["Endereco"] .= " - ".$lin["Bairro"]." - ".$lin["NomeCidade"]."-".$lin["SiglaEstado"]." - Cep: ".$lin["CEP"];
	$pdf->SetY(1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(0, 0.35, $lin["Cedente"], 0, 1, "R");
	$pdf->Cell(0, 0.35, $lin["Endereco"], 0, 1, "R");
	$pdf->Cell(0, 0.35, $lin["Pessoa"], 0, 1, "R");
	$pdf->SetXY(0.4, 2.6);
	$pdf->SetFillColor(16, 81, 173);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(20.2, 0.8, 'SICI - SISTEMA DE COLETA DE INFORMAÇÕES', 0, 1, 'C', true);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(19.86, 0.3, '', 0, 1);
	$pdf->Cell(19.86, 0.44, "Tipo de Apuração: ".$TipoApuracao, 0, 1);
	$pdf->Cell(19.86, 0.44, "Período de Apuração: ".dataConv($local_PeriodoApuracao, "Y-m", "m/Y"), 0, 1);
	$pdf->Cell(19.86, 0.44, "Data de Geração: ".date("d/m/Y"), 0, 1);
	$pdf->Line(0.4, 5.3, 20.6, 5.3);
	$pdf->SetY(5.8);
	$pdf->SetTextColor(15, 85, 169);
	$pdf->Cell(17.46, 0.44, 'Indicadores por UF e MUNICIPIO', 0, 1, 'L');
	$pdf->SetTextColor(0, 0, 0);
	
	$sql_uf = "SELECT
					SICIEstado.IdPais,
					SICIEstado.IdEstado,
					SICIEstado.IEM4,
					SICIEstado.IEM5,
					SICIEstado.IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica,
					SICIEstado.IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica,
					SICIEstado.IEM10MenorPreco1MbpsDedicadoPessoaFisica,
					SICIEstado.IEM10MaiorPreco1MbpsDedicadoPessoaFisica,
					SICIEstado.IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica,
					SICIEstado.IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica,
					SICIEstado.IEM10MenorPreco1MbpsDedicadoPessoaJuridica,
					SICIEstado.IEM10MaiorPreco1MbpsDedicadoPessoaJuridica,
					Estado.SiglaEstado
				FROM
					SICIEstado,
					Estado
				WHERE
					SICIEstado.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
					SICIEstado.IdPais = Estado.IdPais AND
					SICIEstado.IdEstado = Estado.IdEstado;";
	$res_uf = mysql_query($sql_uf, $con);
	
	while($lin_uf = @mysql_fetch_array($res_uf)){
		$pdf->SetX(3.2);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(0.55, 0.6, "UF: ", 0, 0);
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(15, 0.6, "$lin_uf[SiglaEstado]", 0, 1);
		$pdf->SetX(3.76);
		# TIPO APURAÇÃO SEMESTRAL OU ANUAL
		if($local_IdTipoApuracao == 2 || $local_IdTipoApuracao == 3){
			# IEM 4 - INT
			$pdf->SetTextColor(14, 78, 156);
			$pdf->SetFont('Arial', 'BU', 10);
			$pdf->Cell(0.9, 0.6, "IEM 4", 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(13.74, 0.6, " - Evolução do Número de Postos de Trabalho Diretos.", 0, 1);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->Cell(0.2, 0.3, '', 0, 1);
			$pdf->Cell(10.2, 0.44, "Quantidade de empregados contratados diretamente:", 0, 0, "R");
			$pdf->Cell(2.7, 0.4, $lin_uf[IEM4], 1, 1, "L");
			$pdf->Cell(0.2, 0.7, '', 0, 1);
			$pdf->SetX(3.76);
			# IEM 5 - INT
			$pdf->SetTextColor(14, 78, 156);
			$pdf->SetFont('Arial', 'BU', 10);
			$pdf->Cell(0.9, 0.6, "IEM 5", 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(13.74, 0.6, " - Evolução do Número de Postos de Trabalho Indiretos.", 0, 1);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(0.2, 0.3, '', 0, 1);
			$pdf->Cell(10.2, 0.44, "Quantidade de empregados de empresas terceirizadas:", 0, 0, "R");
			$pdf->Cell(2.7, 0.4, $lin_uf[IEM5], 1, 1, "L");
			$pdf->Cell(0.2, 0.7, '', 0, 1);
			$pdf->SetX(3.76);
		}
		# IEM 9 - FLOAT
		$pdf->SetTextColor(14, 78, 156);
		$pdf->SetFont('Arial', 'BU', 10);
		$pdf->Cell(0.9, 0.6, "IEM 9", 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(13.94, 0.6, " - Preço Médio.", 0, 1);
		$pdf->SetTextColor(0, 0, 0);
		$GrupoVelocidade = array(
			1 => "Velocidade <= 512Kbps $Moeda:",
			2 => "Velocidade entre 512Kbps e 2Mbps $Moeda:",
			3 => "Velocidade entre 2Mbps e 12Mbps $Moeda:",
			4 => "Velocidade entre 12Mbps e 34Mbps $Moeda:",
			5 => "Velocidade > 34Mbps $Moeda:"
		);
		$pdf->SetX(6);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(0.74, 0.6, "Tipo: ", 0, 0);
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(15, 0.6, "Pessoa Física", 0, 1);
		$i = $ln = 0;
		$sql_gv = "SELECT 
						SICIEstadoVelocidade.IdVelocidade,
						SICIEstadoVelocidade.IEM9PessoaFisica,
						SICIVelocidade.DescricaoVelocidade
					FROM
						SICIEstadoVelocidade,
						SICIVelocidade
					WHERE
						SICIEstadoVelocidade.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
						SICIEstadoVelocidade.IdPais = '".$lin_uf[IdPais]."' AND
						SICIEstadoVelocidade.IdEstado = '".$lin_uf[IdEstado]."' AND
						SICIEstadoVelocidade.IdVelocidade = SICIVelocidade.IdVelocidade;";
		$res_gv = @mysql_query($sql_gv, $con);
		
		while($lin_gv = @mysql_fetch_array($res_gv)) {
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(6.18, 0.44, $GrupoVelocidade[$lin_gv[IdVelocidade]], 0, 0, "R");
			$pdf->Cell(2.7, 0.4, number_format($lin_gv[IEM9PessoaFisica], 2, ',', ''), 1, $ln);
			
			if($i % 2 == 0) {
				$ln = 1;
			} else {
				$ln = 0;
				$pdf->Cell(0.2, 0.4, '', 0, 1);
			}
			
			$i++;
		}
		
		$pdf->Cell(0.2, 0.7, '', 0, 1);
		$pdf->SetX(6);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(0.74, 0.6, "Tipo: ", 0, 0);
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(15, 0.6, "Pessoa Jurídica", 0, 1);
		$i = $ln = 0;
		$sql_gv = "SELECT 
						SICIEstadoVelocidade.IdVelocidade,
						SICIEstadoVelocidade.IEM9PessoaJuridica,
						SICIVelocidade.DescricaoVelocidade
					FROM
						SICIEstadoVelocidade,
						SICIVelocidade
					WHERE
						SICIEstadoVelocidade.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
						SICIEstadoVelocidade.IdPais = '".$lin_uf[IdPais]."' AND
						SICIEstadoVelocidade.IdEstado = '".$lin_uf[IdEstado]."' AND
						SICIEstadoVelocidade.IdVelocidade = SICIVelocidade.IdVelocidade;";
		$res_gv = @mysql_query($sql_gv, $con);
		
		while($lin_gv = @mysql_fetch_array($res_gv)) {
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(6.18, 0.44, $GrupoVelocidade[$lin_gv[IdVelocidade]], 0, 0, "R");
			$pdf->Cell(2.7, 0.4, number_format($lin_gv[IEM9PessoaJuridica], 2, ',', ''), 1, $ln);
			
			if($i % 2 == 0) {
				$ln = 1;
			} else {
				$ln = 0;
				$pdf->Cell(0.2, 0.4, '', 0, 1);
			}
			
			$i++;
		}
		
		$pdf->Cell(0.2, 0.7, '', 0, 1);
		$pdf->Cell(0.2, 0.4, '', 0, 1);
		$pdf->SetX(3.76);
		# IEM 10 - FLOAT
		$pdf->SetTextColor(14, 78, 156);
		$pdf->SetFont('Arial', 'BU', 10);
		$pdf->Cell(1.1, 0.6, "IEM 10", 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(13.74, 0.6, " - Menor e maior preço por 1 Mbps.", 0, 1);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetX(6);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(0.74, 0.6, "Tipo: ", 0, 0);
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(15, 0.6, "Pessoa Física", 0, 1);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(6.18, 0.44, "Menor preço por 1Mbps (não dedicado)".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_uf[IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica], 2, ',', ''), 1, 0);
		$pdf->Cell(6.18, 0.44, "Menor preço por 1Mbps (dedicado)".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_uf[IEM10MenorPreco1MbpsDedicadoPessoaFisica], 2, ',', ''), 1, 1);
		$pdf->Cell(0.2, 0.4, '', 0, 1);
		$pdf->Cell(6.18, 0.44, "Maior preço por 1Mbps (não dedicado)".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_uf[IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica], 2, ',', ''), 1, 0);
		$pdf->Cell(6.18, 0.44, "Maior preço por 1Mbps (dedicado)".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_uf[IEM10MaiorPreco1MbpsDedicadoPessoaFisica], 2, ',', ''), 1, 1);
		$pdf->Cell(0.2, 0.3, '', 0, 1);
		$pdf->SetX(6);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(0.74, 0.6, "Tipo: ", 0, 0);
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(15, 0.6, "Pessoa Jurídica", 0, 1);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(6.18, 0.44, "Menor preço por 1Mbps (não dedicado)".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_uf[IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica], 2, ',', ''), 1, 0);
		$pdf->Cell(6.18, 0.44, "Menor preço por 1Mbps (dedicado)".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_uf[IEM10MenorPreco1MbpsDedicadoPessoaJuridica], 2, ',', ''), 1, 1);
		$pdf->Cell(0.2, 0.4, '', 0, 1);
		$pdf->Cell(6.18, 0.44, "Maior preço por 1Mbps (não dedicado)".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_uf[IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica], 2, ',', ''), 1, 0);
		$pdf->Cell(6.18, 0.44, "Maior preço por 1Mbps (dedicado)".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_uf[IEM10MaiorPreco1MbpsDedicadoPessoaJuridica], 2, ',', ''), 1, 1);
		$pdf->Cell(0.2, 0.3, '', 0, 1);
		
		# BUSCAR TODOS OS MUNICIPIO QUE POSSUI PESSOAS QUE TEM CONTRATOS DIFERENTE DE CANCELADO PARA IPL 3
		$sql_mn = "SELECT 
						SICICidade.PeriodoApuracao,
						SICICidade.IdPais,
						SICICidade.IdEstado,
						SICICidade.IdCidade,
						SICICidade.IPL3PessoaFisica,
						SICICidade.IPL3PessoaJuridica,
						SICICidade.IPL6,
						Cidade.NomeCidade
					FROM
						SICICidade,
						Cidade
					WHERE
						SICICidade.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
						SICICidade.IdPais = '".$lin_uf[IdPais]."' AND
						SICICidade.IdEstado = '".$lin_uf[IdEstado]."' AND
						SICICidade.IdPais = Cidade.IdPais AND
						SICICidade.IdEstado = Cidade.IdEstado AND
						SICICidade.IdCidade = Cidade.IdCidade;";
		$res_mn = mysql_query($sql_mn, $con);
		
		while($lin_mn = @mysql_fetch_array($res_mn)) {
			$pdf->Cell(0.2, 0.4, '', 0, 1);
			$pdf->SetX(2.36);
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(1.38, 0.6, "Municipio: ", 0, 0);
			$pdf->SetFont('Arial', '', 7);
			$pdf->Cell(15, 0.6, "$lin_mn[NomeCidade]", 0, 1);
			$pdf->SetX(3.76);
			# IPL 3 - INT
			$pdf->SetTextColor(14, 78, 156);
			$pdf->SetFont('Arial', 'BU', 10);
			$pdf->Cell(0.84, 0.6, "IPL 3", 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(14, 0.6, " - Distribuição do quantitativo total de acessos físicos em serviço por tipo de usuário.", 0, 1);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetX(6);
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(0.74, 0.6, "Tipo: ", 0, 0);
			$pdf->SetFont('Arial', '', 7);
			$pdf->Cell(15, 0.6, "Pessoa Física", 0, 1);
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->SetX(0.64);
			$pdf->Cell(6.18, 0.44, "Total de Acessos:", 0, 0, "R");
			$pdf->Cell(2.7, 0.4, $lin_mn[IPL3PessoaFisica], 1, 1);
			$pdf->Cell(0.2, 0.3, '', 0, 1);
			$pdf->SetX(6);
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(0.74, 0.6, "Tipo: ", 0, 0);
			$pdf->SetFont('Arial', '', 7);
			$pdf->Cell(15, 0.6, "Pessoa Jurídica", 0, 1);
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->SetX(0.64);
			$pdf->Cell(6.18, 0.44, "Total de Acessos:", 0, 0, "R");
			$pdf->Cell(2.7, 0.4, $lin_mn[IPL3PessoaJuridica], 1, 1);
			$pdf->Cell(0.2, 0.3, '', 0, 1);
			# IPL 4 - INT
			$pdf->SetX(3.76);
			$pdf->SetTextColor(14, 78, 156);
			$pdf->SetFont('Arial', 'BU', 10);
			$pdf->Cell(0.84, 0.6, "IPL 4", 0, 0);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(14, 0.6, " - Distribuição do quantitativo de acessos físicos em serviço.", 0, 1);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->Cell(0.2, 0.1, '', 0, 1);
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->SetX(1);
			$pdf->Cell(3.7, 0.44, "Tecnologia", 0, 0, "C");
			$pdf->Cell(0.3, 0.4, "", 0, 0);
			$pdf->Cell(2.59, 0.44, "Total de Acessos", 0, 0);
			$pdf->Cell(2.59, 0.44, "0 Kbps a 512 Kbps", 0, 0);
			$pdf->Cell(2.61, 0.44, "512 Kbps a 2 Mbps", 0, 0);
			$pdf->Cell(2.59, 0.44, "2 Mbps a 12 Mbps", 0, 0);
			$pdf->Cell(2.61, 0.44, "12 Mbps a 34 Mbps", 0, 0);
			$pdf->Cell(2.59, 0.44, "> 34 Mbps", 0, 1);
			$pdf->Cell(0, 0.3, "", 0, 1);
			$pdf->SetFont('Arial', '', 7);
			
			$sql_tc = "SELECT 
							SICITecnologia.IdTecnologia,
							SICITecnologia.DescricaoTecnologia
						FROM
							SICITecnologia;";
			$res_tc = mysql_query($sql_tc, $con);
			
			while($lin_tc = @mysql_fetch_array($res_tc)){
				$pdf->SetX(1);
				$pdf->Cell(3.7, 0.44, $lin_tc[DescricaoTecnologia], 0, 0, "C");
				$pdf->Cell(0.4, 0.4, "", 0, 0);
				
				$sql_dc = "SELECT 
								SICICidadeTecnologia.IPL4TotalAcessos
							FROM
								SICICidadeTecnologia
							WHERE
								SICICidadeTecnologia.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
								SICICidadeTecnologia.IdPais = '".$lin_mn[IdPais]."' AND
								SICICidadeTecnologia.IdEstado = '".$lin_mn[IdEstado]."' AND
								SICICidadeTecnologia.IdCidade = '".$lin_mn[IdCidade]."' AND
								SICICidadeTecnologia.IdTecnologia = '".$lin_tc[IdTecnologia]."';";
				$res_dc = mysql_query($sql_dc, $con);
				$lin_dc = @mysql_fetch_array($res_dc);
				
				$pdf->Cell(2.2, 0.4, $lin_dc[IPL4TotalAcessos], 1, 0);
				
				$sql_gv	 = "SELECT 
								SICICidadeTecnologiaVelocidade.IdVelocidade,
								SICICidadeTecnologiaVelocidade.IPL4
							FROM
								SICICidadeTecnologiaVelocidade
								
							WHERE
								SICICidadeTecnologiaVelocidade.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
								SICICidadeTecnologiaVelocidade.IdPais = '".$lin_mn[IdPais]."' AND
								SICICidadeTecnologiaVelocidade.IdEstado = '".$lin_mn[IdEstado]."' AND
								SICICidadeTecnologiaVelocidade.IdCidade = '".$lin_mn[IdCidade]."' AND
								SICICidadeTecnologiaVelocidade.IdTecnologia = '".$lin_tc[IdTecnologia]."';";
				$res_gv = mysql_query($sql_gv, $con);
				
				while($lin_gv = @mysql_fetch_array($res_gv)){
					$pdf->Cell(0.4, 0.4, "", 0, 0);
					
					if($lin_gv[IdVelocidade] < 5) {
						$pdf->Cell(2.2, 0.4, $lin_gv[IPL4], 1, 0);
					} else {
						$pdf->Cell(2.2, 0.4, $lin_gv[IPL4], 1, 1);
					}
				}
				
				$pdf->Cell(0, 0.4, "", 0, 1);
			}
			
			$pdf->Cell(0.2, 0.3, '', 0, 1);
			$pdf->SetX(3.76);
			# IPL 5 - FLOAT
			if($local_IdTipoApuracao == 2 || $local_IdTipoApuracao == 3){
				$pdf->SetTextColor(14, 78, 156);
				$pdf->SetFont('Arial', 'BU', 10);
				$pdf->Cell(0.84, 0.6, "IPL 5", 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(14, 0.6, " - Capacidade total do sistema implantada e em serviço (Mbps).", 0, 1);
				$pdf->SetTextColor(0, 0, 0);
				$pdf->Cell(0.2, 0.1, '', 0, 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->SetX(1);
				$pdf->Cell(3.7, 0.44, "Tecnologia", 0, 0, "C");
				$pdf->Cell(0.3, 0.4, "", 0, 0);
				$pdf->Cell(2.6, 0.44, "Capacidade total do sistema implantada e em serviço em Mbps", 0, 1);
				$pdf->Cell(0, 0.3, "", 0, 1);
				$pdf->SetFont('Arial', '', 7);
				
				$sql_tc = "SELECT 
								SICITecnologia.IdTecnologia,
								SICITecnologia.DescricaoTecnologia
							FROM
								SICITecnologia;";
				$res_tc = mysql_query($sql_tc, $con);
				
				while($lin_tc = @mysql_fetch_array($res_tc)){
					$pdf->SetX(1);
					$pdf->Cell(3.7, 0.44, $lin_tc[DescricaoTecnologia], 0, 0, "C");
					$pdf->Cell(0.4, 0.4, "", 0, 0);
					
					$sql_in = "SELECT 
									SICICidadeTecnologia.IPL5
								FROM 
									SICICidadeTecnologia 
								WHERE 
									SICICidadeTecnologia.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
									SICICidadeTecnologia.IdPais = '".$lin_mn[IdPais]."' AND
									SICICidadeTecnologia.IdEstado = '".$lin_mn[IdEstado]."' AND
									SICICidadeTecnologia.IdCidade = '".$lin_mn[IdCidade]."' AND
									SICICidadeTecnologia.IdTecnologia = '".$lin_tc[IdTecnologia]."';";
					$res_in = @mysql_query($sql_in, $con);
					$lin_in = @mysql_fetch_array($res_in);
					
					$pdf->Cell(2.6, 0.4, number_format($lin_in[IPL5], 2, ",", ""), 1, 1);
					$pdf->Cell(0, 0.4, "", 0, 1);
				}
				
				$pdf->Cell(0.2, 0.3, '', 0, 1);
				$pdf->SetX(3.76);
				# IPL 6 - FLOAT
				$pdf->SetTextColor(14, 78, 156);
				$pdf->SetFont('Arial', 'BU', 10);
				$pdf->Cell(0.84, 0.6, "IPL 6", 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(14, 0.6, " - Capacidade total do sistema instalada em Mbps.", 0, 1);
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(0.2, 0.2, '', 0, 1);
				$pdf->Cell(10.2, 0.44, "Capacidade total do sistema implantada e em serviço:", 0, 0, "R");
				$pdf->Cell(2.7, 0.4, number_format($lin_mn[IPL6], 2, ",", ""), 1, 1, "L");
				$pdf->Cell(0.2, 0.4, '', 0, 1);
				$pdf->SetX(3.76);
			}
		}
	}
	
	$pdf->Cell(0.2, 0.3, '', 0, 1);
	$pdf->SetX(0.6);
	# BUSCAR DADO GERAL DO SICI
	$sql_sici = "SELECT 
				IAU1,
				IPL1TotalKMCaboPrestadora,
				IPL1TotalKMCaboTerceiro,
				IPL1CrescimentoPrevistoKMCaboPrestadora,
				IPL1CrescimentoPrevistoKMCaboTerceiro,
				IPL2TotalKMFibraPrestadora,
				IPL2TotalKMFibraTerceiro,
				IPL2CrescimentoPrevistoKMFibraPrestadora,
				IPL2CrescimentoPrevistoKMFibraTerceiro,
				IEM1Indicador,
				IEM1ValorTotalAplicadoEquipamento,
				IEM1ValorTotalAplicadoPesquisaDesenvolvimento,
				IEM1ValorTotalAplicadoMarketing,
				IEM1ValorTotalAplicadoSoftware,
				IEM1ValorTotalAplicadoServico,
				IEM1ValorTotalAplicadoCentralAtendimento,
				IEM2ValorFaturamentoServico,
				IEM2ValorFaturamentoIndustrizalizacaoServico,
				IEM2ValorFaturamentoServicoAdicional,
				IEM3,
				IEM6,
				IEM7,
				IEM8ValorTotalCustos,
				IEM8ValorDespesaPublicidade,
				IEM8ValorDespesaInterconexao,
				IEM8ValorDespesaOperacaoManutencao,
				IEM8ValorDespesaVenda
			FROM
				SICI
			WHERE
				PeriodoApuracao = '".$local_PeriodoApuracao."';";
	$res_sici = @mysql_query($sql_sici,$con);
	$lin_sici = @mysql_fetch_array($res_sici);
	
	if($local_IdTipoApuracao == 3){
		# TIPO APURAÇÃO ANUAL
		# IAU 1 - STRING
		$pdf->SetTextColor(14, 78, 156);
		$pdf->SetFont('Arial', 'BU', 10);
		$pdf->Cell(0.84, 0.6, "IAU 1", 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(14, 0.6, " - Prestação do Serviço de Comunicação Multimídia.", 0, 1);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(0.2, 0.3, '', 0, 1);
		$pdf->SetX(1.07);
		$pdf->Cell(6, 0.4, "Número do Centro de Atendimento Telefônico:", 0, 0, "R");
		$pdf->Cell(6.44, 0.4, $lin_sici[IAU1], 1, 1);
		$pdf->SetX(1.07);
		$pdf->Cell(0.2, 0.6, '', 0, 1);
		$pdf->SetTextColor(15, 85, 169);
		$pdf->Cell(17.46, 0.44, 'Indicadores', 0, 1, 'L');
		$pdf->Cell(0.2, 0.3, '', 0, 1);
		# IPL 1 - FLOAT
		$pdf->SetTextColor(14, 78, 156);
		$pdf->SetFont('Arial', 'BU', 10);
		$pdf->Cell(0.84, 0.6, "IPL 1", 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(14, 0.6, " - Rede de Fibra Óptica (Quantidade de Cabos).", 0, 1);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(2, 0.3, '', 0, 1);
		$pdf->Cell(6.8, 0.4, "Total em KM de cabo da Prestadora:", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IPL1TotalKMCaboPrestadora], 2, ",", ""), 1, 0);
		$pdf->Cell(7.5, 0.4, "Crescimento previsto em KM do cabo da Prestadora:", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IPL1CrescimentoPrevistoKMCaboPrestadora], 2, ",", ""), 1, 1);
		$pdf->Cell(2, 0.4, '', 0, 1);
		$pdf->Cell(6.8, 0.4, "Total em KM de cabo de Terceiros:", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IPL1TotalKMCaboTerceiro], 2, ",", ""), 1, 0);
		$pdf->Cell(7.5, 0.4, "Crescimento previsto em KM do cabo de Terceiros:", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IPL1CrescimentoPrevistoKMCaboTerceiro], 2, ",", ""), 1, 1);
		$pdf->Cell(0.2, 0.6, '', 0, 1);
		# IPL 2 - FLOAT
		$pdf->SetTextColor(14, 78, 156);
		$pdf->SetFont('Arial', 'BU', 10);
		$pdf->Cell(0.84, 0.6, "IPL 2", 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(14, 0.6, " - Rede de Fibra Óptica (Quantidade de Fibras).", 0, 1);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(2, 0.3, '', 0, 1);
		$pdf->Cell(6.8, 0.4, "Total em KM de fibra implantada pela Prestadora:", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IPL2TotalKMFibraPrestadora], 2, ",", ""), 1, 0);
		$pdf->Cell(7.5, 0.4, "Crescimento previsto em KM de fibra da Prestadora:", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IPL2CrescimentoPrevistoKMFibraPrestadora], 2, ",", ""), 1, 1);
		$pdf->Cell(2, 0.4, '', 0, 1);
		$pdf->Cell(6.8, 0.4, "Total em KM de fibra implantada por Terceiros:", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IPL2TotalKMFibraTerceiro], 2, ",", ""), 1, 0);
		$pdf->Cell(7.5, 0.4, "Crescimento previsto em KM de fibra de Terceiros:", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IPL2CrescimentoPrevistoKMFibraTerceiro], 2, ",", ""), 1, 1);
		$pdf->Cell(0.2, 0.6, '', 0, 1);
		# IEM 1 - FLOAT
		$pdf->SetTextColor(14, 78, 156);
		$pdf->SetFont('Arial', 'BU', 10);
		$pdf->Cell(0.9, 0.6, "IEM 1", 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(14, 0.6, " - Investimento na Planta.", 0, 1);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(2, 0.3, '', 0, 1);
		$pdf->Cell(6.8, 0.4, "Indicador ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM1Indicador], 2, ",", ""), 1, 0);
		$pdf->Cell(7.5, 0.4, "Valor total aplicado em Marketing/Propaganda ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM1ValorTotalAplicadoMarketing], 2, ",", ""), 1, 1);
		$pdf->Cell(2, 0.4, '', 0, 1);
		$pdf->Cell(6.8, 0.4, "Valor total aplicado em equipamentos ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM1ValorTotalAplicadoEquipamento], 2, ",", ""), 1, 0);
		$pdf->Cell(7.5, 0.4, "Valor total aplicado em software ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM1ValorTotalAplicadoSoftware], 2, ",", ""), 1, 1);
		$pdf->Cell(2, 0.4, '', 0, 1);
		$pdf->Cell(6.8, 0.4, "Valor total aplicado em Pesquisa e Desenv. ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM1ValorTotalAplicadoPesquisaDesenvolvimento], 2, ",", ""), 1, 0);
		$pdf->Cell(7.5, 0.4, "Valor total aplicado em serviços ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM1ValorTotalAplicadoServico], 2, ",", ""), 1, 1);
		$pdf->Cell(2, 0.4, '', 0, 1);
		$pdf->Cell(17, 0.4, "Valor total aplicado em Call-Center ou qualquer tipo de central de atendimento ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM1ValorTotalAplicadoCentralAtendimento], 2, ",", ""), 1, 1);
		$pdf->Cell(0.2, 0.6, '', 0, 1);
		# IEM 2 - FLOAT
		$pdf->SetTextColor(14, 78, 156);
		$pdf->SetFont('Arial', 'BU', 10);
		$pdf->Cell(0.9, 0.6, "IEM 2", 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(14, 0.6, " - Faturamento com a Prestação do Serviço.", 0, 1);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(2, 0.3, '', 0, 1);
		$pdf->Cell(6.8, 0.4, "Faturamento com prestação do serviço ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM2ValorFaturamentoServico], 2, ",", ""), 1, 0);
		$pdf->Cell(7.5, 0.4, "Faturamento com exploração ind. de serviços ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM2ValorFaturamentoIndustrizalizacaoServico], 2, ",", ""), 1, 1);
		$pdf->Cell(2, 0.4, '', 0, 1);
		$pdf->Cell(17, 0.4, "Faturamento com provimento de serviços de valor adicionado ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM2ValorFaturamentoServicoAdicional], 2, ",", ""), 1, 1);
		$pdf->Cell(0.2, 0.6, '', 0, 1);
	}
	
	if($local_IdTipoApuracao == 2 || $local_IdTipoApuracao == 3){
		# IEM 3 - FLOAT
		$pdf->SetTextColor(14, 78, 156);
		$pdf->SetFont('Arial', 'BU', 10);
		$pdf->Cell(0.9, 0.6, "IEM 3", 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(14, 0.6, " - Investimentos realizados.", 0, 1);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(2, 0.3, '', 0, 1);
		$pdf->Cell(6.8, 0.4, "Valor consolidado do investimento realizado ".$Moeda.":", 0, 0, "R");
		$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM3], 2, ",", ""), 1, 1);
		$pdf->Cell(0.2, 0.6, '', 0, 1);
	}
	# IEM 6 - FLOAT
	$pdf->SetTextColor(14, 78, 156);
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(0.9, 0.6, "IEM 6", 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(14, 0.6, " - Receita Operacional Bruta auferida no período.", 0, 1);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(2, 0.3, '', 0, 1);
	$pdf->Cell(6.8, 0.4, "Receita operacional bruta total ".$Moeda.":", 0, 0, "R");
	$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM6] , 2, ',', ''), 1, 1);
	$pdf->Cell(0.2, 0.6, '', 0, 1);
	# IEM 7 - FLOAT
	$pdf->SetTextColor(14, 78, 156);
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(0.9, 0.6, "IEM 7", 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(14, 0.6, " - Receita Operacional Líquida auferida no período.", 0, 1);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(2, 0.3, '', 0, 1);
	$pdf->Cell(6.8, 0.4, "Receita operacional líquida total ".$Moeda.":", 0, 0, "R");
	$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM7], 2, ",", ""), 1, 1);
	$pdf->Cell(0.2, 0.6, '', 0, 1);
	# IEM 8 - FLOAT
	$pdf->SetTextColor(14, 78, 156);
	$pdf->SetFont('Arial', 'BU', 10);
	$pdf->Cell(0.9, 0.6, "IEM 8", 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(14, 0.6, " - Despesas envolvendo operação e manutenção, publicidade e vendas e interconexão.", 0, 1);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(2, 0.3, '', 0, 1);
	$pdf->Cell(6.8, 0.4, "Valor total dos custos ".$Moeda.":", 0, 0, "R");
	$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM8ValorTotalCustos], 2, ",", ""), 1, 0);
	$pdf->Cell(7.5, 0.4, "Despesas envolvendo operação e manutenção ".$Moeda.":", 0, 0, "R");
	$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM8ValorDespesaOperacaoManutencao], 2, ",", ""), 1, 1);
	$pdf->Cell(2, 0.4, '', 0, 1);
	$pdf->Cell(6.8, 0.4, "Despesas envolvendo publicidade ".$Moeda.":", 0, 0, "R");
	$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM8ValorDespesaPublicidade], 2, ",", ""), 1, 0);
	$pdf->Cell(7.5, 0.4, "Despesas envolvendo vendas ".$Moeda.":", 0, 0, "R");
	$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM8ValorDespesaVenda], 2, ",", ""), 1, 1);
	$pdf->Cell(2, 0.4, '', 0, 1);
	$pdf->Cell(6.8, 0.4, "Despesas envolvendo interconexão ".$Moeda.":", 0, 0, "R");
	$pdf->Cell(2.7, 0.4, number_format($lin_sici[IEM8ValorDespesaInterconexao], 2, ",", ""), 1, 1);
	$pdf->Cell(0.2, 0.9, '', 0, 1);
	# LN
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(0, 0.6, "Informações de acordo com o \"MANUAL DO SISTEMA DE COLETA DE INFORMAÇÕES – SICI – Edição Janeiro/2012\", disponível em www.anatel.gov.br.", "T");
	# SAIDA
	$NomePDF = "SICI_".$local_PeriodoApuracao.".pdf";
	
	if((int)$local_IdMetodoExportacao == 2){
		$FileName = "temp/".$NomePDF;
		$Anexo = $Path.$FileName;
		
		@unlink($Anexo);
		
		$pdf->Output($Anexo, "F"); # SALBAR O ARQUIVO NO LOCAL 'F' #
	} else{
		$pdf->Output($NomePDF, "D"); # DOWNLOAD DO ARQUIVO 'D' #
	}
?>