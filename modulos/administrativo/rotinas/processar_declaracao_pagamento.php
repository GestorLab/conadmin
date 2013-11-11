<?php
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	
	if($_GET['Local'] == "CDA") {
		session_cache_expire (720);
		session_start("ConAdmin_session_cda");
		
		$local_IdLoja	= $_SESSION["IdLojaCDA"];
		$local_IdPessoa	= $_SESSION["IdPessoaCDA"];
	} else {
		$local_Modulo			= 1;
		$local_Operacao			= 120;
		$local_Suboperacao		= "V";
		
		include ('../../../rotinas/verifica.php');
		
		$local_IdLoja			= $_SESSION["IdLoja"];
		$local_IdPessoa			= $_GET['IdPessoa'];
	}
	
	ini_set("memory_limit",getParametroSistema(138, 1));
	
	$local_AnoReferencia	= $_GET['AnoReferencia'];
	$local_IdTipoPessoa		= $_GET['IdTipoPessoa'];
	$local_IdStatusContrato	= $_GET['IdStatusContrato'];
	
	$sql = "
		select 
			Pessoa.IdPessoa,
			Pessoa.Nome
		from
			Loja,
			Pessoa
		where
			Loja.IdLoja=$local_IdLoja and
			Loja.IdPessoa=Pessoa.IdPessoa;
	";
	$res = @mysql_query($sql,$con);
	if($lin = @mysql_fetch_array($res)){
		$local_NomeProprietario		= $lin["Nome"];
		$local_IdPessoaProprietario	= $lin["IdPessoa"];
	}
	
	$sql = "select 
				Pessoa.IdPessoa, 
				Pessoa.TipoPessoa,
				Pessoa.RazaoSocial,
				Pessoa.CPF_CNPJ,
				PessoaEndereco.Endereco, 
				PessoaEndereco.Numero, 
				PessoaEndereco.Complemento, 
				PessoaEndereco.Bairro,
				PessoaEndereco.CEP,
				Cidade.NomeCidade, 
				Estado.SiglaEstado,
				Pessoa.Telefone1, 
				Pessoa.Telefone2
			from 
				Pessoa,
				PessoaEndereco,
				Cidade, 
				Estado 
			where 
				Pessoa.IdPessoa = '$local_IdPessoaProprietario' and 
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				Cidade.IdPais = PessoaEndereco.IdPais and 
				Cidade.IdEstado = PessoaEndereco.IdEstado and 
				Cidade.IdCidade = PessoaEndereco.IdCidade and 
				Cidade.IdPais = Estado.IdPais and 
				Cidade.IdEstado = Estado.IdEstado";
	$res = mysql_query($sql,$con);
	$linDadosEmpresa = mysql_fetch_array($res);
	
	if($linDadosEmpresa[Telefone1] != ''){
		$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone1];
	}else{
		$linDadosEmpresa[Telefone] = $linDadosEmpresa[Telefone2];
	}
	
	if($linDadosEmpresa["TipoPessoa"] == 1){
		$CPF_CNPJ = "CNPJ";
	}else{			
		$CPF_CNPJ = "CPF";
	}
	
#	SEUS DADOS / Dados da Sua empresa
	$dadosboleto["endereco"]	= $linDadosEmpresa[Endereco].", ".$linDadosEmpresa[Numero];

	if($linDadosEmpresa[Complemento] != ''){
		$dadosboleto["endereco"] .= " - ".$linDadosEmpresa[Complemento];
	}
	
	$dadosboleto["endereco"]   .= " - ".$linDadosEmpresa[Bairro];
	
	$dadosboleto["cidade"]		= $linDadosEmpresa[NomeCidade]."-".$linDadosEmpresa[SiglaEstado]." - Cep: ".$linDadosEmpresa[CEP];
	$dadosboleto["cedente"]		= substr($linDadosEmpresa[RazaoSocial],0,65);
	$dadosboleto["cpf_cnpj_cedente"] 	= $linDadosEmpresa[CPF_CNPJ];
	
	if($linDadosEmpresa[Telefone] != ''){
		$dadosboleto["fone"] 	= " - Fone / Fax: ".$linDadosEmpresa[Telefone];
	}
	
	define('FPDF_FONTPATH','../../../classes/fpdf/font/');
	require("../../../classes/fpdf/class.fpdf.php");
	
	$pdf		= new FPDF('P','cm','A4');
	$sqlAux		= '';
	$where		= '';
	
	if($local_IdStatusContrato != ""){
		$sqlAux	.= ",
						LancamentoFinanceiroContaReceber,
						LancamentoFinanceiro,
						Contrato
					where
						DeclaracaoPagamento.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
						DeclaracaoPagamento.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
						LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
						LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
						LancamentoFinanceiro.IdLoja = Contrato.IdLoja and
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
						Contrato.IdStatus in ($local_IdStatusContrato)";
	}
	
	if($local_IdPessoa != ""){
		$where	.= " and Pessoa.IdPessoa in ($local_IdPessoa)";
	}
	
	if($local_IdTipoPessoa != ""){
		$where	.= " and Pessoa.TipoPessoa = $local_IdTipoPessoa";
	}
	
	$sql = "
		select 
			Pessoa.IdPessoa,
			Pessoa.Nome,
			Pessoa.TipoPessoa,
			Pessoa.CPF_CNPJ
		from
			Pessoa 
		where
			1 
			$where;
	";
	$res = @mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
		$ValorMes		= Array("0,00", "0,00", "0,00", "0,00", "0,00", "0,00", "0,00", "0,00", "0,00", "0,00", "0,00", "0,00");
		$DescricaoMes	= Array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
		$regEncontrado	= 0;
		
#		PEGA E ESCREVER O VALOR CORRESPONDENTE DE CADA MÊS
		$sql1 = "
			select
				DeclaracaoPagamento.IdLoja,
				DeclaracaoPagamento.IdContaReceber,
				DeclaracaoPagamento.Mes,
				DeclaracaoPagamento.Valor,
				DeclaracaoPagamento.DataVencimento
			from
				(select
					ReceberRecebimento.IdLoja,
					ReceberRecebimento.IdContaReceber,
					ReceberRecebimento.Mes,
					sum(ReceberRecebimento.ValorRecebido) Valor,
					ContaReceber.DataVencimento
				 from
					ContaReceber,
					(select 
						ContaReceber.IdLoja,
						ContaReceberRecebimento.IdContaReceber,
						ContaReceberRecebimento.ValorRecebido,
						substring(ContaReceberRecebimento.DataRecebimento, 6, 2) Mes
					 from
						ContaReceber,
						ContaReceberRecebimento
					 where
						ContaReceber.IdLoja = $local_IdLoja and
						ContaReceber.IdPessoa = $lin[IdPessoa] and
						ContaReceber.IdLoja = ContaReceberRecebimento.IdLoja and
						ContaReceber.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
						ContaReceberRecebimento.IdStatus = 1 and
						substring(ContaReceberRecebimento.DataRecebimento, 1, 4) = '$local_AnoReferencia'
					) ReceberRecebimento
				 where
					ContaReceber.IdLoja = ReceberRecebimento.IdLoja and
					ContaReceber.IdContaReceber = ReceberRecebimento.IdContaReceber
					group by
					ReceberRecebimento.Mes
				) DeclaracaoPagamento
				$sqlAux
			group by
				DeclaracaoPagamento.Mes;
		";
		$res1 = @mysql_query($sql1,$con);
		while($lin1 = @mysql_fetch_array($res1)){
			$ValorMes[$lin1["Mes"]-1] = str_replace(".",",",$lin1["Valor"]);
			$regEncontrado++;
		}
		$url_logo = "../../../img/personalizacao/logo_cab.gif";
		if($_GET['Local'] != "CDA") {
		
			$sqlLogoLoja = "select
								IdLoja,
								LogoPersonalizada 
							from
								Loja 
							where
								IdLoja = $local_IdLoja";
			$resLogoLoja = mysql_query($sqlLogoLoja,$con);
			$linLogoLoja = mysql_fetch_array($resLogoLoja);
			if($linLogoLoja[LogoPersonalizada] == 1){
				$url_logo = "../../../img/personalizacao/".$linLogoLoja[LogoPersonalizada]."/logo_cab.gif";
			}else{
				$url_logo = "../../../img/personalizacao/logo_cab.gif";
			}
		}
		if($regEncontrado > 0){
			$pdf->SetMargins(1.64, 1.5, 1.64);
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(17.46, 0.44, 'Declaração de pagamentos', 0, 1, 'R');
			$pdf->SetFont('Arial','',11);
			$pdf->MultiCell(17.46, 0.44, "Pessoa ".getParametroSistema(1,$lin["TipoPessoa"]), 0, 'R', false);
			$pdf->MultiCell(17.46, 0.8, '', 0, '', false);
			
#			PEGANDO O X E Y DA FOTO E CONVERTENDO PARA O TAMANHO 'CM'
			list($width, $height) = getimagesize($url_logo);
			$pdf->Image($url_logo, 1.66, 1.6, $whidth*2.54/100, $height*2.54/100, 'gif');
		
			$pdf->Cell(12.26, 0.2, '', 0, 0);
			$pdf->Cell(0.001, 0.2, '', 1, 0);
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(5.2, 0.2, 'Ano referência', 0, 1);
			$pdf->Cell(12.26, 0.2, '', 0, 0);
			$pdf->Cell(0.001, 0.2, '', 1, 1);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(12.26, 0.5, '', 0, 0);
			$pdf->Cell(0.001, 0.5, '', 1, 0);
			$pdf->Cell(5.2, 0.5, $local_AnoReferencia, 0, 1, 'C');
			$pdf->Cell(12.26, 0.01, '', 0, 0);
			$pdf->Cell(5.2, 0.01, '', 1, 0);
			$pdf->MultiCell(17.46, 0.6, '', 0, '', false);
			
			$pdf->Cell(12.26, 0.2, '', 0, 0);
			$pdf->Cell(0.001, 0.2, '', 1, 0);
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(5.2, 0.2, 'Data Emissão', 0, 1);
			$pdf->Cell(12.26, 0.2, '', 0, 0);
			$pdf->Cell(0.001, 0.2, '', 1, 1);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(12.26, 0.5, '', 0, 0);
			$pdf->Cell(0.001, 0.5, '', 1, 0);
			$pdf->Cell(5.2, 0.5, date('d/m/Y'), 0, 1, 'C');
			$pdf->Cell(12.26, 0.01, '', 0, 0);
			$pdf->Cell(5.2, 0.01, '', 1, 0);
			$pdf->MultiCell(17.46, 0.6, '', 0, '', false);
			
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(0.001, 0.2, '', 1, 0);
			$pdf->Cell(12.26, 0.2, 'Cliente', 0, 0);
			$pdf->Cell(0.001, 0.2, '', 1, 0);
			$pdf->Cell(5.2, 0.2, 'CPF / CNPJ nº', 0, 1);
			$pdf->Cell(0.001, 0.2, '', 1, 0);
			$pdf->Cell(12.26, 0.2, '', 0, 0);
			$pdf->Cell(0.001, 0.2, '', 1, 1);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(0.001, 0.5, '', 1, 0);
			$pdf->Cell(12.26, 0.5, $lin["Nome"], 0, 0);
			$pdf->Cell(0.001, 0.5, '', 1, 0);
			$pdf->Cell(5.2, 0.5, $lin["CPF_CNPJ"], 0, 1, 'C');
			$pdf->Cell(17.46, 0.001, '', 1, 1);
			
			$pdf->MultiCell(17.46, 0.6, '', 0, '', false);
			
			$pdf->SetFont('Arial','',11);
			$pdf->MultiCell(17.46, 2.0, 'Prezado cliente,', 0, 'J', false);
			$pdf->MultiCell(17.46, 0.5, 'Em cumprimento ao disposto na Lei nº 12.007, declaramos que no ano de '.$local_AnoReferencia.' foram efetuadas os seguintes pagamentos, em relação aos quais damos plena quitação:', 0, 'J', false);
			$pdf->MultiCell(17.46, 0.4, '', 0, '', false);
			
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.6, 'Mês Pagamento', 0, 0);
			$pdf->Cell(0.1, 0.6, 'Valor Pago', 0, 1);
			$pdf->SetFont('Arial','',11);
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[0], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[0], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[1], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[1], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[2], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[2], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[3], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[3], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[4], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[4], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[5], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[5], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[6], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[6], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[7], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[7], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[8], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[8], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[9], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[9], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[10], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[10], 0, 1, 'L');
			
			$pdf->Cell(3, 0.6, '', 0, 0);
			$pdf->Cell(7.8, 0.5, $DescricaoMes[11], 0, 0);
			$pdf->Cell(0.7, 0.5, 'R$', 0, 0);
			$pdf->Cell(3.2, 0.5, $ValorMes[11], 0, 1, 'L');
			
			$pdf->MultiCell(17.46, 0.4, '', 0, '', false);
			$pdf->MultiCell(17.46, 0.5, 'Nos termos do art. 4º da mencionada Lei, informamos que a presente declaração de quitação substitui os comprovantes de pagamentos dos valores que foram efetivamente lançados nas respectivas faturas, inclusive aquelas relativos a anos anteriores, os quais consideram os quitados até a data de vencimento da fatura de dezembro de '.$local_AnoReferencia.'.', 0, 'J', false);
			
			$pdf->MultiCell(17.46, 1, '', 0, '', false);
			$pdf->MultiCell(17.46, 0.5, 'Atenciosamente,', 0, 'J', false);
			$pdf->MultiCell(17.46, 0.4, '', 0, '', false);
			
			$pdf->MultiCell(17.46, 0.5, $local_NomeProprietario, 0, 'J', false);
			
			$pdf->MultiCell(17.46, 2.6, '', 0, '', false);
			$pdf->SetFont('Arial','B',11);
			
			$pdf->MultiCell(17.46, 0.5, "$dadosboleto[cedente]\n$dadosboleto[endereco] - $dadosboleto[cidade]\n$CPF_CNPJ: $dadosboleto[cpf_cnpj_cedente]".$dadosboleto[fone], 0, 'R', false);
		}
	}
	
	$pdf->Output("Declaracao_Pagamento_".date("Y-m-d_H-i-s").".pdf", "D");
?>
