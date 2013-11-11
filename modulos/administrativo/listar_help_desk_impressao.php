<?
	$localModulo		=	1;
	$localOperacao		=	164;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login				= $_SESSION['Login']; 
	$local_IdPessoaLogin		= $_SESSION['IdPessoa'];
	$local_IdLoja				= $_SESSION['IdLoja'];
	

	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado			= $_POST['filtro_tipoDado'];
	
	$filtro_campo				= $_POST['filtro_campo'];
	$filtro_valor				= $_POST['filtro_valor'];
	$filtro_prioridade			= $_POST['filtro_prioridade'];
	$filtro_tipo				= $_POST['filtro_tipo'];
	$filtro_sub_tipo			= $_POST['filtro_sub_tipo'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_ticket				= $_POST['IdTicket'];
	$filtro_grupo_atendimento	= $_POST['filtro_grupo_atendimento'];
	$filtro_usuario_atendimento	= $_POST['filtro_usuario_atendimento'];
	$filtro_status				= $_POST['filtro_status'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";
	$IdTicket	= "0";
	
	if($filtro_ticket == '' && $_GET['IdTicket'] != ''){
		$filtro_ticket		= $_GET['IdTicket'];
	}
	
	if($filtro_tipo == '' && $_GET['IdTipo'] != ''){
		$filtro_tipo		= $_GET['IdTipo'];
	}
	
	if($filtro_sub_tipo == '' && $_GET['IdSubTipo'] != ''){
		$filtro_sub_tipo	= $_GET['IdSubTipo'];
	}
	
	if($_GET['IdPessoa'] != ''){
		$filtro_IdPessoa	= $_GET['IdPessoa'];
	}
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipo_dado != "")
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	
	if($filtro_ticket != ""){
		$filtro_url	.= "&IdTicket=$filtro_ticket";
		$filtro_sql .=	" and HelpDesk.IdTicket = '$filtro_ticket'";
	}

	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=$filtro_valor";
		
		if($filtro_valor != ''){
			switch($filtro_campo){
				case 'UsuarioCadastro':
					
					$filtro_sql .=	" and (HelpDesk.LoginCriacao like '$filtro_valor')";
					break;
				case 'EscritoPor':				
					
					$filtro_sql1 .=	" and (HelpDeskHistorico.LoginCriacao like '$filtro_valor')";
					break;
				case 'UsuarioResponsavel':					
					
					$filtro_sql1 .=	" and (HelpDesk.LoginResponsavel like '%$filtro_valor%')";
					break;
			}
		}
	}else{
		$filtro_valor	=	"";	
	}
	if($filtro_prioridade!=""){
		$filtro_url	.= "&IdPrioridade=".$filtro_prioridade;
		$filtro_sql	.= " and HelpDesk.IdPrioridade=".$filtro_prioridade;
	}
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=$filtro_IdPessoa";
		$filtro_sql .=	" and Pessoa.IdPessoa=$filtro_IdPessoa";
	}
	if($filtro_tipo!=""){
		$filtro_url	.= "&Tipo=".$filtro_tipo;
		$filtro_sql	.= " and HelpDesk.IdTipoHelpDesk = $filtro_tipo";
	}
	if($filtro_sub_tipo!=""){
		$filtro_url	.= "&SubTipo=".$filtro_sub_tipo;
		$filtro_sql	.= " and HelpDesk.IdSubTipoHelpDesk = $filtro_sub_tipo";
	}		

	if($filtro_grupo_atendimento != ''){
		$filtro_url	.=	"&GrupoAtendimento=".$filtro_grupo_atendimento;
		$filtro_sql	.= " and HelpDesk.IdGrupoUsuario = $filtro_grupo_atendimento";
	}

	if($filtro_usuario_atendimento != ''){
		$filtro_url	 .=	"&UsuarioAtendimento=".$filtro_usuario_atendimento;
		$filtro_sql .= " and HelpDesk.LoginResponsavel like '$filtro_usuario_atendimento'";
	}

	if($filtro_status!=""){
		$filtro_url	.= "&IdStatus=".$filtro_status;
		$filtro_sql	.= " and HelpDesk.IdStatus=".$filtro_status;
	}

	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_help_desk_impressao_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"SELECT
					HelpDesk.IdTicket,
					HelpDesk.IdLocalAbertura,
					HelpDesk.Assunto AssuntoTemp,
					CONCAT('Assunto: (',HelpDesk.Assunto,')') Assunto,
					HelpDesk.IdPessoa,
					HelpDesk.IdPrioridade,
					HelpDesk.IdMarcador,
					HelpDesk.IdStatus,
					HelpDesk.DataCriacao,
					HelpDesk.PrevisaoEtapa,
					HelpDesk.IdTipoHelpDesk,
					HelpDesk.IdSubTipoHelpDesk,
					HelpDesk.LoginResponsavel,
					HelpDesk.LoginCriacao,
					SUBSTR(Pessoa.RazaoSocial,1,12) NomeRepresentanteTemp,
					Pessoa.RazaoSocial NomeRepresentante,
					SUBSTR(Pessoa.Nome,1,12) NomeTemp,
					Pessoa.Nome
				FROM
					HelpDesk,
					HelpDeskHistorico,
					Pessoa
				WHERE
					HelpDesk.IdTicket = HelpDeskHistorico.IdTicket AND
					HelpDeskHistorico.IdTicketHistorico = 1 AND
					HelpDesk.IdPessoa = Pessoa.IdPessoa 
					$filtro_sql
				ORDER BY
					HelpDesk.IdTicket desc,
					HelpDesk.LoginResponsavel,
					HelpDesk.IdPrioridade,
					HelpDesk.DataCriacao $Limit";
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
		
		$sql3 = "SELECT 
					Obs,
					DataCriacao
				FROM 
					HelpDeskHistorico 
				WHERE 
					IdTicket = '$lin[IdTicket]' and
					IdTicketHistorico = 1 
				ORDER BY 
					IdTicketHistorico ASC 
				LIMIT 1;";
		$res3 = @mysql_query($sql3,$conCNT);
		$lin3 = @mysql_fetch_array($res3);
		
		if($lin3[Obs] != ''){
			$lin3[Obs] = str_replace(array("\r", "\n"), '', str_replace("'", "\'", $lin3[Obs]));
			$lin[Assunto] .= " <br />Data: " . dataConv($lin3[DataCriacao],"Y-m-d H:i:s","d/m/Y H:i:s");
			$lin[Assunto] .= " <br />Escrito por: (" . str_replace("</div>", ')', str_replace(" <div style=\'margin-top:6px;\'>", ") <br />Menssagem: (", end(explode("<b>Escrito por:</b> ", $lin3[Obs]))));
			$Conteudo = end(explode("<b>Escrito por:</b> ", $lin3[Obs]));		
			$Conteudo = "Escrito por: ".$Conteudo;
			$Conteudo = str_replace("</div>","",$Conteudo);
			$Conteudo = str_replace("<div style=\'margin-top:6px;\'>","",$Conteudo);
			$Conteudo = explode("Data do Cadastro:", $Conteudo);	
			$Conteudo = $Conteudo[0];
			$Conteudo = explode("Previsão de Conclusão da Etapa.", $Conteudo);	
			$Conteudo = $Conteudo[0];			 
		}		
		
		$lin[DataHora]		= dataConv($lin[DataCriacao],"Y-m-d","Ymd");
		$lin[DataHoraTemp]	= dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
				
		if($lin[PrevisaoEtapa] != '' && $lin[IdStatus] != 200){
			$lin[PrevisaoEtapa]		= $lin[PrevisaoEtapa];
			$lin[PrevisaoEtapaTemp]	= diferencaDataRegressivo($lin[PrevisaoEtapa], date("Y-m-d H:i:s"));
		} else{
			$lin[PrevisaoEtapa]		= '';
			$lin[PrevisaoEtapaTemp]	= '';
		}		
		
		echo "<reg>";
		echo 	"<IdTicket>$lin[IdTicket]</IdTicket>";
		echo 	"<TipoSubTipo><![CDATA[".str_replace('"', "&quot;", $TipoSubTipo)."]]></TipoSubTipo>";
		echo 	"<AssuntoTemp><![CDATA[$lin[AssuntoTemp]]]></AssuntoTemp>";
		echo 	"<Assunto><![CDATA[".str_replace('"', "&quot;", $lin[Assunto])."]]></Assunto>";
		echo 	"<Conteudo><![CDATA[$Conteudo]]></Conteudo>";		
		echo 	"<LoginResponsavel><![CDATA[$lin[LoginResponsavel]]]></LoginResponsavel>";
		echo 	"<PrevisaoEtapa><![CDATA[$lin[PrevisaoEtapa]]]></PrevisaoEtapa>";
		echo 	"<PrevisaoEtapaTemp><![CDATA[$lin[PrevisaoEtapaTemp]]]></PrevisaoEtapaTemp>";
		echo 	"<Status><![CDATA[$lin1[ValorParametroSistema]]]></Status>";
		echo 	"<DataHora><![CDATA[$lin[DataHora]]]></DataHora>";
		echo 	"<DataHoraTemp><![CDATA[$lin[DataHoraTemp]]]></DataHoraTemp>";
		echo "</reg>";
	}
	
	echo "</db>";
?>