<?
	$localModulo		=	1;
	$localOperacao		=	19;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_descricao_grupo_servico	= url_string_xsl($_POST['filtro_descricao_grupo_servico'],'url',false);
	$filtro_grupo_servico			= $_GET['IdServicoGrupo'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_grupo_servico!=''){
		$filtro_url .= "&IdServicoGrupo=$filtro_grupo_servico";
		$filtro_sql .=	"and IdServicoGrupo = '$filtro_grupo_servico'";
	}
		
	if($filtro_descricao_grupo_servico!=''){
		$filtro_url .= "&DescricaoServicoGrupo=$filtro_descricao_grupo_servico";
		$filtro_sql .=	"and DescricaoServicoGrupo like '%$filtro_descricao_grupo_servico%'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
	
	if($filtro_sql != "")
		$filtro_sql = "and IdServicoGrupo!=''".$filtro_sql;


	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_servico_grupo_xsl.php$filtro_url\"?>";
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
					IdLoja,
					IdServicoGrupo, 
					DescricaoServicoGrupo 
				from 
					ServicoGrupo
				where 
					IdLoja='$local_IdLoja' 
					$filtro_sql 
				order by
					IdServicoGrupo desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";		
		echo 	"<IdServicoGrupo>$lin[IdServicoGrupo]</IdServicoGrupo>";	
		echo 	"<DescricaoServicoGrupo><![CDATA[$lin[DescricaoServicoGrupo]]]></DescricaoServicoGrupo>";	
		echo "</reg>";	
	}	
	echo "</db>";
?>
