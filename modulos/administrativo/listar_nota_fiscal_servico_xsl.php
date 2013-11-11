<?php 
	$localModulo		= 1;
	$localOperacao		= 203;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'url',false);
	$localIdNotaFiscal				= $_GET['IdNotaFiscal'];
	$localIdNotaFiscalLayout		= $_GET['IdNotaFiscalLayout'];
	$localAbrirRegistro				= $_GET['AbrirRegistro'];
	$localDataRetorno				= $_GET['DataRetorno'];
	$localDataInicio				= $_GET['DataInicio'];
	$localDataTermino				= $_GET['DataTermino'];
	$localIdStatus					= $_GET['IdStatus'];
	$localIdServico					= $_GET['IdServico'];
	$localNumeroDocumento			= $_GET['NumeroDocumento'];
	$localIdContaReceber			= $_GET['IdContaReceber'];
	$localIdLancamentoFinanceiro	= $_GET['IdLancamentoFinanceiro'];
	$localMesVencimento				= $_GET['MesVencimento'];
	$localMesPagamento				= $_GET['MesPagamento'];
	$localIdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
	$localBoleto					= $_GET['Boleto'];
	$localLimit						= $_GET['Limit'];
	$localTipoPessoa				= $_GET['TipoPessoa'];
	
	if($localOrdem == ''){
		$localOrdem = "DataEmissao";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = "descending";
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'number';
	}
	
	if($localAbrirRegistro == ""){
		$session = $_SESSION['filtro_abrir_registro'];
		
		switch($session){
			case 1:
				$session_target = "_self";
				break;
			case 2:
				$session_target = "_blank";
				break;
		}
	}
	
	switch($localAbrirRegistro){
		case 1:
		$target = "_self";
		break;
		case 2:
		$target = "_blank";
		break;
		default:
		$target = $session_target;
		break;
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
		<script type='text/javascript' src='js/nota_fiscal_servico.js'></script>
	</head>
	<body onLoad="ativaNome('<?php echo dicionario(1003); ?>')">
		<?php include('filtro_nota_fiscal_servico.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<!-- Id -->
						<a href="javascript:filtro_ordenar('IdNotaFiscal','number')"><?php echo dicionario(141)." ".compara($localOrdem,"IdNotaFiscal",$ImgOrdernarASC,''); ?></a>
					</td>
					<td>
						<!-- Nome Pessoa -->
						<a href="javascript:filtro_ordenar('Nome','text')"><?php echo visualizarBuscaPessoa(getCodigoInterno(3,24))." ".compara($localOrdem,"Nome",$ImgOrdernarASC,''); ?></a>
					</td>
					<td>
						<!-- Nº Conta R. -->
						<a href="javascript:filtro_ordenar('IdContaReceber','number')"><?php echo dicionario(1004)." ".compara($localOrdem,"IdContaReceber",$ImgOrdernarASC,''); ?></a>
					</td>
					<td>
						<!-- Lanç. Fin. -->
						<a href="javascript:filtro_ordenar('IdLancamentoFinanceiro','number')"><?php echo dicionario(1008)." ".compara($localOrdem,"IdLancamentoFinanceiro",$ImgOrdernarASC,''); ?></a>
					</td>
					<td>
						<!-- Nº NF -->
						<a href="javascript:filtro_ordenar('NumeroNF','number')"><?php echo dicionario(760)." ".compara($localOrdem,"NumeroNF",$ImgOrdernarASC,''); ?></a>
					</td>			
					<td>
						<!-- Local Cob. -->
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')"><?php echo dicionario(290)." ".compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,''); ?></a>
					</td>			
					<td>
						<!-- Vencimento -->
						<a href="javascript:filtro_ordenar('DataVencimento','number')"><?php echo dicionario(229)." ".compara($localOrdem,"DataVencimento",$ImgOrdernarASC,''); ?></a>
					</td>
					<td>
						<!-- Data Emissão -->
						<a href="javascript:filtro_ordenar('DataEmissao','number')"><?php echo dicionario(1005)." ".compara($localOrdem,"DataEmissao",$ImgOrdernarASC,''); ?></a>
					</td>
					<td class='valor'>
						<!-- Valor Lanç. Fin. (R$) -->
						<a href="javascript:filtro_ordenar('ValorLancamentoFinanceiro','number')"><?php echo dicionario(1009)." (".getParametroSistema(5,1).") ".compara($localOrdem,"ValorLancamentoFinanceiro",$ImgOrdernarASC,''); ?></a>
					</td>
					<td class='valor'>
						<!-- Base Calculo (R$) -->
						<a href="javascript:filtro_ordenar('ValorBaseCalculoICMS','number')"><?php echo dicionario(1010)." (".getParametroSistema(5,1).") ".compara($localOrdem,"ValorBaseCalculoICMS",$ImgOrdernarASC,''); ?></a>
					</td>
					<td class='valor'>
						<!-- Valor (R$)	 -->
						<a href="javascript:filtro_ordenar('Valor','number')"><?php echo dicionario(204)." (".getParametroSistema(5,1).") ".compara($localOrdem,"Valor",$ImgOrdernarASC,''); ?></a>
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
					<xsl:attribute name="accessKey"><xsl:value-of select="IdNotaFiscal"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>						
						<xsl:value-of select="IdNotaFiscal"/>						
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>						
						<xsl:value-of select="Nome"/>						
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/> </xsl:attribute><xsl:attribute name="target"><?=$target?></xsl:attribute>
							<xsl:value-of select="IdContaReceber"/>
						</xsl:element>						
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/> </xsl:attribute><xsl:attribute name="target"><?=$target?></xsl:attribute>
							<xsl:value-of select="IdLancamentoFinanceiro"/>
						</xsl:element>						
					</xsl:element>
					<xsl:element name='td'>
						<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>								
						<xsl:value-of select='NumeroNF'/>							
					</xsl:element>					
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>					
						<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
					</xsl:element>				
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>						
						<xsl:value-of select="DataVencimentoTemp"/>						
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>						
						<xsl:value-of select="DataEmissaoTemp"/>						
					</xsl:element>	
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>					
						<xsl:value-of select='format-number(ValorLancamentoFinanceiro,"0,00","euro")'/>				
					</xsl:element>	
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>					
						<xsl:value-of select='format-number(ValorBaseCalculoICMS,"0,00","euro")'/>				
					</xsl:element>	
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>						
						<xsl:value-of select='format-number(Valor,"0,00","euro")'/>						
					</xsl:element>	
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:if test="IdStatus != 0">
								<xsl:attribute name="href"><xsl:value-of select="Link"/></xsl:attribute>
							</xsl:if>
							<xsl:attribute name="target"><xsl:value-of select="Target"/></xsl:attribute>
							<xsl:value-of select="MsgLink"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">bt_lista</xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
							<xsl:attribute name="alt">Cancelar?</xsl:attribute>
							<xsl:if test="IdStatus != 0">
								<xsl:attribute name="onClick">
									<xsl:choose>
										<xsl:when test="NotaFiscalTransmitida &gt; 0">
											alert('Cancelamento não permitido!\r\n\r\nEsta nota fiscal já foi transmitida para a SEFAZ.')
										</xsl:when>
										<xsl:otherwise>
											cancelar(<xsl:value-of select="IdNotaFiscal"/>,<xsl:value-of select="IdContaReceber"/>,<xsl:value-of select="IdStatus"/>)
										</xsl:otherwise>
									</xsl:choose>
								</xsl:attribute>
							</xsl:if>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='8'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorLancamentoFinanceiro),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorBaseCalculoICMS),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
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
		<script type='text/javascript'>
			tableMultColor('tableListar',document.filtro.corRegRand.value);
			menu_form = false;
		</script>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
</xsl:template>
</xsl:stylesheet>

