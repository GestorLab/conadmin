<?
	$localModulo		= 1;
	$localOperacao		= 172;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localServidorEndereco	= url_string_xsl($_GET['ServidorEndereco'],'url',false);
	$localServidorUsuario	= url_string_xsl($_GET['ServidorUsuario'],'url',false);
	$localLimit				= $_GET['Limit'];
	
	if($localOrdem == ''){
		$localOrdem = "ServidorEndereco";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
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
		<script type='text/javascript' src='js/backup_conta.js'></script>
	</head>
	<body onLoad="ativaNome('Contas de Backup')">
		<? include('filtro_backup_conta.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdBackupConta','number')">Id <?=compara($localOrdem,"IdBackupConta",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('ServidorEndereco','text')">Endereço do Servidor (FTP) <?=compara($localOrdem,"ServidorEndereco",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('ServidorUsuario','text')">Usuário <?=compara($localOrdem,"ServidorUsuario",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('BackupCaminho','text')">Pasta para backup <?=compara($localOrdem,"BackupCaminho",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('ServidorPorta','number')">Porta <?=compara($localOrdem,"ServidorPorta",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdBackupConta"/></xsl:attribute>
					<td class='sequencial' style='width:25px'>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_backup_conta.php?IdBackupConta=<xsl:value-of select="IdBackupConta"/></xsl:attribute>
							<xsl:value-of select="IdBackupConta"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_backup_conta.php?IdBackupConta=<xsl:value-of select="IdBackupConta"/></xsl:attribute>
							<xsl:value-of select="ServidorEndereco"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_backup_conta.php?IdBackupConta=<xsl:value-of select="IdBackupConta"/></xsl:attribute>
							<xsl:value-of select="ServidorUsuario"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_backup_conta.php?IdBackupConta=<xsl:value-of select="IdBackupConta"/></xsl:attribute>
							<xsl:value-of select="BackupCaminho"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_backup_conta.php?IdBackupConta=<xsl:value-of select="IdBackupConta"/></xsl:attribute>
							<xsl:value-of select="ServidorPorta"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdBackupConta"/>)</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='5' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
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
<script type="text/javascript">
<!--
	verificaAcao();
	tableMultColor('tableListar', document.filtro.corRegRand.value);
	-->
</script>
</xsl:template>
</xsl:stylesheet>