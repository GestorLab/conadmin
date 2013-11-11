<?
	$localModulo		=	1;
	$localOperacao		=	205;
	$localSuboperacao	=	"V";
	//$localCadComum		= true;

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
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
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/device.js'></script>
		<!--<script type = 'text/javascript' src = 'js/device_default.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_device_default.js'></script>-->
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
			.esconde {
				display: none;
			}
			
			/*Janela Modal*/
			/*a {
				color: #333;
				text-decoration: none;
			}
			
			a:hover {
				color: #ccc;
				text-decoration: none;
			}*/
			#mask {
				position: absolute;
				left: 0;
				top: 0;
				z-index: 0000;
				
				display: none;
			}
			
			/*#boxes {
				position: static;
			}*/

			#quadros_fluantes .quadroFlutuante {
				position: absolute;
				left:0;
				top:0;
				width:440px;
				height:200px;
				z-index:9999;
			}
			.close {
				display: block;
				text-align: right;
			}
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(996)?>')">
		<? include('filtro_device.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form id="form" name='formulario' method='post' action='files/inserir/inserir_device.php'>
				<!--<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>-->
				<input type='hidden' name='Local' value='Device'>
				<input type="hidden" id="IdLoja" value="<?php echo $_SESSION['IdLoja'];?>" />
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'><?=dicionario(996)?></td>
							<td class='separador'>&nbsp;</td>						
							<td class='descCampo'><B><?=dicionario(82)?></B></td>
							<td class="separador">&nbsp;</td>
							<td class="descCampo esconde">Perfil</td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' >
								<input type='text' id="IdDevice" name="dados[IdDevice]" value='' autocomplete="off" style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" >
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo' >
							<select class="obrig" id="IdTipoDevice" name="dados[IdTipoDevice]" style="width: 100px;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								<option></option>
								<option value="1"><?=getParametroSistema(276,1)?></option>
								<option value="2"><?=getParametroSistema(276,2)?></option>
							</select>
							</td>
							<td class="separador">&nbsp;</td>
							<td class="descCampo">
								<select class="esconde" id="IdDevicePerfil" name="dados[IdDevicePerfil]" style="width: 100px;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
									<option value=""></option>
									<?php
									if($_GET){
										$_GET = key($_GET);
										$IdGet = json_decode($_GET);
									}
										$sql = "SELECT IdDevicePerfil, DescricaoPerfil FROM DevicePerfil WHERE IdDevicePerfil > 0";
										$res = mysql_query($sql, $con);
										while($lin = @mysql_fetch_array($res, MYSQL_ASSOC)){
											if($IdGet->IdDevicePerfil == $lin['IdDevicePerfil']){
									?>
											 	<option value="<?php echo $lin['IdDevicePerfil']?>" selected="selected"><?php echo $lin['DescricaoPerfil']?></option>
									<?php 		
											}else{
									?>
												<option value="<?php echo $lin['IdDevicePerfil']?>"><?php echo $lin['DescricaoPerfil']?></option>
									<?php 			
											}
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<div id='cp_tit'><?=dicionario(1012)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(125)?></B></td>	
							<td class='find'>&nbsp;</td>	
							<td class='descCampo'><?=dicionario(998)?></td>	
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>QTD. Portas</td>
							
						</tr>
						<tr>
							<td class='find'></td>	
							<td class='descCampo'><input  type='text' class="obrig" id="DescricaoDevice" name="dados[DescricaoDevice]" value="" style='width:343px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"></td>	
							<td class='find'><img id="#dialog" name="modal" src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' id="IdGrupoDevice" name="dados[IdGrupoDevice]" value='' autocomplete="off" style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"><input class='agrupador' type='text' id="DescricaoGrupoDevice" name="DescricaoGrupoDevice" value='' style='width:289px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo' >
								<input type="text" id='QuantidadePortas' value='' style='width: 65px;' onFocus="Foco(this, 'in')" onBlur="Foco(this, 'out')" />
							</td>
							
						</tr>
					</table>
					<div class="atributos"></div>
					<div class="portas"></div>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Observações e Log</div>
					<table>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="descCampo">Histórico [<a id="atualizaHistorico" style="cursor: pointer;">Atualizar</a>]</td>
						</tr>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="campo">
								<textarea id="Observacao" name="Observacao" style='width: 816px;' rows='5' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" readonly="readonly"></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="descCampo"><?=dicionario(766)?></td>
						</tr>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="campo">
								<textarea id="historico" name="historico" style='width: 816px;' rows=5 maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
							</td>
						</tr>
					</table>
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
								<input type='text' id="LoginCriacao" value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id="DataCriacao" value='' style='width:202px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id="LoginAlteracao" value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id="DataAlteracao" value='' style='width:203px' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='submit' id="bt_inserir" name='action[action_insert]' value='Cadastrar' class='botao'>
								<input type='submit' id="bt_alterar" name='action[action_insert]' value='Alterar' class='botao' disabled="disabled">
								<input type='submit' id="bt_excluir" name='action[action_excluir]' value='Excluir' class='botao' disabled="disabled">
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
		<div id="quadros_fluantes">
			<?
				include("files/busca/grupo_device.php");
			?>
		</div>
	</body>
</html>
<script>
	enterAsTab(document.forms.formulario);
</script>