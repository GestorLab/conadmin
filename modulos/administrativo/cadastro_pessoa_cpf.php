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
	
	$local_IdPessoa	 			= formatText($_POST['IdPessoa'],NULL);
	$local_CPF_CNPJ				= formatText($_POST['CPF_CNPJ'],NULL);
	$local_TipoPessoa			= formatText($_POST['TipoPessoa'],NULL);
	
	if($local_TipoPessoa == ""){
		$local_TipoPessoa	=	getCodigoInterno(3,5);
	}
	
	if($_GET['IdPessoa']!=''){
		$local_IdPessoa	=	$_GET['IdPessoa'];
	}
	
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_pessoa_cpf.php');
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
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_cpf.js'></script>
		<script type='text/javascript' src='../../js/val_cnpj.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script>  
		<script type='text/javascript' src='../../js/event.js'></script>  
		<script type='text/javascript' src='js/pessoa_cpf.js'></script>
		<script type='text/javascript' src='js/pessoa_default.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Pessoas/Alterar CPF/CNPJ')">
		<? include('filtro_pessoa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_pessoa_cpf.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='PessoaCPF'>
				<input type='hidden' name='CPF_CNPJ_Obrigatorio' value='<?=getCodigoInterno(11,2)?>'>
				<input type='hidden' name='CPF_CNPJ_Duplicado' value='<?=getCodigoInterno(11,4)?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Tipo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cp_CPF_CNPJ_Titulo' style='color:#000'>CPF_CNPJ</B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPessoa', true, event, null, 94); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdPessoa' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoPessoa' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="return ativaPessoa(this.value)" tabindex='2'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cp_CPF_CNPJ_Input' name='CPF_CNPJ' value='' style='width:170px' maxlength='18' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="verificar_CPF_CNPJ(this.value);"  onkeypress="chama_mascara(this,event)" tabindex='3'>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCadastrais'>
					<div id='cp_tit'>Dados Cadastrais</div>
					<div id='cp_Fisica'>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Nome Pessoa</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Sexo</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Data Nasc.</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Estado Civil</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>RG</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Nome' value='' style='width:330px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='Sexo' style='width: 60px' disabled>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=8 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataNascimento' value='' style='width:90px' maxlength='10' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='EstadoCivil' style='width:130px' disabled>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=9 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='RG_IE' value='' maxlength='30' style='width:150px' readOnly>
								</td>
							</tr>
						</table>
					</div>
					<div id='cp_Juridica'>
						<?
							$nome =	"<table>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='descCampo'>Nome Fantasia</td>
											<td class='separador'>&nbsp;</td>
											<td class='descCampo'>Razão Social</td>
										</tr>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='NomeFantasia' value='' style='width:400px' maxlength='100' readOnly>
											</td>
											<td class='separador'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='RazaoSocial' value='' style='width:399px' maxlength='100' readOnly>
											</td>
										</tr>
									</table>";
									
							$razao = "<table>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='descCampo'>Razão Social</td>
											<td class='separador'>&nbsp;</td>
											<td class='descCampo'>Nome Fantasia</td>
										</tr>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='RazaoSocial' value='' style='width:399px' maxlength='100' readOnly>
											</td>
											<td class='separador'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='NomeFantasia' value='' style='width:400px' maxlength='100' readOnly>
											</td>
										</tr>
									</table>";	
									
							switch(getCodigoInterno(3,24)){
								case 'Nome':
									echo "$razao";
									break;
								default:
									echo "$nome";
							}
						?>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Nome Representante</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Inscrição Estadual</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Inscrição Municipal</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Data Fundação</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='NomeRepresentante' value='' style='width:400px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='InscricaoEstadual' value='' maxlength='20' style='width:138px' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='InscricaoMunicipal' value='' maxlength='30' style='width:137px' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataFundacao' value='' style='width:90px' maxlength='10' readOnly>
								</td>
							</tr>
						</table>
					</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Fone Residencial</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone Comercial (1)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone Comercial (2)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Celular</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fax</td>	
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone1' value='' style='width:150px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2' value='' style='width:150px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3' value='' style='width:150px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular' value='' style='width:149px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Fax' value='' style='width:149px' maxlength='18' readOnly>
							</td>					
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>								
							<td class='descCampo'>Complemento Fone</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000000' id='Email'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Site (url)</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>					
							<td class='campo'>
								<input type='text' name='ComplementoTelefone' value='' style='width:150px' maxlength='30' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:294px' maxlength='255' autocomplete="off" readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Site' value='' style='width:294px' maxlength='100' readOnly>
							</td>
							<td class='find' onClick='JsHttp(document.formulario.Site.value)'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Acessar Site'></td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='4' onClick='cadastrar()'>
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
				include("files/busca/pessoa.php");
			?>
		</div>
	</body>
</html>
<script language='JavaScript' type='text/javascript'> 
<?
	if($local_IdPessoa!=''){
		echo "busca_pessoa($local_IdPessoa,false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";	
	}else{
		echo "
			ativaPessoa(".getCodigoInterno(3,5).");
		";
	}
?>	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
