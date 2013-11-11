<?
	$localModulo		=	1;
	$localOperacao		=	29;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localDescricaoLocalCobranca	= url_string_xsl($_GET['DescricaoLocalCobranca'],'url',false);
	$localMesReferencia				= url_string_xsl($_GET['MesReferencia'],'');
	$localNomePessoa				= url_string_xsl($_GET['Nome'],'url',false);
	$localFormaCobranca				= url_string_xsl($_GET['FormaCobranca'],'');
	$localIdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
	$localIdPessoaEndereco			= $_GET['IdPessoaEndereco'];
	$localLimit						= $_GET['Limit'];
	$localIdPessoa					= $_GET['IdPessoa'];

	if($localOrdem == ''){							$localOrdem = "IdContaReceber";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localNomePessoa != ""){
		$localNomePessoa = str_replace("\\","",$localNomePessoa);
	}
	LimitVisualizacao('xsl');

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
		<script type = 'text/javascript' src = 'js/pessoa.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_forma_cobranca.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(195)?>')">
		<? include('filtro_pessoa_forma_cobranca.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<xsl:for-each select="db/reg">	
				<table class='tableListar' id='tableListar' cellspacing='0' style='border-bottom:2px #000 solid; margin-bottom:8px;'>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:element name="tr">
						<xsl:attribute name="class">tableListarTitle</xsl:attribute>
						<td colspan='6'>
							<?=dicionario(56)?>: 
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="IdContaReceber"/>
							</xsl:element>
						</td>
						<td colspan='2'><?=dicionario(200)?>: <xsl:value-of select="DataLancamento"/></td>
					</xsl:element>
					<xsl:element name="tr">
						<xsl:attribute name="cursor">normal</xsl:attribute>
						<td class='descCampo' style='width:80px' colspan='2'><xsl:value-of select="CampoNome"/></td>
						<td colspan='5'>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Nome"/>
							</xsl:element>
						</td>
					</xsl:element>
					<xsl:element name="tr">
						<td class='descCampo'  colspan='2'><?=dicionario(155)?></td>
						<td  colspan='5'>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:attribute name="cursor">normal</xsl:attribute>
								<xsl:value-of select="Endereco"/>
							</xsl:element>
						</td>
					</xsl:element>
					<xsl:element name="tr">
						<td class='descCampo' colspan='2'><xsl:value-of select="cpCNPJ"/></td>
						<td style='width:50%'>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:value-of select="CPF_CNPJ"/>
							</xsl:element>
						</td>
						<td class='descCampo' colspan='4'>						
							<table class='tableListar'>
								<tr>
									<td class='descCampo'>										
										<xsl:value-of select="cpIE"/>										
									</td>
									<td style='font-weight:normal;'>																				
										<xsl:value-of select="RG_IE"/>										
									</td>
								</tr>
							</table>							
						</td>
					</xsl:element>
					<xsl:element name="tr">
						<td class='descCampo' colspan='2'><?=dicionario(158)?></td>
						<td colspan='5'>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_pessoa.php?IdPessoa=<xsl:value-of select="IdPessoa"/></xsl:attribute>
								<xsl:value-of select="Telefone1"/>
							</xsl:element>		
						</td>
					</xsl:element>
					<tr class='tableListarTitle' style='background-color:#0065D5; '>
						<td style='border-bottom:1px #D4D0C8 solid;'><?=dicionario(82)?></td>
						<td style='border-bottom:1px #D4D0C8 solid;'><?=dicionario(201)?></td>
						<td style='border-bottom:1px #D4D0C8 solid;'><?=dicionario(125)?></td>
						<td style='text-align:center;  border-bottom:1px #D4D0C8 solid;'><?=dicionario(202)?></td>
						<td style='text-align:center;  border-bottom:1px #D4D0C8 solid;'><?=dicionario(203)?></td>
						<td style='border-bottom:1px #D4D0C8 solid;' class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
						<td style='border-bottom:1px #D4D0C8 solid;' class='valor'><?=dicionario(205)?> (<?=getParametroSistema(5,1)?>)</td>
						<td style='border-bottom:1px #D4D0C8 solid;' class='valor'><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</td>
					</tr>
					<xsl:for-each select="lancamentos">
					<xsl:element name="tr">
							<td style='width:40px'>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="Codigo"/></xsl:attribute>
									<xsl:value-of select="Tipo"/>
								</xsl:element>	
							</td>
							<td style='width:50px'>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="Codigo"/></xsl:attribute>
									<xsl:value-of select="Codigo"/>
								</xsl:element>	
							</td>
							<td>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="Codigo"/></xsl:attribute>
									<xsl:value-of select="Descricao"/>
								</xsl:element>	
							</td>
							<td style='text-align:center; padding-right:4px;'>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="Codigo"/></xsl:attribute>
									<xsl:value-of select="Referencia"/>
								</xsl:element>	
							</td>
							<td style='text-align:center;'>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="Codigo"/></xsl:attribute>
									<xsl:value-of select="DataVencimento"/>
								</xsl:element>	
							</td>
							<td class='valor'>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="Codigo"/></xsl:attribute>
									<xsl:value-of select='format-number(Valor,"0,00","euro")'/>
								</xsl:element>	
							</td>
							<td class='valor'>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="Codigo"/></xsl:attribute>
									<xsl:value-of select='format-number(ValorDescontoAConceber,"0,00","euro")'/>
								</xsl:element>	
							</td>
							<td class='valor'>
								<xsl:element name="a">
									<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="Codigo"/></xsl:attribute>
									<xsl:value-of select='format-number(ValorFinal,"0,00","euro")'/>
								</xsl:element>	
							</td>
						</xsl:element>
					</xsl:for-each>
					<xsl:if test="ValorDespesaLocalCobranca &gt; 0">
						<xsl:element name="tr">
							<td colspan='3'><?=dicionario(207)?></td>
							<td style='text-align:center'><?=dicionario(208)?></td>
							<td />
							<td class='valor'><xsl:value-of select='format-number(ValorDespesaLocalCobranca,"0,00","euro")' /></td>
							<td class='valor'>0,00</td>
							<td class='valor'><xsl:value-of select='format-number(ValorDespesaLocalCobranca,"0,00","euro")' /></td>
						</xsl:element>
					</xsl:if>
					<tr class='tableListarTitle' style='background-color:#0065D5' >
						<td colspan='4'><?=dicionario(128)?>: <xsl:value-of select="count(lancamentos)" /></td>
						<td />
						<xsl:if test="ValorDespesaLocalCobranca &gt; 0">	
							<td class='valor'><xsl:value-of select='format-number(sum(lancamentos/Valor)+ValorDespesaLocalCobranca,"0,00","euro")' /></td>
							<td class='valor'><xsl:value-of select='format-number(sum(lancamentos/ValorDescontoAConceber),"0,00","euro")' /></td>
							<td class='valor'><xsl:value-of select='format-number(sum(lancamentos/ValorFinal)+ValorDespesaLocalCobranca,"0,00","euro")' /></td>
						</xsl:if>
						<xsl:if test="ValorDespesaLocalCobranca = 0">
							<td class='valor'><xsl:value-of select='format-number(sum(lancamentos/Valor),"0,00","euro")' /></td>
							<td class='valor'><xsl:value-of select='format-number(sum(lancamentos/ValorDescontoAConceber),"0,00","euro")' /></td>
							<td class='valor'><xsl:value-of select='format-number(sum(lancamentos/ValorFinal),"0,00","euro")' /></td>
						</xsl:if>
					</tr>
				</table>
			</xsl:for-each>
			<table class='tableListar' style='margin-top: 10px' cellspacing='0'>		
				<tr class='tableListarTitle'>
					<td colspan='0' id='tableListarTotal'><?=dicionario(209)?>: <xsl:value-of select="count(db/reg)" /></td>					
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
