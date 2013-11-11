<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdNotaFiscalLayout	= $_POST['IdNotaFiscalLayout'];
	$local_PeriodoApuracao		= $_POST['PeriodoApuracao'];
	$local_IdNotaFiscal			= $_POST['IdNotaFiscal'];

	switch ($local_Acao){
		case 'reprocessar':	
				$local_PeriodoApuracaoTemp = dataConv($local_PeriodoApuracao,"m-Y","Y-m");

				$sql = "select
						IdContaReceber
					from
						NotaFiscal
					where
						IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
						IdLoja = $local_IdLoja and
						PeriodoApuracao = '$local_PeriodoApuracaoTemp' and
						IdNotaFiscal = $local_IdNotaFiscal";
				$res = mysql_query($sql,$con);
				$lin = mysql_fetch_array($res);
	
				reprocessa_nf($local_IdLoja, $lin[IdContaReceber]);
			break;
		default:
			$local_Acao = 'reprocessar';
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
		<script type = 'text/javascript' src = 'js/nota_fiscal_reprocessar.js'></script>
		<script type = 'text/javascript' src = 'js/nota_fiscal_reprocessar_default.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Nota Fiscal Reprocessar')">
		<? include('filtro_nota_fiscal.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_nota_fiscal_reprocessar.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Local' value='NotaFiscalReprocessar'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Nota Fiscal Layout</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Período Apuração</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Nota Fiscal</B></td>
						</tr>
						<tr>						
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdNotaFiscalLayout' onFocus="Foco(this,'in')"  style='width:350px' onBlur="Foco(this,'out');" onChange='busca_Periodo_Apuracao(this.value)' tabindex='1'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select
													IdNotaFiscalLayout,
													DescricaoNotaFiscalLayout
												from
													NotaFiscalLayout
												where
													IdNotaFiscalLayout in (1, 2)
												order by
													DescricaoNotaFiscalLayout";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdNotaFiscalLayout]'>$lin[DescricaoNotaFiscalLayout]</option>";
										}
									?>
								</select>							
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='PeriodoApuracao' onFocus="Foco(this,'in')"  style='width:120px' onChange='buscaNotaFiscalPorPeriodoApuracao()' onBlur="Foco(this,'out');" tabindex='2'>
									<option value=''>&nbsp;</option>							
								</select>							
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdNotaFiscal' value='' autocomplete="off" style='width:120px' maxlength='11' onChange='busca_nota_fiscal_reprocessar(document.formulario.IdNotaFiscalLayout.value, document.formulario.PeriodoApuracao.value, this.value, false, document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>												
						</tr>
					</table>
				</div>
				<div id='cp_nota_fiscal'>
					<div id='cp_tit'>Dados Nota Fiscal</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='tit_DataEmissao' style='color:#000'>Data Emissão</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Total NF (<?=getParametroSistema(5,1)?>)</td>					
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataEmissao' id='cpDataEmissao' value='' style='width:95px' tabindex='4' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorNotaFiscal' value='' style='width:120px' tabindex='5' readOnly>
							</td>						
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px'  readOnly>
							</td>						
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_reprocessar' value='Reprocesar' class='botao' tabindex='2' onClick='cadastrar()'>									
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
<script>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
