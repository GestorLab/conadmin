<?php 
	$localModulo		=	1;
	$localOperacao		=	121;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];

	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];

	$local_MesVencimento		= $_POST['MesVencimento'];
	$local_IdContrato			= $_POST['IdContrato'];
	$local_IdGrupoPessoa		= $_POST['IdGrupoPessoa'];
	$local_IdFormaAvisoCobranca	= $_POST['IdFormaAvisoCobranca'];
		
	switch ($local_Acao){
		case 'gerar':
			include("rotinas/gerar_nota_fiscal_em_massa.php");
			header("Location: listar_nota_fiscal.php?ListaContrato=$local_IdContrato&filtro_data_inicio=01/".date('m/Y')."&filtro_data_termino=31/".date('m/Y')."&filtro_limit=$i");
			break;

		default:
			$local_Acao = 'gerar';
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/gerar_nota_fiscal_em_massa.js'></script>
		

    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Gerar Nota Fiscal em Massa')">		
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_gerar_nota_fiscal_em_massa.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?php echo $local_Acao; ?>'>
				<input type='hidden' name='Erro' value='<?php echo $local_Erro; ?>'>
				<input type='hidden' name='Local' value='GerarNotaFiscalEmMassa'>
				<input type='hidden' name='IdContaReceber' value=''>
				<input type='hidden' name='Visualizar' value=''>
				<input type='hidden' name='IdCidadeTemp' value='' />
				
				<div id='cpDadosLocalCobranca'>
					<div id='cp_tit' style='margin-top:0'>Dados do Gerar Nota Fiscal em Massa</div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cp_MesVencimento'>Mês Vencimento</B></td>												
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='MesVencimento' value='' autocomplete="off" style='width:100px' maxlength='7' onChange="verifica_mes('cp_MesVencimento',this)" onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>							
							</td>														
						</tr>
					</table>		
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'>Relação de Códigos dos Contratos</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='campo'>
								<textarea name='IdContrato' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'></textarea><BR>
								Ex.: 1234,5678,91011
							</td>							
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'>Grupo Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tipo Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Forma Aviso Cobrança</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Status Contrato</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Status Contas a Receber</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='campo'>
								<select name='IdGrupoPessoa' onFocus="Foco(this,'in')"  style='width:160px' onBlur="Foco(this,'out');" tabindex='3'>
									<option value=''>&nbsp;</option>
								<?php 
									$sql = "select
				      							IdGrupoPessoa,
							   			        DescricaoGrupoPessoa				      
											from
											    GrupoPessoa";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdGrupoPessoa]'>$lin[DescricaoGrupoPessoa]</option>";																		
									}
								?>
								</select>									
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoPessoa' onFocus="Foco(this,'in')"  style='width:98px' onBlur="Foco(this,'out');" tabindex='4'>
									<option value=''>&nbsp;</option>
								<?php 
									$sql = "select 
												IdParametroSistema,
												ValorParametroSistema 
											from
												ParametroSistema 
											where
												IdGrupoParametroSistema = 1 
											order by
												ValorParametroSistema";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
									}
								?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdFormaAvisoCobranca' onFocus="Foco(this,'in')"  style='width:140px' onBlur="Foco(this,'out');" tabindex='4'>
									<option value=''>&nbsp;</option>
								<?php 
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=76 order by ValorParametroSistema";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
									}
								?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatusContrato' onFocus="Foco(this,'in')"  style='width:200px' onBlur="Foco(this,'out');" tabindex='4'>
									<option value=''>&nbsp;</option>
								<?php 
									$i = 0;
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 69 order by ValorParametroSistema";
									$res = @mysql_query($sql, $con);
									while($lin = @mysql_fetch_array($res)){
										$vetor[$i] = substituir_string($lin[ValorParametroSistema])."#".$lin[IdParametroSistema];
										$i++;
									}
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 113 order by ValorParametroSistema";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										$vetor[$i] = substituir_string($lin[ValorParametroSistema])." (Todos)#G_".$lin[IdParametroSistema];
										$i++;
									}
									sort($vetor);
									foreach ($vetor as $key => $val) {
										$vet	= explode("#",$val);
										$id		= trim($vet[1]);	
										$value	= trim($vet[0]);	
										
										echo "<option value='$id'>$value</option>";
									}
								?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatusContaReceber' onFocus="Foco(this,'in')"  style='width:180px' onBlur="Foco(this,'out');" tabindex='4'>
									<option value=''>&nbsp;</option>
								<?php 
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 35 and IdParametroSistema not in (0,7,6) order by ValorParametroSistema";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
										
										echo "<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
									}
									
									echo "<option value='200'>Vencido</option>";
								?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Estado</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Cidade</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Servico</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdEstado' style='width:160px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_filtro_cidade_estado(this.value)">
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdEstado, NomeEstado from Estado where 1 order by NomeEstado asc";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo "<option value='$lin[IdEstado]' ".compara($local_IdEstado,$lin[IdEstado],"selected='selected'","").">$lin[NomeEstado]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdCidade' style='width:249px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)" tabindex='4'>
									<option value=''>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdServico' style='width:391px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')">
									<option value=''>&nbsp;</option>
									<?
										$sql = "SELECT 
												  IdServico,
												  DescricaoServico,
												  IdNotaFiscalTipo 
												FROM
												  Servico 
												WHERE IdLoja = $local_IdLoja 
												  AND IdNotaFiscalTipo != ''";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo "<option value='$lin[IdServico]' ".compara($local_IdServico,$lin[IdServico],"selected='selected'","").">$lin[IdServico] - 
											$lin[DescricaoServico]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px; text-align:right' border='0'>
						<tr>
							<td class='find'>&nbsp;</td>										
							<td class='campo' style='text-align: left'>
								<input type='button' style='width:100px' name='bt_analizar' value='Analizar' class='botao' tabindex='10' onClick="buscaAnalizar(); validar();">
							</td>				
							<td class='separador'>&nbsp;</td>					
							<td class='campo' style='text-align: right'>	
								<input type='button' style='width:70px' name='bt_gerar' value='Gerar' class='botao' tabindex='11' onClick="mutarPacoteNotaFiscal()">
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
				<div id='cp_titulos' style='margin-top:10px; display:none'>
					<div id='cp_tit' style='margin:0'>Contas à Receber</div>
					<table id='tabelaTitulos' class='tableListarCad' cellspacing='0'> 
						<tr class='tableListarTitleCad'>
							<td><input type='checkbox' name='check_geral' checked="true" onclick="MarcaDesMarcaContaReceber(this.checked)" /></td>
							<td class='tableListarEspaco'>Conta Rec.</td>
							<td>Cliente</td>
							<td>Data Lanc.</td>
							<td>Data Venc.</td>
							<td class='valor'>Valor (<?php echo getParametroSistema(5,1); ?>)</td>					
							<td>Status</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='4' id='tabelaTotal'>Total: 0</td>									
							<td id='ValorTotal' class='valor'>0,00</td>
							<td colspan='2'>&nbsp;</td>
						</tr>
					</table>
				</div>											
			</form>
		</div>	
		<script type='text/javascript'>
			verificaErro();
			verificaAcao();
			inicia();
			enterAsTab(document.forms.formulario);
		</script>
	</body>	
</html>