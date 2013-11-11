<?
	$localModulo		=	1;
	$localOperacao		=	63;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro						= $_GET['Filtro'];
	$localOrdem							= $_GET['Ordem'];
	$localOrdemDirecao					= $_GET['OrdemDirecao'];
	$localTipoDado						= $_GET['TipoDado'];
	$localDescricaoParametroContrato	= url_string_xsl($_GET['DescricaoParametroContrato'],'URL',false);
	$localValorDefault					= url_string_xsl($_GET['ValorDefault'],'URL',false);
	$localObrigatorio					= $_GET['Obrigatorio'];
	$localIdStatus						= $_GET['IdStatus'];
	$local_IdLocalCobranca				= $_GET['IdLocalCobranca'];
	
	$Limit					= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DescricaoParametroContrato";		}
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
		<script type = 'text/javascript' src = 'js/local_cobranca_parametro_contrato.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(856)?>')">
		<? include('filtro_local_cobranca_parametro_contrato.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdLocalCobrancaParametroContrato','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdLocalCobrancaParametroContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoParametroContrato')"><?=dicionario(648)?><?=compara($localOrdem,"DescricaoParametroContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('ValorDefault')"><?=dicionario(609)?> <?=compara($localOrdem,"ValorDefault",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Editavel')"><?=dicionario(592)?> <?=compara($localOrdem,"Editavel",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Obrigatorio')"><?=dicionario(593)?> <?=compara($localOrdem,"Obrigatorio",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Calculavel')"><?=dicionario(848)?> <?=compara($localOrdem,"Calculavel",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status')"><?=dicionario(140)?> <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdLocalCobranca"/>_<xsl:value-of select="IdLocalCobrancaParametroContrato"/></xsl:attribute>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca_parametro_contrato.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdLocalCobrancaParametroContrato=<xsl:value-of select="IdLocalCobrancaParametroContrato"/></xsl:attribute>
							<xsl:value-of select="IdLocalCobrancaParametroContrato"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca_parametro_contrato.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdLocalCobrancaParametroContrato=<xsl:value-of select="IdLocalCobrancaParametroContrato"/></xsl:attribute>
							<xsl:value-of select="DescricaoParametroContrato"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca_parametro_contrato.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdLocalCobrancaParametroContrato=<xsl:value-of select="IdLocalCobrancaParametroContrato"/></xsl:attribute>
							<xsl:value-of select="ValorDefault"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca_parametro_contrato.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdLocalCobrancaParametroContrato=<xsl:value-of select="IdLocalCobrancaParametroContrato"/></xsl:attribute>
							<xsl:value-of select="Editavel"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca_parametro_contrato.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdLocalCobrancaParametroContrato=<xsl:value-of select="IdLocalCobrancaParametroContrato"/></xsl:attribute>
							<xsl:value-of select="Obrigatorio"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca_parametro_contrato.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdLocalCobrancaParametroContrato=<xsl:value-of select="IdLocalCobrancaParametroContrato"/></xsl:attribute>
							<xsl:value-of select="Calculavel"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_local_cobranca_parametro_contrato.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdLocalCobrancaParametroContrato=<xsl:value-of select="IdLocalCobrancaParametroContrato"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdLocalCobranca"/>,<xsl:value-of select="IdLocalCobrancaParametroContrato"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='8' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
				</tr>
			</table>
			<table>
				<tr>
					<td class='find' />
					<td><h1 id='helpText' name='helpText' /></td>
				</tr>
			</table>
		</div>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
<script>
	verificaAcao();
	
	addParmUrl("marLocalCobranca","IdLocalCobranca",'<?=$local_IdLocalCobranca?>');
	addParmUrl("marLocalCobrancaParametro","IdLocalCobranca",'<?=$local_IdLocalCobranca?>');
	addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca",'<?=$local_IdLocalCobranca?>');
	addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca",'<?=$local_IdLocalCobranca?>');
	addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca",'<?=$local_IdLocalCobranca?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
