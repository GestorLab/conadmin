<?php
	$localModulo		= 1;
	$localOperacao		= 178;
	$localSuboperacao	= "R";
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localPessoa				= url_string_xsl($_GET['Pessoa'],'URL',false);
	$localData					= $_GET['Data'];
	$localDataInicio			= $_GET['DataInicio'];
	$localDataFim				= $_GET['DataFim'];
	$localIdStatusCaixa			= $_GET['IdStatusCaixa'];
	$localIdTipoMovimentacao	= $_GET['IdTipoMovimentacao'];
	$localIdStatusMovimentacao	= $_GET['IdStatusMovimentacao'];
	$localLimit					= $_GET['Limit'];
	$localIdPessoa				= $_GET['IdPessoa'];
	
	if($localOrdem == ""){
		$localOrdem = "IdCaixaMovimentacao";
	}
	
	if($localOrdemDirecao == ""){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localNomePessoa != ""){
		$localNomePessoa = str_replace("\\", "", $localNomePessoa);
	}
	
	if($localTipoDado == ""){
		$localTipoDado = "number";
	}
	
	LimitVisualizacao("xsl");
	
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
				
				<script type='text/javascript' src='../../js/funcoes.js'></script>
				<script type='text/javascript' src='../../js/incremental_search.js'></script>
				<script type='text/javascript' src='../../js/mensagens.js'></script>
				<script type='text/javascript' src='../../js/event.js'></script>
				<script type='text/javascript' src='js/pessoa.js'></script>
				<script type='text/javascript' src='js/pessoa_forma_cobranca.js'></script>
				
				<style type="text/css">
					.tableListarTitlePrint 
					{
						font-size: 16px;
						font-weight: bold;
					}
					.line
					{
						border-bottom: 1px solid #aaaaaa;
						padding-bottom: 1px;
					}
				</style>
			</head>
			<body onLoad="ativaNome('<?php echo dicionario(953); ?>')">
				<?php #include("filtro_caixa.php"); ?>
				<?php include("filtro_caixa_movimentacao.php"); ?>
				<div id='carregando'><?php echo dicionario(17); ?></div>
				<div id='conteudo'>
					<xsl:element name="div">
						<xsl:attribute name="id">tableListar</xsl:attribute>
						<xsl:for-each select="db/reg">
							<!-- INÍCIO ITEM RELATÓRIO -->
							<xsl:element name="div">
								<xsl:attribute name="class">ocultarPrint</xsl:attribute>
								<xsl:element name="table">
									<xsl:attribute name="class">tableListar</xsl:attribute>
									<xsl:attribute name="cellspacing">0</xsl:attribute>
									<xsl:attribute name="style">border-bottom:2px #000 solid; margin-bottom:8px;</xsl:attribute>
									<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
									<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
									<xsl:element name="tr">
										<xsl:attribute name="class">tableListarTitle</xsl:attribute>
										<xsl:element name="td">
											<xsl:attribute name="colspan">2</xsl:attribute>
											<?php echo dicionario(171); ?>: <!-- Caixa -->
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
												<xsl:value-of select="IdCaixa"/>
											</xsl:element>
										</xsl:element>
										<xsl:element name="td">
											<xsl:attribute name="style">width:242px;</xsl:attribute>
											<?php echo dicionario(140); ?>: <!-- Status -->
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
												<xsl:value-of select="Status"/>
											</xsl:element>
										</xsl:element>
									</xsl:element>
									<xsl:element name="tr">
										<xsl:element name="td">
											<xsl:attribute name="colspan">2</xsl:attribute>
											<xsl:element name="b"><?php echo dicionario(488); ?>: </xsl:element> <!-- Responsável -->
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
												<xsl:value-of select="NomeResponsavel"/>
											</xsl:element>
										</xsl:element>
										<xsl:element name="td">
											<xsl:element name="b"><?php echo dicionario(388); ?>: </xsl:element> <!-- Data Abertura -->
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
												<xsl:value-of select="DataAberturaTemp"/>
											</xsl:element>
										</xsl:element>
									</xsl:element>
									<xsl:element name="tr">
										<xsl:element name="td">
											<xsl:attribute name="colspan">2</xsl:attribute>
											<xsl:element name="b"><?php echo dicionario(949); ?>: </xsl:element> <!-- Usuário Abertura -->
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
												<xsl:value-of select="LoginAbertura"/>
											</xsl:element>
										</xsl:element>
										<xsl:element name="td">
											<xsl:element name="b"><?php echo dicionario(951); ?>: </xsl:element> <!-- Data Fechamento -->
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
												<xsl:choose>
													<xsl:when test="DataFechamentoTemp = ''">
														<xsl:value-of select="Status"/>
													</xsl:when>
													<xsl:otherwise>
														<xsl:value-of select="DataFechamentoTemp"/>
													</xsl:otherwise>
												</xsl:choose>
											</xsl:element>
										</xsl:element>
									</xsl:element>
								</xsl:element>
							</xsl:element>
							<!-- FIM ITEM RELATÓRIO -->
							<!-- INÍCIO ITEM IMPRESSÃO -->
							<xsl:element name="div">
								<xsl:attribute name="class">ocultar</xsl:attribute>
								<xsl:element name="table">
									<xsl:attribute name="class">tableListar</xsl:attribute>
									<xsl:attribute name="cellspacing">0</xsl:attribute>
									<xsl:attribute name="style">margin-bottom:8px;</xsl:attribute>
									<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
									<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
									<xsl:element name="tr">
										<xsl:attribute name="class">tableListarTitlePrint</xsl:attribute>
										<xsl:element name="td">
											<xsl:attribute name="class">line</xsl:attribute>
											<xsl:attribute name="colspan">2</xsl:attribute>
											<?php echo dicionario(171); ?>: <!-- Caixa -->
											<xsl:value-of select="IdCaixa"/>
										</xsl:element>
										<xsl:element name="td">
											<xsl:attribute name="class">line</xsl:attribute>
											<xsl:attribute name="style">width:181px; font-size:12px; font-weight:normal;</xsl:attribute>
											<?php echo date("d/m/Y H:i:s"); ?> <!-- Data impressão -->
										</xsl:element>
										<xsl:element name="td">
											<xsl:attribute name="class">line</xsl:attribute>
											<xsl:attribute name="style">width:323px; text-align:right;</xsl:attribute>
											<?php echo dicionario(140); ?>: <!-- Status -->
											<xsl:value-of select="Status"/>
										</xsl:element>
									</xsl:element>
								</xsl:element>
								<xsl:element name="div">
									<xsl:attribute name="style">padding:4px 8px 1px; border:1px solid #000;</xsl:attribute>
									<xsl:element name="table">
										<xsl:attribute name="style">width:100%;</xsl:attribute>
										<xsl:attribute name="cellspacing">0</xsl:attribute>
										<xsl:element name="tr">
											<xsl:element name="td">
												<xsl:attribute name="colspan">2</xsl:attribute>
												<xsl:element name="b"><?php echo dicionario(488); ?>: </xsl:element> <!-- Responsável -->
												<xsl:value-of select="NomeResponsavel"/>
											</xsl:element>
											<xsl:element name="td">
												<xsl:attribute name="style">width:242px;</xsl:attribute>
												<xsl:element name="b"><?php echo dicionario(388); ?>: </xsl:element> <!-- Data Abertura -->
												<xsl:value-of select="DataAberturaTemp"/>
											</xsl:element>
										</xsl:element>
										<xsl:element name="tr">
											<xsl:element name="td">
												<xsl:attribute name="colspan">2</xsl:attribute>
												<xsl:element name="b"><?php echo dicionario(949); ?>: </xsl:element> <!-- Usuário Abertura -->
												<xsl:value-of select="LoginAbertura"/>
											</xsl:element>
											<xsl:element name="td">
												<xsl:element name="b"><?php echo dicionario(951); ?>: </xsl:element> <!-- Data Fechamento -->
												<xsl:choose>
													<xsl:when test="DataFechamentoTemp = ''">
														<xsl:value-of select="Status"/>
													</xsl:when>
													<xsl:otherwise>
														<xsl:value-of select="DataFechamentoTemp"/>
													</xsl:otherwise>
												</xsl:choose>
											</xsl:element>
										</xsl:element>
									</xsl:element>
								</xsl:element>
								<xsl:element name="div">
									<xsl:attribute name="class">line</xsl:attribute>
									<xsl:attribute name="style">margin-top:16px;</xsl:attribute>
									<xsl:element name="div">
										<xsl:attribute name="class">tableListarTitlePrint</xsl:attribute>
										<?php echo dicionario(911); ?> <!-- Movimentação -->
									</xsl:element>
								</xsl:element>
							</xsl:element>
							<!-- FIM ITEM IMPRESSÃO -->
							<xsl:for-each select="Movimentacao">
								<!-- INÍCIO ITEM RELATÓRIO -->
								<xsl:element name="div">
									<xsl:attribute name="class">ocultarPrint</xsl:attribute>
									<xsl:element name="table">
										<xsl:attribute name="class">tableListar</xsl:attribute>
										<xsl:attribute name="cellspacing">0</xsl:attribute>
										<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
										<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
										<xsl:attribute name="style">border-bottom:2px #000 solid; margin-bottom:8px;</xsl:attribute>
										<xsl:element name="tr">
											<xsl:attribute name="class">tableListarTitle</xsl:attribute>
											<xsl:element name="td">
												<xsl:element name="b"><?php echo dicionario(911); ?>: </xsl:element> <!-- Movimentação -->
												<xsl:element name="a">
													<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
													<xsl:value-of select="IdCaixaMovimentacao"/>
												</xsl:element>
											</xsl:element>
											<xsl:element name="td">
												<xsl:attribute name="style">width:282px;</xsl:attribute>
												<xsl:element name="b"><?php echo dicionario(732); ?>: </xsl:element> <!-- Tipo Movimentação -->
												<xsl:element name="a">
													<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
													<xsl:value-of select="TipoMovimentacao"/>
												</xsl:element>
											</xsl:element>
											<xsl:element name="td">
												<xsl:attribute name="style">width:161px;</xsl:attribute> <!-- Status -->
												<?php echo dicionario(140); ?>: 
												<xsl:element name="a">
													<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
													<xsl:value-of select="Status"/>
												</xsl:element>
											</xsl:element>
										</xsl:element>
										<xsl:for-each select="Item">
											<xsl:element name="tr">
												<xsl:element name="td">
													<xsl:attribute name="class">tableListarSubTitle</xsl:attribute>
													<xsl:attribute name="colspan">4</xsl:attribute>
													<?php echo dicionario(928); ?>: <!-- Item -->
													<xsl:element name="a">
														<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
														<xsl:value-of select="IdCaixaItem"/>
													</xsl:element>
												</xsl:element>
											</xsl:element>
											<xsl:element name="tr">
												<xsl:element name="td">
													<xsl:attribute name="style">padding-bottom:4px;</xsl:attribute>
													<xsl:attribute name="colspan">4</xsl:attribute>
													<xsl:element name="table">
														<xsl:attribute name="class">tableListar</xsl:attribute>
														<xsl:attribute name="cellspacing">0</xsl:attribute>
														<xsl:element name="tr">
															<xsl:element name="td">
																<xsl:attribute name="style">min-width:141px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(670); ?>: </xsl:element> <!-- Conta Receber -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="IdContaReceber"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">min-width:166px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(681); ?>: </xsl:element> <!-- Número Documento -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="NumeroDocumento"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="colspan">2</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(85); ?>: </xsl:element> <!-- Nome Pessoa -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="Nome"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:element name="b"><?php echo dicionario(399); ?>: </xsl:element> <!-- Data Vencimento -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="DataVencimentoTemp"/>
																</xsl:element>
															</xsl:element>
														</xsl:element>
														<xsl:element name="tr">
															<xsl:element name="td">
																<xsl:element name="b"><?php echo dicionario(204)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="format-number(ValorItem,'0,00','euro')"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:element name="b"><?php echo "(+) ".dicionario(912)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Multa -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="format-number(ValorMulta,'0,00','euro')"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:242px;</xsl:attribute>
																<xsl:element name="b"><?php echo "(+) ".dicionario(913)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Juros -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="format-number(ValorJuros,'0,00','euro')"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:242px;</xsl:attribute>
																<xsl:element name="b"><?php echo "(-) ".dicionario(579)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Desconto -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="format-number(ValorDesconto,'0,00','euro')"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:242px;</xsl:attribute>
																<xsl:element name="b"><?php echo "(=) ".dicionario(206)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Final -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="format-number(ValorFinal,'0,00','euro')"/>
																</xsl:element>
															</xsl:element>
														</xsl:element>
													</xsl:element>
												</xsl:element>
											</xsl:element>
										</xsl:for-each>
										<xsl:element name="tr">
											<xsl:element name="td">
												<xsl:attribute name="class">line</xsl:attribute>
												<xsl:attribute name="colspan">4</xsl:attribute>
											</xsl:element>
										</xsl:element>
										<xsl:element name="tr">
											<xsl:element name="td">
												<xsl:attribute name="style">padding:4px 0;</xsl:attribute>
												<xsl:attribute name="colspan">4</xsl:attribute>
												<xsl:for-each select="FormaPagamento">
													<xsl:element name="table">
														<xsl:attribute name="class">tableListar</xsl:attribute>
														<xsl:attribute name="cellspacing">0</xsl:attribute>
														<xsl:element name="tr">
															<xsl:element name="td">
																<xsl:attribute name="style">min-width:141px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(954); ?>: </xsl:element> <!-- Forma de Pagamento -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="DescricaoFormaPagamento"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:141px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(225); ?>: </xsl:element> <!-- QTD. Parcelas -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="QtdParcelas"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:196px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(940)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Parcela -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="format-number(ValorParcela,'0,00','euro')"/>
																</xsl:element>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:181px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(398)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Total -->
																<xsl:element name="a">
																	<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
																	<xsl:value-of select="format-number(ValorTotal,'0,00','euro')"/>
																</xsl:element>
															</xsl:element>
														</xsl:element>
													</xsl:element>
												</xsl:for-each>
											</xsl:element>
										</xsl:element>
									</xsl:element>
								</xsl:element>
								<!-- FIM ITEM RELATÓRIO -->
								<!-- INÍCIO ITEM IMPRESSÃO -->
								<xsl:element name="div">
									<xsl:attribute name="style">margin:8px 0 8px; padding:4px 8px 1px; border:1px solid #000;</xsl:attribute>
									<xsl:attribute name="class">ocultar</xsl:attribute>
									<xsl:element name="table">
										<xsl:attribute name="style">width:100%;</xsl:attribute>
										<xsl:attribute name="cellspacing">0</xsl:attribute>
										<xsl:element name="tr">
											<xsl:element name="td">
												<xsl:element name="b"><?php echo dicionario(911); ?>: </xsl:element> <!-- Movimentação -->
												<xsl:value-of select="IdCaixaMovimentacao"/>
											</xsl:element>
											<xsl:element name="td">
												<xsl:attribute name="style">width:282px;</xsl:attribute>
												<xsl:element name="b"><?php echo dicionario(732); ?>: </xsl:element> <!-- Tipo Movimentação -->
												<xsl:value-of select="TipoMovimentacao"/>
											</xsl:element>
											<xsl:element name="td">
												<xsl:attribute name="style">width:161px;</xsl:attribute> <!-- Status -->
												<?php echo dicionario(140); ?>: 
												<xsl:value-of select="Status"/>
											</xsl:element>
										</xsl:element>
										<xsl:for-each select="Item">
											<xsl:element name="tr">
												<xsl:element name="td">
													<xsl:attribute name="class">line</xsl:attribute>
													<xsl:attribute name="style">padding-top:11px;</xsl:attribute>
													<xsl:attribute name="colspan">4</xsl:attribute>
													<?php echo dicionario(928); ?>: <!-- Item -->
													<xsl:value-of select="IdCaixaItem"/>
												</xsl:element>
											</xsl:element>
											<xsl:element name="tr">
												<xsl:element name="td">
													<xsl:attribute name="style">margin-top:2px;</xsl:attribute>
													<xsl:attribute name="colspan">4</xsl:attribute>
													<xsl:element name="table">
														<xsl:attribute name="class">tableListar</xsl:attribute>
														<xsl:attribute name="cellspacing">0</xsl:attribute>
														<xsl:element name="tr">
															<xsl:element name="td">
																<xsl:attribute name="style">min-width:141px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(670); ?>: </xsl:element> <!-- Conta Receber -->
																<xsl:value-of select="IdContaReceber"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">min-width:166px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(681); ?>: </xsl:element> <!-- Número Documento -->
																<xsl:value-of select="NumeroDocumento"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="colspan">2</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(85); ?>: </xsl:element> <!-- Nome Pessoa -->
																<xsl:value-of select="Nome"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:element name="b"><?php echo dicionario(399); ?>: </xsl:element> <!-- Data Vencimento -->
																<xsl:value-of select="DataVencimentoTemp"/>
															</xsl:element>
														</xsl:element>
														<xsl:element name="tr">
															<xsl:element name="td">
																<xsl:element name="b"><?php echo dicionario(204)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor -->
																<xsl:value-of select="format-number(ValorItem,'0,00','euro')"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:element name="b"><?php echo "(+) ".dicionario(912)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Multa -->
																<xsl:value-of select="format-number(ValorMulta,'0,00','euro')"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:242px;</xsl:attribute>
																<xsl:element name="b"><?php echo "(+) ".dicionario(913)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Juros -->
																<xsl:value-of select="format-number(ValorJuros,'0,00','euro')"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:242px;</xsl:attribute>
																<xsl:element name="b"><?php echo "(-) ".dicionario(579)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Desconto -->
																<xsl:value-of select="format-number(ValorDesconto,'0,00','euro')"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:242px;</xsl:attribute>
																<xsl:element name="b"><?php echo "(=) ".dicionario(206)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Final -->
																<xsl:value-of select="format-number(ValorFinal,'0,00','euro')"/>
															</xsl:element>
														</xsl:element>
													</xsl:element>
												</xsl:element>
											</xsl:element>
										</xsl:for-each>
										<xsl:element name="tr">
											<xsl:element name="td">
												<xsl:attribute name="class">line</xsl:attribute>
												<xsl:attribute name="style">padding-top:11px;</xsl:attribute>
												<xsl:attribute name="colspan">4</xsl:attribute>
											</xsl:element>
										</xsl:element>
										<xsl:element name="tr">
											<xsl:element name="td">
												<xsl:attribute name="style">padding-top:11px;</xsl:attribute>
												<xsl:attribute name="colspan">4</xsl:attribute>
												<xsl:element name="table">
													<xsl:attribute name="style">width:100%;</xsl:attribute>
													<xsl:attribute name="cellpadding">0</xsl:attribute>
													<xsl:attribute name="cellspacing">0</xsl:attribute>
													<xsl:for-each select="FormaPagamento">
														<xsl:element name="tr">
															<xsl:element name="td">
																<xsl:attribute name="style">min-width:141px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(954); ?>: </xsl:element> <!-- Forma de Pagamento -->
																<xsl:value-of select="DescricaoFormaPagamento"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:141px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(225); ?>: </xsl:element> <!-- QTD. Parcelas -->
																<xsl:value-of select="QtdParcelas"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:196px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(940)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Parcela -->
																<xsl:value-of select="format-number(ValorParcela,'0,00','euro')"/>
															</xsl:element>
															<xsl:element name="td">
																<xsl:attribute name="style">width:181px;</xsl:attribute>
																<xsl:element name="b"><?php echo dicionario(398)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Total -->
																<xsl:value-of select="format-number(ValorTotal,'0,00','euro')"/>
															</xsl:element>
														</xsl:element>
													</xsl:for-each>
												</xsl:element>
											</xsl:element>
										</xsl:element>
									</xsl:element>
								</xsl:element>
								<!-- FIM ITEM IMPRESSÃO -->
							</xsl:for-each>
							<!-- INÍCIO ITEM RELATÓRIO -->
							<xsl:element name="div">
								<xsl:attribute name="class">ocultarPrint</xsl:attribute>
								<xsl:element name="table">
									<xsl:attribute name="class">tableListar</xsl:attribute>	
									<xsl:attribute name="style">border-bottom:2px #000 solid; margin-bottom:8px;</xsl:attribute>
									<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
									<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
									<xsl:attribute name="cellspacing">0</xsl:attribute>	
									<xsl:element name="tr">
										<xsl:element name="td">
											<xsl:attribute name="class">tableListarTitle</xsl:attribute>
											<xsl:attribute name="colspan">4</xsl:attribute>
											<?php echo dicionario(955); ?> <!-- Resumo Caixa -->
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_caixa_movimentacao.php?IdCaixa=<xsl:value-of select="IdCaixa"/>&amp;IdCaixaMovimentacao=<xsl:value-of select="IdCaixaMovimentacao"/></xsl:attribute>
												<xsl:value-of select="IdCaixaItem"/>
											</xsl:element>
										</xsl:element>
									</xsl:element>
									<xsl:element name="tr">
										<xsl:element name="td">
											<xsl:element name="b"><?php echo dicionario(948)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Total Abertura -->
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
												<xsl:value-of select="format-number(ResumoCaixa/ValorAberturaTotal,'0,00','euro')"/>
											</xsl:element>
										</xsl:element>
										<xsl:element name="td">
											<xsl:element name="b"><?php echo dicionario(956)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Saldo Total Atual -->
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_caixa.php?IdCaixa=<xsl:value-of select="IdCaixa"/></xsl:attribute>
												<xsl:value-of select="format-number(ResumoCaixa/ValorAtualTotal,'0,00','euro')"/>
											</xsl:element>
										</xsl:element>
									</xsl:element>
								</xsl:element>
							</xsl:element>
							<!-- FIM ITEM RELATÓRIO -->
							<!-- INÍCIO ITEM IMPRESSÃO -->
							<xsl:element name="div">
								<xsl:attribute name="class">ocultar</xsl:attribute>
								<xsl:element name="div">
									<xsl:attribute name="class">line</xsl:attribute>
									<xsl:attribute name="style">margin-top:16px;</xsl:attribute>
									<xsl:element name="div">
										<xsl:attribute name="class">tableListarTitlePrint</xsl:attribute>
										<?php echo dicionario(955); ?> <!-- Resumo Caixa -->
									</xsl:element>
								</xsl:element>
								<xsl:element name="div">
									<xsl:attribute name="style">margin:8px 0 8px; padding:4px 8px 1px; border:1px solid #000;</xsl:attribute>
									<xsl:element name="table">
										<xsl:attribute name="style">width:100%; margin-top:2px;</xsl:attribute>
										<xsl:attribute name="cellspacing">0</xsl:attribute>	
										<xsl:element name="tr">
											<xsl:element name="td">
												<xsl:element name="b"><?php echo dicionario(948)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Valor Total Abertura -->
												<xsl:value-of select="format-number(ResumoCaixa/ValorAberturaTotal,'0,00','euro')"/>
											</xsl:element>
											<xsl:element name="td">
												<xsl:element name="b"><?php echo dicionario(956)." (".getParametroSistema(5,1); ?>): </xsl:element> <!-- Saldo Total Atual -->
												<xsl:value-of select="format-number(ResumoCaixa/ValorAtualTotal,'0,00','euro')"/>
											</xsl:element>
										</xsl:element>
									</xsl:element>
								</xsl:element>
							</xsl:element>
							<!-- FIM ITEM IMPRESSÃO -->
							<!-- INÍCIO ITEM RELATÓRIO -->
							<xsl:element name="div">
								<xsl:attribute name="class">ocultarPrint</xsl:attribute>
								<xsl:attribute name="style">margin-bottom:18px;</xsl:attribute>
								<xsl:element name="table">
									<xsl:attribute name="class">tableListar</xsl:attribute>	
									<xsl:attribute name="style">margin-top: 10px;</xsl:attribute>
									<xsl:attribute name="cellspacing">0</xsl:attribute>	
									<xsl:element name="tr">
										<xsl:attribute name="class">tableListarTitle</xsl:attribute>
										<xsl:element name="td">
											<xsl:attribute name="colspan">0</xsl:attribute>
											<xsl:attribute name="id">tableListarTotal</xsl:attribute>
											<?php echo dicionario(209); ?>: <xsl:value-of select="count(Movimentacao)" />
										</xsl:element>
									</xsl:element>
								</xsl:element>
							</xsl:element>
							<!-- FIM ITEM RELATÓRIO -->
							<!-- INÍCIO ITEM IMPRESSÃO -->
							<xsl:element name="div">
								<xsl:attribute name="class">ocultar</xsl:attribute>
								<xsl:attribute name="style">margin-bottom:55px;</xsl:attribute>
								<xsl:element name="table">
									<xsl:attribute name="style">margin-top: 10px;</xsl:attribute>
									<xsl:attribute name="cellspacing">0</xsl:attribute>	
									<xsl:element name="tr">
										<xsl:element name="td">
											<xsl:attribute name="colspan">0</xsl:attribute>
											<xsl:attribute name="id">tableListarTotal</xsl:attribute>
											<xsl:element name="b"><?php echo dicionario(209); ?>: <xsl:value-of select="count(Movimentacao)" /></xsl:element>
										</xsl:element>
									</xsl:element>
								</xsl:element>
							</xsl:element>
							<!-- FIM ITEM IMPRESSÃO -->
						</xsl:for-each>
					</xsl:element>
				</div>
				<script type="text/javascript">
					menu_form = false;
				</script>
				<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
			</body>	
		</html>
	</xsl:template>
</xsl:stylesheet>