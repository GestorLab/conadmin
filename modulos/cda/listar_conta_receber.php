<?	
	# Vars Cabeçalho **********************************
	
	$local_EtapaProxima				= "";
	$local_EtapaAnterior			= "menu.php";
	# Fim Vars Cabeçalho *******************************
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');	
	include('files/funcoes.php');	
	include('rotinas/verifica.php');
	
	$local_DescricaoEtapa	= getParametroSistema(23,17);
	
	$local_IdLoja	= $_SESSION["IdLoja"];
	$local_IdPessoa	= $_SESSION["IdPessoa"];
	$local_IdStatus	= $_POST["filtro_status"];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<?
			include ("files/header.php");
		?>
	</head>
	<body>
		<div id='container'>
			<?
				include ("files/cabecalho.php");
			?>
			<div id='conteudo'>
				<table style='width:760px'>
					<tr>
						<td><p class='titulo'><B><?=formTexto(getParametroSistema(84,18))?></B></p></td>
						<td style='text-align:right'></td>
					</tr>
				</table>
					<fieldset>
						<legend>Lançamentos Financeiros</legend>	
						<div id='filtroBuscar'>
							<form name='filtro' method='post' action='listar_conta_receber.php'>
								<table style='float:right; margin-right:4px'>
									<tr>
										<td>Status</td>
									</tr>
									<tr>
										<td>
											<select name='filtro_status' style='width:180px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="document.filtro.submit()">
												<option value=''>Todos</option>
												<?
													$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 and IdParametroSistema >= 1 and  IdParametroSistema <= 2 order by ValorParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo "<option value='$lin[IdParametroSistema]' ".compara($local_IdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
									</tr>
								</table>
							</form>
						</div>
						<form action='<?=$local_EtapaProxima?>' name='formulario' method='post'>
							<table class='Quadro' style='width:714px; margin-bottom:15px;' id='tabelaProduto' cellspacing=0>
								<tr>
									<th class='tabelaQuadro'>Código</th>
									<th class='tabelaQuadro'>Nº Doc.</th>
									<th class='tabelaQuadro'>Data Lanç.</th>
									<th class='tabelaQuadro'>Valor (<?=getParametroSistema(5,1)?>)</th>
									<th class='tabelaQuadro'>Data Venc.</th>
									<th class='tabelaQuadro'>Valor Receb. (<?=getParametroSistema(5,1)?>)</th>
									<th class='tabelaQuadro'>Data Pag.</th>
									<th class='tabelaQuadro'>&nbsp;</th>
								</tr>
								<?
									$filtro_sql	=	"";
									
									if($local_IdStatus!=""){
										$filtro_sql  .= " and ContaReceber.IdStatus = ".$local_IdStatus;
									}
									
									$i = 0;
									$Tvalor		=	0;
									$Trebedido	=	0;
									$sql	=	"select
													ContaReceber.IdLoja,
													ContaReceber.IdContaReceber,
													ContaReceber.NumeroDocumento,
													ContaReceber.NumeroNF,
													ContaReceber.DataLancamento,
													(ContaReceber.ValorLancamento + ContaReceber.ValorDespesas) Valor,
													ContaReceber.ValorDesconto,
													ContaReceber.DataVencimento,
													ContaReceber.IdLocalCobranca,
													LocalCobranca.AbreviacaoNomeLocalCobranca,
													LancamentoFinanceiro.IdProcessoFinanceiro,
													ContaReceberRecebimento.DataRecebimento,
													ContaReceberRecebimento.IdRecibo,
													ContaReceberRecebimento.ValorRecebido,
													ContaReceber.IdStatus,
													LocalCobranca.IdLocalCobrancaLayout
												from
													(select LancamentoFinanceiro.IdLoja, LancamentoFinanceiro.IdLancamentoFinanceiro, LancamentoFinanceiro.IdContrato, LancamentoFinanceiro.IdProcessoFinanceiro, ContaEventual.IdContaEventual, Contrato.DescricaoServico, LancamentoFinanceiro.IdOrdemServico, if(LancamentoFinanceiro.IdContrato != '',Contrato.IdPessoa,if(LancamentoFinanceiro.IdContaEventual != '',ContaEventual.IdPessoa,if(LancamentoFinanceiro.IdOrdemServico != '',OrdemServico.IdPessoa,'??'))) IdPessoa from LancamentoFinanceiro left join (select Contrato.IdLoja, Contrato.IdContrato, Contrato.IdPessoa, Servico.DescricaoServico from Contrato, Servico where Contrato.IdLoja = Servico.IdLoja and Contrato.IdServico = Servico.IdServico) Contrato on (LancamentoFinanceiro.IdLoja = Contrato.IdLoja and LancamentoFinanceiro.IdContrato = Contrato.IdContrato) left join (select IdLoja, IdContaEventual, IdPessoa from ContaEventual) ContaEventual on (LancamentoFinanceiro.IdLoja = ContaEventual.IdLoja and LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual) left join (select IdLoja, IdOrdemServico, IdPessoa from OrdemServico) OrdemServico on (LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico), Pessoa where (Contrato.IdPessoa = Pessoa.IdPessoa or ContaEventual.IdPessoa = Pessoa.IdPessoa or OrdemServico.IdPessoa = Pessoa.IdPessoa)) LancamentoFinanceiro,
													LancamentoFinanceiroContaReceber,
													ContaReceber LEFT JOIN ContaReceberRecebimento On (ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja and ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber and ContaReceberRecebimento.IdStatus != 0),
													LocalCobranca
												where
													LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
													LancamentoFinanceiro.IdLoja = ContaReceber.IdLoja and
													LancamentoFinanceiro.IdLoja = LocalCobranca.IdLoja and
													LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
													LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and
													ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
													(ContaReceber.IdStatus != 0 or ContaReceber.IdStatus = 2) and
													LancamentoFinanceiro.IdPessoa = $local_IdPessoa $filtro_sql
												group by
													ContaReceber.IdContaReceber";
									$res	=	@mysql_query($sql,$con);
									if(@mysql_num_rows($res) >=1){
										while($lin	=	@mysql_fetch_array($res)){
											if(($i%2) != 0){
												$color	=	"background-color:#EEEEEE";
											}else{
												$color	=	"";
											}
											if($lin[Valor] == "")			$lin[Valor] 		= 0;
											if($lin[ValorRecebido] == "")	$lin[ValorRecebido] = 0;
											
											$Tvalor		+=	$lin[Valor];
											$Trebedido	+=	$lin[ValorRecebido];
											
											switch($lin[IdStatus]){
												case '1':
													$lin[Link]		= "../administrativo/local_cobranca/$lin[IdLocalCobrancaLayout]/index.php";
													if(file_exists($lin[Link])){
														$lin[Link]		= "../administrativo/local_cobranca/$lin[IdLocalCobrancaLayout]/index.php?IdLoja=$lin[IdLoja]&IdContaReceber=$lin[IdContaReceber]";
														$ini			= "<a href='$lin[Link]'	target='_blank'>";
														$fim			= "</a>";
														$img			= "img/ico_imprimir.gif";
													}else{	
														$ini			= "";
														$fim			= "";
														$img			= "img/ico_imprimir.gif";	
													}
													break;
												case '2':
													$ini			= "";
													$fim			= "";
													$img			= "img/ico_pago.gif";		
													break;
											}
											
											if($lin[Valor]!=0){
												$Valor	=	formatNumber(formata_double(($lin[Valor])));
											}else{
												$Valor	=	"&nbsp;";
											}
											
											if($lin[ValorRecebido]!=0){
												$ValorRecebido	=	formatNumber(formata_double(($lin[ValorRecebido])));
											}else{
												$ValorRecebido	=	"&nbsp;";
											}
											
											echo"
											<tr>
												<TD style='$color'>$lin[IdContaReceber]</TD>
												<TD style='$color'>$lin[NumeroDocumento]</TD>
												<TD style='$color; text-align:center'>".dataConv($lin[DataLancamento],'Y-m-d','d/m/Y')."</TD>
												<TD style='$color; text-align:right'>$Valor</TD>
												<TD style='$color; text-align:center'>".dataConv($lin[DataVencimento],'Y-m-d','d/m/Y')."</TD>
												<TD style='$color; text-align:right'>$ValorRecebido</TD>
												<TD style='$color; text-align:center'>".dataConv($lin[DataRecebimento],'Y-m-d','d/m/Y')."&nbsp;</TD>
												<TD style='$color; text-align:center; width:34px'>$ini<img src='$img'>$fim</TD>
											</tr>
											";
											
											$i++;
										}
									}else{
										echo"<tr>
												<TD>&nbsp;</TD>
												<TD>&nbsp;</TD>
												<TD>&nbsp;</TD>
												<TD>&nbsp;</TD>
												<TD>&nbsp;</TD>
												<TD>&nbsp;</TD>
												<TD>&nbsp;</TD>
												<TD>&nbsp;</TD>
										</tr>";
									}
									echo"
										<tr>
											<th class='tabelaQuadro' colspan='3' style='text-align:left'>Total: $i</th>
											<TH class='tabelaQuadro' style='text-align:right'>".formatNumber(formata_double($Tvalor))."</TH>
											<TH class='tabelaQuadro'>&nbsp;</TH>
											<TH class='tabelaQuadro' style='text-align:right'>".formatNumber(formata_double($Trebedido))."</TH>
											<TH class='tabelaQuadro' colspan='2'>&nbsp;</TH>
										</tr>
									";
									
								?>
							</table>
					</fieldset>
					<table id='botoes' style='background-color:<?=getParametroSistema(84,11)?>'>
						<tr>
							<td class='voltar' style='width:50%;'>
								<?
									if($local_EtapaAnterior != ""){
										echo "<input type='image' src='img/ico_voltar_text.gif' name='Voltar' onClick=\"mudaAction('$local_EtapaAnterior',true)\" />";
									}
								?>
							</td>
							<td class='proximo'>&nbsp;</td>
						</tr>
					</table>				
				</form>
			</div>
			<?
				include("files/rodape.php");
			?>
		</div>	
	</body>
</html>
