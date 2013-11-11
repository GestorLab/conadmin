<?
	$localModulo		=	1;
	$localOperacao		=	161;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localNome					= url_string_xsl($_GET['Nome'],'');
	$localDataInicio			= url_string_xsl($_GET['DataInicio'],'');
	$localDataFim				= url_string_xsl($_GET['DataFim'],'');
	$localCampo					= url_string_xsl($_GET['Campo'],'');
	$localValor					= url_string_xsl($_GET['Valor'],'');
	$localTipo					= $_GET['Tipo'];
	$localSubTipo				= $_GET['SubTipo'];
	$localGrupoResponsavel		= $_GET['GrupoResponsavel'];
	$localUsuarioResponsavel	= $_GET['UsuarioResponsavel'];
	$localAssunto				= $_GET['Assunto'];
	$localIdTicket				= $_GET['IdTicket'];
	$localIdStatus				= $_GET['IdStatus'];
	$localIdLocalAbertura		= $_GET['IdLocalAbertura'];
	$localLimit					= $_GET['Limit'];
	
	$filtro_oculta_local_aber	= $_SESSION["filtro_oculta_local_aber"];
	
	if($localOrdem == ''){			$localOrdem = "IdTicket";	}
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
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media='print' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/help_desk_previsao.js'></script>
	</head>
	<body onLoad="ativaNome('Help Desk/Previsão')">
		<? include('filtro_help_desk_previsao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdTicket','number')">Id<?=compara($localOrdem,"IdTicket",$ImgOrdernarASC,'')?></a>
					</td>
					<td width='10px' />
					<td>
						<a href="javascript:filtro_ordenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?><?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($filtro_oculta_local_aber == 2){
							echo"<td>
						<a href=\"javascript:filtro_ordenar('LocalAbertura')\">Local de Aber.".compara($localOrdem,"LocalAbertura",$ImgOrdernarASC,'')."</a>
					</td>";
						}	
					?>
					<td>
						<a href="javascript:filtro_ordenar('TipoSubTipo')">Tipo/SubTipo<?=compara($localOrdem,"TipoSubTipo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Assunto')">Assunto<?=compara($localOrdem,"Assunto",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Responsavel')">Responsável<?=compara($localOrdem,"Responsavel",$ImgOrdernarASC,'')?></a>
					</td>
					<td width='92px'>
						<a href="javascript:filtro_ordenar('DataHora','number')">Data de Aber.<?=compara($localOrdem,"DataHora",$ImgOrdernarASC,'')?></a>
					</td>
					<td width='72px'>
						<a href="javascript:filtro_ordenar('PrevisaoEtapa')">Previsão<?=compara($localOrdem,"PrevisaoEtapa",$ImgOrdernarASC,'')?></a>
					</td>
					<td width='170px'>
						<a href="javascript:filtro_ordenar('Status')">Status<?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdTicket"/></xsl:attribute>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
							<xsl:value-of select="IdTicket"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="ColorFundoMarcador"/></xsl:attribute>
						<xsl:element name="a">							
							<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
							<xsl:attribute name="style">padding: 0 1px 0 2px; font-size: 10px; color:<xsl:value-of select="CorMarcador"/></xsl:attribute>																
								<xsl:value-of select="Marcador"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
							<xsl:attribute name="onmousemove">quadro_alt(event, this, &quot;<xsl:value-of select="Nome"/>&quot;);</xsl:attribute>
							<xsl:value-of select="NomeTemp"/>
						</xsl:element>
					</td>
					<?
						if($filtro_oculta_local_aber == 2){
							echo"<td>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:element name='a'>
								<xsl:attribute name='href'>cadastro_help_desk.php?IdTicket=<xsl:value-of select='IdTicket'/></xsl:attribute>
								<xsl:value-of select='LocalAbertura'/>
								</xsl:element>
								</td>";
						}	
					?>				
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
							<xsl:attribute name="onmousemove">quadro_alt(event, this, &quot;<xsl:value-of select="TipoSubTipo"/>&quot;);</xsl:attribute>
							<xsl:value-of select="TipoSubTipoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
							<xsl:attribute name="onmousemove">quadro_alt(event, this, &quot;<xsl:value-of select="Assunto"/>&quot;);</xsl:attribute>
							<xsl:value-of select="AssuntoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
							<xsl:value-of select="Responsavel"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
							<xsl:value-of select="DataHoraTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
							<xsl:value-of select="PrevisaoEtapaTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='11' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>	
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
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>