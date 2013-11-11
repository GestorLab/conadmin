<?php 
	$localModulo		= 1;
	$localOperacao		= 197;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localIdLoja						= $_SESSION["IdLoja"];
	$localProtocoloConcluido			= $_SESSION["filtro_protocolo_concluido"];
	$localProtocoloOcultarLocalAbertura	= $_SESSION["filtro_protocolo_ocultar_local_abertura"];
	$localFiltro						= $_GET['Filtro'];
	$localOrdem							= $_GET['Ordem'];
	$localOrdemDirecao					= $_GET['OrdemDirecao'];
	$localTipoDado						= $_GET['TipoDado'];
	$localPessoa						= url_string_xsl($_GET['Pessoa'], "URL", false);
	$localAssunto						= url_string_xsl($_GET['Assunto'], "URL", false);
	$localIdProtocoloTipo				= $_GET['IdProtocoloTipo'];
	$localProtocoloExpirado				= $_GET['ProtocoloExpirado'];
	$localIdGrupoUsuario				= $_GET['IdGrupoUsuario'];
	$localLoginResponsavel				= url_string_xsl($_GET['LoginResponsavel'], "URL", false);
	$localIdGrupoAlteracao				= $_GET['IdGrupoAlteracao'];
	$localLoginAlteracao				= url_string_xsl($_GET['LoginAlteracao'], "URL", false);
	$localDataInicio					= url_string_xsl($_GET['DataInicio'], "URL", false);
	$localDataFim						= url_string_xsl($_GET['DataFim'], "URL", false);
	$localLocalAbertura					= $_GET['LocalAbertura'];
	$localIdStatus						= $_GET['IdStatus'];
	$Limit								= $_GET['Limit'];
	
	if($localOrdem == ''){
		$localOrdem = "Assunto";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
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
		<script type='text/javascript' src='js/protocolo_usuario_atendimento.js'></script>
	</head>
	<body onLoad="ativaNome('<?php echo dicionario(922); ?>')">
		<?php include('filtro_protocolo_usuario_atendimento.php'); ?>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'><!-- COLUNA [Id] -->
						<a href="javascript:filtro_ordenar('IdProtocolo','number')"><?php echo dicionario(141)." ".compara($localOrdem,"IdProtocolo",$ImgOrdernarASC,''); ?></a>
					</td>
					<td><!-- COLUNA [Nome Pessoa] -->
						<a href="javascript:filtro_ordenar('Nome','text')"><?php echo dicionario(85)." ".compara($localOrdem,"Nome",$ImgOrdernarASC,''); ?></a>
					</td>
					<td><!-- COLUNA [Tipo Protocolo] -->
						<a href="javascript:filtro_ordenar('DescricaoProtocoloTipo','text')"><?php echo dicionario(929)." ".compara($localOrdem,"DescricaoProtocoloTipo",$ImgOrdernarASC,''); ?></a>
					</td>
					<?php
						if($localProtocoloOcultarLocalAbertura != "1"){
							echo "
							<td><!-- COLUNA [Local Aber.] -->
								<a href=\"javascript:filtro_ordenar('LocalAbertura','text')\">".dicionario(924)." ".compara($localOrdem,"LocalAbertura",$ImgOrdernarASC,'')."</a>
							</td>";
						}
					?>
					<td><!-- COLUNA [Assunto] -->
						<a href="javascript:filtro_ordenar('Assunto','text')"><?php echo dicionario(719)." ".compara($localOrdem,"Assunto",$ImgOrdernarASC,'');?></a>
					</td>
					<td><!-- COLUNA [Responsável] -->
						<a href="javascript:filtro_ordenar('LoginResponsavel','text')"><?php echo dicionario(488)." ".compara($localOrdem,"LoginResponsavel",$ImgOrdernarASC,'')?></a>
					</td>
					<td><!-- COLUNA [Data de Aber.] -->
						<a href="javascript:filtro_ordenar('DataCriacao','number')"><?php echo dicionario(925)." ".compara($localOrdem,"DataCriacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td><!-- COLUNA [Previsão] -->
						<a href="javascript:filtro_ordenar('PrevisaoEtapa','text')"><?php echo dicionario(926)." ".compara($localOrdem,"PrevisaoEtapa",$ImgOrdernarASC,'')?></a>
					</td>
					<td><!-- COLUNA [Status] -->
						<a href="javascript:filtro_ordenar('Status','text')"><?php echo dicionario(140)." ".compara($localOrdem,"IdStatus",$ImgOrdernarASC,'')?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?php echo $localOrdemDirecao; ?>" select="<?php echo $localOrdem; ?>" data-type="<?php echo $localTipoDado; ?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdProtocolo"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="IdProtocolo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="DescricaoProtocoloTipo"/>
						</xsl:element>
					</td>
					<?php
						if($localProtocoloOcultarLocalAbertura != "1"){
							echo "
							<td>
								<xsl:attribute name=\"bgcolor\"><xsl:value-of select=\"CorReg\"/></xsl:attribute>
								<xsl:element name=\"a\">
									<xsl:attribute name=\"href\">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select=\"IdProtocolo\"/></xsl:attribute>
									<xsl:value-of select=\"LocalAbertura\"/>
								</xsl:element>
							</td>";
						}
					?>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="Assunto"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="LoginResponsavel"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="DataCriacaoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="PrevisaoEtapaTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="CorReg"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_protocolo.php?IdProtocolo=<xsl:value-of select="IdProtocolo"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='8' id='tableListarTotal'><?php echo dicionario(927); ?> <xsl:value-of select="count(db/reg)" /></td>
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
			verificaAcao();
			tableMultColor('tableListar', document.filtro.corRegRand.value);
		</script>
	</body>	
</html>
</xsl:template>
</xsl:stylesheet>