								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$local_Login				=	$_SESSION['LoginCDA'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
								    $local_IdStatus		=	$_POST['filtro_status'];
								    $Limit				=	getParametroSistema(95,13)
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
												<form name='filtro' method='post' action='?ctt=listar_mensagem.php&IdParametroSistema=<?=$local_IdParametroSistema?>'>
													<table>
														<tr>
															<td class='title'>Status</td>
														</tr>
														<tr>
															<td>
																<select class='FormPadrao' name='filtro_status' style='width:180px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="document.filtro.submit()">
																	<option value=''  <?=compara($local_IdStatus,'',"selected='selected'","")?>>Todos</option>
																	<option value='1' <?=compara($local_IdStatus,'1',"selected='selected'","")?>>Última semana</option>
																	<option value='2' <?=compara($local_IdStatus,'2',"selected='selected'","")?>>Último mês</option>
																	<option value='3' <?=compara($local_IdStatus,'3',"selected='selected'","")?>>Último semestre</option>
																	<option value='4' <?=compara($local_IdStatus,'4',"selected='selected'","")?>>Último ano</option>
																</select>
															</td>
														</tr>
													</table>
												</form>
											</div>
											<table width="100%" id='tableQuadro' style='margin-top:10px;' border="0" cellspacing="0" cellpadding="0">
												<tr>
													<th style='width:45px'>E-mail</th>
													<th>Destinatário</th>
													<!--th>Tipo</th-->
													<th style='text-align:center'>Data Envio</th>
													<th style='text-align:center'>Status</th>
													<th>&nbsp;</th>
												</tr>
												<?
													$filtro_sql	=	"";
													
													if($local_IdStatus!=""){
														switch($local_IdStatus){
															case '1': //Semana
																$user_date = date('Y-m-d'); 
																$sun = 0; //sunday = start of week 
																$sat = 6; //saturday = end of week 
																
																$user_date = strtotime($user_date); 
																$dayOfWeek = date('w', $user_date);  // get day number 
																$days_until_sat = $sat - $dayOfWeek; 
																$days_from_sun = $dayOfWeek - $sun; 
																
																$week_start = date('Y-m-d', strtotime(" - $days_from_sun days", $user_date)); 
																$week_end   = date('Y-m-d', strtotime(" + $days_until_sat days", $user_date)); 
																
																$filtro_sql  .= " and substr(HistoricoMensagem.DataEnvio,1,10) BETWEEN '$week_start' AND '$week_end'";
																break;
															case '2': //Mes
																$filtro_sql  .= " and MONTH(HistoricoMensagem.DataEnvio) = MONTH(CURDATE()) and YEAR(HistoricoMensagem.DataEnvio) = YEAR(CURDATE())";
																break;
															case '3': //Semestre
																$user_month = (int) date('m'); 
													
																if($user_month <= 6){
																	$user_inicio	=	1;
																	$user_ultimo	=	7;
																}else{
																	$user_inicio	=	7;
																	$user_ultimo	=	13;
																}	
																
																$qtd_mes_ini	=	$user_month - $user_inicio;
																$qtd_mes_fim	=	$user_ultimo - $user_month;
																
																$dia_ini		=	date("Y-m-01", strtotime("-$qtd_mes_ini month", strtotime(date("Y-m-d")))); 
																$dia_fim_aux	=	date("Y-m-d", strtotime("+$qtd_mes_fim month", strtotime(date("Y-m-01"))));
																$dia_fim		=	date("Y-m-d", strtotime("-1 day", strtotime($dia_fim_aux))); 
															
																$filtro_sql  .= " and substr(HistoricoMensagem.DataEnvio,1,10) BETWEEN '$dia_ini' AND '$dia_fim'";
																break;
															case '4': //Ano
																$filtro_sql  .= " and YEAR(HistoricoMensagem.DataEnvio) = YEAR(CURDATE()) ";
																break;
														}	
													}
													
													if($Limit != ""){
														$Limit	=	"limit 0,$Limit";
													}
													
													$i = 0;
													$sql	=	"select
																	HistoricoMensagem.IdLoja,
																	HistoricoMensagem.IdHistoricoMensagem,
																    HistoricoMensagem.IdTipoMensagem,
																    HistoricoMensagem.Email,
																    HistoricoMensagem.DataEnvio,
																    HistoricoMensagem.IdStatus,
																	HistoricoMensagem.MD5,
																    substr(TipoMensagem.Assunto,16,23) Assunto
																from
																	HistoricoMensagem,
																    TipoMensagem,
																    Pessoa
																where
																    HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and
																    HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem and
																    HistoricoMensagem.IdPessoa = Pessoa.IdPessoa and
																    HistoricoMensagem.IdLoja = $local_IdLoja and
																	HistoricoMensagem.IdPessoa = $local_IdPessoa 
																	$filtro_sql 
																order by
																	HistoricoMensagem.DataEnvio DESC 
																	$Limit";
																
													$res	=	@mysql_query($sql,$con);
													if(@mysql_num_rows($res) >=1){
														while($lin	=	@mysql_fetch_array($res)){
															if(($i%2) != 0){
																$color	=	"background-color:#F2F2F2";
															}else{
																$color	=	"";
															}
															
															$sql2	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 193 and IdParametroSistema = $lin[IdStatus]";
															$res2	=	@mysql_query($sql2,$con);
															$lin2	=	@mysql_fetch_array($res2);
															
															if($lin[HoraEnvio] == '00:00') $lin[HoraEnvio]= "";
															
															$Email			=	explode(';',$lin[Email]);
															$Destinatario	=	"";
															
															$y	=	0;
															while($y < sizeof($Email)){
																if($Destinatario!=""){ $Destinatario .= "<BR>"; }
																$Destinatario	.=	$Email[$y];
																$y++;
															}
															
															//eval("\$lin[Assunto] = \"$lin[Assunto]\";");
															
															echo"
															<tr>
																<TD style='$color'>$lin[IdHistoricoMensagem]</TD>
																<TD style='$color'>$Destinatario</TD>
																<!--TD style='$color'>$lin[Assunto]</TD-->
																<TD style='$color; text-align:center; width:92px'>".dataConv($lin[DataEnvio],'Y-m-d H:i:s','d/m/Y H:i')."</TD>
																<TD style='$color; text-align:center'>$lin2[ValorParametroSistema]</TD>
																<TD style='$color; text-align:center'><a href='../../visualizar_mensagem.php?mensagem=$lin[MD5]' target='_blank' style='color:#C10000'>Visualizar</a></TD>
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
								
