								<?
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									
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
							    			<div id='filtroBuscar' align="right">
												<form name='filtro' method='post' action='?ctt=listar_ordem_servico.php&IdParametroSistema=<?=$local_IdParametroSistema?>'>
													<table>
														<tr>
															<td class='title'>Status</td>
														</tr>
														<tr>
															<td>
																<select class='FormPadrao' name='filtro_status' style='width:180px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="document.filtro.submit()">
																	<option value=''>Todos</option>
																	<?
																		$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema!= 0 order by ValorParametroSistema ASC";
																		$res = @mysql_query($sql,$con);
																		while($lin = @mysql_fetch_array($res)){
																			echo "<option value='$lin[IdParametroSistema]' ".compara($local_IdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
																		}
																	?>
																</select>
															</td>
														</tr>
													</table>
												</form>
											</div>
											<table width="100%" id='tableQuadro' style='margin-top:10px;' border="0" cellspacing="0" cellpadding="0">
												<tr>
													<th style='width:45px'>Código</th>
													<th>Descrição</th>
													<th>Tipo</th>
													<th style='text-align:center'>Data&nbsp;Agendamento</th>
													<th style='text-align:center'>Status</th>
													<th>&nbsp;</th>
												</tr>
												<?
													$filtro_sql	=	"";
													
													if($local_IdStatus!=""){
														$filtro_sql  .= " and OrdemServico.IdStatus = ".$local_IdStatus;
													}
													
													$i = 0;
													$sql	=	"select
																	OrdemServico.IdTipoOrdemServico,
																	OrdemServico.IdOrdemServico,
																	OrdemServico.DescricaoCDA,
																	OrdemServico.DescricaoOS,
																	substring(OrdemServico.DescricaoOS,1,30) DescricaoOSTemp,
																	substring(OrdemServico.DescricaoCDA,1,30) DescricaoCDATemp,
																	OrdemServico.IdStatus,
																	OrdemServico.DataAgendamentoAtendimento,
																	OrdemServico.LoginAtendimento
																from
																	OrdemServico,
																	Pessoa
																where
																	OrdemServico.IdStatus != 0 and
																	OrdemServico.IdPessoa = $local_IdPessoa and
																	OrdemServico.IdPessoa = Pessoa.IdPessoa $filtro_sql
																order by 
																	OrdemServico.DataAgendamentoAtendimento DESC, 
																	OrdemServico.IdOrdemServico DESC";
													$res	=	@mysql_query($sql,$con);
													if(@mysql_num_rows($res) >=1){
														while($lin	=	@mysql_fetch_array($res)){
															if(($i%2) != 0){
																$color	=	"background-color:#F2F2F2";
															}else{
																$color	=	"";
															}

															if($lin[DescricaoCDA] != ""){
																$valorDescOS = "$lin[DescricaoCDATemp]";
															}else{
																$valorDescOS = "$lin[DescricaoOSTemp]";
															}
															
//															$lin[Tipo]	= getParametroSistema(72,$lin[IdTipoOrdemServico]);
															$sql2 = "select	TipoOrdemServico.DescricaoTipoOrdemServico from TipoOrdemServico where TipoOrdemServico.IdLoja=$local_IdLoja and TipoOrdemServico.IdTipoOrdemServico=$lin[IdTipoOrdemServico];";
															$res2 = @mysql_query($sql2,$con);
															$lin2 = @mysql_fetch_array($res2);
															$lin[Tipo] = $lin2[DescricaoTipoOrdemServico];
															
															$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
															$res3 = @mysql_query($sql3,$con);
															$lin3 = @mysql_fetch_array($res3);
															
															echo"
															<tr>
																<TD style='$color'>$lin[IdOrdemServico]</TD>
																<TD style='$color' title='$valorDescOS'>$valorDescOS</TD>
																<TD style='$color'>$lin[Tipo]</TD>
																<TD style='$color; text-align:center; width:92px'>".dataConv($lin[DataAgendamentoAtendimento],'Y-m-d H:i:s','d/m/Y H:i')."</TD>
																<TD style='$color; text-align:center'>$lin3[ValorParametroSistema]</TD>
																<TD style='$color'><a href='menu.php?ctt=cadastro_ordem_servico.php&IdOrdemServico=$lin[IdOrdemServico]&IdPessoa=$local_IdPessoa&IdParametroSistema=4' target='_blank' style='color:#C10000;'>Visualizar</a></TD>
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
															<th colspan='6' style='text-align:left'>Total: $i</th>
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
