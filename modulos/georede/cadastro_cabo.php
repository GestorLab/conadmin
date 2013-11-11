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
	
	$local_IdCabo								=	formatText($_POST['IdCabo'],NULL);
	$local_TipoCabo								=	formatText($_POST['TipoCabo'],NULL);
	$local_NomeCabo								=	formatText($_POST['NomeCabo'],NULL);
	$local_Especificacao						= 	formatText($_POST['Especificacao'],NULL);
	$local_QtdFibra								= 	formatText($_POST['QtdFibra'],NULL);
	$local_EspessuraVisual						= 	formatText($_POST['EspessuraVisual'],NULL);
	$local_DataInstalacao						= 	formatText($_POST['DataInstalacao'],NULL);
	$local_Cor									= 	formatText($_POST['Cor'],NULL);
	$local_Opacidade							= 	formatText($_POST['Opacidade'],NULL);
	$local_Oculto								= 	formatText($_POST['Oculto'],NULL);

	if($_GET['IdCabo'] != ''){
		$local_IdCabo	= $_GET['IdCabo'];	
	}

	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_cabo.php');
			break;
		case 'alterar':
			include('files/editar/editar_cabo.php');
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
		<script type = 'text/javascript' src = 'js/cabo.js'></script>
		<script type = 'text/javascript' src = 'js/cabo_default.js'></script>
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
		<link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
		
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Cabo')">
		<? include('filtro_cabo.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_cabo.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='cabo'>
				<div>
					<table >
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'>Cabo</td>
							<td class='separador'>&nbsp;</td>						
							<td class='descCampo'><B>Tipo Cabo</B></td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' >
								<input type='text' name='IdCabo' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_cabo(this.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100'>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo' >
							<select name="TipoCabo" style="width: auto;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='101'>
								<option></option>
								<?php
									$sql = "SELECT 
												IdCaboTipo,
												DescricaoCaboTipo 
											FROM
												CaboTipo
											ORDER BY DescricaoCaboTipo ASC";
									$res = mysql_query($sql,$con);
									while($lin = mysql_fetch_array($res)){
										echo "<option value='".$lin[IdCaboTipo]."'>".$lin[DescricaoCaboTipo]."</option>";
									}								
								?>
							</select>
							</td>
						</tr>
					</table>
						
					<div id='cp_tit'>Dados Cabo</div>
					
					<table border="0">
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Nome Cabo</B></td>	
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><B>Especificação</B></td>	
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'>Qtd Fibra</td>
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><B>Data Instalação</B></td>	
							<td class='find'>&nbsp;</td>	
							<td class='separador'>&nbsp;</td>		
							<td class='descCampo'>Cor</td>
							<td class='find'>&nbsp;</td>								
						</tr>
						<tr>
							<td class='find'></td>	
							<td class='descCampo'><input  type='text' name='NomeCabo' value="" style='width:150px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='102'></td>	
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><input  type='text' name='Especificacao' value="" style='width:306px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='102'></td>	
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input  type='text' name='QtdFibra' value="" style='width:70px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" tabindex='103'>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input  type='text' id="cpDataInstalacao" name='DataInstalacao' value="" style='width:110px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onchange="validar_Data(this.value,this)" onkeypress="mascara(this,event,'date')" tabindex='104'>
							</td>
							<td class='find'><img id='cpDataInstalacaoIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataInstalacao",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataInstalacaoIco"
							    });
							</script>							
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input  type='text'  name='Cor' value="" style='width:70px' maxlength='7' onFocus="Foco(this,'in')" onkeypress="return mascara(this,event,'cor');"  onBlur="Foco(this,'out')" tabindex='105'>
							</td>												
							<td class='find'><img id="ico_cor" src="../../img/estrutura_sistema/cores.gif" style="display: block; margin-right: 8px;" alt="Alterar Cor" title="Alterar Cor" onclick="vi_id('quadroBuscaCor', true, event, null, 100);"></td>							
						</tr>
						
					</table>	
					
					<table border="0">
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Opacidade</td>	
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'>Oculto</td>							
							<td class='separador'>&nbsp;</td>		
							<td class='descCampo'>Espessura Visual</td>
								
						</tr>
						<tr>
							<td class='find'></td>	
							<td class='descCampo'>
							<select name="Opacidade" style="width: 70px;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='106'>
								<option></option>								
								<option value='1'>0.1</option>								
								<option value='2'>0.2</option>								
								<option value='3'>0.3</option>								
								<option value='4'>0.4</option>								
								<option value='5'>0.5</option>								
								<option value='6'>0.6</option>								
								<option value='7'>0.7</option>								
								<option value='8'>0.8</option>								
								<option value='9'>0.9</option>								
								<option value='10'>1.0</option>								
							</select></td>	
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<select name="Oculto" style="width: 75px;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='106'>
									<option></option>								
									<option value='1'>Sim</option>									
									<option value='2'>Não</option>									
								</select>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo' >
							<select name="EspessuraVisual" style="width: 112px;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='106'>
								<option></option>								
								<option value='1'>1px</option>								
								<option value='2'>2px</option>								
								<option value='3'>3px</option>								
								<option value='4'>4px</option>								
								<option value='5'>5px</option>								
							</select>
							</td>
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
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='115' onClick='cadastrar()' disabled />
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' 	class='botao' tabindex='116' onClick='cadastrar()' />
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='117' onClick="excluir(document.formulario.IdCabo.value,'Cadastro')" />
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
		<div id='quadros_fluantes'>
			<?
				include("files/busca/cores.php");
			?>
		</div>
	</body>
</html>
<script>
	<?php
		if($local_IdCabo != ""){
			echo "busca_cabo($local_IdCabo);";
		}
	?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>