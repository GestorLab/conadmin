<?
	$localModulo		=	1;
	$localOperacao		=	77;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_IdPessoa					= $_SESSION['IdPessoa'];	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= $_POST['filtro_nome'];
	$filtro_recebido				= $_POST['filtro_recebido'];
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_data_lanc_ini			= $_POST['filtro_data_lanc_ini'];
	$filtro_data_lanc_fim			= $_POST['filtro_data_lanc_fim'];
	$filtro_data_venc_ini			= $_POST['filtro_data_venc_ini'];
	$filtro_data_venc_fim			= $_POST['filtro_data_venc_fim'];
	$filtro_servico					= $_POST['filtro_servico'];
	$filtro_valor					= $_POST['filtro_valor'];
	$filtro_data_paga_ini			= $_POST['filtro_data_paga_ini'];
	$filtro_data_paga_fim			= $_POST['filtro_data_paga_fim'];
	$filtro_numero_doc				= $_POST['filtro_numero_doc'];
	$filtro_processo				= $_POST['filtro_processo'];
	$filtro_idpais					= $_POST['filtro_idpais'];
	$filtro_estado					= $_POST['filtro_estado'];
	$filtro_cidade					= $_POST['filtro_cidade'];
	$filtro_numero_nf				= $_POST['filtro_numero_nf'];
	$filtro_limit					= $_POST['filtro_limit'];
	$largura						= $_POST['largura'];
	
	$filtro_chNome					= $_POST['chNome'];
	$filtro_chRazao					= $_POST['chRazao'];
	$filtro_chFone1					= $_POST['chFone1'];
	$filtro_chFone2					= $_POST['chFone2'];
	$filtro_chFone3					= $_POST['chFone3'];
	$filtro_chCel					= $_POST['chCel'];
	$filtro_chFax					= $_POST['chFax'];
	$filtro_chCompF					= $_POST['chCompF'];
	$filtro_chEmail					= $_POST['chEmail'];
	$filtro_chNumD					= $_POST['chNumD'];
	$filtro_chNumNF					= $_POST['chNumNF'];
	$filtro_chDataF					= $_POST['chDataF'];
	$filtro_chFax					= $_POST['chFax'];
	$filtro_chLCob					= $_POST['chLCob'];
	$filtro_chDataL					= $_POST['chDataL'];
	$filtro_chDataP					= $_POST['chDataV'];
	$filtro_chDataV					= $_POST['chDataP'];
	$filtro_chValor					= $_POST['chValor'];
	$filtro_chValDe					= $_POST['chValDe'];
	$filtro_chValDp					= $_POST['chValDp'];
	$filtro_chValF					= $_POST['chValF'];
	$filtro_chLRec					= $_POST['chLRec'];
	$filtro_chStat					= $_POST['chStat'];
	$filtro_chObs					= $_POST['chObs'];
	$filtro_chLink					= $_POST['chLink'];
	
	$filtro_qtdNome					= $_POST['qtdNome'];
	$filtro_qtdRazao				= $_POST['qtdRazao'];
	$filtro_qtdFone1				= $_POST['qtdFone1'];
	$filtro_qtdFone2				= $_POST['qtdFone2'];
	$filtro_qtdFone3				= $_POST['qtdFone3'];
	$filtro_qtdCel					= $_POST['qtdCel'];
	$filtro_qtdFax					= $_POST['qtdFax'];
	$filtro_qtdCompF				= $_POST['qtdCompF'];
	$filtro_qtdEmail				= $_POST['qtdEmail'];
	$filtro_qtdNumD					= $_POST['qtdNumD'];
	$filtro_qtdNumNF				= $_POST['qtdNumNF'];
	$filtro_qtdDataF				= $_POST['qtdDataF'];
	$filtro_qtdLCob					= $_POST['qtdLCob'];
	$filtro_qtdDataL				= $_POST['qtdDataL'];
	$filtro_qtdDataP				= $_POST['qtdDataV'];
	$filtro_qtdDataV				= $_POST['qtdDataP'];
	$filtro_qtdValor				= $_POST['qtdValor'];
	$filtro_qtdValDe				= $_POST['qtdValDe'];
	$filtro_qtdValDp				= $_POST['qtdValDp'];
	$filtro_qtdValF					= $_POST['qtdValF'];
	$filtro_qtdLRec					= $_POST['qtdLRec'];
	$filtro_qtdStat					= $_POST['qtdStat'];
	$filtro_qtdObs					= $_POST['qtdObs'];
	$filtro_qtdLink					= $_POST['qtdLink'];
	
	$filtro_idPessoa				= $_GET['IdPessoa'];
	$filtro_contrato				= $_GET['IdContrato'];
	
	if($filtro_processo == '' && $_GET['IdProcessoFinanceiro']!= ''){
		$filtro_processo	= $_GET['IdProcessoFinanceiro'];
	}
	
	$codigo_de_barras			= $_POST['codigo_de_barras'];
	
	if($codigo_de_barras !=''){
		switch(strlen($codigo_de_barras)){
			case '44':
				$filtro_nosso_numero = (int)(substr($codigo_de_barras,32,10));
				break;
		}
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";	
	
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
	
	if($filtro_recebido!=""){
		$filtro_url  .= "&Recebido=".$filtro_recebido;
		$filtro_sql  .= " and ContaReceberDados.IdStatus = ".$filtro_recebido." and ContaReceber.IdStatus != 7";
	}	
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaReceberDados.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_idPessoa!=""){
		$filtro_sql .= " and Pessoa.IdPessoa = $filtro_idPessoa";
	}
	
	if($filtro_contrato!=""){
		$filtro_sql .= " and Demonstrativo.Codigo = $filtro_contrato";
	}
	
	if($filtro_data_lanc_ini!=""){
		$filtro_url .= "&DataLancamentoIni=".$filtro_data_lanc_ini;
		$filtro_data_lanc_ini = dataConv($filtro_data_lanc_ini,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberDados.DataLancamento >= '$filtro_data_lanc_ini'";
	}
	
	if($filtro_data_lanc_fim!=""){
		$filtro_url .= "&DataLancamentoFim=".$filtro_data_lanc_fim;
		$filtro_data_lanc_fim = dataConv($filtro_data_lanc_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberDados.DataLancamento <= '$filtro_data_lanc_fim'";
	}
	
	if($filtro_data_venc_ini!=""){
		$filtro_url .= "&DataVencimentoIni=".$filtro_data_venc_ini;
		$filtro_data_venc_ini = dataConv($filtro_data_venc_ini,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberDados.DataVencimento >= '$filtro_data_venc_ini'";
	}
	
	if($filtro_data_venc_fim!=""){
		$filtro_url .= "&DataVencimentoFim=".$filtro_data_venc_fim;
		$filtro_data_venc_fim = dataConv($filtro_data_venc_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberDados.DataVencimento <= '$filtro_data_venc_fim'";
	}
	
	if($filtro_data_paga_ini!=""){
		$filtro_url .= "&DataPagamentoIni=".$filtro_data_paga_ini;
		$filtro_data_paga_ini = dataConv($filtro_data_paga_ini,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberRecebimento.DataRecebimento >= '$filtro_data_paga_ini'";
	}
	
	if($filtro_data_paga_fim!=""){
		$filtro_url .= "&DataPagamentoFim=".$filtro_data_paga_fim;
		$filtro_data_paga_fim = dataConv($filtro_data_paga_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberRecebimento.DataRecebimento <= '$filtro_data_paga_fim'";
	}

	if($filtro_valor!=""){
		$filtro_url .= "&Valor=".$filtro_valor;
		$filtro_valor	=	str_replace(".", "", $filtro_valor);	
		$filtro_valor	= 	str_replace(",", ".", $filtro_valor);
		$filtro_sql .= " and ((ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas)  = '$filtro_valor')";
	}
	
	if($filtro_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_servico;
		$filtro_sql .= " and Demostrativo.Descricao like '%$filtro_servico%'";
	}
	
	if($filtro_numero_doc!=""){
		$filtro_url  .= "&NumeroDocumento=".$filtro_numero_doc;
		$filtro_sql  .= " and ContaReceberDados.NumeroDocumento = ".$filtro_numero_doc;
	}
	
	if($filtro_idpais!=''){
		$filtro_url .= "&IdPais=$filtro_idpais";
		$filtro_sql .=	" and Pais.IdPais = $filtro_idpais";
	}
	
	if($filtro_idpais!='' && $filtro_estado!='0' && $filtro_estado!=''){
		$filtro_url .= "&IdEstado=".$filtro_estado;
		$filtro_sql .= " and Estado.IdEstado=".$filtro_estado;
	}
	
	if($filtro_idpais!='' && $filtro_estado!='0' && $filtro_estado!='' && $filtro_cidade!=''){
		$filtro_url .= "&Cidade=".$filtro_cidade;
		$filtro_sql .= " and Cidade.NomeCidade like '%$filtro_cidade%'";
	}
	
	if($filtro_numero_nf!=""){
		$filtro_url  .= "&NumeroNF=".$filtro_numero_nf;
		$filtro_sql  .= " and ContaReceberDados.NumeroNF = ".$filtro_numero_nf;
	}
	
	if($filtro_processo!=""){
		$filtro_url  .= "&IdProcessoFinanceiro=".$filtro_processo;
		$filtro_sql  .= " and Demonstrativo.IdProcessoFinanceiro = ".$filtro_processo;
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
	
	if($filtro_chNome !="")		$filtro_url  .= "&chNome=".$filtro_chNome;
	if($filtro_chRazao !="")	$filtro_url  .= "&chRazao=".$filtro_chRazao;
	if($filtro_chFone1 !="")	$filtro_url  .= "&chFone1=".$filtro_chFone1;
	if($filtro_chFone2 !="")	$filtro_url  .= "&chFone2=".$filtro_chFone2;
	if($filtro_chFone3 !="")	$filtro_url  .= "&chFone3=".$filtro_chFone3;
	if($filtro_chCel !="")		$filtro_url  .= "&chCel=".$filtro_chCel;
	if($filtro_chFax !="")		$filtro_url  .= "&chFax=".$filtro_chFax;
	if($filtro_chCompF !="")	$filtro_url  .= "&chCompF=".$filtro_chCompF;
	if($filtro_chEmail !="")	$filtro_url  .= "&chEmail=".$filtro_chEmail;
	if($filtro_chNumD !="")		$filtro_url  .= "&chNumD=".$filtro_chNumD;
	if($filtro_chNumNF !="")	$filtro_url  .= "&chNumNF=".$filtro_chNumNF;
	if($filtro_chDataF !="")	$filtro_url  .= "&chDataF=".$filtro_chDataF;
	if($filtro_chLCob !="")		$filtro_url  .= "&chLCob=".$filtro_chLCob;
	if($filtro_chDataL !="")	$filtro_url  .= "&chDataL=".$filtro_chDataL;
	if($filtro_chDataV !="")	$filtro_url  .= "&chDataV=".$filtro_chDataV;
	if($filtro_chDataP !="")	$filtro_url  .= "&chDataP=".$filtro_chDataV;
	if($filtro_chValor !="")	$filtro_url  .= "&chValor=".$filtro_chValor;
	if($filtro_chValDe !="")	$filtro_url  .= "&chValDe=".$filtro_chValDe;
	if($filtro_chValDp !="")	$filtro_url  .= "&chValDp=".$filtro_chValDp;
	if($filtro_chValF !="")		$filtro_url  .= "&chValF=".$filtro_chValF;
	if($filtro_chLRec !="")		$filtro_url  .= "&chLRec=".$filtro_chLRec;
	if($filtro_chStat !="")		$filtro_url  .= "&chStat=".$filtro_chStat;
	if($filtro_chObs !="")		$filtro_url  .= "&chObs=".$filtro_chObs;
	if($filtro_chLink !="")		$filtro_url  .= "&chLink=".$filtro_chLink;
	
	
	if($filtro_qtdNome =="")	$filtro_qtdNome	 = getParametroSistema(47,1);	
	if($filtro_qtdRazao =="")	$filtro_qtdRazao = getParametroSistema(47,2);	
	if($filtro_qtdFone1 =="")	$filtro_qtdFone1 = getParametroSistema(47,3);	
	if($filtro_qtdFone2 =="")	$filtro_qtdFone2 = getParametroSistema(47,4);	
	if($filtro_qtdFone3 =="")	$filtro_qtdFone3 = getParametroSistema(47,5);	
	if($filtro_qtdCel =="")		$filtro_qtdCel	 = getParametroSistema(47,6);	
	if($filtro_qtdFax =="")		$filtro_qtdFax 	 = getParametroSistema(47,7);	
	if($filtro_qtdCompF =="")	$filtro_qtdCompF = getParametroSistema(47,8);	
	if($filtro_qtdEmail =="")	$filtro_qtdEmail = getParametroSistema(47,9);	
	if($filtro_qtdNumD =="")	$filtro_qtdNumD	 = getParametroSistema(47,10);	
	if($filtro_qtdNumNF =="")	$filtro_qtdNumNF = getParametroSistema(47,11);	
	if($filtro_qtdDataF =="")	$filtro_qtdDataF = getParametroSistema(47,12);	
	if($filtro_qtdLCob =="")	$filtro_qtdLCob	 = getParametroSistema(47,13);	
	if($filtro_qtdDataL =="")	$filtro_qtdDataL = getParametroSistema(47,14);	
	if($filtro_qtdDataV =="")	$filtro_qtdDataV = getParametroSistema(47,15);	
	if($filtro_qtdDataP =="")	$filtro_qtdDataP = getParametroSistema(47,16);	
	if($filtro_qtdValor =="")	$filtro_qtdValor = getParametroSistema(47,17);	
	if($filtro_qtdValDe =="")	$filtro_qtdValDe = getParametroSistema(47,18);	
	if($filtro_qtdValDp =="")	$filtro_qtdValDp = getParametroSistema(47,19);	
	if($filtro_qtdValF =="")	$filtro_qtdValF  = getParametroSistema(47,20);	
	if($filtro_qtdLRec =="")	$filtro_qtdLRec  = getParametroSistema(47,21);	
	if($filtro_qtdStat =="")	$filtro_qtdStat	 = getParametroSistema(47,22);	
	if($filtro_qtdObs =="")		$filtro_qtdObs	 = getParametroSistema(47,23);	
	if($filtro_qtdLink =="")	$filtro_qtdLink	 = getParametroSistema(47,24);	
	
	$filtro_url  	.= "&qtdRazao=".$filtro_qtdRazao;
	$filtro_url  	.= "&qtdNome=".$filtro_qtdNome;
	$filtro_url  	.= "&qtdFone1=".$filtro_qtdFone1;
	$filtro_url  	.= "&qtdFone2=".$filtro_qtdFone2;
	$filtro_url  	.= "&qtdFone3=".$filtro_qtdFone3;
	$filtro_url  	.= "&qtdCel=".$filtro_qtdCel;
	$filtro_url  	.= "&qtdFax=".$filtro_qtdFax;
	$filtro_url  	.= "&qtdCompF=".$filtro_qtdCompF;
	$filtro_url  	.= "&qtdEmail=".$filtro_qtdEmail;
	$filtro_url  	.= "&qtdNumD=".$filtro_qtdNumD;
	$filtro_url  	.= "&qtdNumNF=".$filtro_qtdNumNF;
	$filtro_url  	.= "&qtdDataF=".$filtro_qtdDataF;
	$filtro_url  	.= "&qtdLCob=".$filtro_qtdLCob;
	$filtro_url  	.= "&qtdDataL=".$filtro_qtdDataL;
	$filtro_url  	.= "&qtdDataV=".$filtro_qtdDataV;
	$filtro_url  	.= "&qtdDataP=".$filtro_qtdDataP;
	$filtro_url  	.= "&qtdValor=".$filtro_qtdValor;
	$filtro_url  	.= "&qtdValDe=".$filtro_qtdValDe;
	$filtro_url  	.= "&qtdValDp=".$filtro_qtdValDp;
	$filtro_url  	.= "&qtdValF=".$filtro_qtdValF;
	$filtro_url  	.= "&qtdLRec=".$filtro_qtdLRec;
	$filtro_url  	.= "&qtdStat=".$filtro_qtdStat;
	$filtro_url  	.= "&qtdObs=".$filtro_qtdObs;
	$filtro_url  	.= "&qtdLink=".$filtro_qtdLink;
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro != "s"){
		$filtro_limit	=	getCodigoInterno(7,5);
	}
	
	$tam	=	0;
	$cont	=	0;
	$i		=	0;
	
	
	$j		=	0;
	$sql	=	"select
					ContaReceberDados.IdLoja,
					ContaReceberDados.IdContaReceber,
					Demonstrativo.Tipo,
					Demonstrativo.Codigo,
					substr(Pessoa.Nome,1,$filtro_qtdNome) Nome,
					substr(Pessoa.RazaoSocial,1,$filtro_qtdRazao) RazaoSocial,
					substr(Pessoa.Telefone1,1,$filtro_qtdFone1) Telefone1,
					substr(Pessoa.Cob_Telefone1,1,$filtro_qtdFone1) Cob_Telefone1,
					substr(Pessoa.Telefone2,1,$filtro_qtdFone2) Telefone2,
					substr(Pessoa.Telefone3,1,$filtro_qtdFone3) Telefone3,
					substr(Pessoa.Celular,1,$filtro_qtdCel) Celular,
					substr(Pessoa.Fax,1,$filtro_qtdFax) Fax,
					substr(Pessoa.ComplementoTelefone,1,$filtro_qtdCompF) ComplementoTelefone,
					substr(Pessoa.Email,1,$filtro_qtdEmail) Email,
					substr(Pessoa.Cob_Email,1,$filtro_qtdEmail) Cob_Email,
					substr(ContaReceberDados.NumeroDocumento,1,$filtro_qtdNumD) NumeroDocumento,
					substr(ContaReceberDados.NumeroNF,1,$filtro_qtdNumNF) NumeroNF,
					ContaReceberDados.DataLancamento,
					ContaReceberDados.DataNF,
					(ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas) Valor,
					substr(ContaReceberDados.ValorLancamento,1,$filtro_qtdValor) ValorLancamento,
					substr(ContaReceberDados.ValorDespesas,1,$filtro_qtdValDp) ValorDespesas,
					substr(ContaReceberDados.ValorDesconto,1,$filtro_qtdValDe) ValorDesconto,
					ContaReceberDados.DataVencimento,
					substr(LocalCobranca.DescricaoLocalCobranca,1,$filtro_qtdLCob) DescricaoLocalCobranca,
					Demonstrativo.IdProcessoFinanceiro,
					ContaReceberDados.IdStatus,
					LocalCobranca.IdLocalCobrancaLayout,
					substr(ContaReceberDados.Obs,1,$filtro_qtdObs) Obs
				from
				    Demonstrativo,
				    ContaReceberDados LEFT JOIN (
						select 
							ContaReceberRecebimento.IdLoja, 
							ContaReceberRecebimento.IdContaReceber,
							ContaReceberRecebimento.DataRecebimento, 
							ContaReceberRecebimento.IdRecibo, 
							ContaReceberRecebimento.ValorRecebido, 
							ContaReceberRecebimento.IdLocalCobranca, 
							ContaReceberRecebimento.IdStatus 
						from 
							ContaReceberRecebimento, 
							(
								select 
									IdLoja, 
									IdContaReceber, 
									max(IdContaReceberRecebimento) IdContaReceberRecebimento 
								from 
									ContaReceberRecebimento 
								group by 
									IdLoja, 
									IdContaReceber
							) ContaReceberRecebimentoUltimo 
						where 
							ContaReceberRecebimento.IdLoja = ContaReceberRecebimentoUltimo.IdLoja and 
							ContaReceberRecebimento.IdContaReceber = ContaReceberRecebimentoUltimo.IdContaReceber and 
							ContaReceberRecebimento.IdContaReceberRecebimento = ContaReceberRecebimentoUltimo.IdContaReceberRecebimento
					) ContaReceberRecebimento ON (
						ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and 
						ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber
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
				    Pais,
				    Estado,
				    Cidade
				where
				    ContaReceberDados.IdLoja = $local_IdLoja and
				    ContaReceberDados.IdLoja = Demonstrativo.IdLoja and
				    ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
				    Demonstrativo.IdContaReceber = ContaReceberDados.IdContaReceber and 
				    Demonstrativo.IdPessoa = Pessoa.IdPessoa and
				    ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				    Pessoa.IdPais = Pais.IdPais and
				    Pessoa.IdPais = Estado.IdPais and
				    Pessoa.IdEstado = Estado.IdEstado and
				    Pessoa.IdPais = Cidade.IdPais and
				    Pessoa.IdEstado = Cidade.IdEstado and
				    Pessoa.IdCidade = Cidade.IdCidade
				    $filtro_sql
				group by
				    ContaReceberDados.IdContaReceber";		    
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$query = 'true';
		
		if($lin[Tipo]=='CO' && $lin[Codigo]!=''){
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
								AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
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
									AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
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
											AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}
			}
		}
		
		/*
		if($lin[Tipo]=='EV' && $lin[Codigo]!=''){
			$sql2	=	"select IdContrato from ContaEventual where IdLoja = $local_IdLoja and IdContaEventual = $lin[Codigo]";
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
		
		if($lin[Tipo]=='OS'&& $lin[Codigo]!=''){
			$sql2	=	"select IdContrato,IdContratoFaturamento from OrdemServico where IdLoja = $local_IdLoja and IdOrdemServico = $lin[Codigo]";
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
		
			$temp	=	0;
			
			$lin[DataLancamentoTemp] 	= dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
			$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
			$lin[DataNFTemp] 			= dataConv($lin[DataNF],"Y-m-d","d/m/Y");
			
			$lin[DataLancamento] 		= dataConv($lin[DataLancamento],"Y-m-d","Ymd");
			$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","Ymd");
			$lin[DataNF] 				= dataConv($lin[DataNF],"Y-m-d","Ymd");
		
			$sql2 = "select 
						DataRecebimento,
						ContaReceberRecebimento.MD5,
						IdRecibo 
					from 
						ContaReceberRecebimento,
						ContaReceber 
					where 
						ContaReceberRecebimento.IdLoja = $local_IdLoja and 
						ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber and 
						ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber]";
			$res2 = @mysql_query($sql2,$con);
			$qtd = @mysql_num_rows($res2);
			
			if($qtd > 1){
				$lin[DataRecebimento] 		= 0;
				$lin[DataRecebimentoTemp] 	= '***';
			}else{
				$lin2	=	@mysql_fetch_array($res2);
				$lin[DataRecebimento] 		= dataConv($lin2[DataRecebimento],"Y-m-d","Ymd");
				$lin[DataRecebimentoTemp] 	= dataConv($lin2[DataRecebimento],"Y-m-d","d/m/Y");
			}
			
			if($lin[ValorDesconto]!='')	$lin[Valor]	=	$lin[Valor] - $lin[ValorDesconto];
			
			$lin[Valor]	=	substr($lin[Valor],0,$filtro_qtdValor);
			
			$sql3	=	"select substr(DescricaoLocalCobranca,1,$filtro_qtdLRec) DescricaoLocalCobranca from ContaReceberRecebimento,LocalCobranca where ContaReceberRecebimento.IdLocalCobranca = LocalCobranca.IdLocalCobranca and ContaReceberRecebimento.IdLoja= $local_IdLoja and ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber]";
			$res3	=	@mysql_query($sql3,$con);
			$qtd3	=	@mysql_num_rows($res3);
			
			if($qtd3 > 1){
				$lin[DescricaoLocalRecebimento]	=	'***';
			}else{
				$lin3	=	@mysql_fetch_array($res3);
				$lin[DescricaoLocalRecebimento]	=	$lin3[DescricaoLocalCobranca];
			}
			
			if($lin[Telefone1] == '' && $lin[Cob_Telefone1] != '')	$lin[Telefone1] = $lin[Cob_Telefone1];
			if($lin[Email] == '' && $lin[Cob_Email] != '')			$lin[Email]		= $lin[Cob_Email];
			
			if($lin[Valor] == '')				$lin[Valor]				=	0;
			if($lin[ValorLancamento] == '')		$lin[ValorLancamento]	=	0;
			if($lin[ValorDespesas] == '')		$lin[ValorDespesas]		=	0;
			if($lin[ValorDesconto] == '')		$lin[ValorDesconto]		=	0;
			
			$lin[DataLancamentoTemp]	=	substr($lin[DataLancamentoTemp],0,$filtro_qtdDataL);	
			$lin[DataVencimentoTemp]	=	substr($lin[DataVencimentoTemp],0,$filtro_qtdDataV);	
			$lin[DataRecebimentoTemp]	=	substr($lin[DataRecebimentoTemp],0,$filtro_qtdDataP);		
			$lin[DataNFTemp]			=	substr($lin[DataNFTemp],0,$filtro_qtdDataF);		
			
			$sql3 = "select substr(ValorParametroSistema,1,$filtro_qtdStat) ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 and IdParametroSistema=$lin[IdStatus]";
			$res3 = @mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
			
			$lin[Status]	=	$lin3[ValorParametroSistema];
			
			$IdContaReceber[$cont]				=	$lin[IdContaReceber];
			$IdStatus[$cont]					=	$lin[IdStatus];
			$Nome[$cont]						=	sem_quebra_string($lin[Nome]);
			$RazaoSocial[$cont]					=	sem_quebra_string($lin[RazaoSocial]);
			$Telefone1[$cont]					=	$lin[Telefone1];
			$Telefone2[$cont]					=	$lin[Telefone2];
			$Telefone3[$cont]					=	$lin[Telefone3];
			$Celular[$cont]						=	$lin[Celular];
			$Fax[$cont]							=	$lin[Fax];
			$ComplementoTelefone[$cont]			=	sem_quebra_string($lin[ComplementoTelefone]);
			$Email[$cont]						=	$lin[Email];
			$NumeroDocumento[$cont]				=	$lin[NumeroDocumento];
			$NumeroNF[$cont]					=	$lin[NumeroNF];
			$DescricaoLocalCobranca[$cont]		=	sem_quebra_string($lin[DescricaoLocalCobranca]);
			$DescricaoLocalRecebimento[$cont]	=	sem_quebra_string($lin[DescricaoLocalRecebimento]);
			$Status[$cont]						=	sem_quebra_string($lin[Status]);
			$Obs[$cont]							=	sem_quebra_string($lin[Obs]);
			$DataLancamento[$cont]				=	$lin[DataLancamento];
			$DataLancamentoTemp[$cont]			=	$lin[DataLancamentoTemp];
			$DataNF[$cont]						=	$lin[DataNF];
			$DataNFTemp[$cont]					=	$lin[DataNFTemp];
			$Valor[$cont]						=	$lin[Valor];
			$ValorLancamento[$cont]				=	$lin[ValorLancamento];
			$ValorDesconto[$cont]				=	$lin[ValorDesconto];
			$ValorDespesas[$cont]				=	$lin[ValorDespesas];
			$DataVencimentoTemp[$cont]			=	$lin[DataVencimentoTemp];
			$DataRecebimento[$cont]				=	$lin[DataRecebimento];
			$DataRecebimentoTemp[$cont]			=	$lin[DataRecebimentoTemp];
			$IdLocalCobrancaLayout[$cont]		=	$lin[IdLocalCobrancaLayout];
			$IdRecibo[$cont]					=	$lin2[IdRecibo];
			$MD5[$cont]							=	$lin2[MD5];
			
			$filtro_chNome[$cont]				=	$filtro_chNome;
			$filtro_chRazao[$cont]				=	$filtro_chRazao;
			$filtro_chFone1[$cont]				=	$filtro_chFone1;
			$filtro_chFone2[$cont]				=	$filtro_chFone2;
			$filtro_chFone3[$cont]				=	$filtro_chFone3;
			$filtro_chCel[$cont]				=	$filtro_chCel;
			$filtro_chFax[$cont]				=	$filtro_chFax;
			$filtro_chCompF[$cont]				=	$filtro_chCompF;
			$filtro_chEmail[$cont]				=	$filtro_chEmail;
			$filtro_chNumD[$cont]				=	$filtro_chNumD;
			$filtro_chNumNF[$cont]				=	$filtro_chNumNF;
			$filtro_chDataF[$cont]				=	$filtro_chDataF;
			$filtro_chLCob[$cont]				=	$filtro_chLCob;
			$filtro_chDataL[$cont]				=	$filtro_chDataL;
			$filtro_chDataV[$cont]				=	$filtro_chDataV;
			$filtro_chDataP[$cont]				=	$filtro_chDataP;
			$filtro_chValor[$cont]				=	$filtro_chValor;
			$filtro_chValDe[$cont]				=	$filtro_chValDe;
			$filtro_chValDp[$cont]				=	$filtro_chValDp;
			$filtro_chValF[$cont]				=	$filtro_chValF;
			$filtro_chLRec[$cont]				=	$filtro_chLRec;
			$filtro_chStat[$cont]				=	$filtro_chStat;
			$filtro_chObs[$cont]				=	$filtro_chObs;
			$filtro_chLink[$cont]				=	$filtro_chLink;
			
			if($filtro_chNome[$cont] == 1)		$temp	+=	strlen($Nome[$cont]);	$i++;
			if($filtro_chRazao[$cont] == 1)		$temp	+=	strlen($RazaoSocial[$cont]);	$i++;
			if($filtro_chFone1[$cont] == 1)		$temp	+=	strlen($Telefone1[$cont]);	$i++;
			if($filtro_chFone2[$cont] == 1)		$temp	+=	strlen($Telefone2[$cont]);	$i++;
			if($filtro_chFone3[$cont] == 1)		$temp	+=	strlen($Telefone3[$cont]);	$i++;
			if($filtro_chCel[$cont] == 1)		$temp	+=	strlen($Celular[$cont]);	$i++;
			if($filtro_chFax[$cont] == 1)		$temp	+=	strlen($Fax[$cont]);	$i++;
			if($filtro_chCompF[$cont] == 1)		$temp	+=	strlen($ComplementoTelefone[$cont]);	$i++;
			if($filtro_chEmail[$cont] == 1)		$temp	+=	strlen($Email[$cont]);	$i++;
			if($filtro_chNumD[$cont] == 1)		$temp	+=	strlen($NumeroDocumento[$cont]);	$i++;
			if($filtro_chNumNF[$cont] == 1)		$temp	+=	strlen($NumeroNF[$cont]);	$i++;
			if($filtro_chDataF[$cont] == 1)		$temp	+=	strlen($DataNF[$cont]);	$i++;
			if($filtro_chLCob[$cont] == 1)		$temp	+=	strlen($DescricaoLocalCobranca[$cont]);	$i++;
			if($filtro_chDataL[$cont] == 1)		$temp	+=	strlen($DataLancamento[$cont]);	$i++;
			if($filtro_chDataV[$cont] == 1)		$temp	+=	strlen($DataVencimento[$cont]);	$i++;
			if($filtro_chDataP[$cont] == 1)		$temp	+=	strlen($DataRecebimento[$cont]);	$i++;
			if($filtro_chValor[$cont] == 1)		$temp	+=	strlen($Valor[$cont]);	$i++;
			if($filtro_chValDe[$cont] == 1)		$temp	+=	strlen($ValorDesconto[$cont]);	$i++;
			if($filtro_chValF[$cont] == 1)		$temp	+=	strlen($ValorFinal[$cont]);	$i++;
			if($filtro_chValDp[$cont] == 1)		$temp	+=	strlen($ValorDespesas[$cont]);	$i++;
			if($filtro_chLRec[$cont] == 1)		$temp	+=	strlen($DescricaoLocalRecebimento[$cont]);	$i++;
			if($filtro_chStat[$cont] == 1)		$temp	+=	strlen($Status[$cont]);	$i++;	
			if($filtro_chObs[$cont] == 1)		$temp	+=	strlen($Obs[$cont]);	$i++;
		
			$temp	=	($temp+6)*10;
			
			if($temp > $tam){
				$tam	=	$temp;
			}			
			
			$cont++;
			
			$j++;
			
			if($filtro_limit!= ""){
				if($j >= $filtro_limit){
					break;
				}
			}
		}
	}
	
	$tam	=	$tam+$i;
	
	if($tam	< 853){
		$tam	=	$largura-170;	
	}else{
		if($tam > 1900){
			$tam	=	$tam+$i;	
		}
	}
	
	$filtro_url  	.= "&tam=".$tam;
	
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_avancado_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	
	$i	=	0;
	while($i < $cont){
		switch($IdStatus[$i]){
			case '0': 
				$Color[$i]	  =	getParametroSistema(15,2);
				$ImgExc[$i]	  = "../../img/estrutura_sistema/ico_del_c.gif";
				$Recebido[$i] = "N";
				$MsgLink[$i]  = "Canc.";
				$Link[$i]	  = "cadastro_conta_receber.php?IdContaReceber=$IdContaReceber[$i]";	
				$Target[$i]   = "_self";	
				break;
			case '1':
				$Link[$i]  	  = "local_cobranca/$IdLocalCobrancaLayout[$i]/index.php";
				if(file_exists($Link[$i])){
					$Recebido[$i]   = "N";
					$MsgLink[$i]	= "Boleto";
					$Target[$i]		= "_blank";	
					$Link[$i]		= "local_cobranca/$IdLocalCobrancaLayout[$i]/index.php?IdLoja=$local_IdLoja&IdContaReceber=$IdContaReceber[$i]";
				}else{	
					$Recebido[$i]   = "N";
					$MsgLink[$i]	= "";
					$Link[$i]		= "";
					$Target[$i]		= "";	
				}
				$Color[$i] 	  = "";		
				$ImgExc[$i]	  = "../../img/estrutura_sistema/ico_del.gif";		
				break;
			case '2':
				$Recebido[$i] 	= "S";
				$MsgLink[$i]	= "Recibo";
				$Link[$i]		= "recibo.php?Recibo=$MD5[$i]";
				$Color[$i] 		= getParametroSistema(15,3);		
				$ImgExc[$i]		= "../../img/estrutura_sistema/ico_del.gif";
				$Target[$i]		= "_blank";		
				break;
		}
		
		$MsgLink[$i]	=	substr($MsgLink[$i],0,$filtro_qtdLink);
		
		echo "<reg>";	
		echo 	"<IdContaReceber><![CDATA[$IdContaReceber[$i]]]></IdContaReceber>";
		echo 	"<IdStatus><![CDATA[$IdStatus[$i]]]></IdStatus>";
		echo 	"<Nome><![CDATA[$Nome[$i]]]></Nome>";	
		echo 	"<RazaoSocial><![CDATA[$RazaoSocial[$i]]]></RazaoSocial>";	
		echo 	"<Telefone1><![CDATA[$Telefone1[$i]]]></Telefone1>";
		echo 	"<Telefone2><![CDATA[$Telefone2[$i]]]></Telefone2>";	
		echo 	"<Telefone3><![CDATA[$Telefone3[$i]]]></Telefone3>";	
		echo 	"<Celular><![CDATA[$Celular[$i]]]></Celular>";
		echo 	"<Fax><![CDATA[$Fax[$i]]]></Fax>";	
		echo 	"<ComplementoTelefone><![CDATA[$ComplementoTelefone[$i]]]></ComplementoTelefone>";	
		echo 	"<Email><![CDATA[$Email[$i]]]></Email>";		
		echo 	"<NumeroDocumento><![CDATA[$NumeroDocumento[$i]]]></NumeroDocumento>";
		echo 	"<NumeroNF><![CDATA[$NumeroNF[$i]]]></NumeroNF>";
		echo 	"<DescricaoLocalCobranca><![CDATA[$DescricaoLocalCobranca[$i]]]></DescricaoLocalCobranca>";	
		echo 	"<DescricaoLocalRecebimento><![CDATA[$DescricaoLocalRecebimento[$i]]]></DescricaoLocalRecebimento>";
		echo 	"<Status><![CDATA[$Status[$i]]]></Status>";	
		echo 	"<Obs><![CDATA[$Obs[$i]]]></Obs>";	
		
		echo 	"<DataLancamento><![CDATA[$DataLancamento[$i]]]></DataLancamento>";
		echo 	"<DataLancamentoTemp><![CDATA[$DataLancamentoTemp[$i]]]></DataLancamentoTemp>";
		
		echo 	"<DataNF><![CDATA[$DataNF[$i]]]></DataNF>";
		echo 	"<DataNFTemp><![CDATA[$DataNFTemp[$i]]]></DataNFTemp>";
		
		echo 	"<Valor><![CDATA[$Valor[$i]]]></Valor>";
		echo 	"<ValorLancamento><![CDATA[$ValorLancamento[$i]]]></ValorLancamento>";
		echo 	"<ValorDesconto><![CDATA[$ValorDesconto[$i]]]></ValorDesconto>";
		echo 	"<ValorDespesas><![CDATA[$ValorDespesas[$i]]]></ValorDespesas>";
		
		echo 	"<DataVencimento><![CDATA[$DataVencimento[$i]]]></DataVencimento>";
		echo 	"<DataVencimentoTemp><![CDATA[$DataVencimentoTemp[$i]]]></DataVencimentoTemp>";
		
		echo 	"<Recebido><![CDATA[$Recebido[$i]]]></Recebido>";
		echo 	"<MsgLink><![CDATA[$MsgLink[$i]]]></MsgLink>";
		echo 	"<Link><![CDATA[$Link[$i]]]></Link>";
		echo 	"<Color><![CDATA[$Color[$i]]]></Color>";
		echo 	"<ImgExc><![CDATA[$ImgExc[$i]]]></ImgExc>";
		echo 	"<Target><![CDATA[$Target[$i]]]></Target>";
		
		echo 	"<DataRecebimento><![CDATA[$DataRecebimento[$i]]]></DataRecebimento>";
		echo 	"<DataRecebimentoTemp><![CDATA[$DataRecebimentoTemp[$i]]]></DataRecebimentoTemp>";
		
		echo 	"<chNome><![CDATA[$filtro_chNome[$i]]]></chNome>";
		echo 	"<chRazao><![CDATA[$filtro_chRazao[$i]]]></chRazao>";
		echo 	"<chFone1><![CDATA[$filtro_chFone1[$i]]]></chFone1>";
		echo 	"<chFone2><![CDATA[$filtro_chFone2[$i]]]></chFone2>";
		echo 	"<chFone3><![CDATA[$filtro_chFone3[$i]]]></chFone3>";
		echo 	"<chCel><![CDATA[$filtro_chCel[$i]]]></chCel>";
		echo 	"<chFax><![CDATA[$filtro_chFax[$i]]]></chFax>";
		echo 	"<chCompF><![CDATA[$filtro_chCompF[$i]]]></chCompF>";
		echo 	"<chEmail><![CDATA[$filtro_chEmail[$i]]]></chEmail>";
		echo 	"<chNumD><![CDATA[$filtro_chNumD[$i]]]></chNumD>";
		echo 	"<chNumNF><![CDATA[$filtro_chNumNF[$i]]]></chNumNF>";
		echo 	"<chDataF><![CDATA[$filtro_chDataF[$i]]]></chDataF>";
		echo 	"<chLCob><![CDATA[$filtro_chLCob[$i]]]></chLCob>";
		echo 	"<chDataL><![CDATA[$filtro_chDataL[$i]]]></chDataL>";
		echo 	"<chDataV><![CDATA[$filtro_chDataV[$i]]]></chDataV>";
		echo 	"<chDataP><![CDATA[$filtro_chDataP[$i]]]></chDataP>";
		echo 	"<chValor><![CDATA[$filtro_chValor[$i]]]></chValor>";
		echo 	"<chValDe><![CDATA[$filtro_chValDe[$i]]]></chValDe>";
		echo 	"<chValDp><![CDATA[$filtro_chValDp[$i]]]></chValDp>";
		echo 	"<chValF><![CDATA[$filtro_chValF[$i]]]></chValF>";
		echo 	"<chLRec><![CDATA[$filtro_chLRec[$i]]]></chLRec>";
		echo 	"<chStat><![CDATA[$filtro_chStat[$i]]]></chStat>";
		echo 	"<chObs><![CDATA[$filtro_chObs[$i]]]></chObs>";
		echo 	"<chLink><![CDATA[$filtro_chLink[$i]]]></chLink>";
		
		echo "</reg>";	
		
		$i++;
	}
	
	echo "</db>";
?>
