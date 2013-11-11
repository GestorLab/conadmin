<?
	$localModulo		=	1;
	$localOperacao		=	69;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro						= $_GET['Filtro'];
	$localOrdem							= $_GET['Ordem'];
	$localOrdemDirecao					= $_GET['OrdemDirecao'];
	$localTipoDado						= $_GET['TipoDado'];
	$localIdLocalCobranca				= $_GET['IdLocalCobranca'];
	$localLoginCriacao					= url_string_xsl($_GET['LoginCriacao'],'URL',false);
	$localNomeCidade					= url_string_xsl($_GET['NomeCidade'],'URL',false);
	$localDataInicio					= url_string_xsl($_GET['DataInicio'],'URL',false);
	$localDataFim						= url_string_xsl($_GET['DataFim'],'URL',false);
	$localIdLocalCobrancaRecebimento	= $_GET['IdLocalCobrancaRecebimento'];
	$localIdStatus						= $_GET['IdStatus'];
	$localIdGrupoPessoa					= $_GET['IdGrupoPessoa'];
	$local_IdPessoa						= $_GET['IdPessoa'];
	$localTipoPessoa					= $_GET['TipoPessoa'];
	$local_IdContaReceber				= $_GET['IdContaReceber'];
	$localIdContrato					= $_GET['IdContrato'];
	$localIdPais						= $_GET['IdPais'];
	$localNotaFiscal					= $_GET['NotaFiscal'];
	$localIdEstado						= $_GET['IdEstado'];
	$localIdContaEventual				= $_GET['IdContaEventual'];
	$localIdProcessoFinanceiro			= $_GET['IdProcessoFinanceiro'];
	$local_IdOrdemServico				= $_GET['IdOrdemServico'];
	$localCancelado						= $_GET['Cancelado'];
	$localData							= $_GET['Data'];
	$localLimit							= $_GET['Limit'];
	$local_IdServico					= $_GET['IdServico'];
	$localDescricaoServico				= url_string_xsl($_GET['DescricaoServico'],"URL",false);
	$localEndereco						= url_string_xsl($_GET['Endereco'],"URL",false);
	$localBairro						= url_string_xsl($_GET['Bairro'],"URL",false);
	$localIdGrupoPessoa					= $_GET['IdGrupoPessoa'];
	$localTipo							= $_GET['Tipo'];
	$ColSpan							= 4;
		
	$localCPFCNPJ						=	$_SESSION["filtro_cpf_cnpj"];
	$localOcultaCidadeUF			 	= 	$_SESSION["filtro_oculta_cidade_uf"];

	if($localOrdem == ''){							$localOrdem = "DataRecebimento";		}
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
		<script type = 'text/javascript' src = 'js/conta_receber_movimentacao.js'></script>
	</head>
	<body  onLoad="ativaNome('Contas a Receber/Movimentação Diária')">
		<? include('filtro_conta_receber_movimentacao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdContaReceber','number')">Id<?=compara($localOrdem,"IdContaReceber",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdContaReceberRecebimento','number')">Receb.<?=compara($localOrdem,"IdContaReceberRecebimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome','text')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?><?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td style="width: 35px">
						<a href="javascript:filtro_ordenar('Tipo','text')">Tipo<?=compara($localOrdem,"Tipo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdLancamentoFinanceiro','text')">Lanç. Fin. <?=compara($localOrdem,"IdLancamentoFinanceiro",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($localCPFCNPJ == 2){
							$ColSpan++;
							echo "
							<td>
								<a href=\"javascript:filtro_ordenar('CPF_CNPJ','text')\">CPF/CNPJ ".compara($localOrdem,"CPF_CNPJ",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
						
						if($localNotaFiscal == 2){
							$ColSpan++;
							echo"
							<td>
								<a href=\"javascript:filtro_ordenar('NumeroNF','number')\">Nº NF ".compara($localOrdem,"NumeroNF",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
					?>
					<td>
						<a href="javascript:filtro_ordenar('NumeroDocumento','number')">Nº Doc. <?=compara($localOrdem,"NumeroDocumento",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($localOcultaCidadeUF == 2){
							$ColSpan++;
							echo "
							<td>
								<a href=\"javascript:filtro_ordenar('NomeCidade','text')\">Cidade-UF ".compara($localOrdem,"NomeCidade",$ImgOrdernarASC,'')."</a>
							</td>
							";
						}
					?>
					<td class='valor' style='width: 85px'>
						<a href="javascript:filtro_ordenar('Valor','number')">Valor (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataVencimento','number')">Vencimento <?=compara($localOrdem,"DataVencimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Desc.','number')">Desc. (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"Desc.",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Multa','number')">Multa. (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"Multa",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorRecebido','number')">Receb. (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorRecebido",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataRecebimento','number')">Pagamento <?=compara($localOrdem,"DataRecebimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataCriacao','number')">Movimentação <?=compara($localOrdem,"DataCriacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoLocalRecebimento','text')">Local Receb. <?=compara($localOrdem,"DescricaoLocalRecebimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContaReceber"/>_<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select="IdContaReceber"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select="IdContaReceberRecebimento"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:element name="table">
							<xsl:attribute name="border">0</xsl:attribute>
							<xsl:attribute name="cellspacing">0</xsl:attribute>
							<xsl:attribute name="cellpadding">0</xsl:attribute>
							<xsl:for-each select="Lancamentos/LancamentoFinanceiro">
								<xsl:element name="tr">
									<xsl:element name="td">
										<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
										<xsl:attribute name="valign">top</xsl:attribute>
										<xsl:element name="a">
											<xsl:attribute name="style">font-size: 7pt</xsl:attribute>
											<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
											<xsl:value-of select="Tipo"/>
										</xsl:element>
									</xsl:element>
								</xsl:element>
							</xsl:for-each>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:element name="table">
							<xsl:attribute name="border">0</xsl:attribute>
							<xsl:attribute name="cellspacing">0</xsl:attribute>
							<xsl:attribute name="cellpadding">0</xsl:attribute>
							<xsl:for-each select="Lancamentos/LancamentoFinanceiro">
								<xsl:element name="tr">
									<xsl:element name="td">
										<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
										<xsl:attribute name="valign">top</xsl:attribute>
										<xsl:element name="a">
											<xsl:attribute name="style">font-size: 7pt</xsl:attribute>
											<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
											<xsl:value-of select="IdLancamentoFinanceiro"/>
										</xsl:element>
									</xsl:element>
								</xsl:element>
							</xsl:for-each>
						</xsl:element>
					</xsl:element>
					<?
						if($localCPFCNPJ == 2){
							echo "
							<xsl:element name='td'>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:attribute name='style'>border-bottom: solid 1px #A8A8A8</xsl:attribute>
								<xsl:attribute name='valign'>top</xsl:attribute>
								<xsl:element name='a'>
									<xsl:attribute name='href'>cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select='IdContaReceber'/>&amp;IdContaReceberRecebimento=<xsl:value-of select='IdContaReceberRecebimento'/></xsl:attribute>
									<xsl:value-of select='CPF_CNPJ'/>
								</xsl:element>
							</xsl:element>
							";
						}
						
						if($localNotaFiscal == 2){
							echo"
							<xsl:element name='td'>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:attribute name='style'>border-bottom: solid 1px #A8A8A8</xsl:attribute>
								<xsl:attribute name='valign'>top</xsl:attribute> 
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
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select="NumeroDocumento"/>
						</xsl:element>
					</xsl:element>
					<?
						if($localOcultaCidadeUF == 2){
							echo "
							<xsl:element name='td'>
								<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
								<xsl:attribute name='style'>border-bottom: solid 1px #A8A8A8</xsl:attribute>
								<xsl:attribute name='valign'>top</xsl:attribute>
								<xsl:element name='a'>
									<xsl:attribute name='href'>cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select='IdContaReceber'/>&amp;IdContaReceberRecebimento=<xsl:value-of select='IdContaReceberRecebimento'/></xsl:attribute>
									<xsl:value-of select='NomeCidade'/>
								</xsl:element>
							</xsl:element>
							";
						}
					?>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:attribute name="style">text-align: right;border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:element name="table">
							<xsl:attribute name="border">0</xsl:attribute>
							<xsl:attribute name="cellspacing">0</xsl:attribute>
							<xsl:attribute name="cellpadding">0</xsl:attribute>
							<xsl:attribute name="style">width: 85px</xsl:attribute>
							<xsl:for-each select="Lancamentos/LancamentoFinanceiro">
								<xsl:element name="tr">
									<xsl:element name="td">
										<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
										<xsl:attribute name="style">text-align: right</xsl:attribute>
										<xsl:element name="a">
											<xsl:attribute name="style">font-size: 7pt</xsl:attribute>
											<xsl:attribute name="href">cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=<xsl:value-of select="IdLancamentoFinanceiro"/></xsl:attribute>
											<xsl:value-of select='format-number(ValorLancamento,"0,00","euro")'/>
										</xsl:element>
									</xsl:element>
								</xsl:element>
							</xsl:for-each>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select="DataVencimentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorDesconto,"0,00","euro")'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorMoraMulta,"0,00","euro")'/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:attribute name="class">valor</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select="ValorRecebidoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select="DataRecebimentoTemp"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select="DataCriacao"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8;text-align: center;</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber_recebimento.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/>&amp;IdContaReceberRecebimento=<xsl:value-of select="IdContaReceberRecebimento"/></xsl:attribute>
							<xsl:value-of select="DescricaoLocalRecebimento"/>
						</xsl:element>
					</xsl:element>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="style">border-bottom: solid 1px #A8A8A8</xsl:attribute>
						<xsl:attribute name="valign">top</xsl:attribute>
						<xsl:attribute name="class">bt_lista</xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href"><xsl:value-of select="Link"/></xsl:attribute>
							<xsl:element name="img">
								<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
								<xsl:attribute name="alt">Cancelar?</xsl:attribute>
							</xsl:element>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='<?=$ColSpan?>'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td />
					<td />
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/Lancamentos/LancamentoFinanceiro/ValorLancamento),"0,00","euro")' /></td>
					<td />
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorDesconto),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorMoraMulta),"0,00","euro")' /></td>
					<td class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorRecebido),"0,00","euro")' /></td>
					<td colspan='6' />
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

