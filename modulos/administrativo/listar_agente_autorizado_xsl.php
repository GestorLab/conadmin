<?
	$localModulo		=	1;
	$localOperacao		=	23;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localNome					= url_string_xsl($_GET['Nome'],'url',false);
	$localIdStatus				= $_GET['IdStatus'];
	$localRestringir			= $_GET['Restringir'];
	$Limit						= $_GET['Limit'];
	$IdAgenteAutorizado			= $_GET['IdAgenteAutorizado'];

	if($localOrdem == ''){					$localOrdem = "RazaoSocial";		}
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
		<script type = 'text/javascript' src = 'js/agente.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(32)?>')">
		<? include('filtro_agente_autorizado.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdAgenteAutorizado','number')"><?=dicionario(141)?> <?=compara($localOrdem,"IdAgenteAutorizado",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('RazaoSocial','text')"><?=dicionario(768)?> <?=compara($localOrdem,"RazaoSocial",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Restringir','text')"><?=dicionario(741)?> <?=compara($localOrdem,"Restringir",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status','text')"><?=dicionario(140)?> <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdAgenteAutorizado"/></xsl:attribute>
					<td class='sequencial'  style='width:25px'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agente_autorizado.php?IdAgenteAutorizado=<xsl:value-of select="IdAgenteAutorizado"/></xsl:attribute>
							<xsl:value-of select="IdAgenteAutorizado"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agente_autorizado.php?IdAgenteAutorizado=<xsl:value-of select="IdAgenteAutorizado"/></xsl:attribute>
							<xsl:value-of select="RazaoSocial"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agente_autorizado.php?IdAgenteAutorizado=<xsl:value-of select="IdAgenteAutorizado"/></xsl:attribute>
							<xsl:value-of select="Restringir"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agente_autorizado.php?IdAgenteAutorizado=<xsl:value-of select="IdAgenteAutorizado"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdAgenteAutorizado"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='5' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
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
	addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marCarteira","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marCarteiraNovo","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marCarteiraComissionamento","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marCarteiraComissionamentoNovo","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>