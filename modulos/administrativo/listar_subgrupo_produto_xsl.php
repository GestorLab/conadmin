<?
	$localModulo		=	1;
	$localOperacao		=	49;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localDescricaoGrupoProduto		= url_string_xsl($_GET['DescricaoGrupoProduto'],'');
	$localDescricaoSubGrupoProduto	= url_string_xsl($_GET['DescricaoSubGrupoProduto'],'');
	$localIdGrupoProduto			= $_GET['IdGrupoProduto'];
	$localIdSubGrupoProduto			= $_GET['IdSubGrupoProduto'];
	$Limit							= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DescricaoGrupoProduto";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
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
		<script type = 'text/javascript' src = 'js/subgrupo_produto.js'></script>
	</head>
	<body  onLoad="ativaNome('SubGrupo Produto')">
		<? include('filtro_subgrupo_produto.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar' />
					<td>
						<a href="javascript:filtro_ordenar('DescricaoGrupoProduto')">Nome Grupo Produto <?=compara($localOrdem,"DescricaoGrupoProduto",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoSubGrupoProduto')">Nome SubGrupo Produto <?=compara($localOrdem,"DescricaoSubGrupoProduto",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />
				
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdGrupoProduto"/>_<xsl:value-of select="IdSubGrupoProduto"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:number value="position()" format="1" />
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_subgrupo_produto.php?IdGrupoProduto=<xsl:value-of select="IdGrupoProduto"/>&amp;IdSubGrupoProduto=<xsl:value-of select="IdSubGrupoProduto"/></xsl:attribute>
							<xsl:value-of select="DescricaoGrupoProduto"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_subgrupo_produto.php?IdGrupoProduto=<xsl:value-of select="IdGrupoProduto"/>&amp;IdSubGrupoProduto=<xsl:value-of select="IdSubGrupoProduto"/></xsl:attribute>
							<xsl:value-of select="DescricaoSubGrupoProduto"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdGrupoProduto"/>,<xsl:value-of select="IdSubGrupoProduto"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='4' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
				</tr>
			</table>
		</div>
		<table>
			<tr>
				<td class='find' />
				<td><h1 id='helpText' name='helpText' /></td>
			</tr>
		</table>
	</body>	
</html>
<script>
	addParmUrl("marSubGrupoProduto","IdSubGrupoProduto",'<?=$localIdSubGrupoProduto?>');
	addParmUrl("marSubGrupoProduto","IdGrupoProduto",'<?=$localIdProduto?>');

	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
