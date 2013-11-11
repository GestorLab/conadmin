<?
	$localModulo		=	1;
	$localOperacao		=	10002;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$local_id					= $_GET['id'];
	$localNasname				= $_GET['nasname'];
	$localSecret				= $_GET['secret'];
	$Limit						= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "Data";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/nas.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(1023)?>')">
		<? include('filtro_nas.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('id','number')"><?=compara($localOrdem,"id",$imgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('nasname','text')"><?=dicionario(1025);// Nas Name?><?=compara($localOrdem,"TYPE",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('shortname','text')"><?=dicionario(1026);// Short Name?><?=compara($localOrdem,"ports",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('TYPE','text')"><?=dicionario(1027);// TYPE?><?=compara($localOrdem,"TYPE",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('ports','text')"><?=dicionario(1028);// PORTS?><?=compara($localOrdem,"ports",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('secret','text')"><?=dicionario(1029);// Secret?><?=compara($localOrdem,"secret",$ImgOrdernarASC,'')?></a>
					</td>
					
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="id"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nas.php?id=<xsl:value-of select="id"/></xsl:attribute>
							<xsl:value-of select="id"/>
						</xsl:element>
					</td>
				<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nas.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="nasname"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nas.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="shortname"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nas.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="TYPE"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nas.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="ports"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nas.php?id=<xsl:value-of select="id"/>&amp;Tipo=<xsl:value-of select="Tipo"/>&amp;IdServidor=<xsl:value-of select="IdServidor"/></xsl:attribute>
							<xsl:value-of select="secret"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="id"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='7' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>