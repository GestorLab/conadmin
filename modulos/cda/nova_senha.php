<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('files/funcoes.php');

	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= 'rotinas/autentica_redefinir_senha.php';
	$local_EtapaAnterior	= 'index.php';
	$local_Erro				= $_GET['Erro'];
	$local_EnviaSenha		= $_GET['EnviaSenha'];
		
	$IdLoja					= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************
	
	$sair = 'disabled';
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
	
	if($local_EnviaSenha == md5(1)){
		$local_EnviaSenhaTemp = 1;
		
		if(getParametroSistema(95,2) == 1){
			$local_DescricaoEtapa = getParametroSistema(95,10);
		}else{
			$local_DescricaoEtapa = getParametroSistema(95,14);
		}
		
		if($row > 0){
			$local_MSGDescricao	= getParametroSistema(95,30);
		} else{
			$local_MSGDescricao	= getParametroSistema(95,11);
		}
	}
	
	if($local_EnviaSenha == md5(2)){
		$local_EnviaSenhaTemp = 2;
		$local_DescricaoEtapa = getParametroSistema(95,28);
		
		if($row > 0){
			$local_MSGDescricao	= getParametroSistema(95,31);
		} else{
			$local_MSGDescricao	= getParametroSistema(95,27);
		}
	}
	
	$local_IdLoja			= $_GET['IdLoja'];
	$local_CPF_CNPJ			= formatText($_GET['CPF_CNPJ'],'MA');	
	$local_Senha			= $_GET['Senha'];
	$local_Email			= $_GET['Email'];
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
				        	<td><img src="img/marca_conadmin2.png" width="260" height="50"></td>
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
							   <div style='width:100%; margin:0; padding:0; text-align:center'>
								   <table width="80%" border="0" cellpadding="0" cellspacing="0" id="marcaCliente" align="center">
						       	    	<tr>
						       	    		<td id='marcaCliente_esq'>&nbsp;</td>
						              		<td id='marcaCliente_center' align="center" valign="middle"><img src="../../img/personalizacao/logo_cab.jpg"/></td>
						              		<td id='marcaCliente_dir'>&nbsp;</td>
						            	</tr>
				          	   		</table>
					          	   <BR>
					          	   
					               <?
										if($local_Erro != md5("Email_NULL")) {
											echo"<p>".trim($local_MSGDescricao)."</p>
												<form action='$local_EtapaProxima' name='formulario' method='post' onSubmit='return validarSenha()'>
												   <input type='hidden' name='Local' value='cda'>
												   <input type='hidden' name='Browser' value=''>	
												   <input type='hidden' name='Erro' value='$local_Erro'>
												   <input type='hidden' name='EnviaSenhaNova' value='1'>
												   <input type='hidden' name='EnviaSenha' value='$local_EnviaSenhaTemp'>	
												   <table border='0' cellpadding='0' cellspacing='2' class='txtA' style='margin:20px'>";
											
											if($row > 0){
												$width = "130px";
												echo "
												<tr>
													<td style='text-align:right;'>CPF/CNPJ/Usuário:</td>
													<td>
														<input name='CPF_CNPJ' type='text' autocomplete='off' value='$local_CPF_CNPJ' class='FormPadrao' style='width:$width' maxlength='255' onFocus=\"Foco(this,'in',true)\" onBlur=\"Foco(this,'out')\" onkeyDown='cadastrar(this,event)' tabindex='1' />
														<input type='hidden' name='ValidarCPF_CNPJ' value='0'>
													</td>
												</tr>";
											} else{
												$width = "177px";
												echo "
												<tr>
													<td style='text-align:right;'>CPF/CNPJ:</td>
													<td>
														<input name='CPF_CNPJ' type='text' autocomplete='off' value='$local_CPF_CNPJ' class='FormPadrao' style='width:$width' maxlength='18' onFocus=\"Foco(this,'in',true)\" onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'cpf_cnpj')\" onChange=\"validaCampo(this.value)\" onkeyDown='cadastrar(this,event)' tabindex='1' />
														<input type='hidden' name='ValidarCPF_CNPJ' value='1'>
													</td>
												</tr>";
											}
											
											if($local_EnviaSenha == md5(1)){
												echo "<tr>	
															<td>&nbsp;</td>
															<td><input name='bt_submit' type='submit' class='BotaoPadrao' value='Avançar' tabindex='3'/></td>
														 </tr>";
											}
											
											if($local_EnviaSenha == md5(2)){
												echo "<tr>
															<td style='text-align:right;'>E-mail:</td>
															<td><input name='Email' type='text' autocomplete='off' value='$local_Email' class='FormPadrao' style='width:$width' maxlength='255' onFocus=\"Foco(this,'in',true)\" onBlur=\"Foco(this,'out')\" onChange=\"validaEmail(this.value)\" onkeyDown='cadastrar(this,event)' tabindex='1' /></td>
														</tr>
														<tr>	
															<td>&nbsp;</td>
															<td><input name='bt_submit' type='submit' class='BotaoPadrao' value='Enviar' tabindex='3'/></td>
														 </tr>";
											}
											
											echo "</table>
												</form>";
										} else {
											echo 
											"<p>Não foi possível realizar a operação, pois este usuário não possui nem um e-mail para o envio de redefinição de senha, contacte o suporte para esta auxiliando nesta operação!</p><br />";
										}
									?>
						           	<p style='text-align:right;'><img src="img/bullet_seta_voltar.png" border="0"/> <a href="index.php">Voltar</a></p>
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
	    			<table id="rpL" width="100%" cellspacing="0" cellpadding="0">
		      			<tr>
		        			<td class="borda"><img src="img/rp1.png" /></td>
		        			<td rowspan='2' class="transparente">
								<?
									include("files/rodape.php");
								?>
							</td>
		       				<td class="borda"><img src="img/rp2.png" /></td>
		      			</tr>
						<tr>
							<td class="borda-bottom">&nbsp;</td>
							<td class="borda-bottom">&nbsp;</td>
						</tr>
		   			</table>
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