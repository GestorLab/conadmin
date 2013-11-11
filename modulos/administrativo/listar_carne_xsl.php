<?
	$localModulo		=	1;
	$localOperacao		=	45;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localErro						= $_GET['Erro'];
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'URL',false);
	$localDataLancamento			= url_string_xsl($_GET['DataLancamento'],'');
	$localIdContaReceber			= url_string_xsl($_GET['IdContaReceber'],'');
	$localIdLocalCobranca			= $_GET['IdLocalCobranca'];
	$localIdStatus					= $_GET['IdStatus'];
	$localIdStatusParcela			= $_GET['IdStatusParcela'];
	$localImprimirContaReceber		= $_GET['ImprimirContaReceber'];
	$localLimit						= $_GET['Limit'];

	if($localOrdem == ''){							$localOrdem = "DataLancamento";		}
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
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type="text/javascript">
		    var $j = jQuery.noConflict();
		</script>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media='print' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/carne.js'></script>
		<script type = 'text/javascript' src = 'js/carne_busca_pessoa_aproximada.js'></script>
	</head>
	<body  onLoad="ativaNome('Carnês')">
		<? include('filtro_carne.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdCarne','number')">Id<?=compara($localOrdem,"IdCarne",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome','text')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?><?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Tipo','text')">Tipo Lanç.<?=compara($localOrdem,"Tipo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataLancamento','number')">Data Lanç.<?=compara($localOrdem,"DataLancamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')">Local Cob. <?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td> 
					<td>
						<a href="javascript:filtro_ordenar('QtdTitulo','number')">Qtd. <?=compara($localOrdem,"QtdTitulo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QtdTituloQuitado','number')">Quitados <?=compara($localOrdem,"QtdTituloQuitado",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QtdTituloEmAberto','number')">Aguardando Pag. <?=compara($localOrdem,"QtdTituloEmAberto",$ImgOrdernarASC,'')?></a>
					</td>
					<td />
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">listar_conta_receber.php?IdCarne=<xsl:value-of select="IdCarne"/>&amp;ImprimirContaReceber=<?=$localImprimirContaReceber?>&amp;IdStatus=<?=$localIdStatusParcela?></xsl:attribute>
							<xsl:value-of select="IdCarne"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">listar_conta_receber.php?IdCarne=<xsl:value-of select="IdCarne"/>&amp;ImprimirContaReceber=<?=$localImprimirContaReceber?>&amp;IdStatus=<?=$localIdStatusParcela?></xsl:attribute>
							<xsl:value-of select="Nome"/>	
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:for-each select="Tipo">
							<xsl:choose>
								<xsl:when test="Valor='CO'">
									<xsl:value-of select="Separado"/>
									<xsl:element name="a">
										<xsl:attribute name="href">listar_contrato.php?IdContaReceber=<xsl:value-of select="IdCarne"/></xsl:attribute>
										<xsl:value-of select="Valor"/>
									</xsl:element>
								</xsl:when>
								<xsl:when test="Valor='OS'">
									<xsl:value-of select="Separado"/>
									<xsl:element name="a">
										<xsl:attribute name="href">listar_ordem_servico.php?IdContaReceber=<xsl:value-of select="IdCarne"/></xsl:attribute>
										<xsl:value-of select="Valor"/>
									</xsl:element>
								</xsl:when>
								<xsl:when test="Valor='EV'">
									<xsl:value-of select="Separado"/>
									<xsl:element name="a">
										<xsl:attribute name="href">listar_conta_eventual.php?IdContaReceber=<xsl:value-of select="IdCarne"/></xsl:attribute>
										<xsl:value-of select="Valor"/>
									</xsl:element>
								</xsl:when>
								<xsl:when test="Valor='EF'">
									<xsl:value-of select="Separado"/>
									<xsl:element name="a">
										<xsl:attribute name="href">listar_conta_receber.php?IdContaReceber=<xsl:value-of select="IdCarne"/></xsl:attribute>
										<xsl:value-of select="Valor"/>
									</xsl:element>
								</xsl:when>
							</xsl:choose>
						</xsl:for-each>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">listar_conta_receber.php?IdCarne=<xsl:value-of select="IdCarne"/>&amp;ImprimirContaReceber=<?=$localImprimirContaReceber?>&amp;IdStatus=<?=$localIdStatusParcela?></xsl:attribute>
							<xsl:value-of select="DataLancamentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">listar_conta_receber.php?IdCarne=<xsl:value-of select="IdCarne"/>&amp;ImprimirContaReceber=<?=$localImprimirContaReceber?>&amp;IdStatus=<?=$localIdStatusParcela?></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">listar_conta_receber.php?IdCarne=<xsl:value-of select="IdCarne"/>&amp;ImprimirContaReceber=<?=$localImprimirContaReceber?>&amp;IdStatus=<?=$localIdStatusParcela?></xsl:attribute>
							<xsl:value-of select="QtdTitulo"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">listar_conta_receber.php?IdCarne=<xsl:value-of select="IdCarne"/>&amp;ImprimirContaReceber=<?=$localImprimirContaReceber?>&amp;IdStatus=<?=$localIdStatusParcela?></xsl:attribute>
							<xsl:value-of select="QtdTituloQuitado"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">listar_conta_receber.php?IdCarne=<xsl:value-of select="IdCarne"/>&amp;ImprimirContaReceber=<?=$localImprimirContaReceber?>&amp;IdStatus=<?=$localIdStatusParcela?></xsl:attribute>
							<xsl:value-of select="QtdTituloEmAberto"/>
						</xsl:element>
					</xsl:element>
					<td style='width:66px'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href"><xsl:value-of select="Link"/></xsl:attribute>
							<xsl:attribute name="target"><xsl:value-of select="Target"/></xsl:attribute>
							<xsl:value-of select="MsgLink"/>
						</xsl:element>
					</td>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">bt_lista</xsl:attribute>
						<xsl:attribute name="style">cursor:pointer</xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
							<xsl:attribute name="alt">Cancelar?</xsl:attribute>
							<xsl:attribute name="onClick">cancelar(<xsl:value-of select="IdCarne"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='3'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td />
					<td />
					<td><xsl:value-of select='sum(db/reg/QtdTitulo)' /></td>
					<td><xsl:value-of select='sum(db/reg/QtdTituloEmAberto)' /></td>
					<td><xsl:value-of select='sum(db/reg/QtdTituloQuitado)' /></td>
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
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	mensagens(<?=$localErro?>);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>

