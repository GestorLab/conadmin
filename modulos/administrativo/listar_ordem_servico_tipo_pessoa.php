<?
	$localModulo		=	1;
	$localOperacao		=	163;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja				= $_SESSION['IdLoja']; 
	$local_Login				= $_SESSION['Login']; 
	$local_IdPessoaLogin		= $_SESSION['IdPessoa'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_servico				= url_string_xsl($_POST['filtro_servico'],'url',false);
	$filtro_nome				= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_idstatus			= $_POST['filtro_idstatus'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_campo				= $_POST['filtro_campo'];
	$filtro_valor				= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_faturado			= $_POST['filtro_faturado'];
	$filtro_forma_cobranca		= $_POST['filtro_forma_cobranca'];
	$filtro_tipo_pessoa			= $_POST['filtro_tipo_pessoa'];
	$filtro_pessoa				= $_GET['IdPessoa'];
	$filtro_os					= $_GET['IdOrdemServico'];
	$filtro_lancamento			= $_GET['IdLancamentoFinanceiro'];
	$filtro_contrato			= $_GET['IdContrato'];
	$filtro_IdCarne				= $_GET['IdCarne'];
	$filtro_IdContaReceber		= $_GET['IdContaReceber'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_lista_concluido		= $_POST['filtro_lista_concluido'];
	$filtro_lista_cancelado		= $_POST['filtro_lista_cancelado'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";
	
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
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_descricao_os!=''){
		$filtro_url .= "&DescricaoOS=$filtro_descricao_os";
		$filtro_sql .=	" and OrdemServico.DescricaoOS like '%$filtro_descricao_os%'";
	}
		
	if($filtro_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_servico%'";
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

	if($filtro_forma_cobranca!=""){
		$filtro_url .= "&FormaCobranca=".$filtro_forma_cobranca;
		$filtro_sql .= " and OrdemServico.FormaCobranca = '$filtro_forma_cobranca'";
	}

	if($filtro_tipo_pessoa!=""){
		$filtro_url .= "&TipoPessoa=".$filtro_tipo_pessoa;
		$filtro_sql .= " and Pessoa.TipoPessoa = '$filtro_tipo_pessoa'";
	}
	
	if($filtro_idstatus!=''){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and OrdemServico.IdStatus = $filtro_idstatus";
	}
	
	if($filtro_IdCarne!=''){
		$filtro_sql .= " and OrdemServico.IdOrdemServico in (
			SELECT DISTINCT
				LancamentoFinanceiroDados.IdOrdemServico 
			FROM 
				ContaReceberDados, 
				LancamentoFinanceiroDados
			WHERE 
				ContaReceberDados.IdLoja = $local_IdLoja AND
				ContaReceberDados.IdCarne = $filtro_IdCarne AND
				ContaReceberDados.IdLoja = LancamentoFinanceiroDados.IdLoja AND
				ContaReceberDados.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber)";
	}
	
	if($filtro_faturado!=''){
		$filtro_url .= "&Faturado=".$filtro_faturado;
		$filtro_sql .= " and OrdemServico.Faturado = $filtro_faturado";
	}
	
	if($filtro_lista_concluido == 2 && $filtro_idstatus != 200){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and OrdemServico.IdStatus != 200";
	}
	
	if($filtro_lista_cancelado == 2 && $filtro_idstatus != "0"){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and OrdemServico.IdStatus != 0";
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
	
	if($filtro_IdContaReceber!=''){
		$filtro_sql .=	" and OrdemServico.IdOrdemServico in (
			select	
				LancamentoFinanceiro.IdOrdemServico
			from
				LancamentoFinanceiroContaReceber,
				LancamentoFinanceiro	
			where
				LancamentoFinanceiroContaReceber.IdLoja = '$local_IdLoja' and
				LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
				LancamentoFinanceiroContaReceber.IdContaReceber = '$filtro_IdContaReceber' and
				LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro
		)";
	}
	
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=$filtro_valor";
		
		switch($filtro_campo){
			case 'DataCadastro':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql    .=	" and OrdemServico.DataCriacao like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and OrdemServico.DataCriacao is NULL";
				}
				break;
			case 'DataAlteracao':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql    .=	" and OrdemServico.DataAlteracao like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and OrdemServico.DataAlteracao is NULL";
				}
				break;
			case 'MesCadastro':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql    .=	" and substring(OrdemServico.DataCriacao, 1, 7) like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and substring(OrdemServico.DataCriacao, 1, 7) is NULL";
				}
				break;
			case 'MesAlteracao':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql    .=	" and substring(OrdemServico.DataAlteracao, 1, 7) like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and substring(OrdemServico.DataAlteracao, 1, 7) is NULL";
				}
				break;
			case 'MesConclusao':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql    .=	" and substring(OrdemServico.DataConclusao, 1, 7) like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and substring(OrdemServico.DataConclusao, 1, 7) is NULL";
				}
				break;
			case 'DataAgendamento':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql    .=	" and OrdemServico.DataAgendamentoAtendimento like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and OrdemServico.DataAgendamentoAtendimento is NULL";
				}
				break;
			case 'DataConclusao':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql    .=	" and OrdemServico.DataConclusao like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and OrdemServico.DataConclusao is NULL";
				}
				break;
			case 'DataFaturamento':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql    .=	" and OrdemServico.DataFaturamento like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and OrdemServico.DataFaturamento is NULL";
				}
				break;
			case 'MesAgendamento':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql    .=	" and substring(OrdemServico.DataAgendamentoAtendimento,1,7) = '$filtro_valor'";
				}else{
					$filtro_sql    .=	" and substring(OrdemServico.DataAgendamentoAtendimento, 1, 7) is NULL";
				}
				break;
			case 'MesFaturamento':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql    .=	" and substring(OrdemServico.DataFaturamento, 1, 7) = '$filtro_valor'";
				}else{
					$filtro_sql    .=	" and substring(OrdemServico.DataFaturamento, 1, 7) is NULL";
				}	
				break;
			case 'DescricaoOS':
				$filtro_sql .=	" and OrdemServico.DescricaoOS like '%$filtro_valor%'";
				break;
			case 'IdGrupoUsuarioAtendimento':
				$filtro_sql .=	" and GrupoUsuario.DescricaoGrupoUsuario like '%$filtro_valor%' AND GrupoUsuario.IdGrupoUsuario = OrdemServico.IdGrupoUsuarioAtendimento";
				break;
			case 'UsuarioCadastro':
				$filtro_sql .=	" and (OrdemServico.LoginCriacao like '%$filtro_valor%')";
				break;
			case 'LoginAtendimento':
				$filtro_sql .=	" and (OrdemServico.LoginAtendimento like '%$filtro_valor%')";
				break;
		}
	}else{
		$filtro_valor	=	"";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_ordem_servico_tipo_pessoa_xsl.php$filtro_url\"?>";
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
				    OrdemServico.IdOrdemServico,
				    OrdemServico.IdTipoOrdemServico,
				    TipoOrdemServico.DescricaoTipoOrdemServico,
				    OrdemServico.IdSubTipoOrdemServico,
				    SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
				    substr(OrdemServico.DescricaoOS,1,12) DescricaoOS,
					Pessoa.TipoPessoa,
				    substr(Pessoa.Nome,1,12) Nome,
				    substr(Pessoa.RazaoSocial,1,12) RazaoSocial,
				    substr(Servico.DescricaoServico,1,12) DescricaoServico,
				    OrdemServico.ValorTotal,
				    OrdemServico.LoginCriacao,
					OrdemServico.DataFaturamento,
				    OrdemServico.LoginAtendimento,
					OrdemServico.IdGrupoUsuarioAtendimento,
				    OrdemServico.IdStatus,
					OrdemServico.DataAgendamentoAtendimento,
					OrdemServico.IdContrato,
					OrdemServico.IdMarcador,
					OrdemServico.DataCriacao
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
					GrupoUsuario,
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
					OrdemServico.DataCriacao DESC, 
					OrdemServico.IdOrdemServico DESC $Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$sql2 = "
			SELECT 
				UsuarioGrupoUsuario.IdGrupoUsuario, 
				GrupoUsuario.DescricaoGrupoUsuario,
				Usuario.Login,
				Pessoa.Nome,
				UsuarioGrupoUsuario.DataCriacao,
				UsuarioGrupoUsuario.LoginCriacao
			FROM 
				UsuarioGrupoUsuario, 
				GrupoUsuario, 
				Usuario, 
				Pessoa 
			WHERE 
				UsuarioGrupoUsuario.IdLoja = '$local_IdLoja' AND 
				UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja AND 
				UsuarioGrupoUsuario.Login = Usuario.Login AND 
				UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario AND 
				Usuario.IdPessoa = Pessoa.IdPessoa AND 
				Pessoa.TipoUsuario = 1 AND
				Usuario.IdStatus = 1 AND 
				UsuarioGrupoUsuario.Login = '$lin[LoginAtendimento]' AND 
				UsuarioGrupoUsuario.IdGrupoUsuario = '$lin[IdGrupoUsuarioAtendimento]'
			ORDER BY
				Usuario.Login 
		";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$temp = explode(' ', $lin2[Nome]);
		$lin2[NomeTemp] = $temp[0];
		
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		if($lin[IdStatus] >= 0 && $lin[IdStatus] <= 99){			
			#$Color	  =	getParametroSistema(15,2);
			if($local_Login == $lin[LoginCriacao]){		
				$ImgExc	  		= "../../img/estrutura_sistema/ico_del.gif";
			}else{
				$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";
			}
		}
		if($lin[IdStatus] >= 400 && $lin[IdStatus] <= 499){			
			#$Color	  = "";		
			$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
		}
		if($lin[IdStatus] >= 500 && $lin[IdStatus] <= 599){						
			#$Color	  		= getParametroSistema(15,3);
			$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";
		}		
		if($lin[IdStatus] > 99 && $lin[IdStatus] < 400){
			#$Color	  = "";		
			$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";	
		}
		
		$Color = getOrdemServicoCor($lin[IdOrdemServico]);
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
		if(($lin[IdStatus] >= 0 && $lin[IdStatus] <= 99) || ($lin[IdStatus] >= 200 && $lin[IdStatus] <= 299)){
			$local_TempoAbertura = "";			
		}else{
		    if((int)getCodigoInterno(3,182) == 1){
				$local_TempoAbertura = diferencaDataUtil($lin[DataCriacao], date("Y-m-d H:i:s"));
            } else{
                $local_TempoAbertura = diferencaData($lin[DataCriacao], date("Y-m-d H:i:s"));
            }
		}
	
		$DiaAbertura = nDiasIntervalo($lin[DataCriacao],Date("Y-m-d H:i:s"));
				
		$lin[DataCriacaoTemp]	 = dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");	
		$lin[DataCriacao] 		 = dataConv($lin[DataCriacao],"Y-m-d","Ymd");	
		
		if($lin[TipoPessoa] == 1)
			$lin[Tipo_Pessoa] = "Juridica";
		else
			$lin[Tipo_Pessoa] = "Fisica";
			
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
		echo 	"<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
		echo 	"<Tipo_Pessoa><![CDATA[$lin[Tipo_Pessoa]]]></Tipo_Pessoa>";
		echo 	"<DescricaoOS><![CDATA[$lin[DescricaoOS]]]></DescricaoOS>";
		echo 	"<DescricaoTipoOrdemServico><![CDATA[$lin[DescricaoTipoOrdemServico]]]></DescricaoTipoOrdemServico>";
		echo 	"<DescricaoSubTipoOrdemServico><![CDATA[$lin[DescricaoSubTipoOrdemServico]]]></DescricaoSubTipoOrdemServico>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<Valor><![CDATA[$lin[ValorTotal]]]></Valor>";
		echo 	"<DataHora><![CDATA[$lin[DataHora]]]></DataHora>";	
		echo 	"<Nome><![CDATA[$lin2[Nome]]]></Nome>";	
		echo 	"<NomeTemp><![CDATA[$lin2[NomeTemp]]]></NomeTemp>";	
		
		echo 	"<DataFaturamento><![CDATA[$lin[DataFaturamento]]]></DataFaturamento>";
		echo 	"<DataFaturamentoTemp><![CDATA[$lin[DataFaturamentoTemp]]]></DataFaturamentoTemp>";
		echo 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo 	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<Status><![CDATA[$lin3[ValorParametroSistema]]]></Status>";
		echo 	"<CorMarcador>$local_CorMarcador</CorMarcador>";
		echo 	"<Marcador>$local_Marcador</Marcador>";
		echo 	"<TempoAbertura><![CDATA[$local_TempoAbertura]]></TempoAbertura>";
		echo 	"<DiaAbertura><![CDATA[$DiaAbertura]]></DiaAbertura>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
		echo "</reg>";
	}
	
	echo "</db>";
?>
