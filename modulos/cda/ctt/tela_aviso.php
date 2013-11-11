								<?
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_Erro					=	$_GET['Erro'];
									$anexo						= 	$_GET['Anexo'];
									$local_IdContrato			= 	$_GET['IdContrato'];
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
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
							    			<BR>
											<p style='text-align:center'>
												<?
													if($local_Erro == 80){
														echo formTexto(getParametroSistema(99,$local_Erro)).$anexo."<br/><input name='bt_imprimir_contrato' type='button' class='BotaoPadrao' value='Imprimir Contrato' onClick=\"window.open('../administrativo/imprimir_contrato.php?IdContrato=$local_IdContrato&cda=1')\" tabindex='301'>";
													}else{
														echo formTexto(getParametroSistema(99,$local_Erro)).$anexo;
													}
												?>
											</p>
											<BR>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>