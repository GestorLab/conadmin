<?
	$localModulo		= 1;
	$localOperacao		= 140;
	$localSuboperacao	= "V";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_IdContaReceberAgrupador		= $_POST['IdContaReceberAgrupador'];
	$local_IdPessoa						= $_POST["IdPessoa"];
	$local_IdPessoaEndereco				= $_POST['IdPessoaEndereco'];
	$local_IdContaReceberAgrupados		= $_POST['IdContaReceberAgrupados'];
	$local_QtdParcela					= $_POST['QtdParcela'];
	$local_DataVencimento				= formatText($_POST['DataVencimento'], NULL);
	$local_OcultarReferencia			= $_POST['OcultarReferencia'];
	$local_IdLocalCobranca				= $_POST['IdLocalCobranca'];
	$local_ValorTaxaReImpressaoBoleto	= (float)(str_replace(",", ".", $_POST['ValorTaxaReImpressaoBoleto']));
	$local_ValorOutrasDespesas			= (float)(str_replace(",", ".", $_POST['ValorOutrasDespesas']));
	$local_ValorJurosVencimento			= (float)(str_replace(",", ".", $_POST['ValorJurosVencimento']));
	$local_ValorMoraMulta				= (float)(str_replace(",", ".", $_POST['ValorMoraMulta']));
	$local_ValorVencimento				= (float)(str_replace(",", ".", $_POST['ValorVencimento']));
	$local_ValorDespesaLocalCobranca	= (float)(str_replace(",", ".", $_POST['ValorDespesaLocalCobranca']));
	$local_ValorDescontoVencimento		= (float)(str_replace(",", ".", $_POST['ValorDescontoVencimento']));
	$local_ValorFinalVencimento			= (float)(str_replace(",", ".", $_POST['ValorFinalVencimento']));
	
	if($local_IdPessoa == ''){
		$local_IdPessoa					= $_POST["IdPessoaF"];
	}
	
	if($local_IdContaReceberAgrupador == ''){
		$local_IdContaReceberAgrupador	= $_GET["IdContaReceberAgrupador"];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_agrupar_conta_receber.php');
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
		<link rel='stylesheet' type='text/css' href='../../classes/calendar/calendar-blue.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/mascara_real.js'></script>
		<script type='text/javascript' src='../../js/val_data.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='../../classes/calendar/calendar.js'></script>
		<script type='text/javascript' src='../../classes/calendar/calendar-setup.js'></script>
		<script type='text/javascript' src='../../classes/calendar/calendar-br.js'></script>
		<script type='text/javascript' src='js/pessoa_default.js'></script>
		<script type='text/javascript' src='js/conta_receber_default.js'></script>
		<script type='text/javascript' src='js/agrupar_conta_receber.js'></script>
		<script type='text/javascript' src='js/agrupar_conta_receber_default.js'></script>
		<script type='text/javascript' src='js/local_cobranca_default.js'></script>
		
		<style type='text/css'>
			input[type=text]:readonly { background-color:#FFF; }
			input[type=datetime]:readonly { background-color:#FFF; }
			input[type=date]:readonly { background-color:#FFF; }
			textarea:readonly { background-color:#FFF; }
			select:disabled { background-color:#FFF; }
			select:disabled { color:#000; }
			#cp_vencimento table tr td { vertical-align:top; }
			#cpDataVencimentoIco { padding-top:4px; }
		</style>
	</head>
	<body onLoad="ativaNome('Agrupar Contas a Receber')">
		<? include('filtro_agrupar_conta_receber.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_agrupar_conta_receber.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='AgruparContaReceber'>
				<input type='hidden' name='IdContaReceberAgrupados' value=''>
				<input type='hidden' name='Buca' value='0'>
				<input type='hidden' name='PercentualJurosDiarios' value='0.00'>
				<input type='hidden' name='PercentualMulta' value='0.00'>
				<div id='cp_conta_receber'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Agrupador</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' rowspan='2'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaReceberAgrupador' value='' autocomplete="off" style='width:70px' onFocus="Foco(this,'in','auto');"  onBlur="Foco(this,'out');" onChange="busca_agrupar_conta_receber(this.value,'false',document.formulario.Local.value)" onkeypress="mascara(this,event,'int');" tabindex='1' />
							</td>
							<td class='separador'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
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
											<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2' /><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo' id='cp_RazaoSocial'>
											<input type='text'  name='RazaoSocial' value='' style='width:278px' maxlength='100' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readonly='readonly' />
										</td>
										
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Nome Representante</td>	
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Inscrição Estadual</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>E-mail</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readonly='readonly' />
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readonly='readonly' />
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
											<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2' /><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='Nome' value='' style='width:278px' maxlength='100' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readonly='readonly' />
										</td>										
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Nome Representante</td>	
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Inscrição Estadual</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Email</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readonly='readonly' />
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readonly='readonly' />
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
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2' /><input type='text' class='agrupador' name='NomeF' value='' style='width:220px' maxlength='100' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNascimento' value='' style='width:70px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:180px' readonly='readonly' />
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:100px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RG' value='' style='width:81px' readonly='readonly' />
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
								<input type='text' name='Telefone1' value='' style='width:122px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2' value='' style='width:122px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3' value='' style='width:122px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular' value='' style='width:122px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Fax' value='' style='width:122px' readonly='readonly' />
							</td>	
							<td class='separador'>&nbsp;</td>						
							<td class='campo'>
								<input type='text' name='ComplementoTelefone' value='' style='width:121px' readonly='readonly' />
							</td>			
						</tr>
					</table>
				</div>
				<div  id='cpEndereco' style='padding-top:6px'>
					<div class='cp_tit'>Endereço do Contas a Receber</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titIdPessoaEndereco'>Descrição Endereço</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Nome Responsável</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdPessoaEndereco' style='width:400px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_pessoa_endereco(document.formulario.IdPessoa.value,this.value)" tabindex='3'>
									<option value=''>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeResponsavelEndereco' value='' style='width:405px' maxlength='100' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>CEP</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Endereço</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Nº</td>
							<td class='separador'>&nbsp;</td>					
							<td class='descCampo'>Complemento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Bairro</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CEP' value='' style='width:70px' maxlength='9' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Endereco' value='' style='width:268px' maxlength='60' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Numero' value='' style='width:55px' maxlength='5' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Complemento' value='' style='width:161px' maxlength='30' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Bairro' value='' style='width:194px' maxlength='30' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:54px; color:#000'>País</B>Nome País</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:38px; color:#000'>Estado</b>Nome Estado</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:38px; color:#000'>Cidade</B>Nome Cidade</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdPais' value='' style='width:70px' maxlength='11' readonly='readonly' /><input  class='agrupador' type='text' name='Pais' value='' style='width:140px' maxlength='100' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdEstado' value='' style='width:70px' maxlength='11' readonly='readonly' /><input class='agrupador' type='text' name='Estado' value='' style='width:140px' maxlength='100' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdCidade' value='' style='width:70px' maxlength='11' readonly='readonly' /><input class='agrupador' type='text' name='Cidade' value='' style='width:233px' maxlength='100' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Contas a Receber Agrupados </div>
					<table style='margin:10px 0 5px 0' id='titTabelaServico'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Conta Receber</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='tit_ContaReceberPessoa'>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Status</td>
							<td class='separador'>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaContaReceber', true, event, null, 165);"></td>
							<td class='campo'>
								<input type='text' name='IdContaReceber' value='' autocomplete="off" style='width:80px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_conta_receber_agrupar(this.value);" onkeypress="mascara(this,event,'int');" tabindex='4' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='NomePessoa' value='' style='width:432px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdStatus' style='width:181px' disabled='disabled'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo "<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_add_conta_receber' value='Adicionar' class='botao' style='width:84px;' tabindex='5' onClick="simular_parcela(false); busca_conta_receber(document.formulario.IdContaReceber.value,false,'AdicionarAgruparContaReceber');" disabled='disabled' />
							</td>
						</tr>
					</table>
					<table id='tabelaContaReceber' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:60px'>Id</td>
							<td>Nome Pessoa</td>
							<td>Tipo Lanç.</td>
							<td>Nº Doc.</td>
							<td>Nº NF</td>
							<td>Local Cob.</td>
							<td>Data Lanç.</td>
							<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
							<td>Vencimento</td>
							<td>Status</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='7' id='totaltabelaContaReceber'>Total: 0</td>
							<td class='valor' id="totalValorTabelaContaReceber">0,00</td>
							<td colspan='3'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='cp_vencimento'>
					<div id='cp_tit'>Novo Vencimento</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titDataVencimento'>Data Vencimento</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titIdLocalCobranca'>Local de Cobrança</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titOcultarReferencia'>Ocultar Ref.</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titQTDParcelas'>QTD. Parcelas</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Despesas (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpCalcularMulta'>Calcular Multa e Juros</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='DataVencimento' id='cpDataVencimento' value='' style='width:96px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="simular_parcela(false); validar_Data('titDataVencimento',this); calcula_valor();" tabindex='6' />
							</td>
							<td class='find'><img id='cpDataVencimentoIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
								Calendar.setup({
									inputField		: "cpDataVencimento",
									ifFormat		: "%d/%m/%Y",
									button			: "cpDataVencimentoIco"
								});
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' id='cpLocalCobranca' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="simular_parcela(false); busca_local_cobranca(this.value,'false',document.formulario.Local.value);" style='width:182px;' tabindex='7'>
									<option value=''>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='OcultarReferencia' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  style='width:75px' tabindex='8'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=248 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcela' value='' style='width:81px' maxlength='12' onFocus="Foco(this,'in')" onChange="simular_parcela(false);" onkeypress="mascara(this,event,'int')" onBlur="Foco(this,'out')" tabindex='9' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDespesaLocalCobranca' value='' style='width:116px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="simular_parcela(false);" onkeypress="mascara(this,event,'float')" tabindex='10' />
								<div>Valor por parcela.</div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='CalcularMulta' style='width:177px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="simular_parcela(false); calcula_valor();" tabindex='11'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=114 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
								<p id='titMultaJuros' style='margin:0; padding:0;'>Multa 0,000%.  Juros 0,000%</p>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>(=) Valor (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><b id='titValorMoraMulta'>(+) Multa (<?=getParametroSistema(5,1)?>)</b></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><b id='titValorJurosVencimento'>(+) Juros (<?=getParametroSistema(5,1)?>)</b></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><b id='titValorTaxaReImpressaoBoleto'>(+) Taxa de Atualização (<?=getParametroSistema(5,1)?>)</b></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorVencimento' value='' style='width:191px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMoraMulta' value='' style='width:191px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="simular_parcela(false);calcular_novo_vencimento(this.name);" onkeypress="mascara(this,event,'float')" tabindex='12' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorJurosVencimento' value='' style='width:191px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="simular_parcela(false); calcular_novo_vencimento(this.name);" onkeypress="mascara(this,event,'float')" tabindex='13' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTaxaReImpressaoBoleto' value='' style='width:192px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="simular_parcela(false); calcular_novo_vencimento(this.name);" onkeypress="mascara(this,event,'float')" tabindex='14' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titValorOutrasDespesas'>(+) Outras Despesas (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titValorDescontoVencimento'>(-) Desconto (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titPercentualVencimento'>(-) Percentual (%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(=) Valor Final (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorOutrasDespesas' value='' style='width:191px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onChange="simular_parcela(false); calcular_novo_vencimento(this.name);" onkeypress="mascara(this,event,'float')" tabindex='15' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDescontoVencimento' value='' style='width:191px' maxlength='12' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onChange="simular_parcela(false); calcular_novo_vencimento(this.name);" onkeypress="mascara(this,event,'float')" tabindex='16' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='PercentualVencimento' value='' style='width:191px' maxlength='12' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="simular_parcela(false); calcular_novo_vencimento(this.name);" onkeypress="mascara(this,event,'float')" tabindex='17' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFinalVencimento' value='' style='width:192px' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_simular' value='Simular' class='botao' onClick='simular_parcela(true);' tabindex='18' />
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_Vencimento' style='display:none;'>
					<div id='cp_tit'>Vencimentos</div>
					<table id='tabelaVencimento' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Parcela</td>
							<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Perc. (%)</td>
							<td class='valor' id='tabValorDesp'>Valor Despesas (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Valor Total (<?=getParametroSistema(5,1)?>)</td>
							<td id='tableDataVenc'>Data Vencimento</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' id='totalVencimentos'>Total: 0</td>
							<td id='totalValor' class='valor'>0,00</td>
							<td id='totalPercentual' class='valor'>0,00</td>
							<td id='totalValorDespesa' class='valor'>0,00</td>
							<td id='totalValorTotal' class='valor'>0,00</td>
							<td>&nbsp;</td>
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
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readonly='readonly' />
							</td>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right'>
								<input type='button' name='bt_abrir_conta_receber' style='width:152px' value='Abrir Contas a Receber' class='botao' tabindex='19' onClick="cadastrar('abrirContaReceber');" />
								<input type='button' name='bt_cadastrar' style='width:70px' value='Cadastrar' class='botao' tabindex='20' onClick="cadastrar('inserir');" />
							</td>
						</tr>
					</table>
				</div>
				<table style='width:100%;'>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 id='helpText' name='helpText' style='margin:0'>&nbsp;</h1></td>
					</tr>
				</table>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/pessoa.php");
				include("files/busca/conta_receber.php");
			?>
		</div>
		<script type='text/javascript'>
			<?
				if($local_IdContaReceberAgrupador!=''){
					echo "busca_agrupar_conta_receber($local_IdContaReceberAgrupador,false,document.formulario.Local.value);";
					echo "scrollWindow('bottom');";
				} else{
					echo "buscar_local_cobranca('', false);";
				}
			?>
			
			function statusInicial(){
				if(document.formulario.CalcularMulta.value == ''){
					document.formulario.CalcularMulta.value	= '<?=getCodigoInterno(3,74)?>';
				}
				
				if(document.formulario.ValorVencimento.value == ''){
					document.formulario.ValorVencimento.value = "0,00";
				}
				
				if(document.formulario.ValorDespesaLocalCobranca.value == ''){
					document.formulario.ValorDespesaLocalCobranca.value = "0,00";
				}
				
				if(document.formulario.ValorMoraMulta.value == ''){
					document.formulario.ValorMoraMulta.value = "0,00";
				}
				
				if(document.formulario.ValorJurosVencimento.value == ''){
					document.formulario.ValorJurosVencimento.value = "0,00";
				}
				
				if(document.formulario.ValorTaxaReImpressaoBoleto.value == ''){
					document.formulario.ValorTaxaReImpressaoBoleto.value = "0,00";
				}
				
				if(document.formulario.ValorOutrasDespesas.value == ''){
					document.formulario.ValorOutrasDespesas.value = "0,00";
				}
				
				if(document.formulario.ValorDescontoVencimento.value == ''){
					document.formulario.ValorDescontoVencimento.value = "0,00";
				}
				
				if(document.formulario.PercentualVencimento.value == ''){
					document.formulario.PercentualVencimento.value = "0,00";
				}
				
				if(document.formulario.ValorFinalVencimento.value == ''){
					document.formulario.ValorFinalVencimento.value = "0,00";
				}
				
				calcular_novo_vencimento();
			}
			
			verificaAcao();
			inicia();
			verificaErro();
			enterAsTab(document.forms.formulario);
		</script>
	</body>
</html>