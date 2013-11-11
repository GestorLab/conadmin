<?
	$localModulo		= 1;
	$localOperacao		= 10001;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localFiltro		= $_GET['Filtro'];
	$localOrdem			= $_GET['Ordem'];
	$localOrdemDirecao	= $_GET['OrdemDirecao'];
	$localTipoDado		= $_GET['TipoDado'];
	$localIdServidor	= url_string_xsl($_GET['IdServidor'],'');
	$localLogin			= url_string_xsl($_GET['Login'],'');
	$localMAC			= url_string_xsl($_GET['MAC'],'');
	$localIP			= url_string_xsl($_GET['IP'],'');
	$localIdServidor	= $_GET['IdServidor'];
	$localid			= $_GET['id'];
	$localOcultaIP		= $_GET['OcultaIP'];
	$localOcultaMAC		= $_GET['OcultaMAC'];
	$localOcultaNAS		= $_GET['OcultaNAS'];
	$localNAS			= $_GET['NAS'];
	$Limit				= $_GET['Limit'];
	$local_IdLicenca	= $_SESSION["IdLicenca"];
	
	if($localOrdem == ''){
		$localOrdem = "DataInicio";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($Limit == '' && $localFiltro == ''){
		$Limit = getCodigoInterno(7,5);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'number';
	}
	
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
		<script type='text/javascript' src='js/radius_conexao_ativa.js'></script>
	</head>
	<body onLoad="ativaNome('Radius/Conexão Ativa')">
		<? include('filtro_radius_conexao_ativa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('RadAcctId','number')">Id <?=compara($localOrdem,"RadAcctId",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('UserName','text')">Login/MAC <?=compara($localOrdem,"UserName",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($localOcultaIP == 2){
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('IP','text')\">IP ".compara($localOrdem,"IP",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
						if($localOcultaMAC == 2){
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('MAC','text')\">MAC ".compara($localOrdem,"MAC",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
						if($localOcultaNAS == 2){
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('NAS','text')\">NAS ".compara($localOrdem,"NAS",$ImgOrdernarASC,'')."</a>
							</td>";
						}
					?>
					
					<td>
						<a href="javascript:filtro_ordenar('DataInicio','number')">Data Início <?=compara($localOrdem,"DataInicio",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Duracao','number')">Duração <?=compara($localOrdem,"Duracao",$ImgOrdernarASC,'')?></a>
					</td>
					<td style='text-align:right'>
						<a href="javascript:filtro_ordenar('Download','number')">Download <?=compara($localOrdem,"Download",$ImgOrdernarASC,'')?></a>
					</td>
					<td style='text-align:right'>
						<a href="javascript:filtro_ordenar('Upload','number')">Upload <?=compara($localOrdem,"Upload",$ImgOrdernarASC,'')?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="RadAcctId"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:value-of select="RadAcctId"/>
					</td>
					<td>
						<xsl:value-of select="UserName"/>
					</td>
					<?
						if($localOcultaIP == 2){
							echo"
							<td>
								<xsl:value-of select='IP'/>
							</td>
							";
						}
						if($localOcultaMAC == 2){
							echo"
							<td>
								<xsl:value-of select='MAC'/>
							</td>
							";
						}
						if($localOcultaNAS == 2){
							echo"
								<td>
									<xsl:value-of select='NAS'/>
								</td>
							";
						}
					?>
				
					<td>
						<xsl:value-of select="DataInicioTemp"/>
					</td>
					<td>
						<xsl:value-of select="DuracaoTemp"/>
					</td>
					<td style='text-align:right'>
						<xsl:value-of select='DownloadTemp'/>
					</td>
					<td style='text-align:right'>
						<xsl:value-of select='UploadTemp'/>
					</td>
				</xsl:element>
				</xsl:for-each>

				<tr class='tableListarTitle'>
					<td colspan='3' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<?
						if($localOcultaIP == 2){
							echo"<td />";
						}
						if($localOcultaMAC == 2){
							echo"<td />";
						}
						if($localOcultaNAS == 2){
							echo"<td />";
						}
					?>
					<td id='tableListarTotal'><xsl:value-of select="db/somatorio/Duracao" /></td>
					<td id='tableListarTotal' style='text-align:right'><xsl:value-of select="db/somatorio/Download" /></td>
					<td id='tableListarTotal' style='text-align:right'><xsl:value-of select="db/somatorio/Upload" /></td>				
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
