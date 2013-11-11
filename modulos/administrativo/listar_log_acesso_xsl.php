<?
	$localModulo		= 1;
	$localOperacao		= 36;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localFiltro		= $_GET['Filtro'];
	$localOrdem			= $_GET['Ordem'];
	$localOrdemDirecao	= $_GET['OrdemDirecao'];
	$localTipoDado		= $_GET['TipoDado'];
	$localLogin			= url_string_xsl($_GET['Login'],'');
	$localNome			= url_string_xsl($_GET['Nome'],'url',false);
	$localData			= url_string_xsl($_GET['Data'],'url',false);
	$localNavegador		= url_string_xsl($_GET['Navegador'],'');
	$localFechada		= $_GET['Fechada'];
	$Limit				= $_GET['Limit'];

	if($localOrdem == ''){
		$localOrdem = "DataHora";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'number';
	}
	
	LimitVisualizacao('xsl');
	
	$ImgOrdernarASC = "<img src='../../img/estrutura_sistema/seta_$localOrdemDirecao.gif' alt='Ordenado por' />";
	
	header("content-type: text/xsl");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:decimal-format name="euro" decimal-separator="," grouping-separator="."/>
<xsl:template match="/">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/impress.css' media='print' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/log_acesso.js'></script>
		<script type='text/javascript' src='js/log_acesso_busca_pessoa_aproximada.js'></script>
	</head>
	<body onLoad="ativaNome('Log de Acesso')">
		<? include('filtro_log_acesso.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'/>
					<td>
						<a href="javascript:filtro_ordenar('Login')">Login <?=compara($localOrdem,"Login",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome')">Nome Usuário <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IP')">IP <?=compara($localOrdem,"IP",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataHora','number')">Data Acesso <?=compara($localOrdem,"DataHora",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataUltimaAtualizacao','number')">Última Atualização <?=compara($localOrdem,"DataUltimaAtualizacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Navegador')">Navegador <?=compara($localOrdem,"Navegador",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoFechada')">Encerrada <?=compara($localOrdem,"DescricaoFechada",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdLogAcesso"/></xsl:attribute>
					<td class='sequencial' style='width=25px'>
						<xsl:number value="position()" format="1" />
					</td>
					<td style='cursor:pointer'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_usuario.php?Login=<xsl:value-of select="Login"/></xsl:attribute>
							<xsl:value-of select="Login"/>
						</xsl:element>
					</td>
					<td style='cursor:pointer'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_usuario.php?Login=<xsl:value-of select="Login"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>
					<td>
						<xsl:value-of select="IP"/>
					</td>
					<td>
						<xsl:value-of select="DataHoraTemp"/>
					</td>
					<td>
						<xsl:value-of select="DataUltimaAtualizacaoTemp"/>
					</td>
					<td>
						<xsl:value-of select="Navegador"/>
					</td>
					<td>
						<xsl:value-of select="DescricaoFechada"/>
					</td>
					<td class='bt_lista' style='cursor:pointer'>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="Img"/></xsl:attribute>
							<xsl:attribute name="alt">Derrubar conexão?</xsl:attribute>
							<xsl:attribute name="onClick">derrubar_conexao(<xsl:value-of select="IdLogAcesso"/>,<xsl:value-of select="Fechada"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='9'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
<script type="text/javascript">
	addParmUrl("marUsuario","Login",'<?=$local_Login?>');
	addParmUrl("marUsuarioPermissao","Login",'<?=$local_Login?>');
	addParmUrl("marUsuarioGrupoPermissao","Login",'<?=$local_Login?>');
	addParmUrl("marLogAcesso","Login",'<?=$local_Login?>');
	addParmUrl("marUsuarioGrupoUsuario","Login",'<?=$local_Login?>');
	addParmUrl("marUsuarioGrupoUsuarioNovo","Login",'<?=$local_Login?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>