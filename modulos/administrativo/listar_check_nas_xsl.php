<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localAcesso			= $_GET['Acesso'];
	$localSSH				= $_GET['SSH'];
	$localSNMP				= $_GET['SNMP'];
	$localNas				= url_string_xsl($_GET['Nas'],'url',false);
	$localNome				= url_string_xsl($_GET['Nome'],'');
	$Limit					= $_GET['Limit'];
	
	if($localOrdem == ''){
		$localOrdem = "NasTemp";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
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
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/impress.css' media='print' />
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
	</head>
	<body onLoad="ativaNome('Check NAS')">
		<div id='carregando'>carregando</div>
		<? include("filtro_check_nas.php"); ?>
		<div id='conteudo'>
			<table class='tableListar' id='tableListar'  cellspacing='0'>
				<tr class='tableListarTitle'>
					<td style='min-width:100px;'>
						<a href="javascript:filtro_ordenar('Name','text')">Nome <?=compara($localOrdem,"Name",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('NasTemp','text')">NAS <?=compara($localOrdem,"NasTemp",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('AcessoTemp','text')">Acesso (Porta <?=getCodigoInterno(43,1)?>) <?=compara($localOrdem,"AcessoTemp",$ImgOrdernarASC,'')?></a>
					</td>					
					<td>
						<a href="javascript:filtro_ordenar('SSHTemp','text')">SSH (Porta <?=getCodigoInterno(43,2)?>) <?=compara($localOrdem,"SSHTemp",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('SNMPTemp','text')">SNMP (Comunity <?=getCodigoInterno(43,3)?>) <?=compara($localOrdem,"SNMPTemp",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Latencia','text')">Latência (ms) <?=compara($localOrdem,"Latencia",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="Nas"/></xsl:attribute>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:value-of select="Name"/>
					</td>	
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:value-of select="Nas"/>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:value-of select="AcessoTemp"/>
					</td>					
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:value-of select="SSHTemp"/>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:value-of select="SNMPTemp"/>
					</td>
					<td>
						<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
						<xsl:value-of select="Latencia"/>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td />
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
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
