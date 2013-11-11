<?
	$localModulo		=	1;
	$localOperacao		=	1;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localOperadora			= $_GET['IdOperadora'];
	$localDescricao			= url_string_xsl($_GET['DescricaoContaSMS'],'url',false);
	$localStatus			= $_GET['IdStatus'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localLimit				= $_GET['Limit'];
	
	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localLimit == '' && $localFiltro == ''){	$localLimit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
	
	$ImgOrdernarASC = "<img src='../../img/estrutura_sistema/seta_$localOrdemDirecao.gif' alt='Ordenado por' />";
	
	header ("content-type: text/xsl");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:decimal-format name="euro" decimal-separator="," />
<xsl:template match="/">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media='print' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa.js'></script>
	</head>
	<body  onLoad="ativaNome('Conta SMS')">
		<? include('filtro_conta_sms.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' id='tableListar' cellspacing='0'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdPessoa','number')">Id <?=compara($localOrdem,"IdContaSMS",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome')">Descrição <?=compara($localOrdem,"DescricaoContaSMS",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('RazaoSocial')">Operadora <?=compara($localOrdem,"IdOperadoraSMS",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Telefone1')">Status <?=compara($localOrdem,"IdStatus",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContaSMS"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_sms.php?IdContaSMS=<xsl:value-of select="IdContaSMS"/></xsl:attribute>
							<xsl:value-of select="IdContaSMS"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_sms.php?IdContaSMS=<xsl:value-of select="IdContaSMS"/></xsl:attribute>
							<xsl:value-of select="DescricaoContaSMS"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_sms.php?IdContaSMS=<xsl:value-of select="IdContaSMS"/></xsl:attribute>
							<xsl:value-of select="IdOperadora"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_sms.php?IdContaSMS=<xsl:value-of select="IdContaSMS"/></xsl:attribute>
							<xsl:value-of select="IdStatus"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdContaSMS"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='6'>Total: <xsl:value-of select="count(db/reg)" /></td>
				</tr>
				<tr class='tableListarTitle'>
					<td colspan='4'></td>
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
	
	addParmUrl("marPessoa","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marOrdemServico","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marOrdemServicoNovo","IdPessoa",'<?=$local_IdPessoa?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
