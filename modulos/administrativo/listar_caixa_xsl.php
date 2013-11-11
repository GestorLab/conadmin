<?php
	$localModulo		= 1;
	$localOperacao		= 178;
	$localSuboperacao	= "R";
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localPessoa				= url_string_xsl($_GET['Pessoa'],'URL',false);
	$localData					= $_GET['Data'];
	$localDataInicio			= $_GET['DataInicio'];
	$localDataFim				= $_GET['DataFim'];
	$localIdStatus				= $_GET['IdStatus'];
	$Limit						= $_GET['Limit'];
	
	if($localOrdem == ''){
		$localOrdem = "DataAbertura";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'number';
	}
	
	LimitVisualizacao('xsl');	
	
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
		<script type='text/javascript' src='./js/caixa.js'></script>
		<script type='text/javascript' src='js/pessoa_busca_pessoa_aproximada.js'></script>
	</head>
	<body onLoad="ativaNome('<?php echo dicionario(171); ?>')">
		<?php include('filtro_caixa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdCaixa','number')"><?php echo dicionario(141)." ".compara($localOrdem,"IdCaixa",$ImgOrdernarASC,''); ?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('NomeResponsavel','text')"><?php echo dicionario(488)." ".compara($localOrdem,"NomeResponsavel",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorAberturaTotal','number')"><?php echo dicionario(952)." (".getParametroSistema(5,1).")".compara($localOrdem, "ValorAberturaTotal", $ImgOrdernarASC, ''); ?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorAtualTotal','number')"><?php echo dicionario(964)." (".getParametroSistema(5,1).")".compara($localOrdem, "ValorAtualTotal", $ImgOrdernarASC, ''); ?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('LoginAbertura','text')"><?php echo dicionario(949)." ".compara($localOrdem,"LoginAbertura",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataAbertura','number')"><?php echo dicionario(388)." ".compara($localOrdem,"DataAbertura",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataFechamento','number')"><?php echo dicionario(951)." ".compara($localOrdem,"DataFechamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status','text')"><?php echo dicionario(140)." ".compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdCaixa"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="class">sequencial</xsl:attribute>
						<xsl:attribute name="style">width:25px;</xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
							<xsl:value-of select="IdCaixa"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
							<xsl:value-of select="NomeResponsavel"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorAberturaTotal,"0,00","euro")'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorAtualTotal,"0,00","euro")'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
							<xsl:value-of select="LoginAbertura"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
							<xsl:value-of select="DataAberturaTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
							<xsl:value-of select="DataFechamentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="class">bt_lista</xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:choose>
								<xsl:when test="LoginConclusao = ''">
									<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
									<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdProtocolo"/>)</xsl:attribute>
								</xsl:when>
								<xsl:otherwise>
									<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
								</xsl:otherwise>
							</xsl:choose>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="title">Excluir?</xsl:attribute>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarTitle</xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="colspan">2</xsl:attribute>
						<xsl:attribute name="id">tableListarTotal</xsl:attribute>
						Total: <xsl:value-of select="count(db/reg)" />
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:value-of select='format-number(sum(db/reg/ValorAberturaTotal),"0,00","euro")' />
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:value-of select='format-number(sum(db/reg/ValorAtualTotal),"0,00","euro")' />
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="colspan">5</xsl:attribute>
					</xsl:element>
				</xsl:element>
			</table>
		</div>
		<table>
			<tr>
				<td class='find' />
				<td><h1 id='helpText' name='helpText' /></td>
			</tr>
		</table>
		<script type="text/javascript">
			verificaAcao();
			tableMultColor('tableListar', document.filtro.corRegRand.value);
		</script>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
</xsl:template>
</xsl:stylesheet>