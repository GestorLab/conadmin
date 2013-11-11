<?
	$localModulo		=	1;
	$localOperacao		=	123;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja			= $_SESSION['IdLoja']; 
	$local_Login			= $_SESSION['Login']; 
	$local_IdPessoaLogin	= $_SESSION['IdPessoa'];
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_data_inicio		= $_POST['filtro_data_inicio'];
	$filtro_data_fim		= $_POST['filtro_data_fim'];
	$filtro_nome			= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_idstatus		= $_POST['filtro_idstatus'];
	$filtro_limit			= $_POST['filtro_limit'];
	$filtro_usuario			= $_POST['filtro_usuario'];
	$filtro_grupo			= $_POST['filtro_grupo'];	
	$filtro_pessoa			= $_GET['IdPessoa'];
	$filtro_os				= $_GET['IdOrdemServico'];
	$filtro_lista_concluido	= $_POST['filtro_lista_concluido'];	
	$filtro_lista_cancelado	= $_POST['filtro_lista_cancelado'];
	$filtro_lancamento		= $_GET['IdLancamentoFinanceiro'];
	$filtro_contrato		= $_GET['IdContrato'];
	$filtro_limit			= $_POST['filtro_limit'];
	
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
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and subString(OrdemServico.DataCriacao,1,10)  >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_fim!=""){
		$filtro_url .= "&DataFim=".$filtro_data_fim;
		$filtro_data_fim	= dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and subString(OrdemServico.DataCriacao,1,10)  <= '$filtro_data_fim'";
	}

	if($filtro_lancamento!=""){
		$filtro_url .= "&IdLancamentoFinanceiro=".$filtro_lancamento;
		$filtro_sql .= " and OrdemServico.IdOrdemServico in (
			select distinct
				IdOrdemServico
			from
				LancamentoFinanceiro 
			where 
				IdLoja = '$local_IdLoja' and 
				IdOrdemServico = '$filtro_lancamento' 
		)";
	}
	
	if($filtro_idstatus!=''){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and OrdemServico.IdStatus = $filtro_idstatus";
	}
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_pessoa;
		$filtro_sql .= " and OrdemServico.IdPessoa = $filtro_pessoa";
	}
	
	if($filtro_contrato!=''){
		$filtro_url .= "&IdConrato=".$filtro_contrato;
		$filtro_sql .= " and OrdemServico.IdContrato = $filtro_contrato";
		
		$filtro_os = "";
	}
	
	if($filtro_os!=''){
		$filtro_url .= "&IdOrdemServico=$filtro_os";
		$filtro_sql .=	" and OrdemServico.IdOrdemServico = '$filtro_os'";
	}
	
	
	if($filtro_grupo!=''){
		$filtro_url .= "&IdGrupoUsuarioAtendimento=".$filtro_grupo;
		$filtro_sql .=	" and OrdemServico.IdGrupoUsuarioAtendimento = '$filtro_grupo'";
	}
	
	if($filtro_usuario!=''){
		$filtro_url .= "&LoginAtendimento=".$filtro_usuario;
		$filtro_sql .=	" and OrdemServico.LoginAtendimento = '$filtro_usuario'";
	}
	if($filtro_lista_concluido == 2 && $filtro_idstatus != 200){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and OrdemServico.IdStatus != 200";
	}
	
	if($filtro_lista_cancelado == 2 && $filtro_idstatus != "0"){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and OrdemServico.IdStatus != 0";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_ordem_servico_data_cadastro_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
	$cont	=	0;
	$sql	=	"select
				    substr(OrdemServico.DescricaoOS,1,15) DescricaoOS,
					Pessoa.TipoPessoa,
				    substr(Pessoa.Nome,1,15) Nome,
				    substr(Pessoa.RazaoSocial,1,15) RazaoSocial,
				    substr(Servico.DescricaoServico,1,15) DescricaoServico,
				    OrdemServico.ValorTotal,
				    OrdemServico.LoginCriacao,
					OrdemServico.DataFaturamento,
				    OrdemServico.IdOrdemServico,
				    OrdemServico.LoginAtendimento,
				    OrdemServico.IdTipoOrdemServico,
				    TipoOrdemServico.DescricaoTipoOrdemServico,
				    OrdemServico.IdSubTipoOrdemServico,
				    SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
				    OrdemServico.IdStatus,
					OrdemServico.DataAgendamentoAtendimento,
					OrdemServico.IdMarcador
				from    
				    OrdemServico LEFT JOIN Pessoa ON (
						OrdemServico.IdPessoa = Pessoa.IdPessoa
					) left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					) LEFT JOIN Servico ON (
						OrdemServico.IdLoja = Servico.IdLoja and 
						OrdemServico.IdServico = Servico.IdServico
					) $sqlAux,
					TipoOrdemServico,
					SubTipoOrdemServico
				where
					OrdemServico.IdLoja = $local_IdLoja and
					OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
					TipoOrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
					OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
					OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico and
					TipoOrdemServico.IdTipoOrdemServico = SubTipoOrdemServico.IdTipoOrdemServico $filtro_sql
				group by
					OrdemServico.IdOrdemServico
				order by 
					OrdemServico.DataAgendamentoAtendimento DESC, OrdemServico.IdOrdemServico DESC $Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		switch($lin[IdStatus]){
			case '0': 
				#$Color	  =	getParametroSistema(15,2);
				if($local_Login == $lin[LoginCriacao]){		
					$ImgExc	  		= "../../img/estrutura_sistema/ico_del.gif";
				}else{
					$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";
				}
				break;
			case '4':
				#$Color	  = "";		
				$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
				break;
			case '5':
				#$Color	  		= getParametroSistema(15,3);
				$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";
				break;
			default:
				#$Color	  = "";		
				$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";	
		}
		
		$Color = getOrdemServicoCor($lin[IdOrdemServico]);
		$lin[DataHoraTemp] 	= dataConv($lin[DataAgendamentoAtendimento],"Y-m-d","d/m/Y");
		$lin[DataHora] 		= dataConv($lin[DataAgendamentoAtendimento],"Y-m-d H:i:s","YmdHis");
		
		$lin[DataFaturamentoTemp] 	= dataConv($lin[DataFaturamento],"Y-m-d","d/m/Y");
		$lin[DataFaturamento] 		= dataConv($lin[DataFaturamento],"Y-m-d H:i:s","YmdHis");
		
		if($lin[ValorTotal] == '')	$lin[ValorTotal] = 0;
		
		$local_Marcador		= '';
		$local_CorMarcador	= '';
		
		if($lin[IdMarcador] > 0 && $lin[IdMarcador] < 4){ //seleciona o marcador
			$local_CorMarcador = getParametroSistema(155, $lin[IdMarcador]);
		}
		
		if($local_CorMarcador!=''){
			$local_Marcador = '&#8226;';
		}
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
		echo 	"<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
		echo 	"<DescricaoOS><![CDATA[$lin[DescricaoOS]]]></DescricaoOS>";
		echo 	"<DescricaoTipoOrdemServico><![CDATA[$lin[DescricaoTipoOrdemServico]]]></DescricaoTipoOrdemServico>";
		echo 	"<DescricaoSubTipoOrdemServico><![CDATA[$lin[DescricaoSubTipoOrdemServico]]]></DescricaoSubTipoOrdemServico>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<Valor><![CDATA[$lin[ValorTotal]]]></Valor>";
		echo 	"<DataHora><![CDATA[$lin[DataHora]]]></DataHora>";
		echo 	"<DataHoraTemp><![CDATA[$lin[DataHoraTemp]]]></DataHoraTemp>";
		echo 	"<DataFaturamento><![CDATA[$lin[DataFaturamento]]]></DataFaturamento>";
		echo 	"<DataFaturamentoTemp><![CDATA[$lin[DataFaturamentoTemp]]]></DataFaturamentoTemp>";
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<Status><![CDATA[$lin3[ValorParametroSistema]]]></Status>";
		echo 	"<CorMarcador>$local_CorMarcador</CorMarcador>";
		echo 	"<Marcador>$local_Marcador</Marcador>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
		echo "</reg>";
	}
	
	echo "</db>";
?>
