<?
	$localModulo		=	1;
	$localOperacao		=	67;
	$localSuboperacao	=	"R";
		
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
	$localDescricaoParametroServico	= url_string_xsl($_GET['DescricaoParametroServico'],'url',false);
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'url',false);
	$localValorParametroServico		= url_string_xsl($_GET['ValorParametroServico'],'url',false);
	$localIdStatus					= url_string_xsl($_GET['IdStatus'],'');
	$localUsuario					= url_string_xsl($_GET['Usuario'],'');
	$localDataInicio				= $_GET['DataInicio'];
	$localDataTermino				= $_GET['DataTermino'];
	$localCancelado					= $_GET['Cancelado'];
	$localSoma						= $_GET['Soma'];
	$localIdCidade					= $_GET['IdCidade'];
	$localIdEstado					= $_GET['IdEstado'];
	$localBairro					= url_string_xsl($_GET['Bairro'],'url',false);
	$localEndereco					= url_string_xsl($_GET['Endereco'],'url',false);
	$Limit							= $_GET['Limit'];
	$local_IdPessoa					= $_GET['IdPessoa'];
	$local_IdContrato				= $_GET['IdContrato'];
	$localIdServico					= $_GET['IdServico'];
	
	if($acesso == '')	$acesso = 'filtro_contrato_sem_cobranca.php';

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
		<script type = 'text/javascript' src = 'js/contrato_sem_cobranca.js'></script>
		<script type = 'text/javascript' src = 'js/contrato.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(295)?>')">
		<? include("filtro_contrato_sem_cobranca.php"); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' id='tableListar'  cellspacing='0'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdContrato','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServico')"><?=dicionario(223)?><?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('TipoContrato')"><?=dicionario(82)?> <?=compara($localOrdem,"TipoContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataInicio','number')"><?=dicionario(283)?><?=compara($localOrdem,"DataInicio",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataTermino','number')"><?=dicionario(284)?><?=compara($localOrdem,"DataTermino",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')"><?=dicionario(285)?>.<?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Valor','number')"><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorDesconto','number')"><?=dicionario(286)?>. (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorDesconto",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorFinal','number')"><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorFinal",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status','text','DataTemporaria','number')"><?=dicionario(140)?><?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
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
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="IdContrato"/>
						</xsl:element>
					</td>
					<td>						
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>
					<td>						
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="DescricaoServico"/>
						</xsl:element>
					</td>
					<td>						
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="TipoContrato"/>
						</xsl:element>
					</td>
					<td>						
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="DataInicioTemp"/>
						</xsl:element>
					</td>							
					<td>						
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="DataTerminoTemp"/>
						</xsl:element>
					</td>	
					<td>						
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
						</xsl:element>
					</td>
					<td class='valor'>						
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="ValorTemp"/>
						</xsl:element>
					</td>
					<td class='valor'>						
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="ValorDescontoTemp"/>
						</xsl:element>
					</td>						
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="ValorFinalTemp"/>
						</xsl:element>
					</td>
					<td>						
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="StatusDesc"/>
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
					<td colspan='2' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
					<td />
					<td />
					<td />
					<td />
					<td />
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorSoma),"0,00","euro")' /></td>
					<td class='valor' id='tableListarDesconto'><xsl:value-of select='format-number(sum(db/reg/ValorDescontoSoma),"0,00","euro")' /></td>
					<td class='valor' id='tableListarFinal'><xsl:value-of select='format-number(sum(db/reg/ValorFinalSoma),"0,00","euro")' /></td>
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
