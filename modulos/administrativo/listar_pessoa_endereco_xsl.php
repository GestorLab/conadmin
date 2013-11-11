<?
	$localModulo		=	1;
	$localOperacao		=	1;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localCampo				= url_string_xsl($_GET['Campo'],'');
	$localValor				= url_string_xsl($_GET['Valor'],'url',false);
	$localGrupoPessoa		= $_GET['GrupoPessoa'];
	$localTipoPessoa		= $_GET['TipoPessoa'];
	$localNome				= url_string_xsl($_GET['Nome'],'URL',false);
	$localFormaCobranca		= $_GET['FormaCobranca'];
	$localLimit				= $_GET['Limit'];
	$localIdPessoa			= $_GET['IdPessoa'];
	$local_IdEstado			= $_GET['IdEstado'];
	$local_IdCidade			= $_GET['IdCidade'];

	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
	
	if($localNome != ""){
		$localNome = str_replace("\\","",$localNome);
	}
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
		<script type = 'text/javascript' src = 'js/pessoa_endereco.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_busca_pessoa_aproximada.js'></script>
	</head>
	<body onLoad="javascript:ativaNome('<?=dicionario(1054)?>');"> 
		<? include("filtro_pessoa_endereco.php"); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>	
			<table class='tableListar' id='tableListar' cellspacing='0'>
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtro_ordenar('Id');"><?=dicionario(141)?><?=compara($localOrdem,"Id",$ImgOrdernarASC,'');?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome');"><?=dicionario(85)?><?=compara($localOrdem,"Nome",$ImgOrdernarASC,'');?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Endereco');"><?=dicionario(155)?><?=compara($localOrdem,"Endereco",$ImgOrdernarASC,'');?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdPessoa"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
							<xsl:value-of select="IdPessoa"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:element name="table">
							<xsl:attribute name="border">0</xsl:attribute>
							<xsl:attribute name="cellspacing">0</xsl:attribute>
							<xsl:attribute name="cellpadding">0</xsl:attribute>
							<xsl:for-each select="EnderecoCliente/EnderecoEncontrado">
								<xsl:element name="tr">
									<xsl:element name="td">
										<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
										<xsl:attribute name="valign">top</xsl:attribute>
										<xsl:element name="a">
											<xsl:attribute name="style">font-size: 7pt</xsl:attribute>
											<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
											<xsl:value-of select="Endereco"/>
										</xsl:element>
									</xsl:element>
								</xsl:element>
							</xsl:for-each>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdPessoa"/>)</xsl:attribute>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='8' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
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
	addParmUrl("marPessoa","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContrato","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContratoNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marProcessoFinanceiro","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marProcessoFinanceiroNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServico","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServicoNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marReenvioEmail","IdPessoa",'<?=$localIdPessoa?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>