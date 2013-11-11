<?
	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localLimit == '' && $localFiltro == ''){	$localLimit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_pessoa.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td>Tipo Pessoa</td>
					<td>Grupo</td>
					<td>Campo</td>
					<td>Valor</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:230px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_tipo_pessoa' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdParametroSistema]' ".compara($localTipoPessoa,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
							</select>
					</td>
					<td>
						<select name='filtro_grupo_pessoa' style='width:125px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<?
								$sql = "select IdGrupoPessoa, DescricaoGrupoPessoa from GrupoPessoa order by DescricaoGrupoPessoa ASC";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdGrupoPessoa]' ".compara($localGrupoPessoa,$lin[IdGrupoPessoa],"selected='selected'","").">$lin[DescricaoGrupoPessoa]</option>";
								}
							?>
							</select>
					</td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:150px'  onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<option value='CPF_CNPJ' <?=compara($localCampo,"CPF_CNPJ","selected='selected'","")?>>CPF/CNPJ</option>
							<option value='Endereco' <?=compara($localCampo,"Endereco","selected='selected'","")?>>Endereço</option>
							<option value='CEP' <?=compara($localCampo,"CEP","selected='selected'","")?>>CEP</option>
							<option value='Email' <?=compara($localCampo,"Email","selected='selected'","")?>>E-mail</option>
							<option value='Estado' <?=compara($localCampo,"Estado","selected='selected'","")?>>Estado</option>
							<option value='Cidade' <?=compara($localCampo,"Cidade","selected='selected'","")?>>Cidade</option>
							<option value='Fone' <?=compara($localCampo,"Fone","selected='selected'","")?>>Fone</option>
							<option value='DiaCobranca' <?=compara($localCampo,"DiaCobranca","selected='selected'","")?>>Vencimento</option>
							<option value='Obs' <?=compara($localCampo,"Obs","selected='selected'","")?>>Observações</option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:130px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' 	name='filtro_limit' value='<?=$localLimit?>' size='2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(20);
		?>
	</div>
