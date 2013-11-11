<?
	$localModulo		=	1;
	$localOperacao		=	65;
	$localSuboperacao	=	"V";	

	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_IdPessoaLogin				= $_SESSION["IdPessoa"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	
	$local_IdTipo						= $_POST['IdTipo'];
	$local_IdSubTipo					= $_POST['IdSubTipo'];
	$local_IdStatusSubTipo				= $_POST['IdStatusSubTipo'];
	$local_DescricaoSubTipo				= formatText($_POST['DescricaoSubTipo'],NULL);
	$local_IdStatus						= $_POST['IdStatus'];
	$local_LoginCriacao					= $_POST['LoginCriacao'];
	
	if($local_IdTipo == ''){
		$local_IdTipo = $_GET['IdTipo'];
	}
	
	if($local_IdSubTipo == ''){
		$local_IdSubTipo = $_GET['IdSubTipo'];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_subtipo_help_desk.php');
			break;
			
		case 'alterar':
			include('files/editar/editar_subtipo_help_desk.php');
			break;
			
		default:
			$local_Acao 	= 'inserir';
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
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		
		<script type = 'text/javascript' src = 'js/subtipo_help_desk.js'></script>
		<script type = 'text/javascript' src = 'js/subtipo_help_desk_default.js'></script>
		<script type = 'text/javascript' src = 'js/tipo_help_desk_default.js'></script>
	</head>
	<body onLoad="ativaNome('SubTipo Help Desk')">
		<? include('filtro_subtipo_help_desk.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_subtipo_help_desk.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='SubTipoHelpDesk'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Tipo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Descrição Tipo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Status</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdTipo' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_tipo_help_desk(this.value,true,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoTipo' value='' style='width:613px' maxlength='155' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdStatus' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width: 106px' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" disabled>
									<option value=''></option>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from
													ParametroSistema
												where
													IdGrupoParametroSistema = 157;";
										$res = @mysql_query($sql,$conCNT);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]' ".compara($local_IdStatus,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados do SubTipo</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>SubTipo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Descrição SubTipo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Status</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdSubTipo' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_subtipo_help_desk(this.value,document.formulario.IdTipo.value,true,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoSubTipo' value='' style='width:613px' maxlength='155' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdStatusSubTipo' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width: 106px' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='4'>
									<option value=''></option>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from
													ParametroSistema
												where
													IdGrupoParametroSistema = 157;";
										$res = @mysql_query($sql,$conCNT);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]' ".compara($local_IdStatus,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
										}
									?>
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
				<div class='cp_botao' style='height:50px;'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='5' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='6' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='7' onClick="excluir(document.formulario.IdTipo.value,document.formulario.IdSubTipo.value)">
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
	<?
		if($local_IdTipo != ""){
			echo "busca_tipo_help_desk($local_IdTipo,false);";
			
			if($local_IdSubTipo != ""){
				echo "busca_subtipo_help_desk($local_IdSubTipo,$local_IdTipo,false);";
			}
		}
	?>
	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>