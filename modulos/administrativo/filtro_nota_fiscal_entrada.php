<?
	if($localOrdem == ''){						$localOrdem = "DataNF";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){					$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_nota_fiscal_entrada.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Número NF</td>
					<td>CNPJ Fornecedor</td>
					<td>Série NF</td>
					<td>Tipo NF</td>
					<td>Data NF</td>
					<td>Campo</td>
					<td>Valor</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localNumeroNF?>' name='filtro_numero_nf' maxlength='11' style='width:90px' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localCPF_CNPJ?>' name='filtro_cnpj' maxlength='18' style='width:110px' onkeypress="mascara(this,event,'cnpj')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localSerieNF?>' name='filtro_serie_nf' maxlength='2' style='width:50px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_tipo_nf' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:90px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=65 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localTipoMovimentacao,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localDataNF?>' name='filtro_data_nf' style='width:80px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:140px'  onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<option value='DescricaoNatureza' <?=compara($localCampo,"DescricaoNatureza","selected='selected'","")?>>Descrição CFOP</option>
							<option value='DescricaoEstoque' <?=compara($localCampo,"DescricaoEstoque","selected='selected'","")?>>Estoque</option>
							<option value='RazaoSocial' <?=compara($localCampo,"RazaoSocial","selected='selected'","")?>>Nome Fornecedor</option>
							<!--option value='DescricaoReduzidaProduto' <?=compara($localCampo,"DescricaoReduzidaProduto","selected='selected'","")?>>Nome Produto</option-->
							<option value='ValorNF' <?=compara($localCampo,"ValorNF","selected='selected'","")?>>Valor NF</option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:90px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' onKeyPress="mascara(this,event,'int')" style='width:34px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(33);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
