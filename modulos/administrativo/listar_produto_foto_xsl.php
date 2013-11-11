<?
	$localModulo		=	1;
	$localOperacao		=	96;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localDescricaoFoto				= url_string_xsl($_GET['DescricaoFoto'],'');
	$localIdProduto					= $_GET['IdProduto'];
	$IdProdutoTemp					= $_GET['IdProdutoTemp'];
	$Limit							= $_GET['Limit'];

	if($IdProdutoTemp == ""){
		$campo = 1;
	}
	
	if($campo == 1){
		if($localOrdem == ''){					$localOrdem = "IdProduto";		}
		if($localOrdemDirecao == ''){			$localOrdemDirecao = 'ascending';	}	
	}else{
		if($localOrdem == ''){					$localOrdem = "IdProdutoFoto";		}
		if($localOrdemDirecao == ''){			$localOrdemDirecao = 'descending';	}	
	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
	
	LimitVisualizacao('xsl');
	
	$ImgOrdernarASC = "<img src='../../img/estrutura_sistema/seta_$localOrdemDirecao.gif' alt='Ordenado por' />";
	header ("content-type: text/xsl");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:decimal-format name="euro" decimal-separator="," grouping-separator="."/>
<xsl:template match="/">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media='print' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/produto_foto.js'></script>
	</head>
	<body  onLoad="ativaNome('Produto/Foto')">
		<? include('filtro_produto_foto.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<? 
						if($campo == 1){
							echo "	<td class='id_listar' style='width: 80px'>
										<a href=\"javascript:filtro_ordenar('IdProduto','number')\">Id Produto".compara($localOrdem,"IdProduto",$ImgOrdernarASC,'')."</a>
									</td>
									<td class='id_listar' style='width: 70px'>
										<a href=\"javascript:filtro_ordenar('IdProdutoFoto','number')\">Qtd Fotos ".compara($localOrdem,"IdProdutoFoto",$ImgOrdernarASC,'')."</a>
									</td>
									<td>
										<a href=\"javascript:filtro_ordenar('DescricaoFoto','text')\">Descrição Ultima Foto ".compara($localOrdem,"DescricaoFoto",$ImgOrdernarASC,'')."</a>
									</td>
								";
									
						}else{
							echo"	<td class='id_listar' style='width: 60px'>
										<a href=\"javascript:filtro_ordenar('IdProdutoFoto','number')\">Id".compara($localOrdem,"IdProdutoFoto",$ImgOrdernarASC,'')."</a>
									</td>
									<td>
										<a href=\"javascript:filtro_ordenar('DescricaoFoto','text')\">Descrição Foto ".compara($localOrdem,"DescricaoFoto",$ImgOrdernarASC,'')."</a>
									</td>
									<td class='bt_lista' />
								";
						}
					?>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdProduto"/>_<xsl:value-of select="IdProdutoFoto"/></xsl:attribute>
					<?
						if($campo == 1){
							echo"
								<td class='sequencial'>
									<xsl:element name=\"a\">
										<xsl:attribute name=\"href\">cadastro_produto.php?IdProduto=<xsl:value-of select=\"IdProduto\"/></xsl:attribute>
										<xsl:value-of select=\"IdProduto\"/>
									</xsl:element>
								</td>
								<td class='sequencial'>
									<xsl:element name=\"a\">
										<xsl:attribute name=\"href\">listar_produto_foto.php?IdProduto=<xsl:value-of select=\"IdProduto\"/></xsl:attribute>
										<xsl:value-of select=\"Qtd\"/>
									</xsl:element>
								</td>
								<td>
									<xsl:element name=\"a\">
										<xsl:attribute name=\"href\">listar_produto_foto.php?IdProduto=<xsl:value-of select=\"IdProduto\"/></xsl:attribute>
										<xsl:value-of select=\"DescricaoFoto\"/>
									</xsl:element>
								</td>
							";
						}else{
							echo"
								<td class='sequencial'>
									<xsl:element name=\"a\">
										<xsl:attribute name=\"href\">cadastro_produto_foto.php?IdProduto=<xsl:value-of select=\"IdProduto\"/>&amp;IdProdutoFoto=<xsl:value-of select=\"IdProdutoFoto\"/></xsl:attribute>
										<xsl:value-of select=\"IdProdutoFoto\"/>
									</xsl:element>
								</td>
								<td>
									<xsl:element name=\"a\">
										<xsl:attribute name=\"href\">cadastro_produto_foto.php?IdProduto=<xsl:value-of select=\"IdProduto\"/>&amp;IdProdutoFoto=<xsl:value-of select=\"IdProdutoFoto\"/></xsl:attribute>
										<xsl:value-of select=\"DescricaoFoto\"/>
									</xsl:element>
								</td>
							";
						}
					
						if($campo != 1){
							echo"
								<td class='bt_lista'>
									<xsl:element name=\"img\">
										<xsl:attribute name=\"src\">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
										<xsl:attribute name=\"alt\">Excluir?</xsl:attribute>
										<xsl:attribute name=\"onClick\">excluir(<xsl:value-of select=\"IdProduto\"/>,<xsl:value-of select=\"IdProdutoFoto\"/>)</xsl:attribute>
									</xsl:element>
								</td>
							";
						}
					?>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='3' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
				</tr>
			</table>
		</div>
		<table>
			<tr>
				<td class='find' />
				<td><h1 id='helpText' name='helpText' /></td>
			</tr>
		</table>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
<script>
	
	addParmUrl("marProduto","IdProduto",'<?=$localIdProduto?>');
	addParmUrl("marProdutoFoto","IdProduto",'<?=$localIdProduto?>');
	addParmUrl("marProdutoFotoNovo","IdProduto",'<?=$localIdProduto?>');
	addParmUrl("marProdutoTabelaPreco","IdProduto",'<?=$localIdProduto?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
