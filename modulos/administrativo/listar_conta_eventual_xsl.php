<?
	$localModulo		=	1;
	$localOperacao		=	31;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'url',false);
	$localDescricaoContaEventual	= url_string_xsl($_GET['DescricaoContaEventual'],'url',false);
	$localIdLocalCobranca			= $_GET['IdLocalCobranca'];
	$localVencimento				= url_string_xsl($_GET['Vencimento'],'url',false);
	$localIdStatus					= $_GET['IdStatus'];
	$localIdContaReceber			= $_GET['IdContaReceber'];
	$Limit							= $_GET['Limit'];
	$local_IdContaEventual			= $_GET['IdContaEventual'];
	$local_IdPessoa					= $_GET['IdPessoa'];
	$localCampo						= $_GET['Campo'];
	$localCampoValor				= $_GET['Valor'];
	
	
	if($localOrdem == ''){					$localOrdem = "IdContaEventual";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = 'descending';	}	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
	LimitVisualizacao('xsl');
	
	$ImgOrdernarASC = "<img src='../../img/estrutura_sistema/seta_$localOrdemDirecao.gif' alt='Ordenado por' />";
	
	header ("content-type: text/xsl");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!--xsl:decimal-format name="euro" decimal-separator="," /-->
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
		<script type = 'text/javascript' src = 'js/conta_eventual.js'></script>
		<script type = 'text/javascript' src = 'js/conta_eventual_busca_pessoa_aproximada.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(28)?>')">
		<? include('filtro_conta_eventual.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdContaEventual','number')"><?=dicionario(141)?> <?=compara($localOrdem,"IdContaEventual",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome','text')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoContaEventual','text')"><?=dicionario(386)?> <?=compara($localOrdem,"DescricaoContaEventual",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')"><?=dicionario(285)?>. <?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('FormaCobranca','text')"><?=dicionario(406)?>. <?=compara($localOrdem,"FormaCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QtdParcela','number')"><?=dicionario(407)?> <?=compara($localOrdem,"QtdParcela",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Vencimento','number')"><?=dicionario(202)?> <?=compara($localOrdem,"Vencimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotal','number')"><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorTotal",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status','text')"><?=dicionario(140)?> <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContaEventual"/></xsl:attribute>
					<td class='sequencial'  style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_eventual.php?IdContaEventual=<xsl:value-of select="IdContaEventual"/></xsl:attribute>
							<xsl:value-of select="IdContaEventual"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_eventual.php?IdContaEventual=<xsl:value-of select="IdContaEventual"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_eventual.php?IdContaEventual=<xsl:value-of select="IdContaEventual"/></xsl:attribute>
							<xsl:value-of select="DescricaoContaEventual"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_eventual.php?IdContaEventual=<xsl:value-of select="IdContaEventual"/></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_eventual.php?IdContaEventual=<xsl:value-of select="IdContaEventual"/></xsl:attribute>
							<xsl:value-of select="FormaCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_eventual.php?IdContaEventual=<xsl:value-of select="IdContaEventual"/></xsl:attribute>
							<xsl:value-of select="QtdParcela"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_eventual.php?IdContaEventual=<xsl:value-of select="IdContaEventual"/></xsl:attribute>
							<xsl:value-of select="VencimentoTemp"/>
						</xsl:element>
					</td>
					
					<td class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_eventual.php?IdContaEventual=<xsl:value-of select="IdContaEventual"/></xsl:attribute>
							<xsl:value-of select="format-number(ValorTotal, '0,00', 'euro')"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_eventual.php?IdContaEventual=<xsl:value-of select="IdContaEventual"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdContaEventual"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='7' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
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
	
	addParmUrl("marLancamentoFinanceiro","IdContaEventual",'<?=$local_IdContaEventual?>');
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContasReceber","IdContaEventual",'<?=$local_IdContaEventual?>',true);
	addParmUrl("marContrato","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContratoNovo","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marReenvioMensagem","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventual","IdContaEventual",'<?=$local_IdContaEventual?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$local_IdPessoa?>');
</script>
</xsl:template>
</xsl:stylesheet>
