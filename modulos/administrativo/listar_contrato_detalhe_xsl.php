<?
	$localModulo		=	1;
	$localOperacao		=	130;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$Limit							= $_GET['Limit'];
	
	$localPessoa					= url_string_xsl($_GET['Pessoa'],'url',false);
	$localDescricaoParametroServico	= url_string_xsl($_GET['DescricaoParametroServico'],'url',false);
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'url',false);
	$localValorParametroServico		= url_string_xsl($_GET['ValorParametroServico'],'url',false);
	$localIdStatus					= url_string_xsl($_GET['IdStatus'],'');
	$localCancelado					= $_GET['Cancelado'];
	$localSoma						= $_GET['Soma'];
	$localIdPessoa					= $_GET['IdPessoa'];
	$localIdContrato				= $_GET['IdContrato'];
	$localIdServico					= $_GET['IdServico'];
	$localConsultarPor				= $_GET['ConsultarPor'];
	#$localIdPessoa					= $_GET['IdPessoa'];
	$localIdServico					= $_GET['IdServico'];
	
	LimitVisualizacao('xsl');

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
		<script type = 'text/javascript' src = 'js/contrato_detalhe.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(316)?>')">
		<? include('filtro_contrato_detalhe.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<xsl:for-each select="db/reg">				
				<table class='tableListar' id='tableListar' cellspacing='0' style='border-bottom:2px #000 solid; margin-bottom:10px;'>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:element name="tr">
						<xsl:attribute name="class">tableListarTitle</xsl:attribute>
						<td colspan='4'><?=dicionario(177)?>: <xsl:value-of select="IdPessoa"/></td>						
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
						<td class='descCampo'><xsl:value-of select="cpCNPJ"/></td>						
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
						<td colspan='4'>
							<table class='tableListar' cellspacing='0'>					
								<xsl:for-each select="contrato">
									<xsl:element name="tr">
										<xsl:attribute name="class">tableListarTitle</xsl:attribute>
										<td colspan='2'>Contrato: <xsl:value-of select="IdContrato"/></td>	
										<td>Status: <xsl:value-of select="StatusDesc"/></td>
									</xsl:element>	
									<xsl:element name="tr">
										<xsl:attribute name="cursor">normal</xsl:attribute>
										<td class='descCampo' style='width:400px'>Serviço</td>	
										<td class='descCampo' style='width:250px'>Data do Cadastro</td>	
										<td class='descCampo' style='width:200px'>Usuário Cadastro</td>	
									</xsl:element>
									<xsl:element name="tr">									
										<td>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
												<xsl:value-of select="DescricaoServico"/>
											</xsl:element>
										</td>										
										<td>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
												<xsl:value-of select="DataCriacao"/>
											</xsl:element>
										</td>
										<td>
											<xsl:element name="a">
												<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
												<xsl:value-of select="LoginCriacao"/>
											</xsl:element>
										</td>							
									 </xsl:element>	
									 <xsl:element name="tr">
									 	<xsl:attribute name="class">tableListarTitle</xsl:attribute>
									 	<td colspan='3'><?=dicionario(319)?>:</td>
			 						 </xsl:element>	
									 <xsl:element name="tr">
									 	<td colspan='3'>
									 		<table class='tableListar' cellpadding='0'>						 		
									 			<xsl:element name="tr">
											 		<xsl:attribute name="cursor">normal</xsl:attribute>	
											 		<td class='descCampo' style='width:600px'><?=dicionario(155)?></td>									
													<td class='descCampo' style='width:70px'><?=dicionario(320)?></td>	
													<td class='descCampo' style='width:250px'><?=dicionario(160)?></td>										
													<td class='descCampo' style='width:100px'><?=dicionario(255)?></td>			 		
												</xsl:element>
												<xsl:element name="tr">									
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="EnderecoPrincipal"/>
														</xsl:element>
													</td>										
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="NumeroPrincipal"/>
														</xsl:element>
													</td>
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="BairroPrincipal"/>
														</xsl:element>
													</td>
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="ComplementoPrincipal"/>
														</xsl:element>
													</td>				
												</xsl:element>	
												<xsl:element name="tr">										
													<td class='descCampo' style='width:200px'><?=dicionario(156)?></td>						 	
													<td class='descCampo' style='width:200px'><?=dicionario(186)?></td>
													<td class='descCampo' style='width:200px'><?=dicionario(139)?></td>
													<td class='descCampo' style='width:250px'><?=dicionario(233)?></td>	
												</xsl:element>
												<xsl:element name="tr">										
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="CEPPrincipal"/>
														</xsl:element>
													</td>										
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="NomeCidadePrincipal"/>
														</xsl:element>
													</td>
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="SiglaEstadoPrincipal"/>
														</xsl:element>
													</td>
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="TelefonePrincipal"/>
														</xsl:element>
													</td>			
												</xsl:element>
												<xsl:element name="tr">										
													<td class='descCampo' style='width:200px'><?=dicionario(100)?></td>						 	
													<td class='descCampo' style='width:200px'><?=dicionario(101)?></td>
													<td class='descCampo' style='width:200px'><?=dicionario(176)?></td>
													<td class='descCampo' style='width:250px'><?=dicionario(178)?></td>											
												</xsl:element>
												<xsl:element name="tr">
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="TelefonePrincipal"/>
														</xsl:element>
													</td>	
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="CelularPrincipal"/>
														</xsl:element>
													</td>										
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="FaxPrincipal"/>
														</xsl:element>
													</td>
													<td>
														<xsl:element name="a">
															<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
															<xsl:value-of select="NomeResponsavelEnderecoPrincipal"/>
														</xsl:element>
													</td>			
												</xsl:element>											
											</table>							
									 	</td>
									 </xsl:element>
									 <xsl:if test="IdPessoaEndereco != IdPessoaEnderecoCobranca">								 	
										 <xsl:element name="tr">
										 	<xsl:attribute name="class">tableListarTitle</xsl:attribute>
										 	<td colspan='3'><?=dicionario(321)?>:</td>
				 						 </xsl:element>	
										 <xsl:element name="tr">
										 	<td colspan='3'>
										 		<table class='tableListar' cellpadding='0'>						 		
										 			<xsl:element name="tr">
												 		<xsl:attribute name="cursor">normal</xsl:attribute>													 		
														<td class='descCampo' style='width:600px'><?=dicionario(155)?></td>
														<td class='descCampo' style='width:70px'><?=dicionario(320)?></td>	
														<td class='descCampo' style='width:250px'><?=dicionario(160)?></td>										
														<td class='descCampo' style='width:100px'><?=dicionario(255)?></td>		
													</xsl:element>
													<xsl:element name="tr">									
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="EnderecoCobranca"/>
															</xsl:element>
														</td>										
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="NumeroCobranca"/>
															</xsl:element>
														</td>
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="BairroCobranca"/>
															</xsl:element>
														</td>
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="ComplementoCobranca"/>
															</xsl:element>
														</td>				
													</xsl:element>	
													<xsl:element name="tr">											
														<td class='descCampo' style='width:200px'><?=dicionario(156)?></td>						 	
														<td class='descCampo' style='width:200px'><?=dicionario(186)?></td>
														<td class='descCampo' style='width:200px'><?=dicionario(139)?></td>
														<td class='descCampo' style='width:250px'><?=dicionario(213)?></td>
													</xsl:element>
													<xsl:element name="tr">										
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="CEPCobranca"/>
															</xsl:element>
														</td>										
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="NomeCidadeCobranca"/>
															</xsl:element>
														</td>
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="SiglaEstadoCobranca"/>
															</xsl:element>
														</td>	
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="TelefoneCobranca"/>
															</xsl:element>
														</td>		
													</xsl:element>
													<xsl:element name="tr">										
														<td class='descCampo' style='width:200px'><?=dicionario(100)?></td>						 	
														<td class='descCampo' style='width:200px'><?=dicionario(101)?></td>
														<td class='descCampo' style='width:250px'><?=dicionario(176)?></td>
														<td class='descCampo' style='width:200px'><?=dicionario(178)?></td>	
													</xsl:element>
													<xsl:element name="tr">
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="TelefoneCobranca"/>
															</xsl:element>
														</td>	
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="CelularCobranca"/>
															</xsl:element>
														</td>										
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="FaxCobranca"/>
															</xsl:element>
														</td>
														<td>
															<xsl:element name="a">
																<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																<xsl:value-of select="NomeResponsavelEnderecoCobranca"/>
															</xsl:element>
														</td>			
													</xsl:element>											
												</table>														
										 	</td>
										 </xsl:element>
							 	 	 </xsl:if>	
 								     <xsl:if test="VisualizarContratoParametro = 1">								
										 <xsl:element name="tr">
										    <xsl:attribute name="class">tableListarTitle</xsl:attribute>
											<td colspan='3'><?=dicionario(262)?>:</td>						
										 </xsl:element>
									 	 <xsl:element name="tr">	
										  	 <td colspan ='3'>							
												 <table class='tableListar' cellpadding='0'>
													 <xsl:choose>
														 <xsl:when test="contratoParametro = ''">
														 	<tr><td height='8px' colspan='2'></td></tr>
														 </xsl:when>
														 <xsl:otherwise>
															 <xsl:for-each select="contratoParametro/parametro">
																<xsl:element name="tr">
																	<td width='130px' style='font-weight: bold'>
																		<xsl:element name="a">
																			<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																			<xsl:value-of select="DescricaoParametroServico"/>
																		</xsl:element>
																	</td>
																	<td>
																		<xsl:element name="a">
																			<xsl:attribute name="href">cadastro_contrato.php?IdContrato=<xsl:value-of select="IdContrato"/></xsl:attribute>
																			<xsl:value-of select="Valor"/>
																		</xsl:element>
																	</td>
																</xsl:element>
															 </xsl:for-each>
													 	</xsl:otherwise>
													 </xsl:choose>
												 </table>
											 </td>
										 </xsl:element>
								  	 </xsl:if>
								</xsl:for-each>
							</table>								
						</td>						
					</xsl:element>				
				</table>						
			</xsl:for-each>			
			<table class='tableListar' style='margin-top: 10px' cellspacing='0'>		
				<tr class='tableListarTitle'>
					<td colspan='2' id='tableListarTotal'><?=dicionario(209)?>: <xsl:value-of select="count(db/reg)" /></td>						
			
					<td colspan='3'><?=dicionario(322)?> <? echo date("d/m/Y");?></td>				
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
