<?
	$IdLoja = $_SESSION['IdLoja'];
	
	if($localOrdem == ''){					$localOrdem = "NomeTerceiro";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_ordem_servico_lote_repasse_terceiro_parcela_quitada.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td>Nome Serviço</td>
					<td>Campo</td>
					<td>Valor Campo</td>
					<td>Forma Cobranca</td>		
					<td>Terceiro</td>
					<td>Qtd.</td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_nome' value='<?=$localNome?>' style='width:155px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_servico' value='<?=$localDescricaoServico?>' style='width:85px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>							
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px'  onKeyDown="listar(event)">
							<option value=''>Todos</option>							
							<option value='MesFatura' <?=compara($localCampo,"MesFatura","selected='selected'","")?>>Mês Fatura</option>
							<option value='MesVencimento' <?=compara($localCampo,"MesVencimento","selected='selected'","")?>>Mês Vencimento</option>							
							<option value='MesRecebimento' <?=compara($localCampo,"MesRecebimento","selected='selected'","")?>>Mês Recebimento</option>							
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:80px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeypress="chama_mascara(this,event)"/></td>				
					<td>
						<select name='filtro_forma_cobranca' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:95px'  onKeyDown="listar(event)">
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
					<td>
						<select name='filtro_terceiro' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
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
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_ordem_servico_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>	
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>													
								<tr>
									<td>Ocultar coluna Rep. Terceiro</td>
									<td>
										<select name='filtro_percentual_repasse_terceiro' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
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
									<td>Ocultar coluna Rep. Terceiro Outros</td>
									<td>
										<select name='filtro_percentual_repasse_terceiro_outros' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
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
			echo menu_acesso_rapido(20);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
