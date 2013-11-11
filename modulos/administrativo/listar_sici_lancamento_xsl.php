<?
	$localModulo		= 1;
	$localOperacao		= 159;
	$localSuboperacao	= "R";
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");

	set_time_limit(0);
	ini_set("memory_limit",getParametroSistema(138, 1));
	
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localPeriodoApuracao		= url_string_xsl($_GET['PeriodoApuracao'],'');
	$localDescricaoServico		= url_string_xsl($_GET['DescricaoServico'],'');
	$localNumeroNF				= url_string_xsl($_GET['NumeroNF'],'');
	$localDataNFInicial			= url_string_xsl($_GET['DataNFInicial'],'');
	$localDataNFFinal			= url_string_xsl($_GET['DataNFFinal'],'');
	$Limit						= $_GET['Limit'];
	
	if($localOrdem == ""){
		$localOrdem = "NumeroNF";
	}
	
	if($localOrdemDirecao == ""){
		#$localOrdemDirecao = getCodigoInterno(7,6);
		$localOrdemDirecao = "ascending";
	}
	
	if($localTipoDado == ""){
		$localTipoDado = "number";
	}
	
	LimitVisualizacao("xsl");	
	
	$ImgOrdernarASC = "<img src='../../img/estrutura_sistema/seta_$localOrdemDirecao.gif' alt='Ordenado por' />";
	
	header("content-type: text/xsl");
	
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
		<script type='text/javascript' src='./js/sici.js'></script>
	</head>
	<body onLoad="ativaNome('SICI Lançamentos')">
		<? include('filtro_sici_lancamento.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar' style='width:80px;'>
						<a href="javascript:filtro_ordenar('PeriodoApuracao','number')">Apuração <?=compara($localOrdem,"PeriodoApuracao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdLoja','number')">Loja <?=compara($localOrdem,"IdLoja",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdContrato','number')">CO <?=compara($localOrdem,"IdContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdContaReceber','number')">CR <?=compara($localOrdem,"IdContaReceber",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdLancamentoFinanceiro','number')">LF <?=compara($localOrdem,"IdLancamentoFinanceiro",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServico','text')">Descrição do Serviço <?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('NumeroNF','number')">Número NF <?=compara($localOrdem,"NumeroNF",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataNF','number')">Data NF <?=compara($localOrdem,"DataNF",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Valor','number')">Valor (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorDescontoAConceber','number')">Valor Desc. (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorDescontoAConceber",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorFinal','number')">Valor Final (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorFinal",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="PeriodoApuracaoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="IdLoja"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="IdContrato"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="IdContaReceber"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="IdLancamentoFinanceiro"/>
						</xsl:element>
					</td>
					<td>
						<xsl:choose>
							<xsl:when test="PeriodoApuracao = ''">
								<xsl:value-of select="DescricaoServico"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
									<xsl:value-of select="DescricaoServico"/>
								</xsl:element>
							</xsl:otherwise>
						</xsl:choose>
					</td>
					<td>
						<xsl:choose>
							<xsl:when test="PeriodoApuracao = ''">
								<xsl:value-of select="NumeroModeloNF"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
									<xsl:value-of select="NumeroModeloNF"/>
								</xsl:element>
							</xsl:otherwise>
						</xsl:choose>
					</td>
					<td>
						<xsl:choose>
							<xsl:when test="PeriodoApuracao = ''">
								<xsl:value-of select="DataNFTemp"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
									<xsl:value-of select="DataNFTemp"/>
								</xsl:element>
							</xsl:otherwise>
						</xsl:choose>
					</td>
					<td class='valor'>
						<xsl:choose>
							<xsl:when test="PeriodoApuracao = ''">
								<xsl:value-of select='format-number(Valor,"0,00","euro")' />
							</xsl:when>
							<xsl:otherwise>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
									<xsl:value-of select='format-number(Valor,"0,00","euro")' />
								</xsl:element>
							</xsl:otherwise>
						</xsl:choose>
					</td>
					<td class='valor'>
						<xsl:choose>
							<xsl:when test="PeriodoApuracao = ''">
								<xsl:value-of select='format-number(ValorDescontoAConceber,"0,00","euro")' />
							</xsl:when>
							<xsl:otherwise>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
									<xsl:value-of select='format-number(ValorDescontoAConceber,"0,00","euro")' />
								</xsl:element>
							</xsl:otherwise>
						</xsl:choose>
					</td>
					<td class='valor'>
						<xsl:choose>
							<xsl:when test="PeriodoApuracao = ''">
								<xsl:value-of select='format-number(ValorFinal,"0,00","euro")' />
							</xsl:when>
							<xsl:otherwise>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_sici.php?PeriodoApuracao=<xsl:value-of select="PeriodoApuracaoTemp"/></xsl:attribute>
									<xsl:value-of select='format-number(ValorFinal,"0,00","euro")' />
								</xsl:element>
							</xsl:otherwise>
						</xsl:choose>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="title">Excluir?</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='8' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorDescontoAConceber),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorFinal),"0,00","euro")' /></td>
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
		<script type="text/javascript">
		<!--
			verificaAcao();
			tableMultColor('tableListar', document.filtro.corRegRand.value);
			-->
		</script>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
</xsl:template>
</xsl:stylesheet>