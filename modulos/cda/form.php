<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	
	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= getParametroSistema(6,3).'/modulos/cda/rotinas/autentica.php';
	$IdLoja					= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************
?>
<html>
	<body>
		<form action='<?=$local_EtapaProxima?>' name='formulario' method='post'>
		   <input type='hidden' name='Local' value='cda'>
		   <input type='hidden' name='Browser' value=''>	
		   <input type='hidden' name='ExigirSenha' value='<?=getParametroSistema(95,2)?>'>	
		   <input type='hidden' name='Erro' value='<?=$local_Erro?>'>	
		   CPF/CNPJ: <input name="CPF_CNPJ" type="text" autocomplete="off"  class="FormPadrao" style='width:180px' maxlength='18' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validaCampo(this.value)" onkeyDown='cadastrar(this,event)' tabindex='1' /><br>
		   <?
				if(getParametroSistema(95,2) == 1){ 
					echo"<br>Senha: <input name='Senha' type='password' autocomplete='off' class='FormPadrao' style='width:180px' maxlength='12' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\"  onkeyDown='cadastrar(this,event)' tabindex='2' />";
				}
		   ?>
			<input name="bt_submit" type="submit" class="BotaoPadrao" value="Entrar" tabindex='3'/>
		</form>
	</body>
</html>