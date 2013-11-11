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
	$filtro_id_status			= $_POST['filtro_id_status'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";
	
	if($filtro_idtipo == '' && $_GET['IdTipo'] != ''){
		$filtro_idtipo	= $_GET['IdTipo'];
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
	if($filtro_id_status!=''){
		$filtro_url .= "&IdStatus=$filtro_id_status";
		$filtro_sql .=	" and HelpDeskTipo.IdStatus = '$filtro_id_status'";
	}
	if($filtro_idtipo!=''){
		$filtro_sql .=	" and HelpDeskTipo.IdTipoHelpDesk = '$filtro_idtipo'";
	}
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_tipo_help_desk_xsl.php$filtro_url\"?>";
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
					ParametroSistema.ValorParametroSistema as Status
				from
					HelpDeskTipo,
					ParametroSistema
				where
					ParametroSistema.IdGrupoParametroSistema = 157 and
					ParametroSistema.IdParametroSistema = HelpDeskTipo.IdStatus $filtro_sql
				order by 
					HelpDeskTipo.IdTipoHelpDesk DESC 
				$Limit";
	$res	= @mysql_query($sql,$conCNT);
	while($lin = @mysql_fetch_array($res)){
		echo "<reg>";
		echo	"<IdTipo>$lin[IdTipoHelpDesk]</IdTipo>";
		echo 	"<DescricaoTipo>$lin[DescricaoTipoHelpDesk]</DescricaoTipo>";
		echo 	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo "</reg>";
	}
	
	echo "</db>";
?>
