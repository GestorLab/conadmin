<?
	$localModulo		=	1;
	$localOperacao		=	170;
	$localSuboperacao	=	"R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localLoja				= url_string_xsl($_GET['Loja'],'');
	$localDescricaoLoja		= url_string_xsl($_GET['DescricaoLoja'],'url',false);
	$localDescricaoServico	= url_string_xsl($_GET['DescricaoServico'],'url',false);
	$localCampo				= $_GET['Campo'];
	$localDataInicio		= $_GET['DataInicio'];
	$localDataFim			= $_GET['DataFim'];
	$localIdStatus			= $_GET['IdStatus'];
	$localIdServico			= $_GET['IdServico'];
	$localIdLocalCobranca	= $_GET['IdLocalCobranca'];
	$localUsuario			= $_GET['Usuario'];
	$localIdEstado			= $_GET['IdEstado'];
	$localIdCidade			= $_GET['IdCidade'];
	$localBairro			= url_string_xsl($_GET['Bairro'],'url',false);
	$localEndereco			= url_string_xsl($_GET['Endereco'],'url',false);
	$Limit					= $_GET['Limit'];
	
	if($localOrdem == ''){
		$localOrdem = "DescricaoLoja";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
	}
	
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
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/impress.css' media='print' />
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/contrato.js'></script>
	</head>
	<body onLoad="ativaNome('<?=dicionario(333)?>')">
		<div id='carregando'><?=dicionario(17)?></div>
		<? include("filtro_contrato_loja.php"); ?>
		<div id='conteudo'>
			<table class='tableListar' id='tableListar'  cellspacing='0'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdLoja','number')"><?=dicionario(141)?> <?=compara($localOrdem,"IdLoja",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoLoja','text')"><?=dicionario(334)?> <?=compara($localOrdem,"DescricaoLoja",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('QTDPessoa','number')"><?=dicionario(337)?> <?=compara($localOrdem,"QTDPessoa",$ImgOrdernarASC,'')?></a>
					</td>	
					<td class='valor'>
						<a href="javascript:filtro_ordenar('QTDPessoaContrato','number')"><?=dicionario(338)?> <?=compara($localOrdem,"QTDPessoaContrato",$ImgOrdernarASC,'')?></a>
					</td>	
					<td class='valor'>
						<a href="javascript:filtro_ordenar('QTDContrato','number')"><?=dicionario(339)?> <?=compara($localOrdem,"QTDContrato",$ImgOrdernarASC,'')?></a>
					</td>	
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorContrato','number')"><?=dicionario(340)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('QTDOrdemServico','number')"><?=dicionario(341)?> <?=compara($localOrdem,"QTDOrdemServico",$ImgOrdernarASC,'')?></a>
					</td>		
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorOrdemServico','number')"><?=dicionario(342)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorOrdemServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdLoja"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:value-of select="IdLoja"/>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:value-of select="DescricaoLoja"/>
					</td>
					<td class='valor'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:value-of select="QTDPessoa"/>
					</td>	
					<td class='valor'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:value-of select="QTDPessoaContrato"/>
					</td>	
					<td class='valor'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:value-of select="QTDContrato"/>
					</td>
					<td class='valor'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:value-of select="ValorContratoTemp"/>
					</td>
					<td class='valor'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:value-of select="QTDOrdemServico"/>
					</td>	
					<td class='valor'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:value-of select="ValorOrdemServicoTemp"/>
					</td>	
					<td class='bt_lista'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='8' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
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
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>