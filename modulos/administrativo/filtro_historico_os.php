<?
	if($localOrdem == ''){					$localOrdem = "DescricaoServico";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){	$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_historico_os.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Nome Serviço</td>
					<td>Grupo Serviço</td>
					<td>Periodicidade</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:240px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_servico_grupo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:200px' onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<?
								$sql = "select IdServicoGrupo, DescricaoServicoGrupo from ServicoGrupo order by DescricaoServicoGrupo";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoServicoGrupo] = url_string_xsl($lin[DescricaoServicoGrupo],'convert');
									echo "<option value='$lin[IdServicoGrupo]' ".compara($localServicoGrupo,$lin[IdServicoGrupo],"selected='selected'","").">$lin[DescricaoServicoGrupo]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_periodicidade' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:160px' onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<?
								$sql = "select IdCodigoInterno, DescricaoCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno=8 order by DescricaoCodigoInterno";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoCodigoInterno] = url_string_xsl($lin[DescricaoCodigoInterno],'convert');
									echo "<option value='$lin[IdCodigoInterno]' ".compara($localPeriodicidade,$lin[IdCodigoInterno],"selected='selected'","").">$lin[DescricaoCodigoInterno]</option>";
								}
							?>
						</select>
					</td>								
					<td>
						<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px' onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=17 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>	
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
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
