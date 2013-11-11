<?
	$localModulo		=	1;
	$localOperacao		=	135;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja			= $_SESSION["IdLoja"]; 
	$local_Login			= $_SESSION['Login'];
	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	
	$filtro_IdFormaAvisoCobranca				= $_POST['IdFormaAvisoCobranca'];	
	$filtro_descricao_forma_aviso_cobranca		= $_POST['filtro_descricao_forma_aviso_cobranca'];
	$filtro_grupo_usuario_monitor				= $_POST['filtro_grupo_usuario_monitor'];		
	$filtro_limit								= $_POST['filtro_limit'];
	
	if($filtro_IdFormaAvisoCobranca == ''&& $_GET['IdFormaAvisoCobranca']!=''){
		$filtro_IdFormaAvisoCobranca	= $_GET['IdFormaAvisoCobranca'];
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
		
	if($filtro_descricao_forma_aviso_cobranca!=""){
		$filtro_url .= "&DescricaoFormaAvisoCobranca=".$filtro_descricao_forma_aviso_cobranca;
		$filtro_sql .= " and (DescricaoFormaAvisoCobranca like '%$filtro_descricao_forma_aviso_cobranca%')";
	}

	if($filtro_grupo_usuario_monitor!=""){
		$filtro_url	.= "&IdGrupoUsuarioMonitor=".$filtro_grupo_usuario_monitor;
		$filtro_sql	.= " and FormaAvisoCobranca.IdGrupoUsuarioMonitor =".$filtro_grupo_usuario_monitor;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
			
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_forma_aviso_cobranca_xsl.php$filtro_url\"?>";
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
					FormaAvisoCobranca.IdFormaAvisoCobranca, 
					FormaAvisoCobranca.DescricaoFormaAvisoCobranca,
					GrupoUsuario.DescricaoGrupoUsuario
				from 
					FormaAvisoCobranca
						LEFT JOIN GrupoUsuario ON (FormaAvisoCobranca.IdGrupoUsuarioMonitor = GrupoUsuario.IdGrupoUsuario)						
				where
					FormaAvisoCobranca.IdLoja = $local_IdLoja 
					$filtro_sql 
				order by
					FormaAvisoCobranca.IdFormaAvisoCobranca desc
				$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){		
		echo "<reg>";			
		echo 	"<IdFormaAvisoCobranca>$lin[IdFormaAvisoCobranca]</IdFormaAvisoCobranca>";
		echo 	"<DescricaoFormaAvisoCobranca><![CDATA[$lin[DescricaoFormaAvisoCobranca]]]></DescricaoFormaAvisoCobranca>";
		echo	"<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
