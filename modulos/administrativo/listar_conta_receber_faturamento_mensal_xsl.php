<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localCPFCNPJ					= $_SESSION["filtro_cpf_cnpj"];
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'url',false);
	$localCampo						= url_string_xsl($_GET['Campo'],'');
	$localValor						= url_string_xsl($_GET['Valor'],'');
	$localIdStatus					= $_GET['IdStatus'];
	$localIdLocalCobranca			= $_GET['IdLocalCobranca'];
	$localCancelado					= $_GET['Cancelado'];
	$localJuros						= $_GET['Juros'];
	$localSoma						= $_GET['Soma'];
	$localNotaFiscal				= $_GET['NotaFiscal'];
	$localTipoPessoa				= $_GET['TipoPessoa'];
	$localBoleto					= $_GET['Boleto'];
	$localDescricaoServico			= $_GET['DescricaoServico'];
	$localIdContrato				= $_GET['IdContrato'];
	$localIdContaEventual			= $_GET['IdContaEventual'];
	$localIdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
	$local_IdPessoa					= $_GET['IdPessoa'];
	$local_IdContaReceber			= $_GET['IdContaReceber'];
	$local_IdOrdemServico			= $_GET['IdOrdemServico'];
	$local_IdCarne					= $_GET['IdCarne'];
	$local_IdServico				= $_GET['IdServico'];
	$localLimit						= $_GET['Limit'];

	if($localOrdem == ''){							$localOrdem = "DataVencimento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/conta_receber.js'></script>
		<script type = 'text/javascript' src = 'js/conta_receber_busca_pessoa_aproximada.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(920)?>')">
		<div id='carregando'><?=dicionario(17)?></div>
		<? include('filtro_conta_receber_faturamento_mensal.php'); ?>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' cellpadding='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdServico','number')"><?=dicionario(928)?><?=compara($localOrdem,"IdServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServico','text')"><?=dicionario(223)?><?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServicoGrupo','text')"><?=dicionario(367)?><?=compara($localOrdem,"DescricaoServicoGrupo",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Valor','number')"><?=dicionario(398)?> <?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorDesconto','number')"><?=dicionario(934)?>.<?=compara($localOrdem,"ValorDesconto",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Valor','number')"><?=dicionario(206)?><?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContaReceber"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="IdServico"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="DescricaoServico"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="DescricaoServicoGrupo"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select='Valor'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="ValorDesconto"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="ValorFinal"/>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td style='width: 90px' colspan='1'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td />
					<td />
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorSoma),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorRecebidoSoma),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorFinalSoma),"0,00","euro")' /></td>
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
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marLancamentoFinanceiro","IdOrdemServico",'<?=$local_IdOrdemServico?>');
	addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",'<?=$local_IdProcessoFinanceiro?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContrato","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContratoNovo","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marReenvioMensagem","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marReenvioMensagem","IdContaReceber",'<?=$local_IdContaReceber?>');
	addParmUrl("marContaEventual","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$local_IdPessoa?>');	
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;

</script>
</xsl:template>
</xsl:stylesheet>

