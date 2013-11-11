<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');	
	include('files/funcoes.php');	
	include('rotinas/verifica.php');
	
	# Vars Cabeçalho **********************************
	$local_IdPessoa				= $_SESSION["IdPessoa"];
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_Login				= $_SESSION["Login"];
	$local_CPF_CNPJ				= $_SESSION["CPF"];
	
	if($local_IdPessoa != ''){	
		$local_EtapaProxima			= "cadastro_pessoa.php";
		$local_EtapaAnterior		= "menu.php";
	}
	
	# Fim Vars Cabeçalho *******************************
	$local_DescricaoEtapa			= getParametroSistema(84,25);
	$local_Acao 					= $_POST['Acao'];
	$local_Erro						= $_GET['Erro'];
	$local_IdPais					= getCodigoInternoCDA(3,1);
	$local_TipoPessoa				= formatText($_POST['TipoPessoa'],NULL);
	$local_DataNascimento			= $_POST["DataNascimento"];
	$local_Nome						= formatText($_POST['Nome'],NULL);
	$local_RazaoSocial				= formatText($_POST['RazaoSocial'],NULL);
	$local_NomeRepresentante		= formatText($_POST['NomeRepresentante'],NULL);
	$local_RG_IE					= formatText($_POST['RG_IE'],NULL);
	$local_InscricaoMunicipal		= formatText($_POST['InscricaoMunicipal'],NULL);
	$local_Sexo						= $_POST['Sexo'];
	$local_EstadoCivil				= $_POST['EstadoCivil'];
	$local_Endereco					= formatText($_POST['Endereco'],NULL);
	$local_Complemento				= formatText($_POST['Complemento'],NULL);
	$local_Numero					= formatText($_POST['Numero'],NULL);
	$local_Bairro					= formatText($_POST['Bairro'],NULL);
	$local_CEP						= formatText($_POST['CEP'],NULL);	
	$local_IdEstado					= $_POST['IdEstado'];
	$local_IdCidade					= $_POST['IdCidade'];
	$local_Telefone1				= formatText($_POST['Telefone1'],NULL);
	$local_Telefone2				= formatText($_POST['Telefone2'],NULL);
	$local_Telefone3				= formatText($_POST['Telefone3'],NULL);
	$local_Celular					= formatText($_POST['Celular'],NULL);
	$local_Fax						= formatText($_POST['Fax'],NULL);
	$local_Email					= $_POST['Email'];
	$local_Site						= formatText($_POST['Site'],'MI');
	$local_ComplementoTelefone		= formatText($_POST['ComplementoTelefone'],NULL);
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_pessoa.php');
			break;
		case 'alterar':
			include('files/editar/editar_pessoa.php');
			break;
		default:
			$local_Acao 	= 'inserir';
			break;
	}
	
	
	if($local_IdPessoa != ''){
		$sql	=	"select 
						IdPessoa,
						Nome,
						RG_IE,
						CPF_CNPJ,
						Sexo,
						TipoPessoa,
						InscricaoMunicipal,
						DataNascimento,
						EstadoCivil,
						Endereco,
						Complemento,
						Numero,
						Bairro,
						CEP,
						IdPais,
						IdEstado,
						IdCidade,
						Telefone1,
						Telefone2,
						Telefone3,
						Celular,
						Fax,
						Email,
						Site,
						ComplementoTelefone,
						NomeRepresentante,
						RazaoSocial
					from 
						Pessoa 
					where 
						IdPessoa =".$local_IdPessoa;
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
		
		$_SESSION["IdPessoa"]			= $lin[IdPessoa];
		$local_Nome						= $lin[Nome];
		$local_CPF_CNPJ					= $lin[CPF_CNPJ];
		$local_RG_IE					= $lin[RG_IE];
		$local_DataNascimento			= dataConv($lin[DataNascimento],'Y-m-d','d/m/Y');
		$local_Sexo						= $lin[Sexo];
		$local_EstadoCivil				= $lin[EstadoCivil];
		$local_Endereco					= $lin[Endereco];
		$local_Complemento				= $lin[Complemento];
		$local_Numero					= $lin[Numero];
		$local_Bairro					= $lin[Bairro];
		$local_CEP						= $lin[CEP];	
		$local_IdPais					= $lin[IdPais];
		$local_IdEstado					= $lin[IdEstado];
		$local_IdCidade					= $lin[IdCidade];
		$local_Telefone1				= $lin[Telefone1];
		$local_Telefone2				= $lin[Telefone2];
		$local_Telefone3				= $lin[Telefone3];
		$local_Celular					= $lin[Celular];
		$local_Fax						= $lin[Fax];
		$local_Email					= $lin[Email];
		$local_Site						= $lin[Site];
		$local_InscricaoMunicipal		= $lin[InscricaoMunicipal];
		$local_TipoPessoa				= $lin[TipoPessoa];
		$local_ComplementoTelefone		= $lin[ComplementoTelefone];
		$local_NomeRepresentante		= $lin[NomeRepresentante];
		$local_RazaoSocial				= $lin[RazaoSocial];
		$local_Acao						= 'alterar';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<?
			include ("files/header.php");
		?>
	</head>	
	<body>
		<div id='container'>
			<?
				include ("files/cabecalho.php");
			?>	
			<div id='conteudo'>
				<p class='titulo'><B><?=formTexto(getParametroSistema(84,26))?></B></p>
				<form action='<?=$local_EtapaProxima?>' name='formulario' method='post'>
					<input type='hidden' name='Acao' 				value='<?=$local_Acao?>'>
					<input type='hidden' name='Erro' 				value=<?=$local_Erro?>>
					<input type='hidden' name='Local' 				value='Pessoa'>		
					<input type='hidden' name='IdPessoa' 			value='<?=$local_IdPessoa?>' />
					<input type='hidden' name='IdPais' 				value='<?=$local_IdPais?>' />
					<input type='hidden' name='CPF_CNPJ' 			value='<?=$local_CPF_CNPJ?>' />
					<input type='hidden' name='TipoPessoa' 			value='<?=$local_TipoPessoa?>' />
					<fieldset>
						<legend>Dados Pessoais</legend>
						<div id='carregando'>carregando...</div>
						<table>
							<tr>
								<td>
									<table>							
										<tr>
											<th><B id='cp_Nome'>Nome<B></th>
											<th>&nbsp;</th>										
											<th id='cp_Sexo'>Sexo</th>
										</tr>
										<tr>
											<td>
												<input class='campo' type='text' name='Nome' value='<?=$local_Nome?>' style='width:280px'  maxlength='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
											</td>
											<td class='separador'>&nbsp;</td>
											<td id='input_Sexo'>												
												<select class='campo' name='Sexo' style='width:47px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
													<option value='0'></option>
														<?
															$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=8 order by ValorParametroSistema";
															$res = @mysql_query($sql,$con);
															while($lin = @mysql_fetch_array($res)){
																echo"<option value='$lin[IdParametroSistema]' ".compara($local_Sexo,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
															}
														?>
												</select>						
											</td>
										</tr>
									</table>
									<table id='table_razao_social'>							
										<tr>
											<th><B>Razão Social<B></th>
										</tr>
										<tr>
											<td>
												<input class='campo' type='text' name='RazaoSocial' value='<?=$local_RazaoSocial?>' style='width:348px'  maxlength='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
											</td>
										</tr>						
										<tr>
											<th><B>Nome Representante<B></th>
										</tr>
										<tr>
											<td>
												<input class='campo' type='text' name='NomeRepresentante' value='<?=$local_NomeRepresentante?>' style='width:348px'  maxlength='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>
											</td>
										</tr>
									</table>
									<table>
										<tr>
											<th id='cp_RG_IE'>RG</th>
											<th>&nbsp;</th>
											<th><B id='cp_DataNascimento' style='color:#000'>Data Nascim.</B></th>
											<th>&nbsp;</th>
											<th id='cp_EstadoCivil'>Estado Civil</th>
											<th id='cp_InscricaoMunicipal'>Insc. Municipal</th>
										</tr>
										<tr>
											<td>
												<input class='campo' type='text' name='RG_IE' value='<?=$local_RG_IE?>' style='width:105px' maxlength='20' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  tabindex='5'>
											</td>
											<td class='separador'>&nbsp;</td>
											<td>
												<input class='campo' type='text' name='DataNascimento' autocomplete="off" value='<?=$local_DataNascimento?>' style='width:70px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validar_Data('cp_DataNascimento',this.value)" tabindex='6' onkeypress="mascara(this,event,'date')">
											</td>
											<td class='separador'>&nbsp;</td>
											<td id='input_EstadoCivil'>												
												<select class='campo' name='EstadoCivil' style='width:124px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7'>
													<option value='0'>Selecione</option>
													<?	
														$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=9 order by ValorParametroSistema";
														$res = @mysql_query($sql,$con);
														while($lin = @mysql_fetch_array($res)){
															echo"<option value='$lin[IdParametroSistema]' ".compara($local_EstadoCivil,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
														}
													?>
												</select>																	
											</td>
											<td id='input_InscricaoMunicipal'> 
												<input class='campo' type='text' name='InscricaoMunicipal' autocomplete="off" value='<?=$local_InscricaoMunicipalo?>' style='width:117px' maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8'>
											</td>
										</tr>
									</table>
									<table>
										<tr>
											<th>Fone Residencial</th>
											<th>&nbsp;</th>
											<th>Fone Comercial(1)</th>
											<th>&nbsp;</th>
											<th>Fone Comercial(2)</th>
										</tr>
										<tr>
											<td>
												<input class='campo' type='text' name='Telefone1' value='<?=$local_Telefone1?>' style='width:97px' maxlength='13' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9'  onChange="verificaFone(this)">
											</td>
											<td class='separador'>&nbsp;</td>
											<td>
												<input class='campo' type='text' name='Telefone2' value='<?=$local_Telefone2?>' style='width:97px' maxlength='13' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10'  onChange="verificaFone(this)">
											</td>
											<td class='separador'>&nbsp;</td>
											<td>
												<input class='campo' type='text' name='Telefone3' value='<?=$local_Telefone3?>' style='width:97px' maxlength='13' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'  onChange="verificaFone(this)">
											</td>
										</tr>
									</table>
									<table>
										<tr>
											<th>Celular</th>
											<th>&nbsp;</th>
											<th>Fax</th>
											<th>&nbsp;</th>
											<th>Complemen. Fone</th>
										</tr>
										<tr>
											<td>
												<input class='campo' type='text' name='Celular' value='<?=$local_Celular?>' style='width:97px' maxlength='13' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13'  onChange="verificaFone(this)">
											</td>
											<td class='separador'>&nbsp;</td>
											<td>
												<input class='campo' type='text' name='Fax' value='<?=$local_Fax?>' style='width:97px' maxlength='13' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='14'  onChange="verificaFone(this)">
											</td>
											<td class='separador'>&nbsp;</td>
											<td>
												<input class='campo' type='text' name='ComplementoTelefone' value='<?=$local_ComplementoTelefone?>' style='width:98px' maxlength='18' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15'>
											</td>
										</tr>
									</table>
									<table>							
										<tr>
											<th>E-mail</th>
										</tr>
										<tr>
											<td>
												<input type='text' name='Email' value='<?=$local_Email?>' style='width:348px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='16'>
											</td>
										</tr>
									</table>
									<table>							
										<tr>
											<th>Site</th>
										</tr>
										<tr>
											<td>
												<input type='text' name='Site' value='<?=$local_Site?>' style='width:348px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='17'>
											</td>
										</tr>
									</table>
								</td>															
							</tr>
						</table>
					</fieldset>
					<fieldset>
						<legend>Endereço</legend>
						<div id='carregando2'>carregando...</div>
						<table>
							<tr>
								<td>
																		
									<table>							
										<tr>
											<th><B id='cp_Estado'>Estado</B></th>
											<th>&nbsp;</th>
											<th><B id='cp_Cidade'>Cidade</B></th>
										</tr>
										<tr>
											<td>
												<select name='IdEstado' style='width:50px' onChange="verifica_cidade(document.formulario.IdPais.value,this.value)"   onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='18'>
													<option value='0' selected = 'selected'>UF</option>
													<?
														$sql	=	"select IdEstado,SiglaEstado from Pais,Estado where Estado.IdPais = Pais.IdPais and Estado.IdPais = $local_IdPais order by SiglaEstado";
														$res	= 	mysql_query($sql,$con);
														while($lin = mysql_fetch_array($res)){
															echo"<option value=$lin[IdEstado] ".compara($local_IdEstado,$lin[IdEstado],"selected", "").">$lin[SiglaEstado]</option>\n";
														}
													?>
												</select>
											</td>
											<td class='separador'>&nbsp;</td>
											<td>
												<select class='campo' name='IdCidade' style='width:282px;'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='19'>
													<option value='0'>Selecione</option>
													<?
														if($local_IdPais != "" && $local_IdEstado != "" && $local_IdCidade != ""){
															$sql = "select Cidade.IdCidade,Cidade.NomeCidade from Pais,Estado,Cidade where Cidade.IdPais = $local_IdPais and Cidade.IdEstado = $local_IdEstado and Estado.IdEstado = Cidade.IdEstado";
															$res = mysql_query($sql,$con);
															while($lin = mysql_fetch_array($res)){	
																echo"<option value=$lin[IdCidade] ".compara($local_IdCidade,$lin[IdCidade],"selected", "").">$lin[NomeCidade]</option>\n";
															}
														}
													?>
												</select>
											</td>
										</tr>
									</table>
									<table>							
										<tr>
											<th><B id='cp_Endereco'>Endereço</B></th>
											<th>&nbsp;</th>
											<th><B>Número</B></th>
										</tr>
										<tr>
											<td>
												<input class='campo' type='text' name='Endereco' value='<?=$local_Endereco?>' style='width:265px' maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='20'>
											</td>
											<td class='separador'>&nbsp;</td>
											<td>
												<input class='campo' type='text' name='Numero' value='<?=$local_Numero?>' style='width:55px' maxlength='5'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='21'>
											</td>
										</tr>
									</table>
									<table>							
										<tr>
											<th>Complemento</th>
										</tr>
										<tr>
											<td>
												<input class='campo' type='text' name='Complemento' value='<?=$local_Complemento?>' style='width:348px' maxlength='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='22'>
											</td>
										</tr>
									</table>
									<table>
										<tr>
											<th>Bairro</th>
											<th>&nbsp;</th>
											<th><B id='cp_CEP'>CEP</B></th>
										</tr>
										<tr>
											<td>
												<input class='campo' type='text' name='Bairro' value='<?=$local_Bairro?>' style='width:240px' maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='23'>
											</td>
											<td class='separador'>&nbsp;</td>
											<td>
												<input class='campo' type='text' name='CEP' value='<?=$local_CEP?>' style='width:80px' maxlength='9' onkeypress="mascara(this,event,'cep')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='24'>
											</td>
										</tr>
									</table>
								</td>															
							</tr>
						</table>
						<table style='margin-left:-5px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td><h2 id='helpText' name='helpText'>&nbsp;</h2></td>
							</tr>
						</table>
					</fieldset>				
					<h1 style='background-color:<?=getParametroSistema(84,11)?>'><?=getParametroSistema(84,27)?></h1>
					<table id='botoes' style='background-color:<?=getParametroSistema(84,11)?>'>
						<tr>
							<td class='voltar' style='width:50%;'>&nbsp;
								<?
									if($local_EtapaAnterior != ""){
										echo "<input type='image' src='img/ico_voltar_text.gif' name='Voltar' onClick=\"mudaAction('$local_EtapaAnterior',true)\" />";
									}
								?>
							</td>
							<td class='proximo'>
								<?
								//	if($local_IdPessoa != ''){
										echo"<input type='image' src='img/ico_salvar_text.gif'  name='ProximaEtapa' onClick=\"return mudaAction('$local_EtapaProxima',validar())\" tabindex='25'/>";
								//	}else{
								//		echo"<input type='image' src='img/ico_avancar_text.gif' name='ProximaEtapa' onClick=\"javascript:return mudaAction('$local_EtapaProxima',validar())\" tabindex='25'/>";
								//	}
								?>
							</td>
						</tr>
					</table>				
				</form>
			</div>
			<?
				include("files/rodape.php");
			?>
		</div>	
	</body>
</html>
<script type="text/javascript">
	function validar(){
		if(document.formulario.Nome.value==''){						
			mensagens(1);
			document.formulario.Nome.focus();
			return false;
		}
		if(document.formulario.TipoPessoa.value == 1){
			if(document.formulario.RazaoSocial.value==''){						
				mensagens(1);
				document.formulario.RazaoSocial.focus();
				return false;
			}
			if(document.formulario.NomeRepresentante.value==''){						
				mensagens(1);
				document.formulario.NomeRepresentante.focus();
				return false;
			}
		}
		if(document.formulario.DataNascimento.value!=''){
			if(validar_Data('cp_DataNascimento',document.formulario.DataNascimento.value) == false){
				mensagens(27);
				document.formulario.DataNascimento.focus();
				return false;
			}
		}
		if(document.formulario.Email.value!=''){
			if(isEmail(document.formulario.Email.value) == false){
				mensagens(12);
				document.formulario.Email.focus();
				return false;
			}
		}
		if(document.formulario.IdEstado.value==0){				
			mensagens(1);
			document.formulario.IdEstado.focus();
			return false;
		}
		if(document.formulario.IdCidade.value==0){				
			mensagens(1);
			document.formulario.IdCidade.focus();
			return false;
		}
		if(document.formulario.Endereco.value==''){					
			mensagens(1);
			document.formulario.Endereco.focus();
			return false;
		}
		if(document.formulario.Numero.value==''){					
			mensagens(1);
			document.formulario.Numero.focus();
			return false;
		}
		if(document.formulario.CEP.value==''){					
			mensagens(1);
			document.formulario.CEP.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.Nome.focus();
		
		if(document.formulario.IdPessoa.value == ""){
			var temp = "", tipo = "", cpf="";
			CPF_CNPJ = retiraCaracter(retiraCaracter(retiraCaracter(document.formulario.CPF_CNPJ.value, '.'), '-'),'/');
	    
		    switch(CPF_CNPJ.length){
				case 11:
					//CPF / Fisica
					document.formulario.TipoPessoa.value = 2;
					break;
				case 14:
					//CNPJ / Juridica
					document.formulario.TipoPessoa.value = 1;
					break;
			}
		}
		switch(document.formulario.TipoPessoa.value){
			case '1':
				document.getElementById('cp_Nome').innerHTML						=	"Nome Fantasia";
				document.getElementById('cp_Sexo').style.display					=	"none";
				document.getElementById('input_Sexo').style.display					=	"none";
				document.formulario.Nome.style.width								=	"348px";
				document.getElementById('table_razao_social').style.display			=	"block";
				document.getElementById('cp_RG_IE').innerHTML						=	"Insc. Estadual";
				document.getElementById('cp_DataNascimento').innerHTML				=	"Data Fund.";
				document.getElementById('cp_EstadoCivil').style.display				=	"none";
				document.getElementById('input_EstadoCivil').style.display			=	"none";
				document.getElementById('cp_InscricaoMunicipal').style.display		=	"block";
				document.getElementById('input_InscricaoMunicipal').style.display	=	"block";
				break;
			case '2':
				document.getElementById('cp_Nome').innerHTML						=	"Nome";
				document.getElementById('cp_Sexo').style.display					=	"block";
				document.getElementById('input_Sexo').style.display					=	"block";
				document.formulario.Nome.style.width								=	"280px";
				document.getElementById('table_razao_social').style.display			=	"none";
				document.getElementById('cp_RG_IE').innerHTML						=	"RG";
				document.getElementById('cp_DataNascimento').innerHTML				=	"Data Nasc.";
				document.getElementById('cp_EstadoCivil').style.display				=	"block";
				document.getElementById('input_EstadoCivil').style.display			=	"block";
				document.getElementById('cp_InscricaoMunicipal').style.display		=	"none";
				document.getElementById('input_InscricaoMunicipal').style.display	=	"none";
				break;
		}
	}	
	inicia();
	verificaErro();
</script>
