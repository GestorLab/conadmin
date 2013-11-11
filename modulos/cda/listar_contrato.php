<?	
	# Vars Cabeçalho **********************************
	
	$local_EtapaProxima				= "";
	$local_EtapaAnterior			= "menu.php";
	# Fim Vars Cabeçalho *******************************
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');	
	include('files/funcoes.php');	
	include('rotinas/verifica.php');
	
	$local_DescricaoEtapa	= getParametroSistema(84,19);
	
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
						<td><p class='titulo'><B><?=formTexto(getParametroSistema(84,20))?></B></p></td>
						<td style='text-align:right'></td>
					</tr>
				</table>
				<fieldset>
					<legend>Contratos</legend>	
					<form action='<?=$local_EtapaProxima?>' name='formulario' method='post'>
						<table class='Quadro' style='width:714px; margin-bottom:15px;' id='tabelaProduto' cellspacing=0>
							<tr>
								<th class='tabelaQuadro'>Código</th>
								<th class='tabelaQuadro'>Serviço</th>
								<th class='tabelaQuadro'>Início</th>
								<th class='tabelaQuadro'>Término</th>
								<th class='tabelaQuadro'>Valor (<?=getParametroSistema(5,1)?>)</th>
								<th class='tabelaQuadro'>Desc. (<?=getParametroSistema(5,1)?>)</th>
								<th class='tabelaQuadro'>Valor Final (<?=getParametroSistema(5,1)?>)</th>
								<th class='tabelaQuadro'>Status</th>
							</tr>
							<?
								$i = 0;
								$Tvalor		=	0;
								$Tdesconto	=	0;
								$Ttotal		=	0;
								$sql	=	"select
												Contrato.IdLoja,
												Contrato.IdContrato,
											    Contrato.IdServico,
											    substring(DescricaoServico,1,30) DescricaoServico,
											    Contrato.IdPessoa,				    
												Contrato.DataInicio,
											    Contrato.DataTermino,
											    LocalCobranca.AbreviacaoNomeLocalCobranca,
											    (ContratoVigenciaAtiva.Valor - ContratoVigenciaAtiva.ValorDesconto) ValorFinal,
											    ContratoVigenciaAtiva.Valor,
												ContratoVigenciaAtiva.ValorDesconto,
											    Contrato.TipoContrato,
											    Contrato.IdStatus,
											    Contrato.VarStatus
											from
											    Contrato 
												left join (
													select 
														ContratoVigenciaAtiva.IdContrato, 
														ContratoVigenciaAtiva.DataInicio, 
														ContratoVigenciaAtiva.DataTermino, 
														ContratoVigenciaAtiva.Valor, 
														ContratoVigenciaAtiva.ValorDesconto 
													from 
														Loja, 
														ContratoVigenciaAtiva, 
														Contrato 
													where 
														ContratoVigenciaAtiva.IdLoja = Loja.IdLoja and 
														Contrato.IdLoja = Loja.IdLoja and 
														ContratoVigenciaAtiva.IdContrato = Contrato.IdContrato) 
												ContratoVigenciaAtiva on Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato,
											    Servico,
											    LocalCobranca 
											where
											    Contrato.IdLoja = Servico.IdLoja and
											    Contrato.IdLoja = LocalCobranca.IdLoja and
											    Contrato.IdServico = Servico.IdServico and
											    Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
											    Contrato.IdStatus != 1 and
												Contrato.IdPessoa = $local_IdPessoa 
											group by
												Contrato.IdContrato";
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
										$Tdesconto	+=	$lin[ValorDesconto];
										$Ttotal		+=	$lin[ValorFinal];
										
										if($lin[Valor]!=0){
											$Valor	=	formatNumber(formata_double(($lin[Valor])));
										}else{
											$Valor	=	"&nbsp;";
										}
										
										if($lin[ValorDesconto]!=""){
											$ValorDesconto	=	formatNumber(formata_double(($lin[ValorDesconto])));
										}
										
										if($lin[ValorFinal]!=""){
											$ValorFinal	=	formatNumber(formata_double(($lin[ValorFinal])));
										}
										
										$sql2 = "select substr(ValorParametroSistema,1,3) ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema=$lin[TipoContrato]";
										$res2 = @mysql_query($sql2,$con);
										$lin2 = @mysql_fetch_array($res2);
										
										$sql3 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
										$res3 = @mysql_query($sql3,$con);
										$lin3 = @mysql_fetch_array($res3);
										
										if($lin[VarStatus] != ''){
											switch($lin[IdStatus]){
												case '201':
													$lin3[Status]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin3[Status]);	
													break;
											}					
										}
										
										if($lin[DataTermino]!= ""){
											$lin[DataTermino]	=	dataConv($lin[DataTermino],'Y-m-d','d/m/Y');
										}else{
											$lin[DataTermino]	=	"&nbsp;";
										}
										
										echo"
										<tr>
											<TD style='$color; height:32px'>$lin[IdContrato]</TD>
											<TD style='$color;'>".sem_quebra_string($lin[DescricaoServico])."</TD>
											<TD style='$color; text-align:center'>".dataConv($lin[DataInicio],'Y-m-d','d/m/Y')."</TD>
											<TD style='$color; text-align:center'>$lin[DataTermino]</TD>
											<TD style='$color; text-align:right'>$Valor</TD>
											<TD style='$color; text-align:right'>$ValorDesconto</TD>
											<TD style='$color; text-align:right'>$ValorFinal</TD>
											<TD style='$color; text-align:center'>$lin3[Status]</TD>
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
									<th class='tabelaQuadro' colspan='4' style='text-align:left'>Total: $i</th>
									<TH class='tabelaQuadro' style='text-align:right'>".formatNumber(formata_double($Tvalor))."</TH>
									<TH class='tabelaQuadro' style='text-align:right'>".formatNumber(formata_double($Tdesconto))."</TH>
									<TH class='tabelaQuadro' style='text-align:right'>".formatNumber(formata_double($Ttotal))."</TH>
									<TH class='tabelaQuadro'>&nbsp;</TH>
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
			<?
				include("files/rodape.php");
			?>
			</div>
		</div>	
	</body>
</html>
