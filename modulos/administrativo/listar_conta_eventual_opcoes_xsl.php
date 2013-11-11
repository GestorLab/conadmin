<?
	$localModulo		=	1;
	$localOperacao		=	31;
	$localSuboperacao	=	"R";

	///array( Pessoa , Contrato, Lanc. Financ., Contas a Receber, Email Enviados, Conta Eventual)
	$array_operacao 	= array(  "1", "2", "18", "17", "31", "27", "94") ;

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');
	 
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	
	if($localOrdem == ''){					$localOrdem = "Operacao";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = "ascending";	}
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
	</head>
	<body  onLoad="ativaNome('Conta Eventual/Pesquisa Avançada')">
		<div id='filtroBuscar'>
			<form name='filtro' method='post' action='listar_conta_eventual_opcoes.php'>
				<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
				<input type='hidden' name='filtro' 					value='s' />
				<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
				<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
				<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			</form>
		</div>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar' />
					<td>
						<a href="javascript:filtro_ordenar('Operacao','text')">Pesquisa Avançada<?=compara($localOrdem,"Operacao",$imgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Tipo','text')">Tipo<?=compara($localOrdem,"Tipo",$imgOrdernarASC,'')?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href"><xsl:value-of select="Link"/></xsl:attribute>
							<xsl:value-of select="IdOperacao"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href"><xsl:value-of select="Link"/></xsl:attribute>
							<xsl:value-of select="Operacao"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href"><xsl:value-of select="Link"/></xsl:attribute>
							<xsl:value-of select="Tipo"/>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='3'>Total: <xsl:value-of select="count(db/reg)" /></td>
				</tr>
			</table>
		</div>
		<table>
			<tr>
				<td class='find' />
				<td><h1 id='helpText' name='helpText' /></td>
			</tr>
		</table>
	</body>	
</html>
<script>
	verificaAcao();

	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
