<?
	$localModulo		=	1;
	$localOperacao		=	108;
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
	$localNome						= url_string_xsl($_GET['Nome'],'url',false);
	$localFormaCobranca				= url_string_xsl($_GET['FormaCobranca'],'');
	$localIdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
	$localLimit						= $_GET['Limit'];
	$localIdPessoa					= $_GET['IdPessoa'];
	$localIdStatus					= $_GET['IdStatus'];
	$localIdLocalCobranca			= $_GET['IdLocalCobranca'];
	$localFormaCobranca				= $_GET['FormaCobranca'];
	$localDataInicio				= url_string_xsl($_GET['DataInicio'],'');
	$localDataTermino				= url_string_xsl($_GET['DataTermino'],'');
	$localBoleto					= $_GET['Boleto'];
	$localIdContratoStatus 			= $_GET['IdContratoStatus'];
	$localIdEstado					= $_GET['IdEstado'];
	$localIdCidade					= $_GET['IdCidade'];
	$localBairro					= url_string_xsl($_GET['Bairro'],'url',false);
	

	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}

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
		<script type = 'text/javascript' src = 'js/conta_receber.js'></script>
		<script type = 'text/javascript' src = 'js/conta_receber_reaviso.js'></script>
	</head>
	<body  onLoad="ativaNome('Conta a Receber/Reaviso')">
		<? include('filtro_conta_receber_reaviso.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<xsl:for-each select="db/reg">	
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />
				<table class='tableListar' cellspacing='0' style='border-bottom:2px #000 solid; margin-bottom:0px;'>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:element name="tr">
						<xsl:attribute name="class">tableListarTitle</xsl:attribute>
						<td colspan='5'>Dados da Pessoa:</td>
					</xsl:element>
					<xsl:element name="tr">
						<xsl:attribute name="cursor">normal</xsl:attribute>
						<td class='descCampo' style='width:200px'><xsl:value-of select="CampoNome"/></td>
						<td class='descCampo' style='width:120px'>Fone Residencial</td>	
						<td class='descCampo' style='width:140px'>Fone Residencial(1)</td>	
						<td class='descCampo' colspan='0' style='width:150px'>Fone Residencial(2)</td>
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
						<td class='descCampo'>Email</td>
						<td class='descCampo'>Fax</td>
						<td class='descCampo'>Complemento Fone</td>
						<td class='descCampo'>Celular</td>
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
						<td class='descCampo'  colspan='4'>Endereço</td>
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
					<tr>
						<td colspan='4'>	
							<table class='tableListar' cellspacing='0'>
								<tr class='tableListarTitle' style='background-color:#0065D5; '>
									<td style='border-bottom:1px #D4D0C8 solid; width: 80px'>Vencimento</td>
									<td style='border-bottom:1px #D4D0C8 solid; width: 80px'>Tipo</td>
									<td style='border-bottom:1px #D4D0C8 solid;'>Conta Receber</td>
									<td style='text-align:center;  border-bottom:1px #D4D0C8 solid;'>Nº Documento</td>
									<td style='border-bottom:1px #D4D0C8 solid;' colspan='2' class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
									<td style='border-bottom:1px #D4D0C8 solid;' colspan='2' class='valor'>Valor Multa/Juros (<?=getParametroSistema(5,1)?>)</td>
									<td style='border-bottom:1px #D4D0C8 solid;' colspan='2' class='valor'>Valor Atualizado (<?=getParametroSistema(5,1)?>)</td>
								</tr>
								<xsl:for-each select="lancamentos">
									<xsl:element name="tr">
										<td style='width:80px'>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
												<xsl:value-of select="DataVencimento"/>
											</xsl:element>
										</td>
										<td style='width:50px'>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
												<xsl:value-of select="Tipo"/>
											</xsl:element>
										</td>
										<td>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
												<xsl:value-of select="IdContaReceber"/>
											</xsl:element>
										</td>
										<td style='text-align:center;'>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
												<xsl:value-of select="NumeroDocumento"/>
											</xsl:element>
										</td>
										<td class='valor' colspan='2'>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
												<xsl:value-of select='format-number(Valor,"0,00","euro")'/>
											</xsl:element>
										</td>
										<td class='valor' colspan='2'>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
												<xsl:value-of select='format-number(ValorMultaJurosAtualizado,"0,00","euro")'/>
											</xsl:element>
										</td>
										<td class='valor' colspan='2'>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
												<xsl:value-of select='format-number(ValorFinalAtualizado,"0,00","euro")'/>
											</xsl:element>
										</td>
									</xsl:element>
								</xsl:for-each>
								<tr class='tableListarTitle' style='background-color:#0065D5' >
									<td colspan='5'>Total: <xsl:value-of select="count(lancamentos)" /></td>
									<td class='valor'><xsl:value-of select='format-number(sum(lancamentos/Valor),"0,00","euro")' /></td>
									<td class='valor' colspan='2'><xsl:value-of select='format-number(sum(lancamentos/ValorMultaJurosAtualizado),"0,00","euro")' /></td>
									<td class='valor'><xsl:value-of select='format-number(sum(lancamentos/ValorFinalAtualizado),"0,00","euro")' /></td>
									<td></td>
									<td></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</xsl:for-each>
			<table border='0' class='tableListar' style='margin-top: 10px' cellspacing='0'>
				<tr class='tableListarTitle'>
					<td width="180" id='tableListarTotal'>Total de Registros: <xsl:value-of select="count(db/reg)" /></td>
			
					<td width="250" >Valor atualizado para dia <? echo date("d/m/Y");?></td>
					<td width="210"  class="valor">Valor total: <xsl:value-of select='format-number(sum(db/reg/lancamentos/Valor),"0,00","euro")' /></td>
					<td width="281" class="valor">Valor Total Multa/Juros: <xsl:value-of select='format-number(sum(db/reg/lancamentos/ValorMultaJurosAtualizado),"0,00","euro")' /></td>
					<td class="valor">Valor Total Atualizado: <xsl:value-of select='format-number(sum(db/reg/lancamentos/ValorFinalAtualizado),"0,00","euro")' /></td>
					
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
