								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_IdContrato			=	$_GET['IdContrato'];
									$local_Erro					=	$_GET['Erro'];
									$local_Acao					=  	$_POST['Acao'];
									$local_IdParametroSistemaCad = 	$_POST['IdParametroSistema'];							
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
								    
								    switch($local_Acao){
										case 'alterar':
											include('files/editar/editar_contrato.php');
											break;
										default:
											$local_Acao	= 'alterar';
											break;	
									
									}	    
								    
								    
								?>
								<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							      		<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
									    <td id="tit" width="387"><h1><img src="img/icones/<?=$local_IdParametroSistema?>.png" /> <?=$local_Descricao?></h1></td>
									    <td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="?ctt=index.php">Página Inicial</a></td>
									    <td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
							    	</tr>
							    </table>
							    <table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							    		<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
							    			<form name='formulario' method='post' action='files/editar/editar_contrato.php?IdParametroSistema=<?=$local_IdParametroSistema?>' onSubmit='return cadastrar_contrato()'>
												<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
												<input type='hidden' name='IdParametroSistema' value='<?=$local_IdParametroSistemaCad?>'>
												<input type='hidden' name='ContratoAssinado' value=''>
												<?
													$sqlAux	=	"select IdContrato IdContratoPai from ContratoAutomatico where IdLoja = $local_IdLoja and IdContratoAutomatico = $local_IdContrato group by	IdContrato";
													$resAux	= 	@mysql_query($sqlAux,$con);
													$linAux = 	@mysql_fetch_array($resAux);
												
													if($linAux[IdContratoPai]!=""){
														$local_IdContrato	=	$linAux[IdContratoPai];
													}
													
													echo"<input type='hidden' name='IdContrato' value='$local_IdContrato'>";
													
													$sql	=	"select
																	Contrato.IdServico, 
																	Servico.DescricaoServico,
																	Servico.UrlContratoImpresso,
																	Servico.UrlDistratoImpresso,
																	Servico.AtivacaoAutomatica,
																	Servico.TermoCienciaCDA,
																	DATE_FORMAT(Contrato.DataInicio, '%d/%m/%Y') DataInicio,
																	DATE_FORMAT(Contrato.DataTermino, '%d/%m/%Y') DataTermino,
																	DATE_FORMAT(Contrato.DataBaseCalculo, '%d/%m/%Y') DataBaseCalculo,
																	DATE_FORMAT(Contrato.DataPrimeiraCobranca, '%d/%m/%Y') DataPrimeiraCobranca,
																	DATE_FORMAT(Contrato.DataUltimaCobranca, '%d/%m/%Y') DataUltimaCobranca,
																	Contrato.AssinaturaContrato,
																	Contrato.IdAgenteAutorizado,
																	Contrato.IdCarteira,
																	Contrato.IdPeriodicidade,
																	Contrato.QtdParcela,
																	Contrato.IdLocalCobranca,
																	Contrato.DiaCobranca,
																	LocalCobranca.DescricaoLocalCobranca,
																	LocalCobranca.IdTipoLocalCobranca,
																	LocalCobranca.AbreviacaoNomeLocalCobranca,
																	Contrato.IdContratoAgrupador,
																	Contrato.AdequarLeisOrgaoPublico,
																	Contrato.TipoContrato, 
																	Contrato.IdStatus,
																	Contrato.VarStatus,
																	Contrato.Obs,
																	Periodicidade.DescricaoPeriodicidade,
																	Contrato.MesFechado,
																	Contrato.QtdMesesFidelidade,
																	Contrato.MultaFidelidade
																from 
																	Contrato,
																	Pessoa,
																	Servico,
																	Periodicidade,
																	LocalCobranca 
																where 
																	Contrato.IdLoja = $local_IdLoja and
																	Contrato.IdLoja = Servico.IdLoja and
																	Contrato.IdLoja = Periodicidade.IdLoja and
																	Contrato.IdLoja = LocalCobranca.IdLoja and
																	Contrato.IdPessoa = Pessoa.IdPessoa and
																	Contrato.IdServico = Servico.IdServico and
																	Contrato.IdPeriodicidade = Periodicidade.IdPeriodicidade and
																	Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
																	Contrato.IdContrato = $local_IdContrato 
																order by
																	Contrato.IdContrato DESC";
													$res	=	@mysql_query($sql,$con);
													$lin	=	@mysql_fetch_array($res);
													
													$sql2 	= "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema = $lin[IdStatus]";
													$res2	=	@mysql_query($sql2,$con);
													$lin2	=	@mysql_fetch_array($res2);
													
													if($lin[VarStatus] != ''){
														switch($lin[IdStatus]){
															case '201':
																$lin2[ValorParametroSistema] = str_replace("Temporariamente","até $lin[VarStatus]", $lin2[ValorParametroSistema]);	
																break;
														}					
													}
													
													$lin[Status] = $lin2[ValorParametroSistema];
													if($lin[IdStatus] >= 500){ $lin[IdStatus] = "200";}
													$lin[ColorStatus] = getCodigoInternoCDA(15, substr($lin[IdStatus],0,1));
													
													$sql2 	= "select ValorParametroSistema DescricaoTipoContrato from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema = $lin[TipoContrato]";
													$res2	=	@mysql_query($sql2,$con);
													$lin2	=	@mysql_fetch_array($res2);
													
													$sql3 	= "select ValorParametroSistema DescricaoMesFechado from ParametroSistema where IdGrupoParametroSistema=70 and IdParametroSistema = $lin[MesFechado]";
													$res3 	= @mysql_query($sql3,$con);
													$lin3	= @mysql_fetch_array($res3);
													
													$lin4[DescricaoContratoAgrupador]	=	"";
													
													if($lin[IdContratoAgrupador]!=""){
														$sql4	=	"select
																		Servico.DescricaoServico,
																		Contrato.IdContrato
																	from 
																		Contrato,
																		Servico
																	where 
																		Contrato.IdLoja = $local_IdLoja and 
																		Contrato.IdLoja = Servico.IdLoja and 
																		Contrato.IdServico = Servico.IdServico and
																		Contrato.IdContrato = $lin[IdContratoAgrupador]";
														$res4	=	@mysql_query($sql4,$con);
														$lin4 	= 	@mysql_fetch_array($res4);
														
														if($lin4[IdContrato]!=""){
															$lin4[DescricaoContratoAgrupador]	=	"(".$lin4[IdContrato].") ".$lin4[DescricaoServico];
														}
													}
													
													$sql5 = "select ValorCodigoInterno DescricaoAssinaturaContrato from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno=9 and IdCodigoInterno=$lin[AssinaturaContrato]";
													$res5 = @mysql_query($sql5,$con);
													$lin5 = @mysql_fetch_array($res5);
													
													$lin6[Nome]	= "";
													$lin7[Nome]	= "";
													
													if($lin[IdAgenteAutorizado] != ""){
														$sql6 = "select RazaoSocial,Nome,TipoPessoa from Pessoa where IdPessoa = $lin[IdAgenteAutorizado] and TipoAgenteAutorizado=1";
														$res6 = @mysql_query($sql6,$con);
														$lin6 = @mysql_fetch_array($res6);
														
														if($lin6[TipoPessoa]=='1'){
															$lin6[Nome]	=	$lin6[getCodigoInternoCDA(3,24)];	
														}
														
														$sql7 = "select RazaoSocial,Nome,TipoPessoa from Pessoa where IdPessoa = '$lin[IdCarteira]' and TipoVendedor=1";
														$res7 = mysql_query($sql7,$con);
														$lin7 = mysql_fetch_array($res7);
														
														if($lin7[TipoPessoa]=='1'){
															$lin7[Nome]	=	$lin7[getCodigoInternoCDA(3,24)];	
														}
													}
													
													$sql8 	= "select ValorParametroSistema DescricaoAdequarLeisOrgaoPublico from ParametroSistema where IdGrupoParametroSistema=43 and IdParametroSistema = $lin[AdequarLeisOrgaoPublico]";
													$res8 	= @mysql_query($sql8,$con);
													$lin8	= @mysql_fetch_array($res8);
													
													$sql9	=	"select Valor,ValorRepasseTerceiro,ValorDesconto,IdTipoDesconto,IdContratoTipoVigencia,LimiteDesconto from ContratoVigenciaAtiva where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato";
													$res9	=	@mysql_query($sql9,$con);
													$lin9	=	@mysql_fetch_array($res9);
													
													if($lin9[IdTipoDesconto] == 1){
														$lin9[ValorFinal]	=	$lin9[Valor] - $lin9[ValorDesconto];
													}else{
														$lin9[ValorFinal]	=	$lin9[Valor];
													}
													
													$lin9[Valor]	=	formata_double($lin9[Valor]);
													$lin9[Valor]	=	str_replace('.',',',$lin9[Valor]);
													
													$lin9[ValorDesconto]		=	formata_double($lin9[ValorDesconto]);
													$lin9[ValorDesconto]		=	str_replace('.',',',$lin9[ValorDesconto]);
													
													$sql10	=	"SELECT Fator from Periodicidade where Periodicidade.IdLoja = $local_IdLoja and Periodicidade.IdPeriodicidade = $lin[IdPeriodicidade]";
													$res10	=	@mysql_query($sql10,$con);
													$lin10	=	@mysql_fetch_array($res10);
													
													$lin10[ValorPeriodicidade] = 	$lin9[ValorFinal]*$lin10[Fator];
													$lin10[ValorPeriodicidade] =	formata_double($lin10[ValorPeriodicidade]);
													$lin10[ValorPeriodicidade] =	str_replace('.',',',$lin10[ValorPeriodicidade]);
													
													$lin9[ValorFinal]		=	formata_double($lin9[ValorFinal]);
													$lin9[ValorFinal]		=	str_replace('.',',',$lin9[ValorFinal]);
													
													$lin10[ValorPeriodicidadeTerceiro] = 	$lin9[ValorRepasseTerceiro]*$lin10[Fator];
													$lin10[ValorPeriodicidadeTerceiro] =	formata_double($lin10[ValorPeriodicidadeTerceiro]);
													$lin10[ValorPeriodicidadeTerceiro] =	str_replace('.',',',$lin10[ValorPeriodicidadeTerceiro]);
													
													$lin9[ValorRepasseTerceiro]	=	formata_double($lin9[ValorRepasseTerceiro]);
													$lin9[ValorRepasseTerceiro]	=	str_replace('.',',',$lin9[ValorRepasseTerceiro]);
													
													$lin[MultaFidelidade]	=	formata_double($lin[MultaFidelidade]);
													$lin[MultaFidelidade]	=	str_replace('.',',',$lin[MultaFidelidade]);
													
													echo"
														<table>
															<tr>
																<td class='title'>Contrato</td>
																<td class='sep' />
																<td rowspan='2' colspan='6' style='text-align:right; font-size:16px;'>
																	<B style='color:$lin[ColorStatus];'>$lin[Status]</B>
																</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='DescricaoServico' value='$local_IdContrato' style='width:70px' maxlength='100' readOnly>
																</td>
																<td class='sep' />
															</tr>
															<tr>
																<td class='title'>Serviço</td>
																<td class='sep' />
																<td class='title'>Periodicidade</td>
																<td class='sep' />
																<td class='title'>QTD. Parcelas</td>
																<td class='sep' />
																<td class='title'>Tipo Contrato</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='DescricaoServico' value='$lin[DescricaoServico]' style='width:309px' maxlength='100' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='IdPeriodicidade' value='$lin[DescricaoPeriodicidade]'  style='width:100px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='QtdParcela' value='$lin[QtdParcela]'  style='width:80px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='TipoContrato' value='$lin2[DescricaoTipoContrato]'  style='width:80px' readOnly>
																</td>
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'>Mês Fechado</td>
																<td class='sep' />
																<td class='title'>Contrato Agrupador</td>
																<td class='sep' />
																<td class='title'>Vencimento</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='MesFechado' value='$lin3[DescricaoMesFechado]'  style='width:70px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='IdContratoAgrupador' value='$lin4[DescricaoContratoAgrupador]'  style='width:430px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='DiaCobranca' value='$lin[DiaCobranca]'  style='width:80px' readOnly>
																</td>
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'>Data Início Cont.</td>
																<td class='sep' />
																<td class='title'>Data Início Cob.</td>
																<td class='sep' />
																<td class='title'>Data Base</td>
																<td class='sep' />
																<td class='title'>Data Término Cont.</td>
																<td class='sep' />
																<td class='title'>Data Última Cob.</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='DataInicio' value='$lin[DataInicio]' style='width:115px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='DataPrimeiraCobranca' value='$lin[DataPrimeiraCobranca]' style='width:115px' readOnly>
																</td>																
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='DataBaseCalculo' value='$lin[DataBaseCalculo]' style='width:115px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='DataTermino' value='$lin[DataTermino]' style='width:112px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='DataUltimaCobranca' value='$lin[DataUltimaCobranca]' style='width:114px' readOnly>
																</td>
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'>Valor Mensal do Serviço (".getParametroSistema(5,1).")</td>													
																<td class='sep' />
																<td class='title'>Valor Desconto (".getParametroSistema(5,1).")</td>													
																<td class='sep' />
																<td class='title'>Valor Final do Serviço (".getParametroSistema(5,1).")</td>													
																<td class='sep' />
																<td class='title'>Valor Periodicidade (".getParametroSistema(5,1).")</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='ValorServico' value='$lin9[Valor]' style='width:150px' readOnly>
																</td>													
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='ValorDesconto' value='$lin9[ValorDesconto]' style='width:140px' readOnly>
																</td>													
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='ValorFinal' value='$lin9[ValorFinal]' style='width:140px' readOnly>
																</td>													
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='ValorPeriodicidade' value='$lin10[ValorPeriodicidade]' style='width:140px' readOnly>
																</td>
															</tr>
														</table>";
														
														//Ocultar - Agente Autorizado e Carteira
														if(getCodigoInternoCDA(13,1) == 2){
															if(getCodigoInternoCDA(3,52) == 1){
																echo"
																<table>
																	<tr>
																		<td class='title'>Agente Autorizado</td>
																		<td class='sep' />
																		<td class='title'>Vendedor</td>
																		<td class='sep' />
																		<td class='title'>Adequar as leis de orgão público</td>
																	</tr>
																	<tr>
																		<td>
																			<input class='FormPadrao' type='text' name='NomeAgenteAutorizado' value='$lin6[Nome]' style='width:206px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='NomeCarteira' value='$lin7[Nome]' style='width:206px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='AdequarLeisOrgaoPublico' value='$lin8[DescricaoAdequarLeisOrgaoPublico]' style='width:166px' readOnly>
																		</td>
																	</tr>
																</table>";
															}else{
																echo"
																<table>
																	<tr>
																		<td class='title'>Agente Autorizado</td>
																		<td class='sep' />
																		<td class='title'>Vendedor</td>
																	</tr>
																	<tr>
																		<td>
																			<input class='FormPadrao' type='text' name='NomeAgenteAutorizado' value='$lin6[Nome]' style='width:206px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='NomeCarteira' value='$lin7[Nome]' style='width:206px' readOnly>
																		</td>
																	</tr>
																</table>";
															}
														}else{
															if(getCodigoInternoCDA(3,52) == 1){
																echo"
																<table>
																	<tr>
																		<td class='title'>Adequar as leis de orgão público</td>
																	</tr>
																	<tr>
																		<td>
																			<input class='FormPadrao' type='text' name='AdequarLeisOrgaoPublico' value='$lin8[DescricaoAdequarLeisOrgaoPublico]' style='width:166px' readOnly>
																		</td>
																	</tr>
																</table>";
															}
														}
														$sqlSta	="	select 
																		Contrato.IdStatus 
																	from
																		Contrato left join ContratoParametro on
																		(
																		  Contrato.IdLoja = ContratoParametro.IdLoja 
																		  and Contrato.IdServico = ContratoParametro.IdServico 
																		  and ContratoParametro.IdContrato = $local_IdContrato
																		) 
																	where
																		Contrato.IdLoja = $local_IdLoja and
																		Contrato.IdServico = $lin[IdServico] and
																		Contrato.IdContrato = $local_IdContrato 
																	group by
																		IdStatus";
														$resSta	=	@mysql_query($sqlSta,$con);
														$linSta =	@mysql_fetch_array($resSta);
														
														$bt_alterar = false;
														$qtd	=	0;
														$tab	=	1;
														$sqlPam	=	"select
																		ServicoParametro.IdServico,
																		ServicoParametro.IdParametroServico,
																		ServicoParametro.DescricaoParametroServicoCDA,
																		ContratoParametro.Valor,
																		ServicoParametro.ValorDefault,
																		ServicoParametro.Obrigatorio,
																		ServicoParametro.Obs,
																		ServicoParametro.RotinaCalculo,
																		ServicoParametro.RotinaOpcoes,
																		ServicoParametro.RotinaOpcoesContrato,
																		ServicoParametro.Calculavel,
																		ServicoParametro.RotinaOpcoes,
																		ServicoParametro.RotinaOpcoesContrato,
																		ServicoParametro.CalculavelOpcoes,
																		ServicoParametro.Editavel,
																		ServicoParametro.IdTipoParametro,
																		ServicoParametro.IdMascaraCampo,
																		ServicoParametro.IdTipoTexto,
																		ServicoParametro.ExibirSenha,
																		ServicoParametro.TamMinimo,
																		ServicoParametro.TamMaximo,
																		ServicoParametro.OpcaoValor,
																		ServicoParametro.VisivelCDA,
																		ServicoParametro.AcessoCDA
																	from 
																		Loja,
																		Servico,
																		ServicoParametro LEFT JOIN 
																				ContratoParametro ON (
																					ServicoParametro.IdLoja = ContratoParametro.IdLoja and 
																					ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico and
																					ServicoParametro.IdServico = ContratoParametro.IdServico and
																					ContratoParametro.IdContrato = $local_IdContrato)
																	where
																		Servico.IdLoja = $local_IdLoja and
																		Servico.IdServico = ServicoParametro.IdServico and
																		ServicoParametro.IdLoja = Servico.IdLoja and
																		Servico.IdLoja = Loja.IdLoja and
																		ServicoParametro.IdServico = $lin[IdServico] and
																		ServicoParametro.VisivelCDA = 1 and
																		ServicoParametro.IdStatus = 1
																	order by 
																ServicoParametro.IdParametroServico ASC";
														$resPam	=	@mysql_query($sqlPam,$con);
														$i=0;
														while($linPam = @mysql_fetch_array($resPam)){
															if($linPam[VisivelCDA] == 1 || $linPam[AcessoCDA] == 1){
																if($linSta[IdStatus] >= 200){
																	
																	if(($linPam[IdTipoTexto] == 3 || $linPam[IdTipoTexto] == 4 || $linPam[IdTipoTexto] == 5) || ($linPam[IdTipoParametro] == 2)){
																		$linPam[IdTipoTexto] = 1;
																	}
																	if($linPam[IdTipoTexto] == 2){																													
																		$ParametroValor[$i]			=	$linPam[Valor];															
																		$i++;
																		
																		$Parametro[IdParametroServico][$qtd]		=	'10000';
																		$Parametro[DescricaoParametroServico][$qtd]	=	'Senha Atual '.$linPam[DescricaoParametroServico];
																		$Parametro[Valor][$qtd]						=	$linPam[Valor];
																		$Parametro[ValorDefault][$qtd]				=	'';
																		$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
																		$Parametro[Obs][$qtd]						=	'';
																		$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
																		$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
																		$Parametro[IdMascaraCampo][$qtd]			=	'';
																		$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
																		$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
																		$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
																		$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
																		$Parametro[OpcaoValor][$qtd]				=	'';
																		
																		$qtd++;	
																		
																		$Parametro[IdParametroServico][$qtd]		=	$linPam[IdParametroServico];
																		$Parametro[DescricaoParametroServico][$qtd]	=	$linPam[DescricaoParametroServicoCDA];
																		$Parametro[Valor][$qtd]						=	$linPam[Valor];
																		$Parametro[ValorDefault][$qtd]				=	$linPam[ValorDefault];
																		$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
																		$Parametro[Obs][$qtd]						=	$linPam[Obs];
																		$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
																		$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
																		$Parametro[IdMascaraCampo][$qtd]			=	$linPam[IdMascaraCampo];
																		$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
																		$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
																		$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
																		$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
																		$Parametro[OpcaoValor][$qtd]				=	$linPam[OpcaoValor];
																																																				
																		$qtd++;	
																		
																		$Parametro[IdParametroServico][$qtd]		=	'10001';
																		$Parametro[DescricaoParametroServico][$qtd]	=	'Confirmação '.$linPam[DescricaoParametroServico];
																		$Parametro[Valor][$qtd]						=	'';
																		$Parametro[ValorDefault][$qtd]				=	'';
																		$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
																		$Parametro[Obs][$qtd]						=	'';
																		$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
																		$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
																		$Parametro[IdMascaraCampo][$qtd]			=	'';
																		$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
																		$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
																		$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
																		$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
																		$Parametro[OpcaoValor][$qtd]				=	'';
																		
																		$qtd++;
																	}else{
																		$Parametro[IdParametroServico][$qtd]		=	$linPam[IdParametroServico];
																		$Parametro[DescricaoParametroServico][$qtd]	=	$linPam[DescricaoParametroServicoCDA];
																		$Parametro[Valor][$qtd]						=	$linPam[Valor];
																		$Parametro[ValorDefault][$qtd]				=	$linPam[ValorDefault];
																		$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
																		$Parametro[Obs][$qtd]						=	$linPam[Obs];
																		$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
																		$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
																		$Parametro[IdMascaraCampo][$qtd]			=	$linPam[IdMascaraCampo];
																		$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
																		$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
																		$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
																		$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
																		$Parametro[OpcaoValor][$qtd]				=	$linPam[OpcaoValor];
																	
																		$qtd++;															
																	}
																}
															}
														}
														
														$i	=	0;
														
														if($qtd>0){
															echo"<BR>Parâmetros do Serviço:";
															$bt_alterar = true;
														}
														
														for($i=0; $i<$qtd; $i++){														
															echo "<table border='0'>
																	<tr>";
															
															if($Parametro[Obrigatorio][$i] == 1){
																if($Parametro[IdTipoTexto][$i] == 2){																															
																	$Descricao	=	$Parametro[DescricaoParametroServico][$i];
																}else{	 														
																	$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$i]."</B>";
																}
															}else{
																$Descricao	=	$Parametro[DescricaoParametroServico][$i];
															}																														
															echo "<td class='title'>$Descricao</td>";
																																													
															$tipoColuna1 = $Parametro[IdTipoTexto][$i];
															
															$prox1	=	$i+1;
														
															if(($Parametro[IdTipoTexto][$i] == 1 && $Parametro[IdTipoTexto][$prox1] == 1) || ($Parametro[IdTipoTexto][$i] == 2 && $Parametro[IdTipoTexto][$prox1] == 2)){
																if($prox1 < $qtd){
																	echo"<td class='sep' />";
																	
																	if($Parametro[Obrigatorio][$prox1] == 1){
																		if($Parametro[IdTipoTexto][$prox1] == 2){
																			$Descricao	=	$Parametro[DescricaoParametroServico][$prox1];
																		}else{
																			$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$prox1]."</B>";
																		}
																	}else{
																		$Descricao	=	$Parametro[DescricaoParametroServico][$prox1];
																	}
																	
																	echo "<td class='title'>$Descricao</td>";
																}
															}
															
															$tipoColuna2 = $Parametro[IdTipoTexto][$prox1];
															
															$prox2	=	$i+2;														
															if(($Parametro[IdTipoTexto][$prox1] == 1 && $Parametro[IdTipoTexto][$prox2] == 1) || ($Parametro[IdTipoTexto][$prox1-1] == 2 && $Parametro[IdTipoTexto][$prox2] == 2)){															
																if($prox2 < $qtd){
																	echo"<td class='sep' />";
																	
																	if($Parametro[Obrigatorio][$prox2] == 1){
																		if($Parametro[IdTipoTexto][$prox2] == 2){
																			$Descricao	=	$Parametro[DescricaoParametroServico][$prox2];
																		}else{
																			$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$prox2]."</B>";
																		}
																	}else{
																		$Descricao	=	$Parametro[DescricaoParametroServico][$prox2];
																	}
																
																	echo "<td class='title'>$Descricao</td>";
																}																
															}
															
															$tipoColuna3 = $Parametro[IdTipoTexto][$prox2];
															
															echo "</tr>";
															echo "<tr>";
															
															if($Parametro[TamMaximo][$i]!=""){
																$tamMax	=	"maxlength='".$Parametro[TamMaximo][$i]."'";
															}else{
																$tamMax	=	"";
															}
															
															if($Parametro[Editavel][$i]==1){
																$disabled	=	"";
																$tabindex	=	"tabindex='$tab'";
															}else{
																$disabled	=	"readOnly";
																$tabindex	=	"";
															}
															
															if($Parametro[IdTipoParametro][$i]==1){	
																switch($Parametro[IdTipoTexto][$i]){
																	case '1':																	
																		switch($Parametro[IdMascaraCampo][$i]){
																			case '1':	//Data
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																				break;
																			case '2':	//Inteiro	
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																				break;
																			case '3':	//Float																				
																				if($Parametro[Editavel][$i] == 1){
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																				}else{
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																				}
																				break;
																			case '4':	//Usuário																				
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'usuario')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																				break;
																			case '5':	//MAC																				
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'mac')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																				break;
																			default:
																				if($Parametro[IdTipoTexto][$i] == 1){
																					$tipo	=	'text';
																				}else{
																					$tipo	=	'password';
																				}																																						
																				echo"<td valign='top'>
																						<input class='FormPadrao' type='$tipo' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																						<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																						<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>
																						<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>																						
																						";																				
																		}
																		break;
																	case '2':																			
																		echo"<td valign='top'>
																				<input class='FormPadrao' type='password' name='Valor_".$Parametro[IdParametroServico][$i]."' value='' $tamMax style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																				<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																				<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>																		
																				<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";																
																		if($Parametro[IdTipoTexto][$i] ==	2)
																		   echo"<input type='hidden' name='SenhaValor_".$Parametro[IdParametroServico][$i]."' value=''>";																		
																		break;
																}
																if($Parametro[Obs][$i]!=""){
																	echo	"<BR>".$Parametro[Obs][$i];
																}
																echo"</td>";
															}else{
																if($disabled == "readOnly"){
																	$disabled	=	"disabled";
																}
																
																echo"<td valign='top'>";	
																echo	"<select name='Valor_".$Parametro[IdParametroServico][$i]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled $tabindex>";
																echo		"<option value=''></option>";
																
																$valor	=	explode("\n",$Parametro[OpcaoValor][$i]);
																$tam = 0;
																for($ii=0; $ii<count($valor); $ii++){
																	if($valor[$ii] != "") $tam++;
																}
																for($ii=0; $ii<$tam; $ii++){	
																	$selecionado = "";
																	if(trim($Parametro[Valor][$i]) == trim($valor[$ii])){
																		$selecionado	=	"selected=true";
																	}
																	echo "<option value=".$valor[$ii]." $selecionado>".$valor[$ii]."</option>";
																}
																
																echo "</select>";
																echo	"<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																
																if($Parametro[Obs][$i]!=""){
																	echo	"<BR>".$Parametro[Obs][$i];
																}
																echo"</TD>";
															}			
															
															
															if($prox1 < $qtd){
																if($Parametro[TamMaximo][$prox1]!=""){
																	$tamMax	=	"maxlength='".$Parametro[TamMaximo][$prox1]."'";
																}else{
																	$tamMax	=	"";
																}
															
																if($Parametro[Editavel][$prox1]==1){
																	$tab++;
																	$disabled	=	"";
																	$tabindex	=	"tabindex='$tab'";
																}else{
																	$disabled	=	"readOnly";
																	$tabindex	=	"";
																}
															
																echo"<td class='sep' />";
																if($Parametro[IdTipoParametro][$prox1]==1){	
																	switch($Parametro[IdTipoTexto][$prox1]){
																		case '1':
																			switch($Parametro[IdMascaraCampo][$prox1]){
																				case '1':	//Data
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																					break;
																				case '2':	//Inteiro
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																					break;
																				case '3':	//Float
																					if($Parametro[Editavel][$prox1] == 1){
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																					}else{
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																					}
																					break;
																				case '4':	//Usuário
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'usuario')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																					break;
																				case '5':	//MAC
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'mac')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																					break;
																				default:	
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																			}
																			break;
																		case '2':
																			if($Parametro[IdTipoTexto][$i] == 2 && $Parametro[IdTipoTexto][$prox1] == 2){
																				echo"<td valign='top'>
																						<input class='FormPadrao' type='password' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='' $tamMax style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																						<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$i]."'>
																						<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'>
																						<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";																				
																			}
																			break;
																	}
																	
																	if($Parametro[Obs][$prox1]!=""){
																		echo	"<BR>".$Parametro[Obs][$prox1];
																	}
																	
																	echo"</td>";
																}else{
																	if($disabled == "readOnly"){
																		$disabled	=	"disabled";
																	}
																	
																	echo"<td valign='top'>";	
																	echo	"<select name='Valor_".$Parametro[IdParametroServico][$prox1]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled $tabindex>";
																	echo		"<option value=''></option>";
																	
																	$valor	=	explode("\n",$Parametro[OpcaoValor][$prox1]);
																	$tam = 0;
																	for($ii=0; $ii<count($valor); $ii++){
																		if($valor[$ii] != "") $tam++;
																	}
																	for($ii=0; $ii<$tam; $ii++){	
																		$selecionado = "";
																		if(trim($Parametro[Valor][$prox1]) == trim($valor[$ii])){
																			$selecionado	=	"selected=true";
																		}
																		echo "<option value='".$valor[$ii]."' $selecionado>".$valor[$ii]."</option>";
																	}
																	
																	echo "</select>";
																	echo	"<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																
																	
																	if($Parametro[Obs][$prox1]!=""){
																		echo	"<BR>".$Parametro[Obs][$prox1];
																	}
																	
																	echo"</td>";
																}
															}
															
															if($prox2 < $qtd){
																if($Parametro[TamMaximo][$prox2]!=""){
																	$tamMax	=	"maxlength='".$Parametro[TamMaximo][$prox2]."'";
																}else{
																	$tamMax	=	"";
																}
															
																
																if($Parametro[Editavel][$prox2]==1){
																	$tab++;
																	$disabled	=	"";
																	$tabindex	=	"tabindex='$tab'";
																}else{
																	$disabled	=	"readOnly";
																	$tabindex	=	"";
																}
																
															
																echo"<td class='sep' />";
																if($Parametro[IdTipoParametro][$prox2]==1){	
																	switch($Parametro[IdTipoTexto][$prox2]){
																		case '1':
																			switch($Parametro[IdMascaraCampo][$prox2]){
																				case '1':	//Data
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																					break;
																				case '2':	//Inteiro
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																					break;
																				case '3':	//Float
																					if($Parametro[Editavel][$prox2] == 1){
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																					}else{
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																					}
																					break;
																				case '4':	//Usuário
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'usuario')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																					break;
																				case '5':	//MAC
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'mac')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																					break;
																				default:	
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																			}
																			break;
																		case '2':
																			if($Parametro[IdTipoTexto][$prox1-1] == 2 && $tipoColuna2 == 2 && $Parametro[IdTipoTexto][$prox2] == 2){
																				echo"<td valign='top'><input class='FormPadrao' type='password' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='' $tamMax style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																			}
																			break;
																	}
																	if($Parametro[IdTipoTexto][$prox1] == 1 &&  $tipoColuna1 == 1){
																		if($Parametro[Obs][$prox2]!=""){
																			echo	"<BR>".$Parametro[Obs][$prox2];
																		}
																	}
																	echo"</td>";
																}else{
																	if($disabled == "readOnly"){
																		$disabled	=	"disabled";
																	}
																	
																	echo"<td valign='top'>";	
																	echo	"<select name='Valor_".$Parametro[IdParametroServico][$prox2]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled $tabindex>";
																	echo		"<option value=''></option>";
																	
																	$valor	=	explode("\n",$Parametro[OpcaoValor][$prox2]);
																	$tam = 0;
																	for($ii=0; $ii<count($valor); $ii++){
																		if($valor[$ii] != "") $tam++;
																	}
																	for($ii=0; $ii<$tam; $ii++){	
																		$selecionado = "";
																		if(trim($Parametro[Valor][$prox2]) == trim($valor[$ii])){
																			$selecionado	=	"selected=true";
																		}
																		echo "<option value='".$valor[$ii]."' $selecionado>".$valor[$ii]."</option>";
																	}
																	
																	echo "</select>";
																	echo	"<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																	
																	if($Parametro[Obs][$prox2]!=""){
																		echo	"<BR>".$Parametro[Obs][$prox2];
																	}
																	echo"</td>";
																}
															}
															
															echo"	</tr>";	
															echo "</table>";
															if($tipoColuna1 == 1 && $tipoColuna2 == 1 && ($tipoColuna3 == 2 || $tipoColuna3 == 0)){															
																$i +=1;
															}															
															if($tipoColuna1 == 1 && $tipoColuna2 == 1 && ($tipoColuna3 == 1 || $tipoColuna3 == 0)){
																$i +=2;
															}
															if($tipoColuna1 == 2 && $tipoColuna2 == 2 && ($tipoColuna3 == 2 || $tipoColuna3 == 0)){
																$i +=2;
															}
															
															$tipoColuna3 = 0;															
											
														}
														
														
														$cont		=	1;
														$sqlAuto	=	"select 
																			ContratoAutomatico.IdContratoAutomatico,
																			Contrato.IdServico,
																			Contrato.IdServico, 
																			Servico.DescricaoServico,
																			Contrato.IdPeriodicidade
																		from 
																			(select	ContratoAutomatico.IdContrato,	ContratoAutomatico.IdContratoAutomatico from ContratoAutomatico where ContratoAutomatico.IdLoja = $local_IdLoja and ContratoAutomatico.IdContrato = $local_IdContrato) ContratoAutomatico, 
																			Contrato,
																			Servico 
																		where 
																			Contrato.IdLoja = $local_IdLoja and 
																			Contrato.IdLoja = Servico.IdLoja and
																			Contrato.IdServico = Servico.IdServico and
																			Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
														$resAuto 	= 	@mysql_query($sqlAuto,$con);
														while($linAuto = @mysql_fetch_array($resAuto)){
															$sql20	=	"select Valor,ValorRepasseTerceiro,ValorDesconto,IdTipoDesconto,IdContratoTipoVigencia,LimiteDesconto from ContratoVigenciaAtiva where IdLoja = $local_IdLoja and IdContrato = $linAuto[IdContratoAutomatico]";
															$res20	=	@mysql_query($sql20,$con);
															$lin20	=	@mysql_fetch_array($res20);
															
															$lin20[Valor]	=	formata_double($lin20[Valor]);
															$lin20[Valor]	=	str_replace('.',',',$lin20[Valor]);
															
															if($lin20[IdTipoDesconto] == 1){
																$lin20[ValorFinal]	=	$lin20[Valor] - $lin20[ValorDesconto];
															}else{
																$lin20[ValorFinal]	=	$lin20[Valor];
															}
															
															$sql21	=	"SELECT Fator from Periodicidade where Periodicidade.IdLoja = $local_IdLoja and Periodicidade.IdPeriodicidade = $linAuto[IdPeriodicidade]";
															$res21	=	@mysql_query($sql21,$con);
															$lin21	=	@mysql_fetch_array($res21);
															
															$lin21[ValorPeriodicidade] = 	$lin20[ValorFinal]*$lin21[Fator];
															$lin21[ValorPeriodicidade] =	formata_double($lin21[ValorPeriodicidade]);
															$lin21[ValorPeriodicidade] =	str_replace('.',',',$lin21[ValorPeriodicidade]);
															
															echo"
															<div class='separador'>&nbsp;</div>

															
															<table>
																<tr>
																	<td class='title'>Serviço Automático ($cont)</td>
																	<td class='sep' />
																	<td class='title'>Valor Mensal do Serviço (".getParametroSistema(5,1).")</td>
																	<td class='sep' />
																	<td class='title'>Valor Periodicidade (".getParametroSistema(5,1).")</td>
																</tr>
																<tr>
																	<td>
																		<input class='FormPadrao' type='text' name='DescricaoServico_$linAuto[IdContratoAutomatico]' value='$linAuto[DescricaoServico]' style='width:288px' maxlength='100' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' name='ValorMensal_$linAuto[IdContratoAutomatico]' value='$lin20[Valor]' style='width:150px' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' name='ValorPeriodicidade_$linAuto[IdContratoAutomatico]' value='$lin21[ValorPeriodicidade]' style='width:140px' readOnly>
																	</td>
																</tr>
															</table>
															";
															
															
															$qtd	=	0;
															$sqlPam	=	"select
																			ServicoParametro.IdServico,
																			ServicoParametro.IdParametroServico,
																			ServicoParametro.DescricaoParametroServicoCDA,
																			ContratoParametro.Valor,
																			ServicoParametro.ValorDefault,
																			ServicoParametro.Obrigatorio,
																			ServicoParametro.Obs,
																			ServicoParametro.RotinaCalculo,
																			ServicoParametro.RotinaOpcoes,
																			ServicoParametro.RotinaOpcoesContrato,
																			ServicoParametro.Calculavel,
																			ServicoParametro.RotinaOpcoes,
																			ServicoParametro.RotinaOpcoesContrato,
																			ServicoParametro.CalculavelOpcoes,
																			ServicoParametro.Editavel,
																			ServicoParametro.IdTipoParametro,
																			ServicoParametro.IdMascaraCampo,
																			ServicoParametro.IdTipoTexto,
																			ServicoParametro.ExibirSenha,
																			ServicoParametro.TamMinimo,
																			ServicoParametro.TamMaximo,
																			ServicoParametro.OpcaoValor,
																			ServicoParametro.VisivelCDA
																		from 
																			Loja,
																			Servico,
																			ServicoParametro LEFT JOIN 
																					ContratoParametro ON (
																						ServicoParametro.IdLoja = ContratoParametro.IdLoja and 
																						ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico and
																						ServicoParametro.IdServico = ContratoParametro.IdServico and
																						ContratoParametro.IdContrato = $linAuto[IdContratoAutomatico])
																		where
																			Servico.IdLoja = $local_IdLoja and
																			Servico.IdServico = ServicoParametro.IdServico and
																			ServicoParametro.IdLoja = Servico.IdLoja and
																			Servico.IdLoja = Loja.IdLoja and
																			ServicoParametro.IdServico = $linAuto[IdServico] and
																			ServicoParametro.VisivelCDA = 1 and
																			ServicoParametro.IdStatus = 1
																		order by 
																	ServicoParametro.IdParametroServico ASC";
															$resPam	=	@mysql_query($sqlPam,$con);
															$i = 0;
															while($linPam = @mysql_fetch_array($resPam)){
																if(($linPam[IdTipoTexto] == 3 || $linPam[IdTipoTexto] == 4 || $linPam[IdTipoTexto] == 5) || ($linPam[IdTipoParametro] == 2)){
																	$linPam[IdTipoTexto] = 1;
																}
																if($linPam[IdTipoTexto] == 2){
																	$ParametroValor2[$i]						=	$linPam[Valor];															
																	$i++;
																
																	$Parametro[IdParametroServico][$qtd]		=	'10000';
																	$Parametro[DescricaoParametroServico][$qtd]	=	'Senha Atual '.$linPam[DescricaoParametroServico];
																	$Parametro[Valor][$qtd]						=	'';
																	$Parametro[ValorDefault][$qtd]				=	'';
																	$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];;
																	$Parametro[Obs][$qtd]						=	'';
																	$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
																	$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
																	$Parametro[IdMascaraCampo][$qtd]			=	'';
																	$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
																	$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
																	$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
																	$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
																	$Parametro[OpcaoValor][$qtd]				=	'';
																	
																	$qtd++;	
																	
																	$Parametro[IdParametroServico][$qtd]		=	$linPam[IdParametroServico];
																	$Parametro[DescricaoParametroServico][$qtd]	=	$linPam[DescricaoParametroServicoCDA];
																	$Parametro[Valor][$qtd]						=	$linPam[Valor];
																	$Parametro[ValorDefault][$qtd]				=	$linPam[ValorDefault];
																	$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
																	$Parametro[Obs][$qtd]						=	$linPam[Obs];
																	$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
																	$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
																	$Parametro[IdMascaraCampo][$qtd]			=	$linPam[IdMascaraCampo];
																	$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
																	$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
																	$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
																	$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
																	$Parametro[OpcaoValor][$qtd]				=	$linPam[OpcaoValor];
																																																			
																	$qtd++;	
																	
																	$Parametro[IdParametroServico][$qtd]		=	'10001';
																	$Parametro[DescricaoParametroServico][$qtd]	=	'Confirmação '.$linPam[DescricaoParametroServico];
																	$Parametro[Valor][$qtd]						=	'';
																	$Parametro[ValorDefault][$qtd]				=	'';
																	$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];;
																	$Parametro[Obs][$qtd]						=	'';
																	$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
																	$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
																	$Parametro[IdMascaraCampo][$qtd]			=	'';
																	$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
																	$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
																	$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
																	$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
																	$Parametro[OpcaoValor][$qtd]				=	'';
																	
																	$qtd++;
																}else{
																	$Parametro[IdParametroServico][$qtd]		=	$linPam[IdParametroServico];
																	$Parametro[DescricaoParametroServico][$qtd]	=	$linPam[DescricaoParametroServicoCDA];
																	$Parametro[Valor][$qtd]						=	$linPam[Valor];
																	$Parametro[ValorDefault][$qtd]				=	$linPam[ValorDefault];
																	$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
																	$Parametro[Obs][$qtd]						=	$linPam[Obs];
																	$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
																	$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
																	$Parametro[IdMascaraCampo][$qtd]			=	$linPam[IdMascaraCampo];
																	$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
																	$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
																	$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
																	$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
																	$Parametro[OpcaoValor][$qtd]				=	$linPam[OpcaoValor];
																
																	$qtd++;															
																}
															}
															
															$i		=	0;
															
															if($qtd>0){
																echo"<BR>Parâmetros do Serviço Automático ($cont):";
																$bt_alterar = true;
															}
															
															for($i=0; $i< $qtd; $i++){
																echo "<table>
																		<tr>";
																if($Parametro[Obrigatorio][$i] == 1){
																	if($Parametro[IdTipoTexto][$i] == 2){
																		$Descricao	=	$Parametro[DescricaoParametroServico][$i];
																	}else{
																		$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$i]."</B>";
																	}
																}else{
																	$Descricao	=	$Parametro[DescricaoParametroServico][$i];
																}
																
																echo "<td class='title'>$Descricao</td>";
																
																$tipoColuna1 = $Parametro[IdTipoTexto][$i];
	
																$prox1	=	$i+1;
															
																if($Parametro[IdTipoTexto][$i] == 1 && $Parametro[IdTipoTexto][$prox1] == 1){
																	if($prox1 < $qtd){
																		echo"<td class='sep' />";
																		
																		if($Parametro[Obrigatorio][$prox1] == 1){
																			if($Parametro[IdTipoTexto][$prox1] == 2){
																				$Descricao	=	$Parametro[DescricaoParametroServico][$prox1];
																			}else{
																				$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$prox1]."</B>";
																			}
																		}else{
																			$Descricao	=	$Parametro[DescricaoParametroServico][$prox1];
																		}
																		
																		echo "<td class='title'>$Descricao</td>";
																	}
																}else if($Parametro[IdTipoTexto][$i] == 2 && $Parametro[IdTipoTexto][$prox1] == 2){
																	if($prox1 < $qtd){
																		echo"<td class='sep' />";
																		
																		if($Parametro[Obrigatorio][$prox1] == 1){
																			if($Parametro[IdTipoTexto][$prox1] == 2){
																				$Descricao	=	$Parametro[DescricaoParametroServico][$prox1];
																			}else{
																				$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$prox1]."</B>";
																			}
																		}else{
																			$Descricao	=	$Parametro[DescricaoParametroServico][$prox1];
																		}
																		
																		echo "<td class='title'>$Descricao</td>";
																	}																
																}
																
																$tipoColuna2 = $Parametro[IdTipoTexto][$prox1];
	
																$prox2	=	$i+2;														
																if($Parametro[IdTipoTexto][$prox1] == 1 && $Parametro[IdTipoTexto][$prox2] == 1){															
																	if($prox2 < $qtd){
																		echo"<td class='sep' />";
																		
																		if($Parametro[Obrigatorio][$prox2] == 1){
																			if($Parametro[IdTipoTexto][$prox2] == 2){
																				$Descricao	=	$Parametro[DescricaoParametroServico][$prox2];
																			}else{
																				$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$prox2]."</B>";
																			}
																		}else{
																			$Descricao	=	$Parametro[DescricaoParametroServico][$prox2];
																		}
																	
																		echo "<td class='title'>$Descricao</td>";
																	}																
																}else if($Parametro[IdTipoTexto][$prox1-1] == 2 && $Parametro[IdTipoTexto][$prox2] == 2){
																	if($prox2 < $qtd){
																		echo"<td class='sep' />";
																		
																		if($Parametro[Obrigatorio][$prox2] == 1){
																			if($Parametro[IdTipoTexto][$prox2] == 2){
																				$Descricao	=	$Parametro[DescricaoParametroServico][$prox2];
																			}else{
																				$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$prox2]."</B>";
																			}
																		}else{
																			$Descricao	=	$Parametro[DescricaoParametroServico][$prox2];
																		}
																	
																		echo "<td class='title'>$Descricao</td>";
																	}				
																}
																$tipoColuna3 = $Parametro[IdTipoTexto][$prox2];
																
																echo "</tr>";
																echo "<tr>";
																
																if($Parametro[TamMaximo][$i]!=""){
																	$tamMax	=	"maxlength='".$Parametro[TamMaximo][$i]."'";
																}else{
																	$tamMax	=	"";
																}
																
																if($Parametro[Editavel][$i]==1){
																	$tab++;
																	$disabled	=	"";
																	$tabindex	=	"tabindex='$tab'";
																}else{
																	$disabled	=	"readOnly";
																	$tabindex	=	"";
																}
																
																if($Parametro[IdTipoParametro][$i]==1){	
																	switch($Parametro[IdTipoTexto][$i]){
																		case '1':
																			switch($Parametro[IdMascaraCampo][$i]){
																				case '1':	//Data
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																					break;
																				case '2':	//Inteiro
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																					break;
																				case '3':	//Float
																					if($Parametro[Editavel][$i] == 1){
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																					}else{
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																					}
																					break;
																				case '4':	//Usuário
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'usuario')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																					break;
																				case '5':	//MAC
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'mac')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																					break;
																				default:
																					if($Parametro[IdTipoTexto][$i] == 1){
																						$tipo	=	'text';
																					}else{
																						$tipo	=	'password';
																					}	
																					echo"<td valign='top'>
																							<input class='FormPadrao' type='$tipo' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Valor][$i]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																							<input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																							<input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>
																							<input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";																					
																			}
																			break;
																		case '2':
																			if($Parametro[ExibirSenha][$i] == 1){
																				$tipo	=	'text';
																			}else{
																				$tipo	=	'password';
																			}	
																			echo"<td valign='top'>
																					<input class='FormPadrao' type='$tipo' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='' $tamMax style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																					<input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																					<input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>
																					<input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			if($Parametro[IdTipoTexto][$i] ==	2)
																			   echo"<input type='hidden' name='SenhaValorAutomatico_".$Parametro[IdParametroServico][$i]."' value=''>";
																			break;
																	}
																	
																	if($Parametro[Obs][$i]!=""){
																		echo	"<BR>".$Parametro[Obs][$i];
																	}
																}else{
																	if($disabled == "readOnly"){
																		$disabled	=	"disabled";
																	}
																	
																	echo"<td valign='top'>";	
																	echo	"<select name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled $tabindex>";
																	echo		"<option value=''></option>";
																	
																	$valor	=	explode("\n",$Parametro[OpcaoValor][$i]);
																	$tam = 0;
																	for($ii=0; $ii<count($valor); $ii++){
																		if($valor[$ii] != "") $tam++;
																	}
																	for($ii=0; $ii<$tam; $ii++){
																		$selecionado = "";
																		if(trim($Parametro[Valor][$i]) == trim($valor[$ii])){
																			$selecionado	=	"selected=true";
																		}
																		echo "<option value=".$valor[$ii]." $selecionado>".$valor[$ii]."</option>";
																	}
																	
																	echo "</select>";
																	echo "<input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																	
																	if($Parametro[Obs][$i]!=""){
																		echo	"<BR>".$Parametro[Obs][$i];
																	}
																	echo"</td>";
																}		
																
																$tab++;	
																
																if($prox1 < $qtd){
																	if($Parametro[TamMaximo][$prox1]!=""){
																		$tamMax	=	"maxlength='".$Parametro[TamMaximo][$prox1]."'";
																	}else{
																		$tamMax	=	"";
																	}
																
																	if($Parametro[Editavel][$prox1]==1){
																		$tab++;
																		$disabled	=	"";
																		$tabindex	=	"tabindex='$tab'";
																	}else{
																		$disabled	=	"readOnly";
																		$tabindex	=	"";
																	}
																
																	echo"<td class='sep' />";
																	if($Parametro[IdTipoParametro][$prox1]==1){	
																		switch($Parametro[IdTipoTexto][$prox1]){
																			case '1':
																				switch($Parametro[IdMascaraCampo][$prox1]){
																					case '1':	//Data
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																						break;
																					case '2':	//Inteiro
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																						break;
																					case '3':	//Float
																						if($Parametro[Editavel][$prox1] == 1){
																							echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																						}else{
																							echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																						}
																						break;
																					case '4':	//Usuário
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'usuario')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																						break;
																					case '5':	//MAC
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'mac')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																						break;
																					default:	
																						echo"<td valign='top'><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Valor][$prox1]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				}
																				break;
																			case '2':
																				if($Parametro[IdTipoTexto][$i] == 2 && $Parametro[IdTipoTexto][$prox1] == 2){
																					if($Parametro[ExibirSenha][$prox1] == 1){
																						$tipo	=	'text';
																					}else{
																						$tipo	=	'password';
																					}		
																					echo"<td valign='top'><input class='FormPadrao' type='$tipo' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='' $tamMax style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				}
																				break;
																		}
																		if($Parametro[Obs][$prox1]!=""){
																			echo	"<BR>".$Parametro[Obs][$prox1];
																		}
																		echo"</td>";
																	}else{
																		if($disabled == "readOnly"){
																			$disabled	=	"disabled";
																		}
																		
																		echo"<td valign='top'>";	
																		echo	"<select name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled $tabindex>";
																		echo		"<option value=''></option>";
																		
																		$valor	=	explode("\n",$Parametro[OpcaoValor][$prox1]);
																		$tam = 0;
																		for($ii=0; $ii<count($valor); $ii++){
																			if($valor[$ii] != "") $tam++;
																		}
																		for($ii=0; $ii<$tam; $ii++){
																			$selecionado = "";
																			if(trim($Parametro[Valor][$prox1]) == trim($valor[$ii])){
																				$selecionado	=	"selected=true";
																			}
																			echo "<option value='".$valor[$ii]."' $selecionado>".$valor[$ii]."</option>";
																		}
																		
																		echo "</select>";
																		echo	"<input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																		
																		if($Parametro[Obs][$prox1]!=""){
																			echo	"<BR>".$Parametro[Obs][$prox1];
																		}
																		echo"</td>";
																	}
																}
																
																if($prox2 < $qtd){
																	if($Parametro[TamMaximo][$prox2]!=""){
																		$tamMax	=	"maxlength='".$Parametro[TamMaximo][$prox2]."'";
																	}else{
																		$tamMax	=	"";
																	}
																
																	if($Parametro[Editavel][$prox2]==1){
																		$tab++;
																		$disabled	=	"";
																		$tabindex	=	"tabindex='$tab'";
																	}else{
																		$disabled	=	"readOnly";
																		$tabindex	=	"";
																	}
																	
																	$tab++;
																
																	echo"<td class='sep' />";
																	if($Parametro[IdTipoParametro][$prox2]==1){	
																		switch($Parametro[IdTipoTexto][$prox2]){
																			case '1':
																				switch($Parametro[IdMascaraCampo][$prox2]){
																					case '1':	//Data
																						echo"<td><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																						break;
																					case '2':	//Inteiro
																						echo"<td><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																						break;
																					case '3':	//Float
																						if($Parametro[Editavel][$prox2] == 1){
																							echo"<td><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'>";
																						}else{
																							echo"<td><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																						}
																						break;
																					case '4':	//Usuário
																						echo"<td><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'usuario')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																						break;
																					case '5':	//MAC
																						echo"<td><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px' maxlenght='17' onkeypress=\"mascara(this,event,'mac')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																						break;
																					default:	
																						echo"<td><input class='FormPadrao' type='text' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Valor][$prox2]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				}
																				break;
																			case '2':
																				if($Parametro[IdTipoTexto][$i] == 2 && $Parametro[IdTipoTexto][$prox1] == 2){
																					if($Parametro[ExibirSenha][$prox1] == 1){
																						$tipo	=	'text';
																					}else{
																						$tipo	=	'password';
																					}	
																					echo"<td valign='top'><input class='FormPadrao' type='$tipo' name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='' $tamMax style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				}
																				break;
																		}
																		if($Parametro[IdTipoTexto][$prox1] == 1 &&  $tipoColuna1 == 1){
																			if($Parametro[Obs][$prox2]!=""){
																				echo	"<BR>".$Parametro[Obs][$prox2];
																			}
																		}
																		echo"</td>";
																	}else{
																		if($disabled == "readOnly"){
																			$disabled	=	"disabled";
																		}
																		
																		echo"<td>";	
																		echo	"<select name='ValorAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled $tabindex>";
																		echo		"<option value=''></option>";
																		
																		$valor	=	explode("\n",$Parametro[OpcaoValor][$prox2]);
																		$tam = 0;
																		for($ii=0; $ii<count($valor); $ii++){
																			if($valor[$ii] != "") $tam++;
																		}
																		for($ii=0; $ii<$tam; $ii++){
																			$selecionado = "";
																			if(trim($Parametro[Valor][$prox2]) == trim($valor[$ii])){
																				$selecionado	=	"selected=true";
																			}
																			echo "<option value='".$valor[$ii]."' $selecionado>".$valor[$ii]."</option>";
																		}
																		
																		echo "</select>";
																		echo	"<input type='hidden' name='ObrigatorioAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTextoAutomatico_".$linAuto[IdServico]."_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																		
																		if($Parametro[Obs][$prox2]!=""){
																			echo	"<BR>".$Parametro[Obs][$prox2];
																		}
																		echo"</td>";
																	}
																}
																
																echo"	</tr>";	
																echo "</table>";	
																if($tipoColuna1 == 1 && $tipoColuna2 == 1 && ($tipoColuna3 == 2 || $tipoColuna3 == 0)){															
																	$i +=1;
																}															
																if($tipoColuna1 == 1 && $tipoColuna2 == 1 && ($tipoColuna3 == 1 || $tipoColuna3 == 0)){
																	$i +=2;
																	}
																if($tipoColuna1 == 2 && $tipoColuna2 == 2 && ($tipoColuna3 == 2 || $tipoColuna3 == 0)){
																	$i +=2;
																}
															
																$tipoColuna3 = 0;
																//$i=$i+2;
																$tab++;
															}
															
															$cont++;
														}
														
														$botao		=	"";
																																										
														if($lin[UrlDistratoImpresso]!="" && $lin[DataTermino] != ""){
															$botao	= "&nbsp;&nbsp;<input name='bt_imprimir_distrato' type='button' class='BotaoPadrao' value='Imprimir Distrato' onClick=\"imprimir('../administrativo/imprimir_distrato.php?IdContrato=$local_IdContrato&cda=1')\" tabindex='301'>";
						               					}
														
														if($lin[UrlContratoImpresso]!=""){
															if($lin[AssinaturaContrato] == 2 && $lin[TermoCienciaCDA] == 1){
																echo " <div id='frame'>
																			<iframe src='../administrativo/imprimir_contrato.php?IdContrato=$local_IdContrato&cda=1' style='width: 600px;height: 220px;border: none'></iframe>
																			<input type='checkbox' style='margin-top: 10px' onChange='habilitar_butao_salvar(this)'/>".getParametroSistema(95,36)."<br/>
																	   </div>";
																	   
																$botaosalvar ="<input type='button' name='bt_salvar' value='salvar' class='BotaoPadrao' onClick='cadastrar_contrato()' disabled />";	 
															}
															else{
																$botao	.=	"&nbsp;&nbsp;<input name='bt_imprimir_contrato' type='button' class='BotaoPadrao' value='Imprimir Contrato' onClick=\"imprimir('../administrativo/imprimir_contrato.php?IdContrato=$local_IdContrato&cda=1')\"  tabindex='302'/>";
															}
														}
														
														if($bt_alterar == true){
															$botao	.=	"&nbsp;&nbsp;<input name='bt_alterar' type='button' class='BotaoPadrao' value='Alterar' onClick='cadastrar_contrato()'  tabindex='303'/>";
														}
														
														echo "<table style='width: 100%;'>
																<tr>	
																	<td>$botaosalvar</td>
						               								<td style='text-align: right; width: 468px'><input name='bt_voltar' type='button' class='BotaoPadrao' value='Voltar' onClick=\"window.location='?ctt=listar_contrato.php&IdParametroSistema=$local_IdParametroSistema'\"  tabindex='300' /></td>
						               								<td>$botao</td>
						               							</tr>
						             		 				</table>
						             		 				<table>
																<tr>
																	<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
																</tr>
															</table>";
														
												?>
											</form>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
								<script>
									<?
										if($local_Erro!=""){
										//	echo "mensagem($local_Erro);";
										}
										$i=0;
										echo"var pos=0;";
										while($ParametroValor[$i]){																						
											echo"												 											 
												 for(i=pos; i<document.formulario.length; i++){
												 	if(document.formulario[i].name == 'Valor_10000'){													
													   document.formulario[i+4].value = '".$ParametroValor[$i]."';													   
													   pos = i+1; 
													   break;
													}
												 }";									
											$i++;
										}	
										
										// Parametro Automatico
										$i=0;
										echo"var pos2=0, str2, campo2;";
										while($ParametroValor2[$i]){																						
											echo"												 											 
												 for(i=pos2; i<document.formulario.length; i++){
												 	str2 	= document.formulario[i].name.split('_');
													campo2 	= str2[0]+'_'+str2[2];
												 	if(campo2 == 'ValorAutomatico_10000'){													
													   document.formulario[i+4].value = '".$ParametroValor2[$i]."';													   
													   pos2 = i+1; 
													   break;
													}
												 }";									
											$i++;
										}										
									?>
									function imprimir(url){
										window.open(url); 
									}								
									enterAsTab(document.forms.formulario);
								</script>					
