<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('files/funcoes.php');
	
	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= 'listar_plano_disponivel.php';
	$local_EtapaAnterior	= 'index.php';
	$local_Erro				= $_GET['Erro'];
	$local_IdLoja			= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************
	
	$_SESSION["IdLoja"]		= $local_IdLoja;
	$_SESSION["IdLojaCDA"]	= $local_IdLoja;
	$local_MSGDescricao		= getParametroSistema(95,11);
	$local_CPF_CNPJ			= formatText($_POST['CPF_CNPJ'],'MA');
	$local_TipoPessoa		= tipo_pessoa($local_CPF_CNPJ);	
	$local_IdPais			= getCodigoInternoCDA(3,1);	
	$local_IdEstado			= getCodigoInternoCDA(3,2);	
	$local_IdCidade			= getCodigoInternoCDA(3,3);	
	$local_Erro				= $_GET['Erro'];
	$tamanhomaximo 			= getParametroSistema(95,32);

	if($tamanhomaximo > 255 || $tamanhomaximo == "" || $tamanhomaximo == 0){
		$tamanhomaximo = 255;
	}
?>
<html>
	<head>
		<?
			include ("files/header.php");
		?>
		
		<script type="text/javascript" src="./js/pessoa.js"></script>	
		<script type="text/javascript" src="../../js/val_ie.js"></script>	
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
										<td id='tit'><h1><img src="./img/icones/7.png" /> <?=getParametroSistema(95,24)?></h1></td>
										<td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
									</tr>
								</table>
								<table width="640" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td colspan='3' class='coluna2main'>
											<form name='formulario' method='post' action='<?=$local_EtapaProxima?>' onSubmit='return validar()'>
												<input type='hidden' name='Telefone_Obrigatorio' value='<?=getCodigoInternoCDA(11,3)?>'>
												<input type='hidden' name='Senha_Obrigatorio' value='<?=getParametroSistema(95,2)?>'>
												<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
												<input type='hidden' name='TipoPessoa' value='<?=$local_TipoPessoa?>'>
												<input type='hidden' name='CPF_CNPJ' value='<?=$local_CPF_CNPJ?>'>
												<input type='hidden' name='IdLoja' value='<?=$local_IdLoja?>'>
												<input type='hidden' name='Login' value='cda'>
												<input type='hidden' name='Obs' value=''>
												<input type='hidden' name='AgruparContratos' value='<?=getCodigoInternoCDA(3,7)?>'>
												<input type='hidden' name='Cob_CobrarDespesaBoleto' value='<?=getCodigoInternoCDA(3,26)?>'>
												<input type='hidden' name='IdGrupoPessoa' value='<?=getCodigoInternoCDA(3,71)?>'>
												<input type='hidden' name='IdMonitorFinanceiro' value='<?=getCodigoInternoCDA(3,155)?>'>
												<!--input type='hidden' name='DataNascimento_Obrigatorio' value='<?=getCodigoInternoCDA(11,6)?>'-->
												<input type='hidden' name='Numero_Obrigatorio' value='<?=getCodigoInternoCDA(11,7)?>'>
												<input type='hidden' name='Endereco_Length' value='<?=getCodigoInterno(7,11)?>'>
												<input type='hidden' name='TipoUsuario' value=''>
												<input type='hidden' name='TipoAgenteAutorizado' value=''>
												<input type='hidden' name='TipoFornecedor' value=''>
												<input type='hidden' name='TipoVendedor' value=''>
												<input type='hidden' name='QtdEndereco' value='2'>
												<input type='hidden' name='DescricaoEndereco_1' value='Endereço Principal'>
												<input type='hidden' name='IdPais_1' value='<?=$local_IdPais?>'>
												<input type='hidden' name='SiglaEstado' value=''>
												<input type='hidden' name='DataNascimento_Obrigatorio' value='<?=getCodigoInternoCDA(11,20)?>'>
												<input type='hidden' name='Sexo_Obrigatorio' value='<?=getCodigoInternoCDA(11,19)?>'>
												<input type='hidden' name='EstadoCivil_Obrigatorio' value='<?=getCodigoInternoCDA(11,21)?>'>
												<input type='hidden' name='Rg_Obrigatorio' value='<?=getCodigoInternoCDA(11,22)?>'>
												<input type='hidden' name='NomeMae_Obrigatorio' value='<?=getCodigoInternoCDA(11,15)?>'>
												<input type='hidden' name='NomePai_Obrigatorio' value='<?=getCodigoInternoCDA(11,14)?>'>
												<input type='hidden' name='NomeConjugue_Obrigatorio' value='<?=getCodigoInternoCDA(11,13)?>'>
												<input type='hidden' name='VisivelNomeConjugue' value='2'>
												
												<?
													if(getCodigoInternoCDA(3,32) == '1'){
														echo "<input type='hidden' name='Cob_FormaCorreio' value='S'>";
													}
													
													if(getCodigoInternoCDA(3,33) == '1'){
														echo "<input type='hidden' name='Cob_FormaEmail' value='S'>";
													}
													
													if(getCodigoInternoCDA(3,34) == '1'){
														echo "<input type='hidden' name='Cob_FormaOutro' value='S'>";
													}
													
													$local_OcultarEnderecoCobranca = '';
													
													foreach($_POST as $key => $value){
														if(substr($key,0,3) != "bt_"){
															if($key == "OcultarEnderecoCobranca"){
																$local_OcultarEnderecoCobranca = "checked";
															} else{
																if(substr($key,0,10) == "CampoExtra" && substr($key,11,11) != "Obrigatorio"){
																	$var = "\$local_CampoExtra[".substr($key,10,1)."]";
																} else{
																	$var = "\$local_".$key;
																}
																
																eval($var." = '".$value."';");
															}
														}
													}
													
													$local_DataNascimento = dataConv(formatText($local_DataNascimento,NULL), "Y-m-d", "d/m/Y");
													
													if($local_TipoPessoa == 1){ //Juridica
														echo"
														<table>
															<tr>
																<td class='title'><B>Nome Fantasia</B></td>
																<td class='sep' />
																<td class='title'><B>Razão Social</B></td>
															</tr>
															<tr>	
																<td>
																	<input class='FormPadrao' type='text' name='Nome' value='$local_Nome' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='RazaoSocial' value='$local_RazaoSocial' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2'>
																</td>
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'><B>Nome Representante</B></td>
																<td class='sep' />
																<td class='title'>Inscrição Estadual</td>
																<td class='sep' />
																<td class='title'>Inscrição Municipal</td>
																<td class='sep' />
																<td class='title'>Data Fundação</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='NomeRepresentante' value='$local_NomeRepresentante' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='3'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='RG_IE' value='$local_RG_IE' maxlength='20' style='width:98px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='4'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='InscricaoMunicipal' value='$local_InscricaoMunicipal' maxlength='30' style='width:98px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='5'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='DataNascimento' value='$local_DataNascimento' style='width:80px' maxlength='10' onFocus=\"Foco(this,'in',true)\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'date')\" tabindex='6'>
																</td>
															</tr>
														</table>
														";
													} else{ //Fisica
														echo"
														<table>
															<tr>
																<td class='title'><B>Nome Pessoa</B></td>
																<td class='sep' />";

																if(getCodigoInterno(11,19) == 1){
																	echo "<td class='title'><B>Sexo</B></td>";
																}else{
																	echo "<td class='title'>Sexo</td>";
																}

														echo"	<td class='sep' />
																<td class='title'>";
														
														if(getCodigoInternoCDA(11,20) == 1){
															echo "<b>Data Nasc.</b>";
														} else{
															echo "Data Nasc.";
														}
														
														echo "
																</td>
																<td class='sep' />";
																if(getCodigoInternoCDA(11,21) == 1){
																	echo "<td class='title'><B>Estado Civil</B></td>";
																}else{
																	echo "<td class='title'>Estado Civil</td>";
																}
														echo "
																<td class='sep' />";
																if(getCodigoInternoCDA(11,22) == 1){
																	echo "<td class='title'><B>RG</B></td>";
																}else{
																	echo "<td class='title'>RG</td>";
																}
														echo"	</td>
																<td class='sep' />
																<td class='title' id='labelOrgaoExpedidor'>Orgão Exp.</td>
															</tr>
															<tr>	
																<td>
																	<input class='FormPadrao' type='text' name='Nome' value='$local_Nome' style='width:190px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='7'>
																</td>
																<td class='sep' />
																<td>
																	<select class='FormPadrao' name='Sexo' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='8' style='width: 55px'>
																		<option value='' selected></option>";
																			$sql2 = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=8 order by ValorParametroSistema";
																			$res2 = @mysql_query($sql2,$con);
																			while($lin2 = @mysql_fetch_array($res2)){
																				echo"<option value='$lin2[IdParametroSistema]' ".compara($local_Sexo,$lin2[IdParametroSistema],"selected='selected'","").">$lin2[ValorParametroSistema]</option>";
																			}
																	echo"
																	</select>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='DataNascimento' value='$local_DataNascimento' style='width:80px' maxlength='10' onFocus=\"Foco(this,'in',true)\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'date')\" tabindex='9'>
																</td>
																<td class='sep' />
																<td>
																	<select class='FormPadrao' name='EstadoCivil' style='width:80px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" Onchange='visualizarConjugue(this.value)' tabindex='10'>
																		<option value='' selected></option>";
																			$sql2 = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=9 order by ValorParametroSistema";
																			$res2 = @mysql_query($sql2,$con);
																			while($lin2 = @mysql_fetch_array($res2)){
																				echo"<option value='$lin2[IdParametroSistema]' ".compara($local_EstadoCivil,$lin2[IdParametroSistema],"selected='selected'","").">$lin2[ValorParametroSistema]</option>";
																			}
																		echo"
																	</select>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='RG_IE' value='$local_RG_IE' maxlength='30' style='width:90px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onchange='obrigatoriedadeOrgaoExpedidor()' tabindex='11'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='OrgaoExpedidor' value='$local_OrgaoExpedidor' style='width:56px' maxlength='30' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='12'>
																</td>
															</tr>
														</table>	
														";

														

														echo"
														<table>
															<tr>
																<td class='title'>";
																if(getCodigoInternoCDA(11,15) == 1){
																	echo "<b>Nome Mãe</b>";
																} else{
																	echo "Nome Mãe";
																}
														echo"	</td>
																<td class='sep' />
																<td class='title'>";
																if(getCodigoInternoCDA(11,14) == 1){
																	echo "<b>Nome Pai</b>";
																} else{
																	echo "Nome Pai";
																}		
																
														echo"   </td>";
														
														echo "<td class='sep' /><td class='title' id='labelNomeConjugue' style='display: none'><B>Nome Conjugue</B></td>";
														echo"
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='NomeMae' value='$local_NomeMae' style='width:295px' maxlength='100' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='13'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='NomePai' value='$local_NomePai' style='width:296px' maxlength='100' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='14'>
																</td>																
																<td class='sep' />
																<td>
																	<input class='FormPadrao' style='display: none;width: 200px' type='text' name='NomeConjugue' value='$local_NomeConjugue' maxlength='18' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='15'>
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
																	<input class='FormPadrao' type='text' name='Telefone1' value='$local_Telefone1' style='width:143px' maxlength='18' onkeypress=\"mascara(this, event, 'fone');\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='16'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Telefone2' value='$local_Telefone2' style='width:143px' maxlength='18' onkeypress=\"mascara(this, event, 'fone');\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='17'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Telefone3' value='$local_Telefone3' style='width:143px' maxlength='18' onkeypress=\"mascara(this, event, 'fone');\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='18'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Celular' value='$local_Celular' style='width:143px' maxlength='18' onkeypress=\"mascara(this, event, 'fone');\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='19'>
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
																	<input class='FormPadrao' type='text' name='Fax' value='$local_Fax' style='width:143px' maxlength='18' onkeypress=\"mascara(this, event, 'fone');\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='20'>
																</td>	
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='ComplementoTelefone' value='$local_ComplementoTelefone' style='width:143px' maxlength='30' onkeypress=\"mascara(this, event, 'fone');\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='21'>
																</td>
																<td style='width:320px;' />
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'><B id='Email'>E-mail</B></td>
																<td class='sep' />
																<td class='sep' />
																<td class='title'>Site (url)</td>
																<td class='sep' />
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='Email' value='$local_Email' style='width:277px' maxlength='255' autocomplete=\"off\" onFocus=\"Foco(this,'in',true)\" onBlur=\"Foco(this,'out'); validar_Email(this.value,'Email')\" tabindex='22'>
																</td>
																<td class='sep' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Site' value='".($local_Site != '' ? $local_Site : "http://")."' style='width:273px' maxlength='100' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='23'>
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
														$TabIndex = 23;
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
															
															echo "<input type='hidden' name='CampoExtra".$IdCampo."Obrigatorio' value ='$lin3[ValorParametroSistema]'>";
															
															if($lin3[ValorParametroSistema]=='S'){
																echo "<td class='title'><B id='titCampoExtra".$IdCampo."'>$lin2[ValorParametroSistema]</B></td>";
															} else{
																echo "<td class='title'><span id='titCampoExtra".$IdCampo."'>$lin2[ValorParametroSistema]</span></td>";
															}
															
															$campo .= "<td><input class='FormPadrao' type='text' name='CampoExtra".$IdCampo."' value='".$local_CampoExtra[$IdCampo]."' style='width:294px' maxlength='255' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='".($TabIndex++)."'></td>";
															
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
																		<input class='FormPadrao' type='text' name='NomeResponsavelEndereco_EnderecoPrincipal' value='$local_NomeResponsavelEndereco_1' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='24'>
																	</td>
																</tr>
															</table>";
													}
													echo "
														<table>
															<tr>
																<td class='title'><B>CEP</B></td>
																<td class='sep' />
																<td class='title'><B>Endereço</B></td>
																<td class='sep' />
																<td class='title'>";
													
													if(getCodigoInternoCDA(11,7) == 1){
														echo "<b>Nº</b>";
													} else{
														echo "N°";
													}
													
													echo "</td>	
																<td class='sep' />
																<td class='title'>Complemento</td>
																<td class='sep' />	
																<td class='title'><B>Bairro</B></td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='CEP_EnderecoPrincipal' value='$local_CEP_1' style='width:70px' maxlength='9' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'cep')\" onChange=\"busca_cep('_EnderecoPrincipal', this.value)\" tabindex='25'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Endereco_EnderecoPrincipal' value='$local_Endereco_1' style='width:195px' maxlength='60' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='26'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Numero_EnderecoPrincipal' value='$local_Numero_1' style='width:55px' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='27'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Complemento_EnderecoPrincipal' value='$local_Complemento_1' style='width:122px' maxlength='30' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='28'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Bairro_EnderecoPrincipal' value='$local_Bairro_1' style='width:122px' maxlength='30' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='29'>
																</td>
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'><B>Estado</B></td>
																<td class='sep' />
																<td class='title'><B>Cidade</B></td>
															</tr>
															<tr>
																<td>
																	<select class='FormPadrao' name='IdEstado_EnderecoPrincipal' style='width:186px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange='busca_cidade(\"_EnderecoPrincipal\", $local_IdPais, this.value)' tabindex='30'>
																		<option value='' selected></option>
																	</select>
																</td>
																<td class='sep' />
																<td>
																	<select class='FormPadrao' name='IdCidade_EnderecoPrincipal' style='width:294px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='31'>
																		<option value='' selected></option>
																	</select>
																</td>
															</tr>
														</table>
														<div style='padding:4px 0 4px 2px;'>
															<table cellspacing='0' cellpading='0' style='width:300px; border:1px solid #A4A4A4;'>
																<tr>
																	<td style='padding:2px 0 1px 2px;'><input type='checkbox' name='OcultarEnderecoCobranca' value='' onClick='ocultar_campo(this.checked);' tabindex='32' $local_OcultarEnderecoCobranca /></td>
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
																		<input class='FormPadrao' type='text' name='NomeResponsavelEndereco_EnderecoCobranca' value='$local_NomeResponsavelEndereco_2' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='33'>
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
																		<input class='FormPadrao' type='text' name='CEP_EnderecoCobranca' value='$local_CEP_2' style='width:70px' maxlength='9' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'cep')\" onChange=\"busca_cep('_EnderecoCobranca', this.value)\" tabindex='34'>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' name='Endereco_EnderecoCobranca' value='$local_Endereco_2' style='width:195px' maxlength='60' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='35'>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' name='Numero_EnderecoCobranca' value='$local_Numero_2' style='width:55px' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='36'>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' name='Complemento_EnderecoCobranca' value='$local_Complemento_2' style='width:122px' maxlength='30' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='37'>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='text' name='Bairro_EnderecoCobranca' value='$local_Bairro_2' style='width:122px' maxlength='30' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='38'>
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
																		<select class='FormPadrao' name='IdEstado_EnderecoCobranca' style='width:186px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange='busca_cidade(\"_EnderecoCobranca\", $local_IdPais, this.value)' tabindex='39'>
																			<option value='' selected></option>
																		</select>
																	</td>
																	<td class='sep' />
																	<td>
																		<select class='FormPadrao' name='IdCidade_EnderecoCobranca' style='width:294px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='40'>
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
																	<td class='title'><B>Senha</B></td>
																	<td class='sep' />
																	<td class='title'><B>Confirme sua Senha</B></td>
																</tr>
																<tr>
																	<td>
																		<input class='FormPadrao' type='password' name='Senha' value='$local_Senha' style='width:142px' maxlength='".$tamanhomaximo."' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='101'>
																	</td>
																	<td class='sep' />
																	<td>
																		<input class='FormPadrao' type='password' name='Confirmacao' value='$local_Senha' style='width:142px' maxlength='".$tamanhomaximo."' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" tabindex='102'>
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
																	<td><textarea name='Obs' style='width: 600px;height: 90px'>$local_Obs</textarea></td>
																</tr>
															</table>
														";
												?>
												<table style="width:100%;">
													<tr>
														<td><input type="button" class="BotaoPadrao" value="Voltar" tabindex='104' onClick="goToURL('<?=$local_EtapaAnterior?>')"/></td>
														<td style='text-align:right;'><input name="bt_submit" type="submit" class="BotaoPadrao" value="Próxima Etapa" tabindex='103'/></td>
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
		echo "busca_estado('_EnderecoPrincipal', '$local_IdPais');";
	}
	
	if($local_OcultarEnderecoCobranca != ''){
		echo "ocultar_campo(true);";
		
		if($local_IdEstado_2 != ''){
			echo "busca_estado('_EnderecoCobranca', '$local_IdPais_2', '$local_IdEstado_2', '$local_IdCidade_2');";
		} else{
			echo "busca_estado('_EnderecoCobranca', '$local_IdPais');";
		}
	} else{
		echo "ocultar_campo(false);";
	}
?>
	}
	
	inicia();
	
	enterAsTab(document.forms.formulario);
	document.formulario.Endereco_EnderecoPrincipal.maxLength = tam_endereco();
	document.formulario.Endereco_EnderecoCobranca.maxLength = tam_endereco();
	
	if(document.formulario.RG_IE.value != "" && document.formulario.OrgaoExpedidor.value == ""){
		obrigatoriedadeOrgaoExpedidor();
	}
	if(document.formulario.EstadoCivil.value == 2){
		visualizarConjugue(document.formulario.EstadoCivil.value);
	}
	-->
</script>