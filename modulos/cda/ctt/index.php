								<?
									ob_start();
									$sql = "SELECT
												ForcarAtualizarDadosCDA Forcar
											FROM
												Pessoa
											WHERE
												(Pessoa.IdLoja IS NULL OR Pessoa.IdLoja = '$local_IdLoja') AND
												Pessoa.IdPessoa = $local_IdPessoa";
									$res = mysql_query($sql,$con);
									$linDados = mysql_fetch_array($res);
									
									if($linDados[Forcar] == 1)
									{
										echo "<script type='text/javascript'>
												window.location = 'menu.php?ctt=cadastro_pessoa.php&IdParametroSistema=7';
											 </script>";
									}
								?>
								<table width="640" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							      		<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
							      		<td id='tit'><h1><img src="img/ico_home.png" /> P&aacute;gina Inicial</h1></td>
							      		<td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
							    	</tr>
							    </table>
							    <table width="640" cellspacing="0" cellpadding="0">
							    	<tr>
							    		<td colspan='3' class='coluna2main'>
											<table>
								    	<?
											$sql_AST = "select 
															ValorCodigoInterno 
														from 
															CodigoInterno 
														where 
															IdGrupoCodigoInterno = '11000' and 
															IdLoja = '$local_IdLoja' and
															IdCodigoInterno = '1';";
											$res_AST = @mysql_query($sql_AST, $con);
											$lin_AST = @mysql_fetch_array($res_AST);
											
											if($lin_AST['ValorCodigoInterno'] == '') {
												$where = " and IdParametroSistema != 11";
											} else {
												$where = "";
											}
											//Menu
											$i = 1;
											$FileLiveZilla = "../../aplicacoes/livezilla/chat.php";
											$sql = "select 
														IdParametroSistema,
														DescricaoParametroSistema,
														ValorParametroSistema 
													from 
														ParametroSistema 
													where 
														IdGrupoParametroSistema = 96 
														$where
													order by 
														IdParametroSistema ASC ";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												if((($lin[IdParametroSistema] == 8 && getParametroSistema(95,2) == 1) || $lin[IdParametroSistema] != 8) && (($lin[IdParametroSistema] == 6 && file_exists($FileLiveZilla)) || $lin[IdParametroSistema] != 6)){
													//Status
													$sql2 = "select 
																ValorParametroSistema 
															from 
																ParametroSistema 
															where 
																IdGrupoParametroSistema = 98 and 
																IdParametroSistema = $lin[IdParametroSistema];";
													$res2 = @mysql_query($sql2,$con);
													$lin2 = @mysql_fetch_array($res2);
													
													$sql3 = "select 
																ValorCodigoInterno 
															from 
																CodigoInterno 
															where 
																IdGrupoCodigoInterno = 10000 and 
																IdCodigoInterno = 1;";
													$res3 = @mysql_query($sql3,$con);
													$lin3 = @mysql_fetch_array($res3);
													
													if($lin2[ValorParametroSistema] == 1 && (($lin[IdParametroSistema] == 13 && $lin3[ValorCodigoInterno] != '') || $lin[IdParametroSistema] != 13)){
														//Link
														$sql4 = "select 
																	ValorParametroSistema 
																from 
																	ParametroSistema 
																where 
																	IdGrupoParametroSistema = 97 and 
																	IdParametroSistema = $lin[IdParametroSistema];";
														$res4 = @mysql_query($sql4,$con);
														$lin4 = @mysql_fetch_array($res4);
														
														if($i%2 != 0){
															echo"<tr>";
															
														} else{
															echo"<td>&nbsp;&nbsp;&nbsp;</td>";
														}
														
#														$invitee = getParametroSistema(95, 21); REMOVER PS
														if($lin[IdParametroSistema] != 6){
															echo"
																<td id='item'>
																	<img src='img/icones/$lin[IdParametroSistema].png' border='0' id='foto' />
																	<a href='?ctt=$lin4[ValorParametroSistema]&IdParametroSistema=$lin[IdParametroSistema]'><h2>$lin[DescricaoParametroSistema] &nbsp; </h2>
																	$lin[ValorParametroSistema]</a>
																</td>
															";
														} else {
															echo"
																<td id='item'>
																	<img src='img/icones/$lin[IdParametroSistema].png' border='0' id='foto' />
																	<a href=\"javascript:void(window.open('".$FileLiveZilla."?code=U0VSVkVSUEFHRQ__','','width=590,height=610,left=0,top=0,resizable=yes,menubar=no,location=yes,status=yes,scrollbars=yes'))\"><h2>$lin[DescricaoParametroSistema] &nbsp; </h2>
																	$lin[ValorParametroSistema]</a>
																</td>
															";
														}
														
														if($i%2 == 0){
															echo"</tr>";
														}
														
														$i++;
													}
												}
											}
											
											if($i%2 != 0){
												echo"</tr>";
											}
											
											echo "
											</table>
										</td>
									</tr>
									<tr>
										<td colspan='3'><img src='img/coluna2_rp.png' width='640' height='15' /></td>
									</tr>
								</table>";
											if(getParametroSistema(206,1) == 1 && getParametroSistema(98,4) == 1) {
												$sql = "select
															OrdemServico.IdTipoOrdemServico,
															OrdemServico.IdOrdemServico,
															substring(OrdemServico.DescricaoOS,1,30) DescricaoOS,
															OrdemServico.IdStatus,
															OrdemServico.DataAgendamentoAtendimento,
															OrdemServico.LoginAtendimento
														from
															OrdemServico
														where
															OrdemServico.IdLoja = '$local_IdLoja' and
															OrdemServico.IdStatus != '0' and
															OrdemServico.NotaAtendimento is NULL and
															(
																(
																	OrdemServico.IdStatus >= 200 and
																	OrdemServico.IdStatus < 300 
																) or
																(
																	OrdemServico.IdStatus >= 500 and
																	OrdemServico.IdStatus < 600 
																)
															) and
															OrdemServico.IdPessoa = '$local_IdPessoa';";
												$res = @mysql_query($sql,$con);
												if(@mysql_num_rows($res) > 0) {
													echo "
								<br />
								<table width='640' border='0' cellspacing='0' cellpadding='0'>
							    	<tr>
							      		<td width='15'><img src='img/hgr1.png' width='15' height='50' /></td>
							      		<td id='tit'><h1><img src='img/icones/4.png' /> Avaliação de Atendimento das Ordens de Serviço</h1></td>
							      		<td width='15'><img src='img/hgr2.png' width='15' height='50' /></td>
							    	</tr>
							    </table>
								<table width='640' cellspacing='0' cellpadding='0'>
									<tr>
										<td colspan='3' class='coluna2main'>
											<table width='100%' id='tableQuadro' style='margin-top:10px;' cellspacing='0' cellpadding='0'>
												<tr>
													<td colspan='6' style='text-align:justify;'>
														<div style='text-indent:30px;'>A seguir, será listado as Ordens de Serviços (atendimentos) realizados e concluídos solicitados pelo Sr. (a). Esta listagem tem como objetivo avaliar o atendimento de nossa equipe perante sua satisfação de cada Atendimento.</div>
														<div style='text-indent:30px;'>Por favor, avalie cada atendimento a seguir para estarmos aprimorando nossa forma de trabalho, disponibilizando um atendimento de qualidade futuramente.</div>
													</td>
												</tr>
												<tr><td>&nbsp;</td></tr>
												<tr>
													<th style='width:45px'>Código</th>
													<th>Descrição</th>
													<th>Tipo</th>
													<th style='width:66px; text-align:center'>Status</th>
													<th style='width:66px;'>&nbsp;</th>
													<th style='width:105px; text-align:center'>Avaliação</th>
												</tr>";
													$i = 0;
													
													while($lin = @mysql_fetch_array($res)) {
														if(($i%2) != 0) {
															$color	=	"background-color:#F2F2F2";
														} else {
															$color	=	"";
														}
														
														$sql_tmp = "select	
																	TipoOrdemServico.DescricaoTipoOrdemServico 
																from 
																	TipoOrdemServico 
																where 
																	TipoOrdemServico.IdLoja = $local_IdLoja and 
																	TipoOrdemServico.IdTipoOrdemServico = $lin[IdTipoOrdemServico];";
														$res_tmp = @mysql_query($sql_tmp,$con);
														$lin_tmp = @mysql_fetch_array($res_tmp);
														$lin[Tipo] = $lin_tmp[DescricaoTipoOrdemServico];
														$sql_tmp = "select 
																		ValorParametroSistema 
																	from 
																		ParametroSistema 
																	where 
																		IdGrupoParametroSistema = 40 and 
																		IdParametroSistema = $lin[IdStatus];";
														$res_tmp = @mysql_query($sql_tmp,$con);
														$lin_tmp = @mysql_fetch_array($res_tmp);
														$lin[Status] = $lin_tmp[ValorParametroSistema];
														echo"
												<tr>
													<TD style='$color'>$lin[IdOrdemServico]</TD>
													<TD style='$color'>$lin[DescricaoOS]</TD>
													<TD style='$color'>$lin[Tipo]</TD>
													<TD style='$color; text-align:center'>$lin[Status]</TD>
													<TD style='$color'><a href='menu.php?ctt=cadastro_ordem_servico.php&IdOrdemServico=$lin[IdOrdemServico]&IdParametroSistema=4' target='_blank' style='color:#C10000;'>Visualizar</a></TD>
													<TD style='$color; text-align:center'>
														<img id='nota_1_$lin[IdOrdemServico]' title='Péssimo' onclick='avalia_atendimento(1,$lin[IdOrdemServico]);' src='../../img/estrutura_sistema/ico_estrela_c.gif' />
														<img id='nota_2_$lin[IdOrdemServico]' title='Ruim' onclick='avalia_atendimento(2,$lin[IdOrdemServico]);' src='../../img/estrutura_sistema/ico_estrela_c.gif' />
														<img id='nota_3_$lin[IdOrdemServico]' title='Satisfatório' onclick='avalia_atendimento(3,$lin[IdOrdemServico]);' src='../../img/estrutura_sistema/ico_estrela_c.gif' />
														<img id='nota_4_$lin[IdOrdemServico]' title='Bom' onclick='avalia_atendimento(4,$lin[IdOrdemServico]);' src='../../img/estrutura_sistema/ico_estrela_c.gif' />
														<img id='nota_5_$lin[IdOrdemServico]' title='Excelente ' onclick='avalia_atendimento(5,$lin[IdOrdemServico]);' src='../../img/estrutura_sistema/ico_estrela_c.gif' />
													</TD>
												</tr>";
														$i++;
													}
												
													echo "
												<tr><th colspan='6' style='text-align:left'>Total: $i</th></tr>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan='3'><img src='img/coluna2_rp.png' width='640' height='15' /></td>
									</tr>
								</table>";
												}
											}
									  	?>