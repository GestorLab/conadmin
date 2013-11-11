<?
	$localModulo		=	1;
	$localOperacao		=	56;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$IdLoja							= $_SESSION['IdLoja'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_numero_nf				= $_POST['filtro_numero_nf'];
	$filtro_cnpj					= $_POST['filtro_cnpj'];
	$filtro_serie_nf				= $_POST['filtro_serie_nf'];
	$filtro_tipo_nf					= $_POST['filtro_tipo_nf'];
	$filtro_data_nf					= $_POST['filtro_data_nf'];
	$filtro_campo					= $_POST['filtro_campo'];
	$filtro_valor					= $_POST['filtro_valor'];
	$filtro_nf						= $_GET['IdMovimentacaoProduto'];
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
	
	if($filtro_nf!=''){
		$filtro_url .= "&IdMovimentacaoProduto=$filtro_nf";
		$filtro_sql .=	" and MovimentacaoProduto.IdMovimentacaoProduto = '$filtro_nf'";
	}
	
	if($filtro_numero_nf!=''){
		$filtro_url .= "&NumeroNF=$filtro_numero_nf";
		$filtro_sql .=	" and MovimentacaoProduto.NumeroNF = '$filtro_numero_nf'";
	}
	
	if($filtro_cnpj!=''){
		$filtro_url .= "&CPF_CNPJ=$filtro_cnpj";
		$filtro_sql .=	" and Pessoa.CPF_CNPJ = '$filtro_cnpj'";
	}
	
	if($filtro_serie_nf!=''){
		$filtro_url .= "&SerieNF=$filtro_serie_nf";
		$filtro_sql .=	" and MovimentacaoProduto.SerieNF = '$filtro_serie_nf'";
	}
	
	if($filtro_tipo_nf!=''){
		$filtro_url .= "&TipoMovimentacao=$filtro_tipo_nf";
		$filtro_sql .=	" and MovimentacaoProduto.TipoMovimentacao = '$filtro_tipo_nf'";
	}
	
	if($filtro_data_nf!=''){
		$filtro_url .= "&DataNF=$filtro_data_nf";
		$filtro_data_nf	=	dataConv($filtro_data_nf,'d/m/Y','Y-m-d');
		$filtro_sql .=	" and MovimentacaoProduto.DataNF = '$filtro_data_nf'";
	}
		
	if($filtro_valor!=""){
		$filtro_url .= "&Valor=".$filtro_valor;
	}
				
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		switch($filtro_campo){
			case 'DescricaoNatureza':
				$filtro_sql .= " and CFOP.NaturezaOperacao like '%$filtro_valor%'";
				break;
			case 'DescricaoEstoque':
				$filtro_sql .= " and Estoque.DescricaoEstoque like '%$filtro_valor%'";
				break;
			case 'RazaoSocial':
				$filtro_sql .=	" and (Pessoa.RazaoSocial like '%$filtro_valor%')";
				break;
			case 'DescricaoReduzidaProduto':
				$filtro_sql .=	" and Produto.DescricaoReduzidaProduto like '%$filtro_valor%'";
				break;
			case 'ValorNF':
				$filtro_valor	=	str_replace(".", "", $filtro_valor);	
				$filtro_valor	= 	str_replace(",", ".", $filtro_valor);
				$filtro_sql .=	" and MovimentacaoProduto.ValorNF = '$filtro_valor'";
				break;
		}
		
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_nota_fiscal_entrada_xsl.php$filtro_url\"?>";
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
					MovimentacaoProduto.IdMovimentacaoProduto, 
					NumeroNF,
					SerieNF,
					Pessoa.CPF_CNPJ,
					TipoMovimentacao,
					ValorNF,
					DataNF,
					substr(Pessoa.RazaoSocial,1,30) RazaoSocial
				from 
					Loja,
					MovimentacaoProduto,
					Fornecedor,
					Pessoa,
					CFOP,
					Estoque 
				where
					Loja.IdLoja = $IdLoja and
					Loja.IdLoja = MovimentacaoProduto.IdLoja and
					Loja.IdLoja = Fornecedor.IdLoja and
					Loja.IdLoja = Estoque.IdLoja and
					MovimentacaoProduto.IdFornecedor = Fornecedor.IdFornecedor and
					Fornecedor.IdFornecedor = Pessoa.IdPessoa and
					MovimentacaoProduto.CFOP = CFOP.CFOP and
					MovimentacaoProduto.IdEstoque = Estoque.IdEstoque
					$filtro_sql
				order by
					MovimentacaoProduto.IdMovimentacaoProduto desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin[DataNFTemp] 	= dataConv($lin[DataNF],"Y-m-d","d/m/Y");
		$lin[DataNF] 		= dataConv($lin[DataNF],"Y-m-d","Ymd");
		
		$sql2 = "select ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=65 and IdParametroSistema=$lin[TipoMovimentacao]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		echo "<reg>";			
		echo 	"<IdMovimentacaoProduto>$lin[IdMovimentacaoProduto]</IdMovimentacaoProduto>";	
		echo 	"<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";	
		echo 	"<SerieNF><![CDATA[$lin[SerieNF]]]></SerieNF>";	
		echo 	"<DataNF><![CDATA[$lin[DataNF]]]></DataNF>";	
		echo 	"<DataNFTemp><![CDATA[$lin[DataNFTemp]]]></DataNFTemp>";	
		echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";	
		echo 	"<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";	
		echo 	"<ValorNF><![CDATA[$lin[ValorNF]]]></ValorNF>";	
		echo 	"<TipoMovimentacao><![CDATA[$lin2[ValorParametroSistema]]]></TipoMovimentacao>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
