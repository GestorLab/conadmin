<?
	if($localOrdem == ''){							$localOrdem = "IdContaReceber";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){						$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_pessoa_forma_cobranca.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(41)?></td>
					<td><?=dicionario(196)?></td>
					<td><?=dicionario(40)?></td>
					<td><?=dicionario(197)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNomePessoa?>" name="filtro_nome" size='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td><input type='text' value='<?=$localIdProcessoFinanceiro?>' name='filtro_processo_financeiro' size='12' onFocus="Foco(this,'in')"  maxlength='11' onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localMesReferencia?>' name='filtro_mes_referencia' size='12' onFocus="Foco(this,'in')"  maxlength='7' onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'mes')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localDescricaoLocalCobranca?>' name='filtro_local_cobranca' size='20' onFocus="Foco(this,'in')"  maxlength='100' onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_forma_cobranca' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<option value='C' <?=compara($localFormaCobranca,"C","selected='selected'","")?>><?=dicionario(193)?></option>
							<option value='E' <?=compara($localFormaCobranca,"E","selected='selected'","")?>><?=dicionario(104)?></option>
							<option value='O' <?=compara($localFormaCobranca,"O","selected='selected'","")?>><?=dicionario(120)?></option>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='<?=dicionario(167)?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_pessoa_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr><tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:181px'><?=dicionario(155)?></td>
									<td>
										<select name='filtro_pessoa_endereco' style='width:121px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select distinct
															IdPessoaEndereco,
															concat('Endereço ',IdPessoaEndereco) DescricaoEndereco
														from
															PessoaEndereco
														order by
															IdPessoaEndereco;";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[DescricaoEndereco] = url_string_xsl($lin[DescricaoEndereco],'convert');
													
													echo "<option value='$lin[IdPessoaEndereco]' ".compara($localIdPessoaEndereco,$lin[IdPessoaEndereco],"selected='selected'","").">$lin[DescricaoEndereco]</option>";
												}
											?>
										</select>
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, '<?=dicionario(199)?>');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='<?=dicionario(168)?>' />
							</div>
						</div>
					</td>
					<td />
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'></div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>