<?
	$localModulo		=	1;
	$localOperacao		=	21;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localIdPlanoConta			= $_GET['IdPlanoConta'];
	$localDescricaoPlanoConta	= url_string_xsl($_GET['DescricaoPlanoConta'],'url',false);
	$localIdAcessoRapido		= url_string_xsl($_GET['IdAcessoRapido'],'url',false);
	$localTipo					= $_GET['Tipo'];
	$localAcaoContabil			= $_GET['AcaoContabil'];
	$Limit						= $_GET['Limit'];

	if($localOrdem == ''){					$localOrdem = "DescricaoPlanoConta";		}
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
		<script type = 'text/javascript' src = 'js/plano_conta.js'></script>
	</head>
	<body  onLoad="ativaNome('Plano de Conta')">
		<? include('filtro_plano_conta.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtro_ordenar('IdPlanoConta')">Plano de Conta <?=compara($localOrdem,"IdPlanoConta",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoPlanoConta')"> Nome Plano de Conta<?=compara($localOrdem,"DescricaoPlanoConta",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdAcessoRapido')">Acesso R�pido <?=compara($localOrdem,"IdAcessoRapido",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Tipo')">Tipo <?=compara($localOrdem,"Tipo",$ImgOrdernarASC,'')?></a>
					</td>
					<td style='width: 90px'>
						<a href="javascript:filtro_ordenar('AcaoContabil')">A��o Cont�bil <?=compara($localOrdem,"AcaoContabil",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdPlanoConta"/></xsl:attribute>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_plano_conta.php?IdPlanoConta=<xsl:value-of select="IdPlanoConta"/></xsl:attribute>
							<xsl:value-of select="IdPlanoConta"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_plano_conta.php?IdPlanoConta=<xsl:value-of select="IdPlanoConta"/></xsl:attribute>
							<xsl:value-of select="DescricaoPlanoConta"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_plano_conta.php?IdPlanoConta=<xsl:value-of select="IdPlanoConta"/></xsl:attribute>
							<xsl:value-of select="IdAcessoRapido"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_plano_conta.php?IdPlanoConta=<xsl:value-of select="IdPlanoConta"/></xsl:attribute>
							<xsl:value-of select="Tipo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_plano_conta.php?IdPlanoConta=<xsl:value-of select="IdPlanoConta"/></xsl:attribute>
							<xsl:value-of select="AcaoContabil"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir('<xsl:value-of select="IdPlanoConta"/>')</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
