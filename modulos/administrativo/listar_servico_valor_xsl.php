<?
	$localModulo		=	1;
	$localOperacao		=	25;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localDescricaoServico		= url_string_xsl($_GET['DescricaoServico'],'');
	$localDescricaoServicoValor	= url_string_xsl($_GET['DescricaoServicoValor'],'');
	$localDataInicio			= url_string_xsl($_GET['DataInicio'],'');
	$localDataTermino			= url_string_xsl($_GET['DataTermino'],'');
	$local_IdServico			= $_GET['IdServico'];
	$Limit						= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DataInicio";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao('xsl');
	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/servico_valor.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(569)?>')">
		<? include('filtro_servico_valor.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar' />
					<td>
						<a href="javascript:filtro_ordenar('DataInicio','number')"><?=dicionario(376)?><?=compara($localOrdem,"DataInicio",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataTermino','number')"><?=dicionario(433)?><?=compara($localOrdem,"DataTermino",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServicoValor')">Desc. Serviço Valor<?=compara($localOrdem,"DescricaoServicoValor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Valor','number')">Valor (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('MultaFidelidade','number')">Valor Multa Fidelidade (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"MultaFidelidade",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdServico"/>_<xsl:value-of select="DataInicioBusca"/></xsl:attribute>
					<td>
						<xsl:number value="position()" format="1" />
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_valor.php?IdServico=<xsl:value-of select="IdServico"/>&amp;DataInicio=<xsl:value-of select="DataInicioBusca"/></xsl:attribute>
							<xsl:value-of select="DataInicioTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_valor.php?IdServico=<xsl:value-of select="IdServico"/>&amp;DataInicio=<xsl:value-of select="DataInicioBusca"/></xsl:attribute>
							<xsl:value-of select="DataTerminoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_valor.php?IdServico=<xsl:value-of select="IdServico"/>&amp;DataInicio=<xsl:value-of select="DataInicioBusca"/></xsl:attribute>
							<xsl:value-of select="DescricaoServicoValor"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_valor.php?IdServico=<xsl:value-of select="IdServico"/>&amp;DataInicio=<xsl:value-of select="DataInicioBusca"/></xsl:attribute>
							<xsl:value-of select='format-number(Valor,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_valor.php?IdServico=<xsl:value-of select="IdServico"/>&amp;DataInicio=<xsl:value-of select="DataInicioBusca"/></xsl:attribute>
							<xsl:value-of select='format-number(MultaFidelidade,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdServico"/>,'<xsl:value-of select="DataInicioBusca"/>')</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='2' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td />
					<td />
					<td class='valor' style='width:120px' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
					<td class='valor' style='width:170px' id='tableListarMulta'><xsl:value-of select='format-number(sum(db/reg/MultaFidelidade),"0,00","euro")' /></td>
					<td />
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
	
	addParmUrl("marServico","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoValor","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoValorNovo","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoParametro","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoParametroNovo","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoRotina","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marMascaraVigencia","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marMascaraVigenciaNovo","IdServico",'<?=$local_IdServico?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
