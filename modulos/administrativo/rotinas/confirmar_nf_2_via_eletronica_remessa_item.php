<?
	# ARQUIVO ITEM
	$Linha			= null;
	$iLinha			= 0;
	$PosNFItemIni	= null;

	$sqlNFItem = "select 
						NotaFiscal.IdNotaFiscal, 
						NotaFiscalItem.IdNotaFiscalItem,
						NotaFiscalItem.IdLancamentoFinanceiro,
						Pessoa.CPF_CNPJ, 
						Estado.SiglaEstado, 
						NotaFiscal.TipoAssinante, 
						NotaFiscal.TipoUtilizacao, 
						NotaFiscal.DataEmissao, 
						NotaFiscal.Modelo, 
						NotaFiscal.Serie, 
						NotaFiscalItem.CFOP, 
						NotaFiscalItem.IdClassificacaoItem, 
						NotaFiscalItem.ValorTotal, 
						NotaFiscalItem.ValorDesconto, 
						NotaFiscalItem.ValorBaseCalculoICMS, 
						NotaFiscalItem.ValorICMS, 
						NotaFiscalItem.ValorNaoTributado, 
						NotaFiscalItem.ValorOutros, 
						NotaFiscalItem.AliquotaICMS, 
						NotaFiscalItem.PeriodoApuracao, 
						NotaFiscalItem.IdStatus 
					from 
						NotaFiscal, 
						NotaFiscalItem,
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
						NotaFiscal.IdLoja = NotaFiscalItem.IdLoja and 
						NotaFiscal.IdLoja = ContaReceber.IdLoja and 
						NotaFiscal.IdContaReceber = ContaReceber.IdContaReceber and 
						NotaFiscal.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and 
						NotaFiscal.IdNotaFiscalLayout = NotaFiscalItem.IdNotaFiscalLayout and 
						NotaFiscal.PeriodoApuracao = '$local_PeriodoApuracao' and 
						NotaFiscal.PeriodoApuracao = NotaFiscalItem.PeriodoApuracao and 
						NotaFiscal.IdNotaFiscal = NotaFiscalItem.IdNotaFiscal and
						ContaReceber.IdPessoa = Pessoa.IdPessoa and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and 
						ContaReceber.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
						PessoaEndereco.IdPais = Estado.IdPais and 
						PessoaEndereco.IdEstado = Estado.IdEstado
					order by 
						NotaFiscal.IdNotaFiscal, 
						NotaFiscalItem.IdNotaFiscalItem";
	$resNFItem = mysql_query($sqlNFItem,$con);
	while($NFItem = mysql_fetch_array($resNFItem)){

		$sqlDemonstrativo = "select 
								Demonstrativo.Tipo,
								Demonstrativo.Codigo, 
								Demonstrativo.Descricao, 
								Demonstrativo.Unidade, 
								Demonstrativo.DataReferenciaInicial, 
								Demonstrativo.DataReferenciaFinal
							from
								Demonstrativo
							where
								IdLoja = $local_IdLoja and
								IdLancamentoFinanceiro = $NFItem[IdLancamentoFinanceiro]";
		$resDemonstrativo = @mysql_query($sqlDemonstrativo,$con);
		$linDemonstrativo = @mysql_fetch_array($resDemonstrativo);

		$NFItem[Tipo]					= $linDemonstrativo[Tipo];
		$NFItem[Codigo]					= $linDemonstrativo[Codigo];
		$NFItem[Descricao]				= $linDemonstrativo[Descricao];
		$NFItem[Unidade]				= $linDemonstrativo[Unidade];
		$NFItem[DataReferenciaInicial]	= $linDemonstrativo[DataReferenciaInicial];
		$NFItem[DataReferenciaFinal]	= $linDemonstrativo[DataReferenciaFinal];

		// Para quando o desconto é maior do que o valor...
#		if($NFCredito[$NFItem[IdNotaFiscal]] < 0){
#			$NFItem[ValorDesconto] += ($NFCredito[$NFItem[IdNotaFiscal]]*-1);
#			$NFCredito[$NFItem[IdNotaFiscal]] = 0;
#		}
#		if(($NFItem[ValorTotal]-$NFItem[ValorDesconto]) < 0){
#			$NFCredito[$NFItem[IdNotaFiscal]] = $NFItem[ValorTotal]-$NFItem[ValorDesconto];
#			$NFItem[ValorDesconto] = $NFItem[ValorTotal];
#		}
#		if($NFCreditoBaseCalculoICMS[$NFItem[IdNotaFiscal]] < 0){
#			$NFItem[ValorBaseCalculoICMS] += $NFCreditoBaseCalculoICMS[$NFItem[IdNotaFiscal]];
#			$NFCreditoBaseCalculoICMS[$NFItem[IdNotaFiscal]] = 0;
#		}
#		if($NFItem[ValorBaseCalculoICMS] < 0){
#			$NFCreditoBaseCalculoICMS[$NFItem[IdNotaFiscal]] = $NFItem[ValorBaseCalculoICMS];
#			$NFItem[ValorBaseCalculoICMS] = 0;
#		}

		$Campo	= null;

		$PosNFItemIni[$NFItem[IdNotaFiscal]][$NFItem[IdNotaFiscalItem]] = $iLinha;


		if($NFItem[CPF_CNPJ] == '' && $NFItem[IdNotaFiscalItem] > 1){
			$NFItem[CPF_CNPJ]		= $PosNFItemIni[$NFItem[IdNotaFiscal]][CPF_CNPJ];
			$NFItem[SiglaEstado]	= $PosNFItemIni[$NFItem[IdNotaFiscal]][SiglaEstado];
		}else{
			$PosNFItemIni[$NFItem[IdNotaFiscal]][CPF_CNPJ]		= $NFItem[CPF_CNPJ];
			$PosNFItemIni[$NFItem[IdNotaFiscal]][SiglaEstado]	= $NFItem[SiglaEstado];
		}

		if($NFItem[Tipo] == ''){
			$NFItem[Tipo]		= 'DESP';
			$NFItem[Descricao]	= getParametroSistema(5, 3);
			$NFItem[Unidade]	= 5;
		}

		// Campo 1 (CNPJ) [14] 1-14 N
		$Campo[1] = formata_CPF_CNPJ($NFItem[CPF_CNPJ]);

		// Campo 2 (UF) [2] 15-16 X
		$Campo[2] = preenche_tam($NFItem[SiglaEstado], 2, 'X');

		// Campo 3 (Tipo de Assinante) [1] 17-17 N
		$Campo[3] = preenche_tam($NFItem[TipoAssinante], 1, 'N');

		// Campo 4 (Tipo de Utilização) [1] 18-18 N
		$Campo[4] = preenche_tam($NFItem[TipoUtilizacao], 1, 'N');

		// Campo 5 (Grupo de Tensão) [2] 19-20 N
		$Campo[5] = preenche_tam("", 2, 'N');

		// Campo 6 (Data de Emissão) [8] 21-28 N
		$Campo[6] = dataConv($NFItem[DataEmissao],'Y-m-d','Ymd');

		// Campo 7 (Modelo) [2] 29-30 N
		$Campo[7] = preenche_tam($NFItem[Modelo], 2, 'N');

		// Campo 8 (Serie) [3] 31-33 X
		$Campo[8] = preenche_tam($NFItem[Serie], 3, 'X');

		// Campo 9 (Numero) [9] 34-42 N
		$Campo[9] = preenche_tam($NFItem[IdNotaFiscal], 9, 'N');

		// Campo 10 (CFOP) [4] 43-46 N
		$Campo[10] = formata_cfop($NFItem[CFOP], 4, 'N');

		// Campo 11 (Item) [3] 47-49 N
		$Campo[11] = preenche_tam($NFItem[IdNotaFiscalItem], 3, 'N');

		// Campo 12 (Código do Serviço ou fornecimento) [10] 50-59 X
		$Campo[12] = preenche_tam($NFItem[Tipo].$NFItem[Codigo],10,'X');

		// Campo 13 (Descricão do Serviço ou fornecimento) [40] 60-99 X
		$Campo[13] = preenche_tam($NFItem[Descricao],40,'X');

		// Campo 14 (Código de Classificação do Item) [4] 100-103 N
		$Campo[14] = preenche_tam($NFItem[IdClassificacaoItem],4,'N');

		// Campo 15 (Unidade) [6] 104-109 X
		$Campo[15] = formata_unidade($NFItem[Unidade],6,'X');

		// Campo 16 (Quantidade 3 decimais) [11] 110-120 N
		$Campo[16] = preenche_tam(nDiasIntervalo($NFItem[DataReferenciaInicial],$NFItem[DataReferenciaFinal]),11,'N');

		// Campo 17 (Quantidade prestada 3 decimais) [11] 121-131 N
		$Campo[17] = preenche_tam(nDiasIntervalo($NFItem[DataReferenciaInicial],$NFItem[DataReferenciaFinal]),11,'N');

		// Campo 18 (Valor Total Item 2 decimais) [11] 132-142 N
		if($NFItem[ValorTotal] < 0){
			$Campo[18] = "-".preenche_tam(formata_valor($NFItem[ValorTotal]),10,'N');
		}else{
			$Campo[18] = preenche_tam(formata_valor($NFItem[ValorTotal]),11,'N');
		}

		// Campo 19 (Valor Desconto 2 decimais) [11] 143-153 N
		$Campo[19] = preenche_tam(formata_valor($NFItem[ValorDesconto]),11,'N');

		// Campo 20 (Valor Acrécimo e despesas acessórias com 2 decimais) [11] 154-164 N
		$Campo[20] = preenche_tam(0,11,'N');

		// Campo 21 (Valor Base de Calculo 2 decimais) [11] 165-175 N
		if($NFItem[ValorBaseCalculoICMS] < 0){
			$Campo[21] = "-".preenche_tam(formata_valor($NFItem[ValorBaseCalculoICMS]),10,'N');
		}else{
			$Campo[21] = preenche_tam(formata_valor($NFItem[ValorBaseCalculoICMS]),11,'N');
		}

		// Campo 22 (Valor ICMS 2 decimais) [11] 176-186 N
		$Campo[22] = preenche_tam(formata_valor($NFItem[ValorICMS]),11,'N');

		// Campo 23 (Valor Isento/Não tributado 2 decimais) [11] 187-197 N
		$Campo[23] = preenche_tam(formata_valor($NFItem[ValorNaoTributado]),11,'N');

		// Campo 24 (Outros Valores 2 decimais) [11] 198-208 N
		$Campo[24] = preenche_tam(formata_valor($NFItem[ValorOutros]),11,'N');

		// Campo 25 (Aliquota ICMS com 2 decimais) [4] 209-212 N
		$Campo[25] = number_format($NFItem[AliquotaICMS], 2, '', '');
		$Campo[25] = preenche_tam(formata_valor($Campo[25]),4,'N');

		// Campo 26 (Situção) [1] 213-213 X
		$Campo[26] = preenche_tam(formata_status($NFItem[IdStatus]), 1, 'X');

		// Campo 27 (AnoMes AAMM Periodo Apuração) [4] 214-217 X
		$Campo[27] = preenche_tam(dataConv($NFItem[PeriodoApuracao], 'Y-m', 'ym'), 4, 'N');

		// Campo 28 (Brancos) [5] 218-222 X
		$Campo[28] = preenche_tam("",5,'X');

		// Campo 29 (Código de Autenticação Digital do registro) [32] 223-254 X
		$Campo[29] = preenche_tam(md5(concatVar($Campo)), 32, 'X');
	
		// Gera a Linha
		$Linha[$iLinha] = concatVar($Campo);
		$iLinha++;
	}

	$String = '';

	for($i=0; $i<count($Linha); $i++){
		$String .= $Linha[$i]."\r\n";
	}

	$FileName = $PatchFile."/".$Processo[NomeArquivoItem];

	@unlink($FileName);

	if($File = fopen($FileName, 'a')) {
		if(fwrite($File, $String)){
			
			$FileSize = filesize($FileName)/1024;
			$FileSize = number_format($FileSize, 2, '.', '');

			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Arquivo Item (".count($Linha)." registros) $FileSize KB.";
		}else{			
			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Geração do Arquivo Item.";
		}
    }else{
		$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [ERRO] Geração do Arquivo Item.";
	}
    fclose($File);


	$sql = "update NotaFiscal2ViaEletronicaArquivo set 
				CodigoAutenticacaoDigitalArquivoItem = '".md5($String)."',
				ConteudoArquivoItem = '$String',
				LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento)
			where 
				IdLoja='$local_IdLoja' and 
				IdNotaFiscalLayout='$local_IdNotaFiscalLayout' and 
				MesReferencia='$local_MesReferencia' and
				Status = '$local_IdStatusArquivoMestre'";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
?>