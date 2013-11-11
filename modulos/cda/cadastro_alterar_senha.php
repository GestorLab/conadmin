<?	
	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= "cadastro_alterar_senha.php";
	$local_EtapaAnterior	= "menu.php";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');	
	include('files/funcoes.php');	
	include('rotinas/verifica.php');
	
	$local_IdLoja	= $_SESSION["IdLoja"];
	$local_IdPessoa	= $_SESSION["IdPessoa"];
	$local_Login	= $_SESSION["Login"];
	
	# Fim Vars Cabeçalho *******************************
	
	$local_DescricaoEtapa	= getParametroSistema(84,21);	
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_Confirmacao	 	= formatText($_POST['Confirmacao'],NULL);
	$local_SenhaAntiga	 	= formatText($_POST['SenhaAntiga'],NULL);
	$local_NovaSenha	 	= formatText($_POST['NovaSenha'],NULL);

	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_alterar_senha.php');
			break;
		default:
			$local_Acao 	= 'alterar';
			break;
	}
	
	$local_Confirmacao 		= "";			
	$local_Password			= "";
	$tamanhomaximo 					= getParametroSistema(95,32);

	if($tamanhomaximo > 255 || $tamanhomaximo == "" || $tamanhomaximo == 0){
		$tamanhomaximo = 255;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<?
			include ("files/header.php");
		?>
	</head>
	<body>
		<div id='container'>
			<?
				include ("files/cabecalho.php");
			?>
			<div id='conteudo'>
				<table style='width:760px'>
					<tr>
						<td><p class='titulo'><B><?=formTexto(getParametroSistema(23,22))?></B></p></td>
						<td style='text-align:right'></td>
					</tr>
				</table>
				<fieldset>
					<legend>Alterar Senha</legend>	
					<form name='formulario' method='post' action='<?=$local_EtapaProxima?>'>
						<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
						<input type='hidden' name='Erro' value=<?=$local_Erro?>>
						<input type='hidden' name='Local' value='Usuario'>
						<div style='width: 580px; margin:auto'>
							<BR>
							<table style='margin-bottom:15px;' cellspacing=0>
								<tr>
									<th><B>Senha Atual</B></td>
									<th><B>Nova Senha</B></td>
									<th><B>Confirme sua Senha</B></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='campo'>
										<input type='password' name='SenhaAntiga' value='' style='width:170px' maxlength='<?=$tamanhomaximo?>' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
									</td>
									<td class='campo'>
										<input type='password' name='NovaSenha' value='' style='width:170px' maxlength='<?=$tamanhomaximo?>' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
									</td>
									<td class='campo'>
										<input type='password' name='Confirmacao' value='' style='width:170px' maxlength='<?=$tamanhomaximo?>' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='3'>
									</td>
								</tr>
							</table>
						</div>
						<table style='margin-left:-5px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td><h2 id='helpText' name='helpText'>&nbsp;</h2></td>
							</tr>
						</table>
					</fieldset>
					<h1 style='background-color:<?=getParametroSistema(84,11)?>'><?=getParametroSistema(84,10)?></h1>	
					<table id='botoes' style='background-color:<?=getParametroSistema(84,11)?>'>
						<tr>
							<td class='voltar' style='width:50%;'>
								<?
									if($local_EtapaAnterior != ""){
										echo "<input type='image' src='img/ico_voltar_text.gif' name='Voltar' onClick=\"mudaAction('$local_EtapaAnterior',true)\" />";
									}
								?>
							</td>
							<td class='proximo'><input type='image' src='img/ico_alterar_text.gif' name='ProximaEtapa' onClick="return mudaAction('<?=$local_EtapaProxima?>',validar())" tabindex='4'/></td>
						</tr>
					</table>			
				</form>
			</div>
			<?
				include("files/rodape.php");
			?>
		</div>	
	</body>
</html>
<script>
	function validar(){
		if(document.formulario.SenhaAntiga.value=='' || document.formulario.NovaSenha.value==''|| document.formulario.Confirmacao.value=='' ){
			mensagens(1);
			if(document.formulario.SenhaAntiga.value==''){
				document.formulario.SenhaAntiga.focus();
			}else if(document.formulario.NovaSenha.value==''){
				document.formulario.NovaSenha.focus();
			}else if(document.formulario.Confirmacao.value==''){
				document.formulario.Confirmacao.focus();	
			}
			return false;
		}else{
			if(document.formulario.NovaSenha.value != document.formulario.Confirmacao.value){
				mensagens(11);
				document.formulario.SenhaAntiga.focus();
				document.formulario.SenhaAntiga.value = "";
				document.formulario.NovaSenha.value = "";
				document.formulario.Confirmacao.value = "";
				return false;
			}
		}
		return true;
	}
	function inicia(){
		document.formulario.SenhaAntiga.focus();
	}
	inicia();
	verificaErro();
</script>
