								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$local_Login				=	$_SESSION['LoginCDA'];
									$local_IdContrato			=	$_GET['IdContrato'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_MesReferencia		=	$_POST['filtro_mes_referencia'];
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	= $lin[DescricaoParametroSistema];
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
											<?
												$sql = "select 
															Pessoa.Nome,
															Pessoa.RazaoSocial,
															Pessoa.TipoPessoa,
															Contrato.MesFechado,
															Contrato.DiaCobranca,
															Contrato.IdStatus,
															Servico.DescricaoServico,
															ContratoParametro.Valor 
														from 
															Contrato,
															ContratoParametro,
															ServicoParametro,
															Servico,
															Pessoa
														where
															Contrato.IdContrato = '$local_IdContrato' and 
															Contrato.IdLoja = ContratoParametro.IdLoja and 
															Contrato.IdLoja = Servico.IdLoja and 
															Contrato.IdPessoa = Pessoa.IdPessoa and 
															Contrato.IdServico = Servico.IdServico and 
															Contrato.IdContrato = ContratoParametro.IdContrato and 
															ContratoParametro.IdLoja = ServicoParametro.IdLoja and
															ContratoParametro.IdServico = ServicoParametro.IdServico and
															ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and
															ServicoParametro.IdTipoTexto = 1 and
															ServicoParametro.IdMascaraCampo = 4";
												$res = mysql_query($sql,$con);
												$lin = mysql_fetch_array($res);

												if($lin[TipoPessoa] == 1){
													$DescCampo = "Razão Social";
													$Nome = $lin[RazaoSocial];
												}else{
													$DescCampo = "Nome";
													$Nome = $lin[Nome];
												}
												
												if($lin[MesFechado] == "1") {
													$lin[DiaCobranca] = "01";
												}

												$DescricaoServico	= substr($lin[DescricaoServico],0,40);
												$StatusContrato		= getParametroSistema(69,$lin[IdStatus]);
												
												$local_DiaCobranca = str_pad($lin[DiaCobranca], 2, "0", STR_PAD_LEFT);
												$local_Usuario = trim($lin[Valor]);
												$i = 0;
												$Duracao = 0;
												$Download = 0;
												$Upload	= 0;
												$RowsRelatorio = '';
												$Increment = 0;
												
												if((int) $local_DiaCobranca > (int) date("d")){
													$Increment--;
												}
												
												$sql = "select 
															ValorCodigoInterno 
														from 
															CodigoInterno 
														where 
															IdLoja = '$local_IdLoja' and 
															IdGrupoCodigoInterno = 10000 and 
															IdCodigoInterno = 1;";
												$res = mysql_query($sql,$con);
												if($lin = @mysql_fetch_array($res)){
													list($bd[server],$bd[login],$bd[senha],$bd[banco]) = explode("\n",$lin[ValorCodigoInterno]);
													$conRadius = mysql_connect(trim($bd[server]),trim($bd[login]),trim($bd[senha]));
													@mysql_select_db(trim($bd[banco]),$conRadius);
													
													echo "
														<div id='filtroBuscar' align='right'>
															<form name='filtro' method='post' action='?ctt=listar_relatorio_utilizacao_radius.php&IdContrato=$local_IdContrato&IdParametroSistema=$local_IdParametroSistema'>
																<table width= 100%>																	
																	<tr>												
																		<td class='title'>$DescCampo</td>																		
																		<td class='title' width='268px'>Referência</td>																		
																	</tr>
																	<tr>		
																		<td>$Nome</td>
																		<td>
																			<select class='FormPadrao' name='filtro_mes_referencia' style='width:264px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" onChange='document.filtro.submit()'>";
													$cont = 0;
													$sqlRadius = "SELECT 
																		SUBSTRING(Temp.DataReferenciaInicio, 1, 7) MesReferencia,
																		Temp.DataReferenciaInicio,
																		Temp.DataReferenciaFim
																	FROM
																		(
																			(
																				SELECT 
																					ADDDATE(CONCAT(SUBSTRING(acctstarttime, 1, 7), '-$local_DiaCobranca'),INTERVAL $Increment MONTH) DataReferenciaInicio, 
																					ADDDATE(ADDDATE(ADDDATE(CONCAT(SUBSTRING(acctstarttime, 1, 7), '-$local_DiaCobranca'),INTERVAL 1 MONTH),INTERVAL -1 DAY),INTERVAL $Increment MONTH) DataReferenciaFim
																				FROM
																					radacctJornal 
																				WHERE 
																					username = '$local_Usuario'
																			) UNION (
																				SELECT 
																					ADDDATE(CONCAT(SUBSTRING(acctstarttime, 1, 7), '-$local_DiaCobranca'),INTERVAL $Increment MONTH) DataReferenciaInicio, 
																					ADDDATE(ADDDATE(ADDDATE(CONCAT(SUBSTRING(acctstarttime, 1, 7), '-$local_DiaCobranca'),INTERVAL 1 MONTH),INTERVAL -1 DAY),INTERVAL $Increment MONTH) DataReferenciaFim
																				FROM
																					radacct 
																				WHERE 
																					username = '$local_Usuario'
																			) 
																			ORDER BY 
																				DataReferenciaInicio desc
																		) Temp 
																	WHERE 
																		Temp.DataReferenciaInicio < '".date("Y-m-d")."'";
													$resRadius = mysql_query($sqlRadius,$conRadius);
													$numRadius = @mysql_num_rows($resRadius);
													
													while($linRadius = @mysql_fetch_array($resRadius)){
														$sql_tmp = "(
																		SELECT 
																			acctstarttime
																		FROM
																			radacctJornal 
																		WHERE 
																			username = '$local_Usuario' AND
																			SUBSTRING(acctstarttime, 1, 10) >= '".$linRadius["DataReferenciaInicio"]."' AND 
																			SUBSTRING(acctstarttime, 1, 10) <= '".$linRadius["DataReferenciaFim"]."' 
																		LIMIT 1
																	) UNION (
																		SELECT 
																			acctstarttime
																		FROM
																			radacct 
																		WHERE 
																			username = '$local_Usuario' AND
																			SUBSTRING(acctstarttime, 1, 10) >= '".$linRadius["DataReferenciaInicio"]."' AND 
																			SUBSTRING(acctstarttime, 1, 10) <= '".$linRadius["DataReferenciaFim"]."' 
																		LIMIT 1
																	)";
														$res_tmp = mysql_query($sql_tmp, $conRadius);
														
														if(mysql_num_rows($res_tmp) > 0){
															$linRadius[MesReferenciaTemp] = dataConv($linRadius[MesReferencia],'Y-m-d','m/Y');
															$linRadius["DataReferenciaFimTemp"] = dataConv($linRadius["DataReferenciaFim"],'Y-m-d','d/m/Y');
															$linRadius["DataReferenciaInicioTemp"] = dataConv($linRadius["DataReferenciaInicio"],'Y-m-d','d/m/Y');
															$cont++;
															
															if($local_MesReferencia == ''){
																$local_MesReferencia = $linRadius[MesReferencia];
																if($numRadius == $cont) {
																	$selected = "selected='selected'";
																}
															} else {
																$selected = compara($local_MesReferencia,$linRadius[MesReferencia],"selected='selected'","");
															}
															
															echo "<option value='$linRadius[MesReferencia]' $selected>$linRadius[MesReferenciaTemp] (".$linRadius["DataReferenciaInicioTemp"]." a ".$linRadius["DataReferenciaFimTemp"].")</option>";
														}
													}
													echo "					</select>
																		</td>																	
																	</tr>
																</table>
															</form>
															<table width= 100% style='border-top:1px #000 solid'>																	
																<tr>
																	<td class='title' width='40px'>CO</td>
																	<td class='title' width='180px'>Serviço</td>
																	<td class='title'>Login de Acesso</td>
																	<td class='title' width='170px'>Status</td>
																</tr>
															</table>
															<table width= 100% style='border=0; border-top:1px #000 solid;'>																	
																<tr>
																	<td width='40px'>$local_IdContrato</td>
																	<td width='180px'>$DescricaoServico</td>
																	<td>$local_Usuario</td>
																	<td width='170px'>$StatusContrato</td>
																</tr>
															</table>
														</div>";
													
													$local_MesReferenciaInicio = $local_MesReferencia."-".$local_DiaCobranca;
													
													list($Ano, $Mes) = explode("-", $local_MesReferencia);
													
													$local_MesReferenciaFim = dataConv(incrementaMesReferencia($Mes."-".$Ano, 1), "m/Y", "Y-m");
													$local_MesReferenciaFim .= "-$local_DiaCobranca";
													
													$sqlRadius = "(
																		select
																			AcctStartTime DataInicio,
																			AcctStopTime DataFim,
																			AcctInputOctets Upload,
																			AcctOutputOctets Download
																		from
																			radacctJornal
																		where
																			UserName = '$local_Usuario' and
																			(
																				(
																					substring(AcctStartTime,1,10) >= '$local_MesReferenciaInicio' and 
																					substring(AcctStartTime,1,10) < '$local_MesReferenciaFim' 
																				) or ( 
																					substring(AcctStopTime,1,10) >= '$local_MesReferenciaInicio' and 
																					substring(AcctStopTime,1,10) < '$local_MesReferenciaFim' 
																				)
																			) and
																			AcctStopTime != ''
																	) union (
																		select
																			AcctStartTime DataInicio,
																			AcctStopTime DataFim,
																			AcctInputOctets Upload,
																			AcctOutputOctets Download
																		from
																			radacct
																		where
																			UserName = '$local_Usuario' and
																			(
																				(
																					substring(AcctStartTime,1,10) >= '$local_MesReferenciaInicio' and 
																					substring(AcctStartTime,1,10) < '$local_MesReferenciaFim' 
																				) or (
																					substring(AcctStopTime,1,10) >= '$local_MesReferenciaInicio' and 
																					substring(AcctStopTime,1,10) < '$local_MesReferenciaFim'
																				)
																			) and
																			AcctStopTime != ''
																	)
																	order by 
																		DataInicio;";
													$resRadius = mysql_query($sqlRadius,$conRadius);
													
													while($linRadius = @mysql_fetch_array($resRadius)){
														if(($i % 2) != 0){
															$color	=	"background-color:#EEEEEE";
														} else{
															$color	=	"";
														}
														
														$linRadius[DataInicioTemp]	= dataConv($linRadius[DataInicio],'Y-m-d H:i:s','d/m/Y H:i:s');
														$linRadius[DataInicio]		= dataConv($linRadius[DataInicio],'Y-m-d H:i:s','YmdHis');
														$linRadius[DataFimTemp]		= dataConv($linRadius[DataFim],'Y-m-d H:i:s','d/m/Y H:i:s');
														$linRadius[DataFim]			= dataConv($linRadius[DataFim],'Y-m-d H:i:s','YmdHis');
														$linRadius[Duracao]			= SubHora($linRadius[DataFimTemp],$linRadius[DataInicioTemp],'s');
														$linRadius[Duracao]			= SegHora($linRadius[Duracao]);
														$Duracao					+= horaSegundo($linRadius[Duracao]);
														$Download					+= $linRadius[Download];
														$Upload						+= $linRadius[Upload];
														$linRadius[DownloadTemp]	= byte_convert($linRadius[Download],2);
														$linRadius[UploadTemp]		= byte_convert($linRadius[Upload],2);
														
														$RowsRelatorio .= "<tr>
															<td style='$color;'>$linRadius[DataInicioTemp]</td>
															<td style='$color;'>$linRadius[DataFimTemp]</td>
															<td style='$color; text-align:center;'>$linRadius[Duracao]</td>
															<td style='$color; text-align:right;'>$linRadius[DownloadTemp]</td>
															<td style='$color; text-align:right;'>$linRadius[UploadTemp]</td>
														</tr>";
														$i++;
													}
													
													@mysql_close($conRadius);
												}
												
												if($i == 0){
													$RowsRelatorio .= "<tr><td colspan='5'>&nbsp;</td></tr>";
												}
												
												$Duracao	= segHora($Duracao);
												$Download	= byte_convert($Download,2);
												$Upload		= byte_convert($Upload,2);
												
												echo"<table width='100%' id='tableQuadro' style='margin-top:10px;' cellspacing='0' cellpadding='0'>
														<tr>
															<th>Data Início</th>
															<th>Data Término</th>
															<th style='text-align:center;'>Duração</th>
															<th style='text-align:right;'>Download</th>
															<th style='text-align:right;'>Upload</th>
														</tr>
														$RowsRelatorio
														<tr>
															<th colspan='2'>Total: $i</th>
															<th style='text-align:center;'>$Duracao</th>
															<th style='text-align:right;'>$Download</th>
															<th style='text-align:right;'>$Upload</th>
														</tr>
													</table>";
													
												include('../../files/conecta.php');
											?>
											<br />
											<div style='float:right;'><input type='button' class='BotaoPadrao' onclick='window.print();' value='Imprimir' /></div>
										</td>
									</tr>
									<tr class='OcultarImpressao'>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
