<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('files/funcoes.php');

	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= 'cadastrar_pessoa.php';
	$local_EtapaAnterior	= 'index.php';
	$local_Erro				= $_GET['Erro'];
	$IdLoja					= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************
	
	$sair = 'disabled';
	
	$local_DescricaoEtapa	= getParametroSistema(95,14);
	$local_MSGDescricao		= getParametroSistema(95,11);
	
	$local_IdLoja			= $_GET['IdLoja'];
	$local_CPF_CNPJ			= formatText($_GET['CPF_CNPJ'],'MA');	
	$local_Senha			= $_GET['Senha'];
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
					          	   <p><?=trim($local_MSGDescricao)?></p>
					               <form action='<?=$local_EtapaProxima?>' name='formulario' method='post' onSubmit='return validarSenha()'>
									   <input type='hidden' name='Local' value='cda'>
									   <input type='hidden' name='Browser' value=''>	
									   <input type='hidden' name='CPF_CNPJ_duplicado' value='0'>	
									   <input type='hidden' name='Erro' value='<?=$local_Erro?>'>	
									   <table border="0" cellpadding="0" cellspacing="2" class="txtA" style="margin:20px">
						              		<tr>
						                		<td>CPF/CNPJ:</td>
						                		<td>
													<input name="CPF_CNPJ" type="text" autocomplete="off" value='<?=$local_CPF_CNPJ?>' class="FormPadrao" style='width:180px' maxlength='18' onFocus="Foco(this,'in',true)" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'cpf_cnpj')" onChange="/*validaCampo(this.value);*/verificar_CPF_CNPJ(this.value);" onkeyDown='cadastrar(this,event)' tabindex='1' />
													<input type='hidden' name='ValidarCPF_CNPJ' value='1'>
												</td>
						              		</tr>
											<tr>	
						               			<td>&nbsp;</td>
						               			<td><input name="bt_submit" type="submit" class="BotaoPadrao" value="Avançar" tabindex='3'/></td>
						             		 </tr>
						           		</table>
						           	</form>
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