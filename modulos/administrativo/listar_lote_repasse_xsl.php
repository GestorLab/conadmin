<?
	$localModulo		=	1;
	$localOperacao		=	42;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome					= url_string_xsl($_GET['Nome'],'url',false);
	$localIdStatus				= url_string_xsl($_GET['IdStatus'],'');
	$local_IdLoteRepasse		= $_GET['IdLoteRepasse'];
	$local_IdPessoa				= $_GET['IdPessoa'];
	$Limit						= $_GET['Limit'];

	if($localOrdem == ''){			$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){	$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){		$localTipoDado = 'text';	}
	
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
		<script type = 'text/javascript' src = 'js/lote_repasse.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(47)?>')">
		<div id='carregando'><?=dicionario(17)?></div>
		<? include('filtro_lote_repasse.php'); ?>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdLoteRepasse','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdLoteRepasse",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome','text')"><?=dicionario(775)?><?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Filtro_MesReferencia','number')"><?=dicionario(196)?><?=compara($localOrdem,'Filtro_MesReferencia',$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotalItens','number')"><?=dicionario(1038)?> (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorTotalItens",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataConfirmacao','number')"><?=dicionario(787)?><?=compara($localOrdem,"DataConfirmacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotalRepasse','number')"><?=dicionario(1039)?> (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorTotalRepasse",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status','text')"><?=dicionario(140)?><?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdLoteRepasse"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lote_repasse.php?IdLoteRepasse=<xsl:value-of select="IdLoteRepasse"/></xsl:attribute>
							<xsl:value-of select="IdLoteRepasse"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lote_repasse.php?IdLoteRepasse=<xsl:value-of select="IdLoteRepasse"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name='td'>
						<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
						<xsl:element name='a'>
							<xsl:attribute name="href">cadastro_lote_repasse.php?IdLoteRepasse=<xsl:value-of select="IdLoteRepasse"/></xsl:attribute>
							<xsl:value-of select="Filtro_MesReferenciaTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lote_repasse.php?IdLoteRepasse=<xsl:value-of select="IdLoteRepasse"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorTotalItens,"0,00","euro")'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name='td'>
						<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
						<xsl:element name='a'>
							<xsl:attribute name="href">cadastro_lote_repasse.php?IdLoteRepasse=<xsl:value-of select="IdLoteRepasse"/></xsl:attribute>
							<xsl:value-of select="DataConfirmacaoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lote_repasse.php?IdLoteRepasse=<xsl:value-of select="IdLoteRepasse"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorTotalRepasse,"0,00","euro")'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lote_repasse.php?IdLoteRepasse=<xsl:value-of select="IdLoteRepasse"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">bt_lista</xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="Img"/></xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdContaReceber"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='3' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorTotalItens),"0,00","euro")' /></td>
					<td />
					<td class='valor' id='tableListarRepasse'><xsl:value-of select='format-number(sum(db/reg/ValorTotalRepasse),"0,00","euro")' /></td>
					<td colspan='2' />
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
	
	addParmUrl("marLoteRepasse","IdLoteRepasse",'<?=$local_IdLoteRepasse?>');
	addParmUrl("marTerceiro","IdPessoa",'<?=$local_IdPessoa?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
	
</script>
</xsl:template>
</xsl:stylesheet>