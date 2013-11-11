<?
	$localModulo		=	1;
	$localOperacao		=	47;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'');
	$localCampo						= url_string_xsl($_GET['Campo'],'');
	$localValor						= url_string_xsl($_GET['Valor'],'');
	$localDesconto					= url_string_xsl($_GET['ValorDesconto'],'');
	$localIdLocalRecebimento		= $_GET['IdLocalRecebimento'];
	$localStatusParcela				= $_GET['StatusParcela'];
	$local_IdPessoa					= $_GET['IdPessoa'];
	$local_IdContaReceber			= $_GET['IdContaReceber'];
	$local_IdVendaProduto			= $_GET['IdVendaProduto'];
	$localLimit						= $_GET['Limit'];

	if($localOrdem == ''){							$localOrdem = "DataLancamento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localLimit == '' && $localFiltro == ''){	$localLimit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
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
	</head>
	<body  onLoad="ativaNome('Contas a Receber/Observações')">
		<? include('filtro_conta_receber_obs.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td>
						<a href="javascript:filtro_ordenar('IdVendaProduto','number')">Venda <?=compara($localOrdem,"IdVendaProduto",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdVendaProdutoParcela','number')">Parc. <?=compara($localOrdem,"IdVendaProdutoParcela",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataLancamento','number')">Data Lanç.<?=compara($localOrdem,"DataLancamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorLancamento','number')">Valor (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorLancamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('AbreviacaoNomeLocalCobranca')">Local Cob.<?=compara($localOrdem,"AbreviacaoNomeLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataVencimento','number')">Data Venc. <?=compara($localOrdem,"DataVencimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorDesconto','number')">Desc. (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"QuantDiaVenc",$ImgOrdernarASC,'')?></a>
					</td>
					<td style='width:180px'>
						<a href="javascript:filtro_ordenar('Obs','text')">Obs. <?=compara($localOrdem,"Obs",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataRecebimento','number')">Data Receb. <?=compara($localOrdem,"DataRecebimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorPago','number')">Pago (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorPago",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('StatusParcela')">Situação<?=compara($localOrdem,"StatusParcela",$ImgOrdernarASC,'')?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdVendaProduto"/>_<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select="IdVendaProduto"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select="IdVendaProdutoParcela"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select="DataLancamentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute> 
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorLancamento,"0,00","euro")' />
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select='AbreviacaoNomeLocalCobranca' />
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select="DataVencimentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute> 
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorDesconto,"0,00","euro")' />
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:attribute name="width">180px</xsl:attribute> 	
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select="Obs"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select="DataRecebimentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute> 
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select="ValorPagoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="VencidoBoleto"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdVendaProduto=<xsl:value-of select="IdVendaProduto"/>&amp;IdVendaProdutoParcela=<xsl:value-of select="IdVendaProdutoParcela"/></xsl:attribute>
							<xsl:value-of select="StatusParcela"/>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='4'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorLancamento),"0,00","euro")' /></td>
					<td colspan='2'/>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorDesconto),"0,00","euro")' /></td>
					<td colspan='2'/>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorPago),"0,00","euro")' /></td>
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
	</body>	
</html>
<script>
	addParmUrl("marPessoa","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContasReceber","IdContaReceber",'<?=$local_IdContaReceber?>');
	addParmUrl("marContasReceberObs","IdContaReceber",'<?=$local_IdContaReceber?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContasReceberObs","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marVenda","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marVenda","IdVendaProduto",'<?=$local_IdVendaProduto?>');
	addParmUrl("marVendaEntrega","IdVendaProduto",'<?=$local_IdVendaProduto?>');
	addParmUrl("marVendaEntregaNovo","IdVendaProduto",'<?=$local_IdVendaProduto?>');
	addParmUrl("marSistemaVenda","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marReenvioEmail","IdPessoa",'<?=$local_IdPessoa?>');

	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
<?
	mysql_close($con);
?>
