<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	
	$local_IdGrupoPermissao 	= $_POST['IdGrupoPermissao'];
	$local_IdLojaDestino		= $_POST['IdLojaDestino'];
	
	
	$sql	=	"select DescricaoLoja from Loja where IdLoja = $local_IdLoja";
	$res	=	mysql_query($sql,$con);
	$lin	=	mysql_fetch_array($res);
	
	$local_DescricaoLoja	=	$lin[DescricaoLoja];
	
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_grupo_permissao_duplicar.php');
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_permissao_duplicar.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Grupo Permissão/Duplicar')">
		<?	include('filtro_grupo_permissao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_grupo_permissao_duplicar.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='GrupoPermissaoDuplicar'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Grupo Permissão</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupoPermissao' style='width:422px' tabindex='1' onChange="listar_grupo_permissao(this.value)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdGrupoPermissao, DescricaoGrupoPermissao from GrupoPermissao order by DescricaoGrupoPermissao ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdGrupoPermissao]'>$lin[DescricaoGrupoPermissao]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCadastrais'>
					<div id='cp_tit'>Lojas Permissão</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Origem</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Destino</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdLojaOrigem' value='<?=$local_IdLoja?>' autocomplete="off" style='width:70px' maxlength='11' readOnly><input class='agrupador' type='text' name='DescricaoGrupoPermissao' value='<?=$local_DescricaoLoja?>' style='width:340px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLojaDestino' style='width:391px' tabindex='2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"> 
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdLoja, DescricaoLoja from Loja where IdLoja != $local_IdLoja order by DescricaoLoja ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdLoja]'>$lin[DescricaoLoja]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_alterar' value='Confirmar' class='botao' tabindex='3' onClick='cadastrar()'>
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
				<div style='margin-bottom:0;'>
					<div id='cp_tit' style='margin-bottom:0'>Permissões</div>
					<table id='tabelaPermissao' style='margin-top:0' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Loja</td>
							<td>Módulo</td>
							<td>Operação</td>
							<td>Sub-Operação</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='4' id='totaltabelaPermissao'>Total: 0</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</body>
</html>
<script language='JavaScript' type='text/javascript'> 
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
