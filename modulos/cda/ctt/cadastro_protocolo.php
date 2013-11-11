								<?
									$local_IdLoja				= $_SESSION['IdLojaCDA'];
									$local_IdParametroSistema	= $_GET['IdParametroSistema'];
									$local_Erro					= $_GET['Erro'];
									$local_IdProtocolo			= $_GET['IdProtocolo'];
									
									if($_POST[IdPessoa] != ''){
										$local_Login				= "cda";
										$local_IdPessoa				= $_POST[IdPessoa];
										$local_IdProtocoloTipo		= $_POST['IdProtocoloTipo'];
										$local_IdLocalAbertura		= $_POST['IdLocalAbertura'];
										$local_Concluir				= $_POST['Concluir'];
										$local_IdPessoaF			= $local_IdPessoa;
										$local_IdTipoPessoa			= $_POST['IdTipoPessoa'];
										$local_Nome					= formatText($_POST['Nome'],NULL);
										$local_NomeF				= $local_Nome;
										$local_CPF					= formatText($_POST['CPF_CNPJ'],NULL);
										$local_CNPJ					= $local_CPF;
										$local_Telefone1			= formatText($_POST['Telefone1'],NULL);
										$local_Telefone2			= formatText($_POST['Telefone2'],NULL);
										$local_Telefone3			= formatText($_POST['Telefone3'],NULL);
										$local_Celular				= formatText($_POST['Celular'],NULL);
										$local_Email				= formatText($_POST['Email'],NULL);
										$local_EmailJuridica		= $local_Email;
										$local_IdStatus				= "101";
										$local_Assunto				= formatText($_POST['Assunto'],NULL);
										$local_Mensagem				= formatText($_POST['Mensagem'],NULL);
										
										include("../administrativo/files/inserir/inserir_protocolo.php");
										
										if(!empty($local_Erro)){
											if((int)$local_Erro == 3){
												$anexo = "<br/><br/><B><font size=4>Protocolo N° $local_IdProtocolo.</font></B>";
												$local_header = "menu.php?ctt=tela_aviso.php&IdParametroSistema=$local_IdParametroSistema&Erro=70&Anexo=$anexo";
											} else{
												$local_header = "menu.php?ctt=tela_aviso.php&IdParametroSistema=$local_IdParametroSistema&Erro=71";
											}
											
											echo "<script type='text/javascript'>window.location.href = '$local_header';</script>";
										}
									}
									
									$sql = "select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema;";
								    $res = @mysql_query($sql,$con);
								    $lin = @mysql_fetch_array($res);
								    $local_Descricao = $lin[DescricaoParametroSistema];
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
							    			<form name='formulario' method='post' action='menu.php?ctt=cadastro_protocolo.php&IdParametroSistema=<?=$local_IdParametroSistema?>' onSubmit='return validar_protocolo();'>
												<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
												<input type='hidden' name='IdLocalAbertura' value='1' />
												<input type='hidden' name='Concluir' value='0' />
												<?
													$sql = "select 
															   IdPessoa,					
															   TipoPessoa,
															   Nome,		
															   RazaoSocial,
															   CPF_CNPJ,
															   Telefone1,
															   Telefone2,
															   Telefone3,					
															   Celular,				
															   Email
															from 
															   Pessoa
															where
															   IdPessoa = $local_IdPessoa";
													$res = @mysql_query($sql,$con);
													$lin = @mysql_fetch_array($res);
													
													$linProtocolo = "";
													if($local_IdProtocolo != ""){
														$sqlProtocolo = "SELECT
																			Protocolo.IdProtocolo,
																			Protocolo.IdProtocoloTipo,
																			Protocolo.Assunto,
																			Protocolo.DataCriacao,
																			Protocolo.IdStatus
																		FROM
																			Protocolo
																		WHERE 
																			Protocolo.IdLoja = $local_IdLoja AND
																			Protocolo.IdProtocolo = $local_IdProtocolo
																			ORDER BY Protocolo.DataCriacao DESC";
														$resProtocolo = mysql_query($sqlProtocolo,$con);
														if(mysql_num_rows($resProtocolo) > 0){
															$linProtocolo = mysql_fetch_array($resProtocolo);
															$readOnly = "readOnly";
															$disabled = "disabled";
															$visibled1 = "display:none";
															$visibled2 = "display:block";
															$color 	   = "#c10000";															
														} else{
															$readOnly = "";
															$disabled = "";
															$visibled1 = "display:block";
															$visibled2 = "display:none";
															$color	   = "#000";
														}
													}
													echo"
													<input type='hidden' name='IdPessoa' value='".$lin[IdPessoa]."' />
													<input type='hidden' name='IdTipoPessoa' value='".$lin[TipoPessoa]."' />
													<input type='hidden' name='Nome' value='".$lin[Nome]."' />
													<input type='hidden' name='Telefone1' value='".$lin[Telefone1]."' />
													<input type='hidden' name='Telefone2' value='".$lin[Telefone2]."' />
													<input type='hidden' name='Telefone3' value='".$lin[Telefone3]."' />
													<input type='hidden' name='Celular' value='".$lin[Celular]."' />
													<input type='hidden' name='Email' value='".$lin[Email]."' />
													<input type='hidden' name='CPF_CNPJ' value='".$lin[CPF_CNPJ]."' />";
														if($local_IdProtocolo != ""){
															echo "
																<table style='width:592px;'>
																	<tr>
																		<td style='text-align: left;font-size: 11pt'><b>Cod: ".$linProtocolo[IdProtocolo]."<b/></td>
																		<td style='text-align: right;color: $color; font-size: 12pt'><b>".getParametroSistema(239,$linProtocolo[IdStatus])."<b/></td>
																	</tr>
																</table>
																<table style='margin-top: 10px'>
																	<tr>
																		<td>
																			<div class='FormPadrao' name='Mensagem' value='' style='width:592px; background-color: #FFF; height:auto; border: none' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='3' $readOnly><p/>";
																				$sql = "SELECT 
																						Mensagem,
																						DATE_FORMAT(DataCriacao,'%d/%m/%Y %h:%i:%s') DataCriacao
																					FROM
																						ProtocoloHistorico
																					WHERE 
																						ProtocoloHistorico.IdLoja = $local_IdLoja and
																						ProtocoloHistorico.IdProtocolo = $local_IdProtocolo";
																				$resMensagem = mysql_query($sql,$con);
																				echo mysql_error();
																				while($linMensagem = mysql_fetch_array($resMensagem)){
																					$Mensagem = explode("<b>Alterado",$linMensagem[Mensagem]);
																					echo $linMensagem[DataCriacao]."<br/><br/>".$Mensagem[0]."<hr/><br/>";
																				}
															echo "			</div>
																		</td>
																	</tr>
																</table>";	
														} else{
															echo "<table>
														<tr>
															<td class='title'><b>Tipo Protocolo</b></td>
														</tr>
														<tr>
															<td>
																<select class='FormPadrao' name='IdProtocoloTipo' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1' style='width:145px' $disabled>
																	<option value='' selected></option>";
																		$sql2 = "SELECT 
																					IdProtocoloTipo,
																					DescricaoProtocoloTipo 
																				FROM 
																					ProtocoloTipo 
																				WHERE 
																					IdLoja = $local_IdLoja AND 
																					AberturaCDA = 1 AND 
																					IdStatus = 1;";
																		$res2 = @mysql_query($sql2,$con);
																		
																		while($lin2 = @mysql_fetch_array($res2)){
																			if($lin2[IdProtocoloTipo] == $linProtocolo[IdProtocoloTipo]){
																				echo"<option value='$lin2[IdProtocoloTipo]' selected='selected'>$lin2[DescricaoProtocoloTipo]</option>";
																			}else{
																				echo"<option value='$lin2[IdProtocoloTipo]'>$lin2[DescricaoProtocoloTipo]</option>";
																			}
																		}
																echo"
																</select>
															</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'><b>Assunto</b></td>
														</tr>
														<tr>	
															<td>
																<input class='FormPadrao' type='text' name='Assunto' value='".$linProtocolo[Assunto]."' style='width:596px' maxlength='200' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2' $readOnly />
															</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'><b>Mensagem</b></td>
														</tr>
																	
																	<tr>
																	<td>
																		<textarea class='FormPadrao' name='Mensagem' value='' style='width:596px; height:120px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='3' $readOnly></textarea>
																	</td>
																</tr>
															</table>";
														}
												?>
												<table id='tableEndereco' style='margin:0; padding:0' cellspacing='0' cellpading='0'><tr><td></td></tr></table>
												<table style='float:right'>
													<tr>	
						               					<td>
															<input name="bt_submit" type="submit" class="BotaoPadrao" value="Cadastrar" tabindex='300' style='<?=$visibled1?>'>
														</td>
														<td>
															<input name="bt_voltar" type="button" class="BotaoPadrao" value="Voltar" tabindex='300' style='<?=$visibled2?>' onclick="window.location='menu.php?ctt=listar_protocolo.php&IdParametroSistema=14';">
														</td>
						               				</tr>
						             		 	</table>					    			
					             		 	</form>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
								<script type='text/javascript'>
									document.formulario.IdProtocoloTipo.focus();
									enterAsTab(document.forms.formulario);
								</script>	