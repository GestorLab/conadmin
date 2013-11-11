<?
	$localModulo		=	1;
	$localOperacao		=	181;
	$localSuboperacao	=	"R";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$IdLoja		= 	$_SESSION['IdLoja'];
	$IdPessoa	= 	$_POST['IdPessoa'];
	$Celular	= 	$_POST['Celular'];
	$Mensagem	= 	$_POST['Mensagem'];
	$UrlSistema =	getParametroSistema(6,3);
	
	if($IdPessoa != "" && $Celular != "" && $IdLoja != "")
	{
		$MD5 = md5(md5($IdLoja.$IdPessoa.$Celular));
		$link = "$UrlSistema/aplicacoes/sms/avulso.php?key=$MD5&IdLoja=$IdLoja&IdPessoa=$IdPessoa&Celular=$Celular&Conteudo=$Mensagem";
	} else{
		$MD5  = "";
		$link = "";
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
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javaScript' src='js/conta_url_sms.js'></script>
		<script type='text/javaScript' src='js/conta_url_sms_default.js'></script>
		<script type='text/javaScript' src='js/pessoa_busca_pessoa_aproximada.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('URL SMS')">
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_url_sms.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='Local' value='UrlSms' />
				<input type='hidden' name='TabIndex' value='4' />
				<div id='cp_dadosConta' style='margin-top: -5px'>
					<div id='cp_tit'>Dados da URL SMS</div>
					<table id='cp_dadosconta' style='padding-bottom:6px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px'><?=dicionario(26)?></B><?=dicionario(85)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px; color:#000'><B><?=dicionario(100)?></B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPessoa', true, event, null, 165);"></td>
							<td class='descCampo'  rowspan='2'>
								<input type='text' name='IdPessoa' value='<?=$IdPessoa?>' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_pessoa(this.value);liberaGeraUrl();" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2' /><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readOnly onkeyup='busca_pessoa_aproximada(this,event);' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular' value='<?=$Celular?>' style='width:180px' maxlength='100' Onchange="liberaGeraUrl()"/>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px; color:#000'><B><?=dicionario(938)?></B></td>
						</tr>
						<tr>
							<td class='find'></td>
							<td class='descCampo'>
								<textarea style='width: 551px; height: 70px' name='Mensagem' onkeyup='maxlegth(this,150)' Onchange="liberaGeraUrl()"><?=$Mensagem?></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'></td>
							<td colspan='6' align='right'><input type='submit' class='botao' value='Gerar URL SMS' name='bt_GeraUrl' disabled/></td>
						</tr>
					</table>
					<?
						if($link != ""){
							echo "
								<table id='tablelink' style='margin-top: 30px;'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='margin-right:36px; color:#000'><B>".dicionario(957)."</B></td>
									</tr>
									<tr>
										<td class='find'></td>
										<td class='descCampo'>
											<textarea rows='4' cols='66' name='Link' readOnly>$link</textarea>
										</td>
									</tr>
								</table>";
						}
					?>
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
<script type='text/javaScript'>
<?
	if($IdPessoa != ""){
		echo "busca_pessoa($IdPessoa);";
		echo "scrollWindow('bottom');";
	}
?>
	CampoIdPessoa = document.formulario.IdPessoa;
	CampoCelular  = document.formulario.Celular;
	CampoMensagem = document.formulario.Mensagem;
	function status_inicial(){ 
	//	if(document.formulario.VoltarDataBase.value == '0'){
	//		document.formulario.VoltarDataBase.value	=	'<?=getCodigoInterno(3,21)?>';
	//	}
	}
	function liberaGeraUrl(){
		verificarcampos(CampoIdPessoa.value,CampoCelular.value,CampoMensagem.value);
	}
	
	verificaAcao();
	function inicia(){
		document.formulario.IdPessoa.focus();
	}
	inicia();
	enterAsTab(document.forms.formulario);
</script>