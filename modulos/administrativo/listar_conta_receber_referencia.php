<?
	$localModulo		=	1;
	$localOperacao		=	128;
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
	$filtro_data_inicio				= $_POST['filtro_data_inicio'];
	$filtro_data_fim				= $_POST['filtro_data_fim'];
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_status					= $_POST['filtro_status'];
	
	$filtro_idPessoa				= $_GET['IdPessoa'];
	$filtro_contrato				= $_GET['IdContrato'];
	$filtro_conta_receber			= $_POST['IdContaReceber'];
	$filtro_ordem_servico			= $_POST['IdOrdemServico'];	
	$filtro_conta_eventual			= $_GET['IdContaEventual'];
	$filtro_carne					= $_GET['IdCarne'];
	$filtro_numero_documento		= $_GET['NumeroDocumento'];
	
	$filtro_cancelado				= $_SESSION["filtro_cancelado"];
	$filtro_juros					= $_SESSION["filtro_juros"];
	$filtro_soma					= $_SESSION["filtro_soma"];
	$filtro_nota_fiscal				= $_SESSION["filtro_nota_fiscal"];
	$filtro_impressao				= $_SESSION["filtro_impressao"];
	$filtro_conta_receber_nota_fiscal = $_SESSION["filtro_conta_receber_nota_fiscal"];	
	
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
	
	if($filtro_status!=""){
		$filtro_url  .= "&IdStatus=".$filtro_status;
		if($filtro_status == 200){
			$filtro_sql  .= " and ContaReceberDados.IdStatus = 1 and DataVencimento < curdate() and ContaReceberDados.IdStatus != 7";
		}else{
			if($filtro_status == 7){
				$filtro_sql  .= " and ContaReceberDados.IdStatus = ".$filtro_status;
			}else{
				$filtro_sql  .= " and ContaReceberDados.IdStatus = ".$filtro_status." and ContaReceberDados.IdStatus!=7";
			}
		}
	}else{
		$filtro_sql  .= " and ContaReceberDados.IdStatus!=7";
	}
	
	if($filtro_data_inicio!="" && $filtro_data_fim!=""){
		if($filtro_data_inicio!=""){
			$filtro_url  .= "&DataInicio=".$filtro_data_inicio."&DataTermino=".$filtro_data_fim;
			
			$filtro_data_inicio	= dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
			$filtro_data_fim	= dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
			
			$filtro_sql .= " and ((LancamentoFinanceiroDados.DataReferenciaInicial >= '$filtro_data_inicio' and LancamentoFinanceiroDados.DataReferenciaInicial <= '$filtro_data_fim') or (LancamentoFinanceiroDados.DataReferenciaFinal >= '$filtro_data_inicio' and LancamentoFinanceiroDados.DataReferenciaFinal <= '$filtro_data_fim'))";
		}
	} else{
		if($filtro_data_inicio!=""){
			$filtro_url  .= "&DataInicio=".$filtro_data_inicio;
			$filtro_data_inicio	= dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
			$filtro_sql .= " and LancamentoFinanceiroDados.DataReferenciaInicial >= '$filtro_data_inicio'";
		}
		
		if($filtro_data_fim!=""){
			$filtro_url  .= "&DataTermino=".$filtro_data_fim;
			$filtro_data_fim	= dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
			$filtro_sql .= " and LancamentoFinanceiroDados.DataReferenciaFinal <= '$filtro_data_fim'";
		}
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
	
	if($filtro_conta_receber_nota_fiscal!=""){
		if($filtro_conta_receber_nota_fiscal == 1){	
			$filtro_sql .=	" and (ContaReceberDados.NumeroNF != '' and ContaReceberDados.NumeroNF is not null)";
		}
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_referencia_xsl.php$filtro_url\"?>";
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
	$sql	=	"select distinct
					ContaReceberDados.IdLoja,
					ContaReceberDados.IdContaReceber,
					ContaReceberDados.DataLancamento,
					ContaReceberDados.ValorFinal Valor,
					ContaReceberDados.ValorDesconto,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.IdLocalCobranca,
					ContaReceberDados.IdStatus,
					ContaReceberDados.NumeroNF,
					ContaReceberDados.MD5,
					ContaReceberRecebimento.MD5 Recibo,
					ContaReceberRecebimento.IdRecibo,
					ContaReceberRecebimento.DataRecebimento,
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,30) Nome,
					substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
					LocalCobranca.AbreviacaoNomeLocalCobranca,
					LocalCobranca.IdLocalCobrancaLayout,
					LancamentoFinanceiroDados.DataReferenciaInicial,
					LancamentoFinanceiroDados.DataReferenciaFinal
				from
					ContaReceberDados left join ContaReceberRecebimento On (
						ContaReceberRecebimento.IdLoja = ContaReceberDados.IdLoja and 
						ContaReceberRecebimento.IdContaReceber = ContaReceberDados.IdContaReceber and 
						ContaReceberRecebimento.IdStatus != 0
					),
					Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					LocalCobranca,
					LancamentoFinanceiroDados,
					LancamentoFinanceiroContaReceber
				where
					ContaReceberDados.IdLoja = $local_IdLoja and 
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and 
					ContaReceberDados.IdLoja = LancamentoFinanceiroDados.IdLoja and 
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
					LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa and
					LancamentoFinanceiroDados.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro
					$filtro_sql
				order by
					ContaReceberDados.IdContaReceber desc
			    $Limit ";		    
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin[DataLancamentoTemp] 	= dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
		$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
		$lin[DataRecebimentoTemp] 	= dataConv($lin[DataRecebimento],"Y-m-d","d/m/Y");
		
		$lin[DataLancamento] 		= dataConv($lin[DataLancamento],"Y-m-d","Ymd");
		$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","Ymd");
		$lin[DataRecebimento] 		= dataConv($lin[DataRecebimento],"Y-m-d","Ymd");
		
		if($lin[DataReferenciaInicial] != '' && $lin[DataReferenciaFinal] != ''){
			$lin[DataReferenciaTemp]	= dataConv($lin[DataReferenciaInicial],"Y-m-d","d/m/Y")." à ".dataConv($lin[DataReferenciaFinal],"Y-m-d","d/m/Y");
			$lin[DataReferencia]		= dataConv($lin[DataReferenciaInicial],"Y-m-d","Ymd")." à ".dataConv($lin[DataReferenciaFinal],"Y-m-d","Ymd");
		}
		
		if($lin[Valor] == ""){
			$lin[Valor] = 0;
		}
		
		if($lin[ValorDesconto]!=''){
			$lin[Valor]	= $lin[Valor] - $lin[ValorDesconto];
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
				#Status = 1 (Aguardando Pagamento)
				if($filtro_impressao == 1){	//Html
					$lin[Link]		= "boleto.php";
					$lin[Tipo]		= "html";
				}else{					//Pdf
					$lin[Link]		= "boleto.php";
					$lin[Tipo]		= "pdf";
				}
				if(file_exists($lin[Link])){
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Boleto";
					$Target			= "_blank";	
					$lin[Link]		.= "?Tipo=$lin[Tipo]&ContaReceber=$lin[MD5]";
				}else{	
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "";
					$lin[Link]		= "";
					$Target			= "";	
				}
				$Color	  = "";		
				$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";		
				
				$lin[DataRecebimento] 		=	"";
				$lin[DataRecebimentoTemp]	=	"";
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
				
				break;
			case 6:
				if($filtro_impressao == 1){		//Html
					$lin[Link]		= "boleto.php";
					$lin[Tipo]		= "html";
				}else{							//Pdf
					$lin[Link]		= "boleto.php";
					$lin[Tipo]		="pdf";
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
		
		/*if($filtro_soma == 1){
			if($lin[IdStatus] == 1){
				$lin[ValorSoma]			=	$lin[Valor];
			}else{
				$lin[ValorSoma]			=	0;
			}
		}else{
			$lin[ValorSoma]			=	$lin[Valor];
		}
		*/
		
		if($filtro_soma == 1){
			$lin[ValorSoma]				=	$lin[Valor];						
		}else{
			if($lin[IdStatus] == 2){
				$lin[ValorSoma]			=	$lin[Valor];			
			}else{
				$lin[ValorSoma]			=	0;			
			}				
		}
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
		}
		
		echo "<reg>";	
		echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
		echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
		echo 	"<NossoNumero>$lin[NossoNumero]</NossoNumero>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";	
		echo 	"<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
		echo 	"<DataLancamentoTemp><![CDATA[$lin[DataLancamentoTemp]]]></DataLancamentoTemp>";
		echo 	"<DataReferencia><![CDATA[$lin[DataReferencia]]]></DataReferencia>";
		echo 	"<DataReferenciaTemp><![CDATA[$lin[DataReferenciaTemp]]]></DataReferenciaTemp>";
		echo 	"<Valor>$lin[Valor]</Valor>";
		echo 	"<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
		echo 	"<DataVencimentoTemp><![CDATA[$lin[DataVencimentoTemp]]]></DataVencimentoTemp>";
		echo 	"<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
		echo 	"<DataRecebimentoTemp><![CDATA[$lin[DataRecebimentoTemp]]]></DataRecebimentoTemp>";
		echo 	"<MsgLink><![CDATA[$lin[MsgLink]]]></MsgLink>";
		echo 	"<Link><![CDATA[$lin[Link]]]></Link>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
		echo 	"<Target><![CDATA[$Target]]></Target>";
		echo 	"<ValorSoma><![CDATA[$lin[ValorSoma]]]></ValorSoma>";
		echo "</reg>";	
		
		$cont++;
		
		if($filtro_limit!= ""){
			if($cont >= $filtro_limit){
				break;
			}
		}
	}
	
	echo "</db>";
?>