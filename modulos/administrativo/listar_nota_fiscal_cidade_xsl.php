<?
	$localModulo		=	1;
	$localOperacao		=	115;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'');
	$localIdNotaFiscal				= $_GET['IdNotaFiscal'];
	$localIdNotaFiscalLayout		= $_GET['IdNotaFiscalLayout'];
	$localAbrirRegistro				= $_GET['AbrirRegistro'];
	$localNumeroDocumentoOcultar	= $_GET['NumeroDocumentoOcultar'];
	$localCodigoNFOcultar			= $_GET['CodigoNFOcultar'];
	
	$localDataRetorno				= $_GET['DataRetorno'];
	$localDataInicio				= $_GET['DataInicio'];
	$localDataTermino				= $_GET['DataTermino'];
	$localIdStatus					= $_GET['IdStatus'];
	
	$localPeriodoApuracao			= $_GET['PeriodoApuracao'];
	$localIdServico					= $_GET['IdServico'];
	$localNumeroDocumento			= $_GET['NumeroDocumento'];
	$localIdContaReceber			= $_GET['IdContaReceber'];
	$localIdEstado					= $_GET['IdEstado'];
	$localNomeCidade				= url_string_xsl($_GET['NomeCidade'],'url',false);
	$localTipoPessoa				= $_GET['TipoPessoa'];
	
	$localBoleto					= $_GET['Boleto'];
	
	$localLimit						= $_GET['Limit'];

	if($localOrdem == ''){							$localOrdem = "DataEmissao";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = "descending";	}	
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/nota_fiscal.js'></script>
	</head>
	<body onLoad="ativaNome('Nota Fiscal/Cidade')">
		<? include('filtro_nota_fiscal_cidade.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdNotaFiscal','number')">Id<?=compara($localOrdem,"IdNotaFiscal",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome','text')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?><?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdContaReceber','number')">N� Conta R. <?=compara($localOrdem,"IdContaReceber",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($localNumeroDocumentoOcultar == 2){
							echo "	<td>
										<a href=\"javascript:filtro_ordenar('NumeroDocumento','number')\">N� Doc. ".compara($localOrdem,"NumeroDocumento",$ImgOrdernarASC,'')."</a>
									</td>";
						}
					?>					
					<td>
						<a href="javascript:filtro_ordenar('NumeroNF','number')">N� NF <?=compara($localOrdem,"NumeroNF",$ImgOrdernarASC,'')?></a>
					</td>			
					<td>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')">Local Cob. <?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>			
					<td>
						<a href="javascript:filtro_ordenar('DataVencimento','number')">Vencimento <?=compara($localOrdem,"DataVencimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataEmissao','number')">Data Emiss�o <?=compara($localOrdem,"DataEmissao",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorBaseCalculoICMS','number')">Base Calculo (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorBaseCalculoICMS",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Valor','number')">Valor (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($localCodigoNFOcultar == 2){
							echo "<td>
									<a href=\"javascript:filtro_ordenar('CodigoNF','text')\">C�digo NF ".compara($localOrdem,"CodigoNF",$ImgOrdernarASC,'')."</a>
								</td>";
						}
					?>
			
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
					<?
						if($localNumeroDocumentoOcultar == 2){
							echo"<xsl:element name=\"td\">
									<xsl:attribute name=\"bgcolor\"><xsl:value-of select=\"Color\"/></xsl:attribute>
									<xsl:value-of select=\"NumeroDocumento\"/>						
								</xsl:element>";
						}
					?>
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
						<xsl:value-of select="ValorBaseCalculoICMS"/>				
					</xsl:element>	
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>						
						<xsl:value-of select='format-number(Valor,"0,00","euro")'/>						
					</xsl:element>			
					<?
						if($localCodigoNFOcultar == 2){
							echo "<xsl:element name=\"td\">
									<xsl:attribute name=\"bgcolor\"><xsl:value-of select=\"Color\"/></xsl:attribute>
									<xsl:value-of select=\"CodigoNF\"/>						
								</xsl:element>";
						}
					?>
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
								<xsl:attribute name="onClick">cancelar(<xsl:value-of select="IdNotaFiscal"/>,<xsl:value-of select="IdContaReceber"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
							</xsl:if>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='2'>Total: <xsl:value-of select="count(db/reg)" /></td>					
					<td />
					<td />
					<td />				
					<td />
					<td />
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorBaseCalculoICMS),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
					<td colspan='5' />
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
	menu_form = false;

</script>
</xsl:template>
</xsl:stylesheet>

