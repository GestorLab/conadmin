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
	$localDataRetorno		= $_GET['DataRetorno'];
	$localCampo				= $_GET['Campo'];
	$localDataInicio		= $_GET['DataInicio'];
	$localDataTermino		= $_GET['DataTermino'];
	$localNomeArquivo		= url_string_xsl($_GET['NomeArquivo'],'url',false);
	$localNumeroSequencial	= $_GET['NSequencial'];
	$localSeqDataIncio		= $_GET['SeqDataIncio'];
	$localSeqDataFinal		= $_GET['SeqDataFinal'];
	$localProcessado		= $_GET['Processado'];
	$Limit					= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "IdArquivoRetorno";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){	$Limit = getCodigoInterno(7,5);	}
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
		<script type = 'text/javascript' src = 'js/arquivo_retorno.js'></script>
	</head>
	<body  onLoad="ativaNome('Arquivo de Retorno (Cancelar Processamento)')">
		<? include('filtro_cancelar_arquivo_retorno.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdArquivoRetorno')">Id<?=compara($localOrdem,"IdArquivoRetorno",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalRecebimento')">Local Receb.<?=compara($localOrdem,"AbreviacaoNomeLocalRecebimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('NomeArquivo')">Nome Original <?=compara($localOrdem,"NomeArquivo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataCadastro','number')">Data Cadastro <?=compara($localOrdem,"DataCadastro",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataRetorno','number')">Data Retorno <?=compara($localOrdem,"DataRetorno",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QtdRegistro','number')">QTD. Lançamentos <?=compara($localOrdem,"QtdRegistro",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotal','number')">Valor Total (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorTotal",$ImgOrdernarASC,'')?></a>
					</td>
					<td style='width: 77px'>
						<a href="javascript:filtro_ordenar('ValorParametroSistema')">Status <?=compara($localOrdem,"ValorParametroSistema",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdLocalRecebimento"/>_<xsl:value-of select="IdArquivoRetorno"/></xsl:attribute>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_retorno.php?IdLocalRecebimento=<xsl:value-of select="IdLocalRecebimento"/>&amp;IdArquivoRetorno=<xsl:value-of select="IdArquivoRetorno"/></xsl:attribute>
							<xsl:value-of select="IdArquivoRetorno"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_retorno.php?IdLocalRecebimento=<xsl:value-of select="IdLocalRecebimento"/>&amp;IdArquivoRetorno=<xsl:value-of select="IdArquivoRetorno"/></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalRecebimento"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_retorno.php?IdLocalRecebimento=<xsl:value-of select="IdLocalRecebimento"/>&amp;IdArquivoRetorno=<xsl:value-of select="IdArquivoRetorno"/></xsl:attribute>
							<xsl:value-of select="NomeArquivo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_retorno.php?IdLocalRecebimento=<xsl:value-of select="IdLocalRecebimento"/>&amp;IdArquivoRetorno=<xsl:value-of select="IdArquivoRetorno"/></xsl:attribute>
							<xsl:value-of select="DataCadastroTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_retorno.php?IdLocalRecebimento=<xsl:value-of select="IdLocalRecebimento"/>&amp;IdArquivoRetorno=<xsl:value-of select="IdArquivoRetorno"/></xsl:attribute>
							<xsl:value-of select="DataRetornoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_retorno.php?IdLocalRecebimento=<xsl:value-of select="IdLocalRecebimento"/>&amp;IdArquivoRetorno=<xsl:value-of select="IdArquivoRetorno"/></xsl:attribute>
							<xsl:value-of select="QtdRegistro"/>
						</xsl:element>
					</td>
					<td  class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_cancelar_arquivo_retorno.php?IdLocalRecebimento=<xsl:value-of select="IdLocalRecebimento"/>&amp;IdArquivoRetorno=<xsl:value-of select="IdArquivoRetorno"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorTotal,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_arquivo_retorno.php?IdLocalRecebimento=<xsl:value-of select="IdLocalRecebimento"/>&amp;IdArquivoRetorno=<xsl:value-of select="IdArquivoRetorno"/></xsl:attribute>
							<xsl:value-of select="Status"/>
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
