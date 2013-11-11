<?
	$localModulo		=	1;
	$localOperacao		=	142;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login				= $_SESSION["Login"];
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_Acao 				= $_POST['Acao'];
	$local_Erro					= $_GET['Erro'];
	
	$local_IdContaDebito 		= $_POST['IdContaDebito'];
	$local_IdPessoa	 			= $_POST['IdPessoa'];
	$local_DescricaoContaDebito	= formatText($_POST['DescricaoContaDebito'],NULL);
	$local_IdLocalCobranca		= formatText($_POST['IdLocalCobranca'],NULL);
	$local_IdLocalCobrancaTemp	= formatText($_POST['IdLocalCobrancaTemp'],NULL);
	$local_NumeroAgencia		= formatText($_POST['NumeroAgencia'],NULL);
	$local_DigitoAgencia		= formatText($_POST['DigitoAgencia'],NULL);
	$local_NumeroConta			= formatText($_POST['NumeroConta'],NULL);
	$local_DigitoConta			= formatText($_POST['DigitoConta'],NULL);
	$local_IdStatus				= formatText($_POST['IdStatus'],NULL);
	
	if($_GET['IdPessoa'] != ''){
		$local_IdPessoa	= $_GET['IdPessoa'];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_pessoa_conta_debito.php');
			break;		
		case 'alterar':
			include('files/editar/editar_pessoa_conta_debito.php');
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
		<script type = 'text/javascript' src = '../../js/val_cpf.js'></script>
		<script type = 'text/javascript' src = '../../js/val_cnpj.js'></script>
		<script type = 'text/javascript' src = '../../js/val_email.js'></script>
		<script type = 'text/javascript' src = '../../js/val_url.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/pessoa_conta_debito.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_conta_debito_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Contas para Débito')">
		<?	include('filtro_pessoa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_pessoa_conta_debito.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='ContaDebito'>
				<input type='hidden' name='IdLocalCobrancaLayout' value=''>
				<input type='hidden' name='LocalCobranca' value='1'>
				<input type='hidden' name='IdPessoaTemp' value='<?=$local_IdPessoa?>'>
				<input type='hidden' name='IdLocalCobrancaTemp' value=''>
				<div id='cp_dadosCliente' style='margin-top:-5px;'>
					<div id='cp_tit'>Dados do Cliente</div>
					<?	
						$nome="	
							<div id='cp_juridica'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='margin-right:36px'>Pessoa</B>Nome Pessoa</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>CPNJ</td>									
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1'><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo' id='cp_RazaoSocial'>
											<input type='text'  name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readOnly>
										</td>
										
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Nome Representate</td>	
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Inscrição Estadual</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>E-mail</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readOnly>
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readOnly>
										</td>
										<td class='find' onClick='JsMail(document.formulario.EmailJuridica.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
									</tr>
								</table>
							</div>						
							";
							
						$razao="	
							<div id='cp_juridica'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='margin-right:36px'>Pessoa</B><B style='color:#000'>Razão Social</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Nome Fantasia</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>CPNJ</td>										
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1'><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readOnly>
										</td>										
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Nome Representate</td>	
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Inscrição Estadual</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Email</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readOnly>
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readOnly>
										</td>
										<td class='find' onClick='JsMail(document.formulario.EmailJuridica.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
									</tr>
								</table>
							</div>													
							";
							
						switch(getCodigoInterno(3,24)){
							case 'Nome':
								echo "$razao";
								break;
							default:
								echo "$nome";
						}
					?>
					<table id='cp_fisica' style='display:none;padding-bottom:6px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Nasc.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>CPF</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>RG</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPessoa', true, event, null, 165);"></td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input type='text' class='agrupador' name='NomeF' value='' style='width:220px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNascimento' value='' style='width:70px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:180px' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:100px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RG' value='' style='width:80px' readOnly>
							</td>
						</tr>
					</table>
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
							<td class='separador'>&nbsp;</td>				
							<td class='descCampo'>Complemento Fone</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone1' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Fax' value='' style='width:122px' readOnly>
							</td>	
							<td class='separador'>&nbsp;</td>						
							<td class='campo'>
								<input type='text' name='ComplementoTelefone' value='' style='width:121px' readOnly>
							</td>			
						</tr>
					</table>
				</div>
				<div id='cp_dadosCadastrais'>
					<div id='cp_tit'>Dados de Contas para Débito</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Conta Débito</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaDebito' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa_conta_debito(document.formulario.IdPessoa.value, this.value, true, document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Descrição</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Local Cobrança</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input name='DescricaoContaDebito' style='width: 399px;' maxlength='100' tabindex='3' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:405px;' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')"  onChange=
								"verifica_layout_local_cobranca(document.formulario.IdContaDebito, document.formulario.IdPessoa.value, this.value, true, document.formulario.Local.value)"tabindex='4'>
									<option value=''></option>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Número da Agência</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='td_titDigitoAgencia' style='width:85px;'><B id='titDigitoAgencia'>Díg.</B></td>
							<td class='descCampo'><B id='titNumeroConta' style='margin-left:9px'>Número da Conta</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Díg.</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Status</B></td>
						</tr>
						<tr>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumeroAgencia' value='' autocomplete="off" style='width:237px;' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'>
							</td>
							<td class='separador'><div id='titHifem'>-</div></td>
							<td class='campo' id='td_cpDigitoAgencia' style='width:85px;'>
								<input type='text' name='DigitoAgencia' value='' autocomplete="off" style='width:85px' maxlength='2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6'>
							</td>
							<td class='campo'>
								<input type='text' name='NumeroConta' value='' autocomplete="off" style='width:237px; margin-left:9px;' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7'>
							</td>
							<td class='separador'>-</td>
							<td class='campo'>
								<input type='text' name='DigitoConta' value='' autocomplete="off" style='width:85px;' maxlength='2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatus' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:109px;' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='9'>
									<option value=''></option>
									<?
										$sql = "
											SELECT 
												IdParametroSistema, 
												ValorParametroSistema 
											FROM 
												ParametroSistema 
											WHERE 
												IdGrupoParametroSistema = 188
											ORDER BY 
												ValorParametroSistema ASC;";
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
				<div>
					<div id='cp_tit'>Log/Histórico</div>
					<table id='cpHistorico'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Histórico</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='HistoricoObs' style='width:816px;' rows='5' readOnly></textarea>
							</td>
						</tr>
					</table>
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
								<input type='text' name='DataAlteracao' value='' style='width:202px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:100%'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_imprimirAutorizacao' value='Imprimir Autorização' class='botao' style='width:130px' tabindex='302' onClick="cadastrar('imprimirAutorizacao')" disabled>
							</td>
							<td class='campo' style='text-align:right; padding-right:6px'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='305' onClick="cadastrar('inserir')">
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='306' onClick="cadastrar('alterar')">
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='307' onClick="excluir(document.formulario.IdPessoa.value, document.formulario.IdContaDebito.value)">
							</td>
						</tr>
					</table>
					<table style="height:28px;">
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
				<div id='cp_conta_debito_cadastrada' style='display:none;'>
					<div id='cp_tit' style='margin-bottom:0'>Contas para Débito Cadastradas</div>
					<table id='tabelaContaDebito' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Id</td>
							<td>Local Cobrança</td>
							<td>Descrição</td>
							<td>Agência</td>
							<td>Conta</td>
							<td>Status</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' id='tabelaContaDebitoTotal'>Total: 0</td>
							<td colspan="6">&nbsp;</td>
						</tr>
					</table>
					<table style="height:28px;">
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
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
	if($local_IdPessoa != ''){
		echo "busca_pessoa($local_IdPessoa, false, document.formulario.Local.value);";
		
		if($local_IdContaDebito != ''){
			echo "busca_pessoa_conta_debito($local_IdPessoa, $local_IdContaDebito, false, document.formulario.Local.value);";
			echo "scrollWindow('bottom');";	
		} else{
			echo "buscar_local_cobranca();";
		}
	} else{
		echo "buscar_local_cobranca();";
	}
?>	
	
	function status_inicial(){
		if(document.formulario.IdPessoa.value != ''){
			document.formulario.IdContaDebito.readOnly			= true;
			document.formulario.DescricaoContaDebito.readOnly	= true;
			document.formulario.IdLocalCobranca.disabled		= true;
			document.formulario.NumeroAgencia.readOnly			= true;
			document.formulario.DigitoAgencia.readOnly			= true;
			document.formulario.NumeroConta.readOnly			= true;
			document.formulario.DigitoConta.readOnly			= true;
			document.formulario.LoginCriacao.readOnly			= true;
			document.formulario.DataCriacao.readOnly			= true;
			document.formulario.LoginAlteracao.readOnly			= true;
			document.formulario.DataAlteracao.readOnly			= true;
		} else{
			document.formulario.IdContaDebito.readOnly			= false;
			document.formulario.DescricaoContaDebito.readOnly	= false;
			document.formulario.IdLocalCobranca.disabled		= false;
			document.formulario.NumeroAgencia.readOnly			= false;
			document.formulario.DigitoAgencia.readOnly			= false;
			document.formulario.NumeroConta.readOnly			= false;
			document.formulario.DigitoConta.readOnly			= false;
			document.formulario.LoginCriacao.readOnly			= false;
			document.formulario.DataCriacao.readOnly			= false;
			document.formulario.LoginAlteracao.readOnly			= false;
			document.formulario.DataAlteracao.readOnly			= false;
		}
		
		if(document.formulario.IdStatus.value == ''){
			document.formulario.IdStatus.value = <?=getCodigoInterno(3, 144)?>;
		}
	}
	
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
	verificaErro();
</script>