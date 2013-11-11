<?
	$localModulo		=	1;
	$localOperacao		=	119;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	
	$localCampo						= url_string_xsl($_GET['Campo'],'');
	$localValor						= url_string_xsl($_GET['Valor'],'url',false);
	$localGrupoPessoa				= $_GET['GrupoPessoa'];
	$localTipoPessoa				= $_GET['TipoPessoa'];
	$localStatusContrato			= $_GET['StatusContrato'];
	$localNome						= url_string_xsl($_GET['Nome'],'url',false);
	$localFormaCobranca				= $_GET['FormaCobranca'];
	$localLimit						= $_GET['Limit'];
	$localIdPessoa					= $_GET['IdPessoa'];

	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localNome != ""){
		$localNome = str_replace("\\","",$localNome);
	}
	LimitVisualizacao('xsl');

	if($localTipoDado == ''){						$localTipoDado = 'text';	}
	
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
		<script type = 'text/javascript' src = 'js/pessoa_dados_cadastrais.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(173)?>')">
		<? include('filtro_pessoa_dados_cadastrais.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<xsl:for-each select="db/reg">					
				<table class='tableListar' cellspacing='0' style='border-bottom:2px #000 solid; margin-bottom:0px;'>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:element name="tr">
						<xsl:attribute name="class">tableListarTitle</xsl:attribute>
						<td colspan='5'><?=dicionario(177)?>:</td>						
					</xsl:element>
					<xsl:element name="tr">
						<xsl:attribute name="cursor">normal</xsl:attribute>
						<td class='descCampo' style='width:200px'><xsl:value-of select="CampoNome"/></td>
						<td class='descCampo' style='width:120px'><?=dicionario(98)?></td>	
						<td class='descCampo' style='width:140px'><?=dicionario(98)?>(1)</td>	
						<td class='descCampo' colspan='0' style='width:150px'><?=dicionario(98)?>(2)</td>													
					</xsl:element>
					<xsl:element name="tr">
						<xsl:attribute name="cursor">normal</xsl:attribute>
						<td>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Nome"/>
							</xsl:element>
						</td>
						<td>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Telefone1"/>						
							</xsl:element>
						</td>
						<td>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Telefone2"/>						
							</xsl:element>
						</td>	
						<td>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Telefone3"/>						
							</xsl:element>
						</td>										
					</xsl:element>
					<xsl:element name="tr">
						<xsl:attribute name="cursor">normal</xsl:attribute>								
						<td class='descCampo'><?=dicionario(176)?></td>					
						<td class='descCampo'><?=dicionario(101)?></td>									
						<td class='descCampo'><?=dicionario(102)?></td>						
						<td class='descCampo'><?=dicionario(100)?></td>										
					</xsl:element>
					<xsl:element name="tr">
						<td>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Email"/>						
							</xsl:element>
						</td>
						<td>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Fax"/>
							</xsl:element>					
						</td>	
						<td>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="ComplementoTelefone"/>						
							</xsl:element>
						</td>
						<td>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Celular"/>				
							</xsl:element>
						</td>
					</xsl:element>					
					<xsl:element name="tr">						
						<xsl:attribute name="cursor">normal</xsl:attribute>	
						<td class='descCampo'  colspan='4'><?=dicionario(155)?></td>						
					</xsl:element>
					<xsl:element name="tr">	
						<xsl:attribute name="cursor">normal</xsl:attribute>					
						<td colspan='4'>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Endereco"/>
							</xsl:element>
						</td>
					</xsl:element>					
					<xsl:element name="tr">
						<xsl:attribute name="cursor">normal</xsl:attribute>	
						<td class='descCampo' colspan='1'><xsl:value-of select="cpCNPJ"/></td>						
						<td class='descCampo' width='20px' colspan='3'><xsl:value-of select="cpIE"/></td>	
					</xsl:element>
					<xsl:element name="tr">
						<td style='width:50%'>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:value-of select="CPF_CNPJ"/>
							</xsl:element>
						</td>						
						<td style='font-weight:normal' colspan='3'>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:value-of select="RG_IE"/>
							</xsl:element>
						</td>							
					</xsl:element>
					<xsl:element name="tr">
						<xsl:attribute name="cursor">normal</xsl:attribute>								
						<td class='descCampo' colspan='1'><xsl:value-of select="cpNomeMae"/></td>						
						<td class='descCampo' width='20px' colspan='3'><xsl:value-of select="cpNomePai"/></td>	
					</xsl:element>
					<xsl:element name="tr">
						<td style='height:12px;'>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="NomeMae"/>					
							</xsl:element>
						</td>
						<td>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="NomePai"/>
							</xsl:element>
						</td>
					</xsl:element>
				</table>
			</xsl:for-each>			
			<table class='tableListar' cellspacing='0'>		
				<tr class='tableListarTitle'>
					<td colspan='2' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>														
				</tr>
			</table>						
		</div>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
<script>
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
