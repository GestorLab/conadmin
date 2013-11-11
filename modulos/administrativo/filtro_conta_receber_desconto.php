<?
	if($localOrdem == ''){							$localOrdem = "DataVencimento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
	$localCancelado	=	$_SESSION["filtro_cancelado"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_conta_receber_desconto.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_carne'			value='<?=$local_IdCarne?>' />
			
			<table>
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td>Local Cob.</td>
					<td>Desc. Conceber Ini. (<?=getParametroSistema(5,1)?>)</td>
					<td>Desc. Conceber Fim (<?=getParametroSistema(5,1)?>)</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td>
						<select name='filtro_local_cobranca' style='width:85px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdLocalCobranca, AbreviacaoNomeLocalCobranca from LocalCobranca where IdLoja = $local_IdLoja order by DescricaoLocalCobranca";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[AbreviacaoNomeLocalCobranca] = url_string_xsl($lin[AbreviacaoNomeLocalCobranca],'convert');
									
									echo "<option value='$lin[IdLocalCobranca]' ".compara($localIdLocalCobranca,$lin[IdLocalCobranca],"selected='selected'","").">$lin[AbreviacaoNomeLocalCobranca]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' name='filtro_desconto_ini' value='<?=$localDescontoIni?>' style='width:140px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'float')" maxlength='12' onKeyDown="listar(event)" /></td>
					<td><input type='text' name='filtro_desconto_fim' value='<?=$localDescontoFim?>' style='width:140px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'float')" maxlength='12' onKeyDown="listar(event)" /></td>
					<td>
						<select name='filtro_status' style='width:110px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
								
								echo "<option value='200' ".compara($localIdStatus,200,"selected='selected'","").">Vencido</option>";
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom'); buscar();" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_receber_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:80px'>Pessoa</td>
									<td>
										<input type='text' name='filtro_id_pessoa' value='<?=$localIdPessoa?>' style='width:70px' maxlength='11' onChange='filtro_buscar_pessoa(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_pessoa' value="<?=$localNome?>" style='width:374px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:80px'>Serviço</td>
									<td>
										<input type='text' name='filtro_id_servico' value='<?=$localIdServico?>' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='<?=$localDescricaoServico?>' style='width:374px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:110px'>Tipo Pessoa</td>
									<td>
										<select name='filtro_tipo_pessoa' style='width:110px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value=''>Todos</option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 order by ValorParametroSistema";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													echo "<option value='$lin[IdParametroSistema]' ".compara($localTipoPessoa,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:245px'>Listar Contas à Receber cancelados</td>
									<td>
										<select name='filtro_cancelado' style='width:110px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=107 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localCancelado,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>									
									<td><?=dicionario(150)?></td>
									<td>
										<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:110px'  onKeyDown="listar(event)">
											<option value=''><?=dicionario(153)?></option>
											<option value='IdArquivoRetorno' <?=compara($localCampo,"IdArquivoRetorno","selected='selected'","")?>><?=dicionario(42)?></option>
											<option value='DataLancamento' <?=compara($localCampo,"DataLancamento","selected='selected'","")?>><?=dicionario(409)?>.</option>
											<option value='DataVencimentoOriginal' <?=compara($localCampo,"DataVencimentoOriginal","selected='selected'","")?>><?=dicionario(709)?></option>
											<option value='DataVencimentoAtual' <?=compara($localCampo,"DataVencimentoAtual","selected='selected'","")?>><?=dicionario(710)?></option>
											<option value='IdLancamentoFinanceiro' <?=compara($localCampo,"IdLancamentoFinanceiro","selected='selected'","")?>><?=dicionario(650)?>.</option>
											<option value='MesLancamento' <?=compara($localCampo,"MesLancamento","selected='selected'","")?>><?=dicionario(412)?>.</option>
											<option value='MesVencimentoOriginal' <?=compara($localCampo,"MesVencimentoOriginal","selected='selected'","")?>><?=dicionario(712)?>.</option>
											<option value='MesVencimentoAtual' <?=compara($localCampo,"MesVencimentoAtual","selected='selected'","")?>><?=dicionario(711)?></option>
											<option value='DescricaoServico' <?=compara($localCampo,"DescricaoServico","selected='selected'","")?>><?=dicionario(223)?></option>
											<option value='NumeroDocumento' <?=compara($localCampo,"NumeroDocumento","selected='selected'","")?>>Nº <?=dicionario(713)?></option>
											<option value='IdContaReceber' <?=compara($localCampo,"IdContaReceber","selected='selected'","")?>>Nº <?=dicionario(387)?>.</option>
											<option value='NumeroNF' <?=compara($localCampo,"NumeroNF","selected='selected'","")?>>Nº <?=dicionario(53)?></option>
											<option value='IdReceibo' <?=compara($localCampo,"IdReceibo","selected='selected'","")?>>N° <?=dicionario(708)?></option>
											<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>><?=dicionario(417)?>.</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(151)?></td>
									<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:104px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar(event);" /></td>
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
			echo menu_acesso_rapido(19);
		?>
	</div>
	<script language="javascript" type="text/javascript">
		function buscar() {
			if(document.filtro.filtro_id_servico.value != ''){
				filtro_buscar_servico(document.filtro.filtro_id_servico.value);

			}
			if(document.filtro.filtro_id_pessoa.value != ''){
				filtro_buscar_pessoa(document.filtro.filtro_id_pessoa.value);
			
			}
		}
		enterAsTab(document.forms.filtro);
	</script>
