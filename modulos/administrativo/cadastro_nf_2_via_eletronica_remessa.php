<?
	$localModulo		=	1;
	$localOperacao		=	110;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Acao 					= $_POST['Acao'];
	$local_Erro						= $_GET['Erro'];
	$local_ErroEmail				= $_GET['EmailErro'];
	$local_TipoEmail				= $_GET['TipoEmail'];
	
	$local_MesReferencia			= $_POST['MesReferencia'];
	$local_IdNotaFiscalLayout		= $_POST['IdNotaFiscalLayout'];	
	$local_IdStatusArquivoMestre	= $_POST['IdStatusArquivoMestre'];	
		
	if($_GET['IdNotaFiscalLayout']!=''){
		$local_IdNotaFiscalLayout	=	$_GET['IdNotaFiscalLayout'];
	}
	
	if($_GET['MesReferencia']!=''){
		$local_MesReferencia		=	$_GET['MesReferencia'];
	}
	
	if($_GET['IdStatusArquivoMestre']!=''){
		$local_IdStatusArquivoMestre		=	$_GET['IdStatusArquivoMestre'];
	}	
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_nf_2_via_eletronica_remessa.php');
			break;		
		case 'processar':
			include('rotinas/processar_nf_2_via_eletronica_remessa.php');
			$local_Acao =	'processar';
			break;
		case 'confirmar':
			include('rotinas/confirmar_nf_2_via_eletronica_remessa.php');
			$local_Acao =	'confirmar';
			break;	
		case 'enviar':
			header("Location: cadastro_enviar_mensagem.php?IdNotaFiscalLayout=$local_IdNotaFiscalLayout&MesReferencia=$local_MesReferencia&IdStatusArquivoMestre=$local_IdStatusArquivoMestre");
			break;
		case 'confirmar_entrega':
			$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Entrega confirmada.";

			$sql = "update NotaFiscal2ViaEletronicaArquivo set 
							LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento),
							IdStatus='4'
					where 
						IdLoja='$local_IdLoja' and 
						IdNotaFiscalLayout='$local_IdNotaFiscalLayout' and 
						MesReferencia='$local_MesReferencia'";
			if(mysql_query($sql,$con) == true){
				$local_Erro = 176;
			}else{
				$local_Erro = 177;		
			}
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
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 	
		<script type = 'text/javascript' src = 'js/nf_2_via_eletronica_remessa.js'></script>		
		<script type = 'text/javascript' src = 'js/nf_2_via_eletronica_remessa_default.js'></script>		
		
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Nota Fiscal 2ª Via Eletrônica (Remessa)')">
	<? include('filtro_nf_2_via_eletronica_remessa.php'); ?>
	<div id='carregando'>carregando</div>
	<div id='conteudo'>
		<form name='formulario' method='post' action='cadastro_nf_2_via_eletronica_remessa.php' onSubmit='return validar()'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
			<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
			<input type='hidden' name='IdStatus' value=''>
			<input type='hidden' name='Local' value='NotaFiscal2ViaEletronicaRemessa'>
			<input type='hidden' name='MesAtual' value='<?=date("Y-m")?>'>
			<input type='hidden' name='NotaFiscalTransmitir' value='2'>
			<div>		
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B>Modelo</B></td>	
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><B id='cp_PeriodoApuracao'>Período de Apuração</B></td>					
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><B>Status</B></td>	
						<td class='find'>&nbsp;</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<select name='IdNotaFiscalLayout' onFocus="Foco(this,'in')"  style='width:350px' onBlur="Foco(this,'out');" onChange='busca_nf_2_via_eletronica_remessa(this.value, document.formulario.MesReferencia.value, document.formulario.IdStatusArquivoMestre.value, false, document.formulario.Local.value);' tabindex='1'>
								<option value=''>&nbsp;</option>
								<?
									$sql = "select
												IdNotaFiscalLayout,
												DescricaoNotaFiscalLayout
											from
												NotaFiscalLayout
											where
												IdNotaFiscalLayout = 1 or IdNotaFiscalLayout = 2
											order by
												DescricaoNotaFiscalLayout";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdNotaFiscalLayout]' ".compara($lin[IdNotaFiscalLayout],$local_IdNotaFiscalLayout,"selected","").">$lin[DescricaoNotaFiscalLayout]</option>";
									}
								?>
							</select>							
						</td>					
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='MesReferencia' value='<?=$local_MesReferencia?>' autocomplete="off" style='width:120px' maxlength='7' onkeypress="mascara(this,event,'mes')" onChange="busca_nf_2_via_eletronica_remessa(document.formulario.IdNotaFiscalLayout.value, this.value, document.formulario.IdStatusArquivoMestre.value, false, document.formulario.Local.value); verifica_mes('cp_PeriodoApuracao',this);" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
						</td>	
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<select name='IdStatusArquivoMestre' onFocus="Foco(this,'in')"  style='width:90px' onBlur="Foco(this,'out');" onChange='busca_nf_2_via_eletronica_remessa(document.formulario.IdNotaFiscalLayout.value, document.formulario.MesReferencia.value, this.value, false, document.formulario.Local.value);' tabindex='3'>
								<option value=''>&nbsp;</option>
								<?
									$sql = "select 
												IdParametroSistema, 
												ValorParametroSistema, 
												DescricaoParametroSistema 
											from 
												ParametroSistema 
											where 
												IdGrupoParametroSistema=140 
											order by 
												ValorParametroSistema";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){	
										$ValorParametroSistema = explode("-",$lin[DescricaoParametroSistema]);
										echo"<option value='$lin[ValorParametroSistema]' ".compara($lin[ValorParametroSistema],$local_StatusMestre,"selected","").">$ValorParametroSistema[2]</option>";
									}
								?>
							</select>							
						</td>				
						<td class='separador'>&nbsp;</td>
						<td class='descricao' style='width:210px;'><B id='cp_Status'>&nbsp;</B></td>					
					</tr>
				</table>
				<div id='cp_tit' style='margin-top:0'>Dados do Contribuinte</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Nome Comercial (Razão Social / Denominação)</td>	
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Inscrição Estadual</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>CNPJ/MF</td>						
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='RazaoSocial' value='' style='width:463px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='InscricaoEstadual' value='' style='width:143px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='CNPJ' value='' style='width:176px' readOnly>
						</td>							
					</tr>
				</table>				
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Endereço</td>						
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='Endereco' value='' style='width:816px' readOnly>
						</td>					
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>CEP</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Bairro</td>					
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Município</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>UF</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='CEP' value='' style='width:75px' readOnly>
						</td>						
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='Bairro' value='' style='width:350px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>					
						<td class='campo'>
							<input type='text' name='NomeCidade' value='' style='width:310px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='SiglaEstado' value='' style='width:30px' readOnly>
						</td>
					</tr>
				</table>
				<div id='cp_tit' style='margin-top:0'>Dados do Arquivo Mestre de Documento Fiscal</div>		
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Qtde de Registros</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Qtde NF Canceladas</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Nome do Arquivo</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Status</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='QtdNF' value='' style='width:146px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='QtdNFCancelado' value='' style='width:146px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='NomeArquivoMestre' value='' style='width:410px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='StatusArquivoMestre' value='' style='width:63px' readOnly>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Data 1ª NF</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Data Última NF</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Número da 1ª NF</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Número Última NF</td>							
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='DataPrimeiraNF' value='' style='width:191px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='DataUltimaNF' value='' style='width:191px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='NumeroPrimeiraNF' value='' style='width:191px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='NumeroUltimaNF' value='' style='width:192px' readOnly>
						</td>											
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Valor Base Cálculo (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>						
						<td class='descCampo'>Valor ICMS (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>						
						<td class='descCampo'>Valor Não Trib. (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>						
						<td class='descCampo'>Outros Valores (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>						
						<td class='descCampo'>Valor Total Canc. (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Valor Total (<?=getParametroSistema(5,1)?>)</td>	
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalBaseCalculo' value='' style='width:121px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalICMS' value='' style='width:121px' readOnly>
						</td>								
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalIsentoNaoTributado' value='' style='width:121px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalOutros' value='' style='width:121px' readOnly>
						</td>	
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalCancelado' value='' style='width:121px' readOnly>
						</td>	
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotal' value='' style='width:121px' readOnly>
						</td>
					</tr>
				</table>				
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Cód. Autenficação Digital do Arq.</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Endereço do Arquivo</td>					
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='CodigoAutenticacaoDigitalArquivoMestre' value='' style='width:230px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='EnderecoArquivoMestre' value='' style='width:569px' readOnly>
						</td>
					</tr>
				</table>				
				<div id='cp_tit' style='margin-top:0'>Dados do Arquivo Item de Documento Fiscal</div>	
					<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Qtde de Registros</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Qtde Itens Cancelados</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Nome do Arquivo</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Status</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='QtdItem' value='' style='width:146px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='QtdItemCancelado' value='' style='width:146px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='NomeArquivoItem' value='' style='width:410px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='StatusArquivoItem' value='' style='width:63px' readOnly>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Valor Total (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Valor Descontos (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Acrés. e Despesas Acessórias (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Valor Base de Calcúlo ICMS (<?=getParametroSistema(5,1)?>)</td>		
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalItem' value='' style='width:191px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalItemDesconto' value='' style='width:191px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalAcrecimoDespesas' value='' style='width:191px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalItemBaseCalculo' value='' style='width:191px' readOnly>
						</td>			
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Valor ICMS (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Valor Isentas/Não Tributadas (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Outros Valores (<?=getParametroSistema(5,1)?>)</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Cód. Autenticação Digital do Arq.</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalItemICMS' value='' style='width:191px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalItemIsentoNaoTributado' value='' style='width:191px' readOnly>
						</td>						
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ValorTotalItemOutros' value='' style='width:191px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='CodigoAutenticacaoDigitalArquivoItem' value='' style='width:192px' readOnly>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Endereço do Arquivo</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='EnderecoArquivoItem' value='' style='width:569px' readOnly>
						</td>
					</tr>
				</table>				
				<div id='cp_tit' style='margin-top:0'>Dados do Arquivo Destinatário de Documento Fiscal</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Qtde de Registros</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Cód. Autentificação Digital do Arq.</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Nome do Arquivo</td>					
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Status</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='QtdRegistroDestinatario' value='' style='width:146px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='CodigoAutenticacaoDigitalArquivoDestinatario' value='' style='width:230px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='NomeArquivoDestinatario' value='' style='width:326px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='StatusArquivoDestinatario' value='' style='width:63px' readOnly>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Endereço do Arquivo</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='EnderecoArquivoDestinatario' value='' style='width:569px' readOnly>
						</td>
					</tr>
				</table>
				<div id='cp_tit' style='margin-top:0'>Dados do Responsável</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Nome</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Data</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Cargo</td>				
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='NomeResponsavel' value='' style='width:357px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='DataResponsavel' value='' style='width:117px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='CargoResponsavel' value='' style='width:308px' readOnly>
						</td>					
					</tr>
				</table>
				<table>
					<tr>			
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Telefone</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>E-mail</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='TelefoneResponsavel' value='' style='width:110px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='EmailResponsavel' value='' style='width:227px' readOnly>
						</td>
					</tr>
				</table>
				<div id='cp_log'>
					<div id='cp_tit' style='margin-top:10px'>Logs</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Log de Processamento [ <a href="#" onclick="atualizaLogProcessamento(document.formulario.IdNotaFiscalLayout.value,false,document.formulario.MesReferencia.value,document.formulario.IdStatusArquivoMestre.value)">Atualizar</a> ]</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='LogProcessamento' style='width: 816px;' rows=5 readOnly></textarea>
							</td>
						</tr>
					</table>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'   readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
							</td>												
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usuário Processamento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Processamento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário Confirmação</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Confirmação</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginProcessamento' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataProcessamento' value='' style='width:202px'readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginConfirmacao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataConfirmacao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>				
			</div>
			<div class='cp_botao'>
				<table style='width:848px; text-align:right' border='0'>
					<tr>
						<td class='find'>&nbsp;</td>										
						<td class='campo' style='text-align: left; width:262px'>
							<input type='button' style='width:100px' name='bt_enviar' value='Enviar E-mail' class='botao' tabindex='3' onClick="cadastrar('enviar')">
							<input type='button' style='width:145px' name='bt_imprimir_nota' value='Imprimir Notas Fiscais' class='botao' tabindex='3' onClick="imprimir_nota_fiscal(document.formulario.IdNotaFiscalLayout,document.formulario.MesReferencia)">
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo' style='text-align: right;'>
							<input type='button' style='width:120px' name='bt_confirmar_entrega' value='Confirmar Entrega' class='botao' tabindex='9' onClick="cadastrar('confirmar_entrega')">
							<input type='button' style='width:75px' name='bt_inserir' value='Cadastrar' class='botao' tabindex='5' onClick="cadastrar('inserir')">								
							<input type='button' style='width:60px' name='bt_excluir' value='Excluir' class='botao' tabindex='6' onClick="excluir(document.formulario.IdNotaFiscalLayout.value,document.formulario.MesReferencia.value,document.formulario.IdStatus.value,document.formulario.IdStatusArquivoMestre.value)">
						</td>							
						<td class='separador'>&nbsp;</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo' style='text-align: right'>
							<input type='button' style='width:80px' name='bt_processar' value='Processar' class='botao' tabindex='7' onClick="cadastrar('processar')">
							<input type='button' style='width:70px' name='bt_confirmar' value='Confirmar' class='botao' tabindex='8' onClick="cadastrar('confirmar')">						
						</td>
					</tr>
				</table>
			</div>
			<table>
				<tr>
					<td class='find'>&nbsp;</td>
					<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
				</tr>
			</table>
			
		</form>
		</div>		
	</body>	
</html>
<script>
	<?
		if($local_IdNotaFiscalLayout !='' && $local_MesReferencia !=''){
			echo "busca_nf_2_via_eletronica_remessa($local_IdNotaFiscalLayout, '$local_MesReferencia', '$local_IdStatusArquivoMestre', false, document.formulario.Local.value);";		
			echo "scrollWindow('bottom');";		
		}
	?>
	
	inicia();
	verificaAcao();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>