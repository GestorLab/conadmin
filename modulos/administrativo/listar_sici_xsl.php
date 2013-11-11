<?
	$localModulo		= 1;
	$localOperacao		= 159;
	$localSuboperacao	= "R";
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localPeriodoApuracao		= url_string_xsl($_GET['PeriodoApuracao'],'');
	$localNumeroFistel			= url_string_xsl($_GET['NumeroFistel'],'');
	$localIdStatus				= $_GET['IdStatus'];
	$Limit						= $_GET['Limit'];
	
	if($localOrdem == ""){
		$localOrdem = "PeriodoApuracao";
	}
	
	if($localOrdemDirecao == ""){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ""){
		$localTipoDado = "number";
	}
	
	LimitVisualizacao("xsl");	
	
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
		<script type='text/javascript' src='./js/sici.js'></script>
	</head>
	<body onLoad="ativaNome('SICI')">
		<? include('filtro_sici.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar' style='width:80px;'>
						<a href="javascript:filtro_ordenar('PeriodoApuracao','number')">Apuração <?=compara($localOrdem,"PeriodoApuracao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Fistel','number')">FISTEL <?=compara($localOrdem,"Fistel",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('LoginCriacao','text')">Usuário Cadastro <?=compara($localOrdem,"LoginCriacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataCriacao','number')">Data Cadastro <?=compara($localOrdem,"DataCriacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdStatus','number')">Status <?=compara($localOrdem,"IdStatus",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
							<xsl:value-of select="PeriodoApuracaoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
							<xsl:value-of select="Fistel"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
							<xsl:value-of select="LoginCriacao"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
							<xsl:value-of select="DataCriacaoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:choose>
								<xsl:when test="IdStatus = '1'">
									<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
									<xsl:attribute name="onClick">excluir('<xsl:value-of select="PeriodoApuracaoTemp"/>')</xsl:attribute>
								</xsl:when>
								<xsl:otherwise>
									<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
								</xsl:otherwise>
							</xsl:choose>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="title">Excluir?</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
		<script type="text/javascript">
		<!--
			verificaAcao();
			tableMultColor('tableListar', document.filtro.corRegRand.value);
			-->
		</script>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
</xsl:template>
</xsl:stylesheet>