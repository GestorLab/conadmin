<?
	$localModulo		=	1;
	$localOperacao		=	25;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'');
	$localDescricaoParametroServico	= url_string_xsl($_GET['DescricaoParametroServico'],'');
	$localValorDefault				= url_string_xsl($_GET['ValorDefault'],'');
	$localObrigatorio				= $_GET['Obrigatorio'];
	$localIdStatus					= $_GET['IdStatus'];
	$local_IdServico				= $_GET['IdServico'];
	
	$Limit					= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DescricaoParametroServico";		}
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
		<script type = 'text/javascript' src = 'js/servico_parametro.js'></script>
	</head>
	<body  onLoad="ativaNome('Serviço Parâmetro')">
		<? include('filtro_servico_parametro.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtro_ordenar('IdServico','number')">Serviço<?=compara($localOrdem,"IdServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdParametroServico','number')">Id<?=compara($localOrdem,"IdParametroServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServico')"><?=dicionario(223)?><?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoParametroServico')">Nome Parâmetro Serviço<?=compara($localOrdem,"DescricaoParametroServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('ValorDefault')">Valor Default <?=compara($localOrdem,"ValorDefault",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Editavel')">Editável <?=compara($localOrdem,"Editavel",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Obrigatorio')">Obrigatório <?=compara($localOrdem,"Obrigatorio",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('ParametroDemonstrativo')">Exibir no Boleto <?=compara($localOrdem,"ParametroDemonstrativo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Calculavel')">Calculo Result. <?=compara($localOrdem,"Calculavel",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('CalculavelOpcoes')">Calculo Opções <?=compara($localOrdem,"CalculavelOpcoes",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status')">Status <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdServico"/>_<xsl:value-of select="IdParametroServico"/></xsl:attribute>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="IdServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="IdParametroServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="DescricaoServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="DescricaoParametroServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="ValorDefault"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="Editavel"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="Obrigatorio"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="ParametroDemonstrativo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="Calculavel"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="CalculavelOpcoes"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_servico_parametro.php?IdServico=<xsl:value-of select="IdServico"/>&amp;IdParametroServico=<xsl:value-of select="IdParametroServico"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdServico"/>,<xsl:value-of select="IdParametroServico"/>)</xsl:attribute>
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
	
	addParmUrl("marServico","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoValor","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoValorNovo","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoParametro","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoParametroNovo","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marServicoRotina","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marMascaraVigencia","IdServico",'<?=$local_IdServico?>');
	addParmUrl("marMascaraVigenciaNovo","IdServico",'<?=$local_IdServico?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
