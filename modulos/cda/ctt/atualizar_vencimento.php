								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
								 	$local_ContaReceber			=	$_GET['ContaReceber'];
									
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
							    			<form name='formulario' method='post' action='files/inserir/inserir_conta_receber_vencimento.php' onSubmit='return validar_atualizar_vencimento()'>
												<input type='hidden' name='ContaReceber' value='<?=$local_ContaReceber?>'>
												<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
												<input type='hidden' name='IdParametroSistema' value='<?=$local_IdParametroSistema?>'>
												<?		
													$sql	=	"select
																	ContaReceberDados.IdLoja,
																	ContaReceberDados.IdContaReceber,
																	ContaReceberDados.IdLocalCobranca,
																	ContaReceberDados.NumeroDocumento,
																	ContaReceberDados.DataLancamento,
																	ContaReceberDados.DataVencimento,
																	ContaReceberDados.ValorLancamento,
																	ContaReceberDados.ValorDespesas,
																	ContaReceberDados.ValorDesconto,
																	ContaReceberDados.ValorJuros,
																	ContaReceberDados.ValorMulta,
																	ContaReceberDados.ValorFinal,
																	ContaReceberDados.DataVencimento,
																	ContaReceberDados.ValorContaReceber,
																	ContaReceberDados.ValorTaxaReImpressaoBoleto,
																	LocalCobranca.PercentualJurosDiarios,
																	LocalCobranca.PercentualMulta,
																	LocalCobranca.CobrarMultaJurosProximaFatura,
																	LocalCobranca.ValorTaxaReImpressaoBoleto
																from
																	ContaReceberDados,
																	LocalCobranca 
																where
																	ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
																	ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
																	ContaReceberDados.MD5 = '$local_ContaReceber'";
													$res	=	mysql_query($sql,$con);
													$lin	=	mysql_fetch_array($res);											
													
													if(mysql_num_rows($res) >= 1){
														$Moeda		=	getParametroSistema(5,1);
														if($lin[CobrarMultaJurosProximaFatura] == 1){
															$PercMulta	=	0.00;
															$PercJuros	=	0.00;
															$lin[PercentualJurosDiarios] = 0.00;
															$lin[PercentualMulta] = 0.00;
														} else{
															$PercMulta	=	formata_double($lin[PercentualMulta],2);
															$PercJuros	=	formata_double($lin[PercentualJurosDiarios],2);
														} 
														
														$PercMulta	=	str_replace('.',',',$PercMulta);
														$PercJuros	=	str_replace('.',',',$PercJuros);
														//Cobrar Taxa Reimpressao boleto)												
														if(getCodigoInternoCDA(3,80) == 1){
															$ValorTaxa	=	formata_double(($lin[ValorTaxaReImpressaoBoleto]),2);
															$ValorTaxa	=	str_replace('.',',',$ValorTaxa);
															$lin[ValorFinal] += $ValorTaxa;
														}else{
															$lin[ValorTaxaReImpressaoBoleto]	=	0;
															$ValorTaxa	=	'0,00';
														}
														
														$lin[ValorFinal] = formata_double(($lin[ValorFinal]),2);
														
														$sql	="select
																	ValorContaReceber,
																	min(DataVencimento) PrimeiroVencimento
																 from
																	ContaReceberVencimento
																 where
																	IdLoja = $lin[IdLoja] and
																	IdContaReceber = $lin[IdContaReceber]"; 
														$res3	=	mysql_query($sql,$con);
														$lin3	=	mysql_fetch_array($res3);

														echo"
															<input type='hidden' name='PercentualJurosDiarios' value='$lin[PercentualJurosDiarios]'>
															<input type='hidden' name='PercentualMulta' value='$lin[PercentualMulta]'>
															<input type='hidden' name='ValorMultaTemp' value='$lin[ValorMulta]'>
															<input type='hidden' name='ValorJurosTemp' value='$lin[ValorJuros]'>
															<input type='hidden' name='ValorFinalTemp' value='$lin[ValorFinal]'>													
															<input type='hidden' name='DataVencimento' value='$lin[DataVencimento]'>
															<table>
																<tr>
																	<td class='title'>Conta Receber</td>
																	<td class='sep' />
																	<td class='title'>Local de Cobrança</td>
																	<td class='sep' />
																	<td class='title'>Nº Documento</td>
																	<td class='sep' />
																	<td class='title'>Data Lançamento</td>
																	<td class='sep' />
																	<td class='title'>Vencimento Original</td>
																</tr>
																<tr>	
																	<td>
																		<input class='FormPadrao' type='text' name='IdContaReceber' value='$lin[IdContaReceber]' style='width:75px' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<select name='IdLocalCobranca' style='width:200px'  disabled>
																			<option value='' selected></option>";
																				$sql2 = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobranca where IdLoja=$local_IdLoja order by DescricaoLocalCobranca";
																				$res2 = @mysql_query($sql2,$con);
																				while($lin2 = @mysql_fetch_array($res2)){
																					echo"<option value='$lin2[IdLocalCobranca]' ".compara($lin[IdLocalCobranca],$lin2[IdLocalCobranca],"selected='selected'","").">$lin2[DescricaoLocalCobranca]</option>";
																				}
																		echo"	
																		</select>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' name='NumeroDocumento' value='$lin[NumeroDocumento]' style='width:80px' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' name='DataLancamento' value='".dataConv($lin[DataLancamento],'Y-m-d','d/m/Y')."' style='width:100px' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' name='PrimeiroVencimento' value='".dataConv($lin3[PrimeiroVencimento],'Y-m-d','d/m/Y')."' style='width:105px' readOnly>
																	</td>
																</tr>
															</table>
															<BR>
															<P style='width:100%; background-color:#F2F2F2; padding:2px 0 2px 2px'><B>Opções de Data de Vencimento</B></P>
															<table>
																<tr>
																	<td class='title'><B>Novo Vencimento</B></td>
																</tr>
																<tr>
																	<td>
																		<select name='Vencimento' style='width:290px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange='calculaValor()' tabindex='1'>
																			<option value='0' selected></option>";
																				$qtdDiasVencimento	=	getCodigoInternoCDA(3,89);
																				$data				=	date('Y-m-d');	
																				$cont				=	1;
																				while($cont <= $qtdDiasVencimento){
																					$dia		=	(int)substr($data,8,2);
																					$data		=	dia_util($data);
																					$dataTemp	=	dataConv($data,'Y-m-d','d/m/Y');
																					$diaSemana	=	diaSemanaExtenso($data);
																					
																					echo"<option value='$dataTemp'>$dataTemp ($diaSemana)</option>";
																					
																					$cont++;
																					$data	=	incrementaData($data,1);
																				}
																		echo"	
																		</select>
																	</td>
																</tr>
															</table>
															<table>
																<tr>
																	<td class='title'>(=) Valor ($Moeda)</td>
																	<td class='sep' />
																	<td class='title'>(+) Multa ($Moeda)*</td>
																	<td class='sep' />
																	<td class='title'>(+) Juros ($Moeda)**</td>
																	<td class='sep' />
																	<td class='title'>(+) Taxa Reimp.($Moeda)***</td>
																	<td class='sep' />
																	<td class='title'>(=) Valor Final ($Moeda)</td>
																</tr>";
															if(getCodigoInterno(3,206) == 1){
																echo "<tr>
																		<td>
																			<input class='FormPadrao' type='text' name='ValorContaReceber' value='".str_replace('.',',',$lin[ValorFinal])."' style='width:110px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='ValorMulta' value='0,00' style='width:105px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='ValorJuros' value='0,00' style='width:105px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='ValorTaxaReImpressaoBoleto' value='$ValorTaxa' style='width:130px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='ValorFinal' value='".str_replace('.',',',$lin[ValorFinal])."' style='width:110px' readOnly>
																		</td>
																	</tr>
																</table>";
															}else{
																echo "<tr>
																		<td>
																			<input class='FormPadrao' type='text' name='ValorContaReceber' value='".str_replace('.',',',$lin3[ValorContaReceber])."' style='width:110px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='ValorMulta' value='0,00' style='width:105px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='ValorJuros' value='0,00' style='width:105px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='ValorTaxaReImpressaoBoleto' value='$ValorTaxa' style='width:130px' readOnly>
																		</td>
																		<td class='sep' />
																		<td>
																			<input class='FormPadrao' type='text' name='ValorFinal' value='".str_replace('.',',',$lin[ValorFinal])."' style='width:110px' readOnly>
																		</td>
																	</tr>
																</table>";
															}
													}
												?>
												<table style='float:right'>
													<tr>	
						               					<td><input name="bt_submit" type="submit" class="BotaoPadrao" value="Confirmar" tabindex='2'/></td>
						               				</tr>
						             		 	</table>
						             		 	<BR>
						             		 	<P style='margin:0;' class='comentario'>* Multa: <?=$PercMulta?>%</P>
												<P style='margin:0' class='comentario'>** Juros diários: <?=$PercJuros?>%</P>
												<P style='margin:0' class='comentario'>** Taxa reimpressão boleto: <?=getParametroSistema(5,1)?> <?=$ValorTaxa?></P>
						             		</form>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
								<script>
									inicia_atualizar_vencimento();
									enterAsTab(document.forms.formulario);
								</script>
