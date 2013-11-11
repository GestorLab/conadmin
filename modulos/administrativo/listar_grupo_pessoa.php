<?
	$localModulo		=	1;
	$localOperacao		=	12;
	$localSuboperacao	=	"R";
	
	$localTituloOperacao	= "Grupo Pessoa";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja			= $_SESSION["IdLoja"];
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_descricao		= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_grupo_pessoa	= $_GET['IdGrupoPessoa'];
	$filtro_limit			= $_POST['filtro_limit'];
	
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
	
	if($filtro_descricao!=""){
		$filtro_url .= "&DescricaoGrupoPessoa=".$filtro_descricao;
		$filtro_sql .= " and (DescricaoGrupoPessoa like '%$filtro_descricao%')";
	}
		
	if($filtro_grupo_pessoa!=""){
		$filtro_url .= "&IdGrupoPessoa=".$filtro_grupo_pessoa;
		$filtro_sql .= " and (IdGrupoPessoa = '$filtro_grupo_pessoa')";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_grupo_pessoa_xsl.php$filtro_url\"?>";
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
					IdGrupoPessoa, 
					DescricaoGrupoPessoa
				from 
					GrupoPessoa
				where
					IdLoja = $local_IdLoja
					$filtro_sql
				order by
					IdGrupoPessoa desc
				$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdGrupoPessoa>$lin[IdGrupoPessoa]</IdGrupoPessoa>";	
		echo 	"<DescricaoGrupoPessoa><![CDATA[$lin[DescricaoGrupoPessoa]]]></DescricaoGrupoPessoa>";		
		echo "</reg>";	
	}
	
	echo "</db>";
?>
