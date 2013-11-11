<?
	// Campo 1 (CNPJ) [14] 1-14 N
	$Campo[1] = formata_CPF_CNPJ($NF[CPF_CNPJ]);

	// Campo 2 (IE) [14] 15-28 X
	$Campo[2] = formata_IE($NF[RG_IE]);

	// Campo 3 (Razгo Social) [35] 29-63 X
	$Campo[3] = str_replace("'","",$NF[RazaoSocial]);
	$Campo[3] = preenche_tam($Campo[3], 35, 'X');

	// Campo 4 (Logradouro) [45] 64-108 X
	$NF[Endereco]	= str_replace("'","",$NF[Endereco]);
	$Campo[4]	= preenche_tam($NF[Endereco], 45, 'X');

	// Campo 5 (Numero) [5] 109-113 N
	$Campo[5] = preenche_tam(numero_endereco($NF[Numero]), 5, 'N');

	// Campo 6 (Complemento) [15] 114-128 X
	$NF[Complemento]	= str_replace("'","",$NF[Complemento]);
	$Campo[6]			= preenche_tam($NF[Complemento], 15, 'X');

	// Campo 7 (CEP) [8] 129-136 N
	$Campo[7] = preenche_tam(formata_valor($NF[CEP]), 8, 'N');

	// Campo 8 (Bairro) [15] 137-151 X
	$NF[Bairro]	= str_replace("'","",$NF[Bairro]);
	$Campo[8]	= preenche_tam($NF[Bairro], 15, 'X');

	// Campo 9 (Nome Cidade) [30] 152-181 X
	$Campo[9] = preenche_tam($NF[NomeCidade], 30, 'X');

	// Campo 10 (Sigla Estado) [2] 182-183 X
	$Campo[10] = preenche_tam($NF[SiglaEstado], 2, 'X');

	// Campo 11 (Telefone) [10] 184-193 N
	$Campo[11] = preenche_tam(formata_valor($NF[Telefone]), 10, 'N');

	// Campo 12 (Codigo de Identificaзгo do Consumidor) [12] 194-205
	$Campo[12] = preenche_tam($NF[IdContaReceber], 12, 'X');

	// Campo 13 (Numero do Terminal Telefonico) [10] 206-215 X
	$Campo[13] =  preenche_tam("", 10, 'X');

	// Campo 14 (UF Terminal Telefonico) [2] 216-217 X		
	$Campo[14] =  preenche_tam("", 2, 'X');

	// Campo 15 (Brancos) [5] 218-222 X
	$Campo[15] =  preenche_tam("", 5, 'X');

	// Campo 16 (Cуdigo de Autenticaзгo Digital do registro)
	$Campo[16] = preenche_tam(md5(concatVar($Campo)), 32, 'X');
?>