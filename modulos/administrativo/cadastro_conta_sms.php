<?
	$localModulo		=	1;
	$localOperacao		=	181;
	$localSuboperacao	=	"R";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdContaSMS		= $_POST['IdContaSMS'];
	$local_Descricao		= $_POST['Descricao'];
	$local_IdOperadora		= $_POST['IdOperadora'];
	$local_IdStatus			= $_POST['IdStatus'];
	
	if($_GET['IdContaSMS'] != ""){
		$local_IdContaSMS	=	$_GET['IdContaSMS'];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_conta_sms.php');
			break;
		case 'alterar':
			include('files/editar/editar_conta_sms.php');
			break;
		case 'testar':
			header("Location: cadastro_enviar_sms.php?IdContaSMS=$local_IdContaSMS&IdOperadora=$local_IdOperadora");
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
		
		<script type='text/javaScript' src='../../js/funcoes.js'></script>
		<script type='text/javaScript' src='../../js/incremental_search.js'></script>
		<script type='text/javaScript' src='../../js/mensagens.js'></script>
		<script type='text/javaScript' src='../../js/event.js'></script>
		<script type='text/javaScript' src='js/conta_sms.js'></script>
		<script type='text/javaScript' src='js/conta_sms_default.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Conta SMS')">
		<? include('filtro_conta_sms.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_conta_sms.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='Local' value='ContaSMS' />
				<input type='hidden' name='Voltar' value='' />
				<input type='hidden' name='IdStatusParcela' value='<?=$local_IdStatusParcela?>' />
				<input type='hidden' name='CancelarContaReceber' value='' />
				<input type='hidden' name='LancamentoFinanceiroTipoContrato' value='' />
				<input type='hidden' name='ContExecucao' value='0' />
				<input type='hidden' name='TabIndex' value='4' />
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Conta SMS</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaSMS' value='' onChange='busca_conta_sms(this.value,document.formulario.Erro.value,document.formulario.Local.value);visualizar_fila(this.value,false)' autocomplete="off" style='width:70px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" tabindex='1' />
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosConta'>
					<div id='cp_tit'>Dados da Conta SMS</div>
					<table id='cp_dadosconta' style='padding-bottom:6px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Descrição Conta SMS</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px; color:#000'><B>Operadora</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px; color:#000'><B>Status</B></td>
						</tr>
						<tr>
							<td class='find'></td>
							<td class='descCampo'><input type='text' maxlength='100' name='Descricao' style='width: 390px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'/></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdOperadora' onChange="buscar_paramentro_operadora(this.value,document.formulario.IdContaSMS.value);" style='width: 250px;' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
									<option value=''></option>
									<?
										$sql = "Select
													IdOperadora,
													DescricaoOperadora
												From
													OperadoraSMS";
										$res = mysql_query($sql,$con);
										if(@mysql_num_rows($res) > 0){
											while($lin = @mysql_fetch_array($res)){
												echo "<option value='$lin[IdOperadora]'>$lin[DescricaoOperadora]</option>";
											}
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>
								<select name='IdStatus' style='width: 154px;' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
									<option value=''></option>
									<?
										$sql = "Select 
												  IdParametroSistema,
												  ValorParametroSistema 
												From
												  ParametroSistema 
												Where
													IdGrupoParametroSistema = 245";
										$res = mysql_query($sql,$con);
										if(@mysql_num_rows($res) > 0){
											while($lin = @mysql_fetch_array($res)){
												echo "<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div id="bl_ParametrosContaSMS" style='display:none;'></div>
				<div>
					<div id='cp_tit'>Logs</div>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='float:left;'>
								<input type='button' name='bt_testar' value='Testar Conta' class='botao' style='width:85px;' tabindex='1008' onClick="cadastrar('testar')">
								<input type='button' name='bt_fila' value='Visualizar Fila de Envio' class='botao' tabindex='15' onClick="visualizar_fila(document.formulario.IdContaSMS.value,true)" />
							</td>
							<td class='campo' style='float:right;'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' style='width:70px;' tabindex='1007' onClick="cadastrar('inserir'),validar()">
								<input type='button' name='bt_alterar' value='Alterar' class='botao' style='width:50px;' tabindex='1008' onClick="cadastrar('alterar')">
								<input type='button' name='bt_excluir' value='Excluir' class='botao' style='width:50px;' tabindex='1009' onClick="excluir(document.formulario.IdContaSMS.value,document.formulario.IdOperadora.value)">
							</td>
						</tr>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
							</tr>
						</table>
					</table>
				</div>
			</form>
			<div id="cp_fila_de_envio" style='display: none;'>				
					<table id="tabelaFilaDeEnvio" cellspacing="0" width="100%">
						<tr style='background-color:#004492; color: #FFF;font-weight: bold; '>
							<td>Id</td>			
							<td>Nome Pessoa </td>					
							<td>Destinatário</td>		
							<td>Tipo Mensagem </td>		
							<td>Data Cadastro</td>						
							<td>Status</td>						
						</tr>						
					</table>										
			</div>	
		</div>
	</body>
</html>
<script type='text/javaScript'>
<?
	if($local_IdContaSMS != ""){
		echo "busca_conta_sms($local_IdContaSMS,false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";
	}
?>
	function status_inicial(){ 
	//	if(document.formulario.VoltarDataBase.value == '0'){
	//		document.formulario.VoltarDataBase.value	=	'<?=getCodigoInterno(3,21)?>';
	//	}
	}
	
	verificaAcao();
	function inicia(){
		document.formulario.IdContaSMS.focus();
	}
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>