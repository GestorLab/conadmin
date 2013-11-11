<?
	$localModulo		=	1;
	$localOperacao		=	155;
	$localSuboperacao	=	"R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localDescricao			= url_string_xsl($_GET['Descricao'],'url',false);
	$localIdTipoConteudo	= $_GET['IdTipoConteudo'];
	$localIdTipoMensagem	= $_GET['IdTipoMensagem'];
	$localIdStatus			= $_GET['IdStatus'];
	$local_IdMalaDireta		= $_GET['IdMalaDireta'];
	$Limit					= $_GET['Limit'];
	
	if($localOrdem == ''){
		$localOrdem = "DescricaoMalaDireta";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
	}
	
	LimitVisualizacao('xsl');	
	
	$ImgOrdernarASC = "<img src='../../img/estrutura_sistema/seta_$localOrdemDirecao.gif' alt='Ordenado por' />";
	
	header("content-type: text/xsl");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:decimal-format name="euro" decimal-separator="," grouping-separator="."/>
<xsl:template match="/">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/impress.css' media='print' />
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/mala_direta.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(34)?>')">
		<? include('filtro_mala_direta.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdMalaDireta','number')"><?=dicionario(141)?> <?=compara($localOrdem,"IdMalaDireta",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoMalaDireta','text')"><?=dicionario(125)?> <?=compara($localOrdem,"DescricaoMalaDireta",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdTipoConteudo','number')"><?=dicionario(810)?> <?=compara($localOrdem,"IdTipoConteudo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdTipoMensagem','number')"><?=dicionario(718)?> <?=compara($localOrdem,"IdTipoMensagem",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdStatus','number')"><?=dicionario(140)?> <?=compara($localOrdem,"IdStatus",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdMalaDireta"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_mala_direta.php?IdMalaDireta=<xsl:value-of select="IdMalaDireta"/></xsl:attribute>
							<xsl:value-of select="IdMalaDireta"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_mala_direta.php?IdMalaDireta=<xsl:value-of select="IdMalaDireta"/></xsl:attribute>
							<xsl:value-of select="DescricaoMalaDireta"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_mala_direta.php?IdMalaDireta=<xsl:value-of select="IdMalaDireta"/></xsl:attribute>
							<xsl:value-of select="TipoConteudo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_mala_direta.php?IdMalaDireta=<xsl:value-of select="IdMalaDireta"/></xsl:attribute>
							<xsl:value-of select="IdTipoMensagem"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_mala_direta.php?IdMalaDireta=<xsl:value-of select="IdMalaDireta"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:choose>
								<xsl:when test="IdStatus != 1">
									<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
								</xsl:when>
								<xsl:otherwise>
									<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
									<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdMalaDireta"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
								</xsl:otherwise>
							</xsl:choose>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='7' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
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
<script type="text/javascript">
<!--
	verificaAcao();
	tableMultColor('tableListar', document.filtro.corRegRand.value);
	-->
</script>
</xsl:template>
</xsl:stylesheet>