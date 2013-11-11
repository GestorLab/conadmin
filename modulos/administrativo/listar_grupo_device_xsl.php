<?
	$localModulo		=	1;
	$localOperacao		=	206;
	$localSuboperacao	=	"R";
		
	$localTituloOperacao	= "Grupo Device";
	$localRelatorio			= "block";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
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
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_device.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(1001)?>')">
		<? include('filtro_grupo_device.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' id='tableListar'>
				<tr class='tableListarTitle'>
					<xsl:if test="db/img/id != ''">
						<xsl:element name="input">
						<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="id">tipoOrdenacao</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select='db/img/id'/></xsl:attribute>
						</xsl:element>
					</xsl:if>
					<td class='sequencial'>
						<a class="ordenar" id="IdGrupoDevice" href="#">Id</a>
						<xsl:if test="db/img/tipo = 'IdGrupoDevice'">
							<xsl:element name="img">
								<xsl:attribute name="src"><xsl:value-of select='db/img/src'/></xsl:attribute>
								<xsl:attribute name="alt">Ordenado por</xsl:attribute>
							</xsl:element>
							<!--<img id="<xsl:value-of select=db/img/id/>'" src="'<xsl:value-of select=db/img/src/>'" alt='Ordenado por' />-->
						</xsl:if>
					</td>
					<td>
						<a class="ordenar" id="DescricaoGrupoDevice" href="#">Nome Grupo Device</a>
						<xsl:if test="db/img/tipo = 'DescricaoGrupoDevice'">
							<xsl:element name="img">
								<xsl:attribute name="src"><xsl:value-of select='db/img/src'/></xsl:attribute>
								<xsl:attribute name="alt">Ordenado por</xsl:attribute>
							</xsl:element>
							<!--<img id="<xsl:value-of select=db/img/id/>'" src="'<xsl:value-of select=db/img/src/>'" alt='Ordenado por' />-->
						</xsl:if>
					</td>
					<td>
						<a class="ordenar" id="DisponivelContrato" href="#">Disponivel Contrato</a>
						<xsl:if test="db/img/tipo = 'DisponivelContrato'">
							<xsl:element name="img">
								<xsl:attribute name="src"><xsl:value-of select='db/img/src'/></xsl:attribute>
								<xsl:attribute name="alt">Ordenado por</xsl:attribute>
							</xsl:element>
							<!--<img id="<xsl:value-of select=db/img/id/>'" src="'<xsl:value-of select=db/img/src/>'" alt='Ordenado por' />-->
						</xsl:if>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdGrupoDevice"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_grupo_device.php?<xsl:value-of select="dadosUrl"/></xsl:attribute>
							<xsl:value-of select="IdGrupoDevice"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_grupo_device.php?<xsl:value-of select="dadosUrl"/></xsl:attribute>
							<xsl:value-of select="DescricaoGrupoDevice"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_grupo_device.php?<xsl:value-of select="dadosUrl"/></xsl:attribute>
							<xsl:value-of select="DisponivelContrato"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="class">excluir</xsl:attribute>
							<xsl:attribute name="id">excluir_<xsl:value-of select="IdGrupoDevice"/></xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='4' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
				</tr>
			</table>
			<table>
				<tr>
					<td class='find'></td>
					<td><h1 id='helpText' name='helpText'></h1></td>
				</tr>
		 	</table>
		</div>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
<script>
	tableMultColor('tableListar',document.filtro.corRegRand.value);
</script>
</xsl:template>
</xsl:stylesheet>
