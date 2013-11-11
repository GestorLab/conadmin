<?
	$localModulo		=	1;
	$localOperacao		=	78;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_IdPessoaLogin			= $_SESSION['IdPessoa'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],"URL",false);
	$filtro_cidade					= url_string_xsl($_POST['filtro_cidade'],"URL",false);
	$filtro_local_cobranca			= url_string_xsl($_POST['filtro_local_cobranca'],"URL",false);
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_status					= $_POST['filtro_status'];
	$filtro_local_recebimento		= url_string_xsl($_POST['filtro_local_recebimento'],"URL",false);
	$filtro_data_inicio				= $_POST['filtro_data_inicio'];
	$filtro_data_fim				= $_POST['filtro_data_fim'];
	$filtro_id_servico				= $_POST['filtro_id_servico'];
	$filtro_descrisao_servico		= url_string_xsl($_POST['filtro_descrisao_servico'],"URL",false);
	$filtro_tipo_pessoa				= $_POST['filtro_tipo_pessoa'];
	$filtro_pais					= getCodigoInterno(3,1);;
	$filtro_estado					= url_string_xsl($_POST['filtro_estado'],"URL",false);
	$filtro_idPessoa				= $_GET['IdPessoa'];
	$filtro_contrato				= $_GET['IdContrato'];
	$filtro_conta_receber			= $_POST['IdContaReceber'];
	$filtro_ordem_servico			= $_POST['IdOrdemServico'];	
	$filtro_conta_eventual			= $_GET['IdContaEventual'];
	$filtro_carne					= $_GET['IdCarne'];
	$filtro_numero_documento		= $_GET['NumeroDocumento'];
	
	$filtro_cancelado				=	$_SESSION["filtro_cancelado"];
	$filtro_recebimento_cancelado	=	$_SESSION["filtro_conta_receber_recebimento_cancelado"];
	
	if($_GET['IdProcessoFinanceiro']!= ''){
		$filtro_processo_financeiro	= $_GET['IdProcessoFinanceiro'];
	}
	
	if($filtro_conta_receber == '' && $_GET['IdContaReceber']!=''){
		$filtro_conta_receber = $_GET['IdContaReceber'];
	}
	
	if($filtro_ordem_servico == '' && $_GET['IdOrdemServico']!=''){
		$filtro_ordem_servico = $_GET['IdOrdemServico'];
	}
	
	$codigo_de_barras			= $_POST['codigo_de_barras'];
	
	if($codigo_de_barras !=''){
		switch(strlen($codigo_de_barras)){
			case '44':
				$filtro_nosso_numero = (int)(substr($codigo_de_barras,32,10));
				break;
		}
	}
	 
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	$sqlAux		 = "";
	
	LimitVisualizacao("listar");	
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaReceberDados.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_ordem_servico!=""){
		$filtro_url  .= "&IdOrdemServico=".$filtro_ordem_servico;
		$filtro_sql .= " and LancamentoFinanceiroDados.IdOrdemServico= $filtro_ordem_servico";
		
		$filtro_idPessoa="";
	}
	
	if($filtro_idPessoa!=""){
		$filtro_url  .= "&IdPessoa=".$filtro_idPessoa;
		$filtro_sql .= " and Pessoa.IdPessoa = $filtro_idPessoa";
	}
	
	if($filtro_estado!=""){
		$filtro_url  .= "&IdEstado=".$filtro_estado;
		$filtro_sql .= " and PessoaEndereco.IdPais = $filtro_pais and PessoaEndereco.IdEstado = $filtro_estado";
	}

	if($filtro_carne!=""){
		$filtro_sql .= " and ContaReceberDados.IdCarne = $filtro_carne";
	}
	
	if($filtro_conta_receber!=""){
		$filtro_url  .= "&IdContaReceber=".$filtro_conta_receber;
		$filtro_sql .= " and LancamentoFinanceiroDados.IdContaReceber = $filtro_conta_receber";
	}
	
	if($filtro_contrato!=""){
		$filtro_url  .= "&IdContrato=".$filtro_contrato;
		$filtro_sql .= " and LancamentoFinanceiroDados.IdContrato = $filtro_contrato";
	}
	
	if($filtro_conta_eventual!=""){
		$filtro_url  .= "&IdContaEventual=".$filtro_conta_eventual;
		$filtro_sql .= " and LancamentoFinanceiroDados.IdContaEventual = $filtro_conta_eventual";
	}
	
	if($filtro_processo_financeiro!=""){
		$filtro_url  .= "&IdProcessoFinanceiro=".$filtro_processo_financeiro;
		$filtro_sql .= " and LancamentoFinanceiroDados.IdProcessoFinanceiro = $filtro_processo_financeiro";
	}
	
	if($filtro_local_recebimento!=""){
		$filtro_url  .= "&IdLocalCobrancaRecebimento=".$filtro_local_recebimento;
		$filtro_sql .= " and ContaReceberRecebimento.IdLocalCobranca = $filtro_local_recebimento";
	}
	
	if($filtro_numero_documento!=""){
		$filtro_sql  .= " and ContaReceberDados.NumeroDocumento = ".$filtro_numero_documento;
	}
	
	if($filtro_cidade!=""){
		$filtro_url  .= "&NomeCidade=".$filtro_cidade;
		$filtro_sql .= " and Cidade.NomeCidade like '%$filtro_cidade%'";
	}
	
	if($filtro_data_inicio!=""){
		$filtro_url  .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio	=	dataConv($filtro_data_inicio, "d/m/Y","Y-m-d");
		$filtro_sql .= " and ContaReceberRecebimento.DataRecebimento >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_fim!=""){
		$filtro_url  .= "&DataFim=".$filtro_data_fim;
		$filtro_data_fim	=	dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberRecebimento.DataRecebimento <= '$filtro_data_fim'";
	}
	
	if($filtro_id_servico!="" || $filtro_descrisao_servico!=''){
		$sqlAux =  ",
					LancamentoFinanceiroContaReceber,
					LancamentoFinanceiro left join Contrato on (
						LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato
					) left join OrdemServico on (
						LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and 
						LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico
					), 
					Servico";
		$filtro_sql .= " and 
						ContaReceberDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
						ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and 
						LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and 
						LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
						(
							Contrato.IdServico = Servico.IdServico OR 
							OrdemServico.IdServico = Servico.IdServico
						)";
		
		if($filtro_id_servico!=""){
			$filtro_url  .= "&IdServico=".$filtro_id_servico;
			$filtro_sql .= " and Servico.IdServico = '$filtro_id_servico'";
		}
		
		if($filtro_descrisao_servico!=''){
			$filtro_url .= "&DescricaoServico=".$filtro_descrisao_servico;
			$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descrisao_servico%'";
		}
	}
	
	if($filtro_tipo_pessoa!=""){
		$filtro_url  .= "&TipoPessoa=".$filtro_tipo_pessoa;
		$filtro_sql .= " and Pessoa.TipoPessoa = '$filtro_tipo_pessoa'";
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
		$filtro_sql  .= "  and ContaReceberDados.IdStatus != 7";
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
	
	if($filtro_cancelado != ""){
		$filtro_url  .= "&Cancelado=".$filtro_cancelado;
		if($filtro_cancelado == 2){
			$filtro_sql .= " and ContaReceberDados.IdStatus != 0";
		}
	}
	
	if($filtro_recebimento_cancelado != ""){
		if($filtro_recebimento_cancelado == 2){
			$filtro_sql .= " and ContaReceberRecebimento.IdStatus != 0";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_cidade_xsl.php$filtro_url\"?>";
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
					ContaReceberDados.DataLancamento,
					(ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas) Valor,
					ContaReceberDados.ValorDesconto,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.IdLocalCobranca,
					ContaReceberDados.IdPessoaEndereco,
					LocalCobranca.AbreviacaoNomeLocalCobranca,
					ContaReceberRecebimento.DataRecebimento,
					ContaReceberRecebimento.MD5 Recibo,
					ContaReceberRecebimento.IdRecibo,
					ContaReceberRecebimento.ValorRecebido,
					ContaReceberRecebimento.IdLocalCobranca IdLocalCobrancaRecebimento,
					ContaReceberRecebimento.IdStatus IdStatusContaReceberRecebimento,
					ContaReceberRecebimento.IdCaixa,
					ContaReceberDados.IdStatus,
					ContaReceberDados.MD5,
					LocalCobranca.IdLocalCobrancaLayout,
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,18) Nome,
					substr(Pessoa.RazaoSocial,1,18) RazaoSocial,
					LancamentoFinanceiroDados.IdProcessoFinanceiro,
					Cidade.NomeCidade,
					Estado.SiglaEstado
				from
					ContaReceberDados,
					ContaReceberRecebimento,
					LocalCobranca,
					LancamentoFinanceiroDados,
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
					Cidade	
					$sqlAux
				where
					ContaReceberDados.IdLoja = $local_IdLoja and
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberDados.IdLoja = LancamentoFinanceiroDados.IdLoja and
					ContaReceberDados.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber and
					ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
					ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and
					ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
					LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					Cidade.IdCidade = PessoaEndereco.IdCidade and
					Pais.IdPais = PessoaEndereco.IdPais and
					Pais.IdPais = Estado.IdPais and
					Pais.IdPais = Cidade.IdPais and
					Estado.IdEstado = PessoaEndereco.IdEstado and
					Estado.IdEstado = Cidade.IdEstado 
					$filtro_sql
				order by
					ContaReceberDados.IdContaReceber desc
				$Limit ";		    
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
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
		
		/*
		if($lin[IdContaEventual]!=''){
			$sql2	=	"select IdContrato from ContaEventual where IdLoja = $local_IdLoja and IdContaEventual = $lin[IdContaEventual]";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			if($lin2[IdContrato]!=""){
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
									AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
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
										AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
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
												AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
				}
			}
		}
		
		if($lin[IdOrdemServico]!=''){
			$sql2	=	"select IdContrato,IdContratoFaturamento from OrdemServico where IdLoja = $local_IdLoja and IdOrdemServico = $lin[IdOrdemServico]";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			if($lin2[IdContrato]!="" ||  $lin2[IdContratoFaturamento]!=""){
			
				if($lin2[IdContrato]!=""){
					$aux	.=	" and AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
				}
				
				if($lin2[IdContrato]!="" && $lin2[IdContratoFaturamento]!=""){
					$aux	.=	" or";
				}else{
					$aux	.=	" and";
				}
				
				if($lin2[IdContratoFaturamento]!=""){
					$aux	.=	" AgenteAutorizadoPessoa.IdContrato = $lin2[IdContratoFaturamento]";
				}
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
									Carteira.IdStatus = 1 $aux";
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
										AgenteAutorizado.IdStatus = 1 $aux";
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
												AgenteAutorizado.IdStatus = 1 $aux";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
				}
			}
		}*/
		
		if($query == 'true'){
			$lin[DataLancamentoTemp] 	= dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
			$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
			$lin[DataRecebimentoTemp] 	= dataConv($lin[DataRecebimento],"Y-m-d","d/m/Y");
			
			$lin[DataLancamento] 		= dataConv($lin[DataLancamento],"Y-m-d","Ymd");
			$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","Ymd");
			$lin[DataRecebimento] 		= dataConv($lin[DataRecebimento],"Y-m-d","Ymd");
			
			if($lin[Valor] == ""){				$lin[Valor]			=	0;		}
			
			if($lin[ValorDesconto]!='')	$lin[Valor]	=	$lin[Valor] - $lin[ValorDesconto];
			
			if($lin[IdCaixa] != ''){
				$lin2 = array("AbreviacaoNomeLocalCobranca" => "Caixa ".$lin[IdCaixa]);
			} else {
				$sql2 = "select 
							AbreviacaoNomeLocalCobranca 
						from 
							LocalCobranca 
						where 
							IdLoja = $local_IdLoja and 
							IdLocalCobranca = '$lin[IdLocalCobrancaRecebimento]'";
				$res2 = mysql_query($sql2,$con);
				$lin2 = mysql_fetch_array($res2);
			}
			
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
					#Status = 1 (Recebido)
					$lin[Link]		= "boleto.php";
					if(file_exists($lin[Link])){
						$lin[Recebido]  = "N";
						$lin[MsgLink]	= "Boleto";
						$Target			= "_blank";	
						$lin[Link]		= "boleto.php?Tipo=html&ContaReceber=$lin[MD5]";
					}else{	
						$lin[Recebido]  = "N";
						$lin[MsgLink]	= "";
						$lin[Link]		= "";
						$Target			= "";	
					}
					$Color	  = "";		
					$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";		
					break;
				case '2':
					$lin[Recebido] 	= "S";
					$Color	  		= getParametroSistema(15,3);		
					$ImgExc	  		= "../../img/estrutura_sistema/ico_del.gif";
					$Target			= "_blank";		
					
					if($lin[ValorRecebido] != '' && $lin[ValorRecebido] < $lin[Valor]){
						$Color = getParametroSistema(15,7);		
					} 
					
					switch($lin[IdStatusContaReceberRecebimento]){
						case '0':
							$lin[MsgLink]	= "Canc.";
							$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";	
							$Color			= "#FFD2D2";
							break;
						case '3':
							$lin[MsgLink]	= "Estorno";
							$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";	
							$Color			= "#FFD2D2";
							break;
						default:
							$lin[Link]		= "recibo.php?Recibo=$lin[Recibo]";
							$lin[MsgLink]	= "Recibo";
					}
					break;
			}
			
			if($lin[ValorRecebido] != ''){
				$lin[ValorRecebidoTemp]		=	str_replace('.',',',formata_double($lin[ValorRecebido]));
			}else{
				$lin[ValorRecebidoTemp]		=	'';
				$lin[ValorRecebido]			=	0;
			}
			
			if($lin[TipoPessoa]=='1'){
				$lin[Nome]	=	$lin[getCodigoInterno(3,24)];
			}
			
			$lin[NomeCidade]	=	$lin[NomeCidade]."-".$lin[SiglaEstado];
			
			echo "<reg>";	
			echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
			echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
			echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
			echo 	"<NumeroDocumento>$lin[NumeroDocumento]</NumeroDocumento>";
			echo 	"<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
			echo 	"<NossoNumero>$lin[NossoNumero]</NossoNumero>";
			echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";	
			echo 	"<DescricaoLocalRecebimento><![CDATA[$lin2[AbreviacaoNomeLocalCobranca]]]></DescricaoLocalRecebimento>";	
			
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
			
			echo 	"<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
			
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