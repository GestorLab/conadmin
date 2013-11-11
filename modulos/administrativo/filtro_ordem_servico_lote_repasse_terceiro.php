<?
	$IdLoja = $_SESSION['IdLoja'];
	
	if($localOrdem == ''){					$localOrdem = "NomeTerceiro";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
	$localFormaCobranca						=	$_SESSION["filtro_forma_cobranca"];
	$localTerceiro							=	$_SESSION["filtro_terceiro"];
	$local_ListaConcluido					=	$_SESSION["filtro_lista_concluido"];	
	$local_ListaCancelado					=	$_SESSION["filtro_lista_cancelado"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_ordem_servico_lote_repasse_terceiro.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(223)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(140)?></td>		
					<td><?=dicionario(152)?></td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_nome' value="<?=$localNome?>" style='width:155px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td><input type='text' name='filtro_servico' value='<?=$localDescricaoServico?>' style='width:95px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>							
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:125px'  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>							
							<option value='DescricaoOS' <?=compara($localCampo,"DescricaoOS","selected='selected'","")?>><?=dicionario(483)?>.</option>
							<option value='IdGrupoUsuarioAtendimento' <?=compara($localCampo,"IdGrupoUsuarioAtendimento","selected='selected'","")?>><?=dicionario(484)?>.</option>
							<option value='LoginResponsavel' <?=compara($localCampo,"LoginResponsavel","selected='selected'","")?>><?=dicionario(488)?></option>
							<option value='LoginAtendimento' <?=compara($localCampo,"LoginAtendimento","selected='selected'","")?>><?=dicionario(485)?>.</option>
							<option value='DataCadastro' <?=compara($localCampo,"DataCadastro","selected='selected'","")?>><?=dicionario(133)?></option>
							<option value='DataVencimento' <?=compara($localCampo,"DataVencimento","selected='selected'","")?>><?=dicionario(399)?></option>
							<option value='DataFatura' <?=compara($localCampo,"DataFatura","selected='selected'","")?>><?=dicionario(489)?></option>							
							<option value='MesFatura' <?=compara($localCampo,"MesFatura","selected='selected'","")?>><?=dicionario(507)?></option>
							<option value='MesCadastro' <?=compara($localCampo,"MesCadastro","selected='selected'","")?>><?=dicionario(300)?></option>
							<option value='MesVencimento' <?=compara($localCampo,"MesVencimento","selected='selected'","")?>><?=dicionario(508)?></option>							
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:85px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeypress="chama_mascara(this,event)"/></td>
					<td>
						<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and (IdParametroSistema = 400 or IdParametroSistema = 500) order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>			
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_ordem_servico_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>	
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:280px'><?=dicionario(460)?></td>
									<td>
										<select name='filtro_forma_cobranca' style='width:180px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<option value=''></option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=50 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localFormaCobranca,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(33)?></td>
									<td>
										<select name='filtro_terceiro' style='width:180px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<option value=''></option>
											<?
												$sql = "select Pessoa.IdPessoa, Pessoa.Nome from Pessoa, Terceiro where Terceiro.IdLoja = $IdLoja and Pessoa.IdPessoa = Terceiro.IdPessoa";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[Nome] = url_string_xsl($lin[Nome],'convert');
													
													echo "<option value='$lin[IdPessoa]' ".compara($localTerceiro,$lin[IdPessoa],"selected='selected'","").">$lin[Nome]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(509)?></td>
									<td>
										<select name='filtro_percentual_repasse_terceiro' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=166 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localPercentualRepasseTerceiro,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(510)?></td>
									<td>
										<select name='filtro_percentual_repasse_terceiro_outros' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=167 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localPercentualRepasseTerceiroOutros,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width: 120px'><?=dicionario(973)?></td>
									<td>
										<select name='filtro_lista_concluido' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=60 order by ValorParametroSistema";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_ListaConcluido,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(978)?></td>
									<td>
										<select name='filtro_lista_cancelado' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event)">
											<?
												$sql = "select
															IdParametroSistema,
															ValorParametroSistema
														from
															ParametroSistema
														where
															IdGrupoParametroSistema = 271
														order by
															ValorParametroSistema";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_ListaCancelado,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
											</select>
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, '<?=dicionario(291)?>');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='<?=dicionario(168)?>' />
							</div>
						</div>
					</td>
					<td />
				</tr>				
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(20);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>