 								<?
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_IdProtocolo			=	$_GET['IdProtocolo'];
									
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
												<form name='filtro' method='post' action='?ctt=listar_protocolo.php&IdParametroSistema=<?=$local_IdParametroSistema?>'>
													<table>
														<tr>
															<td class='title'>Status</td>
														</tr>
														<tr>
															<td>
																<select class='FormPadrao' name='filtro_status' style='width:180px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="document.filtro.submit()">
																	<option value=''>Todos</option>
																	<?
																		$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=239 and IdParametroSistema!= 0 order by ValorParametroSistema ASC";
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
													<th>Tipo Protocolo</th>
													<th>Assunto</th>
													<th style='text-align:center'>Data Abertura</th>
													<th style='text-align:center'>Status</th>
													<th>&nbsp;</th>
													<th>&nbsp;</th>
													<th>&nbsp;</th>
												</tr>
												<?
													$filtro_sql	=	"";
													
													if($local_IdStatus!=""){
														$filtro_sql  .= " and Protocolo.IdStatus = ".$local_IdStatus;
													}
													
													$i = 0;
													$sql	=	"select
																	Protocolo.IdProtocolo,
																	Protocolo.Assunto,
																	Protocolo.IdProtocoloTipo,
																	Protocolo.Nome,
																	Protocolo.IdStatus,
																	Protocolo.DataCriacao,
																	Protocolo.DataConclusao
																from
																	Protocolo,
																	Pessoa
																where
																	Protocolo.IdLoja = $local_IdLoja and
																	Protocolo.IdStatus != 0 and
																	Protocolo.IdPessoa = $local_IdPessoa and
																	Protocolo.IdPessoa = Pessoa.IdPessoa $filtro_sql
																order by 
																	Protocolo.DataCriacao DESC, 
																	Protocolo.IdOrdemServico DESC";
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
															
															$sql2 = "SELECT 
																		IdProtocoloTipo,
																		DescricaoProtocoloTipo 
																	FROM 
																		ProtocoloTipo 
																	WHERE 
																		IdLoja = $local_IdLoja AND 
																		AberturaCDA = 1 and
																		IdProtocoloTipo = $lin[IdProtocoloTipo]";
																		
															$res2 = @mysql_query($sql2,$con);
															$lin2 = @mysql_fetch_array($res2);
															
															$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=239 and IdParametroSistema=$lin[IdStatus]";
															$res3 = @mysql_query($sql3,$con);
															$lin3 = @mysql_fetch_array($res3);
															
															echo"
															<tr>
																<TD style='$color'>$lin[IdProtocolo]</TD>
																<TD style='$color' title='$valorDescOS'>$lin2[DescricaoProtocoloTipo]</TD>
																<TD style='$color'>".substr($lin[Assunto],0,25)."</TD>
																<TD style='$color; text-align:center; width:92px'>".dataConv($lin[DataCriacao],'Y-m-d H:i:s','d/m/Y H:i')."</TD>
																<TD style='$color; text-align:center'>$lin3[ValorParametroSistema]</TD>
																<TD style='$color'><a href='menu.php?ctt=cadastro_protocolo.php&IdProtocolo=$lin[IdProtocolo]&IdParametroSistema=14' target='_self' style='color:#C10000;'>Visualizar</a></TD>
																<TD style='$color'>&nbsp;</TD>
																<TD style='$color'>&nbsp;</TD>
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
																<TD>&nbsp;</TD>
														</tr>";
													}
													echo"
														<tr>
															<th colspan='8' style='text-align:left'>Total: $i</th>
														</tr>
													";
													
												?>
											</table>
											<br />
											<div style='float:right;'>
												<input type='button' class='BotaoPadrao' onclick="window.location='./menu.php?ctt=cadastro_protocolo.php&IdParametroSistema=14';" value='Gerar Novo Protocolo' />
												<input type='button' class='BotaoPadrao' onclick='window.print();' value='Imprimir' />
											</div>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
