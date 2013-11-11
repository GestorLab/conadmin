<?
	$localModulo		=	1;
	$localOperacao		=	110;
	$localSuboperacao	=	"R";

	$localRelatorio			= "block";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	
	$localMesReferencia			= url_string_xsl($_GET['MesReferencia'],'');
	$localIdNotaFiscalLayout	= $_GET['IdNotaFiscalLayout'];
	
	$Limit						= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "Status";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/nf_2_via_eletronica_remessa.js'></script>
	</head>
	<body  onLoad="ativaNome('Nota Fiscal 2ª Via Eletronica (Remessa)')">
		<? include('filtro_nf_2_via_eletronica_remessa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtro_ordenar('IdNotaFiscalLayout','text')">NF Layout<?=compara($localOrdem,"IdNotaFiscalLayout",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('MesReferenciaTemp','text')">P. Apuração. <?=compara($localOrdem,"MesReferenciaTemp",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('QtdNF','number')">Qtd. NF <?=compara($localOrdem,"QtdNF",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataResponsavel','text')">Data <?=compara($localOrdem,"DataResponsavel",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotalBaseCalculo','number')">Valor B. Cálculo (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorTotalBaseCalculo",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotalICMS','number')">ICMS (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorTotalICMS",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotal','number')">Valor Total (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorTotal",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('StatusMestreDescricao','Text')">Arq. Mestre <?=compara($localOrdem,"StatusMestre",$ImgOrdernarASC,'')?></a>
					</td>					
					<td>
						<a href="javascript:filtro_ordenar('Status','Text')">Status <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>					
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdNotaFiscalLayout"/><xsl:value-of select="MesReferencia"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
							<xsl:value-of select="DescricaoNotaFiscalLayout"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
							<xsl:value-of select="MesReferencia"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
							<xsl:value-of select="QtdNF"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
							<xsl:value-of select='DataResponsavelTemp'/>
						</xsl:element>
					</td>					
					<td class='valor'>
						<xsl:if test="ValorTotalBaseCalculoTemp != ''">
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
								<xsl:value-of select='format-number(ValorTotalBaseCalculoTemp,"0,00","euro")'/>
							</xsl:element>
						</xsl:if>
						<xsl:if test="ValorTotalBaseCalculoTemp = ''">
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
								<xsl:value-of select='ValorTotalBaseCalculoTemp'/>
							</xsl:element>
						</xsl:if>
					</td>
					<td class='valor'>
						<xsl:if test="ValorTotalICMSTemp != ''">
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
								<xsl:value-of select='format-number(ValorTotalICMSTemp,"0,00","euro")'/>
							</xsl:element>
						</xsl:if>
						<xsl:if test="ValorTotalICMSTemp = ''">
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
								<xsl:value-of select='ValorTotalICMSTemp'/>
							</xsl:element>
						</xsl:if>						 
					</td>
					<td class='valor'>
						<xsl:if test="ValorTotalTemp != ''">
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
								<xsl:value-of select='format-number(ValorTotalTemp,"0,00","euro")'/>
							</xsl:element>
						</xsl:if>
						<xsl:if test="ValorTotalTemp = ''">
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
								<xsl:value-of select='ValorTotalTemp'/>
							</xsl:element>
						</xsl:if>						 
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
							<xsl:value-of select='StatusMestreDescricao'/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout=<xsl:value-of select="IdNotaFiscalLayout"/>&amp;MesReferencia=<xsl:value-of select="MesReferencia"/>&amp;IdStatusArquivoMestre=<xsl:value-of select="StatusMestre"/></xsl:attribute>
							<xsl:value-of select='Status'/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdNotaFiscalLayout"/>,'<xsl:value-of select="MesReferencia"/>',<xsl:value-of select="IdStatus"/>,'<xsl:value-of select="IdStatusArquivoMestre"/>')</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(db/valortotal/TotalValorTotal,"0,00","euro")' /></td>
					<td />
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
</script>
</xsl:template>
</xsl:stylesheet>