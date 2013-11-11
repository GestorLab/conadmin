<?
	$localModulo		=	1;
	$localOperacao		=	164;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localNome				= url_string_xsl($_GET['Nome'],'');
	$localDataInicio		= url_string_xsl($_GET['DataInicio'],'');
	$localDataFim			= url_string_xsl($_GET['DataFim'],'');
	$localCampo				= url_string_xsl($_GET['Campo'],'');
	$localValor				= url_string_xsl($_GET['Valor'],'');
	$localTipo				= $_GET['Tipo'];
	$localSubTipo			= $_GET['SubTipo'];
	$localIdTicket			= $_GET['IdTicket'];
	$localIdLocalAbertura	= $_GET['IdLocalAbertura'];
	$localIdPrioridade		= $_GET['IdPrioridade'];
	$localUsuarioAtendimento= $_GET['UsuarioAtendimento'];
	$localGrupoAtendimento	= $_GET['GrupoAtendimento'];
	$localIdStatus			= $_GET['IdStatus'];	

	$localLimit				= $_GET['Limit'];
	
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
		<script type = 'text/javascript' src = 'js/help_desk.js'></script>
	</head>
	<body onLoad="ativaNome('Ticket/Para Impressão')">
		<? include('filtro_help_desk_impressao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>			
			<xsl:for-each select="db/reg">
				<table class='tableListar' cellspacing='0' style='border-bottom:2px #000 solid; margin-bottom:0px; text-decoration:none' border='0' Id='tableListar'>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:element name="tr">
						<xsl:attribute name="class">tableListarTitle</xsl:attribute>
						<td colspan='2'>Ticket: <xsl:value-of select="IdTicket"/></td>						
					</xsl:element>
					<xsl:element name="tr">
						<xsl:attribute name="cursor">normal</xsl:attribute>
						<td style='width:200px' valign='top'>
							<table style='font-weight: normal; font-size: 10px'>
								<tr>
									<td><b style='color:#000'>Responsável: </b> 
										<xsl:element name="a">
											<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
											<xsl:attribute name="cursor">normal</xsl:attribute>
											<span style='font-weight: normal'>
											<xsl:value-of select="LoginResponsavel"/></span>
										</xsl:element>
									</td>
								</tr>
								<tr>
									<td><b style='color:#000'>Previsão: </b> 
										<xsl:element name="a">
											<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
											<xsl:attribute name="cursor">normal</xsl:attribute>
											<xsl:value-of select="PrevisaoEtapaTemp"/>
										</xsl:element>
									</td>
								</tr>
								<tr>
									<td><b style='color:#000'>Data Abertura: </b> 
										<xsl:element name="a">
											<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
											<xsl:attribute name="cursor">normal</xsl:attribute>
											<xsl:value-of select="DataHoraTemp"/>
										</xsl:element>
									</td>
								</tr>
							</table>
						</td>
						<td valign='top' style='width:100%-200px'>
							<table border='0' style='font-weight: normal; font-size: 10px' width='100%'>
								<tr>
									<td><b style='color:#000'>Tipo: </b>
										<xsl:element name="a">
											<xsl:attribute name="href">cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
											<xsl:attribute name="cursor">normal</xsl:attribute>
											<xsl:value-of select="TipoSubTipo"/>
										</xsl:element>
									</td>
									<td><b style='color:#000'>Assunto: </b> 
										<xsl:element name='a'>
											<xsl:attribute name='href'>cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
											<xsl:attribute name='cursor'>normal</xsl:attribute>
											<xsl:value-of select="AssuntoTemp"/>
										</xsl:element>
									</td>
								</tr>
								<tr>
									<td colspan='2'><b style='color:#000'>Conteúdo: </b> 
										<xsl:element name='a'>
											<xsl:attribute name='href'>cadastro_help_desk.php?IdTicket=<xsl:value-of select="IdTicket"/></xsl:attribute>
											<xsl:attribute name='cursor'>normal</xsl:attribute>
											<xsl:value-of select="Conteudo"/>
										</xsl:element>
									</td>	
								</tr>
							</table>							
						</td>																		
					</xsl:element>				
				</table>	
			</xsl:for-each>	
			<table class='tableListar' cellspacing='0'>
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