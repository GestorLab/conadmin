<?
	$localModulo		=	1;
	$localOperacao		=	25;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdServico		= $_POST['IdServico'];	
	$local_IdPais			= $_POST['IdPais'];
	$local_IdAliquotaTipo	= $_POST['IdAliquotaTipo'];
	
	switch ($local_Acao){	
		case 'alterar':
			include('files/editar/editar_aliquota.php');
			break;
		default:
			$local_Acao 	 		= 'alterar';
			break;
	}
	
	if($_GET['IdServico']!=''){
		$local_IdServico	= $_GET['IdServico'];	
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
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/aliquota.js'></script>
		<script type = 'text/javascript' src = 'js/aliquota_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		
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
	<body  onLoad="ativaNome('<?=dicionario(540)?>')">
		<? include('filtro_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_aliquota.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='Aliquota'>
				<input type='hidden' name='IdCategoriaTributaria' value=''>
				<input type='hidden' name='IdPais' value='1'>
				
				<div id='cpDadosServico'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(435)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:34px'><?=dicionario(30)?></b><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(436)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaServico', true, event, null, 118); busca_servico_lista();"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:530px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoServico' style='width:200px' disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=71 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>		
				</div>				
				<div id='cpDadosAliquota'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(633)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(634)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdAliquotaTipo' style='width:208px;' onChange="verificar_tipo_aliquota(this.value);" tabindex='1'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=160 order by ValorParametroSistema;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table id='tableAliquota'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(157)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(540)?> (%)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(635)?></B></td>
						</tr>					
					</table>		
				</div>				
				<div id='cp_log'>
					<div id='cp_tit'>Log</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(133)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(134)?></td>
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
								<input type='text' name='DataCriacao' value='' style='width:202px'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  maxlength='20' readOnly>
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
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='100' onClick='cadastrar()'>
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
				<table style='display:none' id='tabelahelpText2'>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
					</tr>
				</table>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/servico.php");
			?>
		</div>		
	</body>	
</html>
<script>
	<?
		if($local_IdServico == ""){
			echo "busca_aliquota_estado(document.formulario.IdPais.value,true,document.formulario.Local.value);";			
		}else{
			echo "busca_aliquota_estado(document.formulario.IdPais.value,false,document.formulario.Local.value);";			
			echo "busca_servico('".$local_IdServico."',false,document.formulario.Local.value);";
		}
	?>
	
	//verificaAcao();
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
