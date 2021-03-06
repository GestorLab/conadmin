<?
	$localModulo		=	2;
	$localOperacao		=	1;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('rotinas/verifica.php');
	  
	$local_IdLoja				= $_SESSION["IdLojaHD"];
	$local_Login				= $_SESSION["LoginHD"];
	$local_IdPessoaLogin		= $_SESSION['IdPessoa'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado			= $_POST['filtro_tipoDado'];
	
	$filtro_assunto				= $_POST['filtro_assunto'];
	$filtro_tipo				= $_POST['filtro_tipo'];
	$filtro_sub_tipo			= $_POST['filtro_sub_tipo'];
	$filtro_data_inicio			= $_POST['filtro_data_inicio'];
	$filtro_data_fim			= $_POST['filtro_data_fim'];
	$filtro_status				= $_POST['filtro_status'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_ticket				= $_POST['IdTicket'];
	$filtro_help_desk_concluido	= $_SESSION["filtro_help_desk_concluido"];
	
	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";
	
	if($filtro_ticket == '' && $_GET['IdTicket'] != ''){
		$filtro_ticket			= $_GET['IdTicket'];
	}
	
	LimitVisualizacaoHelpDesk("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipo_dado != "")
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	
	if($filtro_ticket!=""){
		$filtro_url	.= "&IdTicket=".$filtro_ticket;
		$filtro_sql	.= " and HelpDesk.IdTicket=".$filtro_ticket;
	}
	if($filtro_assunto!=""){
		$filtro_url	.= "&Assunto=".$filtro_assunto;
		$filtro_sql	.= " and HelpDesk.Assunto like '%".$filtro_assunto."%'";
	}
	if($filtro_tipo!=""){
		$filtro_url	.= "&Tipo=".$filtro_tipo;
		$filtro_sql	.= " and HelpDesk.IdTipoHelpDesk=$filtro_tipo";
	}
	if($filtro_sub_tipo!=""){
		$filtro_url	.= "&SubTipo=".$filtro_sub_tipo;
		$filtro_sql	.= " and HelpDesk.IdSubTipoHelpDesk=$filtro_sub_tipo";
	}
	if($filtro_data_inicio!=""){
		$filtro_url	.= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql	.= " and subString(HelpDesk.DataCriacao,1,10) >= '".$filtro_data_inicio."'";
	}
	if($filtro_data_fim!=""){
		$filtro_url	.= "&DataFim=".$filtro_data_fim;
		$filtro_data_fim = dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
		$filtro_sql	.= " and subString(HelpDesk.DataCriacao,1,10) <= '".$filtro_data_fim."'";
	}
	if($filtro_status!=""){
		$filtro_url	.= "&IdStatus=".$filtro_status;
		$filtro_sql	.= " and HelpDesk.IdStatus=".$filtro_status;
	}
	if($filtro_help_desk_concluido!=""){
		if($filtro_help_desk_concluido == 2 && $filtro_status == ""){
			$filtro_sql  .= " and HelpDesk.IdStatus != 400";
		}
	}
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

	if(getParametroSistema(229,1) == 1){
		$filtro_sql .= " and HelpDesk.IdLojaAbertura = $local_IdLoja"; 
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_help_desk_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getParametroSistema(146,2);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	$IdPessoa = getParametroSistema(4,7);
	
	$sql	=	"select
					HelpDesk.IdTicket,
					HelpDesk.IdPessoa,
					HelpDesk.IdMarcador,
					subString(HelpDesk.Assunto,1,18) AssuntoTemp,
					concat('Assunto: (',HelpDesk.Assunto,')') Assunto,
					HelpDesk.IdTipoHelpDesk,
					HelpDesk.IdSubTipoHelpDesk,
					HelpDesk.IdStatus,
					HelpDesk.PrevisaoEtapa,
					HelpDesk.LoginResponsavel,
					HelpDesk.LoginCriacao,
					HelpDesk.DataCriacao
				from
					HelpDesk
				where
					HelpDesk.IdLoja = 1 and					
					HelpDesk.IdPessoa = $IdPessoa
					$filtro_sql
				order by 
					HelpDesk.IdTicket DESC $Limit";
	$res	= @mysql_query($sql,$conCNT);
	while($lin = @mysql_fetch_array($res)){
	
		$sql1 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=128 and IdParametroSistema=$lin[IdStatus]";
		$res1 = @mysql_query($sql1,$con);
		$lin1 = @mysql_fetch_array($res1);
		
		$sql2 = "select
					HelpDeskTipo.DescricaoTipoHelpDesk,
					HelpDeskSubTipo.DescricaoSubTipoHelpDesk,
					subString(HelpDeskTipo.DescricaoTipoHelpDesk, 1, 12) DescricaoTipoHelpDeskTemp,
					subString(HelpDeskSubTipo.DescricaoSubTipoHelpDesk, 1, 12) DescricaoSubTipoHelpDeskTemp
				from
					HelpDeskTipo,
					HelpDeskSubTipo
				where
					HelpDeskTipo.IdTipoHelpDesk = $lin[IdTipoHelpDesk] and
					HelpDeskSubTipo.IdSubTipoHelpDesk = $lin[IdSubTipoHelpDesk] and
					HelpDeskTipo.IdTipoHelpDesk = HelpDeskSubTipo.IdTipoHelpDesk";
		$res2 = @mysql_query($sql2,$conCNT);
		$lin2 = @mysql_fetch_array($res2);
		
		if($lin2[DescricaoTipoHelpDesk] != '' && $lin2[DescricaoSubTipoHelpDesk] != ''){
			$TipoSubTipo		= str_replace(array("\r", "\n"), '', str_replace("'", "\'", "$lin2[DescricaoTipoHelpDesk]/$lin2[DescricaoSubTipoHelpDesk]"));
			$TipoSubTipoTemp	= "$lin2[DescricaoTipoHelpDeskTemp]/$lin2[DescricaoSubTipoHelpDeskTemp]";
		} else{
			$TipoSubTipo		= '';
			$TipoSubTipoTemp	= '';
		}
		
		$sql2 = "SELECT 
					Obs,
					DataCriacao
				FROM 
					HelpDeskHistorico 
				WHERE 
					IdTicket = '$lin[IdTicket]'
				ORDER BY 
					IdTicketHistorico ASC 
				LIMIT 1;";
		$res2 = @mysql_query($sql2,$conCNT);
		$lin2 = @mysql_fetch_array($res2);
		
		if($lin2[Obs] != ''){
			$lin2[Obs] = str_replace(array("\r", "\n"), '', str_replace("'", "\'", $lin2[Obs]));
			$lin[Assunto] .= " <br />Data: " . dataConv($lin2[DataCriacao],"Y-m-d H:i:s","d/m/Y H:i:s");
			$lin[Assunto] .= " <br />Escrito por: (" . str_replace("</div>", ')', str_replace(" <div style=\'margin-top:6px;\'>", ") <br />Mensagem: (", end(explode("<b>Escrito por:</b> ", $lin2[Obs]))));
		}
		
		if(($lin[IdStatus] > 99 && $lin[IdStatus] < 400) || ($lin[IdStatus] > 499)){
			$Color = getParametroSistema(154,$lin[IdStatus]);
		} else{
			$Color = '';
		}
		
		$lin[DataHora]		= dataConv($lin[DataCriacao],"Y-m-d","Ymd");
		$lin[DataHoraTemp]	= dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
		
		if($lin[PrevisaoEtapa] != '' && (($lin[IdStatus] > 99 && $lin[IdStatus] < 200) || ($lin[IdStatus] > 299 && $lin[IdStatus] < 400) || ($lin[IdStatus] > 499 && $lin[IdStatus] < 600))){
			$lin[PrevisaoEtapa]		= $lin[PrevisaoEtapa];
			$lin[PrevisaoEtapaTemp]	= diferencaDataRegressivo($lin[PrevisaoEtapa], date("Y-m-d H:i:s"));
		} else{
			$lin[PrevisaoEtapa]		= '';
			$lin[PrevisaoEtapaTemp]	= '';
		}
		
		echo "<reg>";
		echo	"<IdStatus>$lin[IdStatus]</IdStatus>";
		echo 	"<IdTicket>$lin[IdTicket]</IdTicket>";
		echo 	"<AssuntoTemp><![CDATA[$lin[AssuntoTemp]]]></AssuntoTemp>";
		echo 	"<Assunto><![CDATA[".str_replace('"', "&quot;", $lin[Assunto])."]]></Assunto>";
		echo 	"<Responsavel><![CDATA[$lin[LoginResponsavel]]]></Responsavel>";
		echo 	"<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		echo 	"<TipoSubTipoTemp><![CDATA[$TipoSubTipoTemp]]></TipoSubTipoTemp>";
		echo 	"<TipoSubTipo><![CDATA[".str_replace('"', "&quot;", $TipoSubTipo)."]]></TipoSubTipo>";
		echo 	"<PrevisaoEtapa><![CDATA[$lin[PrevisaoEtapa]]]></PrevisaoEtapa>";
		echo 	"<PrevisaoEtapaTemp><![CDATA[$lin[PrevisaoEtapaTemp]]]></PrevisaoEtapaTemp>";
		echo 	"<Status><![CDATA[$lin1[ValorParametroSistema]]]></Status>";
		echo 	"<DataHora><![CDATA[$lin[DataHora]]]></DataHora>";
		echo 	"<DataHoraTemp><![CDATA[$lin[DataHoraTemp]]]></DataHoraTemp>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";
	}
	
	echo "</db>";
?>
