<?
	if($localOrdem == ''){							$localOrdem = "DataEnvio";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_reenvio_email.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>	
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td>Tipo E-mail</td>
					<td>Campo</td>
					<td>Valor Campo</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:170px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td>
						<select name='filtro_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' style='width:130px'>
							<option value=''>Todos</option>
							<?
								$sql = "select distinct IdTipoEmail, DescricaoTipoEmail from TipoEmail order by DescricaoTipoEmail DESC";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoTipoEmail] = url_string_xsl($lin[DescricaoTipoEmail],'convert');
									
									echo "<option value='$lin[IdTipoEmail]' ".compara($localIdTipoEmail,$lin[IdTipoEmail],"selected='selected'","").">$lin[DescricaoTipoEmail]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px'  onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<option value='AssuntoEmail' <?=compara($localCampo,"AssuntoEmail","selected='selected'","")?>>Assunto E-mail</option>
							<option value='IdContrato' <?=compara($localCampo,"IdContrato","selected='selected'","")?>>Contrato</option>
							<option value='IdContaReceber' <?=compara($localCampo,"IdContaReceber","selected='selected'","")?>>Contas Receber</option>
							<option value='IdContaEventual' <?=compara($localCampo,"IdContaEventual","selected='selected'","")?>>Conta Eventual</option>
							<option value='DataEnvio' <?=compara($localCampo,"DataEnvio","selected='selected'","")?>>Data Envio</option>
							<option value='Email' <?=compara($localCampo,"Email","selected='selected'","")?>>E-mail</option>
							<option value='IdEmail' <?=compara($localCampo,"IdEmail","selected='selected'","")?>>Id E-mail</option>
							<option value='IdLancamentoFinanceiro' <?=compara($localCampo,"IdLancamentoFinanceiro","selected='selected'","")?>>Lanç. Financeiro</option>
							<option value='MesEnvio' <?=compara($localCampo,"MesEnvio","selected='selected'","")?>>Mês Envio</option>
							<option value='IdOrdemServico' <?=compara($localCampo,"IdOrdemServico","selected='selected'","")?>>OS</option>
							<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>>Proc. Financeiro</option>
							<option value='IdPessoa' <?=compara($localCampo,"IdPessoa","selected='selected'","")?>>Pessoa</option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:130px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' style='width:150px'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=37";
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
			echo menu_acesso_rapido(26);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
