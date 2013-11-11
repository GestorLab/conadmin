<?
	$localModulo		=	1;
	$localOperacao		=	4;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localGrupoParametroSistema	= $_GET['GrupoParametroSistema'];
	$localParametroSistema		= url_string_xsl($_GET['ParametroSistema'],'url',false);
	$localValorParametroSistema	= url_string_xsl($_GET['ValorParametroSistema'],'url',false);
	$localLimit					= $_GET['Limit'];

	if($localOrdem == ''){							$localOrdem = "DescricaoGrupoParametroSistema";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
	
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
		<script type = 'text/javascript' src = 'js/parametro_sistema.js'></script>
	</head>
	<body  onLoad="ativaNome('Parâmetros do Sistema')">
		<? include('filtro_parametro_sistema.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtro_ordenar('IdGrupoParametroSistema','number')">Grupo <?=compara($localOrdem,"IdGrupoParametroSistema",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoGrupoParametroSistema','text')">Grupo Parâmetro Sistema  <?=compara($localOrdem,"DescricaoGrupoParametroSistema",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdParametroSistema','number')">Id <?=compara($localOrdem,"IdParametroSistema",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoParametroSistema','text')">Nome Parâmetro Sistema <?=compara($localOrdem,"DescricaoParametroSistema",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('ValorParametroSistema','text')">Valor <?=compara($localOrdem,"ValorParametroSistema",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdGrupoParametroSistema"/>_<xsl:value-of select="IdParametroSistema"/></xsl:attribute>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_parametro_sistema.php?IdGrupoParametroSistema=<xsl:value-of select="IdGrupoParametroSistema"/>&amp;IdParametroSistema=<xsl:value-of select="IdParametroSistema"/></xsl:attribute>
							<xsl:value-of select="IdGrupoParametroSistema"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_parametro_sistema.php?IdGrupoParametroSistema=<xsl:value-of select="IdGrupoParametroSistema"/>&amp;IdParametroSistema=<xsl:value-of select="IdParametroSistema"/></xsl:attribute>
							<xsl:value-of select="DescricaoGrupoParametroSistema"/>
						</xsl:element>
					</td>
					<td style='padding-right:3px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_parametro_sistema.php?IdGrupoParametroSistema=<xsl:value-of select="IdGrupoParametroSistema"/>&amp;IdParametroSistema=<xsl:value-of select="IdParametroSistema"/></xsl:attribute>
							<xsl:value-of select="IdParametroSistema"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_parametro_sistema.php?IdGrupoParametroSistema=<xsl:value-of select="IdGrupoParametroSistema"/>&amp;IdParametroSistema=<xsl:value-of select="IdParametroSistema"/></xsl:attribute>
							<xsl:value-of select="DescricaoParametroSistema"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_parametro_sistema.php?IdGrupoParametroSistema=<xsl:value-of select="IdGrupoParametroSistema"/>&amp;IdParametroSistema=<xsl:value-of select="IdParametroSistema"/></xsl:attribute>
							<xsl:value-of select="ValorParametroSistema"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element> 
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
