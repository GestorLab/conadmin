<?
	if($localOrdem == ''){
		switch(getCodigoInterno(3,229)){		
			case 'Id':
				$localOrdem = "IdOrdemServico";
				break;
			case 'Nome Pessoa':
				$localOrdem = "Nome";
				break;
			case 'Tipo OS':
				$localOrdem = "DescricaoTipoOrdemServico";
				break;
			case 'Responsável':
				$localOrdem = "LoginSupervisor";
				break;
			case 'Nome Serviço':
				$localOrdem = "DescricaoServico";
				break;	
			case 'Descrição OS':
				$localOrdem = "DescricaoOS";
				break;
			case 'Valor (R$)':
				$localOrdem = "Valor";
				break;
			case 'Data Fatura':
				$localOrdem = "DataFaturamento";
				break;
			case 'Data Criação':
				$localOrdem = "DataCriacao";
				break;
			case 'Tempo Aber.':
				$localOrdem = "DiaAbertura";
				break;
			case 'Status':
				$localOrdem = "Status";
				break;
		}
	}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = "descending";	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){		$localTipoDado = 'number';	}
	$local_ListaConcluido			=	$_SESSION["filtro_lista_concluido"];	
	$local_ListaCancelado			=	$_SESSION["filtro_lista_cancelado"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_ordem_servico.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_ordenar'			value='' />
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
							<option value='MesAgendamento' <?=compara($localCampo,"MesAgendamento","selected='selected'","")?>><?=dicionario(480)?></option>
							<option value='MesFaturamento' <?=compara($localCampo,"MesFaturamento","selected='selected'","")?>><?=dicionario(481)?></option>
							<option value='MesConclusao' <?=compara($localCampo,"MesConclusao","selected='selected'","")?>><?=dicionario(482)?></option>
							<option value='DescricaoOS' <?=compara($localCampo,"DescricaoOS","selected='selected'","")?>><?=dicionario(483)?>.</option>
							<option value='IdGrupoUsuarioAtendimento' <?=compara($localCampo,"IdGrupoUsuarioAtendimento","selected='selected'","")?>><?=dicionario(484)?>.</option>
							<option value='LoginAtendimento' <?=compara($localCampo,"LoginAtendimento","selected='selected'","")?>><?=dicionario(485)?>.</option>
							<option value='UsuarioCadastro' <?=compara($localCampo,"UsuarioCadastro","selected='selected'","")?>><?=dicionario(486)?>.</option>
							<option value='Responsavel' <?=compara($localCampo,"Responsavel","selected='selected'","")?>><?=dicionario(488)?></option>
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
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(192)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_ordem_servico_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:120px'><?=dicionario(460)?></td>
									<td>
										<select name='filtro_forma_cobranca' style='width:120px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')">
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=50 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_FormaCobranca,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(112)?></td>
									<td>
										<select name='filtro_tipo_pessoa' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 order by ValorParametroSistema";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_TipoPessoa,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
											</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(973)?></td>
									<td>
										<select name='filtro_lista_concluido' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event)">
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
										<select name='filtro_lista_cancelado' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event)">
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
								<img onmousemove="quadro_alt(event, this, '<?=htmlespecialchars(dicionario(291))?>');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
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
