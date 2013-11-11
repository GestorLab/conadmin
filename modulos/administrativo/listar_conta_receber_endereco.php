<?
	$localModulo		=	1;
	$localOperacao		=	189;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_IdPessoaLogin				= $_SESSION['IdPessoa'];
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_nome						= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_descrisao_servico			= $_POST['filtro_descrisao_servico'];
	$filtro_valor						= $_POST['filtro_valor'];
	$filtro_campo						= $_POST['filtro_campo'];
	$filtro_local_cobranca				= $_POST['filtro_local_cobranca'];
	$filtro_limit						= $_POST['filtro_limit'];
	$filtro_status						= $_POST['filtro_status'];
	$filtro_tipo_pessoa					= $_POST['filtro_tipo_pessoa'];
	$filtro_carne						= $_POST['filtro_carne'];
	$filtro_id_servico					= $_POST['filtro_id_servico'];
	$filtro_estado						= $_POST['filtro_estado'];
	$filtro_cidade						= $_POST['filtro_cidade'];
	$filtro_cep							= $_POST['filtro_cep'];
	$filtro_bairro						= url_string_xsl($_POST['filtro_bairro'],"URL",false);
	$filtro_endereco					= url_string_xsl($_POST['filtro_endereco'],"URL",false);
	$filtro_idPessoa					= $_GET['IdPessoa'];
	$filtro_conta_receber				= $_GET['IdContaReceber'];
	$filtro_ordem_servico				= $_GET['IdOrdemServico'];	
	$filtro_conta_eventual				= $_GET['IdContaEventual'];	
	$filtro_arquivo_retorno				= $_GET['IdArquivoRetorno'];	
	$filtro_arquivo_remessa				= $_GET['IdArquivoRemessa'];
	$filtro_processo_financeiro			= $_GET['IdProcessoFinanceiro'];
	$filtro_imprimir_conta_receber		= $_GET['ImprimirContaReceber'];
	$filtro_id_conta_cancelada			= $_GET['IdContaCancelada'];
	$filtro_id_conta_receber_agrupador	= $_GET['IdContaReceberAgrupador'];
	
	$filtro_cancelado	=	$_SESSION["filtro_cancelado"];
	$filtro_juros		=	$_SESSION["filtro_juros"];
	$filtro_soma		=	$_SESSION["filtro_soma"];
	$filtro_nota_fiscal	=	$_SESSION["filtro_nota_fiscal"];
	$filtro_impressao	=	$_SESSION["filtro_impressao"];
	$filtro_conta_receber_nota_fiscal = $_SESSION["filtro_conta_receber_nota_fiscal"];	
	
	if($filtro_ordem_servico == ''){
		$filtro_contrato = $_GET['IdContrato'];
	}
	
	if($filtro_local_cobranca == '' && $_GET['IdLocalCobranca'] != ''){
		$filtro_local_cobranca = $_GET['IdLocalCobranca'];
	} 
	
	if($filtro_tipo_pessoa == '' && $_GET['IdParametroSistema'] != ''){
		$filtro_tipo_pessoa = $_GET['IdParametroSistema'];
	} 
	
	if($filtro_numero_documento == '' && $_GET['NumeroDocumento']!=''){
		$filtro_numero_documento	= $_GET['NumeroDocumento'];
	}
	
	if($filtro_carne == '' && $_GET['IdCarne']!=''){
		$filtro_carne	= $_GET['IdCarne'];
	}
	
	if($filtro_limit == '' && $_GET['filtro_limit'] != ''){
		$filtro_limit	= $_GET['filtro_limit'];
	}
	
	$codigo_de_barras			= $_POST['codigo_de_barras'];
	
	if($codigo_de_barras !=''){
		switch(strlen($codigo_de_barras)){
			case '44':
				$filtro_nosso_numero = (int)(substr($codigo_de_barras,32,10));
				break;
		}
	}
	
	$filtro_url	 			= "";
	$filtro_sql  			= "";
	$filtro_from 			= "";
	$sqlAux		 			= "";
	$filtro_status_receb	= "";
	$filtro_sql_select		= "";
	
	LimitVisualizacao("listar");

	$sqlAux = ", ContaReceberDados";

	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_imprimir_conta_receber != ''){
		switch($filtro_imprimir_conta_receber){
			case 1:
				$filtro_sql .=	" and ContaReceberDados.IdStatus = 1";
				break;
			case 2:
				$filtro_sql .=	" and ContaReceberDados.IdStatus = 2";
				break;
		}		
	}
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_estado!=''){
		$filtro_url .= "&IdEstado=$filtro_estado";
		$filtro_sql .=	" and PessoaEndereco.IdEstado = $filtro_estado";
	}
	
	if($filtro_cidade!=''){
		$filtro_url .= "&IdCidade=$filtro_cidade";
		$filtro_sql .=	" and PessoaEndereco.IdCidade = $filtro_cidade";
	}
	
	if($filtro_cep!=''){
		$filtro_url .= "&Cep=$filtro_cep";
		$filtro_sql .=	" and PessoaEndereco.CEP = '$filtro_cep'";
	}
	
	if($filtro_bairro!=''){
		$filtro_url .= "&Bairro=$filtro_bairro";
		$filtro_sql .=	" and PessoaEndereco.Bairro like '%$filtro_bairro%'";
	}
	
	if($filtro_endereco!=''){
		$filtro_url .= "&Endereco=$filtro_endereco";
		$filtro_sql .=	" and PessoaEndereco.Endereco like '%$filtro_endereco%'";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaReceberDados.IdLocalCobranca = $filtro_local_cobranca";
	}

	if($filtro_tipo_pessoa != ""){
		$filtro_url	.= "&TipoPessoa=$filtro_tipo_pessoa";
		$filtro_sql .=	" and Pessoa.TipoPessoa = $filtro_tipo_pessoa";
	}
	
	if($filtro_arquivo_remessa!=""){
		$filtro_sql .= " and ContaReceberDados.IdArquivoRemessa = $filtro_arquivo_remessa";		
	}
	
	if($filtro_ordem_servico!=""){
		$filtro_url  .= "&IdOrdemServico=".$filtro_ordem_servico;
		$filtro_sql  .= " and LancamentoFinanceiro.IdOrdemServico= $filtro_ordem_servico";
		
		$filtro_idPessoa="";
	}
	
	if($filtro_idPessoa!=""){
		$filtro_url  .= "&IdPessoa=".$filtro_idPessoa;
		$filtro_sql  .= " and Pessoa.IdPessoa = $filtro_idPessoa";
	}

	if($filtro_carne!=""){
		$filtro_url  .= "&IdCarne=".$filtro_carne;
		$filtro_sql .= " and ContaReceberDados.IdCarne = $filtro_carne";
	}
	
	if($filtro_conta_receber!=""){
		$filtro_url  .= "&IdContaReceber=".$filtro_conta_receber;
		$filtro_sql .= " and ContaReceberDados.IdContaReceber = $filtro_conta_receber";
	}
	
	if($filtro_contrato!=""){
		$filtro_url  .= "&IdContrato=".$filtro_contrato;
		$filtro_sql .= " and LancamentoFinanceiro.IdContrato = $filtro_contrato";
	}
	
	if($filtro_conta_eventual!=""){
		$filtro_url  .= "&IdContaEventual=".$filtro_conta_eventual;
		$filtro_sql .= " and LancamentoFinanceiro.IdContaEventual = $filtro_conta_eventual";
	}
	
	if($filtro_processo_financeiro!=""){
		$filtro_url  .= "&IdProcessoFinanceiro=".$filtro_processo_financeiro;
		$filtro_sql .= " and LancamentoFinanceiro.IdProcessoFinanceiro = $filtro_processo_financeiro";
	}
	
	if($filtro_numero_documento!=""){	
		$filtro_sql .=	" and ContaReceberDados.NumeroDocumento = '$filtro_numero_documento'";
	}
	if($filtro_conta_receber_nota_fiscal!=""){
		if($filtro_conta_receber_nota_fiscal == 1){	
			$filtro_sql .=	" and (ContaReceberDados.NumeroNF != '' and ContaReceberDados.NumeroNF is not null)";
		}
	}
	
	if($filtro_status!=""){
		$filtro_url  .= "&IdStatus=".$filtro_status;
		if($filtro_status == 200){
			$filtro_sql  .= " and ContaReceberDados.IdStatus = 1 and DataVencimento < curdate() and ContaReceberDados.IdStatus != 7";
		}else{
			if($filtro_status != 7){
				$filtro_sql  .= " and ContaReceberDados.IdStatus = ".$filtro_status." and ContaReceberDados.IdStatus != 7";
			}else{
				$filtro_sql  .= " and ContaReceberDados.IdStatus = ".$filtro_status."";
			}
		}
	}else{
		$filtro_sql  .= " and ContaReceberDados.IdStatus != 7";		
	}
	
	if($filtro_arquivo_retorno!=""){
		$filtro_url .= "&Campo=IdArquivoRetorno";
		$filtro_url .= "&Valor=".$filtro_arquivo_retorno;
		
		$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdArquivoRetorno = $filtro_arquivo_retorno";
		
		if($_GET['IdLocalCobranca']!=""){
			$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdLocalCobranca = ".$_GET['IdLocalCobranca'];
		}
		if($_GET['IdLocalRecebimento']!=""){
			$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdLocalCobranca = ".$_GET['IdLocalRecebimento'];
		}
		$filtro_sql .= " and ContaReceberDados.IdLoja = ContaReceberRecebimentoAtivo.IdLoja";
		$filtro_sql .= " and ContaReceberDados.IdContaReceber = ContaReceberRecebimentoAtivo.IdContaReceber";
		
		$filtro_sql_select = ",
			LancamentoFinanceiro.IdProcessoFinanceiro,
			ContaReceberRecebimentoAtivo.DataRecebimento,
			ContaReceberRecebimentoAtivo.IdRecibo,
			ContaReceberRecebimentoAtivo.ValorRecebido,
			ContaReceberRecebimentoAtivo.IdLocalCobranca IdLocalCobrancaRecebimento";
		
		$sqlAux = ", ContaReceberDados, ContaReceberRecebimentoAtivo";		
	}else{
		$filtro_status_receb .=	" and ContaReceberRecebimento.IdStatus != 0";	
	}
	
	if($filtro_id_conta_cancelada != ""){
		$filtro_sql .= " and ContaReceberDados.IdContaReceber in ($filtro_id_conta_cancelada)";
	}
	
	if($filtro_id_conta_receber_agrupador != ""){
		$filtro_sql .= " and ContaReceberDados.IdContaReceber in (
			select 
				IdContaReceber
			from 
				ContaReceberAgrupadoParcela
			where
				IdLoja = $local_IdLoja and
				IdContaReceberAgrupador = $filtro_id_conta_receber_agrupador
		)";
	}
	
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=".$filtro_valor;
	
		switch($filtro_campo){
			case 'IdArquivoRetorno':
				$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdArquivoRetorno = $filtro_valor";
				
				if($_GET['IdLocalCobranca']!=""){
					$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdLocalCobranca = ".$_GET['IdLocalCobranca'];
				}
				
				if($_GET['IdLocalRecebimento']!=""){
					$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdLocalCobranca = ".$_GET['IdLocalRecebimento'];
				}
				
				$filtro_sql .= " and ContaReceberDados.IdLoja = ContaReceberRecebimentoAtivo.IdLoja";
				$filtro_sql .= " and ContaReceberDados.IdContaReceber = ContaReceberRecebimentoAtivo.IdContaReceber";
				
				$filtro_sql_select = ",
					LancamentoFinanceiro.IdProcessoFinanceiro,
					ContaReceberRecebimentoAtivo.DataRecebimento,
					ContaReceberRecebimentoAtivo.IdRecibo,
					ContaReceberRecebimentoAtivo.ValorRecebido,
					ContaReceberRecebimentoAtivo.IdLocalCobranca IdLocalCobrancaRecebimento";
				
				$sqlAux = ", ContaReceberDados, ContaReceberRecebimentoAtivo";
				break;
			case 'DataLancamento':
				$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
				$filtro_sql .= " and ContaReceberDados.DataLancamento = '$filtro_valor'";
				break;
			case 'DataVencimentoAtual':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .= " and ContaReceberDados.DataVencimento = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberDados.DataVencimento is NULL";
				}
				break;
			case 'DataVencimentoOriginal':
				$sqlAux .= ", (select 
								IdLoja,
								IdContaReceber, 
								min(DataVencimento) DataVencimentoOriginal 
							from 
								ContaReceberVencimento 
							group by IdLoja, IdContaReceber) ContaReceberVencimentoOriginal";
				$filtro_sql .= " and 
								ContaReceberDados.IdLoja = ContaReceberVencimentoOriginal.IdLoja and 
								ContaReceberDados.IdContaReceber = ContaReceberVencimentoOriginal.IdContaReceber";
				
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .= " and ContaReceberVencimentoOriginal.DataVencimentoOriginal = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberVencimentoOriginal.DataVencimentoOriginal is NULL";
				}
				break;
			case 'MesLancamento':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and substring(ContaReceberDados.DataLancamento,1,7) = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberDados.DataLancamento is NULL";
				}
				break;
			case 'MesVencimentoAtual':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and substring(ContaReceberDados.DataVencimento,1,7) = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberDados.DataVencimento is NULL";
				}
				break;
			case 'MesVencimentoOriginal':
				$sqlAux .= ", (select 
								IdLoja,
								IdContaReceber, 
								substring(min(DataVencimento),1,7) MesVencimentoOriginal 
							from 
								ContaReceberVencimento 
							group by IdLoja, IdContaReceber) ContaReceberVencimentoOriginal";
				$filtro_sql .= " and 
								ContaReceberDados.IdLoja = ContaReceberVencimentoOriginal.IdLoja and 
								ContaReceberDados.IdContaReceber = ContaReceberVencimentoOriginal.IdContaReceber";
				
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and ContaReceberVencimentoOriginal.MesVencimentoOriginal = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberVencimentoOriginal.MesVencimentoOriginal is NULL";
				}
				break;
			case 'DescricaoServico':
				$filtro_url .= "&DescricaoServico=".$filtro_valor;
				$sqlAux = " left join Contrato on (
								LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
								LancamentoFinanceiro.IdContrato = Contrato.IdContrato
							) left join OrdemServico on (
								LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and 
								LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico
							), 
							Servico".$sqlAux;
				$filtro_sql .= " and (
									Contrato.IdServico = Servico.IdServico OR 
									OrdemServico.IdServico = Servico.IdServico
								) and
								Servico.DescricaoServico like '%$filtro_valor%'";
				break;
			case 'IdProcessoFinanceiro':
				$filtro_sql .=	" and LancamentoFinanceiro.IdProcessoFinanceiro = '$filtro_valor'";
				break;
			case 'NumeroDocumento':
				$filtro_sql .=	" and ContaReceberDados.NumeroDocumento = '$filtro_valor'";
				break;
			case 'NumeroNF':
				$valor = str_pad($filtro_valor,9,"0",STR_PAD_LEFT);
				$filtro_sql .=	" and ContaReceberDados.NumeroNF = '$valor'";
				break;
			case 'IdReceibo':
				$sqlAux .= ", ContaReceberRecebimento";
				$filtro_sql .= " and 
					ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and 
					ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
					ContaReceberRecebimento.IdRecibo = '$filtro_valor'";
				break;
			case 'IdContaReceber':
				$filtro_sql .=	" and ContaReceberDados.IdContaReceber = '$filtro_valor'";
				break;
			case 'IdLancamentoFinanceiro':
				$filtro_sql .=	" and LancamentoFinanceiro.IdLancamentoFinanceiro = '$filtro_valor'";
				break;
		}
		
	}else{
		$filtro_valor	=	"";
	}
		
	if($filtro_descrisao_servico!=''){
		$filtro_url .= "&DescricaoServico=".$filtro_descrisao_servico;
		$sqlAux = " left join Contrato on (
						LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato
					) left join OrdemServico on (
						LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and 
						LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico
					), 
					Servico".$sqlAux;
		$filtro_sql .= " and (
							Contrato.IdServico = Servico.IdServico OR 
							OrdemServico.IdServico = Servico.IdServico
						) and
						Servico.DescricaoServico like '%$filtro_descrisao_servico%'";
		
		if($filtro_id_servico!=""){
			$filtro_url .= "&IdServico=".$filtro_id_servico;
			$filtro_sql .= " and (
								Contrato.IdServico = $filtro_id_servico or 
								OrdemServico.IdServico = $filtro_id_servico
							)";
		}
	} elseif($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$sqlAux = " left join Contrato on (
						LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato
					) left join OrdemServico on (
						LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and 
						LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico
					)".$sqlAux;
		$filtro_sql .= " and (
							Contrato.IdServico = $filtro_id_servico or 
							OrdemServico.IdServico = $filtro_id_servico
						)";
	}
	
	if($filtro_juros!=""){
		$filtro_url .= "&Juros=".$filtro_juros;
	}
	
	if($filtro_soma!=""){
		$filtro_url .= "&Soma=".$filtro_soma;
	}
	
	if($filtro_cancelado!=""){
		$filtro_url .= "&Cancelado=".$filtro_cancelado;
		if($filtro_cancelado == 2 && $filtro_status == ""){
			$filtro_sql  .= " and ContaReceberDados.IdStatus != 0";
		}
	}
	
	if($filtro_nota_fiscal!=""){
		$filtro_url .= "&NotaFiscal=".$filtro_nota_fiscal;
	}
	
	if($filtro_impressao!=""){
		$filtro_url .= "&Boleto=".$filtro_impressao;
	}
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and
							AgenteAutorizado.IdGrupoPessoa is not null";
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
							Carteira.IdCarteira = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_endereco_xsl.php$filtro_url\"?>";
	echo "<db>";
	
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
	
	$cont	=	0;
	$sql	=	"select
					distinct
					ContaReceberDados.IdLoja,
					ContaReceberDados.IdContaReceber,
					ContaReceberDados.NumeroDocumento,
					ContaReceberDados.NumeroNF,
					ContaReceberDados.ModeloNF,
					ContaReceberDados.DataLancamento,
					(ContaReceberDados.ValorFinal) Valor,
					ContaReceberDados.ValorDesconto,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.IdLocalCobranca,
					ContaReceberDados.MD5,
					ContaReceberDados.IdStatus,
					Pessoa.TipoPessoa,
					PessoaEndereco.IdEstado,
					PessoaEndereco.IdCidade,
					PessoaEndereco.Bairro,
					PessoaEndereco.Endereco,
					substr(Pessoa.Nome,1,20) Nome,
					substr(Pessoa.RazaoSocial,1,20) RazaoSocial,
					Pessoa.CPF_CNPJ,
					LocalCobranca.AbreviacaoNomeLocalCobranca,
					LocalCobranca.PercentualMulta,
					LocalCobranca.PercentualJurosDiarios,
					LocalCobranca.IdLocalCobrancaLayout,
					LocalCobranca.IdDuplicataLayout,
					LancamentoFinanceiro.IdProcessoFinanceiro
					$filtro_sql_select
				from					
					LancamentoFinanceiroContaReceber,
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
					LocalCobranca,
					LancamentoFinanceiro
					$sqlAux
				where
					ContaReceberDados.IdLoja = $local_IdLoja and
					ContaReceberDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					ContaReceberDados.IdLoja = LancamentoFinanceiro.IdLoja and
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
					ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
					PessoaEndereco.IdPessoa = Pessoa.IdPessoa and
					ContaReceberDados.IdPessoa = PessoaEndereco.IdPessoa and
					ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco
					$filtro_sql
				order by
					ContaReceberDados.IdContaReceber
				$Limit";		    
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		
		if($filtro_sql_select == ''){
			$sql1	=	"select
							ContaReceberRecebimentoAtivo.DataRecebimento,
							ContaReceberRecebimento.MD5,
							ContaReceberRecebimentoAtivo.IdRecibo,
							ContaReceberRecebimentoAtivo.ValorRecebido,
							ContaReceberRecebimentoAtivo.IdLocalCobranca IdLocalCobrancaRecebimento
						from
							ContaReceberRecebimentoAtivo,
							ContaReceberRecebimento
						where
							ContaReceberRecebimentoAtivo.IdLoja = $lin[IdLoja] and 
							ContaReceberRecebimentoAtivo.IdContaReceber = $lin[IdContaReceber] and
							ContaReceberRecebimentoAtivo.IdLoja = ContaReceberRecebimento.IdLoja and
							ContaReceberRecebimentoAtivo.IdRecibo = ContaReceberRecebimento.IdRecibo and
							ContaReceberRecebimentoAtivo.IdContaReceber = ContaReceberRecebimento.IdContaReceber;";
			$res1	=	mysql_query($sql1,$con);
			if($lin1 = mysql_fetch_array($res1)){
				$lin[DataRecebimento]				= $lin1[DataRecebimento];
				$lin[Recibo]						= $lin1[MD5];
				$lin[IdRecibo]						= $lin1[IdRecibo];
				$lin[ValorRecebido]					= $lin1[ValorRecebido];
				$lin[IdLocalCobrancaRecebimento]	= $lin1[IdLocalCobrancaRecebimento];
			}
		}
		
		$sqlLancamentoFinanceiroDados = "select
											LancamentoFinanceiroDados.IdContrato,
											LancamentoFinanceiroDados.IdContaEventual,
											LancamentoFinanceiroDados.IdOrdemServico
										from
											LancamentoFinanceiroDados
										where
											IdLoja = $local_IdLoja and
											IdContaReceber = $lin[IdContaReceber]";
		$resLancamentoFinanceiroDados = mysql_query($sqlLancamentoFinanceiroDados,$con);
		$linLancamentoFinanceiroDados = mysql_fetch_array($resLancamentoFinanceiroDados);

		$lin[IdContrato]		= $linLancamentoFinanceiroDados[IdContrato];
		$lin[IdContaEventual]	= $linLancamentoFinanceiroDados[IdContaEventual];
		$lin[IdOrdemServico]	= $linLancamentoFinanceiroDados[IdOrdemServico];

		$query = 'true';
		
		if($lin[IdContrato]!=''){
			if($_SESSION["RestringirCarteira"] == true){
				$sqlTemp =	"select 
								AgenteAutorizadoPessoa.IdContrato 
							from 
								AgenteAutorizadoPessoa,
								Carteira 
							where 
								AgenteAutorizadoPessoa.IdLoja = $local_IdLoja and 
								AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
								AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
								Carteira.IdCarteira = $local_IdPessoaLogin and 
								Carteira.Restringir = 1 and 
								Carteira.IdStatus = 1 and
								AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
				$resTemp	=	@mysql_query($sqlTemp,$con);
				if(@mysql_num_rows($resTemp) == 0){
					$query = 'false';
				}
			}else{
				if($_SESSION["RestringirAgenteAutorizado"] == true){
					$sqlTemp =	"select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado
								where 
									AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
									AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1 and
									AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}
				if($_SESSION["RestringirAgenteCarteira"] == true){
					$sqlTemp		=	"select 
											AgenteAutorizadoPessoa.IdContrato
										from 
											AgenteAutorizadoPessoa,
											AgenteAutorizado,
											Carteira
										where 
											AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
											AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
											AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
											AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
											AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
											Carteira.IdCarteira = $local_IdPessoaLogin and 
											AgenteAutorizado.Restringir = 1 and 
											AgenteAutorizado.IdStatus = 1 and
											AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}
			}
		}
		
		if($query == 'true'){
		
			$lin[DataLancamentoTemp] 	= dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
			$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
			$lin[DataRecebimentoTemp] 	= dataConv($lin[DataRecebimento],"Y-m-d","d/m/Y");
			
			$lin[DataLancamento] 		= dataConv($lin[DataLancamento],"Y-m-d","Ymd");
			$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","Ymd");
			$lin[DataRecebimento] 		= dataConv($lin[DataRecebimento],"Y-m-d","Ymd");
			
			if($lin[Valor] == ""){				
				$lin[Valor]			=	0;		
			}
			
/*			if($lin[ValorDesconto]!=''){
				$lin[Valor]	=	$lin[Valor] - $lin[ValorDesconto];
			}
*/			
			$lin3[AbreviacaoNomeLocalCobranca]	=	"";
			
			switch($lin[IdStatus]){
				case '0': 
					$Color	  =	getParametroSistema(15,2);
					$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Canc.";
					$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";	
					$Target			= "_self";	
					break;
				case '1':
					#Status = 1 (Aguardando Pagamento)
					if($filtro_impressao == 1){	//Html
						$lin[Link]		= "boleto.php";
						$lin[Tipo]		= "html";
					} else{					//Pdf
						$lin[Link]		= "boleto.php";
						$lin[Tipo]		= "pdf";
					}
					
					if(file_exists($lin[Link])){
						$lin[Recebido]  = "N";
						$lin[MsgLink]	= "Boleto";
						$Target			= "_blank";	
						$lin[Link]		.= "?Tipo=$lin[Tipo]&ContaReceber=$lin[MD5]";
					} else{	
						$lin[Recebido]  = "N";
						$lin[MsgLink]	= "";
						$lin[Link]		= "";
						$Target			= "";	
					}
					
					$lin[DataRecebimento] 		= "";
					$lin[DataRecebimentoTemp] 	= "";	
					
					$Color	  = "";		
					$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";		
					
					
					// Vencimento alterado
					$sql6 = "select
								count(*) > 1 VencimentoAlterado
								from ContaReceberVencimento
								where IdLoja = $local_IdLoja and  
								IdContaReceber = $lin[IdContaReceber]";
					$res6 = @mysql_query($sql6,$con);
					$lin6 = @mysql_fetch_array($res6);
					if ($lin6[VencimentoAlterado]){
						$Color	 	=  	getCodigoInterno(45,1);
					}
					break;
				case '2':
					$lin[Recebido] 	= "S";
					$lin[MsgLink]	= "Recibo";
					$lin[Link]		= "recibo.php?Recibo=$lin[Recibo]";
					$Color	  		= getParametroSistema(15,3);		
					$ImgExc	  		= "../../img/estrutura_sistema/ico_del.gif";
					$Target			= "_blank";	
					
					if($lin[ValorRecebido] != '' && $lin[ValorRecebido] < $lin[Valor]){
						$Color	  		= getParametroSistema(15,7);		
					} 
					
					$sql3 = "select 
								ContaReceberRecebimentoTemp.QtdReciboAtivo,
								ContaReceberRecebimento.IdCaixa,
								LocalCobranca.AbreviacaoNomeLocalCobranca 
							from
								(
									select 
										count(*) QtdReciboAtivo,
										ContaReceberRecebimentoTemp.IdLocalCobranca 
									from
										(
											select 
												ContaReceberRecebimento.IdLoja,
												ContaReceberRecebimento.IdContaReceber,
												ContaReceberRecebimento.IdLocalCobranca,
												ContaReceberRecebimento.IdStatus 
											from
												ContaReceberRecebimento 
											where 
												ContaReceberRecebimento.IdLoja = $local_IdLoja and 
												ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and 
												ContaReceberRecebimento.IdStatus = 1 
											order by ContaReceberRecebimento.IdContaReceberRecebimento desc 
											limit 1
										) ContaReceberRecebimentoTemp,
										ContaReceberRecebimento 
									where ContaReceberRecebimentoTemp.IdLoja = ContaReceberRecebimento.IdLoja and
										ContaReceberRecebimentoTemp.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
										ContaReceberRecebimentoTemp.IdStatus = ContaReceberRecebimento.IdStatus
								) ContaReceberRecebimentoTemp,
								ContaReceberRecebimento,
								LocalCobranca 
							where 
								ContaReceberRecebimento.IdLoja = $local_IdLoja and 
								ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and 
								ContaReceberRecebimento.IdLoja = LocalCobranca.IdLoja and 
								case
									when ContaReceberRecebimento.IdLocalCobranca is null && ContaReceberRecebimentoTemp.IdLocalCobranca is null 
									then true
									when ContaReceberRecebimentoTemp.IdLocalCobranca is null 
									then ContaReceberRecebimento.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
									else ContaReceberRecebimentoTemp.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
								end 
							order by ContaReceberRecebimento.IdContaReceberRecebimento desc 
							limit 1;";
					$res3 = @mysql_query($sql3,$con);
					$lin3 = @mysql_fetch_array($res3);
				
					if($lin3[IdCaixa] != ''){
						$lin3[AbreviacaoNomeLocalCobranca] = "Caixa ".$lin3[IdCaixa];
					} else {
						if($lin3[QtdReciboAtivo] > 1){
							$lin3[AbreviacaoNomeLocalCobranca]	=	'***';
						}else{
							$lin3[AbreviacaoNomeLocalCobranca]	=	$lin3[AbreviacaoNomeLocalCobranca];
						}
					}
					
					// Pagamento em atraso
					$sql7 = "select
								ContaReceberRecebimento.DataRecebimento,
								ContaReceberVencimento.DataVencimento 
							from
								ContaReceberVencimento,
								ContaReceberRecebimento 
							where
								ContaReceberRecebimento.IdLoja = $local_IdLoja and
								ContaReceberRecebimento.IdLoja = ContaReceberVencimento.IdLoja and
								ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and
								ContaReceberRecebimento.IdContaReceber = ContaReceberVencimento.IdContaReceber";
					$res7 = @mysql_query($sql7,$con);
					$lin7 = @mysql_fetch_array($res7);
					
					if ($lin7[DataRecebimento] > $lin7[DataVencimento]){
						$Color	 	=  getCodigoInterno(44,1);
					}
					break;
				case 3:
					#Status = 3 (Aguardando Envio)									
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "";
					$lin[Link]		= "";
					$Target			= "";	
					
					$lin[DataRecebimento] 		= "";
					$lin[DataRecebimentoTemp] 	= "";	
					
					$Color	  = "";		
					$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";		
					
					// Vencimento alterado
					$sql6 = "select
								count(*) > 0 VencimentoAlterado
								from ContaReceberVencimento
								where IdLoja = $local_IdLoja and  
								IdContaReceber = $lin[IdContaReceber]";
					$res6 = @mysql_query($sql6,$con);
					$lin6 = @mysql_fetch_array($res6);
					if ($lin6[VencimentoAlterado]){
						$Color	 	= 	getCodigoInterno(45,1);
					}
					
					break;
				case 4:	
					#Status = 4 (Em Arquivo de Remessa)
					// Vencimento alterado
					$sql6 = "select
								count(*) > 0 VencimentoAlterado
								from ContaReceberVencimento
								where IdLoja = $local_IdLoja and  
								IdContaReceber = $lin[IdContaReceber]";
					$res6 = @mysql_query($sql6,$con);
					$lin6 = @mysql_fetch_array($res6);
					if ($lin6[VencimentoAlterado]){
						$Color	 =   getCodigoInterno(45,1);
					}
					break;
				case 5:	
					#Status = 5 (Compromisso Agendado)
					// Vencimento alterado
					$sql6 = "select
								count(*) > 0 VencimentoAlterado
								from ContaReceberVencimento
								where IdLoja = $local_IdLoja and  
								IdContaReceber = $lin[IdContaReceber]";
					$res6 = @mysql_query($sql6,$con);
					$lin6 = @mysql_fetch_array($res6);
					if ($lin6[VencimentoAlterado]){
						$Color	 	=  	getCodigoInterno(45,1);
					}
					
					break;	
				case 6:	
					#Status = 6 (Devolvido)
					if($filtro_impressao == 1){		//Html
						$lin[Link]		= "boleto.php";
						$lin[Tipo]		= "html";
					}else{							//Pdf
						$lin[Link]		= "boleto.php";
						$lin[Tipo]		= "pdf";
					}
					
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Devolvido";
					
					if(file_exists($lin[Link])){
						$lin[Link]		.= "?Tipo=$lin[Tipo]&ContaReceber=$lin[MD5]";
						$Target			= "_blank";										
					} else{
						$lin[Link]		= "";
						$Target			= "";
					}
					
					$Color	  = "";		
					$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";		
					
					break;
				
				case '7': 
					$Color	  =	getParametroSistema(15,2);
					$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Exc.";
					$lin[Link]		= "";	
					$Target			= "";	
					break;
					
			}
			if($filtro_juros == 1){
				// Titulo não recebido
				if($lin[IdStatus] != 0 && $lin[IdStatus] != 2 && $lin[IdStatus] != 7 && $lin[IdStatus] != 6){
					$sql4	=	"select BaseVencimento from ContaReceberBaseVencimento where IdLoja= $local_IdLoja and IdContaReceber = $lin[IdContaReceber]";
					$res4	=	@mysql_query($sql4,$con);
					$lin4	=	@mysql_fetch_array($res4);
					
					if($lin4[BaseVencimento] > 0){
						$lin[ValorRecebido]			=	$lin[Valor] + ($lin[Valor] * $lin[PercentualMulta] / 100) + ($lin[Valor] * $lin[PercentualJurosDiarios] / 100 * $lin4[BaseVencimento]);
						$lin[ValorRecebidoTemp]		=	'('.number_format($lin[ValorRecebido],2,",","").')';
					}else{
						$lin[ValorRecebido]			=	$lin[Valor];
						$lin[ValorRecebidoTemp]		=	number_format($lin[Valor],2,",","");
					}
				}else{
					if($lin[ValorRecebido] == ''){
						$lin[ValorRecebido]			=	0;	
					}
					
					$lin[ValorRecebidoTemp]		=	str_replace('.',',',formata_double($lin[ValorRecebido]));
				}
			}else{
				// Titulo não recebido
				if($lin[IdStatus] != 0 && $lin[IdStatus] != 2){
					$lin[ValorRecebido]			=	$lin[Valor];
					$lin[ValorRecebidoTemp]		=	'('.number_format($lin[Valor],2,",","").')';
				}else{
					if($lin[ValorRecebido] == ''){		$lin[ValorRecebido]			=	0;	}
						
					$lin[ValorRecebidoTemp]		=	str_replace('.',',',formata_double($lin[ValorRecebido]));
				}
			}
			
			$lin5[ValorRecebido] = $lin[ValorRecebido];
			if($lin[ValorRecebido] == "***"){
				$sql5	=	"select 
								ContaReceberRecebimento.IdLoja,
								ContaReceberRecebimento.IdContaReceber,
								count(0) AS QtdRecebimentosAtivo,
								ContaReceberRecebimento.DataRecebimento,
								ContaReceberRecebimento.IdRecibo,
								ContaReceberRecebimento.ValorRecebido,
								ContaReceberRecebimento.IdLocalCobranca,
								ContaReceberRecebimento.IdArquivoRetorno 
							from 
								ContaReceberRecebimento 
							where 
								(ContaReceberRecebimento.IdStatus <> 0) and
								(ContaReceberRecebimento.IdLoja = $lin[IdLoja]) and
								(ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber])
							group by 
								ContaReceberRecebimento.IdLoja,ContaReceberRecebimento.IdContaReceber";
				$res5	=	@mysql_query($sql5,$con);
				$lin5	=	@mysql_fetch_array($res5);
				
				$lin5[ValorRecebido] = $lin5[ValorRecebido]*2;
			}
			
			if($filtro_soma == 1){
				$lin[ValorSoma]			=	$lin[Valor];				
				$lin[ValorRecebidoSoma]	=	$lin5[ValorRecebido];				
			}else{
				if($lin[IdStatus] == 2){
					$lin[ValorSoma]			=	$lin[Valor];
					$lin[ValorRecebidoSoma]	=	$lin5[ValorRecebido];	
				}else{
					$lin[ValorSoma]			=	0;
					$lin[ValorRecebidoSoma]	=	0;
				}				
			}
			
		/*	if($filtro_soma == 1){
				if($lin[IdStatus] == 1){
					$lin[ValorSoma]			=	$lin[Valor];
					$lin[ValorRecebidoSoma]	=	0;
				}else{
					$lin[ValorSoma]			=	0;
					
					if($lin[IdStatus] == 2){
						$lin[ValorRecebidoSoma]	=	$lin5[ValorRecebido];
					}else{
						$lin[ValorRecebidoSoma]	=	0;
					}
				}
			}else{
				$lin[ValorSoma]			=	$lin[Valor];
				$lin[ValorRecebidoSoma]	=	$lin5[ValorRecebido];
			}*/
			
			if($lin[TipoPessoa]=='1'){
				$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
			}
			
			if($lin[NumeroNF] == null || $lin[ModeloNF] == null){
				$lin[ModeloNF] == '';
			} else{
				$lin[ModeloNF] = " Mod. ".$lin[ModeloNF];
			}
			
			echo "<reg>";	
			echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
			echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
			echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
			echo 	"<NumeroDocumento>$lin[NumeroDocumento]</NumeroDocumento>";
			echo 	"<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
			echo 	"<ModeloNF><![CDATA[$lin[ModeloNF]]]></ModeloNF>";
			echo 	"<NossoNumero>$lin[NossoNumero]</NossoNumero>";
			echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";	
			echo 	"<DescricaoLocalRecebimento><![CDATA[$lin3[AbreviacaoNomeLocalCobranca]]]></DescricaoLocalRecebimento>";	
			
			echo 	"<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
			echo 	"<DataLancamentoTemp><![CDATA[$lin[DataLancamentoTemp]]]></DataLancamentoTemp>";
			
			echo 	"<Valor>$lin[Valor]</Valor>";
			echo 	"<ValorRecebido>$lin[ValorRecebido]</ValorRecebido>";
			echo 	"<ValorRecebidoTemp><![CDATA[$lin[ValorRecebidoTemp]]]></ValorRecebidoTemp>";
			
			echo 	"<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
			echo 	"<DataVencimentoTemp><![CDATA[$lin[DataVencimentoTemp]]]></DataVencimentoTemp>";
			
			echo 	"<Recebido><![CDATA[$lin[Recebido]]]></Recebido>";
			echo 	"<MsgLink><![CDATA[$lin[MsgLink]]]></MsgLink>";
			echo 	"<Link><![CDATA[$lin[Link]]]></Link>";
			echo 	"<Color><![CDATA[$Color]]></Color>";
			echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
			echo 	"<Target><![CDATA[$Target]]></Target>";
			
			echo 	"<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
			echo 	"<DataRecebimentoTemp><![CDATA[$lin[DataRecebimentoTemp]]]></DataRecebimentoTemp>";
			echo 	"<ValorSoma><![CDATA[$lin[ValorSoma]]]></ValorSoma>";
			echo 	"<ValorRecebidoSoma><![CDATA[$lin[ValorRecebidoSoma]]]></ValorRecebidoSoma>";
			echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
			
			echo "</reg>";	
			
			$cont++;
			
			if($filtro_limit!= ""){
				if($cont >= $filtro_limit){
					break;
				}
			}
		}
	}
	
	echo "</db>";
?>