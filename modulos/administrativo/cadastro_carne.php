<?
	$localModulo		=	1;
	$localOperacao		=	45;
	$localSuboperacao	=	"R";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdCarne			= $_POST['IdCarne'];
	$local_IdStatusParcela	= $_POST['IdStatusParcela'];
	$local_ObsCancelamento	= formatText($_POST['ObsCancelamento'],NULL);
	
	if($_GET['IdCarne']!=''){
		$local_IdCarne	=	$_GET['IdCarne'];
	}
	
	if($_GET['IdStatusParcela']!=''){
		$local_IdStatusParcela	=	$_GET['IdStatusParcela'];
	}

	switch ($local_Acao){
		case 'cancelar':
			include('files/editar/editar_cancelar_conta_receber.php');
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
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type="text/javascript">
		    var $j = jQuery.noConflict(); 
		</script>
		<script type='text/javaScript' src='../../js/funcoes.js'></script>
		<script type='text/javaScript' src='../../js/incremental_search.js'></script>
		<script type='text/javaScript' src='../../js/mensagens.js'></script>
		<script type='text/javaScript' src='../../js/event.js'></script>
		<script type='text/javaScript' src='js/carne.js'></script>
		<script type='text/javaScript' src='js/pessoa_default.js'></script>
		<script type='text/javascript' src='js/carne_busca_pessoa_aproximada.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
			.sinal { cursor:pointer; }
		</style>
	</head>
	<body  onLoad="ativaNome('Carnês')">
		<? include('filtro_carne.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form id="form" name='formulario' method='post' action='files/editar/editar_cancelar_conta_receber.php'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='Local' value='Carne' />
				<input type='hidden' name='Voltar' value='' />
				<input type='hidden' name='IdStatusParcela' value='<?=$local_IdStatusParcela?>' />
				<input type='hidden' name='CancelarContaReceber' value='' />
				<input type='hidden' name='LancamentoFinanceiroTipoContrato' value='' />
				<input type='hidden' name='ContExecucao' value='0' />
				<input type='hidden' name='TabIndex' value='2' />
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Carnê</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdCarne' value='' autocomplete="off" style='width:70px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_carne(this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" tabindex='1' />
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'>Dados do Cliente</div>
					<?	
						$nome="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B>Nome Pessoa</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' readonly='readonly' /><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readonly='readonly' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' id='cp_RazaoSocial'>
										<input type='text'  name='RazaoSocial' value='' style='width:279px' maxlength='100' readonly='readonly' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readonly='readonly' />
									</td>
								</tr>
							</table>";
							
						$razao="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B><B style='color:#000'>Razão Social</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>Nome Fantasia</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' readonly='readonly' /><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readonly='readonly' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='Nome' value='' style='width:279px' maxlength='100' readonly='readonly' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readonly='readonly' />
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
					<table id='cp_fisica' style='display:none;padding-bottom:6px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>CPF</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' readonly='readonly' /><input type='text' class='agrupador' name='NomeF' value='' style='width:279px' maxlength='100' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:283px' maxlength='255' readonly='readonly' />
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail' title='Enviar E-mail' /></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:124px' maxlength='18' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_conta_receber'>
					<div id='cp_tit' style='margin-bottom:0'>Contas a Receber a Ser Cancelado</div>
					<table id='tabelaContaReceber' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:10px;'><input style='border:0' type='checkbox' name='todos_cr' onClick='selecionar(this)' tabindex='2' /></td>
							<td style='width:40px; padding-left:5px;'>Id</td>
							<td>Nº Doc.</td>
							<td>Nº NF.</td>
							<td>Local Cob.</td>
							<td>Data Lanç.</td>
							<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
							<td>Vencimento</td>
							<td class='valor'>Receb. (<?=getParametroSistema(5,1)?>)</td>
							<td>Pagamento</td>
							<td>Local Receb.</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='3' id='tabelaTotal'></td>
							<td colspan='3'>&nbsp;</td>
							<td id='tabelaTotalValor' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td id='tabelaTotalReceb' class='valor'>0,00</td>
							<td colspan='2'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Observações do Cancelamento</div>
					<div id='cpVoltarDataBase'></div>
					<table id='ObsCancelamento'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Observações do Cancelamento</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea class="obrig" name='ObsCancelamento' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='106'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_cancelar' value='Confirmar Cancelamento' class='botao' tabindex='107' />
							</td>
						</tr>
					</table>
				</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 class="teste" id='helpText' name='helpText' style='margin:0'></h1></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>
<script type='text/javaScript'>
<?
	if($local_IdCarne!=''){
		echo "busca_carne($local_IdCarne,false,document.formulario.Local.value);";		
		echo "scrollWindow('bottom');";	
	}
?>
	function status_inicial(){ 
	//	if(document.formulario.VoltarDataBase.value == '0'){
	//		document.formulario.VoltarDataBase.value	=	'<?=getCodigoInterno(3,21)?>';
	//	}
	}
	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>