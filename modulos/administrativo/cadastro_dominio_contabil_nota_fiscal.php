<?
	$localModulo		=	1;
	$localOperacao		=	141;
	$localSuboperacao	=	"P";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
		
	$local_CodigoEmpresa	  = $_POST['CodigoEmpresa'];
	$local_CodigoAcumulador	  = $_POST['CodigoAcumulador'];
	$local_DoctoFinal		  = $_POST['DoctoFinal'];
	$local_IdNotaFiscalLayout = $_POST['IdNotaFiscalLayout'];
	$local_MesVencimento	  = $_POST['MesVencimento'];
		
	switch ($local_Acao){	
		case 'gerar':
			include("rotinas/gerar_dominio_contabil_nota_fiscal.php");
			break;
		default:			
			$local_Acao 	 		= 'gerar';
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
		<script type = 'text/javascript' src = 'js/dominio_contabil_nota_fiscal.js'></script>	

    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Domínio Contábil')">		
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='menu_dominio_contabil_nota_fiscal.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='DominioContabil'>
				
				<div>
					<div id='cp_tit' style='margin-top:0'>Dados de Geração do Arquivo</div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Modelo Nota Fiscal</B></td>
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><B id='cp_MesVencimento'>Mês Vencimento</B></td>												
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>	
							<td class='campo'>
								<select name='IdNotaFiscalLayout' onFocus="Foco(this,'in')"  style='width:381px' onBlur="Foco(this,'out');" tabindex='1'>
									<option value=''>&nbsp;</option>
								<?
									$sql = "select
				      							IdNotaFiscalLayout,
							   			        DescricaoNotaFiscalLayout				      
											from
											    NotaFiscalLayout";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdNotaFiscalLayout]'>$lin[DescricaoNotaFiscalLayout]</option>";																		
									}
								?>
								</select>									
							</td>			
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='MesVencimento' value='' autocomplete="off" style='width:100px' maxlength='7' onChange="verifica_mes('cp_MesVencimento',this)" onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>							
							</td>														
						</tr>
					</table>								
				</div>				
				<div id='cpDadosDominioContabil'>
					<div id='cp_tit' style='margin-top:0'>Dados do Domínio Contábil</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Código Empresa</B></td>
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><B>Código Acumulador</B></td>
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'>Docto Final</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='CodigoEmpresa' value='' autocomplete="off" style='width:120px' maxlength='7' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>							
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CodigoAcumulador' value='' autocomplete="off" style='width:120px' maxlength='7' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>							
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DoctoFinal' value='' autocomplete="off" style='width:101px' maxlength='7' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'>							
							</td>
						</tr>
					</table>								
				</div>
	
				<div class='cp_botao'>
					<table style='width:848px; text-align:right' border='0'>
						<tr>									
							<td class='separador'>&nbsp;</td>					
							<td class='campo' style='text-align: right'>	
								<input type='button' style='width:70px' name='bt_gerar' value='Gerar' class='botao' tabindex='6' onClick="cadastrar('gerar')">
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
	verificaErro();
	inicia();
	function status_inicial(){
		if(document.formulario.CodigoEmpresa.value == 0){
			document.formulario.CodigoEmpresa.value	=	'<?=getCodigoInterno(36,1)?>';
		}	
		if(document.formulario.CodigoAcumulador.value == 0){
			document.formulario.CodigoAcumulador.value	=	'<?=getCodigoInterno(36,2)?>';
		}
		if(document.formulario.DoctoFinal.value == 0){
			document.formulario.DoctoFinal.value	=	'<?=getCodigoInterno(36,3)?>';
		}
	}	
	status_inicial();
	enterAsTab(document.forms.formulario);
</script>
