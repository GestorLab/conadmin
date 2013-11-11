<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('files/funcoes.php');	
	
	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= 'rotinas/autentica.php';
	$local_Erro				= $_GET['Erro'];
	$local_Pessoa			= $_GET['Pessoa'];
	$IdLoja					= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************

	if(file_exists("../../atualizacao") && $local_Erro == ''){
		header("Location: atualizacao.php");
	}
	
	$local_DescricaoEtapa	= getParametroSistema(95,9);
	$Perfil = logoPerfil();
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
	</head>
	<body>
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
		    	<td align="center">
					<table width="612" border="0" cellpadding="0" cellspacing="0">
				      	<tr>
				        	<td><img src="img/marca_conadmin3.png" width="260" height="50"></td>
				        	<td width="2"><img src="img/_Espaco.gif" height="2"></td>
				        	<td>
								<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
				          			<tr>
				            			<td width="15"><img src="img/lgr1.png" width="15" height="50" /></td>
				            			<td id="titl"><h1><img src="img/ico_cadeado.png" width="24" height="33" /> <?=$local_DescricaoEtapa?></h1></td>
				            			<td width="15"><img src="img/lgr2.png" width="15" height="50" /></td>
				          			</tr>
				        		</table>
							</td>
				      	</tr>
					    <tr valign="top">
					        <td id="coluna1main" style='width:200px;'>
								<? include("./files/indice.php"); ?>
						   </td>
					       <td><img src="img/_Espaco.gif" height="2" width="15">&nbsp;</td>
					       <td id="colunaLmain" class="txtA" align="center">
							   <div class="txtA" style='width:100%; margin:0; padding:0; text-align:center;'>
							   		<table width="80%" border="0" cellpadding="0" cellspacing="0" id="marcaCliente" align="center">
						       	    	<tr>
						       	    		<td id='marcaCliente_esq'>&nbsp;</td>
						              		<td id='marcaCliente_center' align="center" valign="middle"><img src="<?=$Perfil[UrlLogoGIF]?>"/></td>
						              		<td id='marcaCliente_dir'>&nbsp;</td>
						            	</tr>
				          	   		</table>
				          	   		<BR>
				          	   		<form action='<?=$local_EtapaProxima?>' name='formulario' method='post' onSubmit='return validar()'>
									   <input type='hidden' name='Local' value='cda'>
									   <input type='hidden' name='Browser' value=''>	
									   <input type='hidden' name='ExigirSenha' value='<?=getParametroSistema(95,2)?>'>	
									   <input type='hidden' name='Erro' value='<?=$local_Erro?>'>	
									   <table border="0" cellpadding="0" cellspacing="2" class="txtA" style="margin:20px 24px 20px 24px">
						              		<?
												if($local_Pessoa != ''){
													$where = " md5(CPF_CNPJ) = '$local_Pessoa'";
													
													if(getParametroSistema(95,2) == 1) {
														$where = " md5(concat(CPF_CNPJ,Senha)) = '$local_Pessoa'";
													}
													
													$option = "<option value=''></option>";
													$sql = "select 
																IdPessoa,
																md5(concat(IdPessoa, CPF_CNPJ, Senha)) Pessoa,
																TipoPessoa,
																Nome,
																RazaoSocial
															from 
																Pessoa 
															where 
																$where;";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														$option .= "<option value='$lin[Pessoa]'>[$lin[IdPessoa]] ";
														
														if($lin[TipoPessoa] == '2'){
															$nome = true;
															$option .= "$lin[Nome]";
														} else{
															$nome = false;
															$option .= "$lin[RazaoSocial]";
														}
														
														$option .= "</option>";
													}
													
													if($nome){
														echo"
														<tr>
															<td>Nome Pessoa</td>
														</tr>";
													} else{
														echo"
														<tr>
															<td>Razão Social</td>
														</tr>";
													}
													
													echo"
													<tr>
														<td>
															<select name='Pessoa' style='width:234px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" onKeyDown='listar(event)'>
																$option
															</select>
														</td>
													</tr>
													<tr>
														<td><input name='bt_avancar' type='submit' class='BotaoPadrao' value='Avançar' tabindex='3'/></td>
													</tr>";
												} else{
													$sql = "select 
																ServicoParametro.AcessoCDA,
																ContratoParametro.Valor
															from
																Pessoa,
																Contrato,
																ContratoParametro,
																ServicoParametro 
															where 
																Pessoa.IdPessoa = Contrato.IdPessoa and
																Contrato.IdLoja = $IdLoja and 
																Contrato.IdLoja = ContratoParametro.IdLoja and 
																Contrato.IdContrato = ContratoParametro.IdContrato and 
																Contrato.IdServico = ContratoParametro.IdServico and 
																ContratoParametro.IdLoja = ServicoParametro.IdLoja and 
																ContratoParametro.IdServico = ServicoParametro.IdServico and 
																ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and 
																ServicoParametro.AcessoCDA = 1;";
													$res = mysql_query($sql,$con);
													$row = mysql_num_rows($res);
													
													if($row > 0){
														$width = "130px";
														echo "
														<tr>
															<td style='text-align:right;'>CPF/CNPJ/Usuário:</td>
															<td>
																<input name='CPF_CNPJ' type='text' autocomplete='off'  class='FormPadrao' style='width:$width' maxlength='255' onFocus=\"Foco(this,'in',true)\"  onBlur=\"Foco(this,'out')\" onkeyDown='cadastrar(this,event)' tabindex='1' />
																<input type='hidden' name='ValidarCPF_CNPJ' value='0'>
															</td>
														</tr>";
													} else{
														$width = "177px";
														echo "
														<tr>
															<td style='text-align:right;'>CPF/CNPJ:</td>
															<td>
																<input name='CPF_CNPJ' type='text' autocomplete='off'  class='FormPadrao' style='width:$width' maxlength='18' onFocus=\"Foco(this,'in',true)\"  onBlur=\"Foco(this,'out')\" onChange=\"validaCampo(this.value)\" onkeyDown='cadastrar(this,event)' tabindex='1' />
																<input type='hidden' name='ValidarCPF_CNPJ' value='1'>
															</td>
														</tr>";
													}
													
													if(getParametroSistema(95,2) == 1){ 
														echo"
														<tr>
															<td style='text-align:right;'>Senha:</td>
															<td><input name='Senha' type='password' autocomplete='off' class='FormPadrao' style='width:$width' maxlength='".$tamanhomaximo."' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\"  onkeyDown='cadastrar(this,event)' tabindex='2' /></td>
														</tr>";
													}
													
													echo"
													<tr>	
														<td>&nbsp;</td>
														<td><input name='bt_submit' type='submit' class='BotaoPadrao' value='Entrar' tabindex='3'/></td>
													 </tr>";
												}
						              		?>
						           		</table>
						           	</form>
						  			<?
										echo"<div><strong><img src='img/bullet_seta.png' bordar='0' /> <a href='nova_senha.php?EnviaSenha=".md5(2)."'>Esqueceu sua senha?</a></strong></div>";
										
								  		if(getParametroSistema(95,2) == 1 && $local_Pessoa == ''){ 
								  			echo"<div><strong><img src='img/bullet_seta.png' border='0' /> <a href='nova_senha.php?EnviaSenha=".md5(1)."'>N&atilde;o tenho senha</a></strong></div>";
								  		}
										
							  			if(getParametroSistema(95,8) == 1 && $local_Pessoa == ''){ 
										  	echo"<div><strong><img src='img/bullet_seta.png' border='0' /> Não sou cliente | <a href='cadastrar.php'>Cadastre-se</a></strong></div>";
								  		}
									?>
									<BR>
								</div>
							</td>
						</tr>
					    <tr>
					       <td>
						   		<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
					        	    <tr>
					            		<td width="15"><img src="img/lrp1.png" width="15" height="15" /></td>
					             		<td class="lrp"><img src="img/_Espaco.gif" /></td>
					              		<td width="15"><img src="img/lrp2.png" width="15" height="15" /></td>
					            	</tr>
					        	</table>
							</td>
					        <td><img src="img/_Espaco.gif" height="2"></td>
					        <td>
								<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
					          		<tr>
					            		<td width="15"><img src="img/lrp1.png" width="15" height="15" /></td>
					            		<td class="lrp"><img src="img/_Espaco.gif" /></td>
					            		<td width="15"><img src="img/lrp2.png" width="15" height="15" /></td>
					          		</tr>
					        	</table>
							</td>
			      		</tr>
			    	</table>
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
<script>
	inicia();
	mensagem(<?=$local_Erro?>);
	enterAsTab(document.forms.formulario);
</script>
