<?
	$localModulo		=	1;
	$localOperacao		=	15;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro		= $_GET['Filtro'];
	$localOrdem			= $_GET['Ordem'];
	$localOrdemDirecao	= $_GET['OrdemDirecao'];
	$localTipoDado		= $_GET['TipoDado'];
	$localIdPais		= $_GET['IdPais'];
	$localEstado		= $_GET['Estado'];
	$localCidade		= url_string_xsl($_GET['Cidade'],'url',false);
	$Limit				= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "Cidade";		}
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
		<script type = 'text/javascript' src = 'js/cidade.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(186)?>')">
		<? include('filtro_cidade.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar' />
					<td>
						<a href="javascript:filtro_ordenar('Pais','text')"><?=dicionario(257)?> <?=compara($localOrdem,"Pais",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Estado','text')"><?=dicionario(259)?> <?=compara($localOrdem,"Estado",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Cidade','text')"><?=dicionario(260)?> <?=compara($localOrdem,"Cidade",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdPais"/>_<xsl:value-of select="IdEstado"/>_<xsl:value-of select="IdCidade"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:number value="position()" format="1" />
					</td>
					<td style='width: 235px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cidade.php?IdPais=<xsl:value-of select="IdPais"/>&amp;IdEstado=<xsl:value-of select="IdEstado"/>&amp;IdCidade=<xsl:value-of select="IdCidade"/></xsl:attribute>
							<xsl:value-of select="Pais"/>
						</xsl:element>
					</td>
					<td style='width: 235px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cidade.php?IdPais=<xsl:value-of select="IdPais"/>&amp;IdEstado=<xsl:value-of select="IdEstado"/>&amp;IdCidade=<xsl:value-of select="IdCidade"/></xsl:attribute>
							<xsl:value-of select="Estado"/>
						</xsl:element>
					</td>
					<td style='width: 235px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cidade.php?IdPais=<xsl:value-of select="IdPais"/>&amp;IdEstado=<xsl:value-of select="IdEstado"/>&amp;IdCidade=<xsl:value-of select="IdCidade"/></xsl:attribute>
							<xsl:value-of select="Cidade"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdPais"/>,<xsl:value-of select="IdEstado"/>,<xsl:value-of select="IdCidade"/>)</xsl:attribute>
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
	addParmUrl("marPais","IdPais",'<?=$local_IdPais?>');
	addParmUrl("marEstado","IdPais",'<?=$local_IdPais?>');
	addParmUrl("marEstado","IdEstado",'<?=$local_IdEstado?>');
	addParmUrl("marEstadoNovo","IdPais",'<?=$local_IdPais?>');
	addParmUrl("marCidade","IdPais",'<?=$local_IdPais?>');
	addParmUrl("marCidade","IdEstado",'<?=$local_IdEstado?>');
	addParmUrl("marCidade","IdCidade",'<?=$local_IdCidade?>');
	addParmUrl("marCidadeNovo","IdPais",'<?=$local_IdPais?>');
	addParmUrl("marCidadeNovo","IdEstado",'<?=$local_IdEstado?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
