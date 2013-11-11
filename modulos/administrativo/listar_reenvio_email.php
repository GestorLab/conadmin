<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja					= $_SESSION['IdLoja']; 
	$local_IdPessoaLogin			= $_SESSION["IdPessoa"];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_tipo					= $_POST['filtro_tipo'];
	$filtro_campo					= $_POST['filtro_campo'];
	$filtro_valor					= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_idstatus				= $_POST['filtro_idstatus'];
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_idContaReceber			= $_GET['IdContaReceber'];
	$filtro_idContaEventual			= $_GET['IdContaEventual'];
	$filtro_idContrato				= $_GET['IdContrato'];
	$filtro_idLancamentoFinanceiro	= $_GET['IdLancamentoFinanceiro'];
	$filtro_idProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
	$filtro_idOrdemServico			= $_GET['IdOrdemServico'];
	$filtro_pessoa					= $_GET['IdPessoa'];
	$filtro_idemail					= $_GET['IdEmail'];
	$filtro_erro					= $_GET['Erro'];
	
	if($filtro_idemail==''	&& $_POST['IdEmail'] !=''){
		$filtro_idemail		= $_POST['IdEmail'];
	}
	
	if($filtro_nome == ''){
		$filtro_nome = $_GET['filtro_nome'];
	}
	
	if($filtro_tipo == ''){
		$filtro_tipo = $_GET['filtro_tipo'];
	}
	
	if($filtro_idstatus == ''){
		$filtro_idstatus = $_GET['filtro_idstatus'];
	}
	
	if($filtro_valor == ''){
		$filtro_valor = $_GET['filtro_valor'];
	}
	
	if($filtro_campo == ''){
		$filtro_campo = $_GET['filtro_campo'];
	}
	
	if($filtro_limit == ''){
		$filtro_limit = $_GET['filtro_limit'];
	}
	
	if($filtro_conta_receber == '' && $_GET['IdContaReceber'] != ''){
		$filtro_conta_receber = $_GET['IdContaReceber'];
	}
	if($filtro_processo == '' && $_GET['IdProcessoFinanceiro'] != ''){
		$filtro_processo = $_GET['IdProcessoFinanceiro'];
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_tipo!=''){
		$filtro_url .= "&IdTipoEmail=$filtro_tipo";
		$filtro_sql .=	" and HistoricoEmail.IdTipoEmail = $filtro_tipo";
	}
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_idemail!=''){
		$filtro_url .= "&IdEmail=$filtro_idemail";
		$filtro_sql .=	" and HistoricoEmail.IdEmail in ($filtro_idemail)";
		
		$sql	=	"select IdPessoa from HistoricoEmail where IdLoja = $local_IdLoja and IdEmail = $filtro_idemail";
		$res	=	@mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
		
		$filtro_url .= "&IdPessoa=$lin[IdPessoa]";
	}
	
	if($filtro_conta_receber!=''){
		$filtro_url .= "&IdContaReceber=$filtro_conta_receber";
		//$filtro_sql .=	" and HistoricoEmail.IdContaReceber = $filtro_conta_receber";
	}
		
	if($filtro_idstatus!=''){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and HistoricoEmail.IdStatus = $filtro_idstatus";
	}
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&IdPessoa=$filtro_pessoa&Campo=IdPessoa&Valor=$filtro_pessoa";
		$filtro_sql .=	" and (HistoricoEmail.IdPessoa = '$filtro_pessoa')";
	
		$filtro_idemail = "";
	}
	
	if($filtro_idContaReceber!=''){
		$filtro_url .= "&Campo=IdContaReceber&Valor=$filtro_idContaReceber";
		$filtro_sql .=	" and HistoricoEmail.IdContaReceber = '$filtro_idContaReceber'";
	}
	
	if($filtro_idContrato!=''){
		$filtro_url .= "&Campo=IdContrato&Valor=$filtro_idContrato";
		$filtro_sql .= " and Pessoa.IdPessoa in (SELECT DISTINCT IdPessoa FROM Contrato WHERE IdLoja = '$local_IdLoja' AND IdContrato = '$filtro_idContrato')";
	}
	
	if($filtro_idContaEventual!=''){
		$filtro_url .= "&Campo=IdContaEventual&Valor=$filtro_idContaEventual";
		$filtro_sql .= " and HistoricoEmail.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdContaEventual = '$filtro_idContaEventual')";
	}
	
	if($filtro_idLancamentoFinanceiro!=''){
		$filtro_url .= "&Campo=IdLancamentoFinanceiro&Valor=$filtro_idLancamentoFinanceiro";
		$filtro_sql .= " and HistoricoEmail.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdLancamentoFinanceiro = '$filtro_idLancamentoFinanceiro')";
	}
	
	if($filtro_idOrdemServico!=''){
		$filtro_url .= "&Campo=IdOrdemServico&Valor=$filtro_idOrdemServico";
		$filtro_sql .= " and HistoricoEmail.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdOrdemServico = '$filtro_idOrdemServico')";
	}
	
	if($filtro_idProcessoFinanceiro!=''){
		$filtro_url .= "&Campo=IdProcessoFinanceiro&Valor=$filtro_idProcessoFinanceiro";
		$filtro_sql .= " and HistoricoEmail.IdProcessoFinanceiro = '$filtro_idProcessoFinanceiro'";
	}
	
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=$filtro_valor";
		switch($filtro_campo){
			case 'AssuntoEmail':
				$filtro_sql .=	" and (TipoEmail.AssuntoEmail like '%$filtro_valor%')";
				break;
			case 'DataEnvio':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .=	" and (HistoricoEmail.DataEnvio like '$filtro_valor%')";
				}else{
					$filtro_sql .=	" and (HistoricoEmail.DataEnvio = '$filtro_valor')";
				}
				break;
			case 'MesEnvio':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .=	" and (subString(HistoricoEmail.DataEnvio,1,7) like '$filtro_valor')";
				}else{
					$filtro_sql .=	" and (subString(HistoricoEmail.DataEnvio,1,7) = '$filtro_valor')";
				}
				break;
			case 'Email':
				$filtro_sql .=	" and (HistoricoEmail.Email like '%$filtro_valor%')";
				break;
			case 'IdEmail':
				$filtro_sql .=	" and (HistoricoEmail.IdEmail = '$filtro_valor')";
				break;
			case 'IdContaReceber':
				$filtro_sql .=	" and HistoricoEmail.IdContaReceber = '$filtro_valor'";
				break;
			case 'IdPessoa':
				$filtro_sql .=	" and HistoricoEmail.IdPessoa = '$filtro_valor'";
				break;
			case 'IdProcessoFinanceiro':
				$filtro_sql .=	" and HistoricoEmail.IdProcessoFinanceiro = '$filtro_valor'";
				break;
			case 'IdContrato':
				$filtro_sql .=	" and Pessoa.IdPessoa in (SELECT DISTINCT IdPessoa FROM Contrato WHERE IdLoja = '$local_IdLoja' AND IdContrato = '$filtro_valor')";
				break;
			case 'IdContaEventual':
				$filtro_sql .= " and HistoricoEmail.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdContaEventual = '$filtro_valor')";
				break;
			case 'IdLancamentoFinanceiro':
				$filtro_sql .= " and HistoricoEmail.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdLancamentoFinanceiro = '$filtro_valor')";
				break;
			case 'IdOrdemServico':
				$filtro_sql .= " and HistoricoEmail.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdOrdemServico = '$filtro_valor')";
				break;
		}		
	}else{
		$filtro_valor	=	"";
	}
	
	if($filtro_erro!=''){
		$filtro_url .= "&Erro=".$filtro_erro;
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
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_reenvio_email_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select distinct
					HistoricoEmail.IdLoja,
					HistoricoEmail.IdEmail,
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,30) Nome,
					substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
					HistoricoEmail.IdTipoEmail,
					substr(HistoricoEmail.Email,1,30) Email,
					substr(TipoEmail.DescricaoTipoEmail,1,25) DescricaoTipoEmail,
					HistoricoEmail.DataEnvio,
					HistoricoEmail.IdStatus
				from
					HistoricoEmail,
					Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					TipoEmail
				where
					HistoricoEmail.IdLoja = $local_IdLoja and
					HistoricoEmail.IdPessoa = Pessoa.IdPessoa and
					HistoricoEmail.IdLoja = TipoEmail.IdLoja and
					HistoricoEmail.IdTipoEmail = TipoEmail.IdTipoEmail
					$filtro_sql
				order by
					HistoricoEmail.IdEmail desc
				$Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$lin[DataEnvioTemp] 	= dataConv($lin[DataEnvio],"Y-m-d H:i:s","d/m/Y H:i:s");
		$lin[DataEnvio] 		= dataConv($lin[DataEnvio],"Y-m-d H:i:s","YmdHis");
		
		$sql2	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 37 and IdParametroSistema = $lin[IdStatus]";
		$res2	=	@mysql_query($sql2,$con);
		$lin2	=	@mysql_fetch_array($res2);
		
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}	
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<IdEmail>$lin[IdEmail]</IdEmail>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<DescricaoTipoEmail><![CDATA[$lin[DescricaoTipoEmail]]]></DescricaoTipoEmail>";
		echo 	"<AssuntoEmail><![CDATA[$lin[AssuntoEmail]]]></AssuntoEmail>";
		echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
		echo 	"<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";
		echo 	"<DataEnvioTemp><![CDATA[$lin[DataEnvioTemp]]]></DataEnvioTemp>";
		echo 	"<DataEnvio><![CDATA[$lin[DataEnvio]]]></DataEnvio>";
		echo 	"<Email><![CDATA[$lin[Email]]]></Email>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>