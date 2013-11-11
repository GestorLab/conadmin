<?
	$localModulo		=	1;
	$localOperacao		=	18;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localErro						= $_GET['Erro'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'url',false);
	$localIdStatus					= $_GET['IdStatus'];
	$localIdLocalCobranca			= $_GET['IdLocalCobranca'];
	$localTipoLancamentoFinanceiro  = $_GET['TipoLancamentoFinanceiro'];
	$localCampo						= url_string_xsl($_GET['Campo'],'');
	$localValor						= url_string_xsl($_GET['Valor'],'url',false);
	$local_IdPessoa					= $_GET['IdPessoa'];
	$local_IdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
	$local_IdContaEventual			= $_GET['IdContaEventual'];
	$local_IdOrdemServico			= $_GET['IdOrdemServico'];
	$local_IdContaReceber			= $_GET['IdContaReceber'];
	$localLimit						= $_GET['Limit'];

	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
	
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
		<script type = 'text/javascript' src = 'js/lancamento_financeiro.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(671)?>')">
		<? include('filtro_lancamento_financeiro.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtroOrdenar('IdLancamentoFinanceiro','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdLancamentoFinanceiro",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('Tipo')"><?=dicionario(82)?> <?=compara($localOrdem,"Tipo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('Codigo','number')"><?=dicionario(201)?> <?=compara($localOrdem,"Codigo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?><?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('Descricao')"><?=dicionario(125)?> <?=compara($localOrdem,"Descricao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('IdProcessoFinanceiro','number')"><?=dicionario(672)?>.<?=compara($localOrdem,"IdProcessoFinanceiro",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('Referencia','text')"><?=dicionario(202)?> <?=compara($localOrdem,"Referencia",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('Valor','number')"><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtroOrdenar('ValorDescontoAConceber','number')"><?=dicionario(673)?> (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorDescontoAConceber",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtroOrdenar('Status')"><?=dicionario(140)?> <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="IdLancamentoFinanceiro"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="Tipo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="Codigo"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="Descricao"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="IdProcessoFinanceiro"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="Referencia"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select='format-number(Valor,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorDescontoAConceber,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>					
					<td style='cursor:pointer'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">bt_lista</xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
							<xsl:attribute name="alt">Cancelar?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdLancamentoFinanceiro"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='2'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td />
					<td />
					<td />
					<td />
					<td />
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorDescontoAConceber),"0,00","euro")' /></td>
					<td />
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
	mensagens(<?=$localErro?>);
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
	
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marLancamentoFinanceiro","IdContaEventual",'<?=$local_IdContaEventual?>');
	addParmUrl("marLancamentoFinanceiro","IdOrdemServico",'<?=$local_IdOrdemServico?>');
	addParmUrl("marContasReceber","IdOrdemServico",'<?=$local_IdOrdemServico?>');
	addParmUrl("marContasReceber","IdContaReceber",'<?=$local_IdContaReceber?>');
	addParmUrl("marContaEventual","IdContaEventual",'<?=$local_IdContaEventual?>');
	addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",'<?=$local_IdProcessoFinanceiro?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContrato","IdPessoa",'<?=$local_IdPessoa?>');
	
	<?
		if($local_IdPessoa!=''){
			echo"addParmUrl('marContasReceber','IdPessoa','$local_IdPessoa');";
		}
	?>
	addParmUrl("marContratoNovo","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marReenvioMensagem","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventual","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventual","IdContaEventual",'<?=$local_IdContaEventual?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$local_IdPessoa?>');
	
	function filtroOrdenar(valor, typeDate, valor2, typeDate2){
		document.filtro.IdProcessoFinanceiro.value = '<?=$local_IdProcessoFinanceiro?>';
		
		filtro_ordenar(valor, typeDate, valor2, typeDate2);
	}
</script>
</xsl:template>
</xsl:stylesheet>

