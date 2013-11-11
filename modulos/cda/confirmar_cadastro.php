<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('files/funcoes.php');
	
	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= 'files/inserir/inserir_pessoa_adesao_plano.php';
	$local_EtapaAnterior	= 'gerar_fatura_ativacao.php';
	$local_Erro				= $_GET['Erro'];
	$local_IdLoja			= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************
	
	$local_MSGDescricao		= getParametroSistema(95,11);
	$local_Erro				= $_GET['Erro'];
?>
<html>
	<head>
		<?
			include ("files/header.php");
		?>
		
		<script type="text/javascript" src="js/pessoa.js"></script>	
		<script type="text/javascript" src="js/funcoes.js"></script>	
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
										<td id='tit'><h1><img src="./img/icones/7.png" /> <?=getParametroSistema(95,29)?></h1></td>
										<td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
									</tr>
								</table>
								<table width="640" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td colspan='3' class='coluna2main'>
											<form name='formulario' method='post' action='<?=$local_EtapaProxima?>' onSubmit='return validar()'>
												<input type='hidden' name='VisivelNomeConjugue' value='2'>
												<?
													$key_existente = array(
														"NomeResponsavelEndereco_1", 
														"NomeResponsavelEndereco_2", 
														"IdEstado_EnderecoPrincipal", 
														"IdCidade_EnderecoPrincipal", 
														"OcultarEnderecoCobranca", 
														"IdEstado_EnderecoCobranca", 
														"IdCidade_EnderecoCobranca", 
														"Senha", 
														"Confirmacao", 
														"ValorServico", 
														"IdPeriodicidade", 
														"QtdParcela", 
														"TipoContrato", 
														"MesFechado", 
														"IdLocalCobranca", 
														"DiaCobranca"
													);
													$local_OcultarEnderecoCobranca = '';
													
													foreach($_POST as $key => $value){
														if(substr($key,0,3) != "bt_"){
															if($key == "OcultarEnderecoCobranca"){
																$local_OcultarEnderecoCobranca = "checked";
															} else{
																if(substr($key,0,6) == "Valor_"){
																	$temp = explode("_",$key);
																	$var = "\$local_".$temp[0]."Parametro[".$temp[1]."]";
																	$key .= "_Temp";
																} else if(substr($key,0,10) == "CampoExtra" && substr($key,11,11) != "Obrigatorio"){
																	$var = "\$local_CampoExtra[".substr($key,10,1)."]";
																	$key .= "_Temp";
																} else{
																	$var = "\$local_".$key;
																}
																
																eval($var." = '".$value."';");
															}
															
															if(in_array($key,$key_existente)){
																$key .= "Temp";
															}
															
															echo "<input type='hidden' name='$key' value='$value'>";
														}
													}
													
													$local_DataNascimento = dataConv(formatText($local_DataNascimento,NULL), "Y-m-d", "d/m/Y");
													
													echo "<p>Dados Cadastrais</p>";
													
													if($local_TipoPessoa == 1){ //Juridica
														echo"
														<table>
															<tr>
																<td class='title'>Nome Fantasia</td>
																<td class='sep' />
																<td class='title'>Razão Social</td>
															</tr>
															<tr>	
																<td>
																	<input class='FormPadrao' type='text' value='$local_Nome' style='width:294px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_RazaoSocial' style='width:294px' readOnly>
																</td>
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'>Nome Representante</td>
																<td class='sep' />
																<td class='title'>Inscrição Estadual</td>
																<td class='sep' />
																<td class='title'>Inscrição Municipal</td>
																<td class='sep' />
																<td class='title'>Data Fundação</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' value='$local_NomeRepresentante' style='width:294px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_RG_IE' style='width:98px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_InscricaoMunicipal' style='width:98px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_DataNascimento' style='width:80px' readOnly>
																</td>
															</tr>
														</table>
														";
													}
													 else{ //Fisica
														echo"
														<table>
															<tr>
																<td class='title'>Nome Pessoa</td>
																<td class='sep' />
																<td class='title'>Sexo</td>
																<td class='sep' />
																<td class='title'>Data Nasc.</td>
																<td class='sep' />
																<td class='title'>Estado Civil</td>
																<td class='sep' />
																<td class='title'>RG</td>
																<td class='sep' />
																<td class='title'>Orgão Exp.</td>
															</tr>
															<tr>	
																<td>
																	<input class='FormPadrao' type='text' value='$local_Nome' style='width:190px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<select class='FormPadrao' style='width: 55px' disabled>";
																		$sql2 = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 8 and IdParametroSistema = '$local_Sexo' order by ValorParametroSistema";
																		$res2 = @mysql_query($sql2,$con);
																		while($lin2 = @mysql_fetch_array($res2)){
																			echo"<option value='$lin2[IdParametroSistema]'>$lin2[ValorParametroSistema]</option>";
																		}
																	echo"
																	</select>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_DataNascimento' style='width:80px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<select class='FormPadrao' style='width:100px' name='EstadoCivil'>";
																		$sql2 = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 9 and IdParametroSistema = '$local_EstadoCivil'";
																		$res2 = @mysql_query($sql2,$con);
																		$lin2 = @mysql_fetch_array($res2);
																		echo"<option value='$lin2[IdParametroSistema]'>$lin2[ValorParametroSistema]</option>";
																		echo"
																	</select>
																	<input type='hidden' name='EstadoCivilResposta' value='$lin2[IdParametroSistema]'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_RG_IE' style='width:90px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='OrgaoExpedidor' value='$local_OrgaoExpedidor' style='width:56px' readOnly>
																</td>
															</tr>
														</table>	
														";

														echo"
														<table>
															<tr>
																<td class='title'>Nome Mãe</td>
																<td class='sep' />
																<td class='title'>Nome Pai</td>																												
																<td class='sep' />
																<td class='title'>Nome Conjugue</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='NomeMae' value='$local_NomeMae' style='width:210px'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='NomePai' value='$local_NomePai' style='width:210px'>
																</td>																
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='NomeConjugue' width='212px' value='$local_NomeConjugue'>
																</td>		
															</tr>
														</table>";			
													}
													
													echo"
														<table>
															<tr>
																<td class='title'>Fone Residencial</td>
																<td class='sep' />
																<td class='title'>Fone Comercial (1)</td>
																<td class='sep' />
																<td class='title'>Fone Comercial (2)</td>
																<td class='sep' />
																<td class='title'>Celular</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' value='$local_Telefone1' style='width:143px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_Telefone2' style='width:143px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_Telefone3' style='width:143px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_Celular' style='width:143px' readOnly>
																</td>		
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'>Fax</td>	
																<td class='sep' />
																<td class='title'>Complemento Fone</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' value='$local_Fax' style='width:143px' readOnly>
																</td>	
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_ComplementoTelefone' style='width:143px' readOnly>
																</td>
																<td style='width:320px;' />
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'>E-mail</td>
																<td class='sep' />
																<td class='sep' />
																<td class='title'>Site (url)</td>
																<td class='sep' />
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' value='$local_Email' style='width:277px' readOnly>
																</td>
																<td class='sep' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='".($local_Site != '' ? $local_Site : "http://")."' style='width:273px' readOnly>
																</td>
																<td class='sep' onClick='window.open(document.formulario.Site.value)'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Acessar Site'></td>
															</tr>
														</table>";
													
													$sql1 = "
														select 
															IdParametroSistema,
															DescricaoParametroSistema,
															ValorParametroSistema 
														from 
															GrupoParametroSistema,
															ParametroSistema 
														where 
															GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and 
															GrupoParametroSistema.IdGrupoParametroSistema = 2 and 
															substr(DescricaoParametroSistema,26,31) = 'Ativo' and 
															ValorParametroSistema = 'S';";
													$res1 = mysql_query($sql1,$con);
													$quant = mysql_num_rows($res1);
													
													if($quant > 0){
														echo "<table><tr>";
														$i = 1;
														$campo = '';
														
														while($lin1 = mysql_fetch_array($res1)){
															$IdTitulo = $lin1[IdParametroSistema] + 1;
															$sql2 = "
																select 
																	IdParametroSistema,
																	ValorParametroSistema 
																from 
																	GrupoParametroSistema,
																	ParametroSistema 
																where 
																	GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and 
																	GrupoParametroSistema.IdGrupoParametroSistema = 2 and 
																	IdParametroSistema = ".$IdTitulo;
															$res2 = mysql_query($sql2,$con);
															$lin2 = mysql_fetch_array($res2);
															$IdCampo = endArray(explode(" ",$lin2[ValorParametroSistema]));
															$IdObrigatoriedade = $lin1[IdParametroSistema] + 2;
															$sql3 = "
																select 
																	IdParametroSistema,
																	ValorParametroSistema 
																from 
																	GrupoParametroSistema,
																	ParametroSistema 
																where 
																	GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and 
																	GrupoParametroSistema.IdGrupoParametroSistema = 2 and 
																	IdParametroSistema = ".$IdObrigatoriedade;
															$res3 = mysql_query($sql3,$con);
															$lin3 = mysql_fetch_array($res3);
															$mod = (($i % 2) == 0);
															
															if($mod){
																echo "<td class='sep' />";
																$campo .= "<td class='sep' />";
															}
															
															echo "<input type='hidden' name='CampoExtra".$IdCampo."Obrigatorio' value ='$lin3[ValorParametroSistema]'><td class='title'><span id='titCampoExtra".$IdCampo."'>$lin2[ValorParametroSistema]</span></td>";
															
															$campo .= "<td><input class='FormPadrao' type='text' name='CampoExtra".$IdCampo."' value='".$local_CampoExtra[$IdCampo]."' style='width:294px' readOnly></td>";
															
															if($mod){
																echo "</tr><tr>$campo</tr></table><table><tr>";
																$campo = '';
															}
															
															$i++;
														}
														
														if($campo != ''){
															echo "<tr>$campo</tr>";
														}
														
														echo "</tr></table>";
													}
													
													echo "
														<table cellspacing='0' cellpading='0' style='margin-top:10px;'>
															<tr>
																<td style='width:40%; text-align:left'>Endereço Principal</td>
															</tr>
														</table>";
													if($local_TipoPessoa == 1){
														echo "<table>
																<tr>
																	<td class='title'>Nome Responsável</td>
																</tr>
																<tr>
																	<td>
																		<input class='FormPadrao' type='text' value='$local_NomeResponsavelEndereco_1' style='width:294px' readOnly>
																	</td>
																</tr>
															</table>";
													}
													echo "
														<table>
															<tr>
																<td class='title'>CEP</td>
																<td class='sep' />
																<td class='title'>Endereço</td>
																<td class='sep' />
																<td class='title'>Nº</td>	
																<td class='sep' />
																<td class='title'>Complemento</td>
																<td class='sep' />	
																<td class='title'>Bairro</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' value='$local_CEP_1' style='width:70px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_Endereco_1' style='width:195px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_Numero_1' style='width:55px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_Complemento_1' style='width:122px' readOnly>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' value='$local_Bairro_1' style='width:122px' readOnly>
																</td>
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'>Estado</td>
																<td class='sep' />
																<td class='title'>Cidade</td>
															</tr>
															<tr>
																<td>
																	<select class='FormPadrao' name='IdEstado_EnderecoPrincipal' style='width:186px' disabled>
																		<option value='' selected></option>
																	</select>
																</td>
																<td class='sep' />
																<td>
																	<select class='FormPadrao' name='IdCidade_EnderecoPrincipal' style='width:294px' disabled>
																		<option value='' selected></option>
																	</select>
																</td>
															</tr>
														</table>
														<div style='padding:4px 0 4px 2px;'>
															<table cellspacing='0' cellpading='0' style='width:300px; border:1px solid #A4A4A4;'>
																<tr>
																	<td style='padding:2px 0 1px 2px;'><input type='checkbox' name='OcultarEnderecoCobranca' $local_OcultarEnderecoCobranca disabled/></td>
																	<td><div>Utilizar outro endereço para correspondência.</td>
																</tr>
															</table>
														</div>
														<div id='cpEnderecoCobranca' style='display:none;'>
															<table cellspacing='0' cellpading='0' style='margin-top:10px;'>
																<tr>
																	<td style='width:40%; text-align:left'>Endereço de Cobrança</td>
																</tr>
															</table>";
														
													if($local_TipoPessoa == 1){
														echo "<table>
																<tr>
																	<td class='title'>Nome Responsável</td>
																</tr>
																<tr>
																	<td>
																		<input class='FormPadrao' type='text' value='$local_NomeResponsavelEndereco_2' style='width:294px;' readOnly>
																	</td>
																</tr>
															</table>";
													}
													
													echo "	<table>
																<tr>
																	<td class='title'>CEP</td>
																	<td class='sep' />
																	<td class='title'>Endereço</td>
																	<td class='sep' />
																	<td class='title'>Nº</td>	
																	<td class='sep' />
																	<td class='title'>Complemento</td>
																	<td class='sep' />	
																	<td class='title'>Bairro</td>
																</tr>
																<tr>
																	<td>
																		<input class='FormPadrao' type='text' value='$local_CEP_2' style='width:70px' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' value='$local_Endereco_2' style='width:195px' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' value='$local_Numero_2' style='width:55px' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' value='$local_Complemento_2' style='width:122px' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' value='$local_Bairro_2' style='width:122px' readOnly>
																	</td>
																</tr>
															</table>
															<table>
																<tr>
																	<td class='title'>Estado</td>
																	<td class='sep' />
																	<td class='title'>Cidade</td>
																</tr>
																<tr>
																	<td>
																		<select class='FormPadrao' name='IdEstado_EnderecoCobranca' style='width:186px' disabled>
																			<option value='' selected></option>
																		</select>
																	</td>
																	<td class='sep' />
																	<td>
																		<select class='FormPadrao' name='IdCidade_EnderecoCobranca' style='width:294px' disabled>
																			<option value='' selected></option>
																		</select>
																	</td>
																</tr>
															</table>
														</div>
													";
													
													if(getParametroSistema(95,2) == 1){
														echo "
															<table cellspacing='0' cellpading='0' style='margin-top:10px;'>
																<tr>
																	<td style='width:40%; text-align:left'>Senha de Acesso a Central do Assinante</td>
																</tr>
															</table>
															<table>
																<tr>
																	<td class='title'>Senha</td>
																	<td class='sep' />
																	<td class='title'>Confirme sua Senha</td>
																</tr>
																<tr>
																	<td>
																		<input class='FormPadrao' type='password' name='Senha' value='$local_Senha' style='width:142px' maxlength='8' autocomplete='off' readOnly>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='password' name='Confirmacao' value='$local_Senha' style='width:142px' maxlength='8' autocomplete='off' readOnly>
																	</td>
																</tr>
															</table>
														";
													}
													
													echo "
															<table cellspacing='0' cellpading='0' style='margin-top:10px;'>
																<tr>
																	<td class='title'>Observações do Cadastro</td>
																</tr>
																<tr>
																	<td><textarea name='Obs' style='width: 600px;height: 90px' ReadOnly='ReadOnly'>$local_Obs</textarea></td>
																</tr>
															</table>
														";
													
													$sql = "select 
																Servico.IdServico,
																Servico.DescricaoServico, 
																Servico.DetalheServico,
																ServicoValor.Valor
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
																		(
																			select
																				ServicoValor.IdLoja,
																				ServicoValor.IdServico,
																				max(DataInicio) DataInicio
																			from 
																				ServicoValor
																			where 
																				ServicoValor.IdLoja = '$local_IdLoja' and
																				ServicoValor.DataInicio <= curdate()
																			group by 
																				ServicoValor.IdServico
																		) ServicoValorTemp
																	where 
																		ServicoValor.IdLoja = ServicoValorTemp.IdLoja and 
																		ServicoValor.IdServico = ServicoValorTemp.IdServico and 
																		ServicoValor.DataInicio = ServicoValorTemp.DataInicio
																) ServicoValor on (
																	Servico.IdLoja = ServicoValor.IdLoja and 
																	Servico.IdServico = ServicoValor.IdServico
																)
															where 
																Servico.IdLoja = '$local_IdLoja' and
																Servico.IdServico = '$local_IdServico' and
																Servico.ContratoViaCDA = '1' and 
																Servico.IdTipoServico = '1' and
																Servico.IdStatus = '1';";
													$res = @mysql_query($sql,$con);
													$lin = @mysql_fetch_array($res);
													$lin[Valor] = str_replace('.', ',', $lin[Valor]);
													
													echo"
													<br />
													<p>Serviços/Planos Disponíveis</p>
													<table>
														<tr>
															<td class='title'>Nome Serviço</td>
															<td class='sep' />
															<td class='title'>Valor (".getParametroSistema(5,1).")</td>
														</tr>
														<tr>	
															<td>
																<input class='FormPadrao' type='text' style='width:483px;' value='$lin[DescricaoServico]' readOnly />
															</td>
															<td class='sep' />
															<td>
																<input class='FormPadrao' type='text' style='width:104px;' value='$lin[Valor]' readOnly />
																<input type='hidden' name='ValorServico' value='$lin[Valor]'>
															</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'>Descrição do Serviço</td>
														</tr>
														<tr>
															<td>
																<textarea style='width:596px;' rows='5' readOnly>$lin[DetalheServico]</textarea>
															</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'>Periodicidade</td>
															<td class='sep' />
															<td class='title'>QTD. Parcelas</td>
															<td class='sep' />
															<td class='title'>Tipo Contrato</td>
															<td class='sep' />
															<td class='title'>Vencimento</td>
														</tr>
														<tr>
															<td>
																<select class='FormPadrao' name='IdPeriodicidade' style='width:257px' disabled>";
													
													$sql0 = "select distinct
																ServicoPeriodicidade.IdServico,	
																ServicoPeriodicidade.IdPeriodicidade,
																Periodicidade.DescricaoPeriodicidade
															from
																Periodicidade,
																ServicoPeriodicidade,
																LocalCobranca
															where
																ServicoPeriodicidade.IdLoja = $local_IdLoja and
																ServicoPeriodicidade.IdLoja = Periodicidade.IdLoja and
																ServicoPeriodicidade.IdLoja = LocalCobranca.IdLoja and
																ServicoPeriodicidade.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
																ServicoPeriodicidade.IdPeriodicidade = Periodicidade.IdPeriodicidade and
																ServicoPeriodicidade.IdPeriodicidade = $local_IdPeriodicidade and
																ServicoPeriodicidade.IdServico = $local_IdServico
															order by 
																Periodicidade.DescricaoPeriodicidade ASC;";
													$res0 = @mysql_query($sql0,$con);
													while($lin0 = @mysql_fetch_array($res0)){
														echo"<option value='$lin0[IdPeriodicidade]'>$lin0[DescricaoPeriodicidade]</option>";
													}
													echo "		</select>
															</td>
															<td class='sep' />
															<td>
																<select class='FormPadrao' name='QtdParcela' style='width:104px' disabled>
																	<option value=''>&nbsp;</option>
																</select>
															</td>
															<td class='sep' />
															<td>
																<select class='FormPadrao' name='TipoContrato' style='width:104px' disabled>
																	<option value=''>&nbsp;</option>
																</select>
															</td>
															<td class='sep' />
															<td>
																<select class='FormPadrao' name='DiaCobranca' style='width:104px' disabled>";
																
																$sql0 = "select 
																			ValorCodigoInterno 
																		from 
																			(
																				select 
																					convert(ValorCodigoInterno,UNSIGNED) ValorCodigoInterno 
																				from 
																					CodigoInterno 
																				where 
																					IdLoja = $local_IdLoja and 
																					IdGrupoCodigoInterno = 1
																			) CodigoInterno 
																		where
																			CodigoInterno.ValorCodigoInterno = $local_DiaCobranca
																		order by 
																			ValorCodigoInterno";
																$res0 = @mysql_query($sql0,$con);
																while($lin0 = @mysql_fetch_array($res0)){
																	echo"<option value='$lin0[ValorCodigoInterno]'>".visualizarNumber($lin0[ValorCodigoInterno])."</option>";
																}
																
																echo"		</select>
																		</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'>Local de Cobrança</td>
															<td class='sep' />
															<td class='title'>Mês Fechado</td>
														</tr>
														<tr>
															<td>
																<select class='FormPadrao' name='IdLocalCobranca' style='width:483px' disabled>
																	<option value=''>&nbsp;</option>
																</select>
															</td>
															<td class='sep' />
															<td>
																<select class='FormPadrao' name='MesFechado' style='width:104px' disabled>
																	<option value=''>&nbsp;</option>
																</select>
															</td>
													</table>";
													# PARAMETRO DO SERVIÇO
													$i = 0;
													$qtd = 0;
													$sqlPam	= " select
																	ServicoParametro.IdServico,
																	ServicoParametro.IdParametroServico,
																	ServicoParametro.DescricaoParametroServicoCDA,
																	ServicoParametro.ValorDefault,
																	ServicoParametro.Obrigatorio,
																	ServicoParametro.Obs,
																	ServicoParametro.RotinaCalculo,
																	ServicoParametro.RotinaOpcoes,
																	ServicoParametro.RotinaOpcoesContrato,
																	ServicoParametro.Calculavel,
																	ServicoParametro.RotinaOpcoes,
																	ServicoParametro.RotinaOpcoesContrato,
																	ServicoParametro.CalculavelOpcoes,
																	(2) Editavel,
																	#ServicoParametro.Editavel,
																	ServicoParametro.IdTipoParametro,
																	ServicoParametro.IdMascaraCampo,
																	ServicoParametro.IdTipoTexto,
																	ServicoParametro.ExibirSenha,
																	ServicoParametro.TamMinimo,
																	ServicoParametro.TamMaximo,
																	ServicoParametro.OpcaoValor,
																	ServicoParametro.VisivelCDA
																from 
																	Loja,
																	Servico,
																	ServicoParametro 
																where
																	Servico.IdLoja = $local_IdLoja and
																	Servico.IdServico = ServicoParametro.IdServico and
																	ServicoParametro.IdLoja = Servico.IdLoja and
																	Servico.IdLoja = Loja.IdLoja and
																	ServicoParametro.IdServico = $lin[IdServico] and
																	ServicoParametro.VisivelCDA = 1 and
																	ServicoParametro.IdStatus = 1
																order by 
																	ServicoParametro.IdParametroServico ASC";
													$resPam	= @mysql_query($sqlPam,$con);
													
													while($linPam = @mysql_fetch_array($resPam)){
														if(($linPam[IdTipoTexto] == 3 || $linPam[IdTipoTexto] == 4 || $linPam[IdTipoTexto] == 5) || ($linPam[IdTipoParametro] == 2)){
															$linPam[IdTipoTexto] = 1;
														}
														
														if($linPam[IdTipoTexto] == 2){
															$i++;
															
															$Parametro[IdParametroServico][$qtd]		=	$linPam[IdParametroServico];
															$Parametro[DescricaoParametroServico][$qtd]	=	$linPam[DescricaoParametroServicoCDA];
															$Parametro[ValorDefault][$qtd]				=	$linPam[ValorDefault];
															$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
															$Parametro[Obs][$qtd]						=	$linPam[Obs];
															$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
															$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
															$Parametro[IdMascaraCampo][$qtd]			=	$linPam[IdMascaraCampo];
															$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
															$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
															$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
															$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
															$Parametro[OpcaoValor][$qtd]				=	$linPam[OpcaoValor];
															
															$qtd++;	
															
															$Parametro[IdParametroServico][$qtd]		=	'10001';
															$Parametro[DescricaoParametroServico][$qtd]	=	'Confirmação '.$linPam[DescricaoParametroServico];
															$Parametro[ValorDefault][$qtd]				=	'';
															$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
															$Parametro[Obs][$qtd]						=	'';
															$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
															$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
															$Parametro[IdMascaraCampo][$qtd]			=	'';
															$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
															$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
															$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
															$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
															$Parametro[OpcaoValor][$qtd]				=	'';
															
															$qtd++;
														} else{
															$Parametro[IdParametroServico][$qtd]		=	$linPam[IdParametroServico];
															$Parametro[DescricaoParametroServico][$qtd]	=	$linPam[DescricaoParametroServicoCDA];
															$Parametro[ValorDefault][$qtd]				=	$linPam[ValorDefault];
															$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
															$Parametro[Obs][$qtd]						=	$linPam[Obs];
															$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
															$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
															$Parametro[IdMascaraCampo][$qtd]			=	$linPam[IdMascaraCampo];
															$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
															$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
															$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
															$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
															$Parametro[OpcaoValor][$qtd]				=	$linPam[OpcaoValor];
														
															$qtd++;															
														}
													}
													
													$bt_alterar = false;
													
													if($qtd > 0){
														echo"<BR><span style='font-size:14px;'>Parâmetros do Serviço:</span>";
														$bt_alterar = true;
													}
													
													for($i = 0; $i < $qtd; $i++){
														echo "<table border='0'>
																<tr>";
														
														$Descricao	=	$Parametro[DescricaoParametroServico][$i];
														
														echo "<td class='title'>$Descricao</td>";
														
														$tipoColuna1 = $Parametro[IdTipoTexto][$i];
														$prox1	=	$i+1;
													
														if(($Parametro[IdTipoTexto][$i] == 1 && $Parametro[IdTipoTexto][$prox1] == 1) || ($Parametro[IdTipoTexto][$i] == 2 && $Parametro[IdTipoTexto][$prox1] == 2)){
															
															if($prox1 < $qtd){
																echo"<td class='sep' />";
																
																$Descricao	=	$Parametro[DescricaoParametroServico][$prox1];
																
																echo "<td class='title'>$Descricao</td>";
															}
														}
														
														$tipoColuna2 = $Parametro[IdTipoTexto][$prox1];
														$prox2	=	$i+2;
														
														if(($Parametro[IdTipoTexto][$prox1] == 1 && $Parametro[IdTipoTexto][$prox2] == 1) || ($Parametro[IdTipoTexto][$prox1-1] == 2 && $Parametro[IdTipoTexto][$prox2] == 2)){															
															if($prox2 < $qtd){
																echo"<td class='sep' />";
																
																$Descricao	=	$Parametro[DescricaoParametroServico][$prox2];
																
																echo "<td class='title'>$Descricao</td>";
															}																
														}
														
														$tipoColuna3 = $Parametro[IdTipoTexto][$prox2];
														
														echo "</tr>";
														echo "<tr>";
														
														if($Parametro[TamMaximo][$i]!=""){
															$tamMax	=	"maxlength='".$Parametro[TamMaximo][$i]."'";
														} else{
															$tamMax	=	"";
														}
														
														if($Parametro[Editavel][$i]==1){
															$disabled	=	"";
														} else{
															$disabled	=	"readOnly";
														}
														
														if($Parametro[IdTipoParametro][$i]==1){	
															switch($Parametro[IdTipoTexto][$i]){
																case '1':
																	switch($Parametro[IdMascaraCampo][$i]){
																		case '1':	//Data
																			echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' maxlength='10' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			break;
																		case '2':	//Inteiro	
																			echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' $tamMax $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			break;
																		case '3':	//Float																				
																			if($Parametro[Editavel][$i] == 1){
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' $tamMax $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			}else{
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			}
																			break;
																		case '4':	//Usuário																				
																			echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' maxlenght='17' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			break;
																		case '5':	//MAC																				
																			echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' maxlenght='17' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			break;
																		default:
																			if($Parametro[IdTipoTexto][$i] == 1){
																				$tipo	=	'text';
																			}else{
																				$tipo	=	'password';
																			}																																						
																			echo"<td valign='top'>
																					<input class='FormPadrao' type='$tipo' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px'  $tamMax $disabled>
																					<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																					<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>
																					<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>																						
																					";																				
																	}
																	break;
																case '2':																			
																	echo"<td valign='top'>
																			<input class='FormPadrao' type='password' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' $tamMax style='width:193px' $disabled>
																			<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																			<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>																		
																			<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";																
																	if($Parametro[IdTipoTexto][$i] ==	2)
																	   echo"<input type='hidden' name='SenhaValor_".$Parametro[IdParametroServico][$i]."' value=''>";																		
																	break;
															}
															
															if($Parametro[Obs][$i]!=""){
																echo	"<BR>".$Parametro[Obs][$i];
															}
															
															echo"</td>";
														} else{
															if($disabled == "readOnly"){
																$disabled	=	"disabled";
															}
															
															echo"<td valign='top'>";	
															echo	"<select name='Valor_".$Parametro[IdParametroServico][$i]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled>";
															echo		"<option value=''></option>";
															
															$valor	=	explode("\n",$Parametro[OpcaoValor][$i]);
															$tam = 0;
															
															for($ii=0; $ii<count($valor); $ii++){
																if($valor[$ii] != "") $tam++;
															}
															
															for($ii=0; $ii<$tam; $ii++){
																echo "<option value='".$valor[$ii]."' ".compara(trim($local_ValorParametro[$Parametro[IdParametroServico][$i]]),trim($valor[$ii]),"selected='selected'","").">".$valor[$ii]."</option>";
															}
															
															echo "</select>";
															echo	"<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
															
															if($Parametro[Obs][$i]!=""){
																echo	"<BR>".$Parametro[Obs][$i];
															}
															
															echo"</TD>";
														}			
														
														if($prox1 < $qtd){
															if($Parametro[TamMaximo][$prox1]!=""){
																$tamMax	=	"maxlength='".$Parametro[TamMaximo][$prox1]."'";
															} else{
																$tamMax	=	"";
															}
														
															if($Parametro[Editavel][$prox1]==1){
																$tab++;
																$disabled	=	"";
															} else{
																$disabled	=	"readOnly";
															}
															
															echo"<td class='sep' />";
															
															if($Parametro[IdTipoParametro][$prox1]==1){	
																switch($Parametro[IdTipoTexto][$prox1]){
																	case '1':
																		switch($Parametro[IdMascaraCampo][$prox1]){
																			case '1':	//Data
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlength='10' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				break;
																			case '2':	//Inteiro
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' $tamMax $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				break;
																			case '3':	//Float
																				if($Parametro[Editavel][$prox1] == 1){
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' $tamMax $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				}else{
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				}
																				break;
																			case '4':	//Usuário
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlenght='17' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				break;
																			case '5':	//MAC
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlenght='17' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				break;
																			default:	
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px'  $tamMax $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																		}
																		break;
																	case '2':
																		if($Parametro[IdTipoTexto][$i] == 2 && $Parametro[IdTipoTexto][$prox1] == 2){
																			echo"<td valign='top'>
																					<input class='FormPadrao' type='password' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' $tamMax style='width:193px' $disabled>
																					<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$i]."'>
																					<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'>
																					<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";																				
																		}
																		break;
																}
																
																if($Parametro[Obs][$prox1]!=""){
																	echo	"<BR>".$Parametro[Obs][$prox1];
																}
																
																echo"</td>";
															} else{
																if($disabled == "readOnly"){
																	$disabled	=	"disabled";
																}
																
																echo"<td valign='top'>";	
																echo	"<select name='Valor_".$Parametro[IdParametroServico][$prox1]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled>";
																echo		"<option value=''></option>";
																
																$valor = explode("\n",$Parametro[OpcaoValor][$prox1]);
																$tam = 0;
																
																for($ii=0; $ii<count($valor); $ii++){
																	if($valor[$ii] != "") $tam++;
																}
																
																for($ii=0; $ii<$tam; $ii++){
																	echo "<option value='".$valor[$ii]."' ".compara(trim($local_ValorParametro[$Parametro[IdParametroServico][$prox1]]),trim($valor[$ii]),"selected='selected'","").">".$valor[$ii]."</option>";
																}
																
																echo "</select>";
																echo	"<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																
																if($Parametro[Obs][$prox1]!=""){
																	echo	"<BR>".$Parametro[Obs][$prox1];
																}
																
																echo"</td>";
															}
														}
														
														if($prox2 < $qtd){
															if($Parametro[TamMaximo][$prox2]!=""){
																$tamMax	=	"maxlength='".$Parametro[TamMaximo][$prox2]."'";
															} else{
																$tamMax	=	"";
															}
															
															if($Parametro[Editavel][$prox2]==1){
																$disabled	=	"";
															} else{
																$disabled	=	"readOnly";
															}
															
															echo"<td class='sep' />";
															
															if($Parametro[IdTipoParametro][$prox2]==1){	
																switch($Parametro[IdTipoTexto][$prox2]){
																	case '1':
																		switch($Parametro[IdMascaraCampo][$prox2]){
																			case '1':	//Data
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlength='10' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				break;
																			case '2':	//Inteiro
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' $tamMax $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				break;
																			case '3':	//Float
																				if($Parametro[Editavel][$prox2] == 1){
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' $tamMax $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				}else{
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				}
																				break;
																			case '4':	//Usuário
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlenght='17' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				break;
																			case '5':	//MAC
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlenght='17' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				break;
																			default:	
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px'  $tamMax $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																		}
																		break;
																	case '2':
																		if($Parametro[IdTipoTexto][$prox1-1] == 2 && $tipoColuna2 == 2 && $Parametro[IdTipoTexto][$prox2] == 2){
																			echo"<td valign='top'><input class='FormPadrao' type='password' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' $tamMax style='width:193px' $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																		}
																		break;
																}
																
																if($Parametro[IdTipoTexto][$prox1] == 1 &&  $tipoColuna1 == 1){
																	if($Parametro[Obs][$prox2]!=""){
																		echo	"<BR>".$Parametro[Obs][$prox2];
																	}
																}
																
																echo"</td>";
															} else{
																if($disabled == "readOnly"){
																	$disabled	=	"disabled";
																}
																
																echo"<td valign='top'>";	
																echo	"<select name='Valor_".$Parametro[IdParametroServico][$prox2]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled>";
																echo		"<option value=''></option>";
																
																$valor = explode("\n",$Parametro[OpcaoValor][$prox2]);
																$tam = 0;
																
																for($ii=0; $ii<count($valor); $ii++){
																	if($valor[$ii] != "") $tam++;
																}
																
																for($ii=0; $ii<$tam; $ii++){
																	echo "<option value='".$valor[$ii]."' ".compara(trim($local_ValorParametro[$Parametro[IdParametroServico][$prox2]]),trim($valor[$ii]),"selected='selected'","").">".$valor[$ii]."</option>";
																}
																
																echo "</select>";
																echo	"<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																
																if($Parametro[Obs][$prox2]!=""){
																	echo	"<BR>".$Parametro[Obs][$prox2];
																}
																
																echo"</td>";
															}
														}
														
														echo"	</tr>";	
														echo "</table>";
														
														if($tipoColuna1 == 1 && $tipoColuna2 == 1 && ($tipoColuna3 == 2 || $tipoColuna3 == 0)){
															$i +=1;
														}
														
														if($tipoColuna1 == 1 && $tipoColuna2 == 1 && ($tipoColuna3 == 1 || $tipoColuna3 == 0)){
															$i +=2;
														}
														
														if($tipoColuna1 == 2 && $tipoColuna2 == 2 && ($tipoColuna3 == 2 || $tipoColuna3 == 0)){
															$i +=2;
														}
														
														$tipoColuna3 = 0;
													}
												?>
												<table style="width:100%;">
													<tr>
														<td><input type="button" class="BotaoPadrao" value="Voltar" tabindex='104' onClick="voltar_etapa('<?=$local_EtapaAnterior?>')"/></td>
														<td style='text-align:right'><input name="bt_submit" type="submit" class="BotaoPadrao" value="Confirmar" tabindex='103'/></td>
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
	function status_inicial(){
		<?
			if($local_IdEstado_1 != '' && $local_IdCidade_1 != ''){
				echo "busca_estado('_EnderecoPrincipal', '$local_IdPais_1', '$local_IdEstado_1', '$local_IdCidade_1');";
			} else{
				echo "busca_estado('_EnderecoPrincipal', '$local_IdPais_1');";
			}
			
			if($local_OcultarEnderecoCobranca != ''){
				echo "ocultar_campo(true);";
				
				if($local_IdEstado_2 != ''){
					echo "busca_estado('_EnderecoCobranca', '$local_IdPais_2', '$local_IdEstado_2', '$local_IdCidade_2');";
				} else{
					echo "busca_estado('_EnderecoCobranca', '$local_IdPais_2');";
				}
			} else{
				echo "busca_estado('_EnderecoCobranca', '$local_IdPais_2');";
			}
		?>
		servico_periodicidade_parcelas_visualiza('<?=$local_IdLoja?>',<?=$_POST[IdServico];?>,<?=$_POST[IdPeriodicidade];?>,<?=$_POST[QtdParcela];?>,<?=$_POST[TipoContrato];?>,<?=$_POST[IdLocalCobranca];?>,<?=$_POST[MesFechado];?>,'busca');
		calculaPeriodicidade(document.formulario.IdPeriodicidade.value,document.formulario.ValorServico.value);
		calculaPeriodicidadeTerceiro(document.formulario.IdPeriodicidade.value,document.formulario.IdPeriodicidade.value,document.formulario.ValorPeriodicidadeTerceiro,'<?=$local_IdLoja?>');
		calculaServicoAutomatico(document.formulario.IdPeriodicidade.value);
		document.formulario.QuantParcela.value = document.formulario.QtdParcela.value; 
		busca_servico_tipo_contrato_visualiza(<?=$_POST[IdServico];?>,<?=$_POST[IdPeriodicidade];?>,<?=$_POST[QtdParcela];?>,<?=$_POST[TipoContrato];?>,<?=$_POST[IdLocalCobranca];?>,<?=$_POST[MesFechado];?>,'busca');
	}
	
	function voltar_etapa(url){
		document.formulario.action = url;
		document.formulario.submit();
	}
	
	inicia();
	enterAsTab(document.forms.formulario);
	-->
</script>