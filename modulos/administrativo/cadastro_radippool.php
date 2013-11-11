<?
	$localModulo		= 1;
	$localOperacao		= 10000;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdRadIdPool		= $_POST['IdRadIdPool'];
	$local_PoolName			= $_POST['PoolName'];
	$local_FrameIpAddress	= $_POST['FrameIpAddress'];
	$local_NasIpAddress		= $_POST['NasIpAddress'];
	
	
	if($_GET['id'] != ''){
		$local_IdRadIdPool = $_GET['id'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_radippool.php');
			break;		
		case 'alterar':
			include('files/editar/editar_radippool.php');
			break;
		default:
			$local_Acao = 'inserir';
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
	    <link rel='stylesheet' type='text/css' href='../../classes/calendar/calendar-blue.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>		
		<script type='text/javascript' src='js/radippool_default.js'></script>
		<script type='text/javascript' src='js/radippool.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Rad Ip Pool')">
		<? include('filtro_radippool.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_radippool.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='Radippool'>
				<input type='hidden' name='id' value='<?=$local_IdRadIdPool?>'>
				<div>
					<table border="0">
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Rad Ip Pool</td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type="text" name="IdRadIdPool" onchange="busca_radippool(this.value)" onkeypress="mascara(this,event,'int')" maxlength="12" style="width: 70px;" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='1' />
							</td>							
						</tr>
					</table>
					<div class="cp_tit">Dados Rad Id Pool</div>
					<table border="0">
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Pool Name</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Framed Ip Address</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Nas Ip Address</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>								
								<input type='text' name='PoolName' style='width:200px' value='' maxlength='253' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='FrameIpAddress' style='width:200px' value='' maxlength='253' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='3'>
							</td>						
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NasIpAddress' style='width:200px' value='' maxlength='253' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='8'>
							</td>
						</tr>
					</table>
				</div>				
				<div class='cp_botao'>
					<table style='float:right; margin-right:5px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='9' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='10' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='11' onClick="excluir(document.formulario.IdRadIdPool.value)">
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>				
			</form>
		</div>
	</body>
</html>
<script type='text/javascript'> 
<?php
	if($local_IdRadIdPool != ''){
		echo "busca_radippool($local_IdRadIdPool );";
	}
?>
	verificaAcao();
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>