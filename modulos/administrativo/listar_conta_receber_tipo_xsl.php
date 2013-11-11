<?
	$localModulo		=	1;
	$localOperacao		=	99;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'url',false);
	$localCampo						= url_string_xsl($_GET['Campo'],'');
	$localValor						= url_string_xsl($_GET['Valor'],'');
	$localIdTipoLancamento			= $_GET['IdTipoLancamento'];
	$localIdStatus					= $_GET['IdStatus'];
	$localIdLocalCobranca			= $_GET['IdLocalCobranca'];
	$localCancelado					= $_GET['Cancelado'];
	$localJuros						= $_GET['Juros'];
	$localSoma						= $_GET['Soma'];
	$localNotaFiscal				= $_GET['NotaFiscal'];
	$localBoleto					= $_GET['Boleto'];
	$local_IdPessoa					= $_GET['IdPessoa'];
	$local_IdContaReceber			= $_GET['IdContaReceber'];
	$localIdContrato				= $_GET['IdContrato'];
	$localIdContaEventual			= $_GET['IdContaEventual'];
	$localIdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
	$local_IdOrdemServico			= $_GET['IdOrdemServico'];
	$local_IdCarne					= $_GET['IdCarne'];
	$localLimit						= $_GET['Limit'];

	if($localOrdem == ''){							$localOrdem = "DataLancamento";		}
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
		<script type = 'text/javascript' src = 'js/conta_receber_tipo.js'></script>
	</head>
	<body  onLoad="ativaNome('Contas a Receber/Tipo')">
		<? include('filtro_conta_receber_tipo.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdContaReceber','number')">Id<?=compara($localOrdem,"IdContaReceber",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome','text')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?><?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('TipoLancamentoFinanceiro','text')">Tipo <?=compara($localOrdem,"TipoLancamentoFinanceiro",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('NumeroDocumento','number')">Nº Doc. <?=compara($localOrdem,"NumeroDocumento",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($localNotaFiscal == 2){
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('NumeroNF','number')\">Nº NF ".compara($localOrdem,"NumeroNF",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
					?>
					<td>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca','text')">Local Cob. <?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataLancamento','number')">Data Lanç.<?=compara($localOrdem,"DataLancamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Valor','number')">Valor (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataVencimento','number')">Vencimento <?=compara($localOrdem,"DataVencimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorRecebido','number')">Receb. (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorRecebido",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataRecebimento','number')">Pagamento <?=compara($localOrdem,"DataRecebimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoLocalRecebimento','text')">Local Receb. <?=compara($localOrdem,"DescricaoLocalRecebimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td />
					<td class='bt_lista' />
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
							<xsl:value-of select="IdContaReceber"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="TipoLancamentoFinanceiro"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="NumeroDocumento"/>
						</xsl:element>
					</xsl:element>
					<?
						if($localNotaFiscal == 2){
							echo"
							<xsl:element name='td'>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:element name='a'>
									<xsl:attribute name='href'>cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select='IdContaReceber'/></xsl:attribute>
									<xsl:value-of select='NumeroNF'/>
								</xsl:element>
							</xsl:element>
							";
						}
					?>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="AbreviacaoNomeLocalCobranca"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="DataLancamentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select='format-number(Valor,"0,00","euro")'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="DataVencimentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="ValorRecebidoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="DataRecebimentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="DescricaoLocalRecebimento"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href"><xsl:value-of select="Link"/></xsl:attribute>
							<xsl:attribute name="target"><xsl:value-of select="Target"/></xsl:attribute>
							<xsl:value-of select="MsgLink"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">bt_lista</xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
							<xsl:attribute name="alt">Cancelar?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdContaReceber"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='3'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td />
					<?
						if($localNotaFiscal == 2){
							echo"<td />";
						}
					?>
					<td />
					<td />
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorSoma),"0,00","euro")' /></td>
					<td />
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorRecebidoSoma),"0,00","euro")' /></td>
					<td colspan='5' />
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
<script>	
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marLancamentoFinanceiro","IdOrdemServico",'<?=$local_IdOrdemServico?>');
	addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",'<?=$local_IdProcessoFinanceiro?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContrato","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContratoNovo","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marReenvioEmail","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marReenvioEmail","IdContaReceber",'<?=$local_IdContaReceber?>');
	addParmUrl("marContaEventual","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$local_IdPessoa?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;

</script>
</xsl:template>
</xsl:stylesheet>

