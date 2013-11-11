								<?
									$local_IdLoja				=	$_SESSION['IdLojaCDA'];
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									$local_Erro					=	$_GET['Erro'];
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
									
									$sqlFormaEmail = "SELECT
												Cob_FormaEmail
											 FROM
												Pessoa
											 WHERE
											  IdPessoa = $local_IdPessoa";
											  
									$resFormaEmail = mysql_query($sqlFormaEmail,$con);
									$linFormaEmail = mysql_fetch_array($resFormaEmail);
									if($linFormaEmail[Cob_FormaEmail] == 'S'){
										$DescEmail = "<b>E-mail</b>";
									}else{
										$DescEmail = "E-mail";
									}									
								?>
								<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							      		<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
									    <td id="tit" width="387"><h1><img src="img/icones/<?=$local_IdParametroSistema?>.png" /> <?=$local_Descricao?></h1></td>
									    <td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="?ctt=index.php">Página Inicial</a></td>
									    <td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
							    	</tr>
							    </table>
							    <table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							    		<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
							    			<form name='formulario' method='post' action='files/inserir/inserir_pessoa_solicitacao.php'>
												<input type='hidden' name='Telefone_Obrigatorio' value='<?=getCodigoInternoCDA(11,3)?>'>
												<input type='hidden' name='Endereco_Length' value='<?=getCodigoInternoCDA(7,11)?>'>
												<input type='hidden' name='Numero_Obrigatorio' value='<?=getCodigoInternoCDA(11,7)?>'>
												<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
												<input type='hidden' name='IdParametroSistema' value='<?=$local_IdParametroSistema?>'>
												<input type='hidden' name='DataNascimento_Obrigatorio' value='<?=getCodigoInternoCDA(11,6)?>'>
												<input type='hidden' name='Sexo_Obrigatorio' value='<?=getCodigoInternoCDA(11,11)?>'>
												<input type='hidden' name='EstadoCivil_Obrigatorio' value='<?=getCodigoInternoCDA(11,12)?>'>
												<input type='hidden' name='Rg_Obrigatorio' value='<?=getCodigoInternoCDA(11,16)?>'>
												<input type='hidden' name='OrgaoExperdidor_Obrigatorio' value='<?=getCodigoInternoCDA(11,6)?>'>
												<input type='hidden' name='NomeMae_Obrigatorio' value='<?=getCodigoInternoCDA(11,15)?>'>
												<input type='hidden' name='NomePai_Obrigatorio' value='<?=getCodigoInternoCDA(11,14)?>'>
												<input type='hidden' name='NomeConjugue_Obrigatorio' value='<?=getCodigoInternoCDA(11,13)?>'>
												<input type='hidden' name='VisivelNomeConjugue' value='2'>
												<input type='hidden' name='Email_Obrigatorio' value='<?=$linFormaEmail[Cob_FormaEmail]?>'>
												<?
													
													$sqlAux	=	"select IdPessoaSolicitacao from PessoaSolicitacao where IdPessoa=$local_IdPessoa and IdStatus=1 order by IdPessoaSolicitacao DESC limit 0,1";
													$resAux	=	@mysql_query($sqlAux,$con);
													
													if(mysql_num_rows($resAux) >= 1){
														$linAux	=	mysql_fetch_array($resAux);
														$sql	="	select 
																		Pessoa.IdPessoa,
																		Pessoa.Nome,
																		Pessoa.TipoPessoa,
																		Pessoa.NomeRepresentante,
																		Pessoa.RazaoSocial,
																		DATE_FORMAT(
																			Pessoa.DataNascimento,'%d/%m/%Y'
																		) DataNascimento,
																		Pessoa.Sexo,
																		Pessoa.RG_IE,
																		Pessoa.OrgaoExpedidor,
																		Pessoa.CPF_CNPJ,
																		Pessoa.EstadoCivil,
																		Pessoa.NomeConjugue,
																		Pessoa.NomePai,
																		Pessoa.NomeMae,
																		Pessoa.InscricaoMunicipal,
																		Pessoa.Telefone1,
																		Pessoa.Telefone2,
																		Pessoa.Telefone3,
																		Pessoa.Celular,
																		Pessoa.Fax,
																		Pessoa.ComplementoTelefone,
																		Pessoa.Email,
																		Pessoa.Site,
																		Pessoa.CampoExtra1,
																		Pessoa.CampoExtra2,
																		Pessoa.CampoExtra3,
																		Pessoa.CampoExtra4,
																		Pessoa.IdEnderecoDefault
																	from 
																	   PessoaSolicitacao,
																	   Pessoa
																	where
																	    PessoaSolicitacao.IdPessoa = Pessoa.IdPessoa and
																		PessoaSolicitacao.IdPessoaSolicitacao = $linAux[IdPessoaSolicitacao] and
																		PessoaSolicitacao.IdPessoa = $local_IdPessoa";
														$res	=	@mysql_query($sql,$con);
														$lin	=	@mysql_fetch_array($res);
														
														$sql2	=	"select count(*) QTDEndereco from PessoaSolicitacaoEndereco where IdPessoaSolicitacao=$linAux[IdPessoaSolicitacao] and IdPessoa=$local_IdPessoa";
														$res2	=	mysql_query($sql2,$con);
														$lin2	=	mysql_fetch_array($res2);
													}else{
														$sql="	select 
																	IdPessoa,
																	TipoPessoa,
																	Nome,
																	NomeRepresentante,
																	RazaoSocial,
																	DATE_FORMAT(DataNascimento, '%d/%m/%Y') DataNascimento,
																	Sexo,
																	RG_IE,
																	CPF_CNPJ,
																	EstadoCivil,
																	OrgaoExpedidor,
																	InscricaoMunicipal,
																	NomePai,
																	NomeMae,
																	NomeConjugue,
																	Telefone1,
																	Telefone2,
																	Telefone3,
																	Celular,
																	Fax,
																	ComplementoTelefone,
																	Email,
																	Site,
																	CampoExtra1,
																	CampoExtra2,
																	CampoExtra3,
																	CampoExtra4,
																	IdEnderecoDefault 
																from
																	Pessoa 
																where IdPessoa = $local_IdPessoa";
														$res	=	@mysql_query($sql,$con);
														$lin	=	@mysql_fetch_array($res);
														
														$sql2	=	"select count(*) QTDEndereco from PessoaEndereco where IdPessoa=$local_IdPessoa";
														$res2	=	mysql_query($sql2,$con);
														$lin2	=	mysql_fetch_array($res2);
													}
													
													echo"<input type='hidden' name='IdPessoa' value='$local_IdPessoa'>";
													echo"<input type='hidden' name='TipoPessoa' value='$lin[TipoPessoa]'>";
													echo"<input type='hidden' name='QtdEndereco' value='$lin2[QTDEndereco]'>";
													echo"<input type='hidden' name='QtdEnderecoAux' value='$lin2[QTDEndereco]'>";
													echo"<input type='hidden' name='IdPessoaSolicitacao' value='$linAux[IdPessoaSolicitacao]'>";
													echo"<input type='hidden' name='DescricaoEndereco1' value='".getCodigoInternoCDA(3,87)."'>";
													echo"<input type='hidden' name='DescricaoEndereco2' value='".getCodigoInternoCDA(3,88)."'>";
													
													if($lin[TipoPessoa] == 1){ //Juridica
														echo"
														<table>
															<tr>
																<td class='title'><B>Nome Fantasia</B></td>
																<td class='sep' />
																<td class='title'><B>Razão Social</B></td>
															</tr>
															<tr>	
																<td>
																	<input class='FormPadrao' type='text' name='Nome' value='$lin[Nome]' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='RazaoSocial' value='$lin[RazaoSocial]' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2'>
																</td>
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'><B>Nome Representante</B></td>
																<td class='sep' />
																<td class='title'>Inscrição Estadual</td>
																<td class='sep' />
																<td class='title'>Inscrição Municipal</td>
																<td class='sep' />
																<td class='title'>Data Fundação</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='NomeRepresentante' value='$lin[NomeRepresentante]' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='3'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='RG_IE' value='$lin[RG_IE]' maxlength='30' style='width:98px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='4'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='InscricaoMunicipal' value='$lin[InscricaoMunicipal]' maxlength='30' style='width:98px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='5'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='DataNascimento' value='$lin[DataNascimento]' style='width:80px' maxlength='10' onFocus=\"Foco(this,'in',true)\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'date')\" tabindex='6'>
																</td>
															</tr>
														</table>
														";
													}else{ //Fisica
														echo"
														<table>
															<tr>
																<td class='title'><B>Nome Pessoa</B></td>
																<td class='sep' />";
																
														if(getCodigoInterno(11,11) == 1){
															echo "<td class='title'><B>Sexo</B></td>";
														}else{
															echo "<td class='title'>Sexo</td>";
														}
														
														echo "<td class='sep' />
																<td class='title'>";
														
														if(getCodigoInterno(11,6) == 1){
															echo "<B>Data Nasc.</B></td>";
														} else{
															echo "Data Nasc.</td>";
														}
														
														echo "
																<td class='sep' />";
														if(getCodigoInternoCDA(11,12) == 1){
															echo "<td class='title'><B>Estado Civil</B></td>";
														}else{
															echo "<td class='title'>Estado Civil</td>";
														}
														
														echo"	<td class='sep' />";
														if(getCodigoInternoCDA(11,16) == 1){
															echo "<td class='title'><B>RG</B></td>";
														}else{
															echo "<td class='title'>RG</td>";
														}
													echo"	<td class='sep' />
															<td class='title' id='labelOrgaoExpedidor'>Orgão Exp.</td>
														</tr>
															<tr>	
																<td>
																	<input class='FormPadrao' type='text' name='Nome' value='$lin[Nome]' style='width:160px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='7'>
																</td>
																<td class='sep' />
																<td>
																	<select class='FormPadrao' name='Sexo' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='8' style='width: 55px'>
																		<option value='' selected></option>";
																			$sql2 = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=8 order by ValorParametroSistema";
																			$res2 = @mysql_query($sql2,$con);
																			while($lin2 = @mysql_fetch_array($res2)){
																				echo"<option value='$lin2[IdParametroSistema]' ".compara($lin[Sexo],$lin2[IdParametroSistema],"selected='selected'","").">$lin2[ValorParametroSistema]</option>";
																			}
																	echo"
																	</select>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='DataNascimento' value='$lin[DataNascimento]' style='width:80px' maxlength='10' onFocus=\"Foco(this,'in',true)\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'date')\" tabindex='9'>
																</td>
																<td class='sep' />
																<td>
																	<select class='FormPadrao' name='EstadoCivil' style='width:90px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='10' Onchange='visualizarConjugue(this.value)'>
																		<option value='' selected></option>";
																			$sql2 = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=9 order by ValorParametroSistema";
																			$res2 = @mysql_query($sql2,$con);
																			while($lin2 = @mysql_fetch_array($res2)){
																				echo"<option value='$lin2[IdParametroSistema]' ".compara($lin[EstadoCivil],$lin2[IdParametroSistema],"selected='selected'","").">$lin2[ValorParametroSistema]</option>";
																			}
																		echo"
																	</select>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='RG_IE' value='$lin[RG_IE]' maxlength='30' style='width:90px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='11' onchange='obrigatoriedadeOrgaoExpedidor()'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='OrgaoExpedidor' value='$lin[OrgaoExpedidor]' maxlength='30' style='width:90px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='11'>
																</td>
															</tr>
														</table>	
														<table>
															<tr>";		
													if(getCodigoInterno(11,15) == 1){
														echo"	<td class='title'><B>Nome Mãe</B></td>
																<td class='sep' />";
													}else{
														echo "	<td class='title'>Nome Mãe</td>
																<td class='sep' />";
													}
													
													if(getCodigoInterno(11,14) == 1){
														echo "	<td class='title'><B>Nome Pai</B></td>
																<td class='sep' />";
													}else{
														echo "	<td class='title'>Nome Pai</td>
																<td class='sep' />";
													}
																										
													if(getCodigoInterno(11,13) == 1){
														echo "	<td class='title' id='labelNomeConjugue' style='display: none'><B>Nome Conjugue</B></td>";
													}else{
														echo "	<td class='title' id='labelNomeConjugue' style='display: none'>Nome Conjugue</td>";
													}
													 echo "
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='NomeMae' value='$lin[NomeMae]' style='width:295px' maxlength='18' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='11'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='NomePai' value='$lin[NomePai]' style='width:294px' maxlength='18' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='11'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' style='display: none;width: 200px' type='text' name='NomeConjugue' value='$lin[NomeConjugue]' maxlength='18' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='11'>
																</td>					
															</tr>
														</table>
														";
													}
													echo"
														<table>
															<tr>
																<td class='title'>Fone Residencial</td>
																<td class='sep' />
																<td class='title'>Fone Comercial (1)</td>
																<td class='sep' />
																<td class='title'>Fone Comercial (2)</td>
																<td class='sep' />
																<td class='title'>Celular</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='Telefone1' value='$lin[Telefone1]' style='width:143px' maxlength='18' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='12'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Telefone2' value='$lin[Telefone2]' style='width:143px' maxlength='18' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='13'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Telefone3' value='$lin[Telefone3]' style='width:143px' maxlength='18' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='14'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Celular' value='$lin[Celular]' style='width:143px' maxlength='18' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='15'>
																</td>					
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'>Fax</td>	
																<td class='sep' />
																<td class='title'>Complemento Fone</td>
																<td class='sep' />
																<td class='title'><B>Endereço Principal</B></td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='Fax' value='$lin[Fax]' style='width:143px' maxlength='18' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='16'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='ComplementoTelefone' value='$lin[ComplementoTelefone]' style='width:143px' maxlength='30' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='17'>
																</td>
																<td class='sep' />
																<td>
																	<select class='FormPadrao' name='IdEnderecoDefault' style='width:292px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='18'>
																		<option value='' selected></option>
																	</select>
																</td>
															</tr>
														</table>
														<table>
															<tr>
																<td class='title'>$DescEmail</td>
																<td class='sep' />
																<td class='title'>Site (url)</td>
															</tr>
															<tr>
																<td>
																	<input class='FormPadrao' type='text' name='Email' value='$lin[Email]' style='width:294px' maxlength='255' autocomplete='off' onFocus=\"Foco(this,'in',true)\"  onBlur=\"Foco(this,'out');\" tabindex='20'>
																</td>
																<td class='sep' />
																<td>
																	<input class='FormPadrao' type='text' name='Site' value='$lin[Site]' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='21'>
																</td>
															</tr>
														</table>
													";
													
													$sql1	=	"select IdParametroSistema,DescricaoParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and substr(DescricaoParametroSistema,26,31) = 'Ativo' and ValorParametroSistema = 'S'";
													$res1	=	mysql_query($sql1,$con);
													$quant	=	mysql_num_rows($res1);
													
													if($quant > 0){
														echo"<table><tr>";
														$i = 1;
														$TabIndex = 21;
														$campo = '';
														
														while($lin1	=	mysql_fetch_array($res1)){
															$IdTitulo	=	$lin1[IdParametroSistema] + 1;
															$sql2	=	"select IdParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and IdParametroSistema=".$IdTitulo;
															$res2	=	mysql_query($sql2,$con);
															$lin2	=	mysql_fetch_array($res2);
														
															$IdObrigatoriedade	=	$lin1[IdParametroSistema] + 2;
															$sql3	=	"select IdParametroSistema,ValorParametroSistema from GrupoParametroSistema,ParametroSistema where GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema and GrupoParametroSistema.IdGrupoParametroSistema = 2 and IdParametroSistema=".$IdObrigatoriedade;
															$res3	=	mysql_query($sql3,$con);
															$lin3	=	mysql_fetch_array($res3);
															$mod = (($i % 2) == 0);
															
															if($mod){
																echo "<td class='sep' />";
																$campo .= "<td class='sep' />";
															}
															
															echo"<input type='hidden' name='CampoExtra".$i."Obrigatorio' value ='$lin3[ValorParametroSistema]'>";																
													
															if($lin3[ValorParametroSistema]=='S'){
																echo"<td class='title'><B>$lin2[ValorParametroSistema]</B></td>";
															}else{
																echo"<td class='title'>$lin2[ValorParametroSistema]</td>";
															}			
															
															$campo .= "<td><input class='FormPadrao' type='text' name='CampoExtra".$i."' value='".$lin["CampoExtra".$i]."' style='width:294px' maxlength='255' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='".($TabIndex++)."'></td>";
															
															if($mod){
																echo "</tr><tr>$campo</tr></table><table><tr>";
																$campo = '';
															}
															
															$i++;
														}
														
														if($campo != ''){
															echo "<tr>$campo</tr>";
														}
													
														/*echo"</tr><tr>";
														$i=1;
														while($i <= $quant){
															$Value	=	$lin["CampoExtra".$i];
														
															if($i!=1){
																echo "<td class='sep' />";
															}
															
															$tab	=	21 + $i;
															
															echo"
																<td>
																	<input class='FormPadrao' type='text' name='CampoExtra$i' value='$Value' style='width:142px' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='$tab'>
																</td>
															";
															$i++;
														}*/
													
														echo"</tr></table>";
													}
												?>
												<table id='tableEndereco' style='margin:0; padding:0' cellspacing='0' cellpading='0'>
													<tr>
													</tr>
												</table>
												<table style='float:right'>
													<tr>	
						               					<td><input name="bt_submit" type="button" class="BotaoPadrao" value="Alterar" tabindex='300' onclick='validar_pessoa()'/></td>
						               				</tr>
						             		 	</table>					    			
					             		 	</form>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
								<script>
									inicia_pessoa();
									obrigatoriedadeOrgaoExpedidor();
									visualizarConjugue(document.formulario.EstadoCivil.value);
									enterAsTab(document.forms.formulario);
									<?
										if($_GET["EmailFocus"] == 1){
											echo"document.formulario.Email.focus();";
										}
									?>								
								</script>	
