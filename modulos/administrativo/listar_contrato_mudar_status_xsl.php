<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"M";
		
	$localTituloOperacao	= "Contratos";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localPessoa					= url_string_xsl($_GET['Pessoa'],'url',false);
	$localDescricaoParametroServico	= url_string_xsl($_GET['DescricaoParametroServico'],'');
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'url',false);
	$localValorParametroServico		= url_string_xsl($_GET['ValorParametroServico'],'url',false);
	$localIdStatus					= url_string_xsl($_GET['IdStatus'],'');
	$localDataInicio				= $_GET['DataInicio'];
	$localDataTermino				= $_GET['DataTermino'];
	$Limit							= $_GET['Limit'];
	$Contratos						= $_GET['Contratos'];
	
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
		<script type = 'text/javascript' src = 'js/contrato_mudar_status.js'></script>
	</head>
	<body onLoad="ativaNome('Contrato/Alterar Status')">
		<? include("filtro_contrato_mudar_status.php"); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_contrato_mudar_status.php' onSubmit='return validarCheck()'>
				<input type='hidden' name='Contratos' value='<?=$Contratos?>' />
				<table class='tableListar' id='tableListar'  cellspacing='0'>
					<tr class='tableListarTitle'>
						<td class='id_listar'>
							<input style='border:0' type='checkbox' name='Todos' onClick="javascript:selecionaTodos(this)" title='Selecionar Todos'/>
						</td>
						<td class='id_listar'>
							<a href="javascript:filtro_ordenar('IdContrato','number')">Id<?=compara($localOrdem,"IdContrato",$ImgOrdernarASC,'')?></a>
						</td>
						<td>
							<a href="javascript:filtro_ordenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
						</td>
						<td>
							<a href="javascript:filtro_ordenar('DescricaoServico')">Nome Servi�o <?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
						</td>
						<td>
							<a href="javascript:filtro_ordenar('TipoContrato')">Tipo <?=compara($localOrdem,"TipoContrato",$ImgOrdernarASC,'')?></a>
						</td>
						<td style='width: 80px'>
							<a href="javascript:filtro_ordenar('DataInicio','number')">In�cio<?=compara($localOrdem,"DataInicio",$ImgOrdernarASC,'')?></a>
						</td>
						<td style='width: 80px'>
							<a href="javascript:filtro_ordenar('DataTermino','number')">T�rmino<?=compara($localOrdem,"DataTermino",$ImgOrdernarASC,'')?></a>
						</td>
						<td>
							<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')">Local Cob.<?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
						</td>
						<td class='valor'>
							<a href="javascript:filtro_ordenar('Valor','number')">Valor (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
						</td>
						<td class='valor'>
							<a href="javascript:filtro_ordenar('ValorDesconto','number')">Desc. (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorDesconto",$ImgOrdernarASC,'')?></a>
						</td>
						<td class='valor'>
							<a href="javascript:filtro_ordenar('ValorFinal','number')">Valor Final (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorFinal",$ImgOrdernarASC,'')?></a>
						</td>
						<td>
							<a href="javascript:filtro_ordenar('Status')">Status <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
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
							<input style='border:0'>
                                <xsl:attribute name="type">checkbox</xsl:attribute>
                                <xsl:attribute name="name">Status_<xsl:value-of select="IdContrato"/></xsl:attribute>
                                <xsl:attribute name="value"><xsl:value-of select="IdContrato"/></xsl:attribute>
                            </input>
						</td>
						<td class='sequencial'>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="IdContrato"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="Nome"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="DescricaoServico"/>
							</xsl:element>
						</td>
						<td style='width:30px'>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="TipoContrato"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="DataInicioTemp"/>
							</xsl:element>
						</td>							
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="DataTerminoTemp"/>
							</xsl:element>
						</td>	
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
							</xsl:element>
						</td>
						<td class='valor'>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="ValorTemp"/>
							</xsl:element>
						</td>
						<td class='valor'>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="ValorDescontoTemp"/>
							</xsl:element>
						</td>						
						<td class='valor'>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?acesso=<?=$acesso?>&amp;IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="ValorFinalTemp"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
								<xsl:value-of select="Status"/>
							</xsl:element>
						</td>
						<td class='bt_lista'>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="img">
								<xsl:attribute name="src">../../img/estrutura_sistema/ico_del_c.gif</xsl:attribute>
								<xsl:attribute name="alt">Excluir?</xsl:attribute>
							</xsl:element>
						</td>
					</xsl:element>
					</xsl:for-each>
					<tr class='tableListarTitle'>
						<td colspan='3' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
						<td />
						<td />
						<td />
						<td />
						<td />
						<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
						<td class='valor' id='tableListarDesconto'><xsl:value-of select='format-number(sum(db/reg/ValorDesconto),"0,00","euro")' /></td>
						<td class='valor' id='tableListarFinal'><xsl:value-of select='format-number(sum(db/reg/ValorFinal),"0,00","euro")' /></td>
						<td />
						<td />
					</tr>
				</table>
				<BR />
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px;'>
						<tr>
							<td class='campo'>
								<input type='submit' name='bt_alterar' value='Avan�ar' class='botao'/>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find' />
							<td><h1 id='helpText' name='helpText' /></td>
						</tr>
					</table>
				</div>	
			</form>
		</div>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
<script>
	iniciaListar();
	
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
