<?
	$localModulo		=	1;
	$localOperacao		=	25;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localServicoGrupo		= $_GET['ServicoGrupo'];
	$localDescricaoServico	= url_string_xsl($_GET['DescricaoServico'],'url',false);
	$localValor				= url_string_xsl($_GET['Valor'],'');
	$localIdStatus			= $_GET['IdStatus'];
	$localIdTipoServico		= $_GET['IdTipoServico'];
	$local_IdServico		= $_GET['IdServico'];
	
	$Limit					= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DescricaoServico";		}
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
		<script type = 'text/javascript' src = 'js/servico.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(30)?>')">
		<? include('filtro_servico_ticket_medio.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtroOrdenar('IdServico','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('DescricaoServico')"><?=dicionario(223)?> <?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('TipoServico')"><?=dicionario(436)?> <?=compara($localOrdem,"TipoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('DescricaoServicoGrupo')"><?=dicionario(568)?><?=compara($localOrdem,"DescricaoServicoGrupo",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('Valor','number')"><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('IdStatus','number')"><?=dicionario(140)?> <?=compara($localOrdem,"IdStatus",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdServico"/></xsl:attribute>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico.php?IdServico=<xsl:value-of select="IdServico"/></xsl:attribute>
							<xsl:value-of select="IdServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico.php?IdServico=<xsl:value-of select="IdServico"/></xsl:attribute>
							<xsl:value-of select="DescricaoServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico.php?IdServico=<xsl:value-of select="IdServico"/></xsl:attribute>
							<xsl:value-of select="format-number(ValorMax,'0,00','euro')"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico.php?IdServico=<xsl:value-of select="IdServico"/></xsl:attribute>
							<xsl:value-of select="format-number(ValorMed,'0,00','euro')"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico.php?IdServico=<xsl:value-of select="IdServico"/></xsl:attribute>
							<xsl:value-of select="format-number(ValorMin,'0,00','euro')"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico.php?IdServico=<xsl:value-of select="IdServico"/></xsl:attribute>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdServico"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='4' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor' style='width:120px' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/valorMax),"0,00","euro")' /></td>
					<td colspan='2' />
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
	
	addParmUrl("marServico","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoValor","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoValorNovo","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoParametro","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoParametroNovo","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoRotina","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marMascaraVigencia","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marMascaraVigenciaNovo","IdServico",'<?=$local_IdServico?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
	
	function filtroOrdenar(valor, typeDate, valor2, typeDate2){
		document.filtro.IdServico.value = '<?=$local_IdServico?>';
		
		filtro_ordenar(valor, typeDate, valor2, typeDate2);
	}
</script>
</xsl:template>
</xsl:stylesheet>
