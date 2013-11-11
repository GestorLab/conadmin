<?
	$localModulo		=	1;
	$localOperacao		=	4;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login		= $_SESSION["Login"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdGrupoParametroSistema				=	formatText($_POST['IdGrupoParametroSistema'],NULL);
	$local_DescricaoGrupoParametroSistema		=	trim($_POST['DescricaoGrupoParametroSistema']);
	$local_IdParametroSistema					= 	formatText($_POST['IdParametroSistema'],NULL);
	$local_DescricaoParametroSistema			= 	trim($_POST['DescricaoParametroSistema']);
	$local_ValorParametroSistema				=	trim($_POST['ValorParametroSistema']);
	
	if($_GET['IdGrupoParametroSistema']!=''){
		$local_IdGrupoParametroSistema	= $_GET['IdGrupoParametroSistema'];
	}	
	if($_GET['IdParametroSistema']!=''){
		$local_IdParametroSistema	= $_GET['IdParametroSistema'];
	}
		
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_parametro_sistema.php');
			break;
		default:
			$local_Acao = 'alterar';
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
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/grupo_parametro_sistema_default.js'></script>
		<script type = 'text/javascript' src = 'js/parametro_sistema.js'></script>
		<script type = 'text/javascript' src = 'js/parametro_sistema_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Parâmetros do Sistema')">	
		<? include('filtro_parametro_sistema.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>	
			<form name='formulario' method='post' action='cadastro_parametro_sistema.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ParametroSistema'>
				<input type='hidden' name='permisssaoParametroSistema' value=''>
				<input type='hidden' name='permisssaoParametroSistemaUsuario' value='<?=$_SESSION['Login'];?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Grupo Parâmetro Sistema</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right: 16px;'>Par. Sistema</B><B>Nome Parâmetro do Sistema</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaGrupoParametro', true, event, null, 94); document.formularioGrupoParametro.Nome.value=''; valorCampoGrupoParametro=''; busca_grupo_parametro_sistema_lista(); document.formularioGrupoParametro.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdGrupoParametroSistema' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_grupo_parametro_sistema(this.value,false)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoGrupoParametroSistema' value='<?=$local_DescricaoGrupoParametroSistema?>' style='width:300px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdParametroSistema' value='' autocomplete="off" style='width:80px' maxlength='11' onChange='busca_parametro_sistema(document.formulario.IdGrupoParametroSistema.value,this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'><input class='agrupador' type='text' name='DescricaoParametroSistema' value="" style='width:338px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Valor Parâmetro Sistema</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea type='textarea' name='ValorParametroSistema'  style='width: 816px;' rows=5 maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='4'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Log</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usuário Cadastro</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Cadastro</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário Alteração</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Alteração</td>
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
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='101' disabled>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='102' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='103' disabled>
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
				include("files/busca/grupo_parametro_sistema.php");
			?>
		</div>
	</body>	
</html>
<script>
<?

	if($local_IdParametroSistema!='') {
		echo "busca_parametro_sistema($local_IdGrupoParametroSistema,$local_IdParametroSistema,false);";		
	} elseif($local_IdGrupoParametroSistema!='') {
		echo "busca_grupo_parametro_sistema($local_IdGrupoParametroSistema,false);";
	}
?>	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
