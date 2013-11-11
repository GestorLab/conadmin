								<?
									$local_IdParametroSistema = $_GET['IdParametroSistema'];
									
									$sql = "select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res = @mysql_query($sql,$con);
								    $lin = @mysql_fetch_array($res);
									
								    $local_Descricao = $lin[DescricaoParametroSistema];
								    $code = base64_encode("SERVERPAGE");
									
									
# javascript:void(window.open('./chat.php?code=U0VSVkVSUEFHRQ__','','width=590,height=610,left=0,top=0,resizable=yes,menubar=no,location=yes,status=yes,scrollbars=yes'))									
									
/*								    $invitee = getParametroSistema(95, 21);
								    $suporte = "<P style='text-align:center'>".getParametroSistema(95,23)."</P>";
								    
							    	if($invitee != ''){
										$suporte = "
											<P style='text-align:center'>".getParametroSistema(95,22)."</P>
											<div>
												<iframe src='http://settings.messenger.live.com/Conversation/IMMe.aspx?invitee=$invitee@apps.messenger.live.com&mkt=pt-br&useTheme=true&themeName=blue&foreColor=333333&backColor=E8F1F8&linkColor=333333&borderColor=AFD3EB&buttonForeColor=333333&buttonBackColor=EEF7FE&buttonBorderColor=AFD3EB&buttonDisabledColor=EEF7FE&headerForeColor=CCEBFF&headerBackColor=3C80AC&menuForeColor=333333&menuBackColor=FFFFFF&chatForeColor=333333&chatBackColor=FFFFFF&chatDisabledColor=F6F6F6&chatErrorColor=760502&chatLabelColor=6E6C6C' style='border: solid 1px #AFD3EB; width: 100%; height: 353px;' frameborder='0' scrolling='no'></iframe>
											</div>
							    			<BR>
											<P style='text-align:center'>Horários de atendimento<BR>".getParametroSistema(95,16)."</P>";
									}*/
								?>
								<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							      		<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
									    <td id="tit" width="387"><h1><img src="img/icones/<?=$local_IdParametroSistema?>.png" /> <?=$local_Descricao?></h1></td>
									    <td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="?ctt=index.php">Página Inicial</a></td>
									    <td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
							    	</tr>
							    </table>
								<table width='640' id='floatleft' border='0' cellspacing='0' cellpadding='0'>
							    	<tr>
							    		<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
											<p style='text-align:center'><?php echo getParametroSistema(95,22); ?></p>
											<div style='padding-bottom:8px;'>
												<iframe src="../../aplicacoes/livezilla/chat.php?code=<?php echo $code; ?>" style='border:1px solid #aaa; background-color:#cacaca; width:100%; height:610px;' framebroder='0' scrolling='no'></iframe>
											</div>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src='img/coluna2_rp.png' width='640' height='15' /></td>
							    	</tr>
								</table>