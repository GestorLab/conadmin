<?
	$localModulo		= 1;
	$localOperacao		= 172;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION["IdLoja"];
	$local_Acao 	= $_POST['Acao'];
	$local_Erro		= $_GET['Erro'];
	
	$local_IdBackupConta		= $_POST['IdBackupConta'];
	$local_ServidorEndereco		= formatText($_POST['ServidorEndereco'],NULL);
	$local_ServidorPorta		= formatText($_POST['ServidorPorta'],NULL);
	$local_BackupCaminho		= formatText($_POST['BackupCaminho'],NULL);
	$local_ServidorUsuario		= formatText($_POST['ServidorUsuario'],NULL);
	$local_ServidorSenha		= formatText($_POST['ServidorSenha'],NULL);
	
	if($local_IdBackupConta == ''){
		$local_IdBackupConta = $_GET['IdBackupConta'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_backup_conta.php');
			break;
		case 'alterar':
			include('files/editar/editar_backup_conta.php');
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
	    
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_email.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/backup_conta.js'></script>
		<script type='text/javascript' src='js/backup_conta_default.js'></script>
		
		<style type='text/css'>
			input[type=text]:readOnly { background-color: #FFF; }
			select:disabled { background-color: #FFF; }
			select:disabled { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Contas de Backup')">
		<? 
			include('filtro_backup_conta.php'); 
		?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_backup_conta.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='Local' value='BackupConta' />
				<input type='hidden' name='ServidorPortaDefault' value='<?=getCodigoInterno(3,174)?>' />
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Conta Backup</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdBackupConta' value='' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_backup_conta(this.value,true,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='1' />
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados da Conta de Backup</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><b>Endereço do Servidor (FTP)</b></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><b>Porta</b></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ServidorEndereco' value='' style='width:729px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ServidorPorta' value='' style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='3' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><b id='titUsuario'>Usuário</b></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><b>Senha</b></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Pasta para backup</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ServidorUsuario' value='' style='width:192px' maxlength='100' onChange="validar_email(this,'titUsuario')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='4' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ServidorSenha' value='' style='width:190px' maxlength='32' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='5' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='BackupCaminho' value='' style='width:400px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='6' />
							</td>
						</tr>
					</table>
				</div>
				<div>
					<!-- Inicio Log -->
					<div id='cp_tit'><?=dicionario(571)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(1033)?></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='HistoricoObs' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='1000' readOnly></textarea>
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
							<td class='separador'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:181px' maxlength='20' readOnly />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:202px' readOnly />
							</td>							
						</tr>
					</table>
					<!-- Fim log -->
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:90px;' name='bt_testar' value='Testar Conta' class='botao' tabindex='7' onClick="cadastrar('testar')" />
							</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' style='width:74px;' name='bt_inserir' value='Cadastrar' class='botao' tabindex='8' onClick="cadastrar('inserir')" />
								<input type='button' style='width:57px;' name='bt_alterar' value='Alterar' class='botao' tabindex='9' onClick="cadastrar('alterar')" />
								<input type='button' style='width:57px;' name='bt_excluir' value='Excluir' class='botao' tabindex='10' onClick="excluir(document.formulario.IdBackupConta.value)" />
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
	</body>
</html>
<script type='text/javascript'>
	permissao_visualizar_senha();
<!-- 
<?
	if($local_IdBackupConta != '') {
		echo "busca_backup_conta($local_IdBackupConta,false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";	
	}
?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
	-->
</script>