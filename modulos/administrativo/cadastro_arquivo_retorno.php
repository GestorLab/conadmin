<?
	$localModulo		=	1;
	$localOperacao		=	22;
	$localSuboperacao	=	"V";	
	$Path				=   "../../";
	
	$extensoesTitle = "Extensões Suportadas:<br/>&nbsp&nbsp&nbsp&nbsp *.ret *.dat *.crt *.txt";
	$extensoes = "new Array('ret','dat','crt', 'txt')";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	include ('../../classes/envia_mensagem/envia_mensagem.php');	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdArquivoRetorno						= $_POST['IdArquivoRetorno'];
	$local_IdLocalRecebimento					= $_POST['IdLocalRecebimento'];
	$local_EndArquivo							= formatText($_POST['EndArquivo'],NULL);
	$local_ValorTotal							= formatText($_POST['ValorTotal'],NULL);
	$local_DataRetorno							= formatText($_POST['DataRetorno'],NULL);
	$local_DataProcessamento					= formatText($_POST['DataProcessamaneto'],NULL);
	$local_QtdRegistro							= formatText($_POST['QtdRegistro'],NULL);
	$local_NomeArquivo							= formatText($_POST['NomeArquivo'],NULL);
	$local_EnderecoArquivo						= formatText($_POST['EnderecoArquivo'],NULL);
	$local_NumSeqArquivo						= formatText($_POST['NumSeqArquivo'],NULL);
	$local_IdArquivoRetornoTipo					= formatText($_POST['IdArquivoRetornoTipo'],NULL);
	$local_IdTipoLocalCobranca					= formatText($_POST['IdTipoLocalCobranca'],NULL);
	$local_EnviarEmailConfirmacaoPagamento		= formatText($_POST['EnviarEmailConfirmacaoPagamento'],NULL);
	
	$local_ContaReceberOcorrencia	= $_POST['ContaReceberOcorrencia'];
	
	if($_GET['IdLocalRecebimento']!=''){
		$local_IdLocalRecebimento	= $_GET['IdLocalRecebimento'];	
	}
	if($_GET['IdArquivoRetorno']!=''){
		$local_IdArquivoRetorno	= $_GET['IdArquivoRetorno'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_arquivo_retorno.php');
			break;
		case 'processar':
			include('rotinas/processar_arquivo_retorno.php');
			break;
		case 'confirmar':
			include('rotinas/confirmar_arquivo_retorno.php');
			break;
		case 'cancelar_recebimento':
			include("files/editar/editar_cancelar_ocorrencia_conta_receber_recebimento.php");
			break;
		default:
			$local_Acao = 'inserir';
			break;
	}
	
	include("../../files/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/procurar.css' />
	    <link rel='stylesheet' type='text/css' href='../../classes/calendar/calendar-blue.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/arquivo_retorno.js'></script>
		<script type='text/javascript' src='js/arquivo_retorno_default.js'></script>
		<script type='text/javascript' src='js/arquivo_retorno_tipo_default.js'></script>
		<script type='text/javascript' src='js/local_cobranca_default.js'></script>
		
    	<style type="text/css">
			input[type=text]:readonly { background-color: #FFF; }
			input[type=datetime]:readonly { background-color: #FFF; }
			input[type=date]:readonly { background-color: #FFF; }
			input[type=checkbox] { padding: 0px; margin: 0px; vertical-align: bottom; }
			textarea:readonly { background-color: #FFF; }
			select:disabled { background-color: #FFF; }
			select:disabled { color: #000; }
			.tableListarCad td { height:14px; }
			.tableColEnd { width:11px; }
		</style>
	</head>
	<body onLoad="ativaNome('Arquivo de Retorno')">
		<?php include('filtro_arquivo_retorno.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_arquivo_retorno.php' onSubmit='return validar()' enctype ='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='IdLoja' value='<?=$local_IdLoja?>' />
				<input type='hidden' name='Login' value='<?=$local_Login?>' />
				<input type='hidden' name='Local' value='ArquivoRetorno' />
				<input type='hidden' name='tempEndArquivo' id='tempEndArquivo' value='' />
				<input type='hidden' name='Arquivo' value='' />
				<input type='hidden' name='IdTipoLocalCobranca' value='' />
				<input type='hidden' name='CorRecebido' value='<?=getParametroSistema(15,3)?>' />
				<input type='hidden' name='CorRecebidoDesc' value='<?=getParametroSistema(15,7)?>' />
				<input type='hidden' name='CorCancelado' value='<?=getParametroSistema(15,2)?>' />
				<input type='hidden' name='EnviarEmailConfirmacaoPagamento' value='2' />								
				<input type='hidden' name='IdStatus' value='' />
				<input type='hidden' name='Visualizar' value='' />
				<input type='hidden' name='LimiteContaReceber' value='<?=getCodigoInterno(3,106)?>' />
				<input type='hidden' name='ContaReceberOcorrencia' value='' />
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descricao' style='width: 822px; text-align:right;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div>
					<?							
						switch(getNavegador()){
							case 'Firefox':
								$maginBtProcurar = 22;
							break;
							case 'Chrome':
								$maginBtProcurar = 24;
							break;
							case 'Safari':
								$maginBtProcurar = 24;
							break;
						}							
					?>
					<div id='cp_tit'>Dados do Arquivo de Retorno</div>
					<table border='0' cellspacing='0' cellpadding='0'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cp_LocalRecebimento'>Local Recebimento</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:6px'>Arq. Retorno</B><B id='cp_Arquivo'>Arquivo</B></td>
						</tr>
						<tr style='margin:0; heigth: 10px;'>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalRecebimento' tabindex='1' style='width:400px; margin-top:1px;' onChange='verificaLocalRecebimento(this.value);' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='EndArquivo'style='display:block;'>								
								<input type='text' name='IdArquivoRetorno' value='' autocomplete="off" style='width:72px;margin:0px 0px 0px auto;' maxlength='11' onChange="document.formulario.IdStatus.value = ''; document.formulario.Acao.value = 'inserir'; busca_arquivo_retorno(document.formulario.IdLocalRecebimento.value,this.value,false,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='2' />
								<input type="text" id="fakeupload" style='width:255px;' onclick='document.formulario.realupload.click();' name="fakeupload" class="fakeupload" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='3' />								
								<div id='bt_procurar' style='margin-top:-<?=$maginBtProcurar?>px; margin-left: 330px;' tabindex='4'></div>
								<input type="file" id="realupload" onmousemove="quadro_alt(event, this, '<?=$extensoesTitle?>');" name='EndArquivo' size='1' class="realupload" onchange="document.formulario.fakeupload.value = this.value; document.formulario.tempEndArquivo.value=document.formulario.EndArquivo.value; verifica_arquivo_retorno(document.formulario.IdLocalRecebimento.value, this.value);verificar_obrigatoriedade(this,<?=$extensoes?>);" /> 																								
							</td>
							<td class='campo' id='EnderecoArquivo' style='display:none;'>
								<input type='text' name='IdArquivoRetorno2' value='' autocomplete="off" style='width:72px' maxlength='11' onChange="document.formulario.IdStatus.value = ''; document.formulario.Acao.value = 'inserir'; busca_arquivo_retorno(document.formulario.IdLocalRecebimento.value,this.value,false,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2'><input type='text' class='agrupador' style='width:328px' name='EnderecoArquivo' value='' readonly='readonly' />
							</td>
						</tr>
						<tr>
							<td/>
							<td/>
							<td/>
							<td>Atenção, tamanho máximo do arquivo é 2M. Extensões: .ret .dat .crt .txt</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Arquivo de Retorno Tipo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Número Seqüencial</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Nome Original</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdArquivoRetornoTipo' value='' autocomplete="off" style='width:72px' maxlength='11' readonly='readonly' /><input type='text' class='agrupador' style='width:308px' name='DescricaoArquivoRetornoTipo' value='' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumSeqArquivo' value='' style='width:105px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeArquivo' value='' style='width:292px' maxlength='20' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Data Retorno</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>QTD. Lançamentos</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tamanho (KB)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Total (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Taxas (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Líquido (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataRetorno' value='' style='width:110px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdRegistro' value='' style='width:120px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='FileSize' value='' style='width:120px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotal' value='' style='width:127px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotalTaxa' value='' style='width:127px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorLiquido' value='' style='width:127px' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Log</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Log de Processamento</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='LogRetorno' style='width:816px;' rows='5' readonly='readonly'></textarea>
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
							<td class='descCampo'>Usuário Processamento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Processamento</td>
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
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginProcessamento' value='' style='width:180px'  maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataProcessamento' value='' style='width:203px' readonly='readonly' />
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width: 848px'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:left'>
								<input type='button' style='width:70px' name='bt_visualizar' value='Visualizar' class='botao' tabindex='4' onClick="buscaVisualizar();">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='text-align:right; padding-right:0px;'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='5' onClick="cadastrar('inserir')">
								<input type='button' name='bt_processar' value='Processar' class='botao' tabindex='7' onClick="cadastrar('processar')">
								<input type='button' name='bt_confirmar' value='Confirmar' class='botao' tabindex='7' onClick="cadastrar('confirmar')">
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='6' onClick="excluir(document.formulario.IdLocalRecebimento.value,document.formulario.IdArquivoRetorno.value)">
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
				<div id='cp_titulos_recebidos' style='margin-top:10px; display:none'>
					<div id='cp_tit' style='margin:0'>Títulos Recebidos</div>
					<table id='tabelaTitulosRecebidos' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Loja</td>
							<td>Nº Doc.</td>
							<td>Rec.</td>
							<td>Cliente</td>
							<td>Lançamento</td>
							<td>Data Venc.</td>
							<td class='valor'>Parc. (<?=getParametroSistema(5,1)?>)</td>
							<td>Data Receb.</td>
							<td class='valor'>Desc. (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Multa (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Receb. (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Desp. (<?=getParametroSistema(5,1)?>)</td>
							<td id='cpPosicaoCobranca' style='display:none'>Pos. Cobrança</td>
							<td>Recibo</td>
							<td>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='6' id='tabelaTotal'>Total: 0</td>
							<td id='cpValorTotal' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td id='ValorDescTotal' class='valor'>0,00</td>
							<td id='ValorMoraMultaTotal' class='valor'>0,00</td>
							<td id='ValorRecebidoTotal' class='valor'>0,00</td>
							<td id='ValorOutrasDespesasTotal' class='valor'>0,00</td>
							<td colspan='4'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<!-- ALERTA DE IRREGULARIDADES -->
				<div id='cp_conta_receber_ocorrencia' style='margin-top:10px; display:none'>
					<div id='cp_tit' style='margin:0'>Contas a Receber - Ocorrências</div>
					<table id='tabelaContaReceberOcorrencia' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Loja</td>
							<td>Nº Doc.</td>
							<td>Rec.</td>
							<td>Cliente</td>
							<td>Lançamento</td>
							<td>Data Venc.</td>
							<td class='valor'>Parc. (<?=getParametroSistema(5,1)?>)</td>
							<td>Data Receb.</td>
							<td class='valor'>Desc. (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Multa (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Receb. (<?=getParametroSistema(5,1)?>)</td>
							<td>Ocorrência</td>
							<td class='tableColEnd'><input type='checkbox' name='ContaReceberOcorrencia_ALL' value='' onClick='habilitar_campo(this, true);' /></td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='6' id='tabelaTotalContaReceberOcorrencia'>Total: 0</td>
							<td id='tabelaValorTotalContaReceberOcorrencia' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td id='tabelaValorDescTotalContaReceberOcorrencia' class='valor'>0,00</td>
							<td id='tabelaValorMoraMultaTotalContaReceberOcorrencia' class='valor'>0,00</td>
							<td id='tabelaValorRecebidoTotalContaReceberOcorrencia' class='valor'>0,00</td>
							<td colspan='4'>&nbsp;</td>
						</tr>
					</table>
					<table style='width: 848px'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right'>
								<input type='button' style='width:154px' name='bt_cancelar_recebimento' value='Cancelar Recebimento' class='botao' tabindex='9' onClick="cadastrar('cancelar_recebimento');" disabled='disabled'>
							</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		
		<script type="text/javascript">
		<?php
			if($local_IdLocalRecebimento!= '' && $local_IdArquivoRetorno!=""){
				echo "listaLocalRecebimento('$local_IdArquivoRetorno',$local_IdLocalRecebimento);";		
			} else{
				echo "listaLocalRecebimento('');";
			}	
		?>
			verificaAcao();
			inicia();
			verificaErro();
			enterAsTab(document.forms.formulario);
		</script>
	</body>
</html>