<?
	$localModulo		=	1;
	$localOperacao		=	46;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localDataInicio			= url_string_xsl($_GET['DataInicio'],'');
	$localDescricao				= url_string_xsl($_GET['DescricaoProdutoTipoVigencia'],'');
	$localDataTermino			= url_string_xsl($_GET['DataTermino'],'');
	$localValor					= url_string_xsl($_GET['Valor'],'');
	$localIdProduto				= $_GET['IdProduto'];
	$Limit						= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DataVigenciaInicioTemp2";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){	$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
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
		<script type = 'text/javascript' src = 'js/produto_vigencia.js'></script>
	</head>
	<body  onLoad="ativaNome('Produto/Vigência')">
		<? include('filtro_produto_vigencia.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar' />
					<td>
						<a href="javascript:filtro_ordenar('DataInicio','text')">Data Início <?=compara($localOrdem,"DataInicio",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataTermino','text')">Data Término <?=compara($localOrdem,"DataTermino",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoProdutoTipoVigencia','text')">Nome Tipo Vigência Produto <?=compara($localOrdem,"DescricaoProdutoTipoVigencia",$ImgOrdernarASC,'')?></a>
					</td>
					<td class="valor">
						<a href="javascript:filtro_ordenar('Valor','number')">Valor (<?=getParametroSistema(5,1);?>) <?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdProduto"/>_<xsl:value-of select="DataInicio2"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:number value="position()" format="1" />
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_produto_vigencia.php?IdProduto=<xsl:value-of select="IdProduto"/>&amp;DataInicio=<xsl:value-of select="DataInicioTemp"/></xsl:attribute>
							<xsl:value-of select="DataInicioTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_produto_vigencia.php?IdProduto=<xsl:value-of select="IdProduto"/>&amp;DataInicio=<xsl:value-of select="DataInicioTemp"/></xsl:attribute>
							<xsl:value-of select="DataTerminoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_produto_vigencia.php?IdProduto=<xsl:value-of select="IdProduto"/>&amp;DataInicio=<xsl:value-of select="DataInicioTemp"/></xsl:attribute>
							<xsl:value-of select="DescricaoProdutoTipoVigencia"/>
						</xsl:element>
					</td>
					<td class="valor">
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_produto_vigencia.php?IdProduto=<xsl:value-of select="IdProduto"/>&amp;DataInicio=<xsl:value-of select="DataInicioTemp"/></xsl:attribute>
							<xsl:value-of select='format-number(Valor,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdProduto"/>,'<xsl:value-of select="DataInicio2"/>')</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='4'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class="valor"><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")'/></td>
					<td />
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
	
	addParmUrl("marProduto","IdProduto",'<?=$localIdProduto?>');
	addParmUrl("marProdutoVigencia","IdProduto",'<?=$localIdProduto?>');
	addParmUrl("marProdutoVigenciaNovo","IdProduto",'<?=$localIdProduto?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
