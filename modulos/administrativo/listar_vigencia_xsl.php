<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localPessoa					= url_string_xsl($_GET['Pessoa'],'');
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'');
	$localDataInicio				= $_GET['DataInicio'];
	$localDataTermino				= $_GET['DataTermino'];
	$localValor						= $_GET['Valor'];
	$Limit							= $_GET['Limit'];
	$local_IdContrato				= $_GET['IdContrato'];	
	$local_DataInicioVigencia		= $_GET['DataInicioVigencia'];	
	$local_IdPessoa					= $_GET['IdPessoa'];

	if($localOrdem == ''){					$localOrdem = "DataInicioVigenciaBusca";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/vigencia.js'></script>
	</head>
	<body  onLoad="ativaNome('Contrato/Vigência')">
		<? include('filtro_vigencia.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' id='tableListar'  cellspacing='0'>
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtroOrdenar('IdContrato','number')">Contrato<?=compara($localOrdem,"IdContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td style='width: 100px'>
						<a href="javascript:filtroOrdenar('DataInicioVigenciaBusca','number')">Data Início<?=compara($localOrdem,"DataInicioVigenciaBusca",$ImgOrdernarASC,'')?></a>
					</td>
					<td style='width: 100px'>
						<a href="javascript:filtroOrdenar('DataTerminoVigenciaBusca','number')">Data Término <?=compara($localOrdem,"DataTerminoVigenciaBusca",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('Valor','number')">Valor (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('ValorDesconto','number')">Desconto (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorDesconto",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('ValorFinal','number')">Valor Final (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorFinal",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('ValorFinal','number')">Tipo Desc.<?=compara($localOrdem,"ValorFinal",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('DescricaoContratoTipoVigencia')">Tipo Vigência <?=compara($localOrdem,"DescricaoContratoTipoVigencia",$ImgOrdernarASC,'')?></a>
					</td>
					<td style='width: 110px'  class='valor'>
						<a href="javascript:filtroOrdenar('LimiteDesconto','number')">Limite Desc.<?=compara($localOrdem,"LimiteDesconto",$ImgOrdernarASC,'')?></a>
					</td>
					<td />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContratoFilho"/>_<xsl:value-of select="DataInicioVigencia"/></xsl:attribute>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_vigencia.php?IdContrato=<xsl:value-of select="IdContrato"/>&amp;DataInicioVigencia=<xsl:value-of select="DataInicioVigencia"/>&amp;IdContratoFilho=<xsl:value-of select="IdContratoFilho"/></xsl:attribute>
							<xsl:value-of select="IdContratoFilho"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_vigencia.php?IdContrato=<xsl:value-of select="IdContrato"/>&amp;DataInicioVigencia=<xsl:value-of select="DataInicioVigencia"/>&amp;IdContratoFilho=<xsl:value-of select="IdContratoFilho"/></xsl:attribute>
							<xsl:value-of select="DataInicioVigenciaTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_vigencia.php?IdContrato=<xsl:value-of select="IdContrato"/>&amp;DataInicioVigencia=<xsl:value-of select="DataInicioVigencia"/>&amp;IdContratoFilho=<xsl:value-of select="IdContratoFilho"/></xsl:attribute>
							<xsl:value-of select="DataTerminoVigenciaTemp"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_vigencia.php?IdContrato=<xsl:value-of select="IdContrato"/>&amp;DataInicioVigencia=<xsl:value-of select="DataInicioVigencia"/>&amp;IdContratoFilho=<xsl:value-of select="IdContratoFilho"/></xsl:attribute>
							<xsl:value-of select="ValorTemp"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_vigencia.php?IdContrato=<xsl:value-of select="IdContrato"/>&amp;DataInicioVigencia=<xsl:value-of select="DataInicioVigencia"/>&amp;IdContratoFilho=<xsl:value-of select="IdContratoFilho"/></xsl:attribute>
							<xsl:value-of select="ValorDescontoTemp"/>
						</xsl:element>
					</td>							
					<td class='valor'> 
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_vigencia.php?IdContrato=<xsl:value-of select="IdContrato"/>&amp;DataInicioVigencia=<xsl:value-of select="DataInicioVigencia"/>&amp;IdContratoFilho=<xsl:value-of select="IdContratoFilho"/></xsl:attribute>
							<xsl:value-of select="ValorFinalTemp"/>
						</xsl:element>
					</td>
					<td class='valor'> 
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_vigencia.php?IdContrato=<xsl:value-of select="IdContrato"/>&amp;DataInicioVigencia=<xsl:value-of select="DataInicioVigencia"/>&amp;IdContratoFilho=<xsl:value-of select="IdContratoFilho"/></xsl:attribute>
							<xsl:value-of select="IdTipoDesconto"/>
						</xsl:element>
					</td>							
					<td style='width:80px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_vigencia.php?IdContrato=<xsl:value-of select="IdContrato"/>&amp;DataInicioVigencia=<xsl:value-of select="DataInicioVigencia"/>&amp;IdContratoFilho=<xsl:value-of select="IdContratoFilho"/></xsl:attribute>
							<xsl:value-of select="DescricaoContratoTipoVigencia"/>
						</xsl:element>
					</td>
					<td  class='valor'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_vigencia.php?IdContrato=<xsl:value-of select="IdContrato"/>&amp;DataInicioVigencia=<xsl:value-of select="DataInicioVigencia"/>&amp;IdContratoFilho=<xsl:value-of select="IdContratoFilho"/></xsl:attribute>
							<xsl:value-of select="LimiteDesconto"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdContratoFilho"/>,'<xsl:value-of select="DataInicioVigencia"/>')</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='2'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td />
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorDesconto),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorFinal),"0,00","euro")' /></td>
					<td />
					<td />
					<td />
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
	addParmUrl("marContrato","IdContrato",'<?=$local_IdContrato?>');
	addParmUrl("marContratoNovo","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marVigencia","IdContrato",'<?=$local_IdContrato?>');
	addParmUrl("marVigencia","DataInicioVigencia",'<?=$local_DataInicioVigencia?>');
	addParmUrl("marVigenciaNovo","IdContrato",'<?=$local_IdContrato?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContasReceber","IdContrato",'<?=$local_IdContrato?>');
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marLancamentoFinanceiro","IdContrato",'<?=$local_IdContrato?>');
	addParmUrl("marReenvioEmail","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventual","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$local_IdPessoa?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
	
	function filtroOrdenar(valor, typeDate, valor2, typeDate2){
		document.filtro.IdPessoa.value		= '<?=$local_IdPessoa?>';
		document.filtro.IdContrato.value	= '<?=$local_IdContrato?>';
		
		filtro_ordenar(valor, typeDate, valor2, typeDate2);
	}
</script>
</xsl:template>
</xsl:stylesheet>
