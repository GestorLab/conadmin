								<?
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_Erro					=	$_GET['Erro'];
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
								    
								    $local_IdPessoa = $_SESSION["IdPessoaCDA"];
								    
								    $sql	=	"select Email from Pessoa where IdPessoa = $local_IdPessoa";
								    $res2	=	@mysql_query($sql,$con);
								    $lin2	=	@mysql_fetch_array($res2);
									$tamanhomaximo 					= getParametroSistema(95,32);

									if($tamanhomaximo > 255 || $tamanhomaximo == "" || $tamanhomaximo == 0){
										$tamanhomaximo = 255;
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
							    			<form name='formulario' method='post' action='files/alterar_senha.php' onSubmit='return validar_alterar_senha()'>
												<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
												<input type='hidden' name='IdParametroSistema' value='<?=$local_IdParametroSistema?>'>
												<table>
													<tr>
														<td class='title'><B>Senha Atual</B></td>
														<td class='sep' />
														<td class='title'><B>Nova Senha</B></td>
														<td class='sep' />
														<td class='title'><B>Confirme Sua Senha</B></td>
													</tr>
													<tr>
														<td>
															<input class='FormPadrao' type='password' name='SenhaAtual' value='' style='width:120px' maxlength='<?=$tamanhomaximo?>' autocomplete='off' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='1'>
														</td>
														<td class='sep' />
														<td>
															<input class='FormPadrao' type='password' name='NovaSenha' value='' style='width:120px' maxlength='<?=$tamanhomaximo?>' autocomplete='off' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='2'>
														</td>
														<td class='sep' />
														<td>
															<input class='FormPadrao' type='password' name='Confirmacao' value='' style='width:120px' maxlength='<?=$tamanhomaximo?>' autocomplete='off' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='3'>
														</td>
													</tr>
													
												</table>
												<?
													if($lin2[Email] == ''){
														echo"
												<p style='margin-top:10px'><a href='menu.php?ctt=cadastro_pessoa.php&IdParametroSistema=7&EmailFocus=1'>Seu cadastro esta incompleto, para alterar sua senha é necessário cadastrar seu e-mail. Clique aqui para cadastrar.</a></p>";												
													}
												?>	
												<table style='float:right'>
												<?
													if($lin2[Email] != ''){
														echo"
													<tr>	
						               					<td><input name='bt_submit' type='submit' class='BotaoPadrao' value='Alterar' tabindex='50'/></td>
						               				</tr>";						             		 	
						             		 		}
						             		 	?>
						             		 	</table>
					             		 	</form>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
								<script>
									inicia_alterar_senha();
									enterAsTab(document.forms.formulario);
								</script>
								
