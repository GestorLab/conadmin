<?
	$localModulo		=	1;
	$localOperacao		=	35;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localNome					= url_string_xsl($_GET['Nome'],'');
	$localServico				= url_string_xsl($_GET['Servico'],'');
	$localParcela				= url_string_xsl($_GET['Parcela'],'');
	$localCarteira				= url_string_xsl($_GET['Carteira'],'');
	$Limit						= $_GET['Limit'];
	$IdAgenteAutorizado			= $_GET['IdAgenteAutorizado'];
	$IdCarteira					= $_GET['IdCarteira'];

	if($localOrdem == ''){					$localOrdem = "RazaoSocial";		}
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
		<script type = 'text/javascript' src = 'js/carteira_comissao.js'></script>
	</head>
	<body  onLoad="ativaNome('Comissionamento Vendedor')">
		<? include('filtro_carteira_comissao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar' />
					<td>
						<a href="javascript:filtro_ordenar('RazaoSocialAgenteAutorizado','text')">Nome Agente Autorizado <?=compara($localOrdem,"RazaoSocialAgenteAutorizado",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('NomeCarteira','text')">Nome Vendedor <?=compara($localOrdem,"NomeCarteira",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServico','text')">Nome Serviço <?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Parcela','number')">Parcela <?=compara($localOrdem,"Parcela",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Percentual','number')">Percentual (%) <?=compara($localOrdem,"Percentual",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdAgenteAutorizado"/>_<xsl:value-of select="IdCarteira"/>_<xsl:value-of select="IdServico"/>_<xsl:value-of select="Parcela"/></xsl:attribute>
					<td class='sequencial' style='width=25px'>
						<xsl:number value="position()" format="1" />
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_carteira_comissao.php?IdAgenteAutorizado=<xsl:value-of select="IdAgenteAutorizado"/>&amp;IdCarteira=<xsl:value-of select="IdCarteira"/>&amp;IdServico=<xsl:value-of select="IdServico"/>&amp;Parcela=<xsl:value-of select="Parcela"/></xsl:attribute>
							<xsl:value-of select="RazaoSocialAgenteAutorizado"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_carteira_comissao.php?IdAgenteAutorizado=<xsl:value-of select="IdAgenteAutorizado"/>&amp;IdCarteira=<xsl:value-of select="IdCarteira"/>&amp;IdServico=<xsl:value-of select="IdServico"/>&amp;Parcela=<xsl:value-of select="Parcela"/></xsl:attribute>
							<xsl:value-of select="NomeCarteira"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_carteira_comissao.php?IdAgenteAutorizado=<xsl:value-of select="IdAgenteAutorizado"/>&amp;IdCarteira=<xsl:value-of select="IdCarteira"/>&amp;IdServico=<xsl:value-of select="IdServico"/>&amp;Parcela=<xsl:value-of select="Parcela"/></xsl:attribute>
							<xsl:value-of select="DescricaoServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_carteira_comissao.php?IdAgenteAutorizado=<xsl:value-of select="IdAgenteAutorizado"/>&amp;IdCarteira=<xsl:value-of select="IdCarteira"/>&amp;IdServico=<xsl:value-of select="IdServico"/>&amp;Parcela=<xsl:value-of select="Parcela"/></xsl:attribute>
							<xsl:value-of select="Parcela"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_carteira_comissao.php?IdAgenteAutorizado=<xsl:value-of select="IdAgenteAutorizado"/>&amp;IdCarteira=<xsl:value-of select="IdCarteira"/>&amp;IdServico=<xsl:value-of select="IdServico"/>&amp;Parcela=<xsl:value-of select="Parcela"/></xsl:attribute>
							<xsl:value-of select='format-number(Percentual,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdAgenteAutorizado"/>,<xsl:value-of select="IdCarteira"/>,<xsl:value-of select="IdServico"/>,<xsl:value-of select="Parcela"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='5' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor' id='tableListarValor' style='width:170px'><xsl:value-of select='format-number(sum(db/reg/Percentual),"0,00","euro")' /></td>
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
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
<script>
	addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marCarteira","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marCarteira","IdCarteira",'<?=$IdCarteira?>');
	addParmUrl("marCarteiraNovo","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marCarteiraComissionamento","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marCarteiraComissionamentoNovo","IdAgenteAutorizado",'<?=$IdAgenteAutorizado?>');
	addParmUrl("marCarteiraComissionamento","IdCarteira",'<?=$IdCarteira?>');
	addParmUrl("marCarteiraComissionamentoNovo","IdCarteira",'<?=$IdCarteira?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
