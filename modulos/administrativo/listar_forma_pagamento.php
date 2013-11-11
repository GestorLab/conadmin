<?
	$localModulo		=	1;
	$localOperacao		=	53;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$IdLoja							= $_SESSION['IdLoja'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_descricao				= $_POST['filtro_descricao'];
	$filtro_qtd_max_parcela			= $_POST['filtro_qtd_max_parcela'];
	$filtro_forma_pagamento			= $_GET['IdFormaPagamento'];
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
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_forma_pagamento!=''){
		$filtro_url .= "&IdFormaPagamento=$filtro_forma_pagamento";
		$filtro_sql .=	" and IdFormaPagamento = '$filtro_forma_pagamento'";
	}
		
	if($filtro_descricao!=''){
		$filtro_url .= "&DescricaoFormaPagamento=$filtro_descricao";
		$filtro_sql .=	" and DescricaoFormaPagamento like '%$filtro_descricao%'";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_forma_pagamento_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	} else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "select 
				IdFormaPagamento, 
				DescricaoFormaPagamento
			from 
				FormaPagamento 
			where
				IdLoja = $IdLoja
				$filtro_sql
			order by
				IdFormaPagamento desc
			$Limit";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<IdFormaPagamento>$lin[IdFormaPagamento]</IdFormaPagamento>";	
		echo 	"<DescricaoFormaPagamento><![CDATA[$lin[DescricaoFormaPagamento]]]></DescricaoFormaPagamento>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>