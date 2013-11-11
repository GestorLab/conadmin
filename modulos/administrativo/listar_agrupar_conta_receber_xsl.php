<?
	$localModulo		= 1;
	$localOperacao		= 17;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'url',false);
	$localCampo						= url_string_xsl($_GET['Campo'],'url',false);
	$localValor						= url_string_xsl($_GET['Valor'],'url',false);
	$localIdLocalCobranca			= $_GET['IdLocalCobranca'];
	$localIdContaReceberAgrupador	= $_GET['IdContaReceberAgrupador'];
	$localLimit						= $_GET['Limit'];

	if($localOrdem == ''){			$localOrdem = "DataLancamento";		}
	if($localOrdemDirecao == ''){	$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){		$localTipoDado = 'number';	}
	
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
		<script type='text/javascript' src='js/conta_receber.js'></script>
		<script type='text/javascript' src='js/agrupar_conta_receber.js'></script>
	</head>
	<body  onLoad="ativaNome('Agrupar Contas a Receber')">
		<? include('filtro_agrupar_conta_receber.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdContaReceberAgrupador','number')">Id <?=compara($localOrdem,"IdContaReceberAgrupador",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome','text')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')">Local Cob. <?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataLancamento','number')">Data Lanç. <?=compara($localOrdem,"DataLancamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QTDParcela','number')">Qtd. <?=compara($localOrdem,"QTDParcela",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QTDParcelaAguardandoPagamento','number')">Aguardando Pag. <?=compara($localOrdem,"QTDParcelaAguardandoPagamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QTDParcelaQuitado','number')">Quitados <?=compara($localOrdem,"QTDParcelaQuitado",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotal','number')">Valor (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorTotal",$ImgOrdernarASC,'')?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContaReceberAgrupador"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agrupar_conta_receber.php?IdContaReceberAgrupador=<xsl:value-of select="IdContaReceberAgrupador"/></xsl:attribute>
							<xsl:value-of select="IdContaReceberAgrupador"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agrupar_conta_receber.php?IdContaReceberAgrupador=<xsl:value-of select="IdContaReceberAgrupador"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agrupar_conta_receber.php?IdContaReceberAgrupador=<xsl:value-of select="IdContaReceberAgrupador"/></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agrupar_conta_receber.php?IdContaReceberAgrupador=<xsl:value-of select="IdContaReceberAgrupador"/></xsl:attribute>
							<xsl:value-of select="DataLancamentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agrupar_conta_receber.php?IdContaReceberAgrupador=<xsl:value-of select="IdContaReceberAgrupador"/></xsl:attribute>
							<xsl:value-of select='QTDParcela'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agrupar_conta_receber.php?IdContaReceberAgrupador=<xsl:value-of select="IdContaReceberAgrupador"/></xsl:attribute>
							<xsl:value-of select='QTDParcelaAguardandoPagamento'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agrupar_conta_receber.php?IdContaReceberAgrupador=<xsl:value-of select="IdContaReceberAgrupador"/></xsl:attribute>
							<xsl:value-of select='QTDParcelaQuitado'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agrupar_conta_receber.php?IdContaReceberAgrupador=<xsl:value-of select="IdContaReceberAgrupador"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorTotal,"0,00","euro")'/>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='2'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td colspan='5' />
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorTotal),"0,00","euro")' /></td>
				</tr>
			</table>
		</div>
		<table>
			<tr>
				<td class='find' />
				<td><h1 id='helpText' name='helpText' /></td>
			</tr>
		</table>
		<script type='text/javascript'>	
			addParmUrl("marContasReceber","IdContaReceberAgrupador",'<?=$localIdContaReceberAgrupador?>');
			addParmUrl("marAgruparContaReceber","IdContaReceberAgrupador",'<?=$localIdContaReceberAgrupador?>');
			
			tableMultColor('tableListar',document.filtro.corRegRand.value);
			menu_form = false;
		</script>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
</xsl:template>
</xsl:stylesheet>