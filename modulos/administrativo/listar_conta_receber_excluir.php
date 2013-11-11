<?
	$localModulo		=	1;
	$localOperacao		=	58;
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
	$filtro_id_conta_receber		= $_POST['filtro_id_conta_receber'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_valor					= $_POST['filtro_valor'];
	$filtro_campo					= $_POST['filtro_campo'];
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	$filtro_idPessoa				= $_GET['IdPessoa'];
	$filtro_contrato				= $_GET['IdContrato'];
	$filtro_conta_receber			= $_POST['IdContaReceber'];
	$filtro_ordem_servico			= $_POST['IdOrdemServico'];	
	$filtro_conta_eventual			= $_GET['IdContaEventual'];
	$filtro_carne					= $_GET['IdCarne'];
	$filtro_numero_documento		= $_GET['NumeroDocumento'];
	
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
		
	if($filtro_id_conta_receber!=""){
		$filtro_url  .= "&ContaReceber=".$filtro_id_conta_receber;
		$filtro_sql .= " and ContaReceberDados.IdContaReceber = $filtro_id_conta_receber";
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
	
	if($filtro_ordem_servico!=""){
		$filtro_url  .= "&IdOrdemServico=".$filtro_ordem_servico;
		$filtro_sql .= " and LancamentoFinanceiroDados.IdOrdemServico= $filtro_ordem_servico";
		
		$filtro_idPessoa="";
	}
	
	if($filtro_idPessoa!=""){
		$filtro_url  .= "&IdPessoa=".$filtro_idPessoa;
		$filtro_sql .= " and Pessoa.IdPessoa = $filtro_idPessoa";
	}

	if($filtro_carne!=""){
		$filtro_sql .= " and ContaReceberDados.IdCarne = $filtro_carne";
	}
	
	if($filtro_conta_receber!=""){
		$filtro_url  .= "&IdContaReceber=".$filtro_conta_receber;
		$filtro_sql .= " and ContaReceberDados.IdContaReceber = $filtro_conta_receber";
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
	
	if($filtro_numero_documento!=""){
		$filtro_sql  .= " and ContaReceberDados.NumeroDocumento = ".$filtro_numero_documento;
	}
	
	if($filtro_valor!=""){
		$filtro_url .= "&Valor=".$filtro_valor;
	}
				
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		switch($filtro_campo){
			case 'DataLancamento':
				$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
				$filtro_sql .= " and ContaReceberDados.DataLancamento = '$filtro_valor'";
				break;
			case 'DataVencimento':
				$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
				$filtro_sql .= " and ContaReceberDados.DataVencimento like '$filtro_valor%'";
				break;
			case 'MesLancamento':
				$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
				$filtro_sql .= " and ContaReceberDados.DataLancamento like '$filtro_valor%'";
				break;
			case 'MesVencimento':
				$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
				$filtro_sql .= " and ContaReceberDados.DataVencimento = '$filtro_valor'";
				break;
			case 'DescricaoServico':
				$filtro_sql .=	" and LancamentoFinanceiroDados.IdServico in (select IdServico from Servico where IdLoja = $local_IdLoja and DescricaoServico like '%$filtro_valor%')";
				break;
			case 'IdProcessoFinanceiro':
				$filtro_sql .=	" and LancamentoFinanceiroDados.IdProcessoFinanceiro = '$filtro_valor'";
				break;
			case 'NumeroDocumento':
				$filtro_sql .=	" and ContaReceberDados.NumeroDocumento = '$filtro_valor'";
				break;
			case 'NumeroNF':
				$filtro_sql .=	" and ContaReceberDados.NumeroNF like '%$filtro_valor%'";
				break;
		}
		
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_excluir_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro != "s"){
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
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,20) Nome,
					substr(Pessoa.RazaoSocial,1,20) RazaoSocial,
					ContaReceberDados.NumeroDocumento,
					ContaReceberDados.NumeroNF,
					ContaReceberDados.DataLancamento,
					(ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas) Valor,
					ContaReceberDados.ValorDesconto,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.IdLocalCobranca,
					LocalCobranca.AbreviacaoNomeLocalCobranca,
					LocalCobranca.PercentualMulta,
					LocalCobranca.PercentualJurosDiarios,
					LancamentoFinanceiroDados.IdProcessoFinanceiro,
					ContaReceberRecebimento.DataRecebimento,
					ContaReceberRecebimento.IdRecibo,
					ContaReceberRecebimento.ValorRecebido,
					ContaReceberRecebimento.IdLocalCobranca IdLocalCobrancaRecebimento,
					ContaReceberDados.IdStatus,
					LocalCobranca.IdLocalCobrancaLayout
				from
					LancamentoFinanceiroDados,
					LancamentoFinanceiroContaReceber,
					ContaReceberDados LEFT JOIN (select ContaReceberRecebimento.IdLoja, ContaReceberRecebimento.IdContaReceber, ContaReceberRecebimento.DataRecebimento, ContaReceberRecebimento.IdRecibo, ContaReceberRecebimento.ValorRecebido, ContaReceberRecebimento.IdLocalCobranca, ContaReceberRecebimento.IdStatus from ContaReceberRecebimento, (select IdLoja, IdContaReceber, max(IdContaReceberRecebimento) IdContaReceberRecebimento from ContaReceberRecebimento group by IdLoja, IdContaReceber) ContaReceberRecebimentoUltimo where ContaReceberRecebimento.IdLoja = ContaReceberRecebimentoUltimo.IdLoja and ContaReceberRecebimento.IdContaReceber = ContaReceberRecebimentoUltimo.IdContaReceber and ContaReceberRecebimento.IdContaReceberRecebimento = ContaReceberRecebimentoUltimo.IdContaReceberRecebimento) ContaReceberRecebimento On (ContaReceberRecebimento.IdLoja = ContaReceberDados.IdLoja and ContaReceberRecebimento.IdContaReceber = ContaReceberDados.IdContaReceber and ContaReceberRecebimento.IdStatus != 0),
					Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					LocalCobranca $sqlAux	
				where
					LancamentoFinanceiroDados.IdLoja = $local_IdLoja and
					LancamentoFinanceiroDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiroDados.IdLoja = ContaReceberDados.IdLoja and
					LancamentoFinanceiroDados.IdLoja = LocalCobranca.IdLoja and
					LancamentoFinanceiroDados.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberDados.IdContaReceber and
					LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberDados.IdStatus = 0 
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
		
		if($query == 'true'){
		
			$lin[DataLancamentoTemp] 	= dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
			$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
			
			$lin[DataLancamento] 		= dataConv($lin[DataLancamento],"Y-m-d","Ymd");
			$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","Ymd");
			
			if($lin[Valor] == ""){				$lin[Valor]			=	0;		}
			
			if($lin[ValorDesconto]!='')	$lin[Valor]	=	$lin[Valor] - $lin[ValorDesconto];
		
			$Color	  =	getParametroSistema(15,2);
			$lin[Recebido]  = "N";
			$lin[MsgLink]	= "Canc.";
			$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";	
			$Target			= "_self";	
			
			// Titulo não recebido
			if($lin[IdStatus] != 0 && $lin[IdStatus] != 2){
				$lin[ValorRecebido]			=	$lin[Valor];
				$lin[ValorRecebidoTemp]		=	'('.number_format($lin[Valor],2,",","").')';
			}else{
				if($lin[ValorRecebido] == ''){		$lin[ValorRecebido]			=	0;	}
					
				$lin[ValorRecebidoTemp]		=	str_replace('.',',',formata_double($lin[ValorRecebido]));
			}
			
			$lin[ValorSoma]			=	$lin[Valor];
			$lin[ValorRecebidoSoma]	=	$lin[ValorRecebido];
			
			
			if($lin[TipoPessoa]=='1'){
				$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
			}
			
			echo "<reg>";	
			echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
			echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
			echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
			echo 	"<NumeroDocumento>$lin[NumeroDocumento]</NumeroDocumento>";
			echo 	"<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
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
