<?php 
	$localModulo		= 1;
	$localOperacao		= 197;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja								= $_SESSION['IdLoja'];
	$filtro										= $_POST['filtro'];
	$filtro_ordem								= $_POST['filtro_ordem'];
	$filtro_ordem_direcao						= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado							= $_POST['filtro_tipoDado'];
	$filtro_pessoa								= url_string_xsl($_POST['filtro_pessoa'], "URL", false);
	$filtro_assunto								= url_string_xsl($_POST['filtro_assunto'], "URL", false);
	$filtro_protocolo_tipo						= $_POST['filtro_protocolo_tipo'];
	$filtro_protocolo_expirado					= $_POST['filtro_protocolo_expirado'];
	$filtro_id_grupo_usuario					= $_POST['filtro_id_grupo_usuario'];
	$filtro_login_responsavel					= url_string_xsl($_POST['filtro_login_responsavel'], "URL", false);
	$filtro_id_grupo_alteracao					= $_POST['filtro_id_grupo_alteracao'];
	$filtro_login_alteracao						= url_string_xsl($_POST['filtro_login_alteracao'], "URL", false);
	$filtro_local_abertura						= $_POST['filtro_local_abertura'];
	$filtro_id_status							= $_POST['filtro_id_status'];
	$filtro_data_inicio							= url_string_xsl($_POST['filtro_data_inicio'], "URL", false);
	$filtro_data_fim							= url_string_xsl($_POST['filtro_data_fim'], "URL", false);
	$filtro_limit								= $_POST['filtro_limit'];
	$filtro_protocolo_concluido					= $_SESSION["filtro_protocolo_concluido"];
	
	if($filtro_protocolo_tipo == ''){
		$filtro_protocolo_tipo = $_GET['IdProtocoloTipo'];
	}
	
	if($filtro_id_protocolo == ''){
		$filtro_id_protocolo = $_GET['IdProtocolo'];
	}
	
	LimitVisualizacao("listar");
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipo_dado != "")
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_id_protocolo != "")
		$filtro_sql .= " and Protocolo.IdProtocolo = $filtro_id_protocolo";
	
	if($filtro_pessoa != "") {
		$filtro_url .= "&Pessoa=".$filtro_pessoa;
		$filtro_pessoa = str_replace("'", "\'", $filtro_pessoa);
		$filtro_sql .= " and (
			Pessoa.Nome like '%$filtro_pessoa%' or 
			Pessoa.RazaoSocial like '%$filtro_pessoa%'
		)";
	}
	
	if($filtro_assunto != "") {
		$filtro_url .= "&Assunto=".$filtro_assunto;
		$filtro_sql .= " and (Protocolo.Assunto like '%$filtro_assunto%')";
	}
	
	if($filtro_protocolo_tipo != "") {
		$filtro_url .= "&IdProtocoloTipo=".$filtro_protocolo_tipo;
		$filtro_sql .= " and Protocolo.IdProtocoloTipo = $filtro_protocolo_tipo";
	}
	
	if($filtro_protocolo_expirado != "") {
		$filtro_url .= "&ProtocoloExpirado=".$filtro_protocolo_expirado;
		
		if($filtro_protocolo_expirado == 2) 
			$filtro_sql .= " and (
				substring(Protocolo.PrevisaoEtapa, 1, 16) >= '".date("Y-m-d H:i")."' or
				Protocolo.PrevisaoEtapa is null
			)";
	}
	
	if($filtro_id_grupo_alteracao != "" || $filtro_login_alteracao != "" || $filtro_data_inicio != "" || $filtro_data_fim != ""){
		$filtro_sql	.= " and Protocolo.IdProtocolo in (select distinct ProtocoloHistorico.IdProtocolo from ProtocoloHistorico where ProtocoloHistorico.IdLoja = $local_IdLoja ";
		
		if($filtro_id_grupo_alteracao != "") {
			$filtro_url .= "&IdGrupoAlteracao=".$filtro_id_grupo_alteracao;
			$filtro_sql .= " and ProtocoloHistorico.LoginCriacao in (SELECT Login FROM UsuarioGrupoUsuario WHERE IdLoja = '$local_IdLoja' AND IdGrupoUsuario = '$filtro_id_grupo_alteracao')";
		}
		
		if($filtro_login_alteracao != "") {
			$filtro_url .= "&LoginAlteracao=".$filtro_login_alteracao;
			$filtro_sql .= " and ProtocoloHistorico.LoginCriacao in (SELECT Login FROM Usuario WHERE IdPessoa in (SELECT IdPessoa FROM Usuario WHERE Login = '$filtro_login_alteracao'))";
		}
		
		if($filtro_data_inicio != ""){
			$filtro_url	.= "&DataInicio=".$filtro_data_inicio;
			$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
			$filtro_sql	.= " and subString(ProtocoloHistorico.DataCriacao,1,10) >= '".$filtro_data_inicio."'";
		}
		if($filtro_data_fim != ""){
			$filtro_url	.= "&DataFim=".$filtro_data_fim;
			$filtro_data_fim = dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
			$filtro_sql	.= " and subString(ProtocoloHistorico.DataCriacao,1,10) <= '".$filtro_data_fim."'";
		}
		
		$filtro_sql	.= ")";
	}	
	
	if($filtro_id_grupo_usuario != "") {
		$filtro_url .= "&IdGrupoUsuario=".$filtro_id_grupo_usuario;
		$filtro_sql .= " and Protocolo.IdGrupoUsuario = '$filtro_id_grupo_usuario'";
	}
	
	if($filtro_login_responsavel != "") {
		$filtro_url .= "&LoginResponsavel=".$filtro_login_responsavel;
		$filtro_sql .= " and Protocolo.LoginResponsavel in (SELECT Login FROM Usuario WHERE IdPessoa in (SELECT IdPessoa FROM Usuario WHERE Login = '$filtro_login_responsavel'))";
	}
	
	if($filtro_protocolo_concluido != "") {
		if($filtro_id_status == ""){
			if($filtro_protocolo_concluido == 2){ 
				$filtro_sql .= " and Protocolo.IdStatus != '200'";
			}
		}
	}
	
	if($filtro_local_abertura != "") {
		$filtro_url .= "&LocalAbertura=".$filtro_local_abertura;
		$filtro_sql .= " and Protocolo.LocalAbertura = $filtro_local_abertura";
	}
	
	if($filtro_id_status != "") {
		$filtro_url .= "&IdStatus=".$filtro_id_status;
		$filtro_sql .= " and Protocolo.IdStatus = $filtro_id_status";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_protocolo_usuario_atendimento_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s") {
		if($filtro_limit != "") {
			$Limit	= " limit $filtro_limit";
		}
	} else {
		if($filtro_limit == "") {
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		} else {
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "select
				Protocolo.IdProtocolo,
				Protocolo.IdProtocoloTipo,
				Protocolo.LocalAbertura IdLocalAbertura,
				substring(Protocolo.Assunto,1,80) Assunto,
				Protocolo.IdStatus,
				Protocolo.LoginResponsavel,
				Protocolo.PrevisaoEtapa,
				Protocolo.DataCriacao,
				Protocolo.LoginCriacao,
				Protocolo.LoginConclusao,
				ProtocoloTipo.DescricaoProtocoloTipo,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				Pessoa.TipoPessoa
			from 
				Protocolo left join ProtocoloTipo on (
					Protocolo.IdLoja = ProtocoloTipo.IdLoja and
					Protocolo.IdProtocoloTipo = ProtocoloTipo.IdProtocoloTipo
				) left join Pessoa on (
					Protocolo.IdPessoa = Pessoa.IdPessoa
				)
			where
				Protocolo.IdLoja = $local_IdLoja 
				$filtro_sql
			order by
				Protocolo.IdProtocolo desc
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[DataCriacao]		= dataConv($lin[DataCriacao],"Y-m-d H:i:s","Ymd");
		$lin[DataCriacaoTemp]	= dataConv($lin[DataCriacao],"Ymd","d/m/Y");
		$lin[Status]			= getParametroSistema(239, $lin[IdStatus]);
		$lin[LocalAbertura]		= getParametroSistema(205,$lin[IdLocalAbertura]);
		
		if($lin[TipoPessoa] == '1'){
			$lin[Nome] = $lin[trim(getCodigoInterno(3,24))];	
		}
		
		$temp = explode("\n", getCodigoInterno(49, $lin[IdStatus][0]));
		$CorReg = str_replace("\r", "", $temp[1]);
		
		$sql_tmp = "select 
						ValorCodigoInterno 
					from 
						CodigoInterno 
					where 
						IdGrupoCodigoInterno = '53' and 
						IdCodigoInterno = '".$lin[IdStatus]."';";
		$res_tmp = mysql_query($sql_tmp, $con);
		$lin_tmp = mysql_fetch_array($res_tmp);
		
		$temp = explode("\n", $lin_tmp[ValorCodigoInterno]);
		
		if(!empty($temp[1])){
			$CorReg = str_replace("\r", "", $temp[1]);
		}
		
		if($lin[PrevisaoEtapa] != '' && $lin[IdStatus] != 200){
			$lin[PrevisaoEtapa]		= $lin[PrevisaoEtapa];
			$lin[PrevisaoEtapaTemp]	= diferencaDataRegressivo($lin[PrevisaoEtapa], date("Y-m-d H:i:s"));
		} else{
			$lin[PrevisaoEtapa]		= '';
			$lin[PrevisaoEtapaTemp]	= '';
		}
		
		echo "<reg>";
		echo 	"<IdProtocolo>$lin[IdProtocolo]</IdProtocolo>";
		echo 	"<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
		echo 	"<IdLocalAbertura><![CDATA[$lin[IdLocalAbertura]]]></IdLocalAbertura>";
		echo 	"<LocalAbertura><![CDATA[$lin[LocalAbertura]]]></LocalAbertura>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo 	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo 	"<CorReg><![CDATA[$CorReg]]></CorReg>";
		echo 	"<LoginResponsavel><![CDATA[$lin[LoginResponsavel]]]></LoginResponsavel>";
		echo 	"<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		echo 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo 	"<LoginConclusao><![CDATA[$lin[LoginConclusao]]]></LoginConclusao>";
		echo 	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
		echo 	"<DescricaoProtocoloTipo><![CDATA[$lin[DescricaoProtocoloTipo]]]></DescricaoProtocoloTipo>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<PrevisaoEtapa><![CDATA[$lin[PrevisaoEtapa]]]></PrevisaoEtapa>";
		echo 	"<PrevisaoEtapaTemp><![CDATA[$lin[PrevisaoEtapaTemp]]]></PrevisaoEtapaTemp>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>