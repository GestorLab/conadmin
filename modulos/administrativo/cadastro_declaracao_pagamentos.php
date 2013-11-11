<?
	$localModulo			= 1;
	$localOperacao			= 120;
	$localSuboperacao		= "V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_IdPessoa 		= $_POST['Filtro_IdPessoa'];
	$local_IdStatusContrato = $_POST['Filtro_IdStatusContrato'];
	$local_AnoReferencia	= $_POST['AnoReferencia'];
	$local_IdTipoPessoa		= $_POST['IdTipoPessoa'];

	switch ($local_Acao){
		case 'exportar':
			header("Location: rotinas/processar_declaracao_pagamento.php?IdPessoa=$local_IdPessoa&IdStatusContrato=$local_IdStatusContrato&AnoReferencia=$local_AnoReferencia&IdTipoPessoa=$local_IdTipoPessoa&IdStatusContrato=$local_IdStatusContrato");
			break;
		default:
			$local_Acao 	= 'exportar';
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
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/processo_financeiro_default.js'></script>
		<script type = 'text/javascript' src = 'js/declaracao_pagamento.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Declaração de Pagamentos')">
	<div id='carregando'>carregando</div>
	<div id='conteudo'>
		<form name='formulario' method='post' action='cadastro_declaracao_pagamentos.php' onSubmit='return validar()'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
			<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
			<input type='hidden' name='Local' value='DeclaracaoPagamento'>
			<input type='hidden' name='Filtro_IdPessoa' value=''>
			<input type='hidden' name='Filtro_IdStatusContrato' value=''>
			<div>
				<div id='cp_tit' style='margin-top:0'>Filtros</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B>Ano Referência<B></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Tipo Pessoa</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<select name='AnoReferencia' style='width:111px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="habilitar_campo_pessoa(this.value)"  tabindex='1'>
								<option value='' selected></option>
								<?
									$sql = "
										select 
											distinct
											Ano
										from
											(select
												substring(DataRecebimento, 1, 4) Ano 
											 from
											 	ContaReceberRecebimento)AnoReferencia
											 where
												Ano < YEAR(curdate())
										order by 
											Ano;
									";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[Ano]'>$lin[Ano]</option>";
									}
								?>
							</select>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<select name='IdTipoPessoa' style='width:140px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  tabindex='2'>
								<option value='' selected>Todos</option>
								<?
									$sql = "
										select 
											IdParametroSistema, 
											ValorParametroSistema 
										from 
											ParametroSistema 
										where 
											IdGrupoParametroSistema=1 
										order by IdParametroSistema ASC;
									";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
									}
								?>
							</select>
						</td>
					</tr>
				</table>
				<div class='cp_tit' id='Filtros'>Filtros</div>
				<div id='cp_filtro_status'>
					<table id='titTabelaStatus'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Status Contrato</td>
							<td class='separador'>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatusContrato' style='width:727px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_add_status' value='Adicionar' class='botao' style='width:84px;' tabindex='4' onClick="addStatus(document.formulario.IdStatusContrato.value,'')">
							</td>
						</tr>
					</table>
					<table id='tabelaStatus' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:60px'>Id</td>
							<td>Status</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='5' id='totaltabelaStatus'>Total: 0</td>
						</tr>
					</table>
				</div>
				<?	
					$nome="	<table id='titTabelaPessoa' style='margin:10px 0 5px 0'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B>Nome Pessoa</td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 60); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();\"></td>
								<td class='campo'>
									<input type='text' name='IdPessoaFiltro' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='5'><input type='text' class='agrupador' name='NomeFiltro' value='' style='width:646px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add' value='Adicionar' class='botao' style='width:84px;' tabindex='6' onClick=\"busca_pessoa(document.formulario.IdPessoaFiltro.value,false,'AdicionarEtiqueta')\">
								</td>
							</tr>
						</table>";
						
					$razao="
						<table id='titTabelaPessoa'style='margin:10px 0 5px 0'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B><B style='color:#000'>Razão Social</B></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 60); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();\"></td>
								<td class='campo'>
									<input type='text' name='IdPessoaFiltro' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='5'><input type='text' class='agrupador' name='NomeFiltro' value='' style='width:646px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add' value='Adicionar' class='botao' style='width:84px;' tabindex='6' onClick=\"busca_pessoa(document.formulario.IdPessoaFiltro.value,false,'AdicionarEtiqueta')\">
								</td>
							</tr>
						</table>";
						
					switch(getCodigoInterno(3,24)){
						case 'Nome':
							echo "$razao";
							break;
						default:
							echo "$nome";
					}
				?>
				<table id='tabelaPessoa' class='tableListarCad' cellspacing='0'>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' style='width:60px'>Pessoa</td>
						<td>Razão Social</td>
						<td>Nome Pessoa</td>
						<td>Telefone</td>
						<td>CNPJ/CPF</td>
						<td>Cidade</td>
						<td>UF</td>
						<td class='bt_lista'>&nbsp;</td>
					</tr>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' colspan='8' id='totaltabelaPessoa'>Total: 0</td>
					</tr>
				</table>
			</div>
			<div class='cp_botao'>
				<table style='margin:9px 6px 0 0'>
					<tr>
						<td class='find'>&nbsp;</td>
						<td style='text-align:right;' class='campo'>
							<input type='button' name='bt_exportar' value='Exportar' class='botao' tabindex='7' onClick='cadastrar()'>
						</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td style='text-align:justify; padding-top:9px;'>
							LEI Nº 12.007, Art. 3°  A declaração de quitação anual deverá ser encaminhada ao consumidor por ocasião do encaminhamento da fatura a vencer no mês de maio do ano seguinte ou no mês subseqüênte à completa quitação dos débitos do ano anterior ou dos anos anteriores, podendo ser emitida em espaço da própria fatura.
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
			<? include("files/busca/pessoa.php"); ?>
		</div>
	</body>	
</html>
<script>
	inicia();
	enterAsTab(document.forms.formulario);
</script>