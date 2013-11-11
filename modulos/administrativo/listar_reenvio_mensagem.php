<?
	$localModulo		=	1;
	$localOperacao		=	27;
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
	$filtro_idTicket				= $_GET['IdTicket'];
	$filtro_idTipoMensagem			= $_GET['IdTipoMensagem'];
	$filtro_idhistorico_mensagem	= $_GET['IdHistoricoMensagem'];
	$filtro_erro					= $_GET['Erro'];
	$filtro_mala_direta				= $_GET['IdMalaDireta'];
	
	if($filtro_idhistorico_mensagem =='' && $_POST['IdHistoricoMensagem'] !=''){
		$filtro_idhistorico_mensagem	= $_POST['IdHistoricoMensagem'];
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
	
	if($filtro_tipo != ''){
		$filtro_url .= "&IdTipoMensagem=$filtro_tipo";
		if($filtro_tipo >= 1000000){
			$from ="left join MalaDireta 
					on (
					  TipoMensagem.IdTipoMensagem = MalaDireta.IdTipoMensagem and
					  MalaDireta.IdLoja = '$local_IdLoja' and
					  TipoMensagem.IdLoja = MalaDireta.IdLoja 
					)";
			$filtro_sql_id_pessoa = "";
			$filtro_sql .=	" and HistoricoMensagem.IdTipoMensagem = $filtro_tipo";
			$order_group =	" group by HistoricoMensagem.IdHistoricoMensagem";
		}else{
			$filtro_sql_id_pessoa = " and HistoricoMensagem.IdPessoa = Pessoa.IdPessoa";
			$filtro_sql .=	" and HistoricoMensagem.IdTipoMensagem = $filtro_tipo";
			$order_group = 	" order by HistoricoMensagem.IdHistoricoMensagem desc";
		}
		
		if($filtro_tipo == 29 || $filtro_tipo == 32){
			$select = "IF(HistoricoMensagem.Celular != 'NULL',HistoricoMensagem.Celular,IF(HistoricoMensagem.Celular != NULL,HistoricoMensagem.Celular,HistoricoMensagem.Celular)) Celular,";
		}else{
			$select = "HistoricoMensagem.Email,";
		}
	}
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	if($filtro_mala_direta != ''){
		$filtro_url .= "&MalaDireta=$filtro_mala_direta";
		$from =", MalaDireta";
		$filtro_sql .=" and MalaDireta.IdLoja = HistoricoMensagem.IdLoja and
						HistoricoMensagem.IdMalaDireta = MalaDireta.IdMalaDireta and
						MalaDireta.IdMalaDireta = $filtro_mala_direta";
						
		$order_group = "group by IdHistoricoMensagem";
	}
	
	if($filtro_idhistorico_mensagem!=''){		
		$filtro_url .= "&IdHistoricoMensgem=$filtro_idhistorico_mensagem";
		$filtro_sql .=	" and HistoricoMensagem.IdHistoricoMensagem in ($filtro_idhistorico_mensagem)";
		
		$sql	=	"select IdPessoa from HistoricoMensagem where IdLoja = $local_IdLoja and IdHistoricoMensagem = $filtro_idhistorico_mensagem";
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
		$filtro_sql .= " and HistoricoMensagem.IdStatus = $filtro_idstatus";
	}
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&IdPessoa=$filtro_pessoa&Campo=IdPessoa&Valor=$filtro_pessoa";
		$filtro_sql .=	" and (HistoricoMensagem.IdPessoa = '$filtro_pessoa')";
	
		$filtro_idhistorico_mensagem = "";
	}
	
	if($filtro_idTicket!=''){
		$filtro_url .= "&IdTicket=$filtro_idTicket";
		$filtro_sql .=	" and HistoricoMensagem.IdTicket = '$filtro_idTicket'";
	
		$filtro_idhistorico_mensagem = "";
	}
	
	if($filtro_idTipoMensagem!=''){
		$filtro_url .= "&IdTipoMensagem=$filtro_idTipoMensagem";
		$filtro_sql .=	" and HistoricoMensagem.IdTipoMensagem = '$filtro_idTipoMensagem'";
	}
	
	if($filtro_idContaReceber!=''){
		$filtro_url .= "&Campo=IdContaReceber&Valor=$filtro_idContaReceber";
		$filtro_sql .=	" and HistoricoMensagem.IdContaReceber = '$filtro_idContaReceber'";
	}
	
	if($filtro_idContrato!=''){
		$filtro_url .= "&Campo=IdContrato&Valor=$filtro_idContrato";
		$filtro_sql .= " and HistoricoMensagem.IdContrato = '$filtro_idContrato'";
	}
	
	if($filtro_idContaEventual!=''){
		$filtro_url .= "&Campo=IdContaEventual&Valor=$filtro_idContaEventual";
		$filtro_sql .= " and HistoricoMensagem.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdContaEventual = '$filtro_idContaEventual')";
	}
	
	if($filtro_idLancamentoFinanceiro!=''){
		$filtro_url .= "&Campo=IdLancamentoFinanceiro&Valor=$filtro_idLancamentoFinanceiro";
		$filtro_sql .= " and HistoricoMensagem.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdLancamentoFinanceiro = '$filtro_idLancamentoFinanceiro')";
	}
	
	if($filtro_idOrdemServico!=''){
		$filtro_url .= "&Campo=IdOrdemServico&Valor=$filtro_idOrdemServico";
		$filtro_sql .= " and HistoricoMensagem.IdOrdemServico = '$filtro_idOrdemServico'";
	}
	
	if($filtro_idProcessoFinanceiro!=''){
		$filtro_url .= "&Campo=IdProcessoFinanceiro&Valor=$filtro_idProcessoFinanceiro";
		$filtro_sql .= " and HistoricoMensagem.IdProcessoFinanceiro = '$filtro_idProcessoFinanceiro'";
	}
		
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=$filtro_valor";
		switch($filtro_campo){
			case 'Assunto':
				$filtro_sql .=	" and (TipoMensagem.Assunto like '%$filtro_valor%')";
				break;
			case 'DataEnvio':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .=	" and (HistoricoMensagem.DataEnvio like '$filtro_valor%')";
				}else{
					$filtro_sql .=	" and (HistoricoMensagem.DataEnvio = '$filtro_valor')";
				}
				break;
				case 'DataCriacao':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .=	" and (HistoricoMensagem.DataCriacao like '$filtro_valor%')";
				}else{
					$filtro_sql .=	" and (HistoricoMensagem.DataCriacao = '$filtro_valor')";
				}
				break;
			case 'MesEnvio':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .=	" and (subString(HistoricoMensagem.DataEnvio,1,7) like '$filtro_valor')";
				}else{
					$filtro_sql .=	" and (subString(HistoricoMensagem.DataEnvio,1,7) = '$filtro_valor')";
				}
				break;
			case 'Email':
				$filtro_sql .=	" and (HistoricoMensagem.Email like '%$filtro_valor%')";
				break;
			case 'IdHistoricoMensagem':
				$filtro_sql .=	" and (HistoricoMensagem.IdHistoricoMensagem = '$filtro_valor')";
				break;
			case 'IdContaReceber':
				$filtro_sql .=	" and HistoricoMensagem.IdContaReceber = '$filtro_valor'";
				break;
			case 'IdPessoa':
				$filtro_sql .=	" and HistoricoMensagem.IdPessoa = '$filtro_valor'";
				break;
			case 'IdProcessoFinanceiro':
				$filtro_sql .=	" and HistoricoMensagem.IdProcessoFinanceiro = '$filtro_valor'";
				break;
			case 'IdContrato':
				$filtro_sql .=	" and HistoricoMensagem.IdContrato = $filtro_valor";
				break;
			case 'IdContaEventual':
				$filtro_sql .= " and HistoricoMensagem.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdContaEventual = '$filtro_valor')";
				break;
			case 'IdLancamentoFinanceiro':
				$filtro_sql .= " and HistoricoMensagem.IdContaReceber in (SELECT DISTINCT IdContaReceber FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdLancamentoFinanceiro = '$filtro_valor')";
				break;
			case 'IdOrdemServico':
				$filtro_sql .= " and HistoricoMensagem.IdOrdemServico = '$filtro_valor'";
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
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_reenvio_mensagem_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select
					HistoricoMensagem.IdHistoricoMensagem,
					$select
					HistoricoMensagem.DataEnvio,
					HistoricoMensagem.DataCriacao,
					HistoricoMensagem.IdStatus,
					HistoricoMensagem.Titulo,
					TipoMensagem.IdTipoMensagem,
					Pessoa.Nome,
					Pessoa.TipoPessoa
				from
					HistoricoMensagem
						LEFT JOIN Pessoa ON (HistoricoMensagem.IdPessoa = Pessoa.IdPessoa)
						LEFT JOIN PessoaGrupoPessoa ON (Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa AND PessoaGrupoPessoa.IdLoja = HistoricoMensagem.IdLoja)
						LEFT JOIN GrupoPessoa ON (PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja AND PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa),
					TipoMensagem
					$from
				where
					HistoricoMensagem.IdLoja = $local_IdLoja and
					HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and	
					HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem
					$filtro_sql_id_pessoa
					$filtro_sql
					$order_group
				$Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$lin[DataEnvioTemp] 	= dataConv($lin[DataEnvio],"Y-m-d H:i:s","d/m/Y H:i:s");
		$lin[DataEnvio] 		= dataConv($lin[DataEnvio],"Y-m-d H:i:s","YmdHis");
		$lin[DataCriacaoTemp] 	= dataConv($lin[DataCriacao],"Y-m-d H:i:s","d/m/Y H:i:s");
		$lin[DataCriacao] 		= dataConv($lin[DataCriacao],"Y-m-d H:i:s","YmdHis");
		
		$sql2	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 193 and IdParametroSistema = $lin[IdStatus]";
		$res2	=	@mysql_query($sql2,$con);
		$lin2	=	@mysql_fetch_array($res2);

		$Status = $lin2[ValorParametroSistema];
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];
		}	
		if($lin[Email] == "NULL"){
			$lin[Email] = "";
		}
				
		echo "<reg>";
		echo 	"<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
		echo 	"<IdHistoricoMensagem><![CDATA[$lin[IdHistoricoMensagem]]]></IdHistoricoMensagem>";
		echo 	"<IdTipoMensagem><![CDATA[$lin[IdTipoMensagem]]]></IdTipoMensagem>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<Titulo><![CDATA[$lin[Titulo]]]></Titulo>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo 	"<Status><![CDATA[$Status]]></Status>";
		echo 	"<DataEnvioTemp><![CDATA[$lin[DataEnvioTemp]]]></DataEnvioTemp>";
		echo 	"<DataEnvio><![CDATA[$lin[DataEnvio]]]></DataEnvio>";
		echo 	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
		echo 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo 	"<Celular><![CDATA[$lin[Celular]]]></Celular>";		
		echo 	"<Email><![CDATA[$lin[Email]]]></Email>";		
		if($filtro_erro != ""){
			$Erro = ",".$filtro_erro;
		}
		$Color = '';
		$Erro = '';
		switch($lin[IdStatus]){
			case 1:
				$Color	= getParametroSistema(15,7);
				$NaoEnviarEmail = "cancelarEnvio($lin[IdHistoricoMensagem],1$Erro)";
				$NaoEnviarEmailIco = "../../img/estrutura_sistema/ico_del.gif";
				break;
			case 2:
				$Color	= getParametroSistema(15,3);
				$NaoEnviarEmail = "#";
				$NaoEnviarEmailIco = "../../img/estrutura_sistema/ico_del_c.gif";
				break;
			case 3:
				$NaoEnviarEmail = "#";
				$NaoEnviarEmailIco = "../../img/estrutura_sistema/ico_del_c.gif";
				break;
			case 4:
				$NaoEnviarEmail = "#";
				$NaoEnviarEmailIco = "../../img/estrutura_sistema/ico_del_c.gif";
				break;
			case 5:
				$Color	= getParametroSistema(15,7);
				$NaoEnviarEmail = "#";
				$NaoEnviarEmailIco = "../../img/estrutura_sistema/ico_del_c.gif";
				break;
			case 6:
				$Color	= getParametroSistema(15,2);
				$NaoEnviarEmail = "#";
				$NaoEnviarEmailIco = "../../img/estrutura_sistema/ico_del_c.gif";
				break;			
		}
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<NaoEnviarEmail><![CDATA[$NaoEnviarEmail]]></NaoEnviarEmail>";
		echo 	"<NaoEnviarEmailIco><![CDATA[$NaoEnviarEmailIco]]></NaoEnviarEmailIco>";
		echo "</reg>";
	}
	
	echo "</db>";
?>