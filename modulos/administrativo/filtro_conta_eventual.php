<?
	if($localOrdem == ''){						$localOrdem = "IdContaEventual";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = 'descending';	}
		
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){					$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_conta_eventual.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(386)?></td>
					<td><?=dicionario(362)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);'/></td>					
					<td><input type='text' value='<?=$localDescricaoContaEventual?>' name='filtro_descricao' style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_local_cobranca' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdLocalCobranca, AbreviacaoNomeLocalCobranca from LocalCobranca order by DescricaoLocalCobranca";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[AbreviacaoNomeLocalCobranca] = url_string_xsl($lin[AbreviacaoNomeLocalCobranca],'convert');
									
									echo "<option value='$lin[IdLocalCobranca]' ".compara($localIdLocalCobranca,$lin[IdLocalCobranca],"selected='selected'","").">$lin[AbreviacaoNomeLocalCobranca]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select  name='filtro_campo' style='width:100px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value =''></option>
							<option value ='DataCriacao' <?php echo compara($localCampo,"DataCriacao","selected='selected'",""); ?>><?=dicionario(133)?></option>
							<option value ='DataAlteracao' <?php echo compara($localCampo,"DataAlteracao","selected='selected'",""); ?>><?=dicionario(135)?></option>
							<option value ='DataConfirmacao' <?php echo compara($localCampo,"DataConfirmacao","selected='selected'",""); ?>><?=dicionario(787)?></option>
							<option value ='MesCadastro' <?php echo compara($localCampo,"MesCadastro","selected='selected'",""); ?>><?=dicionario(300)?></option>
							<option value ='MesAlteracao' <?php echo compara($localCampo,"MesAlteracao","selected='selected'",""); ?>><?=dicionario(479)?></option>
							<option value ='MesConfirmacao' <?php echo compara($localCampo,"MesConfirmacao","selected='selected'",""); ?>><?=dicionario(1055)?></option>
							<option value ='IdContaReceber' <?php echo compara($localCampo,"IdContaReceber","selected='selected'",""); ?>><?=dicionario(1056)?></option>
							<option value ='Referencia' <?php echo compara($localCampo,"Referencia","selected='selected'",""); ?>><?=dicionario(1057)?></option>
							<option value ='DataPrimeiroVencimento' <?php echo compara($localCampo,"DataPrimeiroVencimento","selected='selected'",""); ?>><?=dicionario(1058)?></option>
						</select>
					</td>					
					<td><input type='text' value='<?=$localCampoValor?>' name='filtro_campo_valor' style='width:70px' onFocus="Foco(this,'in')" onkeypress="chama_mascara(this,event)" onBlur="Foco(this,'out')" maxlength='10'  onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:100px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=46 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>	
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao'/></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_eventual_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(23);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
