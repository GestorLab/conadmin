<?
	$localModulo		=	1;
	$localOperacao		=	146;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localIdLoja					= $_SESSION["IdLoja"];
	$local_Login					= $_SESSION['Login']; 
	$local_IdPessoaLogin			= $_SESSION['IdPessoa'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado				= $_POST['filtro_tipoDado'];
	
	$filtro_nome					= $_POST['filtro_nome'];
	$filtro_tipo					= $_POST['filtro_tipo'];
	$filtro_sub_tipo				= $_POST['filtro_sub_tipo'];	
	$filtro_assunto					= $_POST['filtro_assunto'];
	$filtro_local_abertura			= $_POST['filtro_local_abertura'];
	$filtro_status					= $_POST['filtro_status'];
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_campo_usuario			= $_POST['filtro_campo_usuario'];
	$filtro_usuario_alteracao		= $_POST['filtro_usuario_alteracao'];
	$filtro_campo					= $_POST['filtro_campo'];
	$filtro_valor					= $_POST['filtro_valor'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";
	
	if($filtro_tipo == '' && $_GET['IdTipo'] != ''){
		$filtro_tipo		= $_GET['IdTipo'];
	}
	
	if($filtro_sub_tipo == '' && $_GET['IdSubTipo'] != ''){
		$filtro_sub_tipo	= $_GET['IdSubTipo'];
	}
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != ""){
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	}
	if($filtro_tipo_dado != ""){
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	}	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	if($filtro_tipo!=""){
		$filtro_url	.= "&Tipo=".$filtro_tipo;
		$filtro_sql	.= " and HelpDesk.IdTipoHelpDesk = $filtro_tipo";
	}
	if($filtro_sub_tipo!=""){
		$filtro_url	.= "&SubTipo=".$filtro_sub_tipo;
		$filtro_sql	.= " and HelpDesk.IdSubTipoHelpDesk = $filtro_sub_tipo";
	}	
	if($filtro_assunto!=""){
		$filtro_url	.= "&Assunto=".$filtro_assunto;
		$filtro_sql	.= " and HelpDesk.Assunto like '%".$filtro_assunto."%'";
	}	
	if($filtro_status!=""){
		$filtro_url	.= "&IdStatus=".$filtro_status;
		$filtro_sql	.= " and HelpDesk.IdStatus=".$filtro_status;
	}
	if($filtro_local_abertura!=""){
		$filtro_url	.= "&IdLocalAbertura=".$filtro_local_abertura;
		$filtro_sql	.= " and HelpDesk.IdLocalAbertura=".$filtro_local_abertura;
	}
	if($filtro_campo_usuario != "" || $filtro_usuario_alteracao != ""){
		$filtro_url	.= "&UsuarioAlteracao=".$filtro_usuario_alteracao;
		$filtro_url	.= "&CampoUsuario=".$filtro_campo_usuario;
		switch($filtro_campo_usuario){
			case 1:
				$filtro_sql	.= " and HelpDesk.LoginCriacao='$filtro_usuario_alteracao'";
				break;
			case 2:
				$filtro_sql	.= " and HelpDesk.LoginCriacao='$filtro_usuario_alteracao'";
				break;
			case 3:
				$filtro_sql	.= " and HelpDesk.LoginResponsavel='$filtro_usuario_alteracao'";
				break;
		}
	}
	if($filtro_campo != ""){
		$filtro_url	.= "&Campo=$filtro_campo";
		$filtro_url	.= "&CampoValor=$filtro_valor";
		if($filtro_valor != ""){
			switch($filtro_campo){
				case 'DataConclusao':
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql	.= "and HelpDesk.IdStatus = 400 and SUBSTRING(HelpDesk.DataAlteracao,1,10)='$filtro_valor'";
					break;
				case 'DataFinalizacao':
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql	.= "and HelpDesk.IdStatus = 600 and SUBSTRING(HelpDesk.PrevisaoEtapa,1,10)='$filtro_valor'";
					break;
				case 'MesConclusao';
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql	.= "and HelpDesk.IdStatus = 400 and SUBSTRING(HelpDesk.DataAlteracao,1,7)='$filtro_valor'";
					break;
				case 'MesFinalizacao';
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql	.= "and HelpDesk.IdStatus = 600 and SUBSTRING(HelpDesk.PrevisaoEtapa,1,7)='$filtro_valor'";
					break;
			}
		}		
	}	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_help_desk_finalizacao_xsl.php$filtro_url\"?>";
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
					HelpDesk.IdTicket,
					HelpDesk.IdLocalAbertura,
					substr(HelpDesk.Assunto,1,28) AssuntoTemp,
					concat('Assunto: (',HelpDesk.Assunto,')') Assunto,
					HelpDesk.IdPessoa,
					HelpDesk.IdPrioridade,
					HelpDesk.IdMarcador,
					HelpDesk.IdStatus,
					HelpDesk.DataCriacao,
					HelpDesk.DataAlteracao,
					HelpDesk.PrevisaoEtapa,
					HelpDesk.IdTipoHelpDesk,
					HelpDesk.IdSubTipoHelpDesk,
					HelpDesk.LoginResponsavel,
                    Usuario.Login,
				    substr(Pessoa.RazaoSocial,1,12) NomeRepresentanteTemp,
				    Pessoa.RazaoSocial NomeRepresentante,
				    substr(Pessoa.Nome,1,12) NomeTemp,
				    Pessoa.Nome
				from
                    HelpDesk,
                    Usuario,
					Pessoa
				where
					HelpDesk.IdPessoa = Pessoa.IdPessoa
					AND HelpDesk.IdStatus <> 100 
					AND HelpDesk.IdStatus <> 200 
					AND HelpDesk.IdStatus <> 300 
					AND HelpDesk.IdStatus <> 500 
					$filtro_sql
				group by
					HelpDesk.IdTicket
				order by 
					HelpDesk.IdTicket DESC $Limit";
	$res	= @mysql_query($sql,$conCNT);
	while($lin = @mysql_fetch_array($res)){
		$local_Marcador		= '';
		$local_CorMarcador	= '';
		
		if($lin[IdStatus] < 200 || ($lin[IdStatus] > 499 && $lin[IdStatus] < 600)){
			if($lin[IdMarcador] != ''){ //seleciona o marcador
				$local_CorMarcador = getParametroSistema(155,$lin[IdMarcador]);
			}
			
			if($local_CorMarcador!=''){
				$local_Marcador = "&#8226;";
			}
		}
		
		$sql1 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=128 and IdParametroSistema=$lin[IdStatus]";
		$res1 = @mysql_query($sql1,$con);
		$lin1 = @mysql_fetch_array($res1);
		
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=129 and IdParametroSistema=$lin[IdLocalAbertura]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$sql3 = "select
					HelpDeskTipo.DescricaoTipoHelpDesk,
					subString(HelpDeskTipo.DescricaoTipoHelpDesk, 1, 11) DescricaoTipoHelpDeskTemp,
					HelpDeskSubTipo.DescricaoSubTipoHelpDesk,
					subString(HelpDeskSubTipo.DescricaoSubTipoHelpDesk, 1, 11) DescricaoSubTipoHelpDeskTemp
				from
					HelpDeskTipo,
					HelpDeskSubTipo
				where
					HelpDeskTipo.IdTipoHelpDesk = $lin[IdTipoHelpDesk] and
					HelpDeskSubTipo.IdSubTipoHelpDesk = $lin[IdSubTipoHelpDesk] and
					HelpDeskTipo.IdTipoHelpDesk = HelpDeskSubTipo.IdTipoHelpDesk";
		$res3 = @mysql_query($sql3,$conCNT);
		$lin3 = @mysql_fetch_array($res3);
		
		if($lin3[DescricaoTipoHelpDesk] != '' && $lin3[DescricaoSubTipoHelpDesk] != ''){
			$TipoSubTipo = str_replace(array("\r", "\n"), '', str_replace("'", "\'", "$lin3[DescricaoTipoHelpDesk]/$lin3[DescricaoSubTipoHelpDesk]"));
			$TipoSubTipoTemp = "$lin3[DescricaoTipoHelpDeskTemp]/$lin3[DescricaoSubTipoHelpDeskTemp]";
		} else{
			$TipoSubTipo = '';
			$TipoSubTipoTemp = '';
		}
		
		$sql4 = "SELECT 
					Obs,
					DataCriacao
				FROM 
					HelpDeskHistorico 
				WHERE 
					IdTicket = '$lin[IdTicket]'
				ORDER BY 
					IdTicketHistorico ASC 
				LIMIT 1;";
		$res4 = @mysql_query($sql4,$conCNT);
		$lin4 = @mysql_fetch_array($res4);
		
		if($lin4[Obs] != ''){
			$lin4[Obs] = str_replace(array("\r", "\n"), '', str_replace("'", "\'", $lin4[Obs]));
			$lin[Assunto] .= " <br />Data: " . dataConv($lin4[DataCriacao],"Y-m-d H:i:s","d/m/Y H:i:s");
			$lin[Assunto] .= " <br />Escrito por: (" . str_replace("</div>", ')', str_replace(" <div style=\'margin-top:6px;\'>", ") <br />Mensagem: (", end(explode("<b>Escrito por:</b> ", $lin4[Obs]))));
		}
		
		if($lin[IdPrioridade] != ''){			
			$Color		= getParametroSistema(153,$lin[IdPrioridade]);
		}		
		
		if($local_Marcador != ''){
			$ColorFundoMarcador = "#FFFFFF";
		}else{
			if($Color != ""){			
				$ColorFundoMarcador = $Color;
			}else{
				$ColorFundoMarcador = "#FFFFFF";
			}
		}
		$lin[DataHora]		= dataConv($lin[DataCriacao],"Y-m-d","Ymd");
		$lin[DataHoraTemp]	= dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
		
		if($lin[NomeRepresentanteTemp]==""){
			$lin[NomeRepresentanteTemp] = $lin[NomeTemp];
		}
		
		if($lin[NomeRepresentante]==""){
			$lin[NomeRepresentante] = $lin[Nome];
		}
		
		if($lin[PrevisaoEtapa] != '' && $lin[IdStatus] != 200){
			$lin[PrevisaoEtapa]		= $lin[PrevisaoEtapa];
			$lin[PrevisaoEtapaTemp]	= diferencaDataRegressivo($lin[PrevisaoEtapa], date("Y-m-d H:i:s"));
		} else{
			$lin[PrevisaoEtapa]		= '';
			$lin[PrevisaoEtapaTemp]	= '';
		}
		$lin[PrevisaoEtapa] = dataConv($lin[PrevisaoEtapa],"Y-m-d","d/m/Y");
		$lin[DataAlteracao] = dataConv($lin[DataAlteracao],"Y-m-d","d/m/Y");
		
		$lin[NomeRepresentante] = str_replace(array("\r", "\n"), '', str_replace("'", "\'", "$lin[NomeRepresentante]"));
		$lin[Assunto] = str_replace('"', "&quot;", $lin[Assunto]);
		$TipoSubTipo = str_replace('"', "&quot;", $TipoSubTipo);
		
		if($local_ocultar_local_abertura != 1) {
			$lin[AssuntoTemp] = substr($lin[AssuntoTemp], 0, 18);
		}
		$sql5 = "SELECT 
					IdTicket,
					MAX(DataCriacao) DataCriacao,
					IdStatusTicket 
				FROM
					HelpDeskHistorico 
				WHERE IdTicket = $lin[IdTicket] 
					AND IdStatusTicket = 400";
		$res5 = mysql_query($sql5, $con);
		$lin5 = mysql_fetch_array($res5);
		$lin5[DataCriacao] = dataConv($lin5[DataCriacao],"Y-m-d","d/m/Y");
		echo "<reg>";
		echo	"<IdStatus>$lin[IdStatus]</IdStatus>";
		echo 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";
		echo 	"<CorMarcador>$local_CorMarcador</CorMarcador>";
		echo 	"<Marcador>$local_Marcador</Marcador>";
		echo 	"<IdTicket>$lin[IdTicket]</IdTicket>";
		echo 	"<NomeTemp><![CDATA[$lin[NomeRepresentanteTemp]]]></NomeTemp>";
		echo 	"<Nome><![CDATA[$lin[NomeRepresentante]]]></Nome>";
		echo 	"<LocalAbertura><![CDATA[$lin2[ValorParametroSistema]]]></LocalAbertura>";
		echo 	"<TipoSubTipo><![CDATA[$TipoSubTipo]]></TipoSubTipo>";
		echo 	"<TipoSubTipoTemp><![CDATA[$TipoSubTipoTemp]]></TipoSubTipoTemp>";
		echo 	"<AssuntoTemp><![CDATA[$lin[AssuntoTemp]]]></AssuntoTemp>";
		echo 	"<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
		echo 	"<Responsavel><![CDATA[$lin[LoginResponsavel]]]></Responsavel>";
		echo 	"<DataFinalizacao><![CDATA[$lin5[DataCriacao]]]></DataFinalizacao>";
		echo 	"<PrevisaoEtapaTemp><![CDATA[$lin[PrevisaoEtapaTemp]]]></PrevisaoEtapaTemp>";
		echo 	"<Status><![CDATA[$lin1[ValorParametroSistema]]]></Status>";
		echo 	"<DataHora><![CDATA[$lin[DataHora]]]></DataHora>";
		echo 	"<DataHoraTemp><![CDATA[$lin[DataHoraTemp]]]></DataHoraTemp>";
		echo 	"<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<ColorFundoMarcador><![CDATA[$ColorFundoMarcador]]></ColorFundoMarcador>";
		echo "</reg>";
	}
	
	echo "</db>";
?>