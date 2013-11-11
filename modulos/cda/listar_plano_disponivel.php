<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('files/funcoes.php');
	
	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= 'gerar_fatura_ativacao.php';
	$local_EtapaAnterior	= 'cadastrar_pessoa.php';
	$IdLoja					= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************
	
/*	$local_CPF_CNPJ			= formatText($_POST['CPF_CNPJ'],'MA');
	$local_TipoPessoa		= tipo_pessoa($local_CPF_CNPJ);
	$local_VerificaCidade	= formatText($_POST['IdCidade_EnderecoPrincipal']);
	$local_VerificaEstado	= formatText($_POST['IdEstado_EnderecoPrincipal']);*/
	$local_MSGDescricao		= getParametroSistema(95,11);
	$local_IdPais			= getCodigoInternoCDA(3,1);	
?>
<html>
	<head>
		<?
			include ("files/header.php");
		?>
	</head>
	<body>
		<div id='carregando'>carregando</div>
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td align="center">
					<div id="geral">
						<div id="main">
							<div id="coluna1">
								<div><img src="img/marca_conadmin2.png" width="260" height="50"></div>
								<div id="coluna1main">
									<? include("./files/indice.php"); ?>
								</div>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="15"><img src="img/lrp1.png" width="15" height="15" /></td>
										<td class="lrp"><img src="img/_Espaco.gif" /></td>
										<td width="15"><img src="img/lrp2.png" width="15" height="15" /></td>
									</tr>
								</table>
							</div>
							<div id="coluna2">
								<table width="640" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
										<td id='tit'><h1><img src="./img/icones/7.png" /> <?=getParametroSistema(95,25)?></h1></td>
										<td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
									</tr>
								</table>
								<table width="640" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td colspan='3' class='coluna2main'>
											<form name='formulario' method='post' action='<?=$local_EtapaProxima?>' onSubmit='return validar()'>
												<input type='hidden' name='IdLoja' value=''>
												<input type='hidden' name='IdServico' value=''>
												<?
													$key_existente = array(
														"IdLoja",
														"IdServico",
														"DataNascimento",
														"Email",
														"Site",
														"IdPeriodicidadeTemp",
														"DiaCobrancaDefault",
														"DiaCobrancaTemp",
														"DiaCobranca",
														"QtdParcelaTemp",
														"QtdParcela",
														"TipoContratoTemp",
														"TipoContrato",
														"MesFechadoTemp",
														"MesFechado",
														"IdLocalCobrancaTemp",
														"IdLocalCobranca"
													);
													
													foreach($_POST as $key => $value){
														if(substr($key,0,3) != "bt_"){
															$key = str_replace(array("_EnderecoPrincipal", "_EnderecoCobranca"), array("_1", "_2"), $key);
															
															if(!in_array($key,$key_existente)){
																echo "<input type='hidden' name='$key' value='$value'>";
																
																if($key == "Nome"){
																	echo "<input type='hidden' name='NomeFantasia' value='$value'>";
																}
																
																if($key == "OcultarEnderecoCobranca"){
																	echo "<input type='hidden' name='DescricaoEndereco_2' value='Endereço de Cobrança'>";
																	echo "<input type='hidden' name='IdPais_2' value='$local_IdPais'>";
																}
															} else{
																if($key == "DataNascimento"){
																	if(preg_match("/\d\d\/\d\d\/\d\d\d\d/i",$value)){
																		$value = dataConv(formatText($value,NULL), "d/m/Y", "Y-m-d");
																	}
																	
																	echo "<input type='hidden' name='$key' value='$value'>";
																}
																
																if($key == "Email" || $key == "Site"){
																	$value = formatText($value,getParametroSistema(4,5));
																	echo "<input type='hidden' name='$key' value='$value'>";
																}
															}
															
															eval("\$local_".$key." = '".$value."';");
														}
													}
												?>
												<div style='padding-bottom:2px;'>
													<table width="100%" id='tableQuadro' style='margin-top:10px;' border="0" cellspacing="0" cellpadding="0">
														<tr>
															<th>Id</th>
															<th>Nome Serviço</th>
															<th>Nome Loja</th>
															<th style='text-align:right'>Valor Mensal (<?=getParametroSistema(5,1)?>)</th>
															<th>&nbsp;</th>
														</tr>
														<?
															$i = 0;
															$Tvalor		=	0;
															$sql = "select
																		Servico.IdLoja,
																		Loja.DescricaoLoja,
																		Servico.IdServico,
																		Servico.Filtro_IdPaisEstadoCidade,
																		Servico.Filtro_IdTipoPessoa,
																		substr(Servico.DescricaoServico,1,72) DescricaoServico,
																		Servico.IdTipoServico,
																		substr(ServicoGrupo.DescricaoServicoGrupo,1,30) DescricaoServicoGrupo,
																		ServicoValor.Valor,
																		Servico.IdStatus
																	from 
																		Servico left join (
																			select
																				ServicoValor.IdLoja,
																				ServicoValor.IdServico,
																				ServicoValor.DataInicio,
																				ServicoValor.DataTermino,
																				ServicoValor.Valor
																			from 
																				ServicoValor,
																				(select
																					ServicoValor.IdLoja,
																					ServicoValor.IdServico,
																					max(DataInicio)              DataInicio
																				from 
																					ServicoValor
																				where 
																					ServicoValor.DataInicio <= curdate()
																				group by 
																					ServicoValor.IdLoja,
																					ServicoValor.IdServico) ServicoValorTemp
																			where 
																				ServicoValor.IdLoja = ServicoValorTemp.IdLoja and 
																				ServicoValor.IdServico = ServicoValorTemp.IdServico and 
																				ServicoValor.DataInicio = ServicoValorTemp.DataInicio) ServicoValor on (
																			Servico.IdLoja = ServicoValor.IdLoja and 
																			Servico.IdServico = ServicoValor.IdServico),
																		ServicoGrupo,
																		Loja
																	where 
																		Servico.ContratoViaCDA = '1' and 
																		Servico.IdTipoServico = '1' and
																		Servico.IdStatus = '1' and
																		Servico.IdLoja = ServicoGrupo.IdLoja and
																		Loja.IdLoja = Servico.IdLoja and 
																		Servico.IdServicoGrupo = ServicoGrupo.IdServicoGrupo;";
															$res = @mysql_query($sql, $con);
															if(@mysql_num_rows($res) >=1){
																while($lin = @mysql_fetch_array($res)){
																	$Listar_0 = (($lin[Filtro_IdTipoPessoa] != '' && $lin[Filtro_IdTipoPessoa] == $local_TipoPessoa) || $lin[Filtro_IdTipoPessoa] == '');
																	
																	if($lin[Filtro_IdPaisEstadoCidade] != ''){
																		$Filtro_PaisEstadoCidade = explode("^", $lin[Filtro_IdPaisEstadoCidade]);
																		$Listar_1 = false;
																		
																		for($x = 0; $x < count($Filtro_PaisEstadoCidade); $x++){
																			$Filtro_PaisEstadoCidadeTemp = explode(",", $Filtro_PaisEstadoCidade[$x]);
																			
																			if($local_IdPais_1 == $Filtro_PaisEstadoCidadeTemp[0] && $local_IdEstado_1 == $Filtro_PaisEstadoCidadeTemp[1] && $local_IdCidade_1 == $Filtro_PaisEstadoCidadeTemp[2]){
																				$Listar_1 = true;
																				break;
																			}
																		}
																	} else{
																		$Listar_1 = true;
																	}
																	
																	if($Listar_0 && $Listar_1){
																		$lin[TipoServico] = getParametroSistema(71, $lin[IdTipoServico]);
																		$lin[Status] = getParametroSistema(17, $lin[IdStatus]);
																		
																		if(($i%2) != 0){
																			$color = "background-color:#F2F2F2;";
																		} else{
																			$color = "";
																		}
																		
																		if($lin[Valor] == "")
																			$lin[Valor] = 0;
																		
																		if($lin[ValorRecebido] == "")
																			$lin[ValorRecebido] = 0;
																		
																		$Tvalor	+= $lin[Valor];
																		
																		if($lin[Valor] != 0){
																			$Valor = formatNumber(formata_double(($lin[Valor])));
																		} else{
																			$Valor = "&nbsp;";
																		}
																		
																		echo "<tr>
																				<TD style='$color'>$lin[IdServico]</TD>
																				<TD style='$color'>$lin[DescricaoServico]</TD>
																				<TD style='$color'>$lin[DescricaoLoja]</TD>
																				<TD style='$color text-align:right'>$Valor</TD>
																				<TD style='$color text-align:right; padding:2px 0 2px 0;'><input name='bt_submit' type='button' class='BotaoPadrao' value='Contratar' onClick=\"document.formulario.IdServico.value = $lin[IdServico]; document.formulario.IdLoja.value = $lin[IdLoja]; document.formulario.submit();\" /></TD>
																			</tr>";
																		
																		$i++;
																	}
																}
															} else{
																echo "<tr>
																		<TD>&nbsp;</TD>
																		<TD>&nbsp;</TD>
																		<TD>&nbsp;</TD>
																		<TD>&nbsp;</TD>
																		<TD>&nbsp;</TD>
																</tr>";
															}
															
															echo "
																<tr>
																	<th colspan='5' style='text-align:left'>Total: $i</th>
																</tr>
															";
														?>
													</table>
												</div>
												<table style="width:100%;">
													<tr>
														<td><input type="button" class="BotaoPadrao" value="Voltar" tabindex='104' onClick="voltar_etapa('<?=$local_EtapaAnterior?>')"/></td>
													</tr>
												</table>
											</form>
										</td>
									</tr>
									<tr>
										<td colspan='3'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</td>
			</tr>
		  	<tr>
		    	<td height="20" align="center">
					<div id='rodape'>
					<?
						if(file_exists('personalizacao/rodape.php')){
							include("personalizacao/rodape.php");
						}else{
							include("files/rodape.php");
						}
					?>
					</div>
				</td>
		  	</tr>
		</table>
	</body>
</html>
<script type="text/javaScript">
<!--
	function voltar_etapa(url){
		document.formulario.action = url;
		document.formulario.submit();
	}
	
	enterAsTab(document.forms.formulario);
	-->
</script>