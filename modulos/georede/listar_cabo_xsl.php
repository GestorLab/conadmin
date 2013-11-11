<?
	$localModulo		=	1;
	$localOperacao		=	205;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localIdCabo				= $_GET['IdCabo'];
	$localEscpecificacao		= $_GET['filtro_especificacao'];
	$localTipoCabo				= $_GET['fitro_Tipo_Cabo'];
	$localNomeCabo				= $_GET['filtro_nomeCabo'];
	$Limit						= $_GET['Limit'];

	
	if($localOrdem == ''){					$localOrdem = "IdCabo";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){	$Limit = getCodigoInterno(7,5);	}
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
		<script type = 'text/javascript' src = 'js/cabo.js'></script>
	</head>
	<body  onLoad="ativaNome('Cabo')">
		<? include('filtro_cabo.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdCabo','number')">Id<?=compara($localOrdem,"IdCabo",$ImgOrdernarASC,'')?></a>
					</td>
					
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('NomeCabo','number')">Nome Cabo<?=compara($localOrdem,"NomeCabo",$ImgOrdernarASC,'')?></a>
					</td>
					
					<td>
						<a href="javascript:filtro_ordenar('TipoCabo','number')">Tipo Cabo<?=compara($localOrdem,"TipoCabo",$ImgOrdernarASC,'')?></a>
					</td>				
					<td>
						<a href="javascript:filtro_ordenar('Especificacao','text')">Especificação<?=compara($localOrdem,"Especificacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QtdFibra','text')">Qtd. Fibra<?=compara($localOrdem,"QtdFibra",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdCabo"/></xsl:attribute>
					
					<td class='sequencial' style='width:40px'>		
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cabo.php?IdCabo=<xsl:value-of select="IdCabo"/></xsl:attribute>
							<xsl:value-of select="IdCabo"/>
						</xsl:element>
					</td>
					
					<td class='sequencial' style='width:200px'>		
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cabo.php?IdCabo=<xsl:value-of select="IdCabo"/></xsl:attribute>
							<xsl:value-of select="NomeCabo"/>
						</xsl:element>
					</td>
					<td class='sequencial'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cabo.php?IdCabo=<xsl:value-of select="IdCabo"/></xsl:attribute>
							<xsl:value-of select="DescricaoCaboTipo"/>
						</xsl:element>
					</td>
					<td>				
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cabo.php?IdCabo=<xsl:value-of select="IdCabo"/></xsl:attribute>
							<xsl:value-of select="Especificacao"/>
						</xsl:element>
					</td>
					<td>				
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cabo.php?IdCabo=<xsl:value-of select="IdCabo"/></xsl:attribute>
							<xsl:value-of select="QtdFibra"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="title">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdCabo"/>,'')</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
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
	addParmUrl("marPoste","IdCabo",'<?=$localIdCabo?>');
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
