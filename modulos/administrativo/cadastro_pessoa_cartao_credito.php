<?
	$localModulo		=	1;
	$localOperacao		=	200;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login				= $_SESSION["Login"];
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_Acao 				= $_POST['Acao'];
	$local_Erro					= $_GET['Erro'];
	
	$local_IdCartao		 		= $_POST['IdCartao'];
	$local_IdPessoa	 			= $_POST['IdPessoa'];
	$local_IdStatus				= $_POST['IdStatus'];
	$local_NomeTitular			= formatText($_POST['NomeTitular'],NULL);
	$local_NumeroCartao			= formatText($_POST['NumeroCartao'],NULL);
	$local_IdBandeiraCartao		= formatText($_POST['IdBandeiraCartao'],NULL);
	$local_IdBandeiraCartaoTemp	= formatText($_POST['IdBandeiraCartaoTemp'],NULL);
	$local_MesValidade			= formatText($_POST['MesValidade'],NULL);
	$local_MesValidadeTemp		= formatText($_POST['MesValidadeTemp'],NULL);
	$local_AnoValidade			= formatText($_POST['AnoValidade'],NULL);
	$local_AnoValidadeTemp		= formatText($_POST['AnoValidadeTemp'],NULL);
	$local_CodigoSeguranca		= formatText($_POST['CodigoSeguranca'],NULL);
	$local_DiaVencimentoFatura	= formatText($_POST['DiaVencimentoFatura'],NULL);
	
	if($_GET['IdPessoa'] != ''){
		$local_IdPessoa	= $_GET['IdPessoa'];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_pessoa_cartao_credito.php');
			break;		
		case 'alterar':
			include('files/editar/editar_pessoa_cartao_credito.php');
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
		<script type = 'text/javascript' src = 'js/pessoa_cartao_credito.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_cartao_credito_default.js'></script>
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
	<body onLoad="ativaNome('Cartão de Crédito')">
		<?	include('filtro_pessoa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_pessoa_cartao_credito.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='CartaoCredito'>
				<input type='hidden' name='IdLocalCobrancaLayout' value=''>
				<input type='hidden' name='LocalCobranca' value='1'>
				<input type='hidden' name='IdBandeiraCartaoTemp' value='<?=$local_IdPessoa?>'>
				<input type='hidden' name='MesValidadeTemp' value='<?=$local_IdPessoa?>'>
				<input type='hidden' name='AnoValidadeTemp' value='<?=$local_IdPessoa?>'>
				<input type='hidden' name='IdPessoaTemp' value='<?=$local_IdPessoa?>'>
				
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
					<div id='cp_tit'>Dados do Cartão de Crédito</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Cartão</td>
							<td class='separador'/>
							<td class='descCampo'><B>Bandeira do Cartão</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdCartao' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa_cartao_credito(document.formulario.IdPessoa.value, this.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2'>
							</td>
							<td class='separador'/>
							<td class='descCampo'>
								<select name='IdBandeiraCartao' style='width: 200px;' tabindex='3' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')">
									<option value=''></option>
									<?
										$sql = "
											SELECT 
												IdParametroSistema, 
												ValorParametroSistema 
											FROM 
												ParametroSistema 
											WHERE 
												IdGrupoParametroSistema = 267
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
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(960)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(961)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(962)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input name='NomeTitular' style='width: 355px;text-transform:uppercase;' maxlength='250' tabindex='4' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeypress='caixaAlta(this)'><br/>'Como gravado no cartão'. 
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='vertical-align: top'>
								<input name='NumeroCartao' style='width: 255px;' maxlength='19' onkeypress="mascara_cartao(this,1,event)" tabindex='5' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='vertical-align: top'>
								<select name='MesValidade' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:80px;' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='6'>
									<option value=''>Mês</option>
									<?
										$i = 1;
										while($i <= 12){
											if(strlen($i) <= 1){
												$i2 = "0$i";
											}else{
												$i2 = $i;
											}
											$i++;
											echo "<option value='$i2'>$i2</option>";
										}
									?>
								</select> /
								<select name='AnoValidade' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:82px;' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='7'>
									<option value=''>Ano</option>
									<?
										$i = 0;
										$ano = (int) date('Y');
										while($i <= 10){
											echo "<option value='$ano'>$ano</option>";
											$ano += 1;
											$i++;
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(963)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Dia Vencimento da Fatura</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Status</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CodigoSeguranca' value='' autocomplete="off" style='width:115px;' maxlength='4' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8' onkeypress="mascara(this,event,'int')">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DiaVencimentoFatura' value='' autocomplete="off" style='width:145px;' maxlength='2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9' onkeypress="mascara(this,event,'int');controla_dia_vencimento(this,event)">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatus' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:150px;' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='10'>
									<option value=''></option>
									<?
										$sql = "
											SELECT 
												IdParametroSistema, 
												ValorParametroSistema 
											FROM 
												ParametroSistema 
											WHERE 
												IdGrupoParametroSistema = 268
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
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='307' onClick="excluir(document.formulario.IdPessoa.value, document.formulario.IdCartao.value)">
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
					<div id='cp_tit' style='margin-bottom:0'>Cartões de Credito Cadastrados</div>
					<table id='tabelaContaDebito' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Id</td>
							<td>Nome Titular</td>
							<td>Número Cartão</td>
							<td>Validade</td>
							<td>Vencimento Fatura</td>
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
		
		if($local_IdCartao != ''){
			echo "busca_pessoa_cartao_credito($local_IdPessoa, $local_IdCartao, false, document.formulario.Local.value);";
			echo "scrollWindow('bottom');";	
		}
		
	} else{
		echo "buscar_local_cobranca();";
	}
?>	
	
	function status_inicial(){
		
	}
	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>