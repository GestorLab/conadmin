<?
	$localModulo		=	1;
	$localOperacao		=	100;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localPessoa					= url_string_xsl($_GET['Pessoa'],'url',false);
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'url',false);
	$localDataInicio				= url_string_xsl($_GET['DataInicio'],'');
	$localDataTermino				= url_string_xsl($_GET['DataTermino'],'');
	$localIdStatusAntigo			= $_GET['IdStatusAntigo'];
	$localIdStatusNovo				= $_GET['IdStatusNovo'];
	$localIdStatusAtual				= $_GET['IdStatusAtual'];
	$localIdContrato				= $_GET['IdContrato'];
	$Limit							= $_GET['Limit'];
	$local_IdPessoa					= $_GET['IdPessoa'];
	$local_IdServico				= $_GET['IdServico'];
	$local_IdContrato				= $_GET['IdContrato'];
	
	if($localOrdem == ''){					$localOrdem = "Nome";		}
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
		<script type = 'text/javascript' src = 'js/contrato.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_status_data.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(323)?>')">
		<? include("filtro_contrato_status_data.php"); ?>
		<div id='carregando'><?=dicionario(323)?></div>
		<div id='conteudo'>
			<table class='tableListar' id='tableListar'  cellspacing='0'>
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtro_ordenar('IdContrato','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdMudancaStatus')"><?=dicionario(330)?><?=compara($localOrdem,"IdMudancaStatus",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServico')"><?=dicionario(223)?> <?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('StatusAntigo')"><?=dicionario(326)?> <?=compara($localOrdem,"StatusAntigo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('StatusNovo')"><?=dicionario(327)?> <?=compara($localOrdem,"StatusNovo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('LoginAlteracao')"><?=dicionario(2)?> <?=compara($localOrdem,"LoginAlteracao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataAlteracao','number')"><?=dicionario(135)?><?=compara($localOrdem,"DataAlteracao",$ImgOrdernarASC,'')?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContrato"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="IdContrato"/>
						</xsl:element>
					</td>
					<td class='sequencial'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="IdMudancaStatus"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="DescricaoServico"/>
						</xsl:element>
					</td>							
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="StatusAntigo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="StatusNovo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="LoginAlteracao"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="DataAlteracaoTemp"/>
						</xsl:element>
					</td>	
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='8' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
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
	
	addParmUrl("marContrato","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marContrato","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContratoNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marVigencia","IdContrato",'<?=$localIdContrato?>', true);
	addParmUrl("marVigenciaNovo","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marVigencia","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marVigenciaNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marProcessoFinanceiro","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marProcessoFinanceiroNovo","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marReenvioEmail","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContaEventual","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServicoNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServicoNovo","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marOrdemServico","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marOrdemServico","IdPessoa",'<?=$localIdPessoa?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
