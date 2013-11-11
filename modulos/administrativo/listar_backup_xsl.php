<?
	$localModulo		=	1;
	$localOperacao		=	117;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];

	$localLog						= url_string_xsl($_GET['Log'],'url',false);
	$localDataInicio				= url_string_xsl($_GET['DataHoraInicio'],'');
	$localDataTermino				= url_string_xsl($_GET['DataHoraTermino'],'');
		
	$localLimit						= $_GET['Limit'];

	if($localOrdem == ''){			$localOrdem = "DataHoraInicio";				}
	if($localOrdemDirecao == ''){	$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){		$localTipoDado = "number";					}
	
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
	</head>
	<body  onLoad="ativaNome('Backup')">
		<? include('filtro_backup.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtro_ordenar('DataHoraInicio','number')">Inicio <?=compara($localOrdem,"DataHoraInicio",$ImgOrdernarASC,'')?></a>
					</td>				
					<td>
						<a href="javascript:filtro_ordenar('DataHoraConclusao','number')">Conclusão <?=compara($localOrdem,"DataHoraConclusao",$ImgOrdernarASC,'')?></a>
					</td>			
					<td>
						<a href="javascript:filtro_ordenar('Log','text')">Log <?=compara($localOrdem,"Log",$ImgOrdernarASC,'')?></a>
					</td>			
					<td>
						<a href="javascript:filtro_ordenar('Size','number')">Size <?=compara($localOrdem,"Size",$ImgOrdernarASC,'')?></a>
					</td>									
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="DataHoraInicio"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="width">77px</xsl:attribute>
						<xsl:attribute name="align">center</xsl:attribute>
						<xsl:value-of select="DataHoraInicioTemp"/>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="width">77px</xsl:attribute>
						<xsl:attribute name="align">center</xsl:attribute>	
						<xsl:value-of select="DataHoraConclusaoTemp"/>
					</xsl:element>
					<xsl:element name="td">
						<xsl:for-each select="Log/regInt">
							<xsl:value-of select="texto"/><br/>
						</xsl:for-each>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="width">60px</xsl:attribute>
						<xsl:attribute name="align">center</xsl:attribute>								
						<xsl:value-of select="SizeTemp"/>							
					</xsl:element>															
					<xsl:element name="td">
						<xsl:attribute name="class">bt_lista</xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
							<xsl:attribute name="alt">Cancelar?</xsl:attribute>							
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='2'>Total: <xsl:value-of select="count(db/reg)" /></td>								
					<td />				
					<td />				
					<td colspan='4' />
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
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;

</script>
</xsl:template>
</xsl:stylesheet>

