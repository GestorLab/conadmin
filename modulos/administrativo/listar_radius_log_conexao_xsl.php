<?
	$localModulo		=	1;
	$localOperacao		=	10001;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$Descricao					= url_string_xsl($_GET['Descricao'],'');
	$localData					= url_string_xsl($_GET['Data'],'');
	$localHora					= url_string_xsl($_GET['Hora'],'');
	$localIdStatus				= url_string_xsl($_GET['IdStatus'],'');
	$localMesReferencia			= $_GET['MesReferencia'];
	$Limit						= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "Descricao";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){	$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
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
	</head>
	<body  onLoad="ativaNome('Servidor Radius/Log de Conexão')">
		<? include('filtro_radius_log_conexao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('Login')">Login<?=compara($localOrdem,"Login",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Mac')">MAC<?=compara($localOrdem,"Mac",$imgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataIniConexao','number')">Data Início<?=compara($localOrdem,"DataIniConexao",$imgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataFimConexao','number')">Data Término <?=compara($localOrdem,"DataFimConexao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('TempoConexao','number')">Tempo <?=compara($localOrdem,"TempoConexao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Download','number')">Download <?=compara($localOrdem,"Download",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
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
					<td class='sequencial' style='width:25px'>
						<xsl:value-of select="Login"/>
					</td>
					<td>
						<xsl:value-of select="Mac"/>
					</td>
					<td>
						<xsl:value-of select="DataIniConexaoTemp"/>
					</td>
					<td>
						<xsl:value-of select="DataFimConexaoTemp"/>
					</td>
					<td>
						<xsl:value-of select="TempoConexao"/>
					</td>
					<td>
						<xsl:value-of select="Download"/>
					</td>
					<td>
						<xsl:value-of select="Upload"/>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='7' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
