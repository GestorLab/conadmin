<?
	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Acao 					= $_POST['Acao'];
	$local_Erro						= $_GET['Erro'];
	$local_IdContaSMS				= $_GET['IdContaSMS'];
	$local_IdOperadora				= $_GET['IdOperadora'];
	$local_ExecutarViaAjax			= '';
	
	if($local_IdContaSMS != ''){
		$sql = "select
					Pessoa.Celular
				from
					Usuario,
					Pessoa
				where
					Usuario.Login = '$local_Login' and
					Usuario.IdPessoa = Pessoa.IdPessoa;";
					
		$local_ExecutarViaAjax = "rotinas/enviar_sms.php?IdContaSMS=".$local_IdContaSMS."&IdOperadora=".$local_IdOperadora;
	}
	
	if($sql != ''){
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$Celular = $lin[Celular];
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/val_email.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/enviar_sms.js'></script>
	    <style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Enviar SMS')">
		<? include('filtro_reenvio_mensagem.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo' style='margin:0'>
			<form name='formulario' method='post' action='' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='EnviarSMS'>
				<input type='hidden' name='ExecutarViaAjax' value='<?=$local_ExecutarViaAjax?>'>
				<input type='hidden' name='HabilitarMascaraFone' value='<?=getCodigoInterno(3,215)?>'>
				<div id='cp_tit' style='margin-top:0'>Enviar SMS</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B>Para</B></td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='sms' style='width:300px' value='<?=$Celular?>' maxlength='14' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyPress="mascara_fone(this,event)" onChange="" tabindex='1'>
							<p style="margin:1px 0px 0px 3px">(99)9999-9999</p>
						</td>
					</tr>
				</table>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_voltar' value='Voltar' class='botao' tabindex='2' onClick='parent.history.go(-1)'>
							</td>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Enviar' class='botao' tabindex='3' onClick='cadastrar()'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 style='margin-bottom:0' id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</body>
</html>
<script>
	inicia();
	verificaAcao();
	enterAsTab(document.forms.formulario);
</script>