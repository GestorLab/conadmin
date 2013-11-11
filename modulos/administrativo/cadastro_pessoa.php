<?
	$localModulo		=	1;
	$localOperacao		=	1;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	$Path				=   "../../";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	include ('../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];	
	
	$local_IdPessoa	 				= formatText($_POST['IdPessoa'],NULL);
	$local_TipoPessoa				= formatText($_POST['TipoPessoa'],NULL);
	$local_TipoPessoaDefault		= formatText($_POST['TipoPessoaDefault'],NULL);
	$local_Nome						= formatText($_POST['Nome'],NULL);
	$local_NomePai					= formatText($_POST['NomePai'],NULL);
	$local_NomeMae					= formatText($_POST['NomeMae'],NULL);
	$local_NomeFantasia				= formatText($_POST['NomeFantasia'],NULL);
	$local_NomeRepresentante		= formatText($_POST['NomeRepresentante'],NULL);
	$local_RazaoSocial				= formatText($_POST['RazaoSocial'],NULL);
	$local_DataNascimento			= formatText(dataConv($_POST['DataNascimento'],"d/m/Y","Y-m-d"),NULL);
	$local_DataFundacao				= formatText(dataConv($_POST['DataFundacao'],"d/m/Y","Y-m-d"),NULL);
	$local_Sexo						= formatText($_POST['Sexo'],NULL);
	$local_RG_IE					= formatText($_POST['RG_IE'],NULL);
	$local_InscricaoEstadual		= formatText($_POST['InscricaoEstadual'],NULL);
	$local_CPF_CNPJ					= formatText($_POST['CPF_CNPJ'],NULL);
	$local_EstadoCivil				= formatText($_POST['EstadoCivil'],NULL);
	$local_ImovelProprio			= formatText($_POST['ImovelProprio'],NULL);
	$local_InscricaoMunicipal		= formatText($_POST['InscricaoMunicipal'],NULL);
	$local_Telefone1				= formatText($_POST['Telefone1'],NULL);
	$local_Telefone2				= formatText($_POST['Telefone2'],NULL);
	$local_Telefone3				= formatText($_POST['Telefone3'],NULL);
	$local_Celular					= formatText($_POST['Celular'],NULL);
	$local_Fax						= formatText($_POST['Fax'],NULL);
	$local_ComplementoTelefone		= formatText($_POST['ComplementoTelefone'],NULL);
	$local_Email					= formatText($_POST['Email'],getParametroSistema(4,5));
	$local_Site						= formatText($_POST['Site'],getParametroSistema(4,6));
	$local_Obs						= formatText($_POST['Obs'],NULL);
	$local_QtdEndereco				= formatText($_POST['QtdEndereco'],NULL);
	$local_QtdEnderecoAux			= formatText($_POST['QtdEnderecoAux'],NULL);
	$local_AgruparContratos			= formatText($_POST['AgruparContratos'],NULL);
	$local_ForcarAtualizar			= formatText($_POST['ForcarAtualizar'],NULL);
	$local_IdGrupoPessoa			= formatText($_POST['IdGrupoPessoa'],NULL);
	$local_Enviar_Email				= formatText($_POST['Enviar_Email'],NULL);
	$local_Cob_FormaCorreio			= formatText($_POST['Cob_FormaCorreio'],NULL);
	$local_Cob_FormaEmail			= formatText($_POST['Cob_FormaEmail'],NULL);
	$local_Cob_FormaOutro			= formatText($_POST['Cob_FormaOutro'],NULL);
	$local_Cob_CobrarDespesaBoleto	= formatText($_POST['Cob_CobrarDespesaBoleto'],NULL);
	$local_TipoUsuario				= formatText($_POST['TipoUsuario'],NULL);
	$local_TipoAgenteAutorizado		= formatText($_POST['TipoAgenteAutorizado'],NULL);
	$local_TipoFornecedor			= formatText($_POST['TipoFornecedor'],NULL);
	$local_TipoVendedor				= formatText($_POST['TipoVendedor'],NULL);
	$local_CampoExtra1				= formatText($_POST['CampoExtra1'],NULL);
	$local_CampoExtra2				= formatText($_POST['CampoExtra2'],NULL);
	$local_CampoExtra3				= formatText($_POST['CampoExtra3'],NULL);
	$local_CampoExtra4				= formatText($_POST['CampoExtra4'],NULL);
	$local_CPF_CNPJ_Obrigatorio		= formatText($_POST['CPF_CNPJ_Obrigatorio'],NULL);
	$local_IdEnderecoDefault		= formatText($_POST['IdEnderecoDefault'],NULL);
	$local_IdMonitorFinanceiro		= formatText($_POST['IdMonitorFinanceiro'],NULL);
	$local_NomeConjugue				= formatText($_POST['NomeConjugue'],NULL);
	$local_OrgaoExpedidor			= formatText($_POST['OrgaoExpedidor'],NULL);
	
	$local_Nome_Resumido					= formatText($_POST['Nome_Resumido'],NULL);
	$local_Email_Resumido					= formatText($_POST['Email_Resumido'],getParametroSistema(4,5));
	$local_IdGrupoPessoa_Resumido			= formatText($_POST['IdGrupoPessoa_Resumido'],NULL);
	$local_Telefone1_Resumido				= formatText($_POST['Telefone1_Resumido'],NULL);
	$local_Telefone2_Resumido				= formatText($_POST['Telefone2_Resumido'],NULL);
	$local_Telefone3_Resumido				= formatText($_POST['Telefone3_Resumido'],NULL);
	$local_Celular_Resumido					= formatText($_POST['Celular_Resumido'],NULL);
	$local_Fax_Resumido						= formatText($_POST['Fax_Resumido'],NULL);
	$local_ComplementoTelefone_Resumido		= formatText($_POST['ComplementoTelefone_Resumido'],NULL);
	$local_NomeFantasia_Resumido			= formatText($_POST['NomeFantasia_Resumido'],NULL);
	$local_RazaoSocial_Resumido				= formatText($_POST['RazaoSocial_Resumido'],NULL);
	$local_AtivarCadastroResumido			= $_POST['AtivarCadastroResumido'];
	$local_IdPais_Resumido					= $_POST['IdPais_Resumido'];
	$local_IdEstado_Resumido				= $_POST['IdEstado_Resumido'];
	$local_IdCidade_Resumido				= $_POST['IdCidade_Resumido'];
	
	$local_Senha					= trim($_POST['Senha']);
	$local_EcluirAnexos				= $_POST['EcluirAnexos'];
	$tamanhomaximo 					= getParametroSistema(95,32);
	$tipoinputsenha					= "";
	
	if(getParametroSistema(95,34) == 1){
		$tipoinputsenha					= "text";
	} else{
		$tipoinputsenha					= "password";
	}	
	if($local_TipoPessoa == ""){
		$local_TipoPessoa	=	getCodigoInterno(3,5);
	}	
	if($local_Site == ""){
		$local_Site = "http://";
	}
	
	if($_GET['IdPessoa']!=''){
		$local_IdPessoa	=	$_GET['IdPessoa'];
	}
	
	if($tamanhomaximo > 255 || $tamanhomaximo == "" || $tamanhomaximo == 0){
		$tamanhomaximo = 255;
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_pessoa.php');
			break;		
		case 'alterar':
			include('files/editar/editar_pessoa.php');
			break;
		default:
			$local_Acao = 'inserir';
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/procurar.css' />
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_cpf.js'></script>
		<script type='text/javascript' src='../../js/val_cnpj.js'></script>
		<script type='text/javascript' src='../../js/val_email.js'></script>
		<script type='text/javascript' src='../../js/val_url.js'></script>
		<script type='text/javascript' src='../../js/val_data.js'></script>
		<script type='text/javascript' src='../../js/val_ie.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javascript' src='../../js/event.js'></script> 
		<script type='text/javascript' src='js/pessoa.js'></script>
		<script type='text/javascript' src='js/pessoa_busca_pessoa_aproximada.js'></script>
		<script type='text/javascript' src='js/pessoa_default.js'></script>
		<script type='text/javascript' src='js/grupo_pessoa_default.js'></script>
		<script type='text/javascript' src='js/cep_default.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Pessoa')">
		<?	include('filtro_pessoa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_pessoa.php' onSubmit='return validar()' enctype='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='Pessoa'>
				<input type='hidden' name='CPF_CNPJ_Obrigatorio' value='<?=getCodigoInterno(11,2)?>'>
				<input type='hidden' name='DataNascimento_Obrigatorio' value='<?=getCodigoInterno(11,6)?>'>
				<input type='hidden' name='CPF_CNPJ_Duplicado' value='<?=getCodigoInterno(11,4)?>'>
				<input type='hidden' name='TelefoneObrigatorio' value='<?=getCodigoInterno(11,3)?>'>
				<input type='hidden' name='Numero_Obrigatorio' value='<?=getCodigoInterno(11,7)?>'>
				<input type='hidden' name='Endereco_Length' value='<?=getCodigoInterno(7,11)?>'>
				<input type='hidden' name='DescricaoEndereco1' value='<?=getCodigoInterno(3,87)?>'>
				<input type='hidden' name='DescricaoEndereco2' value='<?=getCodigoInterno(3,88)?>'>
				<input type='hidden' name='CEPDefault' value='<?=getCodigoInterno(3,75)?>'>
				<input type='hidden' name='TipoPessoaDefault' value='<?=getCodigoInterno(3,5)?>'>
				<input type='hidden' name='SiglaEstado' value=''>
				<input type='hidden' name='QtdEndereco' value=''>
				<input type='hidden' name='QtdEnderecoAux' value=''>
				<input type='hidden' name='IdLoja' value='<?=$local_IdLoja?>'>
				<input type='hidden' name='CountArquivo' value='0'>
				<input type='hidden' name='ExtensaoAnexo' value='<?=implode(',', $extensao_anexo)?>'>
				<input type='hidden' name='MaxUploads' value='<?=ini_get('max_file_uploads')?>'>
				<input type='hidden' name='MaxSize' value='<?=ini_get('upload_max_filesize')?>'>
				<input type='hidden' name='EcluirAnexos' value=''>
				<input type='hidden' name='SiglaEstadoIE' value=''>
				<input type='hidden' name='ObrigatoriedadeSexo' value='<?=getCodigoInterno(3,207)?>'>
				<input type='hidden' name='ObrigatoriedadeEstadoCivil' value='<?=getCodigoInterno(3,208)?>'>
				<input type='hidden' name='ObrigatoriedadeConjugue' value='<?=getCodigoInterno(3,209)?>'>
				<input type='hidden' name='ObrigatoriedadeNomePai' value='<?=getCodigoInterno(3,210)?>'>
				<input type='hidden' name='ObrigatoriedadeNomeMae' value='<?=getCodigoInterno(3,211)?>'>
				<input type='hidden' name='ObrigatoriedadeRG' value='<?=getCodigoInterno(3,212)?>'>
				<input type='hidden' name='HabilitarMascaraFone' value='<?=getCodigoInterno(3,215)?>'>
				<input type='hidden' name='VisivelConjugueDisplay' value=''>
				<input type='hidden' name='ObrigatoriedadeInscricaoEstadual' value='<?=getCodigoInterno(11,18)?>'>
				<input type='hidden' name='AtivarCadastroResumido' value=''>
				<input type='hidden' name='VisualizarCadastroResumido' value='<?=getCodigoInterno(3,235)?>'>
				<input type='hidden' name='IdTipoPessoaTemp' value=''>
				<input type='hidden' name='PermissaoExcluirAnexo' value='<?=permissaoSubOperacao(1,210,"D")?>'>
				<input type='hidden' name='PermissaoInserirAnexo' value='<?=permissaoSubOperacao(1,210,"I")?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(26)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(82)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cp_CPF_CNPJ_Titulo'><?=dicionario(83)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='lb_CadasdroResumido'>Cadastro Resumido</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPessoa' value='<?=$local_IdPessoa?>' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,true,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoPessoa' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="return ativaPessoa(this.value);" tabindex='2'>
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
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='CadastroResumido' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="ativar_cadastro_resumido(this)" tabindex='3'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=279 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='descricao' id='descricao' style='width:325px; text-align:right;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div id='cp_tit'><?=dicionario(84)?></div>
				<div id='cp_dadosCadastraisResumido'>	
					<div id='cp_Fisica_Resumido'>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(85)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(104)?></B></td>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:10px'>Grupo Pessoa</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Nome_Resumido' value='' style='width:300px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'/>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Email_Resumido' value='' style='width:232px;' maxlength='255' autocomplete="off" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out');" onChange="validar_Email(this.value,'Email');verifica_email_cobranca();" onkeypress="return mascara(this,event,'filtroCaractereEmail','','')" tabindex='5'>
								</td>
								<td class='find'>
									<img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaGrupoPessoa', true, event, null, 300); document.formularioGrupoPessoa.Nome.value='';  valorCampoGrupoPessoa = ''; busca_grupo_pessoa_lista(); document.formularioGrupoPessoa.Nome.focus();">
								</td>
								<td class='campo'>
									<input type='text' name='IdGrupoPessoa_Resumido' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6' onChange='busca_grupo_pessoa(this.value,false,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')"><input class='agrupador' type='text' name='DescricaoGrupoPessoa_Resumido' value='' style='width:162px' maxlength='100' readOnly>
								</td>
							</tr>
						</table>
					</div>
					<div id='cp_Juridica_Resumido'>
						<?
							$nome =	"<table>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='descCampo'><B>".dicionario(92)."</B></td>
											<td class='separador'>&nbsp;</td>
											<td class='descCampo'><B>".dicionario(172)."</B></td>
										</tr>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='NomeFantasia_Resumido' value='' style='width:400px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='11'>
											</td>
											<td class='separador'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='RazaoSocial_Resumido' value='' style='width:399px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='12'>
											</td>
										</tr>
									</table>";
									
							$razao = "<table>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='descCampo'><B>".dicionario(172)."</B></td>
											<td class='separador'>&nbsp;</td>
											<td class='descCampo'><B>".dicionario(92)."</B></td>
										</tr>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='RazaoSocial_Resumido' value='' style='width:399px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='11'>
											</td>
											<td class='separador'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='NomeFantasia_Resumido' value='' style='width:400px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='12'>
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
								<td class='descCampo'><B><?=dicionario(104)?></B></td>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:10px'>Grupo Pessoa</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Email_Resumido2' value='' style='width:400px;' maxlength='255' autocomplete="off" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out');" onChange="validar_Email(this.value,'Email');verifica_email_cobranca();" onkeypress="return mascara(this,event,'filtroCaractereEmail','','')" tabindex='13'>
								</td>
								<td class='find'>
									<img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaGrupoPessoa', true, event, null, 300); document.formularioGrupoPessoa.Nome.value='';  valorCampoGrupoPessoa = ''; busca_grupo_pessoa_lista(); document.formularioGrupoPessoa.Nome.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdGrupoPessoa_Resumido2' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15' onChange='busca_grupo_pessoa(this.value,false,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')"><input class='agrupador' type='text' name='DescricaoGrupoPessoa_Resumido2' value='' style='width:311px' maxlength='100' readOnly>
								</td>
							</tr>
						</table>
					</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(98)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(99)?> (1)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(99)?> (2)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(100)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(101)?></td>	
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(102)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone1_Resumido' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='17'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2_Resumido' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='18'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3_Resumido' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='19'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular_Resumido' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='20'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Fax_Resumido' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='21'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ComplementoTelefone_Resumido' value='' style='width:121px' onkeypress="mascara_fone(this,event)" maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='22'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>			
						</tr>
					</table>
					<table>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="descCampo"><b style="margin-right:54px;">País</b>Nome País</td>
							<td class="separador">&nbsp;</td>		
							<td class="find">&nbsp;</td>		
							<td class="descCampo"><b style="margin-right:38px;">Estado</b>Nome Estado</td>
							<td class="separador">&nbsp;</td>
							<td class="find">&nbsp;</td>
							<td class="descCampo"><b style="margin-right:38px;">Cidade</b>Nome Cidade</td>	
						</tr>
						<tr>
						<td class="find">
							<img src="../../img/estrutura_sistema/ico_lupa.gif" alt="Buscar" onclick="vi_id('quadroBuscaPais', true, event, null, 572, 1);"/></td>		
						<td class="campo">
							<input name="IdPais_Resumido" value="" style="width:70px" maxlength="11" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" tabindex="50" onchange="busca_pessoa_pais(this.value,false,document.formulario.Local.value, 1)" onkeypress="mascara(this,event,'int')" type="text"/><input class="agrupador" name="Pais_Resumido" value="" style="width:140px" maxlength="100" readonly="" type="text"/>
						</td>
						<td class="separador">&nbsp;</td>
						<td class="find">
							<img src="../../img/estrutura_sistema/ico_lupa.gif" alt="Buscar" onclick="vi_id('quadroBuscaEstado', true, event, null, 572, 1);"/>
						</td>
						<td class="campo">
							<input name="IdEstado_Resumido" value="" style="width:70px" maxlength="11" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" tabindex="51" onchange="busca_pessoa_estado(document.formulario.IdPais_Resumido.value,this.value,false,document.formulario.Local.value,1)" onkeypress="mascara(this,event,'int')" type="text"/><input class="agrupador" name="Estado_Resumido" value="" style="width:140px" maxlength="100" readonly="" type="text"/>	
						</td>
						<td class="separador">&nbsp;</td>
						<td class="find">
							<img src="../../img/estrutura_sistema/ico_lupa.gif" alt="Buscar" onclick="vi_id('quadroBuscaCidade', true, event, null, 572, 1);"/>
						</td>
						<td class="campo">
							<input name="IdCidade_Resumido" value="" style="width:70px" maxlength="11" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" tabindex="52" onchange="busca_pessoa_cidade(document.formulario.IdPais_Resumido.value,document.formulario.IdEstado_Resumido.value,this.value,false,document.formulario.Local.value,1)" onkeypress="mascara(this,event,'int')" type="text"><input class="agrupador" name="Cidade_Resumido" value="" style="width:233px" maxlength="100" readonly="" type="text"/>	
						</td>
					</tr>
				</table>
				</div>	
				<div id='cp_dadosCadastrais'>
					<div id='cp_Fisica'>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B><?=dicionario(85)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>
									<?
										if(getCodigoInterno(3,207) == 1){
											echo "<B>".dicionario(86)."</B>";
										}else{
											echo dicionario(86);
										}
									?>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B id='cp_DataNascimento_Titulo'><?=dicionario(87)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>
									<?
										if(getCodigoInterno(3,208) == 1){
											echo "<B>".dicionario(88)."</B>";
										}else{
											echo dicionario(88);
										}
									?>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>
									<?
										if(getCodigoInterno(3,212) == 1){
											echo "<B>".dicionario(89)."</B>";
										}else{
											echo dicionario(89);
										}
									?>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo' id='labelOrgaoExpedidor'><?=dicionario(976)?></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Nome' value='' style='width:300px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'/>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='Sexo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5' style='width: 60px'>
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
									<input type='text' name='DataNascimento' value='' style='width:90px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validar_Data(this.value,'cp_DataNascimento_Titulo')" tabindex='6' onkeypress="mascara(this,event,'date')">
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='EstadoCivil' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7' onchange='visualizarConjugue(this.value)'>
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
									<input type='text' name='RG_IE' value='' maxlength='30' style='width:103px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8' onchange='obrigatoriedadeOrgaoExpedidor(this)'>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='OrgaoExpedidor' value='' maxlength='30' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8'>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>
									<?
										if(getCodigoInterno(3,211) == 1){
											echo "<B>".dicionario(91)."</B>";
										}else{
											echo dicionario(91);
										}
									?>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>
									<?
										if(getCodigoInterno(3,210) == 1){
											echo "<B>".dicionario(90)."</B>";
										}else{
											echo dicionario(90);
										}
									?>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo' id='labelNomeConjugue' style='display: none'>
									<?
										if(getCodigoInterno(3,209) == 1){
											echo "<B>".dicionario(975)."</B>";
										}else{
											echo dicionario(975);
										}
									?>
								</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='NomeMae' value='' style='width:400px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9'>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='NomePai' value='' style='width:399px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10'>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo' id='campoNomeConjugue' style='display: none'>
									<input type='text' name='NomeConjugue' value='' style='width:282px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10'>
								</td>
							</tr>
						</table>
					</div>
					<div id='cp_Juridica'>
						<?
							$nome =	"<table>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='descCampo'><B>".dicionario(92)."</B></td>
											<td class='separador'>&nbsp;</td>
											<td class='descCampo'><B>".dicionario(172)."</B></td>
										</tr>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='NomeFantasia' value='' style='width:400px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='11'>
											</td>
											<td class='separador'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='RazaoSocial' value='' style='width:399px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='12'>
											</td>
										</tr>
									</table>";
									
							$razao = "<table>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='descCampo'><B>".dicionario(172)."</B></td>
											<td class='separador'>&nbsp;</td>
											<td class='descCampo'><B>".dicionario(92)."</B></td>
										</tr>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='RazaoSocial' value='' style='width:399px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='11'>
											</td>
											<td class='separador'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='NomeFantasia' value='' style='width:400px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='12'>
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
								<td class='descCampo'><B><?=dicionario(94)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><span id='tit_InscricaoEstadual'><?=dicionario(95)?></span></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(96)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B style='color:#000'  id='cp_DataFundacao_Titulo'><?=dicionario(97)?></B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='NomeRepresentante' value='' style='width:400px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13'>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='InscricaoEstadual' value='' maxlength='20' style='width:138px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='14' >
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='InscricaoMunicipal' value='' maxlength='30' style='width:137px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15'>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataFundacao' value='' style='width:90px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validar_Data(this.value,'cp_DataFundacao_Titulo')" tabindex='16' onkeypress="mascara(this,event,'date')">
								</td>
							</tr>
						</table>
					</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(98)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(99)?> (1)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(99)?> (2)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(100)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(101)?></td>	
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(102)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone1' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='17'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='18'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='19'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='20'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Fax' value='' style='width:122px' maxlength='18' onkeypress="mascara_fone(this,event)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='21'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ComplementoTelefone' value='' style='width:121px' onkeypress="mascara_fone(this,event)" maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='22'>
								<div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'><?=dicionario(103)?></a></div>
							</td>			
						</tr>
					</table>
					<table cellspacing='0' cellpading='0'  style='margin:0; padding:0'>
						<tr>
							<td  valign='top'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B id='Email'><?=dicionario(104)?></B></td>
										<td class='find'>&nbsp;</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(105)?></td>
										<td class='find'>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='Email' value='' style='width:232px;' maxlength='255' autocomplete="off" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out');" onChange="validar_Email(this.value,'Email');verifica_email_cobranca();" onkeypress="return mascara(this,event,'filtroCaractereEmail','','')" tabindex='23'>
										</td>
										<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='<?=dicionario(164)?>'></td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='Site' value='<?=$local_Site?>' style='width:232px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='24'>
										</td>
										<td class='find' onClick='JsHttp(document.formulario.Site.value)'><img src='../../img/estrutura_sistema/ico_ie.png' alt='<?=dicionario(165)?>'></td>
									</tr>
								</table>
								<?
									if(getParametroSistema(95,2) == 1){
										echo"
											<table style='margin:0; padding:0;'>
												<tr>
													<td class='find'>&nbsp;</td>
													<td class='descCampo'><B style='margin-right:10px'>".dicionario(106)."</td>
													<td class='separador'>&nbsp;</td>
													<td class='descCampo'>".dicionario(107)."</td>
												</tr>
												<tr>
													<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='".dicionario(166)."' onClick=\"vi_id('quadroBuscaGrupoPessoa', true, event, null, 300); document.formularioGrupoPessoa.Nome.value='';  valorCampoGrupoPessoa = ''; busca_grupo_pessoa_lista(); document.formularioGrupoPessoa.Nome.focus();\"></td>
													<td class='campo'>
														<input type='text' name='IdGrupoPessoa' value='' style='width:70px' maxlength='11' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='25' onChange='busca_grupo_pessoa(this.value,false,document.formulario.Local.value)' onkeypress=\"mascara(this,event,'int')\"><input class='agrupador' type='text' name='DescricaoGrupoPessoa' value='' style='width:310px' maxlength='100' readOnly>
													</td>
													<td class='separador'>&nbsp;</td>
													<td class='campo'>
														<input type='".$tipoinputsenha."' name='Senha' value='' style='width:100px' maxlength='".$tamanhomaximo."' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='26'>
													</td>
													<td class='find' onClick='gerar_senha()'><img src='../../img/estrutura_sistema/processar.gif' title='Gerar senha'></td>
												</tr>
											</table>";
									}else{
										echo"
											<table style='margin:0; padding:0;'>
												<tr>
													<td class='find'>&nbsp;</td>
													<td class='descCampo'><B style='margin-right:10px'>Grupo Pessoa</td>
												</tr>
												<tr>
													<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='".dicionario(166)."' onClick=\"vi_id('quadroBuscaGrupoPessoa', true, event, null, 300); document.formularioGrupoPessoa.Nome.value='';  valorCampoGrupoPessoa = ''; busca_grupo_pessoa_lista(); document.formularioGrupoPessoa.Nome.focus();\"></td>
													<td class='campo'>
														<input type='text' name='IdGrupoPessoa' value='' style='width:70px' maxlength='11' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='25' onChange='busca_grupo_pessoa(this.value,false,document.formulario.Local.value)' onkeypress=\"mascara(this,event,'int')\"><input class='agrupador' type='".$tipoinputsenha."' name='DescricaoGrupoPessoa' value='' style='width:450px' maxlength='100' readOnly><input type='hidden' name='Senha' value=''>
													</td>
												</tr>
											</table>";
									}
								?>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(108)?></B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(109)?></B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B id='titEnderecoDefault' style='color:#000'><?=dicionario(110)?></B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><b><?=dicionario(111)?></b></td>
										<td class='separador'>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td>
											<select name='Cob_CobrarDespesaBoleto' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='32' style='width: 100px'>
												<option value='0'></option>
												<?
													$sql = "select IdParametroSistema,ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=41 order by IdParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]' ".compara($local_AgruparContratos,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
										<td>
											<select name='AgruparContratos' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='33' style='width: 105px'>
												<option value='0'></option>
												<?
													$sql = "select IdParametroSistema,ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=25 order by IdParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]' ".compara($local_AgruparContratos,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
										<td>
											<select name='IdEnderecoDefault' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='34' style='width: 185px' disabled>
												<option value='0'></option>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
										<td>
											<select name='IdMonitorFinanceiro' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='35' style='width: 100px'>
												<option value=''></option>
												<?
													$sql = "select IdParametroSistema,ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=209 order by IdParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]' ".compara($local_AgruparContratos,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
									</tr>
								</table>
							</td>
						<td class='separador'>&nbsp;</td>
						<td valign='top'>
							<table style='margin-bottom:1px; width:275px;'>
								<tr>
									<td class='descCampo'><B><?=dicionario(112)?></B></td>
								</tr>
							</table>
							<table style='margin:0; padding:2px; height:66px; border:1px #A4A4A4 solid; width:281px;' cellpading='0' cellspacing='0'>
								<tr>
									<td><input type='checkbox' class='checkbox' name='TipoCliente' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='27' checked='true' disabled></td>
									<td style='padding-right:10px' colspan='3'><?=dicionario(113)?></td>
								</tr>
								<tr>
									<td><input type='checkbox' class='checkbox' name='TipoUsuario' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='28'></td>
									<td><?=dicionario(114)?></td>
									<td><input type='checkbox' class='checkbox' name='TipoFornecedor' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='29'></td>
									<td><?=dicionario(115)?></td>
								</tr>
								<tr>
									<td><input type='checkbox' class='checkbox' name='TipoAgenteAutorizado' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='30'></td>
									<td><?=dicionario(116)?></td>
									<td><input type='checkbox' class='checkbox' name='TipoVendedor' value='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='31'></td>
									<td><?=dicionario(117)?></td>
								</tr>
							</table>
							<table style='margin:2px 0 1px 0; width:275px;'>
								<tr>
									<td class='descCampo'><B><?=dicionario(118)?></B></td>
								</tr>
							</table>
							<table style='border:1px #A4A4A4 solid; height:21px; width:281px; margin-bottom:1px'>
								<tr>
									<td style='width:15px'><input type='checkbox' class='checkbox' name='Cob_FormaCorreio' value='S' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='36'></td>
									<td style='padding-right:10px'><?=dicionario(119)?></td>
									<td style='width:15px'><input type='checkbox' class='checkbox' name='Cob_FormaEmail' value='S' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='37' onclick="verifica_email_cobranca()"></td>
									<td style='padding-right:10px'><?=dicionario(104)?></td>
									<td style='width:15px'><input type='checkbox'  class='checkbox' name='Cob_FormaOutro' value='S' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='38'></td>
									<td><?=dicionario(120)?></td>
								</tr>
							</table>
						</td>
					</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(121)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td>
								<select name='ForcarAtualizar' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='39' style='width: 216px'>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from
													ParametroSistema
												where
													IdGrupoParametroSistema=233
												order by
													IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]' ".compara($local_ForcarAtualizar,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td>Via CDA</td>
						</tr>
					</table>
					<?
						$i		=	0;
						$sql	=	"select IdParametroSistema,DescricaoParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and substr(DescricaoParametroSistema,26,31) = 'Ativo' and ValorParametroSistema = 'S' limit 0,4";
						$res	=	mysql_query($sql,$con);
						while($lin	=	mysql_fetch_array($res)){
							$IdParametroSistema[$i]	=	$lin[IdParametroSistema];
							
							$IdTitulo	=	$IdParametroSistema[$i] + 1;
							$sql2	=	"select IdParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and IdParametroSistema=".$IdTitulo;
							$res2	=	mysql_query($sql2,$con);
							$lin2	=	mysql_fetch_array($res2);
							
							$IdObrigatoriedade	=	$IdParametroSistema[$i] + 2;
							$sql3	=	"select IdParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and IdParametroSistema=".$IdObrigatoriedade;
							$res3	=	mysql_query($sql3,$con);
							$lin3	=	mysql_fetch_array($res3);
							
							if($i == 0){
								echo"<table style='margin:0; padding:0'><tr><td class='find'>&nbsp;</td>";
							}else{
								echo"<td class='separador'>&nbsp;</td>";
							}
							
							if($lin3[ValorParametroSistema]=='S'){
								echo"<td class='descCampo'><B>$lin2[ValorParametroSistema]</B></td>";
							}else{
								echo"<td class='descCampo'>$lin2[ValorParametroSistema]</td>";
							}	
							
							$i++;
						}
						
						if($i> 0){
							echo"</TR>";
						}
						
						$j		= 0;
						$cont	= 1;
						while($j < $i){
							if($j == 0){
								echo "<TR><td class='find'>&nbsp;</td>";
							}else{
								echo"<td class='separador'>&nbsp;</td>";
							}
							
							$IdObrigatoriedade	=	$IdParametroSistema[$j] + 2;
							$sql3	=	"select IdParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and IdParametroSistema=".$IdObrigatoriedade;
							$res3	=	mysql_query($sql3,$con);
							$lin3	=	mysql_fetch_array($res3);
							
							$tabindex	=	39 + $j;
							
							echo"<td class='campo'>
									<input type='hidden' name='CampoExtra".$cont."Obrigatorio' value ='$lin3[ValorParametroSistema]'><input type='text' name='CampoExtra".$cont."' value='' style='width:191px' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='$tabindex'>
								</td>
							";
							
							$j++;
							$cont++;
						}
						
						if($i>0){
							echo"</tr></table>";
						}
					?>
				</div>
				<div id='cp_Endereco_Outros'>
					<table id='tableEndereco' cellspacing='0' cellpadding='0' width='100%'>
						<tr><td></td></tr>
					</table>
					<div <?php if(permissaoSubOperacao(1,210,"V") == false){echo "style='display: none;'";}?>>
						<div id='cp_tit' ><?=dicionario(122)?></div>
						<table id='tituloTabelaArquivo'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(123)?>&nbsp;[<a style='cursor:pointer;' onClick='addArquivo(null);'>+</a>]</td>
								<td class='descCampo'>&nbsp;</td>
							</tr>
						</table>
						<table id='tabelaArquivos' style='width:850px;'></table>
						<div id='cpArquivosAnexados' style='margin-top:10px; display:none;'>
							<table class='tableListarCad' id='tabelaArquivosAnexados' cellspacing='0'>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' style='width: 18px'>&nbsp;</td>
									<td><?=dicionario(124)?></td>
									<td><?=dicionario(125)?></td>
									<td><?=dicionario(126)?></td>
									<td><?=dicionario(127)?></td>
									<td class='bt_lista'>&nbsp;</td> 
								</tr>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' colspan='5' id='tabelaValorTotal'><?=dicionario(128)?>: 0</td>
									<td>&nbsp;</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div>
					<div id='cp_tit'><?=dicionario(129)?></div>	
					<table id='cpHistorico' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(130)?></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='HistoricoObs' style='width: 816px;' rows='5' readOnly></textarea>
							</td>
						</tr>
					</table>
				</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><?=dicionario(131)?></td>				
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<textarea name='Obs' style='width: 816px;' rows='5' tabindex='300' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'charObs')" onBlur="Foco(this,'out');"></textarea>
						</td>
					</tr>
				</table>	
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><?=dicionario(132)?></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><?=dicionario(133)?></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><?=dicionario(134)?></td>
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
							<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
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
				<div class='cp_botao'>
					<table style='width:100%'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_relatorio' value='<?=dicionario(142)?>' class='botao' style='width:100px' tabindex='305' onClick="cadastrar('Relatorio')" disabled>
							</td>
							<td class='campo'>
								<input type='button' name='bt_aviso' value='<?=dicionario(143)?>' class='botao' tabindex='306' onClick='tela_aviso()'>
								<input type='button' name='bt_cda' value='<?=dicionario(144)?>' style='width:60px;' class='botao' tabindex='307' onClick='cda()'>
								<input type='button' name='bt_contaDebito' value='<?=dicionario(145)?>' style='width:111px;' class='botao' tabindex='308' onClick="cadastrar('contaDebito')" disabled>
								<input type='button' name='bt_cartaoCredito' value='<?=dicionario(959)?>' style='width:111px;' class='botao' tabindex='308' onClick="cadastrar('cartaoCredito')" disabled>
							</td>
							<td class='campo' style='text-align:right; padding-right:6px'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='309' onClick="cadastrar('inserir')">
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='310' onClick="cadastrar('alterar')">
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='311' onClick="excluir(document.formulario.IdPessoa.value)">
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
				<div id='cp_atualizacao_cadastro' style='display:none;'>
					<div id='cp_tit' style='margin:10px 0 0 0'><?=dicionario(136)?></div>
					<table id='tabelaAtualizacaoCadastro' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:40px;'><?=dicionario(141)?></td>
							<td><?=dicionario(85)?></td>
							<td><?=dicionario(137)?></td>
							<td><?=dicionario(138)?></td>
                            <td><?=dicionario(93)?></td>
							<td><?=dicionario(170)?></td>                          
							<td><?=dicionario(38)?></td>
							<td><?=dicionario(140)?></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='8' id='tabelaAtualizacaoCadastroTotal'></td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<div id='cp_dados_adicionais' style='display:none'>
			<div id="cp_tit">
				<div style="width:848px;">
					<div style="float:left;">Resumo Físico/Financeiro</div>
					<div style="float:right; width:22px;">
						<img id="botaoQuadroConexao" style="float:right; cursor:pointer; margin-right:4px;" onclick="ocultarQuadroConexao(document.getElementById('botaoQuadroConexao'), 'QuadrosAdicionais'); dados_adicionais(document.formulario.IdPessoa.value)" title="Maximizar" alt="Maximizar" src="../../img/estrutura_sistema/ico_seta_down.gif"/>
					</div>&nbsp;
				</div>
			</div>
			<div id='QuadrosAdicionais' style='margin-left: 25px;display: none'>
				<style type='text/css'>
					#cp_dados_Ordem_Servico td,#cp_dados_conta_eventual td{
						border: solid 1px #a4a4a4;
						border-top: none;
						border-left: none;
						margin-left: -5px;
						text-indent: 4px;
						padding-right: 4px;
					}
				</style>
				<table>
					<tr>
						<td>
							<div id='cp_dados_contrato' style='float: left'><B style='color: #C10000'>Dados Contrato</B>
								<table id='f' cellspacing="5" style="border:1px solid #a4a4a4; width:401px;height: 194px">
									<tr>
										<td><b>Data de início do primeiro contrato: </b></td>
										<td id='tdPrimeiroContrato' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Contratos ativos: </b></td>
										<td id='tdContratoAtivo' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Contratos bloqueados: </b></td>
										<td id='tdContratoBloqueado' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Contratos cancelados: </b></td>
										<td id='tdContratoCancelado' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Contratos migrados: </b></td>
										<td id='tdContratoMigrado' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Valor médio dos Contratos: </b></td>
										<td id='tdValorMedioContrato' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Valor total dos Contratos: </b></td>
										<td id='tdValorTotalContrato' style='text-align: right'></td>
									</tr>
								</table>
							</div>
						</td>
						<td>
							<div style='margin-left: 5px;padding-left: 0px' id='dados_conta_receber'>
								<table  cellspacing="5" style="border:1px solid #a4a4a4; width:401px; height: 150px;"><B style='color: #C10000'>Dados Conta Receber</B>
									<tr>
										<td ><b>Maior CR quitado: </b></td>
										<td id='tdCRValorMax' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Menor CR quitado: </b></td>
										<td id='tdCRValorMin' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Média CR quitados últimos (30 dias): </b></td>
										<td id='tdMediaContaReceberMensal' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Média CR quitados últimos (3 meses): </b></td>
										<td id='tdMediaContaReceberTrimestral' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Média CR quitados últimos (6 meses): </b></td>
										<td id='tdMediaContaReceberSemestral' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Média CR quitados últimos (12 meses): </b></td>
										<td id='tdMediaContaReceberAnual' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Valor total dos CR quitados: </b></td>
										<td id='tdValorTotalQuitado' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Valor total dos CR aguardando pagamento: </b></td>
										<td id='tdValorTotalAberto' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Valor total dos CR vencidos: </b></td>
										<td id='tdValorTotalContaReceberVencidos' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Valor média dos CR vencidos: </b></td>
										<td id='tdValorMediaContaReceberVencidos' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Valor total dos CR: </b></td>
										<td id='tdValorContaReceberTotal' style='text-align: right'></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div id='cp_dados_Ordem_Servico' style='float: left'><B style='color: #C10000'>Dados Ordem Serviço</B>
								<table id='f' cellspacing="0" style="border:1px solid #a4a4a4; width:401px;height: 170px">
									<tr>
										<td></td>
										<td style='text-align: right;font-weight: bold'>Quant.</td>
										<td style='text-align: right;font-weight: bold'>Valor Media</td>
										<td style='text-align: right;font-weight: bold'>Valor Total</td>
									</tr>
									<tr>
										<td class='td_Descricao'><b>Qtd. OS últimos (30 dias):</b></td>
										<td id='tdQuantidadeMesalOrdemServico' style='text-align: right'></td>
										<td id='tdMediaMesalOrdemServico' style='text-align: right'></td>
										<td id='tdValorTotalMesalOrdemServico' style='text-align: right'></td>
									</tr>
									<tr>
										<td class='td_Descricao'><b>Qtd. OS últimos (3 meses):</b></td>
										<td id='tdQuantidadeTrimestralOrdemServico' style='text-align: right'></td>
										<td id='tdMediaTrimestralOrdemServico' style='text-align: right'></td>
										<td id='tdValorTotalTrimestralOrdemServico' style='text-align: right'></td>
									</tr>
									<tr>
										<td class='td_Descricao'><b>Qtd. OS últimos (6 meses):</b></td>
										<td id='tdQuantidadeSemestralOrdemServico' style='text-align: right'></td>
										<td id='tdMediaSemestralOrdemServico' style='text-align: right'></td>
										<td id='tdValorTotalSemestralOrdemServico' style='text-align: right'></td>
									</tr>
									<tr>
										<td class='td_Descricao'><b>Qtd. OS últimos (12 meses):</b></td>
										<td id='tdQuantidadeAnualOrdemServico' style='text-align: right'></td>
										<td id='tdMediaAnualOrdemServico' style='text-align: right'></td>
										<td id='tdValorTotalAnualOrdemServico' style='text-align: right'></td>
									</tr>
								</table>
							</div>
						</td>
						<td>
							<div style='margin-left: 5px;padding-left: 0px' id='cp_dados_conta_eventual'>
								<table cellspacing="0" style="border:1px solid #a4a4a4; width:401px; height: 170px;">
									<B style='color: #C10000'>Dados Conta Eventual</B>
									<tr>
										<td></td>
										<td style='text-align: right;font-weight: bold'>Quant.</td>
										<td style='text-align: right;font-weight: bold'>Valor Media</td>
										<td style='text-align: right;font-weight: bold'>Valor Total</td>
									</tr>
									<tr>
										<td><b>Qtd. EV últimos (30 dias): </b></td>
										<td id='tdQuantidadeMensalContaEventual' style='text-align: right'></td>
										<td id='tdMediaMensalContaEventual' style='text-align: right'></td>
										<td id='tdValorTotalMensalContaEventual' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Qtd. EV últimos (3 meses): </b></td>
										<td id='tdQuantidadeTrimestralContaEventual' style='text-align: right'></td>
										<td id='tdMediaTrimestralContaEventual' style='text-align: right'></td>
										<td id='tdValorTotalTrimestralContaEventual' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Qtd. EV últimos (6 meses): </b></td>
										<td id='tdQuantidadeSemestralContaEventual' style='text-align: right'></td>
										<td id='tdMediaSemestralContaEventual' style='text-align: right'></td>
										<td id='tdValorTotalSemestralContaEventual' style='text-align: right'></td>
									</tr>
									<tr>
										<td><b>Qtd. EV últimos (12 meses): </b></td>
										<td id='tdQuantidadeAnualContaEventual' style='text-align: right'></td>
										<td id='tdMediaAnualContaEventual' style='text-align: right'></td>
										<td id='tdValorTotalAnualContaEventual' style='text-align: right'></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/cep.php");
				include("files/busca/pessoa_pais.php");
				include("files/busca/pessoa_estado.php");
				include("files/busca/pessoa_cidade.php");
				include("files/busca/grupo_pessoa.php");
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
		echo "ativaPessoa(".getCodigoInterno(3,5).");";
	}
?>	
	
	function statusInicial(){
		if(document.formulario.Cob_CobrarDespesaBoleto.value == 0){
			document.formulario.Cob_CobrarDespesaBoleto.value = '<?=getCodigoInterno(3,26)?>';
		}
		if(document.formulario.AgruparContratos.value == 0){
			document.formulario.AgruparContratos.value = '<?=getCodigoInterno(3,7)?>';
		}
		if(document.formulario.IdMonitorFinanceiro.value == ''){
			document.formulario.IdMonitorFinanceiro.value = '<?=getCodigoInterno(3,155)?>';
		}
		if(document.formulario.Acao.value == "inserir"){
			document.formulario.ForcarAtualizar.value = '<?=getCodigoInterno(3,171)?>';
		}

		if(document.formulario.Cob_FormaCorreio.checked == false){
			if('<?=getCodigoInterno(3,32)?>' == '1'){
				document.formulario.Cob_FormaCorreio.value 		= 'S';
				document.formulario.Cob_FormaCorreio.checked	= true;	
			}
		}
		if(document.formulario.Cob_FormaEmail.checked == false){
			if('<?=getCodigoInterno(3,33)?>' == '1'){
				document.formulario.Cob_FormaEmail.value 		= 'S';
				document.formulario.Cob_FormaEmail.checked	= true;	
			}
		}
		if(document.formulario.Cob_FormaOutro.checked == false){
			if('<?=getCodigoInterno(3,34)?>' == '1'){
				document.formulario.Cob_FormaOutro.value 		= 'S';
				document.formulario.Cob_FormaOutro.checked	= true;	
			}
		}
		if(document.formulario.Senha.value == ''){
			document.formulario.Senha.value = '<?=getCodigoInterno(3,117)?>';
		}
		
		if(document.formulario.AtivarCadastroResumido.value != ""){
			document.formulario.AtivarCadastroResumido.value = '<?=getCodigoInterno(3,234)?>';
		}
		
		document.getElementById("cp_DataNascimento_Titulo").style.color = "#000";
		
		if(document.formulario.DataNascimento_Obrigatorio.value == 1){
			document.getElementById("cp_DataNascimento_Titulo").style.color = "#c10000";
		}
		if(document.formulario.Cob_FormaEmail.checked){
			document.getElementById("Email").style.color = '#C10000';
		}else{
			document.getElementById("Email").style.color = '#000000';
		}
		
		if(document.formulario.IdPessoa.value == ""){
			document.getElementById("QuadrosAdicionais").style.display = "none";
		}
		
		if(document.formulario.CadastroResumido.value != ""){
			if(document.formulario.VisualizarCadastroResumido.value == 2){
				
				document.getElementById('lb_CadasdroResumido').style.display = 'block';
				document.formulario.CadastroResumido.style.display = 'block';
				
				if(<?=getCodigoInterno(3,234)?> == 1){
					document.formulario.CadastroResumido.options[1].selected=true;
					ativar_cadastro_resumido(document.formulario.CadastroResumido);
				}
				else
					document.formulario.CadastroResumido.options[0].selected=true;
			}else{
				document.getElementById('lb_CadasdroResumido').style.display = 'none';
				document.getElementById('descricao').style.width = '420px';
				document.formulario.CadastroResumido.style.display = 'none';
			}
		}
	}
	function localidadeDefault(){
		if(document.formulario.IdPessoa.value == ''){
			if(document.formulario.CEPDefault.value == ''){
				busca_pessoa_cidade('<?=getCodigoInterno(3,1)?>','<?=getCodigoInterno(3,2)?>','<?=getCodigoInterno(3,3)?>',false,document.formulario.Local.value,1);
			}else{
				busca_pessoa_cep(document.formulario.CEPDefault.value, false, 1);
			}
		}
	} 
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>