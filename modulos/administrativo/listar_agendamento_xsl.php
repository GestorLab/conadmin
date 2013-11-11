<?
	$localModulo		=	1;
	$localOperacao		=	28;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localData				= url_string_xsl($_GET['Data'],'URL',false);
	$localHora				= url_string_xsl($_GET['Hora'],'URL',false);
	$localLogin				= url_string_xsl($_GET['LoginResponsavel'],'URL',false);
	$localIdOrdemServico	= $_GET['IdOrdemServico'];
	
	$Limit					= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "Data";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){	$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/agendamento.js'></script>
	</head>
	<body  onLoad="ativaNome('Agendamento')">
		<? include('filtro_agendamento.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar' />
					<td>
						<a href="javascript:filtro_ordenar('NomeUsuario')">Responsável <?=compara($localOrdem,"NomeUsuario",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Data','number')">Data Agendamento<?=compara($localOrdem,"Data",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Hora')">Hora Agendamento <?=compara($localOrdem,"Hora",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdOrdemServico"/>_<xsl:value-of select="DataHoraAgendamento"/></xsl:attribute>
					<td>
						<xsl:number value="position()" format="1" />
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agendamento.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/>&amp;Data=<xsl:value-of select="DataTemp"/>&amp;Hora=<xsl:value-of select="HoraTemp"/></xsl:attribute>
							<xsl:value-of select="NomeUsuario"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agendamento.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/>&amp;Data=<xsl:value-of select="DataTemp"/>&amp;Hora=<xsl:value-of select="HoraTemp"/></xsl:attribute>
							<xsl:value-of select="DataTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_agendamento.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/>&amp;Data=<xsl:value-of select="DataTemp"/>&amp;Hora=<xsl:value-of select="HoraTemp"/></xsl:attribute>
							<xsl:value-of select="HoraTemp"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdOrdemServico"/>,'<xsl:value-of select="DataTemp"/>','<xsl:value-of select="HoraTemp"/>')</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='5'>Total: <xsl:value-of select="count(db/reg)" /></td>
				</tr>
			</table>
			<table>
				<tr>
					<td class='find' />
					<td><h1 id='helpText' name='helpText' /></td>
				</tr>
			</table>
		</div>
	</body>	
</html>
<script>
	verificaAcao();
	
	addParmUrl("marOrdemServico","IdOrdemServico",'<?=$localIdOrdemServico?>');
	addParmUrl("marAgendamento","IdOrdemServico",'<?=$localIdOrdemServico?>');
	addParmUrl("marAgendamentoNovo","IdOrdemServico",'<?=$localIdOrdemServico?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>