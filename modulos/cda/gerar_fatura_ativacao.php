<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('files/funcoes.php');
	
	# Vars Cabeçalho **********************************
	$local_EtapaProxima		= 'confirmar_cadastro.php';
	$local_EtapaAnterior	= 'listar_plano_disponivel.php';
	$IdLoja					= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************
	
	$local_MSGDescricao		= getParametroSistema(95,11);
?>
<html>
	<head>
		<?
			include ("files/header.php");
		?>
		
		<script type="text/javascript" src="./js/fatura_ativacao.js"></script>	
	</head>
	<body>
		<div id='carregando'>carregando</div>
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td align="center">
					<div id="geral">
						<div id="main">
							<div id="coluna1">
								<div><img src="img/marca_conadmin2.png" width="260" height="50"></div>
								<div id="coluna1main">
									<? include("./files/indice.php"); ?>
								</div>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
										<td width="15"><img src="img/lrp1.png" width="15" height="15" /></td>
										<td class="lrp"><img src="img/_Espaco.gif" /></td>
										<td width="15"><img src="img/lrp2.png" width="15" height="15" /></td>
									</tr>
								</table>
							</div>
							<div id="coluna2">
								<table width="640" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
										<td id='tit'><h1><img src="./img/icones/7.png" /> <?=getParametroSistema(95,26)?></h1></td>
										<td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
									</tr>
								</table>
								<table width="640" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td colspan='3' class='coluna2main'>
											<form name='formulario' method='post' action='<?=$local_EtapaProxima?>' onSubmit='return validar()'>
												<input type='hidden' name='EtapaProxima' value='<?=$local_EtapaProxima?>'>
												<input type='hidden' name='DiaCobrancaDefault' value='<?=getCodigoInternoCDA(3,6)?>'>
												<input type='hidden' name='DiaCobrancaTemp' value=''>
												<input type='hidden' name='QuantParcela' value=''>
												<input type='hidden' name='MesFechadoTemp' value='<?=$_POST[MesFechadoTemp]?>'>
												<input type='hidden' name='QtdMesesFidelidadeTemp' value=''>
												<input type='hidden' name='IdLocalCobrancaTemp' value='<?=$_POST[IdLocalCobrancaTemp]?>'>
												<input type='hidden' name='TipoContratoTemp' value='<?=$_POST[TipoContratoTemp]?>'>
												<input type='hidden' name='ValorPeriodicidade' value=''>
												<input type='hidden' name='ValorPeriodicidadeTerceiro' value=''>
												<input type='hidden' name='ServicoAutomatico' value=''>
												<input type='hidden' name='DataInicio' value='<?=date("d/m/Y")?>'>
												<input type='hidden' name='DataPrimeiraCobranca' value='<?=date("d/m/Y")?>'>
												<input type='hidden' name='AssinaturaContrato' value='2'>
												<input type='hidden' name='NotaFiscalCDA' value=''>
												<input type='hidden' name='CFOPServico' value=''>
												<input type='hidden' name='IdAgenteAutorizado' value=''>
												<input type='hidden' name='IdCarteira' value=''>
												<input type='hidden' name='IdCarteiraTemp' value=''>
												<?
													$key_existente = array(
														"EtapaProxima",
														"DiaCobrancaDefault",
														"DiaCobrancaTemp",
														"QuantParcela",
														"MesFechadoTemp",
														"QtdMesesFidelidadeTemp",
														"IdLocalCobrancaTemp",
														"TipoContratoTemp",
														"ValorPeriodicidade",
														"ValorPeriodicidadeTerceiro",
														"ServicoAutomatico",
														"DataInicio",
														"DataPrimeiraCobranca",
														"AssinaturaContrato",
														"NotaFiscalCDA",
														"CFOPServico",
														"IdAgenteAutorizado",
														"IdCarteira",
														"IdCarteiraTemp",
														"ValorServico",
														"IdPeriodicidade",
														"QtdParcela",
														"TipoContrato",
														"MesFechado",
														"IdLocalCobranca",
														"DiaCobranca",
														"IdLocalCobranca" 
													);
													
													foreach($_POST as $key => $value){
														if(substr($key,0,3) != "bt_"){
															if(substr($key,0,24) == "NomeResponsavelEndereco_"){
																$key = substr($key,0,25);
															}
															
															if($key == "OcultarEnderecoCobrancaTemp"){
																$key = "OcultarEnderecoCobranca";
															}
															
															if(!in_array($key,$key_existente) && substr($key,0,6) != "Valor_"){
																echo "<input type='hidden' name='$key' value='$value'>";
															}
															
															$temp = explode("_",$key);
															
															if($temp[0] == "Valor" && $temp[2] == "Temp"){
																$var = "\$local_".$temp[0]."Parametro[".$temp[1]."]";
															} else{
																$var = "\$local_".$key;
															}
															
															eval($var." = '".$value."';");
														}
													}
													
													$sql = "select 
																Servico.IdServico,
																Servico.DescricaoServico, 
																Servico.DetalheServico,
																ServicoValor.Valor
															from 
																Servico left join (
																	select
																		ServicoValor.IdLoja,
																		ServicoValor.IdServico,
																		ServicoValor.DataInicio,
																		ServicoValor.DataTermino,
																		ServicoValor.Valor
																	from 
																		ServicoValor,
																		(
																			select
																				ServicoValor.IdLoja,
																				ServicoValor.IdServico,
																				max(DataInicio) DataInicio
																			from 
																				ServicoValor
																			where 
																				ServicoValor.IdLoja = '$local_IdLoja' and
																				ServicoValor.DataInicio <= curdate()
																			group by 
																				ServicoValor.IdServico
																		) ServicoValorTemp
																	where 
																		ServicoValor.IdLoja = ServicoValorTemp.IdLoja and 
																		ServicoValor.IdServico = ServicoValorTemp.IdServico and 
																		ServicoValor.DataInicio = ServicoValorTemp.DataInicio
																) ServicoValor on (
																	Servico.IdLoja = ServicoValor.IdLoja and 
																	Servico.IdServico = ServicoValor.IdServico
																)
															where 
																Servico.IdLoja = '$local_IdLoja' and
																Servico.IdServico = '$local_IdServico' and
																Servico.ContratoViaCDA = '1' and 
																Servico.IdTipoServico = '1' and
																Servico.IdStatus = '1';";
													$res = @mysql_query($sql,$con);
													$lin = @mysql_fetch_array($res);
													$lin[Valor] = str_replace('.', ',', $lin[Valor]);
													
													echo"
													<table style='width:600px;'>
														<tr>
															<td class='title'>Nome Serviço</td>
															<td class='sep' />
															<td class='title'>Valor (".getParametroSistema(5,1).")</td>
														</tr>
														<tr>	
															<td>$lin[DescricaoServico]&nbsp;</td>
															<td class='sep' />
															<td style='width:111px;'>
																$lin[Valor]
																<input type='hidden' name='ValorServico' value='$lin[Valor]'>
															</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'>Descrição do Serviço</td>
														</tr>
														<tr>
															<td>$lin[DetalheServico]&nbsp;</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'><B>Periodicidade</B></td>
															<td class='sep' />
															<td class='title'><B>QTD. Parcelas</B></td>
															<td class='sep' />
															<td class='title'><B>Tipo Contrato</B></td>
															<td class='sep' />
															<td class='title'><B>Vencimento</B></td>
														</tr>
														<tr>
															<td>
																<select class='FormPadrao' name='IdPeriodicidade' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" style='width:257px' tabindex='1' onChange=\"servico_periodicidade_parcelas($local_IdLoja,document.formulario.IdServico.value,this.value,'busca'); calculaPeriodicidade(this.value,document.formulario.ValorServico.value); calculaPeriodicidadeTerceiro(document.formulario.IdPeriodicidade.value,this.value,document.formulario.ValorPeriodicidadeTerceiro); calculaServicoAutomatico(this.value);\">
																	<option value='' selected>&nbsp;</option>";
													
													$sql0 = "select distinct
																ServicoPeriodicidade.IdServico,	
																ServicoPeriodicidade.IdPeriodicidade,
																Periodicidade.DescricaoPeriodicidade
															from
																Periodicidade,
																ServicoPeriodicidade,
																LocalCobranca
															where
																ServicoPeriodicidade.IdLoja = $local_IdLoja and
																ServicoPeriodicidade.IdLoja = Periodicidade.IdLoja and
																ServicoPeriodicidade.IdLoja = LocalCobranca.IdLoja and
																ServicoPeriodicidade.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
																ServicoPeriodicidade.IdPeriodicidade = Periodicidade.IdPeriodicidade and
																ServicoPeriodicidade.IdServico = $local_IdServico
															order by 
																Periodicidade.DescricaoPeriodicidade ASC;";
													$res0 = @mysql_query($sql0,$con);
													while($lin0 = @mysql_fetch_array($res0)){
														echo"<option value='$lin0[IdPeriodicidade]' ".compara($local_IdPeriodicidadeTemp,$lin0[IdPeriodicidade],"selected='selected'","").">$lin0[DescricaoPeriodicidade]</option>";
													}
													echo "		</select>
															</td>
															<td class='sep' />
															<td>
																<select class='FormPadrao' name='QtdParcela' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" style='width:104px' tabindex='2' OnChange=\"document.formulario.QuantParcela.value=document.formulario.QtdParcela.value; busca_servico_tipo_contrato(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,this.value,'busca')\">
																	<option value='' selected>&nbsp;</option>
																</select>
															</td>
															<td class='sep' />
															<td>
																<select class='FormPadrao' name='TipoContrato' style='width:104px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='3' OnChange=\"document.formulario.TipoContratoTemp.value=document.formulario.TipoContrato.value;busca_servico_local_cobranca(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,document.formulario.QtdParcela.value,this.value,'busca');\">
																	<option value=''>&nbsp;</option>
																</select>
															</td>
															<td class='sep' />
															<td>
																<select class='FormPadrao' name='DiaCobranca' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='6' style='width:104px'>
																	<option value='0'></option>";
													
													$sql0 = "select ValorCodigoInterno from (select convert(ValorCodigoInterno,UNSIGNED) ValorCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno = 1) CodigoInterno order by ValorCodigoInterno";
													$res0 = @mysql_query($sql0,$con);
													while($lin0 = @mysql_fetch_array($res0)){
														echo"<option value='$lin0[ValorCodigoInterno]'>".visualizarNumber($lin0[ValorCodigoInterno])."</option>";
													}
													
													echo"		</select>
															</td>
														</tr>
													</table>
													<table>
														<tr>
															<td class='title'><B>Local de Cobrança</B></td>
															<td class='sep' />
															<td class='title'><B>Mês Fechado</B></td>
														</tr>
														<tr>
															<td>
																<select class='FormPadrao' name='IdLocalCobranca' onFocus=\"Foco(this,'in')\"  style='width:483px' onBlur=\"Foco(this,'out')\" tabindex='5' onChange=\"document.formulario.IdLocalCobrancaTemp.value=document.formulario.IdLocalCobranca.value; busca_servico_mes_fechado(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,document.formulario.QtdParcela.value,document.formulario.TipoContrato.value,this.value,'busca');\">
																	<option value=''>&nbsp;</option>
																</select>
															</td>
															<td class='sep' />
															<td>
																<select class='FormPadrao' name='MesFechado' onFocus=\"Foco(this,'in')\"  style='width:104px' onBlur=\"Foco(this,'out')\" tabindex='4' onChange=\"document.formulario.MesFechadoTemp.value=document.formulario.MesFechado.value;\">
																	<option value=''>&nbsp;</option>
																</select>
															</td>
													</table>
													";
													
													$i = 0;
													$qtd = 0;
													$sqlPam	= " select
																	ServicoParametro.IdServico,
																	ServicoParametro.IdParametroServico,
																	ServicoParametro.DescricaoParametroServicoCDA,
																	ServicoParametro.ValorDefault,
																	ServicoParametro.Obrigatorio,
																	ServicoParametro.Obs,
																	ServicoParametro.RotinaCalculo,
																	ServicoParametro.RotinaOpcoes,
																	ServicoParametro.RotinaOpcoesContrato,
																	ServicoParametro.Calculavel,
																	ServicoParametro.RotinaOpcoes,
																	ServicoParametro.RotinaOpcoesContrato,
																	ServicoParametro.CalculavelOpcoes,
																	ServicoParametro.Editavel,
																	ServicoParametro.IdTipoParametro,
																	ServicoParametro.IdMascaraCampo,
																	ServicoParametro.IdTipoTexto,
																	ServicoParametro.ExibirSenha,
																	ServicoParametro.TamMinimo,
																	ServicoParametro.TamMaximo,
																	ServicoParametro.OpcaoValor,
																	ServicoParametro.VisivelCDA
																from 
																	Loja,
																	Servico,
																	ServicoParametro 
																where
																	Servico.IdLoja = $local_IdLoja and
																	Servico.IdServico = ServicoParametro.IdServico and
																	ServicoParametro.IdLoja = Servico.IdLoja and
																	Servico.IdLoja = Loja.IdLoja and
																	ServicoParametro.IdServico = $lin[IdServico] and
																	ServicoParametro.VisivelCDA = 1 and
																	ServicoParametro.IdStatus = 1
																order by 
																	ServicoParametro.IdParametroServico ASC";
													$resPam	= @mysql_query($sqlPam,$con);
													while($linPam = @mysql_fetch_array($resPam)){
														if(($linPam[IdTipoTexto] == 3 || $linPam[IdTipoTexto] == 4 || $linPam[IdTipoTexto] == 5) || ($linPam[IdTipoParametro] == 2)){
															$linPam[IdTipoTexto] = 1;
														}
														
														if($linPam[IdTipoTexto] == 2){
															$i++;
															
															$Parametro[IdParametroServico][$qtd]		=	$linPam[IdParametroServico];
															$Parametro[DescricaoParametroServico][$qtd]	=	$linPam[DescricaoParametroServicoCDA];
															$Parametro[ValorDefault][$qtd]				=	$linPam[ValorDefault];
															$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
															$Parametro[Obs][$qtd]						=	$linPam[Obs];
															$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
															$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
															$Parametro[IdMascaraCampo][$qtd]			=	$linPam[IdMascaraCampo];
															$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
															$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
															$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
															$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
															$Parametro[OpcaoValor][$qtd]				=	$linPam[OpcaoValor];
															
															$qtd++;	
															
															$Parametro[IdParametroServico][$qtd]		=	'10001';
															$Parametro[DescricaoParametroServico][$qtd]	=	'Confirmação '.$linPam[DescricaoParametroServico];
															$Parametro[ValorDefault][$qtd]				=	'';
															$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
															$Parametro[Obs][$qtd]						=	'';
															$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
															$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
															$Parametro[IdMascaraCampo][$qtd]			=	'';
															$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
															$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
															$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
															$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
															$Parametro[OpcaoValor][$qtd]				=	'';
															
															$qtd++;
														} else{
															$Parametro[IdParametroServico][$qtd]		=	$linPam[IdParametroServico];
															$Parametro[DescricaoParametroServico][$qtd]	=	$linPam[DescricaoParametroServicoCDA];
															$Parametro[ValorDefault][$qtd]				=	$linPam[ValorDefault];
															$Parametro[Obrigatorio][$qtd]				=	$linPam[Obrigatorio];
															$Parametro[Obs][$qtd]						=	$linPam[Obs];
															$Parametro[Editavel][$qtd]					=	$linPam[Editavel];
															$Parametro[IdTipoParametro][$qtd]			=	$linPam[IdTipoParametro];
															$Parametro[IdMascaraCampo][$qtd]			=	$linPam[IdMascaraCampo];
															$Parametro[IdTipoTexto][$qtd]				=	$linPam[IdTipoTexto];
															$Parametro[ExibirSenha][$qtd]				=	$linPam[ExibirSenha];
															$Parametro[TamMinimo][$qtd]					=	$linPam[TamMinimo];
															$Parametro[TamMaximo][$qtd]					=	$linPam[TamMaximo];
															$Parametro[OpcaoValor][$qtd]				=	$linPam[OpcaoValor];
														
															$qtd++;															
														}
													}
													
													$tab = 7;
													$bt_alterar = false;
													
													if($qtd > 0){
														echo"<BR><span style='font-size:14px;'>Parâmetros do Serviço:</span>";
														$bt_alterar = true;
													}
													
													for($i = 0; $i < $qtd; $i++){
														echo "<table border='0'>
																<tr>";
														
														if($Parametro[Obrigatorio][$i] == 1){
															$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$i]."</B>";
														} else{
															$Descricao	=	$Parametro[DescricaoParametroServico][$i];
														}
														
														echo "<td class='title'>$Descricao</td>";
														
														$tipoColuna1 = $Parametro[IdTipoTexto][$i];
														$prox1	=	$i+1;
													
														if(($Parametro[IdTipoTexto][$i] == 1 && $Parametro[IdTipoTexto][$prox1] == 1) || ($Parametro[IdTipoTexto][$i] == 2 && $Parametro[IdTipoTexto][$prox1] == 2)){
															
															if($prox1 < $qtd){
																echo"<td class='sep' />";
																
																if($Parametro[Obrigatorio][$prox1] == 1){
																	$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$prox1]."</B>";
																} else{
																	$Descricao	=	$Parametro[DescricaoParametroServico][$prox1];
																}
																
																echo "<td class='title'>$Descricao</td>";
															}
														}
														
														$tipoColuna2 = $Parametro[IdTipoTexto][$prox1];
														$prox2	=	$i+2;
														
														if(($Parametro[IdTipoTexto][$prox1] == 1 && $Parametro[IdTipoTexto][$prox2] == 1) || ($Parametro[IdTipoTexto][$prox1-1] == 2 && $Parametro[IdTipoTexto][$prox2] == 2)){															
															if($prox2 < $qtd){
																echo"<td class='sep' />";
																
																if($Parametro[Obrigatorio][$prox2] == 1){
																	$Descricao	=	"<B>".$Parametro[DescricaoParametroServico][$prox2]."</B>";
																} else{
																	$Descricao	=	$Parametro[DescricaoParametroServico][$prox2];
																}
															
																echo "<td class='title'>$Descricao</td>";
															}																
														}
														
														$tipoColuna3 = $Parametro[IdTipoTexto][$prox2];
														
														echo "</tr>";
														echo "<tr>";
														
														if($Parametro[TamMaximo][$i]!=""){
															$tamMax	=	"maxlength='".$Parametro[TamMaximo][$i]."'";
														} else{
															$tamMax	=	"";
														}
														
														if($Parametro[Editavel][$i]==1){
															$disabled	=	"";
															$tabindex	=	"tabindex='$tab'";
														} else{
															$disabled	=	"readOnly";
															$tabindex	=	"";
														}
														
														if($Parametro[IdTipoParametro][$i]==1){	
															switch($Parametro[IdTipoTexto][$i]){
																case '1':
																	switch($Parametro[IdMascaraCampo][$i]){
																		case '1':	//Data
																			echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			break;
																		case '2':	//Inteiro	
																			echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			break;
																		case '3':	//Float																				
																			if($Parametro[Editavel][$i] == 1){
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			}else{
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			}
																			break;
																		case '4':	//Usuário																				
																			echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' maxlength='17' onkeypress=\"mascara(this,event,'usuario')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			break;
																		case '5':	//MAC																				
																			echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px' maxlength='17' onkeypress=\"mascara(this,event,'mac')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
																			break;
																		default:
																			if($Parametro[IdTipoTexto][$i] == 1){
																				$tipo	=	'text';
																			}else{
																				$tipo	=	'password';
																			}																																						
																			echo"<td valign='top'>
																					<input class='FormPadrao' type='$tipo' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																					<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																					<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>
																					<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>																						
																					";																				
																	}
																	break;
																case '2':																			
																	echo"<td valign='top'>
																			<input class='FormPadrao' type='password' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' $tamMax style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																			<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																			<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>																		
																			<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";																
																	if($Parametro[IdTipoTexto][$i] ==	2)
																	   echo"<input type='hidden' name='SenhaValor_".$Parametro[IdParametroServico][$i]."' value=''>";																		
																	break;
																case '3':
																	echo"<td valign='top'>
																			<input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																			<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																			<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>
																			<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>																						
																			";
																	break;
																case '4':
																	echo"<td valign='top'>
																			<input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																			<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																			<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>
																			<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>																						
																			";
																	break;
																case '5':
																	echo"<td valign='top'>
																			<input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																			<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																			<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>
																			<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>																						
																			";
																	break;
																case '6':
																	echo"<td valign='top'>
																			<input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$i]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$i]]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																			<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'>
																			<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'>
																			<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>																						
																			";
																	break;
																
															}
															
															if($Parametro[Obs][$i]!=""){
																echo	"<BR>".$Parametro[Obs][$i];
															}
															
															echo"</td>";
														} else{
															if($disabled == "readOnly"){
																$disabled	=	"disabled";
															}
															
															echo"<td valign='top'>";	
															echo	"<select name='Valor_".$Parametro[IdParametroServico][$i]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled $tabindex>";
															echo		"<option value=''></option>";
															
															$valor	=	explode("\n",$Parametro[OpcaoValor][$i]);
															$tam = 0;
															
															for($ii=0; $ii<count($valor); $ii++){
																if($valor[$ii] != "") $tam++;
															}
															
															for($ii=0; $ii<$tam; $ii++){
																echo "<option value='".$valor[$ii]."' ".compara(trim($local_ValorParametro[$Parametro[IdParametroServico][$i]]),trim($valor[$ii]),"selected='selected'","").">".$valor[$ii]."</option>";
															}
															
															echo "</select>";
															echo	"<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[TamMinimo][$i]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$i]."' value='".$Parametro[IdTipoTexto][$i]."'>";
															
															if($Parametro[Obs][$i]!=""){
																echo	"<BR>".$Parametro[Obs][$i];
															}
															
															echo"</TD>";
														}			
														
														if($prox1 < $qtd){
															if($Parametro[TamMaximo][$prox1]!=""){
																$tamMax	=	"maxlength='".$Parametro[TamMaximo][$prox1]."'";
															} else{
																$tamMax	=	"";
															}
														
															if($Parametro[Editavel][$prox1]==1){
																$tab++;
																$disabled	=	"";
																$tabindex	=	"tabindex='$tab'";
															} else{
																$disabled	=	"readOnly";
																$tabindex	=	"";
															}
															
															echo"<td class='sep' />";
															
															if($Parametro[IdTipoParametro][$prox1]==1){	
																switch($Parametro[IdTipoTexto][$prox1]){
																	case '1':
																		switch($Parametro[IdMascaraCampo][$prox1]){
																			case '1':	//Data
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				break;
																			case '2':	//Inteiro
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				break;
																			case '3':	//Float
																				if($Parametro[Editavel][$prox1] == 1){
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				}else{
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				}
																				break;
																			case '4':	//Usuário
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlength='17' onkeypress=\"mascara(this,event,'usuario')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				break;
																			case '5':	//MAC
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlength='17' onkeypress=\"mascara(this,event,'mac')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																				break;
																			default:	
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																		}
																		break;
																	case '2':
																		if($Parametro[IdTipoTexto][$i] == 2 && $Parametro[IdTipoTexto][$prox1] == 2){
																			echo"<td valign='top'>
																					<input class='FormPadrao' type='password' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' $tamMax style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex>
																					<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$i]."'>
																					<input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'>
																					<input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";																				
																		}
																		break;
																	case '3':
																		echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																		break;
																	case '4':
																		echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																		break;
																	case '5':
																		echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																		break;
																	case '6':
																		echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox1]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox1]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																		break;
																}
																
																if($Parametro[Obs][$prox1]!=""){
																	echo	"<BR>".$Parametro[Obs][$prox1];
																}
																
																echo"</td>";
															} else{
																if($disabled == "readOnly"){
																	$disabled	=	"disabled";
																}
																
																echo"<td valign='top'>";	
																echo	"<select name='Valor_".$Parametro[IdParametroServico][$prox1]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled $tabindex>";
																echo		"<option value=''></option>";
																
																$valor = explode("\n",$Parametro[OpcaoValor][$prox1]);
																$tam = 0;
																
																for($ii=0; $ii<count($valor); $ii++){
																	if($valor[$ii] != "") $tam++;
																}
																
																for($ii=0; $ii<$tam; $ii++){
																	echo "<option value='".$valor[$ii]."' ".compara(trim($local_ValorParametro[$Parametro[IdParametroServico][$prox1]]),trim($valor[$ii]),"selected='selected'","").">".$valor[$ii]."</option>";
																}
																
																echo "</select>";
																echo	"<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[Obrigatorio][$prox1]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[TamMinimo][$prox1]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox1]."' value='".$Parametro[IdTipoTexto][$prox1]."'>";
																
																if($Parametro[Obs][$prox1]!=""){
																	echo	"<BR>".$Parametro[Obs][$prox1];
																}
																
																echo"</td>";
															}
														}
														
														if($prox2 < $qtd){
															if($Parametro[TamMaximo][$prox2]!=""){
																$tamMax	=	"maxlength='".$Parametro[TamMaximo][$prox2]."'";
															} else{
																$tamMax	=	"";
															}
															
															if($Parametro[Editavel][$prox2]==1){
																$tab++;
																$disabled	=	"";
																$tabindex	=	"tabindex='$tab'";
															} else{
																$disabled	=	"readOnly";
																$tabindex	=	"";
															}
															
															echo"<td class='sep' />";
															
															if($Parametro[IdTipoParametro][$prox2]==1){	
																switch($Parametro[IdTipoTexto][$prox2]){
																	case '1':
																		switch($Parametro[IdMascaraCampo][$prox2]){
																			case '1':	//Data
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				break;
																			case '2':	//Inteiro
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				break;
																			case '3':	//Float
																				if($Parametro[Editavel][$prox2] == 1){
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' $tamMax onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				}else{
																					echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				}
																				break;
																			case '4':	//Usuário
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlength='17' onkeypress=\"mascara(this,event,'usuario')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				break;
																			case '5':	//MAC
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlength='17' onkeypress=\"mascara(this,event,'mac')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																				break;
																			default:	
																				echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px'  $tamMax onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																		}
																		break;
																	case '2':
																		if($Parametro[IdTipoTexto][$prox1-1] == 2 && $tipoColuna2 == 2 && $Parametro[IdTipoTexto][$prox2] == 2){
																			echo"<td valign='top'><input class='FormPadrao' type='password' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' $tamMax style='width:193px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$i]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																		}
																		break;
																	case '3':
																		echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																		break;
																	case '4':
																		echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																		break;
																	case '5':
																		echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																		break;
																	case '6':
																		echo"<td valign='top'><input class='FormPadrao' type='text' name='Valor_".$Parametro[IdParametroServico][$prox2]."' value='".$local_ValorParametro[$Parametro[IdParametroServico][$prox2]]."' style='width:193px' maxlength='10' onkeypress=\"mascara(this,event,'date')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" $disabled $tabindex><input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																		break;
																}
																
																if($Parametro[IdTipoTexto][$prox1] == 1 &&  $tipoColuna1 == 1){
																	if($Parametro[Obs][$prox2]!=""){
																		echo	"<BR>".$Parametro[Obs][$prox2];
																	}
																}
																
																echo"</td>";
															} else{
																if($disabled == "readOnly"){
																	$disabled	=	"disabled";
																}
																
																echo"<td valign='top'>";	
																echo	"<select name='Valor_".$Parametro[IdParametroServico][$prox2]."'  style='width:193px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" $disabled $tabindex>";
																echo		"<option value=''></option>";
																
																$valor = explode("\n",$Parametro[OpcaoValor][$prox2]);
																$tam = 0;
																
																for($ii=0; $ii<count($valor); $ii++){
																	if($valor[$ii] != "") $tam++;
																}
																
																for($ii=0; $ii<$tam; $ii++){
																	echo "<option value='".$valor[$ii]."' ".compara(trim($local_ValorParametro[$Parametro[IdParametroServico][$prox2]]),trim($valor[$ii]),"selected='selected'","").">".$valor[$ii]."</option>";
																}
																
																echo "</select>";
																echo	"<input type='hidden' name='Obrigatorio_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[Obrigatorio][$prox2]."'><input type='hidden' name='TamMinimo_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[TamMinimo][$prox2]."'><input type='hidden' name='IdTipoTexto_".$Parametro[IdParametroServico][$prox2]."' value='".$Parametro[IdTipoTexto][$prox2]."'>";
																
																if($Parametro[Obs][$prox2]!=""){
																	echo	"<BR>".$Parametro[Obs][$prox2];
																}
																
																echo"</td>";
															}
														}
														
														echo"	</tr>";	
														echo "</table>";
														
														if($tipoColuna1 == 1 && $tipoColuna2 == 1 && ($tipoColuna3 == 2 || $tipoColuna3 == 0)){
															$i +=1;
														}
														
														if($tipoColuna1 == 1 && $tipoColuna2 == 1 && ($tipoColuna3 == 1 || $tipoColuna3 == 0)){
															$i +=2;
														}
														
														if($tipoColuna1 == 2 && $tipoColuna2 == 2 && ($tipoColuna3 == 2 || $tipoColuna3 == 0)){
															$i +=2;
														}
														
														$tipoColuna3 = 0;
													}
												?>
												<table style="width:100%;">
													<tr>
														<td><input type="button" class="BotaoPadrao" value="Voltar" tabindex='104' onClick="voltar_etapa('<?=$local_EtapaAnterior?>')"/></td>
														<td style='text-align:right;'><input name="bt_submit" type="submit" class="BotaoPadrao" value="Próxima Etapa" tabindex='1000'/>
														</td>
													</tr>
												</table>
											</form>
										</td>
									</tr>
									<tr>
										<td colspan='3'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</td>
			</tr>
		  	<tr>
		    	<td height="20" align="center">
					<div id='rodape'>
					<?
						if(file_exists('personalizacao/rodape.php')){
							include("personalizacao/rodape.php");
						}else{
							include("files/rodape.php");
						}
					?>
					</div>
				</td>
		  	</tr>
		</table>
	</body>
</html>
<script type="text/javaScript">
<!--
	function voltar_etapa(url){
		document.formulario.action = url;
		document.formulario.submit();
	}	
	<?
		if($local_DiaCobrancaTemp != ''){
			echo "document.formulario.DiaCobrancaDefault.value = '$local_DiaCobrancaTemp';";
		}
	?>
	servico_periodicidade_parcelas_visualiza('<?=$local_IdLoja?>',document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,document.formulario.QtdParcelaTemp.value,document.formulario.TipoContratoTemp.value,document.formulario.IdLocalCobrancaTemp.value,document.formulario.MesFechadoTemp.value,'busca');
	calculaPeriodicidade(document.formulario.IdPeriodicidade.value,document.formulario.ValorServico.value);
	calculaPeriodicidadeTerceiro(document.formulario.IdPeriodicidade.value,document.formulario.IdPeriodicidade.value,document.formulario.ValorPeriodicidadeTerceiro);
	calculaServicoAutomatico(document.formulario.IdPeriodicidade.value);
	document.formulario.QuantParcela.value = document.formulario.QtdParcelaTemp.value; 
	busca_servico_tipo_contrato(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,document.formulario.QtdParcelaTemp.value,document.formulario.TipoContratoTemp.value,document.formulario.IdLocalCobrancaTemp.value,document.formulario.MesFechadoTemp.value,'busca');
	busca_dia_cobranca(document.formulario.DiaCobrancaDefault.value);
	enterAsTab(document.forms.formulario);
	-->
</script>