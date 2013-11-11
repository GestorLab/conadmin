<?
	$localModulo		=	1;
	$localOperacao		=	82;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localDescricaoTipoOrdemServico	= url_string_xsl($_GET['DescricaoTipoOrdemServico'],'url',false);
	$localIdTipoOrdemServico		= $_GET['IdTipoOrdemServico'];
	$Limit							= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DescricaoTipoOrdemServico";		}
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
	</head>
	<body  onLoad="ativaNome('<?=dicionario(737)?>')">
		<? include('filtro_tipo_ordem_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdTipoOrdemServico','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdTipoOrdemServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoTipoOrdemServico','text')"><?=dicionario(738)?><?=compara($localOrdem,"DescricaoTipoOrdemServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Cor','text')"><?=dicionario(529)?><?=compara($localOrdem,"Cor",$ImgOrdernarASC,'')?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdTipoOrdemServico"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_tipo_ordem_servico.php?IdTipoOrdemServico=<xsl:value-of select="IdTipoOrdemServico"/></xsl:attribute>
							<xsl:value-of select="IdTipoOrdemServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_tipo_ordem_servico.php?IdTipoOrdemServico=<xsl:value-of select="IdTipoOrdemServico"/></xsl:attribute>
							<xsl:value-of select="DescricaoTipoOrdemServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_tipo_ordem_servico.php?IdTipoOrdemServico=<xsl:value-of select="IdTipoOrdemServico"/></xsl:attribute>
							<xsl:value-of select="Cor"/>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='3' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
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
	
	addParmUrl("marTipoOrdemServico","IdTipoOrdemServico",'<?=$localIdTipoOrdemServico?>');
	addParmUrl("marSubTipoOrdemServico","IdTipoOrdemServico",'<?=$localIdTipoOrdemServico?>');
	addParmUrl("marSubTipoOrdemServicoNovo","IdTipoOrdemServico",'<?=$localIdTipoOrdemServico?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>