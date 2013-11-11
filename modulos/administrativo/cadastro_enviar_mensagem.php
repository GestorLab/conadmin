<?
	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Acao 					= $_POST['Acao'];
	$local_Erro						= $_GET['Erro'];
	$local_IdHistoricoMensagem		= $_GET['IdHistoricoMensagem'];
	$local_IdContaReceber			= $_GET['IdContaReceber'];
	$local_IdContaEventual			= $_GET['IdContaEventual'];
	$local_IdOrdemServico			= $_GET['IdOrdemServico'];
	$local_IdNotaFiscalLayout		= $_GET['IdNotaFiscalLayout'];
	$local_MesReferencia			= $_GET['MesReferencia'];
	$local_IdStatusArquivoMestre	= $_GET['IdStatusArquivoMestre'];
	$local_IdContaEmail				= $_GET['IdContaEmail'];
	$local_ExecutarViaAjax			= '';
	$local_VerificaTipoEmail		= '';
	
	
	//Verificação de Tipo Email: Mensagem Enviada.
	if($local_IdHistoricoMensagem != ""){
		$local_VerificaTipoEmail = 1;
		if(verificaStatusTipoMensagem($local_IdLoja,$local_IdHistoricoMensagem,'Reenviar') == false){
			header('location: aviso_tipo_mensagem_desativado.php?IdHistoricoMensagem='.$local_IdHistoricoMensagem);
		}
	}	
	//Verificação de Tipo Email: Conta Receber.
	if($local_IdContaReceber != ""){		
		if(verificaStatusTipoMensagem($local_IdLoja,1,'Enviar') == false){
			header('location: aviso_tipo_mensagem_desativado.php?IdContaReceber='.$local_IdContaReceber);
		}
	}
	//Verificação de Tipo Email: Conta Enventual.
	if($local_IdContaEventual != ""){
		if(verificaStatusTipoMensagem($local_IdLoja,2,'Enviar') == false){
			header('location: aviso_tipo_mensagem_desativado.php?IdContaEventual='.$local_IdContaEventual);
		}
	}
	//Verificação de Tipo Email: Ordem de Serviço.
	if($local_IdOrdemServico != ""){	
		if(verificaStatusTipoMensagem($local_IdLoja,19,'Enviar') == false){
			header('location: aviso_tipo_mensagem_desativado.php?IdOrdemServico='.$local_IdOrdemServico);
		}	
	}
	//Verificação de Tipo Email: Nota Fiscal Layout.
	if($local_IdNotaFiscalLayout != ""){
		if(verificaStatusTipoMensagem($local_IdLoja,10,'Enviar') == false){
			header('location: aviso_tipo_mensagem_desativado.php?IdNotaFiscalLayout='.$local_IdNotaFiscalLayout);
		}
	}
	
	if($local_IdHistoricoMensagem!=''){
		$sql = "select
					Email
				from
					HistoricoMensagem
				where
					IdLoja = $local_IdLoja and
					IdHistoricoMensagem=$local_IdHistoricoMensagem";
					
		$UrlAction = "rotinas/reenviar_mensagem.php";
	}
	
	if($local_IdContaReceber!=''){
		$sql = "select
					Pessoa.Email,
					PessoaEndereco.EmailEndereco Cob_Email
				from
					ContaReceber,
					Pessoa,
					PessoaEndereco
				where
					ContaReceber.IdLoja = $local_IdLoja and
					ContaReceber.IdContaReceber = $local_IdContaReceber and
					ContaReceber.IdPessoa = Pessoa.IdPessoa and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					ContaReceber.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco";

		$UrlAction = "rotinas/enviar_mensagem.php";
	}

	if($local_IdContaEventual != ''){
		$sql = "select
					Pessoa.Email,
					PessoaEndereco.EmailEndereco Cob_Email
				from
					ContaEventual,
					Pessoa,
					PessoaEndereco
				where
					ContaEventual.IdLoja = $local_IdLoja and
					ContaEventual.IdContaEventual = $local_IdContaEventual and
					ContaEventual.IdPessoa = Pessoa.IdPessoa and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco";
					
		$UrlAction = "rotinas/enviar_mensagem.php";
	}

	
	if($local_IdOrdemServico != ''){
		$sql = "select
					Pessoa.Email,
					PessoaEndereco.EmailEndereco Cob_Email
				from
					OrdemServico,
					Pessoa,
					PessoaEndereco
				where
					OrdemServico.IdLoja = $local_IdLoja and
					OrdemServico.IdOrdemServico = $local_IdOrdemServico and
					OrdemServico.IdPessoa = Pessoa.IdPessoa and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco";							
					
		$UrlAction = "rotinas/enviar_mensagem.php";
	}
	
	if($local_IdNotaFiscalLayout != ''&& $local_MesReferencia != '' && $local_IdStatusArquivoMestre !=''){
		$sql = "select
					EmailResponsavel Email
				from
					NotaFiscal2ViaEletronicaArquivo
				where
					IdLoja = $local_IdLoja and
					IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
					MesReferencia = '$local_MesReferencia' and
					StatusArquivoMestre='$local_IdStatusArquivoMestre'";
					
		$UrlAction = "rotinas/enviar_email_nf_2_via_eletronica_remessa.php";
	}
	
	if($local_IdContaEmail != ''){
		$sql = "select
					Pessoa.Email
				from
					Usuario,
					Pessoa
				where
					Usuario.Login = '$local_Login' and
					Usuario.IdPessoa = Pessoa.IdPessoa;";
					
		$local_ExecutarViaAjax = "rotinas/enviar_mensagem.php?IdContaEmail=".$local_IdContaEmail;
	}		

	if($sql != ''){
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$Email = $lin[Email];
	
		if($lin[Cob_Email] != ''){
			$Email .= ";".$lin[Cob_Email];
		}
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
		<script type = 'text/javascript' src = '../../js/val_email.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/enviar_mensagem.js'></script>
		<script type = 'text/javascript' src = 'js/reenvio_mensagem_default.js'></script>
	    <style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Enviar Mensagem')">
		<? include('filtro_reenvio_mensagem.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo' style='margin:0'>
			<form name='formulario' method='post' action='<?=$UrlAction?>' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='VerificarTipoMensagem' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='EnviarMensagem'>
				<input type='hidden' name='IdHistoricoMensagem' value='<?=$local_IdHistoricoMensagem?>'>
				<input type='hidden' name='IdContaReceber' value='<?=$local_IdContaReceber?>'>
				<input type='hidden' name='IdContaEventual' value='<?=$local_IdContaEventual?>'>
				<input type='hidden' name='IdOrdemServico' value='<?=$local_IdOrdemServico?>'>
				<input type='hidden' name='IdNotaFiscalLayout' value='<?=$local_IdNotaFiscalLayout?>'>
				<input type='hidden' name='MesReferencia' value='<?=$local_MesReferencia?>'>
				<input type='hidden' name='IdStatusArquivoMestre' value='<?=$local_IdStatusArquivoMestre?>'>
				<input type='hidden' name='ExecutarViaAjax' value='<?=$local_ExecutarViaAjax?>'>			
				<div id='cp_tit' style='margin-top:0'>Enviar Mensagem</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B>Para</B></td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='Email' style='width:300px' value='<?=$Email?>' maxlength='255' onFocus="Foco(this,'in')" onKeypress="return mascara(this,event,'filtroCaractereEmail','','')"  onBlur="Foco(this,'out')" onChange="validar_Email(this.value);" tabindex='1'>
						</td>
					</tr>
				</table>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_voltar' value='Voltar' class='botao' tabindex='2' onClick='parent.history.go(-1)'>
							</td>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Enviar' class='botao' tabindex='3' onClick='cadastrar()'>
							</td>
						</tr>
					</table>
					<table>						
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
						</tr><tr>						
					</table>
				</div>
			</form>
		</div>
	</body>
</html>
<script>
	inicia();
	enterAsTab(document.forms.formulario);
</script>