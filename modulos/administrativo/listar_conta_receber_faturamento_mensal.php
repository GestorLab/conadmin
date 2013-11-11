<?
	set_time_limit(0);
	$localModulo		=	1;
	$localOperacao		=	17;
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
	$filtro_somar_desconto				= $_POST['filtro_somar_desconto'];
	$filtro_campo						= $_POST['filtro_campo'];
	$filtro_local_cobranca				= $_POST['filtro_local_cobranca'];
	$filtro_limit						= $_POST['filtro_limit'];
	$filtro_status						= $_POST['filtro_status'];
	$filtro_tipo_pessoa					= $_POST['filtro_tipo_pessoa'];
	$filtro_carne						= $_POST['filtro_carne'];
	$filtro_id_servico					= $_POST['filtro_id_servico'];
	$filtro_cpf_cnpj					= $_POST['filtro_cpf_cnpj'];
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
	$filtro_tipo_relatorio				= 1;//$_SESSION["filtro_tipo_relatorio"];
	$ValorTotal 						= "0.00";
	$ValorRecebidoSoma 					= "0.00";
	
	$filtro_cancelado	=	$_SESSION["filtro_cancelado"];
	$filtro_juros		=	$_SESSION["filtro_juros"];
	$filtro_soma		=	$_SESSION["filtro_soma"];
	$filtro_nota_fiscal	=	$_SESSION["filtro_nota_fiscal"];
	$filtro_impressao	=	$_SESSION["filtro_impressao"];
	$filtro_conta_receber_nota_fiscal 			= $_SESSION["filtro_conta_receber_nota_fiscal"];
	$filtro_subtrair_desconto_conceber 			= $_SESSION["filtro_subtrair_desconto_conceber"];
	$filtro_contabiliza_recebimentos_vencimento = $_SESSION["filtro_contabiliza_recebimentos_vencimento"];
	$ValorFinalSoma = 0;
	
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

	$sqlAux = "";

	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_tipo_relatorio != ""){
		$filtro_url .= "&TipoRelatorio=".$filtro_tipo_relatorio;
	}
	
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
				$filtro_url .= "&DescricaoServico=".$filtro_descrisao_servico;
				$sqlAux = " left join Contrato on (
								LancamentoFinanceiroDados.IdLoja = Contrato.IdLoja and 
								LancamentoFinanceiroDados.IdContrato = Contrato.IdContrato
							) left join OrdemServico on (
								LancamentoFinanceiroDados.IdLoja = OrdemServico.IdLoja and 
								LancamentoFinanceiroDados.IdOrdemServico = OrdemServico.IdOrdemServico
							), 
							Servico".$sqlAux;
				$filtro_sql .= " and (
									Contrato.IdServico = Servico.IdServico OR 
									OrdemServico.IdServico = Servico.IdServico
								) and
								Servico.DescricaoServico like '%$filtro_descrisao_servico%'";
				break;
			case 'IdProcessoFinanceiro':
				$filtro_sql .=	" and LancamentoFinanceiroDados.IdProcessoFinanceiro = '$filtro_valor'";
				break;
			case 'NumeroDocumento':
				$filtro_sql .=	" and ContaReceberDados.NumeroDocumento = '$filtro_valor'";
				break;
			case 'NossoNumero':
				$filtro_sql .=	" and ContaReceberDados.NossoNumero = '$filtro_valor'";
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
				$filtro_sql .=	" and LancamentoFinanceiroDados.IdLancamentoFinanceiro = '$filtro_valor'";
				break;
		}
		
	}else{
		$filtro_valor	=	"";
	}
		
	if($filtro_descrisao_servico!=''){
		$filtro_url .= "&DescricaoServico=".$filtro_descrisao_servico;
		$sqlAux = " left join Contrato on (
						LancamentoFinanceiroDados.IdLoja = Contrato.IdLoja and 
						LancamentoFinanceiroDados.IdContrato = Contrato.IdContrato
					) left join OrdemServico on (
						LancamentoFinanceiroDados.IdLoja = OrdemServico.IdLoja and 
						LancamentoFinanceiroDados.IdOrdemServico = OrdemServico.IdOrdemServico
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
						LancamentoFinanceiroDados.IdLoja = Contrato.IdLoja and 
						LancamentoFinanceiroDados.IdContrato = Contrato.IdContrato
					) left join OrdemServico on (
						LancamentoFinanceiroDados.IdLoja = OrdemServico.IdLoja and 
						LancamentoFinanceiroDados.IdOrdemServico = OrdemServico.IdOrdemServico
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
	
	$limitenome		= '20';
	$limitecidade	= '15';
	header ("content-type: text/xml");	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_faturamento_mensal_xsl.php$filtro_url\"?>";
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
	$sql	=	"SELECT
						DISTINCT
						ContaReceberDados.IdContaReceber,	
						Servico.IdServico,
						SUBSTRING(Servico.DescricaoServico,1,25) DescricaoServico,
						ServicoGrupo.DescricaoServicoGrupo,
						SUM(LancamentoFinanceiroDados.Valor) Valor,
						SUM(ContaReceberDados.ValorDesconto) ValorDesconto
					FROM					
						Servico,
						ServicoGrupo,
						ContaReceberDados,
						LancamentoFinanceiroDados
						$sqlAux
					WHERE
						ContaReceberDados.IdLoja = $local_IdLoja AND
						LancamentoFinanceiroDados.IdLoja = ContaReceberDados.IdLoja AND
						LancamentoFinanceiroDados.IdContaReceber = ContaReceberDados.IdContaReceber AND
						Servico.IdLoja 	= LancamentoFinanceiroDados.IdLoja AND
						Servico.IdServico = LancamentoFinanceiroDados.IdServico AND
						ServicoGrupo.IdLoja = Servico.IdLoja AND
						ServicoGrupo.IdServicoGrupo = Servico.IdServicoGrupo AND
						ContaReceberDados.IdStatus != 7 AND
						ContaReceberDados.IdStatus != 0
						$filtro_sql
						GROUP BY
							Servico.IdServico
				$Limit";	
	$res	=	mysql_query($sql,$con);
	$IdServicoAnterior = 1;
	while($lin	=	mysql_fetch_array($res)){
		$ValorMultaJuro = "";
		if($filtro_sql_select == ''){
			$sql1	=	"select
							ContaReceberRecebimento.DataRecebimento,
							ContaReceberRecebimento.MD5,
							ContaReceberRecebimento.IdRecibo,
							ContaReceberRecebimento.ValorRecebido,
							ContaReceberRecebimento.ValorMoraMulta,
							ContaReceberRecebimento.IdLocalCobranca IdLocalCobrancaRecebimento
						from
							ContaReceberRecebimento
						where
							ContaReceberRecebimento.IdLoja = '$local_IdLoja' and 
							ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber]";
			$res1	=	mysql_query($sql1,$con);
			if($lin1 = mysql_fetch_array($res1)){
				if($filtro_contabiliza_recebimentos_vencimento == 1){
					$vencimento  = str_replace("-","",$lin[DataVencimento]);
					$recebimento = str_replace("-","",$lin1[DataRecebimento]);
					$ValorMultaJuro = $lin1[ValorMultaJuro];
					
					if($vencimento >= $recebimento){
						$lin[DataRecebimento]				= $lin1[DataRecebimento];
						$lin[Recibo]						= $lin1["MD5"];
						$lin[IdRecibo]						= $lin1[IdRecibo];
						$lin[ValorRecebido]					= $lin1[ValorRecebido];
						$lin[IdLocalCobrancaRecebimento]	= $lin1[IdLocalCobrancaRecebimento];
					}else{
						$lin[DataRecebimento]				= $lin1[DataRecebimento];
						$lin[Recibo]						= $lin1["MD5"];
						$lin[IdRecibo]						= $lin1[IdRecibo];
						$lin[ValorRecebido]					= $lin1[ValorRecebido];
						$lin[ValorSomatorio]				= $lin1[ValorRecebido];
						$lin[IdLocalCobrancaRecebimento]	= $lin1[IdLocalCobrancaRecebimento];
					}
					
				} else{
					$lin[DataRecebimento]				= $lin1[DataRecebimento];
					$lin[Recibo]						= $lin1["MD5"];
					$lin[IdRecibo]						= $lin1[IdRecibo];
					$lin[ValorRecebido]					= $lin1[ValorRecebido];
					$lin[IdLocalCobrancaRecebimento]	= $lin1[IdLocalCobrancaRecebimento];
				}
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
			
			$lin3[AbreviacaoNomeLocalCobranca]	=	"";
		
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
					
					if(is_numeric($lin[ValorRecebido]))
						$lin[ValorRecebidoTemp]		=	str_replace('.',',',formata_double($lin[ValorRecebido]));
				}
			}else{
				// Titulo não recebido
				if($lin[IdStatus] != 0 && $lin[IdStatus] != 2){
					$lin[ValorRecebido]			=	$lin[Valor];
					$lin[ValorRecebidoTemp]		=	'('.number_format($lin[Valor],2,",","").')';
				}else{
					if($lin[ValorRecebido] == ''){		$lin[ValorRecebido]			=	0;	}
					
					if(is_numeric($lin[ValorRecebido]))					
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
			if($lin[ValorSomatorio] != ""){
				$lin[ValorRecebidoSoma] -= $lin[ValorSomatorio];
			}
			
			if($lin[TipoPessoa]=='1'){
				$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
			}
			
			if($lin[NumeroNF] == null || $lin[ModeloNF] == null){
				$lin[ModeloNF] == '';
			} else{
				$lin[ModeloNF] = " Mod. ".$lin[ModeloNF];
			}
			
			$ValorFinal = $lin[Valor] - $lin[ValorDesconto];
			$ValorFinal = number_format($ValorFinal,2,',','.');
			$lin[Valor] = number_format($lin[Valor]+$ValorMultaJuro,2,',','.');
			echo $ValorMultaJuro;
			
			/*echo "<reg>";	
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
			echo 	"<DescricaoServicoGrupo><![CDATA[$lin[DescricaoServicoGrupo]]]></DescricaoServicoGrupo>";
			echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			echo 	"<IdServico>$lin[IdServico]</IdServico>";
			echo 	"<ValorDesconto>".number_format($lin[ValorDesconto],2,',','.')."</ValorDesconto>";
			echo 	"<ValorFinal>".$ValorFinal."</ValorFinal>";
			echo 	"<ValorFinalSoma>".$ValorFinalSoma."</ValorFinalSoma>";
			
			$ValorTotal 		= $lin[ValorSoma];
			$ValorFinalSoma 	= $ValorFinal;
			$ValorRecebidoSoma 	= $lin[ValorRecebidoSoma];
			
			echo "</reg>";	*/
		}
		$cont++;
		
		if($filtro_limit!= ""){
			if($cont >= $filtro_limit){
				break;
			}
		}
	}
	#echo "</db>";
?>