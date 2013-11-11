<?
	# ARQUIVO DESTINATARIO / CADASTRO
	$Linha	= null;
	$iLinha = 0;

	$sqlNF = "select
				NotaFiscal.IdNotaFiscal,
				ContaReceber.IdContaReceber,
				Pessoa.CPF_CNPJ,
				Pessoa.TipoPessoa,
				Pessoa.RG_IE,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				PessoaEndereco.Endereco,
				PessoaEndereco.Numero,
				PessoaEndereco.Complemento,
				PessoaEndereco.CEP,
				PessoaEndereco.Bairro,
				Cidade.NomeCidade,
				Estado.SiglaEstado,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Telefone3,
				Pessoa.Celular
			from
				NotaFiscal,
				NotaFiscalTipo,
				ContaReceber,
				Pessoa,
				PessoaEndereco,
				Estado,
				Cidade
			where
				(
					NotaFiscalTipo.IdLoja = $local_IdLoja or
					NotaFiscalTipo.IdLojaCompartilhada = $local_IdLoja

				) and	
				NotaFiscal.IdLoja = NotaFiscalTipo.IdLoja AND
				NotaFiscal.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND
				NotaFiscal.IdLoja = ContaReceber.IdLoja and
				NotaFiscal.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
				NotaFiscal.PeriodoApuracao = '$local_PeriodoApuracao' and
				NotaFiscal.IdContaReceber = ContaReceber.IdContaReceber and
				ContaReceber.IdPessoa = Pessoa.IdPessoa and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				ContaReceber.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Estado.IdPais and
				PessoaEndereco.IdPais = Cidade.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade
			order by
				NotaFiscal.IdNotaFiscal";
	$resNF = mysql_query($sqlNF,$con);
	while($NF = mysql_fetch_array($resNF)){

		if($NF[Telefone1] != '' && $NF[Telefone] == ''){
			$NF[Telefone] = $NF[Telefone1];
		}

		if($NF[Telefone2] != '' && $NF[Telefone] == ''){
			$NF[Telefone] = $NF[Telefone2];
		}

		if($NF[Telefone3] != '' && $NF[Telefone] == ''){
			$NF[Telefone] = $NF[Telefone3];
		}

		if($NF[Celular] != '' && $NF[Telefone] == ''){
			$NF[Telefone] = $NF[Celular];
		}

		if($NF[TipoPessoa] == 2){
			$NF[RazaoSocial] = $NF[Nome];
		}

		$Campo	= null;

		// Remessas de até 06/2012
		if(dataConv($local_PeriodoApuracao,"Y-m","Ym") <= 201206){
			include("confirmar_nf_2_via_eletronica_remessa_destinatario_001.php");
		}

		// Remessas de até 07/2012
		if(dataConv($local_PeriodoApuracao,"Y-m","Ym") >= 201207){
			include("confirmar_nf_2_via_eletronica_remessa_destinatario_002.php");
		}

		// Gera a Linha
		$Linha[$iLinha] = concatVar($Campo);
		$iLinha++;
	}

	$String = '';

	for($i=0; $i<count($Linha); $i++){
		$String .= $Linha[$i]."\r\n";
	}

	$FileName = $PatchFile."/".$Processo[NomeArquivoDestinatario];

	@unlink($FileName);

	if($File = fopen($FileName, 'a')) {
		if(fwrite($File, $String)){
			
			$FileSize = filesize($FileName)/1024;
			$FileSize = number_format($FileSize, 2, '.', '');

			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Arquivo Destinatário (".count($Linha)." registros) $FileSize KB.";
		}else{			
			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Geração do Arquivo Destinatário.";
		}
    }else{
		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Geração do Arquivo Destinatário.";
	}
    fclose($File);

	$sql = "update NotaFiscal2ViaEletronicaArquivo set 
				CodigoAutenticacaoDigitalArquivoDestinatario = '".md5($String)."',
				ConteudoArquivoDestinatario = '$String',
				LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento)
			where 
				IdLoja='$local_IdLoja' and 
				IdNotaFiscalLayout='$local_IdNotaFiscalLayout' and 
				MesReferencia='$local_MesReferencia' and
				Status = '$local_IdStatusArquivoMestre'";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
?>