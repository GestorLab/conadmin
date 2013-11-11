								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_Erro					=	$_GET['Erro'];
									
									
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
							    			<form name='formulario'>
												<?
													echo"
														<table>
															<tr>
																<td class='title'><B>Ano de Recebimento</B></td>
															</tr>
															<tr>
																<td>
																	<select class='FormPadrao' name='AnoReferencia' style='width:110px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1'>
																		<option value='' selected></option>";
													
													$sql = "select 
																distinct
																Ano
															from
																(
																	select
																		substring(DataRecebimento, 1, 4) Ano 
																	from
																		ContaReceberRecebimento,
																		ContaReceber
																	where
																		ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja and
																		ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber and
																		ContaReceber.IdPessoa = '".$_SESSION["IdPessoaCDA"]."'
																)AnoReferencia
																 where
																	Ano < YEAR(curdate())
															order by 
																Ano;
													";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[Ano]'>$lin[Ano]</option>";
													}
													
													echo "			</select>
																</td>
															</tr>
														</table>";
												?>
												<br />
												<table>
													<tr>
														<td><p style='text-align:justify;'>LEI Nº 12.007, Art. 3° A declaração de quitação anual deverá ser encaminhada ao consumidor por ocasião do encaminhamento da fatura a vencer no mês de maio do ano seguinte ou no mês subseqüênte à completa quitação dos débitos do ano anterior ou dos anos anteriores, podendo ser emitida em espaço da própria fatura.</p></td>
													</tr>
												</table>
												<table style='float:right'>
													<tr>	
						               					<td><input name="bt_exportar" type="button" onclick="exportar();" class="BotaoPadrao" value="Exportar" tabindex='300'/></td>
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
									enterAsTab(document.forms.formulario);
									function exportar() {
										if(document.formulario.AnoReferencia.value == '') {
											mensagem(54);
											document.formulario.AnoReferencia.focus();
										} else {
											window.location.href = "../administrativo/rotinas/processar_declaracao_pagamento.php?Local=CDA&AnoReferencia="+document.formulario.AnoReferencia.value;
										}
									}
									<?
										if($_GET["EmailFocus"] == 1){
											echo"document.formulario.Email.focus();";
										}
									?>								
								</script>	
