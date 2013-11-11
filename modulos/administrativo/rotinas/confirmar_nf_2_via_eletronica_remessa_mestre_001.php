<?
	// Campo 1 (CNPJ) [14] 1-14 N
	$Campo[1] = formata_CPF_CNPJ($NF[CPF_CNPJ]);

	// Campo 2 (IE) [14] 15-28 X
	$Campo[2] = formata_IE($NF[RG_IE]);

	// Campo 3 (Razгo Social) [35] 29-63 X
	$Campo[3] = str_replace("'","",$NF[RazaoSocial]);
	$Campo[3] = preenche_tam($Campo[3], 35, 'X'); # Tamanho menor - Se colocar 35 ele nгo valida.

	// Campo 4 (Sigla UF) [2] 64-65 X
	$Campo[4] = preenche_tam($NF[SiglaEstado], 2, 'X');

	// Campo 5 (Tipo Assinante) [1] 66-66 N
	$Campo[5] = preenche_tam($NF[TipoAssinante], 1, 'N');

	// Campo 6 (Tipo de Utilizaзгo) [1] 67-67 N
	$Campo[6] = preenche_tam($NF[TipoUtilizacao], 1, 'N');

	// Campo 7 (Grupo de Tensгo) [2] 68-69 N
	$Campo[7] = preenche_tam("", 2, 'N');

	// Campo 8 (Codigo do Consumidor) [12] 70-81 X
	$Campo[8] = preenche_tam($NF[IdContaReceber], 12, 'X');

	// Campo 9 (Data de Emissгo) [8] 82-89 N
	$Campo[9] = preenche_tam(dataConv($NF[DataEmissao],'Y-m-d','Ymd'), 8, 'N');

	// Campo 10 (Modelo) [2] 90-91 N
	$Campo[10] = preenche_tam($NF[Modelo], 2, 'N');

	// Campo 11 (Serie) [3] 92-94 X
	$Campo[11] = preenche_tam($NF[Serie], 3, 'X');

	// Campo 12 (Numero) [9] 95-103 N
	$Campo[12] = preenche_tam($NF[IdNotaFiscal], 9, 'N');

	// Campo 13 (Codigo da Autenticaзгo Digital do documento) [32] 104-135 X
	$Campo[13] = preenche_tam($NF[CodigoAutenticacaoDocumento], 32, 'X');

	// Campo 14 (Valor Total com 2 decimais) [12] 136-147 N
	$Campo[14] =  preenche_tam(formata_valor($NF[ValorTotal]), 12, 'N');

	// Campo 15 (Valor BC ICMS com 2 decimais) [12] 148-159 N
	$Campo[15] =  preenche_tam(formata_valor($NF[ValorBaseCalculoICMS]), 12, 'N');

	// Campo 16 (Valor ICMS com 2 decimais) [12] 160-171 N
	$Campo[16] =  preenche_tam(formata_valor($NF[ValorICMS]), 12, 'N');

	// Campo 17 (Operacoes Isentas ou nгo tributadas com 2 decimais) [12] 172-183 N
	$Campo[17] =  preenche_tam(formata_valor($NF[ValorNaoTributado]), 12, 'N');

	// Campo 18 (Outros Valores 2 decimais) [12] 184-195 N
	$Campo[18] =  preenche_tam(formata_valor($NF[ValorOutros]), 12, 'N');

	// Campo 19 (Situacao do documento) [1] 196-196 X
	$Campo[19] =  preenche_tam(formata_status($NF[IdStatus]), 1, 'X');

	// Campo 20 (Ano e Mкs de Referencia da Apuraзгo - AAMM) [4] 197-200 N
	$Campo[20] = preenche_tam(dataConv($NF[PeriodoApuracao], 'Y-m', 'ym'), 4, 'N');

	// Campo 21 (Referencia ao item da NF) [9] 201-209 N
	$Campo[21] = preenche_tam(($PosNFItemIni[$NF[IdNotaFiscal]][1]+1),9,'N');

	// Campo 22 (Numero do terminal telefфnico ou nъmero da conta de consumo) [10] 210-219 X
	$Campo[22] =  preenche_tam("", 10, 'X');

	// Campo 23 (Brancos - Reserva) [3] 220-222 X
	$Campo[23] =  preenche_tam("", 3, 'X');

	// Campo 24 (Cуdigo de autenticaзгo digital do registro) [32] 223-254 X
	$Campo[24] = preenche_tam(md5(concatVar($Campo)), 32, 'X');
?>