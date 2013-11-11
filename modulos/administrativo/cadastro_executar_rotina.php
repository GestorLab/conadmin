<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Erro						= $_GET['Erro'];
	$local_Acao 					= $_POST['Acao'];
	$local_TodasRotinas				= $_POST['TodasRotinas'];

	$local_RealizarBackup			= $_POST['RealizarBackup'];			# 0
	$local_ProcessarRetorno			= $_POST['ProcessarRetorno'];			# 1
	$local_StatusContrato			= $_POST['StatusContrato'];			# 2
	$local_EnviarEmail				= $_POST['EnviarEmail'];				# 3
	$local_TratamentoLogFreeRadius	= $_POST['TratamentoLogFreeRadius'];	# 4
	$local_ApagarArquivoTemporario	= $_POST['ApagarArquivoTemporario'];	# 5
	$local_RotinaPersonalizada		= $_POST['RotinaPersonalizada'];		# 6
	$local_NotaFiscalEmitida		= $_POST['NotaFiscalEmitida'];		# 6

	if($local_RealizarBackup == ''){			$local_RealizarBackup = 'off';			}
	if($local_ProcessarRetorno == ''){			$local_ProcessarRetorno = 'off';		}
	if($local_StatusContrato == ''){			$local_StatusContrato = 'off';			}
	if($local_EnviarEmail == ''){				$local_EnviarEmail = 'off';				}
	if($local_TratamentoLogFreeRadius == ''){	$local_TratamentoLogFreeRadius = 'off'; }
	if($local_ApagarArquivoTemporario == ''){	$local_ApagarArquivoTemporario = 'off'; }
	if($local_RotinaPersonalizada == ''){		$local_RotinaPersonalizada = 'off';		}
	if($local_NotaFiscalEmitida == ''){			$local_NotaFiscalEmitida = 'off';		}

	if($local_Acao == 1){
		$fileurl = getParametroSistema(6,1)."rotinas/cron";
		@unlink("$fileurl.log");
		system(getParametroSistema(6,4)." $fileurl".".php $local_RealizarBackup $local_ProcessarRetorno $local_StatusContrato $local_EnviarEmail $local_TratamentoLogFreeRadius $local_ApagarArquivoTemporario $local_RotinaPersonalizada $local_NotaFiscalEmitida > $fileurl.log &");
		// system
		header("Location: aviso_executar_rotinas_diarias.php");
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
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/executar_rotina.js'></script> 
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Executar rotinas diárias',false)">
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_executar_rotina.php'>
				<input type='hidden' name='Acao' value='1'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ExecutarRotina'>
				<div>
					<table class='tableListar' id='tableListar' cellspacing='0'>
						<tr class='tableListarTitle'>
							<td style='width:21px;' class='id_listar'>
								<input type='checkbox' name='TodasRotinas' onClick='habilitar_camp(this);' />
							</td>
							<td>Rotinas diárias</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="RealizarBackup" onClick='habilitar_camp(this);' /></td>
							<td class='campo'>
								Realizar Backup
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="ProcessarRetorno" onClick='habilitar_camp(this);' /></td>
							<td class='campo'>
								Processar retorno Gateways de pagamento
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="StatusContrato" onClick='habilitar_camp(this);' /></td>
							<td class='campo'>
							<?
								$DiasSemana = array();
								$sql = "SELECT 
											SUBSTRING(DescricaoParametroSistema, 13, 3) DiaSemana 
										FROM
											ParametroSistema 
										WHERE 
											IdGrupoParametroSistema = 136 AND 
											IdParametroSistema < 8 AND 
											ValorParametroSistema = 1;";
								$res = mysql_query($sql, $con);
								
								while($lin = @mysql_fetch_array($res)){
									$DiasSemana[count($DiasSemana)] = $lin[DiaSemana];
								}
								
								if(count($DiasSemana) > 1){
									$DiasSemana[count($DiasSemana)-2] .= " e,";
								}
								
								$DiasSemana = str_replace(",,", "", implode(", ", $DiasSemana));
								
								if(!empty($DiasSemana)){
									$DiasSemana = "(".$DiasSemana.")";
								}
							?>
								Executar rotinas de status de contrato <?=$DiasSemana?>
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="EnviarEmail" onClick='habilitar_camp(this);' /></td>
							<td class='campo'>
								Enviar e-mails do dia
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="TratamentoLogFreeRadius" onClick='habilitar_camp(this);' /></td>
							<td class='campo'>
								Executar tratamento de log do FreeRadius
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="ApagarArquivoTemporario" onClick='habilitar_camp(this);' /></td>
							<td class='campo'>
								Apagar arquivos temporários
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="RotinaPersonalizada" onClick='habilitar_camp(this);' /></td>
							<td class='campo'>
								Rotina personalizada
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="NotaFiscalEmitida" onClick='habilitar_camp(this);' /></td>
							<td class='campo'>
								Enviar Notas Fiscais Emitidas
							</td>
						</tr>
						<tr class='tableListarTitle'>
							<td colspan='2'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div>
					<table style='width:100%;'>
						<tr>
							<td class='find' />
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
							<td style='text-align:right; padding:8px 8px;'><input type='submit' name='bt_executar' value='Executar' disabled /></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<script type='text/javascript'>
			tableMultColor('tableListar','<?=getParametroSistema(15, 1)?>');
			verificaErro();
		</script>
	</body>
</html>