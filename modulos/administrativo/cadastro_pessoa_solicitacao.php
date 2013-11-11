<?
	$localModulo		=	1;
	$localOperacao		=	70;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdPessoaSolicitacao	= formatText($_POST['IdPessoaSolicitacao'],NULL);
	$local_IdPessoa	 			= formatText($_POST['IdPessoa'],NULL);
	$local_TipoPessoa			= formatText($_POST['TipoPessoa'],NULL);
	$local_Nome					= formatText($_POST['Nome'],NULL);
	$local_NomeFantasia			= formatText($_POST['NomeFantasia'],NULL);
	$local_NomeRepresentante	= formatText($_POST['NomeRepresentante'],NULL);
	$local_RazaoSocial			= formatText($_POST['RazaoSocial'],NULL);
	$local_DataNascimento		= formatText(dataConv($_POST['DataNascimento'],"d/m/Y","Y-m-d"),NULL);
	$local_DataFundacao			= formatText(dataConv($_POST['DataFundacao'],"d/m/Y","Y-m-d"),NULL);
	$local_Sexo					= formatText($_POST['Sexo'],NULL);
	$local_IdPais				= formatText($_POST['IdPais'],NULL);
	$local_IdEstado				= formatText($_POST['IdEstado'],NULL);
	$local_IdCidade				= formatText($_POST['IdCidade'],NULL);
	$local_RG_IE				= formatText($_POST['RG_IE'],NULL);
	$local_InscricaoEstadual	= formatText($_POST['InscricaoEstadual'],NULL);
	$local_CPF_CNPJ				= formatText($_POST['CPF_CNPJ'],NULL);
	$local_EstadoCivil			= formatText($_POST['EstadoCivil'],NULL);
	$local_ImovelProprio		= formatText($_POST['ImovelProprio'],NULL);
	$local_InscricaoMunicipal	= formatText($_POST['InscricaoMunicipal'],NULL);
	$local_CEP					= formatText($_POST['CEP'],NULL);
	$local_Endereco				= formatText($_POST['Endereco'],NULL);
	$local_Complemento			= formatText($_POST['Complemento'],NULL);
	$local_Numero				= formatText($_POST['Numero'],NULL);
	$local_Bairro				= formatText($_POST['Bairro'],NULL);
	$local_Telefone1			= formatText($_POST['Telefone1'],NULL);
	$local_Telefone2			= formatText($_POST['Telefone2'],NULL);
	$local_Telefone3			= formatText($_POST['Telefone3'],NULL);
	$local_Celular				= formatText($_POST['Celular'],NULL);
	$local_Fax					= formatText($_POST['Fax'],NULL);
	$local_ComplementoTelefone	= formatText($_POST['ComplementoTelefone'],NULL);
	$local_Email				= formatText($_POST['Email'],getParametroSistema(4,5));
	$local_Site					= formatText($_POST['Site'],getParametroSistema(4,6));
	$local_IdStatus				= trim($_POST['IdStatus']);
	$local_CampoExtra1			= formatText($_POST['CampoExtra1'],NULL);
	$local_CampoExtra2			= formatText($_POST['CampoExtra2'],NULL);
	$local_CampoExtra3			= formatText($_POST['CampoExtra3'],NULL);
	$local_CampoExtra4			= formatText($_POST['CampoExtra4'],NULL);
	$local_QtdEndereco			= formatText($_POST['QtdEndereco'],NULL);
	$local_IdEnderecoDefault	= formatText($_POST['IdEnderecoDefault'],NULL);

	
	if($local_TipoPessoa == ""){
		$local_TipoPessoa	=	getCodigoInterno(3,5);
	}
	
	if($local_Site == ""){
		$local_Site = "http://";
	}
	
	if($_GET['IdPessoaSolicitacao']!=''){
		$local_IdPessoaSolicitacao	=	$_GET['IdPessoaSolicitacao'];
	}
	
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_pessoa_solicitacao.php');
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
		<script type = 'text/javascript' src = 'js/pessoa_solicitacao.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_solicitacao_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/cep_default.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('<?=dicionario(218)?>')">
		<?	include('filtro_pessoa_solicitacao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_pessoa_solicitacao.php'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='PessoaSolicitacao'>
				<input type='hidden' name='CPF_CNPJ_Obrigatorio' value='<?=getCodigoInterno(11,2)?>'>
				<input type='hidden' name='Telefone_Obrigatorio' value='<?=getCodigoInterno(11,3)?>'>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='QtdEnderecoAnterior' value=''>
				<input type='hidden' name='QtdEnderecoAuxAnterior' value=''>
				<input type='hidden' name='QtdEndereco' value=''>
				<input type='hidden' name='QtdEnderecoAux' value=''>
				<input type='hidden' name='IdEnderecoDefaultTemp' value=''>
				<input type='hidden' name='DescricaoEndereco1' value='<?=getCodigoInterno(3,87)?>'>
				<input type='hidden' name='DescricaoEndereco2' value='<?=getCodigoInterno(3,88)?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Solicitação</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tipo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cp_CPF_CNPJ_Titulo' style='color:#000'>CPF_CNPJ</B></td>
							<td class='find'></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPessoaSolicitacao' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_solicitacao(this.value,'false',document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPessoa' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoPessoa' style='width:90px' disabled>
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
								<input type='text' id='cp_CPF_CNPJ_Input' name='CPF_CNPJ' value='' style='width:170px' maxlength='18' readOnly>
							</td><td class='find'>&nbsp;</td>
							<td class='descricao' STYLE='width:342px; text-align:right;'><B id='cpStatus'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCadastrais'>
					<div id='cp_tit'>Dados Solicitados</div>
					<div id='cp_Fisica'>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Nome Pessoa</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Sexo</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B style='color:#000'  id='cp_DataNascimento_Titulo'>Data Nasc.</B></td>
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
									<select name='Sexo' style='width: 60px'disabled>
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
									<input type='text' name='DataNascimento' value='' style='width:90px' maxlength='10' onChange="validar_Data(this.value,'cp_DataNascimento_Titulo')" onkeypress="mascara(this,event,'date')" readOnly>
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
											<td class='descCampo'><B>Razão Social</B></td>
											<td class='separador'>&nbsp;</td>
											<td class='descCampo'><B>Nome Fantasia</B></td>
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
								<td class='descCampo'><B style='color:#000'  id='cp_DataFundacao_Titulo'>Data Fundação</B></td>
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
									<input type='text' name='DataFundacao' value='' style='width:90px' maxlength='10' onChange="validar_Data(this.value,'cp_DataFundacao_Titulo')" onkeypress="mascara(this,event,'date')" readOnly>
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
							<td class='separador'>&nbsp;</td>				
							<td class='descCampo'>Complemento Fone</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone1' value='' style='width:122px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2' value='' style='width:122px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3' value='' style='width:122px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular' value='' style='width:122px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Fax' value='' style='width:122px' maxlength='18' readOnly>
							</td>			
							<td class='separador'>&nbsp;</td>			
							<td class='campo'>
								<input type='text' name='ComplementoTelefone' value='' style='width:121px' maxlength='30' readOnly>
							</td>				
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>	
							<td class='descCampo'><B style='color:#000000' id='Email'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Site (url)</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><span id='titEnderecoDefault'>Endereço Principal</span></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:227px' maxlength='255' autocomplete='off'  onBlur="validar_Email(this.value,'Email')" readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>					
							<td class='campo'>
								<input type='text' name='Site' value='' style='width:227px' maxlength='100' readOnly>
							</td>
							<td class='find' onClick='JsHttp(document.formulario.Site.value)'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Acessar Site'></td>
							<td class='separador'>&nbsp;</td>
							<td>
								<select name='IdEnderecoDefault' style='width: 290px' disabled>
									<option value='0'></option>
								</select>
							</td>
						</tr>
					</table>
					<?
						$sql	=	"select
										IdParametroSistema,
										DescricaoParametroSistema,
										ValorParametroSistema
									from
										GrupoParametroSistema,
										ParametroSistema
									where
										GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and
										GrupoParametroSistema.IdGrupoParametroSistema = 2 and
										substr(DescricaoParametroSistema,26,31) = 'Ativo' and
										ValorParametroSistema = 'S'";
						$res	=	mysql_query($sql,$con);
						$quant	=	mysql_num_rows($res);
						
						$float	=	"";
						
						if($quant > 0){
							
							if($quant < 3){
								$float	=	"style='float:LEFT'";
							}
							
							echo"<table  $float><tr>";
							
							$i=1;
							while($lin	=	mysql_fetch_array($res)){
								$IdTitulo	=	$lin[IdParametroSistema] + 1;
								$sql2	=	"select
												IdParametroSistema,
												ValorParametroSistema
											from
												GrupoParametroSistema,
												ParametroSistema
											where
												GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and
												GrupoParametroSistema.IdGrupoParametroSistema = 2 and
												IdParametroSistema = ".$IdTitulo;
								$res2	=	mysql_query($sql2,$con);
								$lin2	=	mysql_fetch_array($res2);
							
								$IdObrigatoriedade	=	$lin[IdParametroSistema] + 2;
								$sql3	=	"select
												IdParametroSistema,
												ValorParametroSistema
											from
												GrupoParametroSistema,
												ParametroSistema
											where
												GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and
												GrupoParametroSistema.IdGrupoParametroSistema = 2 and
												IdParametroSistema = ".$IdObrigatoriedade;
								$res3	=	mysql_query($sql3,$con);
								$lin3	=	mysql_fetch_array($res3);
							
								if($i==1){
									echo "<td class='find'>&nbsp;</td>";
								}else{
									echo "<td class='separador'>&nbsp;</td>";
								}
								
								echo"<input type='hidden' name='CampoExtra".$i."Obrigatorio' value ='$lin3[ValorParametroSistema]' readOnly>";																
						
								if($lin3[ValorParametroSistema]=='S'){
									echo"<td class='descCampo'><B>$lin2[ValorParametroSistema]</B></td>";
								}else{
									echo"<td class='descCampo'>$lin2[ValorParametroSistema]</td>";
								}			
										
								$i++;
							}
						
							echo"</tr><tr>";
							$i=1;
							while($i <= $quant){
								$Value	=	$lin["CampoExtra".$i];
							
								if($i==1){
									echo "<td class='find'>&nbsp;</td>";
								}else{
									echo "<td class='separador'>&nbsp;</td>";
								}
								
								echo"
									<td class='campo'>
										<input type='text' name='CampoExtra$i' value='$Value' style='width:191px' maxlength='255' readOnly>
									</td>
								";
								$i++;
							}
						
							echo"</tr></table>";
						}
						
						if($quant < 3){
							$float	=	"style='float:TOP'";
						}
					
					?>
					<table id='tableEndereco' style='width:100%;' cellpadding='0' cellspacing='0'>
						<tr>
							<td></td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usuário Solicitação</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Solicitação</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário Alteração</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Alteração</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>IP</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:153px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:153px'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAprovacao' value='' style='width:153px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAprovacao' value='' style='width:153px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IP' value='' style='width:136px' readOnly>
							</td>									
						</tr>
					</table>
				</div>	
				<div>
					<div id='cp_tit'>Dados Anteriores</div>	
					<div id='cp_FisicaAnterior'>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Nome Pessoa</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Sexo</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B style='color:#000'  id='cp_DataNascimento_Titulo'>Data Nasc.</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Estado Civil</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>RG</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='NomeAnterior' value='' style='width:330px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='SexoAnterior' style='width: 60px' disabled>
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
									<input type='text' name='DataNascimentoAnterior' value='' style='width:90px' maxlength='10' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='EstadoCivilAnterior' style='width:130px' disabled>
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
									<input type='text' name='RG_IEAnterior' value='' maxlength='30' style='width:150px' readOnly>
								</td>
							</tr>
						</table>
					</div>
					<div id='cp_JuridicaAnterior'>
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
												<input type='text' name='NomeFantasiaAnterior' value='' style='width:400px' maxlength='100' readOnly>
											</td>
											<td class='separador'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='RazaoSocialAnterior' value='' style='width:399px' maxlength='100' readOnly>
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
												<input type='text' name='RazaoSocialAnterior' value='' style='width:399px' maxlength='100' readOnly>
											</td>
											<td class='separador'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='NomeFantasiaAnterior' value='' style='width:400px' maxlength='100' readOnly>
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
								<td class='descCampo'><B style='color:#000'  id='cp_DataFundacao_Titulo'>Data Fundação</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='NomeRepresentanteAnterior' value='' style='width:400px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='InscricaoEstadualAnterior' value='' maxlength='20' style='width:138px' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='InscricaoMunicipalAnterior' value='' maxlength='30' style='width:137px' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataFundacaoAnterior' value='' style='width:90px' maxlength='10' readOnly>
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
							<td class='separador'>&nbsp;</td>				
							<td class='descCampo'>Complemento Fone</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone1Anterior' value='' style='width:122px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2Anterior' value='' style='width:122px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3Anterior' value='' style='width:122px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CelularAnterior' value='' style='width:122px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='FaxAnterior' value='' style='width:122px' maxlength='18' readOnly>
							</td>				
							<td class='separador'>&nbsp;</td>			
							<td class='campo'>
								<input type='text' name='ComplementoTelefoneAnterior' value='' style='width:121px' maxlength='30' readOnly>
							</td>		
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>	
							<td class='descCampo'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Site (url)</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Endereço Principal</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='EmailAnterior' value='' style='width:227px' maxlength='255' autocomplete='off' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.EmailAnterior.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>					
							<td class='campo'>
								<input type='text' name='SiteAnterior' value='' style='width:227px' maxlength='100' readOnly>
							</td>
							<td class='find' onClick='JsHttp(document.formulario.SiteAnterior.value)'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Acessar Site'></td>
							<td class='separador'>&nbsp;</td>
							<td>
								<select name='IdEnderecoDefaultAnterior' style='width: 290px' disabled>
									<option value='0'></option>
								</select>
							</td>
						</tr>
					</table>
					<?
						$sql	=	"select IdParametroSistema,DescricaoParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and substr(DescricaoParametroSistema,26,31) = 'Ativo' and ValorParametroSistema = 'S'";
						$res	=	mysql_query($sql,$con);
						$quant	=	mysql_num_rows($res);
						
						if($quant > 0){
							
							echo"<table><tr>";
							
							$i=1;
							while($lin	=	mysql_fetch_array($res)){
								$IdTitulo	=	$lin[IdParametroSistema] + 1;
								$sql2	=	"select IdParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and IdParametroSistema=".$IdTitulo;
								$res2	=	mysql_query($sql2,$con);
								$lin2	=	mysql_fetch_array($res2);
							
								$IdObrigatoriedade	=	$lin[IdParametroSistema] + 2;
								$sql3	=	"select IdParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and IdParametroSistema=".$IdObrigatoriedade;
								$res3	=	mysql_query($sql3,$con);
								$lin3	=	mysql_fetch_array($res3);
							
								if($i==1){
									echo "<td class='find'>&nbsp;</td>";
								}else{
									echo "<td class='separador'>&nbsp;</td>";
								}
								
								echo"<td class='descCampo'>$lin2[ValorParametroSistema]</td>";
										
								$i++;
							}
						
							echo"</tr><tr>";
							$i=1;
							while($i <= $quant){
								$Value	=	$lin["CampoExtra".$i];
							
								if($i==1){
									echo "<td class='find'>&nbsp;</td>";
								}else{
									echo "<td class='separador'>&nbsp;</td>";
								}
								
								$tab	=	26 + $i;
								
								echo"
									<td class='campo'>
										<input type='text' name='CampoExtra".$i."Anterior' value='$Value' style='width:191px' maxlength='255' readOnly>
									</td>
								";
								$i++;
							}
						
							echo"</tr></table>";
						}
					?>
					<table id='tableEnderecoAnterior' style='width:100%;' cellpadding='0' cellspacing='0'>
						<tr>	
							<td></td>
						</tr>
					</table>	
				</div>				
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_confirmar' value='Autorizar' class='botao' tabindex='2' onClick="cadastrar('2')">
								<input type='button' name='bt_recusar' value='Recusar' class='botao' tabindex='3' onClick="cadastrar('3')">
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
				include("files/busca/cep.php");
				include("files/busca/pais.php");
				include("files/busca/estado.php");
				include("files/busca/cidade.php");
			?>
		</div>
	</body>
</html>
<script language='JavaScript' type='text/javascript'> 
<?
	if($local_IdPessoaSolicitacao!=''){
		echo "busca_solicitacao($local_IdPessoaSolicitacao,false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";	
	}else{
		echo "ativaPessoa(".getCodigoInterno(3,5).");";
	}
?>	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
