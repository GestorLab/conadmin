<?
	$localModulo		=	1;
	$localOperacao		=	30;
	$localSuboperacao	=	"R";
		
	$localTituloOperacao	= "Local de Cobrança";
	$localRelatorio			= "block";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localAbreviacao			= url_string_xsl($_GET['AbreviacaoNomeLocalCobranca'],"URL", false);
	$localDescricao				= url_string_xsl($_GET['DescricaoLocalCobranca'],"URL", false);
	$localIdLocalCobranca		= $_GET['IdLocalCobranca'];
	$localIdTipoLocalCobranca	= $_GET['IdTipoLocalCobranca'];
	$localIdStatus				= $_GET['IdStatus'];
	$Limit						= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DescricaoLocalCobranca";		}
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
		<script type = 'text/javascript' src = 'js/local_cobranca.js'></script>
	</head>
	<body  onLoad="ativaNome('Local de Cobrança')">
		<? include('filtro_local_cobranca.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdLocalCobranca','number')">Id<?=compara($localOrdem,"IdLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoLocalCobranca','text')">Nome Local de Cobrança <?=compara($localOrdem,"DescricaoLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td width='100px'>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')">Abreviação <?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('TipoLocalCobranca','text')">Tipo<?=compara($localOrdem,"TipoLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoArquivoRetornoTipo','text')">Retorno <?=compara($localOrdem,"DescricaoArquivoRetornoTipo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoArquivoRemessaTipo','text')">Remessa <?=compara($localOrdem,"DescricaoArquivoRemessaTipo",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor' width='110px'>
						<a href="javascript:filtro_ordenar('ValorDespesaLocalCobranca','number')">Valor Desp. (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorDespesaLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status','text')">Status <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
							<xsl:value-of select="IdLocalCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
							<xsl:value-of select="DescricaoLocalCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
							<xsl:value-of select="TipoLocalCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
							<xsl:value-of select="DescricaoArquivoRetornoTipo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
							<xsl:value-of select="DescricaoArquivoRemessaTipo"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorDespesaLocalCobranca,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdLocalCobranca"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorDespesaLocalCobranca),"0,00","euro")' /></td>
					<td colspan='2' />
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
	addParmUrl("marLocalCobrancaParametro","IdLocalCobranca",'<?=$localIdLocalCobranca?>');
	addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca",'<?=$localIdLocalCobranca?>');
	addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca",'<?=$localIdLocalCobranca?>');
	addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca",'<?=$localIdLocalCobranca?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>