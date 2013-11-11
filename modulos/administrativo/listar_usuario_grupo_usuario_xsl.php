<?
	$localModulo		=	1;
	$localOperacao		=	33;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$localFiltro		= $_GET['Filtro'];
	$localOrdem			= $_GET['Ordem'];
	$localOrdemDirecao	= $_GET['OrdemDirecao'];
	$localTipoDado		= $_GET['TipoDado'];
	$localLogin			= url_string_xsl($_GET['Login'],'');
	$localNome			= url_string_xsl($_GET['Nome'],'');
	$localEmail			= url_string_xsl($_GET['Email'],'');
	$localGrupoUsuario	= url_string_xsl($_GET['GrupoUsuario'],'');
	$localStatus		= $_GET['Status'];
	$Limit				= $_GET['Limit'];
	$local_Login		= $_GET['Login'];

	if($localOrdem == ''){					$localOrdem = "Login";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
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
		<script type = 'text/javascript' src = 'js/usuario_grupo_usuario.js'></script>
	</head>
	<body  onLoad="ativaNome('Usuário/Grupo Usuário')">
		<? include('filtro_usuario_grupo_usuario.php'); ?>
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
						<a href="javascript:filtro_ordenar('DescricaoGrupoUsuario')">Grupo Usuário <?=compara($localOrdem,"DescricaoGrupoUsuario",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="Login"/>_<xsl:value-of select="IdGrupoUsuario"/></xsl:attribute>
					<td class='sequencial' style='width=25px'>
						<xsl:number value="position()" format="1" />
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_usuario_grupo_usuario.php?Login=<xsl:value-of select="Login"/>&amp;IdGrupoUsuario=<xsl:value-of select="IdGrupoUsuario"/></xsl:attribute>
							<xsl:value-of select="Login"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_usuario_grupo_usuario.php?Login=<xsl:value-of select="Login"/>&amp;IdGrupoUsuario=<xsl:value-of select="IdGrupoUsuario"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_usuario_grupo_usuario.php?Login=<xsl:value-of select="Login"/>&amp;IdGrupoUsuario=<xsl:value-of select="IdGrupoUsuario"/></xsl:attribute>
							<xsl:value-of select="DescricaoGrupoUsuario"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_del.gif</xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir('<xsl:value-of select="Login"/>',<xsl:value-of select="IdGrupoUsuario"/>)</xsl:attribute>
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
	</body>	
</html>
<script>
	addParmUrl("marUsuario","Login",'<?=$local_Login?>');
	addParmUrl("marUsuarioPermissao","Login",'<?=$local_Login?>');
	addParmUrl("marUsuarioGrupoPermissao","Login",'<?=$local_Login?>');
	addParmUrl("marUsuarioGrupoUsuario","Login",'<?=$local_Login?>');
	addParmUrl("marUsuarioGrupoUsuarioNovo","Login",'<?=$local_Login?>');
	addParmUrl("marLogAcesso","Login",'<?=$local_Login?>');
	
	verificaAcao();
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
