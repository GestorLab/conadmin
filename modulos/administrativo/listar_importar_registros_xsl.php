<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localCampo1				= url_string_xsl($_GET['Campo1'],'url',false);
	$localCampo2				= url_string_xsl($_GET['Campo2'],'url',false);
	$localCampo3				= url_string_xsl($_GET['Campo3'],'url',false);
	$localCampo4				= url_string_xsl($_GET['Campo4'],'url',false);
	$localCampo5				= url_string_xsl($_GET['Campo5'],'url',false);
	$Limit						= $_GET['Limit'];	

	if($localOrdem == ''){					$localOrdem = "Campo1";		}
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
	</head>
	<body  onLoad="ativaNome('Importar Registros')">
		<? include('filtro_importar_registros.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<?
						$coluna	=	explode("\n",getParametroSistema(111,9));
						
						$i	=	1;
						while($i <= count($coluna)){
							echo"<td>".$coluna[$i]."</td>";
							$i++;
						}
					?>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<?
						
						$link	=	getParametroSistema(111,10);
						$campo	=	explode("?",$link);	
						$campo2	=	explode("&",$campo[1]);
						
						$i		=	0;
						while($i < count($campo2)){
							$temp	=	explode('=',$campo2[$i]);
							$var	=	$temp[1];
							$pos	=	explode('$coluna',$var);	
							
							if($pos[1] != ''){	
								$pos	=	(int)$pos[1];
								$Id		=	"<xsl:value-of select='Campo$pos'/>";
								$link	=	str_replace("$var","$Id",$link);
							}
							$i++;
						}
						
						$link	=	str_replace("&","&amp;",$link);
						
						
						$coluna	=	explode("\n",getParametroSistema(111,9));
						$i		=	1;
						while($i <= count($coluna)){
							
						
							echo"<td>
									<xsl:element name='a'>
										<xsl:attribute name='href'>$link</xsl:attribute>
										<xsl:value-of select='Campo$i'/>
									</xsl:element>
								</td>";
								
							$i++;
						}
					?>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='<?=$i?>' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
