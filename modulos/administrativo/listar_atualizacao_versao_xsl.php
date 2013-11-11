<?
	$localModulo		=	1;
	$localOperacao		=	16;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localDescricaoTipoVigencia		= url_string_xsl($_GET['DescricaoTipoVigencia'],'url',false);
	$localIdContratoTipoVigencia	= $_GET['IdContratoTipoVigencia'];
	$localIsento					= $_GET['Isento'];
	$Limit							= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DescricaoContratoTipoVigencia";		}
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
		<script type = 'text/javascript' src = 'js/tipo_vigencia.js'></script>
	</head>
	<body  onLoad="ativaNome('Atualização Versão')">
		<? include ("filtro_atualizacao_versao.php"); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' cellpadding='0' Id='tableListar' border='0'>			
				<xsl:for-each select="db/reg">
					<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />
					<xsl:element name="tr">
						<xsl:attribute name="class">tableListarTitle</xsl:attribute>
						<td colspan='12'><?=dicionario(1053)?>:</td>						
					</xsl:element>
					<xsl:element name="tr">									
						<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
						<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
						<xsl:attribute name="accessKey"><xsl:value-of select="IdLicenca"/></xsl:attribute>
						<td>						
							<table class='tableListar' cellspacing='0' border='0' style='border-bottom:2px #000 solid; margin-bottom:0px;'>
								<tr>
									<td class='descCampo' width="25%">Licença</td>
									<td class='descCampo'>Data Etapa 0</td>
									<td class='descCampo'>Data Etapa 1</td>
									<td class='descCampo'>Log Update MySQL</td>							
								</tr>							
								<tr>
									<td>
										<xsl:value-of select="IdLicenca"/>
									</td>
									<td>										
										<xsl:value-of select="DataEtapa0"/>
									</td>
									<td>										
										<xsl:value-of select="DataEtapa1"/>							
									</td>
									<td>
										<xsl:value-of select="LogUpdateMySQL"/>									
									</td>
								</tr>
								<tr>
									<td class='descCampo'>Atualização</td>
									<td class='descCampo' width="30%">Data Etapa 2</td>
									<td class='descCampo'>Data Etapa 3</td>
									<td class='descCampo'>Data Termino</td>
								</tr>							
								<tr>
									<td>									
										<xsl:attribute name="style">margin-left: 10px</xsl:attribute>
										<xsl:value-of select="IdAtualizacao"/>								
									</td>
									<td>
										<xsl:value-of select="DataEtapa2"/>
									</td>
									<td>
										<xsl:value-of select="DataEtapa3"/>
									</td>
									<td>
										<xsl:value-of select="DataTermino"/>
									</td>
								</tr>
								<tr>
									<td class='descCampo'>Versão</td>
									<td class='descCampo'>Versão Ant.</td>
									<td class='descCampo'>Desc. Versão</td>
									<td class='descCampo'>Login</td>
								</tr>
								<tr>
									<td>
										<xsl:value-of select="IdVersao"/>
									</td>	
									<td>
										<xsl:value-of select="IdVersaoOld"/>										
									</td>								
									<td>
										<xsl:value-of select="DescricaoVersao"/>										
									</td>
									<td>
										<xsl:value-of select="Login"/>
									</td>
								</tr>
								
							</table>
						</td>
					</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='12' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
	
	addParmUrl("marTipoVigencia","IdContratoTipoVigencia",'<?=$localIdContratoTipoVigencia?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
