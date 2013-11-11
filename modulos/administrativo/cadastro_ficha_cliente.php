<?
	$localModulo			= 1;
	$localOperacao			= 2;
	$localSuboperacao		= "V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_PeriodoInicial	= $_POST['PeriodoInicial'];	
	$local_PeriodoFinal		= $_POST['PeriodoFinal'];
	$local_TorrePainel		= $_POST['TorrePainel'];
	$local_Quantidade		= $_POST['Quantidade'];
	$local_IdStatusContrato	= $_POST['IdStatusContrato'];
	

	switch ($local_Acao){
		case 'exportar':
			header("Location: rotinas/link_ficha_clientes.php?PeriodoInicial=$local_PeriodoInicial&PeriodoFinal=$local_PeriodoFinal&TorrePainel=$local_TorrePainel&IdStatusContrato=$local_IdStatusContrato&Quantidade=$local_Quantidade");
			break;
		default:
			$local_Acao 	= 'exportar';
			break;
	}
	
	LimitVisualizacao("filtro");
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
		<script type = 'text/javascript' src = 'js/ficha_cliente.js'></script> 

		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />

    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Ficha de Clientes')">
	<div id='carregando'>carregando</div>
	<div id='conteudo'>
		<form name='formulario' method='post' action='cadastro_ficha_cliente.php' onSubmit='return validar()'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
			<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
			<input type='hidden' name='Local' value='FichaPessoa'>
			
			<div>
				<div id='cp_tit' style='margin-top:0'>Filtros</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Período Inicial</td>					
						<td class='separador'>&nbsp;</td>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Período Final</td>
						<td class='find'>&nbsp;</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Torre Painel</td>		
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Status</td>	
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Qtd.</td>																							
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='PeriodoInicial' id='cpPeriodoInicial' value='' style='width:105px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('PeriodoInicial',this)" tabindex='1'>
						</td>
						<td class='find'><img id='cpPeriodoInicialIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
						<script type="text/javascript">
						    Calendar.setup({
						        inputField     : "cpPeriodoInicial",
						        ifFormat       : "%d/%m/%Y",
						        button         : "cpPeriodoInicialIco"
						    });
						</script>	
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='PeriodoFinal' id='cpPeriodoFinal' value='' style='width:105px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('PeriodoFinal',this)" tabindex='2'>
						</td>
						<td class='find'><img id='cpPeriodoFinalIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
						<script type="text/javascript">
						    Calendar.setup({
						        inputField     : "cpPeriodoFinal",
						        ifFormat       : "%d/%m/%Y",
						        button         : "cpPeriodoFinalIco"
						    });
						</script>
						<td class='separador'>&nbsp;</td>					
						<td class='campo'>
							<input type='text' name='TorrePainel' value='' style='width:200px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
						</td>
						<td class='separador'>&nbsp;</td>
						<td>
							<select name='IdStatusContrato' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:165px' tabindex='4'>
								<option value=''>Todos</option>
								<?
									
									$i	=	0;
									
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										$vetor[$i]	=	substituir_string($lin[ValorParametroSistema])."#".$lin[IdParametroSistema];
										$i++;
									}
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=113 order by ValorParametroSistema";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										$vetor[$i]	=	substituir_string($lin[ValorParametroSistema])." (Todos)#G_".$lin[IdParametroSistema];
										$i++;
									}
									
									sort($vetor);
									
									foreach ($vetor as $key => $val) {
										$vet	=	explode("#",$val);
										$id		=	trim($vet[1]);	
										$value	=	trim($vet[0]);	
										
										echo "<option value='$id' ".compara($localIdStatus,$id,"selected='selected'","").">$value</option>";
									}
								?>
							</select>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='Quantidade' value='<?=$Limit?>' style='width:50px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'>
						</td>		
					</tr>
				</table>
			</div>
			<div class='cp_botao'>
				<table style='margin:9px 6px 0 0; width: 100%' >
					<tr>
						<td class='find'>&nbsp;</td>
						<td style='text-align:right;' class='campo'>
							<input type='button' name='bt_exportar' value='Exportar' class='botao' tabindex='6' onClick='cadastrar()'>
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
	inicia();
	enterAsTab(document.forms.formulario);
</script>

