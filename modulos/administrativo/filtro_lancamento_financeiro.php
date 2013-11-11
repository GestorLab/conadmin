<?
	if($localOrdem == ''){							$localOrdem = "Nome";	}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_lancamento_financeiro.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdContaEventual'			value='<?=$_GET['IdContaEventual']?>' />
			<input type='hidden' name='IdProcessoFinanceiro'	value='' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(40)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event); '/></td>
					<td>
						<select name='filtro_local_cobranca' style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobranca order by DescricaoLocalCobranca";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoLocalCobranca] = url_string_xsl($lin[DescricaoLocalCobranca],'convert');
									echo "<option value='$lin[IdLocalCobranca]' ".compara($localIdLocalCobranca,$lin[IdLocalCobranca],"selected='selected'","").">$lin[DescricaoLocalCobranca]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px'  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<option value='DataReferenciaInicial' <?=compara($localCampo,"DataReferenciaInicial","selected='selected'","")?>><?=dicionario(668)?></option>
							<option value='DataReferenciaFinal' <?=compara($localCampo,"DataReferenciaFinal","selected='selected'","")?>><?=dicionario(669)?></option>
							<option value='IdContaReceber' <?=compara($localCampo,"IdContaReceber","selected='selected'","")?>><?=dicionario(670)?></option>
							<option value='IdContrato' <?=compara($localCampo,"IdContrato","selected='selected'","")?>><?=dicionario(27)?></option>
							<option value='DescricaoServico' <?=compara($localCampo,"DescricaoServico","selected='selected'","")?>><?=dicionario(223)?></option>
							<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>><?=dicionario(417)?>.</option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:100px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:150px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=51";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(21);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
