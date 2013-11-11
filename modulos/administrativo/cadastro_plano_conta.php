<?
	$localModulo		=	1;
	$localOperacao		=	21;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Login		= $_SESSION["Login"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdPlanoConta			= formatText($_POST['IdPlanoConta'],NULL);
	$local_DescricaoPlanoConta	= formatText($_POST['DescricaoPlanoConta'],NULL);
	$local_IdAcessoRapido		= formatText($_POST['IdAcessoRapido'],NULL);
	
	if($_GET['IdPlanoConta']!=''){
		$local_IdPlanoConta	= $_GET['IdPlanoConta'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_plano_conta.php');
			break;		
		case 'alterar':
			include('files/editar/editar_plano_conta.php');
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
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/plano_conta.js'></script>
		<script type = 'text/javascript' src = 'js/plano_conta_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Plano de Conta')">
		<? include('filtro_plano_conta.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_plano_conta.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='PlanoConta'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:32px'>Plano de Conta</B><B>Nome Plano de Conta</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Acesso Rápido</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tipo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Ação Contábil</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPlanoConta' value='<?=$local_IdPlanoConta?>' autocomplete="off" style='width:110px' maxlength='34' onChange="busca_plano_conta(this.value,'false',document.formulario.Local.value)" onkeypress="mascara(this,event,'PlanoConta')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input type='text' class='agrupador' name='DescricaoPlanoConta' value='<?=$local_DescricaoPlanoConta?>' style='width:353px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdAcessoRapido' value='<?=$local_IdAcessoRapido?>' style='width:110px' maxlength='10' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td>
								<select name='Tipo' style='width:99px' disabled>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=33 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]' ".compara($local_Tipo,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td>
								<select name='AcaoContabil' style='width:100px' disabled>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=34 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]' ".compara($local_AcaoContabil,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
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
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='4' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='5' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='6' onClick="excluir(document.formulario.IdPlanoConta.value)">
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
	if($local_IdPlanoConta!=''){
		echo "busca_plano_conta('".$local_IdPlanoConta."','',false);";		
	}
?>

	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>

