<?
	$localModulo		=	1;
	$localOperacao		=	56;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localNumeroNF				= url_string_xsl($_GET['NumeroNF'],'');
	$localCPF_CNPJ				= url_string_xsl($_GET['CPF_CNPJ'],'');
	$localSerieNF				= url_string_xsl($_GET['SerieNF'],'');
	$localTipoMovimentacao		= url_string_xsl($_GET['TipoMovimentacao'],'');
	$localDataNF				= url_string_xsl($_GET['DataNF'],'');
	$localCampo					= url_string_xsl($_GET['Campo'],'');
	$localValor					= url_string_xsl($_GET['Valor'],'');
	$localIdMovimentacaoProduto	= $_GET['IdMovimentacaoProduto'];
	$Limit						= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DataNF";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/nota_fiscal_entrada.js'></script>
	</head>
	<body  onLoad="ativaNome('Nota Fiscal Entrada')">
		<? include('filtro_nota_fiscal_entrada.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtro_ordenar('NumeroNF','number')">Número NF <?=compara($localOrdem,"NumeroNF",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('CPF_CNPJ')">CNPJ Fornecedor <?=compara($localOrdem,"CPF_CNPJ",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('RazaoSocial')">Nome Fornecedor <?=compara($localOrdem,"RazaoSocial",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('SerieNF')">Série NF <?=compara($localOrdem,"SerieNF",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('TipoMovimentacao')">Tipo NF <?=compara($localOrdem,"TipoMovimentacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataNF','number')">Data NF <?=compara($localOrdem,"DataNF",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'> 
						<a href="javascript:filtro_ordenar('ValorNF')">Valor NF (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorNF",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdMovimentacaoProduto"/></xsl:attribute>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nota_fiscal_entrada.php?IdMovimentacaoProduto=<xsl:value-of select="IdMovimentacaoProduto"/></xsl:attribute>
							<xsl:value-of select="NumeroNF"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nota_fiscal_entrada.php?IdMovimentacaoProduto=<xsl:value-of select="IdMovimentacaoProduto"/></xsl:attribute>
							<xsl:value-of select="CPF_CNPJ"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nota_fiscal_entrada.php?IdMovimentacaoProduto=<xsl:value-of select="IdMovimentacaoProduto"/></xsl:attribute>
							<xsl:value-of select="RazaoSocial"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nota_fiscal_entrada.php?IdMovimentacaoProduto=<xsl:value-of select="IdMovimentacaoProduto"/></xsl:attribute>
							<xsl:value-of select="SerieNF"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nota_fiscal_entrada.php?IdMovimentacaoProduto=<xsl:value-of select="IdMovimentacaoProduto"/></xsl:attribute>
							<xsl:value-of select="TipoMovimentacao"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nota_fiscal_entrada.php?IdMovimentacaoProduto=<xsl:value-of select="IdMovimentacaoProduto"/></xsl:attribute>
							<xsl:value-of select="DataNFTemp"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nota_fiscal_entrada.php?IdMovimentacaoProduto=<xsl:value-of select="IdMovimentacaoProduto"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorNF,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdMovimentacaoProduto"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorNF),"0,00","euro")' /></td>
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
	addParmUrl("marNotaFiscalEntrada","IdMovimentacaoProduto",'<?=$localIdMovimentacaoProduto?>');

	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
