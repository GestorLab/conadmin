<?php 
	$localModulo			= 1;
	$localOperacao			= 58;
	$localSuboperacao		= "M";
	$localTituloOperacao	= "Contratos";

	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
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
	
	if($acesso == ''){
		$acesso = 'filtro_contrato.php';
	}

	if($localOrdem == ''){
		$localOrdem = "Nome";
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
		<script type='text/javascript' src='js/conta_receber_mudar_status.js'></script>
	</head>
	<body onLoad="ativaNome('<?php echo dicionario(991); ?>')">
		<?php 
			include("filtro_conta_receber_mudar_status.php"); 
		?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_conta_receber_mudar_status.php' onSubmit='return validarCheck()'>
				<input type='hidden' name='ContasReceber' value='<?php echo $ContasReceber; ?>' />
				<table class='tableListar' id='tableListar' cellspacing='0'>
					<tr class='tableListarTitle'>
						<td class='id_listar'>
							<input style='border:0' type='checkbox' name='Todos' onClick="javascript:selecionaTodos(this)" title='Selecionar Todos'/>
						</td>
						<td class='id_listar'>
							<!-- Id -->
							<a href="javascript:filtro_ordenar('IdContaReceber','number')"><?php echo dicionario(141)." ".compara($localOrdem,"IdContaReceber",$ImgOrdernarASC,''); ?></a>
						</td>
						<td>
							<!-- Receb. -->
							<a href="javascript:filtro_ordenar('IdRecibo','number')"><?php echo dicionario(424)." ".compara($localOrdem,"IdRecibo",$ImgOrdernarASC,''); ?></a>
						</td>
						<td>
							<!-- Nome/Razão Social -->
							<a href="javascript:filtro_ordenar('Nome','text')"><?php echo visualizarBuscaPessoa(getCodigoInterno(3,24))." ".compara($localOrdem,"Nome",$ImgOrdernarASC,''); ?></a>
						</td>
						<td>
							<!-- Local Cob. -->
							<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')"><?php echo dicionario(290)." ".compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,''); ?></a>
						</td>
						<td style='width: 80px'>
							<!-- Nº Doc. -->
							<a href="javascript:filtro_ordenar('NumeroDocumento','number')"><?php echo dicionario(423)." ".compara($localOrdem,"NumeroDocumento",$ImgOrdernarASC,''); ?></a>
						</td>
						<td style='width: 80px'>
							<!-- Cidade-UF -->
							<a href="javascript:filtro_ordenar('NomeCidade','text')"><?php echo dicionario(993)." ".compara($localOrdem,"NomeCidade",$ImgOrdernarASC,''); ?></a>
						</td>
						<td class='valor'>
							<!-- Valor (R$) -->
							<a href="javascript:filtro_ordenar('ValorFinal','number')"><?php echo dicionario(204)." (".getParametroSistema(5,1).") ".compara($localOrdem,"ValorFinal",$ImgOrdernarASC,''); ?></a>
						</td>
						<td>
							<!-- Vencimento -->
							<a href="javascript:filtro_ordenar('DataVencimento','number')"><?php echo dicionario(229)." ".compara($localOrdem,"DataVencimento",$ImgOrdernarASC,''); ?></a>
						</td>
						<td class='valor'>
							<!-- Receb. (R$) -->
							<a href="javascript:filtro_ordenar('ValorRecebido','number')"><?php echo dicionario(424)." (".getParametroSistema(5,1).") ".compara($localOrdem,"ValorRecebido",$ImgOrdernarASC,''); ?></a>
						</td>
						<td>
							<!-- Pagamento -->
							<a href="javascript:filtro_ordenar('DataRecebimento','number')"><?php echo dicionario(425)." ".compara($localOrdem,"DataRecebimento",$ImgOrdernarASC,''); ?></a>
						</td>
						<td>
							<!-- Local Receb. -->
							<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobrancaRecebimento','text')"><?php echo dicionario(426)." ".compara($localOrdem,"AbreviacaoNomeLocalCobrancaRecebimento",$ImgOrdernarASC,''); ?></a>
						</td>
						<td>
							<!-- Status -->
							<a href="javascript:filtro_ordenar('Status')"><?php echo dicionario(140)." ".compara($localOrdem,"Status",$ImgOrdernarASC,''); ?></a>
						</td>
						<td class='bt_lista' />
					</tr>
					<xsl:for-each select="db/reg">
					<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
					<xsl:element name="tr">
						<xsl:attribute name="class">tableListarDados</xsl:attribute>
						<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
						<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
						<xsl:attribute name="accessKey"><xsl:value-of select="IdContaReceber"/></xsl:attribute>
						<td class='sequencial'>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<input style='border:0'>
                                <xsl:attribute name="type">checkbox</xsl:attribute>
                                <xsl:attribute name="name">Status_<xsl:value-of select="IdContaReceber"/></xsl:attribute>
                                <xsl:attribute name="value"><xsl:value-of select="IdContaReceber"/></xsl:attribute>
                            </input>
						</td>
						<td class='sequencial'>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="IdContaReceber"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="IdRecibo"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Nome"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoLocalCobranca"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="NumeroDocumento"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="NomeCidade"/>-<xsl:value-of select="SiglaEstado"/>
							</xsl:element>
						</td>
						<td class='valor'>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?acesso=<?=$acesso?>&amp;IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="ValorFinalTemp"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="DataVencimentoTemp"/>
							</xsl:element>
						</td>
						<td class='valor'>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="ValorRecebidoTemp"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="DataRecebimentoTemp"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="AbreviacaoNomeLocalCobrancaRecebimento"/>
							</xsl:element>
						</td>
						<td>
							<xsl:attribute name="title"><xsl:value-of select="DescricaoParametroServico"/></xsl:attribute>
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_contrato.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
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
						<td colspan='6' id='tableListarTotal'>Total: <xsl:value-of select="count(db/reg)" /></td>
						<td />
						<td class='valor' id='tableListarDesconto'><xsl:value-of select='format-number(sum(db/reg/ValorFinal),"0,00","euro")' /></td>
						<td />
						<td class='valor' id='tableListarFinal'><xsl:value-of select='format-number(sum(db/reg/ValorRecebido),"0,00","euro")' /></td>
						<td colspan='4' />
					</tr>
				</table>
				<BR />
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px;'>
						<tr>
							<td class='campo'>
								<input type='submit' name='bt_alterar' value='Avançar' class='botao'/>
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
		<script type='text/javascript'>
			iniciaListar();
			tableMultColor('tableListar',document.filtro.corRegRand.value);
			
			menu_form = false;
		</script>
	</body>	
</html>
</xsl:template>
</xsl:stylesheet>