<?
	$localModulo		=	1;
	$localOperacao		=	82;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$IdLoja						= $_SESSION['IdLoja'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_IdTipoOrdemServico	= $_POST['IdTipoOrdemServico'];
	$filtro_descricao			= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_limit				= $_POST['filtro_limit'];
	
	if($filtro_IdTipoOrdemServico == ''&& $_GET['IdTipoOrdemServico']!=''){
		$filtro_IdTipoOrdemServico	= $_GET['IdTipoOrdemServico'];
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
		
	if($filtro_descricao!=""){
		$filtro_url .= "&DescricaoTipoOrdemServico=".$filtro_descricao;
		$filtro_sql .= " and (DescricaoTipoOrdemServico like '%$filtro_descricao%')";
	}
	if($filtro_IdTipoOrdemServico!=""){
		$filtro_url	.= "&IdTipoOrdemServico=".$filtro_IdTipoOrdemServico;
		$filtro_sql	.= " and IdTipoOrdemServico =".$filtro_IdTipoOrdemServico;
	}	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_tipo_ordem_servico_xsl.php$filtro_url\"?>";
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
					IdTipoOrdemServico, 
					DescricaoTipoOrdemServico, 
					Cor
				from 
					TipoOrdemServico
				where
					IdLoja = $IdLoja 
					$filtro_sql 
				order by
					IdTipoOrdemServico desc 
				$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdTipoOrdemServico>$lin[IdTipoOrdemServico]</IdTipoOrdemServico>";
		echo 	"<DescricaoTipoOrdemServico><![CDATA[$lin[DescricaoTipoOrdemServico]]]></DescricaoTipoOrdemServico>";
		echo	"<Cor><![CDATA[$lin[Cor]]]></Cor>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
