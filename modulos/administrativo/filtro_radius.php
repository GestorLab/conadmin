<?
	if($localOrdem == ''){					$localOrdem = "Descricao";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_radius.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Servidor Radius</td>
					<td>Grupo</td>
					<td>Tipo</td>
					<td>Atributo</td>
					<td>Valor</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_servidor' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:180px' onKeyDown='listar(event)' onChange="addGrupo(this.value); inserirAtributo(this.value); addAtribute()">
							<option value=''>Todos</option>
							<?
								$sql = "select IdCodigoInterno, DescricaoCodigoInterno from CodigoInterno where IdLoja=$local_IdLoja and IdGrupoCodigoInterno=10000 and IdCodigoInterno < 20 order by DescricaoCodigoInterno";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoCodigoInterno] = url_string_xsl($lin[DescricaoCodigoInterno],'convert');
									
									echo "<option value='$lin[IdCodigoInterno]' ".compara($localIdServidor,$lin[IdCodigoInterno],"selected='selected'","").">$lin[DescricaoCodigoInterno]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_grupo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:180px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
						</select>
					</td>	
					<td>
						<select name='filtro_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:70px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<option value='C' <?=compara($localTipo,'C',"selected='selected'","")?>>Check</option>
							<option value='R' <?=compara($localTipo,'R',"selected='selected'","")?>>Reply</option>
						</select>
					</td>
					<td>
						<select name='filtro_atributo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:150px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja=$local_IdLoja and IdGrupoCodigoInterno=10001 order by ValorCodigoInterno";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[ValorCodigoInterno] = url_string_xsl($lin[ValorCodigoInterno],'convert');
									
									echo "<option value='$lin[ValorCodigoInterno]' ".compara($localAtributo,$lin[ValorCodigoInterno],"selected='selected'","").">$lin[ValorCodigoInterno]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:120px' maxlength='253' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(10000);
		?>
	</div>
	<script>
		function inicia(){
			if(document.filtro.filtro_servidor.value != ""){
				addGrupo(document.filtro.filtro_servidor.value,'<?=$localIdGrupo?>');
			}
		}
		inicia();
		enterAsTab(document.forms.filtro);
	</script>
	
