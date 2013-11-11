<?
	$localModulo			= 1;
	$localOperacao			= 143;
	$localSuboperacao		= "V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login						= $_SESSION["Login"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_IdPessoaContrato				= $_POST['IdPessoaContrato'];
	$local_IdTipoPessoa					= $_POST['IdTipoPessoa'];
	$local_IdStatusContrato				= $_POST['Filtro_IdStatusContrato'];
	$local_Filtro_IdPaisEstadoCidade	= $_POST['Filtro_IdPaisEstadoCidade'];

	switch ($local_Acao){
		case 'exportar':
			header("Location: rotinas/processar_relacao_email.php?IdPessoaContrato=$local_IdPessoaContrato&IdTipoPessoa=$local_IdTipoPessoa&IdStatusContrato=$local_IdStatusContrato&IdPaisEstadoCidade=$local_Filtro_IdPaisEstadoCidade");
			break;
		default:
			$local_Acao = 'exportar';
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
		<script type = 'text/javascript' src = 'js/relacao_email.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Relação de E-mails')">
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_relacao_email.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='RelacaoEmail'>
				<input type='hidden' name='IdStatus' value='1'>
				<input type='hidden' name='Filtro_IdStatusContrato' value=''>
				<input type='hidden' name='Filtro_IdPaisEstadoCidade' value=''>
				<div>
					<div id='cp_tit' style='margin-top:0'>Filtros</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tipo Pessoa</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdPessoaContrato' style='width:111px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="habilitar_campo(this);" tabindex='1'>
									<option value='' selected>Todos</option>
									<?
										$sql = "
											select 
												IdParametroSistema, 
												ValorParametroSistema 
											from 
												ParametroSistema 
											where 
												IdGrupoParametroSistema = 192 
											order by IdParametroSistema ASC;
										";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoPessoa' style='width:140px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')"  tabindex='2'>
									<option value='' selected>Todos</option>
									<?
										$sql = "
											select 
												IdParametroSistema, 
												ValorParametroSistema 
											from 
												ParametroSistema 
											where 
												IdGrupoParametroSistema=1 
											order by IdParametroSistema ASC;
										";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<div id='cp_filtro_status'>
						<table id='titTabelaStatus'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Status Contrato</td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdStatusContrato' style='width:727px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
										<option value=''>&nbsp;</option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add_status' value='Adicionar' class='botao' style='width:84px;' tabindex='4' onClick="addStatus(document.formulario.IdStatusContrato.value,'')">
								</td>
							</tr>
						</table>
						<table id='tabelaStatus' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'>Id</td>
								<td>Status</td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='5' id='totaltabelaStatus'>Total: 0</td>
							</tr>
						</table>
					</div>
					<div id='cp_filtro_cidade'>
						<table style='margin:10px 0 5px 0' id='titTabelaCidade'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:54px; color:#000'>País</B>Nome País</td>
								<td class='separador'>&nbsp;</td>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:38px; color:#000'>Estado</b>Nome Estado</td>
								<td class='separador'>&nbsp;</td>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:38px; color:#000'>Cidade</B>Nome Cidade</td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPais', true, event, null, 265); document.formularioPais.NomePais.value=''; valorCampoPais = ''; busca_pais_lista(); document.formularioPais.NomePais.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdPais' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5' onChange="busca_pais(this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')"><input  class='agrupador' type='text' name='Pais' value='<?=$local_Pais?>' style='width:138px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaEstado', true, event, null, 265); document.formularioEstado.NomeEstado.value=''; valorCampoEstado = ''; busca_estado_lista(); document.formularioEstado.NomeEstado.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdEstado' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6' onChange='busca_estado(document.formulario.IdPais.value,this.value)' onkeypress="mascara(this,event,'int')"><input class='agrupador' type='text' name='Estado' value='<?=$local_Estado?>' style='width:140px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaCidade', true, event, null, 265); document.formularioCidade.NomeCidade.value=''; valorCampoCidade = ''; busca_cidade_lista(); document.formularioCidade.NomeCidade.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdCidade' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7' onChange='busca_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,this.value)' onkeypress="mascara(this,event,'int')"><input class='agrupador' type='text' name='Cidade' value='<?=$local_Cidade?>' style='width:140px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add_cidade' value='Adicionar' class='botao' style='width:84px;' tabindex='8' onClick="busca_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,document.formulario.IdCidade.value,false,'Servico')">
								</td>
							</tr>
						</table>
						<table id='tabelaCidade' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'>&nbsp;</td>
								<td>Nome Pais</td>
								<td>Nome Estado</td>
								<td>Nome Cidade</td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='5' id='totaltabelaCidade'>Total: 0</td>
							</tr>
						</table>
					</div>
				</div>
				<div class='cp_botao'>
					<table style='width:100%; margin-top:9px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td style='text-align:right; padding-right:5px;' class='campo'>
								<input type='button' name='bt_exportar' value='Exportar' class='botao' tabindex='9' onClick='cadastrar()'>
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
				include("files/busca/pais.php");
				include("files/busca/estado.php");
				include("files/busca/cidade.php");
			?>
		</div>
	</body>	
</html>
<script>
	inicia();
	enterAsTab(document.forms.formulario);
</script>
