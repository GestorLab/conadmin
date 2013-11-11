<?
	$localModulo		=	1;
	$localOperacao		=	29;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja				= $_SESSION["IdLoja"]; 
	$IdPessoaLogin				= $_SESSION['IdPessoa'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_mes_referencia		= $_POST['filtro_mes_referencia'];
	$filtro_processo_financeiro	= $_POST['filtro_processo_financeiro'];
	$filtro_local_cobranca		= url_string_xsl($_POST['filtro_local_cobranca'],'url',false);
	$filtro_forma_cobranca		= $_POST['filtro_forma_cobranca'];
	$filtro_pessoa_endereco		= $_POST['filtro_pessoa_endereco'];
	$filtro_nome				= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_limit				= $_POST['filtro_limit'];	
	$filtro_pessoa				= $_GET['IdPessoa'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_limit == "" && $_GET['filtro_limit']!=''){
		$filtro_limit = $_GET['filtro_limit'];
	}
	
	if($filtro_processo_financeiro == '' && $_GET['IdProcessoFinanceiro']){
		$filtro_processo_financeiro	=	$_GET['IdProcessoFinanceiro'];	
	}
		
	if($filtro_mes_referencia!=''){
		$filtro_url .= "&MesReferencia=$filtro_mes_referencia";
		$filtro_sql .=	" and ProcessoFinanceiro.MesReferencia like '%$filtro_mes_referencia%'";
	}
		
	if($filtro_processo_financeiro!=""){
		$filtro_url .= "&IdProcessoFinanceiro=".$filtro_processo_financeiro;
		$filtro_sql .= " and ProcessoFinanceiro.IdProcessoFinanceiro = $filtro_processo_financeiro";
	}
	
	if($filtro_pessoa!=""){
		$filtro_url .= "&IdPessoa=".$filtro_pessoa;
		$filtro_sql .= " and Pessoa.IdPessoa = $filtro_pessoa";
	}
	
	if($filtro_nome!=""){
		$filtro_url .= "&Nome=".$filtro_nome;
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .= " and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_pessoa_endereco!=""){
		$filtro_url .= "&IdPessoaEndereco=".$filtro_pessoa_endereco;
		$filtro_sql .= " and PessoaEndereco.IdPessoaEndereco = $filtro_pessoa_endereco";
	} else{
		$filtro_sql .= " and PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault";
	}
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	if($_SESSION["RestringirAgenteCarteira"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado,
							Carteira
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdLoja = Carteira.IdLoja and
							AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
							Carteira.IdCarteira = '$IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&DescricaoLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and (LocalCobranca.DescricaoLocalCobranca like '%$filtro_local_cobranca%' or LocalCobranca.AbreviacaoNomeLocalCobranca like '%$filtro_local_cobranca%')";
	}
	
	if($filtro_forma_cobranca!=""){
		$filtro_url .= "&FormaCobranca=".$filtro_forma_cobranca;
		
		switch($filtro_forma_cobranca){
			case 'C':
				$filtro_sql .= " and Pessoa.Cob_FormaCorreio = 'S'";
				break;
			case 'E':
				$filtro_sql .= " and Pessoa.Cob_FormaEmail = 'S'";
				break;
			case 'O':
				$filtro_sql .= " and Pessoa.Cob_FormaOutro = 'S'";
				break;
		}
	}
			
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	header ("content-type: text/xml");

	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_pessoa_forma_cobranca_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	$sql	=	"select distinct
					LancamentoFinanceiroDados.IdLoja,
					LancamentoFinanceiroDados.IdContaReceber, 
					Pessoa.IdPessoa, 
					Pessoa.Nome, 
					Pessoa.RazaoSocial, 
					PessoaEndereco.Numero, 
					PessoaEndereco.Endereco, 
					Pessoa.RG_IE, 
					PessoaEndereco.CEP, 
					PessoaEndereco.Bairro, 
					Pessoa.Telefone1, 
					Pessoa.Telefone2, 
					Pessoa.Telefone3, 
					Pessoa.Celular, 
					Pessoa.TipoPessoa, 
					Pessoa.Fax, 
					PessoaEndereco.Complemento, 
					Pessoa.CPF_CNPJ, 
					PessoaEndereco.Telefone, 
					PessoaEndereco.Celular CelularEndereco, 
					PessoaEndereco.Fax FaxEndereco, 
					ContaReceber.DataLancamento, 
					Cidade.NomeCidade, 
					Estado.SiglaEstado,
					ContaReceber.ValorDespesas ValorDespesaLocalCobranca,
					ContaReceber.DataVencimento
				from 
					LancamentoFinanceiroDados,
					ContaReceber,
					Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					PessoaEndereco,
					Pais,
					Estado,
					Cidade,
					LocalCobranca,
					ProcessoFinanceiro
				where
					LancamentoFinanceiroDados.IdLoja = $local_IdLoja and
					LancamentoFinanceiroDados.IdLoja = ContaReceber.IdLoja and
					LancamentoFinanceiroDados.IdLoja = LocalCobranca.IdLoja and
					LancamentoFinanceiroDados.IdLoja = ProcessoFinanceiro.IdLoja and
					Pessoa.IdPessoa = ContaReceber.IdPessoa and
					LancamentoFinanceiroDados.IdProcessoFinanceiro = ProcessoFinanceiro.IdProcessoFinanceiro and
					ContaReceber.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber and
					PessoaEndereco.IdPessoa = Pessoa.IdPessoa and
					PessoaEndereco.IdPais = Pais.IdPais and
					PessoaEndereco.IdPais = Estado.IdPais and
					PessoaEndereco.IdPais = Cidade.IdPais and
					PessoaEndereco.IdEstado = Estado.IdEstado and
					PessoaEndereco.IdEstado = Cidade.IdEstado and
					PessoaEndereco.IdCidade = Cidade.IdCidade and
					LocalCobranca.IdLocalCobranca = ContaReceber.IdLocalCobranca $filtro_sql
	      		order by 
					Pessoa.RazaoSocial, 
					Pessoa.Nome 
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoa] == 2){	
			$lin[Nome]		=	$lin[Nome];
			$lin[CampoNome]	=	dicionario(178);
		}else{
			$lin[Nome]		=	$lin[RazaoSocial];
			$lin[CampoNome]	=	dicionario(172);
		}
		$lin[Endereco]		=	$lin[Endereco].", ".$lin[Numero];
		if($lin[Complemento]!= '' )	$lin[Endereco] .=	" - ".$lin[Complemento];
		if($lin[Bairro]!= '' )	 	$lin[Endereco] .=	" - ".dicionario(160).": ".$lin[Bairro];
		$lin[Endereco]	.=	" - ".$lin[NomeCidade]."-".$lin[SiglaEstado]." - ".dicionario(156).": ".$lin[CEP];
		
		if($lin[ValorDespesaLocalCobranca] == '')	$lin[ValorDespesaLocalCobranca] = 0;
		
		$lin[DataLancamento]	=	dataConv($lin[DataLancamento],'Y-m-d','d/m/Y');
		$lin[DataVencimento]	=	dataConv($lin[DataVencimento],'Y-m-d','d/m/Y');
		
		if($lin[Telefone1] == ''){
			if($lin[Telefone2] == ''){
				if($lin[Telefone3] == ''){
					if($lin[Celular] == ''){
						if($lin[Fax] == ''){
							if($lin[Telefone] == ''){
								if($lin[CelularEndereco]	==	''){
									$lin[Telefone1]	=	$lin[FaxEndereco];										
								}else{
									$lin[Telefone1]	=	$lin[CelularEndereco];
								}
							}else{
								$lin[Telefone1]	=	$lin[Telefone];
							}	
						}else{
							$lin[Telefone1]	=	$lin[Fax];
						}
					}else{
						$lin[Telefone1]	=	$lin[Celular];
					}
				}else{
					$lin[Telefone1]	=	$lin[Telefone3];
				}
			}else{
				$lin[Telefone1]	=	$lin[Telefone2];
			}
		}
		
		if($lin[TipoPessoa] == 1){
			$lin[cpCNPJ]	=	dicionario(179);
			$lin[cpIE]		=	dicionario(180);
		}
		if($lin[TipoPessoa] == 2){
			$lin[cpCNPJ]	=	dicionario(210);
			$lin[cpIE]		=	dicionario(89);
		}		
			
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";
		echo 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";
		echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
		echo 	"<IdProcessoFinanceiro>$lin[IdProcessoFinanceiro]</IdProcessoFinanceiro>";
		echo 	"<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<CampoNome><![CDATA[$lin[CampoNome]]]></CampoNome>";
		echo 	"<Endereco><![CDATA[$lin[Endereco]]]></Endereco>";
		echo 	"<RG_IE><![CDATA[$lin[RG_IE]]]></RG_IE>";
		echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
		echo 	"<ValorDespesaLocalCobranca><![CDATA[$lin[ValorDespesaLocalCobranca]]]></ValorDespesaLocalCobranca>";
		echo 	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
		echo 	"<cpCNPJ><![CDATA[$lin[cpCNPJ]]]></cpCNPJ>";
		echo 	"<cpIE><![CDATA[$lin[cpIE]]]></cpIE>";
		
		$cont  	=   0;
		$sql2	=	"select
						 Codigo,
						 Descricao,
						 Referencia,
						 Valor,
						 ValorDescontoAConceber,
						 (Valor - ValorDescontoAConceber) ValorFinal,
						 Tipo,
						 IdLancamentoFinanceiro
					from 
						 Demonstrativo
					where
						 Demonstrativo.IdLoja=$lin[IdLoja] and
						 Demonstrativo.IdContaReceber = $lin[IdContaReceber]
					order by 
						 Tipo,
						Codigo";
		$res2	=	mysql_query($sql2,$con);
		while($lin2	=	mysql_fetch_array($res2)){
			echo "	<lancamentos>";
			echo 	"<Codigo>$lin2[Codigo]</Codigo>";
			echo 	"<Descricao><![CDATA[$lin2[Descricao]]]></Descricao>";
			echo 	"<Referencia><![CDATA[$lin2[Referencia]]]></Referencia>";	
			echo 	"<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
			echo 	"<Valor><![CDATA[$lin2[Valor]]]></Valor>";
			echo 	"<ValorDescontoAConceber><![CDATA[$lin2[ValorDescontoAConceber]]]></ValorDescontoAConceber>";
			echo 	"<ValorFinal><![CDATA[$lin2[ValorFinal]]]></ValorFinal>";
			echo 	"<Tipo><![CDATA[$lin2[Tipo]]]></Tipo>";
			echo "	</lancamentos>";
			
			$cont++;
		}
			
		echo "</reg>";
	}
	echo "</db>";
?>
