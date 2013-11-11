<?
	$localModulo		=	1;
	$localOperacao		=	65;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login				= $_SESSION['Login']; 
	$local_IdPessoaLogin		= $_SESSION['IdPessoa'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado			= $_POST['filtro_tipoDado'];
	
	$filtro_descricao_tipo		= $_POST['filtro_descricao_tipo'];
	$filtro_descricao_subtipo	= $_POST['filtro_descricao_subtipo'];
	$filtro_id_status			= $_POST['filtro_id_status'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";
	
	if($filtro_idtipo == '' && $_GET['IdTipo'] != ''){
		$filtro_idtipo		= $_GET['IdTipo'];
	}
	if($filtro_idsubtipo == '' && $_GET['IdSubTipo'] != ''){
		$filtro_idsubtipo	= $_GET['IdSubTipo'];
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
	
	if($filtro_descricao_tipo != ""){
		$filtro_url	.= "&DescricaoTipo=$filtro_descricao_tipo";
		$filtro_sql .=	" and HelpDeskTipo.DescricaoTipoHelpDesk like '%$filtro_descricao_tipo%'";
	}
	if($filtro_descricao_subtipo != ""){
		$filtro_url	.= "&DescricaoSubTipo=$filtro_descricao_subtipo";
		$filtro_sql .=	" and HelpDeskSubTipo.DescricaoSubTipoHelpDesk like '%$filtro_descricao_subtipo%'";
	}
	if($filtro_id_status!=''){
		$filtro_url .= "&IdStatus=$filtro_id_status";
		$filtro_sql .=	" and HelpDeskSubTipo.IdStatus = '$filtro_id_status'";
	}
	if($filtro_idtipo!=''){
		$filtro_sql .=	" and HelpDeskTipo.IdTipoHelpDesk = '$filtro_idtipo'";
	}
	if($filtro_idsubtipo!=''){
		$filtro_sql .=	" and HelpDeskSubTipo.IdSubTipoHelpDesk = '$filtro_idsubtipo'";
	}
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_subtipo_help_desk_xsl.php$filtro_url\"?>";
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
					HelpDeskTipo.IdTipoHelpDesk,
					HelpDeskTipo.DescricaoTipoHelpDesk,
					HelpDeskSubTipo.IdSubTipoHelpDesk,
					HelpDeskSubTipo.DescricaoSubTipoHelpDesk,
					ParametroSistema.ValorParametroSistema as Status
				from
					HelpDeskTipo,
					HelpDeskSubTipo,
					ParametroSistema
				where
					HelpDeskTipo.IdTipoHelpDesk = HelpDeskSubTipo.IdTipoHelpDesk and
					ParametroSistema.IdGrupoParametroSistema = 157 and
					ParametroSistema.IdParametroSistema = HelpDeskSubTipo.IdStatus $filtro_sql
				order by 
					HelpDeskTipo.IdTipoHelpDesk desc,
					HelpDeskSubTipo.IdSubTipoHelpDesk desc 
				$Limit";
	$res	= @mysql_query($sql,$conCNT);
	while($lin = @mysql_fetch_array($res)){
		echo "<reg>";
		echo	"<IdTipo>$lin[IdTipoHelpDesk]</IdTipo>";
		echo	"<IdSubTipo>$lin[IdSubTipoHelpDesk]</IdSubTipo>";
		echo 	"<DescricaoTipo>$lin[DescricaoTipoHelpDesk]</DescricaoTipo>";
		echo 	"<DescricaoSubTipo>$lin[DescricaoSubTipoHelpDesk]</DescricaoSubTipo>";
		echo 	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo "</reg>";
	}
	
	echo "</db>";
?>
