<?
	$localModulo		=	1;
	$localOperacao		=	96;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$IdLoja						= $_SESSION['IdLoja'];	
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_descricao			= $_POST['filtro_descricao'];
	$filtro_produto				= $_POST['IdProduto'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_produto_foto		= $_GET['IdProdutoFoto'];
	
	if($filtro_produto == ''&& $_GET['IdProduto']!=''){
		$filtro_produto		= $_GET['IdProduto'];
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
		$filtro_url .= "&DescricaoFoto=".$filtro_descricao;
		$filtro_sql .= " and (DescricaoFoto like '%$filtro_descricao%')";
	}

	if($filtro_produto !=""){
		$filtro_url	.= "&IdProduto=".$filtro_produto;
		$filtro_url	.= "&IdProdutoTemp=".$_GET['IdProduto'];
		$filtro_sql	.= " and ProdutoFoto.IdProduto=".$filtro_produto;
	}
	
	if($filtro_produto_foto!=""){
		$filtro_url .= "&IdProdutoFoto=".$filtro_produto_foto;
		$filtro_sql .= " and IdProdutoFoto = '$filtro_produto_foto'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert','URL');
	}

	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_produto_foto_xsl.php$filtro_url\"?>";
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
	if($_GET['IdProduto'] ==""){
		$sqlTemp	=	"select distinct
							IdProduto
						from 
							ProdutoFoto
						where   
							ProdutoFoto.IdLoja = $IdLoja 
						order by
							IdProdutoFoto desc
						$Limit;";
		$resTemp	=	mysql_query($sqlTemp,$con);
		while($linTemp	=	mysql_fetch_array($resTemp)){
			$sql	=	"select
							IdProduto,
							IdProdutoFoto,
							DescricaoFoto
						from 
							ProdutoFoto
						where   
							ProdutoFoto.IdLoja = $IdLoja and
							ProdutoFoto.IdProduto = $linTemp[IdProduto]	
						order by
							IdProdutoFoto desc
						$Limit;";
			$res	=	mysql_query($sql,$con);
			$lin	=	mysql_fetch_array($res);
			
			$sqlQtd	=	"select
								count(IdProdutoFoto) Qtd
							from 
								ProdutoFoto
							where   
								ProdutoFoto.IdLoja = $IdLoja and
								ProdutoFoto.IdProduto = $linTemp[IdProduto]
							order by
								IdProdutoFoto desc
							$Limit;";
			$resQtd	=	mysql_query($sqlQtd,$con);
			$linQtd	=	mysql_fetch_array($resQtd);
			
			echo "<reg>";			
			echo 	"<IdProduto>$lin[IdProduto]</IdProduto>";	
			echo 	"<IdProdutoFoto><![CDATA[$lin[IdProdutoFoto]]]></IdProdutoFoto>";
			echo	"<DescricaoFoto><![CDATA[$lin[DescricaoFoto]]]></DescricaoFoto>";
			echo 	"<Qtd>$linQtd[Qtd]</Qtd>";	
			echo "</reg>";	
		}
	}else{
		$sql	=	"select
							IdProduto,
							IdProdutoFoto,
							DescricaoFoto
						from 
							ProdutoFoto
						where   
							ProdutoFoto.IdLoja = $IdLoja 
							$filtro_sql	
						order by
							IdProdutoFoto desc
						$Limit;";
		$res	=	mysql_query($sql,$con);
		while($lin	=	mysql_fetch_array($res)){
			echo "<reg>";			
			echo 	"<IdProduto>$lin[IdProduto]</IdProduto>";	
			echo 	"<IdProdutoFoto><![CDATA[$lin[IdProdutoFoto]]]></IdProdutoFoto>";
			echo	"<DescricaoFoto><![CDATA[$lin[DescricaoFoto]]]></DescricaoFoto>";
			echo "</reg>";	
		}
	}
	
	echo "</db>";
?>
