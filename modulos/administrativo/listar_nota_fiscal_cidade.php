<?
	$localModulo		=	1;
	$localOperacao		=	115;
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
	$filtro_nome						= $_POST['filtro_nome'];
	$filtro_id_nota_fiscal				= $_POST['filtro_id_nota_fiscal'];
	$filtro_nota_fiscal_layout			= $_POST['filtro_nota_fiscal_layout'];
	$filtro_data_inicio					= $_POST['filtro_data_inicio'];
	$filtro_data_termino				= $_POST['filtro_data_termino'];
	$filtro_status						= $_POST['filtro_status'];
	$filtro_nova_aba					= $_POST['filtro_abrir_registro'];
	$filtro_tipo_pessoa					= $_POST['filtro_tipo_pessoa'];
	$filtro_periodo_apuracao			= $_POST['filtro_periodo_apuracao'];
	$filtro_estado						= $_POST['filtro_estado'];
	$filtro_id_servico					= $_POST['filtro_id_servico'];
	$filtro_numero_documento			= $_POST['filtro_numero_documento'];
	$filtro_id_conta_receber			= $_POST['filtro_id_conta_receber'];
	$filtro_numero_documento_ocultar	= $_POST["filtro_numero_documento_ocultar"];
	$filtro_codigo_NF					= $_POST["filtro_codigo_NF"];
	$filtro_cidade						= url_string_xsl($_POST['filtro_cidade'],'url',false);
	$filtro_pais						= getCodigoInterno(3,1);
	
	$filtro_IdContaReceber				= $_POST['IdContaReceber']; // IdContaReceber das Notas Fiscais geradas em Massa
	
	$filtro_limit						= $_POST['filtro_limit'];
	
	if($filtro_limit == '' && $_GET['filtro_limit']!=''){
		$filtro_limit	= $_GET['filtro_limit'];
	}
	
	if($filtro_id_nota_fiscal == '' && $_GET['IdNotaFiscal']!=''){
		$filtro_id_nota_fiscal	= $_GET['IdNotaFiscal'];
	}
		
	$filtro_nova_aba					= $_SESSION["filtro_abrir_registro"];
	$filtro_numero_documento_ocultar	= $_SESSION["filtro_numero_documento_ocultar"];
	$filtro_codigo_NF					= $_SESSION["filtro_codigo_NF"];
		
	$filtro_url	 			= "";
	$filtro_sql  			= "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_nova_aba != "")
		$filtro_url .= "&AbrirRegistro=$filtro_abrir_registro";
		
	if($filtro_numero_documento_ocultar != "")
		$filtro_url .= "&NumeroDocumentoOcultar=$filtro_numero_documento_ocultar";
	
	if($filtro_codigo_NF != "")
		$filtro_url .= "&CodigoNFOcultar=$filtro_codigo_NF";
	
	if($filtro_periodo_apuracao != ''){
		$filtro_url  .= "&PeriodoApuracao=".$filtro_periodo_apuracao;
		$filtro_periodo_apuracao = dataConv($filtro_periodo_apuracao,'m/Y','Y-m');
		
		$filtro_sql .=	" and NotaFiscal.PeriodoApuracao = '$filtro_periodo_apuracao'";
	}
	
	if($filtro_estado!=""){
		$filtro_url  .= "&IdEstado=".$filtro_estado;
		$filtro_sql .= " and PessoaEndereco.IdPais = $filtro_pais and PessoaEndereco.IdEstado = $filtro_estado";
	}
	
	if($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql .= " and LancamentoFinanceiroDados.IdServico = '$filtro_id_servico'";
	}
	
	if($filtro_numero_documento!=""){
		$filtro_url .= "&NumeroDocumento=".$filtro_numero_documento;
		$filtro_sql .= " and ContaReceber.NumeroDocumento = '$filtro_numero_documento'";
	}
	
	if($filtro_id_conta_receber!=""){
		$filtro_url .= "&IdContaReceber=".$filtro_id_conta_receber;
		$filtro_sql .= " and ContaReceber.IdContaReceber = '$filtro_id_conta_receber'";
	}
	
	if($filtro_cidade!=""){
		$filtro_url  .= "&NomeCidade=".$filtro_cidade;
		$filtro_sql .= " and Cidade.NomeCidade like '%$filtro_cidade%'";
	}
	
	if($filtro_IdContaReceber!=''){
		$filtro_url .= "&IdContaReceber=$filtro_IdContaReceber";
		$filtro_sql .=	" and ContaReceber.IdContaReceber in ($filtro_IdContaReceber)";
		$IdContaReceber  = explode(',',$filtro_IdContaReceber);
		$filtro_limit	 = sizeof($IdContaReceber);	
	}		
	
	if($filtro_tipo_pessoa != ""){
		$filtro_url	.= "&TipoPessoa=$filtro_tipo_pessoa";
		$filtro_sql .=	" and Pessoa.TipoPessoa = $filtro_tipo_pessoa";
	}
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_id_nota_fiscal!=""){
		$filtro_url .= "&IdNotaFiscal=".$filtro_id_nota_fiscal;
		$filtro_sql	.= " and NotaFiscal.IdNotaFiscal = '$filtro_id_nota_fiscal'";
	}
	
	if($filtro_nota_fiscal_layout!=""){
		$filtro_url .= "&IdNotaFiscalLayout=".$filtro_nota_fiscal_layout;
		$filtro_sql	.= " and NotaFiscal.IdNotaFiscalLayout = '$filtro_nota_fiscal_layout'";
	}
	
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and NotaFiscal.DataEmissao >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_termino!=""){
		$filtro_url .= "&DataTermino=".$filtro_data_termino;
		$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
		$filtro_sql .= " and NotaFiscal.DataEmissao <= '$filtro_data_termino'";
	}
	
	if($filtro_status!=""){
		$filtro_url .= "&IdStatus=".$filtro_status;
		$filtro_sql .= " and NotaFiscal.IdStatus = '$filtro_status'";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_nota_fiscal_cidade_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro != "s"){
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$cont	=	0;
				
	$sql = "
			select distinct		
				substr(Nome, 1,30) Nome,
				substr(RazaoSocial, 1,30) RazaoSocial,
				Pessoa.TipoPessoa,
				ContaReceber.IdContaReceber,
				ContaReceber.IdPessoa,
				ContaReceber.DataVencimento,
				ContaReceber.DataVencimento,
				ContaReceber.IdLocalCobranca,
				ContaReceber.NumeroDocumento,
				LocalCobranca.AbreviacaoNomeLocalCobranca,
				NotaFiscal.IdNotaFiscalLayout,								
				NotaFiscal.IdNotaFiscal,
				NotaFiscal.DataEmissao,
				NotaFiscal.MD5,
				NotaFiscal.CodigoAutenticacaoDocumento,					
				NotaFiscal.ValorBaseCalculoICMS,
				(NotaFiscal.ValorTotal) Valor,
				NotaFiscal.IdStatus					
			from
				ContaReceber,						
				LocalCobranca,
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
				NotaFiscal,
				LancamentoFinanceiroDados				
			where
				ContaReceber.IdLoja = $local_IdLoja and
				ContaReceber.IdLoja = NotaFiscal.IdLoja and 	
				ContaReceber.IdLoja = LocalCobranca.IdLoja and
				ContaReceber.IdPessoa = Pessoa.IdPessoa and
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Pais.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade and
				Pais.IdPais = Estado.IdPais and
				Pais.IdPais = Cidade.IdPais and
				Estado.IdEstado = Cidade.IdEstado and 
				ContaReceber.IdLoja = LancamentoFinanceiroDados.IdLoja and 
				ContaReceber.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber 
				$filtro_sql
			order by
				NotaFiscal.DataEmissao desc, NotaFiscal.IdNotaFiscal asc";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){	
		$lin[NumeroNF] = str_pad($lin[IdNotaFiscal], 9, "0", STR_PAD_LEFT);//zero a esquerda<----
		
		$sqlLancamentoFinanceiroDados = "
									select
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
			
			$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
			$lin[DataRecebimentoTemp] 	= dataConv($lin[DataRecebimento],"Y-m-d","d/m/Y");
					
			$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","Ymd");
			$lin[DataRecebimento] 		= dataConv($lin[DataRecebimento],"Y-m-d","Ymd");
			
			if($lin[Valor] == ""){				
				$lin[Valor]			=	0;		
			}
			
			$lin3[AbreviacaoNomeLocalCobranca]	=	"";
			
			switch($lin[IdStatus]){
				case '0': 
					$Color	  		=	getParametroSistema(15,2);
					$lin[Link]		= "nota_fiscal.php?NotaFiscal=$lin[MD5]";	
					$lin[MsgLink]	= "Cancelado";
					$Target			= "_blank";	
					$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";		
					break;
				case '1':							
					$Color	  		= "";
					$lin[Link]		= "nota_fiscal.php?NotaFiscal=$lin[MD5]";	
					$lin[MsgLink]	= "Imprimir";
					$Target			= "_blank";	
					$ImgExc			= "../../img/estrutura_sistema/ico_del.gif";
					break;				
			}
			
			if($lin[TipoPessoa]=='1'){
				$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
			}
			
			$lin[DataEmissaoTemp] 	=  dataConv($lin[DataEmissao],"Y-m-d","d/m/Y");
			$lin[DataEmissao]		=  dataConv($lin[DataEmissao],"Y-m-d","Ymd");
				
			echo "<reg>";	
			echo 	"<IdNotaFiscal>$lin[IdNotaFiscal]</IdNotaFiscal>";
			echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
			echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
			echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
			echo 	"<NumeroDocumento>$lin[NumeroDocumento]</NumeroDocumento>";
			echo 	"<CodigoNF>$lin[CodigoAutenticacaoDocumento]</CodigoNF>";
			echo 	"<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
			echo 	"<NossoNumero>$lin[NossoNumero]</NossoNumero>";
			echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";	
			echo 	"<DescricaoLocalRecebimento><![CDATA[$lin3[AbreviacaoNomeLocalCobranca]]]></DescricaoLocalRecebimento>";	
					
			echo 	"<Valor>$lin[Valor]</Valor>";
			
			echo 	"<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
			echo 	"<DataVencimentoTemp><![CDATA[$lin[DataVencimentoTemp]]]></DataVencimentoTemp>";
			
			echo 	"<DataEmissao><![CDATA[$lin[DataEmissao]]]></DataEmissao>";
			echo 	"<DataEmissaoTemp><![CDATA[$lin[DataEmissaoTemp]]]></DataEmissaoTemp>";
			echo 	"<ValorBaseCalculoICMS><![CDATA[$lin[ValorBaseCalculoICMS]]]></ValorBaseCalculoICMS>";
		
			echo 	"<MsgLink><![CDATA[$lin[MsgLink]]]></MsgLink>";
			echo 	"<Link><![CDATA[$lin[Link]]]></Link>";
			echo 	"<Color><![CDATA[$Color]]></Color>";
			echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
			echo 	"<Target><![CDATA[$Target]]></Target>";
						
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