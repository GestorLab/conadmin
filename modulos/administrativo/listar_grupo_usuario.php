<?
	$localModulo		=	1;
	$localOperacao		=	32;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja						=$_SESSION["IdLoja"];
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_descricao_GrupoUsuario		= url_string_xsl($_POST['filtro_descricao_GrupoUsuario'],'url',false);
	$filtro_limit						= $_POST['filtro_limit'];
	$filtro_OrdemServico				= $_POST['filtro_ordem_servico'];
	$filtro_IdGrupoUsuario				= $_GET['IdGrupoUsuario'];	

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
		
	if($filtro_descricao_GrupoUsuario!=''){
		$filtro_url .= "&DescricaoGrupoUsuario=$filtro_descricao_GrupoUsuario";
		$filtro_sql .=	" and DescricaoGrupoUsuario like '%$filtro_descricao_GrupoUsuario%'";
	}	
	
	if($filtro_IdGrupoUsuario!=''){
		$filtro_url .= "&IdGrupoUsuario	=$filtro_IdGrupoUsuario";
		$filtro_sql .= " and IdGrupoUsuario = '$filtro_IdGrupoUsuario'";
	}	
	
	if($filtro_OrdemServico!=''){
		$filtro_url .= "&OrdemServico=$filtro_OrdemServico";
		$filtro_sql .= " and OrdemServico = '$filtro_OrdemServico'";
	}	
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_grupo_usuario_xsl.php$filtro_url\"?>";
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
					IdGrupoUsuario, 
					DescricaoGrupoUsuario,
					OrdemServico 
				from 
					GrupoUsuario 
				where
					IdLoja = $local_IdLoja 
					$filtro_sql
				order by
					IdGrupoUsuario desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		
		$lin[OrdemServico] = getParametroSistema(141,$lin[OrdemServico]);
		
		echo "<reg>";	
		echo 	"<IdGrupoUsuario>$lin[IdGrupoUsuario]</IdGrupoUsuario>";	
		echo 	"<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";	
		echo 	"<OrdemServico><![CDATA[$lin[OrdemServico]]]></OrdemServico>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
