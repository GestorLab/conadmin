<?
	$localModulo		=	1;
	$localOperacao		=	205;
	$localSuboperacao	=	"V";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	
	$local_Acao 		= $_POST['Acao'];
	$local_Acao 		= $_POST['Acao'];	
	$local_Erro			= $_GET['Erro'];
	$Local				= "formulario";
	
	$local_IdCaboTipo							=	formatText($_POST['IdCaboTipo'],NULL);
	$local_Descricao							= 	formatText($_POST['Descricao'],NULL);
	$local_SiglaCaboTipo						= 	formatText($_POST['SiglaCaboTipo'],NULL);

	if($_GET['IdCaboTipo'] != ''){
		$local_IdCaboTipo	= $_GET['IdCaboTipo'];	
	}
	

	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_tipo_cabo.php');
			break;
		case 'alterar':
			include('files/editar/editar_tipo_cabo.php');
			break;
		default:
			$local_Acao = 'inserir';
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>		
		<script type = 'text/javascript' src = 'js/funcoes.js'></script>		
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/cabo_tipo.js'></script>
		<script type = 'text/javascript' src = 'js/cabo_tipo_default.js'></script>		
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Tipo Cabo')">
		<? include('filtro_tipo_cabo.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_tipo_cabo.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='cabo'>
				<div>
					<table >
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'>Cabo</td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' >
								<input type='text' name='IdCaboTipo' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_cabo_tipo(this.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100'>
							</td>
						</tr>
					</table>
						
					<div id='cp_tit'>Dados Tipo Cabo</div>
					
					<table border="0">
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Descrição</B></td>								
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Sigla Tipo Cabo</B></td>								
						</tr>
						<tr>
							<td class='find'></td>	
							<td class='descCampo'><input  type='text' name='Descricao' value="" style='width:331px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='102'></td>							
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><input  type='text' name='SiglaCaboTipo' value="" style='width:100px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='102'></td>	
						</tr>
						
					</table>				
					
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Log</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(132)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(133)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(93)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(135)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='102' onClick='cadastrar()' disabled />
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' 	class='botao' tabindex='103' onClick='cadastrar()' />
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='104' onClick="excluir(document.formulario.IdCaboTipo.value,'Cadastro')" />
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
<script>
	<?php
		if($local_IdCaboTipo != ""){
			echo "busca_cabo_tipo($local_IdCaboTipo);";
		}
	?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>