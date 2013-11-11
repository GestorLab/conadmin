<?
	$localModulo		=	1;
	$localOperacao		=	175;
	$localSuboperacao	=	"R";
	
	$localTituloOperacao	= "Contratos";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localOrdem2					= $_GET['Ordem2'];
	$localOrdemDirecao2				= $_GET['OrdemDirecao2'];
	$localTipoDado2					= $_GET['TipoDado2'];
	$localPessoa					= url_string_xsl($_GET['Pessoa'],'url',false);
	$localDescricaoParametroServico	= url_string_xsl($_GET['DescricaoParametroServico'],'');
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'url',false);
	$localValorParametroServico		= url_string_xsl($_GET['ValorParametroServico'],'url',false);
	$localIdStatus					= url_string_xsl($_GET['IdStatus'],'');
	$localCancelado					= $_GET['Cancelado'];
	$localSoma						= $_GET['Soma'];
	$localDataInicio				= $_GET['DataInicio'];
	$localDataTermino				= $_GET['DataTermino'];
	$localTipoContrato				= $_GET['TipoContrato'];
	$localIdServico					= $_GET['IdServico'];
	$localIdPessoa					= $_GET['IdPessoa'];
	$localIdContrato				= $_GET['IdContrato'];
	$Limit							= $_GET['Limit'];
	
	if($acesso == '')	$acesso = 'filtro_contrato.php';

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
		<script type = 'text/javascript' src = 'js/contrato_busca_pessoa_aproximada.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(360)?>')">
		<div id='carregando'><?=dicionario(17)?></div>
		<? include("filtro_contrato_servico.php"); ?>
		<div id='conteudo'>
			<table class='tableListar' id='tableListar'  cellspacing='0'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtroOrdenar('IdContrato','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('DescricaoServico')"><?=dicionario(361)?><?=compara($localOrdem,"IdServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('DescricaoServico')"><?=dicionario(223)?> <?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('DiaCobranca','number')"><?=dicionario(293)?>. <?=compara($localOrdem,"DiaCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('AbreviacaoNomeLocalCobranca','text')"><?=dicionario(362)?>.<?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('ValorDesconto','number')"><?=dicionario(286)?>.(<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorDesconto",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('Valor','number')"><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('Status','text')"><?=dicionario(140)?><?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>					
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<?
					if($localOrdemDirecao2!="" && $localOrdem2!="" && $localTipoDado2!=""){
						echo"<xsl:sort order='$localOrdemDirecao2' select='$localOrdem2' data-type='$localTipoDado2' />";
					}
				?>
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContrato"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="IdContrato"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="IdServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="DescricaoServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="DiaCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="format-number(ValorDesconto,'0,00','euro')"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="ValorTemp"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>				
					<td class='bt_lista'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="Img"/></xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdContrato"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
					<td colspan='1' id='tableListarTotal' class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorDesconto),"0,00","euro")' /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorSoma),"0,00","euro")' /></td>
					<td colspan='2'/>
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
	addParmUrl("marReenvioMensagem","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContaEventual","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServicoNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServicoNovo","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marOrdemServico","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marOrdemServico","IdPessoa",'<?=$localIdPessoa?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
	
	function filtroOrdenar(valor, typeDate, valor2, typeDate2){
		document.filtro.IdPessoa.value		= '<?=$localIdPessoa?>';
		document.filtro.IdContrato.value	= '<?=$localIdContrato?>';
		document.filtro.IdServico.value		= '<?=$localIdServico?>';
		filtro_ordenar(valor, typeDate, valor2, typeDate2);

	}
</script>
</xsl:template>
</xsl:stylesheet>