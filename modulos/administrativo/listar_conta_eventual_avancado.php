<?
	$localModulo		=	1;
	$localOperacao		=	94;
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
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_valor					= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_campo					= $_POST['filtro_campo'];
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_status					= $_POST['filtro_status'];
	$filtro_carne					= $_POST['filtro_carne'];

	$filtro_idPessoa				= $_GET['IdPessoa'];
	$filtro_contrato				= $_GET['IdContrato'];
	$filtro_conta_receber			= $_GET['IdContaReceber'];
	$filtro_ordem_servico			= $_GET['IdOrdemServico'];	
	$filtro_conta_eventual			= $_GET['IdContaEventual'];	
	$filtro_arquivo_retorno			= $_GET['IdArquivoRetorno'];	
	$filtro_processo_financeiro		= $_GET['IdProcessoFinanceiro'];	
	
	$filtro_cancelado	=	$_SESSION["filtro_cancelado"];
	$filtro_juros		=	$_SESSION["filtro_juros"];
	$filtro_soma		=	$_SESSION["filtro_soma"];
	$filtro_nota_fiscal	=	$_SESSION["filtro_nota_fiscal"];
	$filtro_impressao	=	$_SESSION["filtro_impressao"];
	
	if($filtro_carne == '' && $_GET['IdCarne']!=''){
		$filtro_carne	= $_GET['IdCarne'];
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
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaReceberDados.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_arquivo_retorno!=""){
		$filtro_sql .= " and ContaReceberRecebimento.IdArquivoRetorno = $filtro_arquivo_retorno";
		
		if($_GET['IdLocalCobranca']!=""){
			$filtro_sql .= " and ContaReceberRecebimento.IdLocalCobranca = ".$_GET['IdLocalCobranca'];
		}
	}else{
		$filtro_status_receb .=	" and ContaReceberRecebimento.IdStatus != 0";
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
		$filtro_url  .= "&IdCarne=".$filtro_carne;
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
	
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=".$filtro_valor;
	
		switch($filtro_campo){
			case 'DataLancamento':
				$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
				$filtro_sql .= " and ContaReceberDados.DataLancamento = '$filtro_valor'";
				break;
			case 'DataVencimento':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .= " and ContaReceberDados.DataVencimento = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberDados.DataVencimento is NULL";
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
			case 'MesVencimento':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and substring(ContaReceberDados.DataVencimento,1,7) = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberDados.DataVencimento is NULL";
				}
				break;
			case 'DescricaoContaEventual':
				$filtro_sql .=	"  and ContaEventual.IdContaEventual in (select IdContaEventual from ContaEventual where DescricaoContaEventual like '%$filtro_valor%')";
				break;
			case 'IdProcessoFinanceiro':
				$filtro_sql .=	" and LancamentoFinanceiroDados.IdProcessoFinanceiro = '$filtro_valor'";
				break;
			case 'NumeroDocumento':
				$filtro_sql .=	" and ContaReceberDados.NumeroDocumento = '$filtro_valor'";
				break;
			case 'NumeroNF':
				$filtro_sql .=	" and ContaReceberDados.NumeroNF = '$filtro_valor'";
				break;
			case 'IdContaReceber':
				$filtro_sql .=	" and ContaReceberDados.IdContaReceber = '$filtro_valor'";
				break;
		}
		
	}else{
		$filtro_valor	=	"";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_eventual_avancado_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
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
					ContaReceberDados.IdStatus,
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,20) Nome,
					substr(Pessoa.RazaoSocial,1,20) RazaoSocial,
					LocalCobranca.AbreviacaoNomeLocalCobranca,
					LocalCobranca.PercentualMulta,
					LocalCobranca.PercentualJurosDiarios,
					LocalCobranca.IdLocalCobrancaLayout,
					LancamentoFinanceiroDados.IdProcessoFinanceiro,
					LancamentoFinanceiroDados.IdContrato,
					LancamentoFinanceiroDados.IdContaEventual,
					LancamentoFinanceiroDados.IdOrdemServico,
					ContaReceberRecebimento.DataRecebimento,
					ContaReceberRecebimento.IdRecibo,
					ContaReceberRecebimento.ValorRecebido,
					ContaReceberRecebimento.IdLocalCobranca IdLocalCobrancaRecebimento

				from
					ContaReceberDados left join ContaReceberRecebimento on (
						ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and 
						ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber
					),
					LancamentoFinanceiroDados,
					ContaEventual,
					Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					LocalCobranca
					$sqlAux
				where
					ContaReceberDados.IdLoja = $local_IdLoja and
					ContaReceberDados.IdLoja = LancamentoFinanceiroDados.IdLoja and
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
					ContaReceberDados.IdLoja = ContaEventual.IdLoja and
					ContaReceberDados.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa and
					LancamentoFinanceiroDados.IdContaEventual = ContaEventual.IdContaEventual and
					LancamentoFinanceiroDados.IdContaEventual != '' 
					$filtro_sql 
				order by
					ContaEventual.IdContaEventual desc, 
					ContaReceberDados.IdContaReceber desc
				$Limit";		    
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin[DataLancamentoTemp] 	= dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
		$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
		$lin[DataRecebimentoTemp] 	= dataConv($lin[DataRecebimento],"Y-m-d","d/m/Y");
		
		$lin[DataLancamento] 		= dataConv($lin[DataLancamento],"Y-m-d","Ymd");
		$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","Ymd");
		$lin[DataRecebimento] 		= dataConv($lin[DataRecebimento],"Y-m-d","Ymd");
		
		if($lin[Valor] == ""){				
			$lin[Valor]			=	0;		
		}
		
		if($lin[ValorDesconto]!=''){
			$lin[Valor]	=	$lin[Valor] - $lin[ValorDesconto];
		}
		
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
					$lin[Link]		= "local_cobranca/$lin[IdLocalCobrancaLayout]/index.php";
				}else{					//Pdf
					$lin[Link]		= "local_cobranca/$lin[IdLocalCobrancaLayout]/pdf.php";
				}
				if(file_exists($lin[Link])){
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Boleto";
					$Target			= "_blank";	
					$lin[Link]		.= "?IdLoja=$local_IdLoja&IdContaReceber=$lin[IdContaReceber]";
				}else{	
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "";
					$lin[Link]		= "";
					$Target			= "";	
				}
				
				$lin[DataRecebimento] 		= "";
				$lin[DataRecebimentoTemp] 	= "";	
				
				$Color	  = "";		
				$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";		
				
				break;
			case '2':
				$lin[Recebido] 	= "S";
				$lin[MsgLink]	= "Recibo";
				$lin[Link]		= "recibo.php?IdLoja=$local_IdLoja&IdRecibo=$lin[IdRecibo]";
				$Color	  		= getParametroSistema(15,3);		
				$ImgExc	  		= "../../img/estrutura_sistema/ico_del.gif";
				$Target			= "_blank";	
				
				if($lin[ValorRecebido] != '' && $lin[ValorRecebido] < $lin[Valor]){
					$Color	  		= getParametroSistema(15,7);		
				} 
				
				$sql3	=	"select 
								AbreviacaoNomeLocalCobranca 
						from 
								ContaReceberRecebimento,
								LocalCobranca 
						where 
								ContaReceberRecebimento.IdLoja= $local_IdLoja and 
								ContaReceberRecebimento.IdLoja= LocalCobranca.IdLoja and 
								ContaReceberRecebimento.IdLocalCobranca = LocalCobranca.IdLocalCobranca and 
								ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and 
								IdStatus != 0 && IdStatus != 7";
				$res3	=	@mysql_query($sql3,$con);
				$qtd3	=	@mysql_num_rows($res3);
				$lin3	=	@mysql_fetch_array($res3);
			
				if($qtd3 > 1){
					$lin3[AbreviacaoNomeLocalCobranca]	=	'***';
				}else{
					$lin3[AbreviacaoNomeLocalCobranca]	=	$lin3[AbreviacaoNomeLocalCobranca];
				}
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
		
		if($filtro_soma == 1){
			if($lin[IdStatus] == 1){
				$lin[ValorSoma]			=	$lin[Valor];
				$lin[ValorRecebidoSoma]	=	0;
			}else{
				$lin[ValorSoma]			=	0;
				
				if($lin[IdStatus] == 2){
					$lin[ValorRecebidoSoma]	=	$lin[ValorRecebido];
				}else{
					$lin[ValorRecebidoSoma]	=	0;
				}
			}
		}else{
			$lin[ValorSoma]			=	$lin[Valor];
			$lin[ValorRecebidoSoma]	=	$lin[ValorRecebido];
		}
		
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
	}
	
	echo "</db>";
?>
