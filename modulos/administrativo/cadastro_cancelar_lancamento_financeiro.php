<?
	$localModulo		=	1;
	
	if($_GET['IdContaEventual']!=''){		
		$localOperacao	=	31;
	}	
	if($_GET['IdLancamentoFinanceiro']!=''){		
		$localOperacao	=	18;
	}	
	if($_GET['IdOrdemServico']!=''){
		$localOperacao  = 	26;
	}		
	$localSuboperacao	=	"C";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	$where				= "";
	
	$local_IdContaEventual			= $_POST['IdContaEventual'];
	$local_IdLancamentoFinanceiro	= $_POST['IdLancamentoFinanceiro'];
	$local_ObsLancamentoFinanceiro	= $_POST['ObsLancamentoFinanceiro'];
	$local_IdOrdemServico			= $_POST['IdOrdemServico'];
	

	if($_GET['IdContaEventual']!=''){
		$local_IdContaEventual	= $_GET['IdContaEventual'];			
	}	
	if($_GET['IdLancamentoFinanceiro']!=''){
		$local_IdLancamentoFinanceiro	= $_GET['IdLancamentoFinanceiro'];			
	}	
	if($_GET['IdOrdemServico']!=''){
		$local_IdOrdemServico	= $_GET['IdOrdemServico'];			
	}		
	
	if($local_IdContaEventual!=''){
		$where	.=	"and Demonstrativo.Codigo = $local_IdContaEventual and Tipo = 'EV'"; 
	}
	if($local_IdLancamentoFinanceiro!=''){
		$where	.=	"and Demonstrativo.IdLancamentoFinanceiro = $local_IdLancamentoFinanceiro"; 
	}
	if($local_IdOrdemServico!=''){
		$where	.=	"and LancamentoFinanceiro.IdOrdemServico = $local_IdOrdemServico"; 
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
		<script type = 'text/javascript' src = 'js/cancelar_lancamento_financeiro.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Cancelar Lançamentos Financeiro')">
		<? include('filtro_lancamento_financeiro.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='rotinas/cancelar_lancamento_financeiro.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Local' value='ContaEventual'>
				<input type='hidden' name='IdContaEventual' value='<?=$local_IdContaEventual?>'>
				<input type='hidden' name='Lancamentos' value='<?=$local_Lancamentos?>'>
				<input type='hidden' name='CancelarOrdemServico' value='<?=getCodigoInterno(60,1)?>'>
				<input type='hidden' name='IdOrdemServico' value='<?=$local_IdOrdemServico?>'>
				<div>
					<div id='cp_tit' style='margin:0'>Lançamentos Financeiros</div>
					<table id='tabelaVencimento' class='tableListarCad' cellspacing='0' cellpading='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><input style='border:0' type='checkbox' name='todos' onClick='selecionar(this)'></td>
							<td>Id</td>
							<td>Tipo</td>
							<td>Código</td>
							<td>Nome Pessoa</td>
							<td>Descrição</td>
							<td>Proc. Financ.</td>
							<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
							<td>Status</td>
							<td>Recebido</td>
							<td>&nbsp;</td>
						</tr>
						<?
							$i		=	0;	
							$total	=	0;
							
							$sql	=	"select
									      Demonstrativo.IdLoja,
										  Demonstrativo.IdContaReceber,
									      Demonstrativo.IdLancamentoFinanceiro,
									      Demonstrativo.IdProcessoFinanceiro,
									      Demonstrativo.IdPessoa,
									      substr(Pessoa.Nome,1,20) Nome,
									      substr(Pessoa.RazaoSocial,1,20) RazaoSocial,
									      Tipo,
									      Codigo,
									      Descricao,
									      Referencia,
									      Demonstrativo.Valor,
									      ValorDespesas,
									      Demonstrativo.IdStatus
									from
									     Demonstrativo,
									     Pessoa,
									     LancamentoFinanceiro
									where
									     Demonstrativo.IdLoja = $local_IdLoja and
									     Demonstrativo.IdLoja = LancamentoFinanceiro.IdLoja and
										 Demonstrativo.IdPessoa = Pessoa.IdPessoa and
										 Demonstrativo.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro $where
									order by
									     Demonstrativo.IdPessoa,Tipo,Codigo,IdLancamentoFinanceiro";
							$res	=	@mysql_query($sql,$con);
							while($lin	=	@mysql_fetch_array($res)){
								$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
								
								$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=51 and IdParametroSistema=$lin[IdStatus]";
								$res3 = @mysql_query($sql3,$con);
								$lin3 = @mysql_fetch_array($res3);
								
								$sql4	=	"select 
												ContaReceberRecebimento.IdContaReceber,
												ContaReceberRecebimento.IdStatus
											from 
												ContaReceberRecebimento,
												LancamentoFinanceiroContaReceber 
											where 
												ContaReceberRecebimento.IdLoja = $local_IdLoja and
												ContaReceberRecebimento.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
												ContaReceberRecebimento.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
												ContaReceberRecebimento.IdStatus = 1 and
												ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and
												LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = $lin[IdLancamentoFinanceiro]";
								$res4 = @mysql_query($sql4,$con);
								$lin4 = @mysql_fetch_array($res4);
								
								$color	=	"";
								
								if($i%2 != 0){
									$color = "style='background-color:#E2E7ED'";
								}
								
								$total	+=	$lin[Valor];
								
								switch($lin[Tipo]){
									case 'EV':
										$link	=	"cadastro_conta_eventual.php?IdContaEventual=$lin[Codigo]";
										break;
									case 'CO':
										$link	=	"cadastro_contrato.php?IdContrato=$lin[Codigo]";
										break;
									case 'OS':
										$link	=	"cadastro_ordem_servico.php?IdOrdemServico=$lin[Codigo]";
										break;
									default:
										$link	=	"cadastro_contrato.php?IdContrato=$lin[Codigo]";
										break;
								}
								
								
								if($lin[IdStatus] == 2 || $lin[IdStatus] == 3){
									$disabled	=	"";
								}else{
									$disabled	=	"disabled";
								}
								
								$lin[Valor]	=	formata_double($lin[Valor]);
								$lin[Valor]	=	str_replace('.',',',$lin[Valor]);
								
								echo"
									<tr>
										<td $color class='tableListarEspaco'><input style='border:0' type='checkbox' name='IdLancamentoFinanceiro_$lin[IdLancamentoFinanceiro]' value='$lin[IdLancamentoFinanceiro]' $disabled onClick='checarTodos(this)'></td>
										<td $color><a href='cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=$lin[IdLancamentoFinanceiro]'>$lin[IdLancamentoFinanceiro]</a></td>
										<td $color><a href='$link'>$lin[Tipo]</a></td>
										<td $color><a href='$link'>$lin[Codigo]</a></td>
										<td $color><a href='cadastro_pessoa.php?IdPessoa=$lin[IdPessoa]'>$lin[Nome]</a></td>
										<td $color>$lin[Descricao]</td>
										<td $color><a href='cadastro_processo_financeiro.php?IdProcessoFinanceiro=$lin[IdProcessoFinanceiro]'>$lin[IdProcessoFinanceiro]</a></td>
										<td $color class='valor'>$lin[Valor]</td>
										<td $color>$lin3[ValorParametroSistema]</td>";
										
								if($lin4[IdContaReceber] != ''){
									echo"<td $color>Sim</td>";
								}else{
									echo"<td $color>&nbsp;</td>";
								}		
										
								echo"</tr>";	
								
								$i++;
							}
							
							if($i == 1){
								$local_IdContaEventual	=	$lin[IdContaEventual];
							}
							
							$total	=	formata_double($total);
							
							echo"
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' id='totalLancFinanceiro' colspan='7'>Total: $i</td>
									<td id='totalValorTotal' class='valor'>".str_replace('.',',',$total)."</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>		
							";
						?>
					</table>
					<BR>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Observações do Cancelamento</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ObsLancamentoFinanceiro' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='4'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<BR>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_cancelar' value='Confirmar Cancelamento' class='botao' tabindex='5' onClick='cadastrar()'>
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
	addParmUrl("marLancamentoFinanceiro","IdContaEventual",'<?=$local_IdContaEventual?>');
	addParmUrl("marContaEventual","IdContaEventual",'<?=$local_IdContaEventual?>');
	addParmUrl("marContaEventual","Lancamentos",'<?=$local_Lancamentos?>');
	addParmUrl("marLancamentoFinanceiro","IdLancamentoFinanceiro",'<?=$local_IdLancamentoFinanceiro?>');
</script>