<?
	if($localOrdem == ''){					$localOrdem = "DataCriacao";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = "descending";	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	$local_ListaConcluido					=	$_SESSION["filtro_lista_concluido"];	
	$local_ListaCancelado					=	$_SESSION["filtro_lista_cancelado"];	
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_ordem_servico_localidade.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdCidadeTemp'			value='<?=$local_IdCidade?>' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(223)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(477)?></td>
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
							<option value='DataCadastro' <?=compara($localCampo,"DataCadastro","selected='selected'","")?>><?=dicionario(133)?></option>
							<option value='DataAlteracao' <?=compara($localCampo,"DataAlteracao","selected='selected'","")?>><?=dicionario(135)?></option>
							<option value='DataAgendamento' <?=compara($localCampo,"DataAgendamento","selected='selected'","")?>><?=dicionario(451)?></option>
							<option value='DataFaturamento' <?=compara($localCampo,"DataFaturamento","selected='selected'","")?>><?=dicionario(478)?></option>
							<option value='DataConclusao' <?=compara($localCampo,"DataConclusao","selected='selected'","")?>><?=dicionario(462)?></option>
							<option value='MesCadastro' <?=compara($localCampo,"MesCadastro","selected='selected'","")?>><?=dicionario(300)?></option>
							<option value='MesAlteracao' <?=compara($localCampo,"MesAlteracao","selected='selected'","")?>><?=dicionario(479)?></option>
							<option value='MesFaturamento' <?=compara($localCampo,"MesFaturamento","selected='selected'","")?>><?=dicionario(481)?></option>
							<option value='MesConclusao' <?=compara($localCampo,"MesConclusao","selected='selected'","")?>><?=dicionario(482)?></option>
							<option value='DescricaoOS' <?=compara($localCampo,"DescricaoOS","selected='selected'","")?>><?=dicionario(483)?>.</option>
							<option value='IdGrupoUsuarioAtendimento' <?=compara($localCampo,"IdGrupoUsuarioAtendimento","selected='selected'","")?>><?=dicionario(484)?>.</option>
							<option value='LoginResponsavel' <?=compara($localCampo,"LoginResponsavel","selected='selected'","")?>><?=dicionario(488)?></option>
							<option value='LoginAtendimento' <?=compara($localCampo,"LoginAtendimento","selected='selected'","")?>><?=dicionario(485)?>.</option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:85px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeypress="chama_mascara(this,event)"/></td>
					<td>
						<select name='filtro_faturado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:70px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=163 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localFaturado,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 order by ValorParametroSistema";
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
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="atualiza_campo(); consulta_rapida('bottom');" alt='<?=dicionario(167)?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_ordem_servico_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:120px'><?=dicionario(157)?>: </td>
									<td>
										<select name='filtro_estado' style='width:250px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_filtro_cidade(this.value)">
											<option value=''></option>
											<?
												$sql = "select IdEstado, NomeEstado from Estado where 1 order by NomeEstado asc";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													echo "<option value='$lin[IdEstado]' ".compara($local_IdEstado,$lin[IdEstado],"selected='selected'","").">$lin[NomeEstado]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(186)?>: </td>
									<td>
										<select name='filtro_cidade' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value=''></option>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:120px'><?=dicionario(160)?>: </td>
									<td><input type='text' name='filtro_bairro' value='<?=$localBairro?>' style='width:245px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
								</tr>
								<tr>
									<td style='width:120px'><?=dicionario(155)?>: </td>
									<td><input type='text' name='filtro_endereco' value='<?=$localEndereco?>' style='width:245px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
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
								<img onmousemove="quadro_alt(event, this, 'Os filtros do \'Menu Avan�ado\' ao serem alterados, o valor prevalece selecionado durante a sess�o');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='<?=dicionario(168)?>'/>
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
		function atualiza_campo(){
			if(document.filtro.IdCidadeTemp.value != ''){
				busca_filtro_cidade(document.filtro.filtro_estado.value,document.filtro.IdCidadeTemp.value);
				document.filtro.IdCidadeTemp.value = '';
			} 
		}
		enterAsTab(document.forms.filtro);
	</script>