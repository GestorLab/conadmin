<?
	$localModulo		=	1;
	$localOperacao		=	113;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdNotaFiscalLayout	= $_POST['IdNotaFiscalLayout'];
	$local_IdNotaFiscalTipo		= $_POST['IdNotaFiscalTipo'];
		
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_nota_fiscal_parametro.php');
			break;
		default:
			$local_Acao 	 		= 'alterar';
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
		<script type = 'text/javascript' src = 'js/nota_fiscal_parametro.js'></script>
		<script type = 'text/javascript' src = 'js/nota_fiscal_parametro_default.js'></script>

    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Nota Fiscal Parâmetro')">
		<? include('filtro_nf_2_via_eletronica_remessa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_nota_fiscal_parametro.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='NotaFiscalParametro'>
				<input type='hidden' name='IdNotaFiscalLayout' value=''>
				<input type='hidden' name='IdNotaFiscalTipo' value=''>
				
				<div id='cpDadosLocalCobranca'>
					<div id='cp_tit' style='margin-top:0'>Dados do Nota Fiscal Parametro</div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Nota Fiscal Tipo</B></td>						
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdNotaFiscalTipo' onFocus="Foco(this,'in')"  style='width:350px' onBlur="Foco(this,'out');" onChange='busca_nota_fiscal_layout_parametro(this.value, true, document.formulario.Local.value)' tabindex='1'>
								<option value=''>&nbsp;</option>
								<?
									$sql = "select
												NotaFiscalTipo.IdNotaFiscalTipo,
												NotaFiscalLayout.DescricaoNotaFiscalLayout
											from
												NotaFiscalTipo,
												NotaFiscalLayout
											where
												NotaFiscalTipo.IdLoja = $local_IdLoja and
												NotaFiscalTipo.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout and
												NotaFiscalTipo.IdStatus = 1
											order by
												DescricaoNotaFiscalLayout";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdNotaFiscalTipo]' ".compara($lin[IdNotaFiscalTipo],$local_IdNotaFiscalTipo,"selected","").">$lin[DescricaoNotaFiscalLayout]</option>";
									}
								?>
								</select>	
							</td>							
						</tr>
					</table>		
				</div>
				<div>
					<div id='cp_tit'>Dados dos Parâmetros</div>	
					<div id='cpDadosParametros' style='display: none'>					
						<table id='tableParametro'>
							<tr>
								<td class='find'>&nbsp;</td>
							<tr>
						</table>		
					</div>									
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>							
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='100' onClick='cadastrar()'>
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
	<?
		if($local_IdNotaFiscalTipo != ""){
			echo "busca_nota_fiscal_layout_parametro($local_IdNotaFiscalTipo,false);";
			echo "scrollWindow('bottom');";			
		}				
	?>
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>

