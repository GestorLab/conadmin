<?
	if($localOrdem == ''){						$localOrdem = "IdArquivoRetorno";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){					$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_arquivo_retorno.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Local de Recebimento</td>
					<td>Nome Original</td>
					<td>Campo</td>
					<td>Data Início</td>
					<td>Data Término</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_local_receb' style='width:181px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobranca where IdLoja=$local_IdLoja and IdArquivoRetornoTipo != '' order by DescricaoLocalCobranca";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoLocalCobranca] = url_string_xsl($lin[DescricaoLocalCobranca],'convert');
									
									echo "<option value='$lin[IdLocalCobranca]' ".compara($localIdLocalCobranca,$lin[IdLocalCobranca],"selected='selected'","").">$lin[DescricaoLocalCobranca]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localNomeArquivo?>' name='filtro_nome_arquivo' style='width:130px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" maxlength='20'  onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:116px'  onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<option value='DataCadastro' <?=compara($localCampo,"DataCadastro","selected='selected'","")?>>Data Cadastro</option>
							<option value='DataRetorno' <?=compara($localCampo,"DataRetorno","selected='selected'","")?>>Data Retorno</option>
							<option value='DataProcessamento' <?=compara($localCampo,"DataProcessamento","selected='selected'","")?>>Data Process.</option>
						</select>
					</td>
					<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:78px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  maxlength='10'  onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localDataTermino?>' name='filtro_data_termino' style='width:78px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" maxlength='10'  onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_id_status' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select 
											IdParametroSistema, 
											ValorParametroSistema 
										from 
											ParametroSistema 
										where 
											IdGrupoParametroSistema = 195";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus, $lin[IdParametroSistema], "selected='selected'", "").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom');" alt='Parâmetros de Consulta Rápida' /></td>
				</tr>
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:102%; display:none; background-color: #0065D5'>
							<table style='width:80%;'>
								<tr>
									<td style='width:103px'>N° Sequencial</td>
									<td>
										<input type='text' name='filtro_n_sequencial' value='<?=$localNumeroSequencial?>' style='width:70px' maxlength='10' onkeypress="mascara(this,event,'numerico')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/>
									</td>
								</tr>
								<tr>
									<td>Data Cadastro De</td>
									<td>
										<input type='text' name='filtro_data_sequencial_inicio' value='<?=$localSeqDataIncio?>' style='width:70px;' maxlength='10' onChange='filtro_buscar_servico(this.value);' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/> Até <input type='text' class='agrupador' name='filtro_data_sequencial_final' value='<?=$localSeqDataFinal?>' style='width:70px' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')" maxlength='10' onBlur="Foco(this,'out')"/>
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, 'Os filtros do \'Menu Avançado\' ao serem alterados, o valor prevalece selecionado durante a sessão');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
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
			echo menu_acesso_rapido(15);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
