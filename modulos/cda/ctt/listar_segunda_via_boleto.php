								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$IdLoja						= 	$_SESSION["IdLojaCDA"];
									$local_Login				=	$_SESSION['LoginCDA'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_AtualizarVencimento		=	getParametroSistema(95,18);
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
								    $local_IdStatus		=	$_POST['filtro_status'];

									if($local_IdStatus == ''){
									    $local_IdStatus		=	1;
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
							    		<td colspan='4' class='coluna2main'>
							    			<div id='filtroBuscar' align="right">
												<form name='filtro' method='post' action='?ctt=listar_segunda_via_boleto.php&IdParametroSistema=<?=$local_IdParametroSistema?>'>
													<table>
														<tr>
															<td class='title'>Status</td>
														</tr>
														<tr>
															<td>
																<select class='FormPadrao' name='filtro_status' style='width:180px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="document.filtro.submit()">
																	<option value=''>Todos</option>
																	<?
																		$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 and IdParametroSistema != 0 and IdParametroSistema != 7 order by ValorParametroSistema";
																		$res = @mysql_query($sql,$con);
																		while($lin = @mysql_fetch_array($res)){
																			echo "<option value='$lin[IdParametroSistema]' ".compara($local_IdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
																		}
																	?>
																</select>
															</td>
														</tr>
													</table>
												</form>
											</div>
											<table width="100%" id='tableQuadro' style='margin-top:10px;' border="0" cellspacing="0" cellpadding="0">
												<tr>
													<th>Nº Doc.</th>
													<th style='text-align:center'>Data Lanç.</th>
													<th style='text-align:right'>Valor (<?=getParametroSistema(5,1)?>)</th>
													<th style='text-align:center'>Data Venc.</th>
													<th style='text-align:right'>Valor Pago (<?=getParametroSistema(5,1)?>)</th>
													<th style='text-align:center'>Data Pag.</th>
													<th style='text-align:center'>Fatura</th>
													<th style='text-align:center'>Nota Fiscal</th>
												</tr>
												<?
													$filtro_sql	=	"";
													
													if($local_IdStatus!=""){
														switch($local_IdStatus){
															case 1:
																$filtro_sql  .= " and (ContaReceberDados.IdStatus = 1 or ContaReceberDados.IdStatus = 3)";
																break;
															default:
																$filtro_sql  .= " and ContaReceberDados.IdStatus = ".$local_IdStatus;
																break;
														}
													}
														
													$QtdDiasProrrogacoes	=	getCodigoInternoCDA(3,89);
													
													$i = 0;
													$Tvalor		=	0;
													$Trebedido	=	0;
													$sql	=	"select distinct
																	ContaReceberDados.IdLoja,
																	ContaReceberDados.IdContaReceber,
																	ContaReceberDados.NumeroDocumento,
																	ContaReceberDados.NumeroNF,
																	ContaReceberDados.DataLancamento,
																	(ContaReceberDados.ValorFinal) Valor,
																	ContaReceberDados.ValorDesconto,
																	ContaReceberDados.DataVencimento,
																	ContaReceberDados.IdLocalCobranca,
																	ContaReceberDados.MD5,
																	LocalCobranca.AbreviacaoNomeLocalCobranca,
																	ContaReceberRecebimento.DataRecebimento,
																	ContaReceberRecebimento.IdRecibo,
																	ContaReceberRecebimento.ValorRecebido,
																	ContaReceberDados.IdStatus,
																	LocalCobranca.IdLocalCobrancaLayout,
																	LocalCobranca.IdTipoLocalCobranca,
																	LocalCobranca.AtualizarVencimentoViaCDA
																from
																	ContaReceberDados left join ContaReceberRecebimento on (
																		ContaReceberRecebimento.IdStatus <> 0 and
																		ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and
																		ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber
																	),
																	Pessoa,
																	LocalCobranca
																where
																	ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
																	ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
																	ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
																	ContaReceberDados.IdPessoa = $local_IdPessoa and
																	ContaReceberDados.IdStatus != 0 and
																	ContaReceberDados.IdStatus != 7
																	$filtro_sql
																group by
																	ContaReceberDados.IdContaReceber
																order by
																	ContaReceberDados.DataVencimento";
													$res	=	@mysql_query($sql,$con);
													if(@mysql_num_rows($res) >=1){
														while($lin	=	@mysql_fetch_array($res)){													

															if(($i%2) != 0){
																$color	=	"background-color:#F2F2F2";
															}else{
																$color	=	"";
															}
															if($lin[Valor] == "")			$lin[Valor] 		= 0;
															if($lin[ValorRecebido] == "")	$lin[ValorRecebido] = 0;
															
															$Tvalor		+=	$lin[Valor];
															$Trebedido	+=	$lin[ValorRecebido];
															
															if($lin[IdStatus] == 1 || $lin[IdStatus] == 3){
																//Boleto Vencido
																if($lin[AtualizarVencimentoViaCDA] == 1){
																	$linkAtualizar	= "<a href='?ctt=atualizar_vencimento.php&ContaReceber=$lin[MD5]&IdParametroSistema=$local_IdParametroSistema'>Atualizar Venc.</a>";
																}else{
																	$linkAtualizar	= "Atualização Indisponível";
																}
																$linkBoleto	= "<a href='../administrativo/boleto.php?Tipo=html&ContaReceber=$lin[MD5]'	target='_blank' style='color:#C10000'>Imprimir</a>";

																if($lin[DataVencimento] < date('Y-m-d') && $local_AtualizarVencimento == 1){
																	$link = $linkBoleto." | ".$linkAtualizar;
																}else{
																	$link = $linkBoleto;
																}
															}else{
																$link = "";
															}
															
															if($lin[Valor]!=0){
																$Valor	=	formatNumber(formata_double(($lin[Valor])));
															}else{
																$Valor	=	"&nbsp;";
															}
															
															if($lin[ValorRecebido]!=0){
																$ValorRecebido	=	formatNumber(formata_double(($lin[ValorRecebido])));
															}else{
																$ValorRecebido	=	"&nbsp;";
															}
	
															$nfLink = '&nbsp;';

															$sqlNF = "select
																		IdNotaFiscalLayout,
																		MD5
																	from
																		NotaFiscal
																	where
																		IdLoja = $local_IdLoja and
																		IdContaReceber = $lin[IdContaReceber]";
															$resNF = @mysql_query($sqlNF, $con);
															if($linNF = @mysql_fetch_array($resNF)){
																$nfLink	= "<a href='../administrativo/nota_fiscal.php?NotaFiscal=$linNF[MD5]' target='_blank' style='color:#C10000'>Nota Fiscal</a>";
															}
															
															echo"
															<tr>
																<TD style='$color'>$lin[NumeroDocumento]</TD>
																<TD style='$color; text-align:center'>".dataConv($lin[DataLancamento],'Y-m-d','d/m/Y')."</TD>
																<TD style='$color; text-align:right'>$Valor</TD>
																<TD style='$color; text-align:center'>".dataConv($lin[DataVencimento],'Y-m-d','d/m/Y')."</TD>
																<TD style='$color; text-align:right'>$ValorRecebido</TD>
																<TD style='$color; text-align:center'>".dataConv($lin[DataRecebimento],'Y-m-d','d/m/Y')."&nbsp;</TD>
																<TD style='$color; text-align:center;'>$link</TD>
																<TD style='$color; text-align:center;'>$nfLink</TD>
															</tr>
															";
															
															$i++;
														}
													}else{
														echo"<tr>
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
																<TD>&nbsp;</TD>
														</tr>";
													}
													echo"
														<tr>
															<th colspan='2' style='text-align:left'>Total: $i</th>
															<TH style='text-align:right'>".formatNumber(formata_double($Tvalor))."</TH>
															<TH>&nbsp;</TH>
															<TH style='text-align:right'>".formatNumber(formata_double($Trebedido))."</TH>
															<TH colspan='3'>&nbsp;</TH>
														</tr>
													";
													
												?>
											</table>
											<br />
											<div style='float:right;'><input type='button' class='BotaoPadrao' onclick='window.print();' value='Imprimir' /></div>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>