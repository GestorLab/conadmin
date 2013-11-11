<?
	$localModulo		=	1;
	$localOperacao		=	39;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_Data				= formatText($_POST['Data'],NULL);
	$local_TipoData			= formatText($_POST['TipoData'],NULL);
	$local_DescricaoData	= formatText($_POST['DescricaoData'],NULL);
	
	if($_GET['Data']!=''){
		$local_Data = $_GET['Data'];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_datas_especiais.php');
			break;		
		case 'alterar':
			include('files/editar/editar_datas_especiais.php');
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
		<link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
		
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/datas_especiais.js'></script>
		<script type = 'text/javascript' src = 'js/datas_especiais_default.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	</head>
	<style type="text/css">
		input[type=text]:readOnly  		{ background-color: #FFF; }
		input[type=datetime]:readOnly  	{ background-color: #FFF; }
		input[type=date]:readOnly  		{ background-color: #FFF; }
		textarea:readOnly  				{ background-color: #FFF; }
		select:disabled  { background-color: #FFF; }
		select:disabled  { color: #000; }
	</style>
	<body  onLoad="ativaNome('Datas Especiais')">
		<? 
			include('filtro_datas_especiais.php'); 
		?>
	<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_datas_especiais.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='DatasEspeciais'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='LabelData'>Data</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Tipo Data</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Descrição</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Data' id='cpData' value='' style='width:95px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('LabelData',this); busca_datas_especiais(this.value,'false',document.formulario.Local.value)" tabindex='1'>
							</td>
							<td class='find' id='cpDataIco'><img src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpData",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoData' style='width:230px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
									<option value='0'></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=52 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											$lin[ValorParametroSistema]	=	explode("\n",$lin[ValorParametroSistema]);
											echo"<option value='$lin[IdParametroSistema]'>".$lin[ValorParametroSistema][0]."</option>";
										}
									?>
								</select>
						  </td>
						  <td class='separador'>&nbsp;</td>
						  <td class='campo'>
							  <input type='text' name='DescricaoData' value='' style='width:441px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
						  </td>
						</tr>
					</table>
				</div>
				<div id='cp_observacoes'>
					<div id='cp_tit'>Observações e Log</div>					
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
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='6' onClick="excluir(document.formulario.Data.value)">
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
	</div>
</body>
</html>
<script language='JavaScript' type='text/javascript'> 
<?

	if($local_Data!=''){
		echo "busca_datas_especiais('".$local_Data."',false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";
	}
?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
