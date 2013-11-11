<?php
	$localModulo		= 1;
	$localOperacao		= 178;
	$localSuboperacao	= "V";

	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$local_Login				= $_SESSION["Login"];
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_Erro					= $_GET["Erro"];
	$local_Acao 				= $_POST["Acao"];
	$local_IdCaixa				= $_POST["IdCaixa"];
	$local_FormaPagamento		= $_POST["FormaPagamento"];
	
	if($local_IdCaixa == ''){
		$local_IdCaixa = $_GET["IdCaixa"];
	}
	
	if(empty($local_IdCaixa)){
		$sql = "SELECT 
					IdCaixa 
				FROM
					Caixa 
				WHERE 
					IdLoja = $local_IdLoja AND
					IdStatus = 1 AND
					LoginAbertura = '$local_Login'
				LIMIT 1;";
		$res = @mysql_query($sql, $con);
		
		if(mysql_num_rows($res) > 0){
			$lin = @mysql_fetch_array($res);
			$local_IdCaixa = $lin["IdCaixa"];
		}
	}
	
	switch($local_Acao){
		case "inserir":
			include("files/inserir/inserir_caixa.php");
			break;
		case "alterar":
			include("files/editar/editar_caixa.php");
			break;
		case "reabrir":
			include("files/editar/editar_caixa_reabrir.php");
			break;
		default:
			$local_Acao = "inserir";
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
		<script type='text/javascript' src='../../js/mascara_real.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script> 
		<script type='text/javascript' src='js/caixa.js'></script>
		<script type='text/javascript' src='js/caixa_default.js'></script>
		<script type='text/javascript' src='js/pessoa_busca_pessoa_aproximada.js'></script>
		
		<style type='text/css'>
			#cp_Status {
				width:735px;
				text-align:right;
				line-height: 12px;
				font-size:14px;
			}
		</style>
	</head>
	<body onLoad="ativaNome('Caixa')">
		<?php include('filtro_caixa.php'); ?>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_caixa.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?php echo $local_Acao; ?>' />
				<input type='hidden' name='Erro' value='<?php echo $local_Erro; ?>' />
				<input type='hidden' name='Local' value='Caixa' />
				<input type='hidden' name='Moeda' value='<?php echo getParametroSistema(5,1); ?>' />
				<input type='hidden' name='TabIndexFix' value='2' />
				<input type='hidden' name='TabIndex' value='0' />
				<input type='hidden' name='FormaPagamento' value='0' />
				<input type='hidden' name='IdStatus' value='1' />
				<input type='hidden' name='Titular' value='2' />
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(171); ?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' rowspan='2'><div id='cp_Status'>&nbsp;</div></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdCaixa' value='' style='width:70px' maxlength='11' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'><?php echo dicionario(947); ?></div>
					<div id='bl_FormaPagamento'></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(948); ?> (<?php echo getParametroSistema(5,1); ?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' style='width:146px' />
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(956); ?> (<?php echo getParametroSistema(5,1); ?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='width:332px;'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input style='width:140px' type='text' name='ValorAberturaTotal' value='0,00' maxlength='11' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' />
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input style='width:140px' type='text' name='ValorAtualTotal' value='0,00' maxlength='11' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Log</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(949); ?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(388); ?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(950); ?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(951); ?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAbertura' value='' style='width:180px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataAbertura' value='' style='width:202px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginFechamento' value='' style='width:181px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataFechamento' value='' style='width:202px' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right; width:250px;'>
								<input type='button' style='width:52px;display: none' name='bt_reabrir' value='Reabrir' class='botao' onClick="cadastrar('reabrir')" />
								<input type='button' style='width:52px' name='bt_abrir' value='Abrir' class='botao' onClick="cadastrar('inserir')" />
								<input type='button' style='width:64px' name='bt_fechar' value='Fechar' class='botao' onClick="cadastrar('alterar')" />
							</td>
						</tr>
					</table>
				</div>
				<div>	
					<table style='width:100%;height:33px;' border='0'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<script type="text/javascript">
		<?php
			if($local_IdCaixa != ""){
				echo "busca_caixa($local_IdCaixa, false, document.formulario.Local.value);";
			} else{
				echo "listar_forma_pagamento();";
			}
		?>
			
			verificaErro();
			verificaAcao();
			enterAsTab(document.forms.formulario);
		</script>
	</body>	
</html>