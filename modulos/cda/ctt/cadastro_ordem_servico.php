								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$local_IdOrdemServico		=	$_GET['IdOrdemServico'];
									$local_IdPessoa				=	$_GET['IdPessoa'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_Erro					=	$_GET['Erro'];
									
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
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
							    		<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
							    			<form name='formulario' method='post' action='files/inserir/inserir_pessoa_solicitacao.php' onSubmit='return validar_pessoa()'>
												<input type='hidden' name='Telefone_Obrigatorio' value='<?=getCodigoInternoCDA(11,3)?>'>
												<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
												<input type='hidden' name='IdParametroSistema' value='<?=$local_IdParametroSistema?>'>
												<?
													$sql = "select
																count(*) QtdParcelaQuitada,
																Parcela.QtdParcelaAtiva
															from
																(
																	select
																		LancamentoFinanceiroDados.IdLoja,
																		LancamentoFinanceiroDados.IdOrdemServico,
																		COUNT(LancamentoFinanceiroDados.IdOrdemServico) QtdParcelaAtiva,
																		LancamentoFinanceiroDados.IdStatus
																	from
																		LancamentoFinanceiroDados
																	where
																		LancamentoFinanceiroDados.IdPessoa = '$local_IdPessoa' and
																		(
																			LancamentoFinanceiroDados.IdStatus = '1' or
																			LancamentoFinanceiroDados.IdStatus = '2'
																		) and
																		LancamentoFinanceiroDados.IdOrdemServico = '$local_IdOrdemServico'
																	group by
																		LancamentoFinanceiroDados.IdOrdemServico
																)Parcela,
																LancamentoFinanceiroDados
															where
																LancamentoFinanceiroDados.IdLoja = Parcela.IdLoja and
																LancamentoFinanceiroDados.IdOrdemServico = Parcela.IdOrdemServico and
																LancamentoFinanceiroDados.IdStatus = Parcela.IdStatus and
																LancamentoFinanceiroDados.IdStatusContaReceber = '2'";
													$res = @mysql_query($sql,$con);
													if($lin = @mysql_fetch_array($res)){
														if($lin[QtdParcelaAtiva] > 0){
															if($lin[QtdParcelaQuitada] == $lin[QtdParcelaAtiva]){
																$Parcela = " (Quitado)";
															} else{
																if($lin[QtdParcelaQuitada] == 0){
																	$Parcela = " (Aguard. Pagamento)";
																} else{
																	$Parcela = " (Quitado $lin[QtdParcelaQuitada] - $lin[QtdParcelaAtiva])";
																}
															}
														}
													}
													
													$sql = "select 
																OrdemServico.IdOrdemServico,
																OrdemServico.IdTipoOrdemServico,
																OrdemServico.IdSubTipoOrdemServico,
																OrdemServico.Valor,
																OrdemServico.ValorOutros,
																(OrdemServico.Valor+OrdemServico.ValorOutros) ValorTotal,
																OrdemServico.DescricaoOS,
																OrdemServico.DescricaoCDA,
																OrdemServico.DescricaoOutros,
																OrdemServico.Faturado,
																OrdemServico.LoginAtendimento,
																OrdemServico.IdStatus,
																OrdemServico.DataCriacao,
																OrdemServico.DataConclusao,
																Contrato.IdContrato,
																Contrato.DescricaoPeriodicidade,
																Contrato.DescricaoServicoContrato,
																Servico.DescricaoServico,
																Servico.DetalheServico,
																Servico.IdTipoServico
															from 
																OrdemServico left join (
																	select 
																		Contrato.IdLoja,
																		Contrato.IdContrato,
																		Periodicidade.DescricaoPeriodicidade,
																		Servico.DescricaoServico DescricaoServicoContrato
																	from
																		Contrato,
																		Periodicidade,
																		Servico
																	where
																		Contrato.IdLoja = Servico.IdLoja and
																		Contrato.IdServico = Servico.IdServico and
																		Contrato.IdLoja = Periodicidade.IdLoja and
																		Contrato.IdPeriodicidade = Periodicidade.IdPeriodicidade
																) Contrato on (
																	OrdemServico.IdLoja = Contrato.IdLoja and
																	OrdemServico.IdContrato = Contrato.IdContrato
																),
																Servico
															where
																OrdemServico.IdPessoa = '$local_IdPessoa' and
																OrdemServico.IdOrdemServico= '$local_IdOrdemServico' and
																OrdemServico.IdLoja = Servico.IdLoja and
																OrdemServico.IdServico = Servico.IdServico;";
													$res = @mysql_query($sql,$con);
													$lin = @mysql_fetch_array($res);
													$lin[Status] = getParametroSistema(40, $lin[IdStatus]);
													
													if($lin[Faturado] == 1 && $Parcela != '') {
														$lin[Status] .= "<br><span style='font-size:9px;'>$Parcela</span>";
													}
													
													switch($lin[IdStatus][0]){
														case 1:
															$IdStatusTemp = 1;
															break;
														case 2:
															$IdStatusTemp = 2;
															break;
														case 3:
															$IdStatusTemp = 3;
															break;
														case 4:
															$IdStatusTemp = 4;
															break;
														default:
															$IdStatusTemp = 0;
													}
													
													$lin[CorStatus] = getCodigoInterno(16, $IdStatusTemp);
													
													echo"
													<table>
														<tr>
															<td class='title'>OS</td>
															<td class='sep' />
															<td class='title'>Tipo Ordem de Serviço</td>
															<td class='sep' />
															<td class='title'>SubTipo Ordem de Serviço</td>
															<td class='sep' />
															<td rowspan='2' style='width:220px; font-size:15px; color:$lin[CorStatus]; line-height:14px; text-align:right;'>
																<b>$lin[Status]</b>
															</td>
														</tr>
														<tr>	
															<td>
																<input class='FormPadrao' type='text' name='Nome' value='$lin[IdOrdemServico]' style='width:78px' tabindex='1' readOnly>
															</td>
															<td class='sep' />
															<td>
																<select name='IdTipoServico' style='width:135px' tabindex='2' disabled>";
													
													$sql_tm="select 
																TipoOrdemServico.IdLoja,
																TipoOrdemServico.IdTipoOrdemServico,
																TipoOrdemServico.DescricaoTipoOrdemServico 
															from
																TipoOrdemServico,
																OrdemServico 
															where
																OrdemServico.IdPessoa = '$local_IdPessoa' and
																TipoOrdemServico.IdLoja = OrdemServico.IdLoja and
																TipoOrdemServico.IdTipoOrdemServico = $lin[IdTipoOrdemServico]";
													$res_tm = @mysql_query($sql_tm,$con);
													$lin_tm = @mysql_fetch_array($res_tm);
													echo"<option value='$lin_tm[IdTipoOrdemServico]'>$lin_tm[DescricaoTipoOrdemServico]</option>
																</select>
															</td>
															<td class='sep' />
															<td>
																<select name='IdTipoServico' style='width:135px' tabindex='3' disabled>";
													
													$sql_tm = "	select 
																	SubTipoOrdemServico.IdLoja,
																	SubTipoOrdemServico.IdSubTipoOrdemServico,
																	SubTipoOrdemServico.DescricaoSubTipoOrdemServico 
																from
																	SubTipoOrdemServico,
																	OrdemServico 
																where
																	OrdemServico.IdPessoa = '$local_IdPessoa' and
																	SubTipoOrdemServico.IdLoja = OrdemServico.IdLoja and 
																	SubTipoOrdemServico.IdTipoOrdemServico = $lin[IdTipoOrdemServico] and
																	SubTipoOrdemServico.IdSubTipoOrdemServico = $lin[IdSubTipoOrdemServico]";
													$res_tm = @mysql_query($sql_tm,$con);
													$lin_tm = @mysql_fetch_array($res_tm);
													
													if($lin[DescricaoCDA] != ""){
														$valorDescOS = $lin[DescricaoCDA];
													}else{
														$valorDescOS = $lin[DescricaoOS];
													}
													
													echo"<option value='$lin_tm[IdSubTipoOrdemServico]'>$lin_tm[DescricaoSubTipoOrdemServico]</option>
																</select>
															</td>
														</tr>
													</table>";
													if(getCodigoInternoCDA(11,10) == 1){
														echo "<table>
																<tr>
																	<td class='title'>Descrição Ordem de Serviço</td>
																</tr>
																<tr>
																	<td>
																		<textarea name='Justificativa' style='width:596px; height:52px; resize:none;' tabindex='4' readOnly>$valorDescOS</textarea>
																
																	</td>
																</tr>
															</table>";
													}
													echo "
													<table>
														<tr>
															<td class='title'>Data Abertura</td>
															<td class='sep' />
															<td class='title'>Data Atendimento</td>";
													
													if($lin[LoginAtendimento] != '') {
														echo"
															<td class='sep' />
															<td class='title'>Usuário de Atendimento</td>";
													}
													
													echo"
														</tr>
														<tr>
															<td>
																<input class='FormPadrao' type='text' name='Nome' value='".dataConv($lin[DataCriacao],"Y-m-d","d/m/Y")."' style='width:99px' tabindex='6' readOnly>
															</td>
															<td class='sep' />
															<td>
																<input class='FormPadrao' type='text' name='Nome' value='".dataConv($lin[DataConclusao],"Y-m-d","d/m/Y")."' style='width:99px' tabindex='6' readOnly>
															</td>";
													
													if($lin[LoginAtendimento] != '') {
														$sql_tm = "select 
																		Pessoa.Nome
																	from
																		Usuario,
																		Pessoa
																	where
																		Usuario.Login = '$lin[LoginAtendimento]' and
																		Usuario.IdPessoa = Pessoa.IdPessoa";
														$res_tm = @mysql_query($sql_tm,$con);
														$lin_tm = @mysql_fetch_array($res_tm);
														
														echo"
															<td class='sep' />
															<td>
																<input class='FormPadrao' type='text' name='Nome' value='$lin_tm[Nome]' style='width:380px' tabindex='5' readOnly>
															</td>";
													}
													
													echo"
														</tr>
													</table>
													<br />
													Dados do Contrato:
													<table>
														<tr>
															<td class='title'>Contrato</td>
															<td class='sep' />
															<td class='title'>Nome Serviço</td>
															<td class='sep' />
															<td class='title'>Periodicidade</td>
														</tr>
														<tr>	
															<td>
																<input class='FormPadrao' type='text' name='Nome' value='$lin[IdContrato]' style='width:78px' tabindex='6' readOnly>
															</td>
															<td class='sep' />
															<td>
																<input class='FormPadrao' type='text' name='Nome' value='$lin[DescricaoServicoContrato]' style='width:400px' tabindex='7' readOnly>
															</td>
															<td class='sep' />
															<td>
																<input class='FormPadrao' type='text' name='Nome' value='$lin[DescricaoPeriodicidade]' style='width:100px' tabindex='8' readOnly>
															</td>
														</tr>
													</table>
													<br />
													Dados do Serviço:
													<table>
														<tr>
															<td class='title'>Nome Serviço</td>
															<td class='sep' />
															<td class='title'>Tipo Serviço</td>
														</tr>
														<tr>	
															<td>
																<input class='FormPadrao' type='text' name='Nome' value='$lin[DescricaoServico]' style='width:437px' tabindex='9' readOnly>
															</td>
															<td class='sep' />
															<td>
																<select name='IdTipoServico' style='width:150px' tabindex='10' disabled>
																	<option value='$lin[IdTipoServico]'>".getParametroSistema(71, $lin[IdTipoServico])."</option>
																</select>
															</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'>Descrição Serviço</td>
														</tr>
														<tr>
															<td>
																<textarea name='Justificativa' style='width:596px; height:52px; resize:none;' tabindex='11' readOnly>$lin[DetalheServico]</textarea>
															</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'>Valor (".getParametroSistema(5,1).")</td>
															<td class='sep' />
															<td class='title'>Outros Valores (".getParametroSistema(5,1).")</td>
															<td class='sep' />
															<td class='title'>Valor Total (".getParametroSistema(5,1).")</td>
														</tr>
														<tr>
															<td>
																<input class='FormPadrao' type='text' name='NomeRepresentante' value='".number_format($lin[Valor], 2, ',', '')."' style='width:193px' tabindex='12' readOnly>
															</td>
															<td class='sep' />
															<td>
																<input class='FormPadrao' type='text' name='NomeRepresentante' value='".number_format($lin[ValorOutros], 2, ',', '')."' style='width:193px' tabindex='13' readOnly>
															</td>
															<td class='sep' />
															<td>
																<input class='FormPadrao' type='text' name='NomeRepresentante' value='".number_format($lin[ValorTotal], 2, ',', '')."' style='width:192px' tabindex='14' readOnly>
															</td>
														</tr>
													</table>";
													if(getCodigoInternoCDA(11,9) == 1){
														echo "<table>
																<tr>
																	<td class='title'>Justificativa (Outros Valores)</td>
																</tr>
																<tr>
																	<td>
																		<textarea name='Justificativa' style='width:596px; height:52px; resize:none;' tabindex='15' readOnly>$lin[DescricaoOutros]</textarea>
																	</td>
																</tr>
															</table>";
													}
												?>
												<table id='tableEndereco' style='margin:0; padding:0' cellspacing='0' cellpading='0'>
													<tr>
													</tr>
												</table>
												<table style='float:right'>
													<tr>	
						               					<td><input name='bt_voltar' type='button' class='BotaoPadrao' value='Imprimir' onClick="window.print();" tabindex='300'/></td>
						               				</tr>
						             		 	</table>					    			
					             		 	</form>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
								<script>
									inicia_pessoa();
									
									enterAsTab(document.forms.formulario);
									<?
										if($_GET["EmailFocus"] == 1){
											echo"document.formulario.Email.focus();";
										}
									?>								
								</script>	
