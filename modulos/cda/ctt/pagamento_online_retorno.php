								<?
									$local_IdParametroSistema		=	$_GET['IdParametroSistema'];
									$local_ContaReceberRecebimento	=	substr($_GET['ContaReceberRecebimento'],1,32);
									$local_Erro						=	substr($_GET['ContaReceberRecebimento'],0,1);
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
								?>
								<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							      		<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
									    <td id="tit" width="387"><h1><img src="img/icones/<?=$local_IdParametroSistema?>.png" /> <?=$local_Descricao?> - Detalhes</h1></td>
									    <td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="?ctt=index.php">Página Inicial</a></td>
									    <td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
							    	</tr>
							    </table>
							    <table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							    		<td class='coluna2main'>
											<p style='text-align:center; height:150px; padding-top:115px'>
										<?
											switch($local_Erro){
												case '1':
													echo "<B>Pagamento realizado com sucesso!</B>";
													break;

												case '2':
													echo "<B>Pagamento não autorizado!</B>";
													break;

												case '3':
													$sql = "select
																LocalCobranca.DiasCompensacao
															from
																ContaReceberRecebimento,
																LocalCobranca
															where
																ContaReceberRecebimento.MD5 = '$local_ContaReceberRecebimento' and
																ContaReceberRecebimento.IdLojaRecebimento = LocalCobranca.IdLoja and
																ContaReceberRecebimento.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
													$res = mysql_query($sql,$con);
													$lin = mysql_fetch_array($res);

													if($lin[DiasCompensacao] > 0){
														$DiasCompensacao = " (até $lin[DiasCompensacao] dia(s) úteis)";
													}

													echo "<B>Aguardando retorno sobre o pagamento$DiasCompensacao!</B>";
													break;

												case '4':
													echo "<B style='color: red;'>Erro ao processar recebimento!</B><BR>Por favor entre em contato com o suporte.";
													break;
												}
										?>
											<br><a href='menu.php?ctt=listar_conta_receber.php&IdParametroSistema=1'>Clique aqui para acessar seu histórico financeiro.</a>
											</p>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>