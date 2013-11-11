<?
	$localModulo		=	1;
	$localOperacao		=	205;
	$localSuboperacao	=	"V";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	
	$local_Acao 		= $_POST['Acao'];
	$local_Acao 		= $_POST['Acao'];	
	$local_Erro			= $_GET['Erro'];
	$Local				= "formulario";
	
	$local_IdPoste								=	formatText($_POST['IdPoste'],NULL);
	$local_IdTipoPoste							=	formatText($_POST['IdTipoPoste'],NULL);
	$local_NomePoste							= 	formatText($_POST['NomePoste'],NULL);
	$local_DescricaoPoste						= 	formatText($_POST['DescricaoPoste'],NULL);
	$local_IdPais								= 	$_POST['IdPais'];
	$local_IdEstado								= 	$_POST['IdEstado'];
	$local_IdCidade								= 	$_POST['IdCidade'];
	$local_Endereco								= 	formatText($_POST['Endereco'],NULL);
	$local_Numero								= 	formatText($_POST['Numero'],NULL);
	$local_Complemento							= 	formatText($_POST['Complemento'],NULL);
	$local_Bairro								= 	formatText($_POST['Bairro'],NULL);
	$local_Cep									= 	formatText($_POST['CEP'],NULL);
	$local_Latitude								= 	formatText($_POST['Latitude'],NULL);
	$local_Longitude							= 	formatText($_POST['Longitude'],NULL);

	if($_GET['IdPoste'] != ''){
		$local_IdPoste	= $_GET['IdPoste'];	
	}

	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_poste.php');
			break;
		case 'alterar':
			include('files/editar/editar_poste.php');
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
		<meta http-equiv="content-type" content="text/html; charset=ANSI" />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/poste.js'></script>
		<script type = 'text/javascript' src = 'js/poste_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Poste')">
		<? include('filtro_poste.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_poste.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='poste'>
				<div>
					<table >
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'>Poste</td>
							<td class='separador'>&nbsp;</td>						
							<td class='descCampo'><B>Poste Tipo</B></td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' >
								<input type='text' name='IdPoste' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_poste(this.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100'>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo' >
							<select name="IdTipoPoste" style="width: auto;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='101'>
								<option></option>
								<?php
									$sql = "SELECT
												IdPosteTipo,
												DescricaoPosteTipo
											FROM 
												PosteTipo";
									$res = mysql_query($sql,$con);
									while($lin = mysql_fetch_array($res)){
										echo "<option value='".$lin[IdPosteTipo]."'>".$lin[DescricaoPosteTipo]."</option>";
									}								
								?>
							</select>
							</td>
						</tr>
					</table>
						
					<div id='cp_tit'>Dados Poste</div>
					
					<table border="0">
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Nome Poste</B></td>	
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'>Descrição Poste</td>
						</tr>
						<tr>
							<td class='find'></td>	
							<td class='descCampo'><input  type='text' name='NomePoste' value="" style='width:398px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='102'></td>	
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input  type='text' name='DescricaoPoste' value="" style='width:401px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='103'>
							</td>							
						</tr>
					</table>
					
					<table>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="descCampo"><B>CEP</B></td>
							<td class="separador">&nbsp;</td>			
							<td class="descCampo"><B>Endereco</B></td>
							<td class="separador">&nbsp;</td>
							<td class="descCampo">N°</td>
							<td class="separador">&nbsp;</td>
							<td class="descCampo">Complemento</td>
							<td class="separador">&nbsp;</td>
							<td class="descCampo"><B>Bairro</B></td>
						</tr>
						<tr>
							<td class="find"><img src="../../img/estrutura_sistema/ico_lupa.gif" alt="Buscar" onclick="vi_id('quadroBuscaCep', true, event, null, 572);"/></td>
							<td class="campo">
								<input name="CEP" value="" style="width: 70px" maxlength="11" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" tabindex="104" type="text"/>
							</td>
							<td class="separador">&nbsp;</td>
							<td class="campo">
								<input name="Endereco" value="" style="width:266px" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" tabindex="105" type="text"/>	
							</td>
							<td class="separador">&nbsp;</td>							
							<td class="campo">
								<input name="Numero" value="" style="width:70px" maxlength="11" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" type="text">
							</td>
							<td class="separador">&nbsp;</td>							
							<td class="campo">
								<input name="Complemento" value="" style="width:150px"  onfocus="Foco(this,'in')" onblur="Foco(this,'out')"  type="text">
							</td>
							<td class="separador">&nbsp;</td>							
							<td class="campo">
								<input name="Bairro" value="" style="width:192px" maxlength="50" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" tabindex="110" type="text">
							</td>							
							
						</tr>
					</table>
					
					<table>
						<tr>
							<td class="find">&nbsp;</td>
							<td class="descCampo"><b style="margin-right:54px;">País</b>Nome País</td>
							<td class="separador">&nbsp;</td>		
							<td class="find">&nbsp;</td>		
							<td class="descCampo"><b style="margin-right:38px;">Estado</b>Nome Estado</td>
							<td class="separador">&nbsp;</td>
							<td class="find">&nbsp;</td>
							<td class="descCampo"><b style="margin-right:38px;">Cidade</b>Nome Cidade</td>	
						</tr>
						<tr>
							<td class="find">
								<img src="../../img/estrutura_sistema/ico_lupa.gif" alt="Buscar" onclick="vi_id('quadroBuscaPais', true, event, null, 572, 1);busca_pais_lista()"/></td>		
							<td class="campo">
								<input name="IdPais" value="" style="width:70px" maxlength="11" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" tabindex="111" onchange="busca_poste_pais(this.value,false,document.formulario.Local.value, 1)" onkeypress="mascara(this,event,'int')" type="text"/><input class="agrupador" name="Pais" value="" style="width:140px" maxlength="100" readonly="" type="text"/>
							</td>
							<td class="separador">&nbsp;</td>
							<td class="find">
								<img src="../../img/estrutura_sistema/ico_lupa.gif" alt="Buscar" onclick="vi_id('quadroBuscaEstado', true, event, null, 572, 1);busca_estado_lista()"/>
							</td>
							<td class="campo">
								<input name="IdEstado" value="" style="width:70px" maxlength="11" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" tabindex="112" onchange="busca_poste_estado(document.formulario.IdPais.value,this.value,false,document.formulario.Local.value,1)" onkeypress="mascara(this,event,'int')" type="text"/><input class="agrupador" name="Estado" value="" style="width:140px" maxlength="100" readonly="" type="text"/>	
							</td>
							<td class="separador">&nbsp;</td>
							<td class="find">
								<img src="../../img/estrutura_sistema/ico_lupa.gif" alt="Buscar" onclick="vi_id('quadroBuscaCidade', true, event, null, 572, 1);busca_cidade_lista()"/>
							</td>
							<td class="campo">
								<input name="IdCidade" value="" style="width:70px" maxlength="11" onfocus="Foco(this,'in')" onblur="Foco(this,'out')" tabindex="113" onchange="busca_poste_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,this.value,false,document.formulario.Local.value,1)" onkeypress="mascara(this,event,'int')" type="text"><input class="agrupador" name="Cidade" value="" style="width:233px" maxlength="100" readonly="" type="text"/>	
							</td>
						</tr>
					</table>
					
					<table border="0">
						<tr>
							<td class='find'>&nbsp;</td>								
							<td class='descCampo'><B>Latitude</B></td>
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><B>Longitude</B></td>	
						</tr>
						<tr>
							<td class='find'></td>				
							<td class='campo'>
								<input  type='text' name='Latitude' value="" style='width:160px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='114'>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input  type='text' name='Longitude' value="" style='width:160px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='115'>
							</td>
						</tr>
					</table>
					
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Log</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(132)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(133)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(93)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(135)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='115' onClick='cadastrar()' disabled />
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' 	class='botao' tabindex='116' onClick='cadastrar()' />
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='117' onClick="excluir(document.formulario.IdPoste.value,'Cadastro')" />
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
		<div id='quadros_fluantes'>
			<?
				include("files/busca/cep.php");
				include("files/busca/pais.php");
				include("files/busca/estado.php");
				include("files/busca/cidade.php");
			?>
		</div>
	</body>
</html>
<script>
	<?php
		if($local_IdPoste != ""){
			echo "busca_poste($local_IdPoste);";
		}
	?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>