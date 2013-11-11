								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$local_Login				=	$_SESSION['LoginCDA'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
								?>
								<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							      		<td width="15"><img class='OcultarImpressao' src="img/hgr1.png" width="15" height="50" /></td>
									    <td id="tit" width="387"><h1><img src="img/icones/<?=$local_IdParametroSistema?>.png" /> <?=$local_Descricao?></h1></td>
									    <td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="?ctt=index.php">Página Inicial</a></td>
									    <td width="15"><img class='OcultarImpressao' src="img/hgr2.png" width="15" height="50" /></td>
							    	</tr>
							    </table>
							    <table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							    		<td colspan='4' class='coluna2main'>
						    				<table width="100%" id='tableQuadro' style='margin-top:10px;' border="0" cellspacing="0" cellpadding="0">
												<tr>
													<th>Id</th>
													<th>Serviço</th>
													<th>Referência</th>
													<th style='text-align:right'>&nbsp;</th>													
													<th style='text-align:right'>Valor Final (<?=getParametroSistema(5,1)?>)</th>
													<th style='text-align:right'>&nbsp;</th>
													<th style='text-align:center'>Status</th>
												</tr>
												<?
													$where	=	"";
													
													if(getParametroSistema(95,19)==1){
														$where	=	" and Contrato.IdStatus != 1";
													}
													
													$i = 0;
													$Tvalor		=	0;
													$Tdesconto	=	0;
													$Ttotal		=	0;
													$sql	=	"select
																	Contrato.IdLoja,
																	Contrato.IdContrato,
																	Contrato.IdServico,
																	Contrato.IdPessoa,				    
																	Contrato.DataInicio,
																	Contrato.DataTermino,
																	Contrato.TipoContrato,
																	Contrato.IdStatus,
																	Contrato.VarStatus,
																	substr(Servico.DescricaoServico,1,23) DescricaoServico,
																	ContratoVigenciaAtiva.Valor,
																	ContratoVigenciaAtiva.ValorDesconto,
																	LocalCobranca.AbreviacaoNomeLocalCobranca,
																	(ContratoVigenciaAtiva.Valor - ContratoVigenciaAtiva.ValorDesconto) ValorFinal,
																	substr(ContratoParametroDemonstrativo.ParametroDemonstrativo,1,23) ParametroDemonstrativo
																from
																	Contrato left join ContratoParametroDemonstrativo on (Contrato.IdLoja = ContratoParametroDemonstrativo.IdLoja and         Contrato.IdContrato = ContratoParametroDemonstrativo.IdContrato),
																	ContratoVigenciaAtiva,
																	Servico,
																	LocalCobranca
																where
																	Contrato.IdPessoa = $local_IdPessoa and
																	Contrato.IdLoja = Servico.IdLoja and
																	Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja and
																	Contrato.IdServico = Servico.IdServico and
																	Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato and
																	Contrato.IdLoja = LocalCobranca.IdLoja and
																	Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
																	$where
																order by
																	Contrato.IdContrato DESC";
													$res	=	@mysql_query($sql,$con);
													if(@mysql_num_rows($res) >=1){
														while($lin	=	@mysql_fetch_array($res)){
															if(($i%2) != 0){
																$color	=	"background-color:#EEEEEE";
															}else{
																$color	=	"";
															}
															if($lin[Valor] == "")			$lin[Valor] 		= 0;
															if($lin[ValorRecebido] == "")	$lin[ValorRecebido] = 0;
															
															$Tvalor		+=	$lin[Valor];
															$Tdesconto	+=	$lin[ValorDesconto];
															$Ttotal		+=	$lin[ValorFinal];
															
															if($lin[Valor]!=0){
																$Valor	=	formatNumber(formata_double(($lin[Valor])));
															}else{
																$Valor	=	"&nbsp;";
															}
															
															if($lin[ValorDesconto]!=""){
																$ValorDesconto	=	formatNumber(formata_double(($lin[ValorDesconto])));
															}
															
															if($lin[ValorFinal]!=""){
																$ValorFinal	=	formatNumber(formata_double(($lin[ValorFinal])));
															}
															
															$sql2 = "select substr(ValorParametroSistema,1,3) ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema=$lin[TipoContrato]";
															$res2 = @mysql_query($sql2,$con);
															$lin2 = @mysql_fetch_array($res2);
															
															$sql3 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
															$res3 = @mysql_query($sql3,$con);
															$lin3 = @mysql_fetch_array($res3);
															
															if($lin[VarStatus] != ''){
																switch($lin[IdStatus]){
																	case '201':
																		$lin3[Status]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin3[Status]);	
																		break;
																}					
															}
															
															if($lin[DataTermino]!= ""){
																$lin[DataTermino]	=	dataConv($lin[DataTermino],'Y-m-d','d/m/Y');
															}else{
																$lin[DataTermino]	=	"&nbsp;";
															}
															
															echo"
															<tr>
																<TD style='$color;'><a href='?ctt=cadastro_contrato.php&IdParametroSistema=$local_IdParametroSistema&IdContrato=$lin[IdContrato]'>$lin[IdContrato]</a></TD>
																<TD style='$color;'><a href='?ctt=cadastro_contrato.php&IdParametroSistema=$local_IdParametroSistema&IdContrato=$lin[IdContrato]'>$lin[DescricaoServico]</a></TD>																
																<TD style='$color;'><a href='?ctt=cadastro_contrato.php&IdParametroSistema=$local_IdParametroSistema&IdContrato=$lin[IdContrato]'>$lin[ParametroDemonstrativo]</a></TD>
																<TD style='$color;'>&nbsp;</TD>
																<TD style='$color; text-align:right'><a href='?ctt=cadastro_contrato.php&IdParametroSistema=$local_IdParametroSistema&IdContrato=$lin[IdContrato]'>$ValorFinal</a></TD>
																<TD style='$color;'>&nbsp;</TD>
																<TD style='$color; text-align:center'><a href='?ctt=cadastro_contrato.php&IdParametroSistema=$local_IdParametroSistema&IdContrato=$lin[IdContrato]'>$lin3[Status]</a></TD>
															</tr>
															";
															
															$i++;
														}
													}else{
														echo"<tr>
																<TD>&nbsp;</TD>																
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
														</tr>";
													}
												echo"
													<tr>
														<th colspan='4' style='text-align:left'>Total: $i</th>													
														<TH style='text-align:right'>".formatNumber(formata_double($Ttotal))."</TH>


														<TH style='text-align:right'>&nbsp;</TH>														
														<TH>&nbsp;</TH>
													</tr>
												";
											?>
											</table>
											<br />
											<div style='float:right;'><input type='button' class='BotaoPadrao' onclick='window.print();' value='Imprimir' /></div>
										</td>
									</tr>
									<tr class='OcultarImpressao'>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>