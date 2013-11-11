<?
	$localModulo		=	1;
	$localOperacao		=	3;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localMesReferencia			= url_string_xsl($_GET['MesReferencia'],'');
	$localIdStatus				= $_GET['IdStatus'];
	$localMenorVencimento		= $_GET['MenorVencimento'];
	$localLocalCobranca			= $_GET['IdLocalCobranca'];
	$localLimit					= $_GET['Limit'];
	$localIdProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
	$localIdPessoa				= $_GET['IdPessoa'];
	$localIdContrato			= $_GET['IdContrato'];
	$localValorCampo			= $_GET['ValorCampo'];
	$localCampo					= $_GET['Campo'];
	$localUsuarioCadastro		= $_GET['UsuarioCadastro'];
	$localUsuarioAlteracao		= $_GET['UsuarioAlteracao'];
	$localUsuarioConfirmacao	= $_GET['UsuarioConfirmacao'];

	if($localOrdem == ''){							$localOrdem = "MesReferencia";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/processo_financeiro.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(682)?>')">
		<? include('filtro_processo_financeiro.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdProcessoFinanceiro','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdProcessoFinanceiro",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('MesReferencia','number')"><?=dicionario(196)?> <?=compara($localOrdem,"MesReferencia",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('MesVencimento','number')"><?=dicionario(874)?>. <?=compara($localOrdem,"MesVencimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoLocalCobranca','text')"><?=dicionario(285)?>. <?=compara($localOrdem,"DescricaoLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QtdLancamentosFinanceiros','number')"><?=dicionario(875)?>. <?=compara($localOrdem,"QtdLancamentosFinanceiros",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QtdContasReceber','number')"><?=dicionario(876)?>. <?=compara($localOrdem,"QtdContasReceber",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotal','number')"><?=dicionario(398)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorTotal",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Filtros','text')"><?=dicionario(559)?> <?=compara($localOrdem,"Filtros",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoStatus','text')"><?=dicionario(140)?> <?=compara($localOrdem,"DescricaoStatus",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_processo_financeiro.php?IdProcessoFinanceiro=<xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="IdProcessoFinanceiro"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_processo_financeiro.php?IdProcessoFinanceiro=<xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="MesReferenciaTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_processo_financeiro.php?IdProcessoFinanceiro=<xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="MesVencimentoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_processo_financeiro.php?IdProcessoFinanceiro=<xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_processo_financeiro.php?IdProcessoFinanceiro=<xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="QuantLancamentosFinanceiros"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_processo_financeiro.php?IdProcessoFinanceiro=<xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="QuantContasReceber"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_processo_financeiro.php?IdProcessoFinanceiro=<xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorTotal,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_processo_financeiro.php?IdProcessoFinanceiro=<xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="Filtros"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_processo_financeiro.php?IdProcessoFinanceiro=<xsl:value-of select="IdProcessoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="DescricaoStatus"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdProcessoFinanceiro"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorTotal),"0,00","euro")' /></td>
					<td />
					<td />
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

	addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",'<?=$localIdProcessoFinanceiro?>');
	addParmUrl("marLancamentoFinanceiro","IdProcessoFinanceiro",'<?=$localIdProcessoFinanceiro?>');
	addParmUrl("marContasReceber","IdProcessoFinanceiro",'<?=$localIdProcessoFinanceiro?>');
	addParmUrl("marPessoaFormaCobranca","IdProcessoFinanceiro",'<?=$localIdProcessoFinanceiro?>');

	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>