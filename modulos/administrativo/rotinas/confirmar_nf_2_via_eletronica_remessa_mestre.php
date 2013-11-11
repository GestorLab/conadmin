<?
	# ARQUIVO MESTRE
	$Linha	= null;
	$iLinha = 0;

	$sqlNF = "select
				NotaFiscal.IdNotaFiscal,
				NotaFiscal.IdContaReceber,
				Pessoa.TipoPessoa,
				Pessoa.CPF_CNPJ,
				Pessoa.RG_IE,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				Estado.SiglaEstado,
				NotaFiscal.TipoAssinante,
				NotaFiscal.TipoUtilizacao,
				NotaFiscal.DataEmissao,
				NotaFiscal.Modelo,
				NotaFiscal.Serie,
				NotaFiscal.CodigoAutenticacaoDocumento,
				NotaFiscal.ValorTotal,
				NotaFiscal.ValorBaseCalculoICMS,
				NotaFiscal.ValorICMS,
				NotaFiscal.ValorNaoTributado,
				NotaFiscal.ValorOutros,
				NotaFiscal.PeriodoApuracao,
				NotaFiscal.IdStatus
			from
				NotaFiscal,
				NotaFiscalTipo,
				ContaReceber,
				Pessoa,
				PessoaEndereco,
				Estado
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
				NotaFiscal.IdPais = Estado.IdPais and
				NotaFiscal.IdEstado = Estado.IdEstado
			order by
				NotaFiscal.IdNotaFiscal";
	$resNF = mysql_query($sqlNF,$con);
	while($NF = mysql_fetch_array($resNF)){

		if($NF[TipoPessoa] == 2){
			$NF[RazaoSocial] = $NF[Nome];
		}

		$Campo	= null;

		// Remessas de até 06/2012
		if(dataConv($local_PeriodoApuracao,"Y-m","Ym") <= 201206){
			include("confirmar_nf_2_via_eletronica_remessa_mestre_001.php");
		}

		// Remessas de até 07/2012
		if(dataConv($local_PeriodoApuracao,"Y-m","Ym") >= 201207){
			include("confirmar_nf_2_via_eletronica_remessa_mestre_002.php");
		}

		// Gera a Linha
		$Linha[$iLinha] = concatVar($Campo);
		$iLinha++;
	}

	$String = '';

	for($i=0; $i<count($Linha); $i++){
		$String .= $Linha[$i]."\r\n";
	}

	$FileName = $PatchFile."/".$Processo[NomeArquivoMestre];

	@unlink($FileName);

	if($File = fopen($FileName, 'a')) {
		if(fwrite($File, $String)){
			
			$FileSize = filesize($FileName)/1024;
			$FileSize = number_format($FileSize, 2, '.', '');

			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Arquivo Mestre (".count($Linha)." registros) $FileSize KB.";
		}else{			
			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Geração do Arquivo Mestre.";
		}
    }else{
		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Geração do Arquivo Mestre.";
	}
    fclose($File);

	$sql = "update NotaFiscal2ViaEletronicaArquivo set 
				CodigoAutenticacaoDigitalArquivoMestre = '".md5($String)."',
				ConteudoArquivoMestre = '$String',
				LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento)
			where 
				IdLoja='$local_IdLoja' and 
				IdNotaFiscalLayout='$local_IdNotaFiscalLayout' and 
				MesReferencia='$local_MesReferencia' and
				Status = '$local_IdStatusArquivoMestre'";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
?>