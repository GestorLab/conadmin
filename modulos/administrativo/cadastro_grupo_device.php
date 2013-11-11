<?
$localModulo		=	1;
$localOperacao		=	12;
$localSuboperacao	=	"V";	

include ('../../files/conecta.php');
include ('../../files/funcoes.php');
include ('../../rotinas/verifica.php');

$DisponivelContrato = array(
		1 => 'Sim',
		2 => 'Não'
);
//echo key($_GET);die;
//print_r($_GET);die;
if($_GET){
	$data = json_decode(key($_GET), true);
	//print_r($data);die;
}

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<!--<script src="jquery.formobserver.js"></script>-->
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_device.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_device_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(1001)?>')">
		<? include('filtro_grupo_device.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>	
			<form id="form" name='formulario' method='post' action='files/inserir/inserir_grupo_device.php'>
				<input type="hidden" name="dados[IdLoja]" id="IdLoja" value="<?php echo $_SESSION['IdLoja'];?>" />
				<div>
					<table>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="descCampo">Grupo Device</td>
						</tr>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="campo">
								<input type='text' id="IdGrupoDevice" name='dados[IdGrupoDevice]' value='' style='width:70px' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" autocomplete="off" maxlength='11'>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id="cp_tit">Dados Grupo Device</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><b><?=dicionario(1000)?></b></td>
							<td class="find">&nbsp;</td>
							<td class="descCampo">Disponivel Contrato</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' class="obrig" id="DescricaoGrupoDevice" name="dados[DescricaoGrupoDevice]" value=''  style='width:392px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');">
							</td>
							<td class="find">&nbsp;</td>
							<td class="descCampo">
								<select id="DisponivelContrato" name="dados[DisponivelContrato]" style="width: 110px;" onblur="Foco(this,'out')" onfocus="Foco(this,'in')">
									<option value=""></option>
									<?php 
										if(isset($data['DisponivelContrato'])){
											foreach($DisponivelContrato as $key => $value){
												if($key == $data['DisponivelContrato']){
									?>
												<option value="<?php echo $key;?>" selected="selected"><?php echo $value;?></option>
									<?php 
												}else{
									?>
													<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php 
												}
											}
										}else{
									?>
											<option value="1">Sim</option>
											<option value="2" selected="selected">Não</option>
									<?php 
										}
									
									?>
								</select>
							</td>							
						</tr>
					</table>
				</div>
				<div id='cp_log'>
				<div id='cp_tit'><?=dicionario(571)?></div>					
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
								<input type='text' id="LoginCriacao" name='dados[LoginCriacao]' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id="DataCriacao" name='dados[DataCriacao]' value='' style='width:202px'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id="LoginAlteracao" name='dados[LoginAlteracao]' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' id="DataAlteracao" name='dados[DataAlteracao]' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='submit' name='bt_inserir' value='Cadastrar' class='botao'>
								<input type='submit' name='bt_alterar' value='Alterar' class='botao' disabled="disabled">
								<input type='submit' name='bt_excluir' value='Excluir' class='botao' disabled="disabled">
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