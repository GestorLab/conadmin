<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localIdLocalCobranca	= $_GET['IdLocalCobranca'];
	$localIdStatus			= $_GET['IdStatus'];
	$localDataInicio		= $_GET['DataInicio'];
	$localDataTermino		= $_GET['DataTermino'];
	$Limit					= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "IdArquivoRemessa";		}
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
		<script type = 'text/javascript' src = 'js/arquivo_remessa.js'></script>
	</head>
	<body  onLoad="ativaNome('Arquivo de Remessa')">
		<? include('filtro_cancelar_arquivo_remessa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdArquivoRemessa')">Id<?=compara($localOrdem,"IdArquivoRemessa",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca')">Local Cob.<?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('NomeArquivo')">Nome Original <?=compara($localOrdem,"NomeArquivo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataCadastro','number')">Data Cadastro <?=compara($localOrdem,"DataCadastro",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataRemessa','number')">Data Remessa <?=compara($localOrdem,"DataRemessa",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QtdRegistro','number')">QTD. Lançamentos <?=compara($localOrdem,"QtdRegistro",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotal','number')">Valor Total (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorTotal",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status')">Status <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdArquivoRemessa"/>_<xsl:value-of select="IdLocalCobranca"/></xsl:attribute>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_remessa.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdArquivoRemessa=<xsl:value-of select="IdArquivoRemessa"/></xsl:attribute>
							<xsl:value-of select="IdArquivoRemessa"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_remessa.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdArquivoRemessa=<xsl:value-of select="IdArquivoRemessa"/></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_remessa.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdArquivoRemessa=<xsl:value-of select="IdArquivoRemessa"/></xsl:attribute>
							<xsl:value-of select="NomeArquivo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_remessa.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdArquivoRemessa=<xsl:value-of select="IdArquivoRemessa"/></xsl:attribute>
							<xsl:value-of select="DataCadastroTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_remessa.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdArquivoRemessa=<xsl:value-of select="IdArquivoRemessa"/></xsl:attribute>
							<xsl:value-of select="DataRemessaTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_remessa.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdArquivoRemessa=<xsl:value-of select="IdArquivoRemessa"/></xsl:attribute>
							<xsl:value-of select="QtdRegistro"/>
						</xsl:element>
					</td>
					<td  class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_remessa.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdArquivoRemessa=<xsl:value-of select="IdArquivoRemessa"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorTotal,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_remessa.php?IdLocalCobranca=<xsl:value-of select="IdLocalCobranca"/>&amp;IdArquivoRemessa=<xsl:value-of select="IdArquivoRemessa"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:choose>
								<xsl:when test="IdStatus = 1">
									<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
									<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdArquivoRemessa"/>,<xsl:value-of select="IdLocalCobranca"/>)</xsl:attribute>
								</xsl:when>
								<xsl:otherwise>
									<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
								</xsl:otherwise>
							</xsl:choose>
							
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="title">Excluir?</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorTotal),"0,00","euro")' /></td>
					<td />
					<td />
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