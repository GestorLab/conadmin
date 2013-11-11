								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_Erro					=	$_GET['Erro'];
									
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
									
									if($_POST["DataInicial"] == '' && $_POST["DataFinal"] == '') {
										$_POST["DataInicial"] = date("01/m/Y");
										$_POST["DataFinal"] = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y")) . date("/m/Y");
									}
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
							    			<form name='formulario' method='post' action='./menu.php?ctt=listar_extrato_ligacao.php&IdParametroSistema=<?=$local_IdParametroSistema?>' onSubmit='return validar_extrato_ligacao()'>
												<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
												<input type='hidden' name='IdParametroSistema' value='<?=$local_IdParametroSistema?>'>
												<table>
													<tr>
														<td class='title'><B>Número do Telefone</B></td>
														<td class='sep' />
														<td class='title'><B>Data Inicial</B></td>
														<td class='sep' />
														<td class='sep' />
														<td class='title'><B>Data Final</B></td>
														<td class='sep' />
													</tr>
													<tr>
														<td>
															<select class='FormPadrao' name='IdTerminalVOIP' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:120px'>
																<option value=''>&nbsp;</option>
																<?
																	echo$sql = "SELECT
																				ContratoParametro.Valor Terminal
																			FROM
																				Contrato,
																				ContratoParametro,
																				ServicoParametro
																			WHERE
																				Contrato.IdPessoa = $local_IdPessoa AND
																				Contrato.IdLoja = ContratoParametro.IdLoja AND
																				Contrato.IdLoja = ServicoParametro.IdLoja AND
																				Contrato.IdServico = ServicoParametro.IdServico AND
																				Contrato.IdContrato = ContratoParametro.IdContrato AND
																				ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico AND
																				ServicoParametro.IdTipoTexto = 6
																			ORDER BY
																				ContratoParametro.Valor;";
																	$res = mysql_query($sql,$con);
																	while($lin = mysql_fetch_array($res)){
																		echo "<option value='$lin[Terminal]' " . compara($_POST["IdTerminalVOIP"], $lin[Terminal], "selected='selected'", "") . ">$lin[Terminal]</option>";
																	}
																?>
															</select>
														</td>
														<td class='sep' />
														<td>
															<input class='FormPadrao' type='text' id='cpDataInicial' name='DataInicial' value='<?=$_POST["DataInicial"]?>' style='width:94px' maxlength='10' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" tabindex='1'>
														</td>
														<td class='sep'><img id='icDataInicial' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
														<td class='sep' />
														<td>
															<input class='FormPadrao' type='text' id='cpDataFinal' name='DataFinal' value='<?=$_POST["DataFinal"]?>' style='width:94px' maxlength='10' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" tabindex='2'>
														</td>
														<td class='sep'><img id='icDataFinal' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
													</tr>
												</table>
												<br />
												<table style='float:right'>
													<tr>	
						               					<td><input name="bt_submit" id='bt_submit' type="submit" class="BotaoPadrao" value="Gerar Extrato" tabindex='300'/></td>
						               				</tr>
						             		 	</table>
												<br /><br />
												<?
													if($_POST["IdTerminalVOIP"] != '' && $_POST["DataInicial"] != '' && $_POST["DataFinal"] != '') {
														echo "
												<b>Extrato de Ligações (" . $_POST["DataInicial"] . " á " . $_POST["DataFinal"] . ")</b>
												<table width='100%' id='tableQuadro' style='margin-top:10px;' cellspacing='0' cellpadding='0'>
													<tr>
														<th>Data</th>
														<th>Número</th>
														<th>Tarifa</th>
														<th>Tipo</th>
														<th>Duração (seg.)</th>
														<th style='text-align:right;'>Valor (" . getParametroSistema(5, 1) . ")</th>
													</tr>";
														
														include("../../files/conecta_asterisk.php");
														
														$sql = "SELECT 
																	calldate, 
																	src, 
																	dst, 
																	billsec 
																FROM 
																	cdr 
																WHERE 
																	src = '" . $_POST["IdTerminalVOIP"] . "' AND 
																	disposition = 'ANSWERED' AND 
																	billsec >= 1 AND 
																	calldate >= '".dataConv($_POST["DataInicial"], "d/m/Y", "Y-m-d")." 00:00:00' AND
																	calldate <= '".dataConv($_POST["DataFinal"], "d/m/Y", "Y-m-d")." 23:59:59'
																ORDER BY 
																	calldate;";
														$res = mysql_query($sql, $conAST);
														$total = $billmin = $i = 0;
														
														while($lin = @mysql_fetch_array($res)) {
															if(strlen($lin['dst']) == 8) {
																$ddd = "055";
																$prefix = $lin['dst'];
																$dst = $ddd . $prefix;
															} else {
																$ddd = substr ($lin['dst'], 0, 3);
																$prefix = substr ($lin['dst'], 3, 4);
																$dst = $lin['dst'];
															}
															
															if($ddd == "055" && ($prefix == "3511" || $prefix == "3512" || $prefix == "3513")) {
																$tipo = "Fixo Santa Rosa";
																$custo = 0.16;
															} elseif($ddd == "055" && ($prefix[0] == "2" || $prefix[0] == "3" || $prefix[0] == "4" || $prefix[0] == "5" || $prefix[0] == "6")) {
																$tipo = "Fixo area 55";
																$custo = 0.18;
															} elseif($ddd == "055" && ($prefix[0] == "7" || $prefix[0] == "8" || $prefix[0] == "9")) {
																$tipo = "Movel area 55";
																$custo = 0.63;
															} elseif($prefix[0] == "2" || $prefix[0] == "3" || $prefix[0] == "4" || $prefix[0] == "5" || $prefix[0] == "6") {
																$tipo = "Fixo area " . substr($ddd, 1, 2);
																$custo = 0.18;
															} elseif($prefix[0] == "7" || $prefix[0] == "8" || $prefix[0] == "9" ) {
																$tipo = "Movel area " . substr($ddd, 1, 2);
																$custo = 0.69;
															} else {
																$tipo = "Desconhecido";
																$custo = 0;
															}
															
															$n = $lin['billsec'];
															$c = 1;
															$tar = ($custo / 2);
															
															if($n > 30) {
																$n = $n - 30;
																$base = ($custo / 10);
																
																while($n >= 6 ) {
																	$n = $n - 6;
																	$tar = $tar + $base;
																}
																
																if($n > 0) {
																	$tar = $tar + $base;
																}
															}
															
															$total += $tar;
															$i++;
															
															if(($i%2) == 0) {
																$style = "background-color:#eee;";
															} else {
																$style = "";
															}
															
															if($i > 1){
																$style .= "border-top:1px solid #eee;";
															}
															
															$billmin += ($lin['billsec'] / 60);
															
															echo "
													<tr>
														<td style='" . $style . "'>" . dataConv($lin['calldate'], "Y-m-d H:i:s", "d/m/Y H:i:s") . " </td>
														<td style='" . $style . "'>" . $dst . "</td>
														<td style='" . $style . "'>" . $custo . "</td>
														<td style='" . $style . "'>" . $tipo . "</td>
														<td style='" . $style . "'>" . $lin['billsec'] . "</td>
														<td style='" . $style . " text-align:right;'>" . number_format($tar, 2, ',', '.') . "</td>";
														}
														
														echo "
													<tr>
														<th colspan='4'>Total de ligações: " . $i . "</th>
														<th>" . number_format($billmin, 2, ',', '') . " (min.)</th>
														<th style='text-align:right;'>" . number_format($total, 2, ',', '.') . "</th>
													</tr>
												</table>
												<br />
												<table style='float:right'>
													<tr>	
						               					<td><input name='bt_imprimir' id='bt_imprimir' type='button' class='BotaoPadrao' value='Imprimir Extrato' onclick='window.print();' tabindex='300'/></td>
						               				</tr>
						             		 	</table>";
														
														@mysql_free_result($res);
														@mysql_close($con);
														
														require('../../files/conecta.php');
													}
												?>				    			
					             		 	</form>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
								<script>
									function validar_extrato_ligacao() {
										if(document.formulario.IdTerminalVOIP.value=='') {
											mensagem(50);
											document.formulario.IdTerminalVOIP.focus();
											return false;
										}
										
										if(document.formulario.DataInicial.value=='') {
											mensagem(51);
											document.formulario.DataInicial.focus();
											return false;
										}
										
										if(document.formulario.DataFinal.value=='') {
											mensagem(52);
											document.formulario.DataFinal.focus();
											return false;
										}
										
										return true;
									}
									
									Calendar.setup({
										inputField     : "cpDataInicial",
										ifFormat       : "%d/%m/%Y",
										button         : "icDataInicial"
									});
									Calendar.setup({
										inputField     : "cpDataFinal",
										ifFormat       : "%d/%m/%Y",
										button         : "icDataFinal"
									});
									enterAsTab(document.forms.formulario);
									document.formulario.IdTerminalVOIP.focus();
								</script>
								<style type="text/css">
									@media print {
										#bt_submit, #bt_imprimir { display: none; }
									}
								</style>