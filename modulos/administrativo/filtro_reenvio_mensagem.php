<?
	if($localOrdem == ''){							$localOrdem = "DataEnvio";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'number';	}

	$IdLoja		=	$_SESSION["IdLoja"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post'  action='listar_reenvio_mensagem.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />				
			<table>	
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(718)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:170px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);"/></td>
					<td>
						<select name='filtro_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' style='width:130px'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select 
											IdTipoMensagem,
											Titulo
										from
											TipoMensagem 
										where 
											IdLoja = $IdLoja and 
											Titulo is not null 
										order by 
											Titulo ASC";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									eval("\$lin[Titulo] = \"".$lin[Titulo]."\";");
									
									$lin[Titulo] = url_string_xsl($lin[Titulo],'convert');
									$lin[Titulo] = preg_replace("/\([ ]*\)/",'',$lin[Titulo]);
									
									if($lin[IdTipoMensagem] == 3){ // modificar depois, temporario
										$lin[Titulo] = 'Log Processo Financeiro';	
									}						
									
									echo "<option value='$lin[IdTipoMensagem]' ".compara($localIdTipoMensagem,$lin[IdTipoMensagem],"selected='selected'","").">$lin[Titulo]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px'  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<option value='Assunto' <?=compara($localCampo,"Assunto","selected='selected'","")?>><?=dicionario(719)?></option>
							<option value='IdContrato' <?=compara($localCampo,"IdContrato","selected='selected'","")?>><?=dicionario(27)?></option>
							<option value='IdContaReceber' <?=compara($localCampo,"IdContaReceber","selected='selected'","")?>><?=dicionario(670)?></option>
							<option value='IdContaEventual' <?=compara($localCampo,"IdContaEventual","selected='selected'","")?>><?=dicionario(28)?></option>
							<option value='DataEnvio' <?=compara($localCampo,"DataEnvio","selected='selected'","")?>><?=dicionario(720)?></option>
							<option value='DataCriacao' <?=compara($localCampo,"DataCriacao","selected='selected'","")?>><?=dicionario(133)?></option>
							<option value='Email' <?=compara($localCampo,"Email","selected='selected'","")?>><?=dicionario(104)?></option>
							<option value='IdHistoricoMensagem' <?=compara($localCampo,"IdHistoricoMensagem","selected='selected'","")?>>Id Mensagem</option>
							<option value='IdLancamentoFinanceiro' <?=compara($localCampo,"IdLancamentoFinanceiro","selected='selected'","")?>>Lanç. Financeiro</option>
							<option value='MesEnvio' <?=compara($localCampo,"MesEnvio","selected='selected'","")?>>Mês Envio</option>
							<option value='IdOrdemServico' <?=compara($localCampo,"IdOrdemServico","selected='selected'","")?>>OS</option>
							<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>>Proc. Financeiro</option>
							<option value='IdPessoa' <?=compara($localCampo,"IdPessoa","selected='selected'","")?>>Pessoa</option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:130px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)"  onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" style='width:150px'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=193";
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
			echo menu_acesso_rapido(48);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
