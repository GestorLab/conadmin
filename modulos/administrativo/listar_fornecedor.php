<?
	$localModulo		=	1;
	$localOperacao		=	51;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= $_POST['filtro_nome'];
	$filtro_fornecedor				= $_GET['IdFornecedor'];
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
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_fornecedor!=''){
		$filtro_url .= "&IdFornecedor=$filtro_fornecedor";
		$filtro_sql .=	" and (Fornecedor.IdFornecedor = '$filtro_fornecedor')";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_fornecedor_xsl.php$filtro_url\"?>";
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
					Fornecedor.IdLoja,
					Fornecedor.IdFornecedor, 
					if(Pessoa.RazaoSocial != \"\",substr(Pessoa.RazaoSocial, 1, 35),Pessoa.Nome) NomeFornecedor
				from 
					Fornecedor,
					Pessoa 
				where
					Fornecedor.IdLoja = $local_IdLoja and
					Fornecedor.IdFornecedor = Pessoa.IdPessoa
					$filtro_sql
				order by
					Fornecedor.IdFornecedor desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";		
		echo 	"<IdPessoa>$lin[IdFornecedor]</IdPessoa>";	
		echo 	"<Nome><![CDATA[$lin[NomeFornecedor]]]></Nome>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
