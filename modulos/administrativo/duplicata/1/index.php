<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"V";	

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include("../../../../rotinas/verifica.php");
	
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_md5				= $_GET['Duplicata'];
	
	$sql = "SELECT 
				Pessoa.IdPessoa, 
				Pessoa.TipoPessoa, 
				Pessoa.Nome, 
				SUBSTRING(Pessoa.RazaoSocial, 1, 65) Credor, 
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
				Loja.Idloja = $local_IdLoja AND 
				Pessoa.IdPessoa = Loja.IdPessoa AND 
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa AND
				Cidade.IdPais = PessoaEndereco.IdPais AND 
				Cidade.IdEstado = PessoaEndereco.IdEstado AND 
				Cidade.IdCidade = PessoaEndereco.IdCidade AND 
				Cidade.IdPais = Estado.IdPais AND 
				Cidade.IdEstado = Estado.IdEstado;";
	$res = @mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);
	
	$sql2 = "select distinct 
				ContaReceberDados.IdLoja,
				ContaReceberDados.IdPessoa,
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.NumeroNF,
				ContaReceberDados.DataLancamento,
				(ContaReceberDados.ValorFinal) Valor,
				ContaReceberDados.ValorDesconto,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.LimiteDesconto,
				ContaReceberDados.IdLocalCobranca,
				ContaReceberDados.MD5,
				ContaReceberDados.IdStatus,
				Pessoa.TipoPessoa,
				substr(Pessoa.Nome, 1, 20) Nome,
				substr(Pessoa.RazaoSocial, 1, 20) RazaoSocial,
				Pessoa.CPF_CNPJ,
				LocalCobranca.AbreviacaoNomeLocalCobranca,
				LocalCobranca.PercentualMulta,
				LocalCobranca.PercentualJurosDiarios, 
				LocalCobranca.ExtLogo 
			from
				LancamentoFinanceiroContaReceber,
				Pessoa 
				left join (PessoaGrupoPessoa, GrupoPessoa) 
					on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa 
						and PessoaGrupoPessoa.IdLoja = '$local_IdLoja' 
						and PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja 
						and PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
				LocalCobranca,
				LancamentoFinanceiro,
				ContaReceberDados 
			where 
				ContaReceberDados.IdLoja = $local_IdLoja and
				ContaReceberDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
				ContaReceberDados.IdLoja = LancamentoFinanceiro.IdLoja and 
				ContaReceberDados.IdLoja = LocalCobranca.IdLoja and 
				ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and 
				LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and 
				ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and 
				ContaReceberDados.IdPessoa = Pessoa.IdPessoa and 
				ContaReceberDados.MD5 = '$local_md5'";
	$res2 = @mysql_query($sql2,$con);
	$lin2 = @mysql_fetch_array($res2);
	
	$local_IdPessoa 		= $lin2['IdPessoa'];
	$local_IdLocalCobranca 	= $lin2['IdLocalCobranca'];
	$local_ExtLogo			= $lin2['ExtLogo'];
	
	$sql3 ="select 
				Pessoa.IdPessoa,
				Pessoa.RG_IE,
				concat(
					PessoaEndereco.Endereco,',',PessoaEndereco.Numero
				) Endereco,
				PessoaEndereco.CEP,
				PessoaEndereco.Bairro,
				PessoaEndereco.Complemento,
				Estado.NomeEstado,
				Estado.SiglaEstado,
				Cidade.NomeCidade  
			from
				PessoaEndereco  
					LEFT JOIN Estado 
					ON (
					   PessoaEndereco.IdEstado = Estado.IdEstado 
					) 
					LEFT JOIN Cidade 
					ON (
					   PessoaEndereco.IdCidade = Cidade.IdCidade
					   and Cidade.IdEstado = Estado.IdEstado
					),
				Pessoa 
			where 
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				Pessoa.IdPessoa = $local_IdPessoa";
	$res3 = mysql_query($sql3,$con);
	$lin3 = mysql_fetch_array($res3);
	
	$razao = $lin['Credor'];
	$cnpj = "C.N.P.J. (MF)Nº: ".$lin['CPF_CNPJ'];
	$endereco = $lin['Endereco'];
	$numero = $lin['Numero'];
	$cep_cre = $lin['CEP'];
	$bairro = $lin['Bairro'];
	$municipio = $lin['NomeCidade'];
	$estado = $lin['SiglaEstado'];
	$cidade_uf = $municipio."-".$estado;
	
	$valor = $lin2['Valor'];
	$extenso = extenso( $valor,"real", "reais", "centavo", "centavos");
	$valor2 = str_replace(".",",",$valor);
	(int)$valor = str_replace(",",".",$valor);
	
	$num_duplicata = $lin2['IdContaReceber'];
	$venc = dataConv($lin2['DataVencimento'],'Y-m-d','d/m/y');
	$desconto = number_format($lin2['ValorDesconto'],2,",",".");
	$desc_sobre = number_format($lin2['Valor'],2,",",".");
	$desc_data = $lin2['LimiteDesconto'];
	$data_desc_venc = $lin2['DataVencimento'];
	$desc_data = dataConv(incrementaData($data_desc_venc,$desc_data),'Y-m-d','d/m/y');
	$juros = $lin2['PercentualJurosDiarios'];
	$juros = number_format($juros,2,",",".");
	$multa = $lin2['PercentualMulta'];
	$multa = number_format($multa,2,",",".");
	
	if($num_nota !=""){
		$num_nota = $lin2['NumeroNF'];
	} else{
		$num_nota = "-";
	}
	
	
	//------------------------Dados Sacado-----------------------\\
	if($lin2['TipoPessoa'] == 1){
		$nom_sacado = $lin2['RazaoSocial'];
		$ass_aceite = $lin2['RazaoSocial'];
	}else{
		$nom_sacado = $lin2['Nome'];
		$ass_aceite = $lin2['Nome'];
	}
	$cnpj_sac= $lin2['CPF_CNPJ'];
	
	$end_sac= $lin3['Endereco'];
	$cep_sac = $lin3['CEP'];
	$est_sac = $lin3['SiglaEstado'];
	$mun_sac = $lin3['NomeCidade'];
	$praca_paga = $municipio;
	$insc_sac  = $lin3['RG_IE'];
	//------------------------------------------------------------\\

	
	//******************textos conteudo variaveis*****************\\
	$doc = "DUPLICATA";
	$data_emissao = "Data da Emissão: ".date("d")."/".date("m")."/".date("y");
	//--------------------Labels Informações de Pagamento------------\\
	$nf_fatura = "NF FATURA Nº";
	if($lin2['ValorDesconto'] != 0.00){
		$desc_val_data = "DESCONTO DE   ".$desconto."   SOBRE   ".getParametroSistema(5,1)." ".$desc_sobre."   ATÉ   ".$desc_data.".";
	} else{
		$desc_val_data = "";
	}
	$condicoes = "CONDIÇÕES: JUROS DE  ".$juros."% AO DIA E MULTA DE  ".$multa."% APÓS O VENCIMENTO.";
	$insti_financeira = "PARA USO DA\nINSTITUIÇÃO FINANCEIRA";
	$valor_duplicata = "NF FATF/Duplicata - Valor";
	$n_ordem = "Duplicata nº de Ordem";
	$vencimento = "Vencimento";
	$lbl_extenso = "valor por extenso";
	//--------------------Labels Dados Sacado-------------------\\
	$nome_sacado = "NOME DO SACADO:";
	$end_sacado= "ENDEREÇO:";
	$cep = "CEP:";
	$mun = "MUNICÍPIO:";
	$praca = "PRAÇA DE PAGAMENTO:";
	$est = "UF:";
	$cnpj_sacado = "CNPJ/CPF (MF) Nº:";
	if($lin2['TipoPessoa'] == 1){
		$insc = "Insc. Est. Nº:";
	} else{
		$insc = "RG:";
	}
	//---------------------
	
	
	$declaracao = getCodigoInterno(48,1);
	eval("\$declaracao = \"$declaracao\";");//compila toda a string
	$aviso = getCodigoInterno(48,2);
	
	$data_aceite1 = "Em______/______/________";
	$data_aceite2 = "(Data do Aceite)";
	$ass_emitente = "Assinatura do Emitente";
	
	include ('duplicata.php');
?>
