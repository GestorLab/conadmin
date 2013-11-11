<?
	$localModulo		=	1;
	$localOperacao		=	10001;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localIdGrupo				= url_string_xsl($_GET['IdGrupo'],'');
	$localTipo					= url_string_xsl($_GET['Tipo'],'');
	$localAtributo				= url_string_xsl($_GET['Atributo'],'');
	$localValor					= url_string_xsl($_GET['Valor'],'url',false);
	$localIdServidor			= $_GET['IdServidor'];
	$localid					= $_GET['id'];
	$Limit						= $_GET['Limit'];
	$local_IdLicenca			= $_SESSION["IdLicenca"];	

	if($localOrdem == ''){					$localOrdem = "GroupName";		}
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
		<script type = 'text/javascript' src = 'js/radius.js'></script>
	</head>
	<body  onLoad="ativaNome('Servidor Radius')">
		<? include('filtro_radius.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar' />
					<td>
						<a href="javascript:filtro_ordenar('Servidor','number')">Servidor Radius<?=compara($localOrdem,"Servidor",$imgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('GroupName','number')">Grupo<?=compara($localOrdem,"GroupName",$imgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescTipo','text')">Tipo <?=compara($localOrdem,"DescTipo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Attribute','text')">Atributo <?=compara($localOrdem,"Attribute",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('op','text')">Operador <?=compara($localOrdem,"op",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Value','text')">Valor <?=compara($localOrdem,"Value",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="id"/>_<xsl:value-of select="Tipo"/>_<xsl:value-of select="IdServidor"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:number value="position()" format="1" />
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_radius.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="Servidor"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_radius.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="GroupName"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_radius.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="DescTipo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_radius.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="Attribute"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_radius.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="op"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_radius.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="Value"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="id"/>,'<xsl:value-of select="Tipo"/>',<xsl:value-of select="IdServidor"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='8' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
	
	addParmUrl("marRadius","id",'<?=$localid?>');
	addParmUrl("marRadius","IdServidor",'<?=$localIdServidor?>');
	addParmUrl("marRadius","Tipo",'<?=$localTipo?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
