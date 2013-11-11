<?
	$localModulo		= 1;
	$localOperacao		= 162;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localAssunto				= url_string_xsl($_GET['Assunto'],'url',false);
	$localIdProtocoloTipo		= url_string_xsl($_GET['IdProtocoloTipo'],'url',false);
	$localLocalAbertura			= url_string_xsl($_GET['LocalAbertura'],'url',false);
	$localIdStatus				= $_GET['IdStatus'];
	$Limit						= $_GET['Limit'];
	
	if($localOrdem == ''){
		$localOrdem = "Assunto";
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
	</head>
	<body onLoad="ativaNome('Protocolo')">
		<? include('filtro_protocolo.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdProtocolo','number')">Id <?=compara($localOrdem,"IdProtocolo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Assunto','text')">Assunto <?=compara($localOrdem,"Assunto",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoProtocoloTipo','text')">Tipo Protocolo <?=compara($localOrdem,"DescricaoProtocoloTipo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('LoginResponsavel','text')">Responsável <?=compara($localOrdem,"LoginResponsavel",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataCriacao','number')">Usuário de Aber. <?=compara($localOrdem,"DataCriacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataCriacao','number')">Data de Aber. <?=compara($localOrdem,"DataCriacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataCriacao','number')">Previsão<?=compara($localOrdem,"DataCriacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdStatus','number')">Status <?=compara($localOrdem,"IdStatus",$ImgOrdernarASC,'')?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdProtocolo"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="IdProtocolo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="Assunto"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="DescricaoProtocoloTipo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="LoginResponsavel"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="LoginCriacao"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="DataCriacaoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="PrevisaoEtapa"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='7' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
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