<?
	if($localOrdem == ''){							$localOrdem = "DataLancamento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_conta_receber_excluir.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Conta Receber</td>
					<td>Nome Pessoa/Razão Social</td>
					<td>Local Cob.</td>
					<td>Campo</td>
					<td>Valor Campo</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localContaReceber?>' name='filtro_id_conta_receber' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyPress="mascara(this,event,'int')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td>
						<select name='filtro_local_cobranca' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
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
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:110px'  onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<option value='DataLancamento' <?=compara($localCampo,"DataLancamento","selected='selected'","")?>>Data Lançam.</option>
							<option value='DataVencimento' <?=compara($localCampo,"DataVencimento","selected='selected'","")?>>Data Vencim.</option>
							<option value='DescricaoServico' <?=compara($localCampo,"DescricaoServico","selected='selected'","")?>>Nome Serviço</option>
							<option value='MesLancamento' <?=compara($localCampo,"MesLancamento","selected='selected'","")?>>Mês Lançam.</option>
							<option value='MesVencimento' <?=compara($localCampo,"MesVencimento","selected='selected'","")?>>Mês Vencim.</option>
							<option value='NumeroDocumento' <?=compara($localCampo,"NumeroDocumento","selected='selected'","")?>>Nº Documento</option>
							<option value='NumeroNF' <?=compara($localCampo,"NumeroNF","selected='selected'","")?>>Nº Nota Fiscal</option>
							<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>>Proc. Financ.</option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:100px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar(event);" /></td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(19);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
