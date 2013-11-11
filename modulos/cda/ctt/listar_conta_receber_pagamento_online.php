								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$IdLoja						= 	$_SESSION["IdLojaCDA"];
									$local_Login				=	$_SESSION['LoginCDA'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_ImprimirVencido		=	getParametroSistema(95,18);
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
								    $local_IdStatus		=	$_POST['filtro_status'];
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
													<th>Nº Doc.</th>
													<th style='text-align:center'>Data Lanç.</th>
													<th style='text-align:right'>Valor (<?=getParametroSistema(5,1)?>)</th>
													<th style='text-align:center'>Data Venc.</th>
													<th>&nbsp;</th>
												</tr>
												<?
													$filtro_sql	=	"";
													
													if($local_IdStatus!=""){
														$filtro_sql  .= " and ContaReceberDados.IdStatus = ".$local_IdStatus;
													}
																										
													$i = 0;
													$Tvalor		=	0;
													$Trebedido	=	0;
													$sql	=	"select distinct
																	ContaReceberDados.IdLoja,
																	ContaReceberDados.IdContaReceber,
																	ContaReceberDados.NumeroDocumento,
																	ContaReceberDados.DataLancamento,
																	(ContaReceberDados.ValorFinal) Valor,
																	ContaReceberDados.ValorDesconto,
																	ContaReceberDados.DataVencimento,
																	ContaReceberDados.IdLocalCobranca,
																	LocalCobranca.AbreviacaoNomeLocalCobranca,
																	ContaReceberDados.IdStatus,
																	LocalCobranca.IdLocalCobrancaLayout,
																	ContaReceberDados.MD5,
																	ContaReceber.IdStatusConfirmacaoPagamento											
																from
																	ContaReceberDados,
																	ContaReceber,
																	Pessoa,
																	LocalCobranca
																where
																	ContaReceberDados.IdLoja = '$local_IdLoja' and
																	ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
																	ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
																	ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
																	ContaReceberDados.IdPessoa = '$local_IdPessoa' and
																	ContaReceberDados.IdStatus = '1' AND
																	ContaReceberDados.IdLoja = ContaReceber.IdLoja AND
																	ContaReceberDados.IdContaReceber = ContaReceber.IdContaReceber
																	$filtro_sql
																group by
																	ContaReceberDados.IdContaReceber
																order by
																	ContaReceberDados.DataVencimento";
													$res	=	@mysql_query($sql,$con);
													if(@mysql_num_rows($res) >=1){
														while($lin	=	@mysql_fetch_array($res)){
															if(($i%2) != 0){
																$color	=	"background-color:#F2F2F2";
															}else{
																$color	=	"";
															}
															if($lin[Valor] == "")			$lin[Valor] 		= 0;
																
															$Tvalor		+=	$lin[Valor];
																															
															if($lin[Valor]!=0){
																$Valor	=	formatNumber(formata_double(($lin[Valor])));
															}else{
																$Valor	=	"&nbsp;";
															}
																
															echo"
															<tr>
																<TD style='$color'>$lin[NumeroDocumento]</TD>
																<TD style='$color; text-align:center'>".dataConv($lin[DataLancamento],'Y-m-d','d/m/Y')."</TD>
																<TD style='$color; text-align:right'>$Valor</TD>
																<TD style='$color; text-align:center'>".dataConv($lin[DataVencimento],'Y-m-d','d/m/Y')."</TD>
																<TD style='$color; text-align:center;'><a href='menu.php?ctt=pagamento_online_demonstrativo.php&IdParametroSistema=10&ContaReceber=$lin[MD5]' style='color:#C10000;'>Pagar On-Line</a></TD>
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
																	<TD>&nbsp;</TD>
															</tr>";
													}
													echo"
														<tr>
															<th colspan='2' style='text-align:left'>Total: $i</th>
															<TH style='text-align:right'>".formatNumber(formata_double($Tvalor))."</TH>
															<TH>&nbsp;</TH>
															<TH colspan='3'>&nbsp;</TH>
														</tr>
													";													
												?>
											</table>
											<br />
											<div style='float:right;'><input type='button' class='BotaoPadrao' onclick='window.print();' value='Imprimir' /></div>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
