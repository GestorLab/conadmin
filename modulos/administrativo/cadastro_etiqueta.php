<?
	$localModulo		=	1;
	$localOperacao		=	107;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login					= $_SESSION["Login"];
	$local_Acao 					= $_POST['Acao'];
	$local_Erro						= $_GET['Erro'];
	$local_Filtro_IdContaReceber	= $_POST['Filtro_IdContaReceber'];
	$local_IdProcessoFinanceiro		= $_POST['IdProcessoFinanceiro'];
	$local_Filtro_IdPessoa 			= $_POST['Filtro_IdPessoa'];
	$local_Filtro_IdPessoa_Processo	= $_POST['Filtro_IdPessoa_Processo'];
	$local_IdLocalCobranca 			= $_POST['IdLocalCobranca'];
	$local_IdServico	 			= $_POST['Filtro_IdServico'];
	$local_FormatoSaida 			= $_POST['FormatoSaida'];
	$local_Filtro_IdServico			= $_POST['Filtro_IdServico'];

	switch ($local_Acao){
		case 'exportar':			
			header("Location: exportar/$local_FormatoSaida/etiqueta.php?Filtro_IdContaReceber=$local_Filtro_IdContaReceber&IdProcessoFinanceiro=$local_IdProcessoFinanceiro&Filtro_IdPessoa=$local_Filtro_IdPessoa&IdLocalCobranca=$local_IdLocalCobranca&EnderecoCobranca=$local_EnderecoCobranca&FormatoSaida=$local_FormatoSaida&Filtro_IdPessoa_Processo=$local_Filtro_IdPessoa_Processo&Filtro_IdServico=$local_IdServico");
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
		<script type = 'text/javascript' src = 'js/conta_receber_default.js'></script>
		<script type = 'text/javascript' src = 'js/processo_financeiro_default.js'></script>
		<script type = 'text/javascript' src = 'js/etiqueta.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Etiqueta')">
	<div id='carregando'>carregando</div>
	<div id='conteudo'>
		<form name='formulario' method='post' action='cadastro_etiqueta.php' onSubmit='return validar()'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
			<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
			<input type='hidden' name='Local' value='Etiqueta'>
			<input type='hidden' name='Filtro_IdPessoa' value=''>
			<input type='hidden' name='Filtro_IdPessoa_Processo' value=''>
			<input type='hidden' name='Filtro_IdContaReceber' value=''>
			<input type='hidden' name='Filtro_IdServico' value=''>
			<div>
				<div id='cp_tit' style='margin-top:0'>Filtros</div>
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
									<input type='text' name='IdPessoaFiltro' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1'><input type='text' class='agrupador' name='NomeFiltro' value='' style='width:646px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add' value='Adicionar' class='botao' style='width:84px;' tabindex='2' onClick=\"busca_pessoa(document.formulario.IdPessoaFiltro.value,false,'AdicionarEtiqueta')\">
								</td>
							</tr>
						</table>";
						
					$razao="	<table id='titTabelaPessoa'style='margin:10px 0 5px 0'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B><B style='color:#000'>Razão Social</B></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 60); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();\"></td>
								<td class='campo'>
									<input type='text' name='IdPessoaFiltro' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1'><input type='text' class='agrupador' name='NomeFiltro' value='' style='width:646px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add' value='Adicionar' class='botao' style='width:84px;' tabindex='2' onClick=\"busca_pessoa(document.formulario.IdPessoaFiltro.value,false,'AdicionarEtiqueta')\">
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
			<div>
				<div id='cp_tit'>Processo Financeiro</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Processo</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Mês Referência</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Mês Vencimento</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Menor Vencimento</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Local de Cobrança</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Status</td>
					</tr>
					<tr>
						<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaProcessoFinanceiro', true, event, null, 160); limpa_form_processo_financeiro(); busca_processo_financeiro_lista(); document.formularioProcessoFinanceiro.MesReferencia.focus();"></td>
						<td class='campo'>
							<input type='text' name='IdProcessoFinanceiro' value='<?=$local_IdProcessoFinanceiro?>' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_processo_financeiro(this.value,false,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='MesReferencia' value='' style='width:90px' maxlength='7' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='MesVencimento' value='' style='width:90px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='MenorVencimento' value='' style='width:110px'readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='DescFiltro_IdLocalCobranca' value='' style='width:262px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='Status' value='' style='width:108px' readOnly>
						</td>
					</tr>
				</table>
			</div>
			<div id='cp_listar_conta_receber'>
				<div id='cp_tit'>Contas a Receber</div>
				<table style='margin:10px 0 5px 0;' id='titContaRecber'>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Contas Rec.</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Nome Pessoa/Razão Social</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Nº Doc.</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Local Cobrança</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Data Lanc.</td>
						<td class='separador'>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaContaReceber', true, event, null, 220); limpa_form_conta_receber(); busca_conta_receber_lista(); document.formularioContaReceber.Nome.focus();" id='FiltroContaReceber'></td>
						<td class='campo'>
							<input type='text' name='IdContaReceber' value=''  style='width:70px' maxlength='11' onChange='busca_conta_receber(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='NomeContaReceber' value=''  style='width:219px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='NumeroDocumento' value=''  style='width:70px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<select name='IdLocalCobrancaContaReceber' style='width:200px'  disabled>
								<option value='' selected></option>
								<?
									$sql = "select IdLocalCobranca, AbreviacaoNomeLocalCobranca from LocalCobranca where IdLoja=$local_IdLoja order by DescricaoLocalCobranca";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdLocalCobranca]'>$lin[AbreviacaoNomeLocalCobranca]</option>";
									}
								?>
							</select>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='DataLancamento' value=''  style='width:100px' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='button' name='bt_add' value='Adicionar' class='botao' style='width:84px;' tabindex='5' onClick="busca_conta_receber(document.formulario.IdContaReceber.value,false,'AdicionarEtiqueta','')">
						</td>
					</tr>
				</table>
				<table id='tabelaContaReceber' class='tableListarCad' cellspacing='0'>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' style='width:60px'>Id</td>
						<td>Nome Pessoa</td>
						<td>Nº Doc.</td>
						<td>Local Cobrança</td>
						<td>Data Lanç.</td>
						<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
						<td>Vencimento</td>
						<td>Pagamento</td>
						<td>Local Receb.</td>
						<td class='bt_lista'>&nbsp;</td>
					</tr>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' colspan='10' id='totaltabelaContaReceber'>Total: 0</td>
					</tr>
				</table>
			</div>
			<div id='cp_filtro_servico'>
				<div id='cp_tit'>Serviço</div>
				<table style='margin:10px 0 5px 0' id='titTabelaServico'>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B style='margin-right:34px; color:#000'>Serviço</b>Nome Serviço</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Tipo Serviço</td>
						<td class='separador'>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 350);"></td>
						<td class='campo'>
							<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='16'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:455px' maxlength='100' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<select name='IdTipoServico' style='width:180px' disabled>
								<option value='' selected></option>
								<?
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=71 order by ValorParametroSistema";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
									}
								?>
							</select>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='button' name='bt_add_servico' value='Adicionar' class='botao' style='width:84px;' tabindex='17' onClick="addServico()">
						</td>
					</tr>
				</table>
				<table id='tabelaServico' class='tableListarCad' cellspacing='0'>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' style='width:60px'>Serviço</td>
						<td>Nome Serviço</td>
						<td>Tipo Serviço</td>
						<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
						<td class='bt_lista'>&nbsp;</td>
					</tr>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' colspan='5' id='totaltabelaServico'>Total: 0</td>
					</tr>
				</table>
			</div>	
			<div>
				<div id='cp_tit'>Local de Cobrança</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Local de Cobrança</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<select name='IdLocalCobranca' style='width:418px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6'>
								<option value='' selected></option>
								<?
									$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobranca where IdLoja=$local_IdLoja order by DescricaoLocalCobranca";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdLocalCobranca]'>$lin[DescricaoLocalCobranca]</option>";
									}
								?>
							</select>
						</td>
					</tr>
				</table>
			</div>
			<div>
				<div id='cp_tit'>Opções</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>						
						<td class='descCampo'><B>Formato de Saída</B></td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<select name='FormatoSaida' style='width:418px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8'>
								<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 112 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
							</select>
						</td>
					</tr>
				</table>
			</div>
			<div class='cp_botao'>
				<table style='width:100%;'>
					<tr>
						<td class='campo' style='text-align:right; padding-right: 6px;'>
							<input type='button' name='bt_exportar' value='Exportar' class='botao' tabindex='9' onClick='cadastrar()'>
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
				include("files/busca/pessoa.php");
				include("files/busca/processo_financeiro.php");
				include("files/busca/conta_receber.php");
				include("files/busca/servico.php");
			?>
		</div>
	</body>	
</html>
<script>
	function status_inicial(){		
		if(document.formulario.FormatoSaida.value == ''){
			document.formulario.FormatoSaida.value = '<?=getCodigoInterno(3,72)?>';	
		}
	}
	inicia();
	enterAsTab(document.forms.formulario);
</script>
