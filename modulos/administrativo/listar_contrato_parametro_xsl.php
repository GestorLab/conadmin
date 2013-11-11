<?
	$localModulo		=	1;
	$localOperacao		=	97;
	$localSuboperacao	=	"R";
		
	$localTituloOperacao	= "Contratos";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localPessoa					= url_string_xsl($_GET['Pessoa'],'url',false);
	$localParametro1				= url_string_xsl($_GET['Parametro1'],'url',false);
	$localParametro2				= url_string_xsl($_GET['Parametro2'],'url',false);
	$localParametro3				= url_string_xsl($_GET['Parametro3'],'url',false);
	$localParametro4				= url_string_xsl($_GET['Parametro4'],'url',false);
	$localValorParametro1			= url_string_xsl($_GET['ValorParametro1'],'url',false);
	$localValorParametro2			= url_string_xsl($_GET['ValorParametro2'],'url',false);
	$localValorParametro3			= url_string_xsl($_GET['ValorParametro3'],'url',false);
	$localValorParametro4			= url_string_xsl($_GET['ValorParametro4'],'url',false);
	$localIdStatus					= url_string_xsl($_GET['IdStatus'],'');
	$localNomeServico				= url_string_xsl($_GET['NomeServico'],'url',false);
	$localIdCidade					= $_GET['IdCidade'];
	$localNomeCidade				= url_string_xsl($_GET['NomeCidade'],'url',false);
	$localIdEstado					= $_GET['IdEstado'];
	$localBairro					= url_string_xsl($_GET['Bairro'],'url',false);
	$localEndereco					= url_string_xsl($_GET['Endereco'],'url',false);
	$localIdPessoa					= $_GET['IdPessoa'];
	$localIdServico					= $_GET['IdServico'];
	$Limit							= $_GET['Limit'];
	$local_IdPessoa					= $_GET['IdPessoa'];
	$local_IdContrato				= $_GET['IdContrato'];
	$localTelefone					= $_GET['Telefone'];
	
	if($acesso == '')	$acesso = 'filtro_contrato.php';

	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
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
		<script type = 'text/javascript' src = 'js/contrato.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_parametro.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(345);?>')">
		<? include("filtro_contrato_parametro.php"); ?>
		<div id='conteudo'>
			<table class='tableListar' id='tableListar'  cellspacing='0'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdContrato','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdContrato",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServico')"><?=dicionario(223)?><?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($localTelefone == 1){
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('Telefone1','number')\">Telefone".compara($localOrdem,"Telefone1",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
					?>
					<?
						if($localParametro1!=""){
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('ParametroOrdem1')\">$localParametro1 ".compara($localOrdem,"ParametroOrdem1",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
						if($localParametro2!=""){
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('ParametroOrdem2')\">$localParametro2 ".compara($localOrdem,"ParametroOrdem2",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
						if($localParametro3!=""){
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('ParametroOrdem3')\">$localParametro3 ".compara($localOrdem,"ParametroOrdem3",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
						if($localParametro4!=""){
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('ParametroOrdem4')\">$localParametro4 ".compara($localOrdem,"ParametroOrdem4",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
					?>
					<td style='width:76px;' class='valor'>
						<a href="javascript:filtro_ordenar('ValorFinal','number')"><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorFinal",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Status')"><?=dicionario(140)?> <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContrato"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="IdContrato"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="DescricaoServico"/>
						</xsl:element>
					</td>
					<?
						if($localTelefone == 1){
							echo"
							<xsl:element name='td'>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:element name='a'>
									<xsl:attribute name='href'>cadastro_contrato.php?IdContrato=<xsl:value-of select='IdContrato'/></xsl:attribute>
									<xsl:value-of select='Telefone1'/>
								</xsl:element>
							</xsl:element>
							";
						}
					?>
					<?
						if($localParametro1!=""){
							echo"
							<td>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:element name='a'>
									<xsl:attribute name='href'>cadastro_contrato.php?IdContrato=<xsl:value-of select='IdContrato'/></xsl:attribute>
									<xsl:value-of select='Parametro1'/>
								</xsl:element>
							</td>
							";
						}
						if($localParametro2!=""){
							echo"
							<td>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:element name='a'>
									<xsl:attribute name='href'>cadastro_contrato.php?IdContrato=<xsl:value-of select='IdContrato'/></xsl:attribute>
									<xsl:value-of select='Parametro2'/>
								</xsl:element>
							</td>
							";
						}
						if($localParametro3!=""){
							echo"
							<td>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:element name='a'>
									<xsl:attribute name='href'>cadastro_contrato.php?IdContrato=<xsl:value-of select='IdContrato'/></xsl:attribute>
									<xsl:value-of select='Parametro3'/>
								</xsl:element>
							</td>
							";
						}
						if($localParametro4!=""){
							echo"
							<td>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:element name='a'>
									<xsl:attribute name='href'>cadastro_contrato.php?IdContrato=<xsl:value-of select='IdContrato'/></xsl:attribute>
									<xsl:value-of select='Parametro4'/>
								</xsl:element>
							</td>
							";
						}
					?>
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="ValorFinalTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="Img"/></xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdContrato"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='9' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
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
	<?
		if($localParametro1!=""){
				echo "busca_parametro('filtro_parametro_1','$localParametro1',document.filtro.filtro_parametro_2,'$localParametro2','$localParametro3','$localParametro4');";
			if($localParametro2!=""){
			#	echo "busca_parametro('filtro_parametro_2','$localParametro2',document.filtro.filtro_parametro_3,'$localParametro3');";
				if($localParametro3!=""){
			#		echo "busca_parametro('filtro_parametro_3','$localParametro3',document.filtro.filtro_parametro_4,'$localParametro4');";
				}
			}
		}	
	?>

	verificaAcao();
	
	addParmUrl("marContrato","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marContrato","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContratoNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marVigencia","IdContrato",'<?=$localIdContrato?>', true);
	addParmUrl("marVigenciaNovo","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marVigencia","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marVigenciaNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marProcessoFinanceiro","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marProcessoFinanceiroNovo","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marReenvioEmail","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContaEventual","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServicoNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServicoNovo","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marOrdemServico","IdContrato",'<?=$localIdContrato?>');
	addParmUrl("marOrdemServico","IdPessoa",'<?=$localIdPessoa?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
